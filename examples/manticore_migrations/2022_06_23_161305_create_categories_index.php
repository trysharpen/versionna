<?php

use SiroDiaz\ManticoreMigration\Migration;

class CreateCategoriesIndex extends Migration
{
	public $description = 'Create a new categories index and sync with the SQL table';

	public function up(): void
	{
		$this->runner->execute('CREATE TABLE categories (id bigint, name text, description text)');
	}

	public function down(): void
	{
		$this->runner->execute('DROP TABLE categories');
	}
}
