<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Location</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
</head>
<body>
    <h2>Get and Save Your Location</h2>
    <button onclick="getLocation()">Get My Location</button>
    <div id="map" style="height: 400px; width: 100%;"></div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        let map;
        let marker;

        // Initialize the map
        function initMap() {
            map = L.map('map').setView([20.5937, 78.9629], 5); // Default map center (India)

            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Fetch saved locations and plot them on the map
            fetch('/get-locations')
                .then(response => response.json())
                .then(locations => {
                    locations.forEach(location => {
                        L.marker([location.latitude, location.longitude]).addTo(map)
                            .bindPopup(`<b>Saved Location</b><br>Latitude: ${location.latitude}<br>Longitude: ${location.longitude}`)
                            .openPopup();
                    });
                })
                .catch(error => console.error('Error fetching locations:', error));
        }

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function showPosition(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;

            // Set the view to the current location
            map.setView([latitude, longitude], 13);

            // Add a marker at the current location
            if (marker) {
                marker.setLatLng([latitude, longitude]);
            } else {
                marker = L.marker([latitude, longitude]).addTo(map)
                    .bindPopup("<b>Your Location</b>")
                    .openPopup();
            }

            // Save the location to the backend via AJAX
            saveLocationToBackend(latitude, longitude);
        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
        }

        // Function to send location to backend
        function saveLocationToBackend(latitude, longitude) {
            const data = {
                latitude: latitude,
                longitude: longitude,
                _token: '{{ csrf_token() }}'  // CSRF Token for security
            };

            fetch('/save-location', {
                method: 'POST',  // Make sure this is 'POST' method
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data),
            })
            .then(response => response.json())
            .then(data => {
                alert("Location saved successfully!");
            })
            .catch(error => {
                console.error("Error:", error);
            });
        }

        // Call initMap to initialize the map
        initMap();
    </script>
</body>
</html>
