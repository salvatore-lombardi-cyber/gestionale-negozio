@extends('layouts.app')

@section('title', 'Nuovo DDT - Gestionale Negozio')

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
    
    .form-section {
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
        border-radius: 15px;
        padding: 15px 30px;
        font-weight: 600;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: inline-block; /* FIX: Non più width 100% */
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
    
    .sale-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(102, 126, 234, 0.1);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .sale-card:hover {
        border-color: rgba(102, 126, 234, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }
    
    .sale-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #667eea, #764ba2);
    }
    
    .sale-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .sale-id {
        font-size: 1.2rem;
        font-weight: 700;
        color: #667eea;
        margin: 0;
    }
    
    .sale-date {
        color: #6c757d;
        font-size: 0.9rem;
        background: rgba(102, 126, 234, 0.1);
        padding: 4px 8px;
        border-radius: 8px;
    }
    
    .sale-info {
        margin-bottom: 1.5rem;
    }
    
    .sale-detail {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .sale-label {
        font-weight: 600;
        color: #495057;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .sale-value {
        font-weight: 600;
    }
    
    .sale-total {
        font-size: 1.3rem;
        font-weight: 800;
        color: #28a745;
        background: rgba(40, 167, 69, 0.1);
        padding: 0.5rem;
        border-radius: 10px;
        text-align: center;
    }
    
    .client-badge {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }
    
    .info-alert {
        background: rgba(72, 202, 228, 0.1);
        border: 1px solid rgba(72, 202, 228, 0.3);
        border-radius: 15px;
        padding: 1.5rem;
        color: #0077b6;
        margin-bottom: 2rem;
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
        color: #ffc107;
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
    [data-bs-theme="dark"] .sale-card,
    [data-bs-theme="dark"] .empty-state {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .info-alert {
        background: rgba(72, 202, 228, 0.2);
        color: #48cae4;
    }
    
    [data-bs-theme="dark"] .sale-label,
    [data-bs-theme="dark"] .empty-title {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .sale-date {
        background: rgba(102, 126, 234, 0.2);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .empty-description {
        color: #a0aec0;
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
            padding: 12px 24px;
            font-size: 0.9rem;
        }
        
        .sale-card {
            padding: 1rem;
        }
        
        .empty-state {
            padding: 2rem 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .page-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .sale-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
    }
</style>

<div class="sales-container">
    <!-- Header della Pagina -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">
                <i class="bi bi-file-earmark-plus"></i> Nuovo DDT
            </h1>
            <a href="{{ route('ddts.index') }}" class="modern-btn secondary">
                <i class="bi bi-arrow-left"></i> Indietro
            </a>
        </div>
    </div>
    
    @if($vendite->count() > 0)
    <!-- Info Alert -->
    <!-- Sezione Standard -->
    <div class="form-card">
        <div class="form-section">
            <h3 class="section-title">
                <i class="bi bi-info-circle"></i> Seleziona Vendita
            </h3>
            <p style="color: #6c757d; margin-bottom: 0; font-size: 1rem;">
                Scegli una vendita dall'elenco sottostante per creare il DDT corrispondente
            </p>
        </div>
    </div>
    
    <!-- Form Card con Vendite -->
    <div class="form-card">
        <div class="form-section">
            <h3 class="section-title">
                <i class="bi bi-receipt"></i> Vendite Disponibili
            </h3>
            
            @foreach($vendite as $vendita)
            <div class="sale-card" data-vendita-id="{{ $vendita->id }}">
                <div class="sale-header">
                    <h4 class="sale-id">
                        <i class="bi bi-receipt"></i> Vendita #{{ $vendita->id }}
                    </h4>
                    <span class="sale-date">
                        <i class="bi bi-calendar"></i> {{ $vendita->data_vendita->format('d/m/Y') }}
                    </span>
                </div>
                
                <div class="sale-info">
                    <div class="sale-detail">
                        <span class="sale-label">
                            <i class="bi bi-person"></i> Cliente
                        </span>
                        @if($vendita->cliente)
                        <span class="client-badge">
                            <i class="bi bi-person-check"></i>
                            {{ $vendita->cliente->nome_completo }}
                        </span>
                        @else
                        <span class="client-badge" style="background: linear-gradient(135deg, #6c757d, #495057);">
                            <i class="bi bi-person"></i>
                            Cliente occasionale
                        </span>
                        @endif
                    </div>
                    
                    <div class="sale-detail">
                        <span class="sale-label">
                            <i class="bi bi-credit-card"></i> Pagamento
                        </span>
                        <span class="sale-value">
                            @switch($vendita->metodo_pagamento)
                            @case('contanti')
                            <i class="bi bi-cash"></i> Contanti
                            @break
                            @case('carta')
                            <i class="bi bi-credit-card"></i> Carta
                            @break
                            @case('bonifico')
                            <i class="bi bi-bank"></i> Bonifico
                            @break
                            @case('assegno')
                            <i class="bi bi-check2-square"></i> Assegno
                            @break
                            @default
                            {{ $vendita->metodo_pagamento }}
                            @endswitch
                        </span>
                    </div>
                </div>
                
                <div class="sale-total">
                    <i class="bi bi-currency-euro"></i> {{ number_format($vendita->totale_finale, 2, ',', '.') }}
                </div>
                
                <div style="margin-top: 1.5rem;">
                    <a href="{{ route('ddts.create') }}?vendita_id={{ $vendita->id }}" 
                        class="modern-btn success" style="width: 100%; text-align: center;">
                        <i class="bi bi-file-earmark-plus"></i> Crea DDT per questa vendita
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="empty-state">
        <i class="bi bi-exclamation-triangle empty-icon"></i>
        <h3 class="empty-title">Nessuna vendita disponibile</h3>
        <p class="empty-description">
            Tutte le vendite hanno già un DDT associato o non ci sono vendite nel sistema.<br>
            Crea prima una nuova vendita per poter generare un DDT.
        </p>
        <a href="{{ route('vendite.index') }}" class="modern-btn success">
            <i class="bi bi-cart-plus"></i> Vai alle Vendite
        </a>
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animazioni di caricamento identiche a Nuova Vendita
        const elements = document.querySelectorAll('.page-header, .form-card, .sale-card, .empty-state, .info-alert');
        elements.forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            element.style.transition = 'all 0.6s ease';
            
            setTimeout(() => {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, index * 150);
        });
        
        // Hover effects sulle card vendite
        const saleCards = document.querySelectorAll('.sale-card');
        saleCards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
        
        // Animazione loading sui bottoni
        document.addEventListener('click', function(e) {
            if (e.target.closest('.modern-btn.success')) {
                const btn = e.target.closest('.modern-btn.success');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Creando DDT...';
                btn.style.pointerEvents = 'none';
            }
        });
    });
</script>
@endsection