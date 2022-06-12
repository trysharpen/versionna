<?php declare(strict_types=1);

namespace SiroDiaz\ManticoreMigration\Storage;

use PDO;
use PDOException;

class MigrationTable {
	/**
	 *
	 * @var DatabaseConnection
	 */
	protected $connection;

	/**
	 * @var string
	 */
	protected $tablePrefix;

	/**
	 * @var string
	 */
	protected $tableName;

	public function __construct(
		DatabaseConnection $connection,
		string $tablePrefix,
		string $tableName
	)
	{
		$this->connection = $connection;
		$this->tablePrefix = $tablePrefix;
		$this->tableName = $tableName;
	}

	/**
	 * @return DatabaseConnection
	 */
	public function getConnection(): DatabaseConnection
	{
		return $this->connection;
	}

	/**
	 * Returns the PDO connection instance
	 *
	 * @return PDO
	 */
	public function getPDOConnection(): PDO
	{
		return $this->connection->getConnection();
	}

	/**
	 * Returns the table prefix
	 *
	 * @return string
	 */
	public function getTablePrefix(): string
	{
		return $this->tablePrefix;
	}

	/**
	 * Returns the table name
	 *
	 * @return string
	 */
	public function getTableName()
	{
		return $this->tableName;
	}

	/**
	 * Returns the full table name
	 *
	 * @return string
	 */
	public function getFullTableName(): string
	{
		return "{$this->tablePrefix}{$this->tableName}";
	}

	public function exists()
	{
		$query = "SELECT name FROM sqlite_master WHERE name='{$this->getFullTableName()}' AND type='table'";

		$statement = $this->getPDOConnection()->prepare($query);
		$statement->execute();

		return $statement->fetchColumn() !== false;
	}

	public function create()
	{
		$databaseDriver = $this->connection->getConfiguration()->getDriver();

		// $migrationTableCreator = new MigrationTableCreator($databaseDriver);

		// var_dump($migrationTableCreator->getTableSchema($this->getFullTableName()));
		// $migrationTableCreator->create($this->getFullTableName());

		$this->getPDOConnection()->exec(<<<SQL
			CREATE TABLE IF NOT EXISTS {$this->getFullTableName()} (
				id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
				version INTEGER NOT NULL,
				migration_name TEXT NOT NULL,
				description TEXT,
				created_at DATETIME NOT NULL
			)
		SQL
		);
	}

	/**
	 * Returns an array with all the migrations that have been executed
	 * sorted by descendent version order.
	 *
	 * @return array
	 * @throws PDOException
	 */
	public function getSortedMigrations(): array
	{
		$query = "SELECT * FROM {$this->getFullTableName()} ORDER BY version DESC, id DESC";

		$statement = $this->getPDOConnection()->prepare($query);
		$statement->execute();

		$result = $statement->fetchAll(PDO::FETCH_CLASS, MigrationEntity::class);
		return empty($result) ? [] : $result;
	}

	public function getLatestVersion()
	{
		$query = "SELECT MAX(version) FROM {$this->getFullTableName()}";

		$statement = $this->getPDOConnection()->prepare($query);
		$statement->execute();

		return $statement->fetchColumn();
	}

	/**
	 * Returns the next version ID to be used for the next migration execution
	 *
	 * @throws PDOException
	 * @return bool
	 */
	public function getNextVersion()
	{
		return $this->getLatestVersion() + 1;
	}

	/**
	 *
	 * @param MigrationEntity $migrationEntity
	 * @return bool
	 * @throws PDOException
	 */
	public function insert(MigrationEntity $migrationEntity)
	{
		$query = "INSERT INTO {$this->getFullTableName()} (version, migration_name, description, created_at) VALUES (:version, :migration_name, :description, :created_at)";

		if ($migrationEntity->getCreatedAt() === null) {
			$migrationEntity->generateCreatedAt();
		}
		$statement = $this->getPDOConnection()->prepare($query);
		$statement->bindValue(':version', $migrationEntity->getVersion());
		$statement->bindValue(':migration_name', $migrationEntity->getName());
		$statement->bindValue(':description', $migrationEntity->getDescription());
		$statement->bindValue(':created_at', $migrationEntity->getCreatedAt()->format('Y-m-d h:i:s'));

		return $statement->execute();
	}

	/**
	 *
	 * @param MigrationEntity $migrationEntity
	 * @return bool
	 * @throws PDOException
	 */
	public function getMigrationsToUndo(): array
	{
		$query = <<<SQL
		SELECT * FROM {$this->getFullTableName()}
		WHERE version = (
			SELECT MAX(version) FROM {$this->getFullTableName()}
		)
		ORDER BY version DESC
		SQL;
		$statement = $this->getPDOConnection()->prepare($query);

		if (!$statement || !$statement->execute()) {
			return [];
		}

		$latestMigrations = $statement->fetchAll(PDO::FETCH_CLASS);

		return empty($latestMigrations) ? [] : $latestMigrations;
	}

	/**
	 *
	 * @return void
	 * @throws PDOException
	 */
	public function undoPrevious(string $migrationName)
	{
		$query = "DELETE FROM {$this->getFullTableName()} WHERE version = (SELECT MAX(version) FROM {$this->getFullTableName()}) AND migration_name = :migration_name";

		$statement = $this->getPDOConnection()->prepare($query);
		$statement->bindValue(':migration_name', $migrationName);
		$statement->execute();
	}

	public function drop()
	{
		$this->getPDOConnection()->exec("DROP TABLE IF EXISTS {$this->getFullTableName()}");
	}

	public function truncate(bool $force = false)
	{
		if ($force) {
			$this->drop();
			$this->create();
		} else {
			$this->getPDOConnection()->exec("DELETE FROM {$this->getFullTableName()} WHERE 1=1");
		}
	}

	public function rollback(int $version)
	{
		$query = "DELETE FROM {$this->getFullTableName()} WHERE version = :version";

		$statement = $this->getPDOConnection()->prepare($query);
		$statement->bindValue(':version', $version);

		return $statement->execute();
	}

	public function getLatestMigrations()
	{
		$query = "SELECT * FROM {$this->getFullTableName()} WHERE version = (SELECT MAX(version) AS lastest_version FROM {$this->getFullTableName()}) ORDER BY version DESC, migration_name DESC";

		$statement = $this->getPDOConnection()->prepare($query);
		$statement->execute();

		$results = $statement->fetchAll(PDO::FETCH_ASSOC);

		return empty($results) ? null : array_map(function ($migration) {
			return MigrationEntity::fromArray($migration);
		}, $results);
	}
}
