<?php

namespace SiroDiaz\ManticoreMigration\Storage;

use Nyholm\Dsn\DsnParser;
use Nyholm\Dsn\Exception\InvalidDsnException;

final class DatabaseConfiguration
{
    private string $driver;

    private string $host;

    private string $port;

    private string $dbname;

    private ?string $user;

    private ?string $password;

    // private array $params;

    /**
     * @param string|array<string,mixed> $config Configuration array or string uri
     */
    public function __construct($config = [])
    {
        $this->driver = $config['driver'] ?? 'mysql';
        $this->host = $config['host'] ?? 'localhost';
        $this->port = $config['port'] ?? '3306';
        $this->dbname = $config['database'] ?? 'manticore';
        $this->user = $config['user'] ?? null;
        $this->password = $config['password'] ?? null;
        // $this->params = $config['params'] ?? [];
    }

    /**
     * @param string $dsn DSN string
     * @throws InvalidDsnException
     * @return DatabaseConfiguration
     */
    public static function fromDsn(string $dsn): self
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

    /**
     *
     * @param array<string,mixed> $config
     * @return DatabaseConfiguration
     */
    public static function fromArray(array $config): self
    {
        return new self($config);
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
