@extends('layouts.app')

@section('title', 'Modifica ' . ucfirst($anagrafica->tipo) . ' - Anagrafiche')

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
    
    .form-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .form-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .form-subtitle {
        color: #6c757d;
        font-size: 1rem;
        margin-top: 0.5rem;
        margin-bottom: 0;
    }
    
    .record-info {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 0.75rem 1rem;
        margin-top: 1rem;
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .form-container {
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
    
    .form-section {
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
    }
    
    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
        background: white;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(2, 157, 126, 0.3);
    }
    
    .btn-secondary {
        background: #6c757d;
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(108, 117, 125, 0.3);
    }

    .btn-annulla {
        margin-right: 2rem;
    }

    /* .form-check-label-attivo {
        margin-left: 2rem ;
    } */

    .form-check-input{
        margin-left: 2rem !important;
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #ffc107 0%, #ffca2c 100%);
        border: none;
        border-radius: 10px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        color: #212529;
    }
    
    .btn-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(255, 193, 7, 0.3);
        color: #212529;
    }
    
    .form-actions {
        background: rgba(248, 249, 250, 0.8);
        padding: 1.5rem 2rem;
        margin: -2rem -2rem 0 -2rem;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .required {
        color: #dc3545;
    }
    
    .campo-calcolato {
        background: #f8f9fa !important;
        border-style: dashed !important;
        cursor: not-allowed;
    }
    
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
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
    
    @media (max-width: 768px) {
        .anagrafiche-container {
            padding: 1rem;
        }
        
        .form-header {
            padding: 1rem 1.5rem;
        }
        
        .form-title {
            font-size: 1.5rem;
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
        
        .form-actions {
            padding: 1rem 1.5rem;
            margin: -1.5rem -1.5rem 0 -1.5rem;
            flex-direction: column;
            gap: 1rem;
        }
        
        .btn-group {
            width: 100%;
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-group .btn {
            flex: 1;
        }
    }
</style>

<div class="container-fluid anagrafiche-container">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
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

    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="form-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h1 class="form-title">
                            <i class="{{ $config['icon'] }}"></i> 
                            Modifica {{ ucfirst($anagrafica->tipo) }}
                        </h1>
                        <p class="form-subtitle">{{ $config['descrizione'] }}</p>
                        <div class="record-info">
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <span><strong>Codice:</strong> {{ $anagrafica->codice_interno }}</span>
                                <span><strong>Creato:</strong> {{ $anagrafica->created_at->format('d/m/Y H:i') }}</span>
                                <span><strong>Modificato:</strong> {{ $anagrafica->updated_at->format('d/m/Y H:i') }}</span>
                                <span class="status-badge {{ $anagrafica->attivo ? 'status-attivo' : 'status-disattivo' }}">
                                    <i class="bi bi-{{ $anagrafica->attivo ? 'check-circle' : 'x-circle' }}"></i>
                                    {{ $anagrafica->attivo ? 'Attivo' : 'Disattivo' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('anagrafiche.lista', $anagrafica->tipo) }}" 
                           class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Torna alla lista
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Form -->
            <div class="form-container">
                <form action="{{ route('anagrafiche.update', [$anagrafica->tipo, $anagrafica->id]) }}" 
                      method="POST" 
                      id="form-anagrafica">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="tipo" value="{{ $anagrafica->tipo }}">
                    
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
                            <div class="form-section">
                                <h4 class="section-title">
                                    <i class="bi bi-person-fill"></i> Informazioni Base
                                </h4>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                Codice Interno <span class="required">*</span>
                                            </label>
                                            <input type="text" 
                                                   name="codice_interno" 
                                                   class="form-control campo-calcolato" 
                                                   value="{{ $anagrafica->codice_interno }}"
                                                   readonly>
                                        </div>
                                    </div>
                                    
                                    @if(in_array($anagrafica->tipo, ['cliente', 'fornitore', 'vettore', 'agente']))
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Tipo Soggetto</label>
                                            <select name="tipo_soggetto" class="form-select">
                                                <option value="">Seleziona...</option>
                                                <option value="persona_fisica" {{ $anagrafica->tipo_soggetto === 'persona_fisica' ? 'selected' : '' }}>Persona Fisica</option>
                                                <option value="ditta_individuale" {{ $anagrafica->tipo_soggetto === 'ditta_individuale' ? 'selected' : '' }}>Ditta Individuale</option>
                                                <option value="snc" {{ $anagrafica->tipo_soggetto === 'snc' ? 'selected' : '' }}>SNC</option>
                                                <option value="sas" {{ $anagrafica->tipo_soggetto === 'sas' ? 'selected' : '' }}>SAS</option>
                                                <option value="srl" {{ $anagrafica->tipo_soggetto === 'srl' ? 'selected' : '' }}>SRL</option>
                                                <option value="spa" {{ $anagrafica->tipo_soggetto === 'spa' ? 'selected' : '' }}>SPA</option>
                                                <option value="associazione" {{ $anagrafica->tipo_soggetto === 'associazione' ? 'selected' : '' }}>Associazione</option>
                                                <option value="ente_pubblico" {{ $anagrafica->tipo_soggetto === 'ente_pubblico' ? 'selected' : '' }}>Ente Pubblico</option>
                                                <option value="altro" {{ $anagrafica->tipo_soggetto === 'altro' ? 'selected' : '' }}>Altro</option>
                                            </select>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                {{ in_array($anagrafica->tipo, ['articolo', 'servizio']) ? 'Nome/Denominazione' : 'Nome' }} 
                                                <span class="required">*</span>
                                            </label>
                                            <input type="text" 
                                                   name="nome" 
                                                   class="form-control" 
                                                   value="{{ $anagrafica->nome }}"
                                                   required>
                                        </div>
                                    </div>
                                    
                                    @if(!in_array($anagrafica->tipo, ['articolo', 'servizio']))
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Cognome</label>
                                            <input type="text" 
                                                   name="cognome" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->cognome }}">
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if(in_array($anagrafica->tipo, ['articolo']))
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Descrizione Estesa</label>
                                            <input type="text" 
                                                   name="descrizione_estesa" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->descrizione_estesa }}">
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Altro (Note aggiuntive)</label>
                                            <textarea name="altro" 
                                                     class="form-control" 
                                                     rows="3">{{ $anagrafica->altro }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- TAB INDIRIZZO -->
                        @if(isset($config['tabs']['indirizzo']))
                        <div class="tab-pane fade {{ array_key_first($config['tabs']) === 'indirizzo' ? 'show active' : '' }}" 
                             id="indirizzo" role="tabpanel">
                            <div class="form-section">
                                <h4 class="section-title">
                                    <i class="bi bi-geo-alt-fill"></i> Indirizzo
                                </h4>
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="form-label">Indirizzo</label>
                                            <input type="text" 
                                                   name="indirizzo" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->indirizzo }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">CAP</label>
                                            <input type="text" 
                                                   name="cap" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->cap }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Comune</label>
                                            <input type="text" 
                                                   name="comune" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->comune }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Provincia</label>
                                            <input type="text" 
                                                   name="provincia" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->provincia }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Nazione</label>
                                            <select name="nazione" class="form-select">
                                                <option value="ITA" {{ $anagrafica->nazione === 'ITA' ? 'selected' : '' }}>Italia</option>
                                                <option value="DEU" {{ $anagrafica->nazione === 'DEU' ? 'selected' : '' }}>Germania</option>
                                                <option value="FRA" {{ $anagrafica->nazione === 'FRA' ? 'selected' : '' }}>Francia</option>
                                                <option value="ESP" {{ $anagrafica->nazione === 'ESP' ? 'selected' : '' }}>Spagna</option>
                                                <option value="CHE" {{ $anagrafica->nazione === 'CHE' ? 'selected' : '' }}>Svizzera</option>
                                                <option value="AUT" {{ $anagrafica->nazione === 'AUT' ? 'selected' : '' }}>Austria</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- TAB CONTATTI -->
                        @if(isset($config['tabs']['contatti']))
                        <div class="tab-pane fade {{ array_key_first($config['tabs']) === 'contatti' ? 'show active' : '' }}" 
                             id="contatti" role="tabpanel">
                            <div class="form-section">
                                <h4 class="section-title">
                                    <i class="bi bi-telephone-fill"></i> Contatti
                                </h4>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Telefono 1</label>
                                            <input type="tel" 
                                                   name="telefono_1" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->telefono_1 }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Telefono 2</label>
                                            <input type="tel" 
                                                   name="telefono_2" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->telefono_2 }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Email</label>
                                            <input type="email" 
                                                   name="email" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->email }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">PEC</label>
                                            <input type="email" 
                                                   name="pec" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->pec }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Sito Web</label>
                                            <input type="url" 
                                                   name="sito_web" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->sito_web }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Contatto Referente</label>
                                            <input type="text" 
                                                   name="contatto_referente" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->contatto_referente }}">
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
                            <div class="form-section">
                                <h4 class="section-title">
                                    <i class="bi bi-receipt"></i> Dati Fiscali
                                </h4>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Codice Fiscale</label>
                                            <input type="text" 
                                                   name="codice_fiscale" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->codice_fiscale }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Partita IVA</label>
                                            <input type="text" 
                                                   name="partita_iva" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->partita_iva }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Partita IVA CEE</label>
                                            <input type="text" 
                                                   name="partita_iva_cee" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->partita_iva_cee }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Codice SDI</label>
                                            <input type="text" 
                                                   name="codice_sdi" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->codice_sdi }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input type="checkbox" 
                                                   name="is_pubblica_amministrazione" 
                                                   class="form-check-input" 
                                                   id="pubblica_amministrazione"
                                                   {{ $anagrafica->is_pubblica_amministrazione ? 'checked' : '' }}>
                                            <label class="form-check-label" for="pubblica_amministrazione">
                                                Pubblica Amministrazione
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input type="checkbox" 
                                                   name="split_payment" 
                                                   class="form-check-input" 
                                                   id="split_payment"
                                                   {{ $anagrafica->split_payment ? 'checked' : '' }}>
                                            <label class="form-check-label" for="split_payment">
                                                Split Payment
                                            </label>
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
                            <div class="form-section">
                                <h4 class="section-title">
                                    <i class="bi bi-cash-coin"></i> Dati Commerciali
                                </h4>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Sconto 1 (%)</label>
                                            <input type="number" 
                                                   name="sconto_1" 
                                                   class="form-control"
                                                   step="0.01"
                                                   min="0"
                                                   max="100"
                                                   value="{{ $anagrafica->sconto_1 }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Sconto 2 (%)</label>
                                            <input type="number" 
                                                   name="sconto_2" 
                                                   class="form-control"
                                                   step="0.01"
                                                   min="0"
                                                   max="100"
                                                   value="{{ $anagrafica->sconto_2 }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Valuta</label>
                                            <select name="valuta" class="form-select">
                                                <option value="EUR" {{ $anagrafica->valuta === 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                                                <option value="USD" {{ $anagrafica->valuta === 'USD' ? 'selected' : '' }}>Dollaro USA (USD)</option>
                                                <option value="GBP" {{ $anagrafica->valuta === 'GBP' ? 'selected' : '' }}>Sterlina (GBP)</option>
                                                <option value="CHF" {{ $anagrafica->valuta === 'CHF' ? 'selected' : '' }}>Franco Svizzero (CHF)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($anagrafica->tipo === 'fornitore')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Condizioni Pagamento</label>
                                            <input type="text" 
                                                   name="condizioni_pagamento" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->condizioni_pagamento }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Lead Time (giorni)</label>
                                            <input type="number" 
                                                   name="lead_time_giorni" 
                                                   class="form-control"
                                                   min="0"
                                                   value="{{ $anagrafica->lead_time_giorni }}">
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        <!-- TAB BANCARI -->
                        @if(isset($config['tabs']['bancari']))
                        <div class="tab-pane fade {{ array_key_first($config['tabs']) === 'bancari' ? 'show active' : '' }}" 
                             id="bancari" role="tabpanel">
                            <div class="form-section">
                                <h4 class="section-title">
                                    <i class="bi bi-bank"></i> Coordinate Bancarie
                                </h4>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Banca</label>
                                            <input type="text" 
                                                   name="banca" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->banca }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="form-label">IBAN</label>
                                            <input type="text" 
                                                   name="iban" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->iban }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">SWIFT/BIC</label>
                                            <input type="text" 
                                                   name="swift" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->swift }}">
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
                            <div class="form-section">
                                <h4 class="section-title">
                                    <i class="bi bi-box-seam"></i> Dati Specifici Articolo
                                </h4>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Categoria Articolo</label>
                                            <input type="text" 
                                                   name="categoria_articolo" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->categoria_articolo }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Unità di Misura</label>
                                            <select name="unita_misura" class="form-select">
                                                <option value="">Seleziona...</option>
                                                <option value="pz" {{ $anagrafica->unita_misura === 'pz' ? 'selected' : '' }}>Pezzi (pz)</option>
                                                <option value="kg" {{ $anagrafica->unita_misura === 'kg' ? 'selected' : '' }}>Chilogrammi (kg)</option>
                                                <option value="mt" {{ $anagrafica->unita_misura === 'mt' ? 'selected' : '' }}>Metri (mt)</option>
                                                <option value="mq" {{ $anagrafica->unita_misura === 'mq' ? 'selected' : '' }}>Metri quadrati (mq)</option>
                                                <option value="lt" {{ $anagrafica->unita_misura === 'lt' ? 'selected' : '' }}>Litri (lt)</option>
                                                <option value="cf" {{ $anagrafica->unita_misura === 'cf' ? 'selected' : '' }}>Confezioni (cf)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Prezzo Acquisto (€)</label>
                                            <input type="number" 
                                                   name="prezzo_acquisto" 
                                                   class="form-control"
                                                   step="0.0001"
                                                   min="0"
                                                   value="{{ $anagrafica->prezzo_acquisto }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Prezzo Vendita (€)</label>
                                            <input type="number" 
                                                   name="prezzo_vendita" 
                                                   class="form-control"
                                                   step="0.0001"
                                                   min="0"
                                                   value="{{ $anagrafica->prezzo_vendita }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Scorta Minima</label>
                                            <input type="number" 
                                                   name="scorta_minima" 
                                                   class="form-control"
                                                   min="0"
                                                   value="{{ $anagrafica->scorta_minima }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Codice a Barre</label>
                                            <input type="text" 
                                                   name="codice_barre" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->codice_barre }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Fornitore Principale</label>
                                            <select name="fornitore_principale_id" class="form-select">
                                                <option value="">Seleziona fornitore...</option>
                                                <!-- Verrà popolato dinamicamente -->
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Note Tecniche</label>
                                            <textarea name="note_tecniche" 
                                                     class="form-control" 
                                                     rows="3">{{ $anagrafica->note_tecniche }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- TAB SPECIFICI per SERVIZI -->
                        @if(isset($config['tabs']['specifici']) && $anagrafica->tipo === 'servizio')
                        <div class="tab-pane fade {{ array_key_first($config['tabs']) === 'specifici' ? 'show active' : '' }}" 
                             id="specifici" role="tabpanel">
                            <div class="form-section">
                                <h4 class="section-title">
                                    <i class="bi bi-tools"></i> Dati Specifici Servizio
                                </h4>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Categoria Servizio</label>
                                            <input type="text" 
                                                   name="categoria_servizio" 
                                                   class="form-control"
                                                   value="{{ $anagrafica->categoria_servizio }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Durata Standard (minuti)</label>
                                            <input type="number" 
                                                   name="durata_standard_minuti" 
                                                   class="form-control"
                                                   min="0"
                                                   value="{{ $anagrafica->durata_standard_minuti }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Tariffa Oraria (€)</label>
                                            <input type="number" 
                                                   name="tariffa_oraria" 
                                                   class="form-control"
                                                   step="0.01"
                                                   min="0"
                                                   value="{{ $anagrafica->tariffa_oraria }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Competenze Richieste</label>
                                            <textarea name="competenze_richieste" 
                                                     class="form-control" 
                                                     rows="3">{{ is_array($anagrafica->competenze_richieste) ? implode("\n", $anagrafica->competenze_richieste) : $anagrafica->competenze_richieste }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Materiali Inclusi</label>
                                            <textarea name="materiali_inclusi" 
                                                     class="form-control" 
                                                     rows="3">{{ is_array($anagrafica->materiali_inclusi) ? implode("\n", $anagrafica->materiali_inclusi) : $anagrafica->materiali_inclusi }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                    </div>
                    
                    <!-- Actions -->
                    <div class="form-actions">
                        <div class="form-check">
                            <input type="checkbox" 
                                   name="attivo" 
                                   class="form-check-input" 
                                   id="attivo"
                                   {{ $anagrafica->attivo ? 'checked' : '' }}>
                            <label class="form-check-label form-check-label-attivo" for="attivo">
                                <strong>{{ ucfirst($anagrafica->tipo) }} attivo</strong>
                            </label>
                        </div>
                        
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Salva Modifiche
                            </button>
                            <a href="{{ route('anagrafiche.lista', $anagrafica->tipo) }}" 
                               class="btn btn-secondary btn-annulla">
                                <i class="bi bi-x-circle"></i> Annulla
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validazione form
    const form = document.getElementById('form-anagrafica');
    
    form.addEventListener('submit', function(e) {
        const nome = document.querySelector('input[name="nome"]');
        
        if (!nome.value.trim()) {
            e.preventDefault();
            alert('Il nome è obbligatorio!');
            nome.focus();
            return false;
        }
    });
    
    // Carica fornitori per articoli
    @if($anagrafica->tipo === 'articolo')
    loadFornitori();
    @endif
    
    // Auto-dismiss alerts dopo 5 secondi
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});

@if($anagrafica->tipo === 'articolo')
function loadFornitori() {
    fetch('/anagrafiche/fornitori-list')
        .then(response => response.json())
        .then(data => {
            const select = document.querySelector('select[name="fornitore_principale_id"]');
            data.forEach(fornitore => {
                const option = document.createElement('option');
                option.value = fornitore.id;
                option.textContent = `${fornitore.codice_interno} - ${fornitore.nome_completo}`;
                option.selected = fornitore.id === {{ $anagrafica->fornitore_principale_id ?? 'null' }};
                select.appendChild(option);
            });
        })
        .catch(error => console.error('Errore caricamento fornitori:', error));
}
@endif
</script>
@endsection