/**
 * Main JavaScript file for RutasMotor application
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap components
    initBootstrapComponents();
    
    // Initialize map if the element exists
    if (document.getElementById('map')) {
        initMap();
    }
    
    // Initialize rating system if it exists
    if (document.querySelector('.rating')) {
        initRating();
    }
});

/**
 * Initialize Bootstrap components like tooltips and popovers
 */
function initBootstrapComponents() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
}

/**
 * Initialize map using Leaflet library
 * This is a placeholder function - it will be implemented 
 * when Leaflet or Google Maps libraries are added
 */
function initMap() {
    console.log('Map initialization would happen here with the appropriate mapping library');
    
    // Example implementation with Leaflet (commented out until library is added)
    /*
    const mapElement = document.getElementById('map');
    const routeCoordinates = JSON.parse(mapElement.dataset.coordinates || '[]');
    
    // Initialize map centered on Spain if no coordinates
    const map = L.map('map').setView([40.416775, -3.703790], 6);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Add route if coordinates exist
    if (routeCoordinates.length > 0) {
        // Create polyline
        const routePath = L.polyline(routeCoordinates, {color: 'blue'}).addTo(map);
        
        // Fit map to the route bounds
        map.fitBounds(routePath.getBounds());
        
        // Add markers for start and end
        L.marker(routeCoordinates[0]).addTo(map)
            .bindPopup('Inicio de la ruta')
            .openPopup();
            
        L.marker(routeCoordinates[routeCoordinates.length - 1]).addTo(map)
            .bindPopup('Fin de la ruta');
    }
    */
}

/**
 * Initialize star rating system
 */
function initRating() {
    const ratingInputs = document.querySelectorAll('.rating input');
    
    ratingInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Highlight selected star and all stars before it
            const rating = this.value;
            document.querySelector('.rating-value').textContent = rating;
        });
    });
}

/**
 * Toggle display of form sections in a multi-step form
 * @param {string} currentStep - Current step ID
 * @param {string} nextStep - Next step ID
 */
function nextFormStep(currentStep, nextStep) {
    document.getElementById(currentStep).classList.add('d-none');
    document.getElementById(nextStep).classList.remove('d-none');
}

/**
 * Export route to GPX format
 * @param {number} routeId - Route ID to export
 */
function exportRouteGPX(routeId) {
    // This would be implemented with server-side code
    alert('Funcionalidad de exportación a GPX en desarrollo');
}

/**
 * Export route to KML format
 * @param {number} routeId - Route ID to export
 */
function exportRouteKML(routeId) {
    // This would be implemented with server-side code
    alert('Funcionalidad de exportación a KML en desarrollo');
}
