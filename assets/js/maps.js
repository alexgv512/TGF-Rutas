// assets/js/maps.js

let map;
let routePoints = [];
let routeLine;
let markers = [];

// Código para inicializar el mapa con Leaflet
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el mapa si existe el elemento
    if (document.getElementById('map')) {
        initializeMap();
    }
    
    // Validación del formulario
    const rutaForm = document.getElementById('rutaForm');
    if (rutaForm) {
        rutaForm.addEventListener('submit', function(e) {
            let mediosSeleccionados = document.querySelectorAll('input[name="medios[]"]:checked');
            
            if (mediosSeleccionados.length === 0) {
                e.preventDefault();
                alert('Debes seleccionar al menos un medio de transporte');
                return false;
            }
            
            // Validar que hay al menos 2 puntos en la ruta si se requiere
            if (routePoints.length < 2) {
                const confirmProceed = confirm('No has trazado una ruta en el mapa. ¿Deseas continuar sin ruta?');
                if (!confirmProceed) {
                    e.preventDefault();
                    return false;
                }
            }
            
            // Añadir coordenadas de ruta al formulario
            addRouteCoordinatesToForm();
        });
    }
});

function initializeMap() {
    // Inicializar el mapa centrado en España
    map = L.map('map').setView([40.416775, -3.703790], 6);
    
    // Añadir capa de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    // Evento de clic en el mapa para añadir puntos de ruta
    map.on('click', function(e) {
        addRoutePoint(e.latlng);
    });
    
    // Botones de control
    addMapControls();
}

function addRoutePoint(latlng) {
    // Añadir punto a la lista
    routePoints.push(latlng);
    
    // Crear marcador
    const marker = L.marker(latlng).addTo(map);
    
    // Personalizar el marcador según su posición
    if (routePoints.length === 1) {
        marker.bindPopup('Inicio de la ruta').openPopup();
    } else {
        marker.bindPopup(`Punto ${routePoints.length}`);
    }
    
    markers.push(marker);
    
    // Actualizar la ruta según el modo seleccionado
    if (useRoadRouting && routePoints.length >= 2) {
        updateRoadRoute(); // Ahora es async pero no necesitamos await aquí
    } else {
        updateRouteLine();
        updateRouteDistance();
    }
}

async function updateRoadRoute() {
    // Eliminar ruta anterior si existe
    if (routeLine) {
        map.removeLayer(routeLine);
        routeLine = null;
    }
    
    // Crear nueva ruta si hay al menos 2 puntos
    if (routePoints.length >= 2) {
        try {
            // Mostrar indicador de carga
            showLoadingIndicator();
            
            // Preparar coordenadas para OpenRouteService
            const coordinates = routePoints.map(point => [point.lng, point.lat]);
            
            // Llamar a la API de OpenRouteService
            const response = await fetch('https://api.openrouteservice.org/v2/directions/driving-car', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json, application/geo+json, application/gpx+xml, img/png; charset=utf-8',
                    'Authorization': '5b3ce3597851110001cf6248b4c6b4e7c7f44f1a95a0460fa59b9226', // API key gratuita
                    'Content-Type': 'application/json; charset=utf-8'
                },
                body: JSON.stringify({
                    coordinates: coordinates,
                    format: 'geojson'
                })
            });
            
            if (response.ok) {
                const data = await response.json();
                
                if (data.features && data.features.length > 0) {
                    const route = data.features[0];
                    const geometry = route.geometry.coordinates;
                    
                    // Convertir coordenadas para Leaflet (lat, lng)
                    const routeCoords = geometry.map(coord => [coord[1], coord[0]]);
                    
                    // Crear la línea de ruta
                    routeLine = L.polyline(routeCoords, {
                        color: '#007bff',
                        weight: 5,
                        opacity: 0.8
                    }).addTo(map);
                    
                    // Ajustar la vista para mostrar toda la ruta
                    map.fitBounds(routeLine.getBounds(), { padding: [20, 20] });
                    
                    // Obtener información de la ruta
                    const properties = route.properties;
                    const distanceKm = (properties.segments[0].distance / 1000).toFixed(2);
                    const timeSeconds = properties.segments[0].duration;
                    
                    // Actualizar el campo de longitud automáticamente
                    const longitudInput = document.getElementById('longitud');
                    if (longitudInput) {
                        longitudInput.value = distanceKm;
                    }
                    
                    // Mostrar distancia en el mapa
                    showDistanceInfo(distanceKm, timeSeconds);
                } else {
                    throw new Error('No se pudo calcular la ruta');
                }
            } else {
                throw new Error('Error en la API de routing');
            }
        } catch (error) {
            console.error('Error al calcular la ruta:', error);
            // Fallback a línea recta
            updateRouteLine();
            updateRouteDistance();
            showErrorMessage('No se pudo calcular la ruta por carreteras. Mostrando línea recta.');
        } finally {
            hideLoadingIndicator();
        }
    }
}

// Mantener la función original como respaldo
function updateRouteLine() {
    // Eliminar línea anterior si existe
    if (routeLine) {
        map.removeLayer(routeLine);
    }
    
    // Crear nueva línea si hay al menos 2 puntos
    if (routePoints.length >= 2) {
        routeLine = L.polyline(routePoints, {
            color: '#dc3545',
            weight: 2,
            opacity: 0.5,
            dashArray: '5, 10'
        }).addTo(map);
    }
}

function updateRouteDistance() {
    if (routePoints.length >= 2) {
        let totalDistance = 0;
        
        for (let i = 1; i < routePoints.length; i++) {
            totalDistance += routePoints[i-1].distanceTo(routePoints[i]);
        }
        
        // Convertir de metros a kilómetros
        const distanceKm = (totalDistance / 1000).toFixed(2);
        
        // Actualizar el campo de longitud automáticamente solo si no está usando routing por carreteras
        const longitudInput = document.getElementById('longitud');
        if (longitudInput && !longitudInput.value && !useRoadRouting) {
            longitudInput.value = distanceKm;
        }
        
        // Mostrar distancia en el mapa solo si no está usando routing por carreteras
        if (!useRoadRouting) {
            showDistanceInfo(distanceKm);
        }
    }
}

function showDistanceInfo(distance, timeSeconds = null) {
    // Remover info anterior si existe
    const existingInfo = document.querySelector('.route-distance-info');
    if (existingInfo) {
        existingInfo.remove();
    }
    
    // Formatear tiempo si está disponible
    let timeInfo = '';
    if (timeSeconds) {
        const hours = Math.floor(timeSeconds / 3600);
        const minutes = Math.floor((timeSeconds % 3600) / 60);
        timeInfo = `<br><strong>Tiempo estimado:</strong> `;
        if (hours > 0) {
            timeInfo += `${hours}h `;
        }
        timeInfo += `${minutes}min`;
    }
    
    // Crear info de distancia
    const infoDiv = document.createElement('div');
    infoDiv.className = 'route-distance-info alert alert-info mt-2';
    infoDiv.innerHTML = `
        <strong>Distancia por carretera:</strong> ${distance} km
        ${timeInfo}
        <br><small>Ruta calculada siguiendo las carreteras principales.</small>
    `;
    
    // Insertar después del mapa
    const mapElement = document.getElementById('map');
    mapElement.parentNode.insertBefore(infoDiv, mapElement.nextSibling);
}

function addMapControls() {
    // Crear contenedor de controles
    const controlsDiv = document.createElement('div');
    controlsDiv.className = 'map-controls mt-2';
    controlsDiv.innerHTML = `
        <button type="button" class="btn btn-sm btn-warning" onclick="clearRoute()">
            <i class="fas fa-trash"></i> Limpiar Ruta
        </button>
        <button type="button" class="btn btn-sm btn-info" onclick="centerMap()">
            <i class="fas fa-crosshairs"></i> Centrar en España
        </button>
        <button type="button" class="btn btn-sm btn-secondary" onclick="toggleRouteMode()">
            <i class="fas fa-route"></i> <span id="routeModeText">Modo: Carreteras</span>
        </button>
        <small class="text-muted ml-3">Haz clic en el mapa para añadir puntos a la ruta</small>
    `;
    
    // Insertar controles después del mapa
    const mapElement = document.getElementById('map');
    mapElement.parentNode.insertBefore(controlsDiv, mapElement.nextSibling);
}

// Variable global para controlar el modo de ruta
let useRoadRouting = true;

function toggleRouteMode() {
    useRoadRouting = !useRoadRouting;
    const modeText = document.getElementById('routeModeText');
    
    if (useRoadRouting) {
        modeText.textContent = 'Modo: Carreteras';
    } else {
        modeText.textContent = 'Modo: Línea Recta';
    }
    
    // Recalcular la ruta actual con el nuevo modo
    if (routePoints.length >= 2) {
        if (useRoadRouting) {
            updateRoadRoute(); // Async pero no necesitamos await
        } else {
            // Remover routing anterior
            if (routeLine) {
                map.removeLayer(routeLine);
                routeLine = null;
            }
            updateRouteLine();
            updateRouteDistance();
        }
    }
}

function clearRoute() {
    // Limpiar puntos
    routePoints = [];
    
    // Remover marcadores
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];
    
    // Remover línea
    if (routeLine) {
        map.removeLayer(routeLine);
        routeLine = null;
    }
    
    // Limpiar campo de longitud
    const longitudInput = document.getElementById('longitud');
    if (longitudInput) {
        longitudInput.value = '';
    }
    
    // Remover info de distancia
    const existingInfo = document.querySelector('.route-distance-info');
    if (existingInfo) {
        existingInfo.remove();
    }
    
    // Remover mensajes de error
    const errorInfo = document.querySelector('.route-error-info');
    if (errorInfo) {
        errorInfo.remove();
    }
}

function showLoadingIndicator() {
    // Remover indicador anterior si existe
    const existingIndicator = document.querySelector('.route-loading-info');
    if (existingIndicator) {
        existingIndicator.remove();
    }
    
    // Crear indicador de carga
    const loadingDiv = document.createElement('div');
    loadingDiv.className = 'route-loading-info alert alert-info mt-2';
    loadingDiv.innerHTML = `
        <i class="fas fa-spinner fa-spin"></i> Calculando ruta por carreteras...
    `;
    
    // Insertar después del mapa
    const mapElement = document.getElementById('map');
    mapElement.parentNode.insertBefore(loadingDiv, mapElement.nextSibling);
}

function hideLoadingIndicator() {
    const loadingIndicator = document.querySelector('.route-loading-info');
    if (loadingIndicator) {
        loadingIndicator.remove();
    }
}

function showErrorMessage(message) {
    // Remover error anterior si existe
    const existingError = document.querySelector('.route-error-info');
    if (existingError) {
        existingError.remove();
    }
    
    // Crear mensaje de error
    const errorDiv = document.createElement('div');
    errorDiv.className = 'route-error-info alert alert-warning mt-2';
    errorDiv.innerHTML = `
        <i class="fas fa-exclamation-triangle"></i> ${message}
    `;
    
    // Insertar después del mapa
    const mapElement = document.getElementById('map');
    mapElement.parentNode.insertBefore(errorDiv, mapElement.nextSibling);
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        if (errorDiv.parentNode) {
            errorDiv.remove();
        }
    }, 5000);
}

function centerMap() {
    map.setView([40.416775, -3.703790], 6);
}

// Función para cargar una ruta existente (para edición)
function loadExistingRoute(coordinates) {
    if (coordinates && coordinates.length > 0) {
        coordinates.forEach(coord => {
            addRoutePoint(L.latLng(coord.lat, coord.lng));
        });
    }
}

// Function to get route coordinates as JSON for form submission
function getRouteCoordinatesJSON() {
    return JSON.stringify(routePoints.map(point => ({
        lat: point.lat,
        lng: point.lng
    })));
}

// Function to add route coordinates to form before submission
function addRouteCoordinatesToForm() {
    const rutaForm = document.getElementById('rutaForm');
    if (rutaForm && routePoints.length > 0) {
        // Remove existing coordinates input if any
        const existingInput = rutaForm.querySelector('input[name="coordenadas"]');
        if (existingInput) {
            existingInput.remove();
        }
        
        // Add new coordinates input
        const coordinatesInput = document.createElement('input');
        coordinatesInput.type = 'hidden';
        coordinatesInput.name = 'coordenadas';
        coordinatesInput.value = getRouteCoordinatesJSON();
        rutaForm.appendChild(coordinatesInput);
    }
}

