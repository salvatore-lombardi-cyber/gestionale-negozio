@extends('layouts.app')

@section('title', 'Fornitori - Gestionale Negozio')

@section('content')
<style>
    .suppliers-container {
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
    
    .search-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .search-box {
        position: relative;
    }
    
    .search-input {
        width: 100%;
        padding: 15px 50px 15px 20px;
        border: 2px solid rgba(102, 126, 234, 0.2);
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
    }
    
    .search-input:focus {
        outline: none;
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        background: white;
    }
    
    .search-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #029D7E;
        font-size: 1.2rem;
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
        transition: all 0.3s ease;
    }
    
    .table-responsive {
        border-radius: 20px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .modern-table {
        margin: 0;
        border: none;
        background: transparent;
    }
    
    .modern-table thead th {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        color: white;
        font-weight: 600;
        border: none;
        padding: 1rem;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
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
    
    .action-btn {
        background: none;
        border: none;
        padding: 6px 10px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.8rem;
        margin: 0 2px;
    }
    
    .action-btn.view {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
    }
    
    .action-btn.edit {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
    }
    
    .action-btn.delete {
        background: linear-gradient(135deg, #f72585, #c5025a);
        color: white;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
    
    .supplier-badge {
        padding: 4px 8px;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .supplier-badge.strategico {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .supplier-badge.preferito {
        background: linear-gradient(135deg, #007bff, #6610f2);
        color: white;
    }
    
    .supplier-badge.standard {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
    }
    
    .supplier-badge.occasionale {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        color: white;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #029D7E;
        margin-bottom: 1rem;
        opacity: 0.7;
    }
    
    .empty-state h5 {
        margin-bottom: 1rem;
        font-weight: 600;
    }
    
    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .stat-label {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 600;
        margin-top: 0.5rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .suppliers-container {
            padding: 1rem;
        }
        
        .page-title {
            font-size: 2rem;
        }
        
        .modern-table thead th,
        .modern-table tbody td {
            padding: 0.5rem;
            font-size: 0.8rem;
        }
        
        .action-btn {
            padding: 4px 6px;
            font-size: 0.7rem;
        }
    }
</style>

<div class="suppliers-container">
    <!-- Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">
                <i class="bi bi-building"></i> Fornitori
            </h1>
            <div>
                <a href="{{ route('anagrafiche.index') }}" class="btn btn-secondary modern-btn me-2">
                    <i class="bi bi-arrow-left"></i> Torna Indietro
                </a>
                <a href="{{ route('fornitori.create') }}" class="modern-btn">
                    <i class="bi bi-plus-circle"></i> Nuovo Fornitore
                </a>
            </div>
        </div>
    </div>
    
    <!-- Statistiche Dashboard -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3 class="stat-number">{{ $stats['totale'] }}</h3>
            <p class="stat-label">Totale Fornitori</p>
        </div>
        <div class="stat-card">
            <h3 class="stat-number">{{ $stats['attivi'] }}</h3>
            <p class="stat-label">Fornitori Attivi</p>
        </div>
        <div class="stat-card">
            <h3 class="stat-number">{{ $stats['strategici'] }}</h3>
            <p class="stat-label">Strategici</p>
        </div>
        <div class="stat-card">
            <h3 class="stat-number">{{ $stats['necessitano_verifica'] }}</h3>
            <p class="stat-label">Da Verificare</p>
        </div>
    </div>
    
    <!-- Barra di Ricerca Avanzata -->
    <div class="search-container">
        <form method="GET" action="{{ route('fornitori.index') }}" id="searchForm">
            <div class="search-box">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cerca fornitori per nome, P.IVA, email, telefono..." 
                       class="search-input">
                <i class="bi bi-search search-icon"></i>
            </div>
            
            <!-- Filtri Avanzati -->
            <div class="filter-chips">
                <button type="button" class="filter-chip {{ request('tipo_soggetto') == 'persona_giuridica' ? 'active' : '' }}" 
                        onclick="toggleFilter('tipo_soggetto', 'persona_giuridica')">
                    Aziende
                </button>
                <button type="button" class="filter-chip {{ request('tipo_soggetto') == 'persona_fisica' ? 'active' : '' }}" 
                        onclick="toggleFilter('tipo_soggetto', 'persona_fisica')">
                    Persone Fisiche
                </button>
                <button type="button" class="filter-chip {{ request('classe_fornitore') == 'strategico' ? 'active' : '' }}" 
                        onclick="toggleFilter('classe_fornitore', 'strategico')">
                    Strategici
                </button>
                <button type="button" class="filter-chip {{ request('solo_attivi') ? 'active' : '' }}" 
                        onclick="toggleFilter('solo_attivi', '1')">
                    Solo Attivi
                </button>
                @if(request()->hasAny(['search', 'tipo_soggetto', 'classe_fornitore', 'solo_attivi']))
                    <a href="{{ route('fornitori.index') }}" class="filter-chip" style="background: #dc3545;">
                        <i class="bi bi-x"></i> Reset
                    </a>
                @endif
            </div>
            
            <!-- Campi nascosti per filtri -->
            <input type="hidden" name="tipo_soggetto" id="tipo_soggetto" value="{{ request('tipo_soggetto') }}">
            <input type="hidden" name="classe_fornitore" id="classe_fornitore" value="{{ request('classe_fornitore') }}">
            <input type="hidden" name="solo_attivi" id="solo_attivi" value="{{ request('solo_attivi') }}">
        </form>
    </div>
    
    <!-- Tabella Fornitori -->
    @if(count($fornitori) > 0)
    <div class="modern-card">
        <div class="table-responsive">
            <table class="table modern-table">
                <thead>
                    <tr>
                        <th>Fornitore</th>
                        <th>Contatti</th>
                        <th>P.IVA / CF</th>
                        <th>Località</th>
                        <th>Classe</th>
                        <th>Stato</th>
                        <th class="text-center">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fornitori as $fornitore)
                    <tr>
                        <td>
                            <div>
                                <strong>{{ $fornitore->nome_completo }}</strong>
                                @if($fornitore->tipo_soggetto == 'ente_pubblico')
                                    <span class="badge bg-info ms-1">Ente Pubblico</span>
                                @endif
                            </div>
                            @if($fornitore->categoria_merceologica)
                                <small class="text-muted">{{ $fornitore->categoria_merceologica }}</small>
                            @endif
                        </td>
                        <td>
                            @if($fornitore->email)
                                <div><i class="bi bi-envelope text-muted me-1"></i>{{ $fornitore->email }}</div>
                            @endif
                            @if($fornitore->telefono)
                                <div><i class="bi bi-telephone text-muted me-1"></i>{{ $fornitore->telefono }}</div>
                            @endif
                        </td>
                        <td>
                            @if($fornitore->partita_iva)
                                <div><strong>P.IVA:</strong> {{ $fornitore->partita_iva }}</div>
                            @endif
                            @if($fornitore->codice_fiscale)
                                <div><strong>CF:</strong> {{ $fornitore->codice_fiscale }}</div>
                            @endif
                        </td>
                        <td>
                            @if($fornitore->citta)
                                {{ $fornitore->citta }}
                                @if($fornitore->provincia)
                                    ({{ $fornitore->provincia }})
                                @endif
                            @else
                                <span class="text-muted">Non specificata</span>
                            @endif
                        </td>
                        <td>
                            <span class="supplier-badge {{ $fornitore->classe_fornitore }}">
                                {{ ucfirst($fornitore->classe_fornitore) }}
                            </span>
                        </td>
                        <td>
                            @if($fornitore->attivo)
                                <span class="badge bg-success">Attivo</span>
                            @else
                                <span class="badge bg-secondary">Non Attivo</span>
                            @endif
                            
                            @if($fornitore->necessitaAggiornamento())
                                <div><small class="text-warning">
                                    <i class="bi bi-exclamation-triangle"></i> Da verificare
                                </small></div>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('fornitori.show', $fornitore) }}" class="action-btn view" title="Visualizza">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('fornitori.edit', $fornitore) }}" class="action-btn edit" title="Modifica">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('fornitori.destroy', $fornitore) }}" style="display: inline;" 
                                      onsubmit="return confirm('Sei sicuro di voler eliminare questo fornitore?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete" title="Elimina">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Paginazione -->
    <div class="d-flex justify-content-center mt-4">
        {{ $fornitori->links() }}
    </div>
    
    @else
    <div class="modern-card">
        <div class="empty-state">
            <i class="bi bi-building"></i>
            <h5>Nessun fornitore trovato</h5>
            <p>Inizia aggiungendo il primo fornitore utilizzando il pulsante in alto</p>
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit ricerca
    const searchInput = document.querySelector('.search-input');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('searchForm').submit();
        }, 500);
    });
});

function toggleFilter(name, value) {
    const input = document.getElementById(name);
    const currentValue = input.value;
    
    if (currentValue === value) {
        input.value = '';
    } else {
        input.value = value;
    }
    
    document.getElementById('searchForm').submit();
}
</script>
@endsection