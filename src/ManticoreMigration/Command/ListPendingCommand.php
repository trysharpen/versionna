<?php

namespace SiroDiaz\ManticoreMigration\Command;

use SiroDiaz\ManticoreMigration\Manticore\ManticoreConnection;
use SiroDiaz\ManticoreMigration\MigrationDirector;
use SiroDiaz\ManticoreMigration\Storage\DatabaseConfiguration;
use SiroDiaz\ManticoreMigration\Storage\DatabaseConnection;
use SiroDiaz\ManticoreMigration\Storage\MigrationTable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListPendingCommand extends AbstractCommand
{
	protected static $defaultName = 'migration:list:pending';

	/**
	 * {@inheritDoc}
	 *
	 * @return void
	 */
	protected function configure(): void
	{
		parent::configure();

		$this->setDescription('Lists Manticoresearch pending migrations')
			->setHelp(sprintf(
				'%sLists Manticoresearch pending migrations%s',
				PHP_EOL,
				PHP_EOL
			));
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
			)
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

		$pendingMigrations = $director->getPendingMigrations();

		if (count($pendingMigrations) > 0) {
			$io = new SymfonyStyle($input, $output);
			$io->writeln('');

			$io->table(
				['name',],
				array_map(
					function ($migration) {
						return ['name' => $migration];
					},
					array_values(array_keys($pendingMigrations)),
				),
			);
		} else {
			$output->writeln('<info>ManticoreSearch is up to date! no pending migrations</info>');
		}

		return Command::SUCCESS;
	}
}
