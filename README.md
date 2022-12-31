# versionna

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sharpen/versionna.svg?style=flat-square)](https://packagist.org/packages/Sharpen/versionna)
[![tests](https://github.com/Sharpen/versionna/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/Sharpen/versionna/actions/workflows/tests.yml)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/Sharpen/versionna/Check%20&%20fix%20styling?label=code%20style&style=flat-square)](https://github.com/Sharpen/versionna/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![PHPStan Code Styling](https://github.com/Sharpen/versionna/actions/workflows/phpstan.yml/badge.svg?branch=main)](https://github.com/Sharpen/versionna/actions/workflows/phpstan.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/Sharpen/versionna.svg?style=flat-square)](https://packagist.org/packages/Sharpen/versionna)

Manticoresearch migration tool. Keep updated your index schemas up to date using an executable CLI script or integrate it programmatically in your application code.

![migrate and migrate:down](./resources/migrate-migrate-down.gif)

# Table of contents
- [versionna](#versionna)
- [Table of contents](#table-of-contents)
  - [project progress and roadmap](#project-progress-and-roadmap)
  - [Installation](#installation)
  - [Usage](#usage)
    - [Create migration](#create-migration)
      - [CLI](#cli)
      - [programmatically](#programmatically)
    - [Apply migrations](#apply-migrations)
      - [CLI](#cli-1)
      - [programmatically](#programmatically-1)
    - [Rollback migration](#rollback-migration)
      - [CLI](#cli-2)
      - [programmatically](#programmatically-2)
    - [List migrations applied history](#list-migrations-applied-history)
      - [CLI](#cli-3)
      - [programmatically](#programmatically-3)
    - [List pending migrations](#list-pending-migrations)
      - [CLI](#cli-4)
      - [programmatically](#programmatically-4)

## project progress and roadmap
  - [x] Add CI pipeline
    - [x] Add PHP versions supported
      - [x] 8.0
      - [x] 8.1
      - [x] 8.2
    - [x] PhpStan
    - [x] PHPUnit run tests
  - Pre-commit linter and tests checks
    - [x] Add Grumphp
      - [x] PHPStan
      - [x] PHPUnit
  - [ ] Add a logger implementation
  - [x] Add docker-compose stack files for testing and development
  - [ ] Add code documentation
  - [x] Write a complete README file explaining all
  - [ ] Add unit and integration tests
  - [x] Add command line interface feature
    - [x] Add cli application metadata such as name, description, etc.
    - [x] Created structure of the CLI application
  - [x] Executable script (bin/versionna)
  - [ ] Add commands
    - [x] list
    - [ ] make:config
    - [x] make:migration
    - [x] migration:list:pending
    - [x] migration:list:migrated
    - [x] migrate
    - [x] rollback
    - [ ] rollback with --steps
    - [x] fresh
    - [ ] refresh
    - [ ] refresh with --steps
    - [ ] reset
    - [ ] status
    - [x] help
  - [x] Add drivers to support multiple DBs engines dialects
    - [x] Add driver for SQLite
    - [x] Add driver for MySQL
    - [x] Add driver for PostgreSQL
  - [ ] Create a Laravel package
  - [ ] Create a Symfony package
## Installation

```sh
composer require sharpen/versionna
```

## Usage
First of all, you need to install the package.

```sh
composer require sharpen/versionna
```

After been installed, you need to create a directory.
That directory will contain the migration files sorted by creation date.

You have two different ways to use this package:

  - programmatically
  - CLI

You can create your own integration with `versionna` using the programmatically way as you can see in hte **examples** directory in this repository.

In each section of these documentation you will see both: programmatically and CLI version to create, migrate, rollback, list applied and pending migrations.
### Create migration

#### CLI
To create a migration file you have to use the make:migration and the class name (the migration class name that extends Migration class) using
snake_case_style.
This class name should be a descriptive name. It's better a long name for two reasons:
  - to better understand what the migration does
  - and for avoid duplicated class names
```sh
./vendor/bin/versionna make:migration -c config.php create_products_index
```

#### programmatically

```php
<?php

use Sharpen\Versionna\MigrationCreator;

$configuration = require 'config.php';

$migrationName = 'create_users_index';
$description = 'users initial definition of the rt index';
$migrationCreator = new MigrationCreator(
    $configuration['migrations_path'],
    $migrationName,
    $description,
);

$migrationCreator->create();

echo 'Migration created successfully';
```


### Apply migrations
![migrate and migrate:down](./resources/migrate-migrate-down.gif)

#### CLI

There are two available commands for apply pending migrations using the Command Line Interface

```sh
./vendor/bin/versionna migrate -c config.php
```

```sh
./vendor/bin/versionna migrate:up -c config.php
```

#### programmatically

```php
<?php

use Sharpen\Versionna\Manticore\ManticoreConnection;
use Sharpen\Versionna\MigrationDirector;
use Sharpen\Versionna\Storage\DatabaseConfiguration;
use Sharpen\Versionna\Storage\DatabaseConnection;
use Sharpen\Versionna\Storage\MigrationTable;

$configuration = require 'config.php';

$dbConnection = new DatabaseConnection(
    DatabaseConfiguration::fromArray(
        $configuration['connections']['mysql']
    )
);

$manticoreConnection = new ManticoreConnection(
    $configuration['manticore_connection']['host'],
    $configuration['manticore_connection']['port'],
);

$migrationTable = new MigrationTable(
    $dbConnection,
    $configuration['table_prefix'],
    $configuration['migration_table'],
);

$director = new MigrationDirector();

$director
    ->dbConnection($dbConnection)
    ->manticoreConnection($manticoreConnection)
    ->migrationsPath($configuration['migrations_path'])
    ->migrationTable($migrationTable);

if (! $migrationTable->exists()) {
    echo 'Migration table doesn\'t exist';
    exit(1);
} elseif (! $director->hasPendingMigrations()) {
    echo 'No pending migrations';

    exit(0);
}

try {
    $director->migrate();
} catch (Exception $exception) {
    echo $exception->getMessage();

    exit(1);
}

echo 'Applied all migrations';
```

### Rollback migration
![migrate and migrate:down](./resources/migrate-migrate-down.gif)

#### CLI

There are two available commands to rollback applied migrations using the Command Line Interface

```sh
./vendor/bin/versionna rollback -c config.php
```

```sh
./vendor/bin/versionna migrate:down -c config.php
```

#### programmatically

```php
<?php

use Sharpen\Versionna\Manticore\ManticoreConnection;
use Sharpen\Versionna\MigrationDirector;
use Sharpen\Versionna\Storage\DatabaseConfiguration;
use Sharpen\Versionna\Storage\DatabaseConnection;
use Sharpen\Versionna\Storage\MigrationTable;

$configuration = require 'config.php';

$dbConnection = new DatabaseConnection(
  DatabaseConfiguration::fromArray(
    $configuration['connections']['mysql']
  ),
);

$manticoreConnection = new ManticoreConnection(
  $configuration['manticore_connection']['host'],
  $configuration['manticore_connection']['port'],
);

$migrationTable = new MigrationTable(
  $dbConnection,
  $configuration['table_prefix'],
  $configuration['migration_table']
);

$director = new MigrationDirector();
$director
  ->dbConnection($dbConnection)
  ->manticoreConnection($manticoreConnection)
  ->migrationsPath($configuration['migrations_path'])
  ->migrationTable($migrationTable);

$steps = 1;

$director->undoMigrations($steps);
```
### List migrations applied history
![migration:list:migrated](./resources/migration-list-migrated.gif)

#### CLI

For list pending migrations using the Command Line tool

```sh
./vendor/bin/versionna migration:list:pending -c config.php
```
#### programmatically

```php
<?php

$configuration = require 'config.php';

$dbConnection = new DatabaseConnection(
    DatabaseConfiguration::fromArray(
        $configuration['connections']['mysql']
    )
);

$migrationTable = new MigrationTable(
    $dbConnection,
    $configuration['table_prefix'],
    $configuration['migration_table']
);

$ascending = false;

$migrations = $migrationTable->getAll($ascending);

if ($migrations) {
    $migrationsDone = array_map(
        function ($migration) {
            return $migration->toArray();
        },
        $migrations,
    );

    var_dump($migrationsDone);
} else {
    echo 'The migration table is empty';
}
```

### List pending migrations
![migration:list:pending](./resources/migration-list-pending.gif)

#### CLI

For list pending migrations using the Command Line tool

```sh
./vendor/bin/versionna migration:list:pending -c config.php
```
#### programmatically

```php
<?php

use Sharpen\Versionna\Manticore\ManticoreConnection;
use Sharpen\Versionna\MigrationDirector;
use Sharpen\Versionna\Storage\DatabaseConfiguration;
use Sharpen\Versionna\Storage\DatabaseConnection;
use Sharpen\Versionna\Storage\MigrationTable;

$dbConnection = new DatabaseConnection(
    DatabaseConfiguration::fromArray(
        $configuration['connections'][$connection]
    )
);

$manticoreConnection = new ManticoreConnection(
    $configuration['manticore_connection']['host'],
    $configuration['manticore_connection']['port'],
);

$migrationTable = new MigrationTable(
    $dbConnection,
    $configuration['table_prefix'],
    $configuration['migration_table']
);

$director = new MigrationDirector();

$director
    ->dbConnection($dbConnection)
    ->manticoreConnection($manticoreConnection)
    ->migrationsPath($configuration['migrations_path'])
    ->migrationTable($migrationTable);

$pendingMigrations = $director->getPendingMigrations();

if (count($pendingMigrations) > 0) {
    array_map(
        function ($migration) {
            return ['name' => $migration];
        },
        array_values(array_keys($pendingMigrations)),
    );
} else {
    echo 'ManticoreSearch is up to date! no pending migrations';
}
```
