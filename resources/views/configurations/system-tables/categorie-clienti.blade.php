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
        color: #029D7E;
        font-weight: 700;
        margin: 0;
        font-size: 2.5rem;
        display: flex;
        align-items: center;
    }
    
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
    
    .modern-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    .btn-success {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #6c757d, #545b62);
        color: white;
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
    }
    
    .search-filters {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .search-input, .filter-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
    }
    
    .search-input:focus, .filter-select:focus {
        outline: none;
        border-color: #029D7E;
        box-shadow: 0 0 0 3px rgba(2, 157, 126, 0.1);
    }
    
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
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: white;
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
    
    .actions-container {
        display: flex;
        gap: 4px;
        flex-wrap: nowrap;
        justify-content: center;
        min-width: 180px;
    }
    
    .type-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .type-b2b {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    
    .type-b2c {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
    }
    
    .type-vip {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
    }
    
    .type-wholesale {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
    }
    
    .type-retail {
        background: linear-gradient(135deg, #6c757d, #545b62);
        color: white;
    }
    
    .type-standard {
        background: linear-gradient(135deg, #e9ecef, #ced4da);
        color: #495057;
    }
    
    .priority-badge {
        padding: 2px 6px;
        border-radius: 8px;
        font-size: 0.65rem;
        font-weight: 600;
    }
    
    .priority-premium {
        background: #d4edda;
        color: #155724;
    }
    
    .priority-high {
        background: #fff3cd;
        color: #856404;
    }
    
    .priority-medium {
        background: #cce7ff;
        color: #004085;
    }
    
    .priority-low {
        background: #f8d7da;
        color: #721c24;
    }
    
    .stats-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 12px;
        padding: 1rem;
        flex: 1;
        min-width: 120px;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #029D7E;
    }
    
    .stat-label {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
    
    /* Mobile Cards */
    .mobile-cards {
        display: none;
    }
    
    .mobile-item-row {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        opacity: 1;
        transform: translateY(0);
    }
    
    .mobile-item-row:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .item-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .item-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0;
        color: #2d3748;
    }
    
    .item-card-code {
        font-size: 0.9rem;
        font-weight: 600;
        color: #029D7E;
        background: rgba(2, 157, 126, 0.1);
        padding: 4px 8px;
        border-radius: 8px;
    }
    
    .item-card-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .item-detail {
        display: flex;
        flex-direction: column;
    }
    
    .item-detail-label {
        font-size: 0.75rem;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    
    .item-detail-value {
        font-size: 0.9rem;
        font-weight: 600;
        color: #2d3748;
        margin-top: 0.25rem;
    }
    
    .mobile-card-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        border-top: 1px solid rgba(226, 232, 240, 0.3);
        padding-top: 1rem;
        margin-top: 1rem;
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
    
    .mobile-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: white;
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
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #029D7E;
        opacity: 0.5;
    }
    
    .empty-state h4 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .no-results {
        text-align: center;
        padding: 2rem;
        color: #6c757d;
        font-style: italic;
    }
    
    @media (max-width: 768px) {
        .table-container .table-responsive {
            display: none;
        }
        
        .mobile-cards {
            display: block;
        }
        
        .management-container {
            padding: 1rem;
        }
        
        .management-header, .search-filters {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .management-title {
            font-size: 2rem;
        }
        
        .stats-row {
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        }
        
        .item-card-details {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .management-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .mobile-action-btn {
            padding: 10px 6px;
            font-size: 0.7rem;
            min-width: 70px;
        }
        
        .stats-row {
            flex-direction: column;
        }
        
        .stat-card {
            min-width: auto;
        }
    }
</style>

<div class="management-container">
    <!-- Header della pagina -->
    <div class="management-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <h1 class="management-title">
                    <i class="bi bi-people-fill me-3" style="color: #029D7E; font-size: 2rem;"></i>
                    Categorie Clienti
                </h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('configurations.system-tables.index') }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-arrow-left"></i> Torna Indietro
                </a>
                <button type="button" class="btn btn-success modern-btn" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-lg"></i> Nuova Categoria
                </button>
                <button type="button" class="btn btn-warning modern-btn" onclick="exportData()">
                    <i class="bi bi-download"></i> Esporta
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiche rapide -->
    @if(isset($stats))
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['total'] }}</div>
            <div class="stat-label">Totale</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['active'] }}</div>
            <div class="stat-label">Attive</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['vip'] }}</div>
            <div class="stat-label">VIP</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['b2b'] }}</div>
            <div class="stat-label">B2B</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['with_discount'] }}</div>
            <div class="stat-label">Con Sconto</div>
        </div>
    </div>
    @endif

    <!-- Filtri e ricerca -->
    <div class="search-filters">
        <div class="row g-3">
            <div class="col-md-3">
                <input type="text" class="form-control search-input" id="searchInput" placeholder="Cerca categoria..." onkeyup="filterTable()">
            </div>
            <div class="col-md-2">
                <select class="form-select filter-select" id="typeFilter" onchange="filterTable()">
                    <option value="">Tutti i tipi</option>
                    <option value="VIP">VIP</option>
                    <option value="B2B">B2B</option>
                    <option value="B2C">B2C</option>
                    <option value="WHOLESALE">All'ingrosso</option>
                    <option value="RETAIL">Al dettaglio</option>
                    <option value="STANDARD">Standard</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select filter-select" id="priorityFilter" onchange="filterTable()">
                    <option value="">Tutte le priorità</option>
                    <option value="PREMIUM">Premium</option>
                    <option value="HIGH">Alta</option>
                    <option value="MEDIUM">Media</option>
                    <option value="LOW">Bassa</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select filter-select" id="statusFilter" onchange="filterTable()">
                    <option value="">Tutti</option>
                    <option value="1">Attive</option>
                    <option value="0">Inattive</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select filter-select" id="discountFilter" onchange="filterTable()">
                    <option value="">Tutti</option>
                    <option value="with">Con sconto</option>
                    <option value="without">Senza sconto</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-secondary modern-btn w-100" onclick="resetFilters()">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Tabella Desktop -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Codice</th>
                        <th>Nome</th>
                        <th>Tipo</th>
                        <th>Priorità</th>
                        <th>Sconto %</th>
                        <th>Limite Credito</th>
                        <th>Termini Pag.</th>
                        <th>Stato</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($items as $item)
                        <tr class="table-row" 
                            data-code="{{ strtolower($item->code) }}" 
                            data-name="{{ strtolower($item->name) }}"
                            data-type="{{ $item->type }}"
                            data-priority="{{ $item->priority_level ?? 'MEDIUM' }}"
                            data-status="{{ $item->active ? '1' : '0' }}"
                            data-discount="{{ $item->discount_percentage > 0 ? 'with' : 'without' }}">
                            <td>
                                <span class="item-card-code">{{ $item->code }}</span>
                            </td>
                            <td>
                                <strong>{{ $item->name }}</strong>
                                @if($item->description)
                                    <br><small class="text-muted">{{ Str::limit($item->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="type-badge type-{{ strtolower($item->type) }}">
                                    {{ $item->type_translated }}
                                </span>
                            </td>
                            <td>
                                <span class="priority-badge priority-{{ strtolower($item->priority_level ?? 'medium') }}">
                                    {{ ucfirst(strtolower($item->priority_level ?? 'Medium')) }}
                                </span>
                            </td>
                            <td>
                                <strong class="{{ $item->discount_percentage > 0 ? 'text-success' : 'text-muted' }}">
                                    {{ $item->formatted_discount }}
                                </strong>
                            </td>
                            <td>{{ $item->formatted_credit_limit }}</td>
                            <td>{{ $item->payment_terms_days ?? 30 }} giorni</td>
                            <td>
                                @if($item->active)
                                    <span class="badge bg-success">Attiva</span>
                                @else
                                    <span class="badge bg-danger">Inattiva</span>
                                @endif
                            </td>
                            <td>
                                <div class="actions-container">
                                    <button type="button" class="action-btn view" title="Visualizza" onclick="viewItem({{ $item->id }})">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button type="button" class="action-btn edit" title="Modifica" onclick="editItem({{ $item->id }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="action-btn delete" title="Elimina" onclick="deleteItem({{ $item->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noResults">
                            <td colspan="9" class="no-results">
                                <i class="bi bi-people me-2"></i>
                                Nessuna categoria cliente trovata. <a href="#" data-bs-toggle="modal" data-bs-target="#createModal">Crea la prima categoria</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Cards Mobile -->
    <div class="mobile-cards" id="mobileCards">
        @forelse($items as $item)
            <div class="mobile-item-row" 
                data-code="{{ strtolower($item->code) }}" 
                data-name="{{ strtolower($item->name) }}"
                data-type="{{ $item->type }}"
                data-priority="{{ $item->priority_level ?? 'MEDIUM' }}"
                data-status="{{ $item->active ? '1' : '0' }}"
                data-discount="{{ $item->discount_percentage > 0 ? 'with' : 'without' }}">
                
                <div class="item-card-header">
                    <h3 class="item-card-title">{{ $item->name }}</h3>
                    <div class="d-flex flex-column align-items-end gap-2">
                        <span class="item-card-code">{{ $item->code }}</span>
                        <span class="type-badge type-{{ strtolower($item->type) }}">
                            {{ $item->type_translated }}
                        </span>
                    </div>
                </div>
                
                <div class="item-card-details">
                    <div class="item-detail">
                        <span class="item-detail-label">Priorità</span>
                        <span class="item-detail-value">
                            <span class="priority-badge priority-{{ strtolower($item->priority_level ?? 'medium') }}">
                                {{ ucfirst(strtolower($item->priority_level ?? 'Medium')) }}
                            </span>
                        </span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-label">Sconto</span>
                        <span class="item-detail-value {{ $item->discount_percentage > 0 ? 'text-success' : 'text-muted' }}">
                            {{ $item->formatted_discount }}
                        </span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-label">Limite Credito</span>
                        <span class="item-detail-value">{{ $item->formatted_credit_limit }}</span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-label">Stato</span>
                        <span class="item-detail-value">
                            @if($item->active)
                                <span class="badge bg-success">Attiva</span>
                            @else
                                <span class="badge bg-danger">Inattiva</span>
                            @endif
                        </span>
                    </div>
                </div>
                
                <div class="mobile-card-actions">
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
                <i class="bi bi-people"></i>
                <h4>Nessuna categoria cliente</h4>
                <p>Inizia creando la prima categoria per segmentare i tuoi clienti</p>
                <button type="button" class="btn btn-success modern-btn" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-lg"></i> Crea Prima Categoria
                </button>
            </div>
        @endforelse
    </div>

    <!-- Paginazione -->
    @if($items->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $items->links() }}
        </div>
    @endif
</div>

<!-- Modal Creazione/Modifica -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #029D7E, #4DC9A5); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title">
                    <i class="bi bi-people-fill me-2"></i>
                    <span id="modalTitle">Nuova Categoria Cliente</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="categoryForm" method="POST">
                @csrf
                <input type="hidden" id="methodField" name="_method" value="">
                <input type="hidden" id="categoryId" name="id" value="">
                
                <div class="modal-body" style="padding: 2rem;">
                    <div class="row g-4">
                        <!-- Informazioni Base -->
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-info-circle me-2"></i>Informazioni Base
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="code" class="form-label">Codice <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="code" name="code" required 
                                   placeholder="es: VIP001" style="text-transform: uppercase;">
                            <div class="form-text">Codice univoco (solo maiuscole, numeri, _ e -)</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nome Categoria <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required 
                                   placeholder="es: Clienti VIP Premium">
                        </div>
                        
                        <div class="col-12">
                            <label for="description" class="form-label">Descrizione</label>
                            <textarea class="form-control" id="description" name="description" rows="3" 
                                      placeholder="Descrizione dettagliata della categoria..."></textarea>
                        </div>
                        
                        <!-- Classificazione Business -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-diagram-3 me-2"></i>Classificazione Business
                            </h6>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="type" class="form-label">Tipo Cliente <span class="text-danger">*</span></label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">Seleziona tipo...</option>
                                <option value="VIP">VIP - Cliente Premium</option>
                                <option value="B2B">B2B - Business to Business</option>
                                <option value="B2C">B2C - Business to Consumer</option>
                                <option value="WHOLESALE">Wholesale - All'ingrosso</option>
                                <option value="RETAIL">Retail - Al dettaglio</option>
                                <option value="STANDARD">Standard - Cliente standard</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="priority_level" class="form-label">Livello Priorità</label>
                            <select class="form-select" id="priority_level" name="priority_level">
                                <option value="MEDIUM">Medium - Priorità media</option>
                                <option value="PREMIUM">Premium - Massima priorità</option>
                                <option value="HIGH">High - Alta priorità</option>
                                <option value="LOW">Low - Bassa priorità</option>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="price_list" class="form-label">Lista Prezzi</label>
                            <select class="form-select" id="price_list" name="price_list">
                                <option value="LIST_1">Lista 1 - Standard</option>
                                <option value="LIST_2">Lista 2 - Scontata</option>
                                <option value="LIST_3">Lista 3 - Premium</option>
                                <option value="WHOLESALE">Wholesale - All'ingrosso</option>
                                <option value="RETAIL">Retail - Al dettaglio</option>
                            </select>
                        </div>
                        
                        <!-- Configurazione Sconti e Termini -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-percent me-2"></i>Sconti e Termini di Pagamento
                            </h6>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="discount_percentage" class="form-label">Sconto % <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" 
                                       min="0" max="100" step="0.01" value="0" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="payment_terms_days" class="form-label">Termini Pagamento</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="payment_terms_days" name="payment_terms_days" 
                                       min="0" max="365" value="30">
                                <span class="input-group-text">giorni</span>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="credit_limit" class="form-label">Limite Credito €</label>
                            <div class="input-group">
                                <span class="input-group-text">€</span>
                                <input type="number" class="form-control" id="credit_limit" name="credit_limit" 
                                       min="0" step="0.01" placeholder="Illimitato se vuoto">
                            </div>
                        </div>
                        
                        <!-- Personalizzazione e Sicurezza -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-palette me-2"></i>Personalizzazione UI/UX
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="color_hex" class="form-label">Colore Categoria</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" id="color_hex" name="color_hex" value="#029D7E">
                                <input type="text" class="form-control" id="color_hex_text" placeholder="#029D7E" readonly>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="icon" class="form-label">Icona Bootstrap</label>
                            <input type="text" class="form-control" id="icon" name="icon" value="bi-person" 
                                   placeholder="es: bi-person">
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Vedi icone: <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a>
                            </div>
                        </div>
                        
                        <!-- Controlli OWASP -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-shield-check me-2"></i>Controlli Sicurezza OWASP
                            </h6>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="max_orders_per_day" class="form-label">Limite Ordini/Giorno</label>
                            <input type="number" class="form-control" id="max_orders_per_day" name="max_orders_per_day" 
                                   min="1" max="1000" placeholder="Illimitato se vuoto">
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="require_approval" name="require_approval" value="1">
                                <label class="form-check-label" for="require_approval">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Richiedi approvazione ordini
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="priority_support" name="priority_support" value="1">
                                <label class="form-check-label" for="priority_support">
                                    <i class="bi bi-headset me-1"></i>
                                    Supporto prioritario
                                </label>
                            </div>
                        </div>
                        
                        <!-- Note -->
                        <div class="col-12 mt-4">
                            <label for="notes" class="form-label">Note Interne</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" 
                                      placeholder="Note interne per uso amministrativo..."></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer" style="padding: 1.5rem 2rem; border-top: 1px solid #e9ecef;">
                    <button type="button" class="btn btn-secondary modern-btn" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Annulla
                    </button>
                    <button type="submit" class="btn btn-success modern-btn" id="saveButton">
                        <i class="bi bi-check-lg"></i> <span id="saveButtonText">Salva Categoria</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Sincronizza color picker con input text
document.getElementById('color_hex').addEventListener('change', function() {
    document.getElementById('color_hex_text').value = this.value;
});

document.getElementById('color_hex_text').addEventListener('input', function() {
    if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
        document.getElementById('color_hex').value = this.value;
    }
});

// Filtro tabella e mobile cards
function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const typeFilter = document.getElementById('typeFilter').value;
    const priorityFilter = document.getElementById('priorityFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const discountFilter = document.getElementById('discountFilter').value;

    // Filtro tabella desktop
    const rows = document.querySelectorAll('#tableBody tr:not(#noResults)');
    let visibleCount = 0;

    rows.forEach(row => {
        const code = row.dataset.code || '';
        const name = row.dataset.name || '';
        const type = row.dataset.type || '';
        const priority = row.dataset.priority || '';
        const status = row.dataset.status || '';
        const discount = row.dataset.discount || '';

        const matchesSearch = code.includes(searchTerm) || name.includes(searchTerm);
        const matchesType = !typeFilter || type === typeFilter;
        const matchesPriority = !priorityFilter || priority === priorityFilter;
        const matchesStatus = !statusFilter || status === statusFilter;
        const matchesDiscount = !discountFilter || discount === discountFilter;

        const shouldShow = matchesSearch && matchesType && matchesPriority && matchesStatus && matchesDiscount;

        if (shouldShow) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Mostra/nascondi messaggio "nessun risultato"
    const noResults = document.getElementById('noResults');
    if (noResults) {
        noResults.style.display = visibleCount === 0 ? '' : 'none';
    }

    // Filtro mobile cards
    const mobileCards = document.querySelectorAll('.mobile-item-row');
    let mobileVisibleCount = 0;

    mobileCards.forEach(card => {
        const code = card.dataset.code || '';
        const name = card.dataset.name || '';
        const type = card.dataset.type || '';
        const priority = card.dataset.priority || '';
        const status = card.dataset.status || '';
        const discount = card.dataset.discount || '';

        const matchesSearch = code.includes(searchTerm) || name.includes(searchTerm);
        const matchesType = !typeFilter || type === typeFilter;
        const matchesPriority = !priorityFilter || priority === priorityFilter;
        const matchesStatus = !statusFilter || status === statusFilter;
        const matchesDiscount = !discountFilter || discount === discountFilter;

        const shouldShow = matchesSearch && matchesType && matchesPriority && matchesStatus && matchesDiscount;

        if (shouldShow) {
            card.style.display = '';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
            mobileVisibleCount++;
        } else {
            card.style.opacity = '0';
            card.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                card.style.display = 'none';
            }, 300);
        }
    });
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('typeFilter').value = '';
    document.getElementById('priorityFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('discountFilter').value = '';
    filterTable();
}

// Funzioni CRUD
function viewItem(id) {
    window.open(`/system-tables/customer_categories/${id}`, '_blank');
}

function editItem(id) {
    // Carica dati categoria via AJAX
    fetch(`/system-tables/customer_categories/${id}`)
        .then(response => response.json())
        .then(data => {
            // Popola il form
            document.getElementById('categoryId').value = data.id;
            document.getElementById('methodField').value = 'PUT';
            document.getElementById('modalTitle').textContent = 'Modifica Categoria Cliente';
            document.getElementById('saveButtonText').textContent = 'Aggiorna Categoria';
            
            // Compila i campi
            document.getElementById('code').value = data.code || '';
            document.getElementById('name').value = data.name || '';
            document.getElementById('description').value = data.description || '';
            document.getElementById('type').value = data.type || 'STANDARD';
            document.getElementById('priority_level').value = data.priority_level || 'MEDIUM';
            document.getElementById('discount_percentage').value = data.discount_percentage || 0;
            document.getElementById('payment_terms_days').value = data.payment_terms_days || 30;
            document.getElementById('credit_limit').value = data.credit_limit || '';
            document.getElementById('price_list').value = data.price_list || 'LIST_1';
            document.getElementById('color_hex').value = data.color_hex || '#029D7E';
            document.getElementById('color_hex_text').value = data.color_hex || '#029D7E';
            document.getElementById('icon').value = data.icon || 'bi-person';
            document.getElementById('max_orders_per_day').value = data.max_orders_per_day || '';
            document.getElementById('require_approval').checked = data.require_approval || false;
            document.getElementById('priority_support').checked = data.priority_support || false;
            document.getElementById('notes').value = data.notes || '';
            
            // Aggiorna URL del form
            document.getElementById('categoryForm').action = `/system-tables/customer_categories/${id}`;
            
            // Mostra modal
            const modal = new bootstrap.Modal(document.getElementById('createModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Errore nel caricamento dei dati:', error);
            alert('Errore nel caricamento dei dati');
        });
}

function deleteItem(id) {
    if (confirm('Sei sicuro di voler eliminare questa categoria cliente?\nQuesta azione non può essere annullata.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/system-tables/customer_categories/${id}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

function exportData() {
    window.location.href = '/system-tables/customer_categories/export';
}

// Reset form quando si apre modal per creazione
document.getElementById('createModal').addEventListener('show.bs.modal', function (event) {
    if (!event.relatedTarget || !event.relatedTarget.onclick.toString().includes('editItem')) {
        // Reset per nuovo elemento
        document.getElementById('categoryId').value = '';
        document.getElementById('methodField').value = '';
        document.getElementById('modalTitle').textContent = 'Nuova Categoria Cliente';
        document.getElementById('saveButtonText').textContent = 'Salva Categoria';
        document.getElementById('categoryForm').action = '/system-tables/customer_categories';
        document.getElementById('categoryForm').reset();
        document.getElementById('color_hex').value = '#029D7E';
        document.getElementById('color_hex_text').value = '#029D7E';
        document.getElementById('priority_level').value = 'MEDIUM';
        document.getElementById('type').value = 'STANDARD';
        document.getElementById('discount_percentage').value = 0;
        document.getElementById('payment_terms_days').value = 30;
        document.getElementById('price_list').value = 'LIST_1';
        document.getElementById('icon').value = 'bi-person';
    }
});

// Auto-complete del codice basato sul tipo
document.getElementById('type').addEventListener('change', function() {
    if (!document.getElementById('code').value) {
        const typePrefix = this.value === 'VIP' ? 'VIP' : 
                          this.value === 'B2B' ? 'B2B' : 
                          this.value === 'B2C' ? 'B2C' : 
                          this.value === 'WHOLESALE' ? 'WHL' : 
                          this.value === 'RETAIL' ? 'RTL' : 'STD';
        
        const timestamp = Date.now().toString().slice(-4);
        document.getElementById('code').value = typePrefix + '_' + timestamp;
    }
});
</script>
@endsection