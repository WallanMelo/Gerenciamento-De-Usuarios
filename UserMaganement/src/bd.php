<?php
// Verifica se estamos em ambiente Docker
$host = getenv('DB_HOST') ?: 'postgres';
$db   = getenv('DB_NAME') ?: 'usermanagement';
$user = getenv('DB_USER') ?: 'user';
$pass = getenv('DB_PASSWORD') ?: 'passwd';
$port = '5432';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    die(json_encode([
        'error' => 'Erro de conexão com o banco de dados',
        'message' => $e->getMessage(),
        'dsn' => $dsn
    ]));
}

return $pdo;