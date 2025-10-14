<?php
// Debug script to check environment variable loading

// Simple function to load environment variables from .env file
function loadEnv($file) {
    if (!file_exists($file)) {
        return false;
    }
    
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Skip comments
        }
        
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            
            if (!array_key_exists($name, $_ENV)) {
                $_ENV[$name] = $value;
                putenv("$name=$value");
            }
        }
    }
    return true;
}

echo "<h2>Environment Debug</h2>";

echo "<h3>1. Checking .env file existence:</h3>";
$envFile = __DIR__ . '/.env';
echo "Looking for file: " . $envFile . "<br>";
echo "File exists: " . (file_exists($envFile) ? 'YES' : 'NO') . "<br>";

if (file_exists($envFile)) {
    echo "File is readable: " . (is_readable($envFile) ? 'YES' : 'NO') . "<br>";
    echo "File size: " . filesize($envFile) . " bytes<br>";
}

echo "<h3>2. Loading environment variables:</h3>";
$loadResult = loadEnv($envFile);
echo "Load result: " . ($loadResult ? 'SUCCESS' : 'FAILED') . "<br>";

echo "<h3>3. Environment variables:</h3>";
$api_key = $_ENV['BREVO_API_KEY'] ?? '';
$api_url = $_ENV['BREVO_API_URL'] ?? '';

echo "BREVO_API_KEY: " . (empty($api_key) ? 'EMPTY' : 'SET (length: ' . strlen($api_key) . ')') . "<br>";
echo "BREVO_API_URL: " . (empty($api_url) ? 'EMPTY' : htmlspecialchars($api_url)) . "<br>";

echo "<h3>4. Testing cURL initialization:</h3>";
if (!empty($api_url)) {
    $ch = curl_init($api_url);
    if ($ch === false) {
        echo "cURL init FAILED for URL: " . htmlspecialchars($api_url) . "<br>";
    } else {
        echo "cURL init SUCCESS for URL: " . htmlspecialchars($api_url) . "<br>";
        curl_close($ch);
    }
} else {
    echo "Cannot test cURL - API URL is empty<br>";
}

echo "<h3>5. Raw .env file contents:</h3>";
if (file_exists($envFile)) {
    echo "<pre>" . htmlspecialchars(file_get_contents($envFile)) . "</pre>";
}
?>