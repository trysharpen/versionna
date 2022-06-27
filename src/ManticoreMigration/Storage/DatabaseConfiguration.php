<?php

namespace SiroDiaz\ManticoreMigration\Storage;

use Nyholm\Dsn\DsnParser;
use Nyholm\Dsn\Exception\InvalidDsnException;

final class DatabaseConfiguration
{
    /**
     * @var string
     */
    private $driver;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $dbname;

    /**
     * @var ?string
     */
    private $user;

    /**
     * @var ?string
     */
    private $password;

    /**
     * @var array
     */
    private $params;

    /**
     * @param string|array $config Configuration array or string uri
     */
    public function __construct($config = [])
    {
        $this->driver = $config['driver'] ?? 'mysql';
        $this->host = $config['host'] ?? 'localhost';
        $this->port = $config['port'] ?? '3306';
        $this->dbname = $config['database'] ?? 'manticore';
        $this->user = $config['user'] ?? null;
        $this->password = $config['password'] ?? null;
        $this->params = $config['params'] ?? [];
    }

    /**
     * @param string $dsn DSN string
     * @throws InvalidDsnException
     * @return DatabaseConfiguration
     */
    public static function fromDsn(string $dsn): static
    {
        $config = [];
        $dsnParsed = DsnParser::parse($dsn);

        $config['driver'] = $dsnParsed->getScheme();
        $config['host'] = $dsnParsed->getHost();
        $config['dbname'] = $dsnParsed->getPath();
        $config['user'] = $dsnParsed->getUser();
        $config['password'] = $dsnParsed->getPassword();
        $config['params'] = $dsnParsed->getParameters();

        return new DatabaseConfiguration($config);
    }

    public static function fromArray(array $config): static
    {
        return new static($config);
    }

    public function getDriver(): string
    {
        return $this->driver;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getDatabase(): string
    {
        return $this->dbname;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getDsn(): string
    {
        if ($this->getDriver() === 'sqlite') {
            return $this->getDriver() . ':' . $this->getDatabase();
        }

        $dsn = $this->driver . ':';
        $dsn .= 'host=' . $this->host . ';';
        $dsn .= 'port=' . $this->port . ';';
        $dsn .= 'dbname=' . $this->dbname . ';';
        //$dsn .= 'charset=utf8mb4';

        return $dsn;
    }
}
