@extends('layouts.app')

@section('title', __('app.product') . ': ' . $prodotto->nome . ' - Gestionale Negozio')

@section('content')
<style>
    .product-detail-container {
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
        position: relative;
        overflow: hidden;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
    }
    
    .product-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
        margin-bottom: 1rem;
    }
    
    .product-subtitle {
        color: #718096;
        font-size: 1.1rem;
        margin: 0;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }
    
    .modern-btn {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        border: none;
        border-radius: 15px;
        padding: 12px 24px;
        font-weight: 600;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .modern-btn.edit {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
    }
    
    .modern-btn.secondary {
        background: linear-gradient(135deg, #6c757d, #5a6268);
    }
    
    .modern-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .modern-btn:hover::before {
        left: 0;
    }
    
    .modern-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        color: white;
    }
    
    .product-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    .info-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
    }
    
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }
    
    .card-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .card-title i {
        color: #029D7E;
        font-size: 1.5rem;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(2, 157, 126, 0.1);
        transition: all 0.3s ease;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-item:hover {
        background: rgba(2, 157, 126, 0.03);
        padding-left: 0.5rem;
        border-radius: 8px;
    }
    
    .info-label {
        font-weight: 600;
        color: #4a5568;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        min-width: 120px;
    }
    
    .info-label i {
        color: #029D7E;
        width: 16px;
    }
    
    .info-value {
        font-weight: 500;
        color: #2d3748;
        text-align: right;
        flex: 1;
    }
    
    .product-code {
        font-family: 'Courier New', monospace;
        background: rgba(2, 157, 126, 0.1);
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 700;
        color: #029D7E;
        font-size: 1rem;
    }
    
    .price-display {
        font-size: 1.4rem;
        font-weight: 800;
        color: #28a745;
        background: rgba(40, 167, 69, 0.1);
        padding: 8px 16px;
        border-radius: 10px;
        display: inline-block;
    }
    
    .status-badge {
        padding: 8px 16px;
        border-radius: 25px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.9rem;
    }
    
    .status-active {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .status-inactive {
        background: linear-gradient(135deg, #f72585, #c5025a);
        color: white;
    }
    
    .category-badge {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .brand-display {
        font-weight: 600;
        color: #029D7E;
        font-size: 1.1rem;
    }
    
    .description-card {
        grid-column: 1 / -1;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }
    
    .description-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
    }
    
    .description-text {
        font-size: 1.1rem;
        line-height: 1.6;
        color: #4a5568;
        margin: 0;
        padding: 1rem;
        background: rgba(2, 157, 126, 0.05);
        border-radius: 12px;
        border-left: 4px solid #029D7E;
    }
    
    .datetime-display {
        font-size: 0.95rem;
        color: #718096;
        font-weight: 500;
    }
    
    /* Dark Mode */
    [data-bs-theme="dark"] .page-header,
    [data-bs-theme="dark"] .info-card,
    [data-bs-theme="dark"] .description-card {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .product-title,
    [data-bs-theme="dark"] .card-title {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .info-label,
    [data-bs-theme="dark"] .info-value {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .product-code {
        background: rgba(2, 157, 126, 0.2);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .description-text {
        background: rgba(2, 157, 126, 0.1);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .info-item {
        border-bottom-color: rgba(2, 157, 126, 0.2);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .product-detail-container {
            padding: 1rem;
        }
        
        .page-header {
            padding: 1.5rem;
            text-align: center;
        }
        
        .product-title {
            font-size: 2rem;
        }
        
        .action-buttons {
            justify-content: center;
            margin-top: 1rem;
            flex-wrap: wrap;
        }
        
        .product-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .info-card, .description-card {
            padding: 1.5rem;
        }
        
        .info-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .info-value {
            text-align: left;
        }
    }
    
    @media (max-width: 576px) {
        .action-buttons {
            flex-direction: column;
            width: 100%;
        }
        
        .modern-btn {
            width: 100%;
            justify-content: center;
        }
        
        .card-title {
            font-size: 1.1rem;
        }
        
        .product-title {
            font-size: 1.8rem;
        }
    }
</style>

<div class="product-detail-container">
    <!-- Header con Titolo e Azioni -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="product-title">{{ $prodotto->nome }}</h1>
                <p class="product-subtitle">{{ __('app.product') }} • {{ $prodotto->categoria }}</p>
            </div>
            <div class="action-buttons">
                <a href="{{ route('prodotti.edit', $prodotto) }}" class="modern-btn edit">
                    <i class="bi bi-pencil"></i> {{ __('app.edit') }}
                </a>
                <a href="{{ route('prodotti.index') }}" class="modern-btn secondary">
                    <i class="bi bi-arrow-left"></i> {{ __('app.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Grid delle Informazioni -->
    <div class="product-grid">
        <!-- Card Informazioni Principali -->
        <div class="info-card">
            <h3 class="card-title">
                <i class="bi bi-info-circle"></i>
                {{ __('app.product_information') }}
            </h3>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-upc-scan"></i>
                    {{ __('app.product_code') }}
                </span>
                <span class="info-value">
                    <span class="product-code">{{ $prodotto->codice_prodotto }}</span>
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-tag"></i>
                    {{ __('app.name') }}
                </span>
                <span class="info-value">{{ $prodotto->nome }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-grid-3x3-gap"></i>
                    {{ __('app.category') }}
                </span>
                <span class="info-value">
                    <span class="category-badge">{{ $prodotto->categoria }}</span>
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-award"></i>
                    {{ __('app.brand') }}
                </span>
                <span class="info-value">
                    @if($prodotto->brand)
                        <span class="brand-display">{{ $prodotto->brand }}</span>
                    @else
                        <em style="color: #a0aec0;">N/A</em>
                    @endif
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-currency-euro"></i>
                    {{ __('app.price') }}
                </span>
                <span class="info-value">
                    <span class="price-display">€ {{ number_format($prodotto->prezzo, 2) }}</span>
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-flag"></i>
                    {{ __('app.status') }}
                </span>
                <span class="info-value">
                    <span class="status-badge {{ $prodotto->attivo ? 'status-active' : 'status-inactive' }}">
                        <i class="bi bi-{{ $prodotto->attivo ? 'check-circle' : 'x-circle' }}"></i>
                        {{ $prodotto->attivo ? __('app.active') : __('app.inactive') }}
                    </span>
                </span>
            </div>
        </div>

        <!-- Card Metadata -->
        <div class="info-card">
            <h3 class="card-title">
                <i class="bi bi-clock-history"></i>
                {{ __('app.metadata') }}
            </h3>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-plus-circle"></i>
                    {{ __('app.created_on') }}
                </span>
                <span class="info-value">
                    <span class="datetime-display">{{ $prodotto->created_at->format('d/m/Y H:i') }}</span>
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-pencil-square"></i>
                    {{ __('app.last_modified') }}
                </span>
                <span class="info-value">
                    <span class="datetime-display">{{ $prodotto->updated_at->format('d/m/Y H:i') }}</span>
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-calendar-event"></i>
                    Giorni fa
                </span>
                <span class="info-value">
                    <span class="datetime-display">{{ $prodotto->created_at->diffForHumans() }}</span>
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-clock"></i>
                    Ultima modifica
                </span>
                <span class="info-value">
                    <span class="datetime-display">{{ $prodotto->updated_at->diffForHumans() }}</span>
                </span>
            </div>
        </div>

        <!-- Card Descrizione (se presente) -->
        @if($prodotto->descrizione)
        <div class="description-card">
            <h3 class="card-title">
                <i class="bi bi-text-paragraph"></i>
                {{ __('app.description') }}
            </h3>
            <p class="description-text">{{ $prodotto->descrizione }}</p>
        </div>
        @endif
    </div>
</div>
@endsection