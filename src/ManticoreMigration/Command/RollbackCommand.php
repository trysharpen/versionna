<?php

namespace SiroDiaz\ManticoreMigration\Command;

use SiroDiaz\ManticoreMigration\Manticore\ManticoreConnection;
use SiroDiaz\ManticoreMigration\MigrationDirector;
use SiroDiaz\ManticoreMigration\Storage\DatabaseConfiguration;
use SiroDiaz\ManticoreMigration\Storage\DatabaseConnection;
use SiroDiaz\ManticoreMigration\Storage\MigrationTable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RollbackCommand extends AbstractCommand
{
	protected static $defaultName = 'rollback';

	/**
	 * {@inheritDoc}
	 *
	 * @return void
	 */
	protected function configure(): void
	{
		parent::configure();

		$this->setDescription('Rollback a migration to a previous version')
			->setHelp(sprintf(
				'%sCreates a new Manticoresearch migration%s',
				PHP_EOL,
				PHP_EOL
			));

		$this->addOption('--steps', null, InputOption::VALUE_OPTIONAL, 'Specify the number of versions to rollback', 1);
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$commandExitCode = parent::execute($input, $output);

		if ($commandExitCode !== Command::SUCCESS) {
			return $commandExitCode;
		}

		$dbConnection = new DatabaseConnection(
			DatabaseConfiguration::fromArray(
				$this->configuration['connections'][$this->connection]
			),
		);

		$manticoreConnection = new ManticoreConnection(
			$this->configuration['manticore_connection']['host'],
			$this->configuration['manticore_connection']['port'],
		);

		$migrationTable = new MigrationTable(
			$dbConnection,
			$this->configuration['table_prefix'],
			$this->configuration['migration_table']
		);

		$director = new MigrationDirector();
		$director
			->dbConnection($dbConnection)
			->manticoreConnection($manticoreConnection)
			->migrationsPath($this->configuration['migrations_path'])
			->migrationTable($migrationTable);

		$director->undoMigrations($input->getOption('steps'));

		return Command::SUCCESS;
	}
}
