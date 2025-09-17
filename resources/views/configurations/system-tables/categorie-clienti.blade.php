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
    
    /* Header */
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
    
    /* Pulsanti */
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
    
    /* Filtri ricerca */
    .search-filters {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
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
    
    /* Tabella */
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
    
    /* Badge stati */
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
    
    .category-info {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }
    
    .category-code {
        font-family: 'Courier New', monospace;
        background: rgba(2, 157, 126, 0.1);
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.8rem;
        color: #023e8a;
        font-weight: 600;
    }
    
    /* Badge tipi clienti */
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
    
    /* Badge priorità */
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
    
    /* Pulsanti azioni */
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
    
    td .action-btn {
        vertical-align: middle;
        line-height: 1;
    }
    
    /* Mobile cards */
    .mobile-cards {
        display: none;
    }
    
    .category-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .category-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .category-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
        flex: 1;
        min-width: 0;
    }
    
    .category-card-codes {
        display: flex;
        flex-direction: column;
        align-items: end;
        gap: 0.3rem;
        flex-shrink: 0;
    }
    
    .category-detail {
        display: flex;
        flex-direction: column;
    }
    
    .category-detail-label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 0.2rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .category-detail-value {
        font-weight: 500;
        color: #2d3748;
    }
    
    .category-card-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 1.5rem;
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
    
    /* Responsive */
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
        
        .category-card {
            padding: 1rem;
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
</style>

<div class="management-container">
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
    
    <div class="management-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <h1 class="management-title">
                    <i class="bi bi-people me-3" style="color: #48cae4; font-size: 2rem;"></i>
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
            </div>
        </div>
    </div>

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
            <div class="col-md-1">
                <button type="button" class="btn btn-secondary modern-btn w-100" onclick="resetFilters()">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="modern-table" id="categoriesTable">
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
                    @forelse($items ?? [] as $item)
                        <tr class="table-row" 
                            data-code="{{ strtolower($item->code) }}" 
                            data-name="{{ strtolower($item->name) }}"
                            data-type="{{ $item->type }}"
                            data-priority="{{ $item->priority_level ?? 'MEDIUM' }}"
                            data-status="{{ $item->active ? '1' : '0' }}"
                            data-discount="{{ $item->discount_percentage > 0 ? 'with' : 'without' }}">
                            <td>
                                <span class="category-code">{{ $item->code }}</span>
                            </td>
                            <td>
                                <strong>{{ $item->name }}</strong>
                                @if($item->description)
                                    <br><small class="text-muted">{{ Str::limit($item->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="type-badge type-{{ strtolower($item->type) }}">
                                    {{ $item->type_translated ?? $item->type }}
                                </span>
                            </td>
                            <td>
                                <span class="priority-badge priority-{{ strtolower($item->priority_level ?? 'medium') }}">
                                    {{ ucfirst(strtolower($item->priority_level ?? 'Medium')) }}
                                </span>
                            </td>
                            <td>
                                <strong class="{{ $item->discount_percentage > 0 ? 'text-success' : 'text-muted' }}">
                                    {{ $item->formatted_discount ?? ($item->discount_percentage ?? 0) . '%' }}
                                </strong>
                            </td>
                            <td>{{ $item->formatted_credit_limit ?? ($item->credit_limit ? '€' . number_format($item->credit_limit, 2) : 'Illimitato') }}</td>
                            <td>{{ $item->payment_terms_days ?? 30 }} giorni</td>
                            <td>
                                <span class="status-badge {{ $item->active ? 'status-active' : 'status-inactive' }}">
                                    {{ $item->active ? 'Attivo' : 'Inattivo' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
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
                            <td colspan="9">
                                <div class="empty-state">
                                    <i class="bi bi-people"></i>
                                    <h4>Nessuna categoria clienti configurata</h4>
                                    <p>Inizia creando la prima categoria per organizzare i tuoi clienti.</p>
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

    <!-- Mobile cards -->
    <div class="mobile-cards" id="mobileCards">
        @forelse($items ?? [] as $item)
            <div class="category-card mobile-category-row"
                 data-code="{{ strtolower($item->code) }}" 
                 data-name="{{ strtolower($item->name) }}"
                 data-type="{{ $item->type }}"
                 data-priority="{{ $item->priority_level ?? 'MEDIUM' }}"
                 data-status="{{ $item->active ? '1' : '0' }}"
                 data-discount="{{ $item->discount_percentage > 0 ? 'with' : 'without' }}">
                
                <div class="category-card-header">
                    <h3 class="category-card-title">{{ $item->name }}</h3>
                    <div class="category-card-codes">
                        <span class="category-code">{{ $item->code }}</span>
                        <div class="mt-1">
                            <span class="type-badge type-{{ strtolower($item->type) }}" style="font-size: 0.65rem;">
                                {{ $item->type_translated ?? $item->type }}
                            </span>
                        </div>
                        <div class="mt-1">
                            <span class="status-badge {{ $item->active ? 'status-active' : 'status-inactive' }}" style="font-size: 0.7rem;">
                                {{ $item->active ? 'Attivo' : 'Inattivo' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="category-card-details">
                    <div class="category-detail">
                        <span class="category-detail-label">Priorità</span>
                        <span class="category-detail-value">
                            <span class="priority-badge priority-{{ strtolower($item->priority_level ?? 'medium') }}">
                                {{ ucfirst(strtolower($item->priority_level ?? 'Medium')) }}
                            </span>
                        </span>
                    </div>
                    <div class="category-detail">
                        <span class="category-detail-label">Sconto</span>
                        <span class="category-detail-value {{ $item->discount_percentage > 0 ? 'text-success' : 'text-muted' }}">
                            {{ $item->formatted_discount ?? ($item->discount_percentage ?? 0) . '%' }}
                        </span>
                    </div>
                    <div class="category-detail">
                        <span class="category-detail-label">Limite Credito</span>
                        <span class="category-detail-value">{{ $item->formatted_credit_limit ?? ($item->credit_limit ? '€' . number_format($item->credit_limit, 2) : 'Illimitato') }}</span>
                    </div>
                    <div class="category-detail">
                        <span class="category-detail-label">Termini Pag.</span>
                        <span class="category-detail-value">{{ $item->payment_terms_days ?? 30 }} giorni</span>
                    </div>
                </div>
                
                @if($item->description)
                    <div class="category-detail mb-3">
                        <span class="category-detail-label">Descrizione</span>
                        <span class="category-detail-value">{{ $item->description }}</span>
                    </div>
                @endif
                
                <div class="category-card-actions">
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
                <h4>Nessuna categoria clienti configurata</h4>
                <p>Inizia creando la prima categoria per organizzare i tuoi clienti.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal creazione/modifica -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #029D7E, #4DC9A5); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title" id="createModalLabel">
                    <i class="bi bi-plus-lg me-2"></i>Nuova Categoria Cliente
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createForm" onsubmit="submitForm(event, 'create')">
                @csrf
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
                        
                        
                        <!-- Controlli -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-shield-check me-2"></i>Controlli e Opzioni
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
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="active" name="active" checked>
                                <label class="form-check-label" for="active">
                                    <i class="bi bi-toggle-on me-1"></i>
                                    Attivo
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
                    <button type="submit" class="btn btn-success modern-btn">
                        <i class="bi bi-check-lg"></i> Salva Categoria
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let allItems = [];

// Controllo duplicati
async function checkCodeDuplicate(code) {
    if (!code || code.trim() === '') return false;
    
    try {
        const response = await fetch(`{{ route("configurations.system-tables.index", "categorie_clienti") }}?check_duplicate=${encodeURIComponent(code)}`, {
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

// Filtri
function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const typeFilter = document.getElementById('typeFilter').value;
    const priorityFilter = document.getElementById('priorityFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const discountFilter = document.getElementById('discountFilter').value;
    
    const rows = document.querySelectorAll('#tableBody tr:not(#noResults)');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const code = row.dataset.code;
        const name = row.dataset.name;
        const type = row.dataset.type;
        const priority = row.dataset.priority;
        const status = row.dataset.status;
        const discount = row.dataset.discount;
        
        const matchesSearch = code.includes(searchTerm) || name.includes(searchTerm);
        const matchesType = !typeFilter || type === typeFilter;
        const matchesPriority = !priorityFilter || priority === priorityFilter;
        const matchesStatus = !statusFilter || status === statusFilter;
        const matchesDiscount = !discountFilter || discount === discountFilter;
        
        if (matchesSearch && matchesType && matchesPriority && matchesStatus && matchesDiscount) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    const mobileCards = document.querySelectorAll('.mobile-category-row');
    let mobileVisibleCount = 0;
    
    mobileCards.forEach(card => {
        const code = card.dataset.code;
        const name = card.dataset.name;
        const type = card.dataset.type;
        const priority = card.dataset.priority;
        const status = card.dataset.status;
        const discount = card.dataset.discount;
        
        const matchesSearch = code.includes(searchTerm) || name.includes(searchTerm);
        const matchesType = !typeFilter || type === typeFilter;
        const matchesPriority = !priorityFilter || priority === priorityFilter;
        const matchesStatus = !statusFilter || status === statusFilter;
        const matchesDiscount = !discountFilter || discount === discountFilter;
        
        if (matchesSearch && matchesType && matchesPriority && matchesStatus && matchesDiscount) {
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
    
    const noResults = document.getElementById('noResults');
    if (noResults) {
        noResults.style.display = (visibleCount === 0 && mobileVisibleCount === 0) ? '' : 'none';
    }
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('typeFilter').value = '';
    document.getElementById('priorityFilter').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('discountFilter').value = '';
    filterTable();
}

// Submit form
async function submitForm(event, action) {
    event.preventDefault();
    
    const form = event.target;
    
    if (action === 'create') {
        const code = form.code.value.trim().toUpperCase();
        if (await checkCodeDuplicate(code)) {
            alert('⚠️ Errore: Il codice "' + code + '" esiste già!\nScegli un codice diverso.');
            form.code.focus();
            return;
        }
    }
    
    const formData = new FormData(form);
    formData.set('active', form.active.checked ? '1' : '0');
    
    const url = action === 'create' 
        ? '{{ route("configurations.system-tables.store", "categorie_clienti") }}'
        : `{{ route("configurations.system-tables.update", ["table" => "categorie_clienti", "id" => ":id"]) }}`.replace(':id', form.dataset.itemId);
    
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
            let errorMessage = data.message || 'Operazione fallita';
            
            if (errorMessage.includes('Duplicate entry')) {
                const code = errorMessage.match(/'([^']+)'/)?.[1] || 'sconosciuto';
                errorMessage = `⚠️ Codice "${code}" già esistente!\nScegli un codice diverso.`;
            }
            
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

// Visualizza categoria
function viewItem(id) {
    fetch(`{{ route("configurations.system-tables.show", "categorie_clienti") }}/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Errore HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(item => {
            if (!item || item.error) {
                throw new Error(item?.message || 'Dati non validi ricevuti dal server');
            }
            
            const modalContent = `
                <div class="modal fade" id="viewModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content" style="border-radius: 20px; border: none;">
                            <div class="modal-header" style="background: linear-gradient(135deg, #029D7E, #4DC9A5); color: white; border-radius: 20px 20px 0 0;">
                                <h5 class="modal-title">
                                    <i class="bi bi-eye me-2"></i>Dettagli Categoria Cliente
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body" style="padding: 2rem;">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Codice Categoria</label>
                                        <div class="p-2 bg-light rounded">
                                            <span class="category-code">${item.code}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Nome Categoria</label>
                                        <div class="p-2 bg-light rounded">${item.name}</div>
                                    </div>
                                    ${item.description ? `
                                    <div class="col-12">
                                        <label class="form-label fw-bold text-muted">Descrizione</label>
                                        <div class="p-2 bg-light rounded">${item.description}</div>
                                    </div>
                                    ` : ''}
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Tipo Cliente</label>
                                        <div class="p-2 bg-light rounded">${item.type_translated || item.type}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Priorità</label>
                                        <div class="p-2 bg-light rounded">${item.priority_level || 'Medium'}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-muted">Sconto</label>
                                        <div class="p-2 bg-light rounded">${item.discount_percentage || 0}%</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-muted">Termini Pag.</label>
                                        <div class="p-2 bg-light rounded">${item.payment_terms_days || 30} giorni</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold text-muted">Limite Credito</label>
                                        <div class="p-2 bg-light rounded">${item.credit_limit ? '€' + item.credit_limit : 'Illimitato'}</div>
                                    </div>
                                    ${item.max_orders_per_day ? `
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Limite Ordini/Giorno</label>
                                        <div class="p-2 bg-light rounded">${item.max_orders_per_day}</div>
                                    </div>
                                    ` : ''}
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Stato</label>
                                        <div class="p-2">
                                            <span class="status-badge ${item.active ? 'status-active' : 'status-inactive'}">
                                                ${item.active ? 'Attivo' : 'Inattivo'}
                                            </span>
                                        </div>
                                    </div>
                                    ${item.notes ? `
                                    <div class="col-12">
                                        <label class="form-label fw-bold text-muted">Note Interne</label>
                                        <div class="p-2 bg-light rounded">${item.notes}</div>
                                    </div>
                                    ` : ''}
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
            
            const existingModal = document.getElementById('viewModal');
            if (existingModal) {
                existingModal.remove();
            }
            
            document.body.insertAdjacentHTML('beforeend', modalContent);
            new bootstrap.Modal(document.getElementById('viewModal')).show();
        })
        .catch(error => {
            console.error('Errore nel caricamento dei dati:', error);
            alert('🔌 Errore nel caricamento dei dati. Verifica la connessione al server.');
        });
}

// Modifica categoria
function editItem(id) {
    fetch(`{{ route("configurations.system-tables.show", "categorie_clienti") }}/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Errore HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (!data || data.error) {
                throw new Error(data?.message || 'Dati non validi ricevuti dal server');
            }
            
            document.getElementById('code').value = data.code || '';
            document.getElementById('name').value = data.name || '';
            document.getElementById('description').value = data.description || '';
            document.getElementById('type').value = data.type || '';
            document.getElementById('priority_level').value = data.priority_level || 'MEDIUM';
            document.getElementById('price_list').value = data.price_list || 'LIST_1';
            document.getElementById('discount_percentage').value = data.discount_percentage || 0;
            document.getElementById('payment_terms_days').value = data.payment_terms_days || 30;
            document.getElementById('credit_limit').value = data.credit_limit || '';
            document.getElementById('max_orders_per_day').value = data.max_orders_per_day || '';
            document.getElementById('require_approval').checked = data.require_approval || false;
            document.getElementById('priority_support').checked = data.priority_support || false;
            document.getElementById('notes').value = data.notes || '';
            document.getElementById('active').checked = data.active;
            
            const form = document.getElementById('createForm');
            const inputs = form.querySelectorAll('input, textarea, select');
            inputs.forEach(input => input.disabled = false);
            
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.style.display = 'inline-flex';
            
            document.getElementById('createModalLabel').innerHTML = '<i class="bi bi-pencil me-2"></i>Modifica Categoria Cliente';
            form.dataset.itemId = id;
            
            new bootstrap.Modal(document.getElementById('createModal')).show();
        })
        .catch(error => {
            console.error('Errore nel caricamento dei dati:', error);
            alert('🔌 Errore nel caricamento dei dati. Verifica la connessione al server.');
        });
}

// Elimina categoria
function deleteItem(id) {
    if (confirm('Sei sicuro di voler eliminare questa categoria cliente?')) {
        fetch(`{{ route("configurations.system-tables.destroy", ["table" => "categorie_clienti", "id" => ":id"]) }}`.replace(':id', id), {
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

// Reset modal
document.getElementById('createModal').addEventListener('hidden.bs.modal', function () {
    const form = document.getElementById('createForm');
    form.reset();
    form.dataset.itemId = '';
    
    const inputs = form.querySelectorAll('input, textarea, select');
    inputs.forEach(input => input.disabled = false);
    
    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) submitBtn.style.display = 'inline-flex';
    
    document.getElementById('createModalLabel').innerHTML = '<i class="bi bi-plus-lg me-2"></i>Nuova Categoria Cliente';
});

// Validazione codice
let checkTimeout;
document.getElementById('code').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9_-]/g, '');
    
    const code = e.target.value.trim();
    const statusDiv = document.getElementById('codeStatus');
    const messageDiv = document.getElementById('codeMessage');
    
    document.getElementById('codeLoading').style.display = 'none';
    document.getElementById('codeOk').style.display = 'none';
    document.getElementById('codeDuplicate').style.display = 'none';
    messageDiv.style.display = 'none';
    statusDiv.style.display = 'none';
    
    if (code.length >= 3) {
        statusDiv.style.display = 'block';
        document.getElementById('codeLoading').style.display = 'inline';
        
        clearTimeout(checkTimeout);
        checkTimeout = setTimeout(async () => {
            const isDuplicate = await checkCodeDuplicate(code);
            
            document.getElementById('codeLoading').style.display = 'none';
            
            if (isDuplicate) {
                document.getElementById('codeDuplicate').style.display = 'inline';
                messageDiv.textContent = `Codice "${code}" già esistente. Scegli un codice diverso.`;
                messageDiv.style.display = 'block';
                e.target.classList.add('is-invalid');
            } else {
                document.getElementById('codeOk').style.display = 'inline';
                e.target.classList.remove('is-invalid');
                e.target.classList.add('is-valid');
            }
        }, 500);
    } else {
        e.target.classList.remove('is-invalid', 'is-valid');
    }
});

// Inizializzazione
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert, index) {
        setTimeout(function() {
            if (bootstrap.Alert.getOrCreateInstance) {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }
        }, 5000 + (index * 1000));
    });
    
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