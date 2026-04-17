<?php
// Start the session to store a success message
session_start();
// Optionally include db_connect.php if you want to save the message
// require 'db_connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Capture and Sanitize Input
    $name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $message = filter_var($_POST['message'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // 2. Perform Validation
    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['error'] = "Please fill in all fields.";
        header("Location: ../contact.php");
        exit();
    }
    
    // 3. Process/Save Data (Placeholder Logic)
    
    /* * If you wanted to save this to a database table named 'contacts':
    * $sql = "INSERT INTO contacts (name, email, message, created_at) VALUES (?, ?, ?, NOW())";
    * $stmt = $conn->prepare($sql);
    * $stmt->bind_param("sss", $name, $email, $message);
    * $stmt->execute();
    * $stmt->close();
    */
    
    // 4. Set Success Message and Redirect to Home Page (index.php)
    $_SESSION['success'] = "Thank you, {$name}! Your message has been sent successfully. We will be in touch soon.";
    
    // CRITICAL: This is the command that redirects the user to the home page
    header("Location: ../index.php"); 
    exit();
    
} else {
    // Redirect if someone tries to access this page directly
    header("Location: ../contact.php");
    exit();
}
?>