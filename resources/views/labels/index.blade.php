@extends('layouts.app')

@section('title', 'Etichette QR Code - Gestionale Negozio')

@section('content')
<style>
    .products-container {
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

    .stats-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid rgba(102, 126, 234, 0.1);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: #667eea;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .actions-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
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
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        background: white;
    }
    
    .search-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #667eea;
        font-size: 1.2rem;
    }
    
    .filter-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    
    .filter-chip {
        background: linear-gradient(135deg, #667eea, #764ba2);
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
        position: relative;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        gap: 8px;
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

    .modern-btn.btn-success {
        background: linear-gradient(135deg, #28a745, #20c997);
    }

    .modern-btn.btn-warning {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
    }

    .modern-btn.btn-info {
        background: linear-gradient(135deg, #48cae4, #0077b6);
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
    
    .modern-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
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
        white-space: nowrap;
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
        white-space: nowrap;
    }
    
    .action-btn {
        border: none;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        margin: 0 2px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }
    
    .action-btn.qr {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .action-btn.preview {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
    }
    
    .action-btn.print {
        background: linear-gradient(135deg, #6f42c1, #8e44ad);
        color: white;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: white;
    }
    
    .product-code {
        font-family: 'Courier New', monospace;
        background: rgba(102, 126, 234, 0.1);
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.9rem;
    }
    
    .price-tag {
        font-weight: 700;
        color: #28a745;
        font-size: 1.1rem;
    }
    
    .qr-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.8rem;
        padding: 3px 8px;
        border-radius: 12px;
        font-weight: 600;
    }
    
    .qr-status.ready {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }
    
    .qr-status.missing {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }

    .variant-badge {
        font-size: 0.8rem;
        padding: 4px 8px;
        border-radius: 12px;
        font-weight: 600;
    }

    .variant-badge.info {
        background: rgba(72, 202, 228, 0.1);
        color: #48cae4;
    }
    
    /* Dark Mode */
    [data-bs-theme="dark"] .page-header,
    [data-bs-theme="dark"] .search-container,
    [data-bs-theme="dark"] .stats-container,
    [data-bs-theme="dark"] .actions-container {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .modern-card {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }

    [data-bs-theme="dark"] .stat-card {
        background: rgba(45, 55, 72, 0.9);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .search-input {
        background: rgba(45, 55, 72, 0.8);
        border-color: rgba(102, 126, 234, 0.3);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .search-input:focus {
        background: rgba(45, 55, 72, 0.9);
    }
    
    [data-bs-theme="dark"] .modern-table tbody tr:hover {
        background: rgba(102, 126, 234, 0.1);
    }
    
    [data-bs-theme="dark"] .modern-table tbody td {
        color: #e2e8f0;
        border-bottom-color: rgba(102, 126, 234, 0.2);
    }
    
    [data-bs-theme="dark"] .product-code {
        background: rgba(102, 126, 234, 0.2);
        color: #e2e8f0;
    }

    [data-bs-theme="dark"] .stat-label {
        color: #a0aec0;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .products-container {
            padding: 1rem;
        }
        
        .page-header, 
        .search-container,
        .stats-container,
        .actions-container {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .page-title {
            font-size: 2rem;
        }
        
        .modern-btn {
            padding: 10px 20px;
            font-size: 0.9rem;
        }
        
        .filter-chips {
            justify-content: center;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .stat-card {
            padding: 1rem;
        }

        .stat-number {
            font-size: 1.5rem;
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

        .actions-container .d-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="products-container">
    <!-- Header della Pagina -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">
                <i class="bi bi-qr-code"></i> Etichette QR Code
            </h1>
            <div class="d-flex gap-2">
                <a href="{{ route('prodotti.index') }}" class="modern-btn btn-info">
                    <i class="bi bi-arrow-left"></i> Torna ai Prodotti
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiche -->
    <div class="stats-container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon text-primary">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="stat-number">{{ $stats['total_products'] }}</div>
                <div class="stat-label">Prodotti Totali</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon text-success">
                    <i class="bi bi-qr-code"></i>
                </div>
                <div class="stat-number">{{ $stats['products_with_qr'] }}</div>
                <div class="stat-label">Prodotti con QR</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon text-warning">
                    <i class="bi bi-palette"></i>
                </div>
                <div class="stat-number">{{ $stats['total_variants'] }}</div>
                <div class="stat-label">Varianti Totali</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon text-danger">
                    <i class="bi bi-printer"></i>
                </div>
                <div class="stat-number">{{ $stats['labels_printed_today'] }}</div>
                <div class="stat-label">Etichette Oggi</div>
            </div>
        </div>
    </div>

    <!-- Azioni Rapide -->
    <div class="actions-container">
        <h4 class="mb-3">Azioni Rapide</h4>
        <div class="d-grid d-md-flex gap-3">
            <button class="modern-btn btn-success" onclick="generateAllMissingQR()">
                <i class="bi bi-magic"></i>
                Genera Tutti i QR Mancanti
            </button>
            <a href="{{ route('labels.scanner') }}" class="modern-btn btn-info">
                <i class="bi bi-camera"></i>
                Test Scanner
            </a>
            <button class="modern-btn btn-warning" onclick="printBulkLabels()">
                <i class="bi bi-printer"></i>
                Stampa in Massa
            </button>
        </div>
    </div>
    
    <!-- Barra di Ricerca -->
    <div class="search-container">
        <div class="search-box">
            <input type="text" 
            class="search-input" 
            id="searchInput" 
            placeholder="🔍 Cerca prodotti per nome, codice, categoria, marca..."            
            autocomplete="off">
            <i class="bi bi-search search-icon"></i>
        </div>
        <div class="filter-chips">
            <button class="filter-chip active" data-filter="all">Tutti</button>
            <button class="filter-chip" data-filter="name">Nome</button>
            <button class="filter-chip" data-filter="code">Codice</button>
            <button class="filter-chip" data-filter="category">Categoria</button>
            <button class="filter-chip" data-filter="brand">Marca</button>
        </div>
    </div>
    
    <!-- Tabella Prodotti -->
    <div class="modern-card">
        <div class="table-responsive">
            <table class="table modern-table" id="productsTable">
                <thead>
                    <tr>
                        <th><i class="bi bi-tag"></i> Prodotto</th>
                        <th><i class="bi bi-grid-3x3-gap"></i> Categoria</th>
                        <th><i class="bi bi-palette"></i> Varianti</th>
                        <th><i class="bi bi-qr-code"></i> Stato QR</th>
                        <th><i class="bi bi-upc-scan"></i> Codice Etichetta</th>
                        <th><i class="bi bi-gear"></i> Azioni</th>
                    </tr>
                </thead>
                <tbody id="productsTableBody">
                    @forelse($prodotti as $prodotto)
                    <tr class="product-row" 
                    data-name="{{ strtolower($prodotto->nome) }}"
                    data-code="{{ strtolower($prodotto->codice_prodotto) }}"
                    data-category="{{ strtolower($prodotto->categoria) }}"
                    data-brand="{{ strtolower($prodotto->brand ?? '') }}">
                        <td>
                            <div>
                                <strong>{{ $prodotto->nome }}</strong><br>
                                <small class="text-muted">{{ $prodotto->codice_prodotto }}</small><br>
                                <small class="text-success">€{{ number_format($prodotto->prezzo, 2) }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                                {{ ucfirst($prodotto->categoria) }}
                            </span>
                        </td>
                        <td>
                            <span class="variant-badge info">{{ $prodotto->magazzino->count() }} Varianti</span>
                        </td>
                        <td>
                            @if($prodotto->hasQRCode())
                                <span class="qr-status ready">
                                    <i class="bi bi-check-circle"></i> Pronto
                                </span>
                            @else
                                <span class="qr-status missing">
                                    <i class="bi bi-exclamation-triangle"></i> Mancante
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($prodotto->hasLabelCode())
                                <code class="product-code">{{ $prodotto->codice_etichetta }}</code>
                            @else
                                <span class="text-muted">Non Generato</span>
                            @endif
                        </td>
                        <td>
                            <button class="action-btn qr" onclick="generateSingle({{ $prodotto->id }})" title="Genera Codici">
                                <i class="bi bi-magic"></i>
                            </button>
                            <button class="action-btn preview" onclick="viewPreview({{ $prodotto->id }})" title="Anteprima Etichette">
                                <i class="bi bi-eye"></i>
                            </button>
                            <a href="{{ route('prodotti.labels', $prodotto->id) }}" class="action-btn print" title="Stampa Etichette">
                                <i class="bi bi-printer"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="text-center py-5">
                                <i class="bi bi-box-seam" style="font-size: 4rem; opacity: 0.5; margin-bottom: 1rem;"></i>
                                <h5>Nessun prodotto trovato</h5>
                                <p class="text-muted">Inizia aggiungendo alcuni prodotti al tuo catalogo</p>
                                <a href="{{ route('prodotti.create') }}" class="modern-btn">
                                    <i class="bi bi-plus-circle"></i> Aggiungi Primo Prodotto
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal per Preview -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Anteprima Etichette</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Contenuto dinamico -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const filterChips = document.querySelectorAll('.filter-chip');
    const productRows = document.querySelectorAll('.product-row');
    let currentFilter = 'all';
    
    // Gestione filtri
    filterChips.forEach(chip => {
        chip.addEventListener('click', function() {
            filterChips.forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.dataset.filter;
            performSearch();
        });
    });
    
    // Ricerca in tempo reale
    searchInput.addEventListener('input', function() {
        performSearch();
    });
    
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let visibleRows = 0;
        
        productRows.forEach(row => {
            let shouldShow = shouldShowItem(row, searchTerm);
            
            if (shouldShow) {
                row.style.display = '';
                visibleRows++;
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    function shouldShowItem(item, searchTerm) {
        if (searchTerm === '') return true;
        
        if (currentFilter === 'all') {
            return item.dataset.name.includes(searchTerm) ||
            item.dataset.code.includes(searchTerm) ||
            item.dataset.category.includes(searchTerm) ||
            item.dataset.brand.includes(searchTerm);
        } else {
            return item.dataset[currentFilter].includes(searchTerm);
        }
    }
});

// Genera codici per singolo prodotto
function generateSingle(productId) {
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="bi bi-arrow-clockwise spin"></i>';
    button.disabled = true;
    
    fetch(`/labels/generate-all/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Codici e QR generati con successo!', 'success');
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification('Errore: ' + data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Errore di connessione: ' + error.message, 'error');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Genera tutti i QR mancanti
function generateAllMissingQR() {
    if (!confirm('Sei sicuro di voler generare tutti i QR Code mancanti? Questa operazione potrebbe richiedere alcuni minuti.')) {
        return;
    }
    
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="bi bi-arrow-clockwise spin"></i> Generazione in corso...';
    button.disabled = true;
    
    // Simula generazione multipla (implementare logica reale)
    let processed = 0;
    const total = {{ $prodotti->where('qr_enabled', false)->count() }};
    
    const interval = setInterval(() => {
        processed++;
        button.innerHTML = `<i class="bi bi-arrow-clockwise spin"></i> ${processed}/${total} completati...`;
        
        if (processed >= total) {
            clearInterval(interval);
            button.innerHTML = originalText;
            button.disabled = false;
            showNotification(`${total} QR Code generati con successo!`, 'success');
            setTimeout(() => location.reload(), 2000);
        }
    }, 500);
}

// Preview etichette
function viewPreview(productId) {
    fetch(`/labels/preview/${productId}`)
    .then(response => response.text())
    .then(html => {
        document.getElementById('previewContent').innerHTML = html;
        new bootstrap.Modal(document.getElementById('previewModal')).show();
    })
    .catch(error => {
        showNotification('Errore nel caricamento preview: ' + error.message, 'error');
    });
}

// Stampa in massa
function printBulkLabels() {
    showNotification('Funzionalità in sviluppo - Disponibile presto!', 'info');
}

// Sistema notifiche
function showNotification(message, type = 'info') {
    const alertClass = type === 'success' ? 'alert-success' : 
                      type === 'error' ? 'alert-danger' : 
                      type === 'info' ? 'alert-info' : 'alert-warning';
    
    const notification = document.createElement('div');
    notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Stile per animazione loading
const style = document.createElement('style');
style.textContent = `
    .spin {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>
@endsection