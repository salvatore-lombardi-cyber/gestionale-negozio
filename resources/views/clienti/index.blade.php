@extends('layouts.app')

@section('title', __('app.clients') . ' - Gestionale Negozio')

@section('content')
<style>
    .clients-container {
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
    
    .client-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
        margin-right: 10px;
    }
    
    .client-name {
        display: flex;
        align-items: center;
    }
    
    .contact-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .contact-item {
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .contact-item i {
        width: 16px;
        margin-right: 5px;
        color: #667eea;
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
    
    [data-bs-theme="dark"] .contact-item {
        color: #a0aec0;
    }
    
    [data-bs-theme="dark"] .empty-state,
    [data-bs-theme="dark"] .no-results {
        color: #a0aec0;
    }
    
    /* Mobile Cards */
    .mobile-cards {
        display: none;
    }
    
    .client-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .client-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .client-card-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .mobile-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.4rem;
        margin-right: 15px;
        flex-shrink: 0;
    }
    
    .client-card-name {
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.2;
    }
    
    .client-card-details {
        margin-bottom: 1.5rem;
    }
    
    .client-detail {
        display: flex;
        align-items: center;
        margin-bottom: 0.8rem;
        font-size: 0.95rem;
    }
    
    .client-detail i {
        width: 20px;
        color: #667eea;
        margin-right: 10px;
    }
    
    .client-card-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }
    
    .mobile-action-btn {
        flex: 1;
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
    
    .mobile-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: white;
    }
    
    /* Dark mode per mobile cards */
    [data-bs-theme="dark"] .client-card {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .clients-container {
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
        
        .client-card {
            padding: 1rem;
        }
        
        .mobile-action-btn {
            padding: 10px 6px;
            font-size: 0.7rem;
        }
        
        .mobile-action-btn i {
            font-size: 1rem;
        }
    }
</style>

<div class="clients-container">
    <!-- Header della Pagina -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">
                <i class="bi bi-people"></i> {{ __('app.clients') }}
            </h1>
            <a href="{{ route('clienti.create') }}" class="modern-btn">
                <i class="bi bi-person-plus"></i> {{ __('app.new') }} {{ __('app.client') }}
            </a>
        </div>
    </div>
    
    <!-- Barra di Ricerca Avanzata -->
    <div class="search-container">
        <div class="search-box">
            <input type="text" 
            class="search-input" 
            id="searchInput" 
            placeholder="🔍 {{ __('app.search_clients_placeholder') }}"                   
            autocomplete="off">
            <i class="bi bi-search search-icon"></i>
        </div>
        <div class="filter-chips">
            <button class="filter-chip active" data-filter="all">{{ __('app.all') }}</button>
            <button class="filter-chip" data-filter="name">{{ __('app.name') }}</button>
            <button class="filter-chip" data-filter="surname">{{ __('app.surname') }}</button>
            <button class="filter-chip" data-filter="phone">{{ __('app.phone') }}</button>
            <button class="filter-chip" data-filter="email">{{ __('app.email') }}</button>
            <button class="filter-chip" data-filter="city">{{ __('app.city') }}</button>
        </div>
    </div>
    
    <!-- Tabella/Cards Clienti -->
    <div class="modern-card">
        <!-- Tabella Desktop -->
        <div class="table-responsive">
            <table class="table modern-table" id="clientsTable">
                <thead>
                    <tr>
                        <th><i class="bi bi-person"></i> {{ __('app.client') }}</th>
                        <th><i class="bi bi-telephone"></i> {{ __('app.phone') }}</th>
                        <th><i class="bi bi-envelope"></i> {{ __('app.email') }}</th>
                        <th><i class="bi bi-geo-alt"></i> {{ __('app.city') }}</th>
                        <th><i class="bi bi-gear"></i> {{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody id="clientsTableBody">
                    @forelse($clienti as $cliente)
                    <tr class="client-row" 
                    data-name="{{ strtolower($cliente->nome) }}"
                    data-surname="{{ strtolower($cliente->cognome) }}"
                    data-phone="{{ strtolower($cliente->telefono ?? '') }}"
                    data-email="{{ strtolower($cliente->email ?? '') }}"
                    data-city="{{ strtolower($cliente->citta ?? '') }}">
                    <td>
                        <div class="client-name">
                            <div class="client-avatar">
                                {{ strtoupper(substr($cliente->nome, 0, 1)) }}{{ strtoupper(substr($cliente->cognome, 0, 1)) }}
                            </div>
                            <div>
                                <strong>{{ $cliente->nome }} {{ $cliente->cognome }}</strong>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($cliente->telefono)
                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="bi bi-telephone"></i>{{ $cliente->telefono }}
                            </div>
                        </div>
                        @else
                        <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($cliente->email)
                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="bi bi-envelope"></i>{{ $cliente->email }}
                            </div>
                        </div>
                        @else
                        <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        @if($cliente->citta)
                        <span class="badge" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                            {{ $cliente->citta }}
                        </span>
                        @else
                        <span class="text-muted">N/A</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('clienti.show', $cliente) }}" class="action-btn view">
                            <i class="bi bi-eye"></i> <span>{{ __('app.view') }}</span>
                        </a>
                        <a href="{{ route('clienti.edit', $cliente) }}" class="action-btn edit">
                            <i class="bi bi-pencil"></i> <span>{{ __('app.edit') }}</span>
                        </a>
                        <form action="{{ route('clienti.destroy', $cliente) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete" onclick="return confirm('{{ __('app.confirm_delete') }}')">
                                <i class="bi bi-trash"></i> <span>{{ __('app.delete') }}</span>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">
                        <div class="empty-state">
                            <i class="bi bi-people"></i>
                            <h5>{{ __('app.no_clients_found') }}</h5>
                            <p>Inizia aggiungendo il primo cliente al tuo database</p>
                            <a href="{{ route('clienti.create') }}" class="modern-btn">
                                <i class="bi bi-person-plus"></i> Aggiungi Primo Cliente
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
        @forelse($clienti as $cliente)
        <div class="client-card mobile-client-row"
        data-name="{{ strtolower($cliente->nome) }}"
        data-surname="{{ strtolower($cliente->cognome) }}"
        data-phone="{{ strtolower($cliente->telefono ?? '') }}"
        data-email="{{ strtolower($cliente->email ?? '') }}"
        data-city="{{ strtolower($cliente->citta ?? '') }}">
        
        <div class="client-card-header">
            <div class="mobile-avatar">
                {{ strtoupper(substr($cliente->nome, 0, 1)) }}{{ strtoupper(substr($cliente->cognome, 0, 1)) }}
            </div>
            <h3 class="client-card-name">{{ $cliente->nome }} {{ $cliente->cognome }}</h3>
        </div>
        
        <div class="client-card-details">
            @if($cliente->telefono)
            <div class="client-detail">
                <i class="bi bi-telephone"></i>
                <span>{{ $cliente->telefono }}</span>
            </div>
            @endif
            
            @if($cliente->email)
            <div class="client-detail">
                <i class="bi bi-envelope"></i>
                <span>{{ $cliente->email }}</span>
            </div>
            @endif
            
            @if($cliente->citta)
            <div class="client-detail">
                <i class="bi bi-geo-alt"></i>
                <span>{{ $cliente->citta }}</span>
            </div>
            @endif
        </div>
        
        <div class="client-card-actions">
            <a href="{{ route('clienti.show', $cliente) }}" class="mobile-action-btn view">
                <i class="bi bi-eye"></i>
                <span>{{ __('app.view') }}</span>
            </a>
            <a href="{{ route('clienti.edit', $cliente) }}" class="mobile-action-btn edit">
                <i class="bi bi-pencil"></i>
                <span>{{ __('app.edit') }}</span>
            </a>
            <form action="{{ route('clienti.destroy', $cliente) }}" method="POST" style="flex: 1;">
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
    <div class="client-card">
        <div class="empty-state">
            <i class="bi bi-people"></i>
            <h5>{{ __('app.no_clients_found') }}</h5>
            <p>Inizia aggiungendo il primo cliente al tuo database</p>
            <a href="{{ route('clienti.create') }}" class="modern-btn">
                <i class="bi bi-person-plus"></i> Aggiungi Primo Cliente
            </a>
        </div>
    </div>
    @endforelse
</div>

<!-- Messaggio nessun risultato -->
<div id="noResults" class="no-results" style="display: none;">
    <i class="bi bi-search"></i>
    <h5>Nessun cliente trovato</h5>
    <p>Prova a modificare i criteri di ricerca</p>
</div>
</div>
</div>

<script>
    // Sistema di ricerca avanzata per clienti
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const filterChips = document.querySelectorAll('.filter-chip');
        const clientRows = document.querySelectorAll('.client-row');
        const mobileClientRows = document.querySelectorAll('.mobile-client-row');
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
            clientRows.forEach(row => {
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
            mobileClientRows.forEach(row => {
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
                item.dataset.surname.includes(searchTerm) ||
                item.dataset.phone.includes(searchTerm) ||
                item.dataset.email.includes(searchTerm) ||
                item.dataset.city.includes(searchTerm);
            } else {
                return item.dataset[currentFilter].includes(searchTerm);
            }
        }
        
        // Animazione di entrata delle righe desktop
        clientRows.forEach((row, index) => {
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
        mobileClientRows.forEach((row, index) => {
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
</script>
@endsection