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
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2) !important;
    }
    
    /* Gradiente standard per tutti i button */
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
    
    .config-section {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .coming-soon {
        text-align: center;
        padding: 3rem;
        color: #718096;
    }
    
    .coming-soon i {
        font-size: 4rem;
        color: #029D7E;
        margin-bottom: 1rem;
    }
    
    /* Form styling */
    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #029D7E;
        box-shadow: 0 0 0 3px rgba(2, 157, 126, 0.1);
        outline: none;
    }
    
    .btn-success {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(2, 157, 126, 0.3);
    }
    
    /* Alert styling */
    .alert {
        border-radius: 12px;
        border: none;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    /* Modern Table Styling - Coerente con il gestionale */
    .modern-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .modern-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }
    
    .modern-table {
        margin: 0;
        width: 100%;
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
    
    .modern-table tbody tr {
        transition: all 0.3s ease;
        border: none;
    }
    
    .modern-table tbody tr:hover {
        background: rgba(2, 157, 126, 0.05);
        transform: scale(1.01);
    }
    
    .modern-table tbody td {
        padding: 1rem;
        border: none;
        border-bottom: 1px solid rgba(2, 157, 126, 0.1);
        vertical-align: middle;
        white-space: nowrap;
        text-align: center;
    }
    
    .modern-table tbody td:first-child {
        text-align: left;
    }
    
    .modern-table tbody td:nth-child(2) {
        text-align: center;
    }
    
    .modern-table tbody td:nth-child(3) {
        text-align: left;
    }
    
    /* .action-btn {
        border: none;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        margin: 0 2px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        min-width: 32px;
        text-align: center;
    } */
    
    .action-btn.delete {
        background: linear-gradient(135deg, #f72585, #c5025a);
        color: white;
        padding: 8px;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2) !important;
        color: white;
    }
    
    .action-btn.view:hover,
    .action-btn.edit:hover,
    .action-btn.delete:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2) !important;
    }
    
    .action-btn.delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(247, 37, 133, 0.4);
        color: white;
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
    
    .vat-nature-cell {
        display: flex;
        align-items: center;
    }
    
    .vat-nature-description {
        color: #718096;
        font-size: 0.875rem;
        vertical-align: middle;
    }
    
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
    
    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-badge.default {
        background-color: #fff3e0;
        color: #ef6c00;
    }
    
    .status-badge.normal {
        background-color: #e3f2fd;
        color: #1565c0;
    }
    
    /* Mobile Cards */
    .mobile-cards {
        display: none;
    }
    
    .association-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .association-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .card-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
        flex: 1;
        min-width: 0;
        color: #2d3748;
    }
    
    .card-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .card-detail {
        display: flex;
        flex-direction: column;
    }
    
    .card-detail-label {
        font-size: 0.8rem;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 0.3rem;
    }
    
    .card-detail-value {
        font-weight: 600;
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
    
    /* Metriche Performance - Stile Gestionale */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .metric-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
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
        background: linear-gradient(135deg, #4ecdc4, #44a08d); /* Turchese-Verde */
    }
    
    .metric-card:nth-child(2)::before {
        background: linear-gradient(135deg, #48cae4, #0077b6); /* Azzurro-Blu */
    }
    
    .metric-card:nth-child(3)::before {
        background: linear-gradient(135deg, #9c27b0, #7b1fa2); /* Viola-Magenta */
    }
    
    .metric-card:nth-child(4)::before {
        background: linear-gradient(135deg, #ffd60a, #ff8500); /* Giallo-Arancione */
    }
    
    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }
    
    .metric-value {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
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
    
    .search-input {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #029D7E;
        box-shadow: 0 0 0 3px rgba(2, 157, 126, 0.1);
    }
    
    .filter-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
    }
    
    .filter-select:focus {
        outline: none;
        border-color: #029D7E;
        box-shadow: 0 0 0 3px rgba(2, 157, 126, 0.1);
    }
    
    /* Pulsanti Azioni */
    .action-btn {
        border: none;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        margin: 0 2px;
        transition: all 0.3s ease;
        text-decoration: none;
        cursor: pointer;
    }
    
    .action-btn.view {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
        padding: 8px;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .action-btn.edit {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
        padding: 8px;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
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
    
    .action-btn.view:hover,
    .action-btn.edit:hover,
    .action-btn.delete:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2) !important;
    }
    
    /* Colori numeri abbinati ai border */
    .metric-card:nth-child(1) .metric-value {
        color: #4ecdc4; /* Turchese-Verde */
    }
    
    .metric-card:nth-child(2) .metric-value {
        color: #48cae4; /* Azzurro-Blu */
    }
    
    .metric-card:nth-child(3) .metric-value {
        color: #9c27b0; /* Viola-Magenta */
    }
    
    .metric-card:nth-child(4) .metric-value {
        color: #ffd60a; /* Giallo-Arancione */
    }
    
    .metric-label {
        font-size: 0.9rem;
        color: #718096;
        margin-top: 0.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .modern-card .table-responsive {
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
        
        .management-title {
            font-size: 1.8rem;
        }
        
        .modern-btn {
            padding: 10px 16px;
            font-size: 0.9rem;
        }
        
        .card-details {
            grid-template-columns: 1fr;
            gap: 0.8rem;
        }
        
        .metrics-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.8rem;
        }
        
        .metric-card {
            padding: 1rem;
        }
        
        .metric-value {
            font-size: 1.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .management-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .modern-btn {
            padding: 10px 16px;
            font-size: 0.9rem;
            width: 100%;
            justify-content: center;
        }
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
    
    <!-- Header -->
    <div class="management-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <h1 class="management-title">
                    <i class="bi bi-link-45deg me-3" style="color: #029D7E; font-size: 2rem;"></i>
                    Associazioni Nature IVA
                </h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('configurations.system-tables.index') }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-arrow-left"></i> Torna Indietro
                </a>
                <button type="button" class="btn btn-success modern-btn" data-bs-toggle="modal" data-bs-target="#associationModal" onclick="openAddModal()">
                    <i class="bi bi-plus-lg"></i> Nuova Associazione
                </button>
            </div>
        </div>
        
        <p class="mt-3 mb-0 text-muted">
            Configuratore avanzato per associazioni tra aliquote IVA e nature fiscali
        </p>
    </div>

    <!-- Cards Statistiche secondo il Golden Standard -->
    <div class="metrics-grid">
        <div class="metric-card">
            <h3 class="metric-value">{{ $associations->count() }}</h3>
            <p class="metric-label">Associazioni Totali</p>
        </div>
        <div class="metric-card">
            <h3 class="metric-value">{{ $taxRates->count() }}</h3>
            <p class="metric-label">Aliquote IVA</p>
        </div>
        <div class="metric-card">
            <h3 class="metric-value">{{ $vatNatures->count() }}</h3>
            <p class="metric-label">Nature IVA Attive</p>
        </div>
        <div class="metric-card">
            <h3 class="metric-value">{{ $associations->where('is_default', true)->count() }}</h3>
            <p class="metric-label">Predefinite</p>
        </div>
    </div>

    <!-- Filtri e ricerca -->
    <div class="search-filters">
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-control search-input" id="searchInput" placeholder="Cerca per nome associazione o aliquota..." onkeyup="filterTable()">
            </div>
            <div class="col-md-3">
                <select class="form-select filter-select" id="taxRateFilter" onchange="filterTable()">
                    <option value="">Tutte le aliquote</option>
                    @foreach($taxRates as $rate)
                        <option value="{{ $rate->id }}">{{ $rate->code }} - {{ $rate->rate }}%</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select filter-select" id="defaultFilter" onchange="filterTable()">
                    <option value="">Tutti gli stati</option>
                    <option value="1">Predefinite</option>
                    <option value="0">Non predefinite</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-secondary modern-btn w-100" onclick="resetFilters()">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Form Nuova Associazione (Nascosto, compatibilità) -->
    <div class="config-section" style="display: none;" id="legacy-form-section">
        <div class="section-title">
            <i class="bi bi-plus-circle text-success"></i>
            Crea Nuova Associazione
        </div>
        
        <form method="POST" action="{{ route('configurations.system-tables.store', 'associazioni-nature-iva') }}" class="row g-3">
            @csrf
            
            <div class="col-md-6">
                <label for="nome_associazione" class="form-label">Nome Associazione *</label>
                <input type="text" class="form-control" id="nome_associazione" name="nome_associazione" 
                       placeholder="es. IVA 22% Natura N1" value="{{ old('nome_associazione') }}" required>
                @error('nome_associazione')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="descrizione" class="form-label">Descrizione</label>
                <input type="text" class="form-control" id="descrizione" name="descrizione" 
                       placeholder="Descrizione dettagliata dell'associazione" value="{{ old('descrizione') }}">
                @error('descrizione')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="tax_rate_id" class="form-label">Aliquota IVA *</label>
                <select class="form-select" id="tax_rate_id" name="tax_rate_id" required>
                    <option value="">Seleziona aliquota IVA...</option>
                    @foreach($taxRates as $taxRate)
                    <option value="{{ $taxRate->id }}" {{ old('tax_rate_id') == $taxRate->id ? 'selected' : '' }}>
                        {{ $taxRate->code }} - {{ $taxRate->description }} ({{ $taxRate->rate }}%)
                    </option>
                    @endforeach
                </select>
                @error('tax_rate_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="vat_nature_id" class="form-label">Natura IVA *</label>
                <select class="form-select" id="vat_nature_id" name="vat_nature_id" required>
                    <option value="">Seleziona natura IVA...</option>
                    @foreach($vatNatures as $vatNature)
                    <option value="{{ $vatNature->id }}" {{ old('vat_nature_id') == $vatNature->id ? 'selected' : '' }}>
                        {{ $vatNature->code }} - {{ $vatNature->name }}
                    </option>
                    @endforeach
                </select>
                @error('vat_nature_id')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1" 
                           {{ old('is_default') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_default">
                        Imposta come associazione predefinita per questa aliquota IVA
                    </label>
                </div>
            </div>
            
            <div class="col-12">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Crea Associazione
                </button>
                <button type="reset" class="btn btn-outline-secondary ms-2">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </button>
            </div>
        </form>
    </div>

    <!-- Tabella dati -->
    @if($associations->count() > 0)
        <!-- Tabella Desktop -->
        <div class="modern-card">
                <div class="table-responsive">
                    <table class="table modern-table" id="associationsTable">
                        <thead>
                            <tr>
                                <th><i class="bi bi-tag"></i> Nome Associazione</th>
                                <th><i class="bi bi-percent"></i> Aliquota IVA</th>
                                <th><i class="bi bi-file-earmark-text"></i> Natura IVA</th>
                                <th><i class="bi bi-star"></i> Predefinita</th>
                                <th><i class="bi bi-calendar"></i> Data Creazione</th>
                                <th><i class="bi bi-gear"></i> Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($associations as $association)
                            <tr class="association-row">
                                <td>
                                    <div class="association-name">{{ $association->nome_associazione ?? $association->display_name }}</div>
                                    @if($association->descrizione)
                                        <div class="association-description">{{ $association->descrizione }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge" style="background: linear-gradient(135deg, #48cae4, #0077b6); color: white;">
                                        {{ $association->taxRate->code ?? 'N/A' }} - {{ $association->taxRate->rate ?? 0 }}%
                                    </span>
                                </td>
                                <td>
                                    <div class="vat-nature-cell">
                                        <span class="badge" style="background: linear-gradient(135deg, #ffd60a, #ff8500); color: white;">
                                            {{ $association->vatNature->code ?? 'N/A' }}
                                        </span>
                                        @if($association->vatNature->name ?? false)
                                            <span class="vat-nature-description">{{ $association->vatNature->name }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($association->is_default)
                                        <span class="status-badge default">
                                            <i class="bi bi-star-fill"></i> Predefinita
                                        </span>
                                    @else
                                        <span class="status-badge normal">
                                            <i class="bi bi-star"></i> Normale
                                        </span>
                                    @endif
                                </td>
                                <td class="text-muted">
                                    {{ $association->created_at ? $association->created_at->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td>
                                    <button type="button" class="action-btn view" onclick="viewAssociation({{ $association->id }})" title="Visualizza">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button type="button" class="action-btn edit" onclick="editAssociation({{ $association->id }})" title="Modifica">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="action-btn delete" onclick="deleteAssociation({{ $association->id }})" title="Elimina">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Cards Mobile -->
            <div class="mobile-cards">
                @foreach($associations as $association)
                <div class="association-card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $association->nome_associazione ?? $association->display_name }}</h3>
                        @if($association->is_default)
                            <span class="status-badge default">
                                <i class="bi bi-star-fill"></i> Predefinita
                            </span>
                        @else
                            <span class="status-badge normal">
                                <i class="bi bi-star"></i> Normale
                            </span>
                        @endif
                    </div>
                    
                    @if($association->descrizione)
                        <div class="association-description mb-3">{{ $association->descrizione }}</div>
                    @endif
                    
                    <div class="card-details">
                        <div class="card-detail">
                            <span class="card-detail-label">Aliquota IVA</span>
                            <span class="card-detail-value">
                                <span class="badge" style="background: linear-gradient(135deg, #48cae4, #0077b6); color: white; font-size: 0.7rem;">
                                    {{ $association->taxRate->code ?? 'N/A' }} - {{ $association->taxRate->rate ?? 0 }}%
                                </span>
                            </span>
                        </div>
                        <div class="card-detail">
                            <span class="card-detail-label">Natura IVA</span>
                            <span class="card-detail-value">
                                <span class="badge" style="background: linear-gradient(135deg, #ffd60a, #ff8500); color: white; font-size: 0.7rem;">
                                    {{ $association->vatNature->code ?? 'N/A' }}
                                </span>
                                @if($association->vatNature->name ?? false)
                                    <br><small class="text-muted">{{ $association->vatNature->name }}</small>
                                @endif
                            </span>
                        </div>
                        <div class="card-detail">
                            <span class="card-detail-label">Data Creazione</span>
                            <span class="card-detail-value text-muted">
                                {{ $association->created_at ? $association->created_at->format('d/m/Y H:i') : '-' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2 mt-3">
                        <button type="button" class="mobile-action-btn view" onclick="viewAssociation({{ $association->id }})">
                            <i class="bi bi-eye"></i>
                            <span>Visualizza</span>
                        </button>
                        <button type="button" class="mobile-action-btn edit" onclick="editAssociation({{ $association->id }})">
                            <i class="bi bi-pencil"></i>
                            <span>Modifica</span>
                        </button>
                        <button type="button" class="mobile-action-btn delete" onclick="deleteAssociation({{ $association->id }})">
                            <i class="bi bi-trash"></i>
                            <span>Elimina</span>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
    @else
        <div class="modern-card">
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                <h5 class="text-muted mt-3">Nessuna associazione configurata</h5>
                <p class="text-muted">Crea la prima associazione Nature IVA utilizzando il form sopra.</p>
            </div>
        </div>
    @endif

</div>

<!-- Modal Nuova Associazione -->
<div class="modal fade" id="associationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #029D7E, #4DC9A5); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title" id="associationModalTitle">
                    <i class="bi bi-plus-circle me-2"></i>
                    Nuova Associazione Nature IVA
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="associationForm" method="POST" action="{{ route('configurations.system-tables.store', 'associazioni-nature-iva') }}">
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
                                @foreach($taxRates as $taxRate)
                                <option value="{{ $taxRate->id }}">
                                    {{ $taxRate->code }} - {{ $taxRate->description }} ({{ $taxRate->rate }}%)
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="modal_vat_nature_id" class="form-label">Natura IVA *</label>
                            <select class="form-select" id="modal_vat_nature_id" name="vat_nature_id" required>
                                <option value="">Seleziona natura IVA...</option>
                                @foreach($vatNatures as $vatNature)
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
                <div class="modal-footer" style="padding: 1.5rem 2rem; border-top: 1px solid #e9ecef;">
                    <button type="button" class="btn btn-secondary modern-btn" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg"></i> Annulla
                    </button>
                    <button type="submit" class="btn btn-success modern-btn">
                        <i class="bi bi-check-lg"></i> Crea Associazione
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

<!-- Script per migliorare l'esperienza utente -->
<script>
// Funzione per aprire modale nuova associazione
function openAddModal() {
    // Reset form
    document.getElementById('associationForm').reset();
    
    // Focus al primo campo
    setTimeout(() => {
        document.getElementById('modal_nome_associazione').focus();
    }, 500);
}

// Funzione per toggle form legacy (compatibilità)
function toggleLegacyForm() {
    const section = document.getElementById('legacy-form-section');
    const toggle = document.querySelector('a[onclick="toggleLegacyForm()"]');
    
    if (section.style.display === 'none') {
        section.style.display = 'block';
        toggle.innerHTML = '<i class="bi bi-toggle-on"></i> Nascondi form tradizionale';
    } else {
        section.style.display = 'none';
        toggle.innerHTML = '<i class="bi bi-toggle-off"></i> Mostra form tradizionale';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts dopo 5 secondi
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // Conferma eliminazione con dettagli
    const deleteButtons = document.querySelectorAll('form[method="POST"][onsubmit*="confirm"] button[type="submit"]');
    deleteButtons.forEach(function(button) {
        button.closest('form').onsubmit = function(e) {
            const row = button.closest('tr');
            const nomeAssociazione = row.querySelector('td:first-child strong').textContent.trim();
            const aliquota = row.querySelector('.badge.bg-info').textContent.trim();
            
            const confirmed = confirm(`Sei sicuro di voler eliminare l'associazione "${nomeAssociazione}" con aliquota ${aliquota}?\n\nQuesta azione non può essere annullata.`);
            if (!confirmed) {
                e.preventDefault();
                return false;
            }
            return true;
        };
    });
    
    // Validazione client-side form
    const form = document.querySelector('form[method="POST"]');
    if (form && !form.hasAttribute('onsubmit')) {
        form.addEventListener('submit', function(e) {
            const nomeAssociazione = document.getElementById('nome_associazione');
            const taxRateId = document.getElementById('tax_rate_id');
            const vatNatureId = document.getElementById('vat_nature_id');
            
            // Validazione nome associazione
            if (nomeAssociazione.value.trim().length < 3) {
                alert('Il nome associazione deve essere di almeno 3 caratteri.');
                nomeAssociazione.focus();
                e.preventDefault();
                return false;
            }
            
            // Validazione selezioni
            if (!taxRateId.value) {
                alert('Devi selezionare un\'aliquota IVA.');
                taxRateId.focus();
                e.preventDefault();
                return false;
            }
            
            if (!vatNatureId.value) {
                alert('Devi selezionare una natura IVA.');
                vatNatureId.focus();
                e.preventDefault();
                return false;
            }
            
            return true;
        });
    }
    
    // Auto-genera nome associazione quando si selezionano aliquota e natura
    const taxRateSelect = document.getElementById('tax_rate_id');
    const vatNatureSelect = document.getElementById('vat_nature_id');
    const nomeAssociazioneInput = document.getElementById('nome_associazione');
    
    function autoGenerateAssociationName() {
        if (taxRateSelect.value && vatNatureSelect.value && !nomeAssociazioneInput.value.trim()) {
            const taxRateText = taxRateSelect.options[taxRateSelect.selectedIndex].text;
            const vatNatureText = vatNatureSelect.options[vatNatureSelect.selectedIndex].text;
            
            // Estrai codice aliquota e natura
            const taxRateCode = taxRateText.split(' - ')[0];
            const vatNatureCode = vatNatureText.split(' - ')[0];
            
            nomeAssociazioneInput.value = `${taxRateCode} + ${vatNatureCode}`;
        }
    }
    
    if (taxRateSelect && vatNatureSelect && nomeAssociazioneInput) {
        taxRateSelect.addEventListener('change', autoGenerateAssociationName);
        vatNatureSelect.addEventListener('change', autoGenerateAssociationName);
    }
    
    // Animazione di entrata delle righe tabella
    const associationRows = document.querySelectorAll('.association-row');
    associationRows.forEach((row, index) => {
        setTimeout(() => {
            row.style.opacity = '0';
            row.style.transform = 'translateX(-50px)';
            row.style.transition = 'all 0.5s ease';
            
            setTimeout(() => {
                row.style.opacity = '1';
                row.style.transform = 'translateX(0)';
            }, 100);
        }, index * 100);
    });
    
    // Animazione di entrata delle card mobile
    const associationCards = document.querySelectorAll('.association-card');
    associationCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.5s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        }, index * 150);
    });
});

// Funzione globale per conferma eliminazione
function confirmDelete(associationName) {
    return confirm(`Sei sicuro di voler eliminare l'associazione "${associationName}"?\\n\\nQuesta azione non può essere annullata.`);
}

// Funzioni per i pulsanti azioni
function viewAssociation(id) {
    // Carica dati per visualizzazione
    fetch(`{{ route("configurations.system-tables.show", "associazioni-nature-iva") }}/${id}`)
        .then(response => response.json())
        .then(association => {
            // Crea modale di visualizzazione
            const modalContent = `
                <div class="modal fade" id="viewAssociationModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content" style="border-radius: 20px; border: none;">
                            <div class="modal-header" style="background: linear-gradient(135deg, #029D7E, #4DC9A5); color: white; border-radius: 20px 20px 0 0;">
                                <h5 class="modal-title">
                                    <i class="bi bi-eye me-2"></i>Dettagli Associazione Nature IVA
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body" style="padding: 2rem;">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Nome Associazione</label>
                                        <div class="p-2 bg-light rounded">${association.nome_associazione || 'N/A'}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Descrizione</label>
                                        <div class="p-2 bg-light rounded">${association.descrizione || 'N/A'}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Aliquota IVA</label>
                                        <div class="p-2 bg-light rounded">${association.tax_rate?.code || 'N/A'} - ${association.tax_rate?.rate || 0}%</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Natura IVA</label>
                                        <div class="p-2 bg-light rounded">${association.vat_nature?.code || 'N/A'}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Predefinita</label>
                                        <div class="p-2 bg-light rounded">${association.is_default ? 'Sì' : 'No'}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Data Creazione</label>
                                        <div class="p-2 bg-light rounded">${new Date(association.created_at).toLocaleDateString('it-IT')}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" style="padding: 1.5rem 2rem; border-top: 1px solid #e9ecef;">
                                <button type="button" class="btn btn-secondary modern-btn" data-bs-dismiss="modal">
                                    <i class="bi bi-x-lg"></i> Chiudi
                                </button>
                                <button type="button" class="btn btn-primary modern-btn" onclick="editAssociation(${association.id}); bootstrap.Modal.getInstance(document.getElementById('viewAssociationModal')).hide();">
                                    <i class="bi bi-pencil"></i> Modifica
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Rimuovi modale esistente se presente
            const existingModal = document.getElementById('viewAssociationModal');
            if (existingModal) {
                existingModal.remove();
            }
            
            // Aggiungi nuova modale al DOM
            document.body.insertAdjacentHTML('beforeend', modalContent);
            
            // Mostra modale
            const modal = new bootstrap.Modal(document.getElementById('viewAssociationModal'));
            modal.show();
            
            // Rimuovi dal DOM quando si chiude
            document.getElementById('viewAssociationModal').addEventListener('hidden.bs.modal', function () {
                this.remove();
            });
        })
        .catch(error => {
            console.error('Errore:', error);
            alert('Errore nel caricamento dei dettagli');
        });
}

function editAssociation(id) {
    // Carica dati per modifica
    fetch(`{{ route("configurations.system-tables.show", "associazioni-nature-iva") }}/${id}`)
        .then(response => response.json())
        .then(association => {
            // Popola form di modifica nel modale esistente
            const form = document.getElementById('associationForm');
            form.action = `{{ route("configurations.system-tables.update", ["table" => "associazioni-nature-iva", "id" => ":id"]) }}`.replace(':id', id);
            
            // Rimuovi eventuali campi _method esistenti
            const existingMethod = form.querySelector('input[name="_method"]');
            if (existingMethod) {
                existingMethod.remove();
            }
            
            // Aggiungi campo method per PUT
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            form.appendChild(methodInput);
            
            // Popola i campi del form
            document.getElementById('modal_nome_associazione').value = association.nome_associazione || '';
            document.getElementById('modal_descrizione').value = association.descrizione || '';
            document.getElementById('modal_tax_rate_id').value = association.tax_rate_id || '';
            document.getElementById('modal_vat_nature_id').value = association.vat_nature_id || '';
            document.getElementById('modal_is_default').checked = association.is_default || false;
            
            // Cambia titolo del modale
            document.getElementById('associationModalTitle').innerHTML = '<i class="bi bi-pencil me-2"></i>Modifica Associazione Nature IVA';
            
            // Mostra modale
            const modal = new bootstrap.Modal(document.getElementById('associationModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Errore:', error);
            alert('Errore nel caricamento dei dati per la modifica');
        });
}

function deleteAssociation(id) {
    if (confirm('Sei sicuro di voler eliminare questa associazione?')) {
        fetch(`{{ route("configurations.system-tables.destroy", ["table" => "associazioni-nature-iva", "id" => ":id"]) }}`.replace(':id', id), {
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
                alert('Errore nell\'eliminazione');
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Reset form quando si chiude il modal per ripristinare modalità "Crea"
    const associationModal = document.getElementById('associationModal');
    if (associationModal) {
        associationModal.addEventListener('hidden.bs.modal', function () {
            const form = document.getElementById('associationForm');
            if (form) {
                form.reset();
                form.action = `{{ route("configurations.system-tables.store", "associazioni-nature-iva") }}`;
                
                // Rimuovi campo _method se presente
                const methodField = form.querySelector('input[name="_method"]');
                if (methodField) {
                    methodField.remove();
                }
                
                // Ripristina titolo originale
                const modalTitle = document.getElementById('associationModalTitle');
                if (modalTitle) {
                    modalTitle.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Nuova Associazione Nature IVA';
                }
            }
        });
    }
});
</script>