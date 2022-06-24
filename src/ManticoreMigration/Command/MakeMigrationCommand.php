<?php

namespace SiroDiaz\ManticoreMigration\Command;

use SiroDiaz\ManticoreMigration\Manticore\ManticoreConnection;
use SiroDiaz\ManticoreMigration\MigrationCreator;
use SiroDiaz\ManticoreMigration\MigrationDirector;
use SiroDiaz\ManticoreMigration\Storage\DatabaseConfiguration;
use SiroDiaz\ManticoreMigration\Storage\DatabaseConnection;
use SiroDiaz\ManticoreMigration\Storage\MigrationTable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeMigrationCommand extends AbstractCommand
{
	protected static $defaultName = 'make:migration';

	/**
     * {@inheritDoc}
     *
     * @return void
     */
    protected function configure()
    {
        parent::configure();

		$this->setDescription('Generate a new migration file')
            ->setHelp(sprintf(
                '%sGenerate a new migration file%s',
                PHP_EOL,
                PHP_EOL
            ));

		$this->addOption('description', null, InputOption::VALUE_OPTIONAL, 'The migration description or use case');
		$this->addArgument('name', InputArgument::REQUIRED, 'The migration name in snake_case_style');
    }

	protected function execute(InputInterface $input, OutputInterface $output): int
    {
		if ($input->getOption('configuration') === null) {
			$output->writeln('<error>You must specify a configuration file</error>');

			return Command::INVALID;
		}

        $configuration = require $input->getOption('configuration');
		$creator = new MigrationCreator(
			$configuration['migrations_path'],
			$input->getArgument('name'),
			$input->getOption('description') ?? '',
		);

		$creator->create();

		$output->writeln('<info>Migration created successfully</info>');

        return Command::SUCCESS;
    }

}
