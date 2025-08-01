# Complete DirectAdmin Deployment Guide for CrownOpportunities

## Overview
This guide will walk you through deploying the CrownOpportunities job board to a DirectAdmin hosting environment. The application includes a complete PHP backend, responsive frontend, admin panel, and PostgreSQL database.

## Prerequisites
- DirectAdmin hosting account with PHP 8.0+ support
- PostgreSQL database access (or MySQL - we'll provide conversion)
- FTP/SFTP access or File Manager
- Domain name configured

## Step 1: Prepare Your Files

### Download the Complete Package
All files are in the `php-version` folder:
```
php-version/
├── index.php (main homepage)
├── apply.php (job application page)
├── config/
│   └── database.php (database configuration)
├── api/
│   ├── jobs.php
│   ├── categories.php
│   ├── testimonials.php
│   ├── applications.php
│   └── contact.php
├── admin/
│   ├── index.php (dashboard)
│   ├── login.php
│   └── logout.php
├── assets/
│   ├── style.css
│   └── script.js
├── uploads/ (create this folder with 755 permissions)
├── database.sql (PostgreSQL schema)
└── README.md
```

## Step 2: Database Setup

### Option A: PostgreSQL (Recommended)
1. **Create Database in DirectAdmin:**
   - Login to DirectAdmin
   - Go to "PostgreSQL Management"
   - Create new database: `crownopportunities`
   - Create database user with full permissions
   - Note down: database name, username, password, host

2. **Import Database Schema:**
   - Use phpPgAdmin or command line
   - Import the `database.sql` file
   - This creates all tables and inserts sample data

### Option B: MySQL Conversion (If PostgreSQL unavailable)
If your host only supports MySQL, here's the converted schema:

```sql
-- MySQL Version of database.sql
CREATE DATABASE crownopportunities CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE crownopportunities;

-- Users table
CREATE TABLE users (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    is_admin BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table  
CREATE TABLE categories (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name TEXT NOT NULL UNIQUE,
    description TEXT,
    gif_url TEXT,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Jobs table
CREATE TABLE jobs (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    title TEXT NOT NULL,
    company TEXT NOT NULL,
    category TEXT NOT NULL,
    location TEXT NOT NULL,
    description TEXT NOT NULL,
    requirements TEXT NOT NULL,
    salary_min INTEGER,
    salary_max INTEGER,
    job_type TEXT NOT NULL DEFAULT 'Full-time',
    is_urgent BOOLEAN DEFAULT false,
    visa_sponsored BOOLEAN DEFAULT true,
    company_logo TEXT,
    workplace_images JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Applications table
CREATE TABLE applications (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    job_id VARCHAR(36) NOT NULL,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    email TEXT NOT NULL,
    phone TEXT NOT NULL,
    current_location TEXT NOT NULL,
    profile_photo TEXT,
    cv_file TEXT,
    cover_letter TEXT,
    experience TEXT,
    previous_role TEXT,
    status TEXT NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
);

-- Testimonials table
CREATE TABLE testimonials (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    name TEXT NOT NULL,
    country TEXT NOT NULL,
    rating INTEGER NOT NULL,
    comment TEXT NOT NULL,
    photo TEXT,
    video_url TEXT,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Messages table
CREATE TABLE messages (
    id VARCHAR(36) PRIMARY KEY DEFAULT (UUID()),
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    email TEXT NOT NULL,
    subject TEXT NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT false,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data (same as PostgreSQL version)
-- ... (include all INSERT statements from database.sql)
```

## Step 3: Upload Files to DirectAdmin

### Method 1: File Manager (Easiest)
1. Login to DirectAdmin
2. Go to "File Manager"
3. Navigate to `public_html/` (or your domain folder)
4. Upload all files from `php-version/` folder
5. Extract if uploaded as ZIP

### Method 2: FTP/SFTP
1. Connect to your server using FTP client (FileZilla, WinSCP)
2. Navigate to `public_html/` or `domains/yourdomain.com/public_html/`
3. Upload all files maintaining folder structure
4. Set correct permissions (see Step 4)

### Method 3: SSH (Advanced)
```bash
# Connect via SSH
ssh username@yourserver.com

# Navigate to public_html
cd public_html

# Upload files (if using wget/curl)
# Or use SCP from local machine:
scp -r php-version/* username@yourserver.com:public_html/
```

## Step 4: Set File Permissions

**Important:** Set correct permissions for security:

```bash
# Main files and folders
chmod 644 *.php
chmod 644 assets/*.css
chmod 644 assets/*.js
chmod 755 api/
chmod 644 api/*.php
chmod 755 admin/
chmod 644 admin/*.php
chmod 755 config/
chmod 600 config/database.php  # Secure database config
chmod 755 uploads/             # Upload folder needs write access
chmod 775 uploads/             # If 755 doesn't work
```

**Via DirectAdmin File Manager:**
1. Right-click each file/folder
2. Click "Change Permissions"
3. Set as specified above

## Step 5: Configure Database Connection

Edit `config/database.php`:

```php
<?php
class Database {
    // Update these with your DirectAdmin database details
    private $host = "localhost";              // Usually localhost
    private $db_name = "username_crownopportunities";  // Usually prefixed with username
    private $username = "username_crownuser";  // Your database username  
    private $password = "your_database_password";
    private $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            // For PostgreSQL
            $this->conn = new PDO(
                "pgsql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            
            // For MySQL (if using MySQL instead)
            // $this->conn = new PDO(
            //     "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
            //     $this->username,
            //     $this->password
            // );
            
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}
?>
```

## Step 6: Test Your Installation

### 1. Test Homepage
Visit: `https://yourdomain.com`
- Should load homepage with categories, jobs, testimonials
- Check browser console for any JavaScript errors

### 2. Test Admin Panel
Visit: `https://yourdomain.com/admin/`
- Should redirect to login page
- Default credentials:
  - **Username:** admin
  - **Password:** admin123
- After login, check dashboard statistics

### 3. Test Job Application
- Click any job on homepage
- Fill out application form
- Check if application submits successfully

### 4. Test Contact Form
- Scroll to bottom of homepage
- Fill out contact form
- Verify submission works

## Step 7: Domain Configuration

### Set Document Root (if needed)
If your domain isn't pointing to the correct folder:
1. In DirectAdmin, go to "Domain Setup"
2. Edit your domain
3. Set document root to folder containing your files

### SSL Certificate
1. In DirectAdmin, go to "SSL Certificates"
2. Enable "Let's Encrypt" for free SSL
3. Force HTTPS redirect

## Step 8: Security Hardening

### 1. Secure Database Config
```bash
chmod 600 config/database.php
```

### 2. Add .htaccess Protection
Create `.htaccess` in root folder:
```apache
# Deny access to sensitive files
<Files "config/database.php">
    Order allow,deny
    Deny from all
</Files>

# Force HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Security headers
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
```

### 3. Change Default Admin Password
1. Login to admin panel
2. Go to user management (or manually update database)
3. Change default password immediately

## Step 9: Email Configuration (Optional)

For contact form emails, add to `api/contact.php`:
```php
// Add after successful database insert
$to = "admin@yourdomain.com";
$subject = "New Contact Message: " . $input['subject'];
$message = "Name: " . $input['firstName'] . " " . $input['lastName'] . "\n";
$message .= "Email: " . $input['email'] . "\n\n";
$message .= "Message:\n" . $input['message'];
$headers = "From: noreply@yourdomain.com";

mail($to, $subject, $message, $headers);
```

## Step 10: Backup Setup

### Database Backup
Create automatic backup script `backup.php`:
```php
<?php
require_once 'config/database.php';

$backup_file = 'backups/backup_' . date('Y-m-d_H-i-s') . '.sql';
$command = "pg_dump -h localhost -U username dbname > $backup_file";
exec($command);
echo "Backup created: $backup_file";
?>
```

### File Backup
Use DirectAdmin's backup feature or create cron job for regular backups.

## Troubleshooting

### Common Issues:

**1. Database Connection Error**
- Check database credentials in `config/database.php`
- Verify database exists and user has permissions
- Check if PostgreSQL/MySQL extension is enabled

**2. Permission Errors**
- Ensure `uploads/` folder has write permissions (755 or 775)
- Check file permissions are correct

**3. PHP Errors**
- Enable error reporting temporarily:
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

**4. Missing Extensions**
Ensure these PHP extensions are enabled:
- PDO
- PDO_PGSQL (for PostgreSQL) or PDO_MYSQL
- JSON
- CURL
- GD (for image uploads)

## Final Checklist

- [ ] Database created and imported
- [ ] All files uploaded with correct permissions
- [ ] Database connection configured
- [ ] Homepage loads correctly
- [ ] Admin panel accessible
- [ ] Job applications work
- [ ] Contact form functional
- [ ] SSL certificate installed
- [ ] Default admin password changed
- [ ] Backup system in place

## Support

If you encounter issues:
1. Check DirectAdmin error logs
2. Enable PHP error reporting
3. Verify all prerequisites are met
4. Contact your hosting provider for server-specific help

Your CrownOpportunities job board is now ready for production use!