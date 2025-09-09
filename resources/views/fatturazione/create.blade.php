@extends('layouts.app')

@section('title', 'Nuova Fattura - Gestionale Negozio')

@section('content')
<style>
    .fatturazione-container {
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
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
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
    
    .form-section {
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
    }
    
    .modern-btn {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        border: none;
        border-radius: 15px;
        padding: 15px 30px;
        font-weight: 600;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }
    
    .modern-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(2, 157, 126, 0.4);
        color: white;
    }
    
    .modern-btn.secondary {
        background: linear-gradient(135deg, #6c757d, #495057);
    }
    
    .modern-btn.secondary:hover {
        box-shadow: 0 15px 35px rgba(108, 117, 125, 0.4);
    }
    
    .modern-btn.success {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
    }
    
    .modern-btn.success:hover {
        box-shadow: 0 15px 35px rgba(2, 157, 126, 0.4);
    }
    
    .modern-btn.danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
    }
    
    .modern-btn.danger:hover {
        box-shadow: 0 15px 35px rgba(220, 53, 69, 0.4);
    }
    
    .modern-btn.info {
        background: linear-gradient(135deg, #17a2b8, #138496);
    }
    
    .modern-btn.info:hover {
        box-shadow: 0 15px 35px rgba(23, 162, 184, 0.4);
    }
    
    /* Input with icon styles */
    .input-group-icon {
        position: relative;
    }
    
    .input-group-icon .form-control {
        padding-right: 45px;
    }
    
    .input-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #029D7E;
        cursor: pointer;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }
    
    .input-icon:hover {
        color: #4DC9A5;
        transform: translateY(-50%) scale(1.1);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #029D7E;
        box-shadow: 0 0 0 0.25rem rgba(2, 157, 126, 0.25);
    }
    
    /* Calculator Widget Styles */
    .calculator-toggle-btn {
        position: fixed;
        right: 30px;
        bottom: 30px;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        border: none;
        border-radius: 50%;
        color: white;
        font-size: 1.5rem;
        cursor: pointer;
        box-shadow: 0 10px 25px rgba(2, 157, 126, 0.3);
        transition: all 0.3s ease;
        z-index: 9999;
    }
    
    .calculator-toggle-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 15px 35px rgba(2, 157, 126, 0.4);
    }
    
    .calculator-toggle-btn:active {
        transform: scale(0.95);
        box-shadow: 0 5px 15px rgba(2, 157, 126, 0.6);
    }
    
    /* Assicuriamoci che il pulsante sia sempre visibile */
    #calcToggleBtn {
        pointer-events: all !important;
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    .calculator-widget {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0);
        width: 320px;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        z-index: 10000;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        opacity: 0;
        border: 2px solid rgba(2, 157, 126, 0.2);
    }
    
    .calculator-widget.active {
        transform: translate(-50%, -50%) scale(1);
        opacity: 1;
    }
    
    .calculator-header {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        padding: 1.5rem;
        border-radius: 18px 18px 0 0;
        text-align: center;
        font-weight: 600;
        position: relative;
    }
    
    .calc-close-btn {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .calc-close-btn:hover {
        background: rgba(255, 255, 255, 0.3);
    }
    
    .calculator-display {
        padding: 1.5rem;
        background: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    .calc-display-input {
        width: 100%;
        height: 60px;
        border: none;
        background: transparent;
        font-size: 2rem;
        text-align: right;
        color: #333;
        font-family: 'Courier New', monospace;
        font-weight: 600;
    }
    
    .calc-display-input:focus {
        outline: none;
    }
    
    .calculator-buttons {
        padding: 1.5rem;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
    }
    
    .calc-btn {
        height: 50px;
        border: none;
        border-radius: 12px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        background: #f8f9fa;
        color: #333;
    }
    
    .calc-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .calc-btn.operator {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
    }
    
    .calc-btn.equals {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        grid-column: span 2;
    }
    
    .calc-btn.clear {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }
    
    /* Dark Mode */
    [data-bs-theme="dark"] .page-header,
    [data-bs-theme="dark"] .form-card {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .calculator-widget {
        background: rgba(45, 55, 72, 0.98);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .calculator-display {
        background: rgba(0, 0, 0, 0.2);
    }
    
    [data-bs-theme="dark"] .calc-display-input {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .calc-btn {
        background: rgba(255, 255, 255, 0.1);
        color: #e2e8f0;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .fatturazione-container {
            padding: 1rem;
        }
        
        .page-header, .form-card {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .page-title {
            font-size: 2rem;
        }
        
        .modern-btn {
            padding: 12px 24px;
            font-size: 0.9rem;
        }
        
        .calculator-widget {
            width: 90%;
        }
    }
    
    @media (max-width: 576px) {
        .page-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        /* Pulsanti mobili */
        .modern-btn {
            display: block;
            width: 100%;
            margin-bottom: 1rem;
            margin-right: 0 !important;
        }
        
        .form-card .text-center {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
    }
</style>

<div class="fatturazione-container">
    <!-- Header della Pagina -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">
                <i class="bi bi-file-earmark-plus"></i> {{ __('app.nuova_fattura') }}
            </h1>
            <a href="{{ route('fatturazione.index') }}" class="modern-btn">
                <i class="bi bi-arrow-left"></i> {{ __('app.torna_indietro') }}
            </a>
        </div>
    </div>
    
    <form action="{{ route('fatturazione.store') }}" method="POST" id="invoiceForm">
        @csrf
        
        <!-- Dati Fattura -->
        <div class="form-card">
            <div class="form-section">
                <h3 class="section-title">
                    <i class="bi bi-file-text"></i> {{ __('app.dati_fattura') }}
                </h3>
                
                <div class="row">
                    <div class="col-md-3">
                        <label for="tipo" class="form-label">{{ __('app.tipo') }} *</label>
                        <select name="tipo" id="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                            <option value="definitivo" {{ old('tipo', 'definitivo') == 'definitivo' ? 'selected' : '' }}>{{ __('app.definitivo') }}</option>
                            <option value="potenziale" {{ old('tipo') == 'potenziale' ? 'selected' : '' }}>{{ __('app.potenziale') }}</option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="numero_documento" class="form-label">{{ __('app.numero_fattura') }} *</label>
                        <input type="number" name="numero_documento" id="numero_documento" 
                               class="form-control @error('numero_documento') is-invalid @enderror" 
                               value="{{ old('numero_documento', $numeroFattura) }}" required>
                        @error('numero_documento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="data_documento" class="form-label">{{ __('app.data_fattura') }} *</label>
                        <input type="date" name="data_documento" id="data_documento" 
                               class="form-control @error('data_documento') is-invalid @enderror" 
                               value="{{ old('data_documento', date('Y-m-d')) }}" required>
                        @error('data_documento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="cliente_id" class="form-label">{{ __('app.cliente') }}</label>
                        <select name="cliente_id" id="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror">
                            <option value="">{{ __('app.seleziona_cliente') }}</option>
                            @foreach($clienti as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nome }} {{ $cliente->cognome }}
                                </option>
                            @endforeach
                        </select>
                        @error('cliente_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Dati Cliente/Altro -->
        <div class="form-card">
            <div class="form-section">
                <h3 class="section-title">
                    <i class="bi bi-person"></i> {{ __('app.dati_cliente') }}
                </h3>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="indirizzo" class="form-label">{{ __('app.indirizzo') }}</label>
                        <div class="input-group-icon">
                            <input type="text" name="indirizzo" id="indirizzo" 
                                   class="form-control @error('indirizzo') is-invalid @enderror" 
                                   value="{{ old('indirizzo') }}" placeholder="{{ __('app.inserisci_indirizzo') }}">
                            <i class="bi bi-geo-alt input-icon" onclick="openMapModal()" title="{{ __('app.cerca_su_mappa') }}"></i>
                        </div>
                        @error('indirizzo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label for="provincia" class="form-label">{{ __('app.provincia') }}</label>
                        <input type="text" name="provincia" id="provincia" 
                               class="form-control @error('provincia') is-invalid @enderror" 
                               value="{{ old('provincia') }}" maxlength="2" placeholder="RM">
                        @error('provincia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="citta" class="form-label">{{ __('app.citta') }}</label>
                        <input type="text" name="citta" id="citta" 
                               class="form-control @error('citta') is-invalid @enderror" 
                               value="{{ old('citta') }}" placeholder="{{ __('app.inserisci_citta') }}">
                        @error('citta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-1">
                        <label for="cap" class="form-label">{{ __('app.cap') }}</label>
                        <input type="text" name="cap" id="cap" 
                               class="form-control @error('cap') is-invalid @enderror" 
                               value="{{ old('cap') }}" maxlength="5" placeholder="00100">
                        @error('cap')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="paese" class="form-label">{{ __('app.paese') }}</label>
                        <input type="text" name="paese" id="paese" 
                               class="form-control @error('paese') is-invalid @enderror" 
                               value="{{ old('paese', 'Italia') }}" placeholder="Italia">
                        @error('paese')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="codice_fiscale" class="form-label">{{ __('app.codice_fiscale') }}</label>
                        <div class="input-group-icon">
                            <input type="text" name="codice_fiscale" id="codice_fiscale" 
                                   class="form-control @error('codice_fiscale') is-invalid @enderror" 
                                   value="{{ old('codice_fiscale') }}" maxlength="16" placeholder="RSSMRA80A01H501Z">
                            <i class="bi bi-card-text input-icon" onclick="openCodiceFiscaleModal()" title="{{ __('app.calcola_codice_fiscale') }}"></i>
                        </div>
                        @error('codice_fiscale')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="partita_iva" class="form-label">{{ __('app.partita_iva') }}</label>
                        <input type="text" name="partita_iva" id="partita_iva" 
                               class="form-control @error('partita_iva') is-invalid @enderror" 
                               value="{{ old('partita_iva') }}" maxlength="11" placeholder="12345678901">
                        @error('partita_iva')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="partita_iva_cee" class="form-label">{{ __('app.partita_iva_cee') }}</label>
                        <input type="text" name="partita_iva_cee" id="partita_iva_cee" 
                               class="form-control @error('partita_iva_cee') is-invalid @enderror" 
                               value="{{ old('partita_iva_cee') }}" placeholder="IT12345678901">
                        @error('partita_iva_cee')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="telefono" class="form-label">{{ __('app.telefono') }}</label>
                        <input type="tel" name="telefono" id="telefono" 
                               class="form-control @error('telefono') is-invalid @enderror" 
                               value="{{ old('telefono') }}" placeholder="+39 06 12345678">
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="telefax" class="form-label">{{ __('app.telefax') }}</label>
                        <input type="tel" name="telefax" id="telefax" 
                               class="form-control @error('telefax') is-invalid @enderror" 
                               value="{{ old('telefax') }}" placeholder="+39 06 12345679">
                        @error('telefax')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="contatto" class="form-label">{{ __('app.contatto') }}</label>
                        <input type="text" name="contatto" id="contatto" 
                               class="form-control @error('contatto') is-invalid @enderror" 
                               value="{{ old('contatto') }}" placeholder="{{ __('app.nome_contatto') }}">
                        @error('contatto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="email" class="form-label">{{ __('app.email') }}</label>
                        <input type="email" name="email" id="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" placeholder="cliente@esempio.it">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="www" class="form-label">{{ __('app.sito_web') }}</label>
                        <input type="url" name="www" id="www" 
                               class="form-control @error('www') is-invalid @enderror" 
                               value="{{ old('www') }}" placeholder="https://www.esempio.it">
                        @error('www')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="pec" class="form-label">{{ __('app.pec') }}</label>
                        <input type="email" name="pec" id="pec" 
                               class="form-control @error('pec') is-invalid @enderror" 
                               value="{{ old('pec') }}" placeholder="cliente@pec.it">
                        @error('pec')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label for="tessera" class="form-label">{{ __('app.tessera') }}</label>
                        <input type="text" name="tessera" id="tessera" 
                               class="form-control @error('tessera') is-invalid @enderror" 
                               value="{{ old('tessera') }}" placeholder="12345">
                        @error('tessera')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label for="codice_sdi" class="form-label">{{ __('app.codice_sdi') }}</label>
                        <input type="text" name="codice_sdi" id="codice_sdi" 
                               class="form-control @error('codice_sdi') is-invalid @enderror" 
                               value="{{ old('codice_sdi') }}" maxlength="7" placeholder="ABCDEFG">
                        @error('codice_sdi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" name="pubblica_amministrazione" 
                                   id="pubblica_amministrazione" value="1" {{ old('pubblica_amministrazione') ? 'checked' : '' }}>
                            <label class="form-check-label" for="pubblica_amministrazione">
                                {{ __('app.pubblica_amministrazione') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Note -->
        <div class="form-card">
            <div class="form-section">
                <h3 class="section-title">
                    <i class="bi bi-chat-text"></i> {{ __('app.note') }}
                </h3>
                <textarea name="note" class="form-control" rows="4" 
                          placeholder="{{ __('app.inserisci_note_facoltative') }}">{{ old('note') }}</textarea>
            </div>
        </div>
        
        <!-- Pulsanti di azione -->
        <div class="form-card">
            <div class="text-center">
                <button type="submit" class="modern-btn success" style="margin-right: 1rem;">
                    <i class="bi bi-check-circle"></i> {{ __('app.crea_fattura') }}
                </button>
                <a href="{{ route('fatturazione.index') }}" class="modern-btn secondary">
                    <i class="bi bi-x-circle"></i> {{ __('app.annulla') }}
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Calculator Toggle Button -->
<button type="button" id="calcToggleBtn" class="calculator-toggle-btn" title="Calcolatrice" 
        onclick="document.getElementById('calculatorWidget').style.display = document.getElementById('calculatorWidget').style.display === 'block' ? 'none' : 'block';">
    <i class="bi bi-calculator"></i>
</button>

<!-- Calculator Widget -->
<div id="calculatorWidget" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 340px; background: linear-gradient(145deg, #ffffff, #f8f9fa); border: none; border-radius: 20px; z-index: 99999; box-shadow: 0 25px 60px rgba(0,0,0,0.25), 0 0 0 1px rgba(255,255,255,0.1); backdrop-filter: blur(20px);">
    <div style="background: linear-gradient(135deg, #029D7E, #4DC9A5); color: white; padding: 20px; border-radius: 18px 18px 0 0; text-align: center; font-weight: bold; font-size: 18px; position: relative; box-shadow: 0 2px 10px rgba(2, 157, 126, 0.3);">
        <i class="bi bi-calculator" style="margin-right: 8px;"></i>Calcolatrice Finson Pro
        <button type="button" onclick="document.getElementById('calculatorWidget').style.display='none'" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.2); border: none; color: white; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-50%) scale(1.1)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(-50%)'">×</button>
    </div>
    
    <div style="padding: 20px;">
        <input type="text" id="calcDisplay" readonly value="0" style="width: 100%; padding: 15px; font-size: 24px; text-align: right; border: 2px solid #e9ecef; border-radius: 12px; margin-bottom: 20px; font-family: 'Courier New', monospace; background: linear-gradient(145deg, #f8f9fa, #ffffff); color: #2d3748; font-weight: 600; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
        
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;">
            <button onclick="clearAll()" style="padding: 12px; border: none; background: #ff6b6b; color: white; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">AC</button>
            <button onclick="clearEntry()" style="padding: 12px; border: none; background: #ffa500; color: white; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">C</button>
            <button onclick="appendPercentage()" style="padding: 12px; border: none; background: #029D7E; color: white; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">%</button>
            <button onclick="appendOperator('/')" style="padding: 12px; border: none; background: #029D7E; color: white; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">÷</button>
            
            <button onclick="appendNumber('7')" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">7</button>
            <button onclick="appendNumber('8')" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">8</button>
            <button onclick="appendNumber('9')" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">9</button>
            <button onclick="appendOperator('*')" style="padding: 12px; border: none; background: #029D7E; color: white; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">×</button>
            
            <button onclick="appendNumber('4')" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">4</button>
            <button onclick="appendNumber('5')" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">5</button>
            <button onclick="appendNumber('6')" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">6</button>
            <button onclick="appendOperator('-')" style="padding: 12px; border: none; background: #029D7E; color: white; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">−</button>
            
            <button onclick="appendNumber('1')" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">1</button>
            <button onclick="appendNumber('2')" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">2</button>
            <button onclick="appendNumber('3')" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">3</button>
            <button onclick="appendOperator('+')" style="padding: 12px; border: none; background: #029D7E; color: white; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">+</button>
            
            <button onclick="appendNumber('0')" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; grid-column: span 2; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">0</button>
            <button onclick="appendDecimal()" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">.</button>
            <button onclick="calculate()" style="padding: 12px; border: none; background: #28a745; color: white; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'; this.style.background='#218838'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#28a745'">=</button>
        </div>
    </div>
</div>

<!-- Modal Codice Fiscale -->
<div id="codiceFiscaleModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10001; backdrop-filter: blur(5px);" onclick="if(event.target === this) closeCodiceFiscaleModal()">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 90%; max-width: 500px; background: linear-gradient(145deg, #ffffff, #f8f9fa); border-radius: 20px; box-shadow: 0 25px 60px rgba(0,0,0,0.3); overflow: hidden;">
        
        <!-- Header tessera sanitaria -->
        <div style="background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%); color: white; padding: 20px; position: relative;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="bi bi-card-text" style="font-size: 24px;"></i>
                    <div>
                        <div style="font-size: 18px; font-weight: bold;">Tessera Sanitaria Digitale</div>
                        <div style="font-size: 12px; opacity: 0.9;">Calcolo Codice Fiscale</div>
                    </div>
                </div>
                <button onclick="closeCodiceFiscaleModal()" style="background: rgba(255,255,255,0.2); border: none; color: white; border-radius: 50%; width: 35px; height: 35px; cursor: pointer; font-size: 18px; transition: all 0.2s ease;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">×</button>
            </div>
        </div>
        
        <!-- Form dati personali -->
        <div style="padding: 25px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Nome *</label>
                    <input type="text" id="cfNome" placeholder="Mario" style="width: 100%; padding: 10px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 14px;" oninput="this.value = this.value.toUpperCase()">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Cognome *</label>
                    <input type="text" id="cfCognome" placeholder="Rossi" style="width: 100%; padding: 10px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 14px;" oninput="this.value = this.value.toUpperCase()">
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Data di Nascita *</label>
                    <input type="date" id="cfDataNascita" style="width: 100%; padding: 10px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Sesso *</label>
                    <select id="cfSesso" style="width: 100%; padding: 10px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 14px;">
                        <option value="">Seleziona</option>
                        <option value="M">Maschio</option>
                        <option value="F">Femmina</option>
                    </select>
                </div>
            </div>
            
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #333;">Comune di Nascita *</label>
                <input type="text" id="cfComune" placeholder="Roma" style="width: 100%; padding: 10px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 14px;" oninput="this.value = this.value.toUpperCase()">
                <small style="color: #6c757d; font-size: 12px;">Inserire il nome completo del comune</small>
            </div>
            
            <!-- Risultato codice fiscale -->
            <div style="background: linear-gradient(145deg, #f8f9fa, #e9ecef); padding: 15px; border-radius: 12px; margin-bottom: 20px; border: 2px dashed #029D7E;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">Codice Fiscale Calcolato:</label>
                <input type="text" id="cfRisultato" readonly placeholder="Il codice fiscale apparirà qui..." style="width: 100%; padding: 12px; border: none; background: white; border-radius: 8px; font-size: 16px; font-weight: bold; letter-spacing: 2px; text-align: center; color: #029D7E;">
            </div>
            
            <!-- Pulsanti azione -->
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button onclick="calcolaCodiceFiscale()" style="background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%); color: white; border: none; padding: 12px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(2,157,126,0.4)'" onmouseout="this.style.transform=''; this.style.boxShadow=''">
                    <i class="bi bi-calculator" style="margin-right: 5px;"></i>Calcola
                </button>
                <button onclick="copiaCodiceFiscale()" style="background: linear-gradient(135deg, #2196F3, #1976D2); color: white; border: none; padding: 12px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(33,150,243,0.4)'" onmouseout="this.style.transform=''; this.style.boxShadow=''">
                    <i class="bi bi-clipboard" style="margin-right: 5px;"></i>Copia e Usa
                </button>
                <button onclick="closeCodiceFiscaleModal()" style="background: linear-gradient(135deg, #6c757d, #495057); color: white; border: none; padding: 12px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
                    <i class="bi bi-x-circle" style="margin-right: 5px;"></i>Chiudi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Google Maps -->
<div id="mapModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10002; backdrop-filter: blur(5px);" onclick="if(event.target === this) closeMapModal()">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 90%; max-width: 800px; height: 80%; background: linear-gradient(145deg, #ffffff, #f8f9fa); border-radius: 20px; box-shadow: 0 25px 60px rgba(0,0,0,0.3); overflow: hidden; display: flex; flex-direction: column;">
        
        <!-- Header mappa -->
        <div style="background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%); color: white; padding: 20px; position: relative; flex-shrink: 0;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="bi bi-map" style="font-size: 24px;"></i>
                    <div>
                        <div style="font-size: 18px; font-weight: bold;">Ricerca Indirizzo</div>
                        <div style="font-size: 12px; opacity: 0.9;">Google Maps Integrato</div>
                    </div>
                </div>
                <button onclick="closeMapModal()" style="background: rgba(255,255,255,0.2); border: none; color: white; border-radius: 50%; width: 35px; height: 35px; cursor: pointer; font-size: 18px; transition: all 0.2s ease;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">×</button>
            </div>
        </div>
        
        <!-- Barra di ricerca -->
        <div style="padding: 20px; border-bottom: 1px solid #e9ecef; flex-shrink: 0;">
            <div style="display: flex; gap: 10px; align-items: center;">
                <div style="flex: 1; position: relative;">
                    <input type="text" id="mapSearchInput" placeholder="Inserisci indirizzo da cercare..." style="width: 100%; padding: 12px 15px; border: 2px solid #e9ecef; border-radius: 25px; font-size: 14px; padding-left: 45px;" onkeypress="if(event.key==='Enter') searchAddress()">
                    <i class="bi bi-search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                </div>
                <button onclick="searchAddress()" style="background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%); color: white; border: none; padding: 12px 20px; border-radius: 25px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; white-space: nowrap;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(2,157,126,0.4)'" onmouseout="this.style.transform=''; this.style.boxShadow=''">
                    <i class="bi bi-search" style="margin-right: 5px;"></i>Cerca
                </button>
                <button onclick="useCurrentLocation()" style="background: linear-gradient(135deg, #2196F3, #1976D2); color: white; border: none; padding: 12px 20px; border-radius: 25px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; white-space: nowrap;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(33,150,243,0.4)'" onmouseout="this.style.transform=''; this.style.boxShadow=''">
                    <i class="bi bi-geo-alt-fill" style="margin-right: 5px;"></i>Posizione
                </button>
            </div>
        </div>
        
        <!-- Container mappa -->
        <div style="flex: 1; position: relative;">
            <iframe 
                id="googleMapFrame" 
                src="https://www.google.com/maps?q=Italia&output=embed"
                width="100%" 
                height="100%" 
                style="border:0; border-radius: 0 0 20px 20px;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
        
        <!-- Footer con pulsanti -->
        <div style="padding: 15px 20px; border-top: 1px solid #e9ecef; background: #f8f9fa; border-radius: 0 0 20px 20px; flex-shrink: 0;">
            <div style="display: flex; gap: 10px; justify-content: center; align-items: center;">
                <input type="text" id="selectedAddress" readonly placeholder="Nessun indirizzo selezionato..." style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 8px; background: white; font-size: 13px;">
                <button onclick="useSelectedAddress()" style="background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%); color: white; border: none; padding: 10px 16px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; white-space: nowrap;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform=''">
                    <i class="bi bi-check-circle" style="margin-right: 5px;"></i>Usa Indirizzo
                </button>
                <button onclick="closeMapModal()" style="background: linear-gradient(135deg, #6c757d, #495057); color: white; border: none; padding: 10px 16px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; white-space: nowrap;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform=''">
                    <i class="bi bi-x-circle" style="margin-right: 5px;"></i>Chiudi
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Variabili globali calcolatrice
let calculatorCurrentInput = '0';
let calculatorPreviousInput = null;
let calculatorOperator = null;
let calculatorWaitingForOperand = false;

// Animazioni caricamento pagina
function animatePageLoad() {
    const elements = document.querySelectorAll('.page-header, .form-card');
    elements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'all 0.6s ease';
        
        setTimeout(() => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 150);
    });
}

// === FUNZIONI MODAL MAPPA ===
function openMapModal() {
    document.getElementById('mapModal').style.display = 'block';
    
    // Precompila la ricerca con i dati esistenti
    const indirizzo = document.getElementById('indirizzo').value;
    const citta = document.getElementById('citta').value;
    const provincia = document.getElementById('provincia').value;
    
    let searchQuery = '';
    if (indirizzo) searchQuery += indirizzo + ' ';
    if (citta) searchQuery += citta + ' ';
    if (provincia) searchQuery += provincia + ' ';
    searchQuery += 'Italia';
    
    document.getElementById('mapSearchInput').value = searchQuery.trim();
    
    // Se c'è già un indirizzo, cerca subito
    if (indirizzo || citta) {
        setTimeout(() => searchAddress(), 500);
    }
}

function closeMapModal() {
    document.getElementById('mapModal').style.display = 'none';
}

function searchAddress() {
    const query = document.getElementById('mapSearchInput').value.trim();
    if (!query) {
        alert('Inserisci un indirizzo da cercare!');
        return;
    }
    
    // Costruisci URL per Google Maps embed con ricerca
    const encodedQuery = encodeURIComponent(query);
    const embedUrl = `https://www.google.com/maps/embed/v1/search?key=&q=${encodedQuery}&zoom=15&language=it&region=IT`;
    
    // Aggiorna iframe (nota: senza API key funziona comunque per la ricerca di base)
    // URL semplificato per la ricerca su Google Maps
    const mapUrl = `https://www.google.com/maps?q=${encodedQuery}&output=embed`;
    
    document.getElementById('googleMapFrame').src = mapUrl;
    document.getElementById('selectedAddress').value = query;
}

function useCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                // URL semplificato per la posizione corrente
                const locationUrl = `https://www.google.com/maps?q=${lat},${lng}&output=embed`;
                
                document.getElementById('googleMapFrame').src = locationUrl;
                document.getElementById('selectedAddress').value = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
                document.getElementById('mapSearchInput').value = 'La tua posizione corrente';
            },
            function(error) {
                alert('Impossibile ottenere la posizione: ' + error.message);
            }
        );
    } else {
        alert('Geolocalizzazione non supportata dal browser!');
    }
}

function useSelectedAddress() {
    const selectedAddr = document.getElementById('selectedAddress').value;
    if (!selectedAddr) {
        alert('Nessun indirizzo selezionato!');
        return;
    }
    
    // Inserisci l'indirizzo nel campo del form
    document.getElementById('indirizzo').value = selectedAddr;
    
    // Prova ad estrarre città e provincia dall'indirizzo
    const parts = selectedAddr.split(',');
    if (parts.length >= 2) {
        const lastPart = parts[parts.length - 1].trim();
        if (lastPart !== 'Italia') {
            // Prova ad estrarre città dall'ultima parte prima di Italia
            const cityPart = parts[parts.length - 2].trim();
            document.getElementById('citta').value = cityPart;
        }
    }
    
    alert('Indirizzo inserito nel modulo!');
    closeMapModal();
}

// === FUNZIONI MODAL CODICE FISCALE ===
function openCodiceFiscaleModal() {
    document.getElementById('codiceFiscaleModal').style.display = 'block';
    // Reset dei campi
    document.getElementById('cfNome').value = '';
    document.getElementById('cfCognome').value = '';
    document.getElementById('cfDataNascita').value = '';
    document.getElementById('cfSesso').value = '';
    document.getElementById('cfComune').value = '';
    document.getElementById('cfRisultato').value = '';
}

function closeCodiceFiscaleModal() {
    document.getElementById('codiceFiscaleModal').style.display = 'none';
}

// Database comuni italiani (principali città)
const comuni = {
    'ROMA': 'H501', 'MILANO': 'F205', 'NAPOLI': 'F839', 'TORINO': 'L219', 'PALERMO': 'G273',
    'GENOVA': 'D969', 'BOLOGNA': 'A944', 'FIRENZE': 'D612', 'BARI': 'A662', 'CATANIA': 'C351',
    'VENEZIA': 'L736', 'VERONA': 'L781', 'MESSINA': 'F158', 'PADOVA': 'G224', 'TRIESTE': 'L424',
    'BRESCIA': 'B157', 'PARMA': 'G337', 'MODENA': 'F257', 'REGGIO EMILIA': 'H223', 'PERUGIA': 'G478',
    'LIVORNO': 'E625', 'CAGLIARI': 'B354', 'FOGGIA': 'D643', 'RAVENNA': 'H199', 'RIMINI': 'H294',
    'SALERNO': 'H703', 'FERRARA': 'D548', 'SASSARI': 'I452', 'LATINA': 'E472', 'GIUGLIANO IN CAMPANIA': 'E056',
    'MONZA': 'F704', 'SIRACUSA': 'I754', 'PESCARA': 'G482', 'BERGAMO': 'A794', 'VICENZA': 'L840',
    'AREZZO': 'A390', 'UDINE': 'L483', 'BOLZANO': 'A952', 'CESENA': 'C469', 'LECCE': 'E506',
    'TARANTO': 'L049', 'NOVARA': 'F952', 'PIACENZA': 'G535', 'ANCONA': 'A271', 'ANDRIA': 'A285',
    'LA SPEZIA': 'E463', 'COMO': 'C933', 'CARRARA': 'B832', 'BRINDISI': 'B180', 'TERNI': 'L112',
    'BUSTO ARSIZIO': 'B300', 'APRILIA': 'A341', 'RAGUSA': 'H163', 'VARESE': 'L682', 'TRENTO': 'L378',
    'GRAVINA IN PUGLIA': 'E155', 'GRAVINA DI PUGLIA': 'E155', 'GRAVINA': 'E155'
};

function calcolaCodiceFiscale() {
    const nome = document.getElementById('cfNome').value.trim();
    const cognome = document.getElementById('cfCognome').value.trim();
    const dataNascita = document.getElementById('cfDataNascita').value;
    const sesso = document.getElementById('cfSesso').value;
    const comune = document.getElementById('cfComune').value.trim();
    
    if (!nome || !cognome || !dataNascita || !sesso || !comune) {
        alert('Compila tutti i campi obbligatori!');
        return;
    }
    
    try {
        // Calcola codice cognome (3 consonanti + vocali - normale)
        const codiceCognome = estraiCodice(cognome, 3, false);
        
        // Calcola codice nome (3 consonanti + vocali - con regola speciale)
        const codiceNome = estraiCodice(nome, 3, true);
        
        // Anno di nascita (ultime 2 cifre)
        const anno = dataNascita.substring(2, 4);
        
        // Mese (codificato)
        const mesi = ['A', 'B', 'C', 'D', 'E', 'H', 'L', 'M', 'P', 'R', 'S', 'T'];
        const mese = mesi[parseInt(dataNascita.substring(5, 7)) - 1];
        
        // Giorno + sesso
        let giorno = parseInt(dataNascita.substring(8, 10));
        if (sesso === 'F') giorno += 40;
        const giornoStr = giorno.toString().padStart(2, '0');
        
        // Codice comune (cerca nel database)
        const codiceComune = comuni[comune.toUpperCase()] || 'H501'; // Default Roma
        
        // Costruisce codice senza carattere controllo
        const codiceParziale = codiceCognome + codiceNome + anno + mese + giornoStr + codiceComune;
        
        // Debug per verificare i passaggi
        console.log('Cognome:', cognome, '→', codiceCognome);
        console.log('Nome:', nome, '→', codiceNome); 
        console.log('Data:', dataNascita, '→', anno + mese + giornoStr);
        console.log('Comune:', comune, '→', codiceComune);
        console.log('Codice parziale:', codiceParziale);
        
        // Calcola carattere controllo
        const carattereControllo = calcolaCarattereControllo(codiceParziale);
        
        const codiceFiscaleCompleto = codiceParziale + carattereControllo;
        document.getElementById('cfRisultato').value = codiceFiscaleCompleto;
        
    } catch (error) {
        alert('Errore nel calcolo. Verifica i dati inseriti.');
        console.error(error);
    }
}

function estraiCodice(stringa, lunghezza, isNome = false) {
    stringa = stringa.toUpperCase().replace(/[^A-Z]/g, ''); // Solo lettere maiuscole
    const consonanti = stringa.replace(/[AEIOU]/g, '');
    const vocali = stringa.replace(/[BCDFGHJKLMNPQRSTVWXYZ]/g, ''); // Solo vocali
    
    let codice = '';
    
    // Regola speciale per il NOME: se ha più di 3 consonanti, prende 1a, 3a, 4a
    if (isNome && consonanti.length >= 4) {
        codice = consonanti.charAt(0) + consonanti.charAt(2) + consonanti.charAt(3);
    } else {
        // Caso normale: prima le consonanti, poi le vocali
        codice = consonanti.substring(0, lunghezza);
        if (codice.length < lunghezza) {
            codice += vocali.substring(0, lunghezza - codice.length);
        }
    }
    
    // Riempie con X se necessario
    while (codice.length < lunghezza) {
        codice += 'X';
    }
    
    return codice.substring(0, lunghezza);
}

function calcolaCarattereControllo(codice) {
    // Tabelle per il calcolo del carattere di controllo (algoritmo ufficiale)
    const dispari = {
        '0': 1, '1': 0, '2': 5, '3': 7, '4': 9, '5': 13, '6': 15, '7': 17, '8': 19, '9': 21,
        'A': 1, 'B': 0, 'C': 5, 'D': 7, 'E': 9, 'F': 13, 'G': 15, 'H': 17, 'I': 19, 'J': 21,
        'K': 2, 'L': 4, 'M': 18, 'N': 20, 'O': 11, 'P': 3, 'Q': 6, 'R': 8, 'S': 12, 'T': 14,
        'U': 16, 'V': 10, 'W': 22, 'X': 25, 'Y': 24, 'Z': 23
    };
    
    const pari = {
        '0': 0, '1': 1, '2': 2, '3': 3, '4': 4, '5': 5, '6': 6, '7': 7, '8': 8, '9': 9,
        'A': 0, 'B': 1, 'C': 2, 'D': 3, 'E': 4, 'F': 5, 'G': 6, 'H': 7, 'I': 8, 'J': 9,
        'K': 10, 'L': 11, 'M': 12, 'N': 13, 'O': 14, 'P': 15, 'Q': 16, 'R': 17, 'S': 18, 'T': 19,
        'U': 20, 'V': 21, 'W': 22, 'X': 23, 'Y': 24, 'Z': 25
    };
    
    const controllo = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    
    let somma = 0;
    for (let i = 0; i < 15; i++) {
        const carattere = codice.charAt(i);
        if (i % 2 === 0) {
            somma += dispari[carattere];
        } else {
            somma += pari[carattere];
        }
    }
    
    return controllo.charAt(somma % 26);
}

function copiaCodiceFiscale() {
    const codice = document.getElementById('cfRisultato').value;
    if (!codice) {
        alert('Prima calcola il codice fiscale!');
        return;
    }
    
    // Copia nel campo del form
    document.getElementById('codice_fiscale').value = codice;
    
    // Copia negli appunti
    navigator.clipboard.writeText(codice).then(() => {
        alert('Codice fiscale copiato e inserito nel modulo!');
        closeCodiceFiscaleModal();
    }).catch(() => {
        alert('Codice fiscale inserito nel modulo!');
        closeCodiceFiscaleModal();
    });
}

// === FUNZIONI CALCOLATRICE SEMPLIFICATE ===
function updateCalculatorDisplay() {
    document.getElementById('calcDisplay').value = calculatorCurrentInput;
}

function appendNumber(number) {
    if (calculatorWaitingForOperand) {
        calculatorCurrentInput = number;
        calculatorWaitingForOperand = false;
    } else {
        calculatorCurrentInput = calculatorCurrentInput === '0' ? number : calculatorCurrentInput + number;
    }
    updateCalculatorDisplay();
}

function appendDecimal() {
    if (calculatorWaitingForOperand) {
        calculatorCurrentInput = '0.';
        calculatorWaitingForOperand = false;
    } else if (calculatorCurrentInput.indexOf('.') === -1) {
        calculatorCurrentInput += '.';
    }
    updateCalculatorDisplay();
}

function appendOperator(operator) {
    const inputValue = parseFloat(calculatorCurrentInput);

    if (calculatorPreviousInput === null) {
        calculatorPreviousInput = inputValue;
    } else if (calculatorOperator) {
        const result = performCalculation();
        calculatorCurrentInput = String(result);
        calculatorPreviousInput = result;
        updateCalculatorDisplay();
    }
    
    calculatorWaitingForOperand = true;
    calculatorOperator = operator;
}

function calculate() {
    const inputValue = parseFloat(calculatorCurrentInput);
    
    if (calculatorPreviousInput === null || calculatorOperator === null) {
        return;
    }
    
    const result = performCalculation();
    calculatorCurrentInput = String(result);
    calculatorPreviousInput = null;
    calculatorOperator = null;
    calculatorWaitingForOperand = true;
    updateCalculatorDisplay();
}

function performCalculation() {
    const prev = calculatorPreviousInput;
    const current = parseFloat(calculatorCurrentInput);
    
    switch (calculatorOperator) {
        case '+': return prev + current;
        case '-': return prev - current;
        case '*': return prev * current;
        case '/': return current !== 0 ? prev / current : 0;
        default: return current;
    }
}

function clearAll() {
    calculatorCurrentInput = '0';
    calculatorPreviousInput = null;
    calculatorOperator = null;
    calculatorWaitingForOperand = false;
    updateCalculatorDisplay();
}

function clearEntry() {
    calculatorCurrentInput = '0';
    updateCalculatorDisplay();
}

function deleteLast() {
    if (calculatorCurrentInput.length > 1) {
        calculatorCurrentInput = calculatorCurrentInput.slice(0, -1);
    } else {
        calculatorCurrentInput = '0';
    }
    updateCalculatorDisplay();
}

// Funzione percentuale professionale
function appendPercentage() {
    if (calculatorPreviousInput !== null && calculatorOperator !== null) {
        // Calcola percentuale del numero precedente (es: 100 + 20% = 100 + 20)
        const percentage = (calculatorPreviousInput * parseFloat(calculatorCurrentInput)) / 100;
        calculatorCurrentInput = String(percentage);
        updateCalculatorDisplay();
    } else {
        // Converte il numero corrente in percentuale (es: 50% = 0.5)
        const percentage = parseFloat(calculatorCurrentInput) / 100;
        calculatorCurrentInput = String(percentage);
        updateCalculatorDisplay();
    }
}

// Inizializza quando il DOM è pronto
document.addEventListener('DOMContentLoaded', function() {
    animatePageLoad();
});
</script>

@endsection