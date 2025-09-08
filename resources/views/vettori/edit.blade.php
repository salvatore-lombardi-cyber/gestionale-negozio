@extends('layouts.app')

@section('title', 'Modifica Vettore - Gestionale Negozio')

@section('content')
<style>
    .edit-container {
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
    
    .form-card {
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
    
    .modern-input, .modern-select {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid rgba(2, 157, 126, 0.2);
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        margin-bottom: 0.5rem;
    }
    
    .modern-input:focus, .modern-select:focus {
        outline: none;
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
        background: white;
        transform: translateY(-2px);
    }
    
    .modern-input.is-invalid, .modern-select.is-invalid {
        border-color: #f72585;
        box-shadow: 0 0 0 0.2rem rgba(247, 37, 133, 0.25);
    }
    
    .modern-textarea {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid rgba(2, 157, 126, 0.2);
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        resize: vertical;
        min-height: 120px;
        margin-bottom: 0.5rem;
    }
    
    .modern-textarea:focus {
        outline: none;
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
        background: white;
        transform: translateY(-2px);
    }
    
    .form-label {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    
    .form-label.required::after {
        content: ' *';
        color: #f72585;
    }
    
    .form-text {
        font-size: 0.8rem;
        color: #6c757d;
        margin-top: 0.25rem;
    }
    
    .invalid-feedback {
        color: #f72585;
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }
    
    .modern-btn {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        border-radius: 15px;
        padding: 15px 30px;
        font-weight: 600;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 1rem;
    }
    
    .modern-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #6c757d, #5a6268);
    }
    
    .btn-secondary:hover {
        box-shadow: 0 15px 35px rgba(108, 117, 125, 0.4);
    }
    
    .form-check-input {
        width: 1.5rem;
        height: 1.5rem;
        margin-right: 0.5rem;
    }
    
    .form-check-label {
        font-weight: 500;
        color: #4a5568;
    }
    
    .input-group-text {
        background: rgba(2, 157, 126, 0.1);
        border: 2px solid rgba(2, 157, 126, 0.2);
        border-right: none;
        color: #029D7E;
        font-weight: 600;
    }
    
    .input-group .modern-input {
        border-left: none;
        border-radius: 0 15px 15px 0;
    }
    
    .input-group .form-control:first-child {
        border-radius: 15px 0 0 15px;
    }
    
    .checkbox-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .edit-container {
            padding: 1rem;
        }
        
        .page-title {
            font-size: 2rem;
        }
        
        .form-card {
            padding: 1.5rem;
        }
    }
</style>

<div class="edit-container">
    <!-- Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">
                <i class="bi bi-pencil"></i> Modifica Vettore
            </h1>
            <a href="{{ route('vettori.index') }}" class="btn btn-secondary modern-btn">
                <i class="bi bi-arrow-left"></i> Torna all'Elenco
            </a>
        </div>
        <p class="text-muted mt-2 mb-0">Modifica i dati del vettore {{ $vettore->nome_completo }}</p>
    </div>

    <form method="POST" action="{{ route('vettori.update', $vettore) }}" novalidate>
        @csrf
        @method('PUT')
        
        <!-- Dati Identificativi -->
        <div class="form-card">
            <div class="section-title">
                <i class="bi bi-building"></i> Dati Identificativi
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label required">Tipo Soggetto</label>
                    <select name="tipo_soggetto" class="form-control modern-select @error('tipo_soggetto') is-invalid @enderror" required>
                        <option value="">Seleziona tipo</option>
                        <option value="persona_giuridica" {{ old('tipo_soggetto', $vettore->tipo_soggetto) == 'persona_giuridica' ? 'selected' : '' }}>Azienda / Persona Giuridica</option>
                        <option value="persona_fisica" {{ old('tipo_soggetto', $vettore->tipo_soggetto) == 'persona_fisica' ? 'selected' : '' }}>Persona Fisica</option>
                        <option value="ente_pubblico" {{ old('tipo_soggetto', $vettore->tipo_soggetto) == 'ente_pubblico' ? 'selected' : '' }}>Ente Pubblico</option>
                    </select>
                    @error('tipo_soggetto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Determina i campi obbligatori per il vettore</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label required">Codice Vettore</label>
                    <input type="text" name="codice_vettore" class="form-control modern-input @error('codice_vettore') is-invalid @enderror" 
                           value="{{ old('codice_vettore', $vettore->codice_vettore) }}" required maxlength="10" style="text-transform: uppercase;">
                    @error('codice_vettore')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Codice univoco (max 10 caratteri, es. DHL001)</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label required">Tipo Vettore</label>
                    <select name="tipo_vettore" class="form-control modern-select @error('tipo_vettore') is-invalid @enderror" required>
                        <option value="">Seleziona tipo</option>
                        <option value="corriere_espresso" {{ old('tipo_vettore', $vettore->tipo_vettore) == 'corriere_espresso' ? 'selected' : '' }}>Corriere Espresso</option>
                        <option value="trasporto_standard" {{ old('tipo_vettore', $vettore->tipo_vettore) == 'trasporto_standard' ? 'selected' : '' }}>Trasporto Standard</option>
                        <option value="trasporto_pesante" {{ old('tipo_vettore', $vettore->tipo_vettore) == 'trasporto_pesante' ? 'selected' : '' }}>Trasporto Pesante</option>
                        <option value="logistica_integrata" {{ old('tipo_vettore', $vettore->tipo_vettore) == 'logistica_integrata' ? 'selected' : '' }}>Logistica Integrata</option>
                        <option value="posta_ordinaria" {{ old('tipo_vettore', $vettore->tipo_vettore) == 'posta_ordinaria' ? 'selected' : '' }}>Posta Ordinaria</option>
                    </select>
                    @error('tipo_vettore')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label required">Ragione Sociale</label>
                    <input type="text" name="ragione_sociale" class="form-control modern-input @error('ragione_sociale') is-invalid @enderror" 
                           value="{{ old('ragione_sociale', $vettore->ragione_sociale) }}" required>
                    @error('ragione_sociale')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Nome ufficiale per fatturazione e documenti</div>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nome Commerciale</label>
                    <input type="text" name="nome_commerciale" class="form-control modern-input @error('nome_commerciale') is-invalid @enderror" 
                           value="{{ old('nome_commerciale', $vettore->nome_commerciale) }}">
                    @error('nome_commerciale')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Nome con cui è conosciuto</div>
                </div>
            </div>

            <!-- Campi persona fisica (nascosti di default) -->
            <div class="row" id="persona-fisica-fields" style="display: {{ $vettore->tipo_soggetto == 'persona_fisica' ? 'block' : 'none' }};">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control modern-input @error('nome') is-invalid @enderror" 
                           value="{{ old('nome', $vettore->nome) }}">
                    @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Cognome</label>
                    <input type="text" name="cognome" class="form-control modern-input @error('cognome') is-invalid @enderror" 
                           value="{{ old('cognome', $vettore->cognome) }}">
                    @error('cognome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Data di Nascita</label>
                    <input type="date" name="data_nascita" class="form-control modern-input @error('data_nascita') is-invalid @enderror" 
                           value="{{ old('data_nascita', $vettore->data_nascita?->format('Y-m-d')) }}">
                    @error('data_nascita')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Dati Fiscali -->
        <div class="form-card">
            <div class="section-title">
                <i class="bi bi-receipt"></i> Dati Fiscali
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label required">Codice Fiscale</label>
                    <input type="text" name="codice_fiscale" class="form-control modern-input @error('codice_fiscale') is-invalid @enderror" 
                           value="{{ old('codice_fiscale', $vettore->codice_fiscale) }}" required maxlength="16" style="text-transform: uppercase;">
                    @error('codice_fiscale')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">16 caratteri per persone fisiche, 11 per aziende</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Partita IVA</label>
                    <input type="text" name="partita_iva" class="form-control modern-input @error('partita_iva') is-invalid @enderror" 
                           value="{{ old('partita_iva', $vettore->partita_iva) }}" maxlength="11" pattern="[0-9]{11}">
                    @error('partita_iva')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">11 cifre numeriche (obbligatoria per aziende)</div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Regime Fiscale</label>
                    <select name="regime_fiscale" class="form-control modern-select @error('regime_fiscale') is-invalid @enderror">
                        <option value="RF01" {{ old('regime_fiscale', $vettore->regime_fiscale) == 'RF01' ? 'selected' : '' }}>RF01 - Ordinario</option>
                        <option value="RF02" {{ old('regime_fiscale', $vettore->regime_fiscale) == 'RF02' ? 'selected' : '' }}>RF02 - Contribuenti minimi</option>
                        <option value="RF04" {{ old('regime_fiscale', $vettore->regime_fiscale) == 'RF04' ? 'selected' : '' }}>RF04 - Agricoltura</option>
                        <option value="RF19" {{ old('regime_fiscale', $vettore->regime_fiscale) == 'RF19' ? 'selected' : '' }}>RF19 - Forfettario</option>
                    </select>
                    @error('regime_fiscale')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="split_payment" value="1" class="form-check-input @error('split_payment') is-invalid @enderror" 
                               {{ old('split_payment', $vettore->split_payment) ? 'checked' : '' }}>
                        <label class="form-check-label">Split Payment (solo per Enti Pubblici)</label>
                        @error('split_payment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Dati di Contatto -->
        <div class="form-card">
            <div class="section-title">
                <i class="bi bi-telephone"></i> Dati di Contatto
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control modern-input @error('email') is-invalid @enderror" 
                           value="{{ old('email', $vettore->email) }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">PEC (Posta Elettronica Certificata)</label>
                    <input type="email" name="pec" class="form-control modern-input @error('pec') is-invalid @enderror" 
                           value="{{ old('pec', $vettore->pec) }}">
                    @error('pec')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Obbligatoria per aziende e PA</div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Telefono</label>
                    <input type="tel" name="telefono" class="form-control modern-input @error('telefono') is-invalid @enderror" 
                           value="{{ old('telefono', $vettore->telefono) }}">
                    @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Cellulare</label>
                    <input type="tel" name="telefono_mobile" class="form-control modern-input @error('telefono_mobile') is-invalid @enderror" 
                           value="{{ old('telefono_mobile', $vettore->telefono_mobile) }}">
                    @error('telefono_mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Sito Web</label>
                    <input type="url" name="sito_web" class="form-control modern-input @error('sito_web') is-invalid @enderror" 
                           value="{{ old('sito_web', $vettore->sito_web) }}" placeholder="https://">
                    @error('sito_web')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Indirizzo Sede Legale -->
        <div class="form-card">
            <div class="section-title">
                <i class="bi bi-geo-alt"></i> Indirizzo Sede Legale
            </div>
            
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label">Via/Piazza</label>
                    <input type="text" name="indirizzo" class="form-control modern-input @error('indirizzo') is-invalid @enderror" 
                           value="{{ old('indirizzo', $vettore->indirizzo) }}" placeholder="Via Roma 123">
                    @error('indirizzo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">CAP</label>
                    <input type="text" name="cap" class="form-control modern-input @error('cap') is-invalid @enderror" 
                           value="{{ old('cap', $vettore->cap) }}" maxlength="5" pattern="[0-9]{5}">
                    @error('cap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Città</label>
                    <input type="text" name="citta" class="form-control modern-input @error('citta') is-invalid @enderror" 
                           value="{{ old('citta', $vettore->citta) }}">
                    @error('citta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Provincia</label>
                    <input type="text" name="provincia" class="form-control modern-input @error('provincia') is-invalid @enderror" 
                           value="{{ old('provincia', $vettore->provincia) }}" maxlength="2" style="text-transform: uppercase;" placeholder="MI">
                    @error('provincia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Paese</label>
                    <select name="paese" class="form-control modern-select @error('paese') is-invalid @enderror">
                        <option value="IT" {{ old('paese', $vettore->paese) == 'IT' ? 'selected' : '' }}>Italia</option>
                        <option value="FR" {{ old('paese', $vettore->paese) == 'FR' ? 'selected' : '' }}>Francia</option>
                        <option value="DE" {{ old('paese', $vettore->paese) == 'DE' ? 'selected' : '' }}>Germania</option>
                        <option value="ES" {{ old('paese', $vettore->paese) == 'ES' ? 'selected' : '' }}>Spagna</option>
                        <option value="CH" {{ old('paese', $vettore->paese) == 'CH' ? 'selected' : '' }}>Svizzera</option>
                    </select>
                    @error('paese')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Dati Commerciali -->
        <div class="form-card">
            <div class="section-title">
                <i class="bi bi-credit-card"></i> Dati Commerciali
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label required">Classe Vettore</label>
                    <select name="classe_vettore" class="form-control modern-select @error('classe_vettore') is-invalid @enderror" required>
                        <option value="standard" {{ old('classe_vettore', $vettore->classe_vettore) == 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="premium" {{ old('classe_vettore', $vettore->classe_vettore) == 'premium' ? 'selected' : '' }}>Premium</option>
                        <option value="economico" {{ old('classe_vettore', $vettore->classe_vettore) == 'economico' ? 'selected' : '' }}>Economico</option>
                        <option value="occasionale" {{ old('classe_vettore', $vettore->classe_vettore) == 'occasionale' ? 'selected' : '' }}>Occasionale</option>
                    </select>
                    @error('classe_vettore')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label required">Modalità Pagamento</label>
                    <select name="modalita_pagamento" class="form-control modern-select @error('modalita_pagamento') is-invalid @enderror" required>
                        <option value="bonifico" {{ old('modalita_pagamento', $vettore->modalita_pagamento) == 'bonifico' ? 'selected' : '' }}>Bonifico Bancario</option>
                        <option value="rid" {{ old('modalita_pagamento', $vettore->modalita_pagamento) == 'rid' ? 'selected' : '' }}>RID/SDD</option>
                        <option value="assegno" {{ old('modalita_pagamento', $vettore->modalita_pagamento) == 'assegno' ? 'selected' : '' }}>Assegno</option>
                        <option value="contanti" {{ old('modalita_pagamento', $vettore->modalita_pagamento) == 'contanti' ? 'selected' : '' }}>Contanti</option>
                        <option value="carta" {{ old('modalita_pagamento', $vettore->modalita_pagamento) == 'carta' ? 'selected' : '' }}>Carta di Credito</option>
                    </select>
                    @error('modalita_pagamento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label required">Giorni Pagamento</label>
                    <input type="number" name="giorni_pagamento" class="form-control modern-input @error('giorni_pagamento') is-invalid @enderror" 
                           value="{{ old('giorni_pagamento', $vettore->giorni_pagamento) }}" required min="0" max="365">
                    @error('giorni_pagamento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Giorni di dilazione per i pagamenti</div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Costo Base (€/kg)</label>
                    <div class="input-group">
                        <span class="input-group-text">€</span>
                        <input type="number" name="costo_base_kg" class="form-control modern-input @error('costo_base_kg') is-invalid @enderror" 
                               value="{{ old('costo_base_kg', $vettore->costo_base_kg) }}" min="0" step="0.01">
                    </div>
                    @error('costo_base_kg')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Costo Minimo Spedizione (€)</label>
                    <div class="input-group">
                        <span class="input-group-text">€</span>
                        <input type="number" name="costo_minimo_spedizione" class="form-control modern-input @error('costo_minimo_spedizione') is-invalid @enderror" 
                               value="{{ old('costo_minimo_spedizione', $vettore->costo_minimo_spedizione) }}" min="0" step="0.01">
                    </div>
                    @error('costo_minimo_spedizione')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Soglia Franco (€)</label>
                    <div class="input-group">
                        <span class="input-group-text">€</span>
                        <input type="number" name="soglia_franco" class="form-control modern-input @error('soglia_franco') is-invalid @enderror" 
                               value="{{ old('soglia_franco', $vettore->soglia_franco) }}" min="0" step="0.01">
                    </div>
                    @error('soglia_franco')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Spedizione gratuita oltre questo importo</div>
                </div>
            </div>
        </div>

        <!-- Performance e Tracking -->
        <div class="form-card">
            <div class="section-title">
                <i class="bi bi-speedometer2"></i> Performance e Tracking
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tempo Consegna Standard (giorni)</label>
                    <input type="number" name="tempo_consegna_standard" class="form-control modern-input @error('tempo_consegna_standard') is-invalid @enderror" 
                           value="{{ old('tempo_consegna_standard', $vettore->tempo_consegna_standard) }}" min="1" max="30">
                    @error('tempo_consegna_standard')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tempo Consegna Express (giorni)</label>
                    <input type="number" name="tempo_consegna_express" class="form-control modern-input @error('tempo_consegna_express') is-invalid @enderror" 
                           value="{{ old('tempo_consegna_express', $vettore->tempo_consegna_express) }}" min="1" max="10">
                    @error('tempo_consegna_express')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Percentuale Puntualità (%)</label>
                    <input type="number" name="percentuale_puntualita" class="form-control modern-input @error('percentuale_puntualita') is-invalid @enderror" 
                           value="{{ old('percentuale_puntualita', $vettore->percentuale_puntualita) }}" min="0" max="100" step="0.01">
                    @error('percentuale_puntualita')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">API Tracking URL</label>
                    <input type="url" name="api_tracking_url" class="form-control modern-input @error('api_tracking_url') is-invalid @enderror" 
                           value="{{ old('api_tracking_url', $vettore->api_tracking_url) }}" placeholder="https://tracking.corriere.it/track/{tracking}">
                    @error('api_tracking_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Usa {tracking} come placeholder per il numero spedizione</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Prefisso Codice Tracking</label>
                    <input type="text" name="codice_tracking_prefisso" class="form-control modern-input @error('codice_tracking_prefisso') is-invalid @enderror" 
                           value="{{ old('codice_tracking_prefisso', $vettore->codice_tracking_prefisso) }}" maxlength="10">
                    @error('codice_tracking_prefisso')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <!-- Stato e Controllo -->
        <div class="form-card">
            <div class="section-title">
                <i class="bi bi-shield-check"></i> Stato e Controllo
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="attivo" value="1" class="form-check-input @error('attivo') is-invalid @enderror" 
                               {{ old('attivo', $vettore->attivo) ? 'checked' : '' }}>
                        <label class="form-check-label">Vettore Attivo</label>
                        @error('attivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="verificato" value="1" class="form-check-input @error('verificato') is-invalid @enderror" 
                               {{ old('verificato', $vettore->verificato) ? 'checked' : '' }}>
                        <label class="form-check-label">Dati Verificati</label>
                        @error('verificato')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Note Interne</label>
                    <textarea name="note_interne" class="form-control modern-textarea @error('note_interne') is-invalid @enderror" 
                              rows="3">{{ old('note_interne', $vettore->note_interne) }}</textarea>
                    @error('note_interne')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Note visibili solo internamente</div>
                </div>
            </div>
        </div>

        <!-- Privacy e Consensi -->
        <div class="form-card">
            <div class="section-title">
                <i class="bi bi-shield-check"></i> Privacy e Consensi
            </div>
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="consenso_dati" value="1" class="form-check-input @error('consenso_dati') is-invalid @enderror" 
                               {{ old('consenso_dati', $vettore->consenso_dati) ? 'checked' : '' }} required>
                        <label class="form-check-label required">Consenso al trattamento dei dati personali (obbligatorio)</label>
                        @error('consenso_dati')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="col-md-12 mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="consenso_marketing" value="1" class="form-check-input @error('consenso_marketing') is-invalid @enderror" 
                               {{ old('consenso_marketing', $vettore->consenso_marketing) ? 'checked' : '' }}>
                        <label class="form-check-label">Consenso per comunicazioni commerciali (facoltativo)</label>
                        @error('consenso_marketing')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Pulsanti -->
        <div class="d-flex justify-content-between">
            <a href="{{ route('vettori.show', $vettore) }}" class="btn btn-secondary modern-btn">
                <i class="bi bi-x-circle"></i> Annulla
            </a>
            <button type="submit" class="modern-btn">
                <i class="bi bi-check-circle"></i> Aggiorna Vettore
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoSoggetto = document.querySelector('[name="tipo_soggetto"]');
    const personaFisicaFields = document.getElementById('persona-fisica-fields');
    
    function togglePersonaFisica() {
        if (tipoSoggetto.value === 'persona_fisica') {
            personaFisicaFields.style.display = 'block';
        } else {
            personaFisicaFields.style.display = 'none';
        }
    }
    
    tipoSoggetto.addEventListener('change', togglePersonaFisica);
    
    // Auto-maiuscolo per codici
    document.querySelector('[name="codice_fiscale"]').addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
    });
    
    document.querySelector('[name="codice_vettore"]').addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
    });
    
    document.querySelector('[name="provincia"]').addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
    });
    
    // Validazione real-time P.IVA
    document.querySelector('[name="partita_iva"]').addEventListener('input', function(e) {
        const piva = e.target.value.replace(/\D/g, ''); // Solo numeri
        e.target.value = piva;
        
        if (piva.length === 11) {
            // Validazione algoritmo P.IVA italiana
            let sum = 0;
            for (let i = 0; i < 10; i++) {
                if (i % 2 === 0) {
                    sum += parseInt(piva[i]);
                } else {
                    let temp = parseInt(piva[i]) * 2;
                    sum += temp > 9 ? (temp - 9) : temp;
                }
            }
            const checkDigit = (10 - (sum % 10)) % 10;
            
            if (checkDigit != parseInt(piva[10])) {
                e.target.setCustomValidity('Partita IVA non valida');
            } else {
                e.target.setCustomValidity('');
            }
        }
    });
});
</script>
@endsection