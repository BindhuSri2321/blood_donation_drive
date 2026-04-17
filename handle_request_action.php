<?php
// CRITICAL: Session must start first to access $_SESSION['user_id']
session_start(); 
require 'db_connect.php';

// Set header for JSON response
header('Content-Type: application/json');

// 1. Check if user is logged in as a Donor/Bank
if (!isset($_SESSION['user_id']) || ($_SESSION['user_type'] != 'donor' && $_SESSION['user_type'] != 'bank')) {
    // This is the check that was failing and throwing "Unauthorized action."
    echo json_encode(['success' => false, 'message' => 'Unauthorized action. Please log in again.']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $donor_id = $_SESSION['user_id'];
    
    // Check required POST data
    if (!isset($_POST['request_id']) || !isset($_POST['action'])) {
        echo json_encode(['success' => false, 'message' => 'Missing request ID or action.']);
        exit();
    }
    
    $request_id = (int)$_POST['request_id'];
    $action = $conn->real_escape_string($_POST['action']);

    // 2. Determine status and donor ID based on action
    if ($action === 'accept') {
        $status = 'accepted';
        $donor_id_update = $donor_id; 
    } elseif ($action === 'reject') {
        $status = 'rejected';
        $donor_id_update = NULL; // Clear donor ID if rejecting
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action specified.']);
        exit();
    }

    // 3. Update the request status in the database (Only update if still pending)
    // Note: Bind_param format 'sii' (string, integer, integer)
    $sql = "UPDATE requests 
            SET status = ?, donor_id = ? 
            WHERE id = ? AND status = 'pending'"; 
    
    $stmt = $conn->prepare($sql);
    // Use the integer $donor_id_update; PHP handles NULL correctly for integer columns
    $stmt->bind_param("sii", $status, $donor_id_update, $request_id);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $message = "Request " . $status . " successfully.";
        
        // 4. If accepted, fetch and return contact info
        if ($action === 'accept') {
            $sql_contact = "SELECT u.name, u.phone 
                            FROM users u 
                            JOIN requests r ON r.requester_id = u.id 
                            WHERE r.id = ?";
            $stmt_contact = $conn->prepare($sql_contact);
            $stmt_contact->bind_param("i", $request_id);
            $stmt_contact->execute();
            $result_contact = $stmt_contact->get_result();
            $requester = $result_contact->fetch_assoc();
            
            if ($requester) {
                // Use asterisks to highlight the contact info in the message
                $message = "Accepted! Contact Requester ({$requester['name']}): **{$requester['phone']}**."; 
            }
            $stmt_contact->close();
        }
        
        echo json_encode(['success' => true, 'message' => $message, 'status' => $status]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update request or request was already processed.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>