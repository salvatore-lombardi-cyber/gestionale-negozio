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
    
    /* Gradiente GREEN standard per tutti i button */
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
    
    /* Tabella moderna - stile banche */
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
        background: rgba(72, 202, 228, 0.05);
        transform: scale(1.01);
    }
    
    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* Badge specifici per banche */
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
    
    .bank-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
        text-transform: uppercase;
    }
    
    .bank-italian {
        background-color: #d1ecf1;
        color: #0c5460;
    }
    
    .bank-foreign {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .bank-info {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }
    
    .bank-code {
        font-family: 'Courier New', monospace;
        background: rgba(72, 202, 228, 0.1);
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.8rem;
        color: #023e8a;
        font-weight: 600;
    }
    
    .bank-abi {
        font-family: 'Courier New', monospace;
        background: rgba(2, 62, 138, 0.1);
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.7rem;
        color: #023e8a;
    }
    
    .bank-swift {
        font-family: 'Courier New', monospace;
        background: rgba(72, 202, 228, 0.15);
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.7rem;
        color: #0c5460;
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
    
    /* Allineamento perfetto pulsanti azioni */
    td .action-btn {
        vertical-align: middle;
        line-height: 1;
    }
    
    /* Mobile Cards - Nasconde tabella, mostra card */
    .mobile-cards {
        display: none;
    }
    
    .bank-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .bank-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .bank-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .bank-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
        flex: 1;
        min-width: 0;
    }
    
    .bank-card-codes {
        display: flex;
        flex-direction: column;
        align-items: end;
        gap: 0.3rem;
        flex-shrink: 0;
    }
    
    .bank-card-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .bank-detail {
        display: flex;
        flex-direction: column;
    }
    
    .bank-detail-label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 0.2rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .bank-detail-value {
        font-weight: 500;
        color: #2d3748;
    }
    
    .bank-card-actions {
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
        
        .bank-card {
            padding: 1rem;
        }
        
        .bank-card-details {
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
                    <i class="bi bi-bank me-3" style="color: #48cae4; font-size: 2rem;"></i>
                    Banche
                </h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('configurations.system-tables.index') }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-arrow-left"></i> Torna Indietro
                </a>
                <button type="button" class="btn btn-success modern-btn" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-lg"></i> Nuova Banca
                </button>
            </div>
        </div>
    </div>

    <!-- Filtri e ricerca -->
    <div class="search-filters">
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-control search-input" id="searchInput" placeholder="Cerca per nome, codice o ABI..." onkeyup="filterTable()">
            </div>
            <div class="col-md-3">
                <select class="form-select filter-select" id="typeFilter" onchange="filterTable()">
                    <option value="">Tutte le banche</option>
                    <option value="italian">Banche Italiane</option>
                    <option value="foreign">Banche Estere</option>
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
            <table class="modern-table" id="banksTable">
                <thead>
                    <tr>
                        <th style="width: 12%">Codice</th>
                        <th style="width: 20%">Nome Banca</th>
                        <th style="width: 15%">ABI/SWIFT</th>
                        <th style="width: 10.5%">Localizzazione</th>
                        <th style="width: 10%">Tipo</th>
                        <th style="width: 10%">Stato</th>
                        <th style="width: 22.5%; text-align: center;">Azioni</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($items ?? [] as $item)
                        <tr data-type="{{ $item->is_italian ? 'italian' : 'foreign' }}" data-status="{{ $item->active ? '1' : '0' }}">
                            <td>
                                <div class="bank-info">
                                    <span class="bank-code">{{ $item->code }}</span>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $item->name }}</strong>
                                    @if($item->description)
                                        <br><small class="text-muted">{{ $item->description }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="bank-info">
                                    @if($item->abi_code)
                                        <span class="bank-abi">ABI: {{ $item->abi_code }}</span>
                                    @endif
                                    @if($item->bic_swift)
                                        <span class="bank-swift">{{ $item->bic_swift }}</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($item->city)
                                    <strong>{{ $item->city }}</strong><br>
                                @endif
                                <span class="text-muted">{{ $item->country }}</span>
                            </td>
                            <td>
                                <span class="bank-badge {{ $item->is_italian ? 'bank-italian' : 'bank-foreign' }}">
                                    {{ $item->is_italian ? 'Italiana' : 'Estera' }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge {{ $item->active ? 'status-active' : 'status-inactive' }}">
                                    {{ $item->active ? 'Attivo' : 'Inattivo' }}
                                </span>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
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
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="bi bi-bank"></i>
                                    <h4>Nessuna banca configurata</h4>
                                    <p>Inizia creando la prima banca per il tuo sistema.</p>
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
            <div class="bank-card mobile-bank-row"
                 data-type="{{ $item->is_italian ? 'italian' : 'foreign' }}" 
                 data-status="{{ $item->active ? '1' : '0' }}">
                
                <div class="bank-card-header">
                    <h3 class="bank-card-title">{{ $item->name }}</h3>
                    <div class="bank-card-codes">
                        <span class="bank-code">{{ $item->code }}</span>
                        <div class="d-flex flex-column align-items-end gap-1">
                            @if($item->abi_code)
                                <span class="bank-abi">ABI: {{ $item->abi_code }}</span>
                            @endif
                            @if($item->bic_swift)
                                <span class="bank-swift">{{ $item->bic_swift }}</span>
                            @endif
                        </div>
                        <div class="mt-1">
                            <span class="status-badge {{ $item->active ? 'status-active' : 'status-inactive' }}" style="font-size: 0.7rem;">
                                {{ $item->active ? 'Attivo' : 'Inattivo' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="bank-card-details">
                    <div class="bank-detail">
                        <span class="bank-detail-label">Tipo Banca</span>
                        <span class="bank-detail-value">
                            <span class="bank-badge {{ $item->is_italian ? 'bank-italian' : 'bank-foreign' }}">
                                {{ $item->is_italian ? 'Italiana' : 'Estera' }}
                            </span>
                        </span>
                    </div>
                    <div class="bank-detail">
                        <span class="bank-detail-label">Localizzazione</span>
                        <span class="bank-detail-value">
                            {{ $item->city ? $item->city . ', ' : '' }}{{ $item->country }}
                        </span>
                    </div>
                </div>
                
                @if($item->description)
                    <div class="bank-detail mb-3">
                        <span class="bank-detail-label">Descrizione</span>
                        <span class="bank-detail-value">{{ $item->description }}</span>
                    </div>
                @endif
                
                <div class="bank-card-actions">
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
                <i class="bi bi-bank"></i>
                <h4>Nessuna banca configurata</h4>
                <p>Inizia creando la prima banca per il tuo sistema.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Creazione -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #029D7E, #4DC9A5); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title" id="createModalLabel">
                    <i class="bi bi-plus-lg me-2"></i>Nuova Banca
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createForm" onsubmit="submitForm(event, 'create')">
                @csrf
                <div class="modal-body" style="padding: 2rem;">
                    <div class="row g-3">
                        <!-- Prima riga: Informazioni base -->
                        <div class="col-md-4">
                            <label for="code" class="form-label fw-bold">Codice Banca *</label>
                            <div class="position-relative">
                                <input type="text" class="form-control search-input" id="code" name="code" required maxlength="10" style="text-transform: uppercase;">
                                <div id="codeStatus" class="position-absolute" style="top: 50%; right: 10px; transform: translateY(-50%); display: none;">
                                    <span id="codeLoading" class="text-warning">⏳</span>
                                    <span id="codeOk" class="text-success" style="display: none;">✅</span>
                                    <span id="codeDuplicate" class="text-danger" style="display: none;">❌</span>
                                </div>
                            </div>
                            <div class="form-text">Massimo 10 caratteri, solo lettere maiuscole, numeri, _ e -</div>
                            <div id="codeMessage" class="form-text text-danger" style="display: none;"></div>
                        </div>
                        <div class="col-md-4">
                            <label for="name" class="form-label fw-bold">Nome Banca *</label>
                            <input type="text" class="form-control search-input" id="name" name="name" required maxlength="100">
                            <div class="form-text">Nome ufficiale della banca</div>
                        </div>
                        <div class="col-md-4">
                            <label for="description" class="form-label fw-bold">Descrizione</label>
                            <input type="text" class="form-control search-input" id="description" name="description" maxlength="255">
                            <div class="form-text">Descrizione aggiuntiva (opzionale)</div>
                        </div>

                        <!-- Seconda riga: Codici bancari -->
                        <div class="col-md-6">
                            <label for="abi_code" class="form-label fw-bold">
                                Codice ABI
                                <i class="bi bi-info-circle ms-1" 
                                   data-bs-toggle="tooltip" 
                                   data-bs-placement="top" 
                                   title="Associazione Bancaria Italiana - solo per banche italiane"></i>
                            </label>
                            <input type="text" class="form-control search-input" id="abi_code" name="abi_code" maxlength="5" pattern="\d{5}">
                            <div class="form-text">5 cifre per banche italiane (es: 03069)</div>
                        </div>
                        <div class="col-md-6">
                            <label for="bic_swift" class="form-label fw-bold">
                                Codice BIC/SWIFT
                                <i class="bi bi-info-circle ms-1" 
                                   data-bs-toggle="tooltip" 
                                   data-bs-placement="top" 
                                   title="Bank Identifier Code per transazioni internazionali"></i>
                            </label>
                            <input type="text" class="form-control search-input" id="bic_swift" name="bic_swift" maxlength="11" style="text-transform: uppercase;">
                            <div class="form-text">8-11 caratteri per transazioni internazionali (es: BCITITMM)</div>
                        </div>

                        <!-- Terza riga: Localizzazione -->
                        <div class="col-md-6">
                            <label for="city" class="form-label fw-bold">Città</label>
                            <input type="text" class="form-control search-input" id="city" name="city" maxlength="100">
                            <div class="form-text">Città sede principale</div>
                        </div>
                        <div class="col-md-6">
                            <label for="country" class="form-label fw-bold">Paese</label>
                            <input type="text" class="form-control search-input" id="country" name="country" maxlength="100" value="Italia">
                            <div class="form-text">Paese di origine</div>
                        </div>

                        <!-- Quarta riga: Indirizzo completo -->
                        <div class="col-12">
                            <label for="address" class="form-label fw-bold">Indirizzo</label>
                            <textarea class="form-control search-input" id="address" name="address" rows="2" maxlength="500"></textarea>
                            <div class="form-text">Indirizzo completo sede principale (opzionale)</div>
                        </div>

                        <!-- Quinta riga: Contatti -->
                        <div class="col-md-4">
                            <label for="phone" class="form-label fw-bold">Telefono</label>
                            <input type="text" class="form-control search-input" id="phone" name="phone" maxlength="50">
                            <div class="form-text">Numero di telefono principale</div>
                        </div>
                        <div class="col-md-4">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" class="form-control search-input" id="email" name="email" maxlength="100">
                            <div class="form-text">Email di contatto</div>
                        </div>
                        <div class="col-md-4">
                            <label for="website" class="form-label fw-bold">Sito Web</label>
                            <input type="url" class="form-control search-input" id="website" name="website" maxlength="255">
                            <div class="form-text">URL sito web ufficiale</div>
                        </div>

                        <!-- Sesta riga: Opzioni -->
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_italian" name="is_italian" checked>
                                <label class="form-check-label fw-bold" for="is_italian">
                                    Banca Italiana
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="active" name="active" checked>
                                <label class="form-check-label fw-bold" for="active">
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
                        <i class="bi bi-check-lg"></i> Salva Banca
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Variabili globali
let allItems = [];

// Controllo duplicati codice
async function checkCodeDuplicate(code) {
    if (!code || code.trim() === '') return false;
    
    try {
        const response = await fetch(`{{ route("configurations.system-tables.index", "banks") }}?check_duplicate=${encodeURIComponent(code)}`, {
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

// Filtri e ricerca
function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const typeFilter = document.getElementById('typeFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    
    // Filtro per tabella desktop
    const rows = document.querySelectorAll('#tableBody tr:not(#noResults)');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const code = row.children[0].textContent.toLowerCase();
        const name = row.children[1].textContent.toLowerCase(); 
        const abi = row.children[2].textContent.toLowerCase();
        const type = row.dataset.type;
        const status = row.dataset.status;
        
        const matchesSearch = code.includes(searchTerm) || name.includes(searchTerm) || abi.includes(searchTerm);
        const matchesType = !typeFilter || type === typeFilter;
        const matchesStatus = !statusFilter || status === statusFilter;
        
        if (matchesSearch && matchesType && matchesStatus) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Filtro per mobile cards
    const mobileCards = document.querySelectorAll('.mobile-bank-row');
    let mobileVisibleCount = 0;
    
    mobileCards.forEach(card => {
        const name = card.querySelector('.bank-card-title').textContent.toLowerCase();
        const code = card.querySelector('.bank-code').textContent.toLowerCase();
        const type = card.dataset.type;
        const status = card.dataset.status;
        
        const matchesSearch = code.includes(searchTerm) || name.includes(searchTerm);
        const matchesType = !typeFilter || type === typeFilter;
        const matchesStatus = !statusFilter || status === statusFilter;
        
        if (matchesSearch && matchesType && matchesStatus) {
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
    document.getElementById('typeFilter').value = '';
    document.getElementById('statusFilter').value = '';
    filterTable();
}

// CRUD Operations
async function submitForm(event, action) {
    event.preventDefault();
    
    const form = event.target;
    
    // Validazione codice duplicato per nuovi inserimenti
    if (action === 'create') {
        const code = form.code.value.trim().toUpperCase();
        if (await checkCodeDuplicate(code)) {
            alert('⚠️ Errore: Il codice "' + code + '" esiste già!\nScegli un codice diverso.');
            form.code.focus();
            return;
        }
    }
    
    const formData = new FormData(form);
    
    // Converti checkbox in valori booleani
    formData.set('is_italian', form.is_italian.checked ? '1' : '0');
    formData.set('active', form.active.checked ? '1' : '0');
    
    const url = action === 'create' 
        ? '{{ route("configurations.system-tables.store", "banks") }}'
        : `{{ route("configurations.system-tables.update", ["table" => "banks", "id" => ":id"]) }}`.replace(':id', form.dataset.itemId);
    
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
                const code = errorMessage.match(/'([^']+)'/)?.[1] || 'sconosciuto';
                errorMessage = `⚠️ Codice "${code}" già esistente!\nScegli un codice diverso.`;
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
    // Implementa visualizzazione dettaglio
    window.open(`{{ route("configurations.system-tables.show", "banks") }}/${id}`, '_blank');
}

function editItem(id) {
    // Carica dati per modifica - implementa quando necessario
    alert('Funzione di modifica in implementazione');
}

function deleteItem(id) {
    if (confirm('Sei sicuro di voler eliminare questa banca?')) {
        fetch(`{{ route("configurations.system-tables.destroy", ["table" => "banks", "id" => ":id"]) }}`.replace(':id', id), {
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
    document.getElementById('createModalLabel').innerHTML = '<i class="bi bi-plus-lg me-2"></i>Nuova Banca';
});

// Validazione input codice con controllo duplicati in tempo reale
let checkTimeout;
document.getElementById('code').addEventListener('input', function(e) {
    e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9_-]/g, '');
    
    // Controllo duplicati in tempo reale
    const code = e.target.value.trim();
    const statusDiv = document.getElementById('codeStatus');
    const messageDiv = document.getElementById('codeMessage');
    
    // Reset stati
    document.getElementById('codeLoading').style.display = 'none';
    document.getElementById('codeOk').style.display = 'none';
    document.getElementById('codeDuplicate').style.display = 'none';
    messageDiv.style.display = 'none';
    statusDiv.style.display = 'none';
    
    if (code.length >= 3) {
        // Mostra loading
        statusDiv.style.display = 'block';
        document.getElementById('codeLoading').style.display = 'inline';
        
        // Debounce per evitare troppe chiamate
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