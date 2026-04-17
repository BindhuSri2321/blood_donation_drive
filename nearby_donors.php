<?php require 'backend/db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Donation Drive - Nearby Donors</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        #map {
            height: 600px;
            width: 100%;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm">...</nav>
    <main class="py-5">
        <div class="container">
            <h1 class="text-center mb-4 text-danger">🔍 Nearby Donors & Blood Banks</h1>
            <p class="text-center text-muted">A map showing registered donors/banks (that have provided location) near you.</p>

            <div id="map" class="shadow-lg"></div>
            
            <div class="card mt-4 p-3 shadow-sm">
                <p class="mb-0"><strong>Note:</strong> This page will search for **all** registered donors/banks, but for actual blood requests, the system filters by **blood group** and sends notifications to the closest match.</p>
            </div>

</div>
    </main>
    <footer class="bg-danger text-white py-3 mt-5">...</footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js"></script>
    
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AQ.Ab8RN6I1cjj0MLx3XIHDTWC3_TdWEvywyCOTuxghG3tj1OIt3Q"></script>

    <script>
        // Placeholder function, logic is in assets/js/app.js
        function initMap() {
            // This function is the callback for the Google Maps API script.
            // The actual logic is in app.js
        }
        
        document.addEventListener('DOMContentLoaded', () => {
            // We use the function inside app.js to fetch the user location and then draw the map
            // Pass a callback function to be executed after successful geolocation
            getGeolocation(null, null, null, (position) => {
                const userLat = position.coords.latitude;
                const userLon = position.coords.longitude;
                // Initialize the map with user location as center
                initGoogleMap(userLat, userLon);
            });
        });
    </script>
</body>
</html>