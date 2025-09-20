@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .management-container {
        padding: 2rem;
        min-height: calc(100vh - 70px);
    }
    
    /* Header con pulsanti */
    .management-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .management-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
        display: flex;
        align-items: center;
    }
    
    /* Pulsanti modern-btn coerenti */
    .modern-btn {
        padding: 12px 24px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .modern-btn:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(2, 157, 126, 0.3);
    }
    
    .modern-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    /* Gradiente GREEN standard per tutti i button */
    .modern-btn.btn-primary,
    .btn-primary.modern-btn {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        border: none;
    }
    
    .modern-btn.btn-secondary,
    .btn-secondary.modern-btn {
        background: linear-gradient(135deg, #6c757d, #545b62);
        color: white;
        border: none;
    }
    
    .modern-btn.btn-warning,
    .btn-warning.modern-btn {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        color: #212529;
        border: none;
    }
    
    .modern-btn.btn-danger,
    .btn-danger.modern-btn {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        border: none;
    }
    
    /* Details container */
    .details-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .details-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }
    
    .details-title i {
        margin-right: 0.5rem;
        color: #029D7E;
    }
    
    /* Detail fields */
    .detail-field {
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: rgba(2, 157, 126, 0.05);
        border-radius: 12px;
        border-left: 4px solid #029D7E;
    }
    
    .detail-label {
        font-weight: 600;
        color: #4a5568;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    
    .detail-value {
        font-size: 1.1rem;
        color: #2d3748;
        font-weight: 500;
    }
    
    .detail-value.empty {
        color: #a0aec0;
        font-style: italic;
    }
    
    /* Status badges */
    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }
    
    .status-active {
        background-color: #d4edda;
        color: #155724;
    }
    
    .status-inactive {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    /* Code badges */
    .code-badge {
        font-family: 'Courier New', monospace;
        background: rgba(72, 202, 228, 0.1);
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.9rem;
        color: #023e8a;
        font-weight: 600;
        border: 1px solid rgba(72, 202, 228, 0.2);
    }
    
    .abi-badge {
        font-family: 'Courier New', monospace;
        background: rgba(2, 62, 138, 0.1);
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.8rem;
        color: #023e8a;
        border: 1px solid rgba(2, 62, 138, 0.2);
    }
    
    .swift-badge {
        font-family: 'Courier New', monospace;
        background: rgba(72, 202, 228, 0.15);
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.8rem;
        color: #0c5460;
        border: 1px solid rgba(72, 202, 228, 0.3);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .management-container {
            padding: 1rem;
        }
        
        .management-header, .details-container {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .management-title {
            font-size: 1.8rem;
        }
        
        .modern-btn {
            padding: 10px 16px;
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 576px) {
        .management-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .details-container {
            padding: 1rem;
        }
        
        .detail-field {
            padding: 0.8rem;
            margin-bottom: 1rem;
        }
    }
    
    /* Metadata section */
    .metadata-section {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 2px solid rgba(226, 232, 240, 0.3);
    }
    
    .metadata-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }
    
    .metadata-item {
        text-align: center;
        padding: 1rem;
        background: rgba(72, 202, 228, 0.05);
        border-radius: 12px;
    }
    
    .metadata-label {
        font-size: 0.8rem;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.3rem;
    }
    
    .metadata-value {
        font-size: 0.9rem;
        color: #2d3748;
        font-weight: 600;
    }

    /* Icona header show */
    .header-icon-show {
        color: #48cae4;
        font-size: 2rem;
    }
</style>

<div class="management-container">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Attenzione!</strong> Sono stati rilevati dei problemi:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <!-- Header con titolo e pulsanti -->
    <div class="management-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <h1 class="management-title">
                    <i class="{{ $configurazione['icona'] ?? 'bi-eye' }} me-3 header-icon-show"></i>
                    {{ $title }}
                </h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('configurations.gestione-tabelle.tabella', $nomeTabella) }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-arrow-left"></i> Torna alla Lista
                </a>
                <a href="{{ route('configurations.gestione-tabelle.edit', [$nomeTabella, $elemento->id]) }}" class="btn btn-warning modern-btn">
                    <i class="bi bi-pencil"></i> Modifica
                </a>
                <button type="button" class="btn btn-danger modern-btn" onclick="deleteItem({{ $elemento->id }})">
                    <i class="bi bi-trash"></i> Elimina
                </button>
            </div>
        </div>
    </div>

    <!-- Details container -->
    <div class="details-container">
        <div class="details-title">
            <i class="bi bi-info-circle"></i>
            Dettagli Elemento - {{ $configurazione['nome'] ?? ucfirst($nomeTabella) }}
        </div>
        
        <div class="row">
            <!-- Nome -->
            <div class="col-md-6">
                <div class="detail-field">
                    <div class="detail-label">Nome</div>
                    <div class="detail-value">{{ $elemento->name ?? $elemento->nome ?? '-' }}</div>
                </div>
            </div>
            
            <!-- Codice -->
            @if(isset($elemento->code))
            <div class="col-md-6">
                <div class="detail-field">
                    <div class="detail-label">Codice</div>
                    <div class="detail-value">
                        @if($elemento->code)
                            <span class="code-badge">{{ $elemento->code }}</span>
                        @else
                            <span class="empty">Non specificato</span>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Descrizione -->
            @if(isset($elemento->description) || isset($elemento->descrizione))
            <div class="col-12">
                <div class="detail-field">
                    <div class="detail-label">Descrizione</div>
                    <div class="detail-value {{ !($elemento->description ?? $elemento->descrizione) ? 'empty' : '' }}">
                        {{ $elemento->description ?? $elemento->descrizione ?? 'Nessuna descrizione disponibile' }}
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Campi specifici per Banche -->
            @if($nomeTabella === 'banche')
                @if($elemento->abi_code || $elemento->bic_swift)
                <div class="col-md-6">
                    <div class="detail-field">
                        <div class="detail-label">Codici Bancari</div>
                        <div class="detail-value">
                            @if($elemento->abi_code)
                                <span class="abi-badge">ABI: {{ $elemento->abi_code }}</span><br>
                            @endif
                            @if($elemento->bic_swift)
                                <span class="swift-badge">{{ $elemento->bic_swift }}</span>
                            @endif
                            @if(!$elemento->abi_code && !$elemento->bic_swift)
                                <span class="empty">Non specificati</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                
                @if($elemento->city || $elemento->country)
                <div class="col-md-6">
                    <div class="detail-field">
                        <div class="detail-label">Localizzazione</div>
                        <div class="detail-value">
                            @if($elemento->city)
                                <strong>{{ $elemento->city }}</strong><br>
                            @endif
                            {{ $elemento->country ?? 'Non specificato' }}
                        </div>
                    </div>
                </div>
                @endif
                
                @if($elemento->address)
                <div class="col-12">
                    <div class="detail-field">
                        <div class="detail-label">Indirizzo</div>
                        <div class="detail-value">{{ $elemento->address }}</div>
                    </div>
                </div>
                @endif
                
                @if($elemento->phone || $elemento->email || $elemento->website)
                <div class="col-12">
                    <div class="detail-field">
                        <div class="detail-label">Contatti</div>
                        <div class="detail-value">
                            @if($elemento->phone)
                                <strong>Telefono:</strong> {{ $elemento->phone }}<br>
                            @endif
                            @if($elemento->email)
                                <strong>Email:</strong> <a href="mailto:{{ $elemento->email }}">{{ $elemento->email }}</a><br>
                            @endif
                            @if($elemento->website)
                                <strong>Sito Web:</strong> <a href="{{ $elemento->website }}" target="_blank">{{ $elemento->website }}</a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            @endif
            
            <!-- Stato -->
            <div class="col-md-6">
                <div class="detail-field">
                    <div class="detail-label">Stato</div>
                    <div class="detail-value">
                        <span class="status-badge {{ ($elemento->active ?? true) ? 'status-active' : 'status-inactive' }}">
                            {{ ($elemento->active ?? true) ? 'Attivo' : 'Inattivo' }}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Ordine di visualizzazione -->
            @if(isset($elemento->sort_order))
            <div class="col-md-6">
                <div class="detail-field">
                    <div class="detail-label">Ordine di Visualizzazione</div>
                    <div class="detail-value">{{ $elemento->sort_order ?? 0 }}</div>
                </div>
            </div>
            @endif
        </div>
        
        <!-- Metadata section -->
        <div class="metadata-section">
            <h6 class="text-muted mb-3">Informazioni di Sistema</h6>
            <div class="metadata-grid">
                <div class="metadata-item">
                    <div class="metadata-label">Creato</div>
                    <div class="metadata-value">
                        {{ $elemento->created_at ? $elemento->created_at->format('d/m/Y H:i') : 'N/A' }}
                    </div>
                </div>
                <div class="metadata-item">
                    <div class="metadata-label">Ultimo Aggiornamento</div>
                    <div class="metadata-value">
                        {{ $elemento->updated_at ? $elemento->updated_at->format('d/m/Y H:i') : 'N/A' }}
                    </div>
                </div>
                @if(isset($elemento->created_by))
                <div class="metadata-item">
                    <div class="metadata-label">Creato da</div>
                    <div class="metadata-value">{{ $elemento->created_by ?? 'Sistema' }}</div>
                </div>
                @endif
                @if(isset($elemento->updated_by))
                <div class="metadata-item">
                    <div class="metadata-label">Modificato da</div>
                    <div class="metadata-value">{{ $elemento->updated_by ?? 'Sistema' }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
// Elimina elemento
function deleteItem(id) {
    if (confirm('Sei sicuro di voler eliminare questo elemento?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('configurations.gestione-tabelle.destroy', [$nomeTabella, ':id']) }}`.replace(':id', id);
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts dopo 5 secondi
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert, index) {
        setTimeout(function() {
            if (bootstrap.Alert.getOrCreateInstance) {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }
        }, 5000 + (index * 1000));
    });
});
</script>
@endsection