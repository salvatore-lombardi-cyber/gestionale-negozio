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

.stat-icon.escluso {
    background: linear-gradient(135deg, #dc3545, #c82333);
}

.stat-icon.non_soggetto {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.stat-icon.non_imponibile {
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
                    <i class="bi bi-receipt-cutoff me-3" style="color: #029D7E; font-size: 2rem;"></i>
                    Nature IVA
                </h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('configurations.system-tables.index') }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-arrow-left"></i> Torna Indietro
                </a>
                <button type="button" class="btn btn-success modern-btn" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-lg"></i> Nuova Natura IVA
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
                    <p>Attive</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon escluso">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['escluso'] }}</h3>
                    <p>N1 - Escluso</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon non_soggetto">
                    <i class="bi bi-dash-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['non_soggetto'] }}</h3>
                    <p>N2 - Non Soggetto</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon non_imponibile">
                    <i class="bi bi-exclamation-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['non_imponibile'] }}</h3>
                    <p>N3 - Non Imponibile</p>
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
                    <p>Disattivate</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtri -->
    <div class="search-filters">
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-control search-input" id="searchInput" placeholder="Cerca nature IVA..." onkeyup="filterTable()">
            </div>
            <div class="col-md-3">
                <select class="form-select filter-select" id="statusFilter" onchange="filterTable()">
                    <option value="">Tutti gli Stati</option>
                    <option value="1">Attive</option>
                    <option value="0">Inattive</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select filter-select" id="categoryFilter" onchange="filterTable()">
                    <option value="">Tutte le Categorie</option>
                    <option value="N1">N1 - Escluso</option>
                    <option value="N2">N2 - Non Soggetto</option>
                    <option value="N3">N3 - Non Imponibile</option>
                    <option value="N4">N4 - Esente</option>
                    <option value="N5">N5 - Regime Margine</option>
                    <option value="N6">N6 - Inversione Contabile</option>
                    <option value="N7">N7 - IVA Altro Stato UE</option>
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
            <table class="modern-table" id="vatTypesTable">
                <thead>
                    <tr>
                        <th>CODICE</th>
                        <th>NOME</th>
                        <th>DESCRIZIONE</th>
                        <th>STATO</th>
                        <th>ORDINE</th>
                        <th width="120">AZIONI</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($items as $item)
                        <tr data-active="{{ $item->active ? '1' : '0' }}" data-category="{{ substr($item->code, 0, 2) }}">
                            <td>
                                <strong class="code-badge">{{ $item->code }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $item->name }}</strong>
                                    @if($item->active)
                                        <div><small class="status-active"><i class="bi bi-check-circle-fill me-1"></i>Attiva</small></div>
                                    @else
                                        <div><small class="status-inactive"><i class="bi bi-x-circle-fill me-1"></i>Inattiva</small></div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="text-muted">{{ Str::limit($item->description, 80) }}</span>
                            </td>
                            <td>
                                @if($item->active)
                                    <span class="status-active">
                                        <i class="bi bi-check-circle-fill me-1"></i>Attiva
                                    </span>
                                @else
                                    <span class="status-inactive">
                                        <i class="bi bi-x-circle-fill me-1"></i>Inattiva
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $item->sort_order }}</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="action-btn edit" title="Modifica natura IVA" onclick="editItem({{ $item->id }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="action-btn delete" title="Elimina natura IVA" onclick="deleteItem({{ $item->id }}, '{{ $item->name }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noResults" style="display: none;">
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-receipt-cutoff display-1 text-muted mb-3"></i>
                                    <h4>Nessuna natura IVA trovata</h4>
                                    <p class="text-muted">Non sono state trovate nature IVA per i filtri selezionati.</p>
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
            <div class="item-card mobile-item-row" data-active="{{ $item->active ? '1' : '0' }}" data-category="{{ substr($item->code, 0, 2) }}">
                
                <div class="item-card-header">
                    <div class="d-flex align-items-center gap-3">
                        <div>
                            <h3 class="item-card-title">{{ $item->name }}</h3>
                            <p class="item-card-code">Codice: {{ $item->code }}</p>
                        </div>
                    </div>
                    @if($item->active)
                        <span class="status-active">
                            <i class="bi bi-check-circle-fill me-1"></i>Attiva
                        </span>
                    @else
                        <span class="status-inactive">
                            <i class="bi bi-x-circle-fill me-1"></i>Inattiva
                        </span>
                    @endif
                </div>

                <div class="item-card-details">
                    <div class="item-detail">
                        <span class="item-detail-label">Descrizione</span>
                        <span class="item-detail-value">{{ $item->description }}</span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-label">Ordine</span>
                        <span class="item-detail-value">{{ $item->sort_order }}</span>
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
                <i class="bi bi-receipt-cutoff"></i>
                <h4>Nessuna natura IVA trovata</h4>
                <p>Non sono state trovate nature IVA per i filtri selezionati.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Natura IVA -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #029D7E, #4DC9A5); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title">
                    <i class="bi bi-receipt-cutoff me-2"></i>
                    <span id="modalTitle">Nuova Natura IVA</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="vatTypeForm">
                <div class="modal-body" style="padding: 2rem;">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label">Codice *</label>
                            <input type="text" class="form-control" id="code" name="code" required maxlength="10" style="text-transform: uppercase;" oninput="validateCode()">
                            <div class="invalid-feedback" id="codeError"></div>
                            <small class="form-text text-muted">Es: N1, N2.1, N3.1</small>
                        </div>
                        <div class="col-md-6">
                            <label for="sort_order" class="form-label">Ordine Visualizzazione</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order" min="0" max="9999" value="0">
                            <div class="invalid-feedback" id="sort_orderError"></div>
                            <small class="form-text text-muted">Numeri più bassi appaiono per primi</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome *</label>
                        <input type="text" class="form-control" id="name" name="name" required maxlength="100">
                        <div class="invalid-feedback" id="nameError"></div>
                        <small class="form-text text-muted">Nome breve della natura IVA</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Descrizione *</label>
                        <textarea class="form-control" id="description" name="description" required maxlength="500" rows="3"></textarea>
                        <div class="invalid-feedback" id="descriptionError"></div>
                        <small class="form-text text-muted">Descrizione completa della natura IVA</small>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-12 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="active" name="active" checked>
                                <label class="form-check-label" for="active">Natura IVA attiva</label>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer" style="padding: 1.5rem 2rem; border-top: 1px solid #e9ecef;">
                    <button type="button" class="btn btn-secondary modern-btn" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Annulla
                    </button>
                    <button type="submit" class="btn btn-success modern-btn" id="saveBtn">
                        <i class="bi bi-check-lg"></i> Salva Natura IVA
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
    const categoryValue = document.getElementById('categoryFilter').value;
    
    const tableRows = document.querySelectorAll('#tableBody tr[data-active]');
    const mobileCards = document.querySelectorAll('.mobile-item-row[data-active]');
    
    let visibleCount = 0;
    
    // Filtro tabella desktop
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const active = row.getAttribute('data-active');
        const category = row.getAttribute('data-category');
        
        let show = true;
        
        if (searchValue && !text.includes(searchValue)) show = false;
        if (statusValue && active !== statusValue) show = false;
        if (categoryValue && !category.startsWith(categoryValue)) show = false;
        
        row.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });
    
    // Filtro cards mobile
    mobileCards.forEach(card => {
        const text = card.textContent.toLowerCase();
        const active = card.getAttribute('data-active');
        const category = card.getAttribute('data-category');
        
        let show = true;
        
        if (searchValue && !text.includes(searchValue)) show = false;
        if (statusValue && active !== statusValue) show = false;
        if (categoryValue && !category.startsWith(categoryValue)) show = false;
        
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
    document.getElementById('categoryFilter').value = '';
    filterTable();
}

// JavaScript per gestione CRUD
let editingId = null;

function validateCode() {
    const codeInput = document.getElementById('code');
    const code = codeInput.value.toUpperCase().trim();
    
    if (code.length >= 2) {
        fetch(`/configurations/system-tables/vat_types?check_duplicate=${encodeURIComponent(code)}`)
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
    
    fetch(`/configurations/system-tables/vat_types/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalTitle').textContent = 'Modifica Natura IVA';
            document.getElementById('saveBtn').innerHTML = '<i class="bi bi-check-lg"></i> Aggiorna Natura IVA';
            
            // Popola i campi
            document.getElementById('code').value = data.code || '';
            document.getElementById('name').value = data.name || '';
            document.getElementById('description').value = data.description || '';
            document.getElementById('sort_order').value = data.sort_order || 0;
            document.getElementById('active').checked = data.active;
            
            new bootstrap.Modal(document.getElementById('createModal')).show();
        })
        .catch(error => {
            console.error('Errore caricamento dati:', error);
            alert('Errore nel caricamento dei dati per la modifica');
        });
}

function deleteItem(id, name) {
    if (confirm(`Sei sicuro di voler eliminare la natura IVA "${name}"?\n\nQuesta azione non può essere annullata.`)) {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('_method', 'DELETE');
        
        fetch(`/configurations/system-tables/vat_types/${id}`, {
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
            alert('Errore durante l\'eliminazione della natura IVA');
        });
    }
}

function saveVatType() {
    const form = document.getElementById('vatTypeForm');
    const formData = new FormData(form);
    
    // Fix per checkbox
    const activeCheckbox = document.getElementById('active');
    formData.set('active', activeCheckbox.checked ? '1' : '0');
    
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    let url = '/configurations/system-tables/vat_types';
    let method = 'POST';
    
    if (editingId) {
        url = `/configurations/system-tables/vat_types/${editingId}`;
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
        alert('Errore durante il salvataggio della natura IVA');
    });
}

function resetCreateForm() {
    editingId = null;
    document.getElementById('modalTitle').textContent = 'Nuova Natura IVA';
    document.getElementById('saveBtn').innerHTML = '<i class="bi bi-check-lg"></i> Salva Natura IVA';
    document.getElementById('vatTypeForm').reset();
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    document.getElementById('sort_order').value = 0;
    document.getElementById('active').checked = true;
}

function exportData() {
    window.location.href = `/configurations/system-tables/vat_types/export`;
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Form submit
    const form = document.getElementById('vatTypeForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            saveVatType();
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