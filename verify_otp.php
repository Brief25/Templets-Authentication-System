<?php
session_start();

include("connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_otp = $_POST['otp'];

    if (!isset($_SESSION['otp']) || time() - $_SESSION['otp_time'] > 300) {
        die("OTP expired. Please register again.");
    }

    if ($entered_otp == $_SESSION['otp']) {
        $name = $_SESSION['name'];
        $email = $_SESSION['email'];
        $password = password_hash($_SESSION['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO eusers (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        $stmt->execute();
        $stmt->close();

        // Call mail after success
        require 'success_mail.php';

        echo "OTP verified successfully! Redirecting in <span id='countdown'>15</span> seconds...";
        echo "<script>
            let sec = 15;
            const countdown = document.getElementById('countdown');
            const timer = setInterval(() => {
                sec--;
                countdown.textContent = sec;
                if (sec <= 0) {
                    clearInterval(timer);
                    window.location.href = 'login.php';
                }
            }, 1000);
        </script>";

        session_destroy();
    } else {
        echo "Invalid OTP.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Verify OTP</title></head>
<body>
<h2>Enter OTP</h2>
<form method="POST">
    <input type="text" name="otp" required>
    <input type="submit" value="Verify OTP">
</form>
</body>
</html>
