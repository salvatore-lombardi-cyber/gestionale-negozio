@extends('layouts.app')

@section('title', __('app.delivery_documents') . ' - Gestionale Negozio')

@section('content')
<style>
    .ddts-container {
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
    
    .search-input {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid rgba(102, 126, 234, 0.2);
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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
    }
    
    .modern-table {
        width: 100%;
        margin: 0;
    }
    
    .modern-table thead th {
        background: linear-gradient(135deg, #667eea, #764ba2);
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
        border-bottom: 1px solid rgba(102, 126, 234, 0.1);
        vertical-align: middle;
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
    
    .action-btn:hover {
        transform: translateY(-2px);
        color: white;
    }
    
    /* Status Badges */
    .status-badge {
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .status-draft {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        color: white;
    }
    
    .status-confirmed {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
        color: white;
    }
    
    .status-shipped {
        background: linear-gradient(135deg, #fd7e14, #e85d04);
        color: white;
    }
    
    .status-delivered {
        background: linear-gradient(135deg, #198754, #157347);
        color: white;
    }
    
    .ddt-number {
        font-family: 'Courier New', monospace;
        background: rgba(102, 126, 234, 0.1);
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 700;
        color: #667eea;
    }
    
    .customer-name {
        font-weight: 600;
        color: #2d3748;
    }
    
    .recipient-name {
        color: #718096;
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
    
    .ddt-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .ddt-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .ddt-card-number {
        font-family: 'Courier New', monospace;
        background: rgba(102, 126, 234, 0.1);
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 700;
        color: #667eea;
    }
    
    .ddt-card-details {
        margin-bottom: 1.5rem;
    }
    
    .ddt-detail {
        margin-bottom: 1rem;
    }
    
    .ddt-detail-label {
        font-size: 0.8rem;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 0.3rem;
    }
    
    .ddt-detail-value {
        font-weight: 600;
        color: #2d3748;
    }
    
    .ddt-card-status {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    
    .ddt-card-actions {
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
    [data-bs-theme="dark"] .modern-card,
    [data-bs-theme="dark"] .ddt-card {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .search-input {
        background: rgba(45, 55, 72, 0.8);
        border-color: rgba(102, 126, 234, 0.3);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .ddt-number,
    [data-bs-theme="dark"] .ddt-card-number {
        background: rgba(102, 126, 234, 0.2);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .customer-name {
        color: #e2e8f0;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .ddts-container {
            padding: 1rem;
        }
        
        .modern-card .table-responsive {
            display: none;
        }
        
        .mobile-cards {
            display: block;
        }
    }
</style>

<div class="ddts-container">
    <!-- Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">
                <i class="bi bi-file-earmark-text"></i> {{ __('app.delivery_documents') }} (DDT)
            </h1>
            <a href="{{ route('ddts.create') }}" class="modern-btn">
                <i class="bi bi-plus-circle"></i> {{ __('app.new') }} DDT
            </a>
        </div>
    </div>
    
    <!-- Ricerca -->
    <div class="search-container">
        <input type="text" class="search-input" id="searchInput" placeholder="🔍 Cerca DDT per numero, cliente, destinatario, stato...">
        <div class="filter-chips">
            <button class="filter-chip active" data-filter="all">Tutti</button>
            <button class="filter-chip" data-filter="number">Numero</button>
            <button class="filter-chip" data-filter="customer">Cliente</button>
            <button class="filter-chip" data-filter="recipient">Destinatario</button>
            <button class="filter-chip" data-filter="status">Stato</button>
        </div>
    </div>
    
    <!-- Tabella/Cards -->
    <div class="modern-card">
        <!-- Desktop -->
        <div class="table-responsive">
            <table class="table modern-table">
                <thead>
                    <tr>
                        <th><i class="bi bi-hash"></i> {{ __('app.ddt_number') }}</th>
                        <th><i class="bi bi-calendar"></i> {{ __('app.date') }}</th>
                        <th><i class="bi bi-person"></i> {{ __('app.customer') }}</th>
                        <th><i class="bi bi-geo-alt"></i> {{ __('app.recipient') }}</th>
                        <th><i class="bi bi-flag"></i> {{ __('app.status') }}</th>
                        <th><i class="bi bi-gear"></i> {{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ddts as $ddt)
                    <tr class="ddt-row"
                    data-number="{{ strtolower($ddt->numero_ddt) }}"
                    data-customer="{{ strtolower($ddt->cliente ? $ddt->cliente->nome_completo : 'cliente occasionale') }}"
                    data-recipient="{{ strtolower($ddt->destinatario_completo) }}"
                    data-status="{{ $ddt->stato }}">
                    <td>
                        <span class="ddt-number">{{ $ddt->numero_ddt }}</span>
                    </td>
                    <td>{{ $ddt->data_ddt->format('d/m/Y') }}</td>
                    <td>
                        <span class="customer-name">{{ $ddt->cliente ? $ddt->cliente->nome_completo : 'Cliente occasionale' }}</span>
                    </td>
                    <td>
                        <span class="recipient-name">{{ $ddt->destinatario_completo }}</span>
                    </td>
                    <td>
                        <span class="status-badge status-{{ $ddt->stato }}">
                            @if($ddt->stato == 'bozza')
                            <i class="bi bi-file-earmark"></i> {{ __('app.draft') }}
                            @elseif($ddt->stato == 'confermato')
                            <i class="bi bi-check-circle"></i> {{ __('app.confirmed') }}
                            @elseif($ddt->stato == 'spedito')
                            <i class="bi bi-truck"></i> {{ __('app.shipped') }}
                            @else
                            <i class="bi bi-check-circle-fill"></i> {{ __('app.delivered') }}
                            @endif
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('ddts.show', $ddt) }}" class="action-btn view">
                            <i class="bi bi-eye"></i> {{ __('app.view') }}
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <i class="bi bi-file-earmark-text"></i>
                            <h5>{{ __('app.no_ddts_created') }}</h5>
                            <p>{{ __('app.start_creating_first_ddt') }}</p>
                            <a href="{{ route('ddts.create') }}" class="modern-btn">
                                <i class="bi bi-plus-circle"></i> {{ __('app.create_first_ddt') }}
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
        @forelse($ddts as $ddt)
        <div class="ddt-card mobile-ddt-row"
        data-number="{{ strtolower($ddt->numero_ddt) }}"
        data-customer="{{ strtolower($ddt->cliente->nome_completo) }}"
        data-recipient="{{ strtolower($ddt->destinatario_completo) }}"
        data-status="{{ $ddt->stato }}">
        
        <div class="ddt-card-header">
            <span class="ddt-card-number">{{ $ddt->numero_ddt }}</span>
            <span>{{ $ddt->data_ddt->format('d/m/Y') }}</span>
        </div>
        
        <div class="ddt-card-details">
            <div class="ddt-detail">
                <div class="ddt-detail-label">{{ __('app.customer') }}</div>
                <div class="ddt-detail-value">{{ $ddt->cliente->nome_completo }}</div>
            </div>
            
            <div class="ddt-detail">
                <div class="ddt-detail-label">{{ __('app.recipient') }}</div>
                <div class="ddt-detail-value">{{ $ddt->destinatario_completo }}</div>
            </div>
        </div>
        
        <div class="ddt-card-status">
            <span class="status-badge status-{{ $ddt->stato }}">
                @if($ddt->stato == 'bozza')
                <i class="bi bi-file-earmark"></i> {{ __('app.draft') }}
                @elseif($ddt->stato == 'confermato')
                <i class="bi bi-check-circle"></i> {{ __('app.confirmed') }}
                @elseif($ddt->stato == 'spedito')
                <i class="bi bi-truck"></i> {{ __('app.shipped') }}
                @else
                <i class="bi bi-check-circle-fill"></i> {{ __('app.delivered') }}
                @endif
            </span>
        </div>
        
        <div class="ddt-card-actions">
            <a href="{{ route('ddts.show', $ddt) }}" class="mobile-action-btn">
                <i class="bi bi-eye"></i> {{ __('app.view') }}
            </a>
        </div>
    </div>
    @empty
    <div class="ddt-card">
        <div class="empty-state">
            <i class="bi bi-file-earmark-text"></i>
            <h5>{{ __('app.no_ddts_created') }}</h5>
            <p>{{ __('app.start_creating_first_ddt') }}</p>
            <a href="{{ route('ddts.create') }}" class="modern-btn">
                <i class="bi bi-plus-circle"></i> {{ __('app.create_first_ddt') }}
            </a>
        </div>
    </div>
    @endforelse
</div>

<div id="noResults" class="empty-state" style="display: none;">
    <i class="bi bi-search"></i>
    <h5>Nessun DDT trovato</h5>
    <p>Prova a modificare i criteri di ricerca</p>
</div>
</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const filterChips = document.querySelectorAll('.filter-chip');
        const ddtRows = document.querySelectorAll('.ddt-row');
        const mobileDdtRows = document.querySelectorAll('.mobile-ddt-row');
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
        searchInput.addEventListener('input', performSearch);
        
        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let visibleRows = 0;
            
            // Ricerca desktop
            ddtRows.forEach(row => {
                if (shouldShowItem(row, searchTerm)) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Ricerca mobile
            mobileDdtRows.forEach(row => {
                if (shouldShowItem(row, searchTerm)) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            noResults.style.display = (visibleRows === 0 && searchTerm !== '') ? 'block' : 'none';
        }
        
        function shouldShowItem(item, searchTerm) {
            if (searchTerm === '') return true;
            
            if (currentFilter === 'all') {
                return item.dataset.number.includes(searchTerm) ||
                item.dataset.customer.includes(searchTerm) ||
                item.dataset.recipient.includes(searchTerm) ||
                item.dataset.status.includes(searchTerm);
            } else {
                return item.dataset[currentFilter].includes(searchTerm);
            }
        }
    });
</script>
@endsection