@extends('layouts.app')

@section('title', 'Stampa Etichette - ' . $prodotto->nome)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="modern-card p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1>
                            <i class="bi bi-printer me-2"></i>
                            {{ __('app.print_labels') }} - {{ $prodotto->nome }}
                        </h1>
                        <p class="text-muted mb-0">{{ __('app.product_code') }}: {{ $prodotto->codice_prodotto }}</p>
                    </div>
                    <div class="text-end">
                        <button onclick="window.print()" class="btn modern-btn btn-success">
                            <i class="bi bi-printer me-2"></i>{{ __('app.print') }}
                        </button>
                        <a href="{{ route('labels.index') }}" class="btn modern-btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>{{ __('app.back') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Etichette per stampa -->
    <div class="labels-print-container" id="labelsContainer">
        @foreach($selectedVariants as $magazzino)
            @for($copy = 1; $copy <= $copies; $copy++)
                <div class="label-template">
                    <!-- QR Code -->
                    <div class="qr-section">
                        @if($magazzino->hasVariantQR())
                            <img src="{{ $magazzino->getVariantQRUrl() }}" alt="QR Code" class="qr-image">
                        @else
                            <div class="qr-placeholder">
                                <i class="bi bi-qr-code"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Informazioni prodotto -->
                    <div class="info-section">
                        <!-- Codice alfanumerico (grande e visibile) -->
                        <div class="code-main">{{ $magazzino->codice_variante ?? 'N/A' }}</div>
                        
                        <!-- Nome prodotto -->
                        <div class="product-name">{{ Str::limit($prodotto->nome, 25) }}</div>
                        
                        <!-- Taglia e Colore -->
                        <div class="variant-info">
                            <span class="size">{{ $magazzino->taglia }}</span>
                            <span class="separator">-</span>
                            <span class="color">{{ $magazzino->colore }}</span>
                        </div>
                        
                        <!-- Prezzo (discreto) -->
                        <div class="price">€{{ number_format($prodotto->prezzo, 2) }}</div>
                        
                        <!-- Categoria/Brand (discreto) -->
                        <div class="category">{{ $prodotto->categoria }}@if($prodotto->brand) | {{ $prodotto->brand }}@endif</div>
                    </div>
                </div>
            @endfor
        @endforeach
    </div>

    <!-- Info copie -->
    <div class="row mt-4 no-print">
        <div class="col-12">
            <div class="modern-card p-3">
                <div class="text-center text-muted">
                    <strong>{{ $selectedVariants->count() }}</strong> {{ __('app.variants') }} × 
                    <strong>{{ $copies }}</strong> {{ __('app.copies') }} = 
                    <strong>{{ $selectedVariants->count() * $copies }}</strong> {{ __('app.total_labels') }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Stili generali */
body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.modern-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.modern-btn {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
    border-radius: 15px;
    padding: 12px 24px;
    color: white;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.modern-btn.btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.modern-btn.btn-secondary {
    background: linear-gradient(135deg, #6c757d, #495057);
}

/* Container etichette */
.labels-print-container {
    display: flex;
    flex-wrap: wrap;
    gap: 5mm;
    justify-content: flex-start;
    padding: 10mm;
    background: white;
    border-radius: 10px;
    margin: 0 auto;
}

/* Singola etichetta 60mm x 40mm */
.label-template {
    width: 60mm;
    height: 40mm;
    background: white;
    border: 1px solid #ddd;
    border-radius: 3mm;
    padding: 2mm;
    display: flex;
    font-size: 7pt;
    font-family: Arial, sans-serif;
    page-break-inside: avoid;
    position: relative;
    box-sizing: border-box;
}

/* Sezione QR Code (sinistra - 40%) */
.qr-section {
    width: 40%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding-right: 1mm;
}

.qr-image {
    width: 100%;
    height: auto;
    max-height: 35mm;
    object-fit: contain;
}

.qr-placeholder {
    width: 100%;
    height: 25mm;
    background: #f8f9fa;
    border: 1px dashed #ccc;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12pt;
    color: #999;
    border-radius: 2mm;
}

/* Sezione informazioni (destra - 60%) */
.info-section {
    width: 60%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding-left: 1mm;
}

/* Codice alfanumerico (priorità 1) */
.code-main {
    font-size: 9pt;
    font-weight: bold;
    font-family: 'Courier New', monospace;
    background: #f8f9fa;
    padding: 1mm;
    border-radius: 1mm;
    text-align: center;
    border: 1px solid #ddd;
    margin-bottom: 1mm;
}

/* Nome prodotto (priorità 2) */
.product-name {
    font-size: 8pt;
    font-weight: bold;
    line-height: 1.1;
    margin-bottom: 1mm;
    text-align: center;
}

/* Taglia e colore (priorità 3) */
.variant-info {
    font-size: 8pt;
    font-weight: bold;
    text-align: center;
    margin-bottom: 1mm;
    background: #e9ecef;
    padding: 0.5mm;
    border-radius: 1mm;
}

.size, .color {
    font-weight: bold;
}

.separator {
    margin: 0 1mm;
    color: #666;
}

/* Prezzo (priorità 4 - discreto) */
.price {
    font-size: 7pt;
    font-weight: bold;
    text-align: right;
    color: #28a745;
    margin-bottom: 0.5mm;
}

/* Categoria/Brand (priorità 5 - discreto) */
.category {
    font-size: 6pt;
    color: #666;
    text-align: center;
    line-height: 1;
    text-transform: uppercase;
}

/* Stili per stampa */
@media print {
    body {
        background: white !important;
        margin: 0;
        padding: 0;
    }
    
    .no-print {
        display: none !important;
    }
    
    .labels-print-container {
        background: white !important;
        border-radius: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        gap: 2mm;
    }
    
    .label-template {
        border: 1px solid #000 !important;
        margin: 0;
        page-break-inside: avoid;
    }
    
    /* Forza stampa in bianco e nero per QR */
    .qr-image {
        filter: contrast(100%) brightness(100%);
    }
    
    /* Ottimizza il testo per stampa */
    .code-main {
        background: #f0f0f0 !important;
        border: 1px solid #000 !important;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }
    
    .variant-info {
        background: #f5f5f5 !important;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }
}

/* Responsive per preview */
@media (max-width: 768px) {
    .labels-print-container {
        padding: 5mm;
        gap: 3mm;
        justify-content: center;
    }
    
    .label-template {
        transform: scale(1.2);
        margin: 5mm;
    }
}

/* Stili per etichette multiple sulla stessa riga */
@media print {
    .labels-print-container {
        display: flex;
        flex-wrap: wrap;
        gap: 2mm;
    }
    
    /* 4 etichette per riga (formato A4) */
    .label-template {
        flex: 0 0 calc(25% - 1.5mm);
        max-width: calc(25% - 1.5mm);
    }
}
</style>

<script>
// Auto-print se richiesto via URL
if (new URLSearchParams(window.location.search).get('auto_print') === '1') {
    window.onload = function() {
        setTimeout(function() {
            window.print();
        }, 1000);
    };
}

// Funzione per stampare solo etichette selezionate
function printSelected() {
    const selected = document.querySelectorAll('.label-template.selected');
    
    if (selected.length === 0) {
        alert('{{ __("app.select_labels_to_print") }}');
        return;
    }
    
    // Nascondi le non selezionate
    document.querySelectorAll('.label-template:not(.selected)').forEach(label => {
        label.style.display = 'none';
    });
    
    window.print();
    
    // Ripristina la visualizzazione
    document.querySelectorAll('.label-template').forEach(label => {
        label.style.display = 'flex';
    });
}

// Toggle selezione etichetta (per stampa selettiva)
document.addEventListener('click', function(e) {
    if (e.target.closest('.label-template') && !window.matchMedia('print').matches) {
        const label = e.target.closest('.label-template');
        label.classList.toggle('selected');
        
        if (label.classList.contains('selected')) {
            label.style.outline = '2px solid #007bff';
        } else {
            label.style.outline = 'none';
        }
    }
});
</script>
@endsection