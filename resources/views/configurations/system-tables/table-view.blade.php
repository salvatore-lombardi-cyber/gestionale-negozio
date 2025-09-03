@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .dashboard-container {
        padding: 2rem;
        min-height: calc(100vh - 70px);
    }
    
    /* Header stile gestionale */
    .dashboard-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .dashboard-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .back-button {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        border: none;
    }
    
    .back-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(2, 157, 126, 0.3);
        color: white;
        text-decoration: none;
    }
    
    /* Tabella dati */
    .data-table {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
    }
    
    .table {
        margin-bottom: 0;
    }
    
    .table thead th {
        background-color: #f8f9fa;
        border: none;
        font-weight: 600;
        color: #2d3748;
        padding: 1rem;
    }
    
    .table tbody td {
        border: none;
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .table tbody tr:hover {
        background-color: #f7fafc;
    }
    
    /* Search bar */
    .search-container {
        margin-bottom: 2rem;
    }
    
    .search-input {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        width: 100%;
        max-width: 400px;
        transition: all 0.3s ease;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #029D7E;
        box-shadow: 0 0 0 3px rgba(2, 157, 126, 0.1);
    }
    
    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #718096;
    }
    
    .empty-state i {
        font-size: 3rem;
        color: #cbd5e0;
        margin-bottom: 1rem;
    }
    
    /* Badge status */
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .status-active {
        background-color: #c6f6d5;
        color: #22543d;
    }
    
    .status-inactive {
        background-color: #fed7d7;
        color: #742a2a;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .dashboard-title {
            font-size: 1.5rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .data-table {
            padding: 1rem;
        }
        
        .table-responsive {
            border-radius: 12px;
        }
    }
</style>

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="dashboard-title">
                <a href="{{ route('configurations.system-tables.index') }}" class="back-button">
                    <i class="bi bi-arrow-left"></i> Indietro
                </a>
                {{ $config['name'] ?? ucfirst(str_replace('_', ' ', $table)) }}
            </h1>
        </div>
        
        <p class="mt-3 mb-0 text-muted">
            Gestione dati per la tabella: <strong>{{ $table }}</strong>
        </p>
    </div>

    <!-- Search -->
    <div class="search-container">
        <form method="GET" action="{{ route('configurations.system-tables.show', $table) }}">
            <div class="input-group">
                <input type="text" name="search" class="search-input" 
                       placeholder="Cerca per codice, nome o descrizione..." 
                       value="{{ $search }}">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="data-table">
        @if($items && $items->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Codice</th>
                            <th>Nome</th>
                            <th>Descrizione</th>
                            <th>Stato</th>
                            <th>Data Creazione</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->code ?? '-' }}</strong>
                            </td>
                            <td>{{ $item->name ?? '-' }}</td>
                            <td>
                                <span class="text-muted">
                                    {{ Str::limit($item->description ?? '-', 50) }}
                                </span>
                            </td>
                            <td>
                                @if($item->active ?? true)
                                    <span class="status-badge status-active">
                                        <i class="bi bi-check-circle"></i> Attivo
                                    </span>
                                @else
                                    <span class="status-badge status-inactive">
                                        <i class="bi bi-x-circle"></i> Inattivo
                                    </span>
                                @endif
                            </td>
                            <td class="text-muted">
                                {{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if(method_exists($items, 'links'))
                <div class="d-flex justify-content-center mt-4">
                    {{ $items->links() }}
                </div>
            @endif
            
        @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h4>Nessun dato trovato</h4>
                <p class="text-muted">
                    @if($search)
                        Non ci sono risultati per la ricerca "{{ $search }}"
                    @else
                        Questa tabella non contiene ancora dati
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>

<!-- Script semplificato -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Focus automatico su search input
    const searchInput = document.querySelector('.search-input');
    if (searchInput && !searchInput.value) {
        searchInput.focus();
    }
});
</script>
@endsection