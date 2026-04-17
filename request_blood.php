<?php 
// 🔑 CRITICAL FIX: session_start() MUST be the very first line of PHP code
session_start(); 
require 'backend/db_connect.php'; 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // This block is executed if the session data didn't load successfully
    $_SESSION['error'] = "You must be logged in to submit a request. Please log in again.";
    header("Location: login.php");
    exit();
}

// Pre-fill blood group if available
$user_bg = $_SESSION['blood_group'] ?? 'A+'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Drive - Request Blood</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/images/favicon.png" type="image/x-icon">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm">...</nav>
    <main class="py-5">
        <div class="container">
            <h1 class="text-danger mb-4 text-center">Submit a Blood Request</h1>
            
            <?php 
            if (isset($_SESSION['success'])) { echo '<div class="alert alert-success mt-3" role="alert">'.$_SESSION['success'].'</div>'; unset($_SESSION['success']); }
            if (isset($_SESSION['error'])) { echo '<div class="alert alert-danger mt-3" role="alert">'.$_SESSION['error'].'</div>'; unset($_SESSION['error']); }
            ?>

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow-lg border-0 p-4">
                        <p class="text-muted text-center">Your request will be broadcast to nearby donors and blood banks matching the required blood type.</p>
                        
                        <form action="backend/request_handler.php" method="POST">
                            
                            <div class="mb-3">
                                <label for="blood_group" class="form-label">Required Blood Group</label>
                                <select class="form-select" id="blood_group" name="blood_group" required>
                                    <option value="<?php echo htmlspecialchars($user_bg); ?>" selected><?php echo htmlspecialchars($user_bg); ?> (Your Group)</option>
                                    <option value="A+">A+</option><option value="A-">A-</option>
                                    <option value="B+">B+</option><option value="B-">B-</option>
                                    <option value="O+">O+</option><option value="O-">O-</option>
                                    <option value="AB+">AB+</option><option value="AB-">AB-</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity (Units)</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" min="1" max="10" value="1" required>
                            </div>

                            <p class="text-info small">
                                **NOTE:** Your current location (Latitude and Longitude) will be captured and sent with this request to find the closest donors.
                            </p>
                            
                            <input type="hidden" id="latitude" name="latitude" required>
                            <input type="hidden" id="longitude" name="longitude" required>

                            <button type="submit" class="btn btn-danger w-100 mt-3">Submit Emergency Request</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="bg-danger text-white py-3 mt-5">...</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                },
                function(error) {
                    alert('Error getting location. Requests cannot be processed without location data.');
                    console.error('Geolocation Error:', error);
                }
            );
        } else {
            alert('Geolocation is not supported by your browser.');
        }
    });
    </script>
</body>
</html>