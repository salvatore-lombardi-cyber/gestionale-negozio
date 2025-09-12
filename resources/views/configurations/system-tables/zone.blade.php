@extends('layouts.app')

@section('content')

<style>
    body {
        background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
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
    background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    margin: 0;
}

.management-subtitle {
    color: #666;
    margin-top: 0.5rem;
    font-size: 1.1rem;
}

.back-button {
    background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 15px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 8px 25px rgba(0, 176, 155, 0.3);
}

.back-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(0, 176, 155, 0.4);
    color: white;
    text-decoration: none;
}

/* Statistics Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 1.8rem;
    text-align: center;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 45px rgba(0, 0, 0, 0.15);
}

.stat-card .stat-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

.stat-card .stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.stat-card .stat-label {
    font-size: 1.1rem;
    color: #666;
    font-weight: 600;
}

/* Controls Section */
.controls-section {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.search-container {
    position: relative;
    max-width: 400px;
}

.search-input {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    border: 2px solid rgba(0, 176, 155, 0.2);
    border-radius: 15px;
    font-size: 1rem;
    background: rgba(255, 255, 255, 0.8);
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: #00b09b;
    box-shadow: 0 0 0 3px rgba(0, 176, 155, 0.1);
    background: white;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #00b09b;
    font-size: 1.2rem;
}

.add-button {
    background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 15px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(0, 176, 155, 0.3);
}

.add-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(0, 176, 155, 0.4);
}

/* Data Table */
.data-container {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead th {
    background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
    color: white;
    padding: 1.5rem 1rem;
    text-align: left;
    font-weight: 600;
    font-size: 1rem;
    border: none;
}

.data-table tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.data-table tbody tr:hover {
    background: rgba(0, 176, 155, 0.05);
}

.data-table tbody td {
    padding: 1.2rem 1rem;
    border: none;
    vertical-align: middle;
}

.status-badge {
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-align: center;
    min-width: 80px;
    display: inline-block;
}

.status-active {
    background: rgba(76, 175, 80, 0.15);
    color: #4CAF50;
    border: 1px solid rgba(76, 175, 80, 0.3);
}

.status-inactive {
    background: rgba(244, 67, 54, 0.15);
    color: #f44336;
    border: 1px solid rgba(244, 67, 54, 0.3);
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-edit, .btn-delete {
    padding: 0.5rem;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.9rem;
}

.btn-edit {
    background: rgba(33, 150, 243, 0.1);
    color: #2196F3;
}

.btn-edit:hover {
    background: rgba(33, 150, 243, 0.2);
}

.btn-delete {
    background: rgba(244, 67, 54, 0.1);
    color: #f44336;
}

.btn-delete:hover {
    background: rgba(244, 67, 54, 0.2);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #666;
}

.empty-state i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 1rem;
}

/* Modal Styles */
.modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.modal-header {
    background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
    color: white;
    border-radius: 20px 20px 0 0;
    padding: 1.5rem 2rem;
    border: none;
}

.modal-title {
    font-weight: 600;
    font-size: 1.3rem;
}

.modal-body {
    padding: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
    display: block;
}

.form-control {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 2px solid rgba(0, 176, 155, 0.2);
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #00b09b;
    box-shadow: 0 0 0 3px rgba(0, 176, 155, 0.1);
}

.form-check {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-check-input {
    width: 1.2rem;
    height: 1.2rem;
}

.modal-footer {
    padding: 1.5rem 2rem;
    border: none;
    gap: 1rem;
}

.btn-primary {
    background: linear-gradient(135deg, #00b09b 0%, #96c93d 100%);
    border: none;
    padding: 0.8rem 2rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 176, 155, 0.3);
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(0, 176, 155, 0.4);
}

.btn-secondary {
    background: #6c757d;
    border: none;
    padding: 0.8rem 2rem;
    border-radius: 10px;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 768px) {
    .management-container {
        padding: 1rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .data-table {
        font-size: 0.9rem;
    }
    
    .data-table th,
    .data-table td {
        padding: 0.8rem 0.5rem;
    }
    
    .controls-section {
        padding: 1.5rem;
    }
}
</style>

<div class="management-container">
    <!-- Header Section -->
    <div class="management-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="management-title">
                    <i class="bi bi-globe-americas me-2"></i>
                    Gestione Zone
                </h1>
                <p class="management-subtitle">Amministrazione zone geografiche e commerciali</p>
            </div>
            <div class="d-flex gap-3">
                <a href="{{ route('configurations.system-tables.index') }}" class="back-button">
                    <i class="bi bi-arrow-left"></i>
                    Torna Indietro
                </a>
                <button type="button" 
                        class="add-button" 
                        data-bs-toggle="modal" 
                        data-bs-target="#zoneModal"
                        onclick="openAddModal()">
                    <i class="bi bi-plus-circle me-2"></i>
                    Nuova Zona
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-list-ul"></i>
            </div>
            <div class="stat-number">{{ $stats['total'] }}</div>
            <div class="stat-label">Totale Zone</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-number">{{ $stats['active'] }}</div>
            <div class="stat-label">Attive</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-x-circle"></i>
            </div>
            <div class="stat-number">{{ $stats['inactive'] }}</div>
            <div class="stat-label">Inattive</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-globe-europe-africa"></i>
            </div>
            <div class="stat-number">{{ collect($items)->where('name', 'LIKE', '%Europa%')->count() }}</div>
            <div class="stat-label">Zone Europa</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-globe-americas"></i>
            </div>
            <div class="stat-number">{{ collect($items)->where('name', 'LIKE', '%America%')->count() }}</div>
            <div class="stat-label">Zone Americhe</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-globe-asia-australia"></i>
            </div>
            <div class="stat-number">{{ collect($items)->where('name', 'LIKE', '%Asia%')->count() }}</div>
            <div class="stat-label">Zone Asia-Pacifico</div>
        </div>
    </div>

    <!-- Controls Section -->
    <div class="controls-section">
        <div class="search-container">
            <i class="bi bi-search search-icon"></i>
            <input type="text" 
                   class="search-input" 
                   id="searchInput"
                   placeholder="Cerca per codice o nome zona..."
                   value="{{ request('search') }}"
                   onkeyup="performSearch()">
        </div>
    </div>

    <!-- Data Table -->
    <div class="data-container">
        @if($items->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Codice</th>
                        <th>Nome</th>
                        <th>Descrizione</th>
                        <th>Ordine</th>
                        <th>Stato</th>
                        <th style="width: 120px;">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td><strong>{{ $item->code }}</strong></td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->description ?? '-' }}</td>
                            <td>{{ $item->sort_order }}</td>
                            <td>
                                <span class="status-badge {{ $item->active ? 'status-active' : 'status-inactive' }}">
                                    {{ $item->active ? 'Attiva' : 'Inattiva' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button type="button" 
                                            class="btn-edit" 
                                            onclick="editZone({{ $item->id }})"
                                            title="Modifica">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn-delete" 
                                            onclick="deleteZone({{ $item->id }})"
                                            title="Elimina">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <i class="bi bi-globe"></i>
                <h4>Nessuna zona trovata</h4>
                <p>{{ request('search') ? 'Nessun risultato per la ricerca corrente.' : 'Inizia aggiungendo la prima zona.' }}</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal Zona -->
<div class="modal fade" id="zoneModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zoneModalTitle">
                    <i class="bi bi-globe-americas me-2"></i>
                    Nuova Zona
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="zoneForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="code" class="form-label">Codice Zona *</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="code" 
                                       name="code" 
                                       placeholder="es. EUR, USA, ASIA"
                                       required 
                                       maxlength="20"
                                       pattern="[A-Z0-9_-]+"
                                       style="text-transform: uppercase;">
                                <small class="text-muted">Solo lettere maiuscole, numeri, underscore e trattini</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sort_order" class="form-label">Ordine Visualizzazione</label>
                                <input type="number" 
                                       class="form-control" 
                                       id="sort_order" 
                                       name="sort_order" 
                                       placeholder="0"
                                       min="0"
                                       max="9999">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="name" class="form-label">Nome Zona *</label>
                        <input type="text" 
                               class="form-control" 
                               id="name" 
                               name="name" 
                               placeholder="es. Europa, Nord America, Asia-Pacifico"
                               required 
                               maxlength="150">
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Descrizione</label>
                        <textarea class="form-control" 
                                  id="description" 
                                  name="description" 
                                  rows="3"
                                  maxlength="500"
                                  placeholder="Descrizione dettagliata della zona geografica o commerciale..."></textarea>
                    </div>
                    
                    <div class="form-check">
                        <input type="checkbox" 
                               class="form-check-input" 
                               id="active" 
                               name="active" 
                               value="1" 
                               checked>
                        <label class="form-check-label" for="active">
                            Zona attiva
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i>
                        Salva Zona
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentZoneId = null;

// OWASP: Funzione sicura per ricerca zone
function performSearch() {
    const search = document.getElementById('searchInput').value;
    const url = new URL(window.location);
    
    if (search.trim()) {
        url.searchParams.set('search', search.trim());
    } else {
        url.searchParams.delete('search');
    }
    
    window.location.href = url.toString();
}

// Enter key per ricerca
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        performSearch();
    }
});

// Modale per aggiunta zona
function openAddModal() {
    currentZoneId = null;
    document.getElementById('zoneModalTitle').innerHTML = '<i class="bi bi-globe-americas me-2"></i>Nuova Zona';
    document.getElementById('zoneForm').reset();
    document.getElementById('zoneForm').action = '{{ route("configurations.system-tables.store", "zones") }}';
    document.getElementById('active').checked = true;
    
    // Rimuovi method spoofing se presente
    const methodInput = document.querySelector('input[name="_method"]');
    if (methodInput) {
        methodInput.remove();
    }
}

// Modifica zona
function editZone(id) {
    currentZoneId = id;
    document.getElementById('zoneModalTitle').innerHTML = '<i class="bi bi-pencil me-2"></i>Modifica Zona';
    
    // OWASP: CSRF Protection per richiesta AJAX
    fetch(`{{ route('configurations.system-tables.edit', ['zones', '__ID__']) }}`.replace('__ID__', id), {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Errore nel caricamento dei dati');
        }
        return response.json();
    })
    .then(data => {
        // Popola i campi del form
        document.getElementById('code').value = data.code || '';
        document.getElementById('name').value = data.name || '';
        document.getElementById('description').value = data.description || '';
        document.getElementById('sort_order').value = data.sort_order || 0;
        document.getElementById('active').checked = data.active == 1;
        
        // Configura form per update
        const form = document.getElementById('zoneForm');
        form.action = `{{ route('configurations.system-tables.update', ['zones', '__ID__']) }}`.replace('__ID__', id);
        
        // Aggiungi method spoofing per PUT
        let methodInput = form.querySelector('input[name="_method"]');
        if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            form.appendChild(methodInput);
        }
        methodInput.value = 'PUT';
        
        // Mostra modale
        new bootstrap.Modal(document.getElementById('zoneModal')).show();
    })
    .catch(error => {
        console.error('Errore:', error);
        alert('Errore nel caricamento dei dati della zona');
    });
}

// Eliminazione zona
function deleteZone(id) {
    if (confirm('Sei sicuro di voler eliminare questa zona?')) {
        // OWASP: CSRF Protection per delete
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('configurations.system-tables.destroy', ['zones', '__ID__']) }}`.replace('__ID__', id);
        
        // CSRF Token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfInput);
        
        // Method spoofing
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Validazione form in tempo reale
document.getElementById('code').addEventListener('input', function() {
    this.value = this.value.toUpperCase().replace(/[^A-Z0-9_-]/g, '');
});

// Controllo duplicati codice zona
let duplicateCheckTimeout;
document.getElementById('code').addEventListener('input', function() {
    clearTimeout(duplicateCheckTimeout);
    const code = this.value.trim();
    
    if (code.length >= 2) {
        duplicateCheckTimeout = setTimeout(() => {
            fetch(`{{ route('configurations.system-tables.show', 'zones') }}?check_duplicate=${encodeURIComponent(code)}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                const codeInput = document.getElementById('code');
                if (data.exists && currentZoneId === null) {
                    codeInput.style.borderColor = '#dc3545';
                    codeInput.setCustomValidity('Codice già esistente');
                } else {
                    codeInput.style.borderColor = '';
                    codeInput.setCustomValidity('');
                }
            })
            .catch(error => console.error('Errore controllo duplicati:', error));
        }, 500);
    }
});

// Submit form validation
document.getElementById('zoneForm').addEventListener('submit', function(e) {
    const code = document.getElementById('code').value.trim();
    const name = document.getElementById('name').value.trim();
    
    if (!code || !name) {
        e.preventDefault();
        alert('Compilare i campi obbligatori: Codice e Nome zona');
        return;
    }
    
    if (code.length < 2) {
        e.preventDefault();
        alert('Il codice zona deve essere di almeno 2 caratteri');
        return;
    }
});
</script>

@endsection