@extends('layouts.app')

@section('title', __('app.new') . ' ' . __('app.product') . ' - Gestionale Negozio')

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
    
    .modern-btn.success {
        background: linear-gradient(135deg, #28a745, #20c997);
    }
    
    .modern-btn.success:hover {
        box-shadow: 0 15px 35px rgba(40, 167, 69, 0.4);
    }
    
    .modern-btn.danger {
        background: linear-gradient(135deg, #f72585, #c5025a);
    }
    
    .modern-btn.danger:hover {
        box-shadow: 0 15px 35px rgba(247, 37, 133, 0.4);
    }
    
    .modern-checkbox {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(102, 126, 234, 0.2);
        border-radius: 8px;
        padding: 15px 20px;
        transition: all 0.3s ease;
    }
    
    .modern-checkbox:hover {
        background: white;
        border-color: #667eea;
        transform: translateY(-2px);
    }
    
    .modern-checkbox input[type="checkbox"] {
        width: 20px;
        height: 20px;
        margin-right: 10px;
        accent-color: #667eea;
    }
    
    .variants-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .variant-item {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(102, 126, 234, 0.1);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    
    .variant-item:hover {
        border-color: rgba(102, 126, 234, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .quick-select-card {
        background: rgba(102, 126, 234, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1rem;
        border: 1px solid rgba(102, 126, 234, 0.2);
        margin-bottom: 1rem;
    }
    
    .quick-btn {
        background: rgba(102, 126, 234, 0.1);
        border: 1px solid rgba(102, 126, 234, 0.3);
        border-radius: 10px;
        padding: 8px 16px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #667eea;
        margin: 0 5px 5px 0;
        transition: all 0.3s ease;
    }
    
    .quick-btn:hover {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-color: transparent;
        transform: translateY(-2px);
    }
    
    .info-alert {
        background: rgba(72, 202, 228, 0.1);
        border: 1px solid rgba(72, 202, 228, 0.3);
        border-radius: 15px;
        padding: 1.5rem;
        color: #0077b6;
    }
    
    .invalid-feedback {
        color: #f72585;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        font-weight: 500;
    }
    
    /* Dark Mode */
    [data-bs-theme="dark"] .page-header,
    [data-bs-theme="dark"] .form-card,
    [data-bs-theme="dark"] .variants-container {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .modern-input,
    [data-bs-theme="dark"] .modern-textarea {
        background: rgba(45, 55, 72, 0.8);
        border-color: rgba(102, 126, 234, 0.3);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .modern-input:focus,
    [data-bs-theme="dark"] .modern-textarea:focus {
        background: rgba(45, 55, 72, 0.9);
    }
    
    [data-bs-theme="dark"] .modern-checkbox {
        background: rgba(45, 55, 72, 0.8);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .variant-item {
        background: rgba(45, 55, 72, 0.8);
        color: #e2e8f0;
        border-color: rgba(102, 126, 234, 0.2);
    }
    
    [data-bs-theme="dark"] .quick-select-card {
        background: rgba(102, 126, 234, 0.2);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .info-alert {
        background: rgba(72, 202, 228, 0.2);
        color: #48cae4;
    }
    
    [data-bs-theme="dark"] .form-label {
        color: #e2e8f0;
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
        
        .variant-item {
            padding: 1rem;
        }
    }
</style>

<div class="create-container">
    <!-- Header della Pagina -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">
                <i class="bi bi-plus-circle"></i> {{ __('app.new') }} {{ __('app.product') }}
            </h1>
            <a href="{{ route('prodotti.index') }}" class="modern-btn secondary">
                <i class="bi bi-arrow-left"></i> {{ __('app.back') }}
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <form action="{{ route('prodotti.store') }}" method="POST" id="form-prodotto">
            @csrf
            
            <!-- Informazioni Base -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="bi bi-info-circle"></i> {{ __('app.basic_info') }}
                </h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nome" class="form-label">{{ __('app.product_name') }} <span class="required">*</span></label>
                            <input type="text" class="modern-input @error('nome') is-invalid @enderror" 
                            id="nome" name="nome" value="{{ old('nome') }}" required>
                            @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="codice_prodotto" class="form-label">{{ __('app.product_code') }} <span class="required">*</span></label>
                            <input type="text" class="modern-input @error('codice_prodotto') is-invalid @enderror" 
                            id="codice_prodotto" name="codice_prodotto" value="{{ old('codice_prodotto') }}" required>
                            @error('codice_prodotto')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="descrizione" class="form-label">{{ __('app.description') }}</label>
                    <textarea class="modern-textarea @error('descrizione') is-invalid @enderror" 
                    id="descrizione" name="descrizione" rows="3" placeholder="Inserisci una descrizione dettagliata del prodotto...">{{ old('descrizione') }}</textarea>
                    @error('descrizione')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="prezzo" class="form-label">{{ __('app.price') }} <span class="required">*</span></label>
                            <input type="number" class="modern-input @error('prezzo') is-invalid @enderror" 
                            id="prezzo" name="prezzo" step="0.01" value="{{ old('prezzo') }}" required placeholder="0.00">
                            @error('prezzo')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="categoria" class="form-label">{{ __('app.category') }} <span class="required">*</span></label>
                            <input type="text" class="modern-input @error('categoria') is-invalid @enderror" 
                            id="categoria" name="categoria" value="{{ old('categoria') }}" required placeholder="es. Abbigliamento, Elettronica">
                            @error('categoria')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="brand" class="form-label">{{ __('app.brand') }}</label>
                            <input type="text" class="modern-input @error('brand') is-invalid @enderror" 
                            id="brand" name="brand" value="{{ old('brand') }}" placeholder="es. Nike, Apple, Samsung">
                            @error('brand')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="modern-checkbox">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="attivo" name="attivo" value="1" checked>
                        <label class="form-check-label" for="attivo">
                            <strong>{{ __('app.active_product') }}</strong>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Sezione Magazzino -->
            <div class="form-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="section-title">
                        <i class="bi bi-boxes"></i> {{ __('app.warehouse_variants') }}
                    </h3>
                    <div class="d-flex align-items-center gap-3">
                        <button type="button" class="modern-btn success" onclick="aggiungiVariante()">
                            <i class="bi bi-plus"></i> {{ __('app.add_variant') }}
                        </button>
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" class="modern-input" id="scorta_minima_globale" value="5" min="0" style="width: 80px;">
                            <label class="form-label mb-0">{{ __('app.min_stock') }}</label>
                        </div>
                    </div>
                </div>
                
                <div class="variants-container">
                    <div id="varianti-container">
                        <div class="info-alert">
                            <i class="bi bi-info-circle"></i> 
                            Aggiungi le varianti (taglia/colore/quantità) per questo prodotto. Clicca "{{ __('app.add_variant') }}" per iniziare.
                        </div>
                    </div>
                    
                    <!-- Selezione rapida taglie -->
                    <div class="quick-select-card" id="selezione-rapida" style="display: none;">
                        <h6><i class="bi bi-lightning"></i> Selezione Rapida Taglie:</h6>
                        <button type="button" class="quick-btn" onclick="aggiungiTaglieRapide(['XS','S','M','L','XL'])">
                            XS → XL
                        </button>
                        <button type="button" class="quick-btn" onclick="aggiungiTaglieRapide(['S','M','L','XL','XXL'])">
                            S → XXL
                        </button>
                        <button type="button" class="quick-btn" onclick="aggiungiTaglieRapide(['42','44','46','48','50'])">
                            42 → 50
                        </button>
                        <button type="button" class="quick-btn" onclick="aggiungiTaglieRapide(['38','40','42','44','46','48'])">
                            38 → 48
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Pulsanti Azione -->
            <div class="d-flex gap-3 justify-content-end">
                <a href="{{ route('prodotti.index') }}" class="modern-btn secondary">
                    <i class="bi bi-arrow-left"></i> {{ __('app.cancel') }}
                </a>
                <button type="submit" class="modern-btn">
                    <i class="bi bi-check-circle"></i> {{ __('app.save_product_warehouse') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let varianteIndex = 0;
    
    function aggiungiVariante(taglia = '', colore = '', quantita = 0) {
        const container = document.getElementById('varianti-container');
        
        // Nascondi il messaggio info se è la prima variante
        if (varianteIndex === 0) {
            container.innerHTML = '';
            document.getElementById('selezione-rapida').style.display = 'block';
        }
        
        const varianteDivId = 'variante-' + varianteIndex;
        
        const varianteHtml = `
        <div class="variant-item" id="${varianteDivId}">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">{{ __('app.size') }} <span class="required">*</span></label>
                    <input type="text" class="modern-input" name="varianti[${varianteIndex}][taglia]" value="${taglia}" required placeholder="es. L, 42, XL">
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('app.color') }} <span class="required">*</span></label>
                    <input type="text" class="modern-input" name="varianti[${varianteIndex}][colore]" value="${colore}" required placeholder="es. Nero, Blu Navy, RAL123">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('app.quantity') }} <span class="required">*</span></label>
                    <input type="number" class="modern-input" name="varianti[${varianteIndex}][quantita]" value="${quantita}" min="0" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="modern-btn danger w-100" onclick="rimuoviVariante('${varianteDivId}')">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
        
        container.insertAdjacentHTML('beforeend', varianteHtml);
        varianteIndex++;
        
        // Animazione di entrata
        setTimeout(() => {
            const newElement = document.getElementById(varianteDivId);
            newElement.style.opacity = '0';
            newElement.style.transform = 'translateY(20px)';
            newElement.style.transition = 'all 0.5s ease';
            
            setTimeout(() => {
                newElement.style.opacity = '1';
                newElement.style.transform = 'translateY(0)';
            }, 100);
        }, 10);
    }
    
    function rimuoviVariante(varianteDivId) {
        const element = document.getElementById(varianteDivId);
        
        // Animazione di uscita
        element.style.opacity = '0';
        element.style.transform = 'translateY(-20px)';
        
        setTimeout(() => {
            element.remove();
            
            // Se non ci sono più varianti, mostra di nuovo il messaggio
            const container = document.getElementById('varianti-container');
            if (container.children.length === 0) {
                container.innerHTML = `
                <div class="info-alert">
                    <i class="bi bi-info-circle"></i> 
                    Aggiungi le varianti (taglia/colore/quantità) per questo prodotto. Clicca "{{ __('app.add_variant') }}" per iniziare.
                </div>
            `;
                document.getElementById('selezione-rapida').style.display = 'none';
            }
        }, 300);
    }
    
    function aggiungiTaglieRapide(taglie) {
        taglie.forEach((taglia, index) => {
            setTimeout(() => {
                aggiungiVariante(taglia, '', 0);
            }, index * 200); // Aggiunge con delay per effetto cascata
        });
    }
    
    // Validazione form
    document.getElementById('form-prodotto').addEventListener('submit', function(e) {
        const varianti = document.querySelectorAll('[name^="varianti"]');
        if (varianti.length === 0) {
            e.preventDefault();
            
            // Mostra un alert moderno
            const alertHtml = `
                <div class="alert alert-warning alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; background: linear-gradient(135deg, #ffd60a, #ff8500); color: white; border: none; border-radius: 15px;">
                    <strong><i class="bi bi-exclamation-triangle"></i> Attenzione!</strong><br>
                    Aggiungi almeno una variante per il magazzino!
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', alertHtml);
            
            return false;
        }
    });
    
    // Animazioni di caricamento pagina
    document.addEventListener('DOMContentLoaded', function() {
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
    });
</script>
@endsection