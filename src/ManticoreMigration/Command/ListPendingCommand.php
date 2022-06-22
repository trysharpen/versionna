<?php

namespace SiroDiaz\ManticoreMigration\Command;

use SiroDiaz\ManticoreMigration\Manticore\ManticoreConnection;
use SiroDiaz\ManticoreMigration\MigrationDirector;
use SiroDiaz\ManticoreMigration\Storage\DatabaseConfiguration;
use SiroDiaz\ManticoreMigration\Storage\DatabaseConnection;
use SiroDiaz\ManticoreMigration\Storage\MigrationTable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListPendingCommand extends AbstractCommand
{
	protected static $defaultName = 'migration:list:pending';

	/**
     * {@inheritDoc}
     *
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->setDescription('Rollback a migration to a previous version')
            ->setHelp(sprintf(
                '%sLists Manticoresearch pending migrations%s',
                PHP_EOL,
                PHP_EOL
            ));
    }

	protected function execute(InputInterface $input, OutputInterface $output): int
    {
		if ($input->getOption('configuration') === null) {
			$output->writeln('<error>You must specify a configuration file</error>');

			return Command::INVALID;
		}

        $configuration = require $input->getOption('configuration');

		$dbConnection = new DatabaseConnection(
			DatabaseConfiguration::fromArray($configuration['connection'])
		);

		$manticoreConnection = new ManticoreConnection(
			$configuration['manticore_connection']['host'],
			$configuration['manticore_connection']['port'],
		);

		$migrationTable = new MigrationTable($dbConnection, $configuration['table_prefix'], $configuration['migration_table']);

		$director = new MigrationDirector();
		$director
			->dbConnection($dbConnection)
			->manticoreConnection($manticoreConnection)
			->migrationsPath($configuration['migrations_path'])
			->migrationTable($migrationTable);

		$pendingMigrations = $director->getPendingMigrations();

		if (count($pendingMigrations) > 0) {
			var_dump($pendingMigrations);
		} else {
			$output->writeln('<info>ManticoreSearch is up to date! no pending migrations</info>');
		}

        return Command::SUCCESS;
    }

}
