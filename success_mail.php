<?php
require 'PHPMailer/PHPMailer-master/src/Exception.php';
require 'PHPMailer/PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = ''; // Your email
    $mail->Password   = ''; // Use Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('your_email@gmail.com', 'OTP System');
    $mail->addAddress($_SESSION['email'], $_SESSION['name']);

    $mail->isHTML(true);
    $mail->Subject = 'Registration Successful';
    $mail->Body    = "Hi {$_SESSION['name']},<br>Thank you for registering. Your account has been successfully created.";

    $mail->send();
} catch (Exception $e) {
    // Log error silently
}
?>
