@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .management-container {
        padding: 2rem;
    }
    
    .management-container:first-child {
        min-height: auto;
        padding-bottom: 0;
    }
    
    /* Header con form creazione */
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
    
    .modern-btn.primary {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
    }
    
    .modern-btn.secondary {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
    }
    
    .modern-btn.warning {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
    }
    
    .modern-btn.danger {
        background: linear-gradient(135deg, #f72585, #c5025a);
        color: white;
    }
    
    .modern-btn.purple {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
    }
    
    .modern-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        color: white;
        text-decoration: none;
    }
    
    /* Form styling */
    .form-control {
        border-radius: 15px;
        padding: 15px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.8);
    }
    
    .form-control:focus {
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
        background: white;
    }
    
    /* Card tabella */
    .templates-table-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
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
    
    /* Tabella moderna */
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: white;
        border: none;
    }
    
    .modern-table thead th {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        color: white;
        font-weight: 600;
        border: none;
        padding: 1rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }
    
    .modern-table tbody td {
        padding: 1rem;
        border-bottom: 1px solid #f8f9fa;
        vertical-align: middle;
        background: white;
    }
    
    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .modern-table tbody tr:hover td {
        background-color: rgba(2, 157, 126, 0.05);
    }
    
    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .management-container {
            padding: 1rem;
        }
        
        .management-header, .templates-table-card {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .management-title {
            font-size: 1.5rem;
        }
        
        .modern-btn {
            padding: 10px 20px;
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 576px) {
        .management-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .templates-table-card {
            padding: 1rem;
        }
        
        .table-responsive {
            border-radius: 15px;
        }
        
        /* Form responsive mobile */
        .template-form-mobile {
            flex-direction: column !important;
            align-items: stretch !important;
        }
        
        .template-form-mobile .template-button-container {
            margin-right: 0 !important;
            margin-top: 1rem;
            display: flex;
            justify-content: center;
            position: static !important;
            right: auto !important;
        }
    }
    
    /* Allineamento button al margine come header */
    .template-button-container {
        margin-right: 2rem;
    }
    
    /* Action buttons piccoli e quadrati come nelle 27 tabelle */
    .action-btn {
        border: none;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        margin: 0 2px;
        transition: all 0.3s ease;
        text-decoration: none;
        cursor: pointer;
        padding: 8px;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .action-btn.view {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
    }
    
    .action-btn.edit {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
    }
    
    .action-btn.delete {
        background: linear-gradient(135deg, #f72585, #c5025a);
        color: white;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        text-decoration: none;
        color: white;
    }
    
    /* Table container sul verde con suo banner bianco */
    .table-container {
        padding: 0 2rem 2rem 2rem;
    }
    
    .table-container .table-responsive {
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    
    /* Mobile Cards - Nasconde tabella, mostra card */
    .mobile-cards {
        display: none;
    }
    
    .item-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .item-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .item-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
        flex: 1;
        min-width: 0;
    }
    
    .item-card-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .mobile-action-btn {
        flex: 1;
        min-width: 80px;
        border: none;
        border-radius: 10px;
        padding: 12px 8px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
        cursor: pointer;
    }
    
    .mobile-action-btn i {
        font-size: 1.2rem;
    }
    
    .mobile-action-btn.view {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
    }
    
    .mobile-action-btn.edit {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
    }
    
    .mobile-action-btn.delete {
        background: linear-gradient(135deg, #f72585, #c5025a);
        color: white;
    }
    
    .mobile-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: white;
        text-decoration: none;
    }
    
    /* Responsive perfetto */
    @media (max-width: 768px) {        
        /* Nasconde tabella su mobile */
        .table-container .table-responsive {
            display: none;
        }
        
        .mobile-cards {
            display: block;
        }
        
        .table-container {
            padding: 0 1rem 1rem 1rem;
        }
    }
    
    @media (max-width: 576px) {        
        .mobile-action-btn {
            padding: 10px 6px;
            font-size: 0.7rem;
            min-width: 70px;
        }
        
        .mobile-action-btn i {
            font-size: 1rem;
        }
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
    
    <!-- Header con titolo e form creazione -->
    <div class="management-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <h1 class="management-title">
                    <i class="bi bi-printer me-3"></i>
                    Moduli di Stampa
                </h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('configurations.index') }}" class="modern-btn secondary">
                    <i class="bi bi-arrow-left"></i> Torna alle Configurazioni
                </a>
            </div>
        </div>
    </div>

    <!-- Form Creazione Nuovo Template -->
    <div class="templates-table-card">
        <h3 class="section-title">
            <i class="bi bi-plus-circle"></i> Crea Nuovo Template
        </h3>
        
        <form action="{{ route('configurations.templates.store') }}" method="POST" class="d-flex gap-3 align-items-end template-form-mobile">
            @csrf
            <div class="flex-grow-1">
                <label for="name" class="form-label">Nome Template *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" 
                       value="{{ old('name') }}" 
                       placeholder="Inserisci nome template (es. Fattura Standard, DDT Moderno)"
                       required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="template-button-container">
                <button type="submit" class="modern-btn primary">
                    <i class="bi bi-plus-circle"></i> Crea Nuovo Report
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Banner con solo titolo Template di Report -->
<div class="management-container">
    <div class="templates-table-card">
        <h3 class="section-title">
            <i class="bi bi-list-ul"></i> Template di Report
        </h3>
    </div>
</div>

<!-- Tabella separata sul verde -->
<div class="table-container">
    <div class="table-responsive">
        @if($templates->count() > 0)
            <table class="table modern-table" id="dataTable">
                <thead>
                    <tr>
                        <th>Titolo</th>
                        <th>Data di Modifica</th>
                        <th class="text-center">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($templates as $template)
                        <tr>
                            <td>
                                <strong>{{ $template->name }}</strong>
                            </td>
                            <td>
                                <span class="text-muted">{{ $template->formatted_modified_at }}</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('configurations.templates.show', $template->id) }}" 
                                       class="action-btn view" title="Visualizza">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('configurations.templates.edit', $template->id) }}" 
                                       class="action-btn edit" title="Modifica">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button class="action-btn delete" title="Elimina"
                                            onclick="showDeleteModal({{ $template->id }}, '{{ $template->name }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <i class="bi bi-printer"></i>
                <h5>Nessun Template Creato</h5>
                <p class="text-muted">Crea il tuo primo template per iniziare a personalizzare i documenti del gestionale.</p>
            </div>
        @endif
    </div>
    
    <!-- Mobile Cards -->
    <div class="mobile-cards">
        @if($templates->count() > 0)
            @foreach($templates as $template)
                <div class="item-card">
                    <div class="item-card-header">
                        <h5 class="item-card-title">{{ $template->name }}</h5>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">{{ $template->formatted_modified_at }}</small>
                    </div>
                    <div class="item-card-actions">
                        <a href="{{ route('configurations.templates.show', $template->id) }}" 
                           class="mobile-action-btn view">
                            <i class="bi bi-eye"></i>
                            <span>Visualizza</span>
                        </a>
                        <a href="{{ route('configurations.templates.edit', $template->id) }}" 
                           class="mobile-action-btn edit">
                            <i class="bi bi-pencil"></i>
                            <span>Modifica</span>
                        </a>
                        <button class="mobile-action-btn delete"
                                onclick="showDeleteModal({{ $template->id }}, '{{ $template->name }}')">
                            <i class="bi bi-trash"></i>
                            <span>Elimina</span>
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <i class="bi bi-printer"></i>
                <h5>Nessun Template Creato</h5>
                <p class="text-muted">Crea il tuo primo template per iniziare a personalizzare i documenti del gestionale.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal Conferma Eliminazione -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #f8d7da, #f5c6cb); border-radius: 20px 20px 0 0;">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="bi bi-exclamation-triangle" style="color: #f72585;"></i> Conferma Eliminazione
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="bi bi-printer display-4 mb-3" style="color: #f72585;"></i>
                    <h6>Stai per eliminare definitivamente:</h6>
                    <div class="alert alert-warning mt-3">
                        <strong id="templateNameToDelete"></strong>
                    </div>
                    <p class="text-muted">
                        <i class="bi bi-info-circle"></i> Questa azione non può essere annullata.<br>
                        Il template verrà rimosso definitivamente dal sistema.
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modern-btn secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Annulla
                </button>
                <button type="button" class="modern-btn danger" id="confirmDeleteBtn">
                    <i class="bi bi-trash"></i> Elimina Definitivamente
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let templateToDelete = null;

/**
 * Mostra modal di conferma eliminazione
 */
function showDeleteModal(templateId, templateName) {
    templateToDelete = templateId;
    document.getElementById('templateNameToDelete').textContent = templateName;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

/**
 * Conferma eliminazione definitiva
 */
document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (!templateToDelete) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/configurations/templates/${templateToDelete}`;
    
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
});
</script>
@endsection