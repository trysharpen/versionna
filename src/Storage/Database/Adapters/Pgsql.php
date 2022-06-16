<?php

namespace SiroDiaz\ManticoreMigration\Storage\Database\Adapters;

class Pgsql extends Schema
{
	public function createTable()
	{
		$this->connection->exec(<<<SQL
			CREATE TABLE IF NOT EXISTS {$this->tableName} (
				id SERIAL PRIMARY KEY,
				version INT NOT NULL,
				migration_name VARCHAR(255) NOT NULL,
				description VARCHAR(255),
				created_at TIMESTAMP NOT NULL
			)
		SQL);
	}

	public function existsTable(): bool
	{
		$query = <<<SQL
		SELECT EXISTS (
			SELECT FROM
				pg_tables
			WHERE
				schemaname = 'public' AND
				tablename  = :tablename
		)
		SQL;

		$statement = $this->connection->prepare($query);
		$statement->bindValue(':tablename', $this->tableName);
		$statement->execute();

		return $statement->fetchColumn() !== false;
	}
}
