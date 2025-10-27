let map;
let markers = [];
let routePath = [];

function initMap() {
    // Get data from the page
    const mapStations = window.mapStations || [];
    const departureStationId = window.departureStationId || '';
    const arrivalStationId = window.arrivalStationId || '';

    if (mapStations.length < 2) {
        return;
    }

    // Initialize map centered on the first station
    const firstStation = mapStations[0];
    map = new google.maps.Map(document.getElementById('map'), {
        center: { lat: parseFloat(firstStation.lat), lng: parseFloat(firstStation.lng) },
        zoom: 10,
        styles: [
            {
                featureType: 'poi',
                stylers: [{ visibility: 'off' }]
            },
            {
                featureType: 'transit',
                stylers: [{ visibility: 'off' }]
            }
        ]
    });

    // Add markers for all stations
    mapStations.forEach((station, index) => {
        const marker = new google.maps.Marker({
            position: { lat: parseFloat(station.lat), lng: parseFloat(station.lng) },
            map: map,
            title: station.name,
            icon: getMarkerIcon(station.type),
            animation: google.maps.Animation.DROP
        });

        // Add info window
        const infoWindow = new google.maps.InfoWindow({
            content: `
                <div style="max-width: 250px;">
                    <h6>${station.name}</h6>
                    <p><strong>Địa chỉ:</strong> ${station.address || 'Chưa cập nhật'}</p>
                    <p><strong>Tỉnh thành:</strong> ${station.province}</p>
                    <p><strong>Loại:</strong> ${getStationTypeText(station.type)}</p>
                </div>
            `
        });

        marker.addListener('click', () => {
            infoWindow.open(map, marker);
        });

        markers.push(marker);

        // Add to route path
        routePath.push({ lat: parseFloat(station.lat), lng: parseFloat(station.lng) });
    });

    // Draw route line
    if (routePath.length >= 2) {
        const routeLine = new google.maps.Polyline({
            path: routePath,
            geodesic: true,
            strokeColor: '#007bff',
            strokeOpacity: 1.0,
            strokeWeight: 3,
            icons: [{
                icon: {
                    path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                    scale: 3,
                    strokeColor: '#007bff'
                },
                offset: '100%'
            }]
        });

        routeLine.setMap(map);

        // Fit map to show all markers
        const bounds = new google.maps.LatLngBounds();
        routePath.forEach(point => bounds.extend(point));
        map.fitBounds(bounds);
    }
}

function getMarkerIcon(type) {
    switch(type) {
        case 'departure':
            return 'http://maps.google.com/mapfiles/ms/icons/green-dot.png';
        case 'arrival':
            return 'http://maps.google.com/mapfiles/ms/icons/red-dot.png';
        default:
            return 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
    }
}

function getStationTypeText(type) {
    switch(type) {
        case 'departure':
            return 'Điểm khởi hành';
        case 'arrival':
            return 'Điểm đến';
        default:
            return 'Trạm trung chuyển';
    }
}

function refreshMap() {
    if (typeof google !== 'undefined' && map) {
        google.maps.event.trigger(map, 'resize');
        if (routePath.length >= 2) {
            const bounds = new google.maps.LatLngBounds();
            routePath.forEach(point => bounds.extend(point));
            map.fitBounds(bounds);
        }
    }
}

// Auto refresh map every 5 minutes
setInterval(refreshMap, 300000);

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Check if Google Maps API is loaded
    if (typeof google !== 'undefined') {
        initMap();
    }
});
