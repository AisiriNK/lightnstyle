<?php
header('Content-Type: application/json');

// Test database connection with detailed error reporting
$host = 'ep-curly-bar-a18mxr59-pooler.ap-southeast-1.aws.neon.tech';
$dbname = 'neondb';
$username = 'neondb_owner';
$password = 'npg_qKLUE27zrlRN';
$port = '5432';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    echo json_encode([
        'debug' => 'Attempting connection',
        'dsn' => $dsn,
        'username' => $username
    ]);
    
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 30
    ]);
    
    $result = $pdo->query("SELECT version()");
    $version = $result->fetchColumn();
    
    echo json_encode([
        'success' => true,
        'message' => 'Database connected successfully',
        'version' => $version
    ]);
    
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'code' => $e->getCode(),
        'info' => $e->errorInfo ?? null
    ]);
}
?>
