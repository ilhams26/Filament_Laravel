<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Peta Cabang Toko Ilham</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
        }

        #map {
            height: 100vh;
        }
    </style>
</head>

<body>
    <div id="map"></div>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const branches = @json($branches);
        const map = L.map('map').setView([-6.2, 106.82], 12);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        const markers = [];
        branches.forEach(b => {
            const m = L.marker([b.latitude, b.longitude]).addTo(map);
            m.bindPopup('<b>' + b.nama + '</b><br>' + b.alamat);
            markers.push(m);
        });
        if (markers.length) {
            map.fitBounds(L.featureGroup(markers).getBounds().pad(0.2));
        }
    </script>
</body>

</html>