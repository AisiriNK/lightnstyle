# Security Implementation - Light & Style Admin Panel

## Overview
The admin panel has been secured with multiple layers of protection to prevent unauthorized access and protect sensitive data.

## Security Features Implemented

### 1. Password Security
- **Hashed Passwords**: All passwords are hashed using PHP's `password_hash()` function with bcrypt
- **No Hardcoded Credentials**: Passwords are stored in a separate config file, not in source code
- **Secure Password Reset**: Command-line script for changing passwords securely

### 2. Session Security
- **Session Timeout**: Automatic logout after 30 minutes of inactivity
- **Session Regeneration**: Session ID is regenerated on login to prevent fixation attacks
- **Secure Session Configuration**: HttpOnly, Secure, and Strict mode enabled

### 3. Brute Force Protection
- **Rate Limiting**: Maximum 5 login attempts before account lockout
- **Lockout Duration**: 15-minute lockout period after failed attempts
- **Login Delay**: 2-second delay after failed login attempts

### 4. File Protection
- **Protected Directories**: Config and logs directories protected via .htaccess
- **Sensitive File Blocking**: Direct access to config files, logs, and scripts blocked
- **Backup File Protection**: Protection against access to backup and temporary files

### 5. Security Headers
- **XSS Protection**: X-XSS-Protection header enabled
- **Content Type Protection**: X-Content-Type-Options set to nosniff
- **Clickjacking Protection**: X-Frame-Options set to DENY
- **Referrer Policy**: Strict referrer policy implemented

## Files and Structure

### Core Security Files
- `admin_login.php` - Secure login with hashing and rate limiting
- `admin_enquiries.php` - Protected admin panel with session timeout
- `reset_admin_password.php` - Command-line password reset utility
- `config/admin_config.php` - Encrypted password storage (auto-generated)
- `.htaccess` - Web server security configuration

### Default Credentials
- **Username**: admin
- **Password**: lightstyle2024! (hashed)

## Setup Instructions

### Initial Setup
1. Access the admin panel at `/admin_login.php`
2. Use default credentials to login initially
3. Run password reset script to change default password:
   ```bash
   php reset_admin_password.php
   ```

### Changing Passwords
1. Navigate to the project directory
2. Run the password reset script:
   ```bash
   php reset_admin_password.php
   ```
3. Follow the prompts to set new credentials

### File Permissions
Ensure proper file permissions are set:
```bash
chmod 600 config/admin_config.php
chmod 755 logs/
chmod 644 logs/*.json
```

## Security Best Practices

### Server Configuration
1. **Use HTTPS**: Enable SSL/TLS in production
2. **Update Security Settings**: Set `session.cookie_secure = 1` when using HTTPS
3. **Regular Updates**: Keep PHP and web server updated
4. **Backup Security**: Ensure backups are encrypted and secured

### Monitoring
1. **Log Monitoring**: Regularly check login attempt logs
2. **Failed Login Alerts**: Monitor for suspicious login patterns
3. **Session Monitoring**: Watch for unusual session activity

### Maintenance
1. **Regular Password Changes**: Change admin passwords regularly
2. **Remove Unused Files**: Delete old backup files and unused scripts
3. **Security Updates**: Apply security patches promptly

## Emergency Access

### If Locked Out
1. Check server error logs for issues
2. Use the password reset script from command line
3. Verify file permissions on config directory
4. Clear browser cache and cookies

### Forgot Password
1. Access server via SSH/FTP
2. Run: `php reset_admin_password.php`
3. Set new credentials when prompted

## Security Warnings

⚠️ **Important Security Notes**:
- Never commit the `config/admin_config.php` file to version control
- Always use HTTPS in production environments
- Regularly monitor access logs for suspicious activity
- Keep the password reset script secure or delete after use
- Ensure the web server doesn't expose sensitive files

## Contact
For security issues or questions, contact the system administrator.

---
Last Updated: October 10, 2025
Security Level: Enhanced