@extends('layouts.app')

@section('title', 'Dettaglio Magazzino - ' . $prodotto->nome . ' - Gestionale Negozio')

@section('content')
<style>
    .sales-container {
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
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .page-subtitle {
        color: #6c757d;
        font-size: 1.1rem;
        margin-top: 0.5rem;
    }
    
    .form-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #029D7E;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .modern-btn {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        border: none;
        border-radius: 12px;
        padding: 10px 20px;
        font-weight: 600;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: inline-block;
        margin: 0 3px;
        font-size: 0.9rem;
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
        box-shadow: 0 15px 35px rgba(2, 157, 126, 0.4);
        color: white;
    }
    
    .modern-btn.secondary {
        background: linear-gradient(135deg, #6c757d, #495057);
    }
    
    .modern-btn.secondary:hover {
        box-shadow: 0 15px 35px rgba(108, 117, 125, 0.4);
    }
    
    .modern-btn.success {
        background: linear-gradient(135deg, #28a745, #20c997);
    }
    
    .modern-btn.success:hover {
        box-shadow: 0 15px 35px rgba(40, 167, 69, 0.4);
    }
    
    .modern-btn.edit {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        padding: 6px 12px;
        font-size: 0.8rem;
    }
    
    .modern-btn.edit:hover {
        box-shadow: 0 10px 25px rgba(255, 133, 0, 0.4);
    }
    
    .info-detail {
        display: flex;
        margin-bottom: 0.8rem;
        align-items: center;
    }
    
    .info-label {
        font-weight: 600;
        color: #029D7E;
        min-width: 100px;
    }
    
    .info-value {
        color: #495057;
        font-weight: 500;
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
        text-transform: uppercase;
        letter-spacing: 0.5px;
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
    }
    
    .variant-badge {
        padding: 4px 8px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .size-badge {
        background: rgba(2, 157, 126, 0.1);
        color: #029D7E;
        border: 1px solid rgba(2, 157, 126, 0.3);
    }
    
    .color-badge {
        background: rgba(72, 202, 228, 0.1);
        color: #0077b6;
        border: 1px solid rgba(72, 202, 228, 0.3);
    }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    
    .status-ok {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .status-low {
        background: linear-gradient(135deg, #f72585, #c5025a);
        color: white;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }
    
    .stat-card.primary::before {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
    }
    
    .stat-card.success::before {
        background: linear-gradient(135deg, #28a745, #20c997);
    }
    
    .stat-card.warning::before {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
    }
    
    .stat-card.info::before {
        background: linear-gradient(135deg, #48cae4, #0077b6);
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.85rem;
    }
    
    .stat-card.primary .stat-number {
        color: #029D7E;
    }
    
    .stat-card.success .stat-number {
        color: #28a745;
    }
    
    .stat-card.warning .stat-number {
        color: #ff8500;
    }
    
    .stat-card.info .stat-number {
        color: #0077b6;
    }
    
    .empty-state {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .empty-icon {
        font-size: 4rem;
        color: #6c757d;
        margin-bottom: 1.5rem;
        display: block;
    }
    
    .empty-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 1rem;
    }
    
    .empty-description {
        color: #6c757d;
        margin-bottom: 2rem;
        font-size: 1.1rem;
    }
    
    /* Dark Mode */
    [data-bs-theme="dark"] .page-header,
    [data-bs-theme="dark"] .form-card,
    [data-bs-theme="dark"] .stat-card,
    [data-bs-theme="dark"] .empty-state {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .info-value,
    [data-bs-theme="dark"] .stat-label,
    [data-bs-theme="dark"] .empty-title {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .page-subtitle,
    [data-bs-theme="dark"] .empty-description {
        color: #a0aec0;
    }
    
    [data-bs-theme="dark"] .modern-table tbody tr:hover {
        background: rgba(2, 157, 126, 0.1);
    }
    
    [data-bs-theme="dark"] .modern-table tbody td {
        color: #e2e8f0;
        border-bottom-color: rgba(2, 157, 126, 0.2);
    }
    
    [data-bs-theme="dark"] .size-badge {
        background: rgba(2, 157, 126, 0.2);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .color-badge {
        background: rgba(72, 202, 228, 0.2);
        color: #48cae4;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .sales-container {
            padding: 1rem;
        }
        
        .page-header, .form-card {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .page-title {
            font-size: 2rem;
        }
        
        .modern-btn {
            padding: 8px 16px;
            font-size: 0.8rem;
            margin: 2px;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .stat-card {
            padding: 1.5rem;
        }
        
        .stat-number {
            font-size: 2rem;
        }
    }
    
    @media (max-width: 576px) {
        .page-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .info-detail {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.3rem;
        }
    }
</style>

<div class="sales-container">
    <!-- Header della Pagina -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-box-seam"></i> {{ __('app.warehouse_details') }}
                </h1>
                <p class="page-subtitle">{{ $prodotto->nome }}</p>
            </div>
            <div class="action-buttons">
                <a href="{{ route('magazzino.index') }}" class="modern-btn secondary">
                    <i class="bi bi-arrow-left"></i> {{ __('app.back_to_warehouse') }}
                </a>
                <a href="{{ route('magazzino.create') }}" class="modern-btn success">
                    <i class="bi bi-plus"></i> {{ __('app.add_stock') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Informazioni Prodotto -->
    <div class="form-card">
        <h3 class="section-title">
            <i class="bi bi-info-circle"></i> Informazioni Prodotto
        </h3>
        
        <div class="row">
            <div class="col-md-6">
                <h4 style="color: #029D7E; font-weight: 700; margin-bottom: 1rem;">{{ $prodotto->nome }}</h4>
                <p style="color: #6c757d; font-size: 1rem; line-height: 1.6;">{{ $prodotto->descrizione ?: 'Nessuna descrizione disponibile' }}</p>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-6">
                        <div class="info-detail">
                            <span class="info-label">{{ __('app.price') }}:</span>
                            <span class="info-value" style="font-weight: 700; color: #28a745;">€{{ number_format($prodotto->prezzo, 2, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-detail">
                            <span class="info-label">{{ __('app.category') }}:</span>
                            <span class="info-value">{{ $prodotto->categoria }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-detail">
                            <span class="info-label">{{ __('app.brand') }}:</span>
                            <span class="info-value">{{ $prodotto->brand ?: 'N/A' }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-detail">
                            <span class="info-label">{{ __('app.code') }}:</span>
                            <span class="info-value" style="font-family: monospace; background: rgba(2, 157, 126, 0.1); padding: 2px 6px; border-radius: 4px;">{{ $prodotto->codice_prodotto }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Varianti Magazzino -->
    <div class="form-card">
        <h3 class="section-title">
            <i class="bi bi-grid-3x3-gap"></i> {{ __('app.warehouse_variants') }}
        </h3>
        
        @if($prodotto->magazzino->count() > 0)
            <div class="table-responsive">
                <table class="table modern-table">
                    <thead>
                        <tr>
                            <th><i class="bi bi-rulers"></i> {{ __('app.size') }}</th>
                            <th><i class="bi bi-palette"></i> {{ __('app.color') }}</th>
                            <th><i class="bi bi-123"></i> {{ __('app.quantity') }}</th>
                            <th><i class="bi bi-shield-check"></i> {{ __('app.min_stock') }}</th>
                            <th><i class="bi bi-flag"></i> {{ __('app.status') }}</th>
                            <th><i class="bi bi-gear"></i> {{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prodotto->magazzino as $variante)
                        <tr>
                            <td>
                                <span class="variant-badge size-badge">{{ $variante->taglia }}</span>
                            </td>
                            <td>
                                <span class="variant-badge color-badge">{{ $variante->colore }}</span>
                            </td>
                            <td>
                                <strong style="font-size: 1.1rem;">{{ $variante->quantita }}</strong>
                            </td>
                            <td>
                                {{ $variante->scorta_minima }}
                            </td>
                            <td>
                                @if($variante->quantita <= $variante->scorta_minima)
                                    <span class="status-badge status-low">
                                        <i class="bi bi-exclamation-triangle"></i> {{ __('app.low_stock') }}
                                    </span>
                                @else
                                    <span class="status-badge status-ok">
                                        <i class="bi bi-check-circle"></i> OK
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('magazzino.edit', $variante->id) }}" class="modern-btn edit">
                                    <i class="bi bi-pencil"></i> {{ __('app.edit') }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Statistiche -->
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-number">{{ $prodotto->magazzino->count() }}</div>
                    <div class="stat-label">{{ __('app.total_variants') }}</div>
                </div>
                <div class="stat-card success">
                    <div class="stat-number">{{ $prodotto->magazzino->sum('quantita') }}</div>
                    <div class="stat-label">{{ __('app.total_pieces') }}</div>
                </div>
                <div class="stat-card warning">
                    <div class="stat-number">{{ $prodotto->magazzino->where('quantita', '<=', 'scorta_minima')->count() }}</div>
                    <div class="stat-label">{{ __('app.low_stock') }}</div>
                </div>
                <div class="stat-card info">
                    <div class="stat-number">€{{ number_format($prodotto->magazzino->sum('quantita') * $prodotto->prezzo, 0, ',', '.') }}</div>
                    <div class="stat-label">{{ __('app.total_value') }}</div>
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-box empty-icon"></i>
                <h3 class="empty-title">{{ __('app.no_variants_in_warehouse') }}</h3>
                <p class="empty-description">{{ __('app.product_no_variants_loaded') }}</p>
                <a href="{{ route('magazzino.create') }}" class="modern-btn success">
                    <i class="bi bi-plus"></i> {{ __('app.add_first_variant') }}
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animazioni di caricamento
    const elements = document.querySelectorAll('.page-header, .form-card');
    elements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'all 0.6s ease';
        
        setTimeout(() => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 200);
    });
    
    // Animazione statistiche
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.5s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        }, 1000 + (index * 150));
    });
    
    // Animazione righe tabella
    const tableRows = document.querySelectorAll('.modern-table tbody tr');
    tableRows.forEach((row, index) => {
        setTimeout(() => {
            row.style.opacity = '0';
            row.style.transform = 'translateX(-20px)';
            row.style.transition = 'all 0.5s ease';
            
            setTimeout(() => {
                row.style.opacity = '1';
                row.style.transform = 'translateX(0)';
            }, 100);
        }, 800 + (index * 100));
    });
});
</script>
@endsection