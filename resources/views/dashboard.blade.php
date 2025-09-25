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
    
    .stats-card {
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
    
    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        border-radius: 20px 20px 0 0;
    }
    
    .stats-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }
    
    .stats-card.primary::before {
        background: linear-gradient(135deg, #4ecdc4, #44a08d);
    }
    
    .stats-card.success::before {
        background: linear-gradient(135deg, #48cae4, #0077b6);
    }
    
    .stats-card.info::before {
        background: linear-gradient(135deg, #9c27b0, #7b1fa2);

    }
    
    .stats-card.warning::before {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
    }
    
    .stats-icon {
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
    
    .stats-icon.primary {
        background: linear-gradient(135deg, #4ecdc4, #44a08d);
    }

    .stats-icon.success {
        background: linear-gradient(135deg, #48cae4, #0077b6);
    }
    .stats-icon.info {
        background: linear-gradient(135deg, #9c27b0, #7b1fa2);
    }
    
    .stats-icon.warning {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
    }
    
    .stats-icon::before {
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
    
    .stats-card:hover .stats-icon::before {
        opacity: 1;
        animation: shimmer 1s ease-in-out;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%) rotate(45deg); }
        100% { transform: translateX(100%) rotate(45deg); }
    }
    
    .stats-number {
        font-size: 3rem;
        font-weight: 800;
        color: #2d3748;
        margin: 0;
        line-height: 1;
    }
    
    .stats-label {
        font-size: 1.1rem;
        color: #718096;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }
    
    .stats-btn {
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
    
    .stats-btn.primary {
        background: linear-gradient(135deg, #4ecdc4, #44a08d);
        color: white;
    }
    
    .stats-btn.success {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
    }
    
    .stats-btn.info {
        background: linear-gradient(135deg, #9c27b0, #7b1fa2);
        color: white;
    }
    
    .stats-btn.warning {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
    }
    
    .stats-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        color: white;
    }
    
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(2, 157, 126, 0.4); }
        70% { box-shadow: 0 0 0 20px rgba(2, 157, 126, 0); }
        100% { box-shadow: 0 0 0 0 rgba(2, 157, 126, 0); }
    }
    
    .floating-elements {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: -1;
        overflow: hidden;
    }
    
    .floating-element {
        position: absolute;
        opacity: 0.1;
        font-size: 2rem;
        color: white;
        animation: float 6s ease-in-out infinite;
    }
    
    .floating-element:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; }
    .floating-element:nth-child(2) { top: 20%; right: 20%; animation-delay: 1s; }
    .floating-element:nth-child(3) { top: 50%; left: 5%; animation-delay: 2s; }
    .floating-element:nth-child(4) { bottom: 30%; right: 10%; animation-delay: 3s; }
    .floating-element:nth-child(5) { bottom: 10%; left: 30%; animation-delay: 4s; }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }
    
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
        
        .stats-card {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .stats-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
        
        .stats-number {
            font-size: 2.5rem;
        }
        
        .stats-label {
            font-size: 1rem;
        }
        
        .stats-btn {
            padding: 10px 20px;
            font-size: 0.8rem;
        }
    }
    
    @media (max-width: 576px) {
        .dashboard-container {
            padding: 1rem;
        }
        
        .dashboard-title {
            font-size: 1.8rem;
        }
        
        .stats-number {
            font-size: 2rem;
        }
        
        .floating-element {
            font-size: 1.5rem;
        }
    }
    
    /* ===== FIX CARD DIMENSIONI DASHBOARD ===== */
    .dashboard-header .clock-card,
    .dashboard-header .weather-card,
    .dashboard-header .calendar-card,
    .dashboard-header .map-card {
        min-height: 320px !important;
        height: 320px !important;
        max-height: 320px !important;
    }
    
    .dashboard-header .calendar-grid {
        height: auto !important;
        max-height: none !important;
        overflow: visible !important;
    }
    
    .dashboard-header .map-container {
        height: 100px !important;
    }
    
    /* ===== RIMPICCIOLISCI CALENDARIO DASHBOARD ===== */
    .dashboard-header .calendar-day {
        font-size: 0.65rem !important;
        padding: 0.15rem !important;
        min-height: 18px !important;
        height: 18px !important;
    }
    
    .dashboard-header .calendar-weekday {
        font-size: 0.6rem !important;
        padding: 0.2rem 0 !important;
    }
    
    .dashboard-header .calendar-grid {
        gap: 1px !important;
        padding: 0.3rem !important;
    }
    
    .dashboard-header .calendar-date-display {
        margin-top: 0.8rem !important;
    }
</style>

<!-- Elementi fluttuanti di sfondo -->
<div class="floating-elements">
    <i class="bi bi-box-seam floating-element"></i>
    <i class="bi bi-people floating-element"></i>
    <i class="bi bi-cart-check floating-element"></i>
    <i class="bi bi-file-earmark-text floating-element"></i>
    <i class="bi bi-archive floating-element"></i>
</div>

<div class="container-fluid dashboard-container">
    <div class="row">
        <div class="col-12">
            <!-- Header della Dashboard -->
            <div class="dashboard-header text-center">
                <h1 class="dashboard-title">
                    <i class="bi bi-speedometer2"></i> {{ __('app.dashboard') }}
                </h1>
                <p class="welcome-text">{{ __('app.welcome_message') }}</p>
                
                <!-- Card Orologio, Meteo, Calendario e Mappa -->
                <div class="row g-3 mt-3">
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <x-orologio id="dashboard" />
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <x-meteo id="dashboard" city="Roma" />
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <x-calendario id="dashboard" />
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6">
                        <x-mappa id="dashboard" location="Italia" />
                    </div>
                </div>
            </div>
            
            <!-- Cards delle Statistiche -->
            <div class="row g-4">
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="stats-card primary pulse-animation">
                        <div class="stats-icon primary">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h3 class="stats-number">{{ \App\Models\Anagrafica::where('tipo', 'articolo')->count() }}</h3>
                        <p class="stats-label">{{ __('app.products') }}</p>
                        <a href="{{ route('anagrafiche.lista', 'articolo') }}" class="stats-btn primary">
                            <i class="bi bi-arrow-right"></i> {{ __('app.manage') }} {{ __('app.products') }}
                        </a>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="stats-card success">
                        <div class="stats-icon success">
                            <i class="bi bi-people"></i>
                        </div>
                        <h3 class="stats-number">{{ \App\Models\Anagrafica::where('tipo', 'cliente')->count() }}</h3>
                        <p class="stats-label">{{ __('app.clients') }}</p>
                        <a href="{{ route('anagrafiche.lista', 'cliente') }}" class="stats-btn success">
                            <i class="bi bi-arrow-right"></i> {{ __('app.manage') }} {{ __('app.clients') }}
                        </a>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="stats-card info">
                        <div class="stats-icon info">
                            <i class="bi bi-cart-check"></i>
                        </div>
                        <h3 class="stats-number">{{ \App\Models\Vendita::count() }}</h3>
                        <p class="stats-label">{{ __('app.sales') }}</p>
                        <a href="{{ route('vendite.index') }}" class="stats-btn info">
                            <i class="bi bi-arrow-right"></i> {{ __('app.manage') }} {{ __('app.sales') }}
                        </a>
                    </div>
                </div>
                
                <div class="col-xl-3 col-lg-6 col-md-6">
                    <div class="stats-card warning">
                        <div class="stats-icon warning">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <h3 class="stats-number">{{ \App\Models\Ddt::count() }}</h3>
                        <p class="stats-label">{{ __('app.ddts') }}</p>
                        <a href="{{ route('ddts.index') }}" class="stats-btn warning">
                            <i class="bi bi-arrow-right"></i> {{ __('app.manage') }} {{ __('app.ddts') }}
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
        const cards = document.querySelectorAll('.stats-card');
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
    
    // Aggiorna i numeri con animazione
    function animateNumbers() {
        const numbers = document.querySelectorAll('.stats-number');
        numbers.forEach(number => {
            const finalValue = parseInt(number.textContent);
            let currentValue = 0;
            const increment = finalValue / 50;
            const timer = setInterval(() => {
                currentValue += increment;
                if (currentValue >= finalValue) {
                    number.textContent = finalValue;
                    clearInterval(timer);
                } else {
                    number.textContent = Math.floor(currentValue);
                }
            }, 30);
        });
    }
    
    // Avvia animazione numeri al caricamento
    setTimeout(animateNumbers, 500);
</script>
@endsection