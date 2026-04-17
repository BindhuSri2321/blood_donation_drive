<?php require 'backend/db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Drive - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" href="assets/images/favicon.png" type="image/x-icon">

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
                        BloodDrive
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact Us</a>
                </li>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="btn btn-outline-light ms-2" href="profile.php">My Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-warning ms-2" href="backend/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="btn btn-light ms-2" href="login.php">Login / Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
        <main class="py-5">
        <div class="container">
            <?php 
            // Simplified session message display for example
            if (isset($_SESSION['success'])) {
                echo '<div class="alert alert-success mt-3" role="alert">'.$_SESSION['success'].'</div>';
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger mt-3" role="alert">'.$_SESSION['error'].'</div>';
                unset($_SESSION['error']);
            }
            ?>
            
            <header class="text-center p-5 rounded-3 hero-bg">
                <h1 class="display-3 fw-bold text-white mb-3">Save a Life, Donate Blood</h1>
                <p class="lead text-white-50">Connecting those in need with nearby blood banks and registered donors in real-time.</p>
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mt-4">
                    <a href="request_blood.php" class="btn btn-danger btn-lg px-4 me-sm-3 fw-bold">Request Blood Now</a>
                    <a href="register.php" class="btn btn-light btn-lg px-4">Become a Donor / Register Bank</a>
                </div>
            </header>

            <section class="row text-center mt-5">
                <div class="col-md-4">
                    <div class="p-4 bg-light rounded-3 shadow-sm">
                        <i class="fas fa-search display-6 text-danger mb-3"></i>
                        <h2 class="h4">Live Location Matching</h2>
                        <p>Our system uses your live location to find the closest help in an emergency.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 bg-light rounded-3 shadow-sm">
                        <i class="fas fa-heartbeat display-6 text-danger mb-3"></i>
                        <h2 class="h4">Emergency Requests</h2>
                        <p>Get immediate notifications to donors and blood banks for critical needs.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 bg-light rounded-3 shadow-sm">
                        <i class="fas fa-users display-6 text-danger mb-3"></i>
                        <h2 class="h4">Community Driven</h2>
                        <p>Join a network of heroes dedicated to saving lives across the country.</p>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>
</body>
</html>