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
        color: #029D7E;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #029D7E;
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
        color: white;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(2, 157, 126, 0.3);
        color: white;
    }
    
    .modern-btn {
        border: none;
        border-radius: 15px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        font-size: 0.9rem;
    }
    
    .modern-btn.secondary {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
    }
    
    .modern-btn.secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(108, 117, 125, 0.3);
        color: white;
        text-decoration: none;
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
    
    .numbering-card {
        background: rgba(2, 157, 126, 0.1);
        border: 2px solid rgba(2, 157, 126, 0.2);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .numbering-card:hover {
        border-color: rgba(2, 157, 126, 0.4);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(2, 157, 126, 0.2);
    }
    
    .doc-type-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .preview-number {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        padding: 15px;
        border-radius: 15px;
        border: 2px solid rgba(255, 255, 255, 0.8);
        font-weight: 600;
        font-family: monospace;
        font-size: 1.1rem;
        margin-top: 0;
        display: flex;
        width: 100%;
        text-align: center;
        height: 56px;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(2, 157, 126, 0.2);
    }

    .preview-number:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(2, 157, 126, 0.3);
    }

    /* Centra la label Anteprima */
    .preview-container .form-label {
        text-align: center;
        margin-bottom: 10px;
    }

    /* Checkbox personalizzati con verde brand */
    .form-check-input {
        border-color: rgba(2, 157, 126, 0.3);
        transition: all 0.3s ease;
    }
    
    .form-check-input:checked {
        background-color: #029D7E;
        border-color: #029D7E;
    }
    
    .form-check-input:focus {
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
    }
    
    .form-check-input:checked:focus {
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
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
        color: #4DC9A5 !important;
        border-bottom-color: #4DC9A5 !important;
    }
    
    [data-bs-theme="dark"] .numbering-card {
        background: rgba(2, 157, 126, 0.15) !important;
        border-color: rgba(2, 157, 126, 0.3) !important;
    }
    
    [data-bs-theme="dark"] .doc-type-title {
        color: #e2e8f0 !important;
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

    /* Checkbox dark mode */
    [data-bs-theme="dark"] .form-check-input {
        background-color: rgba(45, 55, 72, 0.8) !important;
        border-color: rgba(2, 157, 126, 0.4) !important;
    }
    
    [data-bs-theme="dark"] .form-check-input:checked {
        background-color: #4DC9A5 !important;
        border-color: #4DC9A5 !important;
    }
</style>

<div class="container-fluid config-container">
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
            <strong>Errori di validazione:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <!-- Header -->
    <div class="config-header">
        <div class="text-center">
            <h1 class="config-title">
                <i class="bi bi-123"></i> Configurazione Numeratori
            </h1>
            <p class="text-muted mt-2 mb-3">Gestisci i numeratori automatici per tutti i tipi di documento</p>
            <a href="{{ route('impostazioni.index') }}" class="modern-btn secondary">
                <i class="bi bi-arrow-left"></i> Torna alle Impostazioni
            </a>
        </div>
    </div>

    <form action="{{ route('impostazioni.configurazione-numeratori.update') }}" method="POST">
        @csrf

        <!-- Numeratori Documenti -->
        <div class="config-card">
            <h3 class="section-title">
                <i class="bi bi-123"></i> Numeratori Documenti
            </h3>
            
            <!-- DDT -->
            <div class="numbering-card">
                <div class="doc-type-title">
                    <i class="bi bi-file-earmark-text"></i> DDT (Documenti di Trasporto)
                </div>
                
                <div class="row">
                    <div class="col-md-2">
                        <label class="form-label">Numero Corrente</label>
                        <input type="number" class="form-control" 
                               name="numbering[ddt][current_number]" 
                               value="{{ $numbering['ddt']->current_number ?? 1 }}" 
                               min="1">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Prefisso</label>
                        <input type="text" class="form-control" 
                               name="numbering[ddt][prefix]" 
                               value="{{ $numbering['ddt']->prefix ?? '' }}" 
                               placeholder="DDT">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Separatore</label>
                        <input type="text" class="form-control" 
                               name="numbering[ddt][separator]" 
                               value="{{ $numbering['ddt']->separator ?? '/' }}" 
                               placeholder="/">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Opzioni</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="numbering[ddt][use_year]" 
                                   {{ ($numbering['ddt']->use_year ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Usa Anno Corrente</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="numbering[ddt][use_month]" 
                                   {{ ($numbering['ddt']->use_month ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Usa Mese Corrente</label>
                        </div>
                    </div>
                    
                    <div class="col-md-3 preview-container">
                        <label class="form-label">Anteprima</label>
                        <div class="preview-number" id="ddt-preview">
                            @php
                                $ddt = $numbering['ddt'] ?? null;
                                $parts = [];
                                if($ddt && $ddt->prefix) $parts[] = $ddt->prefix;
                                $parts[] = str_pad($ddt->current_number ?? 1, 4, '0', STR_PAD_LEFT);
                                if($ddt && $ddt->use_month) $parts[] = date('m');
                                if($ddt && $ddt->use_year) $parts[] = date('Y');
                                echo implode($ddt->separator ?? '/', $parts);
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fatture -->
            <div class="numbering-card">
                <div class="doc-type-title">
                    <i class="bi bi-receipt"></i> Fatture
                </div>
                
                <div class="row">
                    <div class="col-md-2">
                        <label class="form-label">Numero Corrente</label>
                        <input type="number" class="form-control" 
                               name="numbering[fatture][current_number]" 
                               value="{{ $numbering['fatture']->current_number ?? 1 }}" 
                               min="1">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Prefisso</label>
                        <input type="text" class="form-control" 
                               name="numbering[fatture][prefix]" 
                               value="{{ $numbering['fatture']->prefix ?? '' }}" 
                               placeholder="FAT">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Separatore</label>
                        <input type="text" class="form-control" 
                               name="numbering[fatture][separator]" 
                               value="{{ $numbering['fatture']->separator ?? '/' }}" 
                               placeholder="/">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Opzioni</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="numbering[fatture][use_year]" 
                                   {{ ($numbering['fatture']->use_year ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Usa Anno Corrente</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="numbering[fatture][use_month]" 
                                   {{ ($numbering['fatture']->use_month ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Usa Mese Corrente</label>
                        </div>
                    </div>
                    
                    <div class="col-md-3 preview-container">
                        <label class="form-label">Anteprima</label>
                        <div class="preview-number" id="fatture-preview">
                            @php
                                $fat = $numbering['fatture'] ?? null;
                                $parts = [];
                                if($fat && $fat->prefix) $parts[] = $fat->prefix;
                                $parts[] = str_pad($fat->current_number ?? 1, 4, '0', STR_PAD_LEFT);
                                if($fat && $fat->use_month) $parts[] = date('m');
                                if($fat && $fat->use_year) $parts[] = date('Y');
                                echo implode($fat->separator ?? '/', $parts);
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preventivi -->
            <div class="numbering-card">
                <div class="doc-type-title">
                    <i class="bi bi-file-earmark-check"></i> Preventivi
                </div>
                
                <div class="row">
                    <div class="col-md-2">
                        <label class="form-label">Numero Corrente</label>
                        <input type="number" class="form-control" 
                               name="numbering[preventivi][current_number]" 
                               value="{{ $numbering['preventivi']->current_number ?? 1 }}" 
                               min="1">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Prefisso</label>
                        <input type="text" class="form-control" 
                               name="numbering[preventivi][prefix]" 
                               value="{{ $numbering['preventivi']->prefix ?? '' }}" 
                               placeholder="PREV">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Separatore</label>
                        <input type="text" class="form-control" 
                               name="numbering[preventivi][separator]" 
                               value="{{ $numbering['preventivi']->separator ?? '/' }}" 
                               placeholder="/">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Opzioni</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="numbering[preventivi][use_year]" 
                                   {{ ($numbering['preventivi']->use_year ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Usa Anno Corrente</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="numbering[preventivi][use_month]" 
                                   {{ ($numbering['preventivi']->use_month ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Usa Mese Corrente</label>
                        </div>
                    </div>
                    
                    <div class="col-md-3 preview-container">
                        <label class="form-label">Anteprima</label>
                        <div class="preview-number" id="preventivi-preview">
                            @php
                                $prev = $numbering['preventivi'] ?? null;
                                $parts = [];
                                if($prev && $prev->prefix) $parts[] = $prev->prefix;
                                $parts[] = str_pad($prev->current_number ?? 1, 4, '0', STR_PAD_LEFT);
                                if($prev && $prev->use_month) $parts[] = date('m');
                                if($prev && $prev->use_year) $parts[] = date('Y');
                                echo implode($prev->separator ?? '/', $parts);
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

            <!-- Note di Credito -->
            <div class="numbering-card">
                <div class="doc-type-title">
                    <i class="bi bi-file-earmark-minus"></i> Note di Credito
                </div>
                
                <div class="row">
                    <div class="col-md-2">
                        <label class="form-label">Numero Corrente</label>
                        <input type="number" class="form-control" 
                               name="numbering[note_credito][current_number]" 
                               value="{{ $numbering['note_credito']->current_number ?? 1 }}" 
                               min="1">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Prefisso</label>
                        <input type="text" class="form-control" 
                               name="numbering[note_credito][prefix]" 
                               value="{{ $numbering['note_credito']->prefix ?? '' }}" 
                               placeholder="NC">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Separatore</label>
                        <input type="text" class="form-control" 
                               name="numbering[note_credito][separator]" 
                               value="{{ $numbering['note_credito']->separator ?? '/' }}" 
                               placeholder="/">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Opzioni</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="numbering[note_credito][use_year]" 
                                   {{ ($numbering['note_credito']->use_year ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Usa Anno Corrente</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="numbering[note_credito][use_month]" 
                                   {{ ($numbering['note_credito']->use_month ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Usa Mese Corrente</label>
                        </div>
                    </div>
                    
                    <div class="col-md-3 preview-container">
                        <label class="form-label">Anteprima</label>
                        <div class="preview-number" id="note_credito-preview">
                            @php
                                $nc = $numbering['note_credito'] ?? null;
                                $parts = [];
                                if($nc && $nc->prefix) $parts[] = $nc->prefix;
                                $parts[] = str_pad($nc->current_number ?? 1, 4, '0', STR_PAD_LEFT);
                                if($nc && $nc->use_month) $parts[] = date('m');
                                if($nc && $nc->use_year) $parts[] = date('Y');
                                echo implode($nc->separator ?? '/', $parts);
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ordini -->
            <div class="numbering-card">
                <div class="doc-type-title">
                    <i class="bi bi-cart"></i> Ordini
                </div>
                
                <div class="row">
                    <div class="col-md-2">
                        <label class="form-label">Numero Corrente</label>
                        <input type="number" class="form-control" 
                               name="numbering[ordini][current_number]" 
                               value="{{ $numbering['ordini']->current_number ?? 1 }}" 
                               min="1">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Prefisso</label>
                        <input type="text" class="form-control" 
                               name="numbering[ordini][prefix]" 
                               value="{{ $numbering['ordini']->prefix ?? '' }}" 
                               placeholder="ORD">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Separatore</label>
                        <input type="text" class="form-control" 
                               name="numbering[ordini][separator]" 
                               value="{{ $numbering['ordini']->separator ?? '/' }}" 
                               placeholder="/">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Opzioni</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="numbering[ordini][use_year]" 
                                   {{ ($numbering['ordini']->use_year ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Usa Anno Corrente</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="numbering[ordini][use_month]" 
                                   {{ ($numbering['ordini']->use_month ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Usa Mese Corrente</label>
                        </div>
                    </div>
                    
                    <div class="col-md-3 preview-container">
                        <label class="form-label">Anteprima</label>
                        <div class="preview-number" id="ordini-preview">
                            @php
                                $ord = $numbering['ordini'] ?? null;
                                $parts = [];
                                if($ord && $ord->prefix) $parts[] = $ord->prefix;
                                $parts[] = str_pad($ord->current_number ?? 1, 4, '0', STR_PAD_LEFT);
                                if($ord && $ord->use_month) $parts[] = date('m');
                                if($ord && $ord->use_year) $parts[] = date('Y');
                                echo implode($ord->separator ?? '/', $parts);
                            @endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="config-card text-center">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-check-circle"></i> Salva Configurazioni Numeratori
            </button>
        </div>
    </form>
</div>

<script>
// Aggiorna anteprima numerazione in tempo reale
document.addEventListener('DOMContentLoaded', function() {
    const documentTypes = ['ddt', 'fatture', 'preventivi', 'note_credito', 'ordini'];
    
    documentTypes.forEach(docType => {
        const inputs = document.querySelectorAll(`[name*="[${docType}]"]`);
        inputs.forEach(input => {
            input.addEventListener('input', () => updatePreview(docType));
        });
    });
    
    function updatePreview(docType) {
        const prefix = document.querySelector(`[name="numbering[${docType}][prefix]"]`).value || '';
        const currentNumber = document.querySelector(`[name="numbering[${docType}][current_number]"]`).value || '1';
        const separator = document.querySelector(`[name="numbering[${docType}][separator]"]`).value || '/';
        const useYear = document.querySelector(`[name="numbering[${docType}][use_year]"]`).checked;
        const useMonth = document.querySelector(`[name="numbering[${docType}][use_month]"]`).checked;
        
        let parts = [];
        if (prefix) parts.push(prefix);
        parts.push(String(currentNumber).padStart(4, '0'));
        if (useMonth) parts.push(String(new Date().getMonth() + 1).padStart(2, '0'));
        if (useYear) parts.push(String(new Date().getFullYear()));
        
        const preview = parts.join(separator);
        document.getElementById(`${docType}-preview`).textContent = preview;
    }
    
    // Animazione card
    const cards = document.querySelectorAll('.config-card, .numbering-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Auto-hide alert dopo 5 secondi
    const alertElement = document.querySelector('.alert-success');
    if (alertElement) {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alertElement);
            bsAlert.close();
        }, 5000);
    }
});
</script>
@endsection