<?php

namespace SiroDiaz\ManticoreMigration\Runner;

// TODO: Refactor methods using a functional approach
class Loader
{
	/**
	 *
	 * @param string $dir
	 * @return array<MigrationMetadata>
	 */
	public static function load(string $dir): array
	{
		$files = scandir($dir);
		$migrations = [];

		foreach ($files as $file) {
			if ($file === '.' || $file === '..') {
				continue;
			}

			$migrationFullFilePath = $dir . DIRECTORY_SEPARATOR . $file;

			if (is_file($migrationFullFilePath)) {
				$migrationClassName = self::getMigrationClassName($migrationFullFilePath);

				if ($migrationClassName) {
					$migrations[preg_replace('/\.php$/', '', $file)] = new MigrationMetadata(
						$migrationFullFilePath,
						preg_replace('/\.php$/', '', $file),
						$migrationClassName
					);
				}
			}
		}

		return $migrations;
	}

	/**
	 * @param string $migrationFullFilePath
	 * @return string|null
	 */
	public static function getMigrationClassName(string $migrationFullFilePath): string|null
	{
		$className = self::getClassName($migrationFullFilePath);

		return !$className ? null : $className;
	}

	/**
	 * @param string $rawClassName
	 * @return string
	 */
	private static function transformToValidClassName(string $rawClassName): string
	{
		return join('', array_map('ucfirst', explode('_', $rawClassName)));
	}

	/**
	 * @param string $file
	 * @return string
	 */
	private static function getClassName(string $file): string
	{
		$parts = explode(DIRECTORY_SEPARATOR, $file);
		$file = $parts[count($parts) - 1];
		$file = str_replace('.php', '', $file);

		$match = [];

		if (preg_match('/^[0-9]+_[0-9]+_[0-9]+_[0-9]+_(?<class_lowercase>[a-zA-Z0-9_]+){1}$/', $file, $match)) {
			$file = $match['class_lowercase'];
		}

		return self::transformToValidClassName($file);
	}
}
