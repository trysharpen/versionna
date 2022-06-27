<?php

namespace SiroDiaz\ManticoreMigration\Indexer;

use SiroDiaz\ManticoreMigration\Manticore\ManticoreConnection;
use SiroDiaz\ManticoreMigration\Storage\DatabaseConnection;

// TODO: Implement review the Indexer required methods
abstract class Indexer
{
    /**
     * @var DatabaseConnection
     */
    protected $databaseConnection;

    /**
     * @var ManticoreConnection
     */
    protected $manticoreConnection;

    public function __construct(DatabaseConnection $databaseConnection, ManticoreConnection $manticoreConnection)
    {
        $this->databaseConnection = $databaseConnection;
        $this->manticoreConnection = $manticoreConnection;
    }

    abstract public function index(string $toIndex, string $query, array $indexFields = ['*'], int $chunkSize = 100, bool $repopulate = true): bool;
}
