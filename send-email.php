<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Get POST data and files
$data = [];
$attachments = [];

// Handle both JSON and form data
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (strpos($contentType, 'application/json') !== false) {
    // Handle JSON data
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON data']);
        exit;
    }
    $data = $input;
} else {
    // Handle form data
    $data = $_POST;
}

// Validate required fields
if (empty($data['name']) || empty($data['email'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Name and email are required']);
    exit;
}

// Validate email format
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email format']);
    exit;
}

// Handle file uploads
if (isset($_FILES['attachments'])) {
    $files = $_FILES['attachments'];
    $fileCount = is_array($files['name']) ? count($files['name']) : 1;

    for ($i = 0; $i < $fileCount; $i++) {
        $fileName = is_array($files['name']) ? $files['name'][$i] : $files['name'];
        $fileType = is_array($files['type']) ? $files['type'][$i] : $files['type'];
        $fileTmpName = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
        
        // Check file size (5MB limit)
        if (filesize($fileTmpName) > 5 * 1024 * 1024) {
            http_response_code(400);
            echo json_encode(['error' => "File $fileName is too large. Maximum size is 5MB"]);
            exit;
        }

        // Convert file to base64
        $content = file_get_contents($fileTmpName);
        $attachments[] = [
            'name' => $fileName,
            'content' => base64_encode($content)
        ];
    }
}
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
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
        }
    }
    return true;
}

// Load environment variables
loadEnv(__DIR__ . '/.env');

$api_key = $_ENV['BREVO_API_KEY'] ?? '';
$api_url = $_ENV['BREVO_API_URL'] ?? '';

// Debug: Check if environment variables are loaded
if (empty($api_key) || empty($api_url)) {
    error_log("Environment variables not loaded properly");
    http_response_code(500);
    echo json_encode(['error' => 'Server configuration error']);
    exit;
}

// Prepare email data with simpler structure
$htmlContent = '<html><body style="font-family: Arial, sans-serif;">
<h2 style="color: #e9bb24;">New Contact Form Submission</h2>
<p><strong>Name:</strong> ' . htmlspecialchars($data['name']) . '</p>
<p><strong>Email:</strong> ' . htmlspecialchars($data['email']) . '</p>
<p><strong>Phone:</strong> ' . htmlspecialchars($data['phone'] ?? 'Not provided') . '</p>
<p><strong>Message:</strong></p>
<p>' . nl2br(htmlspecialchars($data['message'] ?? 'No message provided')) . '</p>';

if (count($attachments) > 0) {
    $htmlContent .= '<p><strong>Attachments:</strong> ' . count($attachments) . ' file(s) attached</p>';
}

$htmlContent .= '</body></html>';

$email_data = [
    'sender' => [
        'name' => 'Light & Style',
        'email' => 'lightnstyle040@gmail.com'
    ],
    'to' => [
        [
            'email' => 'aisirink27@gmail.com',
            'name' => 'Light & Style'
        ]
    ],
    'replyTo' => [
        'email' => $data['email'],
        'name' => $data['name']
    ],
    'subject' => 'New Enquiry from ' . htmlspecialchars($data['name']),
    'htmlContent' => $htmlContent
];

// Add attachments if any (as separate property)
if (!empty($attachments)) {
    $email_data['attachment'] = $attachments;
}

// Log the data being sent
error_log("Form data received: " . print_r($data, true));
error_log("Email data being sent: " . print_r($email_data, true));

// Send request to Brevo API
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($email_data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/json',
    'api-key: ' . $api_key
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check for cURL errors before closing
if ($response === false) {
    $curl_error = curl_error($ch);
    curl_close($ch);
    http_response_code(500);
    echo json_encode(['error' => 'Connection error: ' . $curl_error]);
    exit;
}

curl_close($ch);

// Log the response
error_log("API Response: " . $response);
error_log("HTTP Code: " . $http_code);

// Parse and handle API response
if ($http_code >= 400) {
    $response_data = json_decode($response, true);
    if ($response_data && isset($response_data['message'])) {
        echo json_encode(['error' => 'API Error: ' . $response_data['message']]);
    } else {
        echo json_encode(['error' => 'Email sending failed with code: ' . $http_code]);
    }
    exit;
}

// Return success response
http_response_code($http_code);
echo $response;
?>
