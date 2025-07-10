@extends('layouts.app')

@section('title', 'Nuovo Cliente - Gestionale Negozio')

@section('content')
<style>
    .create-container {
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
        background: linear-gradient(135deg, #667eea, #764ba2);
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
        color: #667eea;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .modern-input {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid rgba(102, 126, 234, 0.2);
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        margin-bottom: 0.5rem;
    }
    
    .modern-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        background: white;
        transform: translateY(-2px);
    }
    
    .modern-input.is-invalid {
        border-color: #f72585;
        box-shadow: 0 0 0 0.2rem rgba(247, 37, 133, 0.25);
    }
    
    .modern-textarea {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid rgba(102, 126, 234, 0.2);
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
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        background: white;
        transform: translateY(-2px);
    }
    
    .modern-textarea.is-invalid {
        border-color: #f72585;
        box-shadow: 0 0 0 0.2rem rgba(247, 37, 133, 0.25);
    }
    
    .modern-select {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid rgba(102, 126, 234, 0.2);
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        margin-bottom: 0.5rem;
        cursor: pointer;
    }
    
    .modern-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        background: white;
        transform: translateY(-2px);
    }
    
    .modern-select.is-invalid {
        border-color: #f72585;
        box-shadow: 0 0 0 0.2rem rgba(247, 37, 133, 0.25);
    }
    
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .required {
        color: #f72585;
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
        position: relative;
        overflow: hidden;
    }
    
    .modern-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .modern-btn:hover::before {
        left: 0;
    }
    
    .modern-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .modern-btn.secondary {
        background: linear-gradient(135deg, #6c757d, #495057);
    }
    
    .modern-btn.secondary:hover {
        box-shadow: 0 15px 35px rgba(108, 117, 125, 0.4);
    }
    
    .invalid-feedback {
        color: #f72585;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        font-weight: 500;
    }
    
    .input-icon {
        position: relative;
    }
    
    .input-icon i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #667eea;
        font-size: 1.1rem;
        z-index: 10;
    }
    
    .input-icon .modern-input {
        padding-left: 50px;
    }
    
    .gender-options {
        display: flex;
        gap: 1rem;
        margin-top: 0.5rem;
    }
    
    .gender-option {
        flex: 1;
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(102, 126, 234, 0.2);
        border-radius: 15px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .gender-option:hover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.1);
        transform: translateY(-2px);
    }
    
    .gender-option.selected {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-color: transparent;
    }
    
    .gender-option input[type="radio"] {
        display: none;
    }
    
    .gender-option .icon {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    /* Dark Mode */
    [data-bs-theme="dark"] .page-header,
    [data-bs-theme="dark"] .form-card {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .modern-input,
    [data-bs-theme="dark"] .modern-textarea,
    [data-bs-theme="dark"] .modern-select {
        background: rgba(45, 55, 72, 0.8);
        border-color: rgba(102, 126, 234, 0.3);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .modern-input:focus,
    [data-bs-theme="dark"] .modern-textarea:focus,
    [data-bs-theme="dark"] .modern-select:focus {
        background: rgba(45, 55, 72, 0.9);
    }
    
    [data-bs-theme="dark"] .form-label {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .gender-option {
        background: rgba(45, 55, 72, 0.8);
        color: #e2e8f0;
        border-color: rgba(102, 126, 234, 0.3);
    }
    
    [data-bs-theme="dark"] .gender-option:hover {
        background: rgba(102, 126, 234, 0.2);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .create-container {
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
        
        .gender-options {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .input-icon i {
            display: none;
        }
        
        .input-icon .modern-input {
            padding-left: 20px;
        }
    }
</style>

<div class="create-container">
    <!-- Header della Pagina -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">
                <i class="bi bi-person-plus"></i> Nuovo Cliente
            </h1>
            <a href="{{ route('clienti.index') }}" class="modern-btn secondary">
                <i class="bi bi-arrow-left"></i> Indietro
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form action="{{ route('clienti.store') }}" method="POST" id="form-cliente">
            @csrf
            
            <!-- Informazioni Personali -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="bi bi-person"></i> Informazioni Personali
                </h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome <span class="required">*</span></label>
                            <div class="input-icon">
                                <i class="bi bi-person"></i>
                                <input type="text" class="modern-input @error('nome') is-invalid @enderror" 
                                       id="nome" name="nome" value="{{ old('nome') }}" required placeholder="Inserisci il nome">
                            </div>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="cognome" class="form-label">Cognome <span class="required">*</span></label>
                            <div class="input-icon">
                                <i class="bi bi-person"></i>
                                <input type="text" class="modern-input @error('cognome') is-invalid @enderror" 
                                       id="cognome" name="cognome" value="{{ old('cognome') }}" required placeholder="Inserisci il cognome">
                            </div>
                            @error('cognome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="data_nascita" class="form-label">Data di Nascita</label>
                            <div class="input-icon">
                                <i class="bi bi-calendar"></i>
                                <input type="date" class="modern-input @error('data_nascita') is-invalid @enderror" 
                                       id="data_nascita" name="data_nascita" value="{{ old('data_nascita') }}">
                            </div>
                            @error('data_nascita')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Genere</label>
                            <div class="gender-options">
                                <div class="gender-option" onclick="selectGender('M', this)">
                                    <input type="radio" name="genere" value="M" {{ old('genere') == 'M' ? 'checked' : '' }}>
                                    <span class="icon">👨</span>
                                    <div>Maschio</div>
                                </div>
                                <div class="gender-option" onclick="selectGender('F', this)">
                                    <input type="radio" name="genere" value="F" {{ old('genere') == 'F' ? 'checked' : '' }}>
                                    <span class="icon">👩</span>
                                    <div>Femmina</div>
                                </div>
                                <div class="gender-option" onclick="selectGender('Altro', this)">
                                    <input type="radio" name="genere" value="Altro" {{ old('genere') == 'Altro' ? 'checked' : '' }}>
                                    <span class="icon">🧑</span>
                                    <div>Altro</div>
                                </div>
                            </div>
                            @error('genere')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contatti -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="bi bi-telephone"></i> Contatti
                </h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Telefono</label>
                            <div class="input-icon">
                                <i class="bi bi-telephone"></i>
                                <input type="text" class="modern-input @error('telefono') is-invalid @enderror" 
                                       id="telefono" name="telefono" value="{{ old('telefono') }}" placeholder="+39 xxx xxx xxxx">
                            </div>
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-icon">
                                <i class="bi bi-envelope"></i>
                                <input type="email" class="modern-input @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" placeholder="cliente@esempio.it">
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Indirizzo -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="bi bi-geo-alt"></i> Indirizzo
                </h3>
                
                <div class="mb-3">
                    <label for="indirizzo" class="form-label">Indirizzo Completo</label>
                    <textarea class="modern-textarea @error('indirizzo') is-invalid @enderror" 
                              id="indirizzo" name="indirizzo" rows="3" placeholder="Via, numero civico, scala, interno...">{{ old('indirizzo') }}</textarea>
                    @error('indirizzo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="citta" class="form-label">Città</label>
                            <div class="input-icon">
                                <i class="bi bi-building"></i>
                                <input type="text" class="modern-input @error('citta') is-invalid @enderror" 
                                       id="citta" name="citta" value="{{ old('citta') }}" placeholder="es. Roma, Milano, Napoli">
                            </div>
                            @error('citta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="cap" class="form-label">CAP</label>
                            <div class="input-icon">
                                <i class="bi bi-mailbox"></i>
                                <input type="text" class="modern-input @error('cap') is-invalid @enderror" 
                                       id="cap" name="cap" value="{{ old('cap') }}" placeholder="es. 00100" maxlength="5">
                            </div>
                            @error('cap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pulsanti Azione -->
            <div class="d-flex gap-3 justify-content-end">
                <a href="{{ route('clienti.index') }}" class="modern-btn secondary">
                    <i class="bi bi-arrow-left"></i> Annulla
                </a>
                <button type="submit" class="modern-btn">
                    <i class="bi bi-check-circle"></i> Salva Cliente
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Gestione selezione genere
    function selectGender(value, element) {
        // Rimuovi selezione da tutti
        document.querySelectorAll('.gender-option').forEach(option => {
            option.classList.remove('selected');
        });
        
        // Aggiungi selezione al cliccato
        element.classList.add('selected');
        
        // Seleziona il radio button
        element.querySelector('input[type="radio"]').checked = true;
    }
    
    // Inizializza genere selezionato se presente
    document.addEventListener('DOMContentLoaded', function() {
        const selectedGender = document.querySelector('input[name="genere"]:checked');
        if (selectedGender) {
            selectedGender.closest('.gender-option').classList.add('selected');
        }
        
        // Formattazione CAP
        const capInput = document.getElementById('cap');
        capInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').substring(0, 5);
        });
        
        // Formattazione telefono
        const telefonoInput = document.getElementById('telefono');
        telefonoInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.length > 0) {
                if (!value.startsWith('39')) {
                    value = '39' + value;
                }
                value = '+' + value;
            }
            this.value = value;
        });
        
        // Animazioni di caricamento pagina
        const elements = document.querySelectorAll('.form-card, .page-header');
        elements.forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            element.style.transition = 'all 0.6s ease';
            
            setTimeout(() => {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, index * 200);
        });
        
        // Validazione email in tempo reale
        const emailInput = document.getElementById('email');
        emailInput.addEventListener('blur', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.classList.add('is-invalid');
                if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('invalid-feedback')) {
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = 'Inserisci un indirizzo email valido';
                    this.parentNode.insertBefore(feedback, this.nextSibling);
                }
            } else {
                this.classList.remove('is-invalid');
                const feedback = this.parentNode.querySelector('.invalid-feedback');
                if (feedback && feedback.textContent === 'Inserisci un indirizzo email valido') {
                    feedback.remove();
                }
            }
        });
    });
    
    // Animazione submit
    document.getElementById('form-cliente').addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Salvando...';
        submitBtn.disabled = true;
    });
</script>
@endsection