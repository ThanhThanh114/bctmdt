// Simple and reliable map initialization
    function initMap() {
        try {
            console.log('Initializing map...');

            // Check if Leaflet is loaded
            if (typeof L === 'undefined') {
                throw new Error('Leaflet library not loaded');
            }

            // Get station data
            const mapStations = window.mapStations || [];
            const tripData = window.tripData || {};

            console.log('Stations:', mapStations);
            console.log('Trip data:', tripData);

            if (!mapStations || mapStations.length < 2) {
                console.warn('Not enough stations');
                return;
            }

            // Filter valid stations
            const validStations = mapStations.filter(station => {
                const lat = parseFloat(station.lat);
                const lng = parseFloat(station.lng);
                return !isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180;
            });

            if (validStations.length < 2) {
                console.error('No valid coordinates');
                return;
            }

            // Check map container
            const mapContainer = document.getElementById('map');
            if (!mapContainer) {
                throw new Error('Map container not found');
            }

            // Initialize map
            const firstStation = validStations[0];
            const map = L.map('map').setView([firstStation.lat, firstStation.lng], 12);

            // Add tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Calculate arrival times
            const arrivalTimes = calculateSimpleArrivalTimes(validStations, tripData);

            // Add markers
            validStations.forEach((station, index) => {
                const marker = L.marker([station.lat, station.lng]).addTo(map);

                let popupContent = `<div style="font-family: Inter, sans-serif; max-width: 220px;">
                    <h6 style="color: #2563eb; margin-bottom: 8px; font-weight: 600;">
                        <i class="fas fa-map-marker-alt"></i> ${station.name}
                    </h6>`;

                if (station.address) {
                    popupContent += `<p style="margin: 4px 0; font-size: 14px;"><strong>Địa chỉ:</strong> ${station.address}</p>`;
                }
                if (station.province) {
                    popupContent += `<p style="margin: 4px 0; font-size: 14px;"><strong>Tỉnh:</strong> ${station.province}</p>`;
                }

                if (arrivalTimes && arrivalTimes[index]) {
                    const timeColor = index === 0 ? '#059669' : (index === validStations.length - 1 ? '#dc2626' : '#7c3aed');
                    popupContent += `<p style="margin: 8px 0 0 0; font-size: 13px; color: ${timeColor}; border-top: 1px solid #e5e7eb; padding-top: 6px;">
                        <i class="fas fa-clock"></i> <strong>Thời gian:</strong><br>
                        ${arrivalTimes[index].estimatedArrival}
                    </p>`;
                }

                popupContent += `</div>`;
                marker.bindPopup(popupContent);
            });

            // Draw route
            const routeCoords = validStations.map(station => [station.lat, station.lng]);
            const routeLine = L.polyline(routeCoords, { color: 'blue', weight: 3 }).addTo(map);

            // Add route info control
            if (arrivalTimes && arrivalTimes.length > 1) {
                const routeInfoControl = L.control({ position: 'topright' });
                routeInfoControl.onAdd = function() {
                    const div = L.DomUtil.create('div', 'route-info');
                    div.style.cssText = `
                        background: white;
                        padding: 12px 15px;
                        border-radius: 8px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                        font-family: Inter, sans-serif;
                        font-size: 14px;
                        min-width: 220px;
                        max-width: 300px;
                    `;

                    const totalDistance = arrivalTimes[arrivalTimes.length - 1].cumulativeDistance;
                    const totalDuration = arrivalTimes[arrivalTimes.length - 1].cumulativeTime;
                    const distanceKm = totalDistance.toFixed(1);
                    const durationHours = Math.floor(totalDuration / 3600);
                    const durationMinutes = Math.floor((totalDuration % 3600) / 60);

                    div.innerHTML = `
                        <div style="border-bottom: 2px solid #2563eb; padding-bottom: 8px; margin-bottom: 8px;">
                            <strong style="color: #2563eb;">Thông tin tuyến đường</strong>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <i class="fas fa-road" style="color: #059669; width: 18px;"></i>
                            <strong>Khoảng cách:</strong> ${distanceKm} km
                        </div>
                        <div style="margin-bottom: 5px;">
                            <i class="fas fa-clock" style="color: #dc2626; width: 18px;"></i>
                            <strong>Thời gian:</strong> ${durationHours}h ${durationMinutes}m
                        </div>
                        <div style="border-top: 1px solid #e5e7eb; padding-top: 8px; margin-top: 8px;">
                            <strong style="font-size: 12px; color: #6b7280;">Thời gian đến dự kiến:</strong>
                            <div style="font-size: 12px; margin-top: 4px;">
                                ${arrivalTimes.slice(1).map((time, idx) =>
                                    `<div>• Trạm ${idx + 2}: ${time.estimatedArrival}</div>`
                                ).join('')}
                            </div>
                        </div>
                    `;
                    return div;
                };
                routeInfoControl.addTo(map);
            }

            // Fit bounds
            map.fitBounds(routeLine.getBounds(), { padding: [20, 20] });

            console.log('Map initialized successfully');

        } catch (error) {
            console.error('Map error:', error);
            const mapContainer = document.getElementById('map');
            if (mapContainer) {
                mapContainer.innerHTML = `
                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #dc2626; font-family: Inter, sans-serif; text-align: center; padding: 20px;">
                        <div>
                            <i class="fas fa-exclamation-triangle fa-2x" style="margin-bottom: 10px;"></i>
                            <div>Không thể tải bản đồ</div>
                            <small style="color: #6b7280;">Vui lòng thử lại sau</small>
                        </div>
                    </div>
                `;
            }
        }
    }

    // OSRM API Integration for Route Calculation and Arrival Time Estimation
    async function getRouteWithTiming(waypoints) {
        try {
            if (!waypoints || waypoints.length < 2) {
                console.warn('Not enough waypoints for routing');
                return null;
            }

            // Create coordinate string for OSRM API
            const coordinates = waypoints.map(wp => `${wp.lng},${wp.lat}`).join(';');

            // Call OSRM API with full routing details
            const response = await fetch(
                `https://router.project-osrm.org/route/v1/driving/${coordinates}?overview=full&geometries=geojson&steps=true&annotations=duration,distance`
            );

            if (!response.ok) {
                throw new Error(`OSRM API error: ${response.status}`);
            }

            const data = await response.json();

            if (data.code === 'Ok' && data.routes && data.routes.length > 0) {
                const route = data.routes[0];
                return {
                    coordinates: route.geometry.coordinates,
                    distance: route.distance, // meters
                    duration: route.duration, // seconds
                    legs: route.legs || []
                };
            }

            console.warn('No valid routes found');
            return null;
        } catch (error) {
            console.error('OSRM API error:', error);
            return null;
        }
    }

    // Calculate Estimated Arrival Times using OSRM route data
    function calculateArrivalTimes(routeData, departureTime, averageSpeedKmh = 50) {
        if (!routeData || !departureTime) return null;

        try {
            console.log('Calculating arrival times using OSRM data');
            const departureDate = new Date(departureTime);

            if (isNaN(departureDate.getTime())) {
                console.error('Invalid departure time:', departureTime);
                return null;
            }

            let cumulativeTime = 0;
            const arrivalTimes = [];

            // Calculate arrival time for each segment using OSRM legs
            routeData.legs.forEach((leg, index) => {
                cumulativeTime += leg.duration;
                const arrivalTime = new Date(departureDate.getTime() + (cumulativeTime * 1000));

                arrivalTimes.push({
                    segmentIndex: index,
                    distance: leg.distance,
                    duration: leg.duration,
                    cumulativeTime: cumulativeTime,
                    cumulativeDistance: (routeData.distance / 1000) * ((cumulativeTime / routeData.duration) || 0), // Approximate distance
                    estimatedArrival: arrivalTime.toLocaleString('vi-VN', {
                        hour: '2-digit',
                        minute: '2-digit'
                    })
                });
            });

            console.log('OSRM arrival times calculated:', arrivalTimes);
            return arrivalTimes;
        } catch (error) {
            console.error('Error calculating OSRM arrival times:', error);
            return null;
        }
    }

    /**
     * Initialize Interactive Map with Routing and Timing
     *
     * Main function that orchestrates:
     * 1. Data validation and filtering
     * 2. OSRM API route fetching
     * 3. Arrival time calculations
     * 4. Leaflet map creation with markers and routes
     * 5. Error handling and fallbacks
     *
     * Features:
     * - Color-coded markers (green/red/yellow)
     * - Real-time route calculation
     * - Estimated arrival times
     * - Responsive design
     * - Comprehensive error handling
     */
    async function initMap() {
        try {
            console.log('Starting map initialization...');

            // Check if Leaflet is loaded
            if (typeof L === 'undefined') {
                throw new Error('Leaflet library not loaded');
            }

            const mapStations = window.mapStations || [];
            const tripData = window.tripData || {};

            console.log('Map stations data:', mapStations);
            console.log('Trip data:', tripData);

            if (!mapStations || mapStations.length < 2) {
                console.warn('Not enough stations for routing');
                return;
            }

            // Filter and validate stations
            const validStations = mapStations.filter(station => {
                const lat = parseFloat(station.lat);
                const lng = parseFloat(station.lng);
                const isValid = !isNaN(lat) && !isNaN(lng) &&
                               lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180;
                console.log(`Station ${station.name}: lat=${lat}, lng=${lng}, valid=${isValid}`);
                return isValid;
            });

            console.log('Valid stations:', validStations);

            if (validStations.length < 2) {
                console.error('Insufficient valid station coordinates');
                return;
            }

            // Check if map container exists
            const mapContainer = document.getElementById('map');
            if (!mapContainer) {
                throw new Error('Map container not found');
            }

            // Initialize Leaflet map
            const firstStation = validStations[0];
            console.log('Creating map at:', [firstStation.lat, firstStation.lng]);
            const map = L.map('map').setView([firstStation.lat, firstStation.lng], 12);

            // Add OpenStreetMap tiles with error handling
            try {
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);
                console.log('Tile layer added successfully');
            } catch (tileError) {
                console.error('Error adding tile layer:', tileError);
                throw new Error('Failed to load map tiles');
            }

            // Create waypoints for routing
            const waypoints = validStations.map(station => ({
                lat: parseFloat(station.lat),
                lng: parseFloat(station.lng)
            }));

            // Get route data from OSRM
            console.log('Fetching route from OSRM...');
            const routeData = await getRouteWithTiming(waypoints);

            let routeLine, routeInfoControl;

            if (routeData) {
                // Convert coordinates and draw route
                const routeCoords = routeData.coordinates.map(coord => [coord[1], coord[0]]);
                routeLine = L.polyline(routeCoords, {
                    color: '#2563eb',
                    weight: 4,
                    opacity: 0.8
                }).addTo(map);

                // Calculate and display arrival times
                const arrivalTimes = calculateArrivalTimes(routeData, tripData.departureTime, tripData.averageSpeed);

                // Add route information control
                routeInfoControl = L.control({ position: 'topright' });
                routeInfoControl.onAdd = function() {
                    const div = L.DomUtil.create('div', 'route-info');
                    div.style.cssText = `
                        background: white;
                        padding: 12px 15px;
                        border-radius: 8px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                        font-family: Inter, sans-serif;
                        font-size: 14px;
                        min-width: 220px;
                        max-width: 300px;
                    `;

                    const distanceKm = (routeData.distance / 1000).toFixed(1);
                    const durationHours = Math.floor(routeData.duration / 3600);
                    const durationMinutes = Math.floor((routeData.duration % 3600) / 60);

                    div.innerHTML = `
                        <div style="border-bottom: 2px solid #2563eb; padding-bottom: 8px; margin-bottom: 8px;">
                            <strong style="color: #2563eb;">Thông tin tuyến đường</strong>
                        </div>
                        <div style="margin-bottom: 5px;">
                            <i class="fas fa-road" style="color: #059669; width: 18px;"></i>
                            <strong>Khoảng cách:</strong> ${distanceKm} km
                        </div>
                        <div style="margin-bottom: 5px;">
                            <i class="fas fa-clock" style="color: #dc2626; width: 18px;"></i>
                            <strong>Thời gian:</strong> ${durationHours}h ${durationMinutes}m
                        </div>
                        <div style="margin-bottom: 5px;">
                            <i class="fas fa-tachometer-alt" style="color: #7c3aed; width: 18px;"></i>
                            <strong>Tốc độ TB:</strong> ${tripData.averageSpeed || 50} km/h
                        </div>
                        ${arrivalTimes && arrivalTimes.length > 1 ? `
                        <div style="border-top: 1px solid #e5e7eb; padding-top: 8px; margin-top: 8px;">
                            <strong style="font-size: 12px; color: #6b7280;">Thời gian đến dự kiến:</strong>
                            <div style="font-size: 12px; margin-top: 4px;">
                                ${arrivalTimes.slice(1).map((time, idx) =>
                                    `<div>• Trạm ${idx + 2}: ${time.estimatedArrival}</div>`
                                ).join('')}
                            </div>
                        </div>` : ''}
                    `;
                    return div;
                };
                routeInfoControl.addTo(map);

                console.log('Route rendered with timing information');
            } else {
                // Fallback: draw straight line if routing fails
                console.warn('OSRM routing failed, using straight line fallback');
                const straightCoords = waypoints.map(wp => [wp.lat, wp.lng]);
                routeLine = L.polyline(straightCoords, {
                    color: '#dc2626',
                    weight: 3,
                    opacity: 0.7,
                    dashArray: '10, 10'
                }).addTo(map);

                // Add warning control
                routeInfoControl = L.control({ position: 'topright' });
                routeInfoControl.onAdd = function() {
                    const div = L.DomUtil.create('div', 'route-warning');
                    div.style.cssText = `
                        background: #fef3c7;
                        padding: 12px 15px;
                        border-radius: 8px;
                        border: 1px solid #f59e0b;
                        font-family: Inter, sans-serif;
                        font-size: 14px;
                        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                    `;
                    div.innerHTML = `
                        <i class="fas fa-exclamation-triangle" style="color: #d97706;"></i>
                        <strong>Thông báo:</strong><br>
                        <small>Hiển thị đường thẳng (không có dữ liệu định tuyến thực tế)</small>
                    `;
                    return div;
                };
                routeInfoControl.addTo(map);
            }

            // Add markers with proper color coding
            const markers = [];
            validStations.forEach((station, index) => {
                try {
                    const icon = getMarkerIcon(station.type);
                    const marker = L.marker([station.lat, station.lng], { icon }).addTo(map);

                // Enhanced popup with station details
                const popupContent = `
                    <div style="font-family: Inter, sans-serif; max-width: 220px;">
                        <h6 style="color: #2563eb; margin-bottom: 8px; font-weight: 600;">
                            <i class="fas fa-map-marker-alt"></i> ${station.name}
                        </h6>
                        ${station.address ? `<p style="margin: 4px 0; font-size: 14px;"><strong>Địa chỉ:</strong> ${station.address}</p>` : ''}
                        ${station.province ? `<p style="margin: 4px 0; font-size: 14px;"><strong>Tỉnh:</strong> ${station.province}</p>` : ''}
                        <p style="margin: 4px 0; font-size: 14px;"><strong>Loại:</strong> ${getStationTypeText(station.type)}</p>
                        ${index === validStations.length - 1 && arrivalTimes ? `
                        <p style="margin: 8px 0 0 0; font-size: 13px; color: #dc2626; border-top: 1px solid #e5e7eb; padding-top: 6px;">
                            <i class="fas fa-clock"></i> <strong>Đến dự kiến:</strong><br>
                            ${arrivalTimes[arrivalTimes.length - 1]?.estimatedArrival || 'N/A'}
                        </p>` : ''}
                    </div>
                `;

                marker.bindPopup(popupContent);
                markers.push(marker);
                console.log(`Marker added for station: ${station.name}`);
                } catch (markerError) {
                    console.error(`Error creating marker for station ${station.name}:`, markerError);
                }
            });

            // Fit map bounds
            try {
                const bounds = routeLine.getBounds();
                map.fitBounds(bounds, { padding: [20, 20] });
            } catch (error) {
                console.error('Error fitting bounds:', error);
                // Fallback to marker bounds
                const markerGroup = L.featureGroup(markers);
                map.fitBounds(markerGroup.getBounds(), { padding: [20, 20] });
            }

            console.log('Map initialization completed successfully');

        } catch (error) {
            console.error('Map initialization error:', error);

            // Show error message in map container
            const mapContainer = document.getElementById('map');
            if (mapContainer) {
                mapContainer.innerHTML = `
                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #dc2626; font-family: Inter, sans-serif; text-align: center; padding: 20px;">
                        <div>
                            <i class="fas fa-exclamation-triangle fa-2x" style="margin-bottom: 10px;"></i>
                            <div>Không thể tải bản đồ</div>
                            <small style="color: #6b7280;">Vui lòng thử lại sau</small>
                            <br><small style="color: #9ca3af; font-size: 12px;">Lỗi: ${error.message}</small>
                        </div>
                    </div>
                `;
            }
        }
    }

    /**
     * Get Marker Icon Based on Station Type
     *
     * Returns appropriate Leaflet icon for different station types:
     * - departure: Green marker
     * - arrival: Red marker
     * - intermediate: Yellow marker
     *
     * @param {string} type - Station type ('departure', 'arrival', 'intermediate')
     * @returns {L.Icon} Leaflet icon object
     */
    function getMarkerIcon(type) {
        let iconUrl;
        switch(type) {
            case 'departure':
                iconUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png';
                break;
            case 'arrival':
                iconUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png';
                break;
            default:
                iconUrl = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-yellow.png';
                break;
        }
        return L.icon({
            iconUrl: iconUrl,
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],      // Icon dimensions
            iconAnchor: [12, 41],    // Point of icon that corresponds to marker position
            popupAnchor: [1, -34],   // Point from which popup opens relative to iconAnchor
            shadowSize: [41, 41]     // Shadow dimensions
        });
    }

    /**
     * Get Vietnamese Text for Station Type
     *
     * Translates station type to Vietnamese for UI display
     *
     * @param {string} type - Station type in English
     * @returns {string} Station type in Vietnamese
     */
    function getStationTypeText(type) {
        switch(type) {
            case 'departure': return 'Điểm khởi hành';
            case 'arrival': return 'Điểm đến';
            default: return 'Trạm trung chuyển';
        }
    }

    // Initialize map when page loads
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(initMap, 500); // Small delay to ensure everything is loaded
    });

    // Fallback for already loaded pages
    if (document.readyState === 'complete') {
        setTimeout(initMap, 500);
    }
