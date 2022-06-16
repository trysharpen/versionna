<?php

return [
	'connection' => 'pgsql',
	'connections' => [
		'sqlite' => [
			'driver' => 'sqlite',
			'database' => __DIR__ . '/database/playground.sqlite',
			'host' => '',
			'port' => '',
			'user' => '',
			'password' => '',
		],
		'mysql' => [
			'driver' => 'mysql',
			'database' => 'manticore',
			'host' => '127.0.0.1',
			'port' => 3306,
			'user' => 'manticore',
			'password' => 'manticore',
		],
		'pgsql' => [
			'driver' => 'pgsql',
			'database' => 'manticore',
			'host' => '127.0.0.1',
			'port' => 5432,
			'user' => 'manticore',
			'password' => 'manticore',
		]
	],

	'table_prefix' => 'manticore_',
	'migration_table' => 'migrations',
	'migrations_path' => __DIR__ . DIRECTORY_SEPARATOR .'manticore_migrations',

	'manticore_connection' => [
		'host' => '127.0.0.1',
		'port' => 9308,
	],
];
