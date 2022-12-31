<?php

require __DIR__ . '/vendor/autoload.php';

use Sharpen\Versionna\Indexer\ManticoreIndexer;
use Sharpen\Versionna\Manticore\ManticoreConnection;
use Sharpen\Versionna\Versionna;
use Sharpen\Versionna\MigrationDirector;
use Sharpen\Versionna\Runner\Loader;
use Sharpen\Versionna\Storage\DatabaseConfiguration;
use Sharpen\Versionna\Storage\DatabaseConnection;
use Sharpen\Versionna\Storage\MigrationTable;

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


//	class CreateProductsIndex extends Versionna {
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

if (!function_exists('testDbConnection')) {
	function testDbConnection()
	{
		$configuration = require __DIR__ . '/config.php';

		$dbConfiguration = DatabaseConfiguration::fromArray(
			$configuration['connection']
		);

		$dbConn = new DatabaseConnection($dbConfiguration);

		if (!$dbConn->getConnection()) {
			exit(1);
		}

		echo 'Connection is OK' . PHP_EOL;

		$migrationTable = new MigrationTable($dbConn, $configuration['table_prefix'], $configuration['migration_table']);

		if ($migrationTable->exists()) {
			echo 'Migration table exists' . PHP_EOL;
		} else {
			echo 'Migration table does not exist' . PHP_EOL;
			$migrationTable->create();
			echo 'Migration table created' . PHP_EOL;
		}
	}
}

if (!function_exists('listMigrations')) {
	function listMigrations()
	{
		$configuration = require __DIR__ . '/config.php';
		var_dump($configuration);
		$dbConfiguration = DatabaseConfiguration::fromArray(
			$configuration['connections']['pgsql'],
		);

		$dbConn = new DatabaseConnection($dbConfiguration);

		$migrationTable = new MigrationTable(
			$dbConn,
			$configuration['table_prefix'],
			$configuration['migration_table']
		);

		$migrations = $migrationTable->getSortedMigrations();

		foreach ($migrations as $migration) {
			echo $migration->getName() . PHP_EOL;
		}
	}
}

//function migrate() {
//	$configuration = require __DIR__ .'/config.php';
//
//	$dbConnection = new DatabaseConnection(
//		DatabaseConfiguration::fromArray($configuration['connection'])
//	);
//
//	$manticoreConnection = new ManticoreConnection(
//		$configuration['manticore_connection']['host'],
//		$configuration['manticore_connection']['port'],
//	);
//
//	$director = new MigrationDirector();
//	$director
//		->dbConnection($dbConnection)
//		->manticoreConnection($manticoreConnection)
//		->migrationsPath($configuration['migrations_path'])
//		->migrationTable(new MigrationTable(
//			$dbConnection,
//			$configuration['table_prefix'],
//			$configuration['migration_table'],
//		));
//
//	$director->migrate();
//}
//
//function rollback($steps = 1) {
//	$configuration = require __DIR__ .'/config.php';
//
//	$dbConnection = new DatabaseConnection(
//		DatabaseConfiguration::fromArray($configuration['connection'])
//	);
//
//	$manticoreConnection = new ManticoreConnection(
//		$configuration['manticore_connection']['host'],
//		$configuration['manticore_connection']['port'],
//	);
//
//	$migrationTable = new MigrationTable($dbConnection, $configuration['table_prefix'], $configuration['migration_table']);
//
//	$director = new MigrationDirector();
//	$director
//		->dbConnection($dbConnection)
//		->manticoreConnection($manticoreConnection)
//		->migrationsPath($configuration['migrations_path'])
//		->migrationTable($migrationTable);
//
//	$director->undoMigrations($steps);
//}
//
//function refresh() {
//	$configuration = require __DIR__ .'/config.php';
//	$dbConnection = new DatabaseConnection(
//		DatabaseConfiguration::fromArray($configuration['connection'])
//	);
//	$migrationTable = new MigrationTable($dbConnection, $configuration['table_prefix'], $configuration['migration_table']);
//
//	$migrationTable->truncate();
//}
//
//function destroy() {
//	$configuration = require __DIR__ .'/config.php';
//	$dbConnection = new DatabaseConnection(
//		DatabaseConfiguration::fromArray($configuration['connection'])
//	);
//	$migrationTable = new MigrationTable($dbConnection, $configuration['table_prefix'], $configuration['migration_table']);
//
//	$migrationTable->drop();
//}
//
//function populate() {
//	$configuration = require __DIR__ .'/config.php';
//	$dbConnection = new DatabaseConnection(
//		DatabaseConfiguration::fromArray($configuration['connection'])
//	);
//
//	$manticoreConnection = new ManticoreConnection(
//		$configuration['manticore_connection']['host'],
//		$configuration['manticore_connection']['port'],
//	);
//
//	$migrationTable = new MigrationTable($dbConnection, $configuration['table_prefix'], $configuration['migration_table']);
//
//	$indexer = new ManticoreIndexer($dbConnection, $manticoreConnection);
//
//	$indexer->index('users', 'SELECT id, name, username, biography, strftime(\'%s\', birthdate) as birthdate FROM users');
//}

// listMigrations();
// testDbConnection();
// populate();
// refresh(true);
// refresh(true);
listMigrations();
// rollback();
