<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Shop Location</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
</head>
<body>
    <h2>Register Your Shop and Set Location</h2>
    
    <label for="shopName">Shop Name:</label>
    <input type="text" id="shopName" placeholder="Enter Shop Name">
    
    <button onclick="getLocation()">Add Your Location</button>
    <button onclick="saveLocation()">Save Location</button>
    
    <div id="map" style="height: 400px; width: 100%;"></div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        let map;
        let marker;
        let latitude, longitude;

        function initMap() {
    map = L.map('map').setView([20.5937, 78.9629], 5); // Default location (India)

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // सभी saved shops को लोड करें और map पर दिखाएं
    fetch('/get-shops')
        .then(response => response.json())
        .then(shops => {
            shops.forEach(shop => {
                L.marker([shop.latitude, shop.longitude]).addTo(map)
                    .bindPopup(`<b>${shop.name}</b><br>Latitude: ${shop.latitude}<br>Longitude: ${shop.longitude}`)
                    .openPopup();
            });
        })
        .catch(error => console.error('Error fetching shops:', error));
}
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    latitude = position.coords.latitude;
                    longitude = position.coords.longitude;

                    map.setView([latitude, longitude], 13);

                    if (marker) {
                        marker.setLatLng([latitude, longitude]);
                    } else {
                        marker = L.marker([latitude, longitude]).addTo(map)
                            .bindPopup("<b>Your Shop Location</b>")
                            .openPopup();
                    }
                }, showError);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
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

        function saveLocation() {
    const shopName = document.getElementById("shopName").value;
    if (!shopName) {
        alert("Please enter a shop name.");
        return;
    }
    if (!latitude || !longitude) {
        alert("Please get your location first.");
        return;
    }

    const data = {
        name: shopName,
        latitude: latitude,
        longitude: longitude,
        _token: '{{ csrf_token() }}'
    };

    fetch('/save-shop', {
        method: 'POST', // ✅ POST method ensure करें
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}' // ✅ CSRF Token Include करें
        },
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .then(data => {
        alert("Shop location saved successfully!");
        location.reload();
    })
    .catch(error => console.error("Error:", error));
}


        initMap();
    </script>
</body>
</html>
