@extends('layouts.app')

@section('title', $vettore->nome_completo . ' - Vettori')

@section('content')
<style>
    .show-container {
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
    
    .info-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 2rem;
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
    
    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #029D7E;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-bottom: 2px solid rgba(2, 157, 126, 0.1);
        padding-bottom: 0.5rem;
    }
    
    .info-row {
        display: flex;
        margin-bottom: 1rem;
        align-items: flex-start;
    }
    
    .info-label {
        font-weight: 600;
        color: #4a5568;
        width: 200px;
        flex-shrink: 0;
    }
    
    .info-value {
        color: #2d3748;
        flex: 1;
    }
    
    .carrier-badge {
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
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
    
    .modern-btn {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        border-radius: 15px;
        padding: 12px 24px;
        font-weight: 600;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        margin-right: 0.5rem;
    }
    
    .modern-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #6c757d, #5a6268);
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #f72585, #c5025a);
    }
    
    .rating-stars {
        color: #ffc107;
        font-size: 1.2rem;
    }
    
    .service-tag {
        display: inline-block;
        background: rgba(2, 157, 126, 0.1);
        color: #029D7E;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        margin: 2px;
        font-weight: 500;
    }
    
    .progress-container {
        margin: 1rem 0;
    }
    
    .progress-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .progress {
        height: 8px;
        border-radius: 10px;
        background: rgba(2, 157, 126, 0.1);
    }
    
    .progress-bar {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        border-radius: 10px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .show-container {
            padding: 1rem;
        }
        
        .page-title {
            font-size: 2rem;
        }
        
        .info-row {
            flex-direction: column;
        }
        
        .info-label {
            width: auto;
            margin-bottom: 0.25rem;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="show-container">
    <!-- Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-truck"></i> {{ $vettore->nome_completo }}
                </h1>
                <div class="mt-2">
                    <span class="carrier-badge {{ $vettore->classe_vettore }}">
                        {{ ucfirst($vettore->classe_vettore) }}
                    </span>
                    <span class="badge bg-info ms-2">
                        {{ ucfirst(str_replace('_', ' ', $vettore->tipo_vettore)) }}
                    </span>
                    @if($vettore->attivo)
                        <span class="badge bg-success ms-2">Attivo</span>
                    @else
                        <span class="badge bg-secondary ms-2">Non Attivo</span>
                    @endif
                    @if($vettore->verificato)
                        <span class="badge bg-success ms-2">Verificato</span>
                    @else
                        <span class="badge bg-warning text-dark ms-2">Da Verificare</span>
                    @endif
                </div>
            </div>
            <div>
                <a href="{{ route('vettori.edit', $vettore) }}" class="modern-btn btn-warning">
                    <i class="bi bi-pencil"></i> Modifica
                </a>
                <button type="button" class="modern-btn btn-danger" 
                        onclick="confirmDelete('{{ $vettore->nome_completo }}', '{{ route('vettori.destroy', $vettore) }}')">
                    <i class="bi bi-trash"></i> Elimina
                </button>
                <a href="{{ route('vettori.index') }}" class="modern-btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Torna all'Elenco
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiche Performance -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $stats['spedizioni_totali'] }}</div>
            <div class="stat-label">Spedizioni Totali</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['spedizioni_anno'] }}</div>
            <div class="stat-label">Spedizioni {{ date('Y') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $stats['rating_stelle'] }}</div>
            <div class="stat-label">Valutazione</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                @if($stats['ultima_spedizione'])
                    {{ $stats['ultima_spedizione']->diffForHumans() }}
                @else
                    Mai
                @endif
            </div>
            <div class="stat-label">Ultima Spedizione</div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Dati Anagrafici -->
            <div class="info-card">
                <div class="section-title">
                    <i class="bi bi-building"></i> Dati Identificativi
                </div>
                
                <div class="info-row">
                    <div class="info-label">Codice Vettore:</div>
                    <div class="info-value">
                        <strong>{{ $vettore->codice_vettore }}</strong>
                    </div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Tipo Soggetto:</div>
                    <div class="info-value">{{ ucwords(str_replace('_', ' ', $vettore->tipo_soggetto)) }}</div>
                </div>
                
                @if($vettore->nome_commerciale && $vettore->nome_commerciale != $vettore->ragione_sociale)
                <div class="info-row">
                    <div class="info-label">Nome Commerciale:</div>
                    <div class="info-value">{{ $vettore->nome_commerciale }}</div>
                </div>
                @endif
                
                @if($vettore->tipo_soggetto == 'persona_fisica' && $vettore->nome)
                <div class="info-row">
                    <div class="info-label">Nome:</div>
                    <div class="info-value">{{ $vettore->nome }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Cognome:</div>
                    <div class="info-value">{{ $vettore->cognome }}</div>
                </div>
                @if($vettore->data_nascita)
                <div class="info-row">
                    <div class="info-label">Data di Nascita:</div>
                    <div class="info-value">{{ $vettore->data_nascita->format('d/m/Y') }}</div>
                </div>
                @endif
                @endif
            </div>

            <!-- Dati Fiscali -->
            <div class="info-card">
                <div class="section-title">
                    <i class="bi bi-receipt"></i> Dati Fiscali
                </div>
                
                <div class="info-row">
                    <div class="info-label">Codice Fiscale:</div>
                    <div class="info-value">
                        <span class="fw-bold">{{ $vettore->codice_fiscale }}</span>
                    </div>
                </div>
                
                @if($vettore->partita_iva)
                <div class="info-row">
                    <div class="info-label">Partita IVA:</div>
                    <div class="info-value">
                        <span class="fw-bold">{{ $vettore->partita_iva }}</span>
                        @if($vettore->isPartitaIvaValida())
                            <span class="badge bg-success ms-2">Valida</span>
                        @else
                            <span class="badge bg-danger ms-2">Non Valida</span>
                        @endif
                    </div>
                </div>
                @endif
                
                @if($vettore->regime_fiscale)
                <div class="info-row">
                    <div class="info-label">Regime Fiscale:</div>
                    <div class="info-value">{{ $vettore->regime_fiscale }}</div>
                </div>
                @endif
                
                @if($vettore->split_payment)
                <div class="info-row">
                    <div class="info-label">Split Payment:</div>
                    <div class="info-value">
                        <span class="badge bg-warning">Abilitato</span>
                    </div>
                </div>
                @endif
            </div>

            <!-- Dati di Contatto -->
            <div class="info-card">
                <div class="section-title">
                    <i class="bi bi-telephone"></i> Dati di Contatto
                </div>
                
                @if($vettore->email)
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value">
                        <a href="mailto:{{ $vettore->email }}">{{ $vettore->email }}</a>
                    </div>
                </div>
                @endif
                
                @if($vettore->pec)
                <div class="info-row">
                    <div class="info-label">PEC:</div>
                    <div class="info-value">
                        <a href="mailto:{{ $vettore->pec }}">{{ $vettore->pec }}</a>
                    </div>
                </div>
                @endif
                
                @if($vettore->telefono)
                <div class="info-row">
                    <div class="info-label">Telefono:</div>
                    <div class="info-value">
                        <a href="tel:{{ $vettore->telefono }}">{{ $vettore->telefono }}</a>
                    </div>
                </div>
                @endif
                
                @if($vettore->telefono_mobile)
                <div class="info-row">
                    <div class="info-label">Cellulare:</div>
                    <div class="info-value">
                        <a href="tel:{{ $vettore->telefono_mobile }}">{{ $vettore->telefono_mobile }}</a>
                    </div>
                </div>
                @endif
                
                @if($vettore->sito_web)
                <div class="info-row">
                    <div class="info-label">Sito Web:</div>
                    <div class="info-value">
                        <a href="{{ $vettore->sito_web }}" target="_blank">{{ $vettore->sito_web }}</a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Indirizzi -->
            <div class="info-card">
                <div class="section-title">
                    <i class="bi bi-geo-alt"></i> Indirizzi
                </div>
                
                @if($vettore->indirizzo)
                <div class="info-row">
                    <div class="info-label">Sede Legale:</div>
                    <div class="info-value">{{ $vettore->indirizzo_completo }}</div>
                </div>
                @endif
                
                @if($vettore->indirizzo_operativo)
                <div class="info-row">
                    <div class="info-label">Sede Operativa:</div>
                    <div class="info-value">{{ $vettore->indirizzo_operativo_completo }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Performance -->
            <div class="info-card">
                <div class="section-title">
                    <i class="bi bi-speedometer2"></i> Performance
                </div>
                
                @if($vettore->valutazione)
                <div class="info-row">
                    <div class="info-label">Valutazione:</div>
                    <div class="info-value">
                        <div class="rating-stars">
                            {{ str_repeat('⭐', (int) round($vettore->valutazione)) }}
                        </div>
                        <small class="text-muted">
                            {{ number_format($vettore->valutazione, 1) }}/5.0 
                            ({{ $vettore->numero_valutazioni }} voti)
                        </small>
                    </div>
                </div>
                @endif
                
                @if($vettore->percentuale_puntualita)
                <div class="progress-container">
                    <div class="progress-label">
                        <span>Puntualità</span>
                        <span>{{ number_format($vettore->percentuale_puntualita, 1) }}%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" style="width: {{ $vettore->percentuale_puntualita }}%"></div>
                    </div>
                </div>
                @endif
                
                @if($vettore->tempo_consegna_standard)
                <div class="info-row">
                    <div class="info-label">Consegna Standard:</div>
                    <div class="info-value">{{ $vettore->tempo_consegna_standard }} giorni</div>
                </div>
                @endif
                
                @if($vettore->tempo_consegna_express)
                <div class="info-row">
                    <div class="info-label">Consegna Express:</div>
                    <div class="info-value">{{ $vettore->tempo_consegna_express }} giorni</div>
                </div>
                @endif
            </div>

            <!-- Condizioni Commerciali -->
            <div class="info-card">
                <div class="section-title">
                    <i class="bi bi-credit-card"></i> Condizioni Commerciali
                </div>
                
                <div class="info-row">
                    <div class="info-label">Modalità Pagamento:</div>
                    <div class="info-value">{{ ucfirst($vettore->modalita_pagamento) }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Giorni Pagamento:</div>
                    <div class="info-value">{{ $vettore->giorni_pagamento }} giorni</div>
                </div>
                
                @if($vettore->costo_base_kg)
                <div class="info-row">
                    <div class="info-label">Costo Base:</div>
                    <div class="info-value">€ {{ number_format($vettore->costo_base_kg, 4) }}/kg</div>
                </div>
                @endif
                
                @if($vettore->costo_minimo_spedizione)
                <div class="info-row">
                    <div class="info-label">Costo Minimo:</div>
                    <div class="info-value">€ {{ number_format($vettore->costo_minimo_spedizione, 2) }}</div>
                </div>
                @endif
                
                @if($vettore->soglia_franco)
                <div class="info-row">
                    <div class="info-label">Soglia Franco:</div>
                    <div class="info-value">€ {{ number_format($vettore->soglia_franco, 2) }}</div>
                </div>
                @endif
            </div>

            <!-- Servizi Offerti -->
            @if($vettore->servizi_offerti)
            <div class="info-card">
                <div class="section-title">
                    <i class="bi bi-list-check"></i> Servizi Offerti
                </div>
                <div>
                    @foreach($vettore->servizi_offerti as $servizio)
                        <span class="service-tag">{{ ucfirst($servizio) }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Aree di Copertura -->
            @if($vettore->aree_copertura)
            <div class="info-card">
                <div class="section-title">
                    <i class="bi bi-globe"></i> Aree di Copertura
                </div>
                <div>
                    @foreach($vettore->aree_copertura as $area)
                        <span class="service-tag">{{ ucfirst($area) }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Tipologie Merci -->
            @if($vettore->tipologie_merci)
            <div class="info-card">
                <div class="section-title">
                    <i class="bi bi-box"></i> Tipologie Merci
                </div>
                <div>
                    @foreach($vettore->tipologie_merci as $tipo)
                        <span class="service-tag">{{ ucfirst($tipo) }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Azioni Rapide -->
            <div class="info-card">
                <div class="section-title">
                    <i class="bi bi-lightning"></i> Azioni Rapide
                </div>
                
                @if(!$vettore->verificato)
                <button type="button" class="btn btn-success w-100 mb-2" onclick="verificaDati({{ $vettore->id }})">
                    <i class="bi bi-check-circle"></i> Verifica Dati
                </button>
                @endif
                
                <button type="button" class="btn btn-info w-100 mb-2" onclick="calcolaCosto({{ $vettore->id }})">
                    <i class="bi bi-calculator"></i> Calcola Costo Spedizione
                </button>
                
                @if($vettore->api_tracking_url)
                <a href="#" class="btn btn-warning w-100 mb-2" onclick="trackSpedizione()">
                    <i class="bi bi-search"></i> Tracking Spedizione
                </a>
                @endif
            </div>
        </div>
    </div>
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

function verificaDati(vettoreId) {
    if (confirm('Aggiornare la verifica dati per questo vettore?')) {
        fetch(`/vettori/${vettoreId}/verifica-dati`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Verifica dati aggiornata con successo!');
                location.reload();
            } else {
                alert('Errore: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Errore durante la verifica dei dati');
        });
    }
}

function calcolaCosto(vettoreId) {
    const peso = prompt('Inserisci il peso della spedizione (kg):');
    if (peso && !isNaN(peso)) {
        const servizio = prompt('Tipo servizio (standard/express):', 'standard');
        
        fetch('/vettori/calcola-costo', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                vettore_id: vettoreId,
                peso: parseFloat(peso),
                servizio: servizio || 'standard'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.gratuita) {
                    alert(`Spedizione gratuita!`);
                } else {
                    alert(`Costo spedizione: € ${data.costo}\nTempo consegna: ${data.dettagli.tempo_consegna} giorni`);
                }
            } else {
                alert('Errore: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Errore durante il calcolo del costo');
        });
    }
}

function trackSpedizione() {
    const numeroSpedizione = prompt('Inserisci il numero di tracking:');
    if (numeroSpedizione) {
        const trackingUrl = '{{ $vettore->api_tracking_url }}'.replace('{tracking}', numeroSpedizione);
        window.open(trackingUrl, '_blank');
    }
}
</script>

@endsection