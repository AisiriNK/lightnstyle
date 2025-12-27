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
        
        // Skip empty files
        if (empty($fileName) || empty($fileTmpName)) {
            continue;
        }
        
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
            'content' => base64_encode($content),
            'type' => $fileType
        ];
        
        error_log("DEBUG: Added attachment - Name: $fileName, Type: $fileType, Size: " . strlen($content) . " bytes");
    }
}

// Also check for attachments[] format (from JavaScript)
if (isset($_FILES['attachments'])) {
    // Already handled above
} else {
    // Check for attachments[] format
    foreach ($_FILES as $key => $file) {
        if (strpos($key, 'attachments') === 0) {
            if (is_array($file['name'])) {
                // Multiple files
                for ($i = 0; $i < count($file['name']); $i++) {
                    if (empty($file['name'][$i]) || empty($file['tmp_name'][$i])) {
                        continue;
                    }
                    
                    if (filesize($file['tmp_name'][$i]) > 5 * 1024 * 1024) {
                        http_response_code(400);
                        echo json_encode(['error' => "File {$file['name'][$i]} is too large. Maximum size is 5MB"]);
                        exit;
                    }
                    
                    $content = file_get_contents($file['tmp_name'][$i]);
                    $attachments[] = [
                        'name' => $file['name'][$i],
                        'content' => base64_encode($content),
                        'type' => $file['type'][$i]
                    ];
                    
                    error_log("DEBUG: Added attachment (array) - Name: {$file['name'][$i]}, Type: {$file['type'][$i]}, Size: " . strlen($content) . " bytes");
                }
            } else {
                // Single file
                if (!empty($file['name']) && !empty($file['tmp_name'])) {
                    if (filesize($file['tmp_name']) > 5 * 1024 * 1024) {
                        http_response_code(400);
                        echo json_encode(['error' => "File {$file['name']} is too large. Maximum size is 5MB"]);
                        exit;
                    }
                    
                    $content = file_get_contents($file['tmp_name']);
                    $attachments[] = [
                        'name' => $file['name'],
                        'content' => base64_encode($content),
                        'type' => $file['type']
                    ];
                    
                    error_log("DEBUG: Added attachment (single) - Name: {$file['name']}, Type: {$file['type']}, Size: " . strlen($content) . " bytes");
                }
            }
        }
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

// Load environment variables: prefer one directory above public_html, fallback to current dir
$parentEnv = dirname(__DIR__) . '/.env';
if (loadEnv($parentEnv)) {
    error_log("DEBUG: Loaded .env from parent directory: " . $parentEnv);
} elseif (loadEnv(__DIR__ . '/.env')) {
    error_log("DEBUG: Loaded .env from current directory: " . __DIR__ . '/.env');
} else {
    error_log("DEBUG: .env not found in parent or current directory: " . $parentEnv);
}

$api_key = $_ENV['BREVO_API_KEY'] ?? '';
$api_url = $_ENV['BREVO_API_URL'] ?? 'https://api.brevo.com/v3/smtp/email';

// Debug: Log the API configuration
error_log("DEBUG: API Key loaded: " . (empty($api_key) ? 'EMPTY' : 'SET (length: ' . strlen($api_key) . ')'));
error_log("DEBUG: API URL loaded: " . (empty($api_url) ? 'EMPTY' : $api_url));

// Function to log enquiry to file
function logEnquiry($data, $attachments, $enquiryType = 'general', $productName = null) {
    try {
        $logsDir = __DIR__ . '/logs';
        if (!is_dir($logsDir)) {
            mkdir($logsDir, 0755, true);
        }
        
        $enquiryData = [
            'id' => uniqid('ENQ_'),
            'timestamp' => date('Y-m-d H:i:s'),
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'message' => $data['message'] ?? null,
            'product_name' => $productName,
            'enquiry_type' => $enquiryType,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'attachments_count' => count($attachments),
            'email_sent' => false,
            'email_error' => null
        ];
        
        error_log("DEBUG: Enquiry data prepared with ID: " . $enquiryData['id']);
        
        // Save attachment details separately (without the base64 content to save space)
        if (!empty($attachments)) {
            $attachmentInfo = [];
            foreach ($attachments as $attachment) {
                $attachmentInfo[] = [
                    'name' => $attachment['name'],
                    'type' => $attachment['type'] ?? 'unknown',
                    'size' => strlen(base64_decode($attachment['content'] ?? ''))
                ];
            }
            
            $attachmentFile = $logsDir . '/attachments_' . $enquiryData['id'] . '.json';
            file_put_contents($attachmentFile, json_encode($attachmentInfo, JSON_PRETTY_PRINT));
            error_log("DEBUG: Saved attachment details to: " . $attachmentFile);
        }
        
        // Save to daily log file
        $logFile = $logsDir . '/enquiries_' . date('Y-m-d') . '.json';
        error_log("DEBUG: Log file path: " . $logFile);
        
        $existingLogs = [];
        
        if (file_exists($logFile)) {
            $existingLogs = json_decode(file_get_contents($logFile), true) ?: [];
            error_log("DEBUG: Loaded " . count($existingLogs) . " existing logs");
        }
        
        $existingLogs[] = $enquiryData;
        
        $jsonData = json_encode($existingLogs, JSON_PRETTY_PRINT);
        if ($jsonData === false) {
            error_log("DEBUG: JSON encoding failed: " . json_last_error_msg());
            return false;
        }
        
        $result = file_put_contents($logFile, $jsonData);
        if ($result === false) {
            error_log("DEBUG: Failed to write to log file");
            return false;
        }
        
        error_log("DEBUG: Successfully logged enquiry with ID: " . $enquiryData['id']);
        return $enquiryData['id'];
        
    } catch (Exception $e) {
        error_log("DEBUG: Exception in logEnquiry: " . $e->getMessage());
        return false;
    }
}

// Function to update email status
function updateEmailStatus($enquiryId, $emailSent, $error = null) {
    try {
        $logsDir = __DIR__ . '/logs';
        
        // Search through recent log files to find the enquiry
        $logFiles = glob($logsDir . '/enquiries_*.json');
        
        foreach ($logFiles as $logFile) {
            $logs = json_decode(file_get_contents($logFile), true) ?: [];
            
            foreach ($logs as &$log) {
                if ($log['id'] === $enquiryId) {
                    $log['email_sent'] = $emailSent;
                    $log['email_error'] = $error;
                    $log['updated_at'] = date('Y-m-d H:i:s');
                    
                    file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT));
                    return true;
                }
            }
        }
        
        return false;
    } catch (Exception $e) {
        error_log("Failed to update email status: " . $e->getMessage());
        return false;
    }
}

// Extract product name and enquiry type from message
$productName = $data['product_name'] ?? null;
$enquiryType = strpos($data['message'] ?? '', 'Product Enquiry for:') !== false ? 'product' : 'general';

error_log("DEBUG: About to log enquiry - Product: " . ($productName ?? 'none') . ", Type: " . $enquiryType);
error_log("DEBUG: Form data: " . print_r($data, true));
error_log("DEBUG: Attachments count: " . count($attachments));

// Log the enquiry to database FIRST (before attempting to send email)
$enquiryId = logEnquiry($data, $attachments, $enquiryType, $productName);

if (!$enquiryId) {
    error_log("WARNING: Failed to log enquiry to file, but continuing with email send");
} else {
    error_log("DEBUG: Enquiry logged successfully with ID: " . $enquiryId);
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
    error_log("DEBUG: Adding " . count($attachments) . " attachments to email");
    foreach ($attachments as $i => $attachment) {
        error_log("DEBUG: Attachment $i - Name: {$attachment['name']}, Type: {$attachment['type']}, Content size: " . strlen($attachment['content']));
    }
}

// Log the data being sent
error_log("Form data received: " . print_r($data, true));
error_log("Email data being sent: " . print_r($email_data, true));

// Validate API configuration before making the request
if (empty($api_key)) {
    http_response_code(500);
    echo json_encode(['error' => 'API key not configured']);
    exit;
}

if (empty($api_url)) {
    http_response_code(500);
    echo json_encode(['error' => 'API URL not configured']);
    exit;
}

// Send request to Brevo API
$ch = curl_init($api_url);

// Check if curl_init failed
if ($ch === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Connection error: Failed to initialize cURL with URL: ' . $api_url]);
    exit;
}
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
    $errorMessage = $response_data['message'] ?? 'Email sending failed with code: ' . $http_code;
    
    // Update database with failure status
    if ($enquiryId) {
        updateEmailStatus($enquiryId, false, $errorMessage);
    }
    
    echo json_encode(['error' => 'API Error: ' . $errorMessage]);
    exit;
}

// Email sent successfully - update database
if ($enquiryId) {
    updateEmailStatus($enquiryId, true);
}

// Return success response
http_response_code($http_code);
echo $response;
?>
