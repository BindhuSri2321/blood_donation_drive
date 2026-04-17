<?php
session_start();
require 'db_connect.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'user') {
    $_SESSION['error'] = "You must be logged in as a general user to submit a request.";
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $requester_id = $_SESSION['user_id'];
    $blood_group = $conn->real_escape_string($_POST['blood_group'] ?? '');
    $quantity = (int)($_POST['quantity'] ?? 0);
    $latitude = $_POST['latitude'] ?? '';
    $longitude = $_POST['longitude'] ?? '';

    // Convert to float, or null if empty
    $latitude_float = empty($latitude) ? null : (float)$latitude;
    $longitude_float = empty($longitude) ? null : (float)$longitude;

    if (empty($blood_group) || $quantity <= 0) {
        $_SESSION['error'] = "Invalid blood group or quantity specified.";
        header("Location: ../request_blood.php");
        exit();
    }
    
    // SQL: 6 placeholders for (requester_id, blood_group, quantity, latitude, longitude, status)
    $sql = "INSERT INTO requests (requester_id, blood_group, quantity, latitude, longitude, status) 
            VALUES (?, ?, ?, ?, ?, ?)"; // <<< FIXED: Status is now a placeholder (?)
    
    $stmt = $conn->prepare($sql);
    $status_pending = 'pending';
    
    // Bind parameters: i (int), s (string), i (int), d (double), d (double), s (string)
    // CRITICAL FIX: The type string "isidds" now correctly matches the 6 variables and 6 placeholders.
    $stmt->bind_param("isidds", 
        $requester_id, 
        $blood_group, 
        $quantity, 
        $latitude_float, 
        $longitude_float, 
        $status_pending // <<< The 'pending' status variable is now bound
    );
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Your request for {$blood_group} blood has been submitted successfully and is now live!";
        header("Location: ../profile.php");
    } else {
        $_SESSION['error'] = "Failed to submit request. Database Error: " . $stmt->error;
        header("Location: ../request_blood.php");
    }

    $stmt->close();
    $conn->close();
    exit();
} else {
    header("Location: ../request_blood.php");
    exit();
}
?>