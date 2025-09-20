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

.stat-icon.alimentare {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.stat-icon.moda {
    background: linear-gradient(135deg, #e91e63, #ad1457);
}

.stat-icon.elettronica {
    background: linear-gradient(135deg, #2196f3, #1976d2);
}

.stat-icon.casa {
    background: linear-gradient(135deg, #ff9800, #f57c00);
}

.stat-icon.servizi {
    background: linear-gradient(135deg, #9c27b0, #7b1fa2);
}

.stat-icon.certifications {
    background: linear-gradient(135deg, #ffc107, #ff8f00);
}

.stat-icon.seasonal {
    background: linear-gradient(135deg, #00bcd4, #0097a7);
}

.stat-icon.high-risk {
    background: linear-gradient(135deg, #f44336, #d32f2f);
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

.category-alimentare {
    color: #155724;
    background: #d4edda;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.category-moda {
    color: #721c24;
    background: #f8d7da;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.category-elettronica {
    color: #055160;
    background: #cff4fc;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.category-casa {
    color: #664d03;
    background: #fff3cd;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.category-servizi {
    color: #432874;
    background: #e2e3ff;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.category-generale {
    color: #495057;
    background: #f8f9fa;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.risk-basso {
    color: #155724;
    background: #d4edda;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.risk-medio {
    color: #664d03;
    background: #fff3cd;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
}

.risk-alto {
    color: #721c24;
    background: #f8d7da;
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
                    <i class="bi bi-diagram-3 me-3" style="color: #029D7E; font-size: 2rem;"></i>
                    Settori Merceologici
                </h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('configurations.system-tables.index') }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-arrow-left"></i> Torna Indietro
                </a>
                <button type="button" class="btn btn-success modern-btn" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-lg"></i> Nuovo Settore Merceologico
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
                <div class="stat-icon alimentare">
                    <i class="bi bi-apple"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['alimentare'] }}</h3>
                    <p>Alimentare</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon moda">
                    <i class="bi bi-bag"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['moda'] }}</h3>
                    <p>Moda</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon elettronica">
                    <i class="bi bi-cpu"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['elettronica'] }}</h3>
                    <p>Elettronica</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon casa">
                    <i class="bi bi-house"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['casa'] }}</h3>
                    <p>Casa</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon servizi">
                    <i class="bi bi-tools"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['servizi'] }}</h3>
                    <p>Servizi</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon certifications">
                    <i class="bi bi-award"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['requires_certifications'] }}</h3>
                    <p>Con Certificazioni</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon seasonal">
                    <i class="bi bi-calendar4-range"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['seasonal'] }}</h3>
                    <p>Stagionali</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon high-risk">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['high_risk'] }}</h3>
                    <p>Alto Rischio</p>
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
                <input type="text" class="form-control search-input" id="searchInput" placeholder="Cerca settori merceologici..." onkeyup="filterTable()">
            </div>
            <div class="col-md-2">
                <select class="form-select filter-select" id="statusFilter" onchange="filterTable()">
                    <option value="">Tutti gli Stati</option>
                    <option value="1">Attivi</option>
                    <option value="0">Inattivi</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select filter-select" id="categoryFilter" onchange="filterTable()">
                    <option value="">Tutte le Categorie</option>
                    <option value="alimentare">Alimentare</option>
                    <option value="moda">Moda</option>
                    <option value="elettronica">Elettronica</option>
                    <option value="casa">Casa</option>
                    <option value="salute">Salute</option>
                    <option value="bellezza">Bellezza</option>
                    <option value="sport">Sport</option>
                    <option value="tempo_libero">Tempo Libero</option>
                    <option value="automotive">Automotive</option>
                    <option value="servizi">Servizi</option>
                    <option value="industriale">Industriale</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select filter-select" id="riskFilter" onchange="filterTable()">
                    <option value="">Tutti i Rischi</option>
                    <option value="basso">Rischio Basso</option>
                    <option value="medio">Rischio Medio</option>
                    <option value="alto">Rischio Alto</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select filter-select" id="seasonalFilter" onchange="filterTable()">
                    <option value="">Tutti</option>
                    <option value="1">Stagionali</option>
                    <option value="0">Non Stagionali</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-secondary modern-btn w-100" onclick="resetFilters()">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Tabella Desktop -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="modern-table" id="merchandiseTable">
                <thead>
                    <tr>
                        <th>CODICE</th>
                        <th>NOME</th>
                        <th>CATEGORIA</th>
                        <th>MARGINE</th>
                        <th>RISCHIO</th>
                        <th>CERTIFICAZIONI</th>
                        <th>STATO</th>
                        <th width="120">AZIONI</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($items as $item)
                        <tr data-active="{{ $item->active ? '1' : '0' }}" 
                            data-category="{{ $item->category }}" 
                            data-risk="{{ $item->risk_level }}"
                            data-seasonal="{{ $item->seasonal ? '1' : '0' }}">
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
                                <span class="category-{{ $item->category }}">
                                    {{ $item->formatted_category }}
                                </span>
                                @if($item->seasonal)
                                    <div><small class="badge bg-info mt-1">Stagionale</small></div>
                                @endif
                            </td>
                            <td>
                                @if($item->average_margin)
                                    <span class="badge bg-success">{{ $item->formatted_margin }}</span>
                                @else
                                    <span class="text-muted">N/D</span>
                                @endif
                            </td>
                            <td>
                                <span class="risk-{{ $item->risk_level }}">
                                    @if($item->risk_level == 'basso')
                                        <i class="bi bi-check-circle me-1"></i>Basso
                                    @elseif($item->risk_level == 'medio')
                                        <i class="bi bi-exclamation-circle me-1"></i>Medio
                                    @else
                                        <i class="bi bi-x-circle me-1"></i>Alto
                                    @endif
                                </span>
                            </td>
                            <td>
                                @if($item->requires_certifications)
                                    <span class="badge bg-warning">
                                        <i class="bi bi-award me-1"></i>Richieste
                                    </span>
                                @else
                                    <span class="text-muted">Nessuna</span>
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
                                    <button type="button" class="action-btn edit" title="Modifica settore" onclick="editItem({{ $item->id }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="action-btn delete" title="Elimina settore" onclick="deleteItem({{ $item->id }}, '{{ $item->name }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="noResults" style="display: none;">
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="bi bi-diagram-3 display-1 text-muted mb-3"></i>
                                    <h4>Nessun settore merceologico trovato</h4>
                                    <p class="text-muted">Non sono stati trovati settori per i filtri selezionati.</p>
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
                 data-category="{{ $item->category }}" 
                 data-risk="{{ $item->risk_level }}"
                 data-seasonal="{{ $item->seasonal ? '1' : '0' }}">
                
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
                        <span class="item-detail-label">Categoria</span>
                        <span class="item-detail-value">{{ $item->formatted_category }}</span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-label">Margine Medio</span>
                        <span class="item-detail-value">{{ $item->formatted_margin }}</span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-label">Livello Rischio</span>
                        <span class="item-detail-value">{{ $item->formatted_risk_level }}</span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-label">Certificazioni</span>
                        <span class="item-detail-value">{{ $item->requires_certifications ? 'Richieste' : 'Nessuna' }}</span>
                    </div>
                    <div class="item-detail">
                        <span class="item-detail-label">Stagionale</span>
                        <span class="item-detail-value">{{ $item->seasonal ? 'Sì' : 'No' }}</span>
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
                <i class="bi bi-diagram-3"></i>
                <h4>Nessun settore merceologico trovato</h4>
                <p>Non sono stati trovati settori per i filtri selezionati.</p>
            </div>
        @endforelse
    </div>

</div>

<!-- Modal Settore Merceologico -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #029D7E, #4DC9A5); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title">
                    <i class="bi bi-diagram-3 me-2"></i>
                    <span id="modalTitle">Nuovo Settore Merceologico</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="merchandiseSectorForm">
                <div class="modal-body" style="padding: 2rem;">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label">Codice *</label>
                            <input type="text" class="form-control" id="code" name="code" required maxlength="20" style="text-transform: uppercase;" oninput="validateCode()">
                            <div class="invalid-feedback" id="codeError"></div>
                            <small class="form-text text-muted">Es: ALI001, MOD001, ELE001</small>
                        </div>
                        <div class="col-md-6">
                            <label for="category" class="form-label">Categoria *</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">Seleziona categoria...</option>
                                <option value="alimentare">Alimentare e Bevande</option>
                                <option value="moda">Moda e Abbigliamento</option>
                                <option value="elettronica">Elettronica e Tecnologia</option>
                                <option value="casa">Casa e Arredamento</option>
                                <option value="salute">Salute e Farmaceutici</option>
                                <option value="bellezza">Bellezza e Cosmetici</option>
                                <option value="sport">Sport e Fitness</option>
                                <option value="tempo_libero">Tempo Libero</option>
                                <option value="automotive">Automotive</option>
                                <option value="servizi">Servizi</option>
                                <option value="industriale">Industriale</option>
                                <option value="generale">Generale</option>
                            </select>
                            <div class="invalid-feedback" id="categoryError"></div>
                            <small class="form-text text-muted">Settore di appartenenza</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome *</label>
                        <input type="text" class="form-control" id="name" name="name" required maxlength="150">
                        <div class="invalid-feedback" id="nameError"></div>
                        <small class="form-text text-muted">Nome del settore merceologico</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Descrizione</label>
                        <textarea class="form-control" id="description" name="description" maxlength="1000" rows="3"></textarea>
                        <div class="invalid-feedback" id="descriptionError"></div>
                        <small class="form-text text-muted">Descrizione dettagliata del settore</small>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="risk_level" class="form-label">Livello di Rischio *</label>
                            <select class="form-select" id="risk_level" name="risk_level" required>
                                <option value="">Seleziona rischio...</option>
                                <option value="basso">Rischio Basso</option>
                                <option value="medio">Rischio Medio</option>
                                <option value="alto">Rischio Alto</option>
                            </select>
                            <div class="invalid-feedback" id="risk_levelError"></div>
                            <small class="form-text text-muted">Livello di rischio commerciale</small>
                        </div>
                        <div class="col-md-4">
                            <label for="average_margin" class="form-label">Margine Medio (%)</label>
                            <input type="number" class="form-control" id="average_margin" name="average_margin" min="0" max="100" step="0.01">
                            <div class="invalid-feedback" id="average_marginError"></div>
                            <small class="form-text text-muted">Margine medio del settore</small>
                        </div>
                        <div class="col-md-4">
                            <label for="sort_order" class="form-label">Ordine</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order" min="0" max="9999" value="0">
                            <div class="invalid-feedback" id="sort_orderError"></div>
                            <small class="form-text text-muted">Ordine visualizzazione</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="certifications_input" class="form-label">Certificazioni</label>
                        <input type="text" class="form-control" id="certifications_input" placeholder="Es: HACCP, ISO 22000, CE">
                        <small class="form-text text-muted">Inserisci le certificazioni separate da virgola</small>
                        <input type="hidden" id="certifications" name="certifications">
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="requires_certifications" name="requires_certifications">
                                <label class="form-check-label" for="requires_certifications">Richiede Certificazioni</label>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="seasonal" name="seasonal">
                                <label class="form-check-label" for="seasonal">Settore Stagionale</label>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="active" name="active" checked>
                                <label class="form-check-label" for="active">Settore Attivo</label>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer" style="padding: 1.5rem 2rem; border-top: 1px solid #e9ecef;">
                    <button type="button" class="btn btn-secondary modern-btn" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Annulla
                    </button>
                    <button type="submit" class="btn btn-success modern-btn" id="saveBtn">
                        <i class="bi bi-check-lg"></i> Salva Settore Merceologico
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// JavaScript per gestione CRUD
let editingId = null;

function filterTable() {
    const searchValue = document.getElementById('searchInput').value.toLowerCase();
    const statusValue = document.getElementById('statusFilter').value;
    const categoryValue = document.getElementById('categoryFilter').value;
    const riskValue = document.getElementById('riskFilter').value;
    const seasonalValue = document.getElementById('seasonalFilter').value;
    
    const tableRows = document.querySelectorAll('#tableBody tr[data-active]');
    const mobileCards = document.querySelectorAll('.mobile-item-row[data-active]');
    
    let visibleCount = 0;
    
    // Filtro tabella desktop
    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const active = row.getAttribute('data-active');
        const category = row.getAttribute('data-category');
        const risk = row.getAttribute('data-risk');
        const seasonal = row.getAttribute('data-seasonal');
        
        let show = true;
        
        if (searchValue && !text.includes(searchValue)) show = false;
        if (statusValue && active !== statusValue) show = false;
        if (categoryValue && category !== categoryValue) show = false;
        if (riskValue && risk !== riskValue) show = false;
        if (seasonalValue && seasonal !== seasonalValue) show = false;
        
        row.style.display = show ? '' : 'none';
        if (show) visibleCount++;
    });
    
    // Filtro cards mobile
    mobileCards.forEach(card => {
        const text = card.textContent.toLowerCase();
        const active = card.getAttribute('data-active');
        const category = card.getAttribute('data-category');
        const risk = card.getAttribute('data-risk');
        const seasonal = card.getAttribute('data-seasonal');
        
        let show = true;
        
        if (searchValue && !text.includes(searchValue)) show = false;
        if (statusValue && active !== statusValue) show = false;
        if (categoryValue && category !== categoryValue) show = false;
        if (riskValue && risk !== riskValue) show = false;
        if (seasonalValue && seasonal !== seasonalValue) show = false;
        
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
    document.getElementById('riskFilter').value = '';
    document.getElementById('seasonalFilter').value = '';
    filterTable();
}

function validateCode() {
    const codeInput = document.getElementById('code');
    let code = codeInput.value.toUpperCase().trim();
    
    // OWASP: Input sanitization client-side
    code = code.replace(/[^A-Z0-9]/g, '');
    
    // OWASP: Formato specifico validation (2-3 lettere + 3 numeri)
    const codeRegex = /^[A-Z]{2,3}[0-9]{3}$/;
    
    if (code.length >= 5) { // Minimo per formato ALI001
        // OWASP: Rate limiting client-side
        if (window.lastValidationTime && (Date.now() - window.lastValidationTime) < 500) {
            return; // Throttling
        }
        window.lastValidationTime = Date.now();
        
        if (!codeRegex.test(code)) {
            const errorDiv = document.getElementById('codeError');
            codeInput.classList.add('is-invalid');
            errorDiv.textContent = 'Formato codice non valido (es: ALI001, MOD001)';
            return;
        }
        
        // OWASP: Secure AJAX request with CSRF protection
        fetch(`/configurations/system-tables/merchandising_sectors?check_duplicate=${encodeURIComponent(code)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            const errorDiv = document.getElementById('codeError');
            if (data.error) {
                codeInput.classList.add('is-invalid');
                errorDiv.textContent = data.error;
            } else if (data.exists) {
                codeInput.classList.add('is-invalid');
                errorDiv.textContent = 'Codice già esistente';
            } else {
                codeInput.classList.remove('is-invalid');
                errorDiv.textContent = '';
            }
        })
        .catch(error => {
            console.error('Errore validazione codice:', error);
            const errorDiv = document.getElementById('codeError');
            codeInput.classList.add('is-invalid');
            errorDiv.textContent = 'Errore durante la validazione';
        });
    }
}

function editItem(id) {
    editingId = id;
    
    fetch(`/configurations/system-tables/merchandising_sectors/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalTitle').textContent = 'Modifica Settore Merceologico';
            document.getElementById('saveBtn').innerHTML = '<i class="bi bi-check-lg"></i> Aggiorna Settore Merceologico';
            
            // Popola i campi
            document.getElementById('code').value = data.code || '';
            document.getElementById('name').value = data.name || '';
            document.getElementById('description').value = data.description || '';
            document.getElementById('category').value = data.category || '';
            document.getElementById('risk_level').value = data.risk_level || '';
            document.getElementById('average_margin').value = data.average_margin || '';
            document.getElementById('sort_order').value = data.sort_order || 0;
            document.getElementById('requires_certifications').checked = data.requires_certifications;
            document.getElementById('seasonal').checked = data.seasonal;
            document.getElementById('active').checked = data.active;
            
            // Gestione certificazioni
            if (data.certifications && data.certifications.length > 0) {
                document.getElementById('certifications_input').value = data.certifications.join(', ');
            } else {
                document.getElementById('certifications_input').value = '';
            }
            
            new bootstrap.Modal(document.getElementById('createModal')).show();
        })
        .catch(error => {
            console.error('Errore caricamento dati:', error);
            alert('Errore nel caricamento dei dati per la modifica');
        });
}

function deleteItem(id, name) {
    if (confirm(`Sei sicuro di voler eliminare il settore "${name}"?\n\nQuesta azione non può essere annullata.`)) {
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('_method', 'DELETE');
        
        fetch(`/configurations/system-tables/merchandising_sectors/${id}`, {
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
            alert('Errore durante l\'eliminazione del settore merceologico');
        });
    }
}

function saveMerchandiseSector() {
    const form = document.getElementById('merchandiseSectorForm');
    const formData = new FormData(form);
    
    // Fix per checkbox
    const activeCheckbox = document.getElementById('active');
    const requiresCertCheckbox = document.getElementById('requires_certifications');
    const seasonalCheckbox = document.getElementById('seasonal');
    formData.set('active', activeCheckbox.checked ? '1' : '0');
    formData.set('requires_certifications', requiresCertCheckbox.checked ? '1' : '0');
    formData.set('seasonal', seasonalCheckbox.checked ? '1' : '0');
    
    // Gestione certificazioni
    const certificationsText = document.getElementById('certifications_input').value;
    if (certificationsText.trim()) {
        const certifications = certificationsText.split(',').map(cert => cert.trim()).filter(cert => cert);
        formData.set('certifications', JSON.stringify(certifications));
    } else {
        formData.set('certifications', JSON.stringify([]));
    }
    
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    let url = '/configurations/system-tables/merchandising_sectors';
    let method = 'POST';
    
    if (editingId) {
        url = `/configurations/system-tables/merchandising_sectors/${editingId}`;
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
        alert('Errore durante il salvataggio del settore merceologico');
    });
}

function resetCreateForm() {
    editingId = null;
    document.getElementById('modalTitle').textContent = 'Nuovo Settore Merceologico';
    document.getElementById('saveBtn').innerHTML = '<i class="bi bi-check-lg"></i> Salva Settore Merceologico';
    document.getElementById('merchandiseSectorForm').reset();
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    document.getElementById('sort_order').value = 0;
    document.getElementById('active').checked = true;
    document.getElementById('requires_certifications').checked = false;
    document.getElementById('seasonal').checked = false;
    document.getElementById('certifications_input').value = '';
}


// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Form submit
    const form = document.getElementById('merchandiseSectorForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            saveMerchandiseSector();
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