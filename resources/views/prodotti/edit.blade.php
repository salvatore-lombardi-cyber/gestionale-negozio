@extends('layouts.app')

@section('title', __('app.edit_product') . ' - Gestionale Negozio')

@section('content')
<style>
    .edit-product-container {
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
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
    }
    
    .page-title {
        font-size: 2.2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
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
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
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
        border-bottom: 2px solid rgba(2, 157, 126, 0.1);
    }
    
    .section-title i {
        color: #029D7E;
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
        color: #029D7E;
        font-size: 1rem;
    }
    
    .form-label .required {
        color: #f72585;
        font-weight: 700;
    }
    
    .modern-input {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid rgba(2, 157, 126, 0.2);
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        font-weight: 500;
    }
    
    .modern-input:focus {
        outline: none;
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
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
        border: 2px solid rgba(2, 157, 126, 0.2);
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        font-weight: 500;
        resize: vertical;
        min-height: 120px;
        font-family: inherit;
    }
    
    .modern-textarea:focus {
        outline: none;
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
        background: white;
    }
    
    .modern-textarea.is-invalid {
        border-color: #f72585;
        box-shadow: 0 0 0 0.2rem rgba(247, 37, 133, 0.25);
    }
    
    .input-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    
    .checkbox-container {
        background: rgba(2, 157, 126, 0.05);
        border: 2px solid rgba(2, 157, 126, 0.1);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }
    
    .checkbox-container:hover {
        background: rgba(2, 157, 126, 0.08);
        border-color: rgba(2, 157, 126, 0.2);
    }
    
    .modern-checkbox {
        display: flex;
        align-items: center;
        gap: 1rem;
        cursor: pointer;
    }
    
    .checkbox-input {
        width: 20px;
        height: 20px;
        border: 2px solid #029D7E;
        border-radius: 6px;
        position: relative;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .checkbox-input:checked {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        border-color: #029D7E;
    }
    
    .checkbox-input:checked::after {
        content: '✓';
        color: white;
        font-weight: bold;
        font-size: 14px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    .checkbox-label {
        font-weight: 600;
        color: #2d3748;
        font-size: 1.1rem;
        cursor: pointer;
        user-select: none;
    }
    
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2.5rem;
        padding-top: 2rem;
        border-top: 2px solid rgba(2, 157, 126, 0.1);
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
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
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
    
    [data-bs-theme="dark"] .form-label,
    [data-bs-theme="dark"] .checkbox-label {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .modern-input,
    [data-bs-theme="dark"] .modern-textarea {
        background: rgba(45, 55, 72, 0.8);
        border-color: rgba(2, 157, 126, 0.3);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .modern-input:focus,
    [data-bs-theme="dark"] .modern-textarea:focus {
        background: rgba(45, 55, 72, 0.9);
    }
    
    [data-bs-theme="dark"] .checkbox-container {
        background: rgba(2, 157, 126, 0.1);
        border-color: rgba(2, 157, 126, 0.2);
    }
    
    [data-bs-theme="dark"] .section-title,
    [data-bs-theme="dark"] .form-actions {
        border-bottom-color: rgba(2, 157, 126, 0.2);
        border-top-color: rgba(2, 157, 126, 0.2);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .edit-product-container {
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
        
        .input-group {
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
        .modern-textarea {
            padding: 12px 16px;
        }
    }
</style>

<div class="edit-product-container">
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-pencil-square"></i>
            {{ __('app.edit_product') }}: {{ $prodotto->nome }}
        </h1>
    </div>
    
    <!-- Form Card -->
    <div class="form-card">
        <form action="{{ route('prodotti.update', $prodotto) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Sezione Informazioni Base -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="bi bi-info-circle"></i>
                    {{ __('app.basic_information') }}
                </h3>
                
                <div class="form-group">
                    <label for="nome" class="form-label">
                        <i class="bi bi-tag"></i>
                        {{ __('app.product_name') }} <span class="required">*</span>
                    </label>
                    <input type="text" 
                    class="modern-input @error('nome') is-invalid @enderror"
                    id="nome" 
                    name="nome" 
                    value="{{ old('nome', $prodotto->nome) }}" 
                    required
                    placeholder="{{ __('app.enter_product_name') }}">
                    @error('nome')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="descrizione" class="form-label">
                        <i class="bi bi-text-paragraph"></i>
                        {{ __('app.description') }}
                    </label>
                    <textarea class="modern-textarea @error('descrizione') is-invalid @enderror"
                    id="descrizione" 
                    name="descrizione" 
                    placeholder="{{ __('app.detailed_product_description_optional') }}">{{ old('descrizione', $prodotto->descrizione) }}</textarea>
                    @error('descrizione')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="input-hint">{{ __('app.provide_detailed_description_help_customers') }}</div>
                </div>
            </div>
            
            <!-- Sezione Prezzi e Categoria -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="bi bi-tags"></i>
                    {{ __('app.prices_and_categorization') }}
                </h3>
                
                <div class="input-group">
                    <div class="form-group">
                        <label for="prezzo" class="form-label">
                            <i class="bi bi-currency-euro"></i>
                            {{ __('app.price') }} <span class="required">*</span>
                        </label>
                        <input type="number" 
                        class="modern-input @error('prezzo') is-invalid @enderror"
                        id="prezzo" 
                        name="prezzo" 
                        step="0.01" 
                        value="{{ old('prezzo', $prodotto->prezzo) }}" 
                        required
                        placeholder="0.00">
                        @error('prezzo')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="categoria" class="form-label">
                            <i class="bi bi-grid-3x3-gap"></i>
                            {{ __('app.category') }} <span class="required">*</span>
                        </label>
                        <input type="text" 
                        class="modern-input @error('categoria') is-invalid @enderror"
                        id="categoria" 
                        name="categoria" 
                        value="{{ old('categoria', $prodotto->categoria) }}" 
                        required
                        placeholder="{{ __('app.category_examples') }}">
                        @error('categoria')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Sezione Dettagli Prodotto -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="bi bi-box-seam"></i>
                    {{ __('app.product_details') }}
                </h3>
                
                <div class="input-group">
                    <div class="form-group">
                        <label for="brand" class="form-label">
                            <i class="bi bi-award"></i>
                            {{ __('app.brand') }}
                        </label>
                        <input type="text" 
                        class="modern-input @error('brand') is-invalid @enderror"
                        id="brand" 
                        name="brand" 
                        value="{{ old('brand', $prodotto->brand) }}"
                        placeholder="{{ __('app.brand_or_manufacturer_optional') }}">
                        @error('brand')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="codice_prodotto" class="form-label">
                            <i class="bi bi-upc-scan"></i>
                            {{ __('app.product_code') }} <span class="required">*</span>
                        </label>
                        <input type="text" 
                        class="modern-input @error('codice_prodotto') is-invalid @enderror"
                        id="codice_prodotto" 
                        name="codice_prodotto" 
                        value="{{ old('codice_prodotto', $prodotto->codice_prodotto) }}" 
                        required
                        placeholder="{{ __('app.unique_product_code') }}">
                        @error('codice_prodotto')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="input-hint">{{ __('app.code_must_be_unique') }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Sezione Stato -->
            <div class="checkbox-container">
                <div class="modern-checkbox">
                    <input class="checkbox-input" 
                    type="checkbox" 
                    id="attivo" 
                    name="attivo" 
                    value="1"
                    {{ old('attivo', $prodotto->attivo) ? 'checked' : '' }}>
                    <label class="checkbox-label" for="attivo">
                        <i class="bi bi-toggle-on"></i>
                        {{ __('app.active_product') }}
                    </label>
                </div>
                <div class="input-hint" style="margin-top: 0.5rem; margin-left: 3rem;">
                    {{ __('app.active_products_visible_purchasable') }}
                </div>
            </div>
            
            <!-- Azioni Form -->
            <div class="form-actions">
                <a href="{{ route('prodotti.show', $prodotto) }}" class="modern-btn btn-secondary">
                    <i class="bi bi-x-circle"></i>
                    {{ __('app.cancel') }}
                </a>
                <button type="submit" class="modern-btn btn-primary">
                    <i class="bi bi-check-circle"></i>
                    {{ __('app.update_product') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection