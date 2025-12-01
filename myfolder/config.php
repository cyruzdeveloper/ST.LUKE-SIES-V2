<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'enrollment_system');
define('DB_USER', 'root');
define('DB_PASS', '');

// Mail configuration (used by simple mailer). Adjust as needed.
if (!defined('MAIL_FROM')) define('MAIL_FROM', 'no-reply@stluke.local');
if (!defined('MAIL_NAME')) define('MAIL_NAME', 'St. Luke Enrollment');


// Set the recipient for administrative copies (school email). Update these to your real addresses.
if (!defined('MAIL_TO')) define('MAIL_TO', 'vasquezdave26@gmail.com');

// SMTP / PHPMailer settings
// Set MAIL_USE_SMTP to true if you want to send via SMTP (recommended).
if (!defined('MAIL_USE_SMTP')) define('MAIL_USE_SMTP', false);
if (!defined('SMTP_HOST')) define('SMTP_HOST', 'smtp.example.com');
if (!defined('SMTP_PORT')) define('SMTP_PORT', 587);
if (!defined('SMTP_USER')) define('SMTP_USER', 'smtp-user@example.com');
if (!defined('SMTP_PASS')) define('SMTP_PASS', 'smtp-password');
// 'tls' or 'ssl' or empty for none
if (!defined('SMTP_SECURE')) define('SMTP_SECURE', 'tls');

/*
 Mail settings you can customize:
 - MAIL_FROM: the From address used when sending emails (e.g., no-reply@yourdomain.com)
 - MAIL_NAME: the friendly name shown as sender (e.g., "St. Luke Enrollment")
 - MAIL_TO: the administrative receiver (school inbox) that will receive a BCC copy of enrollment emails

 Example:
 define('MAIL_FROM', 'no-reply@yourdomain.com');
 define('MAIL_NAME', 'St. Luke Enrollment');
 define('MAIL_TO', 'registrar@yourdomain.com');
*/

// Function to get PDO connection
function getDBConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, 
            DB_USER, 
            DB_PASS
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }
}

// Function to check login and redirect
function requireLogin($role, $redirectPage) {
    $roleKey = $role . '_id';
    if (!isset($_SESSION[$roleKey])) {
        header('Location: ' . $redirectPage);
        exit();
    }
}

// Function to log activity
function logActivity($username, $role, $activityType = 'login', $description = '') {
    try {
        $pdo = getDBConnection();
        
        // Get user_id
        $stmt = $pdo->prepare("SELECT user_id FROM user_account WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $userId = $user ? $user['user_id'] : null;
        
        // Get IP address
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
        
        // Get user agent
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
        
        // Get user's name based on role
        $name = $username;
        if ($role === 'teacher' && $userId) {
            $stmt = $pdo->prepare("SELECT t.teacher_name FROM teacher t INNER JOIN user_account u ON t.teacher_id = u.teacher_id WHERE u.user_id = ?");
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $name = $result ? $result['teacher_name'] : $username;
        } elseif ($role === 'student' && $userId) {
            $stmt = $pdo->prepare("SELECT s.student_name FROM student s INNER JOIN user_account u ON s.student_id = u.student_id WHERE u.user_id = ?");
            $stmt->execute([$userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $name = $result ? $result['student_name'] : $username;
        }
        
        // Set description based on activity type if not provided
        if (empty($description)) {
            switch ($activityType) {
                case 'login':
                    $description = ucfirst($role) . " logged in successfully";
                    break;
                case 'logout':
                    $description = ucfirst($role) . " logged out";
                    break;
                case 'failed_login':
                    $description = "Failed " . $role . " login attempt";
                    break;
                case 'enrollment_submitted':
                    $description = "Student enrollment submitted";
                    break;
                case 'enrollment_approved':
                    $description = "Student enrollment approved";
                    break;
                default:
                    $description = $activityType;
            }
        }
        
        // Insert activity log
        $stmt = $pdo->prepare("
            INSERT INTO activity_log 
            (user_id, username, role, activity_type, activity_description, ip_address, user_agent) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $userId,
            $name, // Store name instead of username for better display
            $role,
            $activityType,
            $description,
            $ipAddress,
            $userAgent
        ]);
        
        return true;
    } catch(PDOException $e) {
        // Silent fail - don't interrupt the login process
        error_log("Activity log error: " . $e->getMessage());
        return false;
    }
}

// Create a shared PDO instance for convenience
// This ensures including `config.php` provides `$pdo` ready-to-use
if (!isset($pdo)) {
    $pdo = getDBConnection();
}
?>