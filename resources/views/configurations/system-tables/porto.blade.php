@extends('layouts.app')

@section('content')

<style>
/* CSS seguendo il Design System */
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
}

.modern-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.stat-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    text-align: center;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #029D7E, #4DC9A5);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: white;
}

.stat-icon.active {
    background: linear-gradient(135deg, #029D7E, #4DC9A5);
}

.stat-icon.inactive {
    background: linear-gradient(135deg, #6c757d, #545b62);
}

.stat-icon.factory {
    background: linear-gradient(135deg, #dc3545, #c82333);
}

.stat-icon.delivery {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.stat-icon.customer_pays {
    background: linear-gradient(135deg, #ffc107, #fd7e14);
}

.stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
    color: #029D7E;
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

.action-btn.edit {
    background: linear-gradient(135deg, #ffd60a, #ff8500);
    color: white;
}

.action-btn.delete {
    background: linear-gradient(135deg, #f72585, #c5025a);
    color: white;
}

.status-active {
    color: #155724;
    background: #d4edda;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.status-inactive {
    color: #721c24;
    background: #f8d7da;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.code-badge {
    color: #0f5132;
    background: #d1e7dd;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.type-factory {
    color: #721c24;
    background: #f8d7da;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.type-delivery {
    color: #155724;
    background: #d4edda;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.type-mixed {
    color: #664d03;
    background: #fff3cd;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

/* Mobile Cards */
.mobile-cards {
    display: none;
}

.item-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.item-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.item-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(226, 232, 240, 0.3);
}

.item-card-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin: 0 0 0.25rem 0;
    color: #029D7E;
}

.item-card-code {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 600;
}

.item-card-details {
    margin-bottom: 1.5rem;
}

.item-detail {
    margin-bottom: 0.75rem;
}

.item-detail-label {
    font-size: 0.8rem;
    color: #6c757d;
    font-weight: 600;
    display: block;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.item-detail-value {
    font-weight: 600;
    color: #2d3748;
}

.item-card-actions {
    display: flex;
    gap: 0.5rem;
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

.mobile-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
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
    padding: 3rem 2rem;
}

.empty-state i {
    font-size: 4rem;
    color: #cbd5e0;
    margin-bottom: 1.5rem;
}

.empty-state h4 {
    color: #4a5568;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #718096;
    font-size: 1.1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .management-container {
        padding: 1rem;
    }

    .management-header {
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .search-filters {
        padding: 1.5rem;
    }

    .table-container {
        display: none;
    }

    .mobile-cards {
        display: block;
    }
}
</style>

<div class="management-container">
    <!-- Header Pagina -->
    <div class="management-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <h1 class="management-title">
                    <i class="bi bi-truck me-3" style="color: #029D7E; font-size: 2rem;"></i>
                    Termini di Porto
                </h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('configurations.system-tables.index') }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-arrow-left"></i> Torna Indietro
                </a>
                <button type="button" class="btn btn-success modern-btn" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-lg"></i> Nuovo Termine Porto
                </button>
                <button type="button" class="btn btn-warning modern-btn" onclick="exportData()">
                    <i class="bi bi-download"></i> Esporta
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiche -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-list-check"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total'] }}</h3>
                    <p>Totale</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon active">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['active'] }}</h3>
                    <p>Attivi</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon factory">
                    <i class="bi bi-building"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['factory'] }}</h3>
                    <p>Ritiro Fabbrica</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon delivery">
                    <i class="bi bi-house-door"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['delivery'] }}</h3>
                    <p>Consegna Domicilio</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon customer_pays">
                    <i class="bi bi-credit-card"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['customer_pays'] }}</h3>
                    <p>Cliente Paga</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon inactive">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['inactive'] }}</h3>
                    <p>Disattivati</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtri -->
    <div class="search-filters">
        <div class="row g-3">
            <div class="col-md-3">
                <input type="text" class="form-control search-input" id="searchInput" placeholder="Cerca termini porto..." onkeyup="filterTable()">
            </div>
            <div class="col-md-2">
                <select class="form-select filter-select" id="statusFilter" onchange="filterTable()">
                    <option value="">Tutti gli Stati</option>
                    <option value="1">Attivi</option>
                    <option value="0">Inattivi</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select filter-select" id="typeFilter" onchange="filterTable()">
                    <option value="">Tutti i Tipi</option>
                    <option value="factory">Ritiro Fabbrica</option>
                    <option value="delivery">Consegna Domicilio</option>
                    <option value="mixed">Termini Misti</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select filter-select" id="shippingFilter" onchange="filterTable()">
                    <option value="">Spese Trasporto</option>
                    <option value="1">Cliente Paga</option>
                    <option value="0">Fornitore Paga</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select filter-select" id="incotermFilter" onchange="filterTable()">
                    <option value="">Tutti gli Incoterm</option>
                    <option value="EXW">EXW - Ex Works</option>
                    <option value="DAP">DAP - Delivered At Place</option>
                    <option value="FOB">FOB - Free On Board</option>
                    <option value="CIF">CIF - Cost Insurance Freight</option>
                    <option value="DDP">DDP - Delivered Duty Paid</option>
                    <option value="CPT">CPT - Carriage Paid To</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-secondary modern-btn w-100" onclick="resetFilters()">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Tabella Desktop -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="modern-table" id="shippingTermsTable">
                <thead>
                    <tr>
                        <th>CODICE</th>
                        <th>NOME</th>
                        <th>DESCRIZIONE</th>
                        <th>INCOTERM</th>
                        <th>TIPO</th>
                        <th>SPESE</th>
                        <th>STATO</th>
                        <th width="120">AZIONI</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($items as $item)
                        <tr data-active="{{ $item->active ? '1' : '0' }}" 
                            data-type="{{ $item->type }}" 
                            data-shipping="{{ $item->customer_pays_shipping ? '1' : '0' }}"
                            data-incoterm="{{ $item->incoterm_code }}">
                            <td>
                                <strong class="code-badge">{{ $item->code }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $item->name }}</strong>
                                    @if($item->active)
                                        <div><small class="status-active"><i class="bi bi-check-circle-fill me-1"></i>Attivo</small></div>
                                    @else
                                        <div><small class="status-inactive"><i class="bi bi-x-circle-fill me-1"></i>Inattivo</small></div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="text-muted">{{ Str::limit($item->description, 60) }}</span>
                            </td>
                            <td>
                                @if($item->incoterm_code)
                                    <span class="badge bg-info">{{ $item->incoterm_code }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->type == 'factory')
                                    <span class="type-factory">
                                        <i class="bi bi-building me-1"></i>Fabbrica
                                    </span>
                                @elseif($item->type == 'delivery')
                                    <span class="type-delivery">
                                        <i class="bi bi-house-door me-1"></i>Consegna
                                    </span>
                                @else
                                    <span class="type-mixed">
                                        <i class="bi bi-shuffle me-1"></i>Misto
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($item->customer_pays_shipping)
                                    <span class="text-warning">
                                        <i class="bi bi-credit-card me-1"></i>Cliente
                                    </span>
                                @else
                                    <span class="text-success">
                                        <i class="bi bi-building me-1"></i>Fornitore
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($item->active)
                                    <span class="status-active">
                                        <i class="bi bi-check-circle-fill me-1"></i>Attivo
                                    </span>
                                @else
                                    <span class="status-inactive">
                                        <i class="bi bi-x-circle-fill me-1"></i>Inattivo
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="action-btn edit" title="Modifica termine porto" onclick="editItem({{ $item->id }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="action-btn delete" title="Elimina termine porto" onclick="deleteItem({{ $item->id }}, '{{ $item->name }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noResults" style="display: none;">
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-truck display-1 text-muted mb-3"></i>
                                    <h4>Nessun termine porto trovato</h4>
                                    <p class="text-muted">Non sono stati trovati termini di porto per i filtri selezionati.</p>
                                </div>
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
            <div class="item-card mobile-item-row" data-active="{{ $item->active ? '1' : '0' }}" 
                 data-type="{{ $item->type }}" 
                 data-shipping="{{ $item->customer_pays_shipping ? '1' : '0' }}"
                 data-incoterm="{{ $item->incoterm_code }}">
                
                <div class="item-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div>
                            <h3 class="item-card-title">{{ $item->name }}</h3>
                            <p class="item-card-code">Codice: {{ $item->code }}</p>
                        </div>
                    </div>
                    @if($item->active)
                        <span class="status-active">
                            <i class="bi bi-check-circle-fill me-1"></i>Attivo
                        </span>
                    @else
                        <span class="status-inactive">
                            <i class="bi bi-x-circle-fill me-1"></i>Inattivo
                        </span>
                    @endif
                </div>

                <div class="item-card-details">
                    <div class="item-detail">
                        <span class="item-detail-label">Descrizione</span>
                        <span class="item-detail-value">{{ $item->description }}</span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-label">Incoterm</span>
                        <span class="item-detail-value">{{ $item->incoterm_code ?: 'Non specificato' }}</span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-label">Tipo</span>
                        <span class="item-detail-value">{{ $item->formatted_type }}</span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-label">Spese Trasporto</span>
                        <span class="item-detail-value">{{ $item->customer_pays_shipping ? 'A carico del cliente' : 'A carico del fornitore' }}</span>
                    </div>
                </div>

                <div class="item-card-actions">
                    <button type="button" class="mobile-action-btn edit" onclick="editItem({{ $item->id }})">
                        <i class="bi bi-pencil"></i>
                        <span>Modifica</span>
                    </button>
                    <button type="button" class="mobile-action-btn delete" onclick="deleteItem({{ $item->id }}, '{{ $item->name }}')">
                        <i class="bi bi-trash"></i>
                        <span>Elimina</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="bi bi-truck"></i>
                <h4>Nessun termine porto trovato</h4>
                <p>Non sono stati trovati termini di porto per i filtri selezionati.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Termine Porto -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #029D7E, #4DC9A5); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title">
                    <i class="bi bi-truck me-2"></i>
                    <span id="modalTitle">Nuovo Termine di Porto</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="shippingTermForm">
                <div class="modal-body" style="padding: 2rem;">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label">Codice *</label>
                            <input type="text" class="form-control" id="code" name="code" required maxlength="10" style="text-transform: uppercase;" oninput="validateCode()">
                            <div class="invalid-feedback" id="codeError"></div>
                            <small class="form-text text-muted">Es: FF, FD, FOB</small>
                        </div>
                        <div class="col-md-6">
                            <label for="incoterm_code" class="form-label">Codice Incoterm</label>
                            <select class="form-select" id="incoterm_code" name="incoterm_code">
                                <option value="">Seleziona Incoterm...</option>
                                <option value="EXW">EXW - Ex Works</option>
                                <option value="FCA">FCA - Free Carrier</option>
                                <option value="FOB">FOB - Free On Board</option>
                                <option value="CIF">CIF - Cost Insurance Freight</option>
                                <option value="DAP">DAP - Delivered At Place</option>
                                <option value="DDP">DDP - Delivered Duty Paid</option>
                                <option value="CPT">CPT - Carriage Paid To</option>
                                <option value="CIP">CIP - Carriage Insurance Paid</option>
                            </select>
                            <div class="invalid-feedback" id="incoterm_codeError"></div>
                            <small class="form-text text-muted">Incoterm di riferimento</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome *</label>
                        <input type="text" class="form-control" id="name" name="name" required maxlength="100">
                        <div class="invalid-feedback" id="nameError"></div>
                        <small class="form-text text-muted">Nome del termine di porto</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Descrizione *</label>
                        <textarea class="form-control" id="description" name="description" required maxlength="500" rows="3"></textarea>
                        <div class="invalid-feedback" id="descriptionError"></div>
                        <small class="form-text text-muted">Descrizione completa del termine</small>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="type" class="form-label">Tipo Termine *</label>
                            <select class="form-select" id="type" name="type" required>
                                <option value="">Seleziona tipo...</option>
                                <option value="factory">Ritiro in fabbrica</option>
                                <option value="delivery">Consegna a domicilio</option>
                                <option value="mixed">Termini misti</option>
                            </select>
                            <div class="invalid-feedback" id="typeError"></div>
                            <small class="form-text text-muted">Modalità di consegna</small>
                        </div>
                        <div class="col-md-6">
                            <label for="sort_order" class="form-label">Ordine Visualizzazione</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order" min="0" max="9999" value="0">
                            <div class="invalid-feedback" id="sort_orderError"></div>
                            <small class="form-text text-muted">Numeri più bassi appaiono per primi</small>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="customer_pays_shipping" name="customer_pays_shipping">
                                <label class="form-check-label" for="customer_pays_shipping">Cliente paga le spese di trasporto</label>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="active" name="active" checked>
                                <label class="form-check-label" for="active">Termine attivo</label>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer" style="padding: 1.5rem 2rem; border-top: 1px solid #e9ecef;">
                    <button type="button" class="btn btn-secondary modern-btn" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Annulla
                    </button>
                    <button type="submit" class="btn btn-success modern-btn" id="saveBtn">
                        <i class="bi bi-check-lg"></i> Salva Termine Porto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Funzioni di filtro
function filterTable() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const statusValue = document.getElementById('statusFilter').value;
    const typeValue = document.getElementById('typeFilter').value;
    const shippingValue = document.getElementById('shippingFilter').value;
    const incotermValue = document.getElementById('incotermFilter').value;
    
    const tableRows = document.querySelectorAll('#tableBody tr[data-active]');
    const mobileCards = document.querySelectorAll('.mobile-item-row[data-active]');
    
    let visibleCount = 0;
    
    // Filtro tabella desktop
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const active = row.getAttribute('data-active');
        const type = row.getAttribute('data-type');
        const shipping = row.getAttribute('data-shipping');
        const incoterm = row.getAttribute('data-incoterm');
        
        let show = true;
        
        if (searchValue && !text.includes(searchValue)) show = false;
        if (statusValue && active !== statusValue) show = false;
        if (typeValue && type !== typeValue) show = false;
        if (shippingValue && shipping !== shippingValue) show = false;
        if (incotermValue && incoterm !== incotermValue) show = false;
        
        row.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });
    
    // Filtro cards mobile
    mobileCards.forEach(card => {
        const text = card.textContent.toLowerCase();
        const active = card.getAttribute('data-active');
        const type = card.getAttribute('data-type');
        const shipping = card.getAttribute('data-shipping');
        const incoterm = card.getAttribute('data-incoterm');
        
        let show = true;
        
        if (searchValue && !text.includes(searchValue)) show = false;
        if (statusValue && active !== statusValue) show = false;
        if (typeValue && type !== typeValue) show = false;
        if (shippingValue && shipping !== shippingValue) show = false;
        if (incotermValue && incoterm !== incotermValue) show = false;
        
        card.style.display = show ? '' : 'none';
    });
    
    // Mostra/nascondi messaggio "nessun risultato"
    const noResults = document.getElementById('noResults');
    if (noResults) {
        noResults.style.display = visibleCount === 0 ? '' : 'none';
    }
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('typeFilter').value = '';
    document.getElementById('shippingFilter').value = '';
    document.getElementById('incotermFilter').value = '';
    filterTable();
}

// JavaScript per gestione CRUD
let editingId = null;

function validateCode() {
    const codeInput = document.getElementById('code');
    const code = codeInput.value.toUpperCase().trim();
    
    if (code.length >= 2) {
        fetch(`/configurations/system-tables/shipping_terms?check_duplicate=${encodeURIComponent(code)}`)
            .then(response => response.json())
            .then(data => {
                const errorDiv = document.getElementById('codeError');
                if (data.exists) {
                    codeInput.classList.add('is-invalid');
                    errorDiv.textContent = 'Codice già esistente';
                } else {
                    codeInput.classList.remove('is-invalid');
                    errorDiv.textContent = '';
                }
            })
            .catch(error => {
                console.error('Errore validazione codice:', error);
            });
    }
}

function editItem(id) {
    editingId = id;
    
    fetch(`/configurations/system-tables/shipping_terms/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalTitle').textContent = 'Modifica Termine di Porto';
            document.getElementById('saveBtn').innerHTML = '<i class="bi bi-check-lg"></i> Aggiorna Termine Porto';
            
            // Popola i campi
            document.getElementById('code').value = data.code || '';
            document.getElementById('name').value = data.name || '';
            document.getElementById('description').value = data.description || '';
            document.getElementById('incoterm_code').value = data.incoterm_code || '';
            document.getElementById('type').value = data.type || '';
            document.getElementById('sort_order').value = data.sort_order || 0;
            document.getElementById('customer_pays_shipping').checked = data.customer_pays_shipping;
            document.getElementById('active').checked = data.active;
            
            new bootstrap.Modal(document.getElementById('createModal')).show();
        })
        .catch(error => {
            console.error('Errore caricamento dati:', error);
            alert('Errore nel caricamento dei dati per la modifica');
        });
}

function deleteItem(id, name) {
    if (confirm(`Sei sicuro di voler eliminare il termine porto "${name}"?\n\nQuesta azione non può essere annullata.`)) {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('_method', 'DELETE');
        
        fetch(`/configurations/system-tables/shipping_terms/${id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Errore durante l\'eliminazione');
            }
        })
        .catch(error => {
            console.error('Errore eliminazione:', error);
            alert('Errore durante l\'eliminazione del termine porto');
        });
    }
}

function saveShippingTerm() {
    const form = document.getElementById('shippingTermForm');
    const formData = new FormData(form);
    
    // Fix per checkbox
    const activeCheckbox = document.getElementById('active');
    const customerPaysCheckbox = document.getElementById('customer_pays_shipping');
    formData.set('active', activeCheckbox.checked ? '1' : '0');
    formData.set('customer_pays_shipping', customerPaysCheckbox.checked ? '1' : '0');
    
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    let url = '/configurations/system-tables/shipping_terms';
    let method = 'POST';
    
    if (editingId) {
        url = `/configurations/system-tables/shipping_terms/${editingId}`;
        formData.append('_method', 'PUT');
    }
    
    // Reset errori precedenti
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            bootstrap.Modal.getInstance(document.getElementById('createModal')).hide();
            location.reload();
        } else {
            // Gestisci errori di validazione
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    const input = document.getElementById(field);
                    const errorDiv = document.getElementById(field + 'Error');
                    if (input && errorDiv) {
                        input.classList.add('is-invalid');
                        errorDiv.textContent = data.errors[field][0];
                    }
                });
            }
            alert('Errore durante il salvataggio. Controlla i campi evidenziati.');
        }
    })
    .catch(error => {
        console.error('Errore salvataggio:', error);
        alert('Errore durante il salvataggio del termine porto');
    });
}

function resetCreateForm() {
    editingId = null;
    document.getElementById('modalTitle').textContent = 'Nuovo Termine di Porto';
    document.getElementById('saveBtn').innerHTML = '<i class="bi bi-check-lg"></i> Salva Termine Porto';
    document.getElementById('shippingTermForm').reset();
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    document.getElementById('sort_order').value = 0;
    document.getElementById('active').checked = true;
    document.getElementById('customer_pays_shipping').checked = false;
}

function exportData() {
    window.location.href = `/configurations/system-tables/shipping_terms/export`;
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Form submit
    const form = document.getElementById('shippingTermForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            saveShippingTerm();
        });
    }
    
    // Reset form quando si apre modal per creazione
    document.getElementById('createModal').addEventListener('show.bs.modal', function() {
        if (!editingId) {
            resetCreateForm();
        }
    });
    
    // Validazione codice con debounce
    const codeInput = document.getElementById('code');
    if (codeInput) {
        let validationTimeout;
        codeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            clearTimeout(validationTimeout);
            validationTimeout = setTimeout(() => {
                if (this.value.length >= 2) {
                    validateCode();
                }
            }, 500);
        });
    }
});
</script>

@endsection