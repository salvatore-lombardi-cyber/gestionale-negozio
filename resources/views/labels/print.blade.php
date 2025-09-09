@extends('layouts.app')

@section('title', __('app.preview_labels') . ' - ' . $prodotto->nome)

@section('content')
<style>
    /* Mobile gradient background */
    @media (max-width: 768px) {
        body {
            background: linear-gradient(135deg, #029D7E, #4DC9A5);
            min-height: 100vh;
        }
    }
    
    .preview-container {
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
    
    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .page-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    /* Desktop Buttons */
    .desktop-buttons {
        display: flex;
        gap: 12px;
    }
    
    .header-btn {
        padding: 10px 20px;
        border-radius: 25px;
        border: none;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
        white-space: nowrap;
    }
    
    .header-btn i {
        font-size: 16px;
    }
    
    .header-btn.btn-success {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .header-btn.btn-secondary {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        color: white;
    }
    
    .header-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        color: white;
    }
    
    /* Mobile Buttons - Hidden by default */
    .mobile-buttons {
        display: none;
        margin-bottom: 1.5rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .mobile-btn {
        width: 100%;
        padding: 14px 24px;
        border-radius: 25px;
        border: none;
        font-weight: 600;
        font-size: 15px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s ease;
        cursor: pointer;
        margin-bottom: 12px;
    }
    
    .mobile-btn:last-child {
        margin-bottom: 0;
    }
    
    .mobile-btn i {
        font-size: 18px;
    }
    
    .mobile-btn.btn-success {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .mobile-btn.btn-secondary {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        color: white;
    }
    
    .mobile-btn:active {
        transform: scale(0.98);
    }
    
    .modern-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .modern-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }
    
    /* Info Prodotto Card */
    .product-info-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .product-info {
        display: flex;
        align-items: center;
        gap: 2rem;
    }
    
    .product-qr-preview {
        flex: 0 0 120px;
    }
    
    .product-qr-preview img {
        width: 120px;
        height: 120px;
        border-radius: 15px;
        border: 3px solid rgba(2, 157, 126, 0.2);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .product-details h2 {
        font-size: 1.8rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1rem;
    }
    
    .product-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .meta-badge {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .meta-badge.secondary {
        background: linear-gradient(135deg, #6c757d, #5a6268);
    }
    
    .meta-badge.success {
        background: linear-gradient(135deg, #28a745, #20c997);
    }
    
    .meta-badge.info {
        background: linear-gradient(135deg, #48cae4, #0077b6);
    }
    
    /* Sezioni Etichette */
    .section-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .section-title i {
        color: #029D7E;
    }
    
    /* Grid Etichette */
    .labels-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
    
    /* Stile Etichette */
    .label-card {
        background: white;
        border: 2px solid #029D7E;
        border-radius: 15px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .label-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(2, 157, 126, 0.2);
        border-color: #4DC9A5;
    }
    
    .label-qr {
        flex: 0 0 60px;
    }
    
    .label-qr img {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        border: 2px solid rgba(2, 157, 126, 0.3);
    }
    
    .no-qr {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, rgba(2, 157, 126, 0.1), rgba(118, 75, 162, 0.1));
        border: 2px dashed rgba(2, 157, 126, 0.5);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: #029D7E;
        font-weight: bold;
    }
    
    .label-info {
        flex: 1;
    }
    
    .label-code {
        font-family: 'Courier New', monospace;
        font-size: 14px;
        font-weight: bold;
        color: #029D7E;
        background: rgba(2, 157, 126, 0.1);
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
        margin-bottom: 8px;
    }
    
    .label-name {
        font-size: 13px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    
    .label-variant {
        font-size: 11px;
        color: #6c757d;
        margin-bottom: 5px;
    }
    
    .label-price {
        font-size: 12px;
        color: #28a745;
        font-weight: 700;
        margin-bottom: 3px;
    }
    
    .label-brand {
        font-size: 10px;
        color: #4DC9A5;
        font-style: italic;
    }
    
    /* Varianti più piccole */
    .variant-label {
        padding: 1rem;
    }
    
    .variant-label .label-qr {
        flex: 0 0 45px;
    }
    
    .variant-label .label-qr img,
    .variant-label .no-qr {
        width: 45px;
        height: 45px;
    }
    
    .variant-label .label-code {
        font-size: 12px;
    }
    
    .variant-label .label-name {
        font-size: 11px;
    }
    
    .variant-label .label-variant {
        font-size: 10px;
    }
    
    .variant-label .label-price {
        font-size: 11px;
    }
    
    /* Dark Mode */
    [data-bs-theme="dark"] .page-header,
    [data-bs-theme="dark"] .product-info-card,
    [data-bs-theme="dark"] .section-card {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .mobile-buttons {
        background: transparent;
    }
    
    [data-bs-theme="dark"] .label-card {
        background: rgba(45, 55, 72, 0.95);
        border-color: rgba(2, 157, 126, 0.5);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .label-name,
    [data-bs-theme="dark"] .section-title {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .label-variant {
        color: #a0aec0;
    }
    
    [data-bs-theme="dark"] .label-code {
        background: rgba(2, 157, 126, 0.2);
        color: #a5b4fc;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .preview-container {
            padding: 1rem;
        }
        
        .page-header {
            padding: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .header-content {
            justify-content: center;
        }
        
        .desktop-buttons {
            display: none;
        }
        
        .mobile-buttons {
            display: block;
            padding: 0 1rem;
        }
        
        .labels-grid {
            grid-template-columns: 1fr;
        }
        
        .product-info {
            flex-direction: column;
            text-align: center;
        }
        
        .page-title {
            font-size: 1.8rem;
            text-align: center;
        }
        
        .product-info-card,
        .section-card {
            padding: 1.5rem;
        }
    }
    
    /* Stampa */
    @media print {
        .page-header,
        .product-info-card,
        .mobile-buttons {
            display: none;
        }
        
        body {
            background: white !important;
        }
        
        .preview-container {
            padding: 0;
        }
        
        .section-card {
            background: white !important;
            box-shadow: none !important;
            border: none !important;
            page-break-inside: avoid;
        }
        
        .label-card {
            background: white !important;
            border: 2px solid #000 !important;
            page-break-inside: avoid;
            margin-bottom: 10px;
        }
        
        .labels-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
    }
</style>

<div class="preview-container">
    <!-- Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">
                <i class="bi bi-tags-fill"></i> {{ __('app.preview_labels') }}
            </h1>
            <div class="d-flex gap-2">
                <a href="{{ route('labels.index') }}" class="header-btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> {{ __('app.back') }}
                </a>
                <button onclick="printLabels()" class="header-btn btn-success">
                    <i class="bi bi-printer"></i> {{ __('app.print') }}
                </button>
            </div>
        </div>
    </div>
    
    <!-- Info Prodotto -->
    <div class="product-info-card">
        <div class="product-info">
            <div class="product-qr-preview">
                @if($prodotto->qr_code_path && Storage::disk('public')->exists($prodotto->qr_code_path))
                    <img src="{{ Storage::url($prodotto->qr_code_path) }}" alt="{{ __('app.product_qr_code') }}">
                @else
                    <div class="no-qr" style="width: 120px; height: 120px; font-size: 14px;">
                        <i class="bi bi-qr-code"></i>
                        <span>{{ __('app.no_qr') }}</span>
                    </div>
                @endif
            </div>
            <div class="product-details">
                <h2>{{ $prodotto->nome }}</h2>
                <div class="product-meta">
                    <span class="meta-badge">
                        <i class="bi bi-upc"></i> {{ $prodotto->codice_prodotto }}
                    </span>
                    <span class="meta-badge secondary">
                        <i class="bi bi-tag"></i> {{ $prodotto->codice_etichetta ?? 'N/A' }}
                    </span>
                    <span class="meta-badge success">
                        <i class="bi bi-currency-euro"></i> {{ number_format($prodotto->prezzo, 2) }}
                    </span>
                    <span class="meta-badge info">
                        <i class="bi bi-grid-3x3-gap"></i> {{ ucfirst($prodotto->categoria) }}
                    </span>
                    @if($prodotto->brand)
                        <span class="meta-badge secondary">
                            <i class="bi bi-building"></i> {{ $prodotto->brand }}
                        </span>
                    @endif
                    <span class="meta-badge">
                        <i class="bi bi-palette"></i> {{ $prodotto->magazzino->count() }} {{ __('app.variants') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Etichette -->
    <div class="section-card">
        <h3 class="section-title">
            <i class="bi bi-tags-fill"></i> 
            @if($prodotto->magazzino->count() > 0)
                {{ __('app.product_labels') }}
                <span class="badge bg-primary">{{ $prodotto->magazzino->count() + 1 }} {{ __('app.total') }}</span>
            @else
                {{ __('app.product_label') }}
            @endif
        </h3>
        
        <div class="labels-grid">
            <!-- Etichetta Prodotto Principale -->
            <div class="label-card">
                <div class="label-qr">
                    @if($prodotto->qr_code_path && Storage::disk('public')->exists($prodotto->qr_code_path))
                        <img src="{{ Storage::url($prodotto->qr_code_path) }}" alt="{{ __('app.qr_code') }}">
                    @else
                        <div class="no-qr">{{ __('app.no_qr') }}</div>
                    @endif
                </div>
                <div class="label-info">
                    <div class="label-code">{{ $prodotto->codice_etichetta ?? $prodotto->codice_prodotto }}</div>
                    <div class="label-name">{{ $prodotto->nome }}</div>
                    <div class="label-price">€{{ number_format($prodotto->prezzo, 2) }}</div>
                    @if($prodotto->brand)
                        <div class="label-brand">{{ $prodotto->brand }}</div>
                    @endif
                </div>
            </div>
            
            <!-- Etichette Varianti -->
            @foreach($prodotto->magazzino as $variante)
                <div class="label-card variant-label">
                    <div class="label-qr">
                        @if($variante->variant_qr_path && Storage::disk('public')->exists($variante->variant_qr_path))
                            <img src="{{ Storage::url($variante->variant_qr_path) }}" alt="{{ __('app.variant_qr_code') }}">
                        @else
                            <div class="no-qr">{{ __('app.no_qr') }}</div>
                        @endif
                    </div>
                    <div class="label-info">
                        <div class="label-code">{{ $variante->codice_variante }}</div>
                        <div class="label-name">{{ $prodotto->nome }}</div>
                        <div class="label-variant">
                            <i class="bi bi-palette2"></i> {{ $variante->taglia }} - {{ $variante->colore }}
                        </div>
                        <div class="label-price">€{{ number_format($prodotto->prezzo, 2) }}</div>
                        @if($prodotto->brand)
                            <div class="label-brand">{{ $prodotto->brand }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
function printLabels() {
    // Animazione pulsante
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="bi bi-arrow-clockwise spin"></i> {{ __('app.preparing') }}';
    button.disabled = true;
    
    // Piccolo delay per l'animazione
    setTimeout(() => {
        window.print();
        button.innerHTML = originalText;
        button.disabled = false;
    }, 500);
}

// Stile per animazione loading
const style = document.createElement('style');
style.textContent = `
    .spin {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);

// Notifica successo stampa (opzionale)
window.addEventListener('afterprint', function() {
    console.log('{{ __('app.print_completed') }}');
});
</script>
@endsection