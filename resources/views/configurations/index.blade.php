@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .dashboard-container {
        padding: 2rem;
    }
    
    .dashboard-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .dashboard-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .welcome-text {
        color: #6c757d;
        font-size: 1.1rem;
        margin-top: 0.5rem;
    }
    
    .config-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        height: 100%;
    }
    
    .config-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        border-radius: 20px 20px 0 0;
    }
    
    .config-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }



    
    .config-card.company::before {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
    }
    
    .config-card.banks::before {
        background: linear-gradient(135deg, #48cae4, #0077b6);
    }
    
    .config-card.tables::before {
        background: linear-gradient(135deg, #48cae4, #0077b6);
    }
    
    .config-card.settings::before {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
    }
    
    .config-card.enterprise::before {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
    }
    
    .config-card.users::before {
        background: linear-gradient(135deg, #f72585, #c5025a);
    }
    
    .config-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        margin-bottom: 1rem;
        position: relative;
        overflow: hidden;
    }
    
    .config-icon.company {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
    }
    
    .config-icon.banks {
        background: linear-gradient(135deg, #48cae4, #0077b6);
    }
    
    .config-icon.tables {
        background: linear-gradient(135deg, #48cae4, #0077b6);
    }
    
    .config-icon.settings {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
    }
    
    .config-icon.enterprise {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
    }
    
    .config-icon.users {
        background: linear-gradient(135deg, #f72585, #c5025a);
    }
    
    .config-icon::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: rgba(255, 255, 255, 0.1);
        transform: rotate(45deg);
        transition: all 0.3s ease;
        opacity: 0;
    }
    
    .config-card:hover .config-icon::before {
        opacity: 1;
        animation: shimmer 1s ease-in-out;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%) rotate(45deg); }
        100% { transform: translateX(100%) rotate(45deg); }
    }
    
    .config-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
        margin: 0 0 1rem 0;
    }
    
    .config-description {
        color: #718096;
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }
    
    .config-btn {
        border: none;
        border-radius: 15px;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        position: relative;
        overflow: hidden;
    }
    
    .config-btn.primary {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
    }
    
    .config-btn.success {
        background: linear-gradient(135deg, #4ecdc4, #44a08d);
        color: white;
    }
    
    .config-btn.info {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
    }
    
    .config-btn.warning {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
    }
    
    .config-btn.danger {
        background: linear-gradient(135deg, #f72585, #c5025a);
        color: white;
    }
    
    .config-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        color: white;
        text-decoration: none;
    }

    /* Dark Mode */
    [data-bs-theme="dark"] .dashboard-header {
        background: rgba(45, 55, 72, 0.95) !important;
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .dashboard-title {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    [data-bs-theme="dark"] .welcome-text {
        color: #a0aec0 !important;
    }
    
    [data-bs-theme="dark"] .config-card {
        background: rgba(45, 55, 72, 0.95) !important;
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .config-title {
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .config-description {
        color: #a0aec0 !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .dashboard-header {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .dashboard-title {
            font-size: 2rem;
        }
        
        .welcome-text {
            font-size: 1rem;
        }
        
        .config-card {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .config-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
        
        .config-title {
            font-size: 1.3rem;
        }
        
        .config-description {
            font-size: 0.9rem;
        }
    }
</style>

<div class="container-fluid dashboard-container">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="dashboard-header text-center">
                <h1 class="dashboard-title">
                    <i class="bi bi-gear"></i> {{ __('app.configurations') }}
                </h1>
                <p class="welcome-text">{{ __('app.configurations_subtitle') }}</p>
            </div>
            
            <!-- Configuration Cards -->
            <div class="row g-4">
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="config-card company">
                        <div class="config-icon company">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <h3 class="config-title">Profilo Utente</h3>
                        <p class="config-description">Gestisci i dati anagrafici, fiscali e di contatto dell'azienda</p>
                        <a href="{{ route('configurations.utente') }}" class="config-btn primary">
                            <i class="bi bi-arrow-right"></i> {{ __('app.manage') }}
                        </a>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="config-card banks">
                        <div class="config-icon banks">
                            <i class="bi bi-bank"></i>
                        </div>
                        <h3 class="config-title">{{ __('app.bank_accounts') }}</h3>
                        <p class="config-description">{{ __('app.bank_accounts_desc') }} Configura metodi di pagamento e coordinate per fatturazione.</p>
                        <a href="{{ route('configurations.bank-accounts') }}" class="config-btn info">
                            <i class="bi bi-arrow-right"></i> {{ __('app.manage') }}
                        </a>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="config-card enterprise">
                        <div class="config-icon enterprise">
                            <i class="bi bi-gear-wide-connected"></i>
                        </div>
                        <h3 class="config-title">Gestisci Tabelle</h3>
                        <p class="config-description">Sistema enterprise per configurazioni avanzate. Associazioni Nature IVA e Aliquote con architettura moderna.</p>
                        <a href="{{ route('configurations.gestione-tabelle.index') }}" class="config-btn warning">
                            <i class="bi bi-arrow-right"></i> Gestisci
                        </a>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="config-card users">
                        <div class="config-icon users">
                            <i class="bi bi-people"></i>
                        </div>
                        <h3 class="config-title">Gestione Utenti</h3>
                        <p class="config-description">Sistema multi-utente enterprise con permessi granulari e controllo accessi avanzato</p>
                        <a href="{{ route('configurations.users.index') }}" class="config-btn danger">
                            <i class="bi bi-arrow-right"></i> {{ __('app.manage') }}
                        </a>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="config-card settings">
                        <div class="config-icon settings">
                            <i class="bi bi-sliders"></i>
                        </div>
                        <h3 class="config-title">{{ __('app.system_settings') }}</h3>
                        <p class="config-description">{{ __('app.system_settings_desc') }}</p>
                        <a href="{{ route('configurations.settings') }}" class="config-btn warning">
                            <i class="bi bi-arrow-right"></i> {{ __('app.manage') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Animazione di entrata delle card
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.config-card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(50px)';
                card.style.transition = 'all 0.6s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100);
            }, index * 150);
        });
    });
</script>
@endsection