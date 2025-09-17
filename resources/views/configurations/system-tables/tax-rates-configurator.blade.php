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
    
    /* Metriche Performance - Stile Golden Standard */
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
    
    .metric-label {
        font-size: 1rem;
        color: #718096;
        margin-top: 0.5rem;
        font-weight: 600;
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
    
    /* Tabella moderna */
    .modern-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .modern-table {
        margin: 0;
        width: 100%;
    }
    
    .modern-table thead th {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        font-weight: 600;
        border: none;
        padding: 1rem;
        font-size: 0.9rem;
        text-align: center;
    }
    
    .modern-table tbody td {
        padding: 1rem;
        border: none;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        text-align: center;
    }
    
    .modern-table tbody tr:hover {
        background: rgba(2, 157, 126, 0.05);
    }
    
    /* Badge percentuali */
    .percentage-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 70px;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 700;
        color: white;
        text-align: center;
    }
    
    .percentage-22 { background: linear-gradient(135deg, #f72585, #c5025a); }
    .percentage-10 { background: linear-gradient(135deg, #00b894, #00a085); }
    .percentage-4 { background: linear-gradient(135deg, #0984e3, #0670c7); }
    .percentage-0 { background: linear-gradient(135deg, #636e72, #4a5258); }
    .percentage-custom { background: linear-gradient(135deg, #6c5ce7, #5f3dc4); }
    
    /* Status badge - Stile come aspetto-beni */
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
    
    .status-badge.active {
        background-color: #d4edda;
        color: #155724;
    }
    
    .status-badge.inactive {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    /* Mobile Cards - Nasconde tabella, mostra card */
    .mobile-cards {
        display: none;
    }
    
    .rate-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .rate-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }
    
    .rate-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .rate-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #2d3748;
        margin: 0;
    }
    
    .rate-card-code {
        font-size: 0.9rem;
        font-weight: 600;
        color: #718096;
    }
    
    .rate-card-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .rate-detail {
        display: flex;
        flex-direction: column;
    }
    
    .rate-detail-label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 0.2rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .rate-detail-value {
        font-weight: 500;
        color: #2d3748;
    }
    
    .rate-card-actions {
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
        text-decoration: none;
    }

    /* Form styling */
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
        background: white;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #029D7E;
        box-shadow: 0 0 0 3px rgba(2, 157, 126, 0.1);
        outline: none;
    }
    
    /* Responsive Design per Mobile */
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
        
        /* Nasconde tabella su mobile */
        .modern-card .table-responsive {
            display: none;
        }
        
        .mobile-cards {
            display: block;
        }
        
        .rate-card {
            padding: 1rem;
        }
        
        .rate-card-details {
            grid-template-columns: 1fr;
            gap: 0.8rem;
        }
        
        .mobile-action-btn {
            padding: 10px 6px;
            font-size: 0.7rem;
            min-width: 70px;
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
                    <i class="bi bi-percent me-3" style="color: #029D7E; font-size: 2rem;"></i>
                    Aliquote IVA
                </h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('configurations.system-tables.index') }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-arrow-left"></i> Torna Indietro
                </a>
                <button type="button" class="btn btn-success modern-btn" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-lg"></i> Nuova Aliquota
                </button>
            </div>
        </div>
        
        <p class="mt-3 mb-0 text-muted">
            Sistema di gestione aliquote IVA per il tuo gestionale
        </p>
    </div>

    <!-- Cards Statistiche secondo il Golden Standard -->
    <div class="metrics-grid">
        <div class="metric-card">
            <h3 class="metric-value">{{ $stats['total_rates'] ?? 0 }}</h3>
            <p class="metric-label">Aliquote Totali</p>
        </div>
        <div class="metric-card">
            <h3 class="metric-value">{{ $stats['active_rates'] ?? 0 }}</h3>
            <p class="metric-label">Aliquote Attive</p>
        </div>
        <div class="metric-card">
            <h3 class="metric-value">{{ $stats['standard_rates'] ?? 0 }}</h3>
            <p class="metric-label">Aliquote Standard</p>
        </div>
        <div class="metric-card">
            <h3 class="metric-value">{{ $stats['custom_rates'] ?? 0 }}</h3>
            <p class="metric-label">Personalizzate</p>
        </div>
    </div>

    <!-- Filtri e ricerca -->
    <div class="search-filters">
        <div class="row g-3">
            <div class="col-md-4">
                <input type="text" class="form-control search-input" id="searchInput" placeholder="Cerca per codice o nome..." onkeyup="filterTable()">
            </div>
            <div class="col-md-3">
                <select class="form-select filter-select" id="statusFilter" onchange="filterTable()">
                    <option value="">Tutti gli stati</option>
                    <option value="1">Attive</option>
                    <option value="0">Inattive</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select filter-select" id="percentageFilter" onchange="filterTable()">
                    <option value="">Tutte le percentuali</option>
                    <option value="22">22%</option>
                    <option value="10">10%</option>
                    <option value="4">4%</option>
                    <option value="0">0%</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-secondary modern-btn w-100" onclick="resetFilters()">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Tabella dati -->
    @if($taxRates->count() > 0)
        <div class="modern-card">
            <div class="table-responsive">
                <table class="table modern-table" id="taxRatesTable">
                    <thead>
                        <tr>
                            <th><i class="bi bi-tag"></i> Codice</th>
                            <th><i class="bi bi-type"></i> Nome</th>
                            <th><i class="bi bi-percent"></i> Aliquota</th>
                            <th><i class="bi bi-file-text"></i> Descrizione</th>
                            <th><i class="bi bi-check-circle"></i> Stato</th>
                            <th><i class="bi bi-calendar"></i> Data Creazione</th>
                            <th><i class="bi bi-gear"></i> Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($taxRates as $rate)
                        <tr class="tax-rate-row">
                            <td>
                                <strong>{{ $rate->code }}</strong>
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $rate->name }}</div>
                            </td>
                            <td>
                                @php
                                    $percentageClass = 'percentage-custom';
                                    if ($rate->percentuale == 22) $percentageClass = 'percentage-22';
                                    elseif ($rate->percentuale == 10) $percentageClass = 'percentage-10';
                                    elseif ($rate->percentuale == 4) $percentageClass = 'percentage-4';
                                    elseif ($rate->percentuale == 0) $percentageClass = 'percentage-0';
                                @endphp
                                <span class="percentage-badge {{ $percentageClass }}">
                                    {{ number_format($rate->percentuale, 2) }}%
                                </span>
                            </td>
                            <td>
                                <div>{{ $rate->description }}</div>
                            </td>
                            <td>
                                @if($rate->active)
                                    <span class="status-badge active">
                                        <i class="bi bi-check-circle"></i> Attiva
                                    </span>
                                @else
                                    <span class="status-badge inactive">
                                        <i class="bi bi-x-circle"></i> Inattiva
                                    </span>
                                @endif
                            </td>
                            <td class="text-muted">
                                {{ $rate->created_at ? $rate->created_at->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td>
                                <button type="button" class="action-btn view" onclick="viewRate({{ $rate->id }})" title="Visualizza">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button type="button" class="action-btn edit" onclick="editRate({{ $rate->id }})" title="Modifica">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button type="button" class="action-btn delete" onclick="deleteRate({{ $rate->id }})" title="Elimina">
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
            @foreach($taxRates as $rate)
                <div class="rate-card mobile-rate-row" 
                     data-status="{{ $rate->active ? '1' : '0' }}"
                     data-percentage="{{ $rate->percentuale }}">
                    
                    <div class="rate-card-header">
                        <h3 class="rate-card-title">{{ $rate->name }}</h3>
                        <div class="d-flex flex-column align-items-end">
                            <span class="rate-card-code">{{ $rate->code }}</span>
                            <div class="mt-1">
                                @php
                                    $percentageClass = 'percentage-custom';
                                    if ($rate->percentuale == 22) $percentageClass = 'percentage-22';
                                    elseif ($rate->percentuale == 10) $percentageClass = 'percentage-10';
                                    elseif ($rate->percentuale == 4) $percentageClass = 'percentage-4';
                                    elseif ($rate->percentuale == 0) $percentageClass = 'percentage-0';
                                @endphp
                                <span class="percentage-badge {{ $percentageClass }}" style="font-size: 0.8rem; padding: 0.3rem 0.6rem;">
                                    {{ number_format($rate->percentuale, 2) }}%
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="rate-card-details">
                        <div class="rate-detail">
                            <span class="rate-detail-label">Descrizione</span>
                            <span class="rate-detail-value">{{ $rate->description }}</span>
                        </div>
                        <div class="rate-detail">
                            <span class="rate-detail-label">Stato</span>
                            <span class="rate-detail-value">
                                @if($rate->active)
                                    <span class="status-badge active" style="font-size: 0.7rem;">
                                        <i class="bi bi-check-circle"></i> Attiva
                                    </span>
                                @else
                                    <span class="status-badge inactive" style="font-size: 0.7rem;">
                                        <i class="bi bi-x-circle"></i> Inattiva
                                    </span>
                                @endif
                            </span>
                        </div>
                        <div class="rate-detail">
                            <span class="rate-detail-label">Data Creazione</span>
                            <span class="rate-detail-value">{{ $rate->created_at ? $rate->created_at->format('d/m/Y') : '-' }}</span>
                        </div>
                        <div class="rate-detail">
                            <span class="rate-detail-label">Riferimento Normativo</span>
                            <span class="rate-detail-value">{{ $rate->riferimento_normativo ? Str::limit($rate->riferimento_normativo, 30) : 'N/A' }}</span>
                        </div>
                    </div>
                    
                    <div class="rate-card-actions">
                        <button type="button" class="mobile-action-btn view" onclick="viewRate({{ $rate->id }})">
                            <i class="bi bi-eye"></i>
                            <span>Visualizza</span>
                        </button>
                        <button type="button" class="mobile-action-btn edit" onclick="editRate({{ $rate->id }})">
                            <i class="bi bi-pencil"></i>
                            <span>Modifica</span>
                        </button>
                        <button type="button" class="mobile-action-btn delete" onclick="deleteRate({{ $rate->id }})">
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
                <i class="bi bi-percent" style="font-size: 4rem; color: #029D7E; opacity: 0.3;"></i>
                <h4>Nessuna aliquota IVA configurata</h4>
                <p class="text-muted">Crea la prima aliquota IVA utilizzando il pulsante "Nuova Aliquota".</p>
            </div>
        </div>
    @endif
</div>

<!-- Modal Nuova Aliquota -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #029D7E, #4DC9A5); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>
                    Nuova Aliquota IVA
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createForm" method="POST" action="{{ route('configurations.system-tables.store', 'tax_rates') }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="modal_code" class="form-label">Codice Aliquota *</label>
                            <input type="text" class="form-control" id="modal_code" name="code" required maxlength="20">
                            <div class="form-text">Massimo 20 caratteri, solo lettere maiuscole, numeri, _ e -</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="modal_name" class="form-label">Nome Aliquota *</label>
                            <input type="text" class="form-control" id="modal_name" name="name" minlength="3" maxlength="255" required>
                            <div class="form-text">Minimo 3 caratteri, massimo 255</div>
                        </div>
                        
                        <div class="col-12">
                            <label for="modal_description" class="form-label">Descrizione *</label>
                            <input type="text" class="form-control" id="modal_description" name="description" minlength="5" maxlength="500" required>
                            <div class="form-text">Minimo 5 caratteri, massimo 500</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="modal_percentuale" class="form-label">Percentuale IVA *</label>
                            <input type="number" class="form-control" id="modal_percentuale" name="percentuale" 
                                   min="0" max="100" step="0.01" required>
                            <div class="form-text">Valore da 0 a 100, con max 2 decimali (es. 22.00)</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="modal_sort_order" class="form-label">Ordine Visualizzazione</label>
                            <input type="number" class="form-control" id="modal_sort_order" name="sort_order" min="0">
                            <div class="form-text">Numero per ordinamento (opzionale)</div>
                        </div>
                        
                        <div class="col-12">
                            <label for="modal_riferimento_normativo" class="form-label">Riferimento Normativo</label>
                            <textarea class="form-control" id="modal_riferimento_normativo" name="riferimento_normativo" rows="3" maxlength="1000"></textarea>
                            <div class="form-text">Massimo 1000 caratteri (opzionale)</div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <!-- Hidden field per assicurare che venga sempre inviato un valore -->
                                <input type="hidden" name="active" value="0">
                                <input class="form-check-input" type="checkbox" id="modal_active" name="active" value="1" checked>
                                <label class="form-check-label" for="modal_active">
                                    <strong>Aliquota attiva</strong>
                                    <small class="text-muted d-block">Se disattivata, l'aliquota non sarà disponibile per nuovi documenti</small>
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
                        <i class="bi bi-check-lg"></i> Crea Aliquota
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
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

// Funzioni filtri e ricerca
function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const percentageFilter = document.getElementById('percentageFilter').value;
    
    // Filtra righe tabella desktop
    const rows = document.querySelectorAll('#taxRatesTable tbody tr');
    rows.forEach(row => {
        const code = row.cells[0].textContent.toLowerCase();
        const name = row.cells[1].textContent.toLowerCase();
        const percentage = row.cells[2].textContent.replace('%', '').trim();
        const status = row.cells[4].textContent.includes('Attiva') ? '1' : '0';

        const matchesSearch = code.includes(searchTerm) || name.includes(searchTerm);
        const matchesStatus = statusFilter === '' || status === statusFilter;
        const matchesPercentage = percentageFilter === '' || percentage.startsWith(percentageFilter);

        if (matchesSearch && matchesStatus && matchesPercentage) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    // Filtra card mobile
    const cards = document.querySelectorAll('.mobile-rate-row');
    cards.forEach(card => {
        const code = card.querySelector('.rate-card-code').textContent.toLowerCase();
        const name = card.querySelector('.rate-card-title').textContent.toLowerCase();
        const percentage = card.dataset.percentage;
        const status = card.dataset.status;

        const matchesSearch = code.includes(searchTerm) || name.includes(searchTerm);
        const matchesStatus = statusFilter === '' || status === statusFilter;
        const matchesPercentage = percentageFilter === '' || percentage.startsWith(percentageFilter);

        if (matchesSearch && matchesStatus && matchesPercentage) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('percentageFilter').value = '';
    filterTable();
}

// Funzioni per i pulsanti azioni
function viewRate(id) {
    // Carica dati per visualizzazione
    fetch(`{{ route("configurations.system-tables.show", "tax_rates") }}/${id}`)
        .then(response => response.json())
        .then(rate => {
            // Crea modale di visualizzazione
            const modalContent = `
                <div class="modal fade" id="viewRateModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content" style="border-radius: 20px; border: none;">
                            <div class="modal-header" style="background: linear-gradient(135deg, #029D7E, #4DC9A5); color: white; border-radius: 20px 20px 0 0;">
                                <h5 class="modal-title">
                                    <i class="bi bi-eye me-2"></i>Dettagli Aliquota IVA
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body" style="padding: 2rem;">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Codice</label>
                                        <div class="p-2 bg-light rounded">${rate.code || 'N/A'}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Nome</label>
                                        <div class="p-2 bg-light rounded">${rate.name || 'N/A'}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Percentuale</label>
                                        <div class="p-2 bg-light rounded">${rate.percentuale || 0}%</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Stato</label>
                                        <div class="p-2 bg-light rounded">${rate.active ? 'Attiva' : 'Inattiva'}</div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold text-muted">Descrizione</label>
                                        <div class="p-2 bg-light rounded">${rate.description || 'N/A'}</div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-bold text-muted">Riferimento Normativo</label>
                                        <div class="p-2 bg-light rounded">${rate.riferimento_normativo || 'N/A'}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-muted">Data Creazione</label>
                                        <div class="p-2 bg-light rounded">${new Date(rate.created_at).toLocaleDateString('it-IT')}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" style="padding: 1.5rem 2rem; border-top: 1px solid #e9ecef;">
                                <button type="button" class="btn btn-secondary modern-btn" data-bs-dismiss="modal">
                                    <i class="bi bi-x-lg"></i> Chiudi
                                </button>
                                <button type="button" class="btn btn-primary modern-btn" onclick="editRate(${rate.id}); bootstrap.Modal.getInstance(document.getElementById('viewRateModal')).hide();">
                                    <i class="bi bi-pencil"></i> Modifica
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Rimuovi modale esistente se presente
            const existingModal = document.getElementById('viewRateModal');
            if (existingModal) {
                existingModal.remove();
            }
            
            // Aggiungi nuova modale al DOM
            document.body.insertAdjacentHTML('beforeend', modalContent);
            
            // Mostra modale
            const modal = new bootstrap.Modal(document.getElementById('viewRateModal'));
            modal.show();
            
            // Rimuovi dal DOM quando si chiude
            document.getElementById('viewRateModal').addEventListener('hidden.bs.modal', function () {
                this.remove();
            });
        })
        .catch(error => {
            console.error('Errore:', error);
            alert('Errore nel caricamento dei dettagli');
        });
}

function editRate(id) {
    // Carica i dati dell'aliquota da modificare
    fetch(`{{ route("configurations.system-tables.show", "tax_rates") }}/${id}`)
        .then(response => response.json())
        .then(rate => {
            // Popola il form di creazione con i dati esistenti
            document.getElementById('modal_code').value = rate.code || '';
            document.getElementById('modal_name').value = rate.name || '';
            document.getElementById('modal_description').value = rate.description || '';
            document.getElementById('modal_percentuale').value = rate.percentuale || '';
            document.getElementById('modal_sort_order').value = rate.sort_order || '';
            document.getElementById('modal_riferimento_normativo').value = rate.riferimento_normativo || '';
            document.getElementById('modal_active').checked = rate.active || false;
            
            // Cambia il titolo della modale
            const modalTitle = document.querySelector('#createModal .modal-title');
            modalTitle.innerHTML = '<i class="bi bi-pencil me-2"></i>Modifica Aliquota IVA';
            
            // Cambia l'action del form per l'update
            const form = document.getElementById('createForm');
            form.action = `{{ route("configurations.system-tables.update", ["table" => "tax_rates", "id" => ":id"]) }}`.replace(':id', id);
            
            // Aggiungi campo hidden per il metodo PUT
            let methodField = form.querySelector('input[name="_method"]');
            if (!methodField) {
                methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PUT';
                form.appendChild(methodField);
            }
            
            // Cambia il testo del pulsante di submit
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> Aggiorna Aliquota';
            
            // Mostra la modale
            const modal = new bootstrap.Modal(document.getElementById('createModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Errore:', error);
            alert('Errore nel caricamento dei dati per la modifica');
        });
}

function deleteRate(id) {
    if (confirm('Sei sicuro di voler eliminare questa aliquota IVA?')) {
        fetch(`{{ route("configurations.system-tables.destroy", ["table" => "tax_rates", "id" => ":id"]) }}`.replace(':id', id), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Errore HTTP: ${response.status}`);
            }
            
            const contentType = response.headers.get('Content-Type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                return { success: true, message: 'Eliminazione completata' };
            }
        })
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Errore nell\'eliminazione: ' + (data.message || 'Errore sconosciuto'));
            }
        })
        .catch(error => {
            console.error('Errore completo:', error);
            alert('Errore nell\'eliminazione: ' + error.message);
        });
    }
}

// Reset form quando si chiude il modal
document.getElementById('createModal').addEventListener('hidden.bs.modal', function () {
    const form = document.getElementById('createForm');
    form.reset();
    
    // Ripristina il form alla modalità creazione
    resetFormToCreateMode();
});

function resetFormToCreateMode() {
    const form = document.getElementById('createForm');
    const modalTitle = document.querySelector('#createModal .modal-title');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Ripristina titolo
    modalTitle.innerHTML = '<i class="bi bi-plus-circle me-2"></i>Nuova Aliquota IVA';
    
    // Ripristina action del form
    form.action = '{{ route("configurations.system-tables.store", "tax_rates") }}';
    
    // Rimuovi campo method PUT se presente
    const methodField = form.querySelector('input[name="_method"]');
    if (methodField) {
        methodField.remove();
    }
    
    // Ripristina testo pulsante
    submitBtn.innerHTML = '<i class="bi bi-check-lg"></i> Crea Aliquota';
}

// Aggiungi listener per il pulsante "Nuova Aliquota"
document.querySelector('[data-bs-target="#createModal"]').addEventListener('click', function() {
    resetFormToCreateMode();
});
</script>