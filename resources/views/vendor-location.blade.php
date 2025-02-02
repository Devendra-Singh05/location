
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Shop Location</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css"/>
    @vite('resources/css/app.css')
</head>
<body>

    <div class="top-right">
        <div class="icon-container">
            <div class="search-icon" onclick="toggleSearch()">🔍</div>
            <div class="tooltip">Search Location</div>
        </div>
        <div class="search-box">
            <input type="text" id="searchBox" placeholder="Search Location..." onkeypress="handleSearch(event)">
        </div>
    </div>

    <div class="right-middle">
        <div class="icon-container">
            <div class="menu-icon" onclick="getLocation()">📍</div>
            <div class="tooltip">Add Your Location</div>
        </div>
        <div class="icon-container">
            <div class="menu-icon" onclick="saveLocation()">💾</div>
            <div class="tooltip">Save Location</div>
        </div>
        <div class="icon-container">
            <div class="menu-icon" onclick="startRoute()">🛣️</div>
            <div class="tooltip">Start Route</div>
        </div>
    </div>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <script>
        let map;
        let marker;
        let latitude, longitude;
        let allShops = [];
        let markers = [];
        let routeControl;

        function initMap() {
            map = L.map('map').setView([20.5937, 78.9629], 5);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            fetch('/get-shops')
                .then(response => response.json())
                .then(shops => {
                    allShops = shops;
                    shops.forEach(shop => {
                        const shopMarker = L.marker([shop.latitude, shop.longitude]).addTo(map)
                            .bindPopup(`<b>${shop.name}</b>`);
                        markers.push({ marker: shopMarker, name: shop.name.toLowerCase(), lat: shop.latitude, lon: shop.longitude });
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
                            .bindPopup("<b>Your Location</b>")
                            .openPopup();
                    }
                }, showError);
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        function showError(error) {
            alert("Error fetching location.");
        }

        function saveLocation() {
            const shopName = prompt("Enter Shop Name:");
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
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
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

        function startRoute() {
            if (!latitude || !longitude) {
                alert("Please get your location first.");
                return;
            }

            // Prompt user to select destination
            const destinationName = prompt("Enter destination name or coordinates (lat, lon):");
            if (!destinationName) {
                alert("Please enter a valid destination.");
                return;
            }

            let destinationLat, destinationLon;

            // Check if destination is a shop
            const shop = allShops.find(shop => shop.name.toLowerCase() === destinationName.toLowerCase());
            if (shop) {
                destinationLat = shop.latitude;
                destinationLon = shop.longitude;
            } else {
                // If not a shop, assume it's a coordinate input
                const coords = destinationName.split(',').map(coord => parseFloat(coord.trim()));
                if (coords.length === 2 && !isNaN(coords[0]) && !isNaN(coords[1])) {
                    destinationLat = coords[0];
                    destinationLon = coords[1];
                } else {
                    alert("Invalid destination.");
                    return;
                }
            }

            if (routeControl) {
                routeControl.setWaypoints([L.latLng(latitude, longitude), L.latLng(destinationLat, destinationLon)]);
            } else {
                routeControl = L.Routing.control({
                    waypoints: [L.latLng(latitude, longitude), L.latLng(destinationLat, destinationLon)],
                    routeWhileDragging: true
                }).addTo(map);
            }
        }

        function toggleSearch() {
            const searchBox = document.querySelector('.search-box');
            searchBox.style.display = searchBox.style.display === 'block' ? 'none' : 'block';
        }

        function handleSearch(event) {
            if (event.key === "Enter") {
                const query = document.getElementById("searchBox").value.toLowerCase();
                const found = markers.find(({ name }) => name.includes(query));

                if (found) {
                    map.setView([found.lat, found.lon], 15);
                    found.marker.openPopup();
                } else {
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.length > 0) {
                                const { lat, lon } = data[0];
                                map.setView([lat, lon], 15);
                                L.marker([lat, lon]).addTo(map)
                                    .bindPopup(`<b>${query}</b>`)
                                    .openPopup();
                            } else {
                                alert("No results found.");
                            }
                        })
                        .catch(error => console.error("Error:", error));
                }
            }
        }

        document.body.addEventListener('click', function(event) {
            if (!event.target.closest('.top-right') && !event.target.closest('.right-middle')) {
                document.querySelector('.search-box').style.display = 'none';
            }
        });

        initMap();
    </script>
</body>
</html>
