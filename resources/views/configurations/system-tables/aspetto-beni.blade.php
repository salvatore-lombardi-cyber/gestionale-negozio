@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .management-container {
        padding: 2rem;
        min-height: calc(100vh - 70px);
    }
    
    /* Header con pulsanti */
    .management-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .management-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
        display: flex;
        align-items: center;
    }
    
    
    /* Pulsanti modern-btn coerenti */
    .modern-btn {
        padding: 12px 24px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .modern-btn:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(2, 157, 126, 0.3);
    }
    
    .modern-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    /* Gradiente standard per tutti i button */
    .modern-btn.btn-primary,
    .btn-primary.modern-btn {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        border: none;
    }
    
    .modern-btn.btn-secondary,
    .btn-secondary.modern-btn {
        background: linear-gradient(135deg, #6c757d, #545b62);
        color: white;
        border: none;
    }
    
    .modern-btn.btn-success,
    .btn-success.modern-btn {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        border: none;
    }
    
    .modern-btn.btn-warning,
    .btn-warning.modern-btn {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        color: #212529;
        border: none;
    }
    
    .modern-btn.btn-danger,
    .btn-danger.modern-btn {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        border: none;
    }
    
    /* Contenitore ricerca e filtri */
    .search-filters {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .search-input {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #029D7E;
        box-shadow: 0 0 0 3px rgba(2, 157, 126, 0.1);
    }
    
    .filter-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 1rem;
        background: white;
        transition: all 0.3s ease;
    }
    
    .filter-select:focus {
        outline: none;
        border-color: #029D7E;
        box-shadow: 0 0 0 3px rgba(2, 157, 126, 0.1);
    }
    
    /* Contenitore tabella - stile gestionale senza sfondo bianco */
    .table-container {
        overflow: hidden;
    }
    
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
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
    
    .modern-table td {
        padding: 1rem;
        border-bottom: 1px solid rgba(226, 232, 240, 0.3);
        background: rgba(255, 255, 255, 0.8);
        vertical-align: middle;
    }
    
    .modern-table tbody tr {
        transition: all 0.3s ease;
        border: none;
    }
    
    .modern-table tbody tr:hover {
        background: rgba(2, 157, 126, 0.05);
        transform: scale(1.01);
    }
    
    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* Badge per stati */
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-active {
        background-color: #d4edda;
        color: #155724;
    }
    
    .status-inactive {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .tipo-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: capitalize;
    }
    
    .tipo-primario {
        background-color: #e3f2fd;
        color: #1565c0;
    }
    
    .tipo-secondario {
        background-color: #f3e5f5;
        color: #7b1fa2;
    }
    
    .tipo-terziario {
        background-color: #fff3e0;
        color: #ef6c00;
    }
    
    .utilizzo-icons {
        display: flex;
        gap: 8px;
    }
    
    .utilizzo-icon {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }
    
    .utilizzo-ddt {
        background-color: #e8f5e8;
        color: #2e7d32;
    }
    
    .utilizzo-fatture {
        background-color: #e3f2fd;
        color: #1976d2;
    }
    
    .utilizzo-disabled {
        background-color: #f5f5f5;
        color: #9e9e9e;
    }
    
    /* Action buttons - Stile del gestionale */
    .action-btn {
        border: none;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        margin: 0 2px;
        transition: all 0.3s ease;
        text-decoration: none;
        cursor: pointer;
        padding: 8px;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
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
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2) !important;
        color: white;
    }
    
    .action-btn.view:hover,
    .action-btn.edit:hover,
    .action-btn.delete:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2) !important;
    }
    
    /* Mobile Cards - Nasconde tabella, mostra card */
    .mobile-cards {
        display: none;
    }
    
    .aspetto-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .aspetto-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .aspetto-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .aspetto-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
        flex: 1;
        min-width: 0;
    }
    
    .aspetto-card-code {
        font-family: 'Courier New', monospace;
        background: rgba(243, 156, 18, 0.1);
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.8rem;
        flex-shrink: 0;
        color: #f39c12;
        font-weight: 600;
    }
    
    .aspetto-card-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .aspetto-detail {
        display: flex;
        flex-direction: column;
    }
    
    .aspetto-detail-label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 0.2rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .aspetto-detail-value {
        font-weight: 500;
        color: #2d3748;
    }
    
    .aspetto-card-actions {
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
    
    .mobile-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: white;
    }
    
    /* Responsive perfetto */
    @media (max-width: 768px) {
        .management-container {
            padding: 1rem;
        }
        
        .management-header, .search-filters {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .management-title {
            font-size: 1.8rem;
        }
        
        .modern-btn {
            padding: 10px 16px;
            font-size: 0.9rem;
        }
        
        /* Nasconde tabella su mobile */
        .table-container .table-responsive {
            display: none;
        }
        
        .mobile-cards {
            display: block;
        }
    }
    
    @media (max-width: 576px) {
        .management-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .aspetto-card {
            padding: 1rem;
        }
        
        .aspetto-card-details {
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
    
    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #718096;
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .empty-state h4 {
        margin-bottom: 1rem;
        color: #4a5568;
    }
    
    /* Statistiche Cards - Stile Dashboard */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .metric-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
    }
    
    .metric-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        border-radius: 20px 20px 0 0;
    }
    
    /* Colori specifici per ogni card */
    .metric-card:nth-child(1)::before {
        background: linear-gradient(135deg, #4ecdc4, #44a08d);
    }
    
    .metric-card:nth-child(2)::before {
        background: linear-gradient(135deg, #48cae4, #0077b6);
    }
    
    .metric-card:nth-child(3)::before {
        background: linear-gradient(135deg, #9c27b0, #7b1fa2);
    }
    
    .metric-card:nth-child(4)::before {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
    }
    
    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }
    
    .metric-value {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }
    
    /* Colori numeri abbinati ai border */
    .metric-card:nth-child(1) .metric-value {
        color: #4ecdc4;
    }
    
    .metric-card:nth-child(2) .metric-value {
        color: #48cae4;
    }
    
    .metric-card:nth-child(3) .metric-value {
        color: #9c27b0;
    }
    
    .metric-card:nth-child(4) .metric-value {
        color: #ffd60a;
    }
    
    .metric-label {
        font-size: 0.9rem;
        color: #718096;
        margin-top: 0.5rem;
    }
</style>

<div class="management-container">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Attenzione!</strong> Sono stati rilevati dei problemi:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <!-- Header con titolo e pulsanti -->
    <div class="management-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <h1 class="management-title">
                    <i class="bi bi-box-seam me-3" style="color: #f39c12; font-size: 2rem;"></i>
                    Aspetto dei Beni
                </h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('configurations.system-tables.index') }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-arrow-left"></i> Torna Indietro
                </a>
                <button type="button" class="btn btn-success modern-btn" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-lg"></i> Nuovo Aspetto
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiche Aspetto Beni -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-value">{{ ($stats['total'] ?? 0) }}</div>
            <div class="metric-label">Totale Aspetti</div>
        </div>
        <div class="metric-card">
            <div class="metric-value">{{ ($stats['active'] ?? 0) }}</div>
            <div class="metric-label">Aspetti Attivi</div>
        </div>
        <div class="metric-card">
            <div class="metric-value">{{ ($stats['primario'] ?? 0) }}</div>
            <div class="metric-label">Packaging Primario</div>
        </div>
        <div class="metric-card">
            <div class="metric-value">{{ ($stats['ddt_enabled'] ?? 0) }}</div>
            <div class="metric-label">Utilizzabili DDT</div>
        </div>
    </div>

    <!-- Filtri e ricerca -->
    <div class="search-filters">
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-control search-input" id="searchInput" placeholder="Cerca per codice o descrizione..." onkeyup="filterTable()">
            </div>
            <div class="col-md-3">
                <select class="form-select filter-select" id="tipoFilter" onchange="filterTable()">
                    <option value="">Tutti i tipi</option>
                    <option value="primario">Primario</option>
                    <option value="secondario">Secondario</option>
                    <option value="terziario">Terziario</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select filter-select" id="statusFilter" onchange="filterTable()">
                    <option value="">Tutti gli stati</option>
                    <option value="1">Attivo</option>
                    <option value="0">Inattivo</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-secondary modern-btn w-100" onclick="resetFilters()">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Tabella dati -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="modern-table" id="aspettoBeniTable">
                <thead>
                    <tr>
                        <th style="width: 15%">Codice</th>
                        <th style="width: 25%">Descrizione</th>
                        <th style="width: 15%">Tipo</th>
                        <th style="width: 15%">Utilizzo</th>
                        <th style="width: 10%">Stato</th>
                        <th style="width: 20%">Azioni</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($items ?? [] as $item)
                        <tr data-tipo="{{ $item->tipo_confezionamento }}" data-status="{{ $item->attivo ? '1' : '0' }}">
                            <td>
                                <strong>{{ $item->codice_aspetto }}</strong>
                            </td>
                            <td>
                                <div>{{ $item->descrizione }}</div>
                                @if($item->descrizione_estesa)
                                    <small class="text-muted">{{ $item->descrizione_estesa }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="tipo-badge tipo-{{ $item->tipo_confezionamento }}">
                                    {{ ucfirst($item->tipo_confezionamento) }}
                                </span>
                            </td>
                            <td>
                                <div class="utilizzo-icons">
                                    <div class="utilizzo-icon {{ $item->utilizzabile_ddt ? 'utilizzo-ddt' : 'utilizzo-disabled' }}" 
                                         title="{{ $item->utilizzabile_ddt ? 'Utilizzabile in DDT' : 'Non utilizzabile in DDT' }}">
                                        <i class="bi bi-truck"></i>
                                    </div>
                                    <div class="utilizzo-icon {{ $item->utilizzabile_fatture ? 'utilizzo-fatture' : 'utilizzo-disabled' }}" 
                                         title="{{ $item->utilizzabile_fatture ? 'Utilizzabile in Fatture' : 'Non utilizzabile in Fatture' }}">
                                        <i class="bi bi-receipt"></i>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge {{ $item->attivo ? 'status-active' : 'status-inactive' }}">
                                    {{ $item->attivo ? 'Attivo' : 'Inattivo' }}
                                </span>
                            </td>
                            <td>
                                <button type="button" class="action-btn view" onclick="viewItem({{ $item->id }})" title="Visualizza">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button type="button" class="action-btn edit" onclick="editItem({{ $item->id }})" title="Modifica">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="action-btn delete" onclick="deleteItem({{ $item->id }})" title="Elimina">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr id="noResults">
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="bi bi-box-seam"></i>
                                    <h4>Nessun aspetto dei beni configurato</h4>
                                    <p>Inizia creando il primo aspetto dei beni per il tuo sistema.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($items) && $items->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    <!-- Cards Mobile -->
    <div class="mobile-cards" id="mobileCards">
        @forelse($items ?? [] as $item)
            <div class="aspetto-card mobile-aspetto-row"
                 data-tipo="{{ $item->tipo_confezionamento }}" 
                 data-status="{{ $item->attivo ? '1' : '0' }}">
                
                <div class="aspetto-card-header">
                    <h3 class="aspetto-card-title">{{ $item->descrizione }}</h3>
                    <div class="d-flex flex-column align-items-end">
                        <span class="aspetto-card-code">{{ $item->codice_aspetto }}</span>
                        <div class="mt-1">
                            <span class="status-badge {{ $item->attivo ? 'status-active' : 'status-inactive' }}" style="font-size: 0.7rem;">
                                {{ $item->attivo ? 'Attivo' : 'Inattivo' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="aspetto-card-details">
                    <div class="aspetto-detail">
                        <span class="aspetto-detail-label">Tipo Confezionamento</span>
                        <span class="aspetto-detail-value">
                            <span class="tipo-badge tipo-{{ $item->tipo_confezionamento }}">
                                {{ ucfirst($item->tipo_confezionamento) }}
                            </span>
                        </span>
                    </div>
                    <div class="aspetto-detail">
                        <span class="aspetto-detail-label">Utilizzo</span>
                        <span class="aspetto-detail-value">
                            <div class="utilizzo-icons">
                                <div class="utilizzo-icon {{ $item->utilizzabile_ddt ? 'utilizzo-ddt' : 'utilizzo-disabled' }}" 
                                     title="{{ $item->utilizzabile_ddt ? 'Utilizzabile in DDT' : 'Non utilizzabile in DDT' }}">
                                    <i class="bi bi-truck"></i>
                                </div>
                                <div class="utilizzo-icon {{ $item->utilizzabile_fatture ? 'utilizzo-fatture' : 'utilizzo-disabled' }}" 
                                     title="{{ $item->utilizzabile_fatture ? 'Utilizzabile in Fatture' : 'Non utilizzabile in Fatture' }}">
                                    <i class="bi bi-receipt"></i>
                                </div>
                            </div>
                        </span>
                    </div>
                </div>
                
                @if($item->descrizione_estesa)
                    <div class="aspetto-detail mb-3">
                        <span class="aspetto-detail-label">Descrizione Estesa</span>
                        <span class="aspetto-detail-value">{{ $item->descrizione_estesa }}</span>
                    </div>
                @endif
                
                <div class="aspetto-card-actions">
                    <button type="button" class="mobile-action-btn view" onclick="viewItem({{ $item->id }})">
                        <i class="bi bi-eye"></i>
                        <span>Visualizza</span>
                    </button>
                    <button type="button" class="mobile-action-btn edit" onclick="editItem({{ $item->id }})">
                        <i class="bi bi-pencil"></i>
                        <span>Modifica</span>
                    </button>
                    <button type="button" class="mobile-action-btn delete" onclick="deleteItem({{ $item->id }})">
                        <i class="bi bi-trash"></i>
                        <span>Elimina</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="bi bi-box-seam"></i>
                <h4>Nessun aspetto dei beni configurato</h4>
                <p>Inizia creando il primo aspetto dei beni per il tuo sistema.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Creazione -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #029D7E, #4DC9A5); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title" id="createModalLabel">
                    <i class="bi bi-plus-lg me-2"></i>Nuovo Aspetto dei Beni
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createForm" onsubmit="submitForm(event, 'create')">
                @csrf
                <div class="modal-body" style="padding: 2rem;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="codice_aspetto" class="form-label fw-bold">Codice Aspetto *</label>
                            <div class="position-relative">
                                <input type="text" class="form-control search-input" id="codice_aspetto" name="codice_aspetto" required maxlength="10" style="text-transform: uppercase;">
                                <div id="codiceStatus" class="position-absolute" style="top: 50%; right: 10px; transform: translateY(-50%); display: none;">
                                    <span id="codiceLoading" class="text-warning">⏳</span>
                                    <span id="codiceOk" class="text-success" style="display: none;">✅</span>
                                    <span id="codiceDuplicate" class="text-danger" style="display: none;">❌</span>
                                </div>
                            </div>
                            <div class="form-text">Massimo 10 caratteri, solo lettere maiuscole, numeri, _ e -</div>
                            <div id="codiceMessage" class="form-text text-danger" style="display: none;"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="tipo_confezionamento" class="form-label fw-bold">
                                Tipo Confezionamento *
                                <i class="bi bi-info-circle ms-1" 
                                   data-bs-toggle="tooltip" 
                                   data-bs-placement="top" 
                                   title="Seleziona il livello di confezionamento del prodotto"></i>
                            </label>
                            <select class="form-select filter-select" id="tipo_confezionamento" name="tipo_confezionamento" required>
                                <option value="">Seleziona tipo</option>
                                <option value="primario" data-bs-toggle="tooltip" title="Es: singola bottiglia, blister, scatola individuale">
                                    🎯 Primario - Prodotto singolo al cliente
                                </option>
                                <option value="secondario" data-bs-toggle="tooltip" title="Es: confezione da 6 pezzi, multipack, scatole collettive">
                                    📦 Secondario - Raggruppamento per vendita
                                </option>
                                <option value="terziario" data-bs-toggle="tooltip" title="Es: pallet, container, bancali per trasporto">
                                    🚛 Terziario - Grandi quantità trasporto
                                </option>
                            </select>
                            <div class="form-text mt-2">
                                <small class="text-muted">
                                    <strong>💡 Guida rapida:</strong><br>
                                    • <strong>Primario</strong>: Come arriva il singolo prodotto al cliente<br>
                                    • <strong>Secondario</strong>: Come ragruppi più prodotti<br>
                                    • <strong>Terziario</strong>: Come trasporti grandi quantità
                                </small>
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="descrizione" class="form-label fw-bold">Descrizione *</label>
                            <input type="text" class="form-control search-input" id="descrizione" name="descrizione" required maxlength="50">
                            <div class="form-text">Massimo 50 caratteri</div>
                        </div>
                        <div class="col-12">
                            <label for="descrizione_estesa" class="form-label fw-bold">Descrizione Estesa</label>
                            <textarea class="form-control search-input" id="descrizione_estesa" name="descrizione_estesa" rows="3" maxlength="255"></textarea>
                            <div class="form-text">Massimo 255 caratteri (opzionale)</div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="utilizzabile_ddt" name="utilizzabile_ddt" checked>
                                <label class="form-check-label fw-bold" for="utilizzabile_ddt">
                                    Utilizzabile in DDT
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="utilizzabile_fatture" name="utilizzabile_fatture" checked>
                                <label class="form-check-label fw-bold" for="utilizzabile_fatture">
                                    Utilizzabile in Fatture
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="attivo" name="attivo" checked>
                                <label class="form-check-label fw-bold" for="attivo">
                                    Attivo
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 1.5rem 2rem; border-top: 1px solid #e9ecef;">
                    <button type="button" class="btn btn-secondary modern-btn" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Annulla
                    </button>
                    <button type="submit" class="btn btn-success modern-btn">
                        <i class="bi bi-check-lg"></i> Salva Aspetto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Variabili globali
let allItems = [];

// Filtri e ricerca
function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const tipoFilter = document.getElementById('tipoFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    
    // Filtro per tabella desktop
    const rows = document.querySelectorAll('#tableBody tr:not(#noResults)');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const codice = row.children[0].textContent.toLowerCase();
        const descrizione = row.children[1].textContent.toLowerCase();
        const tipo = row.dataset.tipo;
        const status = row.dataset.status;
        
        const matchesSearch = codice.includes(searchTerm) || descrizione.includes(searchTerm);
        const matchesTipo = !tipoFilter || tipo === tipoFilter;
        const matchesStatus = !statusFilter || status === statusFilter;
        
        if (matchesSearch && matchesTipo && matchesStatus) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Filtro per mobile cards
    const mobileCards = document.querySelectorAll('.mobile-aspetto-row');
    let mobileVisibleCount = 0;
    
    mobileCards.forEach(card => {
        const codice = card.querySelector('.aspetto-card-code').textContent.toLowerCase();
        const descrizione = card.querySelector('.aspetto-card-title').textContent.toLowerCase();
        const tipo = card.dataset.tipo;
        const status = card.dataset.status;
        
        const matchesSearch = codice.includes(searchTerm) || descrizione.includes(searchTerm);
        const matchesTipo = !tipoFilter || tipo === tipoFilter;
        const matchesStatus = !statusFilter || status === statusFilter;
        
        if (matchesSearch && matchesTipo && matchesStatus) {
            card.style.display = '';
            mobileVisibleCount++;
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        } else {
            card.style.opacity = '0';
            card.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                card.style.display = 'none';
            }, 300);
        }
    });
    
    // Mostra/nascondi messaggio vuoto
    const noResults = document.getElementById('noResults');
    if (noResults) {
        noResults.style.display = (visibleCount === 0 && mobileVisibleCount === 0) ? '' : 'none';
    }
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('tipoFilter').value = '';
    document.getElementById('statusFilter').value = '';
    filterTable();
}

// Controllo duplicati codice
async function checkCodiceDuplicato(codice) {
    if (!codice || codice.trim() === '') return false;
    
    try {
        const response = await fetch(`{{ route("configurations.system-tables.index", "aspetto_beni") }}?check_duplicate=${encodeURIComponent(codice)}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            return data.exists || false;
        }
    } catch (error) {
        console.warn('Errore controllo duplicati:', error);
    }
    return false;
}

// CRUD Operations
async function submitForm(event, action) {
    event.preventDefault();
    
    const form = event.target;
    
    // Validazione codice duplicato per nuovi inserimenti
    if (action === 'create') {
        const codiceAspetto = form.codice_aspetto.value.trim().toUpperCase();
        if (await checkCodiceDuplicato(codiceAspetto)) {
            alert('⚠️ Errore: Il codice "' + codiceAspetto + '" esiste già!\nScegli un codice diverso.');
            form.codice_aspetto.focus();
            return;
        }
    }
    
    const formData = new FormData(form);
    
    // Converti checkbox in valori booleani
    formData.set('utilizzabile_ddt', form.utilizzabile_ddt.checked ? '1' : '0');
    formData.set('utilizzabile_fatture', form.utilizzabile_fatture.checked ? '1' : '0');
    formData.set('attivo', form.attivo.checked ? '1' : '0');
    
    const url = action === 'create' 
        ? '{{ route("configurations.system-tables.store", "aspetto_beni") }}'
        : `{{ route("configurations.system-tables.update", ["table" => "aspetto_beni", "id" => ":id"]) }}`.replace(':id', form.dataset.itemId);
    
    const method = action === 'create' ? 'POST' : 'PUT';
    
    if (method === 'PUT') {
        formData.append('_method', 'PUT');
    }
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            // Gestione errori più specifica
            let errorMessage = data.message || 'Operazione fallita';
            
            // Errori di duplicato
            if (errorMessage.includes('Duplicate entry')) {
                const codice = errorMessage.match(/'([^']+)'/)?.[1] || 'sconosciuto';
                errorMessage = `⚠️ Codice "${codice}" già esistente!\nScegli un codice diverso.`;
            }
            
            // Errori di validazione
            if (data.errors) {
                errorMessage = 'Errori di validazione:\n';
                Object.values(data.errors).forEach(errors => {
                    errors.forEach(error => errorMessage += `• ${error}\n`);
                });
            }
            
            alert('🚨 ' + errorMessage);
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        alert('🔌 Errore di comunicazione con il server');
    });
}

function viewItem(id) {
    // Carica dati per visualizzazione
    fetch(`{{ route("configurations.system-tables.show", "aspetto_beni") }}/${id}`)
        .then(response => response.json())
        .then(item => {
            // Mostra dettagli in modale
            const modalContent = `
                <div class="modal fade" id="viewModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content" style="border-radius: 20px; border: none;">
                            <div class="modal-header" style="background: linear-gradient(135deg, #029D7E, #4DC9A5); color: white; border-radius: 20px 20px 0 0;">
                                <h5 class="modal-title">
                                    <i class="bi bi-eye me-2"></i>Dettagli Aspetto dei Beni
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body" style="padding: 2rem;">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Codice Aspetto</label>
                                        <div class="p-2 bg-light rounded">${item.codice_aspetto}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Tipo Confezionamento</label>
                                        <div class="p-2">
                                            <span class="tipo-badge tipo-${item.tipo_confezionamento}">
                                                ${item.tipo_confezionamento.charAt(0).toUpperCase() + item.tipo_confezionamento.slice(1)}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold text-muted">Descrizione</label>
                                        <div class="p-2 bg-light rounded">${item.descrizione}</div>
                                    </div>
                                    ${item.descrizione_estesa ? `
                                    <div class="col-12">
                                        <label class="form-label fw-bold text-muted">Descrizione Estesa</label>
                                        <div class="p-2 bg-light rounded">${item.descrizione_estesa}</div>
                                    </div>
                                    ` : ''}
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-muted">Stato</label>
                                        <div class="p-2">
                                            <span class="status-badge ${item.attivo ? 'status-active' : 'status-inactive'}">
                                                ${item.attivo ? 'Attivo' : 'Inattivo'}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-muted">Utilizzabile DDT</label>
                                        <div class="p-2">
                                            <div class="utilizzo-icon ${item.utilizzabile_ddt ? 'utilizzo-ddt' : 'utilizzo-disabled'}">
                                                <i class="bi bi-truck"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-muted">Utilizzabile Fatture</label>
                                        <div class="p-2">
                                            <div class="utilizzo-icon ${item.utilizzabile_fatture ? 'utilizzo-fatture' : 'utilizzo-disabled'}">
                                                <i class="bi bi-receipt"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Data Creazione</label>
                                        <div class="p-2 bg-light rounded">${new Date(item.created_at).toLocaleDateString('it-IT')}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Ultimo Aggiornamento</label>
                                        <div class="p-2 bg-light rounded">${new Date(item.updated_at).toLocaleDateString('it-IT')}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" style="padding: 1.5rem 2rem;">
                                <button type="button" class="btn btn-secondary modern-btn" data-bs-dismiss="modal">
                                    <i class="bi bi-x-lg"></i> Chiudi
                                </button>
                                <button type="button" class="btn btn-primary modern-btn" onclick="editItem(${item.id}); bootstrap.Modal.getInstance(document.getElementById('viewModal')).hide();">
                                    <i class="bi bi-pencil"></i> Modifica
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Rimuovi modale esistente se presente
            const existingModal = document.getElementById('viewModal');
            if (existingModal) {
                existingModal.remove();
            }
            
            // Aggiungi nuova modale al DOM
            document.body.insertAdjacentHTML('beforeend', modalContent);
            
            // Mostra modale
            const modal = new bootstrap.Modal(document.getElementById('viewModal'));
            modal.show();
            
            // Rimuovi dal DOM quando si chiude
            document.getElementById('viewModal').addEventListener('hidden.bs.modal', function () {
                this.remove();
            });
        })
        .catch(error => {
            console.error('Errore:', error);
            alert('Errore nel caricamento dei dettagli');
        });
}

function editItem(id) {
    // Carica dati per modifica
    fetch(`{{ route("configurations.system-tables.show", "aspetto_beni") }}/${id}`)
        .then(response => response.json())
        .then(item => {
            // Popola form di modifica
            const form = document.getElementById('createForm');
            form.dataset.itemId = id;
            form.codice_aspetto.value = item.codice_aspetto;
            form.descrizione.value = item.descrizione;
            form.descrizione_estesa.value = item.descrizione_estesa || '';
            form.tipo_confezionamento.value = item.tipo_confezionamento;
            form.utilizzabile_ddt.checked = item.utilizzabile_ddt;
            form.utilizzabile_fatture.checked = item.utilizzabile_fatture;
            form.attivo.checked = item.attivo;
            
            document.getElementById('createModalLabel').innerHTML = '<i class="bi bi-pencil me-2"></i>Modifica Aspetto dei Beni';
            
            const modal = new bootstrap.Modal(document.getElementById('createModal'));
            modal.show();
        });
}

function deleteItem(id) {
    if (confirm('Sei sicuro di voler eliminare questo aspetto dei beni?')) {
        fetch(`{{ route("configurations.system-tables.destroy", ["table" => "aspetto_beni", "id" => ":id"]) }}`.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Errore nell\'eliminazione');
            }
        });
    }
}


// Reset form quando si chiude il modal
document.getElementById('createModal').addEventListener('hidden.bs.modal', function () {
    const form = document.getElementById('createForm');
    form.reset();
    form.dataset.itemId = '';
    document.getElementById('createModalLabel').innerHTML = '<i class="bi bi-plus-lg me-2"></i>Nuovo Aspetto dei Beni';
});

// Validazione input codice aspetto
document.getElementById('codice_aspetto').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9_-]/g, '');
});

// Inizializza tooltip Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts dopo 5 secondi
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert, index) {
        setTimeout(function() {
            if (bootstrap.Alert.getOrCreateInstance) {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }
        }, 5000 + (index * 1000));
    });
    
    // Inizializza tutti i tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            html: true,
            delay: { show: 300, hide: 100 }
        });
    });
});
</script>
@endsection