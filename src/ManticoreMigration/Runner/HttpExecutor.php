<?php

namespace SiroDiaz\ManticoreMigration\Runners;

use SiroDiaz\ManticoreMigration\ManticoreRunner;

class HttpRunner implements ManticoreRunner
{
    private $connection;

    public function __construct(ManticoreConnection $connection)
    {
        $this->connection = $connection;
    }

    public function execute(string $sql): void
    {

        // $dsn = $this->configuration->getDsn();
        // $dsn->setUser($this->configuration->getUser());
        // $dsn->setPassword($this->configuration->getPassword());
        // $dsn->setDatabase($this->configuration->getDbname());
        // $dsn->setPort($this->configuration->getPort());
        // $dsn->setHost($this->configuration->getHost());
        // $dsn->setDriver($this->configuration->getDriver());
        // $dsn->setParams($this->configuration->getParams());
        // $dsn = $dsn->getDsn();
        // $pdo = new \PDO($dsn);
        // $pdo->exec($sentence->getSentence());
    }
}
