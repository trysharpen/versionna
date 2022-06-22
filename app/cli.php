<?php

$autoloader = require __DIR__ . '/../src/composer_autoloader.php';

if (!$autoloader()) {
    die(
        'You need to set up the project dependencies using the following commands:' . PHP_EOL .
        'curl -sS https://getcomposer.org/installer | php' . PHP_EOL .
        'php composer.phar install' . PHP_EOL
    );
}

use Symfony\Component\Console\Application;

$application = new Application();

// register commands
$application->add(new \SiroDiaz\ManticoreMigration\Command\MigrateCommand());
$application->add(new \SiroDiaz\ManticoreMigration\Command\RollbackCommand());
$application->add(new \SiroDiaz\ManticoreMigration\Command\FreshCommand());
$application->add(new \SiroDiaz\ManticoreMigration\Command\ListPendingCommand());
$application->add(new \SiroDiaz\ManticoreMigration\Command\ListMigratedCommand());
$application->add(new \SiroDiaz\ManticoreMigration\Command\MakeMigrationCommand());
