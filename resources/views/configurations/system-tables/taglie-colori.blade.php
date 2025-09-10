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
    
    .modern-btn:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
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
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
        background: rgba(102, 126, 234, 0.05);
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
    
    .mobile-cards {
        display: none;
    }
    
    .card-mobile {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .card-mobile:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .item-card-header {
        display: flex;
        justify-content: between;
        align-items: flex-start;
        margin-bottom: 1rem;
        border-bottom: 1px solid rgba(226, 232, 240, 0.3);
        padding-bottom: 0.5rem;
    }
    
    .item-card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1a202c;
        margin: 0;
        flex-grow: 1;
    }
    
    .item-card-code {
        font-size: 0.8rem;
        color: #667eea;
        font-weight: 600;
        background: rgba(102, 126, 234, 0.1);
        padding: 2px 8px;
        border-radius: 6px;
        margin-bottom: 0.25rem;
    }
    
    .status-badge {
        font-size: 0.7rem;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: 600;
    }
    
    .item-card-details {
        margin-bottom: 1rem;
    }
    
    .item-detail {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        align-items: center;
    }
    
    .item-detail-label {
        font-size: 0.8rem;
        color: #718096;
        font-weight: 500;
    }
    
    .item-detail-value {
        font-size: 0.9rem;
        color: #1a202c;
        font-weight: 500;
        text-align: right;
        flex-shrink: 0;
        max-width: 60%;
        word-break: break-word;
    }
    
    .item-card-actions {
        display: flex;
        gap: 0.5rem;
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
        padding: 3rem 1rem;
        color: #718096;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .empty-state h4 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #1a202c;
    }
    
    .color-swatch {
        width: 20px;
        height: 20px;
        border: 2px solid #fff;
        border-radius: 4px;
        display: inline-block;
        margin-right: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .color-swatch-mobile {
        width: 25px;
        height: 25px;
        border: 2px solid #fff;
        border-radius: 50%;
        margin-right: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
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
    }
</style>

<div class="management-container">
    <!-- Header Pagina -->
    <div class="management-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <h1 class="management-title">
                    <i class="bi bi-palette2 me-3" style="color: #029D7E; font-size: 2rem;"></i>
                    Taglie e Colori
                </h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('configurations.system-tables.index') }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-arrow-left"></i> Torna Indietro
                </a>
                <button type="button" class="btn modern-btn" style="background: linear-gradient(135deg, #029D7E, #4DC9A5); color: white;" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-lg"></i> Nuova Variante
                </button>
                <button type="button" class="btn btn-warning modern-btn" onclick="exportData()">
                    <i class="bi bi-download"></i> Esporta
                </button>
            </div>
        </div>
    </div>

    <!-- Filtri e Ricerca -->
    <div class="search-filters">
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-control search-input" id="searchInput" placeholder="Cerca per codice, nome, colore..." onkeyup="filterTable()">
            </div>
            <div class="col-md-2">
                <select class="form-select filter-select" id="typeFilter" onchange="filterTable()">
                    <option value="">Tutti i tipi</option>
                    <option value="color">Colori</option>
                    <option value="size">Taglie</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select filter-select" id="categoryFilter" onchange="filterTable()">
                    <option value="">Tutte le categorie</option>
                    <option value="EU">Sistema EU</option>
                    <option value="US">Sistema US</option>
                    <option value="UK">Sistema UK</option>
                    <option value="NUMERIC">Numerico</option>
                    <option value="LETTER">Letterale</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select filter-select" id="statusFilter" onchange="filterTable()">
                    <option value="">Tutti gli stati</option>
                    <option value="1">Attivi</option>
                    <option value="0">Inattivi</option>
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
            <table class="modern-table" id="dataTable">
                <thead>
                    <tr>
                        <th style="width: 12%">Codice</th>
                        <th style="width: 20%">Nome</th>
                        <th style="width: 10%">Tipo</th>
                        <th style="width: 15%">Dettagli</th>
                        <th style="width: 12%">Sistema</th>
                        <th style="width: 10%">Prezzo</th>
                        <th style="width: 8%">Utilizzi</th>
                        <th style="width: 8%">Stato</th>
                        <th style="width: 5%">Azioni</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse ($items as $item)
                        <tr class="table-row" 
                            data-type="{{ $item->type }}" 
                            data-category="{{ $item->size_category }}" 
                            data-active="{{ $item->active ? '1' : '0' }}">
                            <td>
                                <span class="fw-bold text-primary">{{ $item->code }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($item->is_color && $item->hex_value)
                                        <div class="color-swatch" style="background:{{ $item->hex_value }};"></div>
                                    @endif
                                    <span>{{ $item->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $item->is_color ? 'bg-danger' : 'bg-info' }}">
                                    {{ $item->type_translated }}
                                </span>
                            </td>
                            <td>
                                @if($item->is_color)
                                    <small>
                                        @if($item->hex_value)
                                            <span class="text-muted">{{ $item->hex_value }}</span><br>
                                        @endif
                                        @if($item->pantone_code)
                                            <span class="badge bg-secondary">{{ $item->pantone_code }}</span>
                                        @endif
                                    </small>
                                @else
                                    <small>
                                        @foreach($item->international_sizes as $system => $size)
                                            @if($size !== 'N/A')
                                                <span class="badge bg-light text-dark me-1">{{ $system }}: {{ $size }}</span>
                                            @endif
                                        @endforeach
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if($item->is_size)
                                    <small class="text-muted">{{ $item->size_category_translated }}</small>
                                @else
                                    <small class="text-muted">-</small>
                                @endif
                            </td>
                            <td>
                                @if($item->price_modifier && $item->price_modifier != 0)
                                    <span class="badge {{ $item->price_modifier > 0 ? 'bg-success' : 'bg-warning' }}">
                                        {{ $item->formatted_price_modifier }}
                                    </span>
                                @else
                                    <small class="text-muted">-</small>
                                @endif
                            </td>
                            <td>
                                <span class="me-2">{{ $item->usage_count }}</span>
                                @if($item->usage_count > 10)
                                    <i class="bi bi-star-fill text-warning"></i>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $item->active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $item->active ? 'Attivo' : 'Inattivo' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button type="button" class="action-btn view" onclick="viewItem({{ $item->id }})" title="Visualizza">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button type="button" class="action-btn edit" onclick="editItem({{ $item->id }})" title="Modifica">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="action-btn delete" onclick="deleteItem({{ $item->id }}, '{{ $item->name }}')" title="Elimina">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noResults">
                            <td colspan="9" class="text-center">
                                <div class="empty-state">
                                    <i class="bi bi-palette2"></i>
                                    <h4>Nessuna variante trovata</h4>
                                    <p>Non sono presenti taglie o colori</p>
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
            <div class="item-card mobile-item-row card-mobile" 
                 data-type="{{ $item->type }}" 
                 data-category="{{ $item->size_category }}" 
                 data-active="{{ $item->active ? '1' : '0' }}">
                
                <div class="item-card-header">
                    <h3 class="item-card-title">
                        @if($item->is_color && $item->hex_value)
                            <div class="color-swatch-mobile d-inline-block" style="background:{{ $item->hex_value }};"></div>
                        @endif
                        {{ $item->name }}
                    </h3>
                    <div class="d-flex flex-column align-items-end">
                        <span class="item-card-code">{{ $item->code }}</span>
                        <span class="status-badge badge {{ $item->active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $item->active ? 'Attivo' : 'Inattivo' }}
                        </span>
                    </div>
                </div>
                
                <div class="item-card-details">
                    <div class="item-detail">
                        <span class="item-detail-label">Tipo</span>
                        <span class="item-detail-value">
                            <span class="badge {{ $item->is_color ? 'bg-danger' : 'bg-info' }}">
                                {{ $item->type_translated }}
                            </span>
                        </span>
                    </div>
                    
                    @if($item->is_color)
                        @if($item->hex_value)
                            <div class="item-detail">
                                <span class="item-detail-label">Colore</span>
                                <span class="item-detail-value">{{ $item->hex_value }}</span>
                            </div>
                        @endif
                        @if($item->pantone_code)
                            <div class="item-detail">
                                <span class="item-detail-label">Pantone</span>
                                <span class="item-detail-value">{{ $item->pantone_code }}</span>
                            </div>
                        @endif
                    @else
                        <div class="item-detail">
                            <span class="item-detail-label">Sistema</span>
                            <span class="item-detail-value">{{ $item->size_category_translated }}</span>
                        </div>
                        @if(count(array_filter($item->international_sizes, fn($size) => $size !== 'N/A')) > 0)
                            <div class="item-detail">
                                <span class="item-detail-label">Equivalenze</span>
                                <span class="item-detail-value">
                                    @foreach($item->international_sizes as $system => $size)
                                        @if($size !== 'N/A')
                                            <span class="badge bg-light text-dark me-1">{{ $system }}: {{ $size }}</span>
                                        @endif
                                    @endforeach
                                </span>
                            </div>
                        @endif
                    @endif
                    
                    <div class="item-detail">
                        <span class="item-detail-label">Utilizzi</span>
                        <span class="item-detail-value">
                            {{ $item->usage_count }}
                            @if($item->usage_count > 10)
                                <i class="bi bi-star-fill text-warning ms-1"></i>
                            @endif
                        </span>
                    </div>
                    
                    @if($item->price_modifier && $item->price_modifier != 0)
                        <div class="item-detail">
                            <span class="item-detail-label">Modif. Prezzo</span>
                            <span class="item-detail-value">{{ $item->formatted_price_modifier }}</span>
                        </div>
                    @endif
                </div>
                
                <div class="item-card-actions">
                    <button type="button" class="mobile-action-btn view" onclick="viewItem({{ $item->id }})">
                        <i class="bi bi-eye"></i>
                        <span>Visualizza</span>
                    </button>
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
                <i class="bi bi-palette2"></i>
                <h4>Nessuna variante trovata</h4>
                <p>Non sono presenti taglie o colori nel sistema</p>
            </div>
        @endforelse
    </div>

    <!-- Paginazione -->
    @if($items->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $items->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Modal Creazione/Modifica -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #029D7E, #4DC9A5); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title">
                    <i class="bi bi-plus-lg me-2"></i>
                    <span id="modalTitle">Nuova Variante</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="variantForm" method="POST" action="{{ route('configurations.system-tables.store', 'size_colors') }}">
                @csrf
                <input type="hidden" id="variantId" name="variant_id">
                <input type="hidden" id="formMethod" name="_method">
                
                <div class="modal-body" style="padding: 2rem;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Codice *</label>
                            <input type="text" name="code" id="code" class="form-control" required 
                                   placeholder="es: RED_001, SIZE_M" maxlength="20">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nome *</label>
                            <input type="text" name="name" id="name" class="form-control" required 
                                   placeholder="es: Rosso Ferrari, Taglia Media" maxlength="100">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-semibold">Descrizione</label>
                            <textarea name="description" id="description" class="form-control" rows="2" 
                                      placeholder="Descrizione dettagliata..." maxlength="1000"></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tipo *</label>
                            <select name="type" id="type" class="form-select" required onchange="toggleTypeFields()">
                                <option value="">Seleziona tipo</option>
                                <option value="color">Colore</option>
                                <option value="size">Taglia</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ordine</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control" 
                                   min="0" max="9999" placeholder="0">
                        </div>
                    </div>

                    <!-- Campi colore -->
                    <div id="colorFields" style="display: none; margin-top: 2rem;">
                        <hr>
                        <h6 class="text-danger mb-3">
                            <i class="bi bi-palette me-2"></i>Configurazione Colore
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Colore Esadecimale *</label>
                                <div class="input-group">
                                    <input type="color" name="hex_value" id="hex_value" class="form-control form-control-color" style="max-width: 60px;">
                                    <input type="text" id="hex_display" class="form-control" placeholder="#FF0000" maxlength="7">
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Codice Pantone</label>
                                <input type="text" name="pantone_code" id="pantone_code" class="form-control" 
                                       placeholder="es: PANTONE 18-1763 TPX" maxlength="20">
                            </div>
                        </div>
                    </div>

                    <!-- Campi taglia -->
                    <div id="sizeFields" style="display: none; margin-top: 2rem;">
                        <hr>
                        <h6 class="text-info mb-3">
                            <i class="bi bi-rulers me-2"></i>Configurazione Taglia
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Sistema Taglie *</label>
                                <select name="size_category" id="size_category" class="form-select">
                                    <option value="">Seleziona sistema</option>
                                    <option value="EU">Sistema Europeo</option>
                                    <option value="US">Sistema Americano</option>
                                    <option value="UK">Sistema Britannico</option>
                                    <option value="NUMERIC">Numerico</option>
                                    <option value="LETTER">Letterale (XS-XXL)</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Taglia EU</label>
                                <input type="text" name="eu_size" id="eu_size" class="form-control" 
                                       placeholder="es: 42, M" maxlength="10">
                            </div>
                            
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Taglia US</label>
                                <input type="text" name="us_size" id="us_size" class="form-control" 
                                       placeholder="es: 8, M" maxlength="10">
                            </div>
                        </div>
                    </div>

                    <!-- Campi comuni -->
                    <div style="margin-top: 2rem;">
                        <hr>
                        <h6 class="text-warning mb-3">
                            <i class="bi bi-gear me-2"></i>Configurazioni Business
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Modificatore Prezzo (€)</label>
                                <input type="number" name="price_modifier" id="price_modifier" class="form-control" 
                                       step="0.01" placeholder="0.00">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Stock Predefinito</label>
                                <input type="number" name="default_stock_level" id="default_stock_level" class="form-control" 
                                       min="0" max="99999" placeholder="0">
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" name="seasonal" id="seasonal" class="form-check-input" value="1">
                                    <label class="form-check-label" for="seasonal">Variante stagionale</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-check">
                                    <input type="checkbox" name="requires_approval" id="requires_approval" class="form-check-input" value="1">
                                    <label class="form-check-label" for="requires_approval">Richiede approvazione per ordini</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer" style="padding: 1.5rem 2rem; border-top: 1px solid #e9ecef;">
                    <button type="button" class="btn btn-secondary modern-btn" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Annulla
                    </button>
                    <button type="submit" class="btn btn-success modern-btn">
                        <i class="bi bi-check-lg"></i> Salva
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Filtro tabella e mobile cards
function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const typeFilter = document.getElementById('typeFilter').value;
    const categoryFilter = document.getElementById('categoryFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    
    // Filtro tabella desktop
    const rows = document.querySelectorAll('#tableBody tr:not(#noResults)');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const code = row.cells[0].textContent.toLowerCase();
        const name = row.cells[1].textContent.toLowerCase();
        const type = row.dataset.type;
        const category = row.dataset.category;
        const active = row.dataset.active;
        
        const matchesSearch = code.includes(searchTerm) || name.includes(searchTerm);
        const matchesType = !typeFilter || type === typeFilter;
        const matchesCategory = !categoryFilter || category === categoryFilter;
        const matchesStatus = !statusFilter || active === statusFilter;
        
        const shouldShow = matchesSearch && matchesType && matchesCategory && matchesStatus;
        
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
        const cardText = card.textContent.toLowerCase();
        const type = card.dataset.type;
        const category = card.dataset.category;
        const active = card.dataset.active;
        
        const matchesSearch = cardText.includes(searchTerm);
        const matchesType = !typeFilter || type === typeFilter;
        const matchesCategory = !categoryFilter || category === categoryFilter;
        const matchesStatus = !statusFilter || active === statusFilter;
        
        const shouldShow = matchesSearch && matchesType && matchesCategory && matchesStatus;
        
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
    document.getElementById('categoryFilter').value = '';
    document.getElementById('statusFilter').value = '';
    filterTable();
}

// Gestione tipo campi dinamici
function toggleTypeFields() {
    const type = document.getElementById('type').value;
    const colorFields = document.getElementById('colorFields');
    const sizeFields = document.getElementById('sizeFields');
    const hexValue = document.getElementById('hex_value');
    const sizeCategory = document.getElementById('size_category');
    
    if (type === 'color') {
        colorFields.style.display = 'block';
        sizeFields.style.display = 'none';
        hexValue.required = true;
        sizeCategory.required = false;
    } else if (type === 'size') {
        colorFields.style.display = 'none';
        sizeFields.style.display = 'block';
        hexValue.required = false;
        sizeCategory.required = true;
    } else {
        colorFields.style.display = 'none';
        sizeFields.style.display = 'none';
        hexValue.required = false;
        sizeCategory.required = false;
    }
}

// Sincronizzazione color picker
document.addEventListener('DOMContentLoaded', function() {
    const colorPicker = document.getElementById('hex_value');
    const hexDisplay = document.getElementById('hex_display');
    
    if (colorPicker && hexDisplay) {
        colorPicker.addEventListener('input', function() {
            hexDisplay.value = this.value.toUpperCase();
        });
        
        hexDisplay.addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                colorPicker.value = this.value;
            }
        });
    }
});

// CRUD Operations
function viewItem(id) {
    // Implementa visualizzazione dettagli
    alert('Visualizza variante ID: ' + id);
}

function editItem(id) {
    fetch(`/configurations/system-tables/size_colors/${id}`)
        .then(response => response.json())
        .then(item => {
            document.getElementById('modalTitle').textContent = 'Modifica Variante';
            document.getElementById('variantId').value = item.id;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('variantForm').action = `/configurations/system-tables/size_colors/${id}`;
            
            // Popola campi
            document.getElementById('code').value = item.code || '';
            document.getElementById('name').value = item.name || '';
            document.getElementById('description').value = item.description || '';
            document.getElementById('type').value = item.type || '';
            document.getElementById('sort_order').value = item.sort_order || '';
            
            if (item.hex_value) {
                document.getElementById('hex_value').value = item.hex_value;
                document.getElementById('hex_display').value = item.hex_value;
            }
            document.getElementById('pantone_code').value = item.pantone_code || '';
            document.getElementById('size_category').value = item.size_category || '';
            document.getElementById('eu_size').value = item.eu_size || '';
            document.getElementById('us_size').value = item.us_size || '';
            document.getElementById('price_modifier').value = item.price_modifier || '';
            document.getElementById('default_stock_level').value = item.default_stock_level || '';
            document.getElementById('seasonal').checked = Boolean(item.seasonal);
            document.getElementById('requires_approval').checked = Boolean(item.requires_approval);
            
            toggleTypeFields();
            new bootstrap.Modal(document.getElementById('createModal')).show();
        })
        .catch(error => {
            console.error('Errore:', error);
            alert('Errore nel caricamento del record');
        });
}

function deleteItem(id, name) {
    if (confirm(`Sei sicuro di voler eliminare "${name}"?`)) {
        fetch(`/configurations/system-tables/size_colors/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Errore nell\'eliminazione');
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            alert('Errore nell\'eliminazione');
        });
    }
}

function exportData() {
    // Implementa export
    alert('Export in sviluppo');
}

// Reset modal
document.getElementById('createModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('variantForm').reset();
    document.getElementById('modalTitle').textContent = 'Nuova Variante';
    document.getElementById('variantId').value = '';
    document.getElementById('formMethod').value = '';
    document.getElementById('variantForm').action = '{{ route("configurations.system-tables.store", "size_colors") }}';
    toggleTypeFields();
});
</script>
@endsection