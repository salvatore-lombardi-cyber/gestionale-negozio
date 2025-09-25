<style>
    /* ===== STILI MAPPA COMPONENTE ===== */
    .map-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        min-height: 200px;
    }
    
    .map-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        border-radius: 20px 20px 0 0;
    }
    
    .map-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }
    
    .map-settings {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255, 214, 10, 0.1);
        border: 1px solid rgba(255, 214, 10, 0.3);
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #ffd60a;
    }
    
    .map-settings:hover {
        background: rgba(255, 214, 10, 0.2);
        transform: rotate(90deg);
    }
    
    .map-container {
        width: 100%;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
        border: 1px solid rgba(255, 214, 10, 0.2);
        box-shadow: 0 0 20px rgba(255, 214, 10, 0.3);
        margin: 0.5rem 0;
        order: 2;
        flex: 1;
    }
    
    .map-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .map-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
        font-weight: 600;
        text-align: center;
        line-height: 1.2;
        padding: 0.5rem;
    }
    
    .map-location {
        font-size: 0.9rem;
        color: #ff8500;
        font-weight: 600;
        text-align: center;
        order: 1;
    }
    
    .map-details {
        display: flex;
        justify-content: center;
        gap: 2rem;
        width: 100%;
        order: 3;
    }
    
    .map-detail {
        display: flex;
        flex-direction: column;
        align-items: center;
        font-size: 0.8rem;
        color: #718096;
    }
    
    .map-detail i {
        font-size: 1.2rem;
        color: #ffd60a;
        margin-bottom: 0.3rem;
    }
    
    .map-detail-value {
        font-weight: 600;
        color: #2d3748;
        font-family: 'Courier New', monospace;
    }
    
    
    .map-loading {
        color: #718096;
        font-style: italic;
    }
    
    .map-error {
        color: #f56565;
        font-size: 0.9rem;
    }
    
    /* Modal Stili */
    .map-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.8);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 999999;
    }
    
    .map-modal-content {
        background: white;
        border-radius: 20px;
        padding: 0;
        max-width: 800px;
        width: 90%;
        height: 80vh;
        max-height: 800px;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5);
        overflow: hidden;
        display: flex;
        flex-direction: column;
        z-index: 999999;
        position: relative;
        margin: auto;
    }
    
    .map-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .map-modal-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #ff8500;
        margin: 0;
    }
    
    .map-modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #718096;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
    }
    
    .map-modal-close:hover {
        background: rgba(113, 128, 150, 0.1);
    }
    
    .map-search-section {
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .map-search-form {
        display: flex;
        gap: 1rem;
        align-items: flex-end;
    }
    
    .map-form-group {
        flex: 1;
    }
    
    .map-form-label {
        display: block;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    
    .map-form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .map-form-input:focus {
        outline: none;
        border-color: #ffd60a;
        box-shadow: 0 0 0 3px rgba(255, 214, 10, 0.1);
    }
    
    .map-btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .map-btn-primary {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
    }
    
    .map-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 214, 10, 0.4);
    }
    
    .map-full-container {
        flex: 1;
        width: 100%;
        position: relative;
        min-height: 500px;
    }
    
    .map-info {
        padding: 1rem 1.5rem;
        background: #f7fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.9rem;
        color: #718096;
    }
</style>

<div class="map-card" id="mapCard-{{ $id ?? 'default' }}">
    <!-- Pulsante Impostazioni -->
    <div class="map-settings" id="mapSettings-{{ $id ?? 'default' }}" title="Apri mappa completa">
        <i class="bi bi-search"></i>
    </div>
    
    <div class="map-loading" id="mapLoading-{{ $id ?? 'default' }}">
        <i class="bi bi-geo-alt"></i><br>
        Caricamento mappa...
    </div>
    
    <div class="map-location" id="mapLocation-{{ $id ?? 'default' }}" style="display: none;">
        <i class="bi bi-geo-alt-fill"></i>
        <span id="mapLocationText-{{ $id ?? 'default' }}">Italia</span>
    </div>
    
    <div class="map-container" id="mapContainer-{{ $id ?? 'default' }}" style="display: none;">
        <div class="map-placeholder" id="mapPlaceholder-{{ $id ?? 'default' }}" style="display: none;">
            <span id="mapPlaceholderText-{{ $id ?? 'default' }}">🌍 Mondo</span>
        </div>
        <div id="mapPreview-{{ $id ?? 'default' }}" style="width: 100%; height: 100%;"></div>
    </div>
    
    <div class="map-details" id="mapDetails-{{ $id ?? 'default' }}" style="display: none;">
        <div class="map-detail">
            <i class="bi bi-geo-alt"></i>
            <span class="map-detail-value" id="mapLatitude-{{ $id ?? 'default' }}">41.90°N</span>
        </div>
        <div class="map-detail">
            <i class="bi bi-geo"></i>
            <span class="map-detail-value" id="mapLongitude-{{ $id ?? 'default' }}">12.50°E</span>
        </div>
        <div class="map-detail">
            <i class="bi bi-zoom-in"></i>
            <span class="map-detail-value" id="mapZoom-{{ $id ?? 'default' }}">Zoom 6</span>
        </div>
    </div>
    
    <div class="map-error" id="mapError-{{ $id ?? 'default' }}" style="display: none;">
        <i class="bi bi-exclamation-triangle"></i><br>
        Impossibile caricare la mappa
    </div>
</div>

<!-- Modal Mappa Completa -->
<div class="map-modal" id="mapModal-{{ $id ?? 'default' }}">
    <div class="map-modal-content">
        <div class="map-modal-header">
            <h3 class="map-modal-title">
                <i class="bi bi-map"></i> Esplora Mappa
            </h3>
            <button class="map-modal-close" id="mapModalClose-{{ $id ?? 'default' }}">
                <i class="bi bi-x"></i>
            </button>
        </div>
        
        <div class="map-search-section">
            <form class="map-search-form" id="mapSearchForm-{{ $id ?? 'default' }}">
                <div class="map-form-group">
                    <label class="map-form-label" for="mapSearchInput-{{ $id ?? 'default' }}">
                        <i class="bi bi-search"></i> Cerca Località
                    </label>
                    <input 
                        type="text" 
                        id="mapSearchInput-{{ $id ?? 'default' }}" 
                        class="map-form-input" 
                        placeholder="Inserisci città, indirizzo o punto di interesse"
                        value="{{ $location ?? '' }}"
                    >
                </div>
                <button type="submit" class="map-btn map-btn-primary">
                    <i class="bi bi-search"></i> Cerca
                </button>
            </form>
        </div>
        
        <div class="map-full-container">
            <div id="mapFull-{{ $id ?? 'default' }}" style="width: 100%; height: 100%;"></div>
        </div>
        
        <div class="map-info">
            <span id="mapCurrentLocation-{{ $id ?? 'default' }}">Italia</span>
            <span id="mapCurrentCoords-{{ $id ?? 'default' }}">41.9028°N, 12.4964°E</span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mapId = '{{ $id ?? "default" }}';
    let currentLocation = localStorage.getItem(`map-location-${mapId}`) || '{{ $location ?? "Italia" }}';
    let currentCoords = { lat: 41.9028, lng: 12.4964 }; // Centro Italia
    let map = null;
    let marker = null;
    let previewMap = null;
    let previewMarker = null;
    
    // Elementi
    const settingsBtn = document.getElementById(`mapSettings-${mapId}`);
    const modal = document.getElementById(`mapModal-${mapId}`);
    const closeBtn = document.getElementById(`mapModalClose-${mapId}`);
    const searchForm = document.getElementById(`mapSearchForm-${mapId}`);
    const searchInput = document.getElementById(`mapSearchInput-${mapId}`);
    const loadingEl = document.getElementById(`mapLoading-${mapId}`);
    const locationEl = document.getElementById(`mapLocation-${mapId}`);
    const containerEl = document.getElementById(`mapContainer-${mapId}`);
    const detailsEl = document.getElementById(`mapDetails-${mapId}`);
    const errorEl = document.getElementById(`mapError-${mapId}`);
    
    // Gestione apertura modal
    settingsBtn?.addEventListener('click', () => {
        // Sposta il modal nel body per evitare problemi di z-index
        if (modal.parentNode !== document.body) {
            document.body.appendChild(modal);
        }
        modal.style.display = 'flex';
        modal.style.position = 'fixed';
        modal.style.top = '0';
        modal.style.left = '0';
        setTimeout(() => initMap(), 200);
    });
    
    closeBtn?.addEventListener('click', () => {
        modal.style.display = 'none';
    });
    
    // Chiudi modal cliccando fuori
    modal?.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    // Gestione ricerca
    searchForm?.addEventListener('submit', (e) => {
        e.preventDefault();
        const location = searchInput.value.trim();
        if (location) {
            searchLocation(location);
        }
    });
    
    function initPreviewMap() {
        const previewContainer = document.getElementById(`mapPreview-${mapId}`);
        if (!previewContainer || previewMap) return;
        
        try {
            // Inizializza mini-mappa nella card
            previewMap = L.map(previewContainer, {
                zoomControl: false,
                scrollWheelZoom: false,
                doubleClickZoom: false,
                boxZoom: false,
                keyboard: false,
                dragging: false,
                touchZoom: false
            }).setView([currentCoords.lat, currentCoords.lng], currentLocation === 'Italia' ? 6 : 11);
            
            // Aggiungi tiles OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '',
                maxZoom: 19
            }).addTo(previewMap);
            
            // Aggiungi marker
            previewMarker = L.marker([currentCoords.lat, currentCoords.lng])
                .addTo(previewMap);
            
        } catch (error) {
            console.error('Errore mini-mappa:', error);
        }
    }
    
    function initMap() {
        const mapContainer = document.getElementById(`mapFull-${mapId}`);
        if (!mapContainer || map) return;
        
        try {
            // Inizializza mappa Leaflet
            map = L.map(mapContainer).setView([currentCoords.lat, currentCoords.lng], currentLocation === 'Italia' ? 6 : 12);
            
            // Aggiungi tiles OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                maxZoom: 19
            }).addTo(map);
            
            // Aggiungi marker
            marker = L.marker([currentCoords.lat, currentCoords.lng])
                .addTo(map)
                .bindPopup(currentLocation)
                .openPopup();
            
            // Click sulla mappa per aggiungere marker
            map.on('click', (e) => {
                updateMapLocation(e.latlng.lat, e.latlng.lng, 'Posizione personalizzata');
            });
            
        } catch (error) {
            showError('Errore inizializzazione mappa: ' + error.message);
        }
    }
    
    async function searchLocation(query) {
        try {
            // Usa Nominatim per geocoding
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1&addressdetails=1`, {
                headers: {
                    'User-Agent': 'Laravel Map Component/1.0'
                }
            });
            
            if (!response.ok) {
                throw new Error('Errore nella ricerca');
            }
            
            const results = await response.json();
            
            if (results.length > 0) {
                const result = results[0];
                const lat = parseFloat(result.lat);
                const lng = parseFloat(result.lon);
                const name = result.display_name;
                
                updateMapLocation(lat, lng, name);
            } else {
                alert('Località non trovata: ' + query);
            }
            
        } catch (error) {
            console.error('Errore ricerca:', error);
            alert('Errore durante la ricerca: ' + error.message);
        }
    }
    
    function updateMapLocation(lat, lng, name) {
        currentCoords = { lat, lng };
        currentLocation = name;
        
        // Salva in localStorage
        localStorage.setItem(`map-location-${mapId}`, name);
        
        // Aggiorna mappa principale (modal)
        if (map && marker) {
            map.setView([lat, lng], 12);
            marker.setLatLng([lat, lng]);
            marker.bindPopup(name).openPopup();
        }
        
        // Aggiorna mini-mappa (card)
        if (previewMap && previewMarker) {
            const zoom = name === 'Italia' || name.includes('Italia') ? 6 : 11;
            previewMap.setView([lat, lng], zoom);
            previewMarker.setLatLng([lat, lng]);
        }
        
        // Aggiorna interfaccia
        updateMapUI();
    }
    
    function updateMapUI() {
        const locationEl = document.getElementById(`mapLocationText-${mapId}`);
        const latitudeEl = document.getElementById(`mapLatitude-${mapId}`);
        const longitudeEl = document.getElementById(`mapLongitude-${mapId}`);
        const zoomEl = document.getElementById(`mapZoom-${mapId}`);
        const currentLocationEl = document.getElementById(`mapCurrentLocation-${mapId}`);
        const currentCoordsEl = document.getElementById(`mapCurrentCoords-${mapId}`);
        const placeholderTextEl = document.getElementById(`mapPlaceholderText-${mapId}`);
        
        const coordsText = `${currentCoords.lat.toFixed(4)}°N, ${currentCoords.lng.toFixed(4)}°E`;
        const latText = `${currentCoords.lat.toFixed(2)}°N`;
        const lngText = `${currentCoords.lng.toFixed(2)}°E`;
        
        // Estrai il nome città dalla location completa
        const cityName = currentLocation.split(',')[0] || currentLocation;
        const zoom = currentLocation === 'Italia' || currentLocation.includes('Italia') ? 6 : 11;
        
        if (locationEl) locationEl.textContent = cityName;
        if (latitudeEl) latitudeEl.textContent = latText;
        if (longitudeEl) longitudeEl.textContent = lngText;
        if (zoomEl) zoomEl.textContent = `Zoom ${zoom}`;
        if (currentLocationEl) currentLocationEl.textContent = currentLocation;
        if (currentCoordsEl) currentCoordsEl.textContent = coordsText;
        if (placeholderTextEl) {
            if (currentLocation === 'Italia' || currentLocation.includes('Italia')) {
                placeholderTextEl.textContent = '🇮🇹 Italia';
            } else {
                placeholderTextEl.textContent = `📍 ${cityName}`;
            }
        }
    }
    
    function showError(message) {
        loadingEl.style.display = 'none';
        locationEl.style.display = 'none';
        containerEl.style.display = 'none';
        detailsEl.style.display = 'none';
        errorEl.style.display = 'block';
        console.error('Errore mappa:', message);
    }
    
    function showContent() {
        loadingEl.style.display = 'none';
        errorEl.style.display = 'none';
        locationEl.style.display = 'block';
        containerEl.style.display = 'block';
        detailsEl.style.display = 'flex';
        updateMapUI();
    }
    
    // Inizializzazione
    setTimeout(() => {
        if (typeof L !== 'undefined') {
            showContent();
            initPreviewMap(); // Inizializza subito la mini-mappa nella card
        } else {
            showError('Leaflet non caricato');
        }
    }, 500);
});
</script>

<!-- Carica Leaflet CSS e JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>