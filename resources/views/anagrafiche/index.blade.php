@extends('layouts.app')

@section('title', 'Anagrafiche - Gestionale Negozio')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .anagrafiche-container {
        padding: 2rem;
        min-height: calc(100vh - 76px);
    }
    
    .page-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .page-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
        text-align: center;
    }
    
    .page-subtitle {
        text-align: center;
        color: #6c757d;
        font-size: 1.1rem;
        margin-top: 0.5rem;
    }
    
    .tipo-card {
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
        text-decoration: none;
        color: inherit;
        display: block;
    }
    
    .tipo-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--card-color);
        border-radius: 20px 20px 0 0;
    }
    
    .tipo-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
        text-decoration: none;
        color: inherit;
    }
    
    .tipo-card.clienti { --card-color: linear-gradient(135deg, #4ecdc4, #44a08d); }
    .tipo-card.fornitori { --card-color: linear-gradient(135deg, #9c27b0, #7b1fa2); }
    .tipo-card.vettori { --card-color: linear-gradient(135deg, #48cae4, #0077b6); }
    .tipo-card.agenti { --card-color: linear-gradient(135deg, #ffd60a, #ff8500); }
    .tipo-card.articoli { --card-color: linear-gradient(135deg, #f72585, #c5025a); }
    .tipo-card.servizi { --card-color: linear-gradient(135deg, #029D7E, #4DC9A5); }
    
    .tipo-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: white;
        margin-bottom: 1.5rem;
        background: var(--card-color);
        position: relative;
        overflow: hidden;
    }
    
    .tipo-icon::before {
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
    
    .tipo-card:hover .tipo-icon::before {
        opacity: 1;
        animation: shimmer 1s ease-in-out;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%) rotate(45deg); }
        100% { transform: translateX(100%) rotate(45deg); }
    }
    
    .tipo-name {
        font-size: 1.3rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        text-align: center;
        color: #2d3748;
    }
    
    .tipo-description {
        font-size: 0.9rem;
        text-align: center;
        line-height: 1.4;
        color: #718096;
        margin-bottom: 1rem;
    }
    
    .tipo-stats {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
    }
    
    .stats-number {
        font-size: 2rem;
        font-weight: 800;
        color: #2d3748;
        margin: 0;
        line-height: 1;
    }
    
    .stats-label {
        font-size: 0.9rem;
        color: #718096;
        font-weight: 600;
    }
    
    .stats-btn {
        background: var(--card-color);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 8px 16px;
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .stats-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        color: white;
        text-decoration: none;
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
    .floating-element:nth-child(3) { bottom: 30%; left: 15%; animation-delay: 2s; }
    .floating-element:nth-child(4) { bottom: 10%; right: 15%; animation-delay: 3s; }
    .floating-element:nth-child(5) { top: 50%; left: 50%; animation-delay: 4s; }
    .floating-element:nth-child(6) { top: 70%; right: 30%; animation-delay: 5s; }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }
    
    @media (max-width: 768px) {
        .anagrafiche-container {
            padding: 1rem;
        }
        
        .page-header {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .page-title {
            font-size: 2rem;
        }
        
        .tipo-card {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .tipo-icon {
            width: 60px;
            height: 60px;
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        .tipo-name {
            font-size: 1.1rem;
        }
        
        .stats-number {
            font-size: 1.5rem;
        }
    }
</style>

<!-- Elementi fluttuanti di sfondo -->
<div class="floating-elements">
    <i class="bi bi-people floating-element"></i>
    <i class="bi bi-building floating-element"></i>
    <i class="bi bi-truck floating-element"></i>
    <i class="bi bi-person-badge floating-element"></i>
    <i class="bi bi-box-seam floating-element"></i>
    <i class="bi bi-tools floating-element"></i>
</div>

<div class="container-fluid anagrafiche-container">
    <div class="row">
        <div class="col-12">
            <!-- Header della pagina -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="bi bi-person-lines-fill"></i> Anagrafiche
                </h1>
                <p class="page-subtitle">Gestione completa di clienti, fornitori, vettori, agenti, articoli e servizi</p>
            </div>
            
            <!-- Grid delle tipologie -->
            <div class="row g-4">
                <!-- CLIENTI -->
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <a href="{{ route('anagrafiche.lista', 'cliente') }}" class="tipo-card clienti">
                        <div class="tipo-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <h3 class="tipo-name">Clienti</h3>
                        <p class="tipo-description">
                            Gestione completa anagrafica clienti con dati fiscali, commerciali e coordinate bancarie
                        </p>
                        <div class="tipo-stats">
                            <div>
                                <div class="stats-number">{{ $stats['clienti'] }}</div>
                                <div class="stats-label">Attivi</div>
                            </div>
                            <div class="stats-btn">
                                <i class="bi bi-arrow-right"></i> Gestisci
                            </div>
                        </div>
                    </a>
                </div>
                
                <!-- FORNITORI -->
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <a href="{{ route('anagrafiche.lista', 'fornitore') }}" class="tipo-card fornitori">
                        <div class="tipo-icon">
                            <i class="bi bi-building"></i>
                        </div>
                        <h3 class="tipo-name">Fornitori</h3>
                        <p class="tipo-description">
                            Anagrafica fornitori con condizioni commerciali, tempi di consegna e categorie merceologiche
                        </p>
                        <div class="tipo-stats">
                            <div>
                                <div class="stats-number">{{ $stats['fornitori'] }}</div>
                                <div class="stats-label">Attivi</div>
                            </div>
                            <div class="stats-btn">
                                <i class="bi bi-arrow-right"></i> Gestisci
                            </div>
                        </div>
                    </a>
                </div>
                
                <!-- VETTORI -->
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <a href="{{ route('anagrafiche.lista', 'vettore') }}" class="tipo-card vettori">
                        <div class="tipo-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <h3 class="tipo-name">Vettori</h3>
                        <p class="tipo-description">
                            Gestione corrieri e trasportatori con zone di consegna, tariffe e tempi standard
                        </p>
                        <div class="tipo-stats">
                            <div>
                                <div class="stats-number">{{ $stats['vettori'] }}</div>
                                <div class="stats-label">Attivi</div>
                            </div>
                            <div class="stats-btn">
                                <i class="bi bi-arrow-right"></i> Gestisci
                            </div>
                        </div>
                    </a>
                </div>
                
                <!-- AGENTI -->
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <a href="{{ route('anagrafiche.lista', 'agente') }}" class="tipo-card agenti">
                        <div class="tipo-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <h3 class="tipo-name">Agenti</h3>
                        <p class="tipo-description">
                            Anagrafica agenti commerciali con provvigioni, zone di competenza e obiettivi di vendita
                        </p>
                        <div class="tipo-stats">
                            <div>
                                <div class="stats-number">{{ $stats['agenti'] }}</div>
                                <div class="stats-label">Attivi</div>
                            </div>
                            <div class="stats-btn">
                                <i class="bi bi-arrow-right"></i> Gestisci
                            </div>
                        </div>
                    </a>
                </div>
                
                <!-- ARTICOLI -->
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <a href="{{ route('anagrafiche.lista', 'articolo') }}" class="tipo-card articoli">
                        <div class="tipo-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <h3 class="tipo-name">Articoli</h3>
                        <p class="tipo-description">
                            Gestione articoli con prezzi, scorte, fornitori principali e categorie merceologiche
                        </p>
                        <div class="tipo-stats">
                            <div>
                                <div class="stats-number">{{ $stats['articoli'] }}</div>
                                <div class="stats-label">Attivi</div>
                            </div>
                            <div class="stats-btn">
                                <i class="bi bi-arrow-right"></i> Gestisci
                            </div>
                        </div>
                    </a>
                </div>
                
                <!-- SERVIZI -->
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <a href="{{ route('anagrafiche.lista', 'servizio') }}" class="tipo-card servizi">
                        <div class="tipo-icon">
                            <i class="bi bi-tools"></i>
                        </div>
                        <h3 class="tipo-name">Servizi</h3>
                        <p class="tipo-description">
                            Anagrafica servizi con tariffe orarie, durata standard e competenze richieste
                        </p>
                        <div class="tipo-stats">
                            <div>
                                <div class="stats-number">{{ $stats['servizi'] }}</div>
                                <div class="stats-label">Attivi</div>
                            </div>
                            <div class="stats-btn">
                                <i class="bi bi-arrow-right"></i> Gestisci
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Animazione di entrata delle card
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.tipo-card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(50px)';
                card.style.transition = 'all 0.6s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100);
            }, index * 100);
        });
    });
    
    // Animazione numeri
    function animateNumbers() {
        const numbers = document.querySelectorAll('.stats-number');
        numbers.forEach(number => {
            const finalValue = parseInt(number.textContent);
            let currentValue = 0;
            const increment = finalValue / 30;
            const timer = setInterval(() => {
                currentValue += increment;
                if (currentValue >= finalValue) {
                    number.textContent = finalValue;
                    clearInterval(timer);
                } else {
                    number.textContent = Math.floor(currentValue);
                }
            }, 50);
        });
    }
    
    // Avvia animazione numeri
    setTimeout(animateNumbers, 300);
</script>
@endsection