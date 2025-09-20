@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .management-container {
        padding: 2rem;
        min-height: calc(100vh - 70px);
    }
    
    /* Header con pulsanti */
    .management-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .management-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
        display: flex;
        align-items: center;
    }
    
    /* Pulsanti modern-btn coerenti */
    .modern-btn {
        padding: 12px 24px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .modern-btn:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(2, 157, 126, 0.3);
    }
    
    .modern-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    /* Gradiente GREEN standard per tutti i button */
    .modern-btn.btn-primary,
    .btn-primary.modern-btn {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        border: none;
    }
    
    .modern-btn.btn-secondary,
    .btn-secondary.modern-btn {
        background: linear-gradient(135deg, #6c757d, #545b62);
        color: white;
        border: none;
    }
    
    .modern-btn.btn-warning,
    .btn-warning.modern-btn {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: #212529;
        border: none;
    }
    
    /* Form container */
    .form-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .form-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }
    
    .form-title i {
        margin-right: 0.5rem;
        color: #029D7E;
    }
    
    /* Form inputs */
    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
    }
    
    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #029D7E;
        box-shadow: 0 0 0 3px rgba(2, 157, 126, 0.1);
    }
    
    .form-label {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
    }
    
    .form-text {
        color: #718096;
        font-size: 0.875rem;
    }
    
    /* Form check switch */
    .form-check-input:checked {
        background-color: #029D7E;
        border-color: #029D7E;
    }
    
    .form-check-input:focus {
        box-shadow: 0 0 0 3px rgba(2, 157, 126, 0.1);
    }
    
    /* Invalid feedback */
    .is-invalid {
        border-color: #dc3545;
    }
    
    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .management-container {
            padding: 1rem;
        }
        
        .management-header, .form-container {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .management-title {
            font-size: 1.8rem;
        }
        
        .modern-btn {
            padding: 10px 16px;
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 576px) {
        .management-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .form-container {
            padding: 1rem;
        }
    }

    /* ===== SISTEMAZIONE CSS INLINE ===== */
    /* Icona header edit */
    .header-icon-edit {
        color: #48cae4;
        font-size: 2rem;
    }

    /* Text uppercase per label */
    .label-uppercase {
        text-transform: uppercase;
    }
</style>

<div class="management-container">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
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
    
    <!-- Header con titolo e pulsanti -->
    <div class="management-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <h1 class="management-title">
                    <i class="{{ $configurazione['icona'] ?? 'bi-pencil' }} me-3 header-icon-edit"></i>
                    {{ $title }}
                </h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('configurations.gestione-tabelle.show', [$nomeTabella, $elemento->id]) }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-arrow-left"></i> Torna ai Dettagli
                </a>
                <a href="{{ route('configurations.gestione-tabelle.tabella', $nomeTabella) }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-list"></i> Lista Elementi
                </a>
            </div>
        </div>
    </div>

    <!-- Form container -->
    <div class="form-container">
        <div class="form-title">
            <i class="bi bi-pencil"></i>
            Modifica Elemento - {{ $configurazione['nome'] ?? ucfirst($nomeTabella) }}
        </div>
        
        <form method="POST" action="{{ route('configurations.gestione-tabelle.update', [$nomeTabella, $elemento->id]) }}">
            @csrf
            @method('PUT')
            
            <div class="row g-3">
                <!-- Campo Nome (comune a tutte le tabelle) -->
                <div class="col-md-6">
                    <label for="name" class="form-label">Nome *</label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $elemento->name ?? $elemento->nome) }}" 
                           required 
                           maxlength="255">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Nome dell'elemento (obbligatorio)</div>
                </div>
                
                <!-- Campo Codice (se presente) -->
                @if(isset($elemento->code))
                <div class="col-md-6">
                    <label for="code" class="form-label">Codice</label>
                    <input type="text" 
                           class="form-control label-uppercase @error('code') is-invalid @enderror" 
                           id="code" 
                           name="code" 
                           value="{{ old('code', $elemento->code) }}" 
                           maxlength="50">
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Codice identificativo univoco</div>
                </div>
                @endif
                
                <!-- Campo Descrizione (comune) -->
                <div class="col-12">
                    <label for="description" class="form-label">Descrizione</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="3" 
                              maxlength="500">{{ old('description', $elemento->description ?? $elemento->descrizione) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Descrizione dettagliata (opzionale)</div>
                </div>
                
                <!-- Campi specifici per Banche -->
                @if($nomeTabella === 'banche')
                    <div class="col-md-6">
                        <label for="abi_code" class="form-label">
                            Codice ABI
                            <i class="bi bi-info-circle ms-1" 
                               data-bs-toggle="tooltip" 
                               data-bs-placement="top" 
                               title="Associazione Bancaria Italiana - solo per banche italiane"></i>
                        </label>
                        <input type="text" 
                               class="form-control @error('abi_code') is-invalid @enderror" 
                               id="abi_code" 
                               name="abi_code" 
                               value="{{ old('abi_code', $elemento->abi_code) }}" 
                               maxlength="5" 
                               pattern="\d{5}">
                        @error('abi_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">5 cifre per banche italiane (es: 03069)</div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="bic_swift" class="form-label">
                            Codice BIC/SWIFT
                            <i class="bi bi-info-circle ms-1" 
                               data-bs-toggle="tooltip" 
                               data-bs-placement="top" 
                               title="Bank Identifier Code per transazioni internazionali"></i>
                        </label>
                        <input type="text" 
                               class="form-control label-uppercase @error('bic_swift') is-invalid @enderror" 
                               id="bic_swift" 
                               name="bic_swift" 
                               value="{{ old('bic_swift', $elemento->bic_swift) }}" 
                               maxlength="11">
                        @error('bic_swift')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">8-11 caratteri per transazioni internazionali</div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="city" class="form-label">Città</label>
                        <input type="text" 
                               class="form-control @error('city') is-invalid @enderror" 
                               id="city" 
                               name="city" 
                               value="{{ old('city', $elemento->city) }}" 
                               maxlength="100">
                        @error('city')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label for="country" class="form-label">Paese</label>
                        <input type="text" 
                               class="form-control @error('country') is-invalid @enderror" 
                               id="country" 
                               name="country" 
                               value="{{ old('country', $elemento->country) }}" 
                               maxlength="100">
                        @error('country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-12">
                        <label for="address" class="form-label">Indirizzo</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" 
                                  name="address" 
                                  rows="2" 
                                  maxlength="500">{{ old('address', $elemento->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="phone" class="form-label">Telefono</label>
                        <input type="text" 
                               class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $elemento->phone) }}" 
                               maxlength="50">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $elemento->email) }}" 
                               maxlength="100">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="website" class="form-label">Sito Web</label>
                        <input type="url" 
                               class="form-control @error('website') is-invalid @enderror" 
                               id="website" 
                               name="website" 
                               value="{{ old('website', $elemento->website) }}" 
                               maxlength="255">
                        @error('website')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
                
                <!-- Campo Stato Attivo (comune) -->
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="active" 
                               name="active" 
                               value="1"
                               {{ old('active', $elemento->active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="active">
                            Attivo
                        </label>
                    </div>
                    <div class="form-text">Indica se l'elemento è attivo nel sistema</div>
                </div>
                
                <!-- Campo Sort Order (se applicabile) -->
                @if(isset($elemento->sort_order) || property_exists($elemento, 'sort_order'))
                <div class="col-md-6">
                    <label for="sort_order" class="form-label">Ordine di Visualizzazione</label>
                    <input type="number" 
                           class="form-control @error('sort_order') is-invalid @enderror" 
                           id="sort_order" 
                           name="sort_order" 
                           value="{{ old('sort_order', $elemento->sort_order ?? 0) }}" 
                           min="0" 
                           max="9999">
                    @error('sort_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Numero per ordinare gli elementi (0-9999)</div>
                </div>
                @endif
            </div>
            
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('configurations.gestione-tabelle.show', [$nomeTabella, $elemento->id]) }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-x-lg"></i> Annulla
                </a>
                <button type="submit" class="btn btn-warning modern-btn">
                    <i class="bi bi-check-lg"></i> Salva Modifiche
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts dopo 5 secondi
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert, index) {
        setTimeout(function() {
            if (bootstrap.Alert.getOrCreateInstance) {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                bsAlert.close();
            }
        }, 5000 + (index * 1000));
    });
    
    // Inizializza tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            html: true,
            delay: { show: 300, hide: 100 }
        });
    });
    
    // Validazione input code in maiuscolo
    const codeInput = document.getElementById('code');
    if (codeInput) {
        codeInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase().replace(/[^A-Z0-9_-]/g, '');
        });
    }
    
    // Validazione input BIC/SWIFT in maiuscolo
    const bicInput = document.getElementById('bic_swift');
    if (bicInput) {
        bicInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.toUpperCase();
        });
    }
});
</script>
@endsection