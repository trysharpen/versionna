<?php

namespace SiroDiaz\ManticoreMigration\Storage\Database\Adapters;

class Mysql extends Schema
{
    public function createTable(): void
    {
        $this->connection->exec(<<<SQL
			CREATE TABLE IF NOT EXISTS {$this->tableName} (
				id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
				version INT UNSIGNED NOT NULL,
				migration_name VARCHAR(255) NOT NULL,
				description VARCHAR(255),
				created_at DATETIME NOT NULL
			)
		SQL);
    }

    public function existsTable(): bool
    {
        $query = <<<SQL
		SELECT COUNT(*)
		FROM information_schema.tables
		WHERE table_schema = DATABASE()
		AND table_name = :tablename
		SQL;

        $statement = $this->connection->prepare($query);
        $statement->bindValue(':tablename', $this->tableName);
        $statement->execute();

        return $statement->fetchColumn() != false;
    }
}
