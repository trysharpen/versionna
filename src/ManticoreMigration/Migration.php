<?php declare(strict_types=1);

namespace SiroDiaz\ManticoreMigration;

use SiroDiaz\ManticoreMigration\Indexer\Indexer;
use SiroDiaz\ManticoreMigration\Runner\Runner;

abstract class Migration
{

	/**
	 * @var Runner
	 */
	protected $runner;

	/**
	 * @var Indexer
	 */
	protected $indexer;

	/**
	 * @var string
	 */
	public $description = '';

	public function __construct(Runner $runner, Indexer $indexer)
	{
		$this->runner = $runner;
		$this->indexer = $indexer;
	}

	public abstract function up(): void;

	public abstract function down(): void;

	public function __toString(): string
	{
		return get_class($this);
	}
}
