<style>
    .movimento-dettagli {
        max-height: 60vh;
        overflow-y: auto;
    }
    
    .movimento-header {
        background: linear-gradient(135deg, rgba(2, 157, 126, 0.1), rgba(77, 201, 165, 0.1));
        border: 1px solid rgba(2, 157, 126, 0.2);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .movimento-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        color: white;
        text-transform: uppercase;
    }
    
    .badge-carico {
        background: linear-gradient(135deg, #10b981, #059669);
    }
    
    .badge-scarico {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }
    
    .badge-trasferimento {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }
    
    .movimento-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }
    
    .movimento-subtitle {
        color: #6b7280;
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }
    
    .detail-section {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1.25rem;
        margin-bottom: 1rem;
    }
    
    .section-title {
        font-weight: 600;
        color: #029D7E;
        font-size: 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }
    
    .detail-item {
        display: flex;
        flex-direction: column;
    }
    
    .detail-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    
    .detail-value {
        font-weight: 500;
        color: #1f2937;
        font-size: 0.95rem;
    }
    
    .detail-value.empty {
        color: #9ca3af;
        font-style: italic;
    }
    
    .quantita-display {
        font-size: 1.2rem;
        font-weight: 700;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        display: inline-block;
    }
    
    .quantita-positiva {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    
    .quantita-negativa {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
        
        .movimento-header {
            padding: 1rem;
        }
    }
</style>

<div class="movimento-dettagli">
    <!-- Header principale -->
    <div class="movimento-header">
        @if($movimento->tipo_movimento === 'carico')
            <div class="movimento-badge badge-carico">
                <i class="bi bi-plus-circle"></i> Carico Magazzino
            </div>
        @elseif($movimento->tipo_movimento === 'scarico')
            <div class="movimento-badge badge-scarico">
                <i class="bi bi-dash-circle"></i> Scarico Magazzino
            </div>
        @elseif(in_array($movimento->tipo_movimento, ['trasferimento_uscita', 'trasferimento_ingresso']))
            <div class="movimento-badge badge-trasferimento">
                <i class="bi bi-arrow-repeat"></i> 
                {{ $movimento->tipo_movimento === 'trasferimento_uscita' ? 'Trasferimento Uscita' : 'Trasferimento Ingresso' }}
            </div>
        @else
            <div class="movimento-badge" style="background: #6b7280;">
                <i class="bi bi-box"></i> {{ ucfirst($movimento->tipo_movimento) }}
            </div>
        @endif
        
        <h3 class="movimento-title">
            @if($movimento->prodotto)
                {{ $movimento->prodotto->descrizione }}
            @else
                Movimento #{{ $movimento->id }}
            @endif
        </h3>
        <div class="movimento-subtitle">
            Movimento #{{ $movimento->id }} • {{ $movimento->data_movimento ? $movimento->data_movimento->format('d/m/Y') : 'Data non specificata' }}
        </div>
    </div>

    <!-- Informazioni principali movimento -->
    <div class="detail-section">
        <div class="section-title">
            <i class="bi bi-info-circle"></i>
            Informazioni Movimento
        </div>
        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">Quantità</span>
                <span class="detail-value">
                    <span class="quantita-display {{ in_array($movimento->tipo_movimento, ['carico', 'trasferimento_ingresso']) ? 'quantita-positiva' : 'quantita-negativa' }}">
                        {{ in_array($movimento->tipo_movimento, ['carico', 'trasferimento_ingresso']) ? '+' : '-' }}{{ number_format($movimento->quantita, 0) }}
                    </span>
                </span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Data Movimento</span>
                <span class="detail-value">{{ $movimento->data_movimento ? $movimento->data_movimento->format('d/m/Y') : 'Non specificata' }}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Causale</span>
                <span class="detail-value">{{ $movimento->causale ? $movimento->causale->descrizione : 'Non specificata' }}</span>
            </div>
        </div>
    </div>

    <!-- Articolo -->
    @if($movimento->prodotto)
    <div class="detail-section">
        <div class="section-title">
            <i class="bi bi-box-seam"></i>
            Articolo
        </div>
        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">Descrizione</span>
                <span class="detail-value">{{ $movimento->prodotto->descrizione }}</span>
            </div>
            @if($movimento->prodotto->codice_articolo || $movimento->prodotto->codice_interno)
            <div class="detail-item">
                <span class="detail-label">Codice</span>
                <span class="detail-value">{{ $movimento->prodotto->codice_articolo ?? $movimento->prodotto->codice_interno }}</span>
            </div>
            @endif
            @if($movimento->prodotto->unita_misura)
            <div class="detail-item">
                <span class="detail-label">Unità di Misura</span>
                <span class="detail-value">{{ $movimento->prodotto->unita_misura }}</span>
            </div>
            @endif
            @if($movimento->prodotto->categoria_articolo)
            <div class="detail-item">
                <span class="detail-label">Categoria</span>
                <span class="detail-value">{{ $movimento->prodotto->categoria_articolo }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Deposito -->
    @if($movimento->deposito)
    <div class="detail-section">
        <div class="section-title">
            <i class="bi bi-building"></i>
            Deposito
        </div>
        <div class="detail-grid">
            <div class="detail-item">
                <span class="detail-label">Nome Deposito</span>
                <span class="detail-value">{{ $movimento->deposito->description }}</span>
            </div>
            @if($movimento->deposito->address)
            <div class="detail-item">
                <span class="detail-label">Indirizzo</span>
                <span class="detail-value">{{ $movimento->deposito->address }}</span>
            </div>
            @endif
            @if($movimento->deposito->type)
            <div class="detail-item">
                <span class="detail-label">Tipo</span>
                <span class="detail-value">{{ $movimento->deposito->type }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Dettagli Trasferimento -->
    @if(in_array($movimento->tipo_movimento, ['trasferimento_uscita', 'trasferimento_ingresso']))
    <div class="detail-section">
        <div class="section-title">
            <i class="bi bi-arrow-left-right"></i>
            Dettagli Trasferimento
        </div>
        <div class="detail-grid">
            @if($movimento->depositoSorgente)
            <div class="detail-item">
                <span class="detail-label">Deposito Sorgente</span>
                <span class="detail-value">{{ $movimento->depositoSorgente->description }}</span>
            </div>
            @endif
            @if($movimento->depositoDestinazione)
            <div class="detail-item">
                <span class="detail-label">Deposito Destinazione</span>
                <span class="detail-value">{{ $movimento->depositoDestinazione->description }}</span>
            </div>
            @endif
            @if($movimento->movimento_collegato_uuid)
            <div class="detail-item">
                <span class="detail-label">UUID Collegamento</span>
                <span class="detail-value" style="font-family: monospace; font-size: 0.8rem;">{{ $movimento->movimento_collegato_uuid }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Cliente/Fornitore -->
    @if($movimento->cliente || $movimento->fornitore)
    <div class="detail-section">
        <div class="section-title">
            <i class="bi bi-people"></i>
            {{ $movimento->cliente ? 'Cliente' : 'Fornitore' }}
        </div>
        <div class="detail-grid">
            @if($movimento->cliente)
            <div class="detail-item">
                <span class="detail-label">Nome Cliente</span>
                <span class="detail-value">{{ $movimento->cliente->descrizione }}</span>
            </div>
            @if($movimento->cliente->codice_interno)
            <div class="detail-item">
                <span class="detail-label">Codice Cliente</span>
                <span class="detail-value">{{ $movimento->cliente->codice_interno }}</span>
            </div>
            @endif
            @if($movimento->cliente->email)
            <div class="detail-item">
                <span class="detail-label">Email</span>
                <span class="detail-value">{{ $movimento->cliente->email }}</span>
            </div>
            @endif
            @endif

            @if($movimento->fornitore)
            <div class="detail-item">
                <span class="detail-label">Nome Fornitore</span>
                <span class="detail-value">{{ $movimento->fornitore->descrizione }}</span>
            </div>
            @if($movimento->fornitore->codice_interno)
            <div class="detail-item">
                <span class="detail-label">Codice Fornitore</span>
                <span class="detail-value">{{ $movimento->fornitore->codice_interno }}</span>
            </div>
            @endif
            @if($movimento->fornitore->email)
            <div class="detail-item">
                <span class="detail-label">Email</span>
                <span class="detail-value">{{ $movimento->fornitore->email }}</span>
            </div>
            @endif
            @endif
        </div>
    </div>
    @endif

    <!-- Informazioni Sistema -->
    <div class="detail-section">
        <div class="section-title">
            <i class="bi bi-gear"></i>
            Informazioni Sistema
        </div>
        <div class="detail-grid">
            @if($movimento->user)
            <div class="detail-item">
                <span class="detail-label">Registrato da</span>
                <span class="detail-value">{{ $movimento->user->name ?? 'Utente non trovato' }}</span>
            </div>
            @endif
            <div class="detail-item">
                <span class="detail-label">Data Registrazione</span>
                <span class="detail-value">{{ $movimento->created_at ? $movimento->created_at->format('d/m/Y H:i:s') : 'Non disponibile' }}</span>
            </div>
            @if($movimento->updated_at && $movimento->updated_at != $movimento->created_at)
            <div class="detail-item">
                <span class="detail-label">Ultimo Aggiornamento</span>
                <span class="detail-value">{{ $movimento->updated_at->format('d/m/Y H:i:s') }}</span>
            </div>
            @endif
            @if($movimento->uuid)
            <div class="detail-item">
                <span class="detail-label">UUID Movimento</span>
                <span class="detail-value" style="font-family: monospace; font-size: 0.8rem; word-break: break-all;">{{ $movimento->uuid }}</span>
            </div>
            @endif
            @if($movimento->note)
            <div class="detail-item" style="grid-column: 1 / -1;">
                <span class="detail-label">Note</span>
                <span class="detail-value">{{ $movimento->note }}</span>
            </div>
            @endif
        </div>
    </div>
</div>