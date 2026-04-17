<?php
// CRITICAL: Start the session before any other code
session_start(); 
require 'db_connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Check if email and password were actually posted
    if (!isset($_POST['email']) || !isset($_POST['password'])) {
        $_SESSION['error'] = "Please enter both email and password.";
        header("Location: ../login.php");
        exit();
    }
    
    // Sanitize input
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Prepare and execute the SQL statement
    $sql = "SELECT id, name, email, password, user_type, blood_group, phone FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            // Login success: Store user data in session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['email'] = $user['email']; // Critical for profile page
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['blood_group'] = $user['blood_group'];
            $_SESSION['phone'] = $user['phone'];

            $_SESSION['success'] = "Welcome back, " . $user['name'] . "!";
            header("Location: ../profile.php"); // Redirect to profile/dashboard
        } else {
            $_SESSION['error'] = "Invalid password. Please try again.";
            header("Location: ../login.php");
        }
    } else {
        $_SESSION['error'] = "No user found with that email.";
        header("Location: ../login.php");
    }

    $stmt->close();
    $conn->close();
    exit(); 
}
// IMPORTANT: No extra closing brace (}) here
?>