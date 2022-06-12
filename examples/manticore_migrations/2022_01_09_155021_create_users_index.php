<?php

use SiroDiaz\ManticoreMigration\Indexer\Indexer;
use SiroDiaz\ManticoreMigration\Migration;
use SiroDiaz\ManticoreMigration\Runner\ManticoreRunner;

class CreateUsersIndex extends Migration
{
	public $description = 'Creates users index';

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void {
		$this->runner->execute('CREATE TABLE users (id bigint, name text indexed, username text stored, biography text, birthdate timestamp)');
		$this->indexer->index('users', 'SELECT id, name, username, biography, birthdate FROM users');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(): void {
		$this->runner->execute('DROP TABLE users');
	}
}
