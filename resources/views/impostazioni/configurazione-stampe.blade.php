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
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .section-title i {
        color: #029D7E;
        font-size: 1.2rem;
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
    
    .modern-btn.btn-danger,
    .btn-danger.modern-btn {
        background: linear-gradient(135deg, #f72585, #c5025a);
        color: white;
        border: none;
    }
    
    .modern-btn.btn-danger:hover,
    .btn-danger.modern-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(247, 37, 133, 0.3);
        color: white;
    }
    
    .association-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .association-card:hover {
        border-color: #029D7E;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(2, 157, 126, 0.2);
    }
    
    .document-type {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    
    .template-name {
        color: #029D7E;
        font-size: 1rem;
        font-weight: 500;
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
    
    .alert-warning {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: #333;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
        color: #029D7E;
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
    
    [data-bs-theme="dark"] .section-title i {
        color: #4DC9A5 !important;
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
    
    [data-bs-theme="dark"] .association-card {
        background: rgba(45, 55, 72, 0.8) !important;
        border-color: rgba(2, 157, 126, 0.3) !important;
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .document-type {
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .template-name {
        color: #4DC9A5 !important;
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
            <strong>Attenzione!</strong> Sono stati rilevati dei problemi:
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
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="config-title">
                    <i class="bi bi-link-45deg"></i> Configurazione Stampe
                </h1>
                <p class="text-muted mt-2">Associa i template grafici ai tipi di documento</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('impostazioni.index') }}" class="modern-btn secondary">
                    <i class="bi bi-arrow-left"></i> Torna alle Impostazioni
                </a>
            </div>
        </div>
    </div>

    <!-- Associazioni Esistenti -->
    <div class="config-card">
        <h3 class="section-title">
            <i class="bi bi-list-ul"></i> Associazioni Configurate
        </h3>
        
        @if(isset($associations) && $associations->count() > 0)
            @foreach($associations as $association)
                <div class="association-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="document-type">{{ $association->documentType->tipo_documento ?? 'Tipo documento non trovato' }}</div>
                            <div class="template-name">
                                <i class="bi bi-file-earmark-text me-1"></i>
                                Template: {{ $association->template->name ?? 'Template non trovato' }}
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-danger modern-btn btn-sm" 
                                    onclick="showDeleteModal('{{ $association->uuid }}', '{{ addslashes($association->documentType->tipo_documento ?? 'Sconosciuto') }}', '{{ addslashes($association->template->name ?? 'Sconosciuto') }}')">
                                <i class="bi bi-trash"></i> Rimuovi
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <i class="bi bi-link-45deg"></i>
                <h5>Nessuna associazione configurata</h5>
                <p class="text-muted">Crea la prima associazione tra un tipo di documento e un template.</p>
            </div>
        @endif
    </div>

    <!-- Form Nuova Associazione -->
    <div class="config-card">
        <h3 class="section-title">
            <i class="bi bi-plus-circle"></i> Crea Nuova Associazione
        </h3>
        
        <form action="{{ route('impostazioni.configurazione-stampe.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tipo_documento" class="form-label">Tipo di Documento *</label>
                        <select class="form-select @error('tipo_documento') is-invalid @enderror" 
                                id="tipo_documento" name="tipo_documento" required>
                            <option value="">Seleziona tipo documento</option>
                            @if(isset($documentTypes))
                                @foreach($documentTypes as $docType)
                                    <option value="{{ $docType->id }}" 
                                            {{ old('tipo_documento') == $docType->id ? 'selected' : '' }}>
                                        {{ $docType->codice }} - {{ $docType->tipo_documento }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('tipo_documento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i> Seleziona il tipo di documento da associare (DDT, Fatture, etc.)
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="template_stampa" class="form-label">Template di Stampa *</label>
                        <select class="form-select @error('template_stampa') is-invalid @enderror" 
                                id="template_stampa" name="template_stampa" required>
                            <option value="">Seleziona template</option>
                            @if(isset($templates))
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}" 
                                            {{ old('template_stampa') == $template->id ? 'selected' : '' }}>
                                        {{ $template->name }}
                                        @if($template->modified_at)
                                            <small>(modificato {{ $template->modified_at->format('d/m/Y') }})</small>
                                        @endif
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('template_stampa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i> Template creato nella sezione "Moduli di Stampa"
                        </div>
                    </div>
                </div>
            </div>

            @if(!isset($documentTypes) || $documentTypes->count() == 0)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Attenzione:</strong> Non sono stati trovati tipi di documento nel sistema. 
                    Verifica la configurazione della tabella "tipidocumenti".
                </div>
            @endif

            @if(!isset($templates) || $templates->count() == 0)
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Attenzione:</strong> Non sono stati trovati template nel sistema. 
                    <a href="{{ route('configurations.templates.index') }}" class="text-decoration-none">
                        Crea il primo template nella sezione "Moduli di Stampa"
                    </a>.
                </div>
            @endif

            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg" 
                        @if(!isset($documentTypes) || $documentTypes->count() == 0 || !isset($templates) || $templates->count() == 0) disabled @endif>
                    <i class="bi bi-plus-circle"></i> Crea Associazione
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Conferma Eliminazione -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle text-danger"></i> Conferma Eliminazione
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="bi bi-link-45deg display-4 mb-3 text-danger"></i>
                    <h6>Stai per eliminare definitivamente l'associazione:</h6>
                    <div class="alert alert-warning mt-3">
                        <strong id="documentTypeToDelete"></strong><br>
                        <small>Template: <span id="templateNameToDelete"></span></small>
                    </div>
                    <p class="text-muted">
                        <i class="bi bi-info-circle"></i> Questa azione non può essere annullata.<br>
                        I documenti di questo tipo non avranno più un template associato.
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modern-btn" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Annulla
                </button>
                <button type="button" class="btn btn-danger modern-btn" id="confirmDeleteBtn">
                    <i class="bi bi-trash"></i> Elimina Definitivamente
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let associationToDelete = null;

/**
 * Mostra modal di conferma eliminazione
 */
function showDeleteModal(uuid, documentType, templateName) {
    associationToDelete = uuid;
    document.getElementById('documentTypeToDelete').textContent = documentType;
    document.getElementById('templateNameToDelete').textContent = templateName;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

/**
 * Conferma eliminazione definitiva
 */
document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (!associationToDelete) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/impostazioni/configurazione-stampe/${associationToDelete}`;
    
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    
    const tokenInput = document.createElement('input');
    tokenInput.type = 'hidden';
    tokenInput.name = '_token';
    tokenInput.value = '{{ csrf_token() }}';
    
    form.appendChild(methodInput);
    form.appendChild(tokenInput);
    document.body.appendChild(form);
    form.submit();
});

// Auto-hide alert dopo 5 secondi
document.addEventListener('DOMContentLoaded', function() {
    const alertElement = document.querySelector('.alert-success');
    if (alertElement) {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alertElement);
            bsAlert.close();
        }, 5000);
    }
    
    // Animazione card
    const cards = document.querySelectorAll('.config-card, .association-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endsection