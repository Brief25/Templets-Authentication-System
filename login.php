<!--

if0_39410410 
Password
3pND2ByIJmkUNIQ

3pND2ByIJmkUNIQ
 username if0_39410410! Here

 Account if0_39410410 (Website for virendra.wuaze.com) created!
Your account has been created with username if0_39410410! Here are some things you need to know:

It will take a few minutes for your account to be set up.
It can take up to 72 hours for the new domain to be visible everywhere, due to DNS caching.
Please login to the control panel once to enable all features.
Not sure what to do next? Please see this guide for some ideas on how to get started.
-->

<?php
session_start();
include("connection.php");
require 'mail_alert.php'; // include the function

define('MAX_ATTEMPTS', 5);
define('LOCK_TIME', 15 * 60); // 15 minutes

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = htmlspecialchars(trim($_POST['password']));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("❌ Invalid email format.");
    }

    $stmt = $conn->prepare("SELECT id, name, password, failed_attempts, last_failed_attempt FROM eusers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $name, $hashed_password, $attempts, $last_failed);
        $stmt->fetch();

        // Check if account is locked
        if ($attempts >= MAX_ATTEMPTS && (time() - strtotime($last_failed)) < LOCK_TIME) {
            echo "⚠️ Account temporarily locked due to too many failed attempts. Try again later.";
            exit;
        }

        if (password_verify($password, $hashed_password)) {
            // Reset failed attempts
            $reset = $conn->prepare("UPDATE eusers SET failed_attempts = 0, last_failed_attempt = NULL WHERE id = ?");
            $reset->bind_param("i", $id);
            $reset->execute();

            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;

            echo "✅ Login successful! Redirecting in <span id='countdown'>5</span> seconds...";
            echo "<script>
                let sec = 5;
                const countdown = document.getElementById('countdown');
                const timer = setInterval(() => {
                    sec--;
                    countdown.textContent = sec;
                    if (sec <= 0) {
                        clearInterval(timer);
                        window.location.href = 'welcome.php';
                    }
                }, 1000);
            </script>";
        } else {
            // Increment failed attempts
            $attempts++;
            $now = date('Y-m-d H:i:s');

            $update = $conn->prepare("UPDATE eusers SET failed_attempts = ?, last_failed_attempt = ? WHERE id = ?");
            $update->bind_param("isi", $attempts, $now, $id);
            $update->execute();

            echo "❌ Incorrect password. Attempt $attempts of " . MAX_ATTEMPTS . ".";

            if ($attempts == MAX_ATTEMPTS) {
                send_alert_email($email, $name);
                echo "<br>⚠️ Your account is temporarily locked and a security alert was sent to your email.";
            }
        }
    } else {
        echo "❌ No account found with that email.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
<h2>Login</h2>
<form method="POST">
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <input type="submit" value="Login">
</form>
<p>I havn't any <a href="register.php">account</a></p>
</body>
</html>
