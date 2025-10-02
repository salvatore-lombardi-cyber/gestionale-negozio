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
        background: linear-gradient(135deg, #ffd60a 0%, #ff8500 100%);
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

    .config-card.print-config::before {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }
    
    .config-card.numbering::before {
        background: linear-gradient(135deg, #48cae4, #0077b6);
    }
    
    .config-card.import::before {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
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
    
    .config-icon.print-config {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }
    
    .config-icon.numbering {
        background: linear-gradient(135deg, #48cae4, #0077b6);
    }
    
    .config-icon.import {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
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
    
    .config-btn.purple {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
    }
    
    .config-btn.info {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
    }
    
    .config-btn.success {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
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

    .modern-btn {
        border: none;
        border-radius: 15px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        font-size: 0.9rem;
    }
    
    .modern-btn.secondary {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
    }
    
    .modern-btn.secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(108, 117, 125, 0.3);
        color: white;
        text-decoration: none;
    }

    /* Dark Mode */
    [data-bs-theme="dark"] .dashboard-header {
        background: rgba(45, 55, 72, 0.95) !important;
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .dashboard-title {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
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
                <div class="d-flex justify-content-between align-items-center">
                    <div class="flex-grow-1">
                        <h1 class="dashboard-title">
                            <i class="bi bi-sliders"></i> Impostazioni
                        </h1>
                        <p class="welcome-text">Configura numeratori, stampe, importazioni e utenze del sistema</p>
                    </div>
                    <a href="{{ route('configurations.index') }}" class="modern-btn secondary">
                        <i class="bi bi-arrow-left"></i> Torna alle Configurazioni
                    </a>
                </div>
            </div>
            
            <!-- Impostazioni Cards -->
            <div class="row g-4">
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <div class="config-card print-config">
                        <div class="config-icon print-config">
                            <i class="bi bi-link-45deg"></i>
                        </div>
                        <h3 class="config-title">Configurazione Stampe</h3>
                        <p class="config-description">Associa i template grafici ai tipi di documento. Collega i template creati ai DDT, Fatture, Preventivi.</p>
                        <a href="{{ route('impostazioni.configurazione-stampe') }}" class="config-btn purple">
                            <i class="bi bi-arrow-right"></i> Gestisci Associazioni
                        </a>
                    </div>
                </div>
                
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <div class="config-card numbering">
                        <div class="config-icon numbering">
                            <i class="bi bi-123"></i>
                        </div>
                        <h3 class="config-title">Configurazione Numeratori</h3>
                        <p class="config-description">Gestisci i numeratori automatici per DDT, Fatture e Preventivi. Configura prefissi, suffissi e formati.</p>
                        <a href="{{ route('impostazioni.configurazione-numeratori') }}" class="config-btn info">
                            <i class="bi bi-arrow-right"></i> Gestisci Numeratori
                        </a>
                    </div>
                </div>
                
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <div class="config-card import">
                        <div class="config-icon import">
                            <i class="bi bi-cloud-upload"></i>
                        </div>
                        <h3 class="config-title">Importazione Dati</h3>
                        <p class="config-description">Importa anagrafiche clienti, fornitori e articoli da file CSV/Excel. Sistema guidato con mappatura campi.</p>
                        <a href="{{ route('impostazioni.importazione-dati') }}" class="config-btn success">
                            <i class="bi bi-arrow-right"></i> Gestisci Import
                        </a>
                    </div>
                </div>
                
                <div class="col-xl-6 col-lg-6 col-md-6">
                    <div class="config-card users">
                        <div class="config-icon users">
                            <i class="bi bi-people"></i>
                        </div>
                        <h3 class="config-title">Gestione Utenze</h3>
                        <p class="config-description">Sistema multi-utente enterprise. Crea sub-account, gestisci permessi e controllo accessi avanzato.</p>
                        <a href="{{ route('configurations.users.index') }}" class="config-btn danger">
                            <i class="bi bi-arrow-right"></i> Gestisci Utenti
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