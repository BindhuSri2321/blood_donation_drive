<?php
require 'db_connect.php';

header('Content-Type: application/json');

// --- Haversine Formula Implementation ---
// Finds the distance between two points in kilometers
function getDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371; // Radius of the earth in km

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $distance = $earthRadius * $c; // Distance in km
    
    return $distance;
}
// ----------------------------------------

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $request_id = isset($_GET['request_id']) ? (int)$_GET['request_id'] : null;
    $target_lat = isset($_GET['lat']) ? (float)$_GET['lat'] : null;
    $target_lon = isset($_GET['lon']) ? (float)$_GET['lon'] : null;
    $target_bg = isset($_GET['bg']) ? $conn->real_escape_string($_GET['bg']) : null;

    if ($target_lat === null || $target_lon === null || $target_bg === null) {
        echo json_encode(['error' => 'Missing location or blood group parameters.']);
        exit();
    }

    $nearby_donors = [];
    $radius_km = 10; // Search within 10 km radius

    // Fetch all potential donors/banks with matching blood group and coordinates
    $sql = "SELECT id, name, user_type, latitude, longitude, phone 
            FROM users 
            WHERE (user_type = 'donor' OR user_type = 'bank') 
            AND blood_group = ? 
            AND latitude IS NOT NULL 
            AND longitude IS NOT NULL";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $target_bg);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($donor = $result->fetch_assoc()) {
        $distance = getDistance($target_lat, $target_lon, $donor['latitude'], $donor['longitude']);
        
        if ($distance <= $radius_km) {
            // Found a nearby donor/bank
            $donor['distance_km'] = round($distance, 2);
            $donor['action_link'] = "#"; // Placeholder for 'Accept/Reject' notification logic
            $nearby_donors[] = $donor;
        }
    }

    $stmt->close();
    $conn->close();

    echo json_encode($nearby_donors);

} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>