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
$env = parse_ini_file(__DIR__ . '/.env'); // parses KEY=VALUE pairs
$api_key = $env['BREVO_API_KEY'];

// Brevo API configuration
$api_url = $env['BREVO_API_URL'];

// Prepare email data
$email_data = [
    'sender' => [
        'name' => 'Light N Style',
        'email' => 'lightnstyle040@gmail.com'
    ],
    'to' => [
        [
            'email' => 'aisirink27@gmail.com',
            'name' => 'Light N Style'
        ]
    ],
    'replyTo' => [
        'email' => $_POST['email'] ?? '',
        'name' => $_POST['name'] ?? ''
    ],
    'subject' => 'New Enquiry from ' . $data['name'],
    'templateId' => 1,
    'params' => [
        'name' => $_POST['name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'phone' => $_POST['phone'] ?? '',
        'message' => $_POST['message'] ?? '',
        'attachmentsCount' => count($attachments)

    ]
];

// Add attachments if any
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
curl_close($ch);

// Return response to client
http_response_code($http_code);
echo $response;
?>
