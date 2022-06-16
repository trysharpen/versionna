<?php

namespace SiroDiaz\ManticoreMigration\Storage\Database\Adapters\Schema;

use SiroDiaz\ManticoreMigration\Storage\DatabaseConnection;

class Sqlite extends AbstractSchema
{
	protected $connection;

	public function __construct(DatabaseConnection $connection)
	{
		$this->connection = $connection;
	}

	public function createTable(string $tableName, array $columns): void
	{
		$this->connection->execute(
			"CREATE TABLE {$this->tablePrefix}{$tableName} (" . implode(', ', $columns) . ')'
		);
	}

	public function dropTable(string $tableName): void
	{
		$this->connection->execute("DROP TABLE {$this->tablePrefix}{$tableName}");
	}

	public function renameTable(string $oldName, string $newName): void
	{
		$this->connection->execute("ALTER TABLE {$this->tablePrefix}{$oldName} RENAME TO {$this->tablePrefix}{$newName}");
	}

	public function addColumn(string $tableName, string $columnName, string $columnType): void
	{
		$this->connection->execute("ALTER TABLE {$this->tablePrefix}{$tableName} ADD COLUMN {$columnName} {$columnType}");
	}

	public function dropColumn(string $tableName, string $columnName): void
	{
		$this->connection->execute("ALTER TABLE {$this->tablePrefix}{$tableName} DROP COLUMN {$columnName}");
	}

	public function renameColumn(string $tableName, string $oldName, string $newName): void
	{
		$this->connection->execute("ALTER TABLE {$this->tablePrefix}{$tableName} RENAME COLUMN {$oldName} TO {$newName}");
	}

	public function changeColumn(string $tableName, string $columnName, string $columnType): void
	{
		$this->connection->execute("ALTER TABLE {$this->tablePrefix}{$tableName} ALTER COLUMN {$columnName} {$columnType}");
	}

	public function addIndex(string $tableName, string $columnName, string $indexName = null): void
	{
		$this->connection->execute("CREATE INDEX {$indexName} ON {$this->tablePrefix}{$tableName} ({$columnName})");
	}

	public function dropIndex(string $tableName, string $indexName): void
	{
		$this->connection->execute("DROP INDEX {$indexName} ON {$this->tablePrefix}{$tableName}");
	}

	public function addForeignKey(string $tableName, string $columnName, string $refTableName, string $refColumnName): void
	{
		$this->connection->execute("ALTER TABLE {$this->tablePrefix}{$tableName} ADD FOREIGN KEY ({$columnName}) REFERENCES {$this->tablePrefix}{$refTableName} ({$refColumnName})");
	}

	public function dropForeignKey(string $tableName, string $columnName): void
	{
		$this->connection->execute("ALTER TABLE {$this->tablePrefix}{$tableName} DROP FOREIGN KEY ({$columnName})");
	}

	public function addPrimaryKey(string $tableName, string $columnName): void
	{
		$this->connection->execute("ALTER TABLE {$this->tablePrefix}{$tableName} ADD PRIMARY KEY ({$columnName})");
	}

	public function dropPrimaryKey(string $tableName): void
	{
		$this->connection->execute("ALTER TABLE {$this->tablePrefix}{$tableName} DROP PRIMARY KEY");
	}

	public function addUniqueKey(string $tableName, string $columnName, string $indexName = null): void
	{
		$this->connection->execute("CREATE UNIQUE INDEX {$indexName} ON {$this->tablePrefix}{$tableName} ({$columnName})");
	}

	public function dropUniqueKey(string $tableName, string $indexName): void
	{
		$this->connection->execute("DROP INDEX {$indexName} ON {$this->tablePrefix}{$tableName}");
	}

}
