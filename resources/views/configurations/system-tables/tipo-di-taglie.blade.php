@extends('layouts.app')

@section('content')

<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        
        min-height: 100vh;
    }
    
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
    font-size: 2rem;
    font-weight: 700;
    background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin: 0;
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
    background: linear-gradient(135deg, #a7f070, #00d2d3);
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

.stat-icon.clothing {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.stat-icon.shoes {
    background: linear-gradient(135deg, #dc3545, #e74c3c);
}

.stat-icon.children {
    background: linear-gradient(135deg, #ff6b6b, #feca57);
}

.stat-icon.accessories {
    background: linear-gradient(135deg, #9c27b0, #7b1fa2);
}

.stat-icon.cm {
    background: linear-gradient(135deg, #2196f3, #1976d2);
}

.stat-icon.inches {
    background: linear-gradient(135deg, #ff9800, #f57c00);
}

.stat-icon.mixed {
    background: linear-gradient(135deg, #ffc107, #ff8f00);
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
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
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
    background: rgba(167, 240, 112, 0.05);
    transform: scale(1.01);
}

.modern-table tbody tr:last-child td {
    border-bottom: none;
}

.desktop-actions {
    display: flex;
    gap: 0.5rem;
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

.badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge.active {
    background: linear-gradient(135deg, #029D7E, #4DC9A5);
    color: white;
}

.badge.inactive {
    background: linear-gradient(135deg, #6c757d, #545b62);
    color: white;
}

.badge.category {
    background: linear-gradient(135deg, #a7f070, #00d2d3);
    color: white;
}

.mobile-cards {
    display: none;
}

.mobile-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.mobile-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.mobile-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
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
                    <i class="bi bi-bar-chart me-3" style="color: #029D7E; font-size: 2rem;"></i>
                    Tipo di Taglie
                </h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('configurations.system-tables.index') }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-arrow-left"></i> Torna Indietro
                </a>
                <button type="button" class="btn btn-success modern-btn" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-lg"></i> Nuovo Tipo di Taglia
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiche -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-bar-chart"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total'] ?? 0 }}</h3>
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
                    <h3>{{ $stats['active'] ?? 0 }}</h3>
                    <p>Attivi</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon clothing">
                    <i class="bi bi-person"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['clothing'] ?? 0 }}</h3>
                    <p>Abbigliamento</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon shoes">
                    <i class="bi bi-bootstrap"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['shoes'] ?? 0 }}</h3>
                    <p>Calzature</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon children">
                    <i class="bi bi-heart"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['children'] ?? 0 }}</h3>
                    <p>Bambini</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon inactive">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['inactive'] ?? 0 }}</h3>
                    <p>Inattivi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtri -->
    <div class="search-filters">
        <div class="row g-3">
            <div class="col-md-6">
                <input type="text" class="form-control search-input" id="searchInput" placeholder="Cerca tipo di taglie..." onkeyup="filterTable()">
            </div>
            <div class="col-md-4">
                <select class="form-select filter-select" id="statusFilter" onchange="filterTable()">
                    <option value="">Tutti gli Stati</option>
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
            <table class="modern-table" id="sizeTypeTable">
                <thead>
                    <tr>
                        <th>CODICE</th>
                        <th>NOME</th>
                        <th>CATEGORIA</th>
                        <th>UNITÀ MISURA</th>
                        <th>DESCRIZIONE</th>
                        <th>STATO</th>
                        <th width="120">AZIONI</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($items as $item)
                        <tr data-active="{{ $item->active ? '1' : '0' }}">
                            <td>
                                <strong class="text-primary">{{ $item->code }}</strong>
                            </td>
                            <td>
                                <strong>{{ $item->name }}</strong>
                            </td>
                            <td>
                                @if($item->category)
                                    <span class="badge category">
                                        {{ $item->formatted_category }}
                                    </span>
                                @else
                                    <small class="text-muted">Non specificato</small>
                                @endif
                            </td>
                            <td>
                                @if($item->measurement_unit)
                                    <span class="badge bg-info">
                                        {{ $item->formatted_measurement_unit }}
                                    </span>
                                @else
                                    <small class="text-muted">Non specificato</small>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $item->description ?? 'Nessuna descrizione' }}
                                </small>
                            </td>
                            <td>
                                @if($item->active)
                                    <span class="badge active">
                                        <i class="bi bi-check-circle me-1"></i>Attivo
                                    </span>
                                @else
                                    <span class="badge inactive">
                                        <i class="bi bi-x-circle me-1"></i>Inattivo
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="action-btn edit" title="Modifica tipo" onclick="loadEditData({{ $item->id }}, '{{ $item->code }}', '{{ $item->name }}', '{{ addslashes($item->description ?? '') }}', '{{ $item->category ?? '' }}', '{{ $item->measurement_unit ?? '' }}', {{ $item->active ? 'true' : 'false' }}, {{ $item->sort_order ?? 0 }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="action-btn delete" title="Elimina tipo" onclick="confirmDelete({{ $item->id }}, '{{ $item->code }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <h4>Nessun tipo di taglia trovato</h4>
                                <p>Non sono ancora stati creati tipi di taglie o nessuno corrisponde ai filtri selezionati.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Cards Mobile -->
    <div class="mobile-cards">
        @forelse($items as $item)
            <div class="mobile-card" data-active="{{ $item->active ? '1' : '0' }}">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h6 class="mb-1">
                            <strong class="text-primary">{{ $item->code }}</strong> - {{ $item->name }}
                        </h6>
                        <p class="mb-2 text-muted small">{{ $item->description ?? 'Nessuna descrizione' }}</p>
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            @if($item->category)
                                <span class="badge category small">{{ $item->formatted_category }}</span>
                            @endif
                            @if($item->measurement_unit)
                                <span class="badge bg-info small">{{ $item->formatted_measurement_unit }}</span>
                            @endif
                            @if($item->active)
                                <span class="badge active small">Attivo</span>
                            @else
                                <span class="badge inactive small">Inattivo</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mobile-actions">
                            <button type="button" 
                                    class="mobile-action-btn edit" 
                                    onclick="loadEditData({{ $item->id }}, '{{ $item->code }}', '{{ $item->name }}', '{{ addslashes($item->description ?? '') }}', '{{ $item->category ?? '' }}', '{{ $item->measurement_unit ?? '' }}', {{ $item->active ? 'true' : 'false' }}, {{ $item->sort_order ?? 0 }})">
                                <i class="bi bi-pencil"></i>
                                <span>Modifica</span>
                            </button>
                            <button type="button" 
                                    class="mobile-action-btn delete" 
                                    onclick="confirmDelete({{ $item->id }}, '{{ $item->code }}')">
                                <i class="bi bi-trash"></i>
                                <span>Elimina</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h4>Nessun tipo di taglia trovato</h4>
                <p>Non sono ancora stati creati tipi di taglie.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Tipo di Taglia -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #a7f070, #00d2d3); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title">
                    <i class="bi bi-bar-chart me-2"></i>
                    <span id="modalTitle">Nuovo Tipo di Taglia</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="sizeTypeForm">
                <div class="modal-body" style="padding: 2rem;">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label">Codice *</label>
                            <input type="text" class="form-control" id="code" name="code" required maxlength="20" style="text-transform: uppercase;" oninput="validateCode()">
                            <div class="invalid-feedback" id="codeError"></div>
                            <small class="form-text text-muted">Es: EU, US, UK, IT, NUMERIC</small>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nome *</label>
                            <input type="text" class="form-control" id="name" name="name" required maxlength="150">
                            <div class="invalid-feedback" id="nameError"></div>
                            <small class="form-text text-muted">Nome descrittivo del sistema</small>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="category" class="form-label">Categoria</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">Seleziona categoria...</option>
                                <option value="clothing">Abbigliamento</option>
                                <option value="shoes">Calzature</option>
                                <option value="children">Bambini</option>
                                <option value="accessories">Accessori</option>
                                <option value="underwear">Intimo</option>
                                <option value="sportswear">Sportivo</option>
                                <option value="formal">Formale</option>
                                <option value="casual">Casual</option>
                            </select>
                            <small class="form-text text-muted">Categoria di appartenenza</small>
                        </div>
                        <div class="col-md-6">
                            <label for="measurement_unit" class="form-label">Unità di Misura</label>
                            <select class="form-select" id="measurement_unit" name="measurement_unit">
                                <option value="">Seleziona unità...</option>
                                <option value="cm">Centimetri</option>
                                <option value="inches">Pollici</option>
                                <option value="mixed">Misto</option>
                                <option value="numeric">Numerico</option>
                                <option value="letter">Lettere</option>
                            </select>
                            <small class="form-text text-muted">Sistema di misurazione</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Descrizione</label>
                        <textarea class="form-control" id="description" name="description" rows="3" maxlength="500" placeholder="Descrizione dettagliata del sistema di taglie (opzionale)"></textarea>
                        <small class="form-text text-muted">Max 500 caratteri</small>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="sort_order" class="form-label">Ordine di visualizzazione</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order" min="0" max="9999" value="0">
                            <small class="form-text text-muted">0-9999 (0 = primo nella lista)</small>
                        </div>
                        <div class="col-md-6">
                            <label for="active" class="form-label">Stato</label>
                            <select class="form-select" id="active" name="active">
                                <option value="1">Attivo</option>
                                <option value="0">Inattivo</option>
                            </select>
                            <small class="form-text text-muted">Solo tipi attivi sono disponibili</small>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x me-2"></i>Annulla
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check me-2"></i>
                        <span id="submitButtonText">Salva Tipo di Taglia</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Variabili globali
let editingId = null;

// Funzione filtro tabella
function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const tableBody = document.getElementById('tableBody');
    const rows = tableBody.querySelectorAll('tr');
    
    rows.forEach(row => {
        if (row.querySelector('.empty-state')) return;
        
        const code = row.cells[0].textContent.toLowerCase();
        const name = row.cells[1].textContent.toLowerCase();
        const category = row.cells[2].textContent.toLowerCase();
        const measurementUnit = row.cells[3].textContent.toLowerCase();
        const description = row.cells[4].textContent.toLowerCase();
        const active = row.dataset.active;
        
        const matchesSearch = !searchTerm || 
            code.includes(searchTerm) || 
            name.includes(searchTerm) || 
            category.includes(searchTerm) ||
            measurementUnit.includes(searchTerm) ||
            description.includes(searchTerm);
            
        const matchesStatus = !statusFilter || active === statusFilter;
        
        if (matchesSearch && matchesStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Reset filtri
function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    filterTable();
}

// Validazione codice
function validateCode() {
    const code = document.getElementById('code').value;
    const codeInput = document.getElementById('code');
    const codeError = document.getElementById('codeError');
    
    // Reset
    codeInput.classList.remove('is-invalid');
    codeError.textContent = '';
    
    if (code.length < 2) {
        codeInput.classList.add('is-invalid');
        codeError.textContent = 'Il codice deve essere almeno di 2 caratteri';
        return false;
    }
    
    if (!/^[A-Z0-9_-]+$/.test(code)) {
        codeInput.classList.add('is-invalid');
        codeError.textContent = 'Il codice può contenere solo lettere maiuscole, numeri, _ e -';
        return false;
    }
    
    // Controllo duplicati (se non in modifica o ID diverso)
    checkDuplicateCode(code, editingId);
    
    return true;
}

// Controllo codice duplicato
function checkDuplicateCode(code, excludeId = null) {
    const url = `{{ route('configurations.system-tables.show', $table) }}?check_duplicate=${encodeURIComponent(code)}`;
    
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const codeInput = document.getElementById('code');
        const codeError = document.getElementById('codeError');
        
        if (data.exists && (!excludeId || excludeId == null)) {
            codeInput.classList.add('is-invalid');
            codeError.textContent = 'Questo codice esiste già';
        }
    })
    .catch(error => {
        console.warn('Errore controllo duplicato:', error);
    });
}

// Modifica tipo di taglia
function loadEditData(id, code, name, description, category, measurementUnit, active, sortOrder) {
    editingId = id;
    
    document.getElementById('modalTitle').textContent = 'Modifica Tipo di Taglia';
    document.getElementById('submitButtonText').textContent = 'Aggiorna Tipo di Taglia';
    
    document.getElementById('code').value = code;
    document.getElementById('name').value = name;
    document.getElementById('description').value = description || '';
    document.getElementById('category').value = category || '';
    document.getElementById('measurement_unit').value = measurementUnit || '';
    document.getElementById('active').value = active ? '1' : '0';
    document.getElementById('sort_order').value = sortOrder || 0;
    
    // Mostra modal
    new bootstrap.Modal(document.getElementById('createModal')).show();
}

// Conferma eliminazione
function confirmDelete(id, code) {
    if (confirm(`Sei sicuro di voler eliminare il tipo di taglia "${code}"?\n\nQuesta azione non può essere annullata.`)) {
        // Crea form per eliminazione
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('configurations.system-tables.show', $table) }}/${id}`;
        form.style.display = 'none';
        
        // CSRF Token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfInput);
        
        // Method DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Salva tipo di taglia
function saveSizeType() {
    const form = document.getElementById('sizeTypeForm');
    const formData = new FormData(form);
    
    // Aggiungi CSRF token
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    let url = '{{ route('configurations.system-tables.store', $table) }}';
    let method = 'POST';
    
    if (editingId) {
        url = `{{ route('configurations.system-tables.show', $table) }}/${editingId}`;
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
        alert('Errore durante il salvataggio del tipo di taglia');
    });
}

function resetCreateForm() {
    editingId = null;
    document.getElementById('modalTitle').textContent = 'Nuovo Tipo di Taglia';
    document.getElementById('submitButtonText').textContent = 'Salva Tipo di Taglia';
    document.getElementById('sizeTypeForm').reset();
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    document.getElementById('sort_order').value = 0;
    document.getElementById('active').value = '1';
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Form submit
    const form = document.getElementById('sizeTypeForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            saveSizeType();
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