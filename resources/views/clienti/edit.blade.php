@extends('layouts.app')

@section('title', __('app.edit_customer') . ' - Gestionale Negozio')

@section('content')
<style>
    .edit-customer-container {
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
        position: relative;
        overflow: hidden;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(135deg, #667eea, #764ba2);
    }
    
    .page-title {
        font-size: 2.2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .customer-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.5rem;
        margin-right: 1rem;
        border: 3px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }
    
    .form-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2.5rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .form-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(135deg, #667eea, #764ba2);
    }
    
    .form-section {
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(102, 126, 234, 0.1);
    }
    
    .section-title i {
        color: #667eea;
        font-size: 1.4rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
    }
    
    .form-label i {
        color: #667eea;
        font-size: 1rem;
    }
    
    .form-label .required {
        color: #f72585;
        font-weight: 700;
    }
    
    .modern-input {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid rgba(102, 126, 234, 0.2);
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        font-weight: 500;
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
        font-weight: 500;
        resize: vertical;
        min-height: 100px;
        font-family: inherit;
    }
    
    .modern-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        background: white;
    }
    
    .modern-select {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid rgba(102, 126, 234, 0.2);
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        font-weight: 500;
        cursor: pointer;
    }
    
    .modern-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        background: white;
    }
    
    .input-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    
    .input-group-3 {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
    }
    
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 2px solid rgba(102, 126, 234, 0.1);
    }
    
    .modern-btn {
        padding: 15px 30px;
        border: none;
        border-radius: 15px;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
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
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        color: white;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    
    .btn-secondary {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        color: white;
    }
    
    .invalid-feedback {
        color: #f72585;
        font-size: 0.9rem;
        font-weight: 600;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .invalid-feedback::before {
        content: '⚠';
        font-size: 1rem;
    }
    
    .input-hint {
        font-size: 0.85rem;
        color: #718096;
        margin-top: 0.5rem;
        font-style: italic;
    }
    
    /* Dark Mode */
    [data-bs-theme="dark"] .page-header,
    [data-bs-theme="dark"] .form-card {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .page-title,
    [data-bs-theme="dark"] .section-title {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .form-label {
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
    
    [data-bs-theme="dark"] .section-title,
    [data-bs-theme="dark"] .form-actions {
        border-bottom-color: rgba(102, 126, 234, 0.2);
        border-top-color: rgba(102, 126, 234, 0.2);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .edit-customer-container {
            padding: 1rem;
        }
        
        .page-header,
        .form-card {
            padding: 1.5rem;
        }
        
        .page-title {
            font-size: 1.8rem;
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .customer-avatar {
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
        }
        
        .input-group,
        .input-group-3 {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .form-actions {
            flex-direction: column-reverse;
        }
        
        .modern-btn {
            width: 100%;
            justify-content: center;
        }
    }
    
    @media (max-width: 576px) {
        .page-title {
            font-size: 1.6rem;
        }
        
        .section-title {
            font-size: 1.1rem;
        }
        
        .modern-input,
        .modern-textarea,
        .modern-select {
            padding: 12px 16px;
        }
    }
</style>

<div class="edit-customer-container">
    <!-- Header -->
    <div class="page-header">
        <div class="d-flex align-items-center">
            <div class="customer-avatar">
                {{ strtoupper(substr($cliente->nome, 0, 1)) }}{{ strtoupper(substr($cliente->cognome, 0, 1)) }}
            </div>
            <h1 class="page-title">
                <i class="bi bi-person-gear"></i>
                {{ __('app.edit_customer') }}: {{ $cliente->nome_completo }}
            </h1>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form action="{{ route('clienti.update', $cliente) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Sezione Dati Anagrafici -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="bi bi-person-vcard"></i>
                    {{ __('app.personal_data') }}
                </h3>
                
                <div class="input-group">
                    <div class="form-group">
                        <label for="nome" class="form-label">
                            <i class="bi bi-person"></i>
                            {{ __('app.name') }} <span class="required">*</span>
                        </label>
                        <input type="text" 
                               class="modern-input @error('nome') is-invalid @enderror"
                               id="nome" 
                               name="nome" 
                               value="{{ old('nome', $cliente->nome) }}" 
                               required
                               placeholder="{{ __('app.customer_name') }}">
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="cognome" class="form-label">
                            <i class="bi bi-person-fill"></i>
                            {{ __('app.surname') }} <span class="required">*</span>
                        </label>
                        <input type="text" 
                               class="modern-input @error('cognome') is-invalid @enderror"
                               id="cognome" 
                               name="cognome" 
                               value="{{ old('cognome', $cliente->cognome) }}" 
                               required
                               placeholder="{{ __('app.customer_surname') }}">
                        @error('cognome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="input-group">
                    <div class="form-group">
                        <label for="data_nascita" class="form-label">
                            <i class="bi bi-calendar-event"></i>
                            {{ __('app.date_of_birth') }}
                        </label>
                        <input type="date" 
                               class="modern-input @error('data_nascita') is-invalid @enderror"
                               id="data_nascita" 
                               name="data_nascita" 
                               value="{{ old('data_nascita', $cliente->data_nascita ? $cliente->data_nascita->format('Y-m-d') : '') }}">
                        @error('data_nascita')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="input-hint">{{ __('app.used_to_calculate_customer_age') }}</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="genere" class="form-label">
                            <i class="bi bi-gender-ambiguous"></i>
                            {{ __('app.gender') }}
                        </label>
                        <select class="modern-select @error('genere') is-invalid @enderror"
                                id="genere" 
                                name="genere">
                            <option value="">{{ __('app.select') }}...</option>
                            <option value="M" {{ old('genere', $cliente->genere) == 'M' ? 'selected' : '' }}>{{ __('app.male') }}</option>
                            <option value="F" {{ old('genere', $cliente->genere) == 'F' ? 'selected' : '' }}>{{ __('app.female') }}</option>
                            <option value="Altro" {{ old('genere', $cliente->genere) == 'Altro' ? 'selected' : '' }}>{{ __('app.other') }}</option>
                        </select>
                        @error('genere')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Sezione Contatti -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="bi bi-telephone"></i>
                    {{ __('app.contact_information') }}
                </h3>
                
                <div class="input-group">
                    <div class="form-group">
                        <label for="telefono" class="form-label">
                            <i class="bi bi-telephone"></i>
                            {{ __('app.phone') }}
                        </label>
                        <input type="text" 
                               class="modern-input @error('telefono') is-invalid @enderror"
                               id="telefono" 
                               name="telefono" 
                               value="{{ old('telefono', $cliente->telefono) }}"
                               placeholder="{{ __('app.phone_example') }}">
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i>
                            {{ __('app.email') }}
                        </label>
                        <input type="email" 
                               class="modern-input @error('email') is-invalid @enderror"
                               id="email" 
                               name="email" 
                               value="{{ old('email', $cliente->email) }}"
                               placeholder="cliente@example.com">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="input-hint">{{ __('app.email_for_communications_billing') }}</div>
                    </div>
                </div>
            </div>

            <!-- Sezione Indirizzo -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="bi bi-geo-alt"></i>
                    {{ __('app.address_and_residence') }}
                </h3>
                
                <div class="form-group">
                    <label for="indirizzo" class="form-label">
                        <i class="bi bi-house"></i>
                        {{ __('app.address') }}
                    </label>
                    <textarea class="modern-textarea @error('indirizzo') is-invalid @enderror"
                              id="indirizzo" 
                              name="indirizzo" 
                              placeholder="{{ __('app.address_placeholder') }}">{{ old('indirizzo', $cliente->indirizzo) }}</textarea>
                    @error('indirizzo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="input-hint">{{ __('app.complete_address_shipping_billing') }}</div>
                </div>
                
                <div class="input-group-3">
                    <div class="form-group">
                        <label for="citta" class="form-label">
                            <i class="bi bi-building"></i>
                            {{ __('app.city') }}
                        </label>
                        <input type="text" 
                               class="modern-input @error('citta') is-invalid @enderror"
                               id="citta" 
                               name="citta" 
                               value="{{ old('citta', $cliente->citta) }}"
                               placeholder="{{ __('app.city_name') }}">
                        @error('citta')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="cap" class="form-label">
                            <i class="bi bi-mailbox"></i>
                            {{ __('app.postal_code') }}
                        </label>
                        <input type="text" 
                               class="modern-input @error('cap') is-invalid @enderror"
                               id="cap" 
                               name="cap" 
                               value="{{ old('cap', $cliente->cap) }}"
                               placeholder="70024"
                               maxlength="5">
                        @error('cap')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Azioni Form -->
            <div class="form-actions">
                <a href="{{ route('clienti.show', $cliente) }}" class="modern-btn btn-secondary">
                    <i class="bi bi-x-circle"></i>
                    {{ __('app.cancel') }}
                </a>
                <button type="submit" class="modern-btn btn-primary">
                    <i class="bi bi-check-circle"></i>
                    {{ __('app.update_customer') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection