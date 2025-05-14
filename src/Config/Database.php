<?php

namespace App\Config;

use PDO;
use mysqli;
use PDOException;

class Database
{
    private static $connection = null;

    public static function connect()
    {
        if (self::$connection === null) {
            $config = require __DIR__ . '/../../config/database.php';
            $default = $config['default'];
            $dbConfig = $config['connections'][$default];

            if ($dbConfig['driver'] === 'mysqli') {
                self::$connection = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['database']);
                if (self::$connection->connect_error) {
                    die("MySQL Connection failed: " . self::$connection->connect_error);
                }
            } elseif ($dbConfig['driver'] === 'sqlsrv') {
                $dsn = "sqlsrv:Server={$dbConfig['server']};Database={$dbConfig['database']}";
                try {
                    self::$connection = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
                    self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    die("SQL Server Connection failed: " . $e->getMessage());
                }
            } else {
                die("Invalid database configuration.");
            }
        }
        return self::$connection;
    }
}
