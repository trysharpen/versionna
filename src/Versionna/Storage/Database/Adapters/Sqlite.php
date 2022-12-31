<?php

namespace Sharpen\Versionna\Storage\Database\Adapters;

class Sqlite extends Schema
{
	public function createTable(): void
	{
		$this->connection->exec(<<<SQL
			CREATE TABLE IF NOT EXISTS {$this->tableName} (
				id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
				version INTEGER NOT NULL,
				migration_name TEXT NOT NULL,
				description TEXT,
				created_at DATETIME NOT NULL
			)
		SQL);
	}

	public function existsTable(): bool
	{
		$query = "SELECT name FROM sqlite_master WHERE name='{$this->tableName}' AND type='table'";

		$statement = $this->connection->prepare($query);
		$statement->execute();

		return $statement->fetchColumn() !== false;
	}
}
