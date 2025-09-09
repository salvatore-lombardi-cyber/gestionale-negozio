@extends('layouts.app')

@section('title', 'Modifica Fattura N. ' . $vendita->numero_documento . ' - Gestionale Negozio')

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
    
    .modern-btn.success {
        background: linear-gradient(135deg, #28a745, #20c997);
    }
    
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
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #029D7E;
        box-shadow: 0 0 0 0.25rem rgba(2, 157, 126, 0.25);
    }
    
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
    }
</style>

<div class="fatturazione-container">
    <!-- Header della Pagina -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">
                <i class="bi bi-pencil-square"></i> Modifica Fattura N. {{ $vendita->numero_documento }}
            </h1>
            <div>
                <a href="{{ route('fatturazione.show', $vendita) }}" class="modern-btn secondary me-2">
                    <i class="bi bi-eye"></i> {{ __('app.view') }}
                </a>
                <a href="{{ route('fatturazione.index') }}" class="modern-btn">
                    <i class="bi bi-arrow-left"></i> {{ __('app.torna_indietro') }}
                </a>
            </div>
        </div>
    </div>
    
    @php
        $datiCliente = json_decode($vendita->prodotti_vendita, true);
        $tipo = $datiCliente['tipo'] ?? 'definitivo';
        $datiExtra = $datiCliente['dati_cliente'] ?? [];
    @endphp
    
    <form action="{{ route('fatturazione.update', $vendita) }}" method="POST" id="invoiceForm">
        @csrf
        @method('PUT')
        
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
                            <option value="definitivo" {{ old('tipo', $tipo) == 'definitivo' ? 'selected' : '' }}>{{ __('app.definitivo') }}</option>
                            <option value="potenziale" {{ old('tipo', $tipo) == 'potenziale' ? 'selected' : '' }}>{{ __('app.potenziale') }}</option>
                        </select>
                        @error('tipo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="numero_documento" class="form-label">{{ __('app.numero_fattura') }} *</label>
                        <input type="number" name="numero_documento" id="numero_documento" 
                               class="form-control @error('numero_documento') is-invalid @enderror" 
                               value="{{ old('numero_documento', $vendita->numero_documento) }}" required>
                        @error('numero_documento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="data_documento" class="form-label">{{ __('app.data_fattura') }} *</label>
                        <input type="date" name="data_documento" id="data_documento" 
                               class="form-control @error('data_documento') is-invalid @enderror" 
                               value="{{ old('data_documento', $vendita->data_documento ? $vendita->data_documento->format('Y-m-d') : '') }}" required>
                        @error('data_documento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="cliente_id" class="form-label">{{ __('app.cliente') }}</label>
                        <select name="cliente_id" id="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror">
                            <option value="">{{ __('app.seleziona_cliente') }}</option>
                            @foreach($clienti as $cliente)
                                <option value="{{ $cliente->id }}" {{ old('cliente_id', $vendita->cliente_id) == $cliente->id ? 'selected' : '' }}>
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
                                   value="{{ old('indirizzo', $datiExtra['indirizzo'] ?? '') }}" 
                                   placeholder="{{ __('app.inserisci_indirizzo') }}">
                            <i class="bi bi-geo-alt input-icon" onclick="openGoogleMaps()" title="{{ __('app.cerca_su_mappa') }}"></i>
                        </div>
                        @error('indirizzo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label for="provincia" class="form-label">{{ __('app.provincia') }}</label>
                        <input type="text" name="provincia" id="provincia" 
                               class="form-control @error('provincia') is-invalid @enderror" 
                               value="{{ old('provincia', $datiExtra['provincia'] ?? '') }}" maxlength="2" placeholder="RM">
                        @error('provincia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="citta" class="form-label">{{ __('app.citta') }}</label>
                        <input type="text" name="citta" id="citta" 
                               class="form-control @error('citta') is-invalid @enderror" 
                               value="{{ old('citta', $datiExtra['citta'] ?? '') }}" 
                               placeholder="{{ __('app.inserisci_citta') }}">
                        @error('citta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-1">
                        <label for="cap" class="form-label">{{ __('app.cap') }}</label>
                        <input type="text" name="cap" id="cap" 
                               class="form-control @error('cap') is-invalid @enderror" 
                               value="{{ old('cap', $datiExtra['cap'] ?? '') }}" maxlength="5" placeholder="00100">
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
                               value="{{ old('paese', $datiExtra['paese'] ?? 'Italia') }}" placeholder="Italia">
                        @error('paese')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="codice_fiscale" class="form-label">{{ __('app.codice_fiscale') }}</label>
                        <div class="input-group-icon">
                            <input type="text" name="codice_fiscale" id="codice_fiscale" 
                                   class="form-control @error('codice_fiscale') is-invalid @enderror" 
                                   value="{{ old('codice_fiscale', $datiExtra['codice_fiscale'] ?? '') }}" 
                                   maxlength="16" placeholder="RSSMRA80A01H501Z">
                            <i class="bi bi-calculator input-icon" onclick="openCodiceFiscaleCalculator()" title="{{ __('app.calcola_codice_fiscale') }}"></i>
                        </div>
                        @error('codice_fiscale')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="partita_iva" class="form-label">{{ __('app.partita_iva') }}</label>
                        <input type="text" name="partita_iva" id="partita_iva" 
                               class="form-control @error('partita_iva') is-invalid @enderror" 
                               value="{{ old('partita_iva', $datiExtra['partita_iva'] ?? '') }}" 
                               maxlength="11" placeholder="12345678901">
                        @error('partita_iva')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="partita_iva_cee" class="form-label">{{ __('app.partita_iva_cee') }}</label>
                        <input type="text" name="partita_iva_cee" id="partita_iva_cee" 
                               class="form-control @error('partita_iva_cee') is-invalid @enderror" 
                               value="{{ old('partita_iva_cee', $datiExtra['partita_iva_cee'] ?? '') }}" 
                               placeholder="IT12345678901">
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
                               value="{{ old('telefono', $datiExtra['telefono'] ?? '') }}" 
                               placeholder="+39 06 12345678">
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="telefax" class="form-label">{{ __('app.telefax') }}</label>
                        <input type="tel" name="telefax" id="telefax" 
                               class="form-control @error('telefax') is-invalid @enderror" 
                               value="{{ old('telefax', $datiExtra['telefax'] ?? '') }}" 
                               placeholder="+39 06 12345679">
                        @error('telefax')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="contatto" class="form-label">{{ __('app.contatto') }}</label>
                        <input type="text" name="contatto" id="contatto" 
                               class="form-control @error('contatto') is-invalid @enderror" 
                               value="{{ old('contatto', $datiExtra['contatto'] ?? '') }}" 
                               placeholder="{{ __('app.nome_contatto') }}">
                        @error('contatto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="email" class="form-label">{{ __('app.email') }}</label>
                        <input type="email" name="email" id="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $datiExtra['email'] ?? '') }}" 
                               placeholder="cliente@esempio.it">
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
                               value="{{ old('www', $datiExtra['www'] ?? '') }}" 
                               placeholder="https://www.esempio.it">
                        @error('www')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="pec" class="form-label">{{ __('app.pec') }}</label>
                        <input type="email" name="pec" id="pec" 
                               class="form-control @error('pec') is-invalid @enderror" 
                               value="{{ old('pec', $datiExtra['pec'] ?? '') }}" 
                               placeholder="cliente@pec.it">
                        @error('pec')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label for="tessera" class="form-label">{{ __('app.tessera') }}</label>
                        <input type="text" name="tessera" id="tessera" 
                               class="form-control @error('tessera') is-invalid @enderror" 
                               value="{{ old('tessera', $datiExtra['tessera'] ?? '') }}" 
                               placeholder="12345">
                        @error('tessera')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <label for="codice_sdi" class="form-label">{{ __('app.codice_sdi') }}</label>
                        <input type="text" name="codice_sdi" id="codice_sdi" 
                               class="form-control @error('codice_sdi') is-invalid @enderror" 
                               value="{{ old('codice_sdi', $datiExtra['codice_sdi'] ?? '') }}" 
                               maxlength="7" placeholder="ABCDEFG">
                        @error('codice_sdi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-2">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" name="pubblica_amministrazione" 
                                   id="pubblica_amministrazione" value="1" 
                                   {{ old('pubblica_amministrazione', $datiExtra['pubblica_amministrazione'] ?? false) ? 'checked' : '' }}>
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
                          placeholder="{{ __('app.inserisci_note_facoltative') }}">{{ old('note', $vendita->note) }}</textarea>
            </div>
        </div>
        
        <!-- Pulsanti di azione -->
        <div class="form-card">
            <div class="text-center">
                <button type="submit" class="modern-btn success me-3">
                    <i class="bi bi-check-circle"></i> {{ __('app.update') }} Fattura
                </button>
                <a href="{{ route('fatturazione.show', $vendita) }}" class="modern-btn secondary">
                    <i class="bi bi-x-circle"></i> {{ __('app.cancel') }}
                </a>
            </div>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
// Animazioni caricamento pagina
document.addEventListener('DOMContentLoaded', function() {
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
});

// Apri Google Maps per cercare indirizzo
function openGoogleMaps() {
    const indirizzo = document.getElementById('indirizzo').value;
    const citta = document.getElementById('citta').value;
    const provincia = document.getElementById('provincia').value;
    
    let query = '';
    if (indirizzo) query += indirizzo + ' ';
    if (citta) query += citta + ' ';
    if (provincia) query += provincia + ' Italia';
    
    if (!query.trim()) query = 'Italia';
    
    const url = 'https://www.google.com/maps/search/' + encodeURIComponent(query);
    window.open(url, '_blank');
}

// Apri calcolatore codice fiscale
function openCodiceFiscaleCalculator() {
    const url = 'https://www.codicefiscale.com/';
    window.open(url, '_blank');
}
</script>
@endsection