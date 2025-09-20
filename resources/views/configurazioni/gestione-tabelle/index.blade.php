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
        background: linear-gradient(135deg, #9c27b0, #7b1fa2);
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
        color: #9c27b0; /* Viola gestionale */
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
    
    /* Limitazione larghezza card quando sono poche */
    .tables-grid .table-card {
        max-width: 350px;
        justify-self: start;
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

    /* ===== SISTEMAZIONE CSS INLINE ===== */
    /* Stella favoriti dorata */
    .favorites-star {
        color: #ffd60a;
    }

    /* Badge "Smart" nei titoli */
    .smart-badge {
        font-size: 0.7em;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 0.2rem 0.6rem;
        border-radius: 50px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
    }

</style>

<div class="dashboard-container">
    <!-- Header -->
    <div class="dashboard-header">
        <h1 class="dashboard-title">
            <i class="bi bi-gear me-3"></i>
            Gestione Tabelle di Sistema
        </h1>
        <p class="dashboard-subtitle">
            Gestisci tutte le tabelle di configurazione del sistema
        </p>
        
        <!-- Metriche Performance -->
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-value">27</div>
                <div class="metric-label">Tabelle Totali</div>
            </div>
            <div class="metric-card">
                <div class="metric-value text-success">27</div>
                <div class="metric-label">Tabelle Attive</div>
            </div>
            <div class="metric-card">
                <div class="metric-value text-info">0</div>
                <div class="metric-label">Record Totali</div>
            </div>
            <div class="metric-card">
                <div class="metric-value text-warning">100%</div>
                <div class="metric-label">Cache Hit Rate</div>
            </div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- SEZIONE TABELLE PREFERITE - NEW! -->
    <!-- ========================================== -->
    <div class="favorites-section">
        <h2 class="favorites-title">
            <i class="bi bi-star-fill favorites-star"></i>
            Le Tue Tabelle Frequenti
            <span class="smart-badge">Smart</span>
        </h2>
        
        <!-- Mostra tabelle frequenti o messaggio vuoto -->
        <div class="favorites-grid">
            @if(isset($tabelle) && count($tabelle) > 0)
                @php
                    $tabelleFrequenti = collect($tabelle)->take(4); // Primi 4 come frequenti
                @endphp
                @foreach($tabelleFrequenti as $tabella)
                    <div class="favorite-card"
                         data-table="{{ $tabella['nome'] }}"
                         data-url="{{ route('configurations.gestione-tabelle.tabella', $tabella['nome']) }}"
                         style="--card-color-from: {{ $tabella['color_from'] ?? ($tabella['colore'] === 'primary' ? '#029D7E' : ($tabella['colore'] === 'warning' ? '#ffd60a' : ($tabella['colore'] === 'info' ? '#48cae4' : '#6c757d'))) }}; --card-color-to: {{ $tabella['color_to'] ?? ($tabella['colore'] === 'primary' ? '#4DC9A5' : ($tabella['colore'] === 'warning' ? '#ff8500' : ($tabella['colore'] === 'info' ? '#0077b6' : '#545b62'))) }};">
                        
                        <!-- Icona -->
                        <div class="favorite-icon">
                            <i class="{{ $tabella['icona'] }}"></i>
                        </div>
                        
                        <!-- Nome -->
                        <div class="favorite-name">{{ $tabella['titolo'] }}</div>
                        
                        <!-- Statistiche Utilizzo -->
                        <div class="favorite-usage">
                            Accesso rapido
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-favorites">
                    <i class="bi bi-star"></i>
                    <h5>Nessuna tabella preferita</h5>
                    <p>Aggiungi le tabelle che usi più spesso per un accesso rapido!</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Grid delle Tabelle Sistema -->
    <div class="tables-grid">
        @if(isset($tabelle))
            @foreach($tabelle as $index => $tabella)
                @php
                    $colorFrom = match($tabella['colore']) {
                        'primary' => '#029D7E',
                        'success' => '#029D7E', 
                        'warning' => '#ffd60a',
                        'info' => '#48cae4',
                        'secondary' => '#6c757d',
                        'danger' => '#dc3545',
                        default => '#029D7E'
                    };
                    $colorTo = match($tabella['colore']) {
                        'primary' => '#4DC9A5',
                        'success' => '#4DC9A5',
                        'warning' => '#ff8500', 
                        'info' => '#0077b6',
                        'secondary' => '#545b62',
                        'danger' => '#c82333',
                        default => '#4DC9A5'
                    };
                @endphp
                
                <a href="{{ route('configurations.gestione-tabelle.tabella', $tabella['nome']) }}" 
                   class="table-card"
                   style="--card-color-from: {{ $tabella['color_from'] ?? $colorFrom }}; --card-color-to: {{ $tabella['color_to'] ?? $colorTo }};">
                   
                    <!-- Badge Record Count -->
                    <div class="records-badge">
                        0
                    </div>
                    
                    <!-- Indicatore Permessi -->
                    <div class="permission-indicator permission-full"></div>
                    
                    <!-- Icona Centrale -->
                    <div class="table-icon-container" style="background: linear-gradient(135deg, var(--card-color-from), var(--card-color-to));">
                        <i class="{{ $tabella['icona'] }}"></i>
                    </div>
                    
                    <!-- Nome Tabella -->
                    <h3 class="table-name">{{ $tabella['titolo'] }}</h3>
                    
                    <!-- Descrizione -->
                    <p class="table-description">
                        {{ $tabella['descrizione'] }}
                    </p>
                </a>
            @endforeach
        @else
            <!-- Tabelle di default se il service non è ancora configurato -->
            @php
                $tabelleDefault = [
                    ['nome' => 'banche', 'titolo' => 'Banche', 'icona' => 'bi-bank', 'colore' => 'primary', 'descrizione' => 'Gestione coordinate bancarie e istituti di credito'],
                    ['nome' => 'clienti', 'titolo' => 'Clienti', 'icona' => 'bi-people', 'colore' => 'success', 'descrizione' => 'Gestione anagrafica clienti'],
                    ['nome' => 'fornitori', 'titolo' => 'Fornitori', 'icona' => 'bi-truck', 'colore' => 'warning', 'descrizione' => 'Gestione anagrafica fornitori'],
                    ['nome' => 'prodotti', 'titolo' => 'Categorie Prodotti', 'icona' => 'bi-tags', 'colore' => 'info', 'descrizione' => 'Gestione categorie merceologiche'],
                ];
            @endphp
            @foreach($tabelleDefault as $tabella)
                @php
                    $colorFrom = match($tabella['colore']) {
                        'primary' => '#029D7E',
                        'success' => '#029D7E', 
                        'warning' => '#ffd60a',
                        'info' => '#48cae4',
                        default => '#029D7E'
                    };
                    $colorTo = match($tabella['colore']) {
                        'primary' => '#4DC9A5',
                        'success' => '#4DC9A5',
                        'warning' => '#ff8500', 
                        'info' => '#0077b6',
                        default => '#4DC9A5'
                    };
                @endphp
                
                <a href="{{ route('configurations.gestione-tabelle.tabella', $tabella['nome']) }}" 
                   class="table-card"
                   style="--card-color-from: {{ $tabella['color_from'] ?? $colorFrom }}; --card-color-to: {{ $tabella['color_to'] ?? $colorTo }};">
                   
                    <div class="records-badge">0</div>
                    <div class="permission-indicator permission-full"></div>
                    
                    <div class="table-icon-container" style="background: linear-gradient(135deg, var(--card-color-from), var(--card-color-to));">
                        <i class="{{ $tabella['icona'] }}"></i>
                    </div>
                    
                    <h3 class="table-name">{{ $tabella['titolo'] }}</h3>
                    <p class="table-description">{{ $tabella['descrizione'] }}</p>
                </a>
            @endforeach
        @endif
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
    
    // Click handler per le card preferite
    document.querySelectorAll('.favorite-card').forEach(card => {
        card.addEventListener('click', function(e) {
            const url = this.dataset.url;
            if (url && url !== '#') {
                window.location.href = url;
            }
        });
    });
});
</script>
@endsection