@extends('layouts.app')

@section('title', 'Vettori - Gestionale Negozio')

@section('content')
<style>
    .carriers-container {
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
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #029D7E;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 500;
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
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .carrier-badge {
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .carrier-badge.premium {
        background: linear-gradient(135deg, #ffd700, #ffa500);
        color: white;
    }
    
    .carrier-badge.standard {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
    }
    
    .carrier-badge.economico {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .carrier-badge.occasionale {
        background: linear-gradient(135deg, #17a2b8, #138496);
        color: white;
    }
    
    .rating-stars {
        color: #ffc107;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .carriers-container {
            padding: 1rem;
        }
        
        .page-title {
            font-size: 2rem;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .search-container,
        .page-header {
            padding: 1rem;
        }
    }
</style>

<div class="carriers-container">
    <!-- Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-truck"></i> Gestione Vettori
                </h1>
                <p class="text-muted mt-2 mb-0">Sistema enterprise per gestione spedizionieri e corrieri</p>
            </div>
            <div>
                <a href="{{ route('anagrafiche.index') }}" class="btn btn-secondary modern-btn me-2">
                    <i class="bi bi-arrow-left"></i> Torna Indietro
                </a>
                <a href="{{ route('vettori.create') }}" class="modern-btn">
                    <i class="bi bi-plus-circle"></i> Nuovo Vettore
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiche Dashboard -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['totale'] }}</div>
            <div class="stat-label">Vettori Totali</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['attivi'] }}</div>
            <div class="stat-label">Attivi</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['premium'] }}</div>
            <div class="stat-label">Premium</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['corrieri_express'] }}</div>
            <div class="stat-label">Corrieri Express</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['rating_alto'] }}</div>
            <div class="stat-label">Rating ≥ 4.0</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['necessitano_verifica'] }}</div>
            <div class="stat-label">Da Verificare</div>
        </div>
    </div>

    <!-- Ricerca e Filtri -->
    <div class="search-container">
        <form method="GET" action="{{ route('vettori.index') }}">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="search-box">
                        <input type="text" name="search" class="search-input" 
                               placeholder="Cerca per ragione sociale, codice, email..." 
                               value="{{ request('search') }}">
                        <i class="bi bi-search search-icon"></i>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <select name="tipo_vettore" class="form-control search-input">
                        <option value="">Tutti i Tipi</option>
                        <option value="corriere_espresso" {{ request('tipo_vettore') == 'corriere_espresso' ? 'selected' : '' }}>Corriere Express</option>
                        <option value="trasporto_standard" {{ request('tipo_vettore') == 'trasporto_standard' ? 'selected' : '' }}>Trasporto Standard</option>
                        <option value="trasporto_pesante" {{ request('tipo_vettore') == 'trasporto_pesante' ? 'selected' : '' }}>Trasporto Pesante</option>
                        <option value="logistica_integrata" {{ request('tipo_vettore') == 'logistica_integrata' ? 'selected' : '' }}>Logistica Integrata</option>
                        <option value="posta_ordinaria" {{ request('tipo_vettore') == 'posta_ordinaria' ? 'selected' : '' }}>Posta Ordinaria</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <select name="classe_vettore" class="form-control search-input">
                        <option value="">Tutte le Classi</option>
                        <option value="premium" {{ request('classe_vettore') == 'premium' ? 'selected' : '' }}>Premium</option>
                        <option value="standard" {{ request('classe_vettore') == 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="economico" {{ request('classe_vettore') == 'economico' ? 'selected' : '' }}>Economico</option>
                        <option value="occasionale" {{ request('classe_vettore') == 'occasionale' ? 'selected' : '' }}>Occasionale</option>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3 mb-3">
                    <input type="text" name="provincia" class="form-control search-input" 
                           placeholder="Provincia (es. MI)" value="{{ request('provincia') }}" maxlength="2">
                </div>
                <div class="col-md-3 mb-3">
                    <input type="text" name="citta" class="form-control search-input" 
                           placeholder="Città" value="{{ request('citta') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <select name="rating_minimo" class="form-control search-input">
                        <option value="">Rating Minimo</option>
                        <option value="4.5" {{ request('rating_minimo') == '4.5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 4.5+</option>
                        <option value="4.0" {{ request('rating_minimo') == '4.0' ? 'selected' : '' }}>⭐⭐⭐⭐ 4.0+</option>
                        <option value="3.5" {{ request('rating_minimo') == '3.5' ? 'selected' : '' }}>⭐⭐⭐ 3.5+</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3 d-flex align-items-center">
                    <div class="form-check">
                        <input type="checkbox" name="solo_attivi" value="1" class="form-check-input" 
                               {{ request('solo_attivi') ? 'checked' : '' }}>
                        <label class="form-check-label">Solo Attivi</label>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <button type="submit" class="modern-btn">
                    <i class="bi bi-search"></i> Cerca
                </button>
                <div>
                    <a href="{{ route('vettori.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                    <a href="{{ route('vettori.export-csv', request()->all()) }}" class="btn btn-outline-success ms-2">
                        <i class="bi bi-download"></i> Export CSV
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabella Vettori -->
    @if($vettori->count() > 0)
    <div class="modern-card">
        <div class="table-responsive">
            <table class="table modern-table">
                <thead>
                    <tr>
                        <th>Codice</th>
                        <th>Ragione Sociale</th>
                        <th>Tipo</th>
                        <th>Classe</th>
                        <th>Città</th>
                        <th>Valutazione</th>
                        <th>Stato</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vettori as $vettore)
                    <tr>
                        <td>
                            <strong>{{ $vettore->codice_vettore }}</strong>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $vettore->nome_completo }}</div>
                            @if($vettore->email)
                                <small class="text-muted">{{ $vettore->email }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">
                                {{ ucfirst(str_replace('_', ' ', $vettore->tipo_vettore)) }}
                            </span>
                        </td>
                        <td>
                            <span class="carrier-badge {{ $vettore->classe_vettore }}">
                                {{ ucfirst($vettore->classe_vettore) }}
                            </span>
                        </td>
                        <td>
                            @if($vettore->citta)
                                {{ $vettore->citta }}
                                @if($vettore->provincia)
                                    <small class="text-muted">({{ $vettore->provincia }})</small>
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($vettore->valutazione)
                                <div class="rating-stars">
                                    {{ str_repeat('⭐', (int) round($vettore->valutazione)) }}
                                </div>
                                <small class="text-muted">{{ number_format($vettore->valutazione, 1) }}/5</small>
                            @else
                                <span class="text-muted">Nessuna valutazione</span>
                            @endif
                        </td>
                        <td>
                            @if($vettore->attivo)
                                <span class="badge bg-success">Attivo</span>
                            @else
                                <span class="badge bg-secondary">Non Attivo</span>
                            @endif
                            @if(!$vettore->verificato)
                                <span class="badge bg-warning text-dark">Da Verificare</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('vettori.show', $vettore) }}" 
                                   class="action-btn view" title="Visualizza">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('vettori.edit', $vettore) }}" 
                                   class="action-btn edit" title="Modifica">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="action-btn delete" 
                                        title="Elimina"
                                        onclick="confirmDelete('{{ $vettore->nome_completo }}', '{{ route('vettori.destroy', $vettore) }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginazione -->
    @if($vettori->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $vettori->links() }}
    </div>
    @endif

    @else
    <!-- Stato vuoto -->
    <div class="modern-card">
        <div class="empty-state">
            <i class="bi bi-truck"></i>
            <h3>Nessun vettore trovato</h3>
            <p>Non ci sono vettori che corrispondono ai criteri di ricerca.</p>
        </div>
    </div>
    @endif
</div>

<!-- Modal Conferma Eliminazione -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Conferma Eliminazione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Sei sicuro di voler eliminare il vettore <strong id="vettoreNome"></strong>?</p>
                <p class="text-warning"><i class="bi bi-exclamation-triangle"></i> Questa azione non può essere annullata.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <form method="POST" id="deleteForm" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Elimina</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(nomeVettore, deleteUrl) {
    document.getElementById('vettoreNome').textContent = nomeVettore;
    document.getElementById('deleteForm').action = deleteUrl;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Auto-submit form su cambio filtri
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('select[name="tipo_vettore"], select[name="classe_vettore"], select[name="rating_minimo"]');
    const checkbox = document.querySelector('input[name="solo_attivi"]');
    
    selects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
    
    if (checkbox) {
        checkbox.addEventListener('change', function() {
            this.form.submit();
        });
    }
});
</script>

@endsection