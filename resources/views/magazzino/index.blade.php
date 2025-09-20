@extends('layouts.app')

@section('title', __('app.controllo_avanzato_giacenze') . ' - Finson')

@section('content')
<style>
    .warehouse-container {
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
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    /* Dashboard Stats */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
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
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
    }
    
    .stat-card.products::before {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
    }
    
    .stat-card.variants::before {
        background: linear-gradient(135deg, #4299e1, #3182ce);
    }
    
    .stat-card.available::before {
        background: linear-gradient(135deg, #48bb78, #38a169);
    }
    
    .stat-card.out-stock::before {
        background: linear-gradient(135deg, #f56565, #e53e3e);
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
        margin-bottom: 1rem;
    }
    
    .stat-card.products .stat-icon {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
    }
    
    .stat-card.variants .stat-icon {
        background: linear-gradient(135deg, #4299e1, #3182ce);
    }
    
    .stat-card.available .stat-icon {
        background: linear-gradient(135deg, #48bb78, #38a169);
    }
    
    .stat-card.out-stock .stat-icon {
        background: linear-gradient(135deg, #f56565, #e53e3e);
    }
    
    .stat-value {
        font-size: 2.2rem;
        font-weight: 800;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #718096;
        font-size: 0.9rem;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    
    .search-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .search-input {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid rgba(2, 157, 126, 0.2);
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
    }
    
    .filter-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .filter-chip {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .filter-chip:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(2, 157, 126, 0.4);
    }
    
    .filter-chip.active {
        background: linear-gradient(135deg, #f72585, #c5025a);
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
    }
    
    .modern-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(2, 157, 126, 0.4);
        color: white;
    }
    
    .modern-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .modern-table {
        width: 100%;
        margin: 0;
    }
    
    .modern-table thead th {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        color: white;
        font-weight: 600;
        border: none;
        padding: 1rem;
        font-size: 0.9rem;
        text-transform: uppercase;
    }
    
    .modern-table tbody td {
        padding: 1rem;
        border: none;
        border-bottom: 1px solid rgba(2, 157, 126, 0.1);
        vertical-align: middle;
    }
    
    .modern-table tbody tr:hover {
        background: rgba(2, 157, 126, 0.05);
        transform: scale(1.01);
    }
    
    .action-btn {
        border: none;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .action-btn.details {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        color: white;
    }
    
    .product-info {
        display: flex;
        flex-direction: column;
    }
    
    .product-name {
        font-weight: 700;
        color: #2d3748;
        font-size: 1.1rem;
    }
    
    .product-category {
        font-size: 0.9rem;
        color: #718096;
        margin-top: 0.2rem;
    }
    
    .product-code {
        font-family: 'Courier New', monospace;
        background: rgba(2, 157, 126, 0.1);
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.9rem;
        color: #029D7E;
        font-weight: 600;
    }
    
    .price-display {
        font-weight: 700;
        color: #48bb78;
        font-size: 1.1rem;
    }
    
    .variant-info {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }
    
    .variant-badge {
        background: linear-gradient(135deg, #4299e1, #3182ce);
        color: white;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        width: fit-content;
    }
    
    .variant-details {
        font-size: 0.8rem;
        color: #718096;
    }
    
    .quantity-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 1rem;
    }
    
    .quantity-available {
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
    }
    
    .quantity-danger {
        background: linear-gradient(135deg, #f56565, #e53e3e);
        color: white;
    }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .status-ok {
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
    }
    
    .status-warning {
        background: linear-gradient(135deg, #fd7e14, #e85d04);
        color: white;
    }
    
    .status-danger {
        background: linear-gradient(135deg, #f56565, #e53e3e);
        color: white;
    }
    
    .low-stock-info {
        font-size: 0.75rem;
        color: #fd7e14;
        margin-top: 0.3rem;
        font-weight: 600;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 5rem;
        margin-bottom: 2rem;
        opacity: 0.3;
    }
    
    /* Mobile Cards */
    .mobile-cards {
        display: none;
    }
    
    .warehouse-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .warehouse-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .warehouse-card-header {
        margin-bottom: 1rem;
    }
    
    .warehouse-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.3rem;
    }
    
    .warehouse-card-subtitle {
        color: #718096;
        font-size: 0.9rem;
    }
    
    .warehouse-card-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .detail-item {
        display: flex;
        flex-direction: column;
    }
    
    .detail-label {
        font-size: 0.8rem;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 0.3rem;
    }
    
    .detail-value {
        font-weight: 600;
        color: #2d3748;
    }
    
    .warehouse-card-status {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    
    .warehouse-card-actions {
        display: flex;
        justify-content: center;
    }
    
    .mobile-action-btn {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .mobile-action-btn:hover {
        color: white;
    }
    
    /* Dark Mode */
    [data-bs-theme="dark"] .page-header,
    [data-bs-theme="dark"] .search-container,
    [data-bs-theme="dark"] .stat-card,
    [data-bs-theme="dark"] .modern-card,
    [data-bs-theme="dark"] .warehouse-card {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .search-input {
        background: rgba(45, 55, 72, 0.8);
        border-color: rgba(2, 157, 126, 0.3);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .stat-value,
    [data-bs-theme="dark"] .product-name,
    [data-bs-theme="dark"] .warehouse-card-title {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .product-code {
        background: rgba(2, 157, 126, 0.2);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .modern-table tbody td {
        color: #e2e8f0;
        border-bottom-color: rgba(2, 157, 126, 0.2);
    }
    
    [data-bs-theme="dark"] .detail-value {
        color: #e2e8f0;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .warehouse-container {
            padding: 1rem;
        }
        
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .stat-card {
            padding: 1.5rem;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }
        
        .stat-value {
            font-size: 1.8rem;
        }
        
        .modern-card .table-responsive {
            display: none;
        }
        
        .mobile-cards {
            display: block;
        }
    }
    
    @media (max-width: 576px) {
        .page-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .warehouse-card-details {
            grid-template-columns: 1fr;
            gap: 0.8rem;
        }
    }
</style>

@php
$totaleVarianti = $prodottiConStatistiche->sum('varianti_totali');
$totaleDisponibili = $prodottiConStatistiche->sum('varianti_disponibili');
$totaleEsauriti = $prodottiConStatistiche->sum('varianti_esaurite');
$totaleSconteBasse = $prodottiConStatistiche->sum('varianti_scorte_basse');
@endphp

<div class="warehouse-container">
    <!-- Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">
                <i class="bi bi-clipboard-data"></i> {{ __('app.controllo_avanzato_giacenze') }}
            </h1>
            <a href="{{ route('magazzino.create') }}" class="modern-btn">
                <i class="bi bi-plus-circle"></i> {{ __('app.new_stock') }}
            </a>
        </div>
    </div>
    
    <!-- Dashboard Statistiche -->
    <div class="stats-grid">
        <div class="stat-card products">
            <div class="stat-icon">
                <i class="bi bi-box"></i>
            </div>
            <div class="stat-value">{{ $prodottiConStatistiche->count() }}</div>
            <div class="stat-label">{{ __('app.products') }}</div>
        </div>
        
        <div class="stat-card variants">
            <div class="stat-icon">
                <i class="bi bi-boxes"></i>
            </div>
            <div class="stat-value">{{ $totaleVarianti }}</div>
            <div class="stat-label">{{ __('app.total_variants') }}</div>
        </div>
        
        <div class="stat-card available">
            <div class="stat-icon">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-value">{{ $totaleDisponibili }}</div>
            <div class="stat-label">{{ __('app.available') }}</div>
        </div>
        
        <div class="stat-card out-stock">
            <div class="stat-icon">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="stat-value">{{ $totaleEsauriti }}</div>
            <div class="stat-label">{{ __('app.out_of_stock') }}</div>
        </div>
    </div>
    
    <!-- Ricerca -->
    <div class="search-container">
        <input type="text" class="search-input" id="searchInput" placeholder="🔍 Cerca prodotti in magazzino per nome, codice, categoria...">
        <div class="filter-chips">
            <button class="filter-chip active" data-filter="all">{{ __('app.all') }}</button>
            <button class="filter-chip" data-filter="name">{{ __('app.name') }}</button>
            <button class="filter-chip" data-filter="code">{{ __('app.code') }}</button>
            <button class="filter-chip" data-filter="category">{{ __('app.category') }}</button>
            <button class="filter-chip" data-filter="status">{{ __('app.status') }}</button>
        </div>
    </div>
    
    <!-- Tabella/Cards -->
    <div class="modern-card">
        <!-- Desktop -->
        <div class="table-responsive">
            <table class="table modern-table">
                <thead>
                    <tr>
                        <th><i class="bi bi-box"></i> {{ __('app.product') }}</th>
                        <th><i class="bi bi-upc-scan"></i> {{ __('app.code') }}</th>
                        <th><i class="bi bi-currency-euro"></i> {{ __('app.price') }}</th>
                        <th><i class="bi bi-boxes"></i> {{ __('app.variants') }}</th>
                        <th><i class="bi bi-hash"></i> {{ __('app.total_pieces') }}</th>
                        <th><i class="bi bi-flag"></i> {{ __('app.status') }}</th>
                        <th><i class="bi bi-gear"></i> {{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prodottiConStatistiche as $item)
                    <tr class="warehouse-row"
                    data-name="{{ strtolower($item->prodotto->nome) }}"
                    data-code="{{ strtolower($item->prodotto->codice_prodotto) }}"
                    data-category="{{ strtolower($item->prodotto->categoria) }}"
                    data-status="{{ $item->stato_generale }}">
                    <td>
                        <div class="product-info">
                            <span class="product-name">{{ $item->prodotto->nome }}</span>
                            <span class="product-category">{{ $item->prodotto->categoria }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="product-code">{{ $item->prodotto->codice_prodotto }}</span>
                    </td>
                    <td>
                        <span class="price-display">€ {{ number_format($item->prodotto->prezzo, 2) }}</span>
                    </td>
                    <td>
                        <div class="variant-info">
                            <span class="variant-badge">{{ $item->varianti_totali }} {{ __('app.variants') }}</span>
                            <span class="variant-details">
                                {{ $item->varianti_disponibili }} {{ __('app.available') }}, {{ $item->varianti_esaurite }} {{ __('app.out_of_stock') }}
                            </span>
                        </div>
                    </td>
                    <td>
                        <span class="quantity-badge {{ $item->totale_quantita > 0 ? 'quantity-available' : 'quantity-danger' }}">
                            {{ $item->totale_quantita }} {{ __('app.pcs') }}
                        </span>
                    </td>
                    <td>
                        @if($item->stato_generale == 'esaurito')
                        <span class="status-badge status-danger">
                            <i class="bi bi-x-circle"></i> {{ __('app.out_of_stock') }}
                        </span>
                        @elseif($item->stato_generale == 'attenzione')
                        <span class="status-badge status-warning">
                            <i class="bi bi-exclamation-triangle"></i> {{ __('app.warning') }}
                        </span>
                        @if($item->varianti_scorte_basse > 0)
                        <div class="low-stock-info">
                            {{ $item->varianti_scorte_basse }} {{ __('app.low_stock') }}
                        </div>
                        @endif
                        @else
                        <span class="status-badge status-ok">
                            <i class="bi bi-check-circle"></i> OK
                        </span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('magazzino.dettaglio-prodotto', $item->prodotto->id) }}" class="action-btn details">
                            <i class="bi bi-eye"></i> {{ __('app.details') }}
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-boxes"></i>
                            <h5>{{ __('app.no_products_in_warehouse') }}</h5>
                            <p>{{ __('app.start_adding_products_and_stock') }}</p>
                            <a href="{{ route('prodotti.create') }}" class="modern-btn">
                                <i class="bi bi-plus-circle"></i> {{ __('app.create_first_product') }}
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Mobile -->
    <div class="mobile-cards">
        @forelse($prodottiConStatistiche as $item)
        <div class="warehouse-card mobile-warehouse-row"
        data-name="{{ strtolower($item->prodotto->nome) }}"
        data-code="{{ strtolower($item->prodotto->codice_prodotto) }}"
        data-category="{{ strtolower($item->prodotto->categoria) }}"
        data-status="{{ $item->stato_generale }}">
        
        <div class="warehouse-card-header">
            <div class="warehouse-card-title">{{ $item->prodotto->nome }}</div>
            <div class="warehouse-card-subtitle">{{ $item->prodotto->categoria }}</div>
        </div>
        
        <div class="warehouse-card-details">
            <div class="detail-item">
                <div class="detail-label">{{ __('app.code') }}</div>
                <div class="detail-value">
                    <span class="product-code">{{ $item->prodotto->codice_prodotto }}</span>
                </div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">{{ __('app.price') }}</div>
                <div class="detail-value">
                    <span class="price-display">€ {{ number_format($item->prodotto->prezzo, 2) }}</span>
                </div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">{{ __('app.variants') }}</div>
                <div class="detail-value">
                    <span class="variant-badge">{{ $item->varianti_totali }}</span>
                </div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">{{ __('app.total_pieces') }}</div>
                <div class="detail-value">
                    <span class="quantity-badge {{ $item->totale_quantita > 0 ? 'quantity-available' : 'quantity-danger' }}">
                        {{ $item->totale_quantita }} {{ __('app.pcs') }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="warehouse-card-status">
            @if($item->stato_generale == 'esaurito')
            <span class="status-badge status-danger">
                <i class="bi bi-x-circle"></i> {{ __('app.out_of_stock') }}
            </span>
            @elseif($item->stato_generale == 'attenzione')
            <span class="status-badge status-warning">
                <i class="bi bi-exclamation-triangle"></i> {{ __('app.warning') }}
            </span>
            @if($item->varianti_scorte_basse > 0)
            <div class="low-stock-info">
                {{ $item->varianti_scorte_basse }} {{ __('app.low_stock') }}
            </div>
            @endif
            @else
            <span class="status-badge status-ok">
                <i class="bi bi-check-circle"></i> OK
            </span>
            @endif
        </div>
        
        <div class="warehouse-card-actions">
            <a href="{{ route('magazzino.dettaglio-prodotto', $item->prodotto->id) }}" class="mobile-action-btn">
                <i class="bi bi-eye"></i> {{ __('app.details') }}
            </a>
        </div>
    </div>
    @empty
    <div class="warehouse-card">
        <div class="empty-state">
            <i class="bi bi-boxes"></i>
            <h5>{{ __('app.no_products_in_warehouse') }}</h5>
            <p>{{ __('app.start_adding_products_and_stock') }}</p>
            <a href="{{ route('prodotti.create') }}" class="modern-btn">
                <i class="bi bi-plus-circle"></i> {{ __('app.create_first_product') }}
            </a>
        </div>
    </div>
    @endforelse
</div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const filterChips = document.querySelectorAll('.filter-chip');
        const warehouseRows = document.querySelectorAll('.warehouse-row');
        const mobileWarehouseRows = document.querySelectorAll('.mobile-warehouse-row');
        let currentFilter = 'all';
        
        // Gestione filtri
        filterChips.forEach(chip => {
            chip.addEventListener('click', function() {
                filterChips.forEach(c => c.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.dataset.filter;
                performSearch();
            });
        });
        
        // Ricerca in tempo reale
        searchInput.addEventListener('input', performSearch);
        
        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let visibleRows = 0;
            
            // Ricerca desktop
            warehouseRows.forEach(row => {
                if (shouldShowItem(row, searchTerm)) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Ricerca mobile
            mobileWarehouseRows.forEach(row => {
                if (shouldShowItem(row, searchTerm)) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        function shouldShowItem(item, searchTerm) {
            if (searchTerm === '') return true;
            
            if (currentFilter === 'all') {
                return item.dataset.name.includes(searchTerm) ||
                item.dataset.code.includes(searchTerm) ||
                item.dataset.category.includes(searchTerm) ||
                item.dataset.status.includes(searchTerm);
            } else {
                return item.dataset[currentFilter].includes(searchTerm);
            }
        }
        
        // Animazioni di entrata
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                card.style.transition = 'all 0.6s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100);
            }, index * 200);
        });
    });
</script>
@endsection