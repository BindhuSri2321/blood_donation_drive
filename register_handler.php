<?php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $blood_group = $conn->real_escape_string($_POST['blood_group']);
    $user_type = $conn->real_escape_string($_POST['user_type']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $latitude = isset($_POST['latitude']) ? (float)$_POST['latitude'] : NULL;
    $longitude = isset($_POST['longitude']) ? (float)$_POST['longitude'] : NULL;

    // Basic validation
    if (empty($name) || empty($email) || empty($_POST['password']) || empty($blood_group) || empty($user_type)) {
        $_SESSION['error'] = "All required fields must be filled.";
        header("Location: ../register.php");
        exit();
    }

    $sql = "INSERT INTO users (name, email, password, blood_group, user_type, phone, latitude, longitude)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssdd", $name, $email, $password, $blood_group, $user_type, $phone, $latitude, $longitude);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful! Please log in.";
        header("Location: ../login.php");
    } else {
        // Check for duplicate entry (email)
        if ($conn->errno == 1062) {
            $_SESSION['error'] = "Error: Email is already registered.";
        } else {
            $_SESSION['error'] = "Error: " . $stmt->error;
        }
        header("Location: ../register.php");
    }

    $stmt->close();
    $conn->close();
}
?>