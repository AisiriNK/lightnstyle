<?php
// Test database connection
require_once '../config/database.php';

try {
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
        'code' => $e->getCode()
    ]);
}
?>
