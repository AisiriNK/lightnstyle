<?php
// Database configuration for Neon.tech
$host = 'ep-curly-bar-a18mxr59-pooler.ap-southeast-1.aws.neon.tech';
$dbname = 'neondb';
$username = 'neondb_owner';
$password = 'npg_qKLUE27zrlRN';
$port = '5432';

try {
    // Set SSL environment for PostgreSQL
    putenv('PGSSLMODE=prefer');
    
    // Try different connection approaches
    $connection_attempts = [
        "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=disable",
        "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=allow", 
        "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=prefer",
        "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require"
    ];
    
    $pdo = null;
    $last_error = null;
    
    foreach ($connection_attempts as $dsn) {
        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 10,
            ];
            $pdo = new PDO($dsn, $username, $password, $options);
            break; // Success, exit loop
        } catch(PDOException $e) {
            $last_error = $e->getMessage();
            continue; // Try next connection method
        }
    }
    
    if (!$pdo) {
        throw new PDOException("All connection attempts failed. Last error: " . $last_error);
    }
    
} catch(PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    throw $e; // Re-throw so API can handle it properly
}
?>