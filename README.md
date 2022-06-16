# manticore-migration
Manticoresearch migration tool. Keep updated your index schemas up to date programmatically in your application.

## project progress and roadmap
  - [ ] Add a logger implementation
  - [ ] Add command line interface feature
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
  - [ ] Add drivers to support multiple DBs engines dialects
    - [x] Add driver for SQLite
    - [ ] Add driver for MySQL
    - [ ] Add driver for PostgreSQL
  - [ ] Create a Laravel package
  - [ ] Create a Symfony package
  - [ ] Add tests
## Installation

```composer require manticore-migration```

## Usage

### Create migration

### Apply migration

### Rollback migration

### List all migrations

### List migrations history

### List pending migrations
