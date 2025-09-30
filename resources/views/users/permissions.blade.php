@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .permissions-container {
        padding: 2rem;
    }
    
    .permissions-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .permissions-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .permissions-card {
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
    
    .user-preview {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        text-align: center;
    }
    
    .user-avatar-preview {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.5rem;
        margin: 0 auto 1rem;
        box-shadow: 0 5px 15px rgba(2, 157, 126, 0.3);
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
    
    .module-card {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .module-card:hover {
        border-color: #029D7E;
        box-shadow: 0 5px 15px rgba(2, 157, 126, 0.1);
    }
    
    .module-name {
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }
    
    .permissions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
    }
    
    .permission-item {
        background: white;
        padding: 1rem;
        border-radius: 10px;
        text-align: center;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .permission-item.active {
        border-color: #029D7E;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
    }
    
    .form-check {
        margin: 1rem 0;
    }
    
    .form-check-input:checked {
        background-color: #029D7E;
        border-color: #029D7E;
    }
    
    .form-check-input:focus {
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
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
    
    .special-permissions {
        background: linear-gradient(135deg, #fff3e0, #ffe0b2);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .special-permissions h5 {
        color: #ef6c00;
        margin-bottom: 1rem;
    }
    
    .date-restrictions {
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .date-restrictions h5 {
        color: #1976d2;
        margin-bottom: 1rem;
    }
    
    @media (max-width: 768px) {
        .permissions-container {
            padding: 1rem;
        }
        
        .permissions-title {
            font-size: 1.5rem;
        }
        
        .permissions-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-fluid permissions-container">
    <!-- Header -->
    <div class="permissions-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="permissions-title">
                    <i class="bi bi-shield-check"></i> Gestione Permessi
                </h1>
                <p class="text-muted mb-0">Configura i permessi dettagliati per l'utente</p>
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
            <form action="{{ route('configurations.users.permissions.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Permessi Moduli -->
                <div class="permissions-card">
                    <h3 class="section-title">
                        <i class="bi bi-grid-3x3-gap"></i>
                        Permessi per Moduli
                    </h3>
                    
                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle"></i> Come funziona</h6>
                        <p class="mb-0">Per ogni modulo puoi assegnare tre tipi di permessi: <strong>Lettura</strong> (visualizzare dati), <strong>Scrittura</strong> (creare/modificare), <strong>Eliminazione</strong> (cancellare dati).</p>
                    </div>
                    
                    @foreach($availableModules as $moduleKey => $moduleName)
                        <div class="module-card">
                            <div class="module-name">
                                <i class="bi bi-{{ $moduleKey === 'magazzino' ? 'boxes' : ($moduleKey === 'vendite' ? 'cart-check' : ($moduleKey === 'fatturazione' ? 'receipt' : ($moduleKey === 'anagrafiche' ? 'people' : 'gear'))) }}"></i>
                                {{ $moduleName }}
                            </div>
                            
                            <div class="permissions-grid">
                                <div class="permission-item {{ ($currentPermission && isset($currentPermission->modules[$moduleKey]['read']) && $currentPermission->modules[$moduleKey]['read']) ? 'active' : '' }}">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="modules[{{ $moduleKey }}][read]" 
                                               id="{{ $moduleKey }}_read"
                                               value="1"
                                               {{ ($currentPermission && isset($currentPermission->modules[$moduleKey]['read']) && $currentPermission->modules[$moduleKey]['read']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $moduleKey }}_read">
                                            <i class="bi bi-eye"></i><br>
                                            <strong>Lettura</strong>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="permission-item {{ ($currentPermission && isset($currentPermission->modules[$moduleKey]['write']) && $currentPermission->modules[$moduleKey]['write']) ? 'active' : '' }}">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="modules[{{ $moduleKey }}][write]" 
                                               id="{{ $moduleKey }}_write"
                                               value="1"
                                               {{ ($currentPermission && isset($currentPermission->modules[$moduleKey]['write']) && $currentPermission->modules[$moduleKey]['write']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $moduleKey }}_write">
                                            <i class="bi bi-pencil"></i><br>
                                            <strong>Scrittura</strong>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="permission-item {{ ($currentPermission && isset($currentPermission->modules[$moduleKey]['delete']) && $currentPermission->modules[$moduleKey]['delete']) ? 'active' : '' }}">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="modules[{{ $moduleKey }}][delete]" 
                                               id="{{ $moduleKey }}_delete"
                                               value="1"
                                               {{ ($currentPermission && isset($currentPermission->modules[$moduleKey]['delete']) && $currentPermission->modules[$moduleKey]['delete']) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $moduleKey }}_delete">
                                            <i class="bi bi-trash"></i><br>
                                            <strong>Eliminazione</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Permessi Speciali -->
                <div class="permissions-card">
                    <h3 class="section-title">
                        <i class="bi bi-star"></i>
                        Permessi Speciali
                    </h3>
                    
                    <div class="special-permissions">
                        <h5><i class="bi bi-lightning"></i> Accessi Privilegiati</h5>
                        <div class="row">
                            @foreach($specialPermissions as $permKey => $permName)
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="special_permissions[{{ $permKey }}]" 
                                               id="{{ $permKey }}"
                                               value="1"
                                               {{ ($currentPermission && isset($currentPermission->special_permissions[$permKey]) && $currentPermission->special_permissions[$permKey]) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $permKey }}">
                                            <strong>{{ $permName }}</strong>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Restrizioni Temporali -->
                <div class="permissions-card">
                    <h3 class="section-title">
                        <i class="bi bi-clock"></i>
                        Restrizioni Temporali
                    </h3>
                    
                    <div class="date-restrictions">
                        <h5><i class="bi bi-calendar-range"></i> Validità Permessi</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="valid_from" class="form-label">Valido da</label>
                                    <input type="datetime-local" class="form-control @error('valid_from') is-invalid @enderror" 
                                           id="valid_from" name="valid_from" 
                                           value="{{ old('valid_from', ($currentPermission && $currentPermission->valid_from) ? $currentPermission->valid_from->format('Y-m-d\TH:i') : '') }}">
                                    @error('valid_from')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Lascia vuoto per validità immediata</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="valid_until" class="form-label">Valido fino a</label>
                                    <input type="datetime-local" class="form-control @error('valid_until') is-invalid @enderror" 
                                           id="valid_until" name="valid_until" 
                                           value="{{ old('valid_until', ($currentPermission && $currentPermission->valid_until) ? $currentPermission->valid_until->format('Y-m-d\TH:i') : '') }}">
                                    @error('valid_until')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Lascia vuoto per validità illimitata</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Note -->
                <div class="permissions-card">
                    <h3 class="section-title">
                        <i class="bi bi-sticky"></i>
                        Note e Motivazioni
                    </h3>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Note sui permessi</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                id="notes" name="notes" rows="4" 
                                placeholder="Motivazioni per l'assegnazione di questi permessi, note particolari...">{{ old('notes', ($currentPermission->notes ?? '')) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Documentazione interna sui permessi assegnati</div>
                    </div>
                </div>

                <!-- Pulsanti Azione -->
                <div class="permissions-card text-center">
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('configurations.users.show', $user) }}" class="btn btn-secondary modern-btn btn-lg">
                            <i class="bi bi-x-lg"></i> Annulla
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-shield-check"></i> Salva Permessi
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Preview Utente -->
            <div class="permissions-card">
                <h3 class="section-title">
                    <i class="bi bi-person-badge"></i>
                    Utente
                </h3>
                
                <div class="user-preview">
                    <div class="user-avatar-preview">
                        {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $user->name)[1] ?? '', 0, 1)) }}
                    </div>
                    <h5>{{ $user->name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                    <span class="current-role role-{{ $user->role }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>

            <!-- Permessi Attuali -->
            @if($currentPermission)
            <div class="permissions-card">
                <h3 class="section-title">
                    <i class="bi bi-shield-fill-check"></i>
                    Stato Attuale
                </h3>
                
                <div class="alert alert-success">
                    <h6><i class="bi bi-check-circle"></i> Permessi Attivi</h6>
                    <p class="mb-1">Configurazione dal: {{ $currentPermission->created_at->format('d/m/Y H:i') }}</p>
                    @if($currentPermission->valid_until)
                        <p class="mb-0">Scadenza: {{ $currentPermission->valid_until->format('d/m/Y H:i') }}</p>
                    @else
                        <p class="mb-0">Validità: Illimitata</p>
                    @endif
                </div>

                @if($currentPermission->notes)
                    <div class="alert alert-info">
                        <h6><i class="bi bi-sticky"></i> Note</h6>
                        <p class="mb-0">{{ $currentPermission->notes }}</p>
                    </div>
                @endif
            </div>
            @else
            <div class="permissions-card">
                <h3 class="section-title">
                    <i class="bi bi-shield-exclamation"></i>
                    Attenzione
                </h3>
                
                <div class="alert alert-warning">
                    <h6><i class="bi bi-exclamation-triangle"></i> Nessun Permesso</h6>
                    <p class="mb-0">Questo utente non ha permessi specifici configurati. Sarà attivo solo il ruolo base.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animazione delle checkbox
    const checkboxes = document.querySelectorAll('.form-check-input');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const permissionItem = this.closest('.permission-item');
            if (this.checked) {
                permissionItem.classList.add('active');
            } else {
                permissionItem.classList.remove('active');
            }
        });
    });
    
    // Animazione cards
    const cards = document.querySelectorAll('.module-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.4s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        }, index * 100);
    });
});
</script>
@endsection