@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .dashboard-container {
        padding: 2rem;
    }
    
    .dashboard-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .dashboard-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .welcome-text {
        color: #6c757d;
        font-size: 1.1rem;
        margin-top: 0.5rem;
    }
    
    .modern-btn {
        border: none;
        border-radius: 15px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        font-size: 0.9rem;
    }
    
    .modern-btn.secondary {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
    }
    
    .modern-btn.secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(108, 117, 125, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .modern-btn.green {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
    }
    
    .modern-btn.green:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(2, 157, 126, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .modern-btn.pdf {
        background: linear-gradient(135deg, #9c27b0, #7b1fa2);
        color: white;
    }
    
    .modern-btn.pdf:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(156, 39, 176, 0.3);
        color: white;
        text-decoration: none;
    }

    /* Search Filters - Stile Configurazioni */
    .search-filters {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .search-input {
        border: 2px solid rgba(2, 157, 126, 0.2);
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
    }
    
    .search-input:focus {
        outline: none;
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
        background: white;
    }
    
    .filter-select {
        border: 2px solid rgba(2, 157, 126, 0.2);
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
    }
    
    .filter-select:focus {
        outline: none;
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
        background: white;
    }

    .tab-navigation {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .nav-tabs {
        border-bottom: none;
        gap: 1rem;
    }

    .nav-tabs .nav-link {
        border: 2px solid transparent;
        border-radius: 15px;
        padding: 12px 24px;
        font-weight: 600;
        color: #6c757d;
        background: rgba(108, 117, 125, 0.1);
        transition: all 0.3s ease;
    }

    .nav-tabs .nav-link:hover {
        color: #029D7E;
        background: rgba(2, 157, 126, 0.1);
        border-color: rgba(2, 157, 126, 0.2);
        transform: translateY(-2px);
    }

    .nav-tabs .nav-link.active {
        color: white;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        border-color: transparent;
        box-shadow: 0 8px 25px rgba(2, 157, 126, 0.3);
    }

    .tab-content {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        min-height: 500px;
    }

    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        transition: all 0.3s ease;
        background: white;
    }

    .form-control:hover, .form-select:hover {
        border-color: #029D7E;
    }

    .form-control:focus, .form-select:focus {
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.1);
        background: white;
    }

    .movimento-card {
        background: rgba(255, 255, 255, 0.8);
        border: 2px solid #e2e8f0;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .movimento-card:hover {
        border-color: #029D7E;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(2, 157, 126, 0.1);
    }

    .badge-movimento {
        font-size: 0.8rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
    }

    .badge-carico {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .badge-scarico {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .badge-trasferimento {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .action-btn {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.5rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        text-decoration: none;
    }

    .action-btn.carico {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .action-btn.scarico {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .action-btn.trasferimento {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    /* Dark Mode */
    [data-bs-theme="dark"] .dashboard-header,
    [data-bs-theme="dark"] .tab-navigation,
    [data-bs-theme="dark"] .tab-content {
        background: rgba(45, 55, 72, 0.95) !important;
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .dashboard-title {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    [data-bs-theme="dark"] .welcome-text {
        color: #a0aec0 !important;
    }

    [data-bs-theme="dark"] .movimento-card {
        background: rgba(45, 55, 72, 0.8) !important;
        border-color: #4a5568 !important;
        color: #e2e8f0 !important;
    }

    [data-bs-theme="dark"] .form-label {
        color: #e2e8f0 !important;
    }

    [data-bs-theme="dark"] .form-control,
    [data-bs-theme="dark"] .form-select {
        background: rgba(45, 55, 72, 0.8) !important;
        border-color: #4a5568 !important;
        color: #e2e8f0 !important;
    }

    /* Tabella moderna - stile anagrafiche */
    .table-responsive {
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: white;
        border: none;
        margin: 0;
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

    .action-buttons {
        display: flex;
        gap: 0.25rem;
    }
    
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
    
    .action-btn.pdf {
        background: linear-gradient(135deg, #9c27b0, #7b1fa2);
        color: white;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        text-decoration: none;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }

    /* Mobile Cards */
    .mobile-cards {
        display: none;
    }
    
    .movimento-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .movimento-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .movimento-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .movimento-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
        flex: 1;
        min-width: 0;
    }
    
    .movimento-card-type {
        flex-shrink: 0;
    }
    
    .movimento-card-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .movimento-detail {
        display: flex;
        flex-direction: column;
    }
    
    .movimento-detail-label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 0.2rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .movimento-detail-value {
        font-weight: 500;
        color: #2d3748;
    }
    
    .movimento-card-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .mobile-action-btn {
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
    
    .mobile-action-btn.view {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
    }
    
    .mobile-action-btn.edit {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
    }
    
    .mobile-action-btn.pdf {
        background: linear-gradient(135deg, #9c27b0, #7b1fa2);
        color: white;
    }
    
    .mobile-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: white;
        text-decoration: none;
    }

    /* Responsive - Include anche laptop piccoli */
    @media (max-width: 1366px) {
        .dashboard-container {
            padding: 1rem;
        }
        
        .dashboard-header {
            padding: 1.5rem;
        }
        
        .dashboard-title {
            font-size: 1.5rem;
        }
        
        /* Tab navigation responsive */
        .tab-navigation {
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .nav-tabs {
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .nav-tabs .nav-link {
            padding: 8px 12px;
            font-size: 0.8rem;
            white-space: nowrap;
        }
        
        /* Tab content */
        .tab-content {
            padding: 1rem;
        }
        
        /* Nasconde tabella su mobile */
        .table-responsive {
            display: none !important;
        }
        
        .mobile-cards {
            display: block;
            margin: 2rem 0 1rem 0;
        }
        
        /* Form responsive */
        .form-control, .form-select {
            padding: 10px 12px;
            font-size: 0.9rem;
        }
        
        .modern-btn {
            padding: 10px 16px;
            font-size: 0.9rem;
        }
    }
    
    /* Laptop e Tablet grandi */
    @media (max-width: 1366px) and (min-width: 1025px) {
        .movimento-card {
            padding: 1.5rem;
        }
        
        .movimento-card-details {
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
        }
    }
    
    /* Tablet specifico */
    @media (max-width: 1024px) and (min-width: 769px) {
        .movimento-card {
            padding: 1.25rem;
        }
        
        .movimento-card-details {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .dashboard-header {
            padding: 1rem;
        }
        
        .tab-navigation {
            padding: 0.5rem;
        }
        
        .nav-tabs .nav-link {
            padding: 6px 8px;
            font-size: 0.7rem;
        }
        
        .tab-content {
            padding: 0.8rem;
        }
        
        .movimento-card {
            padding: 1rem;
        }
        
        .movimento-card-details {
            grid-template-columns: 1fr;
            gap: 0.8rem;
        }
        
        .mobile-action-btn {
            padding: 6px;
            font-size: 0.7rem;
            width: 28px;
            height: 28px;
        }
        
        .movimento-card-actions {
            gap: 0.3rem;
        }
        
        .form-control, .form-select {
            padding: 8px 10px;
            font-size: 0.8rem;
        }
        
        .modern-btn {
            padding: 8px 12px;
            font-size: 0.8rem;
        }
    }
</style>

<div class="container-fluid dashboard-container">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
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

    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="dashboard-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h1 class="dashboard-title">
                            <i class="bi bi-arrow-left-right"></i> Movimenti di Magazzino
                        </h1>
                        <p class="welcome-text">Gestione completa entrate, uscite e trasferimenti con tracciabilità multi-deposito</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('magazzino.index') }}" class="modern-btn secondary">
                            <i class="bi bi-arrow-left"></i> Torna al Magazzino
                        </a>
                    </div>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="tab-navigation">
                <ul class="nav nav-tabs" id="movimentiTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="gestione-tab" data-bs-toggle="tab" data-bs-target="#gestione" 
                                type="button" role="tab" aria-controls="gestione" aria-selected="true">
                            <i class="bi bi-list-ul"></i> Gestione Movimenti
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="carico-tab" data-bs-toggle="tab" data-bs-target="#carico" 
                                type="button" role="tab" aria-controls="carico" aria-selected="false">
                            <i class="bi bi-plus-circle"></i> Carico
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="scarico-tab" data-bs-toggle="tab" data-bs-target="#scarico" 
                                type="button" role="tab" aria-controls="scarico" aria-selected="false">
                            <i class="bi bi-dash-circle"></i> Scarico
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="trasferimento-tab" data-bs-toggle="tab" data-bs-target="#trasferimento" 
                                type="button" role="tab" aria-controls="trasferimento" aria-selected="false">
                            <i class="bi bi-arrow-repeat"></i> Trasferimento
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Tab Content -->
            <div class="tab-content" id="movimentiTabContent">
                <!-- Tab 1: Gestione Movimenti -->
                <div class="tab-pane fade show active" id="gestione" role="tabpanel" aria-labelledby="gestione-tab">
                    <div class="row mb-3">
                        <div class="col-12">
                            <h3 style="color: #029D7E;"><i class="bi bi-list-ul"></i> Storico Movimenti</h3>
                            <p class="text-muted">Visualizza e gestisci tutti i movimenti di magazzino</p>
                        </div>
                    </div>

                    <!-- Filtri di Ricerca -->
                    <div class="search-filters mb-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control search-input" id="searchMovimenti" placeholder="Cerca per articolo, causale, riferimento...">
                            </div>
                            <div class="col-md-4">
                                <select class="form-select filter-select" id="tipoMovimentoFilter">
                                    <option value="">Tutti i Tipi</option>
                                    <option value="carico">Carico</option>
                                    <option value="scarico">Scarico</option>
                                    <option value="trasferimento">Trasferimento</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="modern-btn secondary w-100" onclick="resetFiltriMovimenti()">
                                    <i class="bi bi-arrow-clockwise"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- La tabella si trova ora sul verde, dopo i tab -->
                </div>

                <!-- Tab 2: Carico -->
                <div class="tab-pane fade" id="carico" role="tabpanel" aria-labelledby="carico-tab">
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <h3 style="color: #029D7E;"><i class="bi bi-plus-circle"></i> Registra Carico</h3>
                            <p class="text-muted mb-4">Incrementa giacenze deposito per entrate merce</p>
                            
                            <form id="formCarico">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Causali di magazzino *</label>
                                        <select class="form-select" id="causaleCarico" required>
                                            <option value="">Seleziona causale</option>
                                            @foreach($causali as $causale)
                                                <option value="{{ $causale->id }}">{{ $causale->descrizione }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Riferimento</label>
                                        <input type="text" class="form-control" id="riferimentoCarico" 
                                               placeholder="es: DDT-001/2025, Fattura 123...">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Deposito *</label>
                                        <select class="form-select" id="depositoCarico" required>
                                            <option value="">Seleziona deposito</option>
                                            @foreach($depositi as $deposito)
                                                <option value="{{ $deposito->id }}">{{ $deposito->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Cliente</label>
                                        <select class="form-select" id="clienteCarico">
                                            <option value="">Nessuno</option>
                                            @foreach($clienti as $cliente)
                                                <option value="{{ $cliente->id }}">{{ $cliente->descrizione }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Fornitore</label>
                                        <select class="form-select" id="fornitoreCarico">
                                            <option value="">Nessuno</option>
                                            @foreach($fornitori as $fornitore)
                                                <option value="{{ $fornitore->id }}">{{ $fornitore->descrizione }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Articolo *</label>
                                        <select class="form-select" id="articoloCarico" required>
                                            <option value="">Seleziona articolo</option>
                                            @foreach($articoli as $articolo)
                                                <option value="{{ $articolo->id }}">{{ $articolo->codice_articolo ?? $articolo->codice_interno }} - {{ $articolo->descrizione }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Quantità *</label>
                                        <input type="number" class="form-control" id="quantitaCarico" required 
                                               min="1" step="1" placeholder="es: 50">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Data Movimento *</label>
                                        <input type="date" class="form-control" id="dataCarico" required 
                                               value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <button type="submit" class="modern-btn green w-100">
                                            <i class="bi bi-plus-circle"></i> Esegui Carico
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tab 3: Scarico -->
                <div class="tab-pane fade" id="scarico" role="tabpanel" aria-labelledby="scarico-tab">
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <h3 style="color: #029D7E;"><i class="bi bi-dash-circle"></i> Registra Scarico</h3>
                            <p class="text-muted mb-4">Decrementa giacenze deposito per uscite merce</p>
                            
                            <form id="formScarico">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Causali di magazzino *</label>
                                        <select class="form-select" id="causaleScarico" required>
                                            <option value="">Seleziona causale</option>
                                            @foreach($causali as $causale)
                                                <option value="{{ $causale->id }}">{{ $causale->descrizione }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Riferimento</label>
                                        <input type="text" class="form-control" id="riferimentoScarico" 
                                               placeholder="es: DDT-002/2025, Vendita 456...">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Deposito *</label>
                                        <select class="form-select" id="depositoScarico" required>
                                            <option value="">Seleziona deposito</option>
                                            @foreach($depositi as $deposito)
                                                <option value="{{ $deposito->id }}">{{ $deposito->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Cliente</label>
                                        <select class="form-select" id="clienteScarico">
                                            <option value="">Nessuno</option>
                                            @foreach($clienti as $cliente)
                                                <option value="{{ $cliente->id }}">{{ $cliente->descrizione }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Fornitore</label>
                                        <select class="form-select" id="fornitoreScarico">
                                            <option value="">Nessuno</option>
                                            @foreach($fornitori as $fornitore)
                                                <option value="{{ $fornitore->id }}">{{ $fornitore->descrizione }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Articolo *</label>
                                        <select class="form-select" id="articoloScarico" required>
                                            <option value="">Seleziona articolo</option>
                                            @foreach($articoli as $articolo)
                                                <option value="{{ $articolo->id }}">{{ $articolo->codice_articolo ?? $articolo->codice_interno }} - {{ $articolo->descrizione }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Quantità *</label>
                                        <input type="number" class="form-control" id="quantitaScarico" required 
                                               min="1" step="1" placeholder="es: 10">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Data Movimento *</label>
                                        <input type="date" class="form-control" id="dataScarico" required 
                                               value="{{ date('Y-m-d') }}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <button type="submit" class="modern-btn green w-100">
                                            <i class="bi bi-dash-circle"></i> Esegui Scarico
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tab 4: Trasferimento -->
                <div class="tab-pane fade" id="trasferimento" role="tabpanel" aria-labelledby="trasferimento-tab">
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <h3 style="color: #029D7E;"><i class="bi bi-arrow-repeat"></i> Trasferimento tra Depositi</h3>
                            <p class="text-muted mb-4">Sposta merce da un deposito all'altro con doppio movimento</p>
                            
                            <form id="formTrasferimento">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Articolo *</label>
                                        <select class="form-select" id="articoloTrasferimento" required>
                                            <option value="">Seleziona articolo</option>
                                            @foreach($articoli as $articolo)
                                                <option value="{{ $articolo->id }}">{{ $articolo->codice_articolo ?? $articolo->codice_interno }} - {{ $articolo->descrizione }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Riferimento</label>
                                        <input type="text" class="form-control" id="riferimentoTrasferimento" 
                                               placeholder="es: TRASF-001/2025...">
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Causali di magazzino *</label>
                                        <select class="form-select" id="causaleTrasferimento" required>
                                            <option value="">Seleziona causale</option>
                                            @foreach($causali as $causale)
                                                <option value="{{ $causale->id }}">{{ $causale->descrizione }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Deposito Sorgente *</label>
                                        <select class="form-select" id="depositoSorgente" required>
                                            <option value="">Seleziona deposito sorgente</option>
                                            @foreach($depositi as $deposito)
                                                <option value="{{ $deposito->id }}">{{ $deposito->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Deposito Destinazione *</label>
                                        <select class="form-select" id="depositoDestinazione" required>
                                            <option value="">Seleziona deposito destinazione</option>
                                            @foreach($depositi as $deposito)
                                                <option value="{{ $deposito->id }}">{{ $deposito->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Quantità *</label>
                                        <input type="number" class="form-control" id="quantitaTrasferimento" required 
                                               min="1" step="1" placeholder="es: 25">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Data Movimento *</label>
                                        <input type="date" class="form-control" id="dataTrasferimento" required 
                                               value="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="col-md-6 mb-3 d-flex align-items-end">
                                        <button type="submit" class="modern-btn green w-100">
                                            <i class="bi bi-arrow-repeat"></i> Esegui Trasferimento
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Storico Movimenti - Tabella su verde come Anagrafiche -->
            <div class="table-responsive" style="margin: 3rem 0 2rem 0;">
                <table class="modern-table" id="movimentiTable">
                    <thead>
                        <tr>
                            <th><i class="bi bi-calendar-event"></i> DATA</th>
                            <th><i class="bi bi-arrow-left-right"></i> TIPO</th>
                            <th><i class="bi bi-tag"></i> CAUSALE</th>
                            <th><i class="bi bi-box"></i> ARTICOLO</th>
                            <th><i class="bi bi-building"></i> DEPOSITO</th>
                            <th><i class="bi bi-123"></i> QUANTITÀ</th>
                            <th><i class="bi bi-people"></i> CLIENTE/FORNITORE</th>
                            <th width="120"><i class="bi bi-gear"></i> AZIONI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movimenti as $movimento)
                        <tr>
                            <td>{{ $movimento->data_movimento ? $movimento->data_movimento->format('d/m/Y') : '-' }}</td>
                            <td>
                                @if($movimento->tipo_movimento === 'carico')
                                    <span class="badge-movimento badge-carico">
                                        <i class="bi bi-plus-circle"></i> Carico
                                    </span>
                                @elseif($movimento->tipo_movimento === 'scarico')
                                    <span class="badge-movimento badge-scarico">
                                        <i class="bi bi-dash-circle"></i> Scarico
                                    </span>
                                @elseif(in_array($movimento->tipo_movimento, ['trasferimento_uscita', 'trasferimento_ingresso']))
                                    <span class="badge-movimento badge-trasferimento">
                                        <i class="bi bi-arrow-repeat"></i> 
                                        {{ $movimento->tipo_movimento === 'trasferimento_uscita' ? 'Trasf. Uscita' : 'Trasf. Ingresso' }}
                                    </span>
                                @else
                                    <span class="badge-movimento" style="background: #6c757d;">
                                        <i class="bi bi-question-circle"></i> {{ ucfirst($movimento->tipo_movimento) }}
                                    </span>
                                @endif
                            </td>
                            <td>{{ $movimento->causale ? $movimento->causale->descrizione : '-' }}</td>
                            <td>
                                @if($movimento->prodotto)
                                    <strong>{{ $movimento->prodotto->descrizione }}</strong>
                                    @if($movimento->prodotto->codice_articolo || $movimento->prodotto->codice_interno)
                                        <br><small class="text-muted">{{ $movimento->prodotto->codice_articolo ?? $movimento->prodotto->codice_interno }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">Prodotto non trovato</span>
                                @endif
                            </td>
                            <td>{{ $movimento->deposito ? $movimento->deposito->description : '-' }}</td>
                            <td>
                                <span class="fw-bold" style="color: {{ in_array($movimento->tipo_movimento, ['carico', 'trasferimento_ingresso']) ? '#10b981' : '#ef4444' }};">
                                    {{ in_array($movimento->tipo_movimento, ['carico', 'trasferimento_ingresso']) ? '+' : '-' }}{{ number_format($movimento->quantita, 0) }}
                                </span>
                            </td>
                            <td>
                                @if($movimento->cliente)
                                    <i class="bi bi-person text-primary"></i> {{ $movimento->cliente->descrizione }}
                                @elseif($movimento->fornitore)
                                    <i class="bi bi-building text-success"></i> {{ $movimento->fornitore->descrizione }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button type="button" class="action-btn view" title="Visualizza dettagli" 
                                            onclick="visualizzaMovimento({{ $movimento->id }})">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <a href="{{ route('magazzino.movimento-pdf', $movimento->id) }}" 
                                       class="action-btn pdf" title="Esporta PDF" target="_blank">
                                        <i class="bi bi-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <h5 class="mt-3 mb-2">Nessun movimento registrato</h5>
                                    <p class="text-muted">Inizia registrando il primo movimento di magazzino usando i tab sopra.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Cards Mobile per Movimenti -->
            <div class="mobile-cards">
                @forelse($movimenti as $movimento)
                    <div class="movimento-card">
                        <div class="movimento-card-header">
                            <h3 class="movimento-card-title">
                                @if($movimento->prodotto)
                                    {{ $movimento->prodotto->descrizione }}
                                @else
                                    Movimento #{{ $movimento->id }}
                                @endif
                            </h3>
                            <div class="movimento-card-type">
                                @if($movimento->tipo_movimento === 'carico')
                                    <span class="badge-movimento badge-carico">
                                        <i class="bi bi-plus-circle"></i> Carico
                                    </span>
                                @elseif($movimento->tipo_movimento === 'scarico')
                                    <span class="badge-movimento badge-scarico">
                                        <i class="bi bi-dash-circle"></i> Scarico
                                    </span>
                                @elseif(in_array($movimento->tipo_movimento, ['trasferimento_uscita', 'trasferimento_ingresso']))
                                    <span class="badge-movimento badge-trasferimento">
                                        <i class="bi bi-arrow-repeat"></i> 
                                        {{ $movimento->tipo_movimento === 'trasferimento_uscita' ? 'Trasf. Uscita' : 'Trasf. Ingresso' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="movimento-card-details">
                            <div class="movimento-detail">
                                <span class="movimento-detail-label">Data</span>
                                <span class="movimento-detail-value">
                                    {{ $movimento->data_movimento ? $movimento->data_movimento->format('d/m/Y') : '-' }}
                                </span>
                            </div>
                            
                            <div class="movimento-detail">
                                <span class="movimento-detail-label">Quantità</span>
                                <span class="movimento-detail-value">
                                    <span style="color: {{ in_array($movimento->tipo_movimento, ['carico', 'trasferimento_ingresso']) ? '#10b981' : '#ef4444' }};">
                                        {{ in_array($movimento->tipo_movimento, ['carico', 'trasferimento_ingresso']) ? '+' : '-' }}{{ number_format($movimento->quantita, 0) }}
                                    </span>
                                </span>
                            </div>
                            
                            <div class="movimento-detail">
                                <span class="movimento-detail-label">Deposito</span>
                                <span class="movimento-detail-value">
                                    {{ $movimento->deposito ? $movimento->deposito->description : '-' }}
                                </span>
                            </div>
                            
                            <div class="movimento-detail">
                                <span class="movimento-detail-label">Causale</span>
                                <span class="movimento-detail-value">
                                    {{ $movimento->causale ? $movimento->causale->descrizione : '-' }}
                                </span>
                            </div>
                            
                            @if($movimento->cliente || $movimento->fornitore)
                            <div class="movimento-detail">
                                <span class="movimento-detail-label">
                                    {{ $movimento->cliente ? 'Cliente' : 'Fornitore' }}
                                </span>
                                <span class="movimento-detail-value">
                                    {{ $movimento->cliente ? $movimento->cliente->descrizione : $movimento->fornitore->descrizione }}
                                </span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="movimento-card-actions">
                            <button type="button" class="mobile-action-btn view" title="Visualizza dettagli" 
                                    onclick="visualizzaMovimento({{ $movimento->id }})">
                                <i class="bi bi-eye"></i>
                            </button>
                            <a href="{{ route('magazzino.movimento-pdf', $movimento->id) }}" 
                               class="mobile-action-btn pdf" title="Esporta PDF" target="_blank">
                                <i class="bi bi-file-pdf"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="movimento-card">
                        <div class="empty-state">
                            <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
                            <h5 class="mt-3 mb-2">Nessun movimento registrato</h5>
                            <p class="text-muted">Inizia registrando il primo movimento di magazzino usando i tab sopra.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal Visualizza Movimento -->
<div class="modal fade" id="visualizzaMovimentoModal" tabindex="-1" aria-labelledby="visualizzaMovimentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #029D7E, #4DC9A5); color: white;">
                <h5 class="modal-title" id="visualizzaMovimentoModalLabel">
                    <i class="bi bi-eye"></i> Dettagli Movimento di Magazzino
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="movimentoDettagliContainer">
                    <!-- I dettagli verranno caricati via AJAX -->
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Caricamento...</span>
                        </div>
                        <p class="mt-2 text-muted">Caricamento dettagli movimento...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" id="esportaPdfBtn" class="modern-btn pdf" target="_blank">
                    <i class="bi bi-file-pdf"></i> Esporta PDF
                </a>
                <button type="button" class="modern-btn secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Chiudi
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Gestione form
document.addEventListener('DOMContentLoaded', function() {
    // Validazione depositi diversi per trasferimenti
    document.getElementById('depositoSorgente').addEventListener('change', function() {
        const sorgente = this.value;
        const destinazione = document.getElementById('depositoDestinazione');
        
        // Disabilita opzione uguale
        Array.from(destinazione.options).forEach(option => {
            option.disabled = option.value === sorgente && option.value !== '';
        });
    });

    // Form submissions
    document.getElementById('formCarico').addEventListener('submit', function(e) {
        e.preventDefault();
        eseguiCarico();
    });

    document.getElementById('formScarico').addEventListener('submit', function(e) {
        e.preventDefault();
        eseguiScarico();
    });

    document.getElementById('formTrasferimento').addEventListener('submit', function(e) {
        e.preventDefault();
        eseguiTrasferimento();
    });

    // Funzione per eseguire carico
    function eseguiCarico() {
        const form = document.getElementById('formCarico');
        const formData = new FormData(form);
        
        const data = {
            causale_id: document.getElementById('causaleCarico').value,
            deposito_id: document.getElementById('depositoCarico').value,
            cliente_id: document.getElementById('clienteCarico').value || null,
            fornitore_id: document.getElementById('fornitoreCarico').value || null,
            articolo_id: document.getElementById('articoloCarico').value,
            quantita: document.getElementById('quantitaCarico').value,
            data_movimento: document.getElementById('dataCarico').value,
            _token: '{{ csrf_token() }}'
        };

        fetch('{{ route("magazzino.carico") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect con session flash message
                window.location.href = data.redirect + '?success=' + encodeURIComponent(data.message);
            } else {
                alert('Errore: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            alert('Errore durante il salvataggio');
        });
    }

    // Funzione per eseguire scarico
    function eseguiScarico() {
        const form = document.getElementById('formScarico');
        
        const data = {
            causale_id: document.getElementById('causaleScarico').value,
            deposito_id: document.getElementById('depositoScarico').value,
            cliente_id: document.getElementById('clienteScarico').value || null,
            fornitore_id: document.getElementById('fornitoreScarico').value || null,
            articolo_id: document.getElementById('articoloScarico').value,
            quantita: document.getElementById('quantitaScarico').value,
            data_movimento: document.getElementById('dataScarico').value,
            _token: '{{ csrf_token() }}'
        };

        fetch('{{ route("magazzino.scarico") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect con session flash message
                window.location.href = data.redirect + '?success=' + encodeURIComponent(data.message);
            } else {
                alert('Errore: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            alert('Errore durante il salvataggio');
        });
    }

    // Funzione per eseguire trasferimento
    function eseguiTrasferimento() {
        const form = document.getElementById('formTrasferimento');
        
        const data = {
            causale_id: document.getElementById('causaleTrasferimento').value,
            deposito_sorgente_id: document.getElementById('depositoSorgente').value,
            deposito_destinazione_id: document.getElementById('depositoDestinazione').value,
            articolo_id: document.getElementById('articoloTrasferimento').value,
            quantita: document.getElementById('quantitaTrasferimento').value,
            data_movimento: document.getElementById('dataTrasferimento').value,
            _token: '{{ csrf_token() }}'
        };

        fetch('{{ route("magazzino.trasferimento") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect con session flash message
                window.location.href = data.redirect + '?success=' + encodeURIComponent(data.message);
            } else {
                alert('Errore: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            alert('Errore durante il salvataggio');
        });
    }

    // Ricerca movimenti
    document.getElementById('searchMovimenti').addEventListener('input', function() {
        // Implementare ricerca
        console.log('Ricerca:', this.value);
    });

    // Filtro tipo movimento
    document.getElementById('tipoMovimentoFilter').addEventListener('change', function() {
        // Implementare filtro tipo movimento
        console.log('Filtro tipo:', this.value);
    });

    // Funzione visualizza movimento
    window.visualizzaMovimento = function(id) {
        // Mostra modal
        const modal = new bootstrap.Modal(document.getElementById('visualizzaMovimentoModal'));
        modal.show();
        
        // Aggiorna link PDF
        document.getElementById('esportaPdfBtn').href = `/magazzino/movimento/${id}/pdf`;
        
        // Reset container con spinner
        document.getElementById('movimentoDettagliContainer').innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Caricamento...</span>
                </div>
                <p class="mt-2 text-muted">Caricamento dettagli movimento...</p>
            </div>
        `;
        
        // Carica dettagli via AJAX
        fetch(`/magazzino/movimento/${id}/dettagli`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('movimentoDettagliContainer').innerHTML = data.html;
                } else {
                    document.getElementById('movimentoDettagliContainer').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle"></i>
                            Errore nel caricamento dei dettagli: ${data.message}
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Errore:', error);
                document.getElementById('movimentoDettagliContainer').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                        Errore di connessione durante il caricamento dei dettagli.
                    </div>
                `;
            });
    };

    // Auto-dismiss alerts dopo 5 secondi
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});

// Reset filtri movimenti
function resetFiltriMovimenti() {
    document.getElementById('searchMovimenti').value = '';
    document.getElementById('tipoMovimentoFilter').value = '';
    
    // Triggera gli eventi per applicare il reset
    document.getElementById('searchMovimenti').dispatchEvent(new Event('input'));
    document.getElementById('tipoMovimentoFilter').dispatchEvent(new Event('change'));
}
</script>
@endsection