@extends('layouts.app')

@section('title', __('app.qr_scanner') . ' - Gestionale Negozio')

@section('content')
<style>
    .scanner-container {
        padding: 2rem;
        min-height: calc(100vh - 76px);
    }
    
    .page-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .scanner-modes {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .mode-selector {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .mode-card {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(2, 157, 126, 0.2);
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .mode-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        border-color: #029D7E;
    }
    
    .mode-card.active {
        border-color: #029D7E;
        background: linear-gradient(135deg, rgba(2, 157, 126, 0.1), rgba(118, 75, 162, 0.1));
    }
    
    .mode-card.active::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
    }
    
    .mode-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    
    .mode-icon.sale {
        color: #28a745;
    }
    
    .mode-icon.restock {
        color: #ffc107;
    }
    
    .mode-icon.inventory {
        color: #17a2b8;
    }
    
    .mode-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: #333;
    }
    
    .mode-description {
        font-size: 0.9rem;
        color: #666;
        line-height: 1.4;
    }
    
    .scanner-section {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .scanner-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2rem;
    }
    
    .camera-container {
        position: relative;
        width: 100%;
        max-width: 500px;
        aspect-ratio: 1;
        background: #000;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    #video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .scanner-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .scanner-frame {
        width: 250px;
        height: 250px;
        border: 3px solid #fff;
        border-radius: 15px;
        position: relative;
        box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.5);
    }
    
    .scanner-frame::before,
    .scanner-frame::after {
        content: '';
        position: absolute;
        width: 30px;
        height: 30px;
        border: 3px solid #00ff00;
    }
    
    .scanner-frame::before {
        top: -3px;
        left: -3px;
        border-right: none;
        border-bottom: none;
    }
    
    .scanner-frame::after {
        bottom: -3px;
        right: -3px;
        border-left: none;
        border-top: none;
    }
    
    .scanner-controls {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .scanner-btn {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        border: none;
        border-radius: 50px;
        padding: 15px 30px;
        font-weight: 600;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 1rem;
    }
    
    .scanner-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(2, 157, 126, 0.4);
    }
    
    .scanner-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    
    .scanner-btn.success {
        background: linear-gradient(135deg, #28a745, #20c997);
    }
    
    .scanner-btn.danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
    }
    
    .scanner-status {
        text-align: center;
        padding: 1rem;
        border-radius: 10px;
        font-weight: 600;
        margin-top: 1rem;
    }
    
    .scanner-status.ready {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.3);
    }
    
    .scanner-status.scanning {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }
    
    .scanner-status.error {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: 1px solid rgba(220, 53, 69, 0.3);
    }
    
    .scanner-status.success {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.3);
    }
    
    .results-section {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        display: none;
    }
    
    .results-section.visible {
        display: block;
    }
    
    .result-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border-left: 4px solid #28a745;
    }
    
    .result-card.error {
        border-left-color: #dc3545;
    }
    
    .result-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .result-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #333;
    }
    
    .result-time {
        font-size: 0.9rem;
        color: #666;
    }
    
    .result-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .result-detail {
        display: flex;
        flex-direction: column;
    }
    
    .result-label {
        font-size: 0.8rem;
        color: #666;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 0.3rem;
    }
    
    .result-value {
        font-weight: 600;
        color: #333;
    }
    
    /* Dark Mode */
    [data-bs-theme="dark"] .page-header,
    [data-bs-theme="dark"] .scanner-modes,
    [data-bs-theme="dark"] .scanner-section,
    [data-bs-theme="dark"] .results-section {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .mode-card {
        background: rgba(45, 55, 72, 0.9);
        border-color: rgba(2, 157, 126, 0.3);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .mode-title,
    [data-bs-theme="dark"] .result-title {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .result-card {
        background: rgba(45, 55, 72, 0.9);
        color: #e2e8f0;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .scanner-container {
            padding: 1rem;
        }
        
        .page-header,
        .scanner-modes,
        .scanner-section,
        .results-section {
            padding: 1.5rem;
        }
        
        .page-title {
            font-size: 2rem;
        }
        
        .mode-selector {
            grid-template-columns: 1fr;
        }
        
        .camera-container {
            max-width: 100%;
        }
        
        .scanner-frame {
            width: 200px;
            height: 200px;
        }
        
        .scanner-controls {
            flex-direction: column;
        }
        
        .scanner-btn {
            width: 100%;
            justify-content: center;
        }
    }
    
    @media (max-width: 576px) {
        .page-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .result-details {
            grid-template-columns: 1fr;
        }
    }

      .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #029D7E;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>

<div class="scanner-container">
    <!-- Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">
                <i class="bi bi-camera"></i> {{ __('app.qr_scanner') }}
            </h1>
            <div class="d-flex gap-2">
                <a href="{{ route('labels.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> {{ __('app.back') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Selezione Modalità -->
    <div class="scanner-modes">
        <h3 class="mb-4 section-title">
            <i class="bi bi-gear text-primary"></i> {{ __('app.scan_mode') }}
        </h3>
        
        <div class="mode-selector">
            <div class="mode-card active" data-mode="sale">
                <div class="mode-icon sale">
                    <i class="bi bi-cart-plus"></i>
                </div>
                <div class="mode-title">{{ __('app.sale_mode') }}</div>
                <div class="mode-description">{{ __('app.sale_mode_desc') }}</div>
            </div>
            
            <div class="mode-card" data-mode="restock">
                <div class="mode-icon restock">
                    <i class="bi bi-box-arrow-in-down"></i>
                </div>
                <div class="mode-title">{{ __('app.restock_mode') }}</div>
                <div class="mode-description">{{ __('app.restock_mode_desc') }}</div>
            </div>
            
            <div class="mode-card" data-mode="inventory">
                <div class="mode-icon inventory">
                    <i class="bi bi-clipboard-data"></i>
                </div>
                <div class="mode-title">{{ __('app.inventory_mode') }}</div>
                <div class="mode-description">{{ __('app.inventory_mode_desc') }}</div>
            </div>
        </div>
    </div>

    <!-- Scanner -->
    <div class="scanner-section">
        <div class="scanner-wrapper">
            <div class="camera-container">
                <video id="video" autoplay playsinline></video>
                <div class="scanner-overlay">
                    <div class="scanner-frame"></div>
                </div>
            </div>
            
            <div class="scanner-controls">
                <button id="startBtn" class="scanner-btn success">
                    <i class="bi bi-play-circle"></i> {{ __('app.start_camera') }}
                </button>
                <button id="stopBtn" class="scanner-btn danger" disabled>
                    <i class="bi bi-stop-circle"></i> {{ __('app.stop_camera') }}
                </button>
                <button id="flashBtn" class="scanner-btn" disabled>
                    <i class="bi bi-lightning"></i> {{ __('app.flash') }}
                </button>
            </div>
            
            <div id="scannerStatus" class="scanner-status ready">
                <i class="bi bi-camera"></i> {{ __('app.scanner_ready') }}
            </div>
        </div>
    </div>

    <!-- Risultati -->
    <div id="resultsSection" class="results-section">
        <h3 class="mb-4">
            <i class="bi bi-list-check text-success"></i> {{ __('app.scan_results') }}
        </h3>
        <div id="resultsContainer">
            <!-- I risultati verranno inseriti qui dinamicamente -->
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qr-scanner/1.4.2/qr-scanner.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentMode = 'sale';
    let qrScanner = null;
    let video = document.getElementById('video');
    let startBtn = document.getElementById('startBtn');
    let stopBtn = document.getElementById('stopBtn');
    let flashBtn = document.getElementById('flashBtn');
    let scannerStatus = document.getElementById('scannerStatus');
    let resultsSection = document.getElementById('resultsSection');
    let resultsContainer = document.getElementById('resultsContainer');
    
    // Gestione selezione modalità
    document.querySelectorAll('.mode-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.mode-card').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            currentMode = this.dataset.mode;
            updateStatus('ready', `{{ __('app.mode_selected') }}: ${this.querySelector('.mode-title').textContent}`);
        });
    });
    
    // Avvio camera
    startBtn.addEventListener('click', startCamera);
    stopBtn.addEventListener('click', stopCamera);
    flashBtn.addEventListener('click', toggleFlash);
    
    async function startCamera() {
        try {
            updateStatus('scanning', '{{ __('app.starting_camera') }}');
            
            qrScanner = new QrScanner(video, result => {
                processQRCode(result.data);
            }, {
                onDecodeError: error => {
                    // Errore silenzioso - normale durante la scansione
                },
                highlightScanRegion: true,
                highlightCodeOutline: true,
                maxScansPerSecond: 5
            });
            
            await qrScanner.start();
            
            startBtn.disabled = true;
            stopBtn.disabled = false;
            flashBtn.disabled = false;
            
            updateStatus('scanning', '{{ __('app.scanning_active') }}');
            
        } catch (error) {
            console.error('Errore avvio camera:', error);
            updateStatus('error', '{{ __('app.camera_error') }}');
        }
    }
    
    function stopCamera() {
        if (qrScanner) {
            qrScanner.stop();
            qrScanner = null;
        }
        
        startBtn.disabled = false;
        stopBtn.disabled = true;
        flashBtn.disabled = true;
        
        updateStatus('ready', '{{ __('app.camera_stopped') }}');
    }
    
    async function toggleFlash() {
        if (qrScanner) {
            try {
                await qrScanner.toggleFlash();
                const flashIcon = flashBtn.querySelector('i');
                flashIcon.classList.toggle('bi-lightning');
                flashIcon.classList.toggle('bi-lightning-fill');
            } catch (error) {
                console.error('Errore flash:', error);
            }
        }
    }
    
    function updateStatus(type, message) {
        scannerStatus.className = `scanner-status ${type}`;
        scannerStatus.innerHTML = getStatusIcon(type) + ' ' + message;
    }
    
    function getStatusIcon(type) {
        switch(type) {
            case 'ready': return '<i class="bi bi-camera"></i>';
            case 'scanning': return '<i class="bi bi-search"></i>';
            case 'success': return '<i class="bi bi-check-circle"></i>';
            case 'error': return '<i class="bi bi-exclamation-triangle"></i>';
            default: return '<i class="bi bi-info-circle"></i>';
        }
    }
    
    async function processQRCode(qrData) {
        try {
            updateStatus('success', '{{ __('app.qr_detected') }}');
            
            // Invia QR al server per decodifica
            const response = await fetch('/labels/decode', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    qr_code: qrData,
                    mode: currentMode
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                displayResult(result.data);
                updateStatus('success', '{{ __('app.product_found') }}');
            } else {
                displayError(result.message || '{{ __('app.product_not_found') }}');
                updateStatus('error', '{{ __('app.product_not_found') }}');
            }
            
        } catch (error) {
            console.error('Errore elaborazione QR:', error);
            updateStatus('error', '{{ __('app.processing_error') }}');
        }
    }
    
    function displayResult(data) {
        resultsSection.classList.add('visible');
        
        const resultCard = document.createElement('div');
        resultCard.className = 'result-card';
        resultCard.innerHTML = `
            <div class="result-header">
                <div class="result-title">${data.product_name}</div>
                <div class="result-time">${new Date().toLocaleTimeString()}</div>
            </div>
            <div class="result-details">
                <div class="result-detail">
                    <div class="result-label">{{ __('app.product_code') }}</div>
                    <div class="result-value">${data.product_code}</div>
                </div>
                <div class="result-detail">
                    <div class="result-label">{{ __('app.price') }}</div>
                    <div class="result-value">€${data.price}</div>
                </div>
                <div class="result-detail">
                    <div class="result-label">{{ __('app.category') }}</div>
                    <div class="result-value">${data.category}</div>
                </div>
                <div class="result-detail">
                    <div class="result-label">{{ __('app.mode') }}</div>
                    <div class="result-value">${getModeText(currentMode)}</div>
                </div>
            </div>
        `;
        
        resultsContainer.insertBefore(resultCard, resultsContainer.firstChild);
    }
    
    function displayError(message) {
        resultsSection.classList.add('visible');
        
        const errorCard = document.createElement('div');
        errorCard.className = 'result-card error';
        errorCard.innerHTML = `
            <div class="result-header">
                <div class="result-title">{{ __('app.error') }}</div>
                <div class="result-time">${new Date().toLocaleTimeString()}</div>
            </div>
            <div class="result-details">
                <div class="result-detail">
                    <div class="result-label">{{ __('app.message') }}</div>
                    <div class="result-value">${message}</div>
                </div>
            </div>
        `;
        
        resultsContainer.insertBefore(errorCard, resultsContainer.firstChild);
    }
    
    function getModeText(mode) {
        switch(mode) {
            case 'sale': return '{{ __('app.sale_mode') }}';
            case 'restock': return '{{ __('app.restock_mode') }}';
            case 'inventory': return '{{ __('app.inventory_mode') }}';
            default: return mode;
        }
    }
    
    // Pulizia al cambio pagina
    window.addEventListener('beforeunload', function() {
        if (qrScanner) {
            qrScanner.stop();
        }
    });
});
</script>
@endsection