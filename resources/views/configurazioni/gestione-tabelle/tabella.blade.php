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
    
    /* Table responsive wrapper */
    .table-responsive {
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    /* Tabella moderna */
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: white;
        border: none;
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
                @php
                    $nomeSingolare = $configurazione['nome_singolare'] ?? $configurazione['nome'] ?? 'Elemento';
                    // Parole femminili (finiscono con 'a' o sono femminili per natura)
                    $paroleFemminili = ['Associazione', 'Natura', 'Aliquota', 'Banca', 'Categoria', 'Taglia', 'Causale', 'Condizione', 'Denominazione', 'Modalità'];
                    $isFemminile = str_ends_with($nomeSingolare, 'a') || in_array(explode(' ', $nomeSingolare)[0], $paroleFemminili);
                    $articolo = $isFemminile ? 'Nuova' : 'Nuovo';
                @endphp
                <a href="{{ route('configurations.gestione-tabelle.create', $nomeTabella) }}" class="btn btn-success modern-btn">
                    <i class="bi bi-plus-lg"></i> {{ $articolo }} {{ $nomeSingolare }}
                </a>
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
                                                {{ $item->vatNature->vat_code ?? 'N/A' }}
                                            </span>
                                            @if($item->vatNature->nature ?? false)
                                                <div class="vat-nature-description">{{ $item->vatNature->nature }}</div>
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
                                        @elseif(isset($configurazione['campi_con_tipo'][$campo]) && $configurazione['campi_con_tipo'][$campo] === 'checkbox')
                                            <span class="checkbox-icon">
                                                @if($item->$campo)
                                                    <i class="bi bi-check-square-fill" style="color: #029D7E; font-size: 1.2rem;"></i>
                                                @else
                                                    <i class="bi bi-square" style="color: #6c757d; font-size: 1.2rem;"></i>
                                                @endif
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
            <div class="modern-pagination-container mt-4 d-none d-md-block">
                {{ $dati->links('pagination.modern-pagination') }}
            </div>
        @endif
    </div>

    <!-- Cards Mobile -->
    <div class="mobile-cards" id="mobileCards">
        @forelse($dati as $item)
            <div class="item-card mobile-item-row" data-status="{{ $item->active ?? 1 }}">
                <div class="item-card-header">
                    <h3 class="item-card-title">
                        @if($item->name ?? $item->nome ?? false)
                            {{ $item->name ?? $item->nome }}
                        @else
                            {{ $item->description ?? $item->descrizione ?? 'Elemento #' . $item->id }}
                        @endif
                    </h3>
                    <div>
                        <span class="status-badge status-badge-small {{ ($item->active ?? true) ? 'status-active' : 'status-inactive' }}">
                            {{ ($item->active ?? true) ? 'Attivo' : 'Inattivo' }}
                        </span>
                    </div>
                </div>
                
                @if(($item->description ?? $item->descrizione ?? false) && ($item->name ?? $item->nome ?? false))
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
        
        @if(isset($dati) && $dati->hasPages())
            <div class="modern-pagination-container mt-4 d-md-none">
                {{ $dati->links('pagination.modern-pagination') }}
            </div>
        @endif
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


// Funzioni per visualizzare e modificare elementi
async function viewItem(tabella, id) {
    // Usa i named routes di Laravel - passa direttamente la tabella corrente
    const url = `{{ route('configurations.gestione-tabelle.show', ['nomeTabella' => $nomeTabella, 'id' => ':id']) }}`
        .replace(':id', id);
    window.location.href = url;
}

async function editItem(tabella, id) {
    // Usa i named routes di Laravel - passa direttamente la tabella corrente
    const url = `{{ route('configurations.gestione-tabelle.edit', ['nomeTabella' => $nomeTabella, 'id' => ':id']) }}`
        .replace(':id', id);
    window.location.href = url;
}





function getTableDisplayName(tabella) {
    const names = {
        'banche': 'Banca',
        'aliquote-iva': 'Aliquota IVA',
        'associazioni-nature-iva': 'Associazione Nature IVA',
        'categorie-articoli': 'Categoria Articoli',
        'categorie-clienti': 'Categoria Clienti',
        'categorie-fornitori': 'Categoria Fornitori',
        'settori-merceologici': 'Settore Merceologico'
    };
    return names[tabella] || 'Elemento';
}

</script>

@endsection
