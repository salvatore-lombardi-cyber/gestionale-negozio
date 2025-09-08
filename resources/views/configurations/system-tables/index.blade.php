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

    /* ========================================== */
    /* SEZIONE TABELLE PREFERITE - NEW! */
    /* ========================================== */
    
    .favorites-section {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(15px);
        border-radius: 25px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.3);
        position: relative;
        overflow: hidden;
    }
    
    .favorites-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(135deg, #ffd60a 0%, #ff8500 100%);
        border-radius: 25px 25px 0 0;
    }
    
    .favorites-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #2d3748;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    
    .favorites-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.2rem;
        margin-bottom: 1.5rem;
    }
    
    .favorite-card {
        background: white;
        border-radius: 18px;
        padding: 1.2rem;
        text-decoration: none;
        color: #2d3748;
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: 120px;
        cursor: pointer;
    }
    
    .favorite-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(135deg, var(--card-color-from), var(--card-color-to));
        border-radius: 18px 18px 0 0;
    }
    
    .favorite-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        text-decoration: none;
        color: #2d3748;
    }
    
    .favorite-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.8rem;
        font-size: 1.5rem;
        color: white;
        background: linear-gradient(135deg, var(--card-color-from), var(--card-color-to));
    }
    
    .favorite-name {
        font-size: 0.85rem;
        font-weight: 700;
        text-align: center;
        color: #2d3748;
        line-height: 1.2;
        margin-bottom: 0.3rem;
    }
    
    .favorite-usage {
        font-size: 0.7rem;
        color: #718096;
        text-align: center;
    }
    
    .remove-favorite {
        position: absolute;
        top: 6px;
        right: 6px;
        background: rgba(239, 68, 68, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 0.7rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        opacity: 0;
    }
    
    .favorite-card:hover .remove-favorite {
        opacity: 1;
    }
    
    .remove-favorite:hover {
        background: rgba(220, 38, 38, 1);
        transform: scale(1.1);
    }
    
    .add-favorite-section {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 15px;
        border: 2px dashed #cbd5e0;
    }
    
    .add-favorite-dropdown {
        flex: 1;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.8rem;
        font-size: 0.9rem;
        background: white;
    }
    
    .add-favorite-btn {
        background: linear-gradient(135deg, #ffd60a 0%, #ff8500 100%);
        color: white;
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .add-favorite-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255, 133, 0, 0.3);
    }
    
    .empty-favorites {
        text-align: center;
        padding: 2rem;
        color: #718096;
    }
    
    .empty-favorites i {
        font-size: 3rem;
        color: #cbd5e0;
        margin-bottom: 1rem;
    }

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

    <!-- ========================================== -->
    <!-- SEZIONE TABELLE PREFERITE - NEW! -->
    <!-- ========================================== -->
    <div class="favorites-section">
        <h2 class="favorites-title">
            <i class="bi bi-star-fill" style="color: #ffd60a;"></i>
            Le Tue Tabelle Frequenti
            <span style="font-size: 0.7em; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 0.2rem 0.6rem; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Smart</span>
        </h2>
        
        @if($favoriteTablesWithDetails && $favoriteTablesWithDetails->count() > 0)
            <div class="favorites-grid">
                @foreach($favoriteTablesWithDetails as $favorite)
                    @php
                        $systemTable = $favorite->systemTable;
                        $permissions = $user_permissions[$systemTable->objname] ?? [];
                        $canRead = $permissions['read'] ?? false;
                        
                        $cardUrl = '#';
                        if ($canRead) {
                            if ($systemTable->objname === 'vat_nature_associations') {
                                $cardUrl = route('configurations.vat-nature-configurator');
                            } elseif ($systemTable->objname === 'tax_rates') {
                                $cardUrl = route('configurations.tax-rates-configurator');
                            } else {
                                $cardUrl = route('configurations.system-tables.show', $systemTable->objname);
                            }
                        }
                    @endphp
                    
                    <div class="favorite-card {{ !$canRead ? 'disabled' : '' }}" 
                         data-table="{{ $systemTable->objname }}"
                         data-url="{{ $cardUrl }}"
                         style="--card-color-from: {{ $systemTable->color_from }}; --card-color-to: {{ $systemTable->color_to }};">
                        
                        <!-- Pulsante Rimuovi -->
                        <button class="remove-favorite" onclick="removeFavorite('{{ $systemTable->objname }}')">
                            <i class="bi bi-x"></i>
                        </button>
                        
                        <!-- Icona -->
                        <div class="favorite-icon">
                            {!! $systemTable->icon_svg !!}
                        </div>
                        
                        <!-- Nome -->
                        <div class="favorite-name">{{ $systemTable->display_name }}</div>
                        
                        <!-- Statistiche Utilizzo -->
                        <div class="favorite-usage">
                            @if($favorite->click_count > 0)
                                {{ $favorite->click_count }} utilizzi
                            @else
                                Nuovo preferito
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-favorites">
                <i class="bi bi-star"></i>
                <h5>Nessuna tabella preferita</h5>
                <p>Aggiungi le tabelle che usi più spesso per un accesso rapido!</p>
            </div>
        @endif
        
        <!-- Sezione Aggiungi Preferito -->
        <div class="add-favorite-section">
            <div style="display: flex; align-items: center; gap: 0.5rem; color: #718096; font-weight: 600;">
                <i class="bi bi-plus-circle"></i>
                Aggiungi tabella preferita:
            </div>
            
            <select id="addFavoriteDropdown" class="add-favorite-dropdown">
                <option value="">Seleziona una tabella...</option>
                @foreach($tables as $table)
                    @php
                        $permissions = $user_permissions[$table->objname] ?? [];
                        $canRead = $permissions['read'] ?? false;
                        $alreadyFavorite = $favoriteTablesWithDetails->where('table_objname', $table->objname)->count() > 0;
                    @endphp
                    @if($canRead && !$alreadyFavorite)
                        <option value="{{ $table->objname }}">{{ $table->display_name }}</option>
                    @endif
                @endforeach
            </select>
            
            <button id="addFavoriteBtn" class="add-favorite-btn" onclick="addFavorite()">
                <i class="bi bi-plus"></i> Aggiungi
            </button>
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
                    } elseif ($table->objname === 'tax_rates') {
                        $cardUrl = route('configurations.tax-rates-configurator');
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
    
    // ========================================== 
    // GESTIONE PREFERITI - NEW!
    // ==========================================
    
    // Click handler per le card preferite
    document.querySelectorAll('.favorite-card').forEach(card => {
        card.addEventListener('click', function(e) {
            // Non fare nulla se è stato cliccato il pulsante rimuovi
            if (e.target.closest('.remove-favorite')) return;
            
            const url = this.dataset.url;
            const tableObjname = this.dataset.table;
            
            if (url && url !== '#') {
                // Traccia l'utilizzo
                trackTableUsage(tableObjname);
                // Vai alla pagina
                window.location.href = url;
            }
        });
    });
});

// ==========================================
// FUNZIONI PREFERITI
// ==========================================

/**
 * Aggiungi tabella ai preferiti
 */
function addFavorite() {
    const dropdown = document.getElementById('addFavoriteDropdown');
    const tableObjname = dropdown.value;
    
    if (!tableObjname) {
        alert('Seleziona una tabella dalla lista!');
        return;
    }
    
    // Disabilita il pulsante
    const btn = document.getElementById('addFavoriteBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Aggiungendo...';
    btn.disabled = true;
    
    fetch('{{ route("configurations.system-tables.favorites.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            table_objname: tableObjname
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            // Successo - ricarica la pagina per mostrare la nuova card
            location.reload();
        } else if (data.error) {
            alert('Errore: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        alert('Errore durante l\'aggiunta della tabella ai preferiti');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

/**
 * Rimuovi tabella dai preferiti
 */
function removeFavorite(tableObjname) {
    if (!confirm('Rimuovere questa tabella dai preferiti?')) return;
    
    fetch('{{ route("configurations.system-tables.favorites.remove") }}', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            table_objname: tableObjname
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            // Successo - ricarica la pagina
            location.reload();
        } else if (data.error) {
            alert('Errore: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        alert('Errore durante la rimozione della tabella dai preferiti');
    });
}

/**
 * Traccia utilizzo tabella
 */
function trackTableUsage(tableObjname) {
    fetch('{{ route("configurations.system-tables.track-usage") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            table_objname: tableObjname
        })
    })
    .catch(error => {
        // Silently fail - tracking non è critico
        console.log('Tracking error:', error);
    });
}
</script>
@endsection