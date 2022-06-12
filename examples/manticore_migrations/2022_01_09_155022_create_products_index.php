<?php

use SiroDiaz\ManticoreMigration\Indexer\Indexer;
use SiroDiaz\ManticoreMigration\Migration;
use SiroDiaz\ManticoreMigration\Runner\ManticoreRunner;

class CreateProductsIndex extends Migration
{
	public $description = 'Creates products index';
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void {
		$this->runner->execute('CREATE TABLE products (id bigint, seller_id bigint, title text stored, description text stored, price float)');
		$this->indexer->index('products', 'SELECT id, seller_id, name as title, description, price FROM products');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(): void {
		$this->runner->execute('DROP TABLE products');
	}
}
