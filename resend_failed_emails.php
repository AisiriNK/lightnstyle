<?php
session_start();

// Check if admin is logged in
if (!($_SESSION['admin_logged_in'] ?? false)) {
    header('Location: admin_login.php');
    exit;
}

// Function to load all enquiries from files
function loadAllEnquiries() {
    $logsDir = __DIR__ . '/logs';
    $allEnquiries = [];
    
    if (!is_dir($logsDir)) {
        return $allEnquiries;
    }
    
    $logFiles = glob($logsDir . '/enquiries_*.json');
    
    foreach ($logFiles as $logFile) {
        $logs = json_decode(file_get_contents($logFile), true) ?: [];
        foreach ($logs as $log) {
            $log['_source_file'] = $logFile; // Keep track of source file
            $allEnquiries[] = $log;
        }
    }
    
    return $allEnquiries;
}

// Function to update enquiry status
function updateEnquiryStatus($enquiryId, $emailSent, $error = null) {
    $logsDir = __DIR__ . '/logs';
    $logFiles = glob($logsDir . '/enquiries_*.json');
    
    foreach ($logFiles as $logFile) {
        $logs = json_decode(file_get_contents($logFile), true) ?: [];
        $updated = false;
        
        foreach ($logs as &$log) {
            if ($log['id'] === $enquiryId) {
                $log['email_sent'] = $emailSent;
                $log['email_error'] = $error;
                $log['updated_at'] = date('Y-m-d H:i:s');
                $updated = true;
                break;
            }
        }
        
        if ($updated) {
            file_put_contents($logFile, json_encode($logs, JSON_PRETTY_PRINT));
            return true;
        }
    }
    
    return false;
}

// Function to resend a single email
function resendEmail($enquiry) {
    // Load environment variables
    function loadEnv($file) {
        if (!file_exists($file)) {
            return false;
        }
        
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
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

    loadEnv(__DIR__ . '/.env');
    
    $api_key = $_ENV['BREVO_API_KEY'] ?? '';
    $api_url = $_ENV['BREVO_API_URL'] ?? '';
    
    if (empty($api_key) || empty($api_url)) {
        return ['success' => false, 'error' => 'API configuration missing'];
    }

    // Prepare email content
    $htmlContent = '<html><body style="font-family: Arial, sans-serif;">
    <h2 style="color: #e9bb24;">New Contact Form Submission</h2>
    <p><strong>Name:</strong> ' . htmlspecialchars($enquiry['name']) . '</p>
    <p><strong>Email:</strong> ' . htmlspecialchars($enquiry['email']) . '</p>
    <p><strong>Phone:</strong> ' . htmlspecialchars($enquiry['phone'] ?? 'Not provided') . '</p>
    <p><strong>Type:</strong> ' . ucfirst($enquiry['enquiry_type']) . '</p>';
    
    if ($enquiry['product_name']) {
        $htmlContent .= '<p><strong>Product:</strong> ' . htmlspecialchars($enquiry['product_name']) . '</p>';
    }
    
    $htmlContent .= '<p><strong>Message:</strong></p>
    <p>' . nl2br(htmlspecialchars($enquiry['message'] ?? 'No message provided')) . '</p>';
    
    if ($enquiry['attachments_count'] > 0) {
        $htmlContent .= '<p><strong>Attachments:</strong> ' . $enquiry['attachments_count'] . ' file(s) were attached</p>';
    }
    
    $htmlContent .= '<p><em>This is a resend of enquiry #' . $enquiry['id'] . ' from ' . $enquiry['created_at'] . '</em></p>
    </body></html>';

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
            'email' => $enquiry['email'],
            'name' => $enquiry['name']
        ],
        'subject' => '[RESEND] New Enquiry from ' . htmlspecialchars($enquiry['name']),
        'htmlContent' => $htmlContent
    ];

    // Send email
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

    if ($http_code >= 200 && $http_code < 300) {
        return ['success' => true];
    } else {
        $response_data = json_decode($response, true);
        return ['success' => false, 'error' => $response_data['message'] ?? 'Unknown error'];
    }
}

// Handle form submission
if ($_POST['action'] ?? '' === 'resend_all') {
    $allEnquiries = loadAllEnquiries();
    $failedEnquiries = array_filter($allEnquiries, function($e) { 
        return !($e['email_sent'] ?? false); 
    });
    $failedEnquiries = array_slice($failedEnquiries, 0, 50); // Limit to 50
    
    $results = ['success' => 0, 'failed' => 0, 'errors' => []];
    
    foreach ($failedEnquiries as $enquiry) {
        $result = resendEmail($enquiry);
        
        if ($result['success']) {
            updateEnquiryStatus($enquiry['id'], true);
            $results['success']++;
        } else {
            updateEnquiryStatus($enquiry['id'], false, $result['error']);
            $results['failed']++;
            $results['errors'][] = "ID {$enquiry['id']}: {$result['error']}";
        }
        
        // Add small delay to avoid rate limiting
        usleep(100000); // 0.1 second
    }
}

// Handle single resend
if ($_POST['action'] ?? '' === 'resend_single' && !empty($_POST['id'])) {
    $allEnquiries = loadAllEnquiries();
    $enquiry = null;
    
    foreach ($allEnquiries as $e) {
        if ($e['id'] === $_POST['id'] && !($e['email_sent'] ?? false)) {
            $enquiry = $e;
            break;
        }
    }
    
    if ($enquiry) {
        $result = resendEmail($enquiry);
        
        if ($result['success']) {
            updateEnquiryStatus($enquiry['id'], true);
            $singleResult = ['success' => true, 'message' => 'Email resent successfully'];
        } else {
            updateEnquiryStatus($enquiry['id'], false, $result['error']);
            $singleResult = ['success' => false, 'message' => $result['error']];
        }
    } else {
        $singleResult = ['success' => false, 'message' => 'Enquiry not found or already sent'];
    }
}

// Get failed enquiries for display
$allEnquiries = loadAllEnquiries();
$failedEnquiries = array_filter($allEnquiries, function($e) { 
    return !($e['email_sent'] ?? false); 
});
$failedEnquiries = array_slice($failedEnquiries, 0, 50);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resend Failed Emails - Light & Style</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5; }
        .header { background: #000; color: #e9bb24; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .alert { padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .btn { background: #e9bb24; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin: 5px; }
        .btn:hover { background: #d4a017; }
        .btn-sm { padding: 5px 10px; font-size: 0.9em; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #e9bb24; color: white; }
        .error-message { max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    </style>
</head>
<body>
    <div class="header">
        <h1>📧 Resend Failed Emails</h1>
        <p>Manage and resend emails that failed to deliver</p>
        <a href="admin_enquiries.php" style="color: #e9bb24; text-decoration: none;">← Back to Admin Panel</a>
    </div>

    <?php if (isset($results)): ?>
        <div class="alert alert-<?php echo $results['failed'] === 0 ? 'success' : 'danger'; ?>">
            <h3>Bulk Resend Results</h3>
            <p>Successfully sent: <?php echo $results['success']; ?></p>
            <p>Failed: <?php echo $results['failed']; ?></p>
            <?php if (!empty($results['errors'])): ?>
                <details>
                    <summary>Error Details</summary>
                    <ul>
                        <?php foreach ($results['errors'] as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </details>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($singleResult)): ?>
        <div class="alert alert-<?php echo $singleResult['success'] ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($singleResult['message']); ?>
        </div>
    <?php endif; ?>

    <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h3>Failed Emails (<?php echo count($failedEnquiries); ?>)</h3>
        
        <?php if (count($failedEnquiries) > 0): ?>
            <form method="POST" style="margin-bottom: 20px;">
                <input type="hidden" name="action" value="resend_all">
                <button type="submit" class="btn" onclick="return confirm('Are you sure you want to resend all failed emails?')">
                    📤 Resend All Failed Emails
                </button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Type</th>
                        <th>Error</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($failedEnquiries as $enquiry): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($enquiry['id']); ?></td>
                            <td><?php echo date('M j, Y H:i', strtotime($enquiry['timestamp'])); ?></td>
                            <td><?php echo htmlspecialchars($enquiry['name']); ?></td>
                            <td><?php echo htmlspecialchars($enquiry['email']); ?></td>
                            <td><?php echo ucfirst($enquiry['enquiry_type'] ?? 'general'); ?></td>
                            <td class="error-message" title="<?php echo htmlspecialchars($enquiry['email_error'] ?? 'Unknown error'); ?>">
                                <?php echo htmlspecialchars(substr($enquiry['email_error'] ?? 'Unknown error', 0, 50)); ?>
                            </td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="resend_single">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($enquiry['id']); ?>">
                                    <button type="submit" class="btn btn-sm">Resend</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-success">
                <p>🎉 Great! No failed emails found. All enquiries have been successfully delivered.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>