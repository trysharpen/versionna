<?php

namespace Sharpen\Versionna\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\MissingInputException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AbstractCommand extends Command
{
    protected static $defaultName = 'manticore';

    /**
     * @var array<string,mixed>
     */
    protected array $configuration;

    /**
     * @var string
     */
    protected string $connection;

    protected function configure(): void
    {
        $this->addOption('--connection', null, InputOption::VALUE_OPTIONAL, 'Specify the database connection to use');
        $this->addOption('--configuration', '-c', InputOption::VALUE_REQUIRED, 'The configuration file to load');
        /** @todo */
        // $this->addOption('--parser', '-p', InputOption::VALUE_REQUIRED, 'Parser used to read the config file. Defaults to PHP');
        $this->addOption('--no-info', null, InputOption::VALUE_NONE, 'Hides all debug information');

        $this->addOption('--no-info', null, InputOption::VALUE_NONE, 'Hides all debug information');
    }

    /**
     * Validates if the configuration file is passed as cli argument
     *
     * @param array<string,mixed>|null $configuration
     * @return void
     * @throws MissingInputException
     */
    protected function validateConfigurationOption(array|null $configuration): void
    {
        if ($configuration === null) {
            throw new MissingInputException('<error>You must specify a configuration file</error>');
        }
    }

    /**
     * Loads the file PHP file that contains the configuration
     *
     * @param string $configuration
     * @return void
     */
    protected function requireConfigFile(string $configuration): void
    {
        $this->configuration = require $configuration;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->validateConfigurationOption($input->getOption('configuration'));
        } catch (MissingInputException $exception) {
            $output->writeln($exception->getMessage());

            return Command::INVALID;
        }

        $this->requireConfigFile($input->getOption('configuration'));

        $this->connection = $input->getOption('connection') ?? $this->configuration['connection'];

        return Command::SUCCESS;
    }
}
