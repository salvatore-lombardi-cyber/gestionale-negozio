@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .error-container {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .error-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 3rem;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        max-width: 500px;
    }
    
    .error-icon {
        font-size: 4rem;
        background: linear-gradient(135deg, #dc3545, #c82333);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1.5rem;
    }
    
    .error-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #dc3545, #c82333);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
    }
    
    .error-message {
        color: #6c757d;
        margin-bottom: 2rem;
        line-height: 1.6;
    }
    
    .btn-home {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        border: none;
        border-radius: 15px;
        padding: 12px 30px;
        color: white;
        text-decoration: none;
        font-weight: 600;
        transition: transform 0.3s ease;
    }
    
    .btn-home:hover {
        transform: translateY(-2px);
        color: white;
    }
    
    .contact-info {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e9ecef;
        font-size: 0.9rem;
        color: #6c757d;
    }
</style>

<div class="container-fluid">
    <div class="error-container">
        <div class="error-card">
            <div class="error-icon">
                <i class="bi bi-shield-exclamation"></i>
            </div>
            
            <h1 class="error-title">Accesso Negato</h1>
            
            <div class="error-message">
                <p>Non hai i permessi necessari per accedere a questa sezione.</p>
                <p>La gestione utenti è riservata agli amministratori del sistema.</p>
            </div>
            
            <a href="{{ route('dashboard') ?? '/' }}" class="btn-home">
                <i class="bi bi-house"></i> Torna alla Home
            </a>
            
            <div class="contact-info">
                <p><strong>Hai bisogno di accesso?</strong></p>
                <p>Contatta l'amministratore di sistema per richiedere i permessi necessari.</p>
            </div>
        </div>
    </div>
</div>
@endsection