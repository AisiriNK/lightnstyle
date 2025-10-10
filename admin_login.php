<?php
session_start();

// Simple admin credentials (you should change these)
$admin_username = 'admin';
$admin_password = 'lightstyle2024!'; // Change this password!

// Handle login
if ($_POST['action'] ?? '' === 'login') {
    if ($_POST['username'] === $admin_username && $_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin_enquiries.php');
        exit;
    } else {
        $error = 'Invalid credentials';
    }
}

// Check if already logged in
if ($_SESSION['admin_logged_in'] ?? false) {
    header('Location: admin_enquiries.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Light & Style</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #111;
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-container {
            background: #222;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 400px;
            border: 1px solid #e9bb24;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo h1 {
            color: #e9bb24;
            font-family: 'Playfair Display', serif;
            margin: 0;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #ccc;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #444;
            border-radius: 5px;
            background: #333;
            color: #fff;
            font-size: 16px;
            box-sizing: border-box;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: #e9bb24;
            box-shadow: 0 0 10px rgba(233, 187, 36, 0.3);
        }
        .btn {
            width: 100%;
            background: #e9bb24;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #d4a017;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .warning {
            background: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>🔐 Admin Login</h1>
            <p style="color: #ccc; margin: 0;">Light & Style Enquiries</p>
        </div>

        <div class="warning">
            <strong>⚠️ Security Notice:</strong> Please change the default admin password in <code>admin_login.php</code>
        </div>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="action" value="login">
            
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn">Login to Admin Panel</button>
        </form>

        <div style="margin-top: 20px; text-align: center; color: #666; font-size: 12px;">
            <p>Default credentials: admin / lightstyle2024!</p>
            <p><strong>Change these immediately!</strong></p>
        </div>
    </div>
</body>
</html>