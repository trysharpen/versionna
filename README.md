# manticore-migration
Manticoresearch migration tool. Keep updated your index schemas up to date programmatically in your application.

## project progress and roadmap
  - [ ] Add CI pipeline
    - [ ] Add PHP versions supported
      - [ ] 7.3
      - [ ] 7.4
      - [ ] 8.0
      - [ ] 8.1
    - [ ] CSniffer
    - [ ] PhpStan
    - [ ] PHPUnit run tests
  - [ ] Add a logger implementation
  - [x] Add docker-compose stack files for testing and development
  - [ ] Add unit and integration tests
  - [ ] Add command line interface feature
    - [ ] Add cli application metadata such as name, description, etc.
    - [x] Created structure of the CLI application
  - [x] Executable script (bin/manticore-migration)
  - [ ] Add commands
    - [ ] list
    - [ ] migration:list:pending
    - [ ] migration:list:migrated
    - [x] migrate
    - [x] rollback
    - [ ] rollback with --steps
    - [x] fresh
    - [ ] refresh
    - [ ] refresh with --steps
    - [ ] reset
    - [ ] status
    - [ ] help
  - [x] Add drivers to support multiple DBs engines dialects
    - [x] Add driver for SQLite
    - [x] Add driver for MySQL
    - [x] Add driver for PostgreSQL
  - [ ] Create a Laravel package
  - [ ] Create a Symfony package
## Installation

```composer require manticore-migration```

## Usage

### Create migration

### Apply migration

### Rollback migration

### List all migrations

### List migrations history

### List pending migrations
