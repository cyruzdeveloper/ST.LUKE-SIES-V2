<?php
// test_mail.php - quick script to test sendEnrollmentEmail()
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/mailer.php';

// Determine recipient: prefer ?to= in query string, otherwise use MAIL_TO or prompt
$to = null;
if (php_sapi_name() === 'cli') {
    // try to read from argv
    $arg = $argv[1] ?? null;
    if ($arg) $to = $arg;
} else {
    $to = $_GET['to'] ?? null;
}

if (empty($to)) {
    $to = defined('MAIL_TO') && MAIL_TO ? MAIL_TO : null;
}

if (empty($to)) {
    echo "No recipient specified. Provide an email as CLI arg or ?to=you@example.com\n";
    exit(1);
}

$subject = "[Test] Enrollment Email from St. Luke";
$username = '2024-TEST1';
$password = '123456';
$body = "<html><body>" .
        "<p>This is a test enrollment email.</p>" .
        "<p><strong>Username:</strong> " . htmlspecialchars($username) . "</p>" .
        "<p><strong>Password:</strong> " . htmlspecialchars($password) . "</p>" .
        "</body></html>";

echo "Sending test email to: {$to}\n";
$result = sendEnrollmentEmail($to, $subject, $body);

echo "Result:\n";
var_export($result);
echo "\n";

if (!empty($result['success'])) {
    echo "Mail reported as sent. Check recipient inbox (and BCC admin) for delivery.\n";
} else {
    echo "Mail sending failed. Error: " . ($result['error'] ?? 'unknown') . "\n";
}

?>
