@extends('layouts.app')

@section('title', $fornitore->nome_completo . ' - Fornitori')

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
    
    .supplier-badge {
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
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
    
    .modern-btn {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
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
        box-shadow: 0 15px 35px rgba(2, 157, 126, 0.4);
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
</style>

<div class="show-container">
    <!-- Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-building"></i> {{ $fornitore->nome_completo }}
                </h1>
                <div class="mt-2">
                    <span class="supplier-badge {{ $fornitore->classe_fornitore }}">
                        {{ ucfirst($fornitore->classe_fornitore) }}
                    </span>
                    @if($fornitore->attivo)
                        <span class="badge bg-success ms-2">Attivo</span>
                    @else
                        <span class="badge bg-secondary ms-2">Non Attivo</span>
                    @endif
                    @if($fornitore->isEntePublico())
                        <span class="badge bg-info ms-2">Ente Pubblico</span>
                    @endif
                </div>
            </div>
            <div>
                <a href="{{ route('fornitori.edit', $fornitore) }}" class="modern-btn btn-warning">
                    <i class="bi bi-pencil"></i> Modifica
                </a>
                <a href="{{ route('fornitori.index') }}" class="modern-btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Torna all'Elenco
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Dati Anagrafici -->
            <div class="info-card">
                <div class="section-title">
                    <i class="bi bi-person-vcard"></i> Dati Anagrafici
                </div>
                
                <div class="info-row">
                    <div class="info-label">Tipo Soggetto:</div>
                    <div class="info-value">{{ ucwords(str_replace('_', ' ', $fornitore->tipo_soggetto)) }}</div>
                </div>
                
                @if($fornitore->tipo_soggetto == 'persona_fisica' && $fornitore->nome)
                <div class="info-row">
                    <div class="info-label">Nome:</div>
                    <div class="info-value">{{ $fornitore->nome }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Cognome:</div>
                    <div class="info-value">{{ $fornitore->cognome }}</div>
                </div>
                @endif
                
                @if($fornitore->categoria_merceologica)
                <div class="info-row">
                    <div class="info-label">Categoria:</div>
                    <div class="info-value">{{ $fornitore->categoria_merceologica }}</div>
                </div>
                @endif
                
                @if($fornitore->data_nascita)
                <div class="info-row">
                    <div class="info-label">Data Nascita:</div>
                    <div class="info-value">{{ $fornitore->data_nascita->format('d/m/Y') }}</div>
                </div>
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
                        <strong>{{ $fornitore->codice_fiscale }}</strong>
                        @if($fornitore->isCodiceFiscaleValido())
                            <i class="bi bi-check-circle text-success ms-1" title="Valido"></i>
                        @else
                            <i class="bi bi-x-circle text-danger ms-1" title="Non valido"></i>
                        @endif
                    </div>
                </div>
                
                @if($fornitore->partita_iva)
                <div class="info-row">
                    <div class="info-label">Partita IVA:</div>
                    <div class="info-value">
                        <strong>{{ $fornitore->partita_iva }}</strong>
                        @if($fornitore->isPartitaIvaValida())
                            <i class="bi bi-check-circle text-success ms-1" title="Valida"></i>
                        @else
                            <i class="bi bi-x-circle text-danger ms-1" title="Non valida"></i>
                        @endif
                    </div>
                </div>
                @endif
                
                <div class="info-row">
                    <div class="info-label">Regime Fiscale:</div>
                    <div class="info-value">{{ $fornitore->regime_fiscale ?? 'RF01' }}</div>
                </div>
                
                @if($fornitore->codice_destinatario)
                <div class="info-row">
                    <div class="info-label">Codice SDI:</div>
                    <div class="info-value">{{ $fornitore->codice_destinatario }}</div>
                </div>
                @endif
                
                @if($fornitore->split_payment)
                <div class="info-row">
                    <div class="info-label">Split Payment:</div>
                    <div class="info-value"><span class="badge bg-warning">Attivo</span></div>
                </div>
                @endif
            </div>

            <!-- Dati di Contatto -->
            <div class="info-card">
                <div class="section-title">
                    <i class="bi bi-telephone"></i> Contatti
                </div>
                
                @if($fornitore->email)
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value">
                        <a href="mailto:{{ $fornitore->email }}">{{ $fornitore->email }}</a>
                    </div>
                </div>
                @endif
                
                @if($fornitore->pec)
                <div class="info-row">
                    <div class="info-label">PEC:</div>
                    <div class="info-value">
                        <a href="mailto:{{ $fornitore->pec }}">{{ $fornitore->pec }}</a>
                    </div>
                </div>
                @endif
                
                @if($fornitore->telefono)
                <div class="info-row">
                    <div class="info-label">Telefono:</div>
                    <div class="info-value">
                        <a href="tel:{{ $fornitore->telefono }}">{{ $fornitore->telefono }}</a>
                    </div>
                </div>
                @endif
                
                @if($fornitore->telefono_mobile)
                <div class="info-row">
                    <div class="info-label">Cellulare:</div>
                    <div class="info-value">
                        <a href="tel:{{ $fornitore->telefono_mobile }}">{{ $fornitore->telefono_mobile }}</a>
                    </div>
                </div>
                @endif
                
                @if($fornitore->sito_web)
                <div class="info-row">
                    <div class="info-label">Sito Web:</div>
                    <div class="info-value">
                        <a href="{{ $fornitore->sito_web }}" target="_blank">{{ $fornitore->sito_web }} <i class="bi bi-box-arrow-up-right"></i></a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Indirizzo -->
            @if($fornitore->indirizzo_completo)
            <div class="info-card">
                <div class="section-title">
                    <i class="bi bi-geo-alt"></i> Indirizzo
                </div>
                
                <div class="info-row">
                    <div class="info-label">Indirizzo Completo:</div>
                    <div class="info-value">{{ $fornitore->indirizzo_completo }}</div>
                </div>
                
                @if($fornitore->paese != 'IT')
                <div class="info-row">
                    <div class="info-label">Paese:</div>
                    <div class="info-value">{{ $fornitore->paese }}</div>
                </div>
                @endif
            </div>
            @endif

            <!-- Dati Commerciali -->
            <div class="info-card">
                <div class="section-title">
                    <i class="bi bi-credit-card"></i> Dati Commerciali
                </div>
                
                <div class="info-row">
                    <div class="info-label">Modalità Pagamento:</div>
                    <div class="info-value">{{ ucfirst($fornitore->modalita_pagamento) }}</div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Giorni Pagamento:</div>
                    <div class="info-value">{{ $fornitore->giorni_pagamento }} giorni</div>
                </div>
                
                @if($fornitore->iban)
                <div class="info-row">
                    <div class="info-label">IBAN:</div>
                    <div class="info-value"><code>{{ $fornitore->iban }}</code></div>
                </div>
                @endif
                
                @if($fornitore->limite_credito)
                <div class="info-row">
                    <div class="info-label">Limite Credito:</div>
                    <div class="info-value">€ {{ number_format($fornitore->limite_credito, 2, ',', '.') }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Statistiche -->
            <div class="info-card">
                <div class="section-title">
                    <i class="bi bi-graph-up"></i> Statistiche
                </div>
                
                <div class="info-row">
                    <div class="info-label">Ordini Totali:</div>
                    <div class="info-value"><strong>{{ $stats['ordini_totali'] ?? 0 }}</strong></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Ordini Quest'Anno:</div>
                    <div class="info-value"><strong>{{ $stats['ordini_anno'] ?? 0 }}</strong></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Prodotti Forniti:</div>
                    <div class="info-value"><strong>{{ $stats['prodotti_forniti'] ?? 0 }}</strong></div>
                </div>
                
                @if($stats['ultimo_ordine'] ?? false)
                <div class="info-row">
                    <div class="info-label">Ultimo Ordine:</div>
                    <div class="info-value">{{ $stats['ultimo_ordine']->format('d/m/Y') }}</div>
                </div>
                @endif
            </div>

            <!-- Info Sistema -->
            <div class="info-card">
                <div class="section-title">
                    <i class="bi bi-info-circle"></i> Info Sistema
                </div>
                
                <div class="info-row">
                    <div class="info-label">Creato il:</div>
                    <div class="info-value">{{ $fornitore->created_at->format('d/m/Y H:i') }}</div>
                </div>
                
                @if($fornitore->updated_at != $fornitore->created_at)
                <div class="info-row">
                    <div class="info-label">Modificato il:</div>
                    <div class="info-value">{{ $fornitore->updated_at->format('d/m/Y H:i') }}</div>
                </div>
                @endif
                
                @if($fornitore->ultima_verifica_dati)
                <div class="info-row">
                    <div class="info-label">Ultima Verifica:</div>
                    <div class="info-value">{{ $fornitore->ultima_verifica_dati->format('d/m/Y') }}</div>
                </div>
                @endif
                
                @if($fornitore->necessitaAggiornamento())
                <div class="alert alert-warning mt-3">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Attenzione:</strong> I dati necessitano di verifica
                    <button class="btn btn-sm btn-warning mt-2" onclick="verificaDati()">
                        Aggiorna Verifica
                    </button>
                </div>
                @endif
            </div>

            <!-- Azioni -->
            <div class="info-card">
                <div class="section-title">
                    <i class="bi bi-gear"></i> Azioni
                </div>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('fornitori.edit', $fornitore) }}" class="modern-btn">
                        <i class="bi bi-pencil"></i> Modifica Fornitore
                    </a>
                    
                    <button class="modern-btn btn-danger" onclick="confirmDelete()">
                        <i class="bi bi-trash"></i> Elimina Fornitore
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form nascosto per eliminazione -->
<form id="deleteForm" method="POST" action="{{ route('fornitori.destroy', $fornitore) }}" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function verificaDati() {
    fetch('{{ route('fornitori.verifica-dati', $fornitore) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Verifica dati aggiornata con successo!');
            location.reload();
        } else {
            alert('Errore durante l\'aggiornamento');
        }
    });
}

function confirmDelete() {
    if (confirm('Sei sicuro di voler eliminare questo fornitore? Questa azione non può essere annullata.')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endsection