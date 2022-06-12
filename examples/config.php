<?php

return [
	'connection' => [
		'driver' => 'sqlite',
		'database' => __DIR__ . '/database/playground.sqlite',
		'host' => '',
		'port' => '',
		'user' => '',
		'password' => '',
	],

	'table_prefix' => 'manticore_',
	'migration_table' => 'migrations',
	'migrations_path' => __DIR__ . DIRECTORY_SEPARATOR .'manticore_migrations',

	'manticore_connection' => [
		'host' => '127.0.0.1',
		'port' => 9308,
	],
];
