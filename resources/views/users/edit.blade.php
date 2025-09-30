@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .edit-container {
        padding: 2rem;
    }
    
    .edit-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .edit-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .edit-card {
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
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
        background: white;
    }
    
    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
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
    
    .btn-outline-secondary {
        border-radius: 15px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid #6c757d;
    }
    
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
    
    .modern-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    .modern-btn.btn-secondary,
    .btn-secondary.modern-btn {
        background: linear-gradient(135deg, #6c757d, #545b62);
        color: white;
        border: none;
    }
    
    .alert {
        border-radius: 15px;
        border: none;
        padding: 20px;
    }
    
    .user-preview {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    
    .user-avatar-preview {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #9c27b0, #7b1fa2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 2rem;
        margin: 0 auto 1rem;
        box-shadow: 0 5px 15px rgba(156, 39, 176, 0.3);
    }
    
    .current-role {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 0.5rem;
    }
    
    .role-admin { background: #e3f2fd; color: #1976d2; }
    .role-employee { background: #f3e5f5; color: #7b1fa2; }
    .role-readonly { background: #f1f8e9; color: #388e3c; }
    .role-manager { background: #fff3e0; color: #f57c00; }
    .role-accountant { background: #fce4ec; color: #c2185b; }
    
    .form-text {
        color: #6c757d;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }
    
    .info-box {
        background: #e3f2fd;
        border: 1px solid #2196f3;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    .info-box h6 {
        color: #1976d2;
        margin-bottom: 0.5rem;
    }
    
    .info-box p {
        margin: 0;
        color: #455a64;
        font-size: 0.9rem;
    }
</style>

<div class="container-fluid edit-container">
    <!-- Header -->
    <div class="edit-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="edit-title">
                    <i class="bi bi-person-gear"></i> Modifica Utente
                </h1>
                <p class="text-muted mb-0">Aggiorna le informazioni e il ruolo dell'utente</p>
            </div>
            <div>
                <a href="{{ route('configurations.users.show', $user) }}" class="btn btn-secondary modern-btn me-2">
                    <i class="bi bi-arrow-left"></i> Torna ai Dettagli
                </a>
                <a href="{{ route('configurations.users.index') }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-list"></i> Lista Utenti
                </a>
            </div>
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

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('configurations.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Dati Anagrafici -->
                <div class="edit-card">
                    <h3 class="section-title">
                        <i class="bi bi-person-badge"></i>
                        Dati Anagrafici
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome Completo *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required
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
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required
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
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
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
                                       id="department" name="department" value="{{ old('department', $user->department) }}"
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
                                                {{ old('company_id', $user->company_id) == $company->id ? 'selected' : '' }}>
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
                                        placeholder="Informazioni aggiuntive sull'utente, ruolo specifico, responsabilità...">{{ old('bio', $user->bio) }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ruolo -->
                <div class="edit-card">
                    <h3 class="section-title">
                        <i class="bi bi-shield-check"></i>
                        Ruolo e Accessi
                    </h3>
                    
                    <div class="info-box">
                        <h6><i class="bi bi-info-circle"></i> Importante</h6>
                        <p>La modifica del ruolo non aggiorna automaticamente i permessi. Dopo aver salvato, usa la sezione "Gestisci Permessi" per configurare i permessi specifici.</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Ruolo *</label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" name="role" required>
                                    <option value="">Seleziona ruolo...</option>
                                    @foreach($roles as $roleKey => $roleLabel)
                                        <option value="{{ $roleKey }}" 
                                                {{ old('role', $user->role) == $roleKey ? 'selected' : '' }}>
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
                    <div id="role-info" class="role-info" style="display: none; background: #f8f9fa; border-radius: 10px; padding: 1rem; border-left: 4px solid #029D7E;">
                        <h6 id="role-title" style="color: #029D7E; font-weight: 600; margin-bottom: 0.5rem;"></h6>
                        <ul id="role-permissions" style="margin: 0; padding-left: 1.2rem;"></ul>
                    </div>
                </div>

                <!-- Pulsanti Azione -->
                <div class="edit-card text-center">
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('configurations.users.show', $user) }}" class="btn btn-secondary modern-btn btn-lg">
                            <i class="bi bi-x-lg"></i> Annulla
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-check-lg"></i> Salva Modifiche
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Preview Utente -->
            <div class="edit-card">
                <h3 class="section-title">
                    <i class="bi bi-eye"></i>
                    Anteprima Utente
                </h3>
                
                <div class="user-preview">
                    <div class="user-avatar-preview" id="avatar-preview">
                        {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $user->name)[1] ?? '', 0, 1)) }}
                    </div>
                    <h5 id="name-preview">{{ $user->name }}</h5>
                    <p class="text-muted" id="email-preview">{{ $user->email }}</p>
                    <span class="current-role role-{{ $user->role }}" id="role-preview">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>

            <!-- Informazioni Account -->
            <div class="edit-card">
                <h3 class="section-title">
                    <i class="bi bi-info-circle"></i>
                    Informazioni Account
                </h3>
                
                <div class="info-grid" style="display: grid; grid-template-columns: 1fr; gap: 1rem;">
                    <div style="background: #f8f9fa; border-radius: 10px; padding: 1rem;">
                        <small style="color: #6c757d; font-weight: 600; text-transform: uppercase;">Registrato il</small>
                        <div style="font-weight: 500; color: #2d3748;">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    
                    <div style="background: #f8f9fa; border-radius: 10px; padding: 1rem;">
                        <small style="color: #6c757d; font-weight: 600; text-transform: uppercase;">Ultimo accesso</small>
                        <div style="font-weight: 500; color: #2d3748;">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Mai' }}
                        </div>
                    </div>
                    
                    <div style="background: #f8f9fa; border-radius: 10px; padding: 1rem;">
                        <small style="color: #6c757d; font-weight: 600; text-transform: uppercase;">Status</small>
                        <div style="font-weight: 500; color: {{ $user->is_active ? '#28a745' : '#dc3545' }};">
                            {{ $user->is_active ? 'Attivo' : 'Inattivo' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Azioni Rapide -->
            <div class="edit-card">
                <h3 class="section-title">
                    <i class="bi bi-lightning"></i>
                    Azioni Post-Salvataggio
                </h3>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('configurations.users.permissions', $user) }}" class="btn btn-outline-warning">
                        <i class="bi bi-shield-check"></i> Gestisci Permessi Dettagliati
                    </a>
                    
                    <form action="{{ route('configurations.users.reset-password', $user) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger w-100" 
                                onclick="return confirm('Sei sicuro di voler resettare la password?')">
                            <i class="bi bi-key"></i> Reset Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const roleSelect = document.getElementById('role');
    
    const namePreview = document.getElementById('name-preview');
    const emailPreview = document.getElementById('email-preview');
    const rolePreview = document.getElementById('role-preview');
    const avatarPreview = document.getElementById('avatar-preview');
    
    const roleInfo = document.getElementById('role-info');
    const roleTitle = document.getElementById('role-title');
    const rolePermissions = document.getElementById('role-permissions');

    // Aggiorna preview in tempo reale
    nameInput.addEventListener('input', function() {
        namePreview.textContent = this.value || 'Nome Utente';
        updateAvatar(this.value);
    });

    emailInput.addEventListener('input', function() {
        emailPreview.textContent = this.value || 'email@esempio.it';
    });

    function updateAvatar(name) {
        if (name) {
            const words = name.split(' ');
            const initials = words.length > 1 
                ? (words[0][0] + words[1][0]).toUpperCase()
                : words[0][0].toUpperCase();
            avatarPreview.textContent = initials;
        } else {
            avatarPreview.textContent = 'UU';
        }
    }

    // Gestione info ruoli
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
        
        // Aggiorna preview ruolo
        if (selectedRole) {
            rolePreview.textContent = this.options[this.selectedIndex].text;
            rolePreview.className = `current-role role-${selectedRole}`;
        }
        
        // Mostra info ruolo
        if (selectedRole && roleDescriptions[selectedRole]) {
            const info = roleDescriptions[selectedRole];
            roleTitle.textContent = info.title;
            rolePermissions.innerHTML = info.permissions
                .map(perm => `<li style="color: #6c757d; font-size: 0.85rem;">${perm}</li>`)
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