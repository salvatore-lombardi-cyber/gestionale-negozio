@extends('layouts.app')

@section('title', 'Crea ' . ucfirst($tipo) . ' - Anagrafiche')

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
    
    .form-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 0;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .form-content {
        padding: 2rem;
    }
    
    .form-section {
        background: rgba(255, 255, 255, 0.7);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(226, 232, 240, 0.5);
        transition: all 0.3s ease;
    }
    
    .form-section:hover {
        background: rgba(255, 255, 255, 0.9);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #029D7E;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid rgba(2, 157, 126, 0.2);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .section-title i {
        font-size: 1.4rem;
        color: #029D7E;
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
    
    
    .required {
        color: #dc3545;
    }
    
    .campo-calcolato {
        background: #f8f9fa !important;
        border-style: dashed !important;
        cursor: not-allowed;
    }
    
    
    /* Indicatore sezione attiva durante lo scroll */
    .form-section.in-view {
        border-left: 4px solid #029D7E;
        background: rgba(2, 157, 126, 0.05);
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
        
        .form-content {
            padding: 1.5rem;
        }
        
        .form-section {
            padding: 1rem;
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
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="form-title">
                            <i class="{{ $config['icon'] }}"></i> 
                            Nuovo {{ ucfirst($tipo) }}
                        </h1>
                        <p class="form-subtitle">{{ $config['descrizione'] }}</p>
                    </div>
                    <a href="{{ route('anagrafiche.lista', $tipo) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Torna Indietro
                    </a>
                </div>
            </div>
            
            <!-- Form -->
            <div class="form-container">
                <form action="{{ route('anagrafiche.store') }}" method="POST" id="form-anagrafica">
                    @csrf
                    <input type="hidden" name="tipo" value="{{ $tipo }}">
                    
                    <!-- Form Content Unificato -->
                    <div class="form-content">
                        
                        <!-- SEZIONE DATI BASE -->
                        @if(isset($config['tabs']['dati-base']))
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
                                                   placeholder="Generato automaticamente"
                                                   readonly>
                                        </div>
                                    </div>
                                    
                                    @if(in_array($tipo, ['cliente', 'fornitore', 'vettore', 'agente']))
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Tipo Soggetto</label>
                                            <select name="tipo_soggetto" class="form-select">
                                                <option value="">Seleziona...</option>
                                                <option value="persona_fisica">Persona Fisica</option>
                                                <option value="ditta_individuale">Ditta Individuale</option>
                                                <option value="snc">SNC</option>
                                                <option value="sas">SAS</option>
                                                <option value="srl">SRL</option>
                                                <option value="spa">SPA</option>
                                                <option value="associazione">Associazione</option>
                                                <option value="ente_pubblico">Ente Pubblico</option>
                                                <option value="altro">Altro</option>
                                            </select>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                {{ in_array($tipo, ['articolo', 'servizio']) ? 'Nome/Denominazione' : 'Nome' }} 
                                                <span class="required">*</span>
                                            </label>
                                            <input type="text" 
                                                   name="nome" 
                                                   class="form-control" 
                                                   required
                                                   placeholder="{{ in_array($tipo, ['articolo', 'servizio']) ? 'Inserisci il nome' : 'Inserisci il nome' }}">
                                        </div>
                                    </div>
                                    
                                    @if(!in_array($tipo, ['articolo', 'servizio']))
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Cognome</label>
                                            <input type="text" 
                                                   name="cognome" 
                                                   class="form-control"
                                                   placeholder="Inserisci il cognome">
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if(in_array($tipo, ['articolo']))
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Descrizione Estesa</label>
                                            <input type="text" 
                                                   name="descrizione_estesa" 
                                                   class="form-control"
                                                   placeholder="Descrizione dettagliata dell'articolo">
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
                                                     rows="3"
                                                     placeholder="Note aggiuntive o informazioni specifiche"></textarea>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        @endif
                        
                        <!-- SEZIONE INDIRIZZO -->
                        @if(isset($config['tabs']['indirizzo']))
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
                                                   placeholder="Via, Piazza, etc.">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">CAP</label>
                                            <input type="text" 
                                                   name="cap" 
                                                   class="form-control"
                                                   placeholder="00000">
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
                                                   placeholder="Nome del comune">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Provincia</label>
                                            <input type="text" 
                                                   name="provincia" 
                                                   class="form-control"
                                                   placeholder="Sigla provincia">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Nazione</label>
                                            <select name="nazione" class="form-select">
                                                <option value="ITA" selected>Italia</option>
                                                <option value="DEU">Germania</option>
                                                <option value="FRA">Francia</option>
                                                <option value="ESP">Spagna</option>
                                                <option value="CHE">Svizzera</option>
                                                <option value="AUT">Austria</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        @endif
                        
                        <!-- SEZIONE CONTATTI -->
                        @if(isset($config['tabs']['contatti']))
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
                                                   placeholder="+39 000 0000000">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Telefono 2</label>
                                            <input type="tel" 
                                                   name="telefono_2" 
                                                   class="form-control"
                                                   placeholder="+39 000 0000000">
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
                                                   placeholder="email@esempio.com">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">PEC</label>
                                            <input type="email" 
                                                   name="pec" 
                                                   class="form-control"
                                                   placeholder="pec@esempio.com">
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
                                                   placeholder="https://www.esempio.com">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Contatto Referente</label>
                                            <input type="text" 
                                                   name="contatto_referente" 
                                                   class="form-control"
                                                   placeholder="Nome del referente">
                                        </div>
                                    </div>
                                </div>
                        </div>
                        @endif
                        
                        <!-- SEZIONE FISCALI -->
                        @if(isset($config['tabs']['fiscali']))
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
                                                   placeholder="RSSMRA80A01H501U">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Partita IVA</label>
                                            <input type="text" 
                                                   name="partita_iva" 
                                                   class="form-control"
                                                   placeholder="12345678901">
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
                                                   placeholder="IT12345678901">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Codice SDI</label>
                                            <input type="text" 
                                                   name="codice_sdi" 
                                                   class="form-control"
                                                   placeholder="ABCDEFG">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input type="checkbox" 
                                                   name="is_pubblica_amministrazione" 
                                                   class="form-check-input" 
                                                   id="pubblica_amministrazione">
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
                                                   id="split_payment">
                                            <label class="form-check-label" for="split_payment">
                                                Split Payment
                                            </label>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        @endif
                        
                        <!-- SEZIONE COMMERCIALI -->
                        @if(isset($config['tabs']['commerciali']))
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
                                                   placeholder="0.00">
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
                                                   placeholder="0.00">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Valuta</label>
                                            <select name="valuta" class="form-select">
                                                <option value="EUR" selected>Euro (EUR)</option>
                                                <option value="USD">Dollaro USA (USD)</option>
                                                <option value="GBP">Sterlina (GBP)</option>
                                                <option value="CHF">Franco Svizzero (CHF)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($tipo === 'fornitore')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Condizioni Pagamento</label>
                                            <input type="text" 
                                                   name="condizioni_pagamento" 
                                                   class="form-control"
                                                   placeholder="es. 30 gg f.m.">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Lead Time (giorni)</label>
                                            <input type="number" 
                                                   name="lead_time_giorni" 
                                                   class="form-control"
                                                   min="0"
                                                   placeholder="Giorni di consegna">
                                        </div>
                                    </div>
                                </div>
                                @endif
                        </div>
                        @endif
                        
                        <!-- SEZIONE BANCARI -->
                        @if(isset($config['tabs']['bancari']))
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
                                                   placeholder="Nome della banca">
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
                                                   placeholder="IT00A0000000000000000000000">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">SWIFT/BIC</label>
                                            <input type="text" 
                                                   name="swift" 
                                                   class="form-control"
                                                   placeholder="ABCDITM1XXX">
                                        </div>
                                    </div>
                                </div>
                        </div>
                        @endif
                        
                        <!-- SEZIONE SPECIFICI per ARTICOLI -->
                        @if(isset($config['tabs']['specifici']) && $tipo === 'articolo')
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
                                                   placeholder="Categoria merceologica">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Unità di Misura</label>
                                            <select name="unita_misura" class="form-select">
                                                <option value="">Seleziona...</option>
                                                <option value="pz">Pezzi (pz)</option>
                                                <option value="kg">Chilogrammi (kg)</option>
                                                <option value="mt">Metri (mt)</option>
                                                <option value="mq">Metri quadrati (mq)</option>
                                                <option value="lt">Litri (lt)</option>
                                                <option value="cf">Confezioni (cf)</option>
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
                                                   placeholder="0.0000">
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
                                                   placeholder="0.0000">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Scorta Minima</label>
                                            <input type="number" 
                                                   name="scorta_minima" 
                                                   class="form-control"
                                                   min="0"
                                                   placeholder="Quantità minima">
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
                                                   placeholder="Codice EAN/UPC">
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
                                                     rows="3"
                                                     placeholder="Specifiche tecniche, istruzioni d'uso, etc."></textarea>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        @endif
                        
                        <!-- SEZIONE SPECIFICI per SERVIZI -->
                        @if(isset($config['tabs']['specifici']) && $tipo === 'servizio')
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
                                                   placeholder="Tipologia di servizio">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Durata Standard (minuti)</label>
                                            <input type="number" 
                                                   name="durata_standard_minuti" 
                                                   class="form-control"
                                                   min="0"
                                                   placeholder="Durata in minuti">
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
                                                   placeholder="0.00">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Competenze Richieste</label>
                                            <textarea name="competenze_richieste" 
                                                     class="form-control" 
                                                     rows="3"
                                                     placeholder="Competenze necessarie per erogare il servizio (una per riga)"></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">Materiali Inclusi</label>
                                            <textarea name="materiali_inclusi" 
                                                     class="form-control" 
                                                     rows="3"
                                                     placeholder="Materiali/strumenti inclusi nel servizio (uno per riga)"></textarea>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        @endif
                        
                        <!-- Actions Section -->
                        <div class="form-section" style="text-align: center; background: rgba(248, 249, 250, 0.8);">
                            <div class="d-flex gap-3 justify-content-center">
                                <a href="{{ route('anagrafiche.lista', $tipo) }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Annulla
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Salva {{ ucfirst($tipo) }}
                                </button>
                            </div>
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
    
    // Evidenziazione sezione attiva durante scroll della pagina
    const sections = document.querySelectorAll('.form-section');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Rimuovi evidenziazione da tutte le sezioni
                sections.forEach(section => section.classList.remove('in-view'));
                // Aggiungi evidenziazione alla sezione corrente
                entry.target.classList.add('in-view');
            }
        });
    }, {
        threshold: 0.4
    });
    
    // Osserva tutte le sezioni
    sections.forEach(section => {
        observer.observe(section);
    });
    
    // Carica fornitori per articoli
    @if($tipo === 'articolo')
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

@if($tipo === 'articolo')
function loadFornitori() {
    fetch('/anagrafiche/fornitori-list')
        .then(response => response.json())
        .then(data => {
            const select = document.querySelector('select[name="fornitore_principale_id"]');
            data.forEach(fornitore => {
                const option = document.createElement('option');
                option.value = fornitore.id;
                option.textContent = `${fornitore.codice_interno} - ${fornitore.nome_completo}`;
                select.appendChild(option);
            });
        })
        .catch(error => console.error('Errore caricamento fornitori:', error));
}
@endif
</script>
@endsection