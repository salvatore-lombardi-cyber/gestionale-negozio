@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .fatturazione-container {
        padding: 2rem;
    }
    
    .fatturazione-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .fatturazione-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .welcome-text {
        color: #6c757d;
        font-size: 1.1rem;
        margin-top: 0.5rem;
    }
    
    .stats-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
    }
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        border-radius: 20px 20px 0 0;
    }
    
    .stats-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }
    
    .stats-card.primary::before {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
    }
    
    .stats-card.success::before {
        background: linear-gradient(135deg, #4ecdc4, #44a08d);
    }
    
    .stats-card.info::before {
        background: linear-gradient(135deg, #48cae4, #0077b6);
    }
    
    .stats-card.warning::before {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
    }
    
    .stats-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        margin-bottom: 1rem;
        position: relative;
        overflow: hidden;
    }
    
    .stats-icon.primary {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
    }
    
    .stats-icon.success {
        background: linear-gradient(135deg, #4ecdc4, #44a08d);
    }
    
    .stats-icon.info {
        background: linear-gradient(135deg, #48cae4, #0077b6);
    }
    
    .stats-icon.warning {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
    }
    
    .stats-icon::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: rgba(255, 255, 255, 0.1);
        transform: rotate(45deg);
        transition: all 0.3s ease;
        opacity: 0;
    }
    
    .stats-card:hover .stats-icon::before {
        opacity: 1;
        animation: shimmer 1s ease-in-out;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%) rotate(45deg); }
        100% { transform: translateX(100%) rotate(45deg); }
    }
    
    .stats-number {
        font-size: 3rem;
        font-weight: 800;
        color: #2d3748;
        margin: 0;
        line-height: 1;
    }
    
    .stats-label {
        font-size: 1.1rem;
        color: #718096;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }
    
    .stats-btn {
        border: none;
        border-radius: 15px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        position: relative;
        overflow: hidden;
    }
    
    .stats-btn.primary {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
    }
    
    .stats-btn.success {
        background: linear-gradient(135deg, #4ecdc4, #44a08d);
        color: white;
    }
    
    .stats-btn.info {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
    }
    
    .stats-btn.warning {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
    }
    
    .stats-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        color: white;
    }
    
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(2, 157, 126, 0.4); }
        70% { box-shadow: 0 0 0 20px rgba(2, 157, 126, 0); }
        100% { box-shadow: 0 0 0 0 rgba(2, 157, 126, 0); }
    }
    
    .floating-elements {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: -1;
        overflow: hidden;
    }
    
    .floating-element {
        position: absolute;
        opacity: 0.1;
        font-size: 2rem;
        color: white;
        animation: float 6s ease-in-out infinite;
    }
    
    .floating-element:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; }
    .floating-element:nth-child(2) { top: 20%; right: 20%; animation-delay: 1s; }
    .floating-element:nth-child(3) { bottom: 30%; left: 15%; animation-delay: 2s; }
    .floating-element:nth-child(4) { bottom: 10%; right: 15%; animation-delay: 3s; }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }
    
    /* Calcolatrice Widget */
    .calculator-widget {
        position: fixed;
        top: 50%;
        right: -320px;
        transform: translateY(-50%);
        width: 300px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        z-index: 1000;
        transition: all 0.3s ease;
    }
    
    .calculator-widget.active {
        right: 20px;
    }
    
    .calculator-toggle {
        position: absolute;
        left: -50px;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        border: none;
        border-radius: 15px 0 0 15px;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }
    
    .calculator-toggle:hover {
        transform: translateY(-50%) scale(1.1);
    }
    
    .calculator-header {
        padding: 1rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        text-align: center;
        font-weight: 600;
        color: #2d3748;
    }
    
    .calculator-display {
        background: #f8f9fa;
        padding: 1rem;
        text-align: right;
        font-size: 1.5rem;
        font-weight: bold;
        color: #2d3748;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        min-height: 60px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }
    
    .calculator-buttons {
        padding: 1rem;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.5rem;
    }
    
    .calc-btn {
        height: 50px;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #f8f9fa;
        color: #2d3748;
    }
    
    .calc-btn:hover {
        background: #e9ecef;
        transform: translateY(-2px);
    }
    
    .calc-btn.operator {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        color: white;
    }
    
    .calc-btn.operator:hover {
        background: linear-gradient(135deg, #027a6b 0%, #45b392 100%);
    }
    
    .calc-btn.equals {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        grid-column: span 2;
    }
    
    .calc-btn.equals:hover {
        background: linear-gradient(135deg, #5a6fd8, #6b4190 100%);
    }
    
    @media (max-width: 768px) {
        .fatturazione-header {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .fatturazione-title {
            font-size: 2rem;
        }
        
        .welcome-text {
            font-size: 1rem;
        }
        
        .stats-card {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .stats-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
        
        .stats-number {
            font-size: 2.5rem;
        }
        
        .stats-label {
            font-size: 1rem;
        }
        
        .stats-btn {
            padding: 10px 20px;
            font-size: 0.8rem;
        }
        
        .calculator-widget {
            display: none; /* Nasconde su mobile */
        }
    }
    
    @media (max-width: 576px) {
        .fatturazione-container {
            padding: 1rem;
        }
        
        .fatturazione-title {
            font-size: 1.8rem;
        }
        
        .stats-number {
            font-size: 2rem;
        }
        
        .floating-element {
            font-size: 1.5rem;
        }
    }
    
    /* Stili per Fatture Esistenti - Identici agli Articoli */
    .search-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .search-box {
        position: relative;
    }
    
    .search-input {
        width: 100%;
        padding: 15px 50px 15px 20px;
        border: 2px solid rgba(2, 157, 126, 0.2);
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
    }
    
    .search-input:focus {
        outline: none;
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
        background: white;
    }
    
    .search-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #029D7E;
        font-size: 1.2rem;
    }
    
    .filter-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
        justify-content: center;
    }
    
    .filter-chip {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
    }
    
    .filter-chip:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(2, 157, 126, 0.4);
    }
    
    .filter-chip.active {
        background: linear-gradient(135deg, #f72585, #c5025a);
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
    
    .modern-table {
        margin: 0;
        width: 100%;
    }
    
    .modern-table thead th {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        color: white;
        font-weight: 600;
        border: none;
        padding: 1rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }
    
    .modern-table tbody tr {
        transition: all 0.3s ease;
        border: none;
    }
    
    .modern-table tbody tr:hover {
        background: rgba(2, 157, 126, 0.05);
        transform: scale(1.01);
    }
    
    .modern-table tbody td {
        padding: 1rem;
        border: none;
        border-bottom: 1px solid rgba(2, 157, 126, 0.1);
        vertical-align: middle;
        white-space: nowrap;
    }
    
    .action-btn {
        border: none;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        margin: 0 2px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .action-btn.view {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
    }
    
    .action-btn.edit {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
    }
    
    .action-btn.delete {
        background: linear-gradient(135deg, #f72585, #c5025a);
        color: white;
    }
    
    .action-btn.qr {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        color: white;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: white;
    }
    
    .product-code {
        font-family: 'Courier New', monospace;
        background: rgba(2, 157, 126, 0.1);
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .price-tag {
        font-weight: 700;
        color: #28a745;
        font-size: 1.1rem;
    }
    
    .no-results {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
    }
    
    .qr-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.8rem;
        padding: 3px 8px;
        border-radius: 12px;
        font-weight: 600;
    }
    
    .qr-status.ready {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }
    
    .qr-status.missing {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }
    
    /* Mobile Cards */
    .mobile-cards {
        display: none;
    }
    
    .product-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .product-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .product-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
        flex: 1;
        min-width: 0;
    }
    
    .product-card-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .product-detail {
        display: flex;
        flex-direction: column;
    }
    
    .product-detail-label {
        font-size: 0.8rem;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 0.3rem;
    }
    
    .product-detail-value {
        font-weight: 600;
    }
    
    .product-card-price {
        font-size: 1.4rem;
        font-weight: 800;
        color: #029D7E;
        text-align: center;
        margin-bottom: 1.5rem;
        padding: 0.5rem;
        background: rgba(40, 167, 69, 0.1);
        border-radius: 10px;
    }
    
    .product-card-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .mobile-action-btn {
        flex: 1;
        min-width: 80px;
        border: none;
        border-radius: 10px;
        padding: 12px 8px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.3rem;
        text-align: center;
    }
    
    .mobile-action-btn i {
        font-size: 1.2rem;
    }
    
    .mobile-action-btn.view {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
    }
    
    .mobile-action-btn.edit {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
    }
    
    .mobile-action-btn.delete {
        background: linear-gradient(135deg, #f72585, #c5025a);
        color: white;
    }
    
    .mobile-action-btn.qr {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        color: white;
    }
    
    .mobile-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: white;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .search-container {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .filter-chips {
            justify-content: center;
        }
        
        .modern-card .table-responsive {
            display: none;
        }
        
        .mobile-cards {
            display: block;
        }
    }
    
    @media (max-width: 576px) {
        .product-card {
            padding: 1rem;
        }
        
        .product-card-details {
            grid-template-columns: 1fr;
            gap: 0.8rem;
        }
        
        .mobile-action-btn {
            padding: 10px 6px;
            font-size: 0.7rem;
            min-width: 70px;
        }
        
        .mobile-action-btn i {
            font-size: 1rem;
        }
    }
</style>

<!-- Elementi fluttuanti di sfondo -->
<div class="floating-elements">
    <i class="bi bi-receipt floating-element"></i>
    <i class="bi bi-graph-up floating-element"></i>
    <i class="bi bi-currency-euro floating-element"></i>
</div>


<div class="container-fluid fatturazione-container">
    <div class="row">
        <div class="col-12">
            <!-- Header della pagina Fatturazione -->
            <div class="fatturazione-header text-center">
                <h1 class="fatturazione-title">
                    <i class="bi bi-receipt"></i> {{ __('app.fatturazione') }}
                </h1>
                <p class="welcome-text">{{ __('app.fatturazione_welcome') }}</p>
            </div>
            
            <!-- Cards della Fatturazione -->
            <div class="row g-4">
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="stats-card primary pulse-animation">
                        <div class="stats-icon primary">
                            <i class="bi bi-file-plus"></i>
                        </div>
                        <h3 class="stats-number">{{ __('app.nuova') }}</h3>
                        <p class="stats-label">{{ __('app.crea_fattura') }}</p>
                        <a href="{{ route('fatturazione.create') }}" class="stats-btn primary">
                            <i class="bi bi-plus-circle"></i> {{ __('app.nuova_fattura') }}
                        </a>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="stats-card success">
                        <div class="stats-icon success">
                            <i class="bi bi-bar-chart"></i>
                        </div>
                        <h3 class="stats-number">€{{ number_format($stats['fatturato_mensile'], 0, ',', '.') }}</h3>
                        <p class="stats-label">{{ __('app.riepilogo_fatturazione') }}</p>
                        <a href="{{ route('fatturazione.riepilogo') }}" class="stats-btn success">
                            <i class="bi bi-eye"></i> {{ __('app.view') }} {{ __('app.riepilogo_fatturazione') }}
                        </a>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="stats-card info">
                        <div class="stats-icon info">
                            <i class="bi bi-inbox"></i>
                        </div>
                        <h3 class="stats-number">{{ $stats['fatture_ricevute'] }}</h3>
                        <p class="stats-label">{{ __('app.fatture_ricevute') }}</p>
                        <a href="{{ route('fatturazione.fatture_ricevute') }}" class="stats-btn info">
                            <i class="bi bi-arrow-right"></i> {{ __('app.manage') }} {{ __('app.fatture_ricevute') }}
                        </a>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="stats-card warning">
                        <div class="stats-icon warning">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h3 class="stats-number">{{ $stats['clienti_attivi'] }}</h3>
                        <p class="stats-label">{{ __('app.analytics_fatturazione') }}</p>
                        <a href="{{ route('fatturazione.analytics') }}" class="stats-btn warning">
                            <i class="bi bi-bar-chart"></i> {{ __('app.view') }} {{ __('app.analytics_fatturazione') }}
                        </a>
                    </div>
                </div>
            </div>
            
            @php
                $fatture = App\Models\Vendita::where('tipo_documento', 'fattura')
                    ->with('cliente')
                    ->orderBy('numero_documento', 'desc')
                    ->paginate(10);
            @endphp
            
            @if($fatture->count() > 0)
            <!-- Lista Fatture Esistenti -->
            <div class="row mt-5">
                <div class="col-12">
                    <!-- Header della Sezione -->
                    <div class="fatturazione-header mb-4">
                        <h3 class="fatturazione-title" style="font-size: 2rem;">
                            <i class="bi bi-list-ul"></i> Fatture Esistenti
                        </h3>
                        <p class="welcome-text">Gestisci le tue fatture emesse</p>
                    </div>
                    
                    <!-- Barra di Ricerca per Fatture -->
                    <div class="search-container">
                        <div class="search-box">
                            <input type="text" 
                            class="search-input" 
                            id="fattureSearchInput" 
                            placeholder="🔍 Cerca fatture per numero, cliente, data..."            
                            autocomplete="off">
                            <i class="bi bi-search search-icon"></i>
                        </div>
                        <div class="filter-chips">
                            <button class="filter-chip active" data-filter="all">Tutte</button>
                            <button class="filter-chip" data-filter="definitivo">Definitive</button>
                            <button class="filter-chip" data-filter="potenziale">Potenziali</button>
                            <button class="filter-chip" data-filter="recent">Recenti</button>
                        </div>
                    </div>
                    
                    <!-- Tabella Fatture Stile Moderno -->
                    <div class="modern-card">
                        <!-- Tabella Desktop -->
                        <div class="table-responsive">
                            <table class="table modern-table" id="fattureTable">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-hash"></i> N. Fattura</th>
                                        <th><i class="bi bi-calendar"></i> Data</th>
                                        <th><i class="bi bi-person"></i> Cliente</th>
                                        <th><i class="bi bi-currency-euro"></i> Totale</th>
                                        <th><i class="bi bi-flag"></i> Stato</th>
                                        <th><i class="bi bi-gear"></i> Azioni</th>
                                    </tr>
                                </thead>
                                <tbody id="fattureTableBody">
                                    @foreach($fatture as $fattura)
                                        @php
                                            $datiCliente = json_decode($fattura->prodotti_vendita, true);
                                            $tipo = $datiCliente['tipo'] ?? 'definitivo';
                                        @endphp
                                        <tr class="fattura-row" 
                                        data-numero="{{ $fattura->numero_documento }}"
                                        data-cliente="{{ strtolower($fattura->cliente ? $fattura->cliente->nome . ' ' . $fattura->cliente->cognome : 'cliente occasionale') }}"
                                        data-data="{{ $fattura->data_documento ? $fattura->data_documento->format('Y-m-d') : '' }}"
                                        data-stato="{{ $tipo }}"
                                        data-totale="{{ $fattura->totale }}">
                                        <td>
                                            <span class="product-code">{{ str_pad($fattura->numero_documento, 4, '0', STR_PAD_LEFT) }}</span>
                                            <br><small class="text-muted">{{ ucfirst($tipo) }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $fattura->data_documento ? $fattura->data_documento->format('d/m/Y') : '-' }}</strong>
                                            @if($fattura->data_documento)
                                                <br><small class="text-muted">{{ $fattura->data_documento->diffForHumans() }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($fattura->cliente)
                                                <strong>{{ $fattura->cliente->nome }} {{ $fattura->cliente->cognome }}</strong>
                                                @if($fattura->cliente->email)
                                                    <br><small class="text-muted">{{ $fattura->cliente->email }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted"><i class="bi bi-person-dash"></i> Cliente occasionale</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="price-tag">€{{ number_format($fattura->totale, 2, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            @if($tipo === 'definitivo')
                                                <span class="badge" style="background: linear-gradient(135deg, #28a745, #20c997); color: white;">
                                                    <i class="bi bi-check-circle"></i> Definitiva
                                                </span>
                                            @else
                                                <span class="badge" style="background: linear-gradient(135deg, #ffc107, #ff8500); color: white;">
                                                    <i class="bi bi-clock"></i> Potenziale
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('fatturazione.show', $fattura) }}" class="action-btn view" title="Visualizza">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('fatturazione.edit', $fattura) }}" class="action-btn edit" title="Modifica">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="{{ route('fatturazione.pdf', $fattura) }}" class="action-btn qr" title="Genera PDF" target="_blank">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </a>
                                            <form action="{{ route('fatturazione.destroy', $fattura) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="action-btn delete" title="Elimina" onclick="return confirm('Sei sicuro di voler eliminare questa fattura?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Cards Mobile -->
                        <div class="mobile-cards" id="fattureMobileCards">
                            @foreach($fatture as $fattura)
                                @php
                                    $datiCliente = json_decode($fattura->prodotti_vendita, true);
                                    $tipo = $datiCliente['tipo'] ?? 'definitivo';
                                @endphp
                                <div class="product-card mobile-fattura-row"
                                data-numero="{{ $fattura->numero_documento }}"
                                data-cliente="{{ strtolower($fattura->cliente ? $fattura->cliente->nome . ' ' . $fattura->cliente->cognome : 'cliente occasionale') }}"
                                data-data="{{ $fattura->data_documento ? $fattura->data_documento->format('Y-m-d') : '' }}"
                                data-stato="{{ $tipo }}"
                                data-totale="{{ $fattura->totale }}">
                                
                                <div class="product-card-header">
                                    <h3 class="product-card-title">Fattura #{{ str_pad($fattura->numero_documento, 4, '0', STR_PAD_LEFT) }}</h3>
                                    <div class="d-flex flex-column align-items-end">
                                        @if($tipo === 'definitivo')
                                            <small class="qr-status ready">
                                                <i class="bi bi-check-circle"></i> Definitiva
                                            </small>
                                        @else
                                            <small class="qr-status missing">
                                                <i class="bi bi-clock"></i> Potenziale
                                            </small>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="product-card-details">
                                    <div class="product-detail">
                                        <span class="product-detail-label">Cliente</span>
                                        <span class="product-detail-value">
                                            @if($fattura->cliente)
                                                {{ $fattura->cliente->nome }} {{ $fattura->cliente->cognome }}
                                            @else
                                                Cliente occasionale
                                            @endif
                                        </span>
                                    </div>
                                    <div class="product-detail">
                                        <span class="product-detail-label">Data</span>
                                        <span class="product-detail-value">{{ $fattura->data_documento ? $fattura->data_documento->format('d/m/Y') : '-' }}</span>
                                    </div>
                                </div>
                                
                                <div class="product-card-price">
                                    €{{ number_format($fattura->totale, 2, ',', '.') }}
                                </div>
                                
                                <div class="product-card-actions">
                                    <a href="{{ route('fatturazione.show', $fattura) }}" class="mobile-action-btn view">
                                        <i class="bi bi-eye"></i>
                                        <span>Visualizza</span>
                                    </a>
                                    <a href="{{ route('fatturazione.edit', $fattura) }}" class="mobile-action-btn edit">
                                        <i class="bi bi-pencil"></i>
                                        <span>Modifica</span>
                                    </a>
                                    <a href="{{ route('fatturazione.pdf', $fattura) }}" class="mobile-action-btn qr" target="_blank">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                        <span>PDF</span>
                                    </a>
                                    <form action="{{ route('fatturazione.destroy', $fattura) }}" method="POST" style="flex: 1;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="mobile-action-btn delete" style="width: 100%; border: none;" onclick="return confirm('Sei sicuro di voler eliminare questa fattura?')">
                                            <i class="bi bi-trash"></i>
                                            <span>Elimina</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <!-- Messaggio nessun risultato -->
                        <div id="fattureNoResults" class="no-results" style="display: none;">
                            <i class="bi bi-search"></i>
                            <h5>Nessuna fattura trovata</h5>
                            <p>Prova a modificare i criteri di ricerca</p>
                        </div>
                    </div>
                        
                    @if($fatture->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $fatture->links() }}
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Variabili calcolatrice
    let display = document.getElementById('display');
    let currentInput = '0';
    let operator = null;
    let waitingForNewOperand = false;
    
    // Toggle calcolatrice
    function toggleCalculator() {
        const widget = document.getElementById('calculatorWidget');
        widget.classList.toggle('active');
    }
    
    // Funzioni calcolatrice
    function updateDisplay() {
        display.textContent = currentInput;
    }
    
    function appendNumber(number) {
        if (waitingForNewOperand) {
            currentInput = number;
            waitingForNewOperand = false;
        } else {
            currentInput = currentInput === '0' ? number : currentInput + number;
        }
        updateDisplay();
    }
    
    function appendOperation(nextOperator) {
        const inputValue = parseFloat(currentInput);
        
        if (operator && !waitingForNewOperand) {
            const result = calculate(parseFloat(display.textContent), inputValue, operator);
            currentInput = String(result);
            updateDisplay();
        }
        
        waitingForNewOperand = true;
        operator = nextOperator;
    }
    
    function calculate(firstOperand = null, secondOperand = null, operator = null) {
        if (arguments.length === 0) {
            const inputValue = parseFloat(currentInput);
            
            if (operator && !waitingForNewOperand) {
                const result = performCalculation(parseFloat(display.textContent), inputValue, operator);
                currentInput = String(result);
                updateDisplay();
                operator = null;
                waitingForNewOperand = true;
            }
            return;
        }
        
        return performCalculation(firstOperand, secondOperand, operator);
    }
    
    function performCalculation(first, second, op) {
        switch (op) {
            case '+': return first + second;
            case '-': return first - second;
            case '*': return first * second;
            case '/': return first / second;
            case '%': return first % second;
            default: return second;
        }
    }
    
    function clearAll() {
        currentInput = '0';
        operator = null;
        waitingForNewOperand = false;
        updateDisplay();
    }
    
    function clearEntry() {
        currentInput = '0';
        updateDisplay();
    }
    
    // Animazione di entrata delle card
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.stats-card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(50px)';
                card.style.transition = 'all 0.6s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100);
            }, index * 150);
        });
        
        // Mostra calcolatrice dopo 2 secondi
        setTimeout(() => {
            const widget = document.getElementById('calculatorWidget');
            widget.style.opacity = '0';
            widget.style.display = 'block';
            setTimeout(() => {
                widget.style.opacity = '1';
            }, 100);
        }, 2000);
    });
    
    // Shortcut da tastiera per calcolatrice
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'c') {
            // Copia risultato calcolatrice
            if (display.textContent !== '0') {
                navigator.clipboard.writeText(display.textContent);
                // Feedback visivo
                display.style.background = '#28a745';
                display.style.color = 'white';
                setTimeout(() => {
                    display.style.background = '#f8f9fa';
                    display.style.color = '#2d3748';
                }, 300);
            }
        }
        
        if (e.key === 'F9') {
            e.preventDefault();
            toggleCalculator();
        }
    });
    
    // Sistema di ricerca per fatture - Identico agli articoli
    document.addEventListener('DOMContentLoaded', function() {
        const fattureSearchInput = document.getElementById('fattureSearchInput');
        const fattureFilterChips = document.querySelectorAll('.filter-chip');
        const fattureRows = document.querySelectorAll('.fattura-row');
        const mobileFattureRows = document.querySelectorAll('.mobile-fattura-row');
        const fattureNoResults = document.getElementById('fattureNoResults');
        let currentFattureFilter = 'all';
        
        if (fattureSearchInput) {
            // Gestione filtri fatture
            fattureFilterChips.forEach(chip => {
                chip.addEventListener('click', function() {
                    fattureFilterChips.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    currentFattureFilter = this.dataset.filter;
                    performFattureSearch();
                });
            });
            
            // Ricerca in tempo reale fatture
            fattureSearchInput.addEventListener('input', function() {
                performFattureSearch();
            });
            
            function performFattureSearch() {
                const searchTerm = fattureSearchInput.value.toLowerCase().trim();
                let visibleFattureRows = 0;
                
                // Ricerca nella tabella desktop fatture
                fattureRows.forEach(row => {
                    let shouldShow = shouldShowFattura(row, searchTerm);
                    
                    if (shouldShow) {
                        row.style.display = '';
                        visibleFattureRows++;
                        setTimeout(() => {
                            row.style.opacity = '1';
                            row.style.transform = 'translateX(0)';
                        }, 100);
                    } else {
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(-20px)';
                        setTimeout(() => {
                            row.style.display = 'none';
                        }, 300);
                    }
                });
                
                // Ricerca nelle card mobile fatture
                mobileFattureRows.forEach(row => {
                    let shouldShow = shouldShowFattura(row, searchTerm);
                    
                    if (shouldShow) {
                        row.style.display = '';
                        visibleFattureRows++;
                        setTimeout(() => {
                            row.style.opacity = '1';
                            row.style.transform = 'translateY(0)';
                        }, 100);
                    } else {
                        row.style.opacity = '0';
                        row.style.transform = 'translateY(-20px)';
                        setTimeout(() => {
                            row.style.display = 'none';
                        }, 300);
                    }
                });
                
                // Mostra/nascondi messaggio nessun risultato fatture
                if (fattureNoResults) {
                    if (visibleFattureRows === 0 && searchTerm !== '') {
                        fattureNoResults.style.display = 'block';
                    } else {
                        fattureNoResults.style.display = 'none';
                    }
                }
            }
            
            function shouldShowFattura(item, searchTerm) {
                if (searchTerm === '') {
                    // Applica solo filtro per stato se non c'è ricerca
                    if (currentFattureFilter === 'all') return true;
                    if (currentFattureFilter === 'definitivo') return item.dataset.stato === 'definitivo';
                    if (currentFattureFilter === 'potenziale') return item.dataset.stato === 'potenziale';
                    if (currentFattureFilter === 'recent') {
                        const dataFattura = new Date(item.dataset.data);
                        const oggi = new Date();
                        const giorni30Fa = new Date(oggi.getTime() - (30 * 24 * 60 * 60 * 1000));
                        return dataFattura >= giorni30Fa;
                    }
                    return true;
                }
                
                // Ricerca nel contenuto
                const matchesSearch = item.dataset.numero.includes(searchTerm) ||
                    item.dataset.cliente.includes(searchTerm) ||
                    item.dataset.data.includes(searchTerm) ||
                    item.dataset.totale.includes(searchTerm);
                
                if (!matchesSearch) return false;
                
                // Applica anche filtro per stato
                if (currentFattureFilter === 'all') return true;
                if (currentFattureFilter === 'definitivo') return item.dataset.stato === 'definitivo';
                if (currentFattureFilter === 'potenziale') return item.dataset.stato === 'potenziale';
                if (currentFattureFilter === 'recent') {
                    const dataFattura = new Date(item.dataset.data);
                    const oggi = new Date();
                    const giorni30Fa = new Date(oggi.getTime() - (30 * 24 * 60 * 60 * 1000));
                    return dataFattura >= giorni30Fa;
                }
                
                return true;
            }
            
            // Animazione di entrata delle righe fatture desktop
            fattureRows.forEach((row, index) => {
                setTimeout(() => {
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(-50px)';
                    row.style.transition = 'all 0.5s ease';
                    
                    setTimeout(() => {
                        row.style.opacity = '1';
                        row.style.transform = 'translateX(0)';
                    }, 100);
                }, index * 100);
            });
            
            // Animazione di entrata delle card mobile fatture
            mobileFattureRows.forEach((row, index) => {
                setTimeout(() => {
                    row.style.opacity = '0';
                    row.style.transform = 'translateY(30px)';
                    row.style.transition = 'all 0.5s ease';
                    
                    setTimeout(() => {
                        row.style.opacity = '1';
                        row.style.transform = 'translateY(0)';
                    }, 100);
                }, index * 150);
            });
        }
    });
</script>
@endsection