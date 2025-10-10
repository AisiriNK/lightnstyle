# 📧 File-Based Enquiry Logging System

This system logs all enquiry form submissions to local files and provides an admin panel to manage them.

## 🔧 How It Works

### 1. **Automatic Logging**
- Every time someone submits the contact form or product enquiry, it's automatically logged to `logs/enquiries_YYYY-MM-DD.json`
- Each enquiry gets a unique ID and tracks email delivery status
- No database required - everything is stored in JSON files

### 2. **Admin Panel Access**
- Visit: `your-domain.com/admin_login.php`
- **Default credentials:** 
  - Username: `admin`
  - Password: `lightstyle2024!`
- **⚠️ IMPORTANT: Change these credentials immediately in `admin_login.php`**

### 3. **Admin Features**
- **View All Enquiries:** `admin_enquiries.php`
  - Search by name/email
  - Filter by status (sent/failed) and type (general/product)
  - View statistics dashboard
  
- **Resend Failed Emails:** `resend_failed_emails.php`
  - Automatically retry failed email deliveries
  - Bulk resend or individual resend options
  
- **Export Data:** `export_enquiries.php`
  - Download all enquiries as CSV file
  - Includes all enquiry details and email status

## 📁 File Structure
```
/logs/
  ├── enquiries_2024-10-10.json  # Daily log files
  ├── enquiries_2024-10-11.json
  └── .htaccess                   # Protects log files from web access

/admin_login.php       # Admin login page
/admin_enquiries.php   # Main admin dashboard
/resend_failed_emails.php  # Failed email management
/export_enquiries.php  # CSV export
```

## 🛡️ Security Features
- Admin pages require login authentication
- Log files are protected by `.htaccess`
- Session-based authentication
- Input sanitization and validation

## 📊 What Gets Logged
- Unique enquiry ID
- Timestamp
- Customer details (name, email, phone)
- Message content
- Product name (for product enquiries)
- Enquiry type (general/product)
- Number of attachments
- Email delivery status
- Error messages (if email failed)
- IP address and user agent

## 🔄 Email Backup System
If an email fails to send:
1. The enquiry is still logged with `email_sent: false`
2. Error details are stored
3. Admin can resend failed emails anytime
4. No enquiries are ever lost!

## 🚀 Getting Started
1. Change admin credentials in `admin_login.php`
2. Visit `/admin_login.php` to access the admin panel
3. Monitor enquiries and email delivery status
4. Set up regular backups of the `/logs/` directory

## 📝 Notes
- Log files are created daily (one file per day)
- System automatically handles file creation
- All enquiries are preserved even if emails fail
- Export feature helps with data backup and analysis

## 🔧 Maintenance
- Regularly backup the `/logs/` directory
- Monitor failed emails and investigate issues
- Consider archiving old log files after 1-2 years
- Update admin credentials periodically