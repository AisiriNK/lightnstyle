<?php
session_start();

// Load environment variables
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
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
}

// Load environment variables
loadEnv(__DIR__ . '/.env');

// Security configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
ini_set('session.use_strict_mode', 1);

// Get security settings from environment or use defaults
$max_attempts = (int)($_ENV['MAX_LOGIN_ATTEMPTS'] ?? 5);
$lockout_time = (int)($_ENV['LOCKOUT_TIME'] ?? 900); // 15 minutes

// Initialize session variables for security
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = 0;
}

// Check if account is locked
$time_since_last_attempt = time() - $_SESSION['last_attempt_time'];
if ($_SESSION['login_attempts'] >= $max_attempts && $time_since_last_attempt < $lockout_time) {
    $remaining_time = $lockout_time - $time_since_last_attempt;
    $error = "Too many failed attempts. Account locked for " . ceil($remaining_time / 60) . " more minutes.";
} else {
    // Reset attempts if lockout period has passed
    if ($time_since_last_attempt >= $lockout_time) {
        $_SESSION['login_attempts'] = 0;
    }
}

// Load admin credentials from environment variables
$admin_username = $_ENV['ADMIN_USERNAME'] ?? 'admin';
$admin_password = $_ENV['ADMIN_PASSWORD'] ?? 'lightstyle2024!';

$admin_credentials = [
    $admin_username => $admin_password
];

// Load custom credentials if they exist (for backward compatibility)
$config_file = __DIR__ . '/config/admin_config.php';
if (file_exists($config_file)) {
    include $config_file;
    if (isset($custom_admin_credentials)) {
        $admin_credentials = array_merge($admin_credentials, $custom_admin_credentials);
    }
}

// Handle login
if ($_POST['action'] ?? '' === 'login' && !isset($error)) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Basic input validation
    if (empty($username) || empty($password)) {
        $error = 'Username and password are required';
        $_SESSION['login_attempts']++;
        $_SESSION['last_attempt_time'] = time();
    } else {
        // Check credentials
        if (isset($admin_credentials[$username]) && $admin_credentials[$username] === $password) {
            // Successful login
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            $_SESSION['login_time'] = time();
            $_SESSION['login_attempts'] = 0; // Reset attempts
            
            // Regenerate session ID for security
            session_regenerate_id(true);
            
            header('Location: admin_enquiries.php');
            exit;
        } else {
            $error = 'Invalid credentials';
            $_SESSION['login_attempts']++;
            $_SESSION['last_attempt_time'] = time();
            
            // Add delay to prevent brute force attacks
            sleep(2);
        }
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
            <strong>⚠️ Security Notice:</strong> If this is your first login, use the default credentials and change them immediately using the password reset script.
        </div>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['timeout'])): ?>
            <div class="error">Session expired. Please login again.</div>
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
    </div>
</body>
</html>