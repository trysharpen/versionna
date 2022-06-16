<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();

// register commands
$application->add(new \SiroDiaz\ManticoreMigration\Command\MigrateCommand());
$application->add(new \SiroDiaz\ManticoreMigration\Command\RollbackCommand());
$application->add(new \SiroDiaz\ManticoreMigration\Command\ListPendingCommand());
$application->add(new \SiroDiaz\ManticoreMigration\Command\ListMigratedCommand());
$application->add(new \SiroDiaz\ManticoreMigration\Command\MakeMigrationCommand());
