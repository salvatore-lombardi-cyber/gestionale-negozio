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
        
    </div>

    <!-- ========================================== -->
    <!-- SEZIONE TABELLE FREQUENTI V2 - SENZA LIMITI -->
    <!-- ========================================== -->
    <div class="favorites-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="favorites-title">
                <i class="bi bi-star-fill favorites-star"></i>
                Le Tue Tabelle Frequenti
                @if(isset($favoriteTablesV2) && $favoriteTablesV2->count() > 0)
                    <span class="smart-badge">{{ $favoriteTablesV2->count() }}</span>
                @endif
            </h2>
            
            <!-- Dropdown Moderno per aggiungere nuove tabelle -->
            @auth
            <div class="add-favorite-section-modern">
                <div class="dropdown">
                    <button class="btn btn-add-favorite dropdown-toggle" 
                            type="button" 
                            id="addFavoriteDropdown" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false">
                        <i class="bi bi-plus-lg me-2"></i>
                        <span class="add-text">Aggiungi</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end modern-dropdown" aria-labelledby="addFavoriteDropdown">
                        <li><h6 class="dropdown-header">
                            <i class="bi bi-star me-2"></i>Aggiungi ai Preferiti
                        </h6></li>
                        @if(isset($tabelle))
                            @php $hasAvailableTables = false; @endphp
                            @foreach($tabelle as $tabella)
                                @if(!$favoriteTablesV2->where('table_objname', $tabella['nome'])->count())
                                    @php $hasAvailableTables = true; @endphp
                                    <li>
                                        <a class="dropdown-item modern-dropdown-item" 
                                           href="#" 
                                           data-table="{{ $tabella['nome'] }}"
                                           data-action="add-favorite">
                                            <div class="dropdown-item-content">
                                                <i class="{{ $tabella['icona'] }} dropdown-item-icon"></i>
                                                <div class="dropdown-item-text">
                                                    <div class="dropdown-item-title">{{ $tabella['titolo'] }}</div>
                                                    <small class="dropdown-item-desc">{{ Str::limit($tabella['descrizione'], 40) }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                            @if(!$hasAvailableTables)
                                <li><span class="dropdown-item-text text-muted ps-3">
                                    <i class="bi bi-check-circle me-2"></i>Tutte le tabelle sono già nei preferiti
                                </span></li>
                            @endif
                        @endif
                    </ul>
                </div>
            </div>
            @endauth
        </div>
        
        <!-- Mostra tabelle frequenti REALI V2 o messaggio vuoto -->
        <div class="favorites-grid" id="favoritesGridV2">
            @if(isset($favoriteTablesV2) && $favoriteTablesV2->count() > 0)
                @foreach($favoriteTablesV2 as $favorite)
                    @if(isset($favorite['table_details']))
                        <div class="favorite-card favorite-card-v2"
                             data-table="{{ $favorite['table_objname'] }}"
                             data-url="{{ $favorite['url'] }}"
                             data-favorite-id="{{ $favorite['id'] }}"
                             style="--card-color-from: {{ $favorite['table_details']['color_from'] }}; --card-color-to: {{ $favorite['table_details']['color_to'] }};">
                            
                            <!-- Pulsante rimozione -->
                            <button class="remove-favorite-btn" 
                                    data-table="{{ $favorite['table_objname'] }}"
                                    title="Rimuovi dai preferiti">
                                <i class="bi bi-x"></i>
                            </button>
                            
                            <!-- Icona -->
                            <div class="favorite-icon">
                                <i class="{{ $favorite['table_details']['icona'] }}"></i>
                            </div>
                            
                            <!-- Nome -->
                            <div class="favorite-name">{{ $favorite['table_details']['nome'] }}</div>
                            
                            <!-- Statistiche Utilizzo REALI -->
                            <div class="favorite-usage">
                                @if($favorite['is_frequent'])
                                    <span class="badge bg-success">
                                        <i class="bi bi-fire"></i> Frequente
                                    </span>
                                @endif
                                <small>{{ $favorite['click_count'] }} utilizzi</small>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="empty-favorites">
                    <i class="bi bi-star"></i>
                    <h5>Nessuna tabella preferita</h5>
                    <p>Le tabelle che usi più spesso appariranno automaticamente qui, oppure aggiungile manualmente!</p>
                    @guest
                        <small class="text-muted">Effettua il login per personalizzare i tuoi preferiti</small>
                    @endguest
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

<!-- Script V2 per gestione preferiti avanzata -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
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
    
    // ========================================
    // GESTIONE PREFERITI V2 - SENZA LIMITI
    // ========================================
    
    // Click handler per le card preferite V2 con tracking
    document.querySelectorAll('.favorite-card-v2').forEach(card => {
        card.addEventListener('click', function(e) {
            // Non fare nulla se si clicca sul pulsante rimuovi
            if (e.target.closest('.remove-favorite-btn')) {
                return;
            }
            
            const url = this.dataset.url;
            const tableObjname = this.dataset.table;
            
            if (url && url !== '#') {
                // Tracking utilizzo automatico V2
                trackTableUsageV2(tableObjname);
                window.location.href = url;
            }
        });
    });
    
    // Dropdown moderno aggiunta preferiti V2
    document.querySelectorAll('[data-action="add-favorite"]').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const tableObjname = this.dataset.table;
            if (tableObjname) {
                // Chiudi il dropdown
                const dropdownButton = document.querySelector('#addFavoriteDropdown');
                if (dropdownButton) {
                    const dropdown = bootstrap.Dropdown.getInstance(dropdownButton);
                    if (dropdown) dropdown.hide();
                }
                
                // Aggiungi ai preferiti
                addToFavoritesV2(tableObjname);
            }
        });
    });
    
    // Fix z-index dropdown quando si apre
    const dropdownButton = document.querySelector('#addFavoriteDropdown');
    if (dropdownButton) {
        dropdownButton.addEventListener('shown.bs.dropdown', function() {
            const dropdownMenu = this.nextElementSibling;
            if (dropdownMenu) {
                dropdownMenu.style.zIndex = '99999';
                dropdownMenu.style.position = 'absolute';
                
                // Forza l'append al body per evitare problemi di stacking context
                if (window.innerWidth > 768) {
                    document.body.appendChild(dropdownMenu);
                    const rect = this.getBoundingClientRect();
                    const viewportHeight = window.innerHeight;
                    
                    // Calcola lo spazio disponibile sotto e sopra il pulsante
                    const spaceBelow = viewportHeight - rect.bottom - 10;
                    const spaceAbove = rect.top - 10;
                    
                    // Altezza massima del dropdown
                    const maxHeight = Math.min(400, Math.max(spaceBelow, spaceAbove) - 20);
                    
                    dropdownMenu.style.position = 'fixed';
                    dropdownMenu.style.maxHeight = maxHeight + 'px';
                    dropdownMenu.style.overflowY = 'auto';
                    
                    // Posiziona sopra o sotto in base allo spazio disponibile
                    if (spaceBelow >= 200 || spaceBelow > spaceAbove) {
                        // Posiziona sotto
                        dropdownMenu.style.top = (rect.bottom + 5) + 'px';
                    } else {
                        // Posiziona sopra
                        dropdownMenu.style.top = (rect.top - maxHeight - 5) + 'px';
                    }
                    
                    dropdownMenu.style.left = (rect.right - dropdownMenu.offsetWidth) + 'px';
                    
                    // Assicurati che non esca dal viewport orizzontalmente
                    setTimeout(() => {
                        const dropdownRect = dropdownMenu.getBoundingClientRect();
                        if (dropdownRect.left < 10) {
                            dropdownMenu.style.left = '10px';
                        }
                        if (dropdownRect.right > window.innerWidth - 10) {
                            dropdownMenu.style.left = (window.innerWidth - dropdownRect.width - 10) + 'px';
                        }
                    }, 10);
                }
            }
        });
        
        dropdownButton.addEventListener('hidden.bs.dropdown', function() {
            const dropdownMenu = document.querySelector('.modern-dropdown');
            if (dropdownMenu && dropdownMenu.parentNode === document.body) {
                // Rimetti il dropdown nel suo container originale
                const container = document.querySelector('.add-favorite-section-modern .dropdown');
                if (container) {
                    container.appendChild(dropdownMenu);
                    dropdownMenu.style.position = '';
                    dropdownMenu.style.top = '';
                    dropdownMenu.style.left = '';
                }
            }
        });
    }
    
    // Pulsanti rimozione preferiti V2
    document.querySelectorAll('.remove-favorite-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation(); // Impedisce il click sulla card
            const tableObjname = this.dataset.table;
            removeFromFavoritesV2(tableObjname);
        });
    });
    
    // ========================================
    // FUNZIONI API V2
    // ========================================
    
    function addToFavoritesV2(tableObjname) {
        fetch('{{ route("configurations.gestione-tabelle.favorites-v2.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                table_objname: tableObjname
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showToast('Tabella aggiunta ai preferiti!', 'success');
                setTimeout(() => location.reload(), 1000); // Ricarica per aggiornare UI
            } else {
                showToast(data.message || 'Errore durante aggiunta', 'error');
            }
        })
        .catch(error => {
            console.error('Errore aggiunta preferito:', error);
            showToast('Errore durante aggiunta: ' + error.message, 'error');
        });
    }
    
    function removeFromFavoritesV2(tableObjname) {
        fetch('{{ route("configurations.gestione-tabelle.favorites-v2.remove") }}', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                table_objname: tableObjname
            })
        })
        .then(response => {
            // Controlla se la risposta è OK prima di parsare JSON
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Rimuovi la card dalla UI - selettore più specifico per i preferiti
                const cardToRemove = document.querySelector(`#favoritesGridV2 .favorite-card-v2[data-table="${tableObjname}"]`);
                if (cardToRemove) {
                    cardToRemove.style.animation = 'fadeOut 0.3s ease-out';
                    setTimeout(() => {
                        cardToRemove.remove();
                        
                        // Se non ci sono più preferiti, mostra messaggio vuoto
                        const favoritesGrid = document.getElementById('favoritesGridV2');
                        if (favoritesGrid && favoritesGrid.querySelectorAll('.favorite-card-v2').length === 0) {
                            location.reload(); // Ricarica per mostrare messaggio vuoto
                        }
                    }, 300);
                }
                showToast('Tabella rimossa dai preferiti', 'info');
            } else {
                showToast(data.message || 'Errore durante rimozione', 'error');
            }
        })
        .catch(error => {
            console.error('Errore rimozione preferito:', error);
            showToast('Errore durante rimozione: ' + error.message, 'error');
        });
    }
    
    function trackTableUsageV2(tableObjname) {
        // Tracking asincrono - non blocca la navigazione
        fetch('{{ route("configurations.gestione-tabelle.favorites-v2.track") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                table_objname: tableObjname
            })
        }).catch(error => {
            // Silente - il tracking non deve mai bloccare l'utente
            console.warn('Tracking failed:', error);
        });
    }
    
    // Sistema notifiche toast
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(toast);
        
        // Auto-dismiss dopo 3 secondi
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, 3000);
    }
});

// CSS per animazioni
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        from { opacity: 1; transform: scale(1); }
        to { opacity: 0; transform: scale(0.8); }
    }
    
    .remove-favorite-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(220, 53, 69, 0.9);
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        color: white;
        font-size: 12px;
        cursor: pointer;
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 10;
    }
    
    .favorite-card-v2:hover .remove-favorite-btn {
        opacity: 1;
    }
    
    .remove-favorite-btn:hover {
        background: #dc3545;
        transform: scale(1.1);
    }
    
    .favorite-card-v2 {
        position: relative;
        cursor: pointer;
    }
    
    .smart-badge {
        background: linear-gradient(45deg, #ff6b6b, #ee5a24);
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-left: 0.5rem;
    }
    
    /* ============================= */
    /* DROPDOWN MODERNO E RESPONSIVE */
    /* ============================= */
    
    .add-favorite-section-modern {
        position: relative;
        z-index: 1000;
    }
    
    .add-favorite-section-modern .dropdown {
        position: relative;
        z-index: 1001;
    }
    
    .btn-add-favorite {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 8px 16px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        text-transform: none;
    }
    
    .btn-add-favorite:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        color: white;
    }
    
    .btn-add-favorite:focus {
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
        color: white;
    }
    
    .modern-dropdown {
        background: rgba(255, 255, 255, 0.98) !important;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(102, 126, 234, 0.2) !important;
        border-radius: 15px !important;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15) !important;
        padding: 8px 0;
        min-width: 320px;
        max-width: 400px;
        margin-top: 8px;
    }
    
    /* Stile quando il dropdown è attaccato al body (desktop) */
    body > .modern-dropdown {
        z-index: 99999 !important;
        position: fixed !important;
        background: rgba(255, 255, 255, 0.98) !important;
        backdrop-filter: blur(10px) !important;
        border: 1px solid rgba(102, 126, 234, 0.2) !important;
        border-radius: 15px !important;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15) !important;
        max-height: 400px !important;
        overflow-y: auto !important;
        padding: 8px 0 !important;
        min-width: 320px !important;
        max-width: 400px !important;
    }
    
    /* Scroll personalizzato per il dropdown */
    .modern-dropdown::-webkit-scrollbar {
        width: 6px;
    }
    
    .modern-dropdown::-webkit-scrollbar-track {
        background: rgba(102, 126, 234, 0.1);
        border-radius: 3px;
    }
    
    .modern-dropdown::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 3px;
    }
    
    .modern-dropdown::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }
    
    .dropdown-header {
        color: #667eea !important;
        font-weight: 700;
        font-size: 0.9rem;
        padding: 12px 20px 8px;
        margin-bottom: 5px;
        border-bottom: 1px solid rgba(102, 126, 234, 0.1);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .modern-dropdown-item {
        padding: 12px 20px;
        transition: all 0.2s ease;
        border: none;
        color: #333;
        text-decoration: none;
    }
    
    .modern-dropdown-item:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        color: #667eea;
        transform: translateX(5px);
    }
    
    .dropdown-item-content {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .dropdown-item-icon {
        font-size: 1.2rem;
        color: #667eea;
        width: 24px;
        text-align: center;
        flex-shrink: 0;
    }
    
    .dropdown-item-text {
        flex: 1;
        min-width: 0;
    }
    
    .dropdown-item-title {
        font-weight: 600;
        font-size: 0.9rem;
        color: #333;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .dropdown-item-desc {
        color: #666;
        font-size: 0.75rem;
        line-height: 1.3;
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* ============================= */
    /* RESPONSIVE DESIGN */
    /* ============================= */
    
    @media (max-width: 768px) {
        .add-favorite-section-modern {
            position: static;
        }
        
        .btn-add-favorite {
            padding: 10px 16px;
            font-size: 0.8rem;
            border-radius: 20px;
            width: 100%;
            justify-content: center;
        }
        
        .add-text {
            display: inline !important;
        }
        
        .modern-dropdown {
            position: fixed !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            width: 90vw !important;
            max-width: 350px !important;
            max-height: 70vh;
            overflow-y: auto;
            z-index: 9999;
            margin: 0 !important;
        }
        
        .dropdown-item-content {
            flex-direction: column;
            text-align: center;
            gap: 8px;
        }
        
        .dropdown-item-icon {
            font-size: 1.5rem;
        }
        
        .dropdown-item-title {
            white-space: normal;
            text-align: center;
        }
        
        .dropdown-item-desc {
            white-space: normal;
            text-align: center;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .favorites-section {
            margin-bottom: 1rem;
        }
        
        .d-flex.justify-content-between {
            flex-direction: column !important;
            gap: 15px;
        }
        
        .favorites-title {
            text-align: center !important;
            font-size: 1.5rem !important;
        }
    }
    
    @media (max-width: 480px) {
        .btn-add-favorite {
            padding: 12px 20px;
            font-size: 0.9rem;
        }
        
        .add-text::before {
            content: "Aggiungi Preferito";
        }
        
        .add-text {
            display: none;
        }
        
        .modern-dropdown {
            width: 95vw !important;
        }
        
        .dropdown-header {
            font-size: 0.8rem;
            padding: 10px 15px 6px;
        }
        
        .modern-dropdown-item {
            padding: 15px;
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection