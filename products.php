<?php
header('Content-Type: application/json; charset=UTF-8');

// Try parent dir first (one above public_html), then current dir
$paths = [dirname(__DIR__) . '/products.json', __DIR__ . '/products.json'];
$json = null;
foreach ($paths as $p) {
    if (file_exists($p) && is_readable($p)) {
        $json = file_get_contents($p);
        break;
    }
}

if ($json === null) {
    http_response_code(404);
    echo json_encode(['error' => 'products.json not found']);
    exit;
}

// Optional simple token protection: set PRODUCTS_TOKEN in the parent .env to enable
$envFile = dirname(__DIR__) . '/.env';
$productsToken = null;
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($k, $v) = explode('=', $line, 2);
            if (trim($k) === 'PRODUCTS_TOKEN') {
                $productsToken = trim($v);
                break;
            }
        }
    }
}

if ($productsToken) {
    $provided = $_GET['token'] ?? ($_SERVER['HTTP_X_PRODUCTS_TOKEN'] ?? null);
    if ($provided !== $productsToken) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
}

// Serve the raw JSON (already validated as readable above)
echo $json;

// End of file
?>