<?php 
// 🔑 CRITICAL FIX: session_start() MUST be the very first line of PHP code
session_start(); 
require 'backend/db_connect.php'; 

// Restrict access to logged-in users
if (!isset($_SESSION['user_id'])) {
    // If the session failed to load, this redirects you back to login
    $_SESSION['error'] = "Session expired or invalid. Please log in again.";
    header("Location: login.php");
    exit();
}
// ... rest of your profile.php code
// Retrieve session variables, using null coalescing (?? '') for safety 
$user_id = $_SESSION['user_id'];
$user_type = $_SESSION['user_type'] ?? 'user';
$user_name = $_SESSION['user_name'] ?? 'User';
$user_bg = $_SESSION['blood_group'] ?? 'N/A';
$user_phone = $_SESSION['phone'] ?? 'N/A';
$user_email = $_SESSION['email'] ?? 'N/A'; // FIX: Prevents "Undefined array key 'email'" warning

// Fetch all requests made by this user (if user is a general requester)
$my_requests = [];
if ($user_type == 'user') {
    $sql_req = "SELECT * FROM requests WHERE requester_id = ? ORDER BY created_at DESC";
    $stmt_req = $conn->prepare($sql_req);
    $stmt_req->bind_param("i", $user_id);
    $stmt_req->execute();
    $result_req = $stmt_req->get_result();
    while ($row = $result_req->fetch_assoc()) {
        $my_requests[] = $row;
    }
    $stmt_req->close();
}

// Fetch pending requests that match this donor/bank's blood group and are 'pending'
$pending_donor_requests = [];
if ($user_type == 'donor' || $user_type == 'bank') {
    $sql_pend = "SELECT r.*, u.name as requester_name, u.phone as requester_phone 
                 FROM requests r 
                 JOIN users u ON r.requester_id = u.id
                 WHERE r.blood_group = ? AND r.status = 'pending' 
                 ORDER BY r.created_at DESC LIMIT 10"; 
    $stmt_pend = $conn->prepare($sql_pend);
    $stmt_pend->bind_param("s", $user_bg);
    $stmt_pend->execute();
    $result_pend = $stmt_pend->get_result();
    while ($row = $result_pend->fetch_assoc()) {
        $pending_donor_requests[] = $row;
    }
    $stmt_pend->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Drive - My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/images/favicon.png" type="image/x-icon">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm">...</nav> 

    <main class="py-5">
        <div class="container">
            <?php 
            if (isset($_SESSION['success'])) { echo '<div class="alert alert-success mt-3" role="alert">'.$_SESSION['success'].'</div>'; unset($_SESSION['success']); }
            if (isset($_SESSION['error'])) { echo '<div class="alert alert-danger mt-3" role="alert">'.$_SESSION['error'].'</div>'; unset($_SESSION['error']); }
            ?>

            <h1 class="text-danger mb-4">Welcome, <?php echo htmlspecialchars($user_name); ?>!</h1>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card shadow-lg border-0 bg-light">
                        <div class="card-body">
                            <h5 class="card-title text-danger">Account Details</h5>
                            <hr>
                            <p><strong>Type:</strong> <span class="badge bg-secondary"><?php echo ucfirst($user_type); ?></span></p>
                            <p><strong>Blood Group:</strong> <span class="badge bg-danger fs-5"><?php echo htmlspecialchars($user_bg); ?></span></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($user_email); ?></p>
                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user_phone); ?></p>
                            <a href="backend/logout.php" class="btn btn-outline-danger w-100 mt-2">Logout</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-8 mb-4">
                    <?php if ($user_type == 'user'): // General User Requests ?>
                        <div class="card shadow-lg border-0">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0">Your Blood Request History</h5>
                            </div>
                            <div class="card-body">
                                <a href="request_blood.php" class="btn btn-success mb-3">Submit New Request</a>
                                <?php if (!empty($my_requests)): ?>
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($my_requests as $req): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span>
                                                    Request for **<?php echo htmlspecialchars($req['blood_group']); ?>** (<?php echo htmlspecialchars($req['quantity']); ?> units)
                                                    <br><small class="text-muted">Requested on: <?php echo date("Y-m-d H:i", strtotime($req['created_at'])); ?></small>
                                                </span>
                                                <span class="badge bg-<?php echo ($req['status'] == 'accepted' ? 'success' : ($req['status'] == 'pending' ? 'warning' : 'secondary')); ?> text-uppercase p-2">
                                                    <?php echo htmlspecialchars($req['status']); ?>
                                                </span>
                                                <?php if ($req['status'] == 'accepted'): ?>
                                                     <span class="text-success small">Accepted!</span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-muted mt-3">You have not submitted any blood requests yet.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                    <?php elseif ($user_type == 'donor' || $user_type == 'bank'): // Donor/Bank Pending Requests ?>
                        <div class="card shadow-lg border-0">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">Pending Requests Matching Your Group (<?php echo htmlspecialchars($user_bg); ?>)</h5>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($pending_donor_requests)): ?>
                                    <p class="text-muted small">These are the most recent requests matching your blood group. Location filtering is needed for real-time distance matching.</p>
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($pending_donor_requests as $req): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                <div>
                                                    Need **<?php echo htmlspecialchars($req['blood_group']); ?>** (<?php echo htmlspecialchars($req['quantity']); ?> units)
                                                    <br><small class="text-muted">Near: (Lat: <?php echo $req['latitude']; ?>, Lon: <?php echo $req['longitude']; ?>) | Requested by: <?php echo htmlspecialchars($req['requester_name']); ?></small>
                                                </div>
                                                <div class="mt-2 mt-sm-0 action-buttons-container">
                                                    <button 
                                                        class="btn btn-sm btn-success me-2 action-btn" 
                                                        data-action="accept" 
                                                        data-request-id="<?php echo $req['id']; ?>">Accept</button>
                                                    <button 
                                                        class="btn btn-sm btn-secondary action-btn" 
                                                        data-action="reject" 
                                                        data-request-id="<?php echo $req['id']; ?>">Reject</button>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="text-muted mt-3">No pending emergency requests match your blood group right now.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>
    
    <footer class="bg-danger text-white py-3 mt-5">...</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const actionButtons = document.querySelectorAll('.action-btn');

        actionButtons.forEach(button => {
            button.addEventListener('click', function() {
                const requestId = this.getAttribute('data-request-id');
                const action = this.getAttribute('data-action');
                const listItem = this.closest('.list-group-item');
                
                // Disable buttons and show processing
                const buttonsContainer = this.parentNode;
                buttonsContainer.innerHTML = `<span class="text-info small">Processing...</span>`;

                // Use the Fetch API to send data to the PHP handler
                fetch('backend/handle_request_action.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `request_id=${requestId}&action=${action}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the list item visually
                        listItem.classList.remove('list-group-item-warning');
                        const alertType = data.status === 'accepted' ? 'success' : 'info';
                        
                        // Display success message and contact info if accepted
                        listItem.innerHTML += `
                            <div class="w-100 mt-2 alert alert-${alertType} p-2 small">
                                ${data.message}
                            </div>
                        `;
                    } else {
                        // Display error message
                        buttonsContainer.innerHTML = `<span class="text-danger small">${data.message}</span>`;
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    buttonsContainer.innerHTML = `<span class="text-danger small">Network Error</span>`;
                });
            });
        });
    });
    </script>
</body>
</html>