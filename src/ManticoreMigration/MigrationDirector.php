<?php

namespace SiroDiaz\ManticoreMigration;

use DateTime;
use Exception;
use PDOException;
use SiroDiaz\ManticoreMigration\Indexer\ManticoreIndexer;
use SiroDiaz\ManticoreMigration\Manticore\ManticoreConnection;
use SiroDiaz\ManticoreMigration\Runner\Loader;
use SiroDiaz\ManticoreMigration\Runner\ManticoreRunner;
use SiroDiaz\ManticoreMigration\Runner\MigrationMetadata;
use SiroDiaz\ManticoreMigration\Storage\DatabaseConnection;
use SiroDiaz\ManticoreMigration\Storage\MigrationEntity;
use SiroDiaz\ManticoreMigration\Storage\MigrationTable;

class MigrationDirector
{
	/**
     * The name of the database connection to use.
     *
     * @var DatabaseConnection
     */
    protected $dbConnection;

	/**
	 * @var ManticoreConnection
	 */
	protected $manticoreConnection;

	/**
	 * @var MigrationTable
	 */
	protected $migrationTable;

	/**
	 * @var string
	 */
	protected $migrationsPath;

    /**
     * Enables, if supported, wrapping the migration within a transaction.
     *
     * @var bool
     */
    public $withinTransaction = true;

	/**
	 * @param DatabaseConnection $dbConnection
	 * @return MigrationDirector
	 */
	public function dbConnection(DatabaseConnection $dbConnection): MigrationDirector
	{
		$this->dbConnection = $dbConnection;

		return $this;
	}

	/**
	 * @param ManticoreConnection $manticoreConnection
	 * @return MigrationDirector
	 */
	public function manticoreConnection(ManticoreConnection $manticoreConnection): MigrationDirector
	{
		$this->manticoreConnection = $manticoreConnection;

		return $this;
	}

	public function migrationsPath(string $migrationsPath): MigrationDirector
	{
		$this->migrationsPath = $migrationsPath;

		return $this;
	}

	public function migrationTable(MigrationTable $migrationTable): MigrationDirector
	{
		$this->migrationTable = $migrationTable;

		return $this;
	}

	/**
	 *
	 * @param bool $withinTransaction
	 * @return MigrationDirector
	 */
	public function withinTransaction(bool $withinTransaction): MigrationDirector
	{
		$this->withinTransaction = $withinTransaction;

		return $this;
	}

	/**
	 * @param array<int, string> $pendingMigrationFilenames
	 * @return bool
	 */
	protected function hasDuplicatedMigrations(array $pendingMigrationFilenames): bool
	{
		if (count($pendingMigrationFilenames) === 0) {
			return false;
		}

		$duplicates = array_unique($pendingMigrationFilenames);

		return count($duplicates) !== count($pendingMigrationFilenames);
	}

	/**
	 * @return MigrationMetadata[]
	 * @throws Exception
	 */
	public function getPendingMigrations(): array
	{
		$migrations = Loader::load($this->migrationsPath);

		$latestMigrationsName = array_map(function ($migration) {
			return $migration->getName();
		}, $this->getMigrationTable()->getAll());

		$pendingMigrationFilenames = array_diff(
			array_keys($migrations),
			array_values($latestMigrationsName),
		);

		// detect if there are duplicated migrations
		if ($this->hasDuplicatedMigrations($pendingMigrationFilenames)) {
			throw new Exception('Duplicated migrations detected');
		}

		return array_filter(
			$migrations,
			function ($migration, $file) use ($pendingMigrationFilenames) {
				return in_array($file, $pendingMigrationFilenames);
			},
			ARRAY_FILTER_USE_BOTH
		);
	}

	/**
	 *
	 * @return bool
	 * @throws PDOException
	 * @throws Exception
	 */
	public function hasPendingMigrations(): bool
	{
		return count($this->getPendingMigrations()) > 0;
	}

	/**
	 *
	 * @param string $file
	 * @return void
	 * @throws Exception
	 */
	protected function requireClassFile(string $file): void
	{
		if (!file_exists($file)) {
			throw new Exception("File $file does not exist");
		}

		require_once $file;
	}

    /**
     * Get the database connection instance.
     *
     * @return DatabaseConnection
     */
    public function getDbConnection(): DatabaseConnection
    {
        return $this->dbConnection;
    }

	/**
	 * Returns the Manticore connection instance.
	 *
	 * @return ManticoreConnection
	 */
	public function getManticoreConnection(): ManticoreConnection
	{
		return $this->manticoreConnection;
	}

	/**
	 * Returns the migration table instance.
	 *
	 * @return MigrationTable
	 */
	public function getMigrationTable(): MigrationTable
	{
		return $this->migrationTable;
	}

	public function fresh(): void
	{
		if ($this->migrationTable->exists()) {
			$this->migrationTable->truncate(true);
		}

		$this->migrate();
	}

	/**
	 *
	 * @return void
	 * @throws Exception
	 */
	public function migrate(): void
	{
		try {
			if (!$this->migrationTable->exists()) {
				$this->migrationTable->create();
			}

			$this->runPendingMigrations($this->getPendingMigrations());
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 *
	 * @var MigrationMetadata[]
	 * @return void
	 */
	protected function runPendingMigrations(array $pendingMigrations): void
	{
		$nextVersion = $this->migrationTable->getNextVersion();

		try {
			foreach ($pendingMigrations as $migration) {
				$this->setUp();
				$this->requireClassFile($migration->getMigrationFullFilePath());

				$migrationInstance = $migration->getClassInstance(
					new ManticoreRunner($this->manticoreConnection->getClient()),
					new ManticoreIndexer($this->getDbConnection(), $this->getManticoreConnection()),
				);

				$entity = new MigrationEntity($migration->getFilename(), $nextVersion, $migrationInstance->description, new DateTime());

				$this->runUpMigration($migrationInstance, $entity);
				$this->dbConnection->getConnection()->commit();

				echo "Migration {$entity->getName()} (version {$entity->getVersion()}) executed successfully" . PHP_EOL;
			}
		} catch (Exception $e) {
			$this->rollback();
			throw $e;
		}
	}

	protected function runUpMigration(Migration $migration, MigrationEntity $entity): void
	{
		$this->migrationTable->insert($entity);
		$migration->up();
	}

	/** @todo */
	public function undoMigrations(int $steps = 1): void
	{
		$latestVersion = $this->getMigrationTable()->getLatestVersion();

		$migrationsToUndo = $this->migrationTable->getMigrationsToUndo();

		$migrationsEntities = array_reverse(array_map(function ($migration) {
			return new MigrationEntity(
				$migration->migration_name,
				$migration->version,
				$migration->description,
				new DateTime($migration->created_at),
			);
		}, (array) $migrationsToUndo));

		$migrationsMetadata = array_map(function ($migration) {
			return new MigrationMetadata(
				$this->migrationsPath . DIRECTORY_SEPARATOR . $migration->migration_name . '.php',
				$migration->migration_name . '.php',
				Loader::getMigrationClassName($migration->migration_name),
			);
		}, (array) $migrationsToUndo);

		$classFiles = array_map(
			function ($migration) {
				return $migration->buildOriginalFilePath();
			}, $migrationsMetadata
		);

		$migrations = array_combine($classFiles, $migrationsMetadata);

		$loadedMigrations = array_map(function ($migration) {
			include_once $migration->getMigrationFullFilePath();

			$runner = new ManticoreRunner($this->manticoreConnection->getClient());
			$indexer = new ManticoreIndexer($this->getDbConnection(), $this->getManticoreConnection());

			return $migration->getClassInstance($runner, $indexer);
		}, $migrations);

		for ($i = 0; $i < count($loadedMigrations); $i++) {
			$migration = array_values($loadedMigrations)[$i];
			$migrationName = $migrationsEntities[$i]->getName();

			echo "Undoing migration {$migrationName} (version {$migrationsEntities[$i]->getVersion()})..." . PHP_EOL;
			$migration->down();

			$this->setUp();
			$this->migrationTable->undoPrevious($migrationName);
			$this->dbConnection->getConnection()->commit();
			echo "Migration {$migrationName} (version {$migrationsEntities[$i]->getVersion()}) undone successfully" . PHP_EOL;
		}
	}

	private function setUp(): void
	{
		if ($this->withinTransaction) {
			$this->dbConnection->getConnection()->beginTransaction();
		}
	}

	private function rollback(): void
	{
		if ($this->withinTransaction) {
			$this->dbConnection->getConnection()->rollback();
		}
	}
}
