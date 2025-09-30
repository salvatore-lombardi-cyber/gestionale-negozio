@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .create-container {
        padding: 2rem;
    }
    
    .create-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .create-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .create-card {
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
        color: #2d3748;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-control, .form-select {
        border-radius: 15px;
        padding: 15px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.8);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #9c27b0;
        box-shadow: 0 0 0 0.2rem rgba(156, 39, 176, 0.25);
        background: white;
    }
    
    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        border: none;
        border-radius: 15px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(156, 39, 176, 0.3);
    }
    
    .btn-back {
        background: linear-gradient(135deg, #6c757d, #495057);
        border: none;
        border-radius: 15px;
        padding: 12px 30px;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .btn-back:hover {
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
    
    .form-text {
        color: #6c757d;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }
    
    .role-info {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1rem;
        margin-top: 0.5rem;
        border-left: 4px solid #9c27b0;
    }
    
    .role-info h6 {
        color: #9c27b0;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .role-info ul {
        margin: 0;
        padding-left: 1.2rem;
    }
    
    .role-info li {
        color: #6c757d;
        font-size: 0.85rem;
    }

    .form-check {
        margin: 1rem 0;
    }
    
    .form-check-input:checked {
        background-color: #9c27b0;
        border-color: #9c27b0;
    }
    
    .form-check-input:focus {
        box-shadow: 0 0 0 0.2rem rgba(156, 39, 176, 0.25);
    }
    
    .password-info {
        background: linear-gradient(135deg, #e3f2fd, #f3e5f5);
        border: 1px solid #9c27b0;
        border-radius: 15px;
        padding: 1.5rem;
        margin-top: 1rem;
    }
    
    .password-info h6 {
        color: #9c27b0;
        margin-bottom: 0.5rem;
    }
</style>

<div class="container-fluid create-container">
    <!-- Header -->
    <div class="create-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="create-title">
                    <i class="bi bi-person-plus"></i> Nuovo Utente
                </h1>
                <p class="text-muted mb-0">Crea un nuovo utente e assegna ruolo e permessi</p>
            </div>
            <a href="{{ route('configurations.users.index') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i> Torna alla Lista
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <h6><i class="bi bi-exclamation-triangle"></i> Errori di validazione:</h6>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('configurations.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Dati Anagrafici -->
        <div class="create-card">
            <h3 class="section-title">
                <i class="bi bi-person-badge"></i>
                Dati Anagrafici
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Completo *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name') }}" required
                               placeholder="Mario Rossi">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Nome e cognome dell'utente</div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}" required
                               placeholder="mario.rossi@esempio.it">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Email per login e comunicazioni</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefono</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" name="phone" value="{{ old('phone') }}"
                               placeholder="+39 333 123 4567">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="department" class="form-label">Reparto/Divisione</label>
                        <input type="text" class="form-control @error('department') is-invalid @enderror" 
                               id="department" name="department" value="{{ old('department') }}"
                               placeholder="Vendite, Amministrazione, IT...">
                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="company_id" class="form-label">Azienda</label>
                        <select class="form-select @error('company_id') is-invalid @enderror" 
                                id="company_id" name="company_id">
                            <option value="">Seleziona azienda...</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" 
                                        {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Azienda di appartenenza (per multi-tenancy)</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="bio" class="form-label">Note/Biografia</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" 
                                id="bio" name="bio" rows="3" 
                                placeholder="Informazioni aggiuntive sull'utente, ruolo specifico, responsabilità...">{{ old('bio') }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="avatar" class="form-label">Avatar (opzionale)</label>
                        <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                               id="avatar" name="avatar" accept="image/*">
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Formati supportati: JPG, PNG, GIF (max 2MB)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ruolo e Permessi -->
        <div class="create-card">
            <h3 class="section-title">
                <i class="bi bi-shield-check"></i>
                Ruolo e Permessi
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="role" class="form-label">Ruolo *</label>
                        <select class="form-select @error('role') is-invalid @enderror" 
                                id="role" name="role" required>
                            <option value="">Seleziona ruolo...</option>
                            @foreach($roles as $roleKey => $roleLabel)
                                <option value="{{ $roleKey }}" 
                                        {{ old('role') == $roleKey ? 'selected' : '' }}>
                                    {{ $roleLabel }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Info sui ruoli -->
            <div id="role-info" class="role-info" style="display: none;">
                <h6 id="role-title"></h6>
                <ul id="role-permissions"></ul>
            </div>
        </div>

        <!-- Password e Configurazioni -->
        <div class="create-card">
            <h3 class="section-title">
                <i class="bi bi-key"></i>
                Password e Configurazioni
            </h3>
            
            <div class="password-info">
                <h6><i class="bi bi-info-circle"></i> Generazione Password</h6>
                <p class="mb-0">
                    Verrà generata automaticamente una password temporanea sicura di 12 caratteri.
                    L'utente riceverà le credenziali e dovrà cambiarla al primo accesso.
                </p>
            </div>

            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" id="send_credentials" name="send_credentials" value="1">
                <label class="form-check-label" for="send_credentials">
                    <strong>Invia credenziali via email</strong>
                    <div class="form-text">Invia automaticamente email di benvenuto con username e password temporanea</div>
                </label>
            </div>
        </div>

        <!-- Pulsanti Azione -->
        <div class="create-card text-center">
            <div class="d-flex justify-content-center gap-3">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-person-plus"></i> Crea Utente
                </button>
                <a href="{{ route('configurations.users.index') }}" class="btn-back btn-lg">
                    <i class="bi bi-x-lg"></i> Annulla
                </a>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const roleInfo = document.getElementById('role-info');
    const roleTitle = document.getElementById('role-title');
    const rolePermissions = document.getElementById('role-permissions');

    const roleDescriptions = {
        admin: {
            title: 'Amministratore',
            permissions: [
                'Accesso completo a tutti i moduli',
                'Gestione utenti e permessi',
                'Configurazioni di sistema',
                'Visualizzazione report avanzati',
                'Eliminazione e modifica dati critici'
            ]
        },
        employee: {
            title: 'Dipendente',
            permissions: [
                'Gestione magazzino (lettura/scrittura)',
                'Gestione vendite (lettura/scrittura)',
                'Visualizzazione fatture (solo lettura)',
                'Accesso anagrafiche clienti/fornitori'
            ]
        },
        readonly: {
            title: 'Solo Lettura',
            permissions: [
                'Visualizzazione tutti i moduli',
                'Nessuna modifica consentita',
                'Accesso ai report base',
                'Ideale per consulenti o auditor'
            ]
        },
        manager: {
            title: 'Manager',
            permissions: [
                'Accesso completo magazzino e vendite',
                'Gestione anagrafiche',
                'Visualizzazione report avanzati',
                'Modifica prezzi e sconti',
                'Nessun accesso alle configurazioni'
            ]
        },
        accountant: {
            title: 'Contabile',
            permissions: [
                'Accesso completo fatturazione',
                'Gestione pagamenti e scadenze',
                'Report fiscali e contabili',
                'Visualizzazione dati vendite',
                'Accesso limitato al magazzino'
            ]
        }
    };

    roleSelect.addEventListener('change', function() {
        const selectedRole = this.value;
        
        if (selectedRole && roleDescriptions[selectedRole]) {
            const info = roleDescriptions[selectedRole];
            roleTitle.textContent = info.title;
            rolePermissions.innerHTML = info.permissions
                .map(perm => `<li>${perm}</li>`)
                .join('');
            roleInfo.style.display = 'block';
        } else {
            roleInfo.style.display = 'none';
        }
    });

    // Trigger change event se c'è un valore preselezionato
    if (roleSelect.value) {
        roleSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection