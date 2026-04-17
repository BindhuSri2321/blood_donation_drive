<?php 
require 'backend/db_connect.php'; 
// Include the common header and footer content structure here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Drive - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm">...</nav>
    <main class="py-5">
        <div class="container">
            <?php 
            // Simplified session message display
            if (isset($_SESSION['success'])) { echo '<div class="alert alert-success mt-3" role="alert">'.$_SESSION['success'].'</div>'; unset($_SESSION['success']); }
            if (isset($_SESSION['error'])) { echo '<div class="alert alert-danger mt-3" role="alert">'.$_SESSION['error'].'</div>'; unset($_SESSION['error']); }
            ?>

            <h1 class="text-center mb-4 text-danger">Register as a Life Saver</h1>
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow-lg border-0">
                        <div class="card-body p-4 p-md-5">
                            <form action="backend/register_handler.php" method="POST" id="registrationForm">
                                <input type="hidden" name="latitude" id="reg_latitude">
                                <input type="hidden" name="longitude" id="reg_longitude">
                                <p class="text-muted small">Note: Live location is captured automatically for location-based matching.</p>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name / Bank Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone">
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="blood_group" class="form-label">Blood Group</label>
                                        <select class="form-select" id="blood_group" name="blood_group" required>
                                            <option value="">Select Group</option>
                                            <option value="A+">A+</option><option value="A-">A-</option>
                                            <option value="B+">B+</option><option value="B-">B-</option>
                                            <option value="O+">O+</option><option value="O-">O-</option>
                                            <option value="AB+">AB+</option><option value="AB-">AB-</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="user_type" class="form-label">I am a...</label>
                                        <select class="form-select" id="user_type" name="user_type" required>
                                            <option value="">Select Type</option>
                                            <option value="donor">Individual Donor</option>
                                            <option value="bank">Blood Bank</option>
                                            <option value="user">General User (Requester)</option>
                                        </select>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-danger w-100 mt-3" id="registerBtn">Register</button>
                                <p class="text-center mt-3 mb-0">Already have an account? <a href="login.php" class="text-danger">Log in here</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

</div>
        </main>
        <footer class="bg-danger text-white py-3 mt-5">...</footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/app.js"></script>
        <script>
            // Call the geolocation function on page load
            document.addEventListener('DOMContentLoaded', () => {
                getGeolocation(document.getElementById('reg_latitude'), document.getElementById('reg_longitude'), 'registerBtn');
            });
        </script>
    </body>
</html>