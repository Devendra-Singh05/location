<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <style>
    #map {
        width: 100%;
        height: 500px;
    }
    </style>
</head>

<body>

    <h2>User Location</h2>

    <!-- Map Container -->
    <div id="map"></div>

    <!-- Form to Save Location -->
    <form action="{{ route('save.vendor') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="latitude">latitude</label>
            <input type="text" name="latitude" id="latitude">
        </div>
        <div class="mb-3">
            <label for="longitude">longitude</label>
            <input type="text" name="longitude" id="longitude">
        </div>
        <div>
            <label for="number">Number</label>
            <input type="text" placeholder="enter number" class="form-control" name="number">
        </div>
        <button type="submit" style="cursor: pointer;">💾 Save Location</button>
    </form>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
    let map = L.map('map').setView([20.5937, 78.9629], 5);
    let marker;

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Get Current Location
    navigator.geolocation.getCurrentPosition(position => {
        let lat = position.coords.latitude;
        let lon = position.coords.longitude;
        map.setView([lat, lon], 13);

        marker = L.marker([lat, lon]).addTo(map)
            .bindPopup("<b>Your Location</b>")
            .openPopup();

        // Set Hidden Input Values
        document.getElementById("latitude").value = lat;
        document.getElementById("longitude").value = lon;
    });
    </script>
</body>


</html>