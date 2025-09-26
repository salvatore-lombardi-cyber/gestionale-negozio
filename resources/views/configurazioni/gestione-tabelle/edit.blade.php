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
                <!-- Campi dinamici basati su configurazione -->
                @if(isset($configurazione['campi_visibili']) && !empty($configurazione['campi_visibili']))
                    @foreach($configurazione['campi_visibili'] as $campo => $label)
                        @php
                            // Gestione valore iniziale per campo
                            if ($campo === 'aliquota_iva' && $nomeTabella === 'associazioni-nature-iva') {
                                $valore = old('tax_rate_id', $elemento->tax_rate_id ?? '');
                                $inputType = 'select';
                                $campo_reale = 'tax_rate_id';
                            } elseif ($campo === 'natura_iva' && $nomeTabella === 'associazioni-nature-iva') {
                                $valore = old('vat_nature_id', $elemento->vat_nature_id ?? '');
                                $inputType = 'select';
                                $campo_reale = 'vat_nature_id';
                            } elseif ($campo === 'predefinita' && $nomeTabella === 'associazioni-nature-iva') {
                                $valore = old('is_default', $elemento->is_default ?? false);
                                $inputType = 'checkbox';
                                $campo_reale = 'is_default';
                            } else {
                                $valore = old($campo, $elemento->{$campo} ?? '');
                                $campo_reale = $campo;
                            }
                            
                            $required = in_array($campo, ['name', 'nome', 'code', 'description']) ? 'required' : '';
                            $step = null;
                            
                            // Determinazione tipo input (se non già impostato)
                            if (!isset($inputType)) {
                                $inputType = 'text';
                                if (in_array($campo, ['percentuale', 'percentage', 'discount_percentage'])) {
                                    $inputType = 'number';
                                    $step = '0.01';
                                } elseif ($campo === 'email') {
                                    $inputType = 'email';
                                } elseif (in_array($campo, ['description', 'descrizione', 'comment'])) {
                                    $inputType = 'textarea';
                                } elseif ($campo === 'active') {
                                    $inputType = 'checkbox';
                                } elseif ($campo === 'type' && $nomeTabella === 'taglie-colori') {
                                    $inputType = 'select';
                                }
                            }
                        @endphp
                        
                        @if($inputType === 'checkbox')
                            <!-- Campo Checkbox -->
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="{{ $campo_reale }}" 
                                           name="{{ $campo_reale }}" 
                                           value="1"
                                           {{ $valore ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="{{ $campo_reale }}">
                                        {{ $label }}
                                    </label>
                                </div>
                                <div class="form-text">
                                    @if($campo === 'predefinita')
                                        Imposta come associazione predefinita
                                    @else
                                        Indica se l'elemento è attivo nel sistema
                                    @endif
                                </div>
                            </div>
                            
                        @elseif($inputType === 'select' && $campo === 'aliquota_iva')
                            <!-- Campo Select per Aliquota IVA -->
                            <div class="col-md-6">
                                <label for="{{ $campo_reale }}" class="form-label">{{ $label }} *</label>
                                <select class="form-select @error($campo_reale) is-invalid @enderror" 
                                        id="{{ $campo_reale }}" 
                                        name="{{ $campo_reale }}" 
                                        required>
                                    <option value="">Seleziona aliquota IVA...</option>
                                    @foreach(\App\Models\TaxRate::orderBy('percentuale')->get() as $taxRate)
                                    <option value="{{ $taxRate->id }}" {{ $valore == $taxRate->id ? 'selected' : '' }}>
                                        {{ $taxRate->code }} - {{ $taxRate->name }} ({{ $taxRate->percentuale }}%)
                                    </option>
                                    @endforeach
                                </select>
                                @error($campo_reale)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Aliquota IVA associata</div>
                            </div>
                            
                        @elseif($inputType === 'select' && $campo === 'natura_iva')
                            <!-- Campo Select per Natura IVA -->
                            <div class="col-md-6">
                                <label for="{{ $campo_reale }}" class="form-label">{{ $label }} *</label>
                                <select class="form-select @error($campo_reale) is-invalid @enderror" 
                                        id="{{ $campo_reale }}" 
                                        name="{{ $campo_reale }}" 
                                        required>
                                    <option value="">Seleziona natura IVA...</option>
                                    @foreach(\App\Models\VatNature::orderBy('vat_code')->get() as $vatNature)
                                    <option value="{{ $vatNature->id }}" {{ $valore == $vatNature->id ? 'selected' : '' }}>
                                        {{ $vatNature->vat_code }} - {{ $vatNature->nature }}
                                    </option>
                                    @endforeach
                                </select>
                                @error($campo_reale)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Natura IVA associata</div>
                            </div>
                            
                        @elseif($inputType === 'select' && $campo === 'type')
                            <!-- Campo Select per Tipo -->
                            <div class="col-md-6">
                                <label for="{{ $campo_reale }}" class="form-label">{{ $label }} *</label>
                                <select class="form-select @error($campo_reale) is-invalid @enderror" 
                                        id="{{ $campo_reale }}" 
                                        name="{{ $campo_reale }}" 
                                        required>
                                    <option value="">Seleziona tipo...</option>
                                    <option value="TAGLIA" {{ $valore === 'TAGLIA' ? 'selected' : '' }}>Taglia</option>
                                    <option value="COLORE" {{ $valore === 'COLORE' ? 'selected' : '' }}>Colore</option>
                                </select>
                                @error($campo_reale)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Tipo di variante prodotto</div>
                            </div>
                            
                        @elseif($inputType === 'textarea')
                            <!-- Campo Textarea -->
                            <div class="col-12">
                                <label for="{{ $campo_reale }}" class="form-label">{{ $label }}</label>
                                <textarea class="form-control @error($campo_reale) is-invalid @enderror" 
                                          id="{{ $campo_reale }}" 
                                          name="{{ $campo_reale }}" 
                                          rows="3" 
                                          maxlength="500">{{ $valore }}</textarea>
                                @error($campo_reale)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ $label }} (opzionale)</div>
                            </div>
                            
                        @else
                            <!-- Campo Input Text/Number -->
                            <div class="col-md-6">
                                <label for="{{ $campo_reale }}" class="form-label">{{ $label }} @if($required)*@endif</label>
                                <input type="{{ $inputType }}" 
                                       class="form-control @if($campo === 'code') label-uppercase @endif @error($campo_reale) is-invalid @enderror" 
                                       id="{{ $campo_reale }}" 
                                       name="{{ $campo_reale }}" 
                                       value="{{ $valore }}"
                                       @if($step) step="{{ $step }}" @endif
                                       @if($required) required @endif
                                       maxlength="255">
                                @error($campo_reale)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    @if($campo === 'percentuale' || $campo === 'percentage')
                                        Percentuale (es. 22.00 per 22%)
                                    @elseif($campo === 'code')
                                        Codice identificativo univoco
                                    @else
                                        {{ $label }} @if($required)(obbligatorio)@else(opzionale)@endif
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                @else
                    <!-- Fallback per tabelle senza configurazione -->
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Configurazione campi mancante per questa tabella. Contattare l'amministratore.
                        </div>
                    </div>
                @endif
            </div>
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('configurations.gestione-tabelle.tabella', $nomeTabella) }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-x-lg"></i> Annulla
                </a>
                <button type="submit" class="btn btn-primary modern-btn">
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