@extends('layouts.app')

@section('content')
<style>
    body {
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
        min-width: 32px;
        text-align: center;
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
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: white;
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
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    
    .status-badge.default {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
    }
    
    .status-badge.normal {
        background: rgba(2, 157, 126, 0.1);
        color: #029D7E;
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
        margin-top: 1.5rem;
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
    
    /* Colori specifici per ogni card */
    .metric-card:nth-child(1)::before {
        background: linear-gradient(135deg, #029D7E, #4DC9A5); /* Viola gestionale */
    }
    
    .metric-card:nth-child(2)::before {
        background: linear-gradient(135deg, #029D7E, #4DC9A5); /* Verde gestionale */
    }
    
    .metric-card:nth-child(3)::before {
        background: linear-gradient(135deg, #48cae4, #0077b6); /* Azzurro gestionale */
    }
    
    .metric-card:nth-child(4)::before {
        background: linear-gradient(135deg, #ffd60a, #ff8500); /* Giallo gestionale */
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
    
    /* Colori numeri abbinati ai border */
    .metric-card:nth-child(1) .metric-value {
        color: #029D7E; /* Viola gestionale */
    }
    
    .metric-card:nth-child(2) .metric-value {
        color: #029D7E; /* Verde gestionale */
    }
    
    .metric-card:nth-child(3) .metric-value {
        color: #48cae4; /* Azzurro gestionale */
    }
    
    .metric-card:nth-child(4) .metric-value {
        color: #ffd60a; /* Giallo gestionale */
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
</style>

<div class="dashboard-container">
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
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="dashboard-title">
                <i class="bi bi-link-45deg text-success"></i>
                Configuratore Nature IVA
            </h1>
            <a href="{{ route('configurations.system-tables.index') }}" class="back-button">
                <i class="bi bi-arrow-left"></i> Indietro
            </a>
        </div>
        
        <p class="mt-3 mb-0 text-muted">
            Configuratore avanzato per associazioni tra aliquote IVA e nature fiscali
        </p>
    </div>

    <!-- Form Nuova Associazione -->
    <div class="config-section">
        <div class="section-title">
            <i class="bi bi-plus-circle text-success"></i>
            Crea Nuova Associazione
        </div>
        
        <form method="POST" action="{{ route('configurations.system-tables.store', 'vat_nature_associations') }}" class="row g-3">
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

    <!-- Header Associazioni Configurate -->
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="dashboard-title" style="font-size: 1.8rem;">
                <i class="bi bi-list-ul text-success"></i>
                Associazioni Configurate
            </h2>
        </div>
        <p class="mt-3 mb-0 text-muted">
            Elenco delle associazioni tra aliquote IVA e nature fiscali configurate ({{ $associations->count() }} totali)
        </p>
    </div>

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
                                    <form method="POST" action="{{ route('configurations.system-tables.destroy', ['table' => 'vat_nature_associations', 'id' => $association->id]) }}" 
                                          style="display: inline-block;" onsubmit="return confirmDelete('{{ $association->nome_associazione ?? 'questa associazione' }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete" title="Elimina associazione">
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
                    
                    <div class="d-flex justify-content-center mt-3">
                        <form method="POST" action="{{ route('configurations.system-tables.destroy', ['table' => 'vat_nature_associations', 'id' => $association->id]) }}" 
                              style="width: 100%;" onsubmit="return confirmDelete('{{ $association->nome_associazione ?? 'questa associazione' }}')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="mobile-action-btn delete" style="width: 100%;" title="Elimina associazione">
                                <i class="bi bi-trash"></i>
                                <span>Elimina</span>
                            </button>
                        </form>
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

    <!-- Header Statistiche Sistema -->
    <div class="dashboard-header" style="margin-top: 2rem;">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="dashboard-title" style="font-size: 1.8rem;">
                <i class="bi bi-bar-chart text-success"></i>
                Statistiche Sistema
            </h2>
        </div>
        <p class="mt-3 mb-0 text-muted">
            Panoramica dei dati configurati nel sistema Nature IVA
        </p>
        
        <!-- Cards Statistiche Integrate nel Banner -->
        <div class="metrics-grid mt-4">
            <div class="metric-card">
                <h3 class="metric-value">{{ $taxRates->count() }}</h3>
                <p class="metric-label">Aliquote IVA</p>
            </div>
            <div class="metric-card">
                <h3 class="metric-value">{{ $vatNatures->count() }}</h3>
                <p class="metric-label">Nature IVA</p>
            </div>
            <div class="metric-card">
                <h3 class="metric-value">{{ $associations->count() }}</h3>
                <p class="metric-label">Associazioni</p>
            </div>
            <div class="metric-card">
                <h3 class="metric-value">{{ $associations->where('is_default', true)->count() }}</h3>
                <p class="metric-label">Predefinite</p>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Script per migliorare l'esperienza utente -->
<script>
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
</script>