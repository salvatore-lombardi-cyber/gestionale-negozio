@extends('layouts.app')

@section('title', __('app.edit_stock') . ' - Gestionale Negozio')

@section('content')
<style>
    .edit-stock-container {
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
    
    .page-subtitle {
        margin: 0.5rem 0 0 0;
        opacity: 0.8;
        font-size: 1.1rem;
        font-weight: 500;
        color: #6c757d;
    }
    
    .main-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
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
    
    .info-cards {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }
    
    .info-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .info-card::before {
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
    
    .modern-input, .modern-select {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid rgba(102, 126, 234, 0.2);
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        font-weight: 500;
    }
    
    .modern-input:focus, .modern-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        background: white;
        transform: translateY(-2px);
    }
    
    .modern-input.is-invalid, .modern-select.is-invalid {
        border-color: #f72585;
        box-shadow: 0 0 0 0.2rem rgba(247, 37, 133, 0.25);
    }
    
    .input-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
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
    
    .btn-success {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        color: white;
    }
    
    .btn-info {
        background: linear-gradient(135deg, #17a2b8, #6f42c1);
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
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(102, 126, 234, 0.1);
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #4a5568;
    }
    
    .info-value {
        color: #2d3748;
        font-weight: 500;
    }
    
    .quick-actions {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .quick-btn {
        padding: 12px 20px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        justify-content: center;
    }
    
    .quick-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
    
    /* Dark Mode */
    [data-bs-theme="dark"] .page-header,
    [data-bs-theme="dark"] .form-card,
    [data-bs-theme="dark"] .info-card {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .page-title,
    [data-bs-theme="dark"] .section-title {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .page-subtitle {
        color: #a0aec0;
    }
    
    [data-bs-theme="dark"] .form-label,
    [data-bs-theme="dark"] .info-label {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .info-value {
        color: #a0aec0;
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
    
    [data-bs-theme="dark"] .section-title,
    [data-bs-theme="dark"] .form-actions {
        border-bottom-color: rgba(102, 126, 234, 0.2);
        border-top-color: rgba(102, 126, 234, 0.2);
    }
    
    [data-bs-theme="dark"] .info-item {
        border-bottom-color: rgba(102, 126, 234, 0.2);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .edit-stock-container {
            padding: 1rem;
        }
        
        .main-content {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .page-header,
        .form-card,
        .info-card {
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
        .modern-select {
            padding: 12px 16px;
        }
    }
</style>

<div class="edit-stock-container">
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="bi bi-pencil-square"></i>
            {{ __('app.edit_stock') }}
        </h1>
        <p class="page-subtitle">{{ $magazzino->prodotto->nome }} - {{ $magazzino->taglia }} - {{ $magazzino->colore }}</p>
    </div>

    <div class="main-content">
        <!-- Form Card -->
        <div class="form-card">
            <form action="{{ route('magazzino.update', $magazzino) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Sezione Prodotto -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="bi bi-box-seam"></i>
                        {{ __('app.product_information') }}
                    </h3>
                    
                    <div class="form-group">
                        <label for="prodotto_id" class="form-label">
                            <i class="bi bi-tag"></i>
                            {{ __('app.product') }} <span class="required">*</span>
                        </label>
                        <select class="modern-select @error('prodotto_id') is-invalid @enderror"
                                id="prodotto_id" name="prodotto_id" required>
                            <option value="">{{ __('app.select_product') }}...</option>
                            @foreach($prodotti as $prodotto)
                            <option value="{{ $prodotto->id }}" {{ old('prodotto_id', $magazzino->prodotto_id) == $prodotto->id ? 'selected' : '' }}>
                                {{ $prodotto->nome }} ({{ $prodotto->codice_prodotto }})
                            </option>
                            @endforeach
                        </select>
                        @error('prodotto_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Sezione Varianti -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="bi bi-palette"></i>
                        {{ __('app.product_variants') }}
                    </h3>
                    
                    <div class="input-group">
                        <div class="form-group">
                            <label for="taglia" class="form-label">
                                <i class="bi bi-rulers"></i>
                                {{ __('app.size') }} <span class="required">*</span>
                            </label>
                            <select class="modern-select @error('taglia') is-invalid @enderror"
                                    id="taglia" name="taglia" required>
                                <option value="">{{ __('app.select_size') }}...</option>
                                <option value="XXS" {{ old('taglia', $magazzino->taglia) == 'XXS' ? 'selected' : '' }}>XXS</option>
                                <option value="XS" {{ old('taglia', $magazzino->taglia) == 'XS' ? 'selected' : '' }}>XS</option>
                                <option value="S" {{ old('taglia', $magazzino->taglia) == 'S' ? 'selected' : '' }}>S</option>
                                <option value="M" {{ old('taglia', $magazzino->taglia) == 'M' ? 'selected' : '' }}>M</option>
                                <option value="L" {{ old('taglia', $magazzino->taglia) == 'L' ? 'selected' : '' }}>L</option>
                                <option value="XL" {{ old('taglia', $magazzino->taglia) == 'XL' ? 'selected' : '' }}>XL</option>
                                <option value="XXL" {{ old('taglia', $magazzino->taglia) == 'XXL' ? 'selected' : '' }}>XXL</option>
                                <option value="XXXL" {{ old('taglia', $magazzino->taglia) == 'XXXL' ? 'selected' : '' }}>XXXL</option>
                                <option value="4XL" {{ old('taglia', $magazzino->taglia) == '4XL' ? 'selected' : '' }}>4XL</option>
                                <option value="5XL" {{ old('taglia', $magazzino->taglia) == '5XL' ? 'selected' : '' }}>5XL</option>
                                <!-- Taglie numeriche -->
                                <option value="38" {{ old('taglia', $magazzino->taglia) == '38' ? 'selected' : '' }}>38</option>
                                <option value="40" {{ old('taglia', $magazzino->taglia) == '40' ? 'selected' : '' }}>40</option>
                                <option value="42" {{ old('taglia', $magazzino->taglia) == '42' ? 'selected' : '' }}>42</option>
                                <option value="44" {{ old('taglia', $magazzino->taglia) == '44' ? 'selected' : '' }}>44</option>
                                <option value="46" {{ old('taglia', $magazzino->taglia) == '46' ? 'selected' : '' }}>46</option>
                                <option value="48" {{ old('taglia', $magazzino->taglia) == '48' ? 'selected' : '' }}>48</option>
                                <option value="50" {{ old('taglia', $magazzino->taglia) == '50' ? 'selected' : '' }}>50</option>
                                <option value="52" {{ old('taglia', $magazzino->taglia) == '52' ? 'selected' : '' }}>52</option>
                                <option value="54" {{ old('taglia', $magazzino->taglia) == '54' ? 'selected' : '' }}>54</option>
                                <option value="56" {{ old('taglia', $magazzino->taglia) == '56' ? 'selected' : '' }}>56</option>
                            </select>
                            @error('taglia')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="colore" class="form-label">
                                <i class="bi bi-palette-fill"></i>
                                {{ __('app.color') }} <span class="required">*</span>
                            </label>
                            <select class="modern-select @error('colore') is-invalid @enderror"
                                    id="colore" name="colore" required>
                                <option value="">{{ __('app.select_color') }}...</option>
                                <option value="Nero" {{ old('colore', $magazzino->colore) == 'Nero' ? 'selected' : '' }}>{{ __('app.black') }}</option>
                                <option value="Bianco" {{ old('colore', $magazzino->colore) == 'Bianco' ? 'selected' : '' }}>{{ __('app.white') }}</option>
                                <option value="Grigio" {{ old('colore', $magazzino->colore) == 'Grigio' ? 'selected' : '' }}>{{ __('app.gray') }}</option>
                                <option value="Rosso" {{ old('colore', $magazzino->colore) == 'Rosso' ? 'selected' : '' }}>{{ __('app.red') }}</option>
                                <option value="Blu" {{ old('colore', $magazzino->colore) == 'Blu' ? 'selected' : '' }}>{{ __('app.blue') }}</option>
                                <option value="Verde" {{ old('colore', $magazzino->colore) == 'Verde' ? 'selected' : '' }}>{{ __('app.green') }}</option>
                                <option value="Giallo" {{ old('colore', $magazzino->colore) == 'Giallo' ? 'selected' : '' }}>{{ __('app.yellow') }}</option>
                                <option value="Arancione" {{ old('colore', $magazzino->colore) == 'Arancione' ? 'selected' : '' }}>{{ __('app.orange') }}</option>
                                <option value="Rosa" {{ old('colore', $magazzino->colore) == 'Rosa' ? 'selected' : '' }}>{{ __('app.pink') }}</option>
                                <option value="Viola" {{ old('colore', $magazzino->colore) == 'Viola' ? 'selected' : '' }}>{{ __('app.purple') }}</option>
                                <option value="Marrone" {{ old('colore', $magazzino->colore) == 'Marrone' ? 'selected' : '' }}>{{ __('app.brown') }}</option>
                                <option value="Beige" {{ old('colore', $magazzino->colore) == 'Beige' ? 'selected' : '' }}>{{ __('app.beige') }}</option>
                                <option value="Bordeaux" {{ old('colore', $magazzino->colore) == 'Bordeaux' ? 'selected' : '' }}>{{ __('app.bordeaux') }}</option>
                                <option value="Navy" {{ old('colore', $magazzino->colore) == 'Navy' ? 'selected' : '' }}>{{ __('app.navy') }}</option>
                                <option value="Azzurro" {{ old('colore', $magazzino->colore) == 'Azzurro' ? 'selected' : '' }}>{{ __('app.light_blue') }}</option>
                                <option value="Fucsia" {{ old('colore', $magazzino->colore) == 'Fucsia' ? 'selected' : '' }}>{{ __('app.fuchsia') }}</option>
                                <option value="Oro" {{ old('colore', $magazzino->colore) == 'Oro' ? 'selected' : '' }}>{{ __('app.gold') }}</option>
                                <option value="Argento" {{ old('colore', $magazzino->colore) == 'Argento' ? 'selected' : '' }}>{{ __('app.silver') }}</option>
                                <option value="Multicolore" {{ old('colore', $magazzino->colore) == 'Multicolore' ? 'selected' : '' }}>{{ __('app.multicolor') }}</option>
                            </select>
                            @error('colore')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Sezione Scorte -->
                <div class="form-section">
                    <h3 class="section-title">
                        <i class="bi bi-archive"></i>
                        {{ __('app.stock_management') }}
                    </h3>
                    
                    <div class="input-group">
                        <div class="form-group">
                            <label for="quantita" class="form-label">
                                <i class="bi bi-123"></i>
                                {{ __('app.quantity') }} <span class="required">*</span>
                            </label>
                            <input type="number" class="modern-input @error('quantita') is-invalid @enderror"
                                   id="quantita" name="quantita" min="0" value="{{ old('quantita', $magazzino->quantita) }}" required
                                   placeholder="{{ __('app.enter_available_quantity') }}">
                            @error('quantita')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="scorta_minima" class="form-label">
                                <i class="bi bi-exclamation-triangle"></i>
                                {{ __('app.minimum_stock') }} <span class="required">*</span>
                            </label>
                            <input type="number" class="modern-input @error('scorta_minima') is-invalid @enderror"
                                   id="scorta_minima" name="scorta_minima" min="0" value="{{ old('scorta_minima', $magazzino->scorta_minima) }}" required
                                   placeholder="{{ __('app.minimum_threshold_notifications') }}">
                            @error('scorta_minima')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="input-hint">{{ __('app.low_stock_notification') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Azioni Form -->
                <div class="form-actions">
                    <a href="{{ route('magazzino.index') }}" class="modern-btn btn-secondary">
                        <i class="bi bi-x-circle"></i>
                        {{ __('app.cancel') }}
                    </a>
                    <button type="submit" class="modern-btn btn-primary">
                        <i class="bi bi-check-circle"></i>
                        {{ __('app.update_stock') }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Cards -->
        <div class="info-cards">
            <!-- Stock Information -->
            <div class="info-card">
                <h3 class="section-title">
                    <i class="bi bi-info-circle"></i>
                    {{ __('app.stock_information') }}
                </h3>
                
                <div class="info-item">
                    <span class="info-label">{{ __('app.product') }}:</span>
                    <span class="info-value">{{ $magazzino->prodotto->nome }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ __('app.code') }}:</span>
                    <span class="info-value">{{ $magazzino->prodotto->codice_prodotto }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ __('app.price') }}:</span>
                    <span class="info-value">€{{ number_format($magazzino->prodotto->prezzo, 2) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ __('app.category') }}:</span>
                    <span class="info-value">{{ $magazzino->prodotto->categoria }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ __('app.created_on') }}:</span>
                    <span class="info-value">{{ $magazzino->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">{{ __('app.last_modified') }}:</span>
                    <span class="info-value">{{ $magazzino->updated_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="info-card">
                <h3 class="section-title">
                    <i class="bi bi-lightning"></i>
                    {{ __('app.quick_actions') }}
                </h3>
                
                <div class="quick-actions">
                    <button type="button" class="quick-btn btn-success" onclick="document.getElementById('quantita').value = parseInt(document.getElementById('quantita').value || 0) + 10">
                        <i class="bi bi-plus"></i> {{ __('app.add_10_pieces') }}
                    </button>
                    <button type="button" class="quick-btn btn-warning" onclick="document.getElementById('quantita').value = Math.max(0, parseInt(document.getElementById('quantita').value || 0) - 5)">
                        <i class="bi bi-dash"></i> {{ __('app.subtract_5_pieces') }}
                    </button>
                    <button type="button" class="quick-btn btn-info" onclick="document.getElementById('quantita').value = 0">
                        <i class="bi bi-x-circle"></i> {{ __('app.clear_stock') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection