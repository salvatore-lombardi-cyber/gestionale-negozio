@extends('layouts.app')

@section('title', __('app.products') . ' - Gestionale Negozio')

@section('content')
<style>
    .products-container {
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
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
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
    
    .search-box {
        position: relative;
    }
    
    .search-input {
        width: 100%;
        padding: 15px 50px 15px 20px;
        border: 2px solid rgba(102, 126, 234, 0.2);
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
    }
    
    .search-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        background: white;
    }
    
    .search-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #667eea;
        font-size: 1.2rem;
    }
    
    .filter-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .filter-chip {
        background: linear-gradient(135deg, #667eea, #764ba2);
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
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    
    .filter-chip.active {
        background: linear-gradient(135deg, #f72585, #c5025a);
    }
    
    .modern-btn {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        border-radius: 15px;
        padding: 12px 24px;
        font-weight: 600;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
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
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        color: white;
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
        background: linear-gradient(135deg, #667eea, #764ba2);
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
        background: rgba(102, 126, 234, 0.05);
        transform: scale(1.01);
    }
    
    .modern-table tbody td {
        padding: 1rem;
        border: none;
        border-bottom: 1px solid rgba(102, 126, 234, 0.1);
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
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .action-btn.labels {
        background: linear-gradient(135deg, #6f42c1, #8e44ad);
        color: white;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: white;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .product-code {
        font-family: 'Courier New', monospace;
        background: rgba(102, 126, 234, 0.1);
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.9rem;
    }
    
    .price-tag {
        font-weight: 700;
        color: #28a745;
        font-size: 1.1rem;
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
    
    .no-results {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
    }
    
    /* Dark Mode */
    [data-bs-theme="dark"] .page-header,
    [data-bs-theme="dark"] .search-container {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .modern-card {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .search-input {
        background: rgba(45, 55, 72, 0.8);
        border-color: rgba(102, 126, 234, 0.3);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .search-input:focus {
        background: rgba(45, 55, 72, 0.9);
    }
    
    [data-bs-theme="dark"] .modern-table tbody tr:hover {
        background: rgba(102, 126, 234, 0.1);
    }
    
    [data-bs-theme="dark"] .modern-table tbody td {
        color: #e2e8f0;
        border-bottom-color: rgba(102, 126, 234, 0.2);
    }
    
    [data-bs-theme="dark"] .product-code {
        background: rgba(102, 126, 234, 0.2);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .empty-state,
    [data-bs-theme="dark"] .no-results {
        color: #a0aec0;
    }
    
    /* Mobile Cards - Nasconde tabella, mostra card */
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
    
    .product-card-code {
        font-family: 'Courier New', monospace;
        background: rgba(102, 126, 234, 0.1);
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.8rem;
        flex-shrink: 0;
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
        color: #28a745;
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
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .mobile-action-btn.labels {
        background: linear-gradient(135deg, #6f42c1, #8e44ad);
        color: white;
    }
    
    .mobile-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: white;
    }
    
    /* Dark mode per mobile cards */
    [data-bs-theme="dark"] .product-card {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .product-card-code {
        background: rgba(102, 126, 234, 0.2);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .product-detail-label {
        color: #a0aec0;
    }
    
    [data-bs-theme="dark"] .product-card-price {
        background: rgba(40, 167, 69, 0.2);
    }
    
    /* Responsive perfetto */
    @media (max-width: 768px) {
        .products-container {
            padding: 1rem;
        }
        
        .page-header, .search-container {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .page-title {
            font-size: 2rem;
        }
        
        .modern-btn {
            padding: 10px 20px;
            font-size: 0.9rem;
        }
        
        .filter-chips {
            justify-content: center;
        }
        
        /* Nasconde tabella su mobile */
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

<div class="products-container">
    <!-- Header della Pagina -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">
                <i class="bi bi-box-seam"></i> {{ __('app.products') }}
            </h1>
            <div class="d-flex gap-2">
                <a href="{{ route('labels.index') }}" class="modern-btn" style="background: linear-gradient(135deg, #28a745, #20c997);">
                    <i class="bi bi-qr-code"></i> {{ __('app.qr_labels') }}
                </a>
                <a href="{{ route('prodotti.create') }}" class="modern-btn">
                    <i class="bi bi-plus-circle"></i> {{ __('app.new') }} {{ __('app.product') }}
                </a>
            </div>
        </div>
    </div>
    
    <!-- Barra di Ricerca Avanzata -->
    <div class="search-container">
        <div class="search-box">
            <input type="text" 
            class="search-input" 
            id="searchInput" 
            placeholder="🔍 {{ __('app.search_products_placeholder') }}"            
            autocomplete="off">
            <i class="bi bi-search search-icon"></i>
        </div>
        <div class="filter-chips">
            <button class="filter-chip active" data-filter="all">{{ __('app.all') }}</button>
            <button class="filter-chip" data-filter="name">{{ __('app.name') }}</button>
            <button class="filter-chip" data-filter="code">{{ __('app.code') }}</button>
            <button class="filter-chip" data-filter="category">{{ __('app.category') }}</button>
            <button class="filter-chip" data-filter="brand">{{ __('app.brand') }}</button>
            <button class="filter-chip" data-filter="price">{{ __('app.price') }}</button>
        </div>
    </div>
    
    <!-- Tabella Prodotti -->
    <div class="modern-card">
        <!-- Tabella Desktop -->
        <div class="table-responsive">
            <table class="table modern-table" id="productsTable">
                <thead>
                    <tr>
                        <th><i class="bi bi-upc-scan"></i> {{ __('app.code') }}</th>
                        <th><i class="bi bi-tag"></i> {{ __('app.name') }}</th>
                        <th><i class="bi bi-grid-3x3-gap"></i> {{ __('app.category') }}</th>
                        <th><i class="bi bi-award"></i> {{ __('app.brand') }}</th>
                        <th><i class="bi bi-currency-euro"></i> {{ __('app.price') }}</th>
                        <th><i class="bi bi-qr-code"></i> QR</th>
                        <th><i class="bi bi-gear"></i> {{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody id="productsTableBody">
                    @forelse($prodotti as $prodotto)
                    <tr class="product-row" 
                    data-name="{{ strtolower($prodotto->nome) }}"
                    data-code="{{ strtolower($prodotto->codice_prodotto) }}"
                    data-category="{{ strtolower($prodotto->categoria) }}"
                    data-brand="{{ strtolower($prodotto->brand ?? '') }}"
                    data-price="{{ $prodotto->prezzo }}">
                    <td>
                        <span class="product-code">{{ $prodotto->codice_prodotto }}</span>
                        @if($prodotto->hasLabelCode())
                        <br><small class="text-muted">{{ $prodotto->codice_etichetta }}</small>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $prodotto->nome }}</strong>
                    </td>
                    <td>
                        <span class="badge" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                            {{ $prodotto->categoria }}
                        </span>
                    </td>
                    <td>{{ $prodotto->brand ?? 'N/A' }}</td>
                    <td>
                        <span class="price-tag">€ {{ number_format($prodotto->prezzo, 2) }}</span>
                    </td>
                    <td>
                        @if($prodotto->hasQRCode())
                        <span class="qr-status ready">
                            <i class="bi bi-check-circle"></i> Ready
                        </span>
                        @else
                        <span class="qr-status missing">
                            <i class="bi bi-exclamation-triangle"></i> Missing
                        </span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('prodotti.show', $prodotto) }}" class="action-btn view">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('prodotti.edit', $prodotto) }}" class="action-btn edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button class="action-btn qr" onclick="generateQR({{ $prodotto->id }})" title="Genera QR Code">
                            <i class="bi bi-qr-code"></i>
                        </button>
                        <a href="{{ route('prodotti.labels', $prodotto->id) }}" class="action-btn labels" title="Stampa Etichette">
                            <i class="bi bi-printer"></i>
                        </a>
                        <form action="{{ route('prodotti.destroy', $prodotto) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete" onclick="return confirm('{{ __('app.confirm_delete') }}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="bi bi-box-seam"></i>
                            <h5>{{ __('app.no_products_found') }}</h5>
                            <p>Inizia aggiungendo il primo prodotto al tuo catalogo</p>
                            <a href="{{ route('prodotti.create') }}" class="modern-btn">
                                <i class="bi bi-plus-circle"></i> Aggiungi Primo Prodotto
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Cards Mobile -->
    <div class="mobile-cards" id="mobileCards">
        @forelse($prodotti as $prodotto)
        <div class="product-card mobile-product-row"
        data-name="{{ strtolower($prodotto->nome) }}"
        data-code="{{ strtolower($prodotto->codice_prodotto) }}"
        data-category="{{ strtolower($prodotto->categoria) }}"
        data-brand="{{ strtolower($prodotto->brand ?? '') }}"
        data-price="{{ $prodotto->prezzo }}">
        
        <div class="product-card-header">
            <h3 class="product-card-title">{{ $prodotto->nome }}</h3>
            <div class="d-flex flex-column align-items-end">
                <span class="product-card-code">{{ $prodotto->codice_prodotto }}</span>
                @if($prodotto->hasQRCode())
                <small class="qr-status ready mt-1">
                    <i class="bi bi-check-circle"></i> QR Ready
                </small>
                @else
                <small class="qr-status missing mt-1">
                    <i class="bi bi-exclamation-triangle"></i> QR Missing
                </small>
                @endif
            </div>
        </div>
        
        <div class="product-card-details">
            <div class="product-detail">
                <span class="product-detail-label">{{ __('app.category') }}</span>
                <span class="product-detail-value">
                    <span class="badge" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; font-size: 0.7rem;">
                        {{ $prodotto->categoria }}
                    </span>
                </span>
            </div>
            <div class="product-detail">
                <span class="product-detail-label">{{ __('app.brand') }}</span>
                <span class="product-detail-value">{{ $prodotto->brand ?? 'N/A' }}</span>
            </div>
        </div>
        
        <div class="product-card-price">
            € {{ number_format($prodotto->prezzo, 2) }}
        </div>
        
        <div class="product-card-actions">
            <a href="{{ route('prodotti.show', $prodotto) }}" class="mobile-action-btn view">
                <i class="bi bi-eye"></i>
                <span>{{ __('app.view') }}</span>
            </a>
            <a href="{{ route('prodotti.edit', $prodotto) }}" class="mobile-action-btn edit">
                <i class="bi bi-pencil"></i>
                <span>{{ __('app.edit') }}</span>
            </a>
            <button class="mobile-action-btn qr" onclick="generateQR({{ $prodotto->id }})" style="border: none; width: 100%;">
                <i class="bi bi-qr-code"></i>
                <span>QR</span>
            </button>
            <a href="{{ route('prodotti.labels', $prodotto->id) }}" class="mobile-action-btn labels">
                <i class="bi bi-printer"></i>
                <span>{{ __('app.labels') }}</span>
            </a>
            <form action="{{ route('prodotti.destroy', $prodotto) }}" method="POST" style="flex: 1;">
                @csrf
                @method('DELETE')
                <button type="submit" class="mobile-action-btn delete" style="width: 100%; border: none;" onclick="return confirm('{{ __('app.confirm_delete') }}')">
                    <i class="bi bi-trash"></i>
                    <span>{{ __('app.delete') }}</span>
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="product-card">
        <div class="empty-state">
            <i class="bi bi-box-seam"></i>
            <h5>{{ __('app.no_products_found') }}</h5>
            <p>Inizia aggiungendo il primo prodotto al tuo catalogo</p>
            <a href="{{ route('prodotti.create') }}" class="modern-btn">
                <i class="bi bi-plus-circle"></i> Aggiungi Primo Prodotto
            </a>
        </div>
    </div>
    @endforelse
</div>

<!-- Messaggio nessun risultato -->
<div id="noResults" class="no-results" style="display: none;">
    <i class="bi bi-search"></i>
    <h5>Nessun prodotto trovato</h5>
    <p>Prova a modificare i criteri di ricerca</p>
</div>
</div>
</div>

<script>
    // Sistema di ricerca avanzata
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const filterChips = document.querySelectorAll('.filter-chip');
        const productRows = document.querySelectorAll('.product-row');
        const mobileProductRows = document.querySelectorAll('.mobile-product-row');
        const noResults = document.getElementById('noResults');
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
        searchInput.addEventListener('input', function() {
            performSearch();
        });
        
        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let visibleRows = 0;
            
            // Ricerca nella tabella desktop
            productRows.forEach(row => {
                let shouldShow = shouldShowItem(row, searchTerm);
                
                if (shouldShow) {
                    row.style.display = '';
                    visibleRows++;
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
            
            // Ricerca nelle card mobile
            mobileProductRows.forEach(row => {
                let shouldShow = shouldShowItem(row, searchTerm);
                
                if (shouldShow) {
                    row.style.display = '';
                    visibleRows++;
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
            
            // Mostra/nascondi messaggio nessun risultato
            if (visibleRows === 0 && searchTerm !== '') {
                noResults.style.display = 'block';
            } else {
                noResults.style.display = 'none';
            }
        }
        
        function shouldShowItem(item, searchTerm) {
            if (searchTerm === '') return true;
            
            if (currentFilter === 'all') {
                return item.dataset.name.includes(searchTerm) ||
                item.dataset.code.includes(searchTerm) ||
                item.dataset.category.includes(searchTerm) ||
                item.dataset.brand.includes(searchTerm) ||
                item.dataset.price.includes(searchTerm);
            } else {
                return item.dataset[currentFilter].includes(searchTerm);
            }
        }
        
        // Animazione di entrata delle righe desktop
        productRows.forEach((row, index) => {
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
        
        // Animazione di entrata delle card mobile
        mobileProductRows.forEach((row, index) => {
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
    });
    
    // Funzione per generare QR Code
    function generateQR(productId) {
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        
        // Mostra loading
        button.innerHTML = '<i class="bi bi-arrow-clockwise spin"></i>';
        button.disabled = true;
        
        fetch(`/prodotti/${productId}/generate-all-qr`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Aggiorna lo stato visuale
                updateQRStatus(productId, true);
                showNotification('QR Code generati con successo!', 'success');
            } else {
                showNotification('Errore nella generazione: ' + data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Errore di connessione: ' + error.message, 'error');
        })
        .finally(() => {
            // Ripristina il bottone
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
    
    // Aggiorna stato QR visualmente
    function updateQRStatus(productId, hasQR) {
        const rows = document.querySelectorAll(`[data-product-id="${productId}"]`);
        rows.forEach(row => {
            const statusElement = row.querySelector('.qr-status');
            if (statusElement) {
                if (hasQR) {
                    statusElement.className = 'qr-status ready';
                    statusElement.innerHTML = '<i class="bi bi-check-circle"></i> Ready';
                } else {
                    statusElement.className = 'qr-status missing';
                    statusElement.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Missing';
                }
            }
        });
    }
    
    // Sistema notifiche
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
           ${message}
           <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
       `;
        document.body.appendChild(notification);
        
        // Auto rimozione dopo 5 secondi
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
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
</script>
@endsection