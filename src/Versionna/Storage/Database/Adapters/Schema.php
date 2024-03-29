<?php

namespace Sharpen\Versionna\Storage\Database\Adapters;

use PDO;
use Sharpen\Versionna\Storage\Database\Adapters\Schema\CreateSchema;

abstract class Schema implements CreateSchema
{
    protected PDO $connection;

    protected string $tableName;

    public function __construct(PDO $connection, string $tableName)
    {
        $this->connection = $connection;
        $this->tableName = $tableName;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }

    abstract public function existsTable(): bool;
}
