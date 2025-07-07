<?php
    // Database credentials
$servername = "localhost";
$username = "root";        // Use your DB username
$password = "";            // Use your DB password
$dbname = "test";   // Use your DB name

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else //echo"Connected";
?>