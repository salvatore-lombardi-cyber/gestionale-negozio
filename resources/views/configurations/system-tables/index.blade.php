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
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
        text-align: center;
    }
    
    .dashboard-subtitle {
        text-align: center;
        color: #6c757d;
        font-size: 1.1rem;
        margin-top: 0.5rem;
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
        background: linear-gradient(135deg, #667eea, #764ba2); /* Viola gestionale */
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
        color: #667eea; /* Viola gestionale */
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
    
    /* Grid Tabelle - Stile Gestionale */
    .tables-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }
    
    /* Card Tabelle - Stile Gestionale Coerente */
    .table-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        text-decoration: none;
        color: #2d3748;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: 200px;
    }
    
    .table-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, var(--card-color-from), var(--card-color-to));
        border-radius: 20px 20px 0 0;
    }
    
    .table-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
        text-decoration: none;
        color: #2d3748;
    }
    
    /* Icona Centrale Stile Gestionale */
    .table-icon-container {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    
    .table-icon-container i {
        font-size: 2.5rem;
        color: white;
    }
    
    /* Testi delle Card */
    .table-name {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        text-align: center;
        color: #2d3748;
        line-height: 1.3;
    }
    
    .table-description {
        font-size: 0.9rem;
        text-align: center;
        line-height: 1.4;
        color: #718096;
        margin-bottom: 1rem;
    }
    
    /* Badge Record Count */
    .records-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #f7fafc;
        color: #4a5568;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        border: 1px solid #e2e8f0;
    }
    
    /* Indicatore Permessi */
    .permission-indicator {
        position: absolute;
        bottom: 15px;
        right: 15px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .permission-full { background: #48bb78; }
    .permission-limited { background: #ed8936; }
    .permission-none { background: #f56565; }

    /* Dark Mode Support */
    [data-bs-theme="dark"] .dashboard-header {
        background: rgba(45, 55, 72, 0.95) !important;
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .table-card {
        background: rgba(45, 55, 72, 0.9) !important;
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .table-name {
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .metric-card {
        background: rgba(45, 55, 72, 0.9) !important;
        color: #e2e8f0 !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .dashboard-title {
            font-size: 2rem;
        }
        
        .tables-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .table-card {
            min-height: 180px;
            padding: 1.5rem;
        }
        
        .table-icon-container {
            width: 60px;
            height: 60px;
        }
        
        .table-icon-container i {
            font-size: 2rem;
        }
    }
</style>

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <i class="bi bi-gear me-3"></i>
            {{ __('app.system_tables_management') }}
        </h1>
        <p class="dashboard-subtitle">
            Gestisci tutte le tabelle di configurazione del sistema
        </p>
        
        <!-- Metriche Performance -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-value text-primary">{{ $metrics['total_tables'] }}</div>
                <div class="metric-label">Tabelle Totali</div>
            </div>
            <div class="metric-card">
                <div class="metric-value text-success">{{ $metrics['active_tables'] }}</div>
                <div class="metric-label">Tabelle Attive</div>
            </div>
            <div class="metric-card">
                <div class="metric-value text-info">{{ number_format($metrics['total_records']) }}</div>
                <div class="metric-label">Record Totali</div>
            </div>
            <div class="metric-card">
                <div class="metric-value text-warning">{{ $metrics['cache_hit_rate'] }}%</div>
                <div class="metric-label">Cache Hit Rate</div>
            </div>
        </div>
    </div>

    <!-- Grid delle Tabelle Sistema -->
    <div class="tables-grid">
        @foreach($tables as $index => $table)
            @php
                $permissions = $user_permissions[$table->objname] ?? [];
                $canRead = $permissions['read'] ?? false;
                $recordCount = $stats[$table->objname] ?? 0;
            @endphp
            
            @php
                $cardUrl = '#';
                if ($canRead) {
                    if ($table->objname === 'vat_nature_associations') {
                        $cardUrl = route('configurations.vat-nature-configurator');
                    } else {
                        $cardUrl = route('configurations.system-tables.show', $table->objname);
                    }
                }
            @endphp
            
            <a href="{{ $cardUrl }}" 
               class="table-card {{ !$canRead ? 'disabled' : '' }}"
               style="--card-color-from: {{ $table->color_from }}; --card-color-to: {{ $table->color_to }};">
               
                <!-- Badge Record Count -->
                <div class="records-badge">
                    {{ number_format($recordCount) }}
                </div>
                
                <!-- Indicatore Permessi -->
                <div class="permission-indicator 
                    @if($permissions['create'] && $permissions['update'] && $permissions['delete'])
                        permission-full
                    @elseif($permissions['read'])
                        permission-limited  
                    @else
                        permission-none
                    @endif
                "></div>
                
                <!-- Icona Centrale -->
                <div class="table-icon-container" style="background: linear-gradient(135deg, {{ $table->color_from }}, {{ $table->color_to }});">
                    {!! $table->icon_svg !!}
                </div>
                
                <!-- Nome Tabella -->
                <h3 class="table-name">{{ $table->display_name }}</h3>
                
                <!-- Descrizione -->
                <p class="table-description">
                    @switch($table->objname)
                        @case('vat_nature_associations')
                            Configuratore dinamico per associazioni nature IVA
                            @break
                        @case('vat_natures') 
                            Gestione nature IVA per fatturazione elettronica
                            @break
                        @case('good_appearances')
                            Classificazione aspetto fisico prodotti
                            @break
                        @case('banks')
                            Anagrafica banche e istituti di credito
                            @break
                        @case('product_categories')
                            Organizzazione categorie prodotti
                            @break
                        @case('customer_categories')
                            Tipologie e segmentazione clienti
                            @break
                        @case('supplier_categories')
                            Classificazione fornitori e partner
                            @break
                        @default
                            Tabella di sistema configurabile
                    @endswitch
                </p>
            </a>
        @endforeach
    </div>
</div>

<!-- Script semplificato -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animazione di entrata delle card
    const cards = document.querySelectorAll('.table-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.4s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        }, index * 50);
    });
});
</script>
@endsection