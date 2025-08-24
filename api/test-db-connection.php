<?php
header('Content-Type: application/json');

$host = 'ep-curly-bar-a18mxr59-pooler.ap-southeast-1.aws.neon.tech';
$dbname = 'neondb';
$username = 'neondb_owner';
$password = 'npg_qKLUE27zrlRN';
$port = '5432';

try {
    // For local testing, disable SSL
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=disable";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 10,
    ];
    $pdo = new PDO($dsn, $username, $password, $options);
    $result = $pdo->query("SELECT version()");
    $version = $result->fetchColumn();
    echo json_encode([
        'success' => true,
        'message' => 'Connected to Neon.tech database!',
        'version' => $version
    ]);
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
