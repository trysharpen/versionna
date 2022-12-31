<?php

$autoloader = require __DIR__ . '/../src/composer_autoloader.php';

if (!$autoloader()) {
    die('You need to set up the project dependencies using the following commands:' . PHP_EOL .
        'curl -sS https://getcomposer.org/installer | php' . PHP_EOL .
        'php composer.phar install' . PHP_EOL);
}

use Symfony\Component\Console\Application;

$application = new Application('versionna is a CLI tool to keep sync database data and rt index schemas with ManticoreSearch');

$application->add(new \Sharpen\Versionna\Command\MigrateCommand())->setAliases(['migrate:up']);
$application->add(new \Sharpen\Versionna\Command\RollbackCommand())->setAliases(['migrate:down']);
$application->add(new \Sharpen\Versionna\Command\FreshCommand());
$application->add(new \Sharpen\Versionna\Command\ListPendingCommand());
$application->add(new \Sharpen\Versionna\Command\ListMigratedCommand());
$application->add(new \Sharpen\Versionna\Command\MakeMigrationCommand());
