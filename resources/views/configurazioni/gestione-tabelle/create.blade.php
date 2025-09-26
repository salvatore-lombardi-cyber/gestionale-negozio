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
    
    .modern-btn.btn-success,
    .btn-success.modern-btn {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
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
    /* Icona header create */
    .header-icon-create {
        color: #48cae4;
        font-size: 2rem;
    }

    /* Text uppercase per input */
    .input-uppercase {
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
                    <i class="{{ $configurazione['icona'] ?? 'bi-plus' }} me-3 header-icon-create"></i>
                    {{ $title }}
                </h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('configurations.gestione-tabelle.tabella', $nomeTabella) }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-arrow-left"></i> Torna alla Lista
                </a>
            </div>
        </div>
    </div>

    <!-- Form container -->
    <div class="form-container">
        <div class="form-title">
            <i class="bi bi-plus-lg"></i>
            Nuovo Elemento - {{ $configurazione['nome'] ?? ucfirst($nomeTabella) }}
        </div>
        
        <form method="POST" action="{{ route('configurations.gestione-tabelle.store', $nomeTabella) }}">
            @csrf
            
            <div class="row g-3">
                @php
                    // Ottieni i campi dalla configurazione della tabella
                    $campiConfig = $configurazione['campi_visibili'] ?? [];
                @endphp
                
                @foreach($campiConfig as $campo => $label)
                    <div class="col-md-{{ in_array($campo, ['description', 'comment', 'address', 'nome_associazione']) ? '12' : '6' }}">
                        <label for="{{ $campo }}" class="form-label">
                            {{ $label }}{{ in_array($campo, ['nome_associazione', 'description', 'aliquota_iva', 'natura_iva']) ? ' *' : '' }}
                        </label>
                        
                        @if($campo === 'aliquota_iva')
                            {{-- Dropdown Aliquote IVA --}}
                            <select class="form-select @error($campo) is-invalid @enderror" 
                                    id="{{ $campo }}" 
                                    name="{{ $campo }}" 
                                    required>
                                <option value="">Seleziona aliquota...</option>
                                @if(isset($extraData['aliquote_iva']))
                                    @foreach($extraData['aliquote_iva'] as $aliquota)
                                        <option value="{{ $aliquota->id }}" {{ old($campo) == $aliquota->id ? 'selected' : '' }}>
                                            {{ $aliquota->percentuale }}% - {{ $aliquota->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            
                        @elseif($campo === 'natura_iva')
                            {{-- Dropdown Nature IVA --}}
                            <select class="form-select @error($campo) is-invalid @enderror" 
                                    id="{{ $campo }}" 
                                    name="{{ $campo }}" 
                                    required>
                                <option value="">Seleziona natura...</option>
                                @if(isset($extraData['nature_iva']))
                                    @foreach($extraData['nature_iva'] as $natura)
                                        <option value="{{ $natura->id }}" {{ old($campo) == $natura->id ? 'selected' : '' }}>
                                            {{ $natura->vat_code }} - {{ $natura->nature }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            
                        @elseif(in_array($campo, ['description', 'comment', 'address']))
                            {{-- Campi textarea --}}
                            <textarea class="form-control @error($campo) is-invalid @enderror" 
                                      id="{{ $campo }}" 
                                      name="{{ $campo }}" 
                                      rows="3"
                                      @if($campo === 'description') required @endif
                                      maxlength="255">{{ old($campo) }}</textarea>
                        @else
                            {{-- Campi input text --}}
                            <input type="text" 
                                   class="form-control @error($campo) is-invalid @enderror" 
                                   id="{{ $campo }}" 
                                   name="{{ $campo }}" 
                                   value="{{ old($campo) }}" 
                                   @if(in_array($campo, ['nome_associazione', 'description'])) required @endif
                                   maxlength="100">
                        @endif
                        
                        @error($campo)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
            </div>
            
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('configurations.gestione-tabelle.tabella', $nomeTabella) }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-x-lg"></i> Annulla
                </a>
                <button type="submit" class="btn btn-success modern-btn">
                    <i class="bi bi-check-lg"></i> Salva Elemento
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