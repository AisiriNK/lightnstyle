<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Test which API is being called
error_log("Database API products.php called at " . date('Y-m-d H:i:s'));

try {
    require_once '../config/database.php';
    
    // Test simple query first
    $result = $pdo->query("SELECT 1 as test");
    $test = $result->fetch();
    
    echo json_encode([
        'success' => true,
        'message' => 'Database connected successfully!',
        'source' => 'neon_database',
        'test_query' => $test,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    
} catch(Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'source' => 'database_failed',
        'error' => $e->getMessage(),
        'fallback_message' => 'Will use test data instead'
    ]);
}
?>
