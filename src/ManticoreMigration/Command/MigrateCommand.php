<?php

namespace SiroDiaz\ManticoreMigration\Command;

use Exception;
use SiroDiaz\ManticoreMigration\Manticore\ManticoreConnection;
use SiroDiaz\ManticoreMigration\MigrationDirector;
use SiroDiaz\ManticoreMigration\Storage\DatabaseConfiguration;
use SiroDiaz\ManticoreMigration\Storage\DatabaseConnection;
use SiroDiaz\ManticoreMigration\Storage\MigrationTable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends AbstractCommand
{
    protected static $defaultName = 'migrate';

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->setDescription('Syncronizes ManticoreSearch with the migration files')
            ->setHelp(sprintf(
                '%sRun pending Manticoresearch migrations%s',
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
            $this->configuration['migration_table'],
        );

        $director = new MigrationDirector();

        $director
            ->dbConnection($dbConnection)
            ->manticoreConnection($manticoreConnection)
            ->migrationsPath($this->configuration['migrations_path'])
            ->migrationTable($migrationTable);

        if (! $migrationTable->exists()) {
            $output->writeln('<info>Migration table doesn\'t exist</info>');
        } elseif (! $director->hasPendingMigrations()) {
            $output->writeln('<info>No pending migrations</info>');

            return Command::SUCCESS;
        }

        try {
            $director->migrate();
        } catch (Exception $exception) {
            $output->writeln($exception->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
