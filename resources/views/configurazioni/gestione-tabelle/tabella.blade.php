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
    
    /* Tabella moderna */
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
    
    /* Badge */
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

    /* Stili per associazioni */
    .association-name {
        font-weight: 700;
        color: #2d3748;
        font-size: 1rem;
    }

    .association-description {
        color: #718096;
        font-size: 0.875rem;
        font-style: italic;
    }

    .vat-nature-description {
        color: #718096;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .status-badge.default {
        background-color: #fff3e0;
        color: #ef6c00;
    }

    .status-badge.normal {
        background-color: #e3f2fd;
        color: #1565c0;
    }

    .badge {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-weight: 600;
        display: inline-block;
        vertical-align: middle;
        margin-right: 0.5rem;
    }
    
    /* Action buttons */
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
    
    /* Metriche Performance - Golden Standard */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .metric-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
    }
    
    .metric-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        border-radius: 20px 20px 0 0;
    }
    
    /* Colori specifici per ogni card secondo Golden Standard */
    .metric-card:nth-child(1)::before {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
    }
    
    .metric-card:nth-child(2)::before {
        background: linear-gradient(135deg, #48cae4, #0077b6);
    }
    
    .metric-card:nth-child(3)::before {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
    }
    
    .metric-card:nth-child(4)::before {
        background: linear-gradient(135deg, #f72585, #c5025a);
    }
    
    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }
    
    .metric-value {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
        margin-bottom: 0.5rem;
    }
    
    .metric-card:nth-child(1) .metric-value {
        color: #029D7E;
    }
    
    .metric-card:nth-child(2) .metric-value {
        color: #0077b6;
    }
    
    .metric-card:nth-child(3) .metric-value {
        color: #ff8500;
    }
    
    .metric-card:nth-child(4) .metric-value {
        color: #c5025a;
    }
    
    .metric-label {
        font-size: 0.95rem;
        color: #718096;
        font-weight: 600;
        margin: 0;
    }
    
    /* Mobile Cards - Nasconde tabella, mostra card */
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
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .item-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
        flex: 1;
        min-width: 0;
    }
    
    .item-card-actions {
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
        
        .item-card {
            padding: 1rem;
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
    
    /* Stats Panel */
    .stats-panel {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
    }
    
    .stat-item {
        text-align: center;
        padding: 1rem;
        border-radius: 12px;
        background: rgba(2, 157, 126, 0.05);
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: #029D7E;
        display: block;
    }
    
    .stat-label {
        font-size: 0.8rem;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ===== BADGE SISTEMAZIONE CSS ===== */
    /* Badge per codici - Colore azzurro uniforme */
    .badge-code {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
    }

    /* Badge per percentuali e valori numerici */
    .badge-percentage {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
    }

    /* Badge per livelli e ordinamenti */
    .badge-level {
        background: linear-gradient(135deg, #6f42c1, #563d7c);
        color: white;
    }

    /* Badge per ordinamenti e sort */
    .badge-sort {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }

    /* Badge per stati speciali */
    .badge-special {
        background: linear-gradient(135deg, #f093fb, #f5576c);
        color: white;
        font-size: 1rem;
    }

    /* Codici formattati (BIC/SWIFT, etc) */
    .code-formatted {
        font-size: 0.9rem;
        background: #f8f9fa;
        padding: 4px 8px;
        border-radius: 4px;
    }

    /* Icone header tabelle */
    .table-header-icon {
        color: #48cae4;
        font-size: 2rem;
    }

    /* Styling per gerarchie */
    .hierarchy-prefix {
        color: #ccc;
    }

    /* ===== MODALI SISTEMAZIONE CSS ===== */
    /* Content modali uniforme */
    .modal-content-custom {
        border-radius: 20px;
        border: none;
    }

    /* Header modali uniforme */
    .modal-header-custom {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        border-radius: 20px 20px 0 0;
    }

    /* Footer modali uniforme */
    .modal-footer-custom {
        padding: 1.5rem 2rem;
        border-top: 1px solid #e9ecef;
    }

    /* Body modali con padding consistente */
    .modal-body-custom {
        padding: 2rem;
    }

    /* Colonne centrate nelle tabelle */
    .table-center-column {
        text-align: center;
    }

    /* Allineamento verticale celle */
    .table-cell-middle {
        vertical-align: middle;
    }

    /* Status badge piccolo nelle card */
    .status-badge-small {
        font-size: 0.7rem;
    }

    /* Descrizione nelle card */
    .card-description {
        font-size: 0.9rem;
        color: #718096;
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
                    <i class="{{ $configurazione['icona'] ?? 'bi-gear' }} me-3 table-header-icon"></i>
                    {{ $configurazione['nome'] ?? ucfirst($nomeTabella) }}
                </h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('configurations.gestione-tabelle.index') }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-arrow-left"></i> Torna Indietro
                </a>
                @if($nomeTabella === 'associazioni-nature-iva')
                <button type="button" class="btn btn-success modern-btn" onclick="openAddModal()">
                    <i class="bi bi-plus-lg"></i> Nuova Associazione
                </button>
                @elseif($nomeTabella === 'aliquote-iva')
                <button type="button" class="btn btn-success modern-btn" onclick="openAliquotaModal()">
                    <i class="bi bi-plus-lg"></i> Nuova Aliquota IVA
                </button>
                @elseif($nomeTabella === 'banche')
                <button type="button" class="btn btn-success modern-btn" onclick="openBancaModal()">
                    <i class="bi bi-plus-lg"></i> Nuova Banca
                </button>
                @elseif($nomeTabella === 'categorie-articoli')
                <button type="button" class="btn btn-success modern-btn" onclick="openCategoriaModal()">
                    <i class="bi bi-plus-lg"></i> Nuova Categoria
                </button>
                @elseif($nomeTabella === 'categorie-clienti')
                <button type="button" class="btn btn-success modern-btn" onclick="openCategoriaClientiModal()">
                    <i class="bi bi-plus-lg"></i> Nuova Categoria Clienti
                </button>
                @else
                <button type="button" class="btn btn-success modern-btn" data-bs-toggle="modal" data-bs-target="#genericModal">
                    <i class="bi bi-plus-lg"></i> Nuova {{ $configurazione['nome_singolare'] ?? $configurazione['nome'] ?? 'Elemento' }}
                </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistiche rimosse come richiesto dall'utente -->

    <!-- Filtri e ricerca -->
    <div class="search-filters">
        <form method="GET" action="{{ route('configurations.gestione-tabelle.tabella', $nomeTabella) }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control search-input" name="search" value="{{ request('search') }}" placeholder="Cerca elementi..." onkeyup="filterTable()">
                </div>
                <div class="col-md-3">
                    <select class="form-select filter-select" name="active" onchange="this.form.submit()">
                        <option value="">Tutti gli stati</option>
                        <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Attivo</option>
                        <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inattivo</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary modern-btn w-100">
                        <i class="bi bi-search"></i> Cerca
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('configurations.gestione-tabelle.tabella', $nomeTabella) }}" class="btn btn-secondary modern-btn w-100">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabella dati -->
    <div class="table-container">
        <div class="table-responsive">
            <table class="modern-table" id="dataTable">
                <thead>
                    <tr>
                        @if(isset($configurazione['campi_visibili']))
                            @foreach($configurazione['campi_visibili'] as $campo => $label)
                                <th>{{ $label }}</th>
                            @endforeach
                        @else
                            <th>Nome</th>
                            <th>Descrizione</th>
                            <th>Stato</th>
                        @endif
                        <th class="table-center-column">Azioni</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($dati as $item)
                        <tr data-status="{{ $item->active ?? 1 }}">
                            @if(isset($configurazione['campi_visibili']))
                                @foreach($configurazione['campi_visibili'] as $campo => $label)
                                    <td>
                                        @if($campo === 'nome_associazione')
                                            <div class="association-name">{{ $item->nome_associazione }}</div>
                                            @if($item->descrizione)
                                                <div class="association-description">{{ $item->descrizione }}</div>
                                            @endif
                                        @elseif($campo === 'aliquota_iva')
                                            <span class="badge badge-code">
                                                {{ $item->taxRate->code ?? 'N/A' }} - {{ $item->taxRate->percentuale ?? 0 }}%
                                            </span>
                                        @elseif($campo === 'natura_iva')
                                            <span class="badge badge-percentage">
                                                {{ $item->vatNature->code ?? 'N/A' }}
                                            </span>
                                            @if($item->vatNature->name ?? false)
                                                <div class="vat-nature-description">{{ $item->vatNature->name }}</div>
                                            @endif
                                        @elseif($campo === 'predefinita')
                                            @if($item->is_default)
                                                <span class="status-badge default">
                                                    <i class="bi bi-star-fill"></i> Predefinita
                                                </span>
                                            @else
                                                <span class="status-badge normal">
                                                    <i class="bi bi-star"></i> Normale
                                                </span>
                                            @endif
                                        @elseif($campo === 'data_creazione')
                                            {{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-' }}
                                        @elseif($campo === 'percentuale')
                                            <span class="badge badge-special">
                                                {{ $item->percentuale }}%
                                            </span>
                                        @elseif($campo === 'code' && $nomeTabella === 'aliquote-iva')
                                            <span class="badge badge-code">
                                                {{ $item->code }}
                                            </span>
                                        @elseif($campo === 'name' && $nomeTabella === 'aliquote-iva')
                                            <div class="association-name">{{ $item->name }}</div>
                                            @if($item->description)
                                                <div class="association-description">{{ $item->description }}</div>
                                            @endif
                                        @elseif($campo === 'name' && $nomeTabella === 'banche')
                                            <div class="association-name">{{ $item->name }}</div>
                                            @if($item->description)
                                                <div class="association-description">{{ $item->description }}</div>
                                            @endif
                                        @elseif($campo === 'abi_code')
                                            @if($item->abi_code)
                                            <span class="badge badge-code">
                                                ABI: {{ $item->abi_code }}
                                            </span>
                                            @else
                                                -
                                            @endif
                                        @elseif($campo === 'bic_swift')
                                            @if($item->bic_swift)
                                                <code class="code-formatted">{{ $item->bic_swift }}</code>
                                            @else
                                                -
                                            @endif
                                        @elseif($campo === 'code' && $nomeTabella === 'categorie-articoli')
                                            <span class="badge badge-code">
                                                {{ $item->code }}
                                            </span>
                                        @elseif($campo === 'name' && $nomeTabella === 'categorie-articoli')
                                            <div class="association-name">
                                                @for($i = 0; $i < $item->level; $i++)
                                                    <span class="hierarchy-prefix">└─</span>
                                                @endfor
                                                {{ $item->name }}
                                            </div>
                                            @if($item->description)
                                                <div class="association-description">{{ $item->description }}</div>
                                            @endif
                                        @elseif($campo === 'parent_name')
                                            @if($item->parent)
                                                <span class="badge badge-percentage">
                                                    {{ $item->parent->name }}
                                                </span>
                                            @else
                                                <span class="badge badge-code">
                                                    ROOT
                                                </span>
                                            @endif
                                        @elseif($campo === 'level')
                                            <span class="badge badge-level">
                                                Livello {{ $item->level }}
                                            </span>
                                        @elseif($campo === 'sort_order')
                                            <span class="badge badge-sort">
                                                {{ $item->sort_order ?? 0 }}
                                            </span>
                                        @elseif($campo === 'active')
                                            <span class="status-badge {{ ($item->$campo ?? true) ? 'status-active' : 'status-inactive' }}">
                                                {{ ($item->$campo ?? true) ? 'Attivo' : 'Inattivo' }}
                                            </span>
                                        @else
                                            {{ $item->$campo ?? '-' }}
                                        @endif
                                    </td>
                                @endforeach
                            @else
                                <td>{{ $item->name ?? $item->nome ?? '-' }}</td>
                                <td>{{ $item->description ?? $item->descrizione ?? '-' }}</td>
                                <td>
                                    <span class="status-badge {{ ($item->active ?? true) ? 'status-active' : 'status-inactive' }}">
                                        {{ ($item->active ?? true) ? 'Attivo' : 'Inattivo' }}
                                    </span>
                                </td>
                            @endif
                            <td class="text-center table-cell-middle">
                                <button type="button" class="action-btn view" onclick="viewItem('{{ $nomeTabella }}', {{ $item->id }})" title="Visualizza">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button type="button" class="action-btn edit" onclick="editItem('{{ $nomeTabella }}', {{ $item->id }})" title="Modifica">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="action-btn delete" onclick="deleteItem({{ $item->id }})" title="Elimina">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr id="noResults">
                            <td colspan="100%">
                                <div class="empty-state">
                                    <i class="{{ $configurazione['icona'] ?? 'bi-gear' }}"></i>
                                    <h4>Nessun elemento trovato</h4>
                                    <p>Non ci sono elementi in questa tabella.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($dati) && $dati->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $dati->links() }}
            </div>
        @endif
    </div>

    <!-- Cards Mobile -->
    <div class="mobile-cards" id="mobileCards">
        @forelse($dati as $item)
            <div class="item-card mobile-item-row" data-status="{{ $item->active ?? 1 }}">
                <div class="item-card-header">
                    <h3 class="item-card-title">{{ $item->name ?? $item->nome ?? 'Elemento #' . $item->id }}</h3>
                    <div>
                        <span class="status-badge status-badge-small {{ ($item->active ?? true) ? 'status-active' : 'status-inactive' }}">
                            {{ ($item->active ?? true) ? 'Attivo' : 'Inattivo' }}
                        </span>
                    </div>
                </div>
                
                @if($item->description ?? $item->descrizione ?? false)
                    <div class="mb-3">
                        <span class="card-description">{{ $item->description ?? $item->descrizione }}</span>
                    </div>
                @endif
                
                <div class="item-card-actions">
                    <button type="button" class="mobile-action-btn view" onclick="viewItem('{{ $nomeTabella }}', {{ $item->id }})">
                        <i class="bi bi-eye"></i>
                        <span>Visualizza</span>
                    </button>
                    <button type="button" class="mobile-action-btn edit" onclick="editItem('{{ $nomeTabella }}', {{ $item->id }})">
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
                <i class="{{ $configurazione['icona'] ?? 'bi-gear' }}"></i>
                <h4>Nessun elemento trovato</h4>
                <p>Non ci sono elementi in questa tabella.</p>
            </div>
        @endforelse
    </div>
</div>

<script>
// Funzioni di ricerca e filtro
function filterTable() {
    const searchTerm = document.querySelector('input[name="search"]').value.toLowerCase();
    
    // Filtro per tabella desktop
    const rows = document.querySelectorAll('#tableBody tr:not(#noResults)');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const matchesSearch = text.includes(searchTerm);
        
        if (matchesSearch) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Filtro per mobile cards
    const mobileCards = document.querySelectorAll('.mobile-item-row');
    let mobileVisibleCount = 0;
    
    mobileCards.forEach(card => {
        const text = card.textContent.toLowerCase();
        const matchesSearch = text.includes(searchTerm);
        
        if (matchesSearch) {
            card.style.display = '';
            mobileVisibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Mostra/nascondi messaggio vuoto
    const noResults = document.getElementById('noResults');
    if (noResults) {
        noResults.style.display = (visibleCount === 0 && mobileVisibleCount === 0) ? '' : 'none';
    }
}

// Elimina elemento
async function deleteItem(id) {
    if (confirm('Sei sicuro di voler eliminare questo elemento?')) {
        try {
            const response = await fetch(`{{ route('configurations.gestione-tabelle.destroy', [$nomeTabella, ':id']) }}`.replace(':id', id), {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                
                if (data.success) {
                    // Mostra messaggio di successo
                    showSuccessMessage(data.message || 'Elemento eliminato con successo');
                    
                    // Ricarica la pagina dopo 1 secondo per aggiornare la tabella
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showErrorMessage(data.message || 'Errore durante l\'eliminazione');
                }
            } else {
                const errorData = await response.json().catch(() => ({ message: 'Errore del server' }));
                showErrorMessage(errorData.message || 'Errore durante l\'eliminazione');
            }
        } catch (error) {
            console.error('Errore:', error);
            showErrorMessage('Errore di rete durante l\'eliminazione');
        }
    }
}

// Funzione per mostrare messaggi di successo
function showSuccessMessage(message) {
    // Rimuovi alert esistenti
    const existingAlerts = document.querySelectorAll('.dynamic-alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Crea nuovo alert di successo
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-success alert-dismissible fade show dynamic-alert';
    alertDiv.setAttribute('role', 'alert');
    alertDiv.innerHTML = `
        <i class="bi bi-check-circle me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Inserisci all'inizio del container
    const container = document.querySelector('.management-container');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-dismiss dopo 3 secondi
    setTimeout(() => {
        if (alertDiv && bootstrap.Alert.getOrCreateInstance) {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alertDiv);
            bsAlert.close();
        }
    }, 3000);
}

// Funzione per mostrare messaggi di errore
function showErrorMessage(message) {
    // Rimuovi alert esistenti
    const existingAlerts = document.querySelectorAll('.dynamic-alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Crea nuovo alert di errore
    const alertDiv = document.createElement('div');
    alertDiv.className = 'alert alert-danger alert-dismissible fade show dynamic-alert';
    alertDiv.setAttribute('role', 'alert');
    alertDiv.innerHTML = `
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Attenzione!</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    // Inserisci all'inizio del container
    const container = document.querySelector('.management-container');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-dismiss dopo 5 secondi
    setTimeout(() => {
        if (alertDiv && bootstrap.Alert.getOrCreateInstance) {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alertDiv);
            bsAlert.close();
        }
    }, 5000);
}

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
});

// Funzione per aprire modale nuova associazione
function openAddModal() {
    // Reset form
    const form = document.getElementById('associationForm');
    if (form) {
        form.reset();
        form.action = `{{ route("configurations.gestione-tabelle.store", $nomeTabella) }}`;
        
        // Rimuovi campo _method se presente
        const methodField = form.querySelector('input[name="_method"]');
        if (methodField) {
            methodField.remove();
        }
        
        // Aggiorna token CSRF se necessario
        const csrfField = form.querySelector('input[name="_token"]');
        if (csrfField) {
            const metaCsrfToken = document.querySelector('meta[name="csrf-token"]');
            if (metaCsrfToken) {
                csrfField.value = metaCsrfToken.getAttribute('content');
                console.log('CSRF token aggiornato:', csrfField.value);
            }
        }
        
        // Ripristina titolo originale
        const modalTitle = document.getElementById('associationModalTitle');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Nuova Associazione Nature IVA';
        }
        
        // Ripristina pulsante submit
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> Crea Associazione';
        }
    }
    
    // Mostra la modale
    const modal = new bootstrap.Modal(document.getElementById('associationModal'));
    modal.show();
    
    // Focus al primo campo
    setTimeout(() => {
        const nomeField = document.getElementById('modal_nome_associazione');
        if (nomeField) {
            nomeField.focus();
        }
    }, 500);
}

// Aggiungi event listener per debug form submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('associationForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submission started');
            console.log('Form action:', this.action);
            console.log('Form method:', this.method);
            
            // Controlla token CSRF
            const csrfField = this.querySelector('input[name="_token"]');
            if (csrfField) {
                console.log('CSRF token presente:', csrfField.value);
            } else {
                console.error('CSRF token MANCANTE!');
            }
            
            // Controlla dati form
            const formData = new FormData(this);
            console.log('Dati form:', Object.fromEntries(formData));
        });
    }
});

// Funzioni per aprire modali specifiche
function openAliquotaModal() {
    // Reset form
    const form = document.getElementById('aliquotaForm');
    if (form) {
        form.reset();
        form.action = `{{ route("configurations.gestione-tabelle.store", "aliquote-iva") }}`;
        
        // Rimuovi campo _method se presente
        const methodField = form.querySelector('input[name="_method"]');
        if (methodField) {
            methodField.remove();
        }
        
        // Aggiorna token CSRF
        const csrfField = form.querySelector('input[name="_token"]');
        if (csrfField) {
            const metaCsrfToken = document.querySelector('meta[name="csrf-token"]');
            if (metaCsrfToken) {
                csrfField.value = metaCsrfToken.getAttribute('content');
            }
        }
        
        // Ripristina titolo
        const modalTitle = document.getElementById('aliquotaModalTitle');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="bi bi-percent me-2"></i>Nuova Aliquota IVA';
        }
        
        // Ripristina pulsante submit
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> Crea Aliquota';
        }
        
        // Imposta valori di default
        const activeCheckbox = document.getElementById('modal_active_aliquota');
        if (activeCheckbox) activeCheckbox.checked = true;
    }
    
    // Mostra la modale
    const modal = new bootstrap.Modal(document.getElementById('aliquotaModal'));
    modal.show();
    
    // Focus al primo campo
    setTimeout(() => {
        const codeField = document.getElementById('modal_code_aliquota');
        if (codeField) {
            codeField.focus();
        }
    }, 500);
}

function openBancaModal() {
    // Reset form
    const form = document.getElementById('bancaForm');
    if (form) {
        form.reset();
        form.action = `{{ route("configurations.gestione-tabelle.store", "banche") }}`;
        
        // Rimuovi campo _method se presente
        const methodField = form.querySelector('input[name="_method"]');
        if (methodField) {
            methodField.remove();
        }
        
        // Aggiorna token CSRF
        const csrfField = form.querySelector('input[name="_token"]');
        if (csrfField) {
            const metaCsrfToken = document.querySelector('meta[name="csrf-token"]');
            if (metaCsrfToken) {
                csrfField.value = metaCsrfToken.getAttribute('content');
            }
        }
        
        // Ripristina titolo
        const modalTitle = document.getElementById('bancaModalTitle');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="bi bi-bank me-2"></i>Nuova Banca';
        }
        
        // Ripristina pulsante submit
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> Crea Banca';
        }
        
        // Imposta valori di default
        const isItalianCheckbox = document.getElementById('modal_is_italian');
        const activeCheckbox = document.getElementById('modal_active_banca');
        if (isItalianCheckbox) isItalianCheckbox.checked = true;
        if (activeCheckbox) activeCheckbox.checked = true;
    }
    
    // Mostra la modale
    const modal = new bootstrap.Modal(document.getElementById('bancaModal'));
    modal.show();
    
    // Focus al primo campo
    setTimeout(() => {
        const codeField = document.getElementById('modal_code_banca');
        if (codeField) {
            codeField.focus();
        }
    }, 500);
}

// Funzioni per visualizzare e modificare elementi
async function viewItem(tabella, id) {
    try {
        const response = await fetch(`/configurations/gestione-tabelle/${tabella}/${id}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            showViewModal(tabella, data);
        } else {
            alert('Errore nel caricamento dei dati');
        }
    } catch (error) {
        console.error('Errore:', error);
        alert('Errore nel caricamento dei dati');
    }
}

async function editItem(tabella, id) {
    try {
        const response = await fetch(`/configurations/gestione-tabelle/${tabella}/${id}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            showEditModal(tabella, data);
        } else {
            alert('Errore nel caricamento dei dati');
        }
    } catch (error) {
        console.error('Errore:', error);
        alert('Errore nel caricamento dei dati');
    }
}

function showViewModal(tabella, data) {
    // Popolazione modale visualizzazione
    document.getElementById('viewModalTitle').textContent = `Dettagli ${getTableDisplayName(tabella)}`;
    
    let content = '';
    if (tabella === 'banche') {
        content = `
            <div class="row g-3">
                <div class="col-md-6">
                    <strong>Codice:</strong> ${data.code}
                </div>
                <div class="col-md-6">
                    <strong>Nome:</strong> ${data.name}
                </div>
                ${data.description ? `<div class="col-12"><strong>Descrizione:</strong> ${data.description}</div>` : ''}
                ${data.abi_code ? `<div class="col-md-6"><strong>ABI:</strong> ${data.abi_code}</div>` : ''}
                ${data.bic_swift ? `<div class="col-md-6"><strong>BIC/SWIFT:</strong> ${data.bic_swift}</div>` : ''}
                ${data.phone ? `<div class="col-md-6"><strong>Telefono:</strong> ${data.phone}</div>` : ''}
                ${data.email ? `<div class="col-md-6"><strong>Email:</strong> ${data.email}</div>` : ''}
                <div class="col-md-6">
                    <strong>Banca Italiana:</strong> ${data.is_italian ? 'Sì' : 'No'}
                </div>
                <div class="col-md-6">
                    <strong>Stato:</strong> ${data.active ? 'Attiva' : 'Inattiva'}
                </div>
            </div>
        `;
    } else if (tabella === 'aliquote-iva') {
        content = `
            <div class="row g-3">
                <div class="col-md-6">
                    <strong>Codice:</strong> ${data.code}
                </div>
                <div class="col-md-6">
                    <strong>Percentuale:</strong> ${data.percentuale}%
                </div>
                <div class="col-12">
                    <strong>Nome:</strong> ${data.name}
                </div>
                <div class="col-12">
                    <strong>Descrizione:</strong> ${data.description}
                </div>
                ${data.riferimento_normativo ? `<div class="col-12"><strong>Riferimento Normativo:</strong> ${data.riferimento_normativo}</div>` : ''}
                <div class="col-12">
                    <strong>Stato:</strong> ${data.active ? 'Attiva' : 'Inattiva'}
                </div>
            </div>
        `;
    } else if (tabella === 'associazioni-nature-iva') {
        content = `
            <div class="row g-3">
                <div class="col-12">
                    <strong>Nome Associazione:</strong> ${data.nome_associazione || data.name || '-'}
                </div>
                ${data.descrizione ? `<div class="col-12"><strong>Descrizione:</strong> ${data.descrizione}</div>` : ''}
                <div class="col-md-6">
                    <strong>Aliquota IVA:</strong> 
                    <span class="badge badge-code">
                        ${data.tax_rate ? `${data.tax_rate.code} - ${data.tax_rate.percentuale}%` : 'N/A'}
                    </span>
                </div>
                <div class="col-md-6">
                    <strong>Natura IVA:</strong> 
                    <span class="badge badge-percentage">
                        ${data.vat_nature ? `${data.vat_nature.code} - ${data.vat_nature.name}` : 'N/A'}
                    </span>
                </div>
                <div class="col-md-6">
                    <strong>Predefinita:</strong> ${data.is_default ? 'Sì' : 'No'}
                </div>
                <div class="col-md-6">
                    <strong>Stato:</strong> ${data.active ? 'Attiva' : 'Inattiva'}
                </div>
                ${data.created_at ? `<div class="col-12"><strong>Data Creazione:</strong> ${new Date(data.created_at).toLocaleDateString('it-IT')} ${new Date(data.created_at).toLocaleTimeString('it-IT')}</div>` : ''}
            </div>
        `;
    } else if (tabella === 'categorie-articoli') {
        content = `
            <div class="row g-3">
                <div class="col-md-6">
                    <strong>Codice:</strong> 
                    <span class="badge badge-code">
                        ${data.code}
                    </span>
                </div>
                <div class="col-md-6">
                    <strong>Stato:</strong> ${data.active ? 'Attiva' : 'Inattiva'}
                </div>
                <div class="col-12">
                    <strong>Descrizione:</strong> ${data.description || '-'}
                </div>
                ${data.created_at ? `<div class="col-12"><strong>Data Creazione:</strong> ${new Date(data.created_at).toLocaleDateString('it-IT')} ${new Date(data.created_at).toLocaleTimeString('it-IT')}</div>` : ''}
            </div>
        `;
    } else if (tabella === 'categorie-clienti') {
        content = `
            <div class="row g-3">
                <div class="col-md-6">
                    <strong>Codice:</strong> 
                    <span class="badge badge-code">
                        ${data.code}
                    </span>
                </div>
                <div class="col-md-6">
                    <strong>Stato:</strong> ${data.active ? 'Attiva' : 'Inattiva'}
                </div>
                <div class="col-12">
                    <strong>Descrizione:</strong> ${data.description || '-'}
                </div>
                ${data.created_at ? `<div class="col-12"><strong>Data Creazione:</strong> ${new Date(data.created_at).toLocaleDateString('it-IT')} ${new Date(data.created_at).toLocaleTimeString('it-IT')}</div>` : ''}
            </div>
        `;
    }
    
    document.getElementById('viewModalContent').innerHTML = content;
    
    const modal = new bootstrap.Modal(document.getElementById('viewModal'));
    modal.show();
}

function showEditModal(tabella, data) {
    if (tabella === 'banche') {
        showEditBancaModal(data);
    } else if (tabella === 'aliquote-iva') {
        showEditAliquotaModal(data);
    } else if (tabella === 'associazioni-nature-iva') {
        showEditAssociazioneModal(data);
    } else if (tabella === 'categorie-articoli') {
        showEditCategoriaModal(data);
    } else if (tabella === 'categorie-clienti') {
        showEditCategoriaClientiModal(data);
    } else {
        alert('Modifica non ancora implementata per questa tabella');
    }
}

function showEditBancaModal(data) {
    // Popola e mostra la modale di modifica per Banca
    const form = document.getElementById('bancaForm');
    if (form) {
        // Aggiorna action per update
        form.action = `/configurations/gestione-tabelle/banche/${data.id}`;
        
        // Aggiungi method field per PUT
        let methodField = form.querySelector('input[name="_method"]');
        if (!methodField) {
            methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            form.appendChild(methodField);
        }
        methodField.value = 'PUT';
        
        // Popola i campi
        document.getElementById('modal_code_banca').value = data.code || '';
        document.getElementById('modal_name_banca').value = data.name || '';
        document.getElementById('modal_description_banca').value = data.description || '';
        document.getElementById('modal_abi_code').value = data.abi_code || '';
        document.getElementById('modal_bic_swift').value = data.bic_swift || '';
        document.getElementById('modal_phone_banca').value = data.phone || '';
        document.getElementById('modal_email_banca').value = data.email || '';
        document.getElementById('modal_is_italian').checked = data.is_italian || false;
        document.getElementById('modal_active_banca').checked = data.active || false;
        
        // Aggiorna titolo
        const modalTitle = document.getElementById('bancaModalTitle');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="bi bi-pencil me-2"></i>Modifica Banca';
        }
        
        // Aggiorna pulsante submit
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> Aggiorna Banca';
        }
    }
    
    // Mostra la modale
    const modal = new bootstrap.Modal(document.getElementById('bancaModal'));
    modal.show();
}

function showEditAliquotaModal(data) {
    // Popola e mostra la modale di modifica per Aliquota IVA
    const form = document.getElementById('aliquotaForm');
    if (form) {
        // Aggiorna action per update
        form.action = `/configurations/gestione-tabelle/aliquote-iva/${data.id}`;
        
        // Aggiungi method field per PUT
        let methodField = form.querySelector('input[name="_method"]');
        if (!methodField) {
            methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            form.appendChild(methodField);
        }
        methodField.value = 'PUT';
        
        // Popola i campi
        document.getElementById('modal_code_aliquota').value = data.code || '';
        document.getElementById('modal_name_aliquota').value = data.name || '';
        document.getElementById('modal_description_aliquota').value = data.description || '';
        document.getElementById('modal_percentuale').value = data.percentuale || '';
        document.getElementById('modal_riferimento_normativo').value = data.riferimento_normativo || '';
        document.getElementById('modal_active_aliquota').checked = data.active || false;
        
        // Aggiorna titolo
        const modalTitle = document.getElementById('aliquotaModalTitle');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="bi bi-pencil me-2"></i>Modifica Aliquota IVA';
        }
        
        // Aggiorna pulsante submit
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> Aggiorna Aliquota';
        }
    }
    
    // Mostra la modale
    const modal = new bootstrap.Modal(document.getElementById('aliquotaModal'));
    modal.show();
}

function showEditAssociazioneModal(data) {
    // Popola e mostra la modale di modifica per Associazione
    const form = document.getElementById('associationForm');
    if (form) {
        // Aggiorna action per update
        form.action = `/configurations/gestione-tabelle/associazioni-nature-iva/${data.id}`;
        
        // Aggiungi method field per PUT
        let methodField = form.querySelector('input[name="_method"]');
        if (!methodField) {
            methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            form.appendChild(methodField);
        }
        methodField.value = 'PUT';
        
        // Popola i campi
        document.getElementById('modal_nome_associazione').value = data.nome_associazione || '';
        document.getElementById('modal_descrizione').value = data.descrizione || '';
        document.getElementById('modal_tax_rate_id').value = data.tax_rate_id || '';
        document.getElementById('modal_vat_nature_id').value = data.vat_nature_id || '';
        document.getElementById('modal_is_default').checked = data.is_default || false;
        
        // Aggiorna titolo
        const modalTitle = document.getElementById('associationModalTitle');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="bi bi-pencil me-2"></i>Modifica Associazione';
        }
        
        // Aggiorna pulsante submit
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> Aggiorna Associazione';
        }
    }
    
    // Mostra la modale
    const modal = new bootstrap.Modal(document.getElementById('associationModal'));
    modal.show();
}

function getTableDisplayName(tabella) {
    const names = {
        'banche': 'Banca',
        'aliquote-iva': 'Aliquota IVA',
        'associazioni-nature-iva': 'Associazione Nature IVA',
        'categorie-articoli': 'Categoria Articoli',
        'categorie-clienti': 'Categoria Clienti'
    };
    return names[tabella] || 'Elemento';
}

// Funzione per aprire modale nuova categoria
function openCategoriaModal() {
    // Reset form
    const form = document.getElementById('categoriaForm');
    if (form) {
        form.reset();
        form.action = `{{ route("configurations.gestione-tabelle.store", "categorie-articoli") }}`;
        
        // Rimuovi campo _method se presente
        const methodField = form.querySelector('input[name="_method"]');
        if (methodField) {
            methodField.remove();
        }
        
        // Aggiorna token CSRF
        const csrfField = form.querySelector('input[name="_token"]');
        if (csrfField) {
            const metaCsrfToken = document.querySelector('meta[name="csrf-token"]');
            if (metaCsrfToken) {
                csrfField.value = metaCsrfToken.getAttribute('content');
            }
        }
        
        // Ripristina titolo
        const modalTitle = document.getElementById('categoriaModalTitle');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="bi bi-grid-3x3-gap me-2"></i>Nuova Categoria Articoli';
        }
        
        // Ripristina pulsante submit
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> Crea Categoria';
        }
        
        // Imposta valori di default
        const activeCheckbox = document.getElementById('modal_active_categoria');
        if (activeCheckbox) activeCheckbox.checked = true;
    }
    
    // Mostra la modale
    const modal = new bootstrap.Modal(document.getElementById('categoriaModal'));
    modal.show();
    
    // Focus al primo campo
    setTimeout(() => {
        const codeField = document.getElementById('modal_code_categoria');
        if (codeField) {
            codeField.focus();
        }
    }, 500);
}

function showEditCategoriaModal(data) {
    // Popola e mostra la modale di modifica per Categoria Articoli
    const form = document.getElementById('categoriaForm');
    if (form) {
        // Aggiorna action per update
        form.action = `/configurations/gestione-tabelle/categorie-articoli/${data.id}`;
        
        // Aggiungi method field per PUT
        let methodField = form.querySelector('input[name="_method"]');
        if (!methodField) {
            methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            form.appendChild(methodField);
        }
        methodField.value = 'PUT';
        
        // Popola i campi
        document.getElementById('modal_code_categoria').value = data.code || '';
        document.getElementById('modal_description_categoria').value = data.description || '';
        document.getElementById('modal_active_categoria').checked = data.active || false;
        
        // Aggiorna titolo
        const modalTitle = document.getElementById('categoriaModalTitle');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="bi bi-pencil me-2"></i>Modifica Categoria Articoli';
        }
        
        // Aggiorna pulsante submit
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> Aggiorna Categoria';
        }
    }
    
    // Mostra la modale
    const modal = new bootstrap.Modal(document.getElementById('categoriaModal'));
    modal.show();
}
</script>

<!-- Modal Nuova Associazione -->
<div class="modal fade" id="associationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="associationModalTitle">
                    <i class="bi bi-plus-circle me-2"></i>
                    Nuova Associazione Nature IVA
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="associationForm" method="POST" action="{{ route('configurations.gestione-tabelle.store', $nomeTabella) }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="modal_nome_associazione" class="form-label">Nome Associazione *</label>
                            <input type="text" class="form-control" id="modal_nome_associazione" name="nome_associazione" 
                                   placeholder="es. IVA 22% Natura N1" minlength="3" maxlength="255" required>
                            <div class="form-text">Minimo 3 caratteri, massimo 255</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="modal_descrizione" class="form-label">Descrizione</label>
                            <input type="text" class="form-control" id="modal_descrizione" name="descrizione" 
                                   placeholder="Descrizione dettagliata dell'associazione" maxlength="500">
                            <div class="form-text">Massimo 500 caratteri (opzionale)</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="modal_tax_rate_id" class="form-label">Aliquota IVA *</label>
                            <select class="form-select" id="modal_tax_rate_id" name="tax_rate_id" required>
                                <option value="">Seleziona aliquota IVA...</option>
                                @foreach(\App\Models\TaxRate::where('active', true)->orderBy('percentuale')->get() as $taxRate)
                                <option value="{{ $taxRate->id }}">
                                    {{ $taxRate->code }} - {{ $taxRate->name }} ({{ $taxRate->percentuale }}%)
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="modal_vat_nature_id" class="form-label">Natura IVA *</label>
                            <select class="form-select" id="modal_vat_nature_id" name="vat_nature_id" required>
                                <option value="">Seleziona natura IVA...</option>
                                @foreach(\App\Models\VatNature::where('active', true)->orderBy('code')->get() as $vatNature)
                                <option value="{{ $vatNature->id }}">
                                    {{ $vatNature->code }} - {{ $vatNature->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="modal_is_default" name="is_default" value="1">
                                <label class="form-check-label" for="modal_is_default">
                                    Imposta come associazione predefinita per questa aliquota IVA
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn btn-secondary modern-btn" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Annulla
                    </button>
                    <button type="submit" class="btn btn-success modern-btn" onclick="console.log('Form submitted');">
                        <i class="bi bi-check-lg"></i> Crea Associazione
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Nuova Banca -->
<div class="modal fade" id="bancaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="bancaModalTitle">
                    <i class="bi bi-bank me-2"></i>
                    Nuova Banca
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="bancaForm" method="POST" action="{{ route('configurations.gestione-tabelle.store', 'banche') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="modal_code_banca" class="form-label">Codice Banca *</label>
                            <input type="text" class="form-control" id="modal_code_banca" name="code" 
                                   placeholder="es. BNKIT" maxlength="10" required pattern="[A-Z0-9_-]+">
                            <div class="form-text">Solo lettere maiuscole, numeri, underscore e trattini</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="modal_name_banca" class="form-label">Nome Banca *</label>
                            <input type="text" class="form-control" id="modal_name_banca" name="name" 
                                   placeholder="es. Banca Nazionale del Lavoro" maxlength="100" required>
                        </div>
                        
                        <div class="col-12">
                            <label for="modal_description_banca" class="form-label">Descrizione</label>
                            <textarea class="form-control" id="modal_description_banca" name="description" 
                                      placeholder="Descrizione della banca" maxlength="255" rows="2"></textarea>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="modal_abi_code" class="form-label">Codice ABI</label>
                            <input type="text" class="form-control" id="modal_abi_code" name="abi_code" 
                                   placeholder="es. 01005" maxlength="5" pattern="\d{5}">
                            <div class="form-text">5 cifre per banche italiane</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="modal_bic_swift" class="form-label">BIC/SWIFT</label>
                            <input type="text" class="form-control" id="modal_bic_swift" name="bic_swift" 
                                   placeholder="es. BNLIITRR" maxlength="11" pattern="[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?">
                            <div class="form-text">8-11 caratteri formato BIC</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="modal_phone_banca" class="form-label">Telefono</label>
                            <input type="text" class="form-control" id="modal_phone_banca" name="phone" 
                                   placeholder="es. +39 06 47021" maxlength="50">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="modal_email_banca" class="form-label">Email</label>
                            <input type="email" class="form-control" id="modal_email_banca" name="email" 
                                   placeholder="es. info@banca.it" maxlength="100">
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="modal_is_italian" name="is_italian" value="1" checked>
                                <label class="form-check-label" for="modal_is_italian">
                                    Banca italiana
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="modal_active_banca" name="active" value="1" checked>
                                <label class="form-check-label" for="modal_active_banca">
                                    Banca attiva
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn btn-secondary modern-btn" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Annulla
                    </button>
                    <button type="submit" class="btn btn-success modern-btn">
                        <i class="bi bi-check-lg"></i> Crea Banca
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Nuova Aliquota IVA -->
<div class="modal fade" id="aliquotaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="aliquotaModalTitle">
                    <i class="bi bi-percent me-2"></i>
                    Nuova Aliquota IVA
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="aliquotaForm" method="POST" action="{{ route('configurations.gestione-tabelle.store', 'aliquote-iva') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="modal_code_aliquota" class="form-label">Codice Aliquota *</label>
                            <input type="text" class="form-control" id="modal_code_aliquota" name="code" 
                                   placeholder="es. IVA22" maxlength="20" required>
                            <div class="form-text">Codice univoco per l'aliquota</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="modal_percentuale" class="form-label">Percentuale *</label>
                            <input type="number" class="form-control" id="modal_percentuale" name="percentuale" 
                                   placeholder="es. 22" min="0" max="100" step="0.01" required>
                            <div class="form-text">Valore percentuale da 0 a 100</div>
                        </div>
                        
                        <div class="col-12">
                            <label for="modal_name_aliquota" class="form-label">Nome Aliquota *</label>
                            <input type="text" class="form-control" id="modal_name_aliquota" name="name" 
                                   placeholder="es. IVA Ordinaria" maxlength="255" required>
                        </div>
                        
                        <div class="col-12">
                            <label for="modal_description_aliquota" class="form-label">Descrizione *</label>
                            <textarea class="form-control" id="modal_description_aliquota" name="description" 
                                      placeholder="Descrizione dettagliata dell'aliquota IVA" maxlength="500" rows="3" required></textarea>
                        </div>
                        
                        <div class="col-12">
                            <label for="modal_riferimento_normativo" class="form-label">Riferimento Normativo</label>
                            <textarea class="form-control" id="modal_riferimento_normativo" name="riferimento_normativo" 
                                      placeholder="Riferimenti normativi (articoli di legge, decreti, ecc.)" maxlength="1000" rows="2"></textarea>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="modal_active_aliquota" name="active" value="1" checked>
                                <label class="form-check-label" for="modal_active_aliquota">
                                    Aliquota attiva
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn btn-secondary modern-btn" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Annulla
                    </button>
                    <button type="submit" class="btn btn-success modern-btn">
                        <i class="bi bi-check-lg"></i> Crea Aliquota
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Visualizza Elemento -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="viewModalTitle">
                    <i class="bi bi-eye me-2"></i>
                    Dettagli Elemento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewModalContent">
                <!-- Contenuto dinamico -->
            </div>
            <div class="modal-footer modal-footer-custom">
                <button type="button" class="btn btn-secondary modern-btn" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i> Chiudi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Categoria Articoli -->
<div class="modal fade" id="categoriaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="categoriaModalTitle">
                    <i class="bi bi-grid-3x3-gap me-2"></i>
                    Nuova Categoria Articoli
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="categoriaForm" method="POST" action="{{ route('configurations.gestione-tabelle.store', 'categorie-articoli') }}">
                @csrf
                <div class="modal-body modal-body-custom">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="modal_code_categoria" class="form-label">Codice <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modal_code_categoria" name="code" 
                                   placeholder="Codice categoria (es: ELET)" maxlength="20" required
                                   pattern="[A-Z0-9_-]+" title="Solo lettere maiuscole, numeri, underscore e trattini">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="modal_description_categoria" class="form-label">Descrizione <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modal_description_categoria" name="description" 
                                   placeholder="Descrizione categoria (es: Elettronica)" maxlength="255" required>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="modal_active_categoria" name="active" value="1" checked>
                                <label class="form-check-label" for="modal_active_categoria">
                                    Categoria attiva
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn btn-secondary modern-btn" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Annulla
                    </button>
                    <button type="submit" class="btn btn-success modern-btn">
                        <i class="bi bi-check-lg"></i> Crea Categoria
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Categoria Clienti -->
<div class="modal fade" id="categoriaClientiModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="categoriaClientiModalTitle">
                    <i class="bi bi-people me-2"></i>
                    Nuova Categoria Clienti
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="categoriaClientiForm" method="POST" action="{{ route('configurations.gestione-tabelle.store', 'categorie-clienti') }}">
                @csrf
                <div class="modal-body modal-body-custom">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="modal_code_categoria_clienti" class="form-label">Codice <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modal_code_categoria_clienti" name="code" 
                                   placeholder="Codice categoria (es: PRIV)" maxlength="20" required>
                            <div class="form-text">Codice identificativo univoco</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="modal_description_categoria_clienti" class="form-label">Descrizione <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="modal_description_categoria_clienti" name="description" 
                                   placeholder="Descrizione categoria (es: Privato)" maxlength="255" required>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="modal_active_categoria_clienti" name="active" value="1" checked>
                                <label class="form-check-label" for="modal_active_categoria_clienti">
                                    Categoria attiva
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn btn-secondary modern-btn" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Annulla
                    </button>
                    <button type="submit" class="btn btn-success modern-btn">
                        <i class="bi bi-check-lg"></i> Crea Categoria Clienti
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Funzione per aprire modale nuova categoria clienti
function openCategoriaClientiModal() {
    // Reset del form
    const form = document.getElementById('categoriaClientiForm');
    if (form) {
        form.reset();
        
        // Reset action per creazione
        form.action = '{{ route('configurations.gestione-tabelle.store', 'categorie-clienti') }}';
        
        // Reset method a POST
        let methodField = form.querySelector('input[name="_method"]');
        if (methodField) {
            methodField.remove();
        }
        
        // Aggiorna titolo e pulsante per creazione
        const modalTitle = document.getElementById('categoriaClientiModalTitle');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="bi bi-people me-2"></i>Nuova Categoria Clienti';
        }
        
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> Crea Categoria Clienti';
        }
        
        // Assicurati che attivo sia selezionato di default
        const activeCheckbox = document.getElementById('modal_active_categoria_clienti');
        if (activeCheckbox) {
            activeCheckbox.checked = true;
        }
    }
    
    // Apri la modale
    const modal = new bootstrap.Modal(document.getElementById('categoriaClientiModal'));
    modal.show();
    
    // Focus sul campo codice dopo apertura
    setTimeout(() => {
        const codeField = document.getElementById('modal_code_categoria_clienti');
        if (codeField) {
            codeField.focus();
        }
    }, 500);
}

function showEditCategoriaClientiModal(data) {
    // Popola e mostra la modale di modifica per Categoria Clienti
    const form = document.getElementById('categoriaClientiForm');
    if (form) {
        // Aggiorna action per update
        form.action = `/configurations/gestione-tabelle/categorie-clienti/${data.id}`;
        
        // Aggiungi method PUT se non esiste
        let methodField = form.querySelector('input[name="_method"]');
        if (!methodField) {
            methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            form.appendChild(methodField);
        }
        methodField.value = 'PUT';
        
        // Popola i campi
        document.getElementById('modal_code_categoria_clienti').value = data.code || '';
        document.getElementById('modal_description_categoria_clienti').value = data.description || '';
        document.getElementById('modal_active_categoria_clienti').checked = data.active || false;
        
        // Aggiorna titolo e pulsante per modifica
        const modalTitle = document.getElementById('categoriaClientiModalTitle');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="bi bi-pencil me-2"></i>Modifica Categoria Clienti';
        }
        
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> Aggiorna Categoria Clienti';
        }
    }
    
    // Apri la modale
    const modal = new bootstrap.Modal(document.getElementById('categoriaClientiModal'));
    modal.show();
}
</script>

@endsection