@extends('layouts.app')

@section('title', __('app.sales') . ' - Gestionale Negozio')

@section('content')
<style>
1    .sales-container {
        padding: 2rem;
        min-height: calc(100vh - 76px);
    }
    
    .page-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.8rem;
        margin-bottom: 1rem;
    }
    
    .stat-value {
        font-size: 2.2rem;
        font-weight: 800;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #718096;
        font-size: 0.9rem;
        text-transform: uppercase;
        font-weight: 600;
    }
    
    .search-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .search-input {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid rgba(102, 126, 234, 0.2);
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    .filter-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .filter-chip {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .filter-chip:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    
    .filter-chip.active {
        background: linear-gradient(135deg, #f72585, #c5025a);
    }
    
    
    .modern-btn {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        border-radius: 15px;
        padding: 12px 24px;
        font-weight: 600;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .modern-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .modern-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .modern-table {
        width: 100%;
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
    }
    
    .modern-table tbody td {
        padding: 1rem;
        border: none;
        border-bottom: 1px solid rgba(102, 126, 234, 0.1);
        vertical-align: middle;
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
    
    .action-btn.view {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
    }
    
    .action-btn.delete {
        background: linear-gradient(135deg, #f72585, #c5025a);
        color: white;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        color: white;
    }
    
    .payment-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .payment-cash {
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
    }
    
    .payment-card {
        background: linear-gradient(135deg, #4299e1, #3182ce);
        color: white;
    }
    
    .payment-transfer {
        background: linear-gradient(135deg, #ed8936, #dd6b20);
        color: white;
    }
    
    .total-price {
        color: #48bb78;
        font-size: 1.2rem;
        font-weight: 700;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    /* Mobile Cards */
    .mobile-cards {
        display: none;
    }
    
    .sale-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .sale-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }
    
    .sale-card-date {
        font-size: 1.1rem;
        font-weight: 700;
        color: #4a5568;
    }
    
    .sale-card-total {
        font-size: 1.4rem;
        font-weight: 800;
        color: #48bb78;
    }
    
    .sale-card-customer {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #2d3748;
    }
    
    .sale-card-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .sale-detail {
        display: flex;
        flex-direction: column;
    }
    
    .sale-detail-label {
        font-size: 0.8rem;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 0.3rem;
    }
    
    .sale-detail-value {
        font-weight: 600;
    }
    
    .sale-card-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
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
    
    .mobile-action-btn.view {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
    }
    
    .mobile-action-btn.delete {
        background: linear-gradient(135deg, #f72585, #c5025a);
        color: white;
    }
    
    .mobile-action-btn:hover {
        color: white;
    }
    
    /* Dark Mode */
    [data-bs-theme="dark"] .page-header,
    [data-bs-theme="dark"] .search-container,
    [data-bs-theme="dark"] .stat-card,
    [data-bs-theme="dark"] .modern-card,
    [data-bs-theme="dark"] .sale-card {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .stat-value {
        color: #e2e8f0;
    }
    
    /* MacBook Air e laptop small (1366px e inferiori) */
    @media (max-width: 1400px) {
        .modern-table thead th {
            padding: 0.8rem 0.4rem;
            font-size: 0.75rem;
        }
        
        .modern-table tbody td {
            padding: 0.8rem 0.4rem;
            font-size: 0.85rem;
        }
        
        .action-btn {
            padding: 4px 8px;
            font-size: 0.75rem;
            margin: 0 1px;
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sales-container {
            padding: 1rem;
        }
        
        .modern-card .table-responsive {
            display: none;
        }
        
        .mobile-cards {
            display: block;
        }
        
        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
    }
    @media (max-width: 576px) {
        .page-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .page-title {
            font-size: 1.8rem;
        }
        
        .modern-btn {
            padding: 10px 20px;
            font-size: 0.9rem;
        }
    }
</style>

<div class="sales-container">
    <!-- Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">
                <i class="bi bi-cart-check"></i> {{ __('app.sales') }}
            </h1>
            <a href="{{ route('vendite.create') }}" class="modern-btn">
                <i class="bi bi-plus-circle"></i> {{ __('app.new_sale') }}
            </a>
        </div>
    </div>
    
    @if(count($vendite) > 0)
    <!-- Dashboard -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-graph-up"></i>
            </div>
            <div class="stat-value">{{ count($vendite) }}</div>
            <div class="stat-label">{{ __('app.total_sales') }}</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-currency-euro"></i>
            </div>
            <div class="stat-value">€ {{ number_format($vendite->sum('totale_finale'), 2) }}</div>
            <div class="stat-label">{{ __('app.total_revenue') }}</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-bar-chart"></i>
            </div>
            <div class="stat-value">€ {{ number_format($vendite->avg('totale_finale'), 2) }}</div>
            <div class="stat-label">{{ __('app.average_sale') }}</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-calendar-day"></i>
            </div>
            <div class="stat-value">{{ $vendite->where('data_vendita', today())->count() }}</div>
            <div class="stat-label">{{ __('app.todays_sales') }}</div>
        </div>
    </div>
    @endif
    
    <!-- Ricerca -->
    <div class="search-container">
        <input type="text" class="search-input" placeholder="🔍 {{ __('app.search_sales_placeholder') }}">
        
        <!-- Filtri -->
        <div class="filter-chips">
            <button class="filter-chip active" data-filter="all">{{ __('app.all') }}</button>
            <button class="filter-chip" data-filter="today">{{ __('app.today') }}</button>
            <button class="filter-chip" data-filter="week">{{ __('app.this_week') }}</button>
            <button class="filter-chip" data-filter="cash">{{ __('app.cash') }}</button>
            <button class="filter-chip" data-filter="card">{{ __('app.card') }}</button>
            <button class="filter-chip" data-filter="customer">{{ __('app.with_customer') }}</button>
        </div>
    </div>
    
    <!-- Tabella/Cards -->
    <div class="modern-card">
        <!-- Desktop -->
        <div class="table-responsive">
            <table class="table modern-table">
                <thead>
                    <tr>
                        <th><i class="bi bi-calendar"></i> {{ __('app.date') }}</th>
                        <th><i class="bi bi-person"></i> {{ __('app.customer') }}</th>
                        <th><i class="bi bi-currency-euro"></i> {{ __('app.total') }}</th>
                        <th><i class="bi bi-percent"></i> {{ __('app.discount') }}</th>
                        <th><i class="bi bi-cash-coin"></i> {{ __('app.final_total') }}</th>
                        <th><i class="bi bi-credit-card"></i> {{ __('app.payment') }}</th>
                        <th><i class="bi bi-gear"></i> {{ __('app.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendite as $vendita)
                    <tr>
                        <td>{{ $vendita->data_vendita->format('d/m/Y') }}</td>
                        <td>{{ $vendita->cliente ? $vendita->cliente->nome_completo : __('app.occasional_customer') }}</td>
                        <td>€ {{ number_format($vendita->totale, 2) }}</td>
                        <td>{{ $vendita->sconto }}%</td>
                        <td><span class="total-price">€ {{ number_format($vendita->totale_finale, 2) }}</span></td>
                        <td>
                            <span class="payment-badge payment-{{ $vendita->metodo_pagamento }}">
                                @if($vendita->metodo_pagamento == 'contanti')
                                <i class="bi bi-cash"></i> {{ __('app.cash') }}
                                @elseif($vendita->metodo_pagamento == 'carta')
                                <i class="bi bi-credit-card"></i> {{ __('app.card') }}
                                @else
                                <i class="bi bi-bank"></i> {{ __('app.bank_transfer') }}
                                @endif
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('vendite.show', $vendita) }}" class="action-btn view" title="{{ __('app.view') }}">
                                <i class="bi bi-eye"></i>
                            </a>
                            <form action="{{ route('vendite.destroy', $vendita) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="{{ __('app.delete') }}" onclick="return confirm('{{ __('app.confirm_delete_sale') }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="bi bi-cart-x"></i>
                                <h5>{{ __('app.no_sales_found') }}</h5>
                                <p>Inizia registrando la prima vendita utilizzando il pulsante in alto</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Mobile -->
        <div class="mobile-cards">
            @forelse($vendite as $vendita)
            <div class="sale-card">
                <div class="sale-card-header">
                    <div class="sale-card-date">{{ $vendita->data_vendita->format('d/m/Y') }}</div>
                    <div class="sale-card-total">€ {{ number_format($vendita->totale_finale, 2) }}</div>
                </div>
                
                <div class="sale-card-customer">
                    {{ $vendita->cliente ? $vendita->cliente->nome_completo : __('app.occasional_customer') }}
                </div>
                
                <div class="sale-card-details">
                    <div class="sale-detail">
                        <span class="sale-detail-label">{{ __('app.total') }}</span>
                        <span class="sale-detail-value">€ {{ number_format($vendita->totale, 2) }}</span>
                    </div>
                    <div class="sale-detail">
                        <span class="sale-detail-label">{{ __('app.payment') }}</span>
                        <span class="sale-detail-value">
                            <span class="payment-badge payment-{{ $vendita->metodo_pagamento }}">
                                @if($vendita->metodo_pagamento == 'contanti')
                                <i class="bi bi-cash"></i> {{ __('app.cash') }}
                                @elseif($vendita->metodo_pagamento == 'carta')
                                <i class="bi bi-credit-card"></i> {{ __('app.card') }}
                                @else
                                <i class="bi bi-bank"></i> {{ __('app.bank_transfer') }}
                                @endif
                            </span>
                        </span>
                    </div>
                </div>
                
                <div class="sale-card-actions">
                    <a href="{{ route('vendite.show', $vendita) }}" class="mobile-action-btn view">
                        <i class="bi bi-eye"></i>
                        <span>{{ __('app.view') }}</span>
                    </a>
                    <form action="{{ route('vendite.destroy', $vendita) }}" method="POST" style="flex: 1;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="mobile-action-btn delete" style="width: 100%; border: none;" onclick="return confirm('{{ __('app.confirm_delete_sale') }}')">
                            <i class="bi bi-trash"></i>
                            <span>{{ __('app.delete') }}</span>
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="sale-card">
                <div class="empty-state">
                    <i class="bi bi-cart-x"></i>
                    <h5>{{ __('app.no_sales_found') }}</h5>
                    <p>Inizia registrando la prima vendita utilizzando il pulsante in alto</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection