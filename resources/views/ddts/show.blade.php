@extends('layouts.app')

@section('title', 'DDT ' . $ddt->numero_ddt . ' - Gestionale Negozio')

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
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
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
        color: #667eea;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .modern-btn {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        border-radius: 12px;
        padding: 10px 20px;  /* ← PIÙ PICCOLI */
        font-weight: 600;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: inline-block;
        margin: 0 3px;  /* ← MARGINI PIÙ PICCOLI */
        font-size: 0.9rem;  /* ← TESTO PIÙ PICCOLO */
    }
    .action-buttons {
        display: flex;
        gap: 0.5rem;  /* ← GAP UNIFORME */
        align-items: center;
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
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
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
    
    .modern-btn.info {
        background: linear-gradient(135deg, #48cae4, #0077b6);
    }
    
    .modern-btn.info:hover {
        box-shadow: 0 15px 35px rgba(72, 202, 228, 0.4);
    }
    
    .info-detail {
        display: flex;
        margin-bottom: 0.8rem;
        align-items: flex-start;
    }
    
    .info-label {
        font-weight: 600;
        color: #667eea;
        min-width: 120px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-value {
        color: #495057;
        font-weight: 500;
        flex: 1;
    }
    
    .status-badge {
        padding: 8px 16px;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .status-bozza {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
    }
    
    .status-confermato {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    
    .status-spedito {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
    }
    
    .status-consegnato {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .modern-table {
        margin: 0;
        width: 100%;
    }
    
    .modern-table thead th {
        background: linear-gradient(135deg, #667eea, #764ba2);
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
        background: rgba(102, 126, 234, 0.05);
        transform: scale(1.01);
    }
    
    .modern-table tbody td {
        padding: 1rem;
        border: none;
        border-bottom: 1px solid rgba(102, 126, 234, 0.1);
        vertical-align: middle;
    }
    
    .modern-table tfoot th {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
        font-weight: 700;
        font-size: 1.1rem;
        padding: 1rem;
        border: none;
    }
    
    .product-badge {
        padding: 4px 8px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .size-badge {
        background: rgba(102, 126, 234, 0.1);
        color: #667eea;
        border: 1px solid rgba(102, 126, 234, 0.3);
    }
    
    .color-badge {
        background: rgba(72, 202, 228, 0.1);
        color: #0077b6;
        border: 1px solid rgba(72, 202, 228, 0.3);
    }
    
    .ddt-number-badge {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 8px 16px;
        border-radius: 15px;
        font-size: 1.1rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .price-highlight {
        font-weight: 700;
        color: #28a745;
        font-size: 1.1rem;
    }
    
    /* Dark Mode */
    [data-bs-theme="dark"] .page-header,
    [data-bs-theme="dark"] .form-card {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .info-value {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .modern-table tbody tr:hover {
        background: rgba(102, 126, 234, 0.1);
    }
    
    [data-bs-theme="dark"] .modern-table tbody td {
        color: #e2e8f0;
        border-bottom-color: rgba(102, 126, 234, 0.2);
    }
    
    [data-bs-theme="dark"] .modern-table tfoot th {
        background: rgba(102, 126, 234, 0.2);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .size-badge {
        background: rgba(102, 126, 234, 0.2);
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
            padding: 12px 20px;
            font-size: 0.9rem;
            margin: 2px;
        }
        
        .info-detail {
            flex-direction: column;
            gap: 0.3rem;
        }
        
        .info-label {
            min-width: auto;
        }
    }
    
    @media (max-width: 576px) {
        .page-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
    }
</style>

<div class="sales-container">
    <!-- Header della Pagina -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-file-earmark-text"></i> DDT
                </h1>
                <div class="ddt-number-badge">
                    <i class="bi bi-hash"></i> {{ $ddt->numero_ddt }}
                </div>
            </div>
            <div class="action-buttons">
                <a href="{{ route('ddts.index') }}" class="modern-btn secondary">
                    <i class="bi bi-arrow-left"></i> {{ __('app.back_to_ddts') }}
                </a>
                <form method="POST" action="{{ route('ddts.email', $ddt) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="modern-btn success">
                        <i class="bi bi-envelope"></i> {{ __('app.send_email') }}
                    </button>
                </form>
                <a href="{{ route('ddts.pdf', $ddt) }}" class="modern-btn info">
                    <i class="bi bi-printer"></i> {{ __('app.print_pdf') }}
                </a>
            </div>
        </div>
    </div>
    
    <!-- Informazioni DDT e Cliente -->
    <div class="form-card">
        <div class="row">
            <div class="col-md-6">
                <h3 class="section-title">
                    <i class="bi bi-file-earmark-text"></i> {{ __('app.ddt_information') }}
                </h3>
                
                <div class="info-detail">
                    <span class="info-label">
                        <i class="bi bi-hash"></i> {{ __('app.number') }}:
                    </span>
                    <span class="info-value">{{ $ddt->numero_ddt }}</span>
                </div>
                
                <div class="info-detail">
                    <span class="info-label">
                        <i class="bi bi-calendar"></i> {{ __('app.date') }}:
                    </span>
                    <span class="info-value">{{ $ddt->data_ddt->format('d/m/Y') }}</span>
                </div>
                
                <div class="info-detail">
                    <span class="info-label">
                        <i class="bi bi-tag"></i> {{ __('app.reason') }}:
                    </span>
                    <span class="info-value">{{ $ddt->causale }}</span>
                </div>
                
                <div class="info-detail">
                    <span class="info-label">
                        <i class="bi bi-flag"></i> {{ __('app.status') }}:
                    </span>
                    <span class="info-value">
                        @if($ddt->stato == 'bozza')
                        <span class="status-badge status-bozza">
                            <i class="bi bi-file-earmark"></i> {{ __('app.draft') }}
                        </span>
                        @elseif($ddt->stato == 'confermato')
                        <span class="status-badge status-confermato">
                            <i class="bi bi-check-circle"></i> {{ __('app.confirmed') }}
                        </span>
                        @elseif($ddt->stato == 'spedito')
                        <span class="status-badge status-spedito">
                            <i class="bi bi-truck"></i> {{ __('app.shipped') }}
                        </span>
                        @else
                        <span class="status-badge status-consegnato">
                            <i class="bi bi-check-circle-fill"></i> {{ __('app.delivered') }}
                        </span>
                        @endif
                    </span>
                </div>
            </div>
            
            <div class="col-md-6">
                <h3 class="section-title">
                    <i class="bi bi-person"></i> {{ __('app.customer') }}
                </h3>
                
                <div class="info-detail">
                    <span class="info-label">
                        <i class="bi bi-person-check"></i> Nome:
                    </span>
                    <span class="info-value">
                        <strong>{{ $ddt->cliente ? $ddt->cliente->nome_completo : 'Cliente occasionale' }}</strong>
                    </span>
                </div>
                
                @if($ddt->cliente)
                <div class="info-detail">
                    <span class="info-label">
                        <i class="bi bi-geo-alt"></i> Indirizzo:
                    </span>
                    <span class="info-value">
                        {{ $ddt->cliente->indirizzo }}<br>
                        {{ $ddt->cliente->cap }} {{ $ddt->cliente->citta }}
                    </span>
                </div>
                
                @if($ddt->cliente->telefono)
                <div class="info-detail">
                    <span class="info-label">
                        <i class="bi bi-telephone"></i> {{ __('app.tel') }}:
                    </span>
                    <span class="info-value">{{ $ddt->cliente->telefono }}</span>
                </div>
                @endif
                
                @if($ddt->cliente->email)
                <div class="info-detail">
                    <span class="info-label">
                        <i class="bi bi-envelope"></i> {{ __('app.email') }}:
                    </span>
                    <span class="info-value">{{ $ddt->cliente->email }}</span>
                </div>
                @endif
                @else
                <div class="info-detail">
                    <span class="info-label">
                        <i class="bi bi-info-circle"></i> Tipo:
                    </span>
                    <span class="info-value">
                        <em style="color: #6c757d;">Cliente occasionale - Dati non disponibili</em>
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Destinatario -->
    <div class="form-card">
        <h3 class="section-title">
            <i class="bi bi-geo-alt"></i> {{ __('app.recipient') }}
        </h3>
        
        <div class="info-detail">
            <span class="info-label">
                <i class="bi bi-person-badge"></i> Destinatario:
            </span>
            <span class="info-value">
                <strong>{{ $ddt->destinatario_completo }}</strong>
            </span>
        </div>
        
        <div class="info-detail">
            <span class="info-label">
                <i class="bi bi-house"></i> Indirizzo:
            </span>
            <span class="info-value">{{ $ddt->indirizzo_completo }}</span>
        </div>
        
        @if($ddt->trasportatore)
        <div class="info-detail">
            <span class="info-label">
                <i class="bi bi-truck"></i> {{ __('app.carrier') }}:
            </span>
            <span class="info-value">{{ $ddt->trasportatore }}</span>
        </div>
        @endif
    </div>
    
    <!-- Prodotti -->
    <div class="form-card">
        <h3 class="section-title">
            <i class="bi bi-box-seam"></i> {{ __('app.products') }}
        </h3>
        
        <div class="table-responsive">
            <table class="table modern-table">
                <thead>
                    <tr>
                        <th><i class="bi bi-tag"></i> {{ __('app.product') }}</th>
                        <th><i class="bi bi-rulers"></i> {{ __('app.size') }}</th>
                        <th><i class="bi bi-palette"></i> {{ __('app.color') }}</th>
                        <th><i class="bi bi-123"></i> {{ __('app.quantity') }}</th>
                        <th><i class="bi bi-currency-euro"></i> {{ __('app.unit_price') }}</th>
                        <th><i class="bi bi-calculator"></i> {{ __('app.subtotal') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ddt->vendita->dettagli as $dettaglio)
                    <tr>
                        <td><strong>{{ $dettaglio->prodotto->nome }}</strong></td>
                        <td>
                            <span class="product-badge size-badge">{{ $dettaglio->taglia }}</span>
                        </td>
                        <td>
                            <span class="product-badge color-badge">{{ $dettaglio->colore }}</span>
                        </td>
                        <td><strong>{{ $dettaglio->quantita }}</strong></td>
                        <td>€{{ number_format($dettaglio->prezzo_unitario, 2, ',', '.') }}</td>
                        <td>
                            <span class="price-highlight">€{{ number_format($dettaglio->subtotale, 2, ',', '.') }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5">{{ __('app.ddt_total') }}</th>
                        <th>
                            <span class="price-highlight" style="font-size: 1.3rem;">
                                €{{ number_format($ddt->vendita->totale_finale, 2, ',', '.') }}
                            </span>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <!-- Note -->
    @if($ddt->note)
    <div class="form-card">
        <h3 class="section-title">
            <i class="bi bi-chat-text"></i> {{ __('app.notes') }}
        </h3>
        <div style="background: rgba(102, 126, 234, 0.1); padding: 1.5rem; border-radius: 15px; color: #495057; font-size: 1rem; line-height: 1.6;">
            {{ $ddt->note }}
        </div>
    </div>
    @endif
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
            }, index * 100);
        });
    });
</script>
@endsection