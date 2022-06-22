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

class MakeMigrationCommand extends Command
{
	protected static $defaultName = 'make:migration';

	/**
     * {@inheritDoc}
     *
     * @return void
     */
    protected function configure()
    {
        // parent::configure();
		$this->addOption('--configuration', '-c', InputOption::VALUE_REQUIRED, 'The configuration file to load');
        // $this->addOption('--parser', '-p', InputOption::VALUE_REQUIRED, 'Parser used to read the config file. Defaults to PHP');
        $this->addOption('--no-info', null, InputOption::VALUE_NONE, 'Hides all debug information');

		$this->addOption('--no-info', null, InputOption::VALUE_NONE, 'Hides all debug information');
        $this->setDescription('Lists Manticoresearch migrations applied')
            ->setHelp(sprintf(
                '%sLists Manticoresearch migrations applied%s',
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

		$director->undoMigrations($input->getOption('steps'));

        return Command::SUCCESS;
    }

}
