<?php

use SiroDiaz\ManticoreMigration\Indexer\ManticoreIndexer;
use SiroDiaz\ManticoreMigration\Manticore\ManticoreConnection;
use SiroDiaz\ManticoreMigration\ManticoreMigration;
use SiroDiaz\ManticoreMigration\MigrationDirector;
use SiroDiaz\ManticoreMigration\Runner\Loader;
use SiroDiaz\ManticoreMigration\Storage\ConnectionSingleton;
use SiroDiaz\ManticoreMigration\Storage\DatabaseConfiguration;
use SiroDiaz\ManticoreMigration\Storage\DatabaseConnection;
use SiroDiaz\ManticoreMigration\Storage\MigrationTable;

require __DIR__ . '/../vendor/autoload.php';

//$configuration = require __DIR__ .'/config.php';
//
//$dbConfiguration = DatabaseConfiguration::fromArray(
//	$configuration['connection']
//);

//$manticoreConfiguration = ManticoreConfiguration::fromArray(
//	$configuration['manticore_connection']
//);

//$conn = new DatabaseConnection($dbConfiguration);
//ConnectionSingleton::setConnection($conn);
//
//$dbConn = ConnectionSingleton::getInstance();
//
//if ($dbConn->getConnection()) {
//	echo 'Connection is OK'. PHP_EOL;
//}

//$migrationTable = new MigrationTable($dbConn, $configuration['table_prefix'], $configuration['migration_table']);
//
//echo $migrationTable->exists() === true;


//	class CreateProductsIndex extends ManticoreMigration {
//		public function up(ManticoreMigrationRunner $runner, ManticoreIndexer $indexer) {
//			$runner->execute('CREATE TABLE products (title text indexed, description text stored, seller text, price float)');
//
//			$indexer->index('products', 'rt_products', "SELECT * FROM products");
//		}
//
//		public function down($runner) {
//			$runner->execute('DROP TABLE IF NOT EXISTS products');
//		}
//	}

// commands
// php artisan manticore:migrate
// php artisan manticore:rollback
// php artisan manticore:populate
// php artisan manticore:refresh

function testDbConnection()
{
	$configuration = require __DIR__ .'/config.php';

	$dbConfiguration = DatabaseConfiguration::fromArray(
		$configuration['connection']
	);

	$dbConn = new DatabaseConnection($dbConfiguration);
	//ConnectionSingleton::setConnection($conn);

	// $dbConn = ConnectionSingleton::getInstance();

	if (!$dbConn->getConnection()) {
		exit(1);
	}

	echo 'Connection is OK'. PHP_EOL;

	$migrationTable = new MigrationTable($dbConn, $configuration['table_prefix'], $configuration['migration_table']);

	if ($migrationTable->exists()) {
		echo 'Migration table exists'. PHP_EOL;
	} else {
		echo 'Migration table does not exist'. PHP_EOL;
		$migrationTable->create();
		echo 'Migration table created'. PHP_EOL;
	}
}

function listMigrations()
{
	$configuration = require __DIR__ .'/config.php';

	$dbConfiguration = DatabaseConfiguration::fromArray(
		$configuration['connection']
	);

	$dbConn = new DatabaseConnection($dbConfiguration);
	ConnectionSingleton::setConnection($dbConn);

	$migrationTable = new MigrationTable(
		ConnectionSingleton::getInstance(),
		$configuration['table_prefix'],
		$configuration['migration_table']
	);

	$migrations = $migrationTable->getSortedMigrations();

	foreach ($migrations as $migration) {
		echo $migration->getName() . PHP_EOL;
	}
}


function migrate() {
	$configuration = require __DIR__ .'/config.php';
	// check if the migration table exists
	// $migrationTable = new MigrationTable($dbConn, $configuration['table_prefix'], $configuration['migration_table']);
	// // if not, create it
	// $migrationTable->exists() === false ?? $migrationTable->create();
	// // if it exists, check latest migration version
	// $latestMigration = $migrationTable->getLatestMigrations();
	// if the latest migration version is the same as the current version, do nothing
	// if the latest migration version is different, run the migrations
	//$migrations = (new Loader())->load($configuration['migrations_path']);
	$dbConnection = new DatabaseConnection(
		DatabaseConfiguration::fromArray($configuration['connection'])
	);

	$manticoreConnection = new ManticoreConnection(
		$configuration['manticore_connection']['host'],
		$configuration['manticore_connection']['port'],
	);

	$director = new MigrationDirector();
	$director
		->dbConnection($dbConnection)
		->manticoreConnection($manticoreConnection)
		->migrationsPath($configuration['migrations_path'])
		->migrationTable(new MigrationTable(
			$dbConnection,
			$configuration['table_prefix'],
			$configuration['migration_table'],
		));

	$director->migrate();
}

function rollback($steps = 1) {
	$configuration = require __DIR__ .'/config.php';

	$dbConnection = new DatabaseConnection(
		DatabaseConfiguration::fromArray($configuration['connection'])
	);

	$manticoreConnection = new ManticoreConnection(
		$configuration['manticore_connection']['host'],
		$configuration['manticore_connection']['port'],
	);

	$migrationTable = new MigrationTable($dbConnection, $configuration['table_prefix'], $configuration['migration_table']);

	$director = new MigrationDirector();
	$director
		->dbConnection($dbConnection)
		->manticoreConnection($manticoreConnection)
		->migrationsPath($configuration['migrations_path'])
		->migrationTable($migrationTable);

	$director->undoMigrations($steps);

}

function refresh() {
	$configuration = require __DIR__ .'/config.php';
	$dbConnection = new DatabaseConnection(
		DatabaseConfiguration::fromArray($configuration['connection'])
	);
	$migrationTable = new MigrationTable($dbConnection, $configuration['table_prefix'], $configuration['migration_table']);

	$migrationTable->truncate();
}

function destroy() {
	$configuration = require __DIR__ .'/config.php';
	$dbConnection = new DatabaseConnection(
		DatabaseConfiguration::fromArray($configuration['connection'])
	);
	$migrationTable = new MigrationTable($dbConnection, $configuration['table_prefix'], $configuration['migration_table']);

	$migrationTable->drop();
}

function populate() {
	$configuration = require __DIR__ .'/config.php';
	$dbConnection = new DatabaseConnection(
		DatabaseConfiguration::fromArray($configuration['connection'])
	);

	$manticoreConnection = new ManticoreConnection(
		$configuration['manticore_connection']['host'],
		$configuration['manticore_connection']['port'],
	);

	$migrationTable = new MigrationTable($dbConnection, $configuration['table_prefix'], $configuration['migration_table']);

	$indexer = new ManticoreIndexer($dbConnection, $manticoreConnection);

	$indexer->index('users', 'SELECT id, name, username, biography, strftime(\'%s\', birthdate) as birthdate FROM users');
}

// listMigrations();
// testDbConnection();
// populate();
// refresh(true);
// refresh(true);
migrate();
// rollback();
