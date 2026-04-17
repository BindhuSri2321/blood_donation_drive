// Global variables for map
let map;
let userMarker;
const donors = []; // Array to store fetched donor/bank data

/**
 * Captures the user's live coordinates using the Geolocation API.
 * @param {HTMLElement} latInput - The hidden input field for latitude.
 * @param {HTMLElement} lonInput - The hidden input field for longitude.
 * @param {string} btnId - The ID of the submit button to disable/enable.
 * @param {function} callback - Function to execute on successful geolocation (e.g., for map init).
 */
function getGeolocation(latInput, lonInput, btnId, callback = null) {
    const statusDiv = document.createElement('div');
    statusDiv.id = 'geo-status';
    statusDiv.className = 'alert alert-info mt-2';
    statusDiv.textContent = 'Attempting to fetch your location...';
    
    // Insert the status message near the form (assuming latInput is near the form)
    if (latInput && latInput.parentNode) {
        latInput.parentNode.insertBefore(statusDiv, latInput.nextSibling);
    }
    
    if (btnId) {
        document.getElementById(btnId).disabled = true; // Disable button while fetching
    }

    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                
                if (latInput && lonInput) {
                    latInput.value = lat;
                    lonInput.value = lon;
                }

                if (btnId) {
                    document.getElementById(btnId).disabled = false; // Enable button
                }

                if (statusDiv) {
                    statusDiv.className = 'alert alert-success mt-2';
                    statusDiv.textContent = 'Location captured successfully! Lat: ' + lat.toFixed(4) + ', Lon: ' + lon.toFixed(4);
                }

                if (callback) {
                    callback(position);
                }
            },
            (error) => {
                let message = 'Error: Geolocation failed. ';
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        message += "User denied the request for Geolocation. Please allow location access.";
                        break;
                    case error.POSITION_UNAVAILABLE:
                        message += "Location information is unavailable.";
                        break;
                    case error.TIMEOUT:
                        message += "The request to get user location timed out.";
                        break;
                    case error.UNKNOWN_ERROR:
                        message += "An unknown error occurred.";
                        break;
                }
                
                if (btnId) {
                    document.getElementById(btnId).disabled = false; // Still allow submission, but data will be missing
                }
                
                if (statusDiv) {
                    statusDiv.className = 'alert alert-warning mt-2';
                    statusDiv.textContent = message + " (Proceeding without location. Matching will be limited.)";
                }

                console.error(message);
            }
        );
    } else {
        if (btnId) {
            document.getElementById(btnId).disabled = false;
        }
        if (statusDiv) {
             statusDiv.className = 'alert alert-warning mt-2';
             statusDiv.textContent = "Geolocation is not supported by this browser. Matching will be limited.";
        }
    }
}


/**
 * Initializes and draws the Google Map.
 * @param {number} userLat - User's current latitude.
 * @param {number} userLon - User's current longitude.
 */
function initGoogleMap(userLat, userLon) {
    const mapOptions = {
        center: { lat: userLat, lng: userLon },
        zoom: 12, // Zoom level for a 10km radius view
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById('map'), mapOptions);

    // 1. User Marker (Red Pin for Blood Drive theme)
    userMarker = new google.maps.Marker({
        position: { lat: userLat, lng: userLon },
        map: map,
        icon: {
            path: google.maps.SymbolPath.CIRCLE,
            fillColor: '#dc3545', // Red
            fillOpacity: 1,
            strokeColor: 'white',
            strokeWeight: 2,
            scale: 7
        },
        title: 'Your Current Location'
    });

    // 2. Fetch and Draw Nearby Donors
    fetchAndDrawDonors(userLat, userLon);
}


/**
 * Fetches nearby donors/banks from the PHP backend.
 * @param {number} userLat - User's current latitude.
 * @param {number} userLon - User's current longitude.
 */
function fetchAndDrawDonors(userLat, userLon) {
    // Note: Since we are fetching ALL *registered* donors/banks on this map, 
    // we don't pass a specific blood group, but the PHP script will need to adjust.
    // For this example, we'll fetch all eligible for *any* request (using a common group like 'A+').
    
    // In a real system, you might fetch all and filter client-side, or use a general 'all' query.
    // For now, let's just show *all* registered banks/donors for the map view.
    
    // In the final PHP, we can modify 'fetch_donors.php' to accept an 'all' flag.
    // For simplicity in this front-end script, we'll assume we can fetch all.
    
    const apiUrl = `backend/fetch_donors.php?lat=${userLat}&lon=${userLon}&bg=ANY`; // 'ANY' to signify fetching all nearby

    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Backend Error:", data.error);
                return;
            }

            // Draw markers for each nearby donor/bank
            data.forEach(donor => {
                const donorLat = parseFloat(donor.latitude);
                const donorLon = parseFloat(donor.longitude);

                const iconColor = donor.user_type === 'bank' ? '#007bff' : '#28a745'; // Blue for Bank, Green for Donor

                const marker = new google.maps.Marker({
                    position: { lat: donorLat, lng: donorLon },
                    map: map,
                    icon: {
                        path: google.maps.SymbolPath.DROP,
                        fillColor: iconColor,
                        fillOpacity: 0.8,
                        strokeColor: 'black',
                        strokeWeight: 1,
                        scale: 8
                    },
                    title: `${donor.name} (${donor.user_type.charAt(0).toUpperCase() + donor.user_type.slice(1)})`
                });

                // Info window for details
                const contentString = `
                    <div id="content">
                        <h5 class="mb-1">${donor.name}</h5>
                        <p class="mb-0"><strong>Type:</strong> ${donor.user_type}</p>
                        <p class="mb-0"><strong>Distance:</strong> ${donor.distance_km} km</p>
                        <p class="mb-0"><strong>Contact:</strong> ${donor.phone ? donor.phone : 'Not Available'}</p>
                    </div>
                `;

                const infoWindow = new google.maps.InfoWindow({
                    content: contentString,
                });

                marker.addListener("click", () => {
                    infoWindow.open({
                        anchor: marker,
                        map,
                    });
                });

                donors.push(marker);
            });
            console.log(`Successfully mapped ${data.length} nearby donors/banks.`);
        })
        .catch(error => {
            console.error('Error fetching donor data:', error);
        });
}

// Ensure the functions are globally accessible if called from an external script tag (like the Google Maps API)
window.initMap = function() {}; // Placeholder will be overridden by the function in nearby_donors.php