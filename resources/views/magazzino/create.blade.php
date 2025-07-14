@extends('layouts.app')

@section('title', 'Nuova Scorta - Gestionale Negozio')

@section('content')
<style>
    .sales-container {
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
    
    .form-help {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 0.25rem;
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
        display: inline-block;
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
    
    .info-alert {
        background: rgba(72, 202, 228, 0.1);
        border: 2px solid rgba(72, 202, 228, 0.2);
        border-radius: 15px;
        padding: 1.5rem;
        color: #0077b6;
        margin: 2rem 0;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .info-alert i {
        font-size: 1.3rem;
        margin-top: 0.2rem;
    }
    
    .sidebar-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        height: fit-content;
        position: sticky;
        top: 2rem;
    }
    
    .sidebar-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #667eea;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .suggestion-item {
        display: flex;
        align-items: flex-start;
        gap: 0.8rem;
        margin-bottom: 1rem;
        padding: 0.8rem;
        background: rgba(40, 167, 69, 0.05);
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .suggestion-item:hover {
        background: rgba(40, 167, 69, 0.1);
        transform: translateX(5px);
    }
    
    .suggestion-item i {
        color: #28a745;
        font-size: 1.1rem;
        margin-top: 0.2rem;
    }
    
    .suggestion-text {
        color: #495057;
        font-size: 0.9rem;
        line-height: 1.4;
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
    
    .input-icon .modern-input,
    .input-icon .modern-select {
        padding-left: 50px;
    }
    
    /* Dark Mode */
    [data-bs-theme="dark"] .page-header,
    [data-bs-theme="dark"] .form-card,
    [data-bs-theme="dark"] .sidebar-card {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .modern-input,
    [data-bs-theme="dark"] .modern-select {
        background: rgba(45, 55, 72, 0.8);
        border-color: rgba(102, 126, 234, 0.3);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .modern-input:focus,
    [data-bs-theme="dark"] .modern-select:focus {
        background: rgba(45, 55, 72, 0.9);
    }
    
    [data-bs-theme="dark"] .form-label {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .form-help {
        color: #a0aec0;
    }
    
    [data-bs-theme="dark"] .suggestion-item {
        background: rgba(40, 167, 69, 0.1);
    }
    
    [data-bs-theme="dark"] .suggestion-item:hover {
        background: rgba(40, 167, 69, 0.2);
    }
    
    [data-bs-theme="dark"] .suggestion-text {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .info-alert {
        background: rgba(72, 202, 228, 0.2);
        color: #48cae4;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .sales-container {
            padding: 1rem;
        }
        
        .page-header, .form-card, .sidebar-card {
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
        
        .input-icon i {
            display: none;
        }
        
        .input-icon .modern-input,
        .input-icon .modern-select {
            padding-left: 20px;
        }
        
        .sidebar-card {
            position: static;
        }
    }
    
    @media (max-width: 576px) {
        .page-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
    }
</style>

<div class="sales-container">
    <!-- Header della Pagina -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">
                <i class="bi bi-plus-circle"></i> Nuova Scorta
            </h1>
            <a href="{{ route('magazzino.index') }}" class="modern-btn secondary">
                <i class="bi bi-arrow-left"></i> Torna al Magazzino
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Form Principale -->
        <div class="col-lg-8">
            <div class="form-card">
                <form action="{{ route('magazzino.store') }}" method="POST" id="stock-form">
                    @csrf
                    
                    <!-- Selezione Prodotto -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="bi bi-box-seam"></i> Selezione Prodotto
                        </h3>
                        
                        <div class="mb-3">
                            <label for="prodotto_id" class="form-label">Prodotto <span class="required">*</span></label>
                            <div class="input-icon">
                                <i class="bi bi-search"></i>
                                <select class="modern-select @error('prodotto_id') is-invalid @enderror" 
                                        id="prodotto_id" name="prodotto_id" required>
                                    <option value="">Seleziona prodotto...</option>
                                    @foreach($prodotti as $prodotto)
                                        <option value="{{ $prodotto->id }}" {{ old('prodotto_id') == $prodotto->id ? 'selected' : '' }}>
                                            {{ $prodotto->nome }} ({{ $prodotto->codice_prodotto }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('prodotto_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Varianti -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="bi bi-palette"></i> Varianti Prodotto
                        </h3>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="taglia" class="form-label">Taglia <span class="required">*</span></label>
                                    <div class="input-icon">
                                        <i class="bi bi-rulers"></i>
                                        <select class="modern-select @error('taglia') is-invalid @enderror" 
                                                id="taglia" name="taglia" required>
                                            <option value="">Seleziona taglia...</option>
                                            <optgroup label="Taglie Lettere">
                                                <option value="XXS" {{ old('taglia') == 'XXS' ? 'selected' : '' }}>XXS</option>
                                                <option value="XS" {{ old('taglia') == 'XS' ? 'selected' : '' }}>XS</option>
                                                <option value="S" {{ old('taglia') == 'S' ? 'selected' : '' }}>S</option>
                                                <option value="M" {{ old('taglia') == 'M' ? 'selected' : '' }}>M</option>
                                                <option value="L" {{ old('taglia') == 'L' ? 'selected' : '' }}>L</option>
                                                <option value="XL" {{ old('taglia') == 'XL' ? 'selected' : '' }}>XL</option>
                                                <option value="XXL" {{ old('taglia') == 'XXL' ? 'selected' : '' }}>XXL</option>
                                                <option value="XXXL" {{ old('taglia') == 'XXXL' ? 'selected' : '' }}>XXXL</option>
                                                <option value="4XL" {{ old('taglia') == '4XL' ? 'selected' : '' }}>4XL</option>
                                                <option value="5XL" {{ old('taglia') == '5XL' ? 'selected' : '' }}>5XL</option>
                                            </optgroup>
                                            <optgroup label="Taglie Numeriche">
                                                <option value="38" {{ old('taglia') == '38' ? 'selected' : '' }}>38</option>
                                                <option value="40" {{ old('taglia') == '40' ? 'selected' : '' }}>40</option>
                                                <option value="42" {{ old('taglia') == '42' ? 'selected' : '' }}>42</option>
                                                <option value="44" {{ old('taglia') == '44' ? 'selected' : '' }}>44</option>
                                                <option value="46" {{ old('taglia') == '46' ? 'selected' : '' }}>46</option>
                                                <option value="48" {{ old('taglia') == '48' ? 'selected' : '' }}>48</option>
                                                <option value="50" {{ old('taglia') == '50' ? 'selected' : '' }}>50</option>
                                                <option value="52" {{ old('taglia') == '52' ? 'selected' : '' }}>52</option>
                                                <option value="54" {{ old('taglia') == '54' ? 'selected' : '' }}>54</option>
                                                <option value="56" {{ old('taglia') == '56' ? 'selected' : '' }}>56</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    @error('taglia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="colore" class="form-label">Colore <span class="required">*</span></label>
                                    <div class="input-icon">
                                        <i class="bi bi-palette"></i>
                                        <select class="modern-select @error('colore') is-invalid @enderror" 
                                                id="colore" name="colore" required>
                                            <option value="">Seleziona colore...</option>
                                            <optgroup label="Colori Base">
                                                <option value="Nero" {{ old('colore') == 'Nero' ? 'selected' : '' }}>Nero</option>
                                                <option value="Bianco" {{ old('colore') == 'Bianco' ? 'selected' : '' }}>Bianco</option>
                                                <option value="Grigio" {{ old('colore') == 'Grigio' ? 'selected' : '' }}>Grigio</option>
                                                <option value="Rosso" {{ old('colore') == 'Rosso' ? 'selected' : '' }}>Rosso</option>
                                                <option value="Blu" {{ old('colore') == 'Blu' ? 'selected' : '' }}>Blu</option>
                                                <option value="Verde" {{ old('colore') == 'Verde' ? 'selected' : '' }}>Verde</option>
                                            </optgroup>
                                            <optgroup label="Colori Vivaci">
                                                <option value="Giallo" {{ old('colore') == 'Giallo' ? 'selected' : '' }}>Giallo</option>
                                                <option value="Arancione" {{ old('colore') == 'Arancione' ? 'selected' : '' }}>Arancione</option>
                                                <option value="Rosa" {{ old('colore') == 'Rosa' ? 'selected' : '' }}>Rosa</option>
                                                <option value="Viola" {{ old('colore') == 'Viola' ? 'selected' : '' }}>Viola</option>
                                                <option value="Fucsia" {{ old('colore') == 'Fucsia' ? 'selected' : '' }}>Fucsia</option>
                                            </optgroup>
                                            <optgroup label="Colori Naturali">
                                                <option value="Marrone" {{ old('colore') == 'Marrone' ? 'selected' : '' }}>Marrone</option>
                                                <option value="Beige" {{ old('colore') == 'Beige' ? 'selected' : '' }}>Beige</option>
                                                <option value="Bordeaux" {{ old('colore') == 'Bordeaux' ? 'selected' : '' }}>Bordeaux</option>
                                                <option value="Navy" {{ old('colore') == 'Navy' ? 'selected' : '' }}>Navy</option>
                                                <option value="Azzurro" {{ old('colore') == 'Azzurro' ? 'selected' : '' }}>Azzurro</option>
                                            </optgroup>
                                            <optgroup label="Colori Speciali">
                                                <option value="Oro" {{ old('colore') == 'Oro' ? 'selected' : '' }}>Oro</option>
                                                <option value="Argento" {{ old('colore') == 'Argento' ? 'selected' : '' }}>Argento</option>
                                                <option value="Multicolore" {{ old('colore') == 'Multicolore' ? 'selected' : '' }}>Multicolore</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    @error('colore')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quantità e Scorte -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="bi bi-123"></i> Quantità e Scorte
                        </h3>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quantita" class="form-label">Quantità <span class="required">*</span></label>
                                    <div class="input-icon">
                                        <i class="bi bi-box"></i>
                                        <input type="number" class="modern-input @error('quantita') is-invalid @enderror" 
                                               id="quantita" name="quantita" min="0" value="{{ old('quantita', 0) }}" required placeholder="Inserisci quantità">
                                    </div>
                                    @error('quantita')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="scorta_minima" class="form-label">Scorta Minima <span class="required">*</span></label>
                                    <div class="input-icon">
                                        <i class="bi bi-shield-check"></i>
                                        <input type="number" class="modern-input @error('scorta_minima') is-invalid @enderror" 
                                               id="scorta_minima" name="scorta_minima" min="0" value="{{ old('scorta_minima', 5) }}" required placeholder="Soglia minima">
                                    </div>
                                    @error('scorta_minima')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-help">Quando la quantità scende sotto questo valore, riceverai un avviso.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alert Info -->
                    <div class="info-alert">
                        <i class="bi bi-info-circle"></i> 
                        <div>
                            <strong>Nota importante:</strong> Assicurati che la combinazione prodotto + taglia + colore non esista già nel magazzino. Ogni variante deve essere unica.
                        </div>
                    </div>

                    <!-- Pulsanti Azione -->
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('magazzino.index') }}" class="modern-btn secondary">
                            <i class="bi bi-arrow-left"></i> Annulla
                        </a>
                        <button type="submit" class="modern-btn success">
                            <i class="bi bi-check-circle"></i> Salva Scorta
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Suggerimenti -->
        <div class="col-lg-4">
            <div class="sidebar-card">
                <h5 class="sidebar-title">
                    <i class="bi bi-lightbulb"></i> Suggerimenti
                </h5>
                
                <div class="suggestion-item">
                    <i class="bi bi-check-circle"></i>
                    <div class="suggestion-text">
                        <strong>Imposta una scorta minima</strong><br>
                        Riceverai automaticamente avvisi quando le scorte si stanno esaurendo
                    </div>
                </div>
                
                <div class="suggestion-item">
                    <i class="bi bi-check-circle"></i>
                    <div class="suggestion-text">
                        <strong>Ogni combinazione è unica</strong><br>
                        Prodotto + taglia + colore deve essere una combinazione non esistente
                    </div>
                </div>
                
                <div class="suggestion-item">
                    <i class="bi bi-check-circle"></i>
                    <div class="suggestion-text">
                        <strong>Aggiornamenti futuri</strong><br>
                        Potrai sempre modificare le quantità in seguito dal magazzino
                    </div>
                </div>
                
                <div class="suggestion-item">
                    <i class="bi bi-check-circle"></i>
                    <div class="suggestion-text">
                        <strong>Aggiornamento automatico</strong><br>
                        Le vendite aggiorneranno automaticamente le scorte disponibili
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animazioni di caricamento
    const elements = document.querySelectorAll('.page-header, .form-card, .sidebar-card');
    elements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'all 0.6s ease';
        
        setTimeout(() => {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 200);
    });
    
    // Animazione suggerimenti
    const suggestions = document.querySelectorAll('.suggestion-item');
    suggestions.forEach((item, index) => {
        setTimeout(() => {
            item.style.opacity = '0';
            item.style.transform = 'translateX(20px)';
            item.style.transition = 'all 0.5s ease';
            
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            }, 100);
        }, 1000 + (index * 200));
    });
    
    // Validazione in tempo reale
    const quantitaInput = document.getElementById('quantita');
    const scortaMinimaInput = document.getElementById('scorta_minima');
    
    quantitaInput.addEventListener('input', function() {
        if (this.value < 0) this.value = 0;
    });
    
    scortaMinimaInput.addEventListener('input', function() {
        if (this.value < 0) this.value = 0;
    });
    
    // Animazione submit
    document.getElementById('stock-form').addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Salvando Scorta...';
        submitBtn.disabled = true;
    });
});
</script>
@endsection