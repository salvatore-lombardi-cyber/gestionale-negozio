@extends('layouts.app')

@section('content')

<style>
    body {
        background: linear-gradient(135deg, #2998ff 0%, #5643fa 100%);
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
    background: linear-gradient(135deg, #2998ff 0%, #5643fa 100%);
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

.btn-primary-custom {
    background: linear-gradient(135deg, #2998ff, #5643fa);
    color: white;
    border: none;
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(41, 152, 255, 0.3);
    color: white;
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d, #545b62);
    color: white;
}

.modern-table {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.modern-table thead th {
    background: linear-gradient(135deg, #2998ff, #5643fa);
    color: white;
    font-weight: 600;
    padding: 1rem;
    border: none;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.modern-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.modern-table tbody tr:hover {
    background: rgba(41, 152, 255, 0.05);
    transform: scale(1.01);
}

.modern-table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border: none;
}

.action-btn {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 0 2px;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-edit {
    background: linear-gradient(135deg, #fdcb6e, #e17055);
    color: white;
}

.btn-delete {
    background: linear-gradient(135deg, #fd79a8, #e84393);
    color: white;
}

.btn-edit:hover, .btn-delete:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    border: none;
}

.status-active {
    background: linear-gradient(135deg, #2998ff, #5643fa);
    color: white;
}

.status-inactive {
    background: linear-gradient(135deg, #6c757d, #545b62);
    color: white;
}

.mobile-cards {
    display: none;
}

@media (max-width: 768px) {
    .modern-table {
        display: none;
    }
    
    .mobile-cards {
        display: block;
    }
    
    .mobile-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
}

.search-filters {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.stats-summary {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.stat-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.stat-row:last-child {
    margin-bottom: 0;
}

.stat-item {
    text-align: center;
    flex: 1;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    background: linear-gradient(135deg, #2998ff, #5643fa);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.stat-label {
    font-size: 0.9rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.category-stat {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.7);
    border-radius: 10px;
    margin-bottom: 0.5rem;
}

.category-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.weight-icon { background: linear-gradient(135deg, #e74c3c, #c0392b); }
.length-icon { background: linear-gradient(135deg, #3498db, #2980b9); }
.volume-icon { background: linear-gradient(135deg, #9b59b6, #8e44ad); }
.quantity-icon { background: linear-gradient(135deg, #f39c12, #e67e22); }
</style>

<div class="management-container">
    <!-- Header con titolo e pulsanti -->
    <div class="management-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="management-title">
                    <i class="bi bi-rulers me-3" style="font-size: 2rem;"></i>
                    Gestione Unità di Misura
                </h1>
                <p class="text-muted mb-0 mt-2">
                    Gestione completa unità di misura: peso, lunghezza, volume, quantità
                </p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('configurations.system-tables.index') }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-arrow-left"></i> Torna Indietro
                </a>
                <button type="button" class="btn btn-primary-custom modern-btn" data-bs-toggle="modal" data-bs-target="#unitModal">
                    <i class="bi bi-plus-lg"></i>
                    Nuova Unità
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiche riepilogative -->
    <div class="stats-summary">
        <div class="row">
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['total'] }}</div>
                    <div class="stat-label">Totale Unità</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['active'] }}</div>
                    <div class="stat-label">Attive</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-number">{{ $stats['inactive'] }}</div>
                    <div class="stat-label">Inattive</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-number">{{ count($items->where('code', 'like', '%KG%')->where('code', 'like', '%G%')->where('code', 'like', '%LB%')) + count($items->whereIn('code', ['KG', 'G', 'LB', 'OZ'])) }}</div>
                    <div class="stat-label">Peso/Volume</div>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="category-stat">
                    <div class="category-icon weight-icon">
                        <i class="bi bi-box"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">Peso & Massa</h6>
                        <small class="text-muted">Kg, g, libbre, once</small>
                    </div>
                    <div class="stat-number" style="font-size: 1.5rem;">{{ count($items->whereIn('code', ['KG', 'G', 'LB', 'OZ'])) }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="category-stat">
                    <div class="category-icon length-icon">
                        <i class="bi bi-rulers"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">Lunghezza & Distanza</h6>
                        <small class="text-muted">m, cm, mm, pollici</small>
                    </div>
                    <div class="stat-number" style="font-size: 1.5rem;">{{ count($items->whereIn('code', ['M', 'CM', 'MM', 'IN', 'FT'])) }}</div>
                </div>
            </div>
        </div>
        
        <div class="row mt-2">
            <div class="col-md-6">
                <div class="category-stat">
                    <div class="category-icon volume-icon">
                        <i class="bi bi-droplet"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">Volume & Capacità</h6>
                        <small class="text-muted">litri, ml, galloni</small>
                    </div>
                    <div class="stat-number" style="font-size: 1.5rem;">{{ count($items->whereIn('code', ['L', 'ML', 'GAL'])) }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="category-stat">
                    <div class="category-icon quantity-icon">
                        <i class="bi bi-123"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0">Quantità & Conteggio</h6>
                        <small class="text-muted">pezzi, confezioni, pallet</small>
                    </div>
                    <div class="stat-number" style="font-size: 1.5rem;">{{ count($items->whereIn('code', ['PZ', 'PCS', 'CONF', 'SCATOLA', 'PALLET'])) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtri di ricerca -->
    <div class="search-filters">
        <div class="row">
            <div class="col-md-6">
                <label for="searchInput" class="form-label">Cerca unità di misura</label>
                <input type="text" class="form-control" id="searchInput" placeholder="Cerca per codice, nome o descrizione..." value="{{ $search ?? '' }}">
            </div>
            <div class="col-md-4">
                <label for="statusFilter" class="form-label">Filtra per stato</label>
                <select class="form-select" id="statusFilter">
                    <option value="">Tutti gli stati</option>
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

    <!-- Tabella desktop -->
    <div class="modern-table">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>CODICE</th>
                    <th>NOME</th>
                    <th>DESCRIZIONE</th>
                    <th>STATO</th>
                    <th width="120">AZIONI</th>
                </tr>
            </thead>
            <tbody id="unitsTable">
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
                            <span class="status-badge {{ $item->active ? 'status-active' : 'status-inactive' }}">
                                {{ $item->active ? 'Attiva' : 'Inattiva' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="action-btn btn-edit" onclick="editUnit({{ $item->id }}, '{{ $item->code }}', '{{ $item->name }}', '{{ $item->description }}', {{ $item->active ? 'true' : 'false' }}, {{ $item->sort_order }})" title="Modifica">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="action-btn btn-delete" onclick="deleteUnit({{ $item->id }}, '{{ $item->name }}')" title="Elimina">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-state">
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-rulers" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-3">Nessuna unità di misura trovata</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Cards mobile -->
    <div class="mobile-cards">
        @foreach($items as $item)
            <div class="mobile-card" data-active="{{ $item->active ? '1' : '0' }}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <strong class="text-primary">{{ $item->code }}</strong>
                        <h6 class="mb-1">{{ $item->name }}</h6>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" class="action-btn btn-edit" onclick="editUnit({{ $item->id }}, '{{ $item->code }}', '{{ $item->name }}', '{{ $item->description }}', {{ $item->active ? 'true' : 'false' }}, {{ $item->sort_order }})">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button type="button" class="action-btn btn-delete" onclick="deleteUnit({{ $item->id }}, '{{ $item->name }}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                <p class="mb-2 text-muted small">{{ $item->description ?? 'Nessuna descrizione' }}</p>
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    @if($item->active)
                        <span class="badge status-active small">Attiva</span>
                    @else
                        <span class="badge status-inactive small">Inattiva</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal per Nuova/Modifica Unità di Misura -->
<div class="modal fade" id="unitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #2998ff, #5643fa); color: white;">
                <h5 class="modal-title">
                    <i class="bi bi-rulers me-2"></i>
                    <span id="modalTitle">Nuova Unità di Misura</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="unitForm">
                    <input type="hidden" id="unitId" name="id">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label">Codice <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="code" name="code" required maxlength="20" pattern="[A-Z0-9_-]+" style="text-transform: uppercase;">
                            <div class="invalid-feedback" id="codeError"></div>
                            <small class="form-text text-muted">Es: KG, L, M, PZ, CM</small>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nome <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required maxlength="150">
                            <div class="invalid-feedback" id="nameError"></div>
                            <small class="form-text text-muted">Nome completo dell'unità</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Descrizione</label>
                        <textarea class="form-control" id="description" name="description" rows="3" maxlength="500" placeholder="Descrizione dettagliata dell'unità di misura (opzionale)"></textarea>
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
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary-custom" onclick="saveUnit()">
                    <i class="bi bi-check-lg me-2"></i>
                    <span id="saveButtonText">Crea Unità</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let editingUnitId = null;

// Filtri e ricerca in tempo reale
document.getElementById('searchInput').addEventListener('input', filterUnits);
document.getElementById('statusFilter').addEventListener('change', filterUnits);

function filterUnits() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    
    const rows = document.querySelectorAll('#unitsTable tr:not(.empty-state)');
    const cards = document.querySelectorAll('.mobile-card');
    
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
    
    // Stesso filtro per mobile cards
    cards.forEach(card => {
        const code = card.querySelector('.text-primary').textContent.toLowerCase();
        const name = card.querySelector('h6').textContent.toLowerCase();
        const description = card.querySelector('.text-muted').textContent.toLowerCase();
        const active = card.dataset.active;
        
        const matchesSearch = !searchTerm || 
            code.includes(searchTerm) || 
            name.includes(searchTerm) || 
            description.includes(searchTerm);
            
        const matchesStatus = !statusFilter || active === statusFilter;
        
        if (matchesSearch && matchesStatus) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    filterUnits();
}

function saveUnit() {
    const form = document.getElementById('unitForm');
    const formData = new FormData(form);
    
    // Validazione base
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        return;
    }
    
    const url = editingUnitId ? `/configurations/system-tables/unit_of_measures/${editingUnitId}` : '/configurations/system-tables/unit_of_measures';
    const method = editingUnitId ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Errore: ' + (data.message || 'Operazione fallita'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Errore di rete');
    });
}

function editUnit(id, code, name, description, active, sort_order) {
    editingUnitId = id;
    document.getElementById('modalTitle').textContent = 'Modifica Unità di Misura';
    document.getElementById('saveButtonText').textContent = 'Salva Modifiche';
    
    document.getElementById('unitId').value = id;
    document.getElementById('code').value = code;
    document.getElementById('name').value = name;
    document.getElementById('description').value = description || '';
    document.getElementById('active').value = active ? '1' : '0';
    document.getElementById('sort_order').value = sort_order || 0;
    
    new bootstrap.Modal(document.getElementById('unitModal')).show();
}

function deleteUnit(id, name) {
    if (!confirm(`Sei sicuro di voler eliminare l'unità di misura "${name}"?`)) return;
    
    fetch(`/configurations/system-tables/unit_of_measures/${id}`, {
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
            alert('Errore: ' + (data.message || 'Eliminazione fallita'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Errore di rete');
    });
}

// Reset form quando si chiude la modal
document.getElementById('unitModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('unitForm').reset();
    document.getElementById('unitForm').classList.remove('was-validated');
    editingUnitId = null;
    document.getElementById('modalTitle').textContent = 'Nuova Unità di Misura';
    document.getElementById('saveButtonText').textContent = 'Crea Unità';
});

// Controllo duplicati in tempo reale
document.getElementById('code').addEventListener('blur', function() {
    const code = this.value.trim().toUpperCase();
    if (!code || (editingUnitId && this.defaultValue.toUpperCase() === code)) return;
    
    fetch(`/configurations/system-tables/unit_of_measures?check_duplicate=${code}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            this.classList.add('is-invalid');
            document.getElementById('codeError').textContent = 'Codice già esistente';
        } else {
            this.classList.remove('is-invalid');
            document.getElementById('codeError').textContent = '';
        }
    });
});
</script>

@endsection