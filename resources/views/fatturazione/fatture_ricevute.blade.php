@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .fatture-ricevute-container {
        padding: 2rem;
    }
    
    .fatture-ricevute-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .fatture-ricevute-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #48cae4 0%, #0077b6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }
    
    .stats-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        text-align: center;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }
    
    .stats-number {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0;
    }
    
    .stats-label {
        font-size: 1rem;
        color: #718096;
        font-weight: 600;
        margin-top: 0.5rem;
    }
    
    .stats-icon {
        width: 80px;
        height: 80px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: white;
        margin: 0 auto 1rem;
    }
    
    .coming-soon-card {
        text-align: center;
        padding: 4rem 2rem;
    }
    
    .coming-soon-icon {
        font-size: 5rem;
        color: #48cae4;
        margin-bottom: 2rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #48cae4 0%, #0077b6 100%);
        border: none;
        border-radius: 15px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
    
    .feature-list {
        text-align: left;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .feature-item {
        padding: 1rem;
        margin: 0.5rem 0;
        background: rgba(72, 202, 228, 0.1);
        border-radius: 15px;
        border-left: 4px solid #48cae4;
    }
    
    @media (max-width: 768px) {
        .fatture-ricevute-container {
            padding: 1rem;
        }
        
        .fatture-ricevute-title {
            font-size: 2rem;
        }
        
        .stats-number {
            font-size: 2rem;
        }
        
        .coming-soon-icon {
            font-size: 3rem;
        }
    }
</style>

<div class="container-fluid fatture-ricevute-container">
    <!-- Header -->
    <div class="fatture-ricevute-header text-center">
        <h1 class="fatture-ricevute-title">
            <i class="bi bi-inbox"></i> Fatture Ricevute
        </h1>
        <p class="text-muted">Gestione delle fatture ricevute dai fornitori</p>
    </div>
    
    <!-- Statistiche Attuali -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #48cae4, #0077b6);">
                    <i class="bi bi-inbox"></i>
                </div>
                <div class="stats-number text-primary">{{ $stats['fatture_ricevute_totali'] }}</div>
                <div class="stats-label">Totale Ricevute</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #ffc107, #ff8500);">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="stats-number text-warning">{{ $stats['fatture_da_pagare'] }}</div>
                <div class="stats-label">Da Pagare</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stats-number text-success">{{ $stats['fatture_pagate'] }}</div>
                <div class="stats-label">Pagate</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #dc3545, #c82333);">
                    <i class="bi bi-currency-euro"></i>
                </div>
                <div class="stats-number text-danger">€{{ number_format($stats['totale_da_pagare'], 0, ',', '.') }}</div>
                <div class="stats-label">Totale da Pagare</div>
            </div>
        </div>
    </div>
    
    <!-- Coming Soon Card -->
    <div class="row">
        <div class="col-12">
            <div class="card coming-soon-card">
                <div class="coming-soon-icon">
                    <i class="bi bi-tools"></i>
                </div>
                <h2 class="mb-4">Modulo in Sviluppo</h2>
                <p class="lead text-muted mb-4">
                    Il modulo Fatture Ricevute è attualmente in fase di sviluppo. 
                    Presto avrai a disposizione tutte le funzionalità per gestire le fatture dei tuoi fornitori.
                </p>
                
                <div class="feature-list mt-4">
                    <h4 class="mb-3">Funzionalità in arrivo:</h4>
                    
                    <div class="feature-item">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-upload text-primary me-3 fs-4"></i>
                            <div>
                                <strong>Caricamento Fatture</strong>
                                <p class="mb-0 text-muted">Upload e scansione automatica delle fatture ricevute</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-badge text-success me-3 fs-4"></i>
                            <div>
                                <strong>Gestione Fornitori</strong>
                                <p class="mb-0 text-muted">Anagrafica completa dei fornitori con dati fiscali</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar-check text-warning me-3 fs-4"></i>
                            <div>
                                <strong>Scadenze Pagamenti</strong>
                                <p class="mb-0 text-muted">Promemoria automatici per le scadenze</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-pie-chart text-info me-3 fs-4"></i>
                            <div>
                                <strong>Report e Analytics</strong>
                                <p class="mb-0 text-muted">Analisi dei costi e statistiche sui fornitori</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-file-earmark-check text-primary me-3 fs-4"></i>
                            <div>
                                <strong>Controllo Duplicati</strong>
                                <p class="mb-0 text-muted">Rilevamento automatico di fatture duplicate</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-bank text-success me-3 fs-4"></i>
                            <div>
                                <strong>Registrazione Pagamenti</strong>
                                <p class="mb-0 text-muted">Tracciamento completo dei pagamenti effettuati</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('fatturazione.index') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-arrow-left"></i> Torna alla Fatturazione
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection