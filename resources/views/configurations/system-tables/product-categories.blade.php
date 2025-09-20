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
    
    /* Pulsanti modern-btn coerenti GREEN */
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
    
    /* Contenuto principale */
    .content-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    /* Tabella responsive Desktop */
    .table-responsive {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }
    
    .custom-table {
        background: white;
        margin: 0;
        border: none;
    }
    
    .custom-table thead th {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        font-weight: 600;
        border: none;
        padding: 1rem;
        font-size: 0.9rem;
        text-align: center;
    }
    
    .custom-table tbody td {
        padding: 0.9rem;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
        font-size: 0.9rem;
    }
    
    .custom-table tbody tr:hover {
        background-color: rgba(2, 157, 126, 0.05);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    
    /* Indicatore gerarchia */
    .hierarchy-indicator {
        display: inline-block;
        margin-right: 8px;
        color: #029D7E;
        font-weight: 600;
    }
    
    .level-0 { margin-left: 0; }
    .level-1 { margin-left: 20px; }
    .level-2 { margin-left: 40px; }
    .level-3 { margin-left: 60px; }
    .level-4 { margin-left: 80px; }
    
    /* Status badge */
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .status-active {
        background-color: rgba(2, 157, 126, 0.15);
        color: #029D7E;
    }
    
    .status-inactive {
        background-color: rgba(108, 117, 125, 0.15);
        color: #6c757d;
    }
    
    /* Color indicator */
    .color-indicator {
        display: inline-block;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid #ddd;
        margin-right: 8px;
    }
    
    /* Action buttons - Design system coerente */
    .action-btn {
        border: none;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 32px;
        flex-shrink: 0;
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
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: white;
    }
    
    /* Contenitore azioni - sempre orizzontale */
    .actions-container {
        display: flex !important;
        gap: 4px;
        justify-content: center;
        align-items: center;
        flex-wrap: nowrap;
        min-width: 120px;
    }
    
    /* Card mobile */
    .mobile-card {
        background: white;
        border-radius: 12px;
        padding: 1.2rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #f0f0f0;
    }
    
    .mobile-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }
    
    .mobile-card-header {
        display: flex;
        justify-content: between;
        align-items: start;
        margin-bottom: 0.8rem;
    }
    
    .mobile-card-title {
        font-weight: 600;
        color: #029D7E;
        font-size: 1.1rem;
        margin: 0;
    }
    
    .mobile-card-code {
        background: rgba(2, 157, 126, 0.1);
        color: #029D7E;
        padding: 2px 8px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .mobile-card-info {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    /* Azioni mobile - sempre in basso orizzontali */
    .mobile-card-actions {
        display: flex;
        gap: 8px;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #eee;
        justify-content: center;
    }
    
    .mobile-card-actions .action-btn {
        flex: 1;
        max-width: 120px;
        font-size: 0.8rem;
        padding: 8px 12px;
        justify-content: center;
    }
    
    /* Form modale */
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .modal-header {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        border: none;
        border-top-left-radius: 16px;
        border-top-right-radius: 16px;
    }
    
    .form-control:focus {
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
    }
    
    .form-select:focus {
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
    }
    
    /* Responsive breakpoints */
    @media (max-width: 768px) {
        .management-container {
            padding: 1rem;
        }
        
        .management-header {
            padding: 1.5rem;
        }
        
        .management-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .management-title {
            font-size: 1.5rem;
        }
        
        .management-header .d-flex.gap-2 {
            justify-content: center;
            flex-direction: row;
        }
    }
</style>

<div class="management-container">
    <!-- Header -->
    <div class="management-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="management-title">
                <i class="bi bi-diagram-3 me-3"></i>
                Categorie Articoli
            </h1>
            <div class="d-flex gap-2">
                <a href="{{ route('configurations.system-tables.index') }}" class="modern-btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Torna Indietro
                </a>
                <button type="button" class="modern-btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                    <i class="bi bi-plus-circle"></i>
                    Nuova Categoria
                </button>
            </div>
        </div>
    </div>

    <!-- Filtri di ricerca -->
    <div class="content-card mb-4">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Ricerca</label>
                <input type="text" name="search" class="form-control" 
                       placeholder="Cerca per codice, nome, descrizione..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Livello</label>
                <select name="level" class="form-select">
                    <option value="">Tutti i livelli</option>
                    <option value="root" {{ request('level') == 'root' ? 'selected' : '' }}>Solo radice</option>
                    <option value="0" {{ request('level') == '0' ? 'selected' : '' }}>Livello 0</option>
                    <option value="1" {{ request('level') == '1' ? 'selected' : '' }}>Livello 1</option>
                    <option value="2" {{ request('level') == '2' ? 'selected' : '' }}>Livello 2</option>
                    <option value="3" {{ request('level') == '3' ? 'selected' : '' }}>Livello 3</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Tutti</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Attivo</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inattivo</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="modern-btn btn-success me-2">
                    <i class="bi bi-search"></i> Filtra
                </button>
                <a href="{{ route('configurations.system-tables.show', 'product_categories') }}" class="modern-btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Contenuto principale -->
    <div class="content-card">
        <!-- Vista Desktop -->
        <div class="d-none d-md-block">
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>Codice</th>
                            <th>Nome Categoria</th>
                            <th>Descrizione</th>
                            <th>Percorso</th>
                            <th>Livello</th>
                            <th>Colore</th>
                            <th>Status</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td>
                                <code>{{ $item->code }}</code>
                            </td>
                            <td>
                                <div class="level-{{ $item->level }}">
                                    @if($item->level > 0)
                                        <span class="hierarchy-indicator">
                                            @for($i = 0; $i < $item->level; $i++) └── @endfor
                                        </span>
                                    @endif
                                    @if($item->icon)
                                        <i class="bi bi-{{ $item->icon }} me-2"></i>
                                    @endif
                                    <strong>{{ $item->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $item->description }}</td>
                            <td>
                                <small class="text-muted">{{ $item->path ?: $item->name }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $item->level }}</span>
                            </td>
                            <td>
                                @if($item->color_hex)
                                    <span class="color-indicator" style="background-color: {{ $item->color_hex }}"></span>
                                    <small>{{ $item->color_hex }}</small>
                                @else
                                    <small class="text-muted">N/A</small>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge {{ $item->active ? 'status-active' : 'status-inactive' }}">
                                    {{ $item->active ? 'Attivo' : 'Inattivo' }}
                                </span>
                            </td>
                            <td>
                                <div class="actions-container">
                                    <button type="button" class="action-btn edit" 
                                            onclick="editCategory({{ $item->id }})" title="Modifica">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="action-btn delete" 
                                            onclick="deleteCategory({{ $item->id }})" title="Elimina">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-inbox display-4 text-muted"></i>
                                <p class="text-muted mt-2">Nessuna categoria trovata</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Vista Mobile -->
        <div class="d-md-none">
            @forelse($items as $item)
            <div class="mobile-card">
                <div class="mobile-card-header">
                    <div>
                        <h5 class="mobile-card-title">
                            @if($item->level > 0)
                                <span class="hierarchy-indicator">
                                    @for($i = 0; $i < $item->level; $i++) └── @endfor
                                </span>
                            @endif
                            @if($item->icon)
                                <i class="bi bi-{{ $item->icon }} me-2"></i>
                            @endif
                            {{ $item->name }}
                        </h5>
                        <span class="mobile-card-code">{{ $item->code }}</span>
                    </div>
                </div>
                
                <div class="mobile-card-info">
                    <strong>Descrizione:</strong> {{ $item->description ?: 'N/A' }}
                </div>
                
                <div class="mobile-card-info">
                    <strong>Percorso:</strong> {{ $item->path ?: $item->name }}
                </div>
                
                <div class="mobile-card-info">
                    <strong>Livello:</strong> <span class="badge bg-secondary">{{ $item->level }}</span>
                    @if($item->color_hex)
                        <span class="color-indicator ms-2" style="background-color: {{ $item->color_hex }}"></span>
                        <small>{{ $item->color_hex }}</small>
                    @endif
                </div>
                
                <div class="mobile-card-info">
                    <strong>Status:</strong> 
                    <span class="status-badge {{ $item->active ? 'status-active' : 'status-inactive' }}">
                        {{ $item->active ? 'Attivo' : 'Inattivo' }}
                    </span>
                </div>
                
                <!-- Pulsanti azioni mobile - sempre in basso -->
                <div class="mobile-card-actions">
                    <button type="button" class="action-btn edit" 
                            onclick="editCategory({{ $item->id }})">
                        <i class="bi bi-pencil me-1"></i> Modifica
                    </button>
                    <button type="button" class="action-btn delete" 
                            onclick="deleteCategory({{ $item->id }})">
                        <i class="bi bi-trash me-1"></i> Elimina
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center py-4">
                <i class="bi bi-inbox display-4 text-muted"></i>
                <p class="text-muted mt-2">Nessuna categoria trovata</p>
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
</div>

<!-- Modal Form -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-diagram-3 me-2"></i>
                    <span id="modalTitle">Nuova Categoria</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="categoryForm" method="POST">
                @csrf
                <div id="methodField"></div>
                
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Codice Categoria *</label>
                            <input type="text" name="code" id="code" class="form-control" 
                                   placeholder="es: ELETTRONICA" required maxlength="20">
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Nome Categoria *</label>
                            <input type="text" name="name" id="name" class="form-control" 
                                   placeholder="es: Elettronica" required maxlength="100">
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Descrizione</label>
                            <textarea name="description" id="description" class="form-control" 
                                      rows="2" placeholder="Descrizione dettagliata della categoria" 
                                      maxlength="255"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Categoria Padre</label>
                            <select name="parent_id" id="parent_id" class="form-select">
                                <option value="">Categoria radice</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}">{!! $parent->getIndentedName() !!}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Se selezioni una categoria padre, questa diventerà una sottocategoria</small>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Ordinamento</label>
                            <input type="number" name="sort_order" id="sort_order" class="form-control" 
                                   placeholder="0" min="0" max="9999">
                            <small class="text-muted">Numero per ordinamento (0 = primo)</small>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Colore Categoria</label>
                            <input type="color" name="color_hex" id="color_hex" class="form-control form-control-color">
                            <small class="text-muted">Colore identificativo per la categoria</small>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Icona Bootstrap</label>
                            <input type="text" name="icon" id="icon" class="form-control" 
                                   placeholder="es: laptop, phone, car">
                            <small class="text-muted">Nome icona Bootstrap Icons senza 'bi-'</small>
                            <div class="invalid-feedback"></div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input type="hidden" name="active" value="0">
                                <input class="form-check-input" type="checkbox" name="active" 
                                       id="active" value="1" checked>
                                <label class="form-check-label" for="active">
                                    Categoria attiva
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="modern-btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Annulla
                    </button>
                    <button type="submit" class="modern-btn btn-success" id="submitBtn">
                        <i class="bi bi-check-circle"></i> Salva Categoria
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let editingId = null;

// Gestione form modale
document.getElementById('categoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    
    // Stato loading
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Salvataggio...';
    submitBtn.disabled = true;
    
    // Clear previous errors
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    
    const url = editingId 
        ? `/configurations/system-tables/product_categories/${editingId}`
        : `{{ route('configurations.system-tables.store', 'product_categories') }}`;
    
    if (editingId) {
        formData.append('_method', 'PUT');
    }
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Success modal
            showAlert('Categoria salvata con successo!', 'success');
            bootstrap.Modal.getInstance(document.getElementById('categoryModal')).hide();
            setTimeout(() => location.reload(), 1000);
        } else if (data.errors) {
            // Validation errors
            Object.keys(data.errors).forEach(field => {
                const input = document.getElementById(field);
                if (input) {
                    input.classList.add('is-invalid');
                    const feedback = input.parentNode.querySelector('.invalid-feedback');
                    if (feedback) {
                        feedback.textContent = data.errors[field][0];
                    }
                }
            });
        } else {
            showAlert(data.message || 'Errore durante il salvataggio', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Errore di comunicazione con il server', 'error');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Funzioni globali
function editCategory(id) {
    editingId = id;
    document.getElementById('modalTitle').textContent = 'Modifica Categoria';
    document.getElementById('submitBtn').innerHTML = '<i class="bi bi-check-circle"></i> Aggiorna Categoria';
    
    // Carica dati categoria
    fetch(`/configurations/system-tables/product_categories/${id}`, {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(category => {
        // Popola form di modifica
        document.getElementById('code').value = category.code;
        document.getElementById('name').value = category.name;
        document.getElementById('description').value = category.description || '';
        document.getElementById('parent_id').value = category.parent_id || '';
        document.getElementById('sort_order').value = category.sort_order || '';
        document.getElementById('color_hex').value = category.color_hex || '#029D7E';
        document.getElementById('icon').value = category.icon || '';
        document.getElementById('active').checked = category.active;
        
        new bootstrap.Modal(document.getElementById('categoryModal')).show();
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Errore durante il caricamento dei dati', 'error');
    });
}

function deleteCategory(id) {
    if (confirm('Sei sicuro di voler eliminare questa categoria? Questa azione non può essere annullata.')) {
        fetch(`/configurations/system-tables/product_categories/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Categoria eliminata con successo!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showAlert(data.message || 'Errore durante l\'eliminazione', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Errore di comunicazione con il server', 'error');
        });
    }
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Reset form quando si apre per nuova categoria
document.getElementById('categoryModal').addEventListener('show.bs.modal', function() {
    if (!editingId) {
        document.getElementById('categoryForm').reset();
        document.getElementById('modalTitle').textContent = 'Nuova Categoria';
        document.getElementById('submitBtn').innerHTML = '<i class="bi bi-check-circle"></i> Salva Categoria';
        document.getElementById('active').checked = true;
        document.getElementById('color_hex').value = '#029D7E';
    }
});

document.getElementById('categoryModal').addEventListener('hidden.bs.modal', function() {
    editingId = null;
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
});
</script>

@endsection