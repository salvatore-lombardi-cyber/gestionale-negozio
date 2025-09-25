@extends('layouts.app')

@section('title', 'Dettagli ' . ucfirst($anagrafica->tipo) . ' - Anagrafiche')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .anagrafiche-container {
        padding: 2rem;
        min-height: calc(100vh - 76px);
    }
    
    .detail-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .detail-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .detail-subtitle {
        color: #6c757d;
        font-size: 1.2rem;
        margin-top: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .record-meta {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 1rem;
        margin-top: 1rem;
    }
    
    .meta-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .status-attivo {
        background: #d1fae5;
        color: #065f46;
    }
    
    .status-disattivo {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .detail-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 0;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
    }
    
    .nav-tabs {
        background: rgba(248, 249, 250, 0.8);
        border-bottom: 2px solid #e9ecef;
        padding: 0 2rem;
        margin: 0;
    }
    
    .nav-tabs .nav-link {
        border: none;
        border-radius: 0;
        padding: 1rem 1.5rem;
        color: #6c757d;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-bottom: -2px;
        position: relative;
    }
    
    .nav-tabs .nav-link:hover {
        background: rgba(255, 255, 255, 0.5);
        color: #029D7E;
    }
    
    .nav-tabs .nav-link.active {
        background: transparent;
        color: #029D7E;
        border-bottom: 2px solid #029D7E;
    }
    
    .tab-content {
        padding: 2rem;
    }
    
    .detail-section {
        margin-bottom: 2.5rem;
    }
    
    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .detail-field {
        background: rgba(248, 249, 250, 0.5);
        border-radius: 12px;
        padding: 1rem;
        border: 1px solid #e2e8f0;
    }
    
    .field-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.5rem;
    }
    
    .field-value {
        font-size: 1rem;
        color: #2d3748;
        font-weight: 500;
        line-height: 1.4;
        word-break: break-word;
    }
    
    .field-value.empty {
        color: #a0aec0;
        font-style: italic;
    }
    
    .field-value.currency {
        color: #065f46;
        font-weight: 600;
    }
    
    .field-value.percentage {
        color: #7c2d12;
        font-weight: 600;
    }
    
    .field-value.boolean {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .field-value.boolean.true {
        background: #dcfce7;
        color: #166534;
    }
    
    .field-value.boolean.false {
        background: #fef2f2;
        color: #991b1b;
    }
    
    .field-value.array {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .array-item {
        background: #e0f2fe;
        color: #0c4a6e;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .detail-actions {
        background: rgba(248, 249, 250, 0.8);
        padding: 1.5rem 2rem;
        margin: -2rem -2rem 0 -2rem;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(2, 157, 126, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .btn-secondary {
        background: #6c757d;
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(108, 117, 125, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-warning:hover {
        background: linear-gradient(135deg, #ff8500, #ffd60a);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(255, 133, 0, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #f72585, #c5025a);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-danger:hover {
        background: linear-gradient(135deg, #c5025a, #f72585);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(247, 37, 133, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .wide-field {
        grid-column: 1 / -1;
    }
    
    .wide-field .field-value {
        white-space: pre-wrap;
        line-height: 1.6;
    }
    
    @media (max-width: 768px) {
        .anagrafiche-container {
            padding: 1rem;
        }
        
        .detail-header {
            padding: 1.5rem;
        }
        
        .detail-header .btn {
            white-space: nowrap;
        }
        
        .detail-title {
            font-size: 1.8rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .meta-row {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .nav-tabs {
            padding: 0 1rem;
        }
        
        .nav-tabs .nav-link {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
        }
        
        .tab-content {
            padding: 1.5rem;
        }
        
        .detail-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .detail-actions {
            padding: 1rem 1.5rem;
            margin: -1.5rem -1.5rem 0 -1.5rem;
            flex-direction: column;
            gap: 1rem;
        }
        
        .action-buttons {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            justify-content: center;
        }
        
        .action-buttons .btn {
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 10px 16px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
            min-width: 90px;
        }
        
        .action-buttons .btn i {
            font-size: 0.9rem;
        }
        
        .action-buttons .btn span {
            display: inline;
        }
        
        .action-buttons .btn:first-child {
            margin-left: 1rem;
        }
    }
</style>

<div class="container-fluid anagrafiche-container">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="detail-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h1 class="detail-title">
                            <i class="{{ $config['icon'] }}"></i> 
                            {{ $anagrafica->descrizione }}
                        </h1>
                        <p class="detail-subtitle">{{ $config['descrizione'] }}</p>
                        
                        <div class="record-meta">
                            <div class="meta-row">
                                <div class="d-flex gap-3 flex-wrap">
                                    <div class="meta-item">
                                        <i class="bi bi-hash"></i>
                                        <strong>{{ $anagrafica->codice_interno }}</strong>
                                    </div>
                                    <div class="meta-item">
                                        <i class="bi bi-calendar-plus"></i>
                                        Creato: {{ $anagrafica->created_at->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="meta-item">
                                        <i class="bi bi-calendar-check"></i>
                                        Modificato: {{ $anagrafica->updated_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                <div class="status-badge {{ $anagrafica->attivo ? 'status-attivo' : 'status-disattivo' }}">
                                    <i class="bi bi-{{ $anagrafica->attivo ? 'check-circle' : 'x-circle' }}"></i>
                                    {{ $anagrafica->attivo ? 'Attivo' : 'Disattivo' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('anagrafiche.lista', $anagrafica->tipo) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Torna Indietro
                    </a>
                </div>
            </div>
            
            <!-- Content -->
            <div class="detail-container">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs" id="anagraficaTabs" role="tablist">
                    @foreach($config['tabs'] as $tabKey => $tab)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                id="{{ $tabKey }}-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#{{ $tabKey }}" 
                                type="button" 
                                role="tab">
                            <i class="{{ $tab['icon'] }}"></i> {{ $tab['titolo'] }}
                        </button>
                    </li>
                    @endforeach
                </ul>
                
                <!-- Tab Content -->
                <div class="tab-content" id="anagraficaTabContent">
                    
                    <!-- TAB DATI BASE -->
                    @if(isset($config['tabs']['dati-base']))
                    <div class="tab-pane fade {{ array_key_first($config['tabs']) === 'dati-base' ? 'show active' : '' }}" 
                         id="dati-base" role="tabpanel">
                        <div class="detail-section">
                            <h4 class="section-title">
                                <i class="bi bi-person-fill"></i> Informazioni Base
                            </h4>
                            
                            <div class="detail-grid">
                                <div class="detail-field">
                                    <div class="field-label">Codice Interno</div>
                                    <div class="field-value">{{ $anagrafica->codice_interno }}</div>
                                </div>
                                
                                @if(in_array($anagrafica->tipo, ['cliente', 'fornitore', 'vettore', 'agente']))
                                <div class="detail-field">
                                    <div class="field-label">Tipo Soggetto</div>
                                    <div class="field-value {{ $anagrafica->tipo_soggetto ? '' : 'empty' }}">
                                        {{ $anagrafica->tipo_soggetto ? ucwords(str_replace('_', ' ', $anagrafica->tipo_soggetto)) : 'Non specificato' }}
                                    </div>
                                </div>
                                @endif
                                
                                <div class="detail-field">
                                    <div class="field-label">{{ in_array($anagrafica->tipo, ['articolo', 'servizio']) ? 'Nome/Denominazione' : 'Nome' }}</div>
                                    <div class="field-value">{{ $anagrafica->nome }}</div>
                                </div>
                                
                                @if(!in_array($anagrafica->tipo, ['articolo', 'servizio']))
                                <div class="detail-field">
                                    <div class="field-label">Cognome</div>
                                    <div class="field-value {{ $anagrafica->cognome ? '' : 'empty' }}">
                                        {{ $anagrafica->cognome ?: 'Non specificato' }}
                                    </div>
                                </div>
                                @endif
                                
                                @if(in_array($anagrafica->tipo, ['articolo']))
                                <div class="detail-field">
                                    <div class="field-label">Descrizione Estesa</div>
                                    <div class="field-value {{ $anagrafica->descrizione_estesa ? '' : 'empty' }}">
                                        {{ $anagrafica->descrizione_estesa ?: 'Non specificato' }}
                                    </div>
                                </div>
                                @endif
                                
                                @if($anagrafica->altro)
                                <div class="detail-field wide-field">
                                    <div class="field-label">Note Aggiuntive</div>
                                    <div class="field-value">{{ $anagrafica->altro }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- TAB INDIRIZZO -->
                    @if(isset($config['tabs']['indirizzo']))
                    <div class="tab-pane fade {{ array_key_first($config['tabs']) === 'indirizzo' ? 'show active' : '' }}" 
                         id="indirizzo" role="tabpanel">
                        <div class="detail-section">
                            <h4 class="section-title">
                                <i class="bi bi-geo-alt-fill"></i> Indirizzo
                            </h4>
                            
                            <div class="detail-grid">
                                <div class="detail-field">
                                    <div class="field-label">Indirizzo</div>
                                    <div class="field-value {{ $anagrafica->indirizzo ? '' : 'empty' }}">
                                        {{ $anagrafica->indirizzo ?: 'Non specificato' }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">CAP</div>
                                    <div class="field-value {{ $anagrafica->cap ? '' : 'empty' }}">
                                        {{ $anagrafica->cap ?: 'Non specificato' }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Comune</div>
                                    <div class="field-value {{ $anagrafica->comune ? '' : 'empty' }}">
                                        {{ $anagrafica->comune ?: 'Non specificato' }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Provincia</div>
                                    <div class="field-value {{ $anagrafica->provincia ? '' : 'empty' }}">
                                        {{ $anagrafica->provincia ?: 'Non specificato' }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Nazione</div>
                                    <div class="field-value">{{ $anagrafica->nazione ?: 'ITA' }}</div>
                                </div>
                                
                                <div class="detail-field wide-field">
                                    <div class="field-label">Indirizzo Completo</div>
                                    <div class="field-value">{{ $anagrafica->indirizzo_completo }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- TAB CONTATTI -->
                    @if(isset($config['tabs']['contatti']))
                    <div class="tab-pane fade {{ array_key_first($config['tabs']) === 'contatti' ? 'show active' : '' }}" 
                         id="contatti" role="tabpanel">
                        <div class="detail-section">
                            <h4 class="section-title">
                                <i class="bi bi-telephone-fill"></i> Contatti
                            </h4>
                            
                            <div class="detail-grid">
                                <div class="detail-field">
                                    <div class="field-label">Telefono 1</div>
                                    <div class="field-value {{ $anagrafica->telefono_1 ? '' : 'empty' }}">
                                        {{ $anagrafica->telefono_1 ?: 'Non specificato' }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Telefono 2</div>
                                    <div class="field-value {{ $anagrafica->telefono_2 ? '' : 'empty' }}">
                                        {{ $anagrafica->telefono_2 ?: 'Non specificato' }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Email</div>
                                    <div class="field-value {{ $anagrafica->email ? '' : 'empty' }}">
                                        @if($anagrafica->email)
                                            <a href="mailto:{{ $anagrafica->email }}" style="color: inherit;">{{ $anagrafica->email }}</a>
                                        @else
                                            Non specificato
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">PEC</div>
                                    <div class="field-value {{ $anagrafica->pec ? '' : 'empty' }}">
                                        @if($anagrafica->pec)
                                            <a href="mailto:{{ $anagrafica->pec }}" style="color: inherit;">{{ $anagrafica->pec }}</a>
                                        @else
                                            Non specificato
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Sito Web</div>
                                    <div class="field-value {{ $anagrafica->sito_web ? '' : 'empty' }}">
                                        @if($anagrafica->sito_web)
                                            <a href="{{ $anagrafica->sito_web }}" target="_blank" style="color: inherit;">{{ $anagrafica->sito_web }}</a>
                                        @else
                                            Non specificato
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Contatto Referente</div>
                                    <div class="field-value {{ $anagrafica->contatto_referente ? '' : 'empty' }}">
                                        {{ $anagrafica->contatto_referente ?: 'Non specificato' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- TAB FISCALI -->
                    @if(isset($config['tabs']['fiscali']))
                    <div class="tab-pane fade {{ array_key_first($config['tabs']) === 'fiscali' ? 'show active' : '' }}" 
                         id="fiscali" role="tabpanel">
                        <div class="detail-section">
                            <h4 class="section-title">
                                <i class="bi bi-receipt"></i> Dati Fiscali
                            </h4>
                            
                            <div class="detail-grid">
                                <div class="detail-field">
                                    <div class="field-label">Codice Fiscale</div>
                                    <div class="field-value {{ $anagrafica->codice_fiscale ? '' : 'empty' }}">
                                        {{ $anagrafica->codice_fiscale ?: 'Non specificato' }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Partita IVA</div>
                                    <div class="field-value {{ $anagrafica->partita_iva ? '' : 'empty' }}">
                                        {{ $anagrafica->partita_iva ?: 'Non specificato' }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Partita IVA CEE</div>
                                    <div class="field-value {{ $anagrafica->partita_iva_cee ? '' : 'empty' }}">
                                        {{ $anagrafica->partita_iva_cee ?: 'Non specificato' }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Codice SDI</div>
                                    <div class="field-value {{ $anagrafica->codice_sdi ? '' : 'empty' }}">
                                        {{ $anagrafica->codice_sdi ?: 'Non specificato' }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Pubblica Amministrazione</div>
                                    <div class="field-value boolean {{ $anagrafica->is_pubblica_amministrazione ? 'true' : 'false' }}">
                                        <i class="bi bi-{{ $anagrafica->is_pubblica_amministrazione ? 'check-circle' : 'x-circle' }}"></i>
                                        {{ $anagrafica->is_pubblica_amministrazione ? 'Sì' : 'No' }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Split Payment</div>
                                    <div class="field-value boolean {{ $anagrafica->split_payment ? 'true' : 'false' }}">
                                        <i class="bi bi-{{ $anagrafica->split_payment ? 'check-circle' : 'x-circle' }}"></i>
                                        {{ $anagrafica->split_payment ? 'Sì' : 'No' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- TAB COMMERCIALI -->
                    @if(isset($config['tabs']['commerciali']))
                    <div class="tab-pane fade {{ array_key_first($config['tabs']) === 'commerciali' ? 'show active' : '' }}" 
                         id="commerciali" role="tabpanel">
                        <div class="detail-section">
                            <h4 class="section-title">
                                <i class="bi bi-cash-coin"></i> Dati Commerciali
                            </h4>
                            
                            <div class="detail-grid">
                                <div class="detail-field">
                                    <div class="field-label">Sconto 1</div>
                                    <div class="field-value percentage">
                                        {{ $anagrafica->sconto_1 ? $anagrafica->sconto_1 . '%' : '0%' }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Sconto 2</div>
                                    <div class="field-value percentage">
                                        {{ $anagrafica->sconto_2 ? $anagrafica->sconto_2 . '%' : '0%' }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Valuta</div>
                                    <div class="field-value">{{ $anagrafica->valuta ?: 'EUR' }}</div>
                                </div>
                                
                                @if($anagrafica->tipo === 'fornitore')
                                <div class="detail-field">
                                    <div class="field-label">Condizioni Pagamento</div>
                                    <div class="field-value {{ $anagrafica->condizioni_pagamento ? '' : 'empty' }}">
                                        {{ $anagrafica->condizioni_pagamento ?: 'Non specificate' }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Lead Time</div>
                                    <div class="field-value {{ $anagrafica->lead_time_giorni ? '' : 'empty' }}">
                                        {{ $anagrafica->lead_time_giorni ? $anagrafica->lead_time_giorni . ' giorni' : 'Non specificato' }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- TAB BANCARI -->
                    @if(isset($config['tabs']['bancari']))
                    <div class="tab-pane fade {{ array_key_first($config['tabs']) === 'bancari' ? 'show active' : '' }}" 
                         id="bancari" role="tabpanel">
                        <div class="detail-section">
                            <h4 class="section-title">
                                <i class="bi bi-bank"></i> Coordinate Bancarie
                            </h4>
                            
                            <div class="detail-grid">
                                <div class="detail-field wide-field">
                                    <div class="field-label">Banca</div>
                                    <div class="field-value {{ $anagrafica->banca ? '' : 'empty' }}">
                                        {{ $anagrafica->banca ?: 'Non specificato' }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">IBAN</div>
                                    <div class="field-value {{ $anagrafica->iban ? '' : 'empty' }}">
                                        {{ $anagrafica->iban ?: 'Non specificato' }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">SWIFT/BIC</div>
                                    <div class="field-value {{ $anagrafica->swift ? '' : 'empty' }}">
                                        {{ $anagrafica->swift ?: 'Non specificato' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- TAB SPECIFICI per ARTICOLI -->
                    @if(isset($config['tabs']['specifici']) && $anagrafica->tipo === 'articolo')
                    <div class="tab-pane fade {{ array_key_first($config['tabs']) === 'specifici' ? 'show active' : '' }}" 
                         id="specifici" role="tabpanel">
                        <div class="detail-section">
                            <h4 class="section-title">
                                <i class="bi bi-box-seam"></i> Dati Specifici Articolo
                            </h4>
                            
                            <div class="detail-grid">
                                <div class="detail-field">
                                    <div class="field-label">Categoria Articolo</div>
                                    <div class="field-value {{ $anagrafica->categoria_articolo ? '' : 'empty' }}">
                                        {{ $anagrafica->categoria_articolo ?: 'Non specificato' }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Unità di Misura</div>
                                    <div class="field-value {{ $anagrafica->unita_misura ? '' : 'empty' }}">
                                        {{ $anagrafica->unita_misura ?: 'Non specificato' }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Prezzo Acquisto</div>
                                    <div class="field-value currency">
                                        €{{ number_format($anagrafica->prezzo_acquisto ?: 0, 4, ',', '.') }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Prezzo Vendita</div>
                                    <div class="field-value currency">
                                        €{{ number_format($anagrafica->prezzo_vendita ?: 0, 4, ',', '.') }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Scorta Minima</div>
                                    <div class="field-value">{{ $anagrafica->scorta_minima ?: '0' }}</div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Codice a Barre</div>
                                    <div class="field-value {{ $anagrafica->codice_barre ? '' : 'empty' }}">
                                        {{ $anagrafica->codice_barre ?: 'Non specificato' }}
                                    </div>
                                </div>
                                
                                @if($anagrafica->fornitore_principale)
                                <div class="detail-field">
                                    <div class="field-label">Fornitore Principale</div>
                                    <div class="field-value">
                                        <a href="{{ route('anagrafiche.show', ['tipo' => 'fornitore', 'anagrafica' => $anagrafica->fornitore_principale->id]) }}" style="color: inherit; text-decoration: underline;">
                                            {{ $anagrafica->fornitore_principale->codice_interno }} - {{ $anagrafica->fornitore_principale->nome_completo }}
                                        </a>
                                    </div>
                                </div>
                                @endif
                                
                                @if($anagrafica->note_tecniche)
                                <div class="detail-field wide-field">
                                    <div class="field-label">Note Tecniche</div>
                                    <div class="field-value">{{ $anagrafica->note_tecniche }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- TAB SPECIFICI per SERVIZI -->
                    @if(isset($config['tabs']['specifici']) && $anagrafica->tipo === 'servizio')
                    <div class="tab-pane fade {{ array_key_first($config['tabs']) === 'specifici' ? 'show active' : '' }}" 
                         id="specifici" role="tabpanel">
                        <div class="detail-section">
                            <h4 class="section-title">
                                <i class="bi bi-tools"></i> Dati Specifici Servizio
                            </h4>
                            
                            <div class="detail-grid">
                                <div class="detail-field">
                                    <div class="field-label">Categoria Servizio</div>
                                    <div class="field-value {{ $anagrafica->categoria_servizio ? '' : 'empty' }}">
                                        {{ $anagrafica->categoria_servizio ?: 'Non specificato' }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Durata Standard</div>
                                    <div class="field-value {{ $anagrafica->durata_standard_minuti ? '' : 'empty' }}">
                                        {{ $anagrafica->durata_standard_minuti ? $anagrafica->durata_standard_minuti . ' minuti' : 'Non specificato' }}
                                    </div>
                                </div>
                                
                                <div class="detail-field">
                                    <div class="field-label">Tariffa Oraria</div>
                                    <div class="field-value currency">
                                        €{{ number_format($anagrafica->tariffa_oraria ?: 0, 2, ',', '.') }}
                                    </div>
                                </div>
                                
                                @if($anagrafica->competenze_richieste && (is_array($anagrafica->competenze_richieste) ? count($anagrafica->competenze_richieste) > 0 : !empty($anagrafica->competenze_richieste)))
                                <div class="detail-field wide-field">
                                    <div class="field-label">Competenze Richieste</div>
                                    <div class="field-value array">
                                        @if(is_array($anagrafica->competenze_richieste))
                                            @foreach($anagrafica->competenze_richieste as $competenza)
                                                <span class="array-item">{{ $competenza }}</span>
                                            @endforeach
                                        @else
                                            @foreach(explode("\n", $anagrafica->competenze_richieste) as $competenza)
                                                @if(trim($competenza))
                                                    <span class="array-item">{{ trim($competenza) }}</span>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                @endif
                                
                                @if($anagrafica->materiali_inclusi && (is_array($anagrafica->materiali_inclusi) ? count($anagrafica->materiali_inclusi) > 0 : !empty($anagrafica->materiali_inclusi)))
                                <div class="detail-field wide-field">
                                    <div class="field-label">Materiali Inclusi</div>
                                    <div class="field-value array">
                                        @if(is_array($anagrafica->materiali_inclusi))
                                            @foreach($anagrafica->materiali_inclusi as $materiale)
                                                <span class="array-item">{{ $materiale }}</span>
                                            @endforeach
                                        @else
                                            @foreach(explode("\n", $anagrafica->materiali_inclusi) as $materiale)
                                                @if(trim($materiale))
                                                    <span class="array-item">{{ trim($materiale) }}</span>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    
                </div>
                
                <!-- Actions -->
                <div class="detail-actions">
                    <div class="d-flex align-items-center gap-2" style="margin-left: 2rem;">
                        <span class="text-muted">Ultima modifica:</span>
                        <strong>{{ $anagrafica->updated_at->diffForHumans() }}</strong>
                    </div>
                    
                    <div class="action-buttons">
                        <a href="{{ route('anagrafiche.edit', [$anagrafica->tipo, $anagrafica->id]) }}" 
                           class="btn btn-warning" title="Modifica">
                            <i class="bi bi-pencil"></i> <span>Modifica</span>
                        </a>
                        
                        <a href="{{ route('anagrafiche.duplicate', [$anagrafica->tipo, $anagrafica->id]) }}" 
                           class="btn btn-primary" title="Duplica">
                            <i class="bi bi-files"></i> <span>Duplica</span>
                        </a>
                        
                        <button type="button" 
                                class="btn btn-danger" 
                                onclick="confermaEliminazione()" title="Elimina" style="margin-right: 2rem;">
                            <i class="bi bi-trash"></i> <span>Elimina</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal conferma eliminazione -->
<div class="modal fade" id="modalEliminazione" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Conferma Eliminazione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Sei sicuro di voler eliminare questo <strong>{{ $anagrafica->tipo }}</strong>?</p>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Attenzione:</strong> Questa operazione non può essere annullata.
                </div>
                <p><strong>{{ $anagrafica->codice_interno }}</strong> - {{ $anagrafica->descrizione }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <form action="{{ route('anagrafiche.destroy', [$anagrafica->tipo, $anagrafica->id]) }}" 
                      method="POST" 
                      style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Elimina definitivamente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confermaEliminazione() {
    const modal = new bootstrap.Modal(document.getElementById('modalEliminazione'));
    modal.show();
}
</script>
@endsection