<?php require 'backend/db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Drive - About Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm">...</nav>
    <main class="py-5">
        <div class="container">
            <h1 class="text-center mb-4 text-danger">Our Mission: Connecting Lifelines</h1>
            
            <div class="card shadow-lg p-4 mb-5">
                <p class="lead text-center">The **Blood Donation Drive** system was created to minimize the time between an emergency blood requirement and finding a viable donor or blood bank. Every second counts when a life is on the line.</p>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <h2 class="h4 text-danger">How We Help Society</h2>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">**Real-Time Matching:** We use **browser geolocation** to instantly find the closest registered donor or blood bank within a 10km radius.</li>
                        <li class="list-group-item">**Reduce Search Time:** By automating the search for specific blood groups, we bypass manual calls and social media pleas.</li>
                        <li class="list-group-item">**Empower Donors:** Registered donors receive immediate, targeted notifications, making their contribution simple and effective.</li>
                        <li class="list-group-item">**Secure and Private:** Donor and requester contact information is only shared *after* a request is accepted.</li>
                    </ul>
                </div>

                <div class="col-md-6 mb-4">
                    <h2 class="h4 text-danger">Our Core Values</h2>
                    <blockquote class="blockquote">
                        <p class="mb-0">"The gift of blood is the gift of life."</p>
                        <footer class="blockquote-footer">Anon</footer>
                    </blockquote>
                    <p>We are driven by **transparency**, **speed**, and **community**. Our goal is to build a robust network of volunteers and institutions ready to respond to critical needs at a moment's notice.</p>
                    <a href="register.php" class="btn btn-danger mt-3">Join Our Community Today</a>
                </div>
            </div>

</div>
    </main>
    <footer class="bg-danger text-white py-3 mt-5">...</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>