@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .show-container {
        padding: 2rem;
    }
    
    .show-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .show-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .show-card {
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
    
    .user-avatar-large {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 3rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 10px 30px rgba(2, 157, 126, 0.3);
    }
    
    .user-name {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .user-email {
        font-size: 1.1rem;
        color: #6c757d;
        margin: 0.5rem 0;
    }
    
    .user-role-badge {
        display: inline-block;
        padding: 8px 20px;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 1rem 0;
    }
    
    .role-amministratore { background: #e3f2fd; color: #1976d2; }
    .role-dipendente { background: #f3e5f5; color: #7b1fa2; }
    .role-sola_lettura { background: #f1f8e9; color: #388e3c; }
    .role-responsabile { background: #fff3e0; color: #f57c00; }
    .role-contabile { background: #fce4ec; color: #c2185b; }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .status-active {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .status-inactive {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    
    .dot-active { background: #28a745; }
    .dot-inactive { background: #dc3545; }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    
    .info-item {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1.5rem;
        border-left: 4px solid #9c27b0;
    }
    
    .info-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    
    .info-value {
        font-size: 1.1rem;
        color: #2d3748;
        font-weight: 500;
    }
    
    .empty-value {
        color: #adb5bd;
        font-style: italic;
    }
    
    .btn-action {
        padding: 10px 20px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-edit {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
    }
    
    .btn-edit:hover {
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(2, 157, 126, 0.3);
    }
    
    .btn-permissions {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        cursor: pointer;
        border: none;
    }
    
    .btn-permissions:hover {
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(2, 157, 126, 0.3);
    }
    
    .btn-reset {
        background: linear-gradient(135deg, #f72585, #c5025a);
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        cursor: pointer;
        border: none;
    }
    
    .btn-reset:hover {
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(247, 37, 133, 0.3);
    }
    
    .btn-toggle {
        background: linear-gradient(135deg, #6f42c1, #5a2d91);
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        cursor: pointer;
        border: none;
    }
    
    .btn-toggle:hover {
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(111, 66, 193, 0.3);
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
    
    .permissions-table {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .permissions-table table {
        margin: 0;
    }
    
    .permissions-table th {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        font-weight: 600;
        padding: 1rem;
        border: none;
    }
    
    .permissions-table td {
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }
    
    .permission-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .perm-granted { background: #d4edda; color: #155724; }
    .perm-denied { background: #f8d7da; color: #721c24; }
    
    .alert {
        border-radius: 15px;
        border: none;
        padding: 20px;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
    }
    
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(135deg, #9c27b0, #7b1fa2);
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        margin-left: 1rem;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -1.75rem;
        top: 1.5rem;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #9c27b0;
        border: 3px solid white;
        box-shadow: 0 0 0 3px #f8f9fa;
    }
    
    .timeline-date {
        color: #6c757d;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .timeline-content {
        margin-top: 0.5rem;
    }

    /* Mobile Card Style per Permessi */
    .permissions-mobile-cards {
        display: none;
    }
    
    .permission-mobile-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-left: 5px solid #029D7E;
    }
    
    .permission-mobile-header {
        font-weight: 700;
        color: #029D7E;
        margin-bottom: 1rem;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .permission-mobile-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
        text-align: center;
    }
    
    .permission-mobile-item {
        padding: 0.5rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .perm-mobile-granted {
        background: #d4edda;
        color: #155724;
    }
    
    .perm-mobile-denied {
        background: #f8d7da;
        color: #721c24;
    }
    
    @media (max-width: 768px) {
        .show-container {
            padding: 1rem;
        }
        
        .user-name {
            font-size: 2rem;
        }
        
        .user-avatar-large {
            width: 80px;
            height: 80px;
            font-size: 2rem;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .btn-actions {
            flex-direction: column;
        }
        
        .btn-action {
            margin-bottom: 0.5rem;
            text-align: center;
        }
        
        /* Nascondi tabella su mobile */
        .permissions-table {
            display: none !important;
        }
        
        /* Mostra cards su mobile */
        .permissions-mobile-cards {
            display: block !important;
        }
    }
</style>

<div class="container-fluid show-container">
    <!-- Header -->
    <div class="show-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="show-title">
                    <i class="bi bi-person"></i> Dettagli Utente
                </h1>
                <p class="text-muted mb-0">Informazioni complete e gestione permessi</p>
            </div>
            <div>
                <a href="{{ route('configurations.users.index') }}" class="btn btn-secondary modern-btn me-2">
                    <i class="bi bi-arrow-left"></i> Torna alla Lista
                </a>
                <a href="{{ route('configurations.users.edit', $user) }}" class="btn-action btn-edit modern-btn">
                    <i class="bi bi-pencil"></i> Modifica
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <!-- Informazioni Principali -->
        <div class="col-12">
            <!-- Profilo Utente -->
            <div class="show-card">
                <div class="text-center">
                    <div class="user-avatar-large mx-auto">
                        {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $user->name)[1] ?? '', 0, 1)) }}
                    </div>
                    <h2 class="user-name">{{ $user->name }}</h2>
                    <p class="user-email">{{ $user->email }}</p>
                    
                    <div class="d-flex justify-content-center align-items-center gap-3 mb-4">
                        <span class="user-role-badge role-{{ $user->role }}">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                        
                        <span class="status-badge {{ $user->is_active ? 'status-active' : 'status-inactive' }}">
                            <span class="status-dot {{ $user->is_active ? 'dot-active' : 'dot-inactive' }}"></span>
                            {{ $user->is_active ? 'Attivo' : 'Inattivo' }}
                        </span>
                    </div>
                    
                    <!-- Azioni Rapide integrate -->
                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                        <a href="{{ route('configurations.users.permissions', $user) }}" class="btn-action btn-permissions">
                            <i class="bi bi-shield-check"></i> Gestisci Permessi
                        </a>
                        
                        <form action="{{ route('configurations.users.reset-password', $user) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-action btn-reset" 
                                    onclick="return confirm('Sei sicuro di voler resettare la password?')">
                                <i class="bi bi-key"></i> Reset Password
                            </button>
                        </form>
                        
                        <button type="button" class="btn-action btn-toggle" id="toggle-status"
                                data-user-id="{{ $user->id }}" 
                                data-current-status="{{ $user->is_active ? 'true' : 'false' }}">
                            <i class="bi bi-power"></i> 
                            {{ $user->is_active ? 'Disattiva' : 'Attiva' }}
                        </button>
                        
                        @if(!$user->isAdmin() || \App\Models\User::where('role', 'amministratore')->count() > 1)
                            <form action="{{ route('configurations.users.destroy', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-reset" 
                                        onclick="return confirm('Sei sicuro di voler eliminare questo utente? L\'azione è irreversibile.')">
                                    <i class="bi bi-trash"></i> Elimina
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Informazioni Dettagliate -->
            <div class="show-card">
                <h3 class="section-title">
                    <i class="bi bi-info-circle"></i>
                    Informazioni Dettagliate
                </h3>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Telefono</div>
                        <div class="info-value">
                            {{ $user->phone ?: 'Non specificato' }}
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Reparto/Divisione</div>
                        <div class="info-value">
                            {{ $user->department ?: 'Non specificato' }}
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Azienda</div>
                        <div class="info-value">
                            {{ $user->company ? $user->company->name : 'Nessuna azienda' }}
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Registrato il</div>
                        <div class="info-value">
                            {{ $user->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Ultimo accesso</div>
                        <div class="info-value">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Mai' }}
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">Ultimo aggiornamento</div>
                        <div class="info-value">
                            {{ $user->updated_at->diffForHumans() }}
                        </div>
                    </div>
                </div>

                @if($user->bio)
                    <div class="mt-4">
                        <div class="info-label">Note/Biografia</div>
                        <div class="info-value mt-2" style="background: #f8f9fa; padding: 1rem; border-radius: 10px;">
                            {{ $user->bio }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Permessi Attivi -->
            <div class="show-card">
                <h3 class="section-title">
                    <i class="bi bi-shield-check"></i>
                    Permessi Attivi
                </h3>
                
                @php
                    $currentPermission = $user->activePermissions()->first();
                @endphp
                
                @if($currentPermission)
                    <!-- Tabella Desktop -->
                    <div class="permissions-table">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Modulo</th>
                                    <th>Lettura</th>
                                    <th>Scrittura</th>
                                    <th>Eliminazione</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $modules = $currentPermission->modules ?? [];
                                    $availableModules = [
                                        'magazzino' => 'Gestione Magazzino',
                                        'vendite' => 'Gestione Vendite', 
                                        'fatturazione' => 'Fatturazione',
                                        'anagrafiche' => 'Anagrafiche',
                                        'configurazioni' => 'Configurazioni'
                                    ];
                                @endphp
                                
                                @foreach($availableModules as $moduleKey => $moduleName)
                                    <tr>
                                        <td><strong>{{ $moduleName }}</strong></td>
                                        <td>
                                            <span class="permission-badge {{ isset($modules[$moduleKey]['read']) && $modules[$moduleKey]['read'] ? 'perm-granted' : 'perm-denied' }}">
                                                {{ isset($modules[$moduleKey]['read']) && $modules[$moduleKey]['read'] ? 'Sì' : 'No' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="permission-badge {{ isset($modules[$moduleKey]['write']) && $modules[$moduleKey]['write'] ? 'perm-granted' : 'perm-denied' }}">
                                                {{ isset($modules[$moduleKey]['write']) && $modules[$moduleKey]['write'] ? 'Sì' : 'No' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="permission-badge {{ isset($modules[$moduleKey]['delete']) && $modules[$moduleKey]['delete'] ? 'perm-granted' : 'perm-denied' }}">
                                                {{ isset($modules[$moduleKey]['delete']) && $modules[$moduleKey]['delete'] ? 'Sì' : 'No' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Cards Mobile -->
                    <div class="permissions-mobile-cards">
                        @foreach($availableModules as $moduleKey => $moduleName)
                            <div class="permission-mobile-card">
                                <div class="permission-mobile-header">
                                    <i class="bi bi-{{ $moduleKey === 'magazzino' ? 'boxes' : ($moduleKey === 'vendite' ? 'cart-check' : ($moduleKey === 'fatturazione' ? 'receipt' : ($moduleKey === 'anagrafiche' ? 'people' : 'gear'))) }}"></i>
                                    {{ $moduleName }}
                                </div>
                                
                                <div class="permission-mobile-grid">
                                    <div class="permission-mobile-item {{ isset($modules[$moduleKey]['read']) && $modules[$moduleKey]['read'] ? 'perm-mobile-granted' : 'perm-mobile-denied' }}">
                                        <i class="bi bi-eye"></i><br>
                                        Lettura<br>
                                        <small>{{ isset($modules[$moduleKey]['read']) && $modules[$moduleKey]['read'] ? 'Sì' : 'No' }}</small>
                                    </div>
                                    
                                    <div class="permission-mobile-item {{ isset($modules[$moduleKey]['write']) && $modules[$moduleKey]['write'] ? 'perm-mobile-granted' : 'perm-mobile-denied' }}">
                                        <i class="bi bi-pencil"></i><br>
                                        Scrittura<br>
                                        <small>{{ isset($modules[$moduleKey]['write']) && $modules[$moduleKey]['write'] ? 'Sì' : 'No' }}</small>
                                    </div>
                                    
                                    <div class="permission-mobile-item {{ isset($modules[$moduleKey]['delete']) && $modules[$moduleKey]['delete'] ? 'perm-mobile-granted' : 'perm-mobile-denied' }}">
                                        <i class="bi bi-trash"></i><br>
                                        Eliminazione<br>
                                        <small>{{ isset($modules[$moduleKey]['delete']) && $modules[$moduleKey]['delete'] ? 'Sì' : 'No' }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($currentPermission->special_permissions)
                        <div class="mt-4">
                            <h5><i class="bi bi-star"></i> Permessi Speciali</h5>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                @php
                                    $specialLabels = [
                                        'can_manage_users' => 'Gestione Utenti',
                                        'can_view_reports' => 'Visualizzazione Report',
                                        'can_delete_invoices' => 'Eliminazione Fatture',
                                        'can_modify_prices' => 'Modifica Prezzi',
                                        'can_access_sensitive_data' => 'Accesso Dati Sensibili',
                                        'can_export_data' => 'Esportazione Dati'
                                    ];
                                @endphp
                                @foreach($currentPermission->special_permissions as $perm => $granted)
                                    @if($granted)
                                        <span class="permission-badge perm-granted">
                                            {{ $specialLabels[$perm] ?? $perm }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    @if($currentPermission->notes)
                        <div class="mt-4">
                            <h6>Note sui Permessi</h6>
                            <div class="alert alert-info">
                                {{ $currentPermission->notes }}
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-shield-x" style="font-size: 3rem; color: #dee2e6;"></i>
                        <h5 class="text-muted mt-3">Nessun permesso assegnato</h5>
                        <p class="text-muted">L'utente non ha permessi attivi configurati</p>
                    </div>
                @endif
            </div>
            
            <!-- Storico Permessi -->
            @if($permissionHistory->count() > 0)
                <div class="show-card">
                    <h3 class="section-title">
                        <i class="bi bi-clock-history"></i>
                        Storico Permessi
                    </h3>
                    
                    <div class="timeline">
                        @foreach($permissionHistory->take(5) as $permission)
                            <div class="timeline-item">
                                <div class="timeline-date">
                                    {{ $permission->created_at->format('d/m/Y H:i') }}
                                </div>
                                <div class="timeline-content">
                                    <strong>
                                        {{ $permission->is_active ? 'Permessi Attivati' : 'Permessi Disattivati' }}
                                    </strong>
                                    @if($permission->notes)
                                        <p class="text-muted small mb-0 mt-1">{{ $permission->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Status
    const toggleBtn = document.getElementById('toggle-status');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const currentStatus = this.dataset.currentStatus === 'true';
            
            fetch(`/configurations/users/${userId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Errore durante l\'operazione');
                }
            })
            .catch(error => {
                console.error('Errore:', error);
                alert('Errore durante l\'operazione');
            });
        });
    }
});
</script>
@endsection