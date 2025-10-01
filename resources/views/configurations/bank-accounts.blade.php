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
    
    .bank-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .bank-card:hover {
        border-color: #029D7E;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(2, 157, 126, 0.2);
    }
    
    .bank-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    
    .bank-details {
        color: #718096;
        font-size: 0.9rem;
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
    
    .btn-primary {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        border: none;
        border-radius: 15px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(2, 157, 126, 0.3);
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
    
    .modern-btn.btn-secondary,
    .btn-secondary.modern-btn {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
        border: none;
    }
    
    .modern-btn.btn-secondary:hover,
    .btn-secondary.modern-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(108, 117, 125, 0.3);
        color: white;
    }
    
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
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .config-container {
            padding: 1rem;
        }
        
        .config-header, .config-card {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .config-title {
            font-size: 1.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .config-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .config-header .d-flex .d-flex {
            justify-content: center;
        }
        
        .bank-card .d-flex {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch !important;
        }
        
        .bank-card .btn {
            align-self: center;
            min-width: 120px;
        }
        
        .config-card {
            padding: 1rem;
        }
        
        .bank-details {
            margin-bottom: 1rem;
        }
    }
    
    /* Modal styling - colori coerenti con button */
    .modal-danger-icon {
        color: #f72585;
    }
    
    .modal-bank-icon {
        color: #f72585;
    }
    
    .modal-content {
        border-radius: 20px;
        border: none;
    }
    
    .modal-header {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        border-radius: 20px 20px 0 0;
    }
    
    .modal-alert-custom {
        background: linear-gradient(135deg, rgba(247, 37, 133, 0.1), rgba(197, 2, 90, 0.1));
        border: 2px solid rgba(247, 37, 133, 0.3);
        border-radius: 15px;
        color: #f72585;
        font-weight: 600;
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
                    <i class="bi bi-bank"></i> {{ __('app.bank_accounts') }}
                </h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('configurations.index') }}" class="modern-btn secondary">
                    <i class="bi bi-arrow-left"></i> {{ __('app.torna_indietro') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Conti Bancari Esistenti -->
    <div class="config-card">
        <h3 class="section-title">
            <i class="bi bi-list-ul"></i> Coordinate Bancarie Configurate
        </h3>
        
        @forelse($accounts as $account)
            <div class="bank-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="bank-name">{{ $account->nome_banca }}</div>
                        <div class="bank-details">
                            <strong>IBAN:</strong> {{ $account->iban }}<br>
                            <strong>C/C:</strong> {{ $account->conto_corrente }}
                            @if($account->abi && $account->cab)
                                <br><strong>ABI:</strong> {{ $account->abi }} - <strong>CAB:</strong> {{ $account->cab }}
                            @endif
                            @if($account->swift)
                                <br><strong>SWIFT:</strong> {{ $account->swift }}
                            @endif
                            @if($account->sia)
                                <br><strong>SIA:</strong> {{ $account->sia }}
                            @endif
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-danger modern-btn btn-sm" onclick="showDeleteModal('{{ $account->uuid }}', '{{ $account->nome_banca }}', '{{ $account->iban ? substr($account->iban, -4) : 'N/A' }}')">
                            <i class="bi bi-trash"></i> Elimina
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-4">
                <i class="bi bi-bank display-4 text-muted"></i>
                <p class="text-muted mt-3">Nessuna coordinata bancaria configurata</p>
            </div>
        @endforelse
    </div>

    <!-- Form Nuova Coordinata Bancaria -->
    <div class="config-card">
        <h3 class="section-title">
            <i class="bi bi-plus-circle"></i> Aggiungi Nuova Coordinata Bancaria
        </h3>
        
        <form action="{{ route('configurations.bank-accounts.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nome_banca" class="form-label">Nome Banca *</label>
                        <input type="text" class="form-control @error('nome_banca') is-invalid @enderror" 
                               id="nome_banca" name="nome_banca" 
                               value="{{ old('nome_banca') }}" required>
                        @error('nome_banca')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="conto_corrente" class="form-label">Conto Corrente *</label>
                        <input type="text" class="form-control @error('conto_corrente') is-invalid @enderror" 
                               id="conto_corrente" name="conto_corrente" 
                               value="{{ old('conto_corrente') }}" required>
                        @error('conto_corrente')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="iban" class="form-label">IBAN *</label>
                        <input type="text" class="form-control @error('iban') is-invalid @enderror" 
                               id="iban" name="iban" 
                               value="{{ old('iban') }}" 
                               placeholder="IT60 X054 2811 1010 0000 0123 456" 
                               oninput="validateIban(this)" required>
                        <div class="form-text">
                            <span id="iban-feedback" class="text-muted">Inserisci IBAN italiano (27 caratteri)</span>
                        </div>
                        @error('iban')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="abi" class="form-label">ABI</label>
                        <input type="text" class="form-control @error('abi') is-invalid @enderror" 
                               id="abi" name="abi" 
                               value="{{ old('abi') }}"
                               placeholder="12345">
                        @error('abi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="cab" class="form-label">CAB</label>
                        <input type="text" class="form-control @error('cab') is-invalid @enderror" 
                               id="cab" name="cab" 
                               value="{{ old('cab') }}"
                               placeholder="67890">
                        @error('cab')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="swift" class="form-label">SWIFT</label>
                        <input type="text" class="form-control @error('swift') is-invalid @enderror" 
                               id="swift" name="swift" 
                               value="{{ old('swift') }}"
                               placeholder="BPMIIT33XXX">
                        @error('swift')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="sia" class="form-label">SIA</label>
                        <input type="text" class="form-control @error('sia') is-invalid @enderror" 
                               id="sia" name="sia" 
                               value="{{ old('sia') }}"
                               placeholder="AB123">
                        @error('sia')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle"></i> Aggiungi Coordinata Bancaria
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
                    <i class="bi bi-exclamation-triangle modal-danger-icon"></i> Conferma Eliminazione
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="bi bi-bank display-4 mb-3 modal-bank-icon"></i>
                    <h6>Stai per eliminare definitivamente:</h6>
                    <div class="alert modal-alert-custom mt-3">
                        <strong id="bankNameToDelete"></strong><br>
                        <small>IBAN che termina con: <span id="ibanLastDigits"></span></small>
                    </div>
                    <p class="text-muted">
                        <i class="bi bi-info-circle"></i> Questa azione non può essere annullata.<br>
                        Il conto bancario verrà rimosso definitivamente dal sistema.
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
let accountToDelete = null;

/**
 * Mostra modal di conferma eliminazione
 * Replica comportamento vecchio gestionale ma con UX moderna
 */
function showDeleteModal(uuid, bankName, ibanLastDigits) {
    accountToDelete = uuid;
    document.getElementById('bankNameToDelete').textContent = bankName;
    document.getElementById('ibanLastDigits').textContent = ibanLastDigits;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

/**
 * Conferma eliminazione definitiva
 */
document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (!accountToDelete) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/configurations/bank-accounts/${accountToDelete}`;
    
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

/**
 * Validazione IBAN real-time
 * Miglioramento UX rispetto al vecchio gestionale
 */
function validateIban(input) {
    const iban = input.value.replace(/\s/g, '').toUpperCase();
    const feedback = document.getElementById('iban-feedback');
    
    // Reset styling
    input.classList.remove('is-valid', 'is-invalid');
    
    if (iban.length === 0) {
        feedback.textContent = 'Inserisci IBAN italiano (27 caratteri)';
        feedback.className = 'text-muted';
        return;
    }
    
    // Validazione formato base italiano
    const italianIbanRegex = /^IT[0-9]{2}[A-Z][0-9]{5}[0-9]{5}[A-Z0-9]{12}$/;
    
    if (iban.length === 27 && italianIbanRegex.test(iban)) {
        input.classList.add('is-valid');
        feedback.innerHTML = '<i class="bi bi-check-circle text-success"></i> IBAN valido';
        feedback.className = 'text-success';
        
        // Formatta IBAN con spazi per readability
        const formatted = iban.replace(/(.{4})/g, '$1 ').trim();
        if (input.value !== formatted) {
            const cursorPos = input.selectionStart;
            input.value = formatted;
            input.setSelectionRange(cursorPos, cursorPos);
        }
    } else if (iban.length > 27) {
        input.classList.add('is-invalid');
        feedback.innerHTML = '<i class="bi bi-x-circle text-danger"></i> IBAN troppo lungo';
        feedback.className = 'text-danger';
    } else if (iban.length < 27) {
        feedback.innerHTML = `<i class="bi bi-clock text-warning"></i> Mancano ${27 - iban.length} caratteri`;
        feedback.className = 'text-warning';
    } else {
        input.classList.add('is-invalid');
        feedback.innerHTML = '<i class="bi bi-x-circle text-danger"></i> Formato IBAN non valido';
        feedback.className = 'text-danger';
    }
}

/**
 * Auto-format input ABI/CAB/SIA
 */
document.addEventListener('DOMContentLoaded', function() {
    const abiInput = document.getElementById('abi');
    const cabInput = document.getElementById('cab');
    const siaInput = document.getElementById('sia');
    
    // ABI e CAB: solo numeri, max 5 caratteri
    [abiInput, cabInput].forEach(input => {
        if (input) {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5);
            });
        }
    });
    
    // SIA: alfanumerico, max 5 caratteri
    if (siaInput) {
        siaInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^A-Z0-9]/g, '').slice(0, 5).toUpperCase();
        });
    }
    
    // Auto-format conto corrente (solo numeri)
    const ccInput = document.getElementById('conto_corrente');
    if (ccInput) {
        ccInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    }
    
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