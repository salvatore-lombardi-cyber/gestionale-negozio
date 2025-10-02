@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .config-container {
        padding: 2rem;
    }
    
    .config-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .config-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .config-card {
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
        color: #029D7E;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #029D7E;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .section-title i {
        color: #029D7E;
        font-size: 1.2rem;
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
    
    .modern-btn.success {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
    }
    
    .modern-btn.success:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(2, 157, 126, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .feature-card {
        background: rgba(2, 157, 126, 0.1);
        border: 2px solid rgba(2, 157, 126, 0.2);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .feature-card:hover {
        border-color: rgba(2, 157, 126, 0.4);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(2, 157, 126, 0.2);
    }
    
    .feature-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .feature-title i {
        color: #029D7E;
        font-size: 1.2rem;
    }
    
    .feature-description {
        color: #6c757d;
        line-height: 1.6;
        margin-bottom: 1rem;
    }
    
    .supported-formats {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }
    
    .format-badge {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .coming-soon {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: #333;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
        margin-top: 1rem;
    }
    
    .workflow-step {
        background: white;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        border-left: 4px solid #029D7E;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .step-number {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-right: 1rem;
        font-size: 0.9rem;
    }
    
    .step-title {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    
    .step-description {
        color: #6c757d;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    /* Dark Mode */
    [data-bs-theme="dark"] .config-header {
        background: rgba(45, 55, 72, 0.95) !important;
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .config-card {
        background: rgba(45, 55, 72, 0.95) !important;
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .section-title {
        color: #4DC9A5 !important;
        border-bottom-color: #4DC9A5 !important;
    }
    
    [data-bs-theme="dark"] .section-title i {
        color: #4DC9A5 !important;
    }
    
    [data-bs-theme="dark"] .feature-card {
        background: rgba(2, 157, 126, 0.15) !important;
        border-color: rgba(2, 157, 126, 0.3) !important;
    }
    
    [data-bs-theme="dark"] .feature-title {
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .feature-title i {
        color: #4DC9A5 !important;
    }
    
    [data-bs-theme="dark"] .feature-description {
        color: #a0aec0 !important;
    }
    
    [data-bs-theme="dark"] .workflow-step {
        background: rgba(45, 55, 72, 0.8) !important;
        border-left-color: #4DC9A5 !important;
    }
    
    [data-bs-theme="dark"] .step-title {
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .step-description {
        color: #a0aec0 !important;
    }
</style>

<div class="container-fluid config-container">
    <!-- Header -->
    <div class="config-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="config-title">
                    <i class="bi bi-cloud-upload"></i> Importazione Dati
                </h1>
                <p class="text-muted mt-2">Sistema guidato per importare anagrafiche da file CSV/Excel</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('impostazioni.index') }}" class="modern-btn secondary">
                    <i class="bi bi-arrow-left"></i> Torna alle Impostazioni
                </a>
            </div>
        </div>
    </div>

    <!-- Funzionalità Plannate -->
    <div class="config-card">
        <h3 class="section-title">
            <i class="bi bi-list-check"></i> Funzionalità Import/Export
        </h3>
        
        <div class="row">
            <div class="col-md-6">
                <div class="feature-card">
                    <div class="feature-title">
                        <i class="bi bi-people"></i> Anagrafica Clienti
                    </div>
                    <div class="feature-description">
                        Importa i dati dei clienti con mappatura automatica dei campi. 
                        Supporta validazione email, partita IVA e codici fiscali.
                    </div>
                    <div class="supported-formats">
                        <span class="format-badge">CSV</span>
                        <span class="format-badge">XLSX</span>
                        <span class="format-badge">XLS</span>
                    </div>
                    <span class="coming-soon">Coming Soon</span>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="feature-card">
                    <div class="feature-title">
                        <i class="bi bi-truck"></i> Anagrafica Fornitori
                    </div>
                    <div class="feature-description">
                        Importa i dati dei fornitori con controlli automatici di validità. 
                        Gestisce categorie fornitori e coordinate bancarie.
                    </div>
                    <div class="supported-formats">
                        <span class="format-badge">CSV</span>
                        <span class="format-badge">XLSX</span>
                        <span class="format-badge">XLS</span>
                    </div>
                    <span class="coming-soon">Coming Soon</span>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="feature-card">
                    <div class="feature-title">
                        <i class="bi bi-box"></i> Anagrafica Articoli
                    </div>
                    <div class="feature-description">
                        Importa i prodotti del magazzino con prezzi, categorie e varianti. 
                        Supporta codici EAN e classificazioni merceologiche.
                    </div>
                    <div class="supported-formats">
                        <span class="format-badge">CSV</span>
                        <span class="format-badge">XLSX</span>
                        <span class="format-badge">XLS</span>
                    </div>
                    <span class="coming-soon">Coming Soon</span>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="feature-card">
                    <div class="feature-title">
                        <i class="bi bi-download"></i> Export Personalizzati
                    </div>
                    <div class="feature-description">
                        Esporta dati con query personalizzate e filtri avanzati. 
                        Genera report Excel con grafici e formattazioni professionali.
                    </div>
                    <div class="supported-formats">
                        <span class="format-badge">XLSX</span>
                        <span class="format-badge">CSV</span>
                        <span class="format-badge">PDF</span>
                    </div>
                    <span class="coming-soon">Coming Soon</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Workflow Process -->
    <div class="config-card">
        <h3 class="section-title">
            <i class="bi bi-arrow-right-circle"></i> Workflow di Importazione
        </h3>
        
        <div class="workflow-step">
            <div class="d-flex align-items-start">
                <span class="step-number">1</span>
                <div>
                    <div class="step-title">Upload e Analisi File</div>
                    <div class="step-description">
                        Carica il file CSV/Excel. Il sistema analizzerà automaticamente la struttura, 
                        verificherà il formato e mostrerà un'anteprima dei dati.
                    </div>
                </div>
            </div>
        </div>
        
        <div class="workflow-step">
            <div class="d-flex align-items-start">
                <span class="step-number">2</span>
                <div>
                    <div class="step-title">Mappatura Campi</div>
                    <div class="step-description">
                        Mappa ogni colonna del file ai campi del database. 
                        Il sistema suggerirà automaticamente le associazioni più probabili.
                    </div>
                </div>
            </div>
        </div>
        
        <div class="workflow-step">
            <div class="d-flex align-items-start">
                <span class="step-number">3</span>
                <div>
                    <div class="step-title">Validazione Dati</div>
                    <div class="step-description">
                        Controlla la validità dei dati prima dell'importazione. 
                        Evidenzia errori, duplicati e campi mancanti.
                    </div>
                </div>
            </div>
        </div>
        
        <div class="workflow-step">
            <div class="d-flex align-items-start">
                <span class="step-number">4</span>
                <div>
                    <div class="step-title">Importazione e Report</div>
                    <div class="step-description">
                        Esegue l'importazione con barra di progresso in tempo reale. 
                        Genera un report dettagliato con statistiche e eventuali errori.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Specifiche Tecniche -->
    <div class="config-card">
        <h3 class="section-title">
            <i class="bi bi-gear"></i> Specifiche Tecniche
        </h3>
        
        <div class="row">
            <div class="col-md-6">
                <h5><i class="bi bi-file-earmark-text text-success me-2"></i>Formati Supportati</h5>
                <ul class="list-unstyled ms-3">
                    <li><i class="bi bi-check-circle text-success me-2"></i>CSV (UTF-8, separatori configurabili)</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Excel (.xlsx, .xls)</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Encoding automatico</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Max 10MB per file</li>
                </ul>
            </div>
            
            <div class="col-md-6">
                <h5><i class="bi bi-shield-check text-primary me-2"></i>Sicurezza</h5>
                <ul class="list-unstyled ms-3">
                    <li><i class="bi bi-check-circle text-success me-2"></i>Validazione formati rigorosa</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Isolamento file per utente</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Pulizia automatica file temporanei</li>
                    <li><i class="bi bi-check-circle text-success me-2"></i>Log operazioni dettagliato</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Azione -->
    <div class="config-card text-center">
        <h4 class="mb-3">🚀 Sistema in Sviluppo</h4>
        <p class="text-muted mb-4">
            Il sistema di importazione dati è in fase di sviluppo avanzato. 
            Sarà disponibile nella prossima release del gestionale.
        </p>
        <a href="{{ route('impostazioni.index') }}" class="modern-btn success">
            <i class="bi bi-arrow-left"></i> Torna alle Impostazioni
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animazione card
    const cards = document.querySelectorAll('.config-card, .feature-card, .workflow-step');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>
@endsection