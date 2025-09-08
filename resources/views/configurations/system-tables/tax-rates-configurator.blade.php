@extends('layouts.app')

@section('content')
<style>
    .tax-rates-page {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .dashboard-container {
        padding: 2rem;
        min-height: calc(100vh - 70px);
    }
    
    /* Header stile gestionale */
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
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .back-button {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 12px;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        border: none;
    }
    
    .back-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(2, 157, 126, 0.3);
        color: white;
        text-decoration: none;
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
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }
    
    /* Form styling premium */
    .form-label {
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.7rem;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 15px;
        padding: 1rem 1.2rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
        font-weight: 500;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #d63031;
        box-shadow: 0 0 0 4px rgba(214, 48, 49, 0.15);
        outline: none;
        background: white;
        transform: translateY(-2px);
    }
    
    /* Validazione percentuali con stile premium */
    .percentage-input {
        position: relative;
    }
    
    .percentage-input::after {
        content: '%';
        position: absolute;
        right: 1.2rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.2rem;
        font-weight: 700;
        color: #d63031;
        pointer-events: none;
    }
    
    .percentage-input input {
        padding-right: 3rem;
        text-align: center;
        font-size: 1.1rem;
        font-weight: 700;
    }
    
    /* Pulsanti premium */
    .btn-success {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        border: none;
        padding: 1rem 2rem;
        border-radius: 15px;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 8px 25px rgba(2, 157, 126, 0.3);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(2, 157, 126, 0.4);
    }
    
    .btn-outline-secondary {
        border: 2px solid #718096;
        color: #718096;
        padding: 1rem 2rem;
        border-radius: 15px;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .btn-outline-secondary:hover {
        background: #718096;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(113, 128, 150, 0.3);
    }
    
    /* Alert premium */
    .alert {
        border-radius: 20px;
        border: none;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }
    
    .alert-success {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.9), rgba(25, 135, 84, 0.9));
        color: white;
    }
    
    .alert-danger {
        background: linear-gradient(135deg, rgba(220, 53, 69, 0.9), rgba(176, 42, 55, 0.9));
        color: white;
    }
    
    /* Tabella moderna allineata con Nature IVA */
    .premium-table-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .premium-table-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }
    
    .premium-table {
        margin: 0;
        width: 100%;
    }
    
    .premium-table thead th {
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
    
    .premium-table tbody tr {
        transition: all 0.3s ease;
        border: none;
    }
    
    .premium-table tbody tr:hover {
        background: rgba(2, 157, 126, 0.05);
        transform: scale(1.01);
    }
    
    .premium-table tbody td {
        padding: 1rem;
        border: none;
        border-bottom: 1px solid rgba(2, 157, 126, 0.1);
        vertical-align: middle;
    }
    
    /* Badge percentuali premium */
    .percentage-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 80px;
        padding: 0.8rem 1.2rem;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 900;
        color: white;
        text-align: center;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .percentage-badge:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.25);
    }
    
    .percentage-22 { background: linear-gradient(135deg, #d63031, #c42021); }
    .percentage-10 { background: linear-gradient(135deg, #00b894, #00a085); }
    .percentage-4 { background: linear-gradient(135deg, #0984e3, #0670c7); }
    .percentage-0 { background: linear-gradient(135deg, #636e72, #4a5258); }
    .percentage-custom { background: linear-gradient(135deg, #6c5ce7, #5f3dc4); }
    
    /* Status badge premium */
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .status-badge.active {
        background: linear-gradient(135deg, #00b894, #00a085);
        color: white;
        box-shadow: 0 4px 15px rgba(0, 184, 148, 0.3);
    }
    
    .status-badge.inactive {
        background: linear-gradient(135deg, #636e72, #4a5258);
        color: white;
        box-shadow: 0 4px 15px rgba(99, 110, 114, 0.3);
    }
    
    /* Action buttons premium */
    .action-btn {
        border: none;
        border-radius: 12px;
        padding: 8px 12px;
        font-size: 0.9rem;
        font-weight: 700;
        margin: 0 3px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        text-align: center;
    }
    
    .action-btn.edit {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
        box-shadow: 0 4px 15px rgba(255, 214, 10, 0.3);
    }
    
    .action-btn.delete {
        background: linear-gradient(135deg, #f72585, #c5025a);
        color: white;
        box-shadow: 0 4px 15px rgba(247, 37, 133, 0.3);
    }
    
    .action-btn:hover {
        transform: translateY(-3px) scale(1.1);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        color: white;
    }
    
    /* Metriche dashboard premium */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }
    
    .metric-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 25px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(15px);
    }
    
    .metric-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        border-radius: 25px 25px 0 0;
    }
    
    /* Colori premium per le metriche */
    .metric-card:nth-child(1)::before {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
    }
    
    .metric-card:nth-child(2)::before {
        background: linear-gradient(135deg, #00b894, #00a085);
    }
    
    .metric-card:nth-child(3)::before {
        background: linear-gradient(135deg, #0984e3, #0670c7);
    }
    
    .metric-card:nth-child(4)::before {
        background: linear-gradient(135deg, #6c5ce7, #5f3dc4);
    }
    
    .metric-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.2);
    }
    
    .metric-value {
        font-size: 2.5rem;
        font-weight: 900;
        margin: 0;
        line-height: 1;
    }
    
    /* Colori per i valori delle metriche */
    .metric-card:nth-child(1) .metric-value {
        color: #d63031;
    }
    
    .metric-card:nth-child(2) .metric-value {
        color: #00b894;
    }
    
    .metric-card:nth-child(3) .metric-value {
        color: #0984e3;
    }
    
    .metric-card:nth-child(4) .metric-value {
        color: #6c5ce7;
    }
    
    .metric-label {
        font-size: 1rem;
        color: #718096;
        margin-top: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Quick setup aliquote standard */
    .quick-setup {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }
    
    .quick-rate-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 20px;
        padding: 1.5rem;
        text-align: center;
        border: 2px solid transparent;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .quick-rate-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        border-radius: 20px 20px 0 0;
    }
    
    .quick-rate-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        border-color: rgba(2, 157, 126, 0.3);
    }
    
    .quick-rate-percentage {
        font-size: 2rem;
        font-weight: 900;
        margin: 0;
    }
    
    .quick-rate-name {
        font-size: 0.9rem;
        color: #718096;
        margin-top: 0.5rem;
        font-weight: 600;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .dashboard-title {
            font-size: 1.8rem;
        }
        
        .metrics-grid, .quick-setup {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .premium-table-card .table-responsive {
            border-radius: 20px;
        }
        
        .config-section {
            padding: 1.5rem;
        }
        
        .metric-card {
            padding: 1.5rem;
        }
        
        .metric-value {
            font-size: 2rem;
        }
        
        .premium-table thead th {
            font-size: 0.8rem;
            padding: 0.75rem 0.5rem;
        }
        
        .premium-table tbody td {
            padding: 0.75rem 0.5rem;
            font-size: 0.9rem;
        }
    }
    
    /* Animazioni premium */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-slide-up {
        animation: slideInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    
    /* Particelle di sfondo premium */
    .premium-bg::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: 
            radial-gradient(circle at 20% 50%, rgba(214, 48, 49, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(225, 112, 85, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 40% 80%, rgba(214, 48, 49, 0.05) 0%, transparent 50%);
        pointer-events: none;
        z-index: -1;
    }
</style>

<div class="tax-rates-page">
<div class="dashboard-container premium-bg">
    <!-- Alert Messages Premium -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show animate-slide-up" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            <strong>Perfetto!</strong> {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show animate-slide-up" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Attenzione!</strong> Controlla i seguenti errori:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <!-- Header Premium Enterprise -->
    <div class="dashboard-header animate-slide-up">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="dashboard-title">
                <i class="bi bi-percent" style="color: #029D7E;"></i>
                Configuratore Aliquote IVA
            </h1>
            <a href="{{ route('configurations.system-tables.index') }}" class="back-button">
                <i class="bi bi-arrow-left"></i> Indietro
            </a>
        </div>
        
        <p class="mt-3 mb-0" style="color: #718096; font-size: 1.1rem; font-weight: 500;">
            <i class="bi bi-shield-check text-success me-2"></i>
            Sistema gestionale per aliquote IVA - Superiore ad Aruba e concorrenti
        </p>
        
        <!-- Statistiche Integrate Premium -->
        <div class="metrics-grid">
            <div class="metric-card">
                <h3 class="metric-value">{{ $stats['total_rates'] }}</h3>
                <p class="metric-label"><i class="bi bi-list-ul"></i> Totali</p>
            </div>
            <div class="metric-card">
                <h3 class="metric-value">{{ $stats['active_rates'] }}</h3>
                <p class="metric-label"><i class="bi bi-check-circle"></i> Attive</p>
            </div>
            <div class="metric-card">
                <h3 class="metric-value">{{ $stats['standard_rates'] }}</h3>
                <p class="metric-label"><i class="bi bi-award"></i> Standard</p>
            </div>
            <div class="metric-card">
                <h3 class="metric-value">{{ $stats['custom_rates'] }}</h3>
                <p class="metric-label"><i class="bi bi-gear"></i> Personalizzate</p>
            </div>
        </div>
    </div>

    <!-- Form Nuova Aliquota Premium -->
    <div class="config-section animate-slide-up">
        <div class="section-title">
            <i class="bi bi-plus-circle" style="color: #d63031;"></i>
            Crea Nuova Aliquota IVA
        </div>
        
        <form method="POST" action="{{ route('configurations.system-tables.store', 'tax_rates') }}" class="row g-4">
            @csrf
            
            <div class="col-md-6">
                <label for="code" class="form-label">
                    <i class="bi bi-tag me-2"></i>Codice Aliquota *
                </label>
                <input type="text" class="form-control" id="code" name="code" 
                       placeholder="es. IVA22, IVA10" value="{{ old('code') }}" required
                       pattern="[A-Z0-9_-]+" title="Solo lettere maiuscole, numeri, underscore e trattini">
                @error('code')
                    <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="name" class="form-label">
                    <i class="bi bi-type me-2"></i>Nome Aliquota *
                </label>
                <input type="text" class="form-control" id="name" name="name" 
                       placeholder="es. Aliquota Ordinaria" value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-12">
                <label for="description" class="form-label">
                    <i class="bi bi-file-text me-2"></i>Descrizione *
                </label>
                <input type="text" class="form-control" id="description" name="description" 
                       placeholder="es. Aliquota IVA ordinaria del 22%" value="{{ old('description') }}" required>
                @error('description')
                    <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="percentuale" class="form-label">
                    <i class="bi bi-percent me-2"></i>Percentuale IVA *
                </label>
                <div class="percentage-input">
                    <input type="number" class="form-control" id="percentuale" name="percentuale" 
                           min="0" max="100" step="0.01" placeholder="22.00" value="{{ old('percentuale') }}" required>
                </div>
                @error('percentuale')
                    <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-md-6">
                <label for="sort_order" class="form-label">
                    <i class="bi bi-sort-numeric-up me-2"></i>Ordine Visualizzazione
                </label>
                <input type="number" class="form-control" id="sort_order" name="sort_order" 
                       min="0" placeholder="1" value="{{ old('sort_order', 0) }}">
                @error('sort_order')
                    <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-12">
                <label for="riferimento_normativo" class="form-label">
                    <i class="bi bi-book me-2"></i>Riferimento Normativo
                </label>
                <textarea class="form-control" id="riferimento_normativo" name="riferimento_normativo" 
                          rows="3" placeholder="es. Art. 16 DPR 633/72">{{ old('riferimento_normativo') }}</textarea>
                @error('riferimento_normativo')
                    <div class="text-danger small mt-2"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>
                @enderror
            </div>
            
            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="active" name="active" value="1" 
                           {{ old('active', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="active" style="font-weight: 600;">
                        <i class="bi bi-check-circle me-2"></i>Aliquota attiva e disponibile per l'uso
                    </label>
                </div>
            </div>
            
            <div class="col-12">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle me-2"></i>Crea Aliquota
                </button>
                <button type="reset" class="btn btn-outline-secondary ms-3">
                    <i class="bi bi-arrow-clockwise me-2"></i>Reset Form
                </button>
            </div>
        </form>
        
        <!-- Quick Setup Aliquote Standard -->
        <div class="mt-4">
            <h6 class="text-muted mb-3">
                <i class="bi bi-lightning me-2"></i>Setup Rapido Aliquote Standard Italiane:
            </h6>
            <div class="quick-setup">
                @foreach($standardRates as $rate)
                <div class="quick-rate-card" style="--rate-color: {{ $rate['color'] }}" 
                     onclick="fillStandardRate('{{ $rate['code'] }}', '{{ $rate['name'] }}', {{ $rate['percentuale'] }}, '{{ $rate['color'] }}')">
                    <div class="quick-rate-percentage" style="color: {{ $rate['color'] }}">{{ $rate['percentuale'] }}%</div>
                    <div class="quick-rate-name">{{ $rate['name'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Tabella Aliquote Configurate Premium -->
    <div class="config-section animate-slide-up">
        <div class="section-title">
            <i class="bi bi-table" style="color: #029D7E;"></i>
            Aliquote IVA Configurate ({{ $taxRates->count() }} totali)
        </div>
        
        @if($taxRates->count() > 0)
            <div class="premium-table-card">
                <div class="table-responsive">
                    <table class="table premium-table" id="taxRatesTable">
                        <thead>
                            <tr>
                                <th><i class="bi bi-tag"></i> Codice</th>
                                <th><i class="bi bi-type"></i> Nome</th>
                                <th><i class="bi bi-percent"></i> Aliquota</th>
                                <th><i class="bi bi-file-text"></i> Descrizione</th>
                                <th><i class="bi bi-book"></i> Normativa</th>
                                <th><i class="bi bi-check-circle"></i> Stato</th>
                                <th><i class="bi bi-calendar"></i> Creata</th>
                                <th><i class="bi bi-gear"></i> Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($taxRates as $rate)
                            <tr class="tax-rate-row animate-slide-up" style="animation-delay: {{ $loop->index * 0.1 }}s">
                                <td>
                                    <strong style="color: #2d3748; font-weight: 800;">{{ $rate->code }}</strong>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: #2d3748;">{{ $rate->name }}</div>
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
                                    <div style="color: #718096; font-style: italic;">{{ $rate->description }}</div>
                                </td>
                                <td>
                                    @if($rate->riferimento_normativo)
                                        <small class="text-muted">{{ Str::limit($rate->riferimento_normativo, 30) }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
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
                                <td class="text-muted" style="font-size: 0.9rem;">
                                    {{ $rate->created_at ? $rate->created_at->format('d/m/Y') : '-' }}
                                </td>
                                <td>
                                    <button type="button" class="action-btn edit" title="Modifica aliquota" 
                                            onclick="editRate({{ $rate->id }}, '{{ $rate->code }}', '{{ $rate->name }}', '{{ $rate->description }}', {{ $rate->percentuale }}, '{{ $rate->riferimento_normativo }}', {{ $rate->active ? 'true' : 'false' }}, {{ $rate->sort_order }})">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form method="POST" action="{{ route('configurations.system-tables.destroy', ['table' => 'tax_rates', 'id' => $rate->id]) }}" 
                                          style="display: inline-block;" onsubmit="return confirmDelete('{{ $rate->name }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete" title="Elimina aliquota">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="premium-table-card">
                <div class="text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #d63031; opacity: 0.3;"></i>
                    <h5 class="text-muted mt-4">Nessuna aliquota configurata</h5>
                    <p class="text-muted">Crea la prima aliquota IVA utilizzando il form sopra o il setup rapido.</p>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal Modifica Aliquota Premium -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 25px; border: none; box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);">
            <div class="modal-header" style="background: linear-gradient(135deg, #d63031, #e17055); color: white; border-radius: 25px 25px 0 0; padding: 2rem;">
                <h5 class="modal-title" id="editModalLabel" style="font-weight: 800; font-size: 1.3rem;">
                    <i class="bi bi-pencil me-2"></i>Modifica Aliquota IVA
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <form id="editForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_code" class="form-label">Codice Aliquota</label>
                            <input type="text" class="form-control" id="edit_code" name="code" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="edit_name" class="form-label">Nome Aliquota</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        
                        <div class="col-12">
                            <label for="edit_description" class="form-label">Descrizione</label>
                            <input type="text" class="form-control" id="edit_description" name="description" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="edit_percentuale" class="form-label">Percentuale IVA</label>
                            <div class="percentage-input">
                                <input type="number" class="form-control" id="edit_percentuale" name="percentuale" 
                                       min="0" max="100" step="0.01" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="edit_sort_order" class="form-label">Ordine Visualizzazione</label>
                            <input type="number" class="form-control" id="edit_sort_order" name="sort_order" min="0">
                        </div>
                        
                        <div class="col-12">
                            <label for="edit_riferimento_normativo" class="form-label">Riferimento Normativo</label>
                            <textarea class="form-control" id="edit_riferimento_normativo" name="riferimento_normativo" rows="3"></textarea>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_active" name="active" value="1">
                                <label class="form-check-label" for="edit_active">
                                    Aliquota attiva
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="padding: 2rem; border: none;">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x me-2"></i>Annulla
                </button>
                <button type="submit" form="editForm" class="btn btn-success">
                    <i class="bi bi-check-circle me-2"></i>Salva Modifiche
                </button>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts premium
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert, index) {
        setTimeout(function() {
            if (bootstrap.Alert.getOrCreateInstance) {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }
        }, 5000 + (index * 1000));
    });
    
    // Validazione form premium con feedback visivo
    const form = document.querySelector('form[method="POST"]');
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validazione codice
            const code = document.getElementById('code');
            if (code && code.value.trim().length < 2) {
                showFieldError(code, 'Il codice deve essere di almeno 2 caratteri');
                isValid = false;
            }
            
            // Validazione percentuale
            const percentuale = document.getElementById('percentuale');
            if (percentuale) {
                const value = parseFloat(percentuale.value);
                if (isNaN(value) || value < 0 || value > 100) {
                    showFieldError(percentuale, 'La percentuale deve essere tra 0 e 100');
                    isValid = false;
                }
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Animazioni premium per le righe
    const rows = document.querySelectorAll('.tax-rate-row');
    rows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(30px)';
        
        setTimeout(() => {
            row.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Preview percentuale in tempo reale
    const percentualeInput = document.getElementById('percentuale');
    if (percentualeInput) {
        percentualeInput.addEventListener('input', function() {
            const value = parseFloat(this.value);
            if (!isNaN(value)) {
                updatePercentagePreview(value);
            }
        });
    }
});

// Funzioni utility premium
function fillStandardRate(code, name, percentage, color) {
    document.getElementById('code').value = code;
    document.getElementById('name').value = name;
    document.getElementById('percentuale').value = percentage.toFixed(2);
    
    // Genera descrizione automatica
    document.getElementById('description').value = `${name} del ${percentage}%`;
    
    // Aggiungi riferimento normativo automatico
    let normativa = '';
    switch(percentage) {
        case 22:
            normativa = 'Art. 16 DPR 633/72';
            break;
        case 10:
            normativa = 'Art. 16 DPR 633/72 - Tab. A parte II';
            break;
        case 4:
            normativa = 'Art. 16 DPR 633/72 - Tab. A parte III';
            break;
        case 0:
            normativa = 'Art. 8-bis DPR 633/72';
            break;
    }
    document.getElementById('riferimento_normativo').value = normativa;
    
    // Focus al primo campo per UX
    document.getElementById('code').focus();
    
    // Animazione di conferma
    const card = event.target.closest('.quick-rate-card');
    card.style.transform = 'scale(0.95)';
    setTimeout(() => {
        card.style.transform = 'scale(1)';
    }, 150);
}

function editRate(id, code, name, description, percentage, normativa, active, sortOrder) {
    // Popola modal
    document.getElementById('edit_code').value = code;
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_percentuale').value = percentage;
    document.getElementById('edit_riferimento_normativo').value = normativa || '';
    document.getElementById('edit_active').checked = active;
    document.getElementById('edit_sort_order').value = sortOrder || 0;
    
    // Imposta action del form
    const editForm = document.getElementById('editForm');
    editForm.action = `/configurations/system-tables/tax_rates/${id}`;
    
    // Mostra modal
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
    editModal.show();
}

function confirmDelete(rateName) {
    return confirm(`⚠️ ATTENZIONE!\n\nStai per eliminare l'aliquota IVA "${rateName}".\n\nQuesta azione è IRREVERSIBILE e potrebbe compromettere i documenti fiscali esistenti.\n\nSei sicuro di voler procedere?`);
}

function showFieldError(field, message) {
    field.style.borderColor = '#f72585';
    field.style.boxShadow = '0 0 0 3px rgba(247, 37, 133, 0.2)';
    
    // Rimuovi errore dopo 3 secondi
    setTimeout(() => {
        field.style.borderColor = '';
        field.style.boxShadow = '';
    }, 3000);
    
    // Mostra tooltip con messaggio
    field.title = message;
}

function updatePercentagePreview(value) {
    // Aggiorna preview in tempo reale (se necessario)
    console.log(`Preview: ${value}%`);
}
</script>