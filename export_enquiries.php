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
        $allEnquiries = array_merge($allEnquiries, $logs);
    }
    
    // Sort by timestamp (newest first)
    usort($allEnquiries, function($a, $b) {
        return strtotime($b['timestamp']) - strtotime($a['timestamp']);
    });
    
    return $allEnquiries;
}

$allEnquiries = loadAllEnquiries();

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="enquiries_' . date('Y-m-d') . '.csv"');

// Create output stream
$output = fopen('php://output', 'w');

// Write CSV header
fputcsv($output, [
    'ID',
    'Date',
    'Name',
    'Email',
    'Phone',
    'Type',
    'Product Name',
    'Message',
    'Attachments Count',
    'Email Sent',
    'Email Error',
    'IP Address',
    'User Agent'
]);

// Write data rows
foreach ($allEnquiries as $enquiry) {
    fputcsv($output, [
        $enquiry['id'],
        $enquiry['timestamp'],
        $enquiry['name'],
        $enquiry['email'],
        $enquiry['phone'] ?? '',
        $enquiry['enquiry_type'] ?? 'general',
        $enquiry['product_name'] ?? '',
        $enquiry['message'] ?? '',
        $enquiry['attachments_count'] ?? 0,
        ($enquiry['email_sent'] ?? false) ? 'Yes' : 'No',
        $enquiry['email_error'] ?? '',
        $enquiry['ip_address'] ?? '',
        $enquiry['user_agent'] ?? ''
    ]);
}

fclose($output);
exit;
?>