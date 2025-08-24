<?php
header('Content-Type: application/json');

$host = 'ep-curly-bar-a18mxr59-pooler.ap-southeast-1.aws.neon.tech';
$dbname = 'neondb';
$username = 'neondb_owner';
$password = 'npg_qKLUE27zrlRN';
$port = '5432';

// Try different SSL modes
$ssl_modes = ['require', 'prefer', 'disable'];

foreach ($ssl_modes as $ssl_mode) {
    try {
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=$ssl_mode";
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 10
        ]);
        
        $result = $pdo->query("SELECT version()");
        $version = $result->fetchColumn();
        
        echo json_encode([
            'success' => true,
            'message' => "Connected successfully with sslmode=$ssl_mode",
            'version' => $version,
            'ssl_mode' => $ssl_mode
        ]);
        exit;
        
    } catch(PDOException $e) {
        // Continue to next SSL mode
        if ($ssl_mode === 'disable') {
            echo json_encode([
                'success' => false,
                'error' => 'All SSL modes failed',
                'last_error' => $e->getMessage()
            ]);
        }
    }
}
?>
