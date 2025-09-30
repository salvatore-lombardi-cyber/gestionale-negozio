@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .users-container {
        padding: 2rem;
        min-height: calc(100vh - 70px);
    }
    
    .users-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .users-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .users-subtitle {
        color: #6c757d;
        font-size: 1.1rem;
        margin-top: 0.5rem;
    }
    
    
    /* Filters Card */
    .filters-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    /* Users Table */
    .users-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    
    /* User Cards Grid */
    .users-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.5rem;
        margin-top: 2rem;
    }
    
    .user-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        text-decoration: none;
        color: #2d3748;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: 200px;
    }
    
    .user-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        border-radius: 20px 20px 0 0;
    }
    
    .user-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
        text-decoration: none;
        color: #2d3748;
    }
    
    /* Avatar Centrale Stile Gestionale */
    .user-avatar-container {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #ffd60a, #ff8500);
    }
    
    .user-avatar-container i {
        font-size: 2.5rem;
        color: white;
    }
    
    .user-avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 20px;
    }
    
    /* Testi delle Card */
    .user-name {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        text-align: center;
        color: #2d3748;
        line-height: 1.3;
    }
    
    .user-description {
        font-size: 0.9rem;
        text-align: center;
        line-height: 1.4;
        color: #718096;
        margin-bottom: 1rem;
    }
    
    .user-role {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
    }
    
    .role-amministratore { background: #e3f2fd; color: #1976d2; }
    .role-dipendente { background: #f3e5f5; color: #7b1fa2; }
    .role-sola_lettura { background: #f1f8e9; color: #388e3c; }
    .role-responsabile { background: #fff3e0; color: #f57c00; }
    .role-contabile { background: #fce4ec; color: #c2185b; }
    
    .user-meta {
        display: flex;
        justify-content: between;
        align-items: center;
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 1rem;
    }
    
    .user-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }
    
    .status-active { background: #4caf50; }
    .status-inactive { background: #f44336; }
    
    .user-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }
    
    .btn-sm {
        padding: 6px 12px;
        font-size: 0.8rem;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .btn-outline-primary {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
        border: none;
    }
    
    .btn-outline-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 119, 182, 0.3);
        color: white;
    }
    
    .btn-outline-secondary {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
        border: none;
    }
    
    .btn-outline-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 133, 0, 0.3);
        color: white;
    }
    
    .btn-outline-warning {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        border: none;
    }
    
    .btn-outline-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(2, 157, 126, 0.3);
        color: white;
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
    
    .modern-btn.btn-success,
    .btn-success.modern-btn {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        border: none;
    }
    
    .users-list-title {
        color: #029D7E;
    }
    
    .users-list-icon {
        color: #029D7E;
    }
    
    .users-count-badge {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        font-size: 0.9rem;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 15px;
        min-width: 35px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }
    
    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 12px;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #9c27b0;
        box-shadow: 0 0 0 0.2rem rgba(156, 39, 176, 0.25);
    }
    
    .btn-filter {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        border: none;
        color: white;
        padding: 12px 20px;
        border-radius: 10px;
        font-weight: 600;
    }
    
    .btn-filter:hover {
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 5px 15px rgba(2, 157, 126, 0.3);
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .users-container {
            padding: 1rem;
        }
        
        .users-title {
            font-size: 2rem;
        }
        
        
        .users-grid {
            grid-template-columns: 1fr;
        }
        
        .filters-card .row {
            flex-direction: column;
        }
        
        .filters-card .col-md-3,
        .filters-card .col-md-2 {
            margin-bottom: 1rem;
        }
    }
</style>

<div class="users-container">
    <!-- Header -->
    <div class="users-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="users-title">
                    <i class="bi bi-people"></i> Gestione Utenti
                </h1>
                <p class="users-subtitle">Sistema multi-utente enterprise con controllo permessi granulare</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('configurations.index') }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-arrow-left"></i> Torna alle Configurazioni
                </a>
                <a href="{{ route('configurations.users.create') }}" class="btn btn-success modern-btn">
                    <i class="bi bi-person-plus"></i> Nuovo Utente
                </a>
            </div>
        </div>
    </div>


    <!-- Filters -->
    <div class="filters-card">
        <form method="GET" action="{{ route('configurations.users.index') }}">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Cerca</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Nome, email, reparto..." value="{{ $search }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Ruolo</label>
                    <select name="role" class="form-select">
                        <option value="">Tutti i ruoli</option>
                        <option value="amministratore" {{ $role == 'amministratore' ? 'selected' : '' }}>Amministratore</option>
                        <option value="dipendente" {{ $role == 'dipendente' ? 'selected' : '' }}>Dipendente</option>
                        <option value="sola_lettura" {{ $role == 'sola_lettura' ? 'selected' : '' }}>Sola Lettura</option>
                        <option value="responsabile" {{ $role == 'responsabile' ? 'selected' : '' }}>Responsabile</option>
                        <option value="contabile" {{ $role == 'contabile' ? 'selected' : '' }}>Contabile</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Tutti</option>
                        <option value="1" {{ $status == '1' ? 'selected' : '' }}>Attivi</option>
                        <option value="0" {{ $status == '0' ? 'selected' : '' }}>Inattivi</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Azienda</label>
                    <select name="company_id" class="form-select">
                        <option value="">Tutte le aziende</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ $company_id == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-filter">
                            <i class="bi bi-funnel"></i> Filtra
                        </button>
                        <a href="{{ route('configurations.users.index') }}" class="btn btn-secondary modern-btn">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Titolo Lista Utenti -->
    <div class="users-card mb-3">
        <h4 class="mb-0 d-flex align-items-center gap-2 users-list-title">
            <i class="bi bi-people-fill users-list-icon"></i> 
            Lista Utenti 
            <span class="badge users-count-badge">
                {{ $users->total() }}
            </span>
        </h4>
    </div>

    <!-- Users Cards direttamente sul verde -->
    @if($users->count() > 0)
        <div class="users-grid">
            @foreach($users as $user)
                <div class="user-card">
                    <!-- Avatar Centrale -->
                    <div class="user-avatar-container">
                        @if($user->avatar && file_exists(public_path('storage/'.$user->avatar)))
                            <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->name }}" class="user-avatar-img">
                        @else
                            <i class="bi bi-person-fill"></i>
                        @endif
                    </div>
                    
                    <!-- Nome Utente -->
                    <h3 class="user-name">{{ $user->name }}</h3>
                    
                    <!-- Email come descrizione -->
                    <p class="user-description">
                        {{ $user->email }}
                        @if($user->department)
                            <br><small>{{ $user->department }}</small>
                        @endif
                    </p>
                    
                    <!-- Badge Ruolo -->
                    <div class="text-center mb-3">
                        <span class="user-role role-{{ $user->role }}">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </div>
                    
                    <!-- Azioni utente -->
                    <div class="user-actions">
                        <a href="{{ route('configurations.users.show', $user) }}" 
                           class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye"></i> Dettagli
                        </a>
                        <a href="{{ route('configurations.users.edit', $user) }}" 
                           class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-pencil"></i> Modifica
                        </a>
                        <a href="{{ route('configurations.users.permissions', $user) }}" 
                           class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-shield-check"></i> Permessi
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4 d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-people"></i>
            <h4>Nessun utente trovato</h4>
            <p>Non ci sono utenti che corrispondono ai criteri di ricerca.</p>
            <a href="{{ route('configurations.users.create') }}" class="btn btn-success modern-btn">
                <i class="bi bi-plus-lg"></i> Crea il primo utente
            </a>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animazione cards
    const cards = document.querySelectorAll('.user-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.4s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        }, index * 50);
    });
});
</script>
@endsection