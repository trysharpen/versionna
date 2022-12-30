<?php

namespace SiroDiaz\ManticoreMigration\Command;

use SiroDiaz\ManticoreMigration\Storage\DatabaseConfiguration;
use SiroDiaz\ManticoreMigration\Storage\DatabaseConnection;
use SiroDiaz\ManticoreMigration\Storage\MigrationTable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ListMigratedCommand extends AbstractCommand
{
	protected static $defaultName = 'migration:list:migrated';

	/**
	 * {@inheritDoc}
	 *
	 * @return void
	 */
	protected function configure(): void
	{
		parent::configure();

		$this->setDescription('Lists Manticoresearch migrations applied')
			->setHelp(sprintf(
				'%sLists Manticoresearch migrations applied%s',
				PHP_EOL,
				PHP_EOL
			));

		$this->addOption('--ascending', '-asc', InputOption::VALUE_NONE, 'Sort in ascending order (default is descending)');
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

		$migrationTable = new MigrationTable(
			$dbConnection,
			$this->configuration['table_prefix'],
			$this->configuration['migration_table']
		);

		$migrations = $migrationTable->getAll($input->getOption('ascending'));

		$io = new SymfonyStyle($input, $output);
		$io->writeln('');

		if ($migrations) {
			$migrationsDone = array_map(
				function ($migration) {
					return $migration->toArray();
				},
				$migrations,
			);

			$io->table(
				MigrationTable::LISTABLE_COLUMNS,
				$migrationsDone,
			);
		} else {
			$io->writeln('<info>The migration table is empty</info>');
		}

		return Command::SUCCESS;
	}
}
