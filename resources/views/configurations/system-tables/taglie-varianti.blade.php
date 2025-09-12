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

.custom-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
}

.custom-table th {
    background: linear-gradient(135deg, #029D7E, #4DC9A5);
    color: white;
    padding: 1.5rem 1rem;
    font-weight: 700;
    text-align: left;
    border: none;
}

.custom-table td {
    padding: 1rem;
    border-bottom: 1px solid #e2e8f0;
    transition: all 0.3s ease;
}

.custom-table tbody tr:hover {
    background: linear-gradient(135deg, rgba(2, 157, 126, 0.05), rgba(77, 201, 165, 0.05));
    transform: translateX(5px);
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
                    <i class="bi bi-tags-fill me-3" style="color: #029D7E; font-size: 2rem;"></i>
                    Taglie Varianti
                </h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('configurations.system-tables.index') }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-arrow-left"></i> Torna Indietro
                </a>
                <button type="button" class="btn btn-success modern-btn" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-lg"></i> Nuova Taglia Variante
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiche -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-tags-fill"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total'] ?? 0 }}</h3>
                    <p>Totale</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon active">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['active'] ?? 0 }}</h3>
                    <p>Attive</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon inactive">
                    <i class="bi bi-x-circle-fill"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['inactive'] ?? 0 }}</h3>
                    <p>Inattive</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtri -->
    <div class="search-filters">
        <div class="row g-3">
            <div class="col-md-6">
                <input type="text" class="form-control search-input" id="searchInput" placeholder="Cerca taglie varianti..." onkeyup="filterTable()">
            </div>
            <div class="col-md-4">
                <select class="form-select filter-select" id="statusFilter" onchange="filterTable()">
                    <option value="">Tutti gli Stati</option>
                    <option value="1">Attive</option>
                    <option value="0">Inattive</option>
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
            <table class="custom-table" id="sizeVariantTable">
                <thead>
                    <tr>
                        <th>CODICE</th>
                        <th>NOME</th>
                        <th>DESCRIZIONE</th>
                        <th>ORDINE</th>
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
                                <small class="text-muted">
                                    {{ $item->description ?? 'Nessuna descrizione' }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $item->sort_order ?? 0 }}</span>
                            </td>
                            <td>
                                @if($item->active)
                                    <span class="badge active">
                                        <i class="bi bi-check-circle me-1"></i>Attiva
                                    </span>
                                @else
                                    <span class="badge inactive">
                                        <i class="bi bi-x-circle me-1"></i>Inattiva
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="action-btn edit" title="Modifica taglia" onclick="loadEditData({{ $item->id }}, '{{ $item->code }}', '{{ $item->name }}', '{{ addslashes($item->description ?? '') }}', {{ $item->active ? 'true' : 'false' }}, {{ $item->sort_order ?? 0 }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="action-btn delete" title="Elimina taglia" onclick="confirmDelete({{ $item->id }}, '{{ $item->code }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <h4>Nessuna taglia variante trovata</h4>
                                <p>Non sono ancora state create taglie varianti o nessuna corrisponde ai filtri selezionati.</p>
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
                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge bg-secondary small">Ordine: {{ $item->sort_order ?? 0 }}</span>
                            @if($item->active)
                                <span class="badge active small">Attiva</span>
                            @else
                                <span class="badge inactive small">Inattiva</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="mobile-actions">
                            <button type="button" 
                                    class="mobile-action-btn edit" 
                                    onclick="loadEditData({{ $item->id }}, '{{ $item->code }}', '{{ $item->name }}', '{{ addslashes($item->description ?? '') }}', {{ $item->active ? 'true' : 'false' }}, {{ $item->sort_order ?? 0 }})">
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
                <h4>Nessuna taglia variante trovata</h4>
                <p>Non sono ancora state create taglie varianti.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal Taglia Variante -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #029D7E, #4DC9A5); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title">
                    <i class="bi bi-tags-fill me-2"></i>
                    <span id="modalTitle">Nuova Taglia Variante</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="sizeVariantForm">
                <div class="modal-body" style="padding: 2rem;">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label">Codice *</label>
                            <input type="text" class="form-control" id="code" name="code" required maxlength="20" style="text-transform: uppercase;" oninput="validateCode()">
                            <div class="invalid-feedback" id="codeError"></div>
                            <small class="form-text text-muted">Es: XS, S, M, L, XL, XXL</small>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nome *</label>
                            <input type="text" class="form-control" id="name" name="name" required maxlength="150">
                            <div class="invalid-feedback" id="nameError"></div>
                            <small class="form-text text-muted">Nome descrittivo della taglia</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Descrizione</label>
                        <textarea class="form-control" id="description" name="description" rows="3" maxlength="500" placeholder="Descrizione dettagliata della taglia (opzionale)"></textarea>
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
                                <option value="1">Attiva</option>
                                <option value="0">Inattiva</option>
                            </select>
                            <small class="form-text text-muted">Solo taglie attive sono disponibili</small>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x me-2"></i>Annulla
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check me-2"></i>
                        <span id="submitButtonText">Salva Taglia Variante</span>
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
        const description = row.cells[2].textContent.toLowerCase();
        const active = row.dataset.active;
        
        const matchesSearch = !searchTerm || 
            code.includes(searchTerm) || 
            name.includes(searchTerm) || 
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

// Modifica taglia variante
function loadEditData(id, code, name, description, active, sortOrder) {
    editingId = id;
    
    document.getElementById('modalTitle').textContent = 'Modifica Taglia Variante';
    document.getElementById('submitButtonText').textContent = 'Aggiorna Taglia Variante';
    
    document.getElementById('code').value = code;
    document.getElementById('name').value = name;
    document.getElementById('description').value = description || '';
    document.getElementById('active').value = active ? '1' : '0';
    document.getElementById('sort_order').value = sortOrder || 0;
    
    // Mostra modal
    new bootstrap.Modal(document.getElementById('createModal')).show();
}

// Conferma eliminazione
function confirmDelete(id, code) {
    if (confirm(`Sei sicuro di voler eliminare la taglia "${code}"?\n\nQuesta azione non può essere annullata.`)) {
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

// Salva taglia variante
function saveSizeVariant() {
    const form = document.getElementById('sizeVariantForm');
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
        alert('Errore durante il salvataggio della taglia variante');
    });
}

function resetCreateForm() {
    editingId = null;
    document.getElementById('modalTitle').textContent = 'Nuova Taglia Variante';
    document.getElementById('submitButtonText').textContent = 'Salva Taglia Variante';
    document.getElementById('sizeVariantForm').reset();
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    document.getElementById('sort_order').value = 0;
    document.getElementById('active').value = '1';
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Form submit
    const form = document.getElementById('sizeVariantForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            saveSizeVariant();
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