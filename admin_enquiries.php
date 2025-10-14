<?php
session_start();

// Check if admin is logged in
if (!($_SESSION['admin_logged_in'] ?? false)) {
    header('Location: admin_login.php');
    exit;
}

// Session timeout (30 minutes)
$session_timeout = 1800;
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > $session_timeout) {
    session_destroy();
    header('Location: admin_login.php?timeout=1');
    exit;
}

// Update last activity time
$_SESSION['login_time'] = time();

// Handle logout
if ($_GET['action'] ?? '' === 'logout') {
    session_destroy();
    header('Location: admin_login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enquiries Admin Panel - Light & Style</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .header {
            background: #000;
            color: #e9bb24;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #e9bb24;
        }
        .enquiries-table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9em;
        }
        th, td {
            padding: 12px 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
        }
        th {
            background: #e9bb24;
            color: white;
            font-weight: bold;
        }
        .status-sent {
            background: #d4edda;
            color: #155724;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9em;
        }
        .status-failed {
            background: #f8d7da;
            color: #721c24;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.9em;
        }
        .message-preview {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .attachment-list {
            max-width: 150px;
            font-size: 0.9em;
        }
        .attachment-item {
            background: #f8f9fa;
            padding: 2px 6px;
            margin: 2px 0;
            border-radius: 3px;
            border: 1px solid #dee2e6;
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .attachment-count {
            background: #e9bb24;
            color: white;
            padding: 2px 6px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: bold;
        }
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .filter-section input, .filter-section select {
            padding: 8px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            background: #e9bb24;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background: #d4a017;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📧 Enquiries Admin Panel</h1>
        <p>Track all customer enquiries and email delivery status</p>
        <div style="float: right;">
            <a href="?action=logout" style="color: #e9bb24; text-decoration: none; padding: 8px 16px; border: 1px solid #e9bb24; border-radius: 4px;">🚪 Logout</a>
        </div>
        <div style="clear: both;"></div>
    </div>

    <?php
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

    // Function to get attachment details for an enquiry
    function getAttachmentDetails($enquiryId) {
        $logsDir = __DIR__ . '/logs';
        $attachmentFile = $logsDir . '/attachments_' . $enquiryId . '.json';
        
        if (file_exists($attachmentFile)) {
            return json_decode(file_get_contents($attachmentFile), true) ?: [];
        }
        
        return [];
    }

    $allEnquiries = loadAllEnquiries();
    
    // Calculate statistics
    $totalEnquiries = count($allEnquiries);
    $emailsSent = count(array_filter($allEnquiries, function($e) { return $e['email_sent'] ?? false; }));
    $emailsFailed = count(array_filter($allEnquiries, function($e) { return !($e['email_sent'] ?? false); }));
    $productEnquiries = count(array_filter($allEnquiries, function($e) { return ($e['enquiry_type'] ?? '') === 'product'; }));
    $todayEnquiries = count(array_filter($allEnquiries, function($e) { 
        return date('Y-m-d', strtotime($e['timestamp'])) === date('Y-m-d'); 
    }));

    // Apply filters
    $filteredEnquiries = $allEnquiries;
    
    if (!empty($_GET['search'])) {
        $search = strtolower($_GET['search']);
        $filteredEnquiries = array_filter($filteredEnquiries, function($e) use ($search) {
            return strpos(strtolower($e['name']), $search) !== false || 
                   strpos(strtolower($e['email']), $search) !== false;
        });
    }
    
    if (!empty($_GET['status'])) {
        if ($_GET['status'] === 'sent') {
            $filteredEnquiries = array_filter($filteredEnquiries, function($e) { return $e['email_sent'] ?? false; });
        } elseif ($_GET['status'] === 'failed') {
            $filteredEnquiries = array_filter($filteredEnquiries, function($e) { return !($e['email_sent'] ?? false); });
        }
    }
    
    if (!empty($_GET['type'])) {
        $filteredEnquiries = array_filter($filteredEnquiries, function($e) {
            return ($e['enquiry_type'] ?? '') === $_GET['type'];
        });
    }
    
    if (!empty($_GET['attachments'])) {
        if ($_GET['attachments'] === 'yes') {
            $filteredEnquiries = array_filter($filteredEnquiries, function($e) { return ($e['attachments_count'] ?? 0) > 0; });
        } elseif ($_GET['attachments'] === 'no') {
            $filteredEnquiries = array_filter($filteredEnquiries, function($e) { return ($e['attachments_count'] ?? 0) == 0; });
        }
    }
    
    // Limit to 100 results
    $filteredEnquiries = array_slice($filteredEnquiries, 0, 100);
    ?>

    <div class="stats">
        <div class="stat-card">
            <div class="stat-number"><?php echo $totalEnquiries; ?></div>
            <div>Total Enquiries</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $emailsSent; ?></div>
            <div>Emails Sent</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $emailsFailed; ?></div>
            <div>Emails Failed</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $productEnquiries; ?></div>
            <div>Product Enquiries</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo $todayEnquiries; ?></div>
            <div>Today's Enquiries</div>
        </div>
    </div>

    <div class="filter-section">
        <form method="GET">
            <input type="text" name="search" placeholder="Search by name or email" value="<?php echo $_GET['search'] ?? ''; ?>">
            <select name="status">
                <option value="">All Status</option>
                <option value="sent" <?php echo ($_GET['status'] ?? '') === 'sent' ? 'selected' : ''; ?>>Email Sent</option>
                <option value="failed" <?php echo ($_GET['status'] ?? '') === 'failed' ? 'selected' : ''; ?>>Email Failed</option>
            </select>
            <select name="type">
                <option value="">All Types</option>
                <option value="general" <?php echo ($_GET['type'] ?? '') === 'general' ? 'selected' : ''; ?>>General</option>
                <option value="product" <?php echo ($_GET['type'] ?? '') === 'product' ? 'selected' : ''; ?>>Product</option>
            </select>
            <select name="attachments">
                <option value="">All Attachments</option>
                <option value="yes" <?php echo ($_GET['attachments'] ?? '') === 'yes' ? 'selected' : ''; ?>>With Attachments</option>
                <option value="no" <?php echo ($_GET['attachments'] ?? '') === 'no' ? 'selected' : ''; ?>>No Attachments</option>
            </select>
            <button type="submit" class="btn">Filter</button>
            <a href="?" class="btn" style="text-decoration: none; margin-left: 10px;">Clear</a>
        </form>
    </div>

    <div class="enquiries-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Type</th>
                    <th>Product</th>
                    <th>Message</th>
                    <th>Attachments</th>
                    <th>Email Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($filteredEnquiries as $enquiry): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($enquiry['id']); ?></td>
                        <td><?php echo date('M j, Y H:i', strtotime($enquiry['timestamp'])); ?></td>
                        <td><?php echo htmlspecialchars($enquiry['name']); ?></td>
                        <td><?php echo htmlspecialchars($enquiry['email']); ?></td>
                        <td><?php echo htmlspecialchars($enquiry['phone'] ?? 'N/A'); ?></td>
                        <td><?php echo ucfirst($enquiry['enquiry_type'] ?? 'general'); ?></td>
                        <td><?php echo htmlspecialchars($enquiry['product_name'] ?? 'N/A'); ?></td>
                        <td class="message-preview" title="<?php echo htmlspecialchars($enquiry['message'] ?? ''); ?>">
                            <?php echo htmlspecialchars(substr($enquiry['message'] ?? '', 0, 50)) . (strlen($enquiry['message'] ?? '') > 50 ? '...' : ''); ?>
                        </td>
                        <td class="attachment-list">
                            <?php 
                            $attachmentCount = $enquiry['attachments_count'] ?? 0;
                            if ($attachmentCount > 0): 
                                $attachmentDetails = getAttachmentDetails($enquiry['id']);
                            ?>
                                <span class="attachment-count"><?php echo $attachmentCount; ?> file(s)</span>
                                <?php if (!empty($attachmentDetails)): ?>
                                    <div style="margin-top: 5px;">
                                        <?php foreach ($attachmentDetails as $attachment): ?>
                                            <?php 
                                            $fileType = $attachment['type'] ?? 'unknown';
                                            $icon = '📎'; // Default icon
                                            if (strpos($fileType, 'image/') === 0) $icon = '🖼️';
                                            elseif (strpos($fileType, 'text/') === 0) $icon = '📄';
                                            elseif (strpos($fileType, 'application/pdf') === 0) $icon = '📄';
                                            elseif (strpos($fileType, 'application/zip') === 0) $icon = '🗜️';
                                            ?>
                                            <div class="attachment-item" title="<?php echo htmlspecialchars($attachment['name'] ?? 'Unknown file'); ?> (<?php echo htmlspecialchars($attachment['type'] ?? 'Unknown type'); ?>) - <?php echo round(($attachment['size'] ?? 0) / 1024, 1); ?>KB">
                                                <?php echo $icon; ?> <?php echo htmlspecialchars(substr($attachment['name'] ?? 'Unknown', 0, 15)); ?>
                                                <?php if (strlen($attachment['name'] ?? '') > 15) echo '...'; ?>
                                                <small style="color: #666;">(<?php echo round(($attachment['size'] ?? 0) / 1024, 1); ?>KB)</small>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div style="margin-top: 5px; font-size: 0.8em; color: #666;">
                                        <?php echo $attachmentCount; ?> file(s) - details not available
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span style="color: #666; font-size: 0.9em;">No attachments</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($enquiry['email_sent'] ?? false): ?>
                                <span class="status-sent">✓ Sent</span>
                            <?php else: ?>
                                <span class="status-failed" title="<?php echo htmlspecialchars($enquiry['email_error'] ?? 'Unknown error'); ?>">✗ Failed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h3>📊 Quick Actions</h3>
        <a href="?status=failed" class="btn" style="text-decoration: none; margin-right: 10px;">View Failed Emails</a>
        <a href="resend_failed_emails.php" class="btn" style="text-decoration: none; margin-right: 10px;">Resend Failed Emails</a>
        <a href="export_enquiries.php" class="btn" style="text-decoration: none;">Export to CSV</a>
    </div>
</body>
</html>