<?php 
require 'backend/db_connect.php'; 
// ... Common header structure ...
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Drive - Login</title>
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

            <h1 class="text-center mb-4 text-danger">User Login</h1>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-lg border-0">
                        <div class="card-body p-4 p-md-5">
                            <form action="backend/login_handler.php" method="POST">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <button type="submit" class="btn btn-danger w-100 mt-3">Login</button>
                                <p class="text-center mt-3 mb-0">Don't have an account? <a href="register.php" class="text-danger">Register here</a></p>
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
</body>
</html>