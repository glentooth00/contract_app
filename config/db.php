<?php
// Load database config
$config = require_once 'database.php';

// Get the default database type
$default = $config['default'];
$dbConfig = $config['connections'][$default];

$pdo = null;
$mysqli = null;

if ($dbConfig['driver'] === 'mysqli') {
    // MySQLi Connection
    $mysqli = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['database']);

    if ($mysqli->connect_error) {
        die("MySQL Connection failed: " . $mysqli->connect_error);
    }

    echo "Connected to MySQL successfully!";
} elseif ($dbConfig['driver'] === 'sqlsrv') {
    // SQL Server Connection (Using PDO)
    $dsn = "sqlsrv:Server={$dbConfig['server']};Database={$dbConfig['database']}";

    try {
        $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connected to SQL Server successfully!";
    } catch (PDOException $e) {
        die("SQL Server Connection failed: " . $e->getMessage());
    }
} else {
    die("Invalid database configuration.");
}
