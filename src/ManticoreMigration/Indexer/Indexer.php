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

    /**
     * This method allows an entrypoint to index/populate your database
     * and sync it with the desired manticoresearch RT index.
     * Also allowing to transform the data before index it.
     *
     * @param string $toIndex
     * @param string $query
     * @param array<string> $indexFields
     * @param int $chunkSize
     * @param bool $repopulate
     * @return bool
     */
    abstract public function index(string $toIndex, string $query, array $indexFields = ['*'], int $chunkSize = 100, bool $repopulate = true): bool;
}
