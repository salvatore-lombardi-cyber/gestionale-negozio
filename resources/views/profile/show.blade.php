@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .management-container {
        padding: 2rem;
        min-height: calc(100vh - 70px);
    }
    
    /* Header con pulsanti */
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
    
    .modern-btn:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(2, 157, 126, 0.3);
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
    
    .modern-btn.btn-primary,
    .btn-primary.modern-btn {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        border: none;
    }
    
    /* Card moderne */
    .modern-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 2rem;
        height: 100%; /* Altezza uguale per entrambe le card */
        display: flex;
        flex-direction: column;
    }
    
    .modern-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }
    
    /* Assicura che le colonne abbiano la stessa altezza */
    .profile-row {
        display: flex;
        align-items: stretch;
    }
    
    .profile-row > [class*="col-"] {
        display: flex;
        flex-direction: column;
    }
    
    /* Il contenuto della card si espande per riempire lo spazio */
    .modern-card .profile-content {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .profile-icon {
        font-size: 4rem;
        color: #029D7E;
        margin-bottom: 1rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .management-container {
            padding: 1rem;
        }
        
        .management-header {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .management-title {
            font-size: 1.8rem;
        }
        
        .modern-btn {
            padding: 10px 16px;
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 576px) {
        .management-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .modern-btn {
            padding: 10px 16px;
            font-size: 0.9rem;
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="management-container">
    <!-- Header -->
    <div class="management-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <h1 class="management-title">
                    <i class="bi bi-person-circle me-3" style="color: #029D7E; font-size: 2rem;"></i>
                    Il Mio Profilo
                </h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-arrow-left"></i> Torna alla Dashboard
                </a>
            </div>
        </div>
        
        <p class="mt-3 mb-0 text-muted">
            Gestisci le tue informazioni personali e le impostazioni dell'account
        </p>
    </div>
    
    <!-- Sezioni Profilo -->
    <div class="row profile-row">
        <!-- Informazioni Utente -->
        <div class="col-lg-4">
            <div class="modern-card">
                <div class="p-4 text-center profile-content">
                    <div class="profile-icon">
                        @if(Auth::user()->avatar)
                            <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="Avatar" 
                                 style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #029D7E;">
                        @else
                            <i class="bi bi-person-circle"></i>
                        @endif
                    </div>
                    <h4 class="mb-2" style="color: #2d3748; font-weight: 700;">{{ Auth::user()->name }}</h4>
                    <p class="text-muted mb-2">{{ Auth::user()->email }}</p>
                    <p class="text-muted mb-4">
                        <small><i class="bi bi-calendar me-1"></i> Membro dal {{ Auth::user()->created_at->format('d/m/Y') }}</small>
                    </p>
                    <div>
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary modern-btn">
                            <i class="bi bi-pencil"></i> Modifica Profilo
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Informazioni Account -->
        <div class="col-lg-8">
            <div class="modern-card">
                <div class="p-4 profile-content">
                    <div class="d-flex align-items-center mb-4">
                        <i class="bi bi-info-circle me-2" style="color: #029D7E; font-size: 1.5rem;"></i>
                        <h5 class="mb-0" style="color: #2d3748; font-weight: 700;">Informazioni Account</h5>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted">Nome Completo</label>
                                <p class="h6 mb-0" style="color: #2d3748;">{{ Auth::user()->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted">Email</label>
                                <p class="h6 mb-0" style="color: #2d3748;">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted">Data Registrazione</label>
                                <p class="h6 mb-0" style="color: #2d3748;">
                                    <i class="bi bi-calendar-plus me-1 text-success"></i>
                                    {{ Auth::user()->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted">Ultimo Aggiornamento</label>
                                <p class="h6 mb-0" style="color: #2d3748;">
                                    <i class="bi bi-clock me-1 text-primary"></i>
                                    {{ Auth::user()->updated_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Attività Recenti -->
    <div class="row">
        <div class="col-12">
            <div class="modern-card">
                <div class="p-4">
                    <div class="d-flex align-items-center mb-4">
                        <i class="bi bi-clock-history me-2" style="color: #029D7E; font-size: 1.5rem;"></i>
                        <h5 class="mb-0" style="color: #2d3748; font-weight: 700;">Attività Recenti</h5>
                    </div>
                    
                    <div class="activity-list">
                        @if(\App\Models\Vendita::latest()->take(5)->count() > 0)
                            @foreach(\App\Models\Vendita::with('cliente')->latest()->take(5)->get() as $vendita)
                                <div class="d-flex justify-content-between align-items-center p-3 mb-3 rounded" style="background: rgba(2, 157, 126, 0.05); border-left: 4px solid #029D7E;">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="bi bi-receipt" style="color: #029D7E; font-size: 1.5rem;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1" style="color: #2d3748; font-weight: 600;">Vendita #{{ $vendita->id }}</h6>
                                            <small class="text-muted">
                                                <i class="bi bi-person me-1"></i>{{ $vendita->cliente->nome_completo ?? 'N/A' }}
                                                <span class="ms-2">
                                                    <i class="bi bi-calendar me-1"></i>{{ $vendita->data_vendita->format('d/m/Y') }}
                                                </span>
                                            </small>
                                        </div>
                                    </div>
                                    <span class="badge rounded-pill px-3 py-2" style="background: linear-gradient(135deg, #28a745, #20c997); color: white; font-weight: 600;">
                                        €{{ number_format($vendita->totale_finale, 2, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                            
                            <div class="text-center mt-4">
                                <a href="{{ route('vendite.index') }}" class="btn btn-primary modern-btn">
                                    <i class="bi bi-list-ul me-1"></i> Vedi Tutte le Vendite
                                </a>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-cart-x" style="font-size: 4rem; color: #029D7E; opacity: 0.5;"></i>
                                <h5 class="text-muted mt-3 mb-3">Nessuna vendita ancora registrata</h5>
                                <a href="{{ route('vendite.create') }}" class="btn btn-primary modern-btn">
                                    <i class="bi bi-plus-lg me-1"></i> Crea Prima Vendita
                                </a>
                            </div>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection