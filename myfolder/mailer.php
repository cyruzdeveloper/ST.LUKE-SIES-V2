<?php
// mailer.php - helper to send HTML email using PHPMailer (if installed) or PHP mail() fallback.
// Returns array: ['success' => bool, 'error' => string|null]

include_once __DIR__ . '/config.php';

function sendEnrollmentEmail($to, $subject, $htmlBody, $fromName = null, $fromEmail = null) {
    $fromEmail = $fromEmail ?? (defined('MAIL_FROM') ? MAIL_FROM : 'no-reply@example.com');
    $fromName = $fromName ?? (defined('MAIL_NAME') ? MAIL_NAME : 'St. Luke');

    // Prefer PHPMailer if available (installed via Composer)
    $vendor = __DIR__ . '/vendor/autoload.php';
    if (file_exists($vendor)) {
        require_once $vendor;
        // Only attempt to use PHPMailer if the class actually exists to avoid static analysis/runtime errors
        if (class_exists('\\PHPMailer\\PHPMailer\\PHPMailer')) {
            try {
                $mailerClass = '\\PHPMailer\\PHPMailer\\PHPMailer';
                $mail = new $mailerClass(true);
                if (defined('MAIL_USE_SMTP') && MAIL_USE_SMTP) {
                    $mail->isSMTP();
                    $mail->Host = SMTP_HOST;
                    $mail->Port = SMTP_PORT;
                    $mail->SMTPAuth = true;
                    $mail->Username = SMTP_USER;
                    $mail->Password = SMTP_PASS;
                    if (!empty(SMTP_SECURE)) $mail->SMTPSecure = SMTP_SECURE;
                }

                $mail->setFrom($fromEmail, $fromName);
                $mail->addAddress($to);
                if (defined('MAIL_TO') && MAIL_TO) {
                    // Send administrative copy as BCC to configured MAIL_TO
                    $mail->addBCC(MAIL_TO);
                }
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $htmlBody;

                $mail->send();
                return ['success' => true, 'error' => null];
            } catch (\Exception $e) {
                return ['success' => false, 'error' => 'PHPMailer error: ' . $e->getMessage()];
            }
        }
        // If PHPMailer classes aren't available, fall through to PHP mail() fallback
    }

    // Fallback to PHP mail()
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: {$fromName} <{$fromEmail}>\r\n";
    if (defined('MAIL_TO') && MAIL_TO) {
        $headers .= "Bcc: " . MAIL_TO . "\r\n";
    }

    $sent = false;
    try {
        $sent = mail($to, $subject, $htmlBody, $headers);
    } catch (Exception $e) {
        $sent = false;
    }

    if ($sent) return ['success' => true, 'error' => null];
    return ['success' => false, 'error' => 'PHP mail() failed.'];
}

?>