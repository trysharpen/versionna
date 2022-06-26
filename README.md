# manticore-migration: under-construction
Manticoresearch migration tool. Keep updated your index schemas up to date using a executable CLI script or integrate it programmatically in your application code.

![migrate and migrate:down](./resources/migrate-migrate-down.gif)

## project progress and roadmap
  - [ ] Add CI pipeline
    - [ ] Add PHP versions supported
      - [ ] 7.3
      - [ ] 7.4
      - [ ] 8.0
      - [ ] 8.1
    - [x] PhpStan
    - [x] PHPUnit run tests
  - Pre-commit linter and tests checks
    - [x] Add Grumphp
      - [x] PHPStan
      - [x] PHPUnit
  - [ ] Add a logger implementation
  - [x] Add docker-compose stack files for testing and development
  - [ ] Add code documentation
  - [ ] Write a complete README file explaining all
  - [ ] Add unit and integration tests
  - [x] Add command line interface feature
    - [x] Add cli application metadata such as name, description, etc.
    - [x] Created structure of the CLI application
  - [x] Executable script (bin/manticore-migration)
  - [ ] Add commands
    - [x] list
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

```composer require sirodiaz/manticore-migration```

## Usage
First of all, you need to install the package.

```composer require sirodiaz/manticore-migration```

After been installed, you need to create a directory.
That directory will contain the migration files sorted by creation date.

You have two different ways to use this package:

  - programmatically
  - CLI

You can create your own integration with `manticore-migration` using the programmatically way as you can see in hte **examples** directory in this repository.

In each section of these documentation you will see both: programmatically and CLI version to create, migrate, rollback, list applied and pending migrations.
### Create migration

### Apply migration
![migrate and migrate:down](./resources/migrate-migrate-down.gif)
### Rollback migration
![migrate and migrate:down](./resources/migrate-migrate-down.gif)
### List migrations applied history
![migration:list:migrated](./resources/migration-list-migrated.gif)
### List pending migrations
![migration:list:pending](./resources/migration-list-pending.gif)
