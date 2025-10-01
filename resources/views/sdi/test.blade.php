@extends('layouts.app')

@section('title', 'Test Sistema di Interscambio (SDI)')

@section('content')
<style>
    .sdi-container {
        padding: 2rem 0;
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .sdi-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .status-success {
        background: linear-gradient(135deg, #4ecdc4, #44a08d);
        color: white;
    }
    
    .status-error {
        background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
        color: white;
    }
    
    .status-warning {
        background: linear-gradient(135deg, #feca57, #ff9ff3);
        color: white;
    }
    
    .modern-btn {
        padding: 0.75rem 1.5rem;
        border-radius: 50px;
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
    }
    
    .modern-btn.primary {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
    }
    
    .modern-btn.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(2, 157, 126, 0.3);
    }
    
    .modern-btn.secondary {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
    }
    
    .result-box {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
        border-left: 4px solid #029D7E;
    }
    
    .xml-preview {
        background: #2d3748;
        color: #e2e8f0;
        padding: 1rem;
        border-radius: 10px;
        font-family: 'Courier New', monospace;
        font-size: 0.85rem;
        max-height: 200px;
        overflow-y: auto;
    }
</style>

<div class="container-fluid sdi-container">
    <div class="container">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="text-white mb-2">
                            <i class="bi bi-cloud-upload"></i> Test Sistema di Interscambio (SDI)
                        </h1>
                        <p class="text-white-50">Ambiente di test per fatturazione elettronica</p>
                    </div>
                    <a href="{{ route('configurations.utente') }}" class="modern-btn secondary">
                        <i class="bi bi-arrow-left"></i> Configurazioni
                    </a>
                </div>
            </div>
        </div>

        <!-- Status Check -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="sdi-card">
                    <h3><i class="bi bi-gear"></i> Stato Configurazione SDI</h3>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <span class="me-3">Configurazione base:</span>
                                @if($configured)
                                    <span class="status-badge status-success">
                                        <i class="bi bi-check-circle"></i> Configurato
                                    </span>
                                @else
                                    <span class="status-badge status-error">
                                        <i class="bi bi-x-circle"></i> Non configurato
                                    </span>
                                @endif
                            </div>
                            
                            <div class="d-flex align-items-center mb-3">
                                <span class="me-3">Connessione SDI:</span>
                                @if($connection['connected'])
                                    <span class="status-badge status-success">
                                        <i class="bi bi-wifi"></i> Connesso
                                    </span>
                                @else
                                    <span class="status-badge status-warning">
                                        <i class="bi bi-wifi-off"></i> Test Mode
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            @if($company)
                                <h5>Dati Azienda:</h5>
                                <ul class="list-unstyled">
                                    <li><strong>Ragione Sociale:</strong> {{ $company->ragione_sociale ?? 'Non impostata' }}</li>
                                    <li><strong>P.IVA:</strong> {{ $company->partita_iva ?? 'Non impostata' }}</li>
                                    <li><strong>Regime Fiscale:</strong> {{ $company->regime_fiscale ?? 'Non impostato' }}</li>
                                </ul>
                            @else
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle"></i> 
                                    Nessun profilo aziendale configurato
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if(!empty($connection['message']))
                        <div class="result-box mt-3">
                            <strong>Messaggio:</strong> {{ $connection['message'] }}
                        </div>
                    @endif
                    
                    @if(!empty($connection['error']))
                        <div class="alert alert-warning mt-3">
                            <strong>Errore:</strong> {{ $connection['error'] }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Test Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="sdi-card">
                    <h3><i class="bi bi-play-circle"></i> Test Funzionalità</h3>
                    
                    <div class="row mt-3">
                        <div class="col-md-8">
                            <p>Genera una fattura elettronica di test per verificare il funzionamento del sistema.</p>
                            <p class="text-muted">
                                <small>
                                    <i class="bi bi-info-circle"></i> 
                                    In modalità test, l'XML viene generato e salvato localmente senza invio reale a SDI.
                                </small>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="button" class="modern-btn primary" id="generateTestBtn">
                                <i class="bi bi-file-earmark-code"></i> Genera Fattura Test
                            </button>
                        </div>
                    </div>
                    
                    <!-- Result Area -->
                    <div id="testResult" style="display: none;" class="mt-4">
                        <h5>Risultato Test:</h5>
                        <div id="resultContent"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- XML Files -->
        <div class="row">
            <div class="col-12">
                <div class="sdi-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3><i class="bi bi-file-earmark-text"></i> File XML Generati</h3>
                        <button type="button" class="modern-btn secondary" id="refreshFilesBtn">
                            <i class="bi bi-arrow-clockwise"></i> Aggiorna
                        </button>
                    </div>
                    
                    <div id="xmlFilesList">
                        <p class="text-muted">Caricamento file...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Carica lista file all'avvio
    loadXmlFiles();
    
    // Genera fattura test
    document.getElementById('generateTestBtn').addEventListener('click', function() {
        const btn = this;
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Generando...';
        btn.disabled = true;
        
        fetch('{{ route("sdi.generate-test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('testResult').style.display = 'block';
            
            if (data.success) {
                document.getElementById('resultContent').innerHTML = `
                    <div class="alert alert-success">
                        <h6><i class="bi bi-check-circle"></i> Fattura generata con successo!</h6>
                        <ul class="mb-0">
                            <li><strong>Dimensione XML:</strong> ${data.xml_length} caratteri</li>
                            <li><strong>Validazione:</strong> ${data.validation.valid ? 'Valida' : 'Errore: ' + data.validation.error}</li>
                            <li><strong>File:</strong> ${data.sdi_result.filename}</li>
                            <li><strong>Stato:</strong> ${data.sdi_result.message}</li>
                        </ul>
                    </div>
                    <div class="xml-preview">
                        <strong>Anteprima XML:</strong><br>
                        ${data.xml_preview.replace(/</g, '&lt;').replace(/>/g, '&gt;')}
                    </div>
                `;
                
                // Ricarica lista file
                loadXmlFiles();
            } else {
                document.getElementById('resultContent').innerHTML = `
                    <div class="alert alert-danger">
                        <h6><i class="bi bi-x-circle"></i> Errore nella generazione</h6>
                        <p class="mb-0">${data.error}</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            document.getElementById('testResult').style.display = 'block';
            document.getElementById('resultContent').innerHTML = `
                <div class="alert alert-danger">
                    <h6><i class="bi bi-x-circle"></i> Errore di rete</h6>
                    <p class="mb-0">${error.message}</p>
                </div>
            `;
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    });
    
    // Refresh files
    document.getElementById('refreshFilesBtn').addEventListener('click', loadXmlFiles);
    
    function loadXmlFiles() {
        fetch('{{ route("sdi.list-xml") }}')
        .then(response => response.json())
        .then(files => {
            const container = document.getElementById('xmlFilesList');
            
            if (files.length === 0) {
                container.innerHTML = '<p class="text-muted">Nessun file XML generato ancora.</p>';
                return;
            }
            
            let html = '<div class="table-responsive"><table class="table table-striped">';
            html += '<thead><tr><th>Nome File</th><th>Dimensione</th><th>Data Modifica</th><th>Azioni</th></tr></thead><tbody>';
            
            files.forEach(file => {
                const date = new Date(file.modified * 1000).toLocaleString('it-IT');
                const size = (file.size / 1024).toFixed(2) + ' KB';
                
                html += `
                    <tr>
                        <td><code>${file.filename}</code></td>
                        <td>${size}</td>
                        <td>${date}</td>
                        <td>
                            <a href="{{ route('sdi.view-xml', '') }}/${file.filename}" 
                               target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Visualizza
                            </a>
                        </td>
                    </tr>
                `;
            });
            
            html += '</tbody></table></div>';
            container.innerHTML = html;
        })
        .catch(error => {
            document.getElementById('xmlFilesList').innerHTML = 
                '<p class="text-danger">Errore caricamento file: ' + error.message + '</p>';
        });
    }
});
</script>
@endsection