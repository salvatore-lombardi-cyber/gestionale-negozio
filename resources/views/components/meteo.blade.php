<style>
    /* ===== STILI METEO COMPONENTE ===== */
    .weather-card {
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
        justify-content: center;
        min-height: 200px;
    }
    
    .weather-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #48cae4, #0077b6);
        border-radius: 20px 20px 0 0;
    }
    
    .weather-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }
    
    .weather-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #48cae4;
    }
    
    .weather-temp {
        font-size: 2rem;
        font-weight: 700;
        color: #0077b6;
        margin-bottom: 0.5rem;
    }
    
    .weather-condition {
        font-size: 1rem;
        color: #718096;
        margin-bottom: 1rem;
        font-weight: 500;
    }
    
    .weather-details {
        display: flex;
        justify-content: space-around;
        width: 100%;
        margin-top: 1rem;
    }
    
    .weather-detail {
        display: flex;
        flex-direction: column;
        align-items: center;
        font-size: 0.8rem;
        color: #718096;
    }
    
    .weather-detail i {
        font-size: 1.2rem;
        color: #48cae4;
        margin-bottom: 0.3rem;
    }
    
    .weather-detail-value {
        font-weight: 600;
        color: #2d3748;
    }
    
    .weather-loading {
        color: #718096;
        font-style: italic;
    }
    
    .weather-error {
        color: #f56565;
        font-size: 0.9rem;
    }
    
    .weather-location {
        font-size: 0.9rem;
        color: #0077b6;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .weather-settings {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(72, 202, 228, 0.1);
        border: 1px solid rgba(72, 202, 228, 0.3);
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: #48cae4;
    }
    
    .weather-settings:hover {
        background: rgba(72, 202, 228, 0.2);
        transform: rotate(90deg);
    }
    
    .weather-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }
    
    .weather-modal-content {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
    }
    
    .weather-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    
    .weather-modal-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #0077b6;
        margin: 0;
    }
    
    .weather-modal-close {
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
    
    .weather-modal-close:hover {
        background: rgba(113, 128, 150, 0.1);
    }
    
    .weather-form-group {
        margin-bottom: 1.5rem;
    }
    
    .weather-form-label {
        display: block;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    
    .weather-form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .weather-form-input:focus {
        outline: none;
        border-color: #48cae4;
        box-shadow: 0 0 0 3px rgba(72, 202, 228, 0.1);
    }
    
    .weather-form-buttons {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }
    
    .weather-btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .weather-btn-primary {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
    }
    
    .weather-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(72, 202, 228, 0.4);
    }
    
    .weather-btn-secondary {
        background: #f7fafc;
        color: #718096;
        border: 1px solid #e2e8f0;
    }
    
    .weather-btn-secondary:hover {
        background: #edf2f7;
    }
</style>

<div class="weather-card" id="weatherCard-{{ $id ?? 'default' }}">
    <!-- Pulsante Impostazioni -->
    <div class="weather-settings" id="weatherSettings-{{ $id ?? 'default' }}" title="Configura località">
        <i class="bi bi-gear"></i>
    </div>
    
    <div class="weather-loading" id="weatherLoading-{{ $id ?? 'default' }}">
        <i class="bi bi-cloud-download"></i><br>
        Caricamento meteo...
    </div>
    
    <div class="weather-content" id="weatherContent-{{ $id ?? 'default' }}" style="display: none;">
        <div class="weather-location" id="weatherLocation-{{ $id ?? 'default' }}">
            <i class="bi bi-geo-alt"></i>
            <span id="weatherLocationText-{{ $id ?? 'default' }}"></span>
        </div>
        <div class="weather-icon" id="weatherIcon-{{ $id ?? 'default' }}">
            <i class="bi bi-sun"></i>
        </div>
        <div class="weather-temp" id="weatherTemp-{{ $id ?? 'default' }}">--°C</div>
        <div class="weather-condition" id="weatherCondition-{{ $id ?? 'default' }}">--</div>
        
        <div class="weather-details">
            <div class="weather-detail">
                <i class="bi bi-droplet"></i>
                <span class="weather-detail-value" id="weatherHumidity-{{ $id ?? 'default' }}">--%</span>
            </div>
            <div class="weather-detail">
                <i class="bi bi-wind"></i>
                <span class="weather-detail-value" id="weatherWind-{{ $id ?? 'default' }}">-- km/h</span>
            </div>
            <div class="weather-detail">
                <i class="bi bi-eye"></i>
                <span class="weather-detail-value" id="weatherVisibility-{{ $id ?? 'default' }}">-- km</span>
            </div>
        </div>
    </div>
    
    <div class="weather-error" id="weatherError-{{ $id ?? 'default' }}" style="display: none;">
        <i class="bi bi-exclamation-triangle"></i><br>
        Impossibile caricare i dati meteo
    </div>
</div>

<!-- Modal Configurazione -->
<div class="weather-modal" id="weatherModal-{{ $id ?? 'default' }}">
    <div class="weather-modal-content">
        <div class="weather-modal-header">
            <h3 class="weather-modal-title">Configura Meteo</h3>
            <button class="weather-modal-close" id="weatherModalClose-{{ $id ?? 'default' }}">
                <i class="bi bi-x"></i>
            </button>
        </div>
        
        <form id="weatherForm-{{ $id ?? 'default' }}">
            <div class="weather-form-group">
                <label class="weather-form-label" for="weatherCityInput-{{ $id ?? 'default' }}">
                    <i class="bi bi-geo-alt"></i> Località
                </label>
                <input 
                    type="text" 
                    id="weatherCityInput-{{ $id ?? 'default' }}" 
                    class="weather-form-input" 
                    placeholder="Inserisci città (es. Roma, Milano, Napoli)"
                    value="{{ $city ?? 'Roma' }}"
                    required
                >
            </div>
            
            <div class="weather-form-buttons">
                <button type="button" class="weather-btn weather-btn-secondary" id="weatherCancel-{{ $id ?? 'default' }}">
                    Annulla
                </button>
                <button type="submit" class="weather-btn weather-btn-primary">
                    <i class="bi bi-check"></i> Salva
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const weatherId = '{{ $id ?? "default" }}';
    let currentCity = localStorage.getItem(`weather-city-${weatherId}`) || '{{ $city ?? "Roma" }}';
    
    // Elementi del modal
    const settingsBtn = document.getElementById(`weatherSettings-${weatherId}`);
    const modal = document.getElementById(`weatherModal-${weatherId}`);
    const closeBtn = document.getElementById(`weatherModalClose-${weatherId}`);
    const cancelBtn = document.getElementById(`weatherCancel-${weatherId}`);
    const form = document.getElementById(`weatherForm-${weatherId}`);
    const cityInput = document.getElementById(`weatherCityInput-${weatherId}`);
    
    // Gestione apertura/chiusura modal
    settingsBtn?.addEventListener('click', () => {
        cityInput.value = currentCity;
        modal.style.display = 'flex';
    });
    
    closeBtn?.addEventListener('click', () => {
        modal.style.display = 'none';
    });
    
    cancelBtn?.addEventListener('click', () => {
        modal.style.display = 'none';
    });
    
    // Chiudi modal cliccando fuori
    modal?.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    // Gestione form
    form?.addEventListener('submit', (e) => {
        e.preventDefault();
        const newCity = cityInput.value.trim();
        
        if (newCity && newCity !== currentCity) {
            currentCity = newCity;
            localStorage.setItem(`weather-city-${weatherId}`, currentCity);
            modal.style.display = 'none';
            
            // Ricarica i dati meteo
            showLoading();
            loadWeatherData();
        } else {
            modal.style.display = 'none';
        }
    });
    
    // Funzioni di caricamento
    function showLoading() {
        const loadingEl = document.getElementById(`weatherLoading-${weatherId}`);
        const contentEl = document.getElementById(`weatherContent-${weatherId}`);
        const errorEl = document.getElementById(`weatherError-${weatherId}`);
        
        loadingEl.style.display = 'block';
        contentEl.style.display = 'none';
        errorEl.style.display = 'none';
    }
    
    async function loadWeatherData() {
        const loadingEl = document.getElementById(`weatherLoading-${weatherId}`);
        const contentEl = document.getElementById(`weatherContent-${weatherId}`);
        const errorEl = document.getElementById(`weatherError-${weatherId}`);
        
        try {
            // Chiamata API WeatherAPI
            const response = await fetch(`/api/weather?city=${encodeURIComponent(currentCity)}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const weatherData = await response.json();
            
            if (weatherData.error) {
                throw new Error(weatherData.error);
            }
            
            // Aggiorna l'interfaccia
            updateWeatherUI(weatherData);
            
            // Mostra il contenuto
            loadingEl.style.display = 'none';
            contentEl.style.display = 'block';
            errorEl.style.display = 'none';
            
        } catch (error) {
            console.error('Errore caricamento meteo:', error);
            
            // Mostra errore
            loadingEl.style.display = 'none';
            contentEl.style.display = 'none';
            errorEl.style.display = 'block';
            
            // Mostra solo l'errore senza fallback
            console.error('Errore caricamento meteo:', error);
        }
    }
    
    function updateWeatherUI(data) {
        const locationTextEl = document.getElementById(`weatherLocationText-${weatherId}`);
        const iconEl = document.getElementById(`weatherIcon-${weatherId}`);
        const tempEl = document.getElementById(`weatherTemp-${weatherId}`);
        const conditionEl = document.getElementById(`weatherCondition-${weatherId}`);
        const humidityEl = document.getElementById(`weatherHumidity-${weatherId}`);
        const windEl = document.getElementById(`weatherWind-${weatherId}`);
        const visibilityEl = document.getElementById(`weatherVisibility-${weatherId}`);
        
        if (locationTextEl) locationTextEl.textContent = data.location;
        if (tempEl) tempEl.textContent = `${data.temperature}°C`;
        if (conditionEl) conditionEl.textContent = data.condition;
        if (humidityEl) humidityEl.textContent = `${data.humidity}%`;
        if (windEl) windEl.textContent = `${data.windSpeed} km/h`;
        if (visibilityEl) visibilityEl.textContent = `${data.visibility} km`;
        
        // Aggiorna icona in base alla condizione
        if (iconEl) {
            const iconMap = {
                'Sereno': 'bi-sun',
                'Principalmente sereno': 'bi-sun',
                'Parzialmente nuvoloso': 'bi-cloud-sun',
                'Nuvoloso': 'bi-clouds',
                'Nebbia': 'bi-cloud-fog',
                'Nebbia con brina': 'bi-cloud-fog',
                'Pioggerella leggera': 'bi-cloud-drizzle',
                'Pioggerella moderata': 'bi-cloud-drizzle',
                'Pioggerella intensa': 'bi-cloud-rain',
                'Pioggia leggera': 'bi-cloud-rain',
                'Pioggia moderata': 'bi-cloud-rain-heavy',
                'Pioggia intensa': 'bi-cloud-rain-heavy',
                'Rovesci leggeri': 'bi-cloud-rain',
                'Rovesci moderati': 'bi-cloud-rain-heavy',
                'Rovesci intensi': 'bi-cloud-rain-heavy',
                'Temporale': 'bi-cloud-lightning',
                'Temporale con grandine leggera': 'bi-cloud-lightning',
                'Temporale con grandine intensa': 'bi-cloud-lightning'
            };
            
            const iconClass = iconMap[data.condition] || 'bi-sun';
            iconEl.innerHTML = `<i class="${iconClass}"></i>`;
        }
    }
    
    // Avvia il caricamento iniziale
    loadWeatherData();
    
    // Aggiorna ogni 10 minuti
    setInterval(loadWeatherData, 600000);
});
</script>