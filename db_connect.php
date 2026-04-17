<?php
// CRITICAL FIX: DO NOT call session_start() here. It must be called in the main page files only.

$servername = "localhost";
$username = "root"; // Default XAMPP MySQL user
$password = "";     // Default XAMPP MySQL password (empty)
$dbname = "blood_donation_drive"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// NOTE: It is recommended to set default character set after connection
$conn->set_charset("utf8mb4");

?>