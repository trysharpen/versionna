<?php

namespace SiroDiaz\ManticoreMigration;

trait Singleton
{
	private static $instance;

	final private function __construct()
	{
		$this->build();
	}

	/**
	 * @return static
	 */
	final public static function getInstance(): static {
		return !self::$instance
			? self::$instance = new static()
			: self::$instance;
	}

	protected function build()
	{
		// here goes the code to build the object
	}

	protected function __clone()
	{
	}

	protected function __sleep()
	{
		throw new \Exception('Cannot serialize singleton');
	}

	protected function __wakeup() {
		throw new \Exception('Cannot unserialize singleton');
	}
}
