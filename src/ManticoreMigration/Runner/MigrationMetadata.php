<?php

namespace SiroDiaz\ManticoreMigration\Runner;

use Exception;
use SiroDiaz\ManticoreMigration\Indexer\Indexer;
use SiroDiaz\ManticoreMigration\Migration;

class MigrationMetadata
{
	protected $migrationFullFilePath;

	protected $filename;

	protected $namespace;

	protected $className;

	public function __construct(string $migrationFullFilePath, string $filename, string $className, $namespace = null)
	{
		$this->migrationFullFilePath = $migrationFullFilePath;
		$this->filename = $filename;
		$this->className = $className;
		$this->namespace = $namespace;
	}

	public function getMigrationFullFilePath(): string
	{
		return $this->migrationFullFilePath;
	}

	public function getFilename(): string
	{
		return $this->filename;
	}

	public function getClassName(): string
	{
		return $this->className;
	}

	public function buildOriginalFilePath(): string
	{
		$migrationFileFullPath = $this->migrationFullFilePath;

		if (strpos($this->filename, '.php') === false) {
			// return $migrationFileFullPath '.php';
		}

		return $migrationFileFullPath;
	}

	/**
	 * @return null|string
	 */
	public function getNamespace()
	{
		return $this->namespace;
	}

	/**
	 * @return string
	 */
	public function getClass(): string
	{
		return !$this->getNamespace()
			? $this->getClassName()
			: $this->getNamespace() . '\\' . $this->getClassName();
	}

	/**
	 * @return Migration
	 * @throws Exception
	 */
	public function getClassInstance(Runner $runner, Indexer $indexer): Migration
	{
		$class = $this->getClass();

		$instance = new $class($runner, $indexer);

		if (!($instance instanceof Migration)) {
			throw new Exception('Migration class must be an instance of Migration');
		}

		return $instance;
	}
}
