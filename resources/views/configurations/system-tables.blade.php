@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .config-container {
        padding: 2rem;
    }
    
    .config-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .config-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #48cae4, #0077b6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .config-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
    
    .table-responsive {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }
    
    .table {
        margin-bottom: 0;
    }
    
    .table th {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
        border: none;
        font-weight: 600;
        padding: 15px;
    }
    
    .table td {
        padding: 12px 15px;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
    }
    
    .form-control, .form-select {
        border-radius: 10px;
        padding: 10px 15px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.8);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #48cae4;
        box-shadow: 0 0 0 0.2rem rgba(72, 202, 228, 0.25);
        background: white;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        border: none;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(72, 202, 228, 0.3);
    }
    
    .btn-success {
        background: linear-gradient(135deg, #4ecdc4, #44a08d);
        border: none;
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        border: none;
    }
    
    .alert {
        border-radius: 15px;
        border: none;
        padding: 20px;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #4ecdc4, #44a08d);
        color: white;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Dark Mode */
    [data-bs-theme="dark"] .config-header {
        background: rgba(45, 55, 72, 0.95) !important;
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .config-card {
        background: rgba(45, 55, 72, 0.95) !important;
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .section-title {
        color: #e2e8f0 !important;
        border-bottom-color: rgba(255, 255, 255, 0.1) !important;
    }
    
    [data-bs-theme="dark"] .table td {
        background: rgba(45, 55, 72, 0.8) !important;
        color: #e2e8f0 !important;
        border-bottom-color: rgba(255, 255, 255, 0.1) !important;
    }
    
    [data-bs-theme="dark"] .form-control,
    [data-bs-theme="dark"] .form-select {
        background: rgba(45, 55, 72, 0.8) !important;
        border-color: rgba(72, 202, 228, 0.3) !important;
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .form-control:focus,
    [data-bs-theme="dark"] .form-select:focus {
        background: rgba(45, 55, 72, 0.9) !important;
        border-color: #48cae4 !important;
        color: #e2e8f0 !important;
    }
</style>

<div class="container-fluid config-container">
    <!-- Header -->
    <div class="config-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="config-title">
                    <i class="bi bi-table"></i> {{ __('app.system_tables') }}
                </h1>
            </div>
            <a href="{{ route('configurations.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> {{ __('app.back') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Aliquote IVA -->
    <div class="config-card">
        <h3 class="section-title">
            <i class="bi bi-percent"></i> Aliquote IVA
        </h3>
        
        <!-- Form Nuova Aliquota -->
        <div class="row mb-4">
            <div class="col-12">
                <form action="{{ route('configurations.tax-rates.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="code" placeholder="Codice (es. 22)" required>
                    </div>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="description" placeholder="Descrizione (es. Iva Ordinaria)" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="rate" placeholder="22.00" step="0.01" min="0" max="100" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-plus"></i> Aggiungi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabella Aliquote -->
        @if($taxRates->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Codice</th>
                            <th>Descrizione</th>
                            <th>Aliquota %</th>
                            <th>Stato</th>
                            <th width="120">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($taxRates as $rate)
                            <tr>
                                <td><strong>{{ $rate->code }}</strong></td>
                                <td>{{ $rate->description }}</td>
                                <td>{{ $rate->rate }}%</td>
                                <td>
                                    <span class="badge bg-success">Attivo</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="editTaxRate({{ $rate->id }}, '{{ $rate->code }}', '{{ $rate->description }}', {{ $rate->rate }})">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="deleteTaxRate({{ $rate->id }}, '{{ $rate->code }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-percent"></i>
                <p>Nessuna aliquota IVA configurata</p>
            </div>
        @endif
    </div>

    <!-- Metodi di Pagamento -->
    <div class="config-card">
        <h3 class="section-title">
            <i class="bi bi-credit-card"></i> Metodi di Pagamento
        </h3>
        
        <!-- Form Nuovo Metodo -->
        <div class="row mb-4">
            <div class="col-12">
                <form action="{{ route('configurations.payment-methods.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="code" placeholder="Codice (es. CONT)" required>
                    </div>
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="description" placeholder="Descrizione (es. Contanti)" required>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="type">
                            <option value="">Tipo</option>
                            <option value="contanti">Contanti</option>
                            <option value="carta">Carta</option>
                            <option value="bonifico">Bonifico</option>
                            <option value="assegno">Assegno</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-plus"></i> Aggiungi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabella Metodi Pagamento -->
        @if($paymentMethods->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Codice</th>
                            <th>Descrizione</th>
                            <th>Tipo</th>
                            <th>Stato</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paymentMethods as $method)
                            <tr>
                                <td><strong>{{ $method->code }}</strong></td>
                                <td>{{ $method->description }}</td>
                                <td>
                                    @if($method->type)
                                        <span class="badge bg-info">{{ ucfirst($method->type) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-success">Attivo</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-credit-card"></i>
                <p>Nessun metodo di pagamento configurato</p>
            </div>
        @endif
    </div>

    <!-- Valute -->
    <div class="config-card">
        <h3 class="section-title">
            <i class="bi bi-currency-exchange"></i> Valute
        </h3>
        
        <!-- Form Nuova Valuta -->
        <div class="row mb-4">
            <div class="col-12">
                <form action="{{ route('configurations.currencies.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="code" placeholder="EUR" maxlength="3" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="name" placeholder="Euro" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="symbol" placeholder="€" maxlength="5" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="exchange_rate" placeholder="1.000000" step="0.000001" min="0" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="bi bi-plus"></i> Aggiungi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabella Valute -->
        @if($currencies->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Codice</th>
                            <th>Nome</th>
                            <th>Simbolo</th>
                            <th>Tasso di Cambio</th>
                            <th>Stato</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($currencies as $currency)
                            <tr>
                                <td><strong>{{ $currency->code }}</strong></td>
                                <td>{{ $currency->name }}</td>
                                <td><span class="badge bg-primary">{{ $currency->symbol }}</span></td>
                                <td>{{ number_format($currency->exchange_rate, 6) }}</td>
                                <td>
                                    <span class="badge bg-success">Attivo</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-currency-exchange"></i>
                <p>Nessuna valuta configurata</p>
            </div>
        @endif
    </div>
</div>
@endsection