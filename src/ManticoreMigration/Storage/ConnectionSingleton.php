<?php

declare(strict_types=1);

namespace SiroDiaz\ManticoreMigration\Storage;

use Exception;

class ConnectionSingleton
{
    private static $connection = null;

    public static function getInstance()
    {
        if (self::$connection === null) {
            throw new Exception('Connection is not initialized');
        }

        return self::$connection;
    }

    public static function getConnection()
    {
        return self::getInstance()->getConnection();
    }

    public static function setConnection(DatabaseConnection $connection)
    {
        self::$connection = $connection;
    }
}
