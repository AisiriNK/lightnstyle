<?php
header('Content-Type: application/json');

$host = 'ep-curly-bar-a18mxr59-pooler.ap-southeast-1.aws.neon.tech';
$dbname = 'neondb';
$username = 'neondb_owner';
$password = 'npg_qKLUE27zrlRN';
$port = '5432';

try {
    // Try using pg_connect instead of PDO
    $connection_string = "host=$host port=$port dbname=$dbname user=$username password=$password sslmode=require";
    
    $connection = pg_connect($connection_string);
    
    if ($connection) {
        $result = pg_query($connection, "SELECT version()");
        $version = pg_fetch_result($result, 0, 0);
        
        echo json_encode([
            'success' => true,
            'message' => 'Connected using pg_connect!',
            'version' => $version,
            'method' => 'pg_connect'
        ]);
        
        pg_close($connection);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'pg_connect failed',
            'last_error' => pg_last_error()
        ]);
    }
    
} catch(Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require;sslrootcert=/Applications/XAMPP/xamppfiles/htdocs/lightnstyle/neontech.crt";
?>

