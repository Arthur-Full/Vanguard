<?php
function getConnection(): PDO
{
    $host = getenv('DB_HOST') ?: 'localhost';
    $database = getenv('DB_NAME') ?: 'vanguard';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: '';
    $port = getenv('DB_PORT') ?: '3306';

    $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
    return new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
}
?>
