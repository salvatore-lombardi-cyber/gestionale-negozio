@extends('layouts.app')

@section('title', __('app.sale') . ' #' . $vendita->id . ' - Gestionale Negozio')

@section('content')
<style>
    .sale-detail-container {
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
        position: relative;
        overflow: hidden;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
    }
    
    .sale-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .sale-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .sale-number {
        font-family: 'Courier New', monospace;
        background: rgba(2, 157, 126, 0.1);
        padding: 8px 16px;
        border-radius: 10px;
        color: #029D7E;
        font-weight: 700;
        margin-left: 0.5rem;
    }
    
    .modern-btn {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        border: none;
        border-radius: 15px;
        padding: 12px 24px;
        font-weight: 600;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .modern-btn.secondary {
        background: linear-gradient(135deg, #6c757d, #5a6268);
    }
    
    .modern-btn.primary {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
    }
    
    .modern-btn.danger {
        background: linear-gradient(135deg, #f72585, #c5025a);
    }
    
    .modern-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .modern-btn:hover::before {
        left: 0;
    }
    
    .modern-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        color: white;
    }
    
    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }
    
    .main-content {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    
    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    
    .info-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
    }
    
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }
    
    .card-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .card-title i {
        color: #029D7E;
        font-size: 1.5rem;
    }
    
    .sale-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }
    
    .info-section {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(2, 157, 126, 0.1);
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #4a5568;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        min-width: 120px;
    }
    
    .info-label i {
        color: #029D7E;
        width: 16px;
    }
    
    .info-value {
        font-weight: 500;
        color: #2d3748;
        text-align: right;
        flex: 1;
    }
    
    .payment-badge {
        padding: 8px 16px;
        border-radius: 25px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.9rem;
    }
    
    .payment-cash {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .payment-card {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
    }
    
    .payment-transfer {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
    }
    
    .total-display {
        font-size: 2rem;
        font-weight: 800;
        color: #28a745;
        background: rgba(40, 167, 69, 0.1);
        padding: 1rem;
        border-radius: 15px;
        text-align: center;
        margin-top: 1rem;
    }
    
    .discount-info {
        background: rgba(247, 37, 133, 0.1);
        color: #f72585;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        text-align: center;
    }
    
    .products-table {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .modern-table {
        width: 100%;
        margin: 0;
    }
    
    .modern-table thead th {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        font-weight: 600;
        border: none;
        padding: 1rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .modern-table tbody td {
        padding: 1rem;
        border: none;
        border-bottom: 1px solid rgba(2, 157, 126, 0.1);
        vertical-align: middle;
    }
    
    .modern-table tbody tr:hover {
        background: rgba(2, 157, 126, 0.05);
    }
    
    .product-name {
        font-weight: 600;
        color: #2d3748;
    }
    
    .product-variant {
        background: rgba(2, 157, 126, 0.1);
        color: #029D7E;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        margin: 0 2px;
        display: inline-block;
    }
    
    .price-cell {
        font-weight: 700;
        color: #28a745;
        font-size: 1.1rem;
    }
    
    .actions-card {
        position: sticky;
        top: 2rem;
    }
    
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .customer-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 1rem;
        border: 3px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 20px rgba(2, 157, 126, 0.3);
    }
    
    .customer-name {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1rem;
    }
    
    .date-display {
        font-weight: 600;
        color: #029D7E;
        font-size: 1.1rem;
    }
    
    /* Dark Mode */
    [data-bs-theme="dark"] .page-header,
    [data-bs-theme="dark"] .info-card,
    [data-bs-theme="dark"] .products-table {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .sale-title,
    [data-bs-theme="dark"] .card-title {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .info-label,
    [data-bs-theme="dark"] .info-value,
    [data-bs-theme="dark"] .product-name,
    [data-bs-theme="dark"] .customer-name {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .sale-number,
    [data-bs-theme="dark"] .product-variant {
        background: rgba(2, 157, 126, 0.2);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .modern-table tbody td {
        color: #e2e8f0;
        border-bottom-color: rgba(2, 157, 126, 0.2);
    }
    
    [data-bs-theme="dark"] .info-item {
        border-bottom-color: rgba(2, 157, 126, 0.2);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .sale-detail-container {
            padding: 1rem;
        }
        
        .page-header {
            padding: 1.5rem;
        }
        
        .sale-header {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .sale-title {
            font-size: 2rem;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .content-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .sale-info-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .info-card {
            padding: 1.5rem;
        }
        
        .info-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .info-value {
            text-align: left;
        }
        
        .actions-card {
            position: static;
        }
        
        .modern-table {
            font-size: 0.9rem;
        }
        
        .modern-table thead th,
        .modern-table tbody td {
            padding: 0.8rem 0.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .sale-title {
            font-size: 1.8rem;
        }
        
        .card-title {
            font-size: 1.2rem;
        }
        
        .modern-table {
            font-size: 0.8rem;
        }
        
        .product-variant {
            display: block;
            margin: 2px 0;
        }
    }
    /* Mobile Cards per Prodotti */
.mobile-products {
    display: none;
}

.product-mobile-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border: 1px solid rgba(2, 157, 126, 0.1);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.product-mobile-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.product-mobile-name {
    font-weight: 700;
    color: #2d3748;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.product-mobile-price {
    font-weight: 700;
    color: #28a745;
    font-size: 1.2rem;
}

.product-mobile-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.product-mobile-detail {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
}

.product-mobile-label {
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
    font-weight: 600;
}

.product-mobile-value {
    font-weight: 600;
    color: #2d3748;
}

.product-mobile-variants {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.product-mobile-variant {
    background: rgba(2, 157, 126, 0.1);
    color: #029D7E;
    padding: 4px 8px;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
}

.product-mobile-total {
    text-align: center;
    padding: 1rem;
    background: rgba(40, 167, 69, 0.1);
    border-radius: 10px;
    color: #28a745;
    font-weight: 700;
    font-size: 1.1rem;
}

/* Dark Mode per Mobile Cards */
[data-bs-theme="dark"] .product-mobile-card {
    background: rgba(45, 55, 72, 0.95);
    border-color: rgba(2, 157, 126, 0.2);
}

[data-bs-theme="dark"] .product-mobile-name,
[data-bs-theme="dark"] .product-mobile-value {
    color: #e2e8f0;
}

[data-bs-theme="dark"] .product-mobile-variant {
    background: rgba(2, 157, 126, 0.2);
    color: #e2e8f0;
}

/* Responsive Updates */
@media (max-width: 768px) {
    .products-table {
        display: none;
    }
    
    .mobile-products {
        display: block;
    }
    
    .content-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    /* Fix per evitare overflow */
    .sale-detail-container {
        overflow-x: hidden;
    }
    
    .info-card {
        margin-left: 0;
        margin-right: 0;
        max-width: 100%;
    }
    
    /* Miglioramenti generali mobile */
    .sale-number {
        display: block;
        margin-left: 0;
        margin-top: 0.5rem;
        text-align: center;
    }
    
    .action-buttons {
        gap: 0.5rem;
    }
    
    .modern-btn {
        padding: 10px 16px;
        font-size: 0.9rem;
    }
}
</style>

<div class="sale-detail-container">
    <!-- Header -->
    <div class="page-header">
        <div class="sale-header">
            <div>
                <h1 class="sale-title">
                    <i class="bi bi-receipt"></i>
                    {{ __('app.sale') }}
                    <span class="sale-number">#{{ $vendita->id }}</span>
                </h1>
            </div>
            <a href="{{ route('vendite.index') }}" class="modern-btn secondary">
                <i class="bi bi-arrow-left"></i> {{ __('app.back_to_sales') }}
            </a>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Main Content -->
        <div class="main-content">
            <!-- Informazioni Vendita -->
            <div class="info-card">
                <h3 class="card-title">
                    <i class="bi bi-info-circle"></i>
                    {{ __('app.sale_information') }}
                </h3>
                
                <div class="sale-info-grid">
                    <div class="info-section">
                        <div class="info-item">
                            <span class="info-label">
                                <i class="bi bi-calendar"></i>
                                {{ __('app.date') }}
                            </span>
                            <span class="info-value">
                                <span class="date-display">{{ $vendita->data_vendita->format('d/m/Y') }}</span>
                            </span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">
                                <i class="bi bi-person"></i>
                                {{ __('app.customer') }}
                            </span>
                            <span class="info-value">
                                {{ $vendita->cliente ? $vendita->cliente->nome_completo : __('app.occasional_customer') }}
                            </span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">
                                <i class="bi bi-credit-card"></i>
                                {{ __('app.payment_method') }}
                            </span>
                            <span class="info-value">
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
                        
                        @if($vendita->note)
                        <div class="info-item">
                            <span class="info-label">
                                <i class="bi bi-chat-text"></i>
                                {{ __('app.notes') }}
                            </span>
                            <span class="info-value">{{ $vendita->note }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="info-section">
                        <div class="info-item">
                            <span class="info-label">
                                <i class="bi bi-calculator"></i>
                                {{ __('app.subtotal') }}
                            </span>
                            <span class="info-value price-cell">€ {{ number_format($vendita->totale, 2) }}</span>
                        </div>
                        
                        <div class="info-item">
                            <span class="info-label">
                                <i class="bi bi-percent"></i>
                                {{ __('app.discount') }}
                            </span>
                            <span class="info-value">
                                @if($vendita->sconto > 0)
                                    <div class="discount-info">
                                        {{ $vendita->sconto }}% (-€ {{ number_format(($vendita->totale * $vendita->sconto) / 100, 2) }})
                                    </div>
                                @else
                                    <span style="color: #a0aec0;">Nessuno</span>
                                @endif
                            </span>
                        </div>
                        
                        <div class="total-display">
                            <i class="bi bi-cash-coin"></i>
                            € {{ number_format($vendita->totale_finale, 2) }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prodotti Venduti -->
            <div class="info-card">
                <h3 class="card-title">
                    <i class="bi bi-bag-check"></i>
                    {{ __('app.sold_products') }}
                </h3>
                
                <div class="products-table">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>{{ __('app.product') }}</th>
                                <th>{{ __('app.size') }}</th>
                                <th>{{ __('app.color') }}</th>
                                <th>{{ __('app.quantity') }}</th>
                                <th>{{ __('app.unit_price') }}</th>
                                <th>{{ __('app.subtotal') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vendita->dettagli as $dettaglio)
                            <tr>
                                <td>
                                    <span class="product-name">{{ $dettaglio->prodotto->nome }}</span>
                                </td>
                                <td>
                                    <span class="product-variant">{{ $dettaglio->taglia }}</span>
                                </td>
                                <td>
                                    <span class="product-variant">{{ $dettaglio->colore }}</span>
                                </td>
                                <td>
                                    <strong>{{ $dettaglio->quantita }}</strong>
                                </td>
                                <td>
                                    <span class="price-cell">€ {{ number_format($dettaglio->prezzo_unitario, 2) }}</span>
                                </td>
                                <td>
                                    <span class="price-cell">€ {{ number_format($dettaglio->subtotale, 2) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Azioni -->
            <div class="info-card actions-card">
                <h3 class="card-title">
                    <i class="bi bi-gear"></i>
                    {{ __('app.actions') }}
                </h3>
                
                <div class="action-buttons">
                    <button class="modern-btn primary" onclick="window.print()">
                        <i class="bi bi-printer"></i> {{ __('app.print_receipt') }}
                    </button>
                    
                    <form action="{{ route('vendite.destroy', $vendita) }}" method="POST" onsubmit="return confirm('{{ __('app.confirm_delete_sale') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="modern-btn danger" style="width: 100%;">
                            <i class="bi bi-trash"></i> {{ __('app.delete_sale') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Informazioni Cliente -->
            @if($vendita->cliente)
            <div class="info-card">
                <h3 class="card-title">
                    <i class="bi bi-person-vcard"></i>
                    {{ __('app.customer_information') }}
                </h3>
                
                <div class="text-center">
                    <div class="customer-avatar">
                        {{ strtoupper(substr($vendita->cliente->nome, 0, 1)) }}{{ strtoupper(substr($vendita->cliente->cognome, 0, 1)) }}
                    </div>
                    <div class="customer-name">{{ $vendita->cliente->nome_completo }}</div>
                </div>
                
                <div class="info-item">
                    <span class="info-label">
                        <i class="bi bi-telephone"></i>
                        {{ __('app.phone') }}
                    </span>
                    <span class="info-value">{{ $vendita->cliente->telefono ?? 'N/A' }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">
                        <i class="bi bi-envelope"></i>
                        {{ __('app.email') }}
                    </span>
                    <span class="info-value">{{ $vendita->cliente->email ?? 'N/A' }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">
                        <i class="bi bi-geo-alt"></i>
                        {{ __('app.city') }}
                    </span>
                    <span class="info-value">{{ $vendita->cliente->citta ?? 'N/A' }}</span>
                </div>
                
                <div style="margin-top: 1.5rem;">
                    <a href="{{ route('clienti.show', $vendita->cliente) }}" class="modern-btn primary" style="width: 100%; justify-content: center;">
                        <i class="bi bi-person"></i> {{ __('app.view_customer') }}
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection