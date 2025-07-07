<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Make sure you have PHPMailer installed and path is correct
require 'PHPMailer/PHPMailer-master/src/Exception.php';
require 'PHPMailer/PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer/PHPMailer-master/src/SMTP.php';

function send_alert_email($email, $name) {
    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = ''; // Your email
        $mail->Password   = ''; // Use Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Email Headers
        $mail->setFrom('your_email@gmail.com', 'Security System');
        $mail->addAddress($email, $name); // Send to user

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = '⚠️ Multiple Failed Login Attempts Detected';
        $mail->Body    = "
            Hello $name,<br><br>
            We noticed <strong>multiple failed login attempts</strong> on your account.<br>
            If this wasn't you, we recommend changing your password immediately.<br><br>
            Thanks,<br>
            Security Team
        ";

        $mail->send();
    } catch (Exception $e) {
        // Optional: Log error silently
        error_log("Email alert error: {$mail->ErrorInfo}");
    }
}
?>
