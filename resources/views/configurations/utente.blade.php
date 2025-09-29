@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .config-container {
        padding: 2rem;
    }
    
    .config-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .config-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .config-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
    
    .form-control, .form-select {
        border-radius: 15px;
        padding: 15px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.8);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
        background: white;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        border: none;
        border-radius: 15px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(2, 157, 126, 0.3);
    }
    
    .btn-outline-secondary {
        border-radius: 15px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .logo-preview {
        max-width: 200px;
        max-height: 200px;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
    }
    
    .alert {
        border-radius: 15px;
        border: none;
        padding: 20px;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #4ecdc4, #44a08d);
        color: white;
    }
    
    /* Dark Mode */
    [data-bs-theme="dark"] .config-header {
        background: rgba(45, 55, 72, 0.95) !important;
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .config-card {
        background: rgba(45, 55, 72, 0.95) !important;
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .section-title {
        color: #e2e8f0 !important;
        border-bottom-color: rgba(255, 255, 255, 0.1) !important;
    }
    
    [data-bs-theme="dark"] .form-control,
    [data-bs-theme="dark"] .form-select {
        background: rgba(45, 55, 72, 0.8) !important;
        border-color: rgba(2, 157, 126, 0.3) !important;
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .form-control:focus,
    [data-bs-theme="dark"] .form-select:focus {
        background: rgba(45, 55, 72, 0.9) !important;
        border-color: #029D7E !important;
        color: #e2e8f0 !important;
    }
</style>

<div class="container-fluid config-container">
    <!-- Header -->
    <div class="config-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="config-title">
                    <i class="bi bi-person-badge"></i> Profilo Utente
                </h1>
            </div>
            <a href="{{ route('configurations.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> {{ __('app.back') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('configurations.utente.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Dati Anagrafici -->
        <div class="config-card">
            <h3 class="section-title">
                <i class="bi bi-person-badge"></i> {{ __('app.company_data') }}
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="ragione_sociale" class="form-label">{{ __('app.company_name') }}</label>
                        <input type="text" class="form-control @error('ragione_sociale') is-invalid @enderror" 
                               id="ragione_sociale" name="ragione_sociale" 
                               value="{{ old('ragione_sociale', $company->ragione_sociale) }}">
                        @error('ragione_sociale')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="nome" class="form-label">{{ __('app.name') }}</label>
                        <input type="text" class="form-control @error('nome') is-invalid @enderror" 
                               id="nome" name="nome" 
                               value="{{ old('nome', $company->nome) }}">
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="cognome" class="form-label">{{ __('app.surname') }}</label>
                        <input type="text" class="form-control @error('cognome') is-invalid @enderror" 
                               id="cognome" name="cognome" 
                               value="{{ old('cognome', $company->cognome) }}">
                        @error('cognome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="indirizzo_sede" class="form-label">{{ __('app.address') }}</label>
                        <textarea class="form-control @error('indirizzo_sede') is-invalid @enderror" 
                                id="indirizzo_sede" name="indirizzo_sede" rows="3">{{ old('indirizzo_sede', $company->indirizzo_sede) }}</textarea>
                        @error('indirizzo_sede')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="cap" class="form-label">{{ __('app.postal_code') }}</label>
                        <input type="text" class="form-control @error('cap') is-invalid @enderror" 
                               id="cap" name="cap" 
                               value="{{ old('cap', $company->cap) }}">
                        @error('cap')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="provincia" class="form-label">{{ __('app.province') }}</label>
                        <input type="text" class="form-control @error('provincia') is-invalid @enderror" 
                               id="provincia" name="provincia" 
                               value="{{ old('provincia', $company->provincia) }}">
                        @error('provincia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="citta" class="form-label">{{ __('app.city') }}</label>
                        <input type="text" class="form-control @error('citta') is-invalid @enderror" 
                               id="citta" name="citta" 
                               value="{{ old('citta', $company->citta) }}">
                        @error('citta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="nazione" class="form-label">{{ __('app.country') }}</label>
                        <input type="text" class="form-control @error('nazione') is-invalid @enderror" 
                               id="nazione" name="nazione" 
                               value="{{ old('nazione', $company->nazione) }}">
                        @error('nazione')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Contatti -->
        <div class="config-card">
            <h3 class="section-title">
                <i class="bi bi-telephone"></i> {{ __('app.contacts') }}
            </h3>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="telefono1" class="form-label">{{ __('app.phone') }} 1</label>
                        <input type="text" class="form-control @error('telefono1') is-invalid @enderror" 
                               id="telefono1" name="telefono1" 
                               value="{{ old('telefono1', $company->telefono1) }}">
                        @error('telefono1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="telefono2" class="form-label">{{ __('app.phone') }} 2</label>
                        <input type="text" class="form-control @error('telefono2') is-invalid @enderror" 
                               id="telefono2" name="telefono2" 
                               value="{{ old('telefono2', $company->telefono2) }}">
                        @error('telefono2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="fax" class="form-label">{{ __('app.fax') }}</label>
                        <input type="text" class="form-control @error('fax') is-invalid @enderror" 
                               id="fax" name="fax" 
                               value="{{ old('fax', $company->fax) }}">
                        @error('fax')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="cellulare1" class="form-label">{{ __('app.mobile') }} 1</label>
                        <input type="text" class="form-control @error('cellulare1') is-invalid @enderror" 
                               id="cellulare1" name="cellulare1" 
                               value="{{ old('cellulare1', $company->cellulare1) }}">
                        @error('cellulare1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="cellulare2" class="form-label">{{ __('app.mobile') }} 2</label>
                        <input type="text" class="form-control @error('cellulare2') is-invalid @enderror" 
                               id="cellulare2" name="cellulare2" 
                               value="{{ old('cellulare2', $company->cellulare2) }}">
                        @error('cellulare2')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('app.email') }}</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" 
                               value="{{ old('email', $company->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="sito_web" class="form-label">{{ __('app.website') }}</label>
                        <input type="url" class="form-control @error('sito_web') is-invalid @enderror" 
                               id="sito_web" name="sito_web" 
                               value="{{ old('sito_web', $company->sito_web) }}">
                        @error('sito_web')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Dati Fiscali -->
        <div class="config-card">
            <h3 class="section-title">
                <i class="bi bi-receipt"></i> {{ __('app.tax_data') }}
            </h3>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="partita_iva" class="form-label">{{ __('app.vat_number') }}</label>
                        <input type="text" class="form-control @error('partita_iva') is-invalid @enderror" 
                               id="partita_iva" name="partita_iva" 
                               value="{{ old('partita_iva', $company->partita_iva) }}">
                        @error('partita_iva')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="codice_attivita_iva" class="form-label">{{ __('app.vat_activity_code') }}</label>
                        <input type="text" class="form-control @error('codice_attivita_iva') is-invalid @enderror" 
                               id="codice_attivita_iva" name="codice_attivita_iva" 
                               value="{{ old('codice_attivita_iva', $company->codice_attivita_iva) }}">
                        @error('codice_attivita_iva')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="regime_fiscale" class="form-label">{{ __('app.tax_regime') }}</label>
                        <input type="text" class="form-control @error('regime_fiscale') is-invalid @enderror" 
                               id="regime_fiscale" name="regime_fiscale" 
                               value="{{ old('regime_fiscale', $company->regime_fiscale) }}">
                        @error('regime_fiscale')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="iva_esente" name="iva_esente" value="1"
                               {{ old('iva_esente', $company->iva_esente) ? 'checked' : '' }}>
                        <label class="form-check-label" for="iva_esente">
                            {{ __('app.vat_exempt') }}
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logo -->
        <div class="config-card">
            <h3 class="section-title">
                <i class="bi bi-image"></i> {{ __('app.company_logo') }}
            </h3>
            
            @if($company->logo_url)
                <div class="mb-3">
                    <img src="{{ $company->logo_url }}" alt="Logo attuale" class="logo-preview">
                </div>
            @endif
            
            <div class="mb-3">
                <label for="logo" class="form-label">{{ __('app.upload_logo') }}</label>
                <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                       id="logo" name="logo" accept="image/*">
                @error('logo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Formati supportati: JPG, PNG, GIF. Dimensione massima: 2MB</div>
            </div>
        </div>

        <!-- Dati Nascita (per persone fisiche) -->
        <div class="config-card">
            <h3 class="section-title">
                <i class="bi bi-calendar-person"></i> Dati di Nascita
            </h3>
            <p class="text-muted mb-3">Compila solo per titolari persone fisiche</p>
            
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="genere" class="form-label">Genere</label>
                        <select class="form-select @error('genere') is-invalid @enderror" id="genere" name="genere">
                            <option value="">Seleziona</option>
                            <option value="M" {{ old('genere', $company->genere) == 'M' ? 'selected' : '' }}>Maschio</option>
                            <option value="F" {{ old('genere', $company->genere) == 'F' ? 'selected' : '' }}>Femmina</option>
                            <option value="Altro" {{ old('genere', $company->genere) == 'Altro' ? 'selected' : '' }}>Altro</option>
                        </select>
                        @error('genere')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="data_nascita" class="form-label">Data di Nascita</label>
                        <input type="date" class="form-control @error('data_nascita') is-invalid @enderror" 
                               id="data_nascita" name="data_nascita" 
                               value="{{ old('data_nascita', $company->data_nascita ? $company->data_nascita->format('Y-m-d') : '') }}">
                        @error('data_nascita')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="luogo_nascita" class="form-label">Luogo di Nascita</label>
                        <input type="text" class="form-control @error('luogo_nascita') is-invalid @enderror" 
                               id="luogo_nascita" name="luogo_nascita" 
                               value="{{ old('luogo_nascita', $company->luogo_nascita) }}">
                        @error('luogo_nascita')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="provincia_nascita" class="form-label">Provincia di Nascita</label>
                        <input type="text" class="form-control @error('provincia_nascita') is-invalid @enderror" 
                               id="provincia_nascita" name="provincia_nascita" 
                               value="{{ old('provincia_nascita', $company->provincia_nascita) }}">
                        @error('provincia_nascita')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Dati Legali e Registrazioni -->
        <div class="config-card">
            <h3 class="section-title">
                <i class="bi bi-building-check"></i> Dati Legali e Registrazioni
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="attivita" class="form-label">Descrizione Attività</label>
                        <textarea class="form-control @error('attivita') is-invalid @enderror" 
                                id="attivita" name="attivita" rows="3">{{ old('attivita', $company->attivita) }}</textarea>
                        @error('attivita')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="capitale_sociale" class="form-label">Capitale Sociale (€)</label>
                        <input type="number" class="form-control @error('capitale_sociale') is-invalid @enderror" 
                               id="capitale_sociale" name="capitale_sociale" step="0.01"
                               value="{{ old('capitale_sociale', $company->capitale_sociale) }}">
                        @error('capitale_sociale')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="numero_tribunale" class="form-label">Numero REA Tribunale</label>
                        <input type="text" class="form-control @error('numero_tribunale') is-invalid @enderror" 
                               id="numero_tribunale" name="numero_tribunale" 
                               value="{{ old('numero_tribunale', $company->numero_tribunale) }}">
                        @error('numero_tribunale')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="cciaa" class="form-label">Numero CCIAA</label>
                        <input type="text" class="form-control @error('cciaa') is-invalid @enderror" 
                               id="cciaa" name="cciaa" 
                               value="{{ old('cciaa', $company->cciaa) }}">
                        @error('cciaa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Sistema di Interscambio (SDI) -->
        <div class="config-card">
            <h3 class="section-title">
                <i class="bi bi-shield-lock"></i> Sistema di Interscambio (SDI)
            </h3>
            <p class="text-muted mb-3">Credenziali per l'accesso al Sistema di Interscambio dell'Agenzia delle Entrate</p>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="sdi_username" class="form-label">Username SDI</label>
                        <input type="text" class="form-control @error('sdi_username') is-invalid @enderror" 
                               id="sdi_username" name="sdi_username" 
                               value="{{ old('sdi_username') }}"
                               placeholder="Username per accesso SDI">
                        @error('sdi_username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="sdi_password" class="form-label">Password SDI</label>
                        <input type="password" class="form-control @error('sdi_password') is-invalid @enderror" 
                               id="sdi_password" name="sdi_password" 
                               placeholder="Password per accesso SDI">
                        @error('sdi_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i> I dati SDI vengono crittografati prima del salvataggio
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="config-card text-center">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-check-circle"></i> {{ __('app.save_changes') }}
            </button>
        </div>
    </form>
</div>
@endsection