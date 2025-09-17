@extends('layouts.app')

@section('title', 'Causali di Magazzino')

@section('content')
<style>
    /* Design System DESIGN_SYSTEM_GESTIONALE.md - CLASSI ESATTE */
    
    /* Colori sistema */
    :root {
        --primary-gradient: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        --button-gradient: linear-gradient(135deg, #667eea, #764ba2);
        --background-gradient: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        --action-view: linear-gradient(135deg, #48cae4, #0077b6);
        --action-edit: linear-gradient(135deg, #ffd60a, #ff8500);
        --action-delete: linear-gradient(135deg, #f72585, #c5025a);
        --action-success: linear-gradient(135deg, #029D7E, #4DC9A5);
        --action-warning: linear-gradient(135deg, #ffc107, #e0a800);
        --action-secondary: linear-gradient(135deg, #6c757d, #545b62);
        --status-active: #d4edda;
        --status-inactive: #f8d7da;
        --badge-primary: rgba(102, 126, 234, 0.1);
    }

    /* Button System - CLASSI ESATTE */
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

    /* Tabelle - CLASSE ESATTA */
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

    /* Container tabella - NIENTE contenitore bianco */
    .table-container {
        overflow: hidden;
    }

    /* Layout Structure - CLASSI ESATTE */
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

    /* Filtri e ricerca */
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

    /* Mobile Cards - STRUTTURA ESATTA */
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

    /* Action buttons colori */
    .action-btn.view { background: var(--action-view); color: white; }
    .action-btn.edit { background: var(--action-edit); color: white; }
    .action-btn.delete { background: var(--action-delete); color: white; }

    .mobile-action-btn.view { background: var(--action-view); color: white; }
    .mobile-action-btn.edit { background: var(--action-edit); color: white; }
    .mobile-action-btn.delete { background: var(--action-delete); color: white; }

    /* Responsive Breakpoints ESATTI */
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
    <!-- Header Pagina - STRUTTURA ESATTA -->
    <div class="management-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <h1 class="management-title">
                    <i class="bi bi-box-seam me-3" style="color: #029D7E; font-size: 2rem;"></i>
                    Causali di Magazzino
                </h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('configurations.system-tables.index') }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-arrow-left"></i> Torna Indietro
                </a>
                <button type="button" class="btn btn-success modern-btn" data-bs-toggle="modal" data-bs-target="#createModal" style="background: var(--action-success);">
                    <i class="bi bi-plus-lg"></i> Nuova Causale
                </button>
            </div>
        </div>
    </div>

    <!-- Filtri e Ricerca - STRUTTURA ESATTA -->
    <div class="search-filters">
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-control search-input" id="searchInput" placeholder="Cerca causali..." onkeyup="filterTable()">
            </div>
            <div class="col-md-3">
                <select class="form-select filter-select" id="filterMovement" onchange="filterTable()">
                    <option value="">Tutti i movimenti</option>
                    <option value="in">Entrata</option>
                    <option value="out">Uscita</option>
                    <option value="adjustment">Rettifica</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select filter-select" id="filterCategory" onchange="filterTable()">
                    <option value="">Tutte le categorie</option>
                    <option value="ORDINARY">Ordinaria</option>
                    <option value="INVENTORY">Inventario</option>
                    <option value="PRODUCTION">Produzione</option>
                    <option value="LOSS">Perdita</option>
                    <option value="TRANSFER">Trasferimento</option>
                    <option value="RETURN">Reso</option>
                    <option value="SAMPLE">Campione</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-secondary modern-btn w-100" onclick="resetFilters()">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Tabella Desktop - STRUTTURA ESATTA -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="modern-table" id="causaliTable">
                <thead>
                    <tr>
                        <th width="15%">Codice</th>
                        <th width="25%">Descrizione</th>
                        <th width="15%">Movimento</th>
                        <th width="15%">Categoria</th>
                        <th width="10%">Priorità</th>
                        <th width="10%">Fiscale</th>
                        <th width="10%">Azioni</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($items as $causale)
                    <tr data-movement="{{ $causale->movement_type }}" data-category="{{ $causale->category }}">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="me-2" style="width: 20px; height: 20px; background-color: {{ $causale->color_hex ?? '#029D7E' }}; border-radius: 6px;"></div>
                                <strong>{{ $causale->code }}</strong>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div class="fw-bold">{{ $causale->description }}</div>
                                @if($causale->default_location)
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt me-1"></i>{{ $causale->default_location }}
                                    </small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="{{ $causale->getMovementIcon() }} me-2"></i>
                                {{ $causale->getMovementTypeLabel() }}
                            </div>
                        </td>
                        <td>
                            <span class="{{ $causale->getCategoryBadgeClass() }}">
                                {{ $causale->getCategoryLabel() }}
                            </span>
                        </td>
                        <td>
                            <span class="{{ $causale->getPriorityBadgeClass() }}">
                                {{ $causale->getPriorityLabel() }}
                            </span>
                        </td>
                        <td>
                            @if($causale->fiscal_relevant)
                                <i class="bi bi-check-circle-fill text-success" title="Rilevante fiscalmente"></i>
                            @else
                                <i class="bi bi-dash-circle text-muted" title="Non rilevante fiscalmente"></i>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button type="button" class="action-btn view" onclick="viewCausale({{ $causale->id }})" title="Visualizza">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button type="button" class="action-btn edit" onclick="editCausale({{ $causale->id }})" title="Modifica">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="action-btn delete" onclick="deleteCausale({{ $causale->id }})" title="Elimina">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="noResults">
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox display-4 d-block mb-3 opacity-50"></i>
                                <h4>Nessuna causale di magazzino</h4>
                                <p>Clicca "Nuova Causale" per iniziare</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Cards Mobile - STRUTTURA ESATTA -->
    <div class="mobile-cards" id="mobileCards">
        @forelse($items as $causale)
            <div class="card-mobile mobile-item-row" data-movement="{{ $causale->movement_type }}" data-category="{{ $causale->category }}">
                
                <div class="item-card-header d-flex justify-content-between align-items-start mb-3">
                    <h3 class="item-card-title d-flex align-items-center">
                        <div class="me-2" style="width: 24px; height: 24px; background-color: {{ $causale->color_hex ?? '#029D7E' }}; border-radius: 8px;"></div>
                        {{ $causale->code }}
                    </h3>
                    <div class="d-flex flex-column align-items-end">
                        <span class="status-badge {{ $causale->getPriorityBadgeClass() }}">{{ $causale->getPriorityLabel() }}</span>
                    </div>
                </div>
                
                <div class="item-card-details mb-3">
                    <div class="fw-bold mb-2">{{ $causale->description }}</div>
                    
                    <div class="item-detail d-flex justify-content-between">
                        <span class="item-detail-label">Movimento</span>
                        <span class="item-detail-value d-flex align-items-center">
                            <i class="{{ $causale->getMovementIcon() }} me-1"></i>
                            {{ $causale->getMovementTypeLabel() }}
                        </span>
                    </div>
                    <div class="item-detail d-flex justify-content-between">
                        <span class="item-detail-label">Categoria</span>
                        <span class="item-detail-value">
                            <span class="{{ $causale->getCategoryBadgeClass() }}">{{ $causale->getCategoryLabel() }}</span>
                        </span>
                    </div>
                    <div class="item-detail d-flex justify-content-between">
                        <span class="item-detail-label">Fiscale</span>
                        <span class="item-detail-value">
                            @if($causale->fiscal_relevant)
                                <i class="bi bi-check-circle-fill text-success"></i> Sì
                            @else
                                <i class="bi bi-dash-circle text-muted"></i> No
                            @endif
                        </span>
                    </div>
                    @if($causale->default_location)
                        <div class="item-detail d-flex justify-content-between">
                            <span class="item-detail-label">Ubicazione</span>
                            <span class="item-detail-value">{{ $causale->default_location }}</span>
                        </div>
                    @endif
                </div>
                
                <div class="item-card-actions d-flex gap-2">
                    <button type="button" class="mobile-action-btn view" onclick="viewCausale({{ $causale->id }})">
                        <i class="bi bi-eye"></i>
                        <span>Visualizza</span>
                    </button>
                    <button type="button" class="mobile-action-btn edit" onclick="editCausale({{ $causale->id }})">
                        <i class="bi bi-pencil"></i>
                        <span>Modifica</span>
                    </button>
                    <button type="button" class="mobile-action-btn delete" onclick="deleteCausale({{ $causale->id }})">
                        <i class="bi bi-trash"></i>
                        <span>Elimina</span>
                    </button>
                </div>
            </div>
        @empty
            <div class="empty-state text-center py-5">
                <i class="bi bi-inbox display-1 text-muted mb-3"></i>
                <h4>Nessun dato trovato</h4>
                <p>Nessuna causale di magazzino configurata</p>
            </div>
        @endforelse
    </div>

    <!-- Paginazione -->
    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>

<!-- Modal Standard - STRUTTURA ESATTA -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #029D7E, #4DC9A5); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title">
                    <i class="bi bi-plus-lg me-2"></i><span id="modalTitle">Nuova Causale di Magazzino</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="causaleForm" method="POST">
                @csrf
                <div id="methodField"></div>
                <div class="modal-body" style="padding: 2rem;">
                    <div class="row g-3">
                        <!-- Prima riga -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-code-square me-1 text-primary"></i>
                                Codice Causale *
                            </label>
                            <input type="text" class="form-control search-input" name="code" id="code" required 
                                   placeholder="es. ENT_ACQ" maxlength="20">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-palette me-1 text-primary"></i>
                                Colore Identificativo
                            </label>
                            <input type="color" class="form-control" name="color_hex" id="color_hex" 
                                   value="#029D7E" required>
                        </div>

                        <!-- Seconda riga -->
                        <div class="col-12">
                            <label class="form-label fw-bold">
                                <i class="bi bi-card-text me-1 text-primary"></i>
                                Descrizione *
                            </label>
                            <input type="text" class="form-control search-input" name="description" id="description" 
                                   required placeholder="es. Entrata merce da acquisto" maxlength="255">
                        </div>

                        <!-- Terza riga -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-arrow-left-right me-1 text-primary"></i>
                                Tipo Movimento *
                            </label>
                            <select class="form-select filter-select" name="movement_type" id="movement_type" required>
                                <option value="">Seleziona...</option>
                                <option value="in">Entrata</option>
                                <option value="out">Uscita</option>
                                <option value="adjustment">Rettifica</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-tags me-1 text-primary"></i>
                                Categoria *
                            </label>
                            <select class="form-select filter-select" name="category" id="category" required>
                                <option value="">Seleziona...</option>
                                <option value="ORDINARY">Ordinaria</option>
                                <option value="INVENTORY">Inventario</option>
                                <option value="PRODUCTION">Produzione</option>
                                <option value="LOSS">Perdita</option>
                                <option value="TRANSFER">Trasferimento</option>
                                <option value="RETURN">Reso</option>
                                <option value="SAMPLE">Campione</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">
                                <i class="bi bi-exclamation-triangle me-1 text-primary"></i>
                                Priorità *
                            </label>
                            <select class="form-select filter-select" name="priority_level" id="priority_level" required>
                                <option value="">Seleziona...</option>
                                <option value="LOW">Bassa</option>
                                <option value="MEDIUM" selected>Media</option>
                                <option value="HIGH">Alta</option>
                                <option value="CRITICAL">Critica</option>
                            </select>
                        </div>

                        <!-- Controlli -->
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="affects_cost" id="affects_cost" checked>
                                <label class="form-check-label fw-bold" for="affects_cost">
                                    Influenza il costo del prodotto
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="fiscal_relevant" id="fiscal_relevant">
                                <label class="form-check-label fw-bold" for="fiscal_relevant">
                                    Rilevante fiscalmente
                                </label>
                            </div>
                        </div>

                        <!-- Opzioni avanzate -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-geo-alt me-1 text-primary"></i>
                                Ubicazione Predefinita
                            </label>
                            <input type="text" class="form-control search-input" name="default_location" id="default_location" 
                                   placeholder="es. Magazzino Principale" maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="bi bi-bell me-1 text-primary"></i>
                                Soglia Notifica (€)
                            </label>
                            <input type="number" step="0.01" min="0" max="999999.99" class="form-control search-input" 
                                   name="notify_threshold" id="notify_threshold" placeholder="0.00">
                        </div>

                        <!-- Note compliance -->
                        <div class="col-12">
                            <label class="form-label fw-bold">
                                <i class="bi bi-shield-check me-1 text-primary"></i>
                                Note Conformità
                            </label>
                            <textarea class="form-control search-input" name="compliance_notes" id="compliance_notes" 
                                      rows="3" maxlength="500" placeholder="Note aggiuntive per conformità normativa italiana..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 1.5rem 2rem; border-top: 1px solid #e9ecef;">
                    <button type="button" class="btn btn-secondary modern-btn" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Annulla
                    </button>
                    <button type="submit" class="btn btn-success modern-btn" style="background: var(--action-success);">
                        <i class="bi bi-check-lg"></i> <span id="submitText">Salva</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// JavaScript seguendo il template della documentazione

let editingId = null;

// Template filtri base dalla documentazione
function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const movementFilter = document.getElementById('filterMovement').value;
    const categoryFilter = document.getElementById('filterCategory').value;
    
    // Filtro tabella desktop
    const rows = document.querySelectorAll('#tableBody tr:not(#noResults)');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const code = row.cells[0]?.textContent.toLowerCase() || '';
        const description = row.cells[1]?.textContent.toLowerCase() || '';
        const movement = row.getAttribute('data-movement') || '';
        const category = row.getAttribute('data-category') || '';
        
        const matchesSearch = code.includes(searchTerm) || description.includes(searchTerm);
        const matchesMovement = !movementFilter || movement === movementFilter;
        const matchesCategory = !categoryFilter || category === categoryFilter;
        
        const shouldShow = matchesSearch && matchesMovement && matchesCategory;
        row.style.display = shouldShow ? '' : 'none';
        
        if (shouldShow) visibleCount++;
    });
    
    // Filtro mobile cards con animazioni dalla documentazione
    const mobileCards = document.querySelectorAll('.mobile-item-row');
    let mobileVisibleCount = 0;
    
    mobileCards.forEach(card => {
        const code = card.querySelector('.item-card-title')?.textContent.toLowerCase() || '';
        const description = card.querySelector('.fw-bold')?.textContent.toLowerCase() || '';
        const movement = card.getAttribute('data-movement') || '';
        const category = card.getAttribute('data-category') || '';
        
        const matchesSearch = code.includes(searchTerm) || description.includes(searchTerm);
        const matchesMovement = !movementFilter || movement === movementFilter;
        const matchesCategory = !categoryFilter || category === categoryFilter;
        
        const shouldShow = matchesSearch && matchesMovement && matchesCategory;
        
        // Animazione show/hide mobile cards dalla documentazione
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
    document.getElementById('filterMovement').value = '';
    document.getElementById('filterCategory').value = '';
    filterTable();
}

// CRUD Operations
function viewCausale(id) {
    window.location.href = `/configurations/system-tables/warehouse_causes/${id}`;
}

function editCausale(id) {
    editingId = id;
    document.getElementById('modalTitle').textContent = 'Modifica Causale di Magazzino';
    document.getElementById('submitText').textContent = 'Aggiorna';
    
    fetch(`/configurations/system-tables/warehouse_causes/${id}`)
        .then(response => response.json())
        .then(data => {
            // Popola form
            Object.keys(data).forEach(key => {
                const field = document.getElementById(key);
                if (field) {
                    if (field.type === 'checkbox') {
                        field.checked = Boolean(data[key]);
                    } else {
                        field.value = data[key] || '';
                    }
                }
            });
            
            document.getElementById('causaleForm').action = `/configurations/system-tables/warehouse_causes/${id}`;
            document.getElementById('methodField').innerHTML = '@method("PUT")';
            new bootstrap.Modal(document.getElementById('createModal')).show();
        })
        .catch(error => {
            console.error('Errore:', error);
            alert('Errore nel caricamento dei dati');
        });
}

function deleteCausale(id) {
    if (confirm('Sei sicuro di voler eliminare questa causale di magazzino?')) {
        fetch(`/configurations/system-tables/warehouse_causes/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Errore durante l\'eliminazione');
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            alert('Errore durante l\'eliminazione');
        });
    }
}

function exportData() {
    window.location.href = '/configurations/system-tables/warehouse_causes/export';
}

// Reset form per nuovo elemento
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('createModal');
    const form = document.getElementById('causaleForm');

    // Reset quando si apre modal per creazione
    modal.addEventListener('show.bs.modal', function() {
        if (!editingId) {
            form.reset();
            form.action = '/configurations/system-tables/warehouse_causes';
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('modalTitle').textContent = 'Nuova Causale di Magazzino';
            document.getElementById('submitText').textContent = 'Salva';
            document.getElementById('color_hex').value = '#029D7E';
            document.getElementById('priority_level').value = 'MEDIUM';
            document.getElementById('affects_cost').checked = true;
        }
        editingId = null;
    });

    // Submit form
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(modal).hide();
                location.reload();
            } else {
                alert('Errore durante il salvataggio');
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            alert('Errore durante il salvataggio');
        });
    });
});
</script>
@endsection