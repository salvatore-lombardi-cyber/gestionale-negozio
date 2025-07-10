@extends('layouts.app')

@section('title', 'Crea DDT per Vendita #' . $vendita->id . ' - Gestionale Negozio')

@section('content')
<style>
    .sales-container {
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
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .form-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 2rem;
    }
    
    .form-section {
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #667eea;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .modern-input {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid rgba(102, 126, 234, 0.2);
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        margin-bottom: 0.5rem;
    }
    
    .modern-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        background: white;
        transform: translateY(-2px);
    }
    
    .modern-input.is-invalid {
        border-color: #f72585;
        box-shadow: 0 0 0 0.2rem rgba(247, 37, 133, 0.25);
    }
    
    .modern-select {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid rgba(102, 126, 234, 0.2);
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        margin-bottom: 0.5rem;
        cursor: pointer;
    }
    
    .modern-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        background: white;
        transform: translateY(-2px);
    }
    
    .modern-textarea {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid rgba(102, 126, 234, 0.2);
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        resize: vertical;
        min-height: 120px;
        margin-bottom: 0.5rem;
    }
    
    .modern-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        background: white;
        transform: translateY(-2px);
    }
    
    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .required {
        color: #f72585;
    }
    
    .modern-btn {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        border-radius: 15px;
        padding: 15px 30px;
        font-weight: 600;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: inline-block;
    }
    
    .modern-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .modern-btn:hover::before {
        left: 0;
    }
    
    .modern-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        color: white;
    }
    
    .modern-btn.secondary {
        background: linear-gradient(135deg, #6c757d, #495057);
    }
    
    .modern-btn.secondary:hover {
        box-shadow: 0 15px 35px rgba(108, 117, 125, 0.4);
    }
    
    .modern-btn.success {
        background: linear-gradient(135deg, #28a745, #20c997);
    }
    
    .modern-btn.success:hover {
        box-shadow: 0 15px 35px rgba(40, 167, 69, 0.4);
    }
    
    .info-card {
        background: rgba(255, 255, 255, 0.95);  /* ← SFONDO BIANCO */
        backdrop-filter: blur(10px);
        border: 2px solid rgba(102, 126, 234, 0.2);  /* ← BORDO VIOLA */
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .info-label {
        font-weight: 600;
        color: #667eea;  /* ← COLORE VIOLA LEGGIBILE */
        min-width: 120px;
    }
    
    .info-detail {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
        gap: 0.5rem;
    }
    
    .info-label {
        font-weight: 600;
        color: #0077b6;
        min-width: 120px;
    }
    
    .info-value {
        color: #495057;
        font-weight: 500;
    }
    
    .vendita-badge {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 8px 16px;
        border-radius: 15px;
        font-size: 1.1rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    /* Dark Mode */
    [data-bs-theme="dark"] .page-header,
    [data-bs-theme="dark"] .form-card {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .info-card {
        background: rgba(72, 202, 228, 0.2);
        border-color: rgba(72, 202, 228, 0.3);
    }
    
    [data-bs-theme="dark"] .modern-input,
    [data-bs-theme="dark"] .modern-textarea,
    [data-bs-theme="dark"] .modern-select {
        background: rgba(45, 55, 72, 0.8);
        border-color: rgba(102, 126, 234, 0.3);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .modern-input:focus,
    [data-bs-theme="dark"] .modern-textarea:focus,
    [data-bs-theme="dark"] .modern-select:focus {
        background: rgba(45, 55, 72, 0.9);
    }
    
    [data-bs-theme="dark"] .form-label {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .info-label {
        color: #48cae4;
    }
    
    [data-bs-theme="dark"] .info-value {
        color: #e2e8f0;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .sales-container {
            padding: 1rem;
        }
        
        .page-header, .form-card {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .page-title {
            font-size: 2rem;
        }
        
        .modern-btn {
            padding: 12px 24px;
            font-size: 0.9rem;
        }
        
        .info-card {
            padding: 1rem;
        }
    }
    
    @media (max-width: 576px) {
        .page-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .info-detail {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.2rem;
        }
    }
</style>

<div class="sales-container">
    <!-- Header della Pagina -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">
                <i class="bi bi-file-earmark-plus"></i> Crea DDT
            </h1>
            <a href="{{ route('ddts.create') }}" class="modern-btn secondary">
                <i class="bi bi-arrow-left"></i> Torna alla Lista
            </a>
        </div>
        <div style="margin-top: 1rem;">
            <span class="vendita-badge">
                <i class="bi bi-receipt"></i> Vendita #{{ $vendita->id }}
            </span>
        </div>
    </div>
    
    <!-- Informazioni Vendita -->
    <div class="info-card">
        <h3 class="section-title" style="color: #0077b6; margin-bottom: 1rem;">
            <i class="bi bi-info-circle"></i> Informazioni Vendita
        </h3>
        <div class="row">
            <div class="col-md-6">
                <div class="info-detail">
                    <span class="info-label">
                        <i class="bi bi-person"></i> Cliente:
                    </span>
                    <span class="info-value">{{ $vendita->cliente ? $vendita->cliente->nome_completo : 'Cliente occasionale' }}</span>
                </div>
                <div class="info-detail">
                    <span class="info-label">
                        <i class="bi bi-calendar"></i> Data Vendita:
                    </span>
                    <span class="info-value">{{ $vendita->data_vendita->format('d/m/Y') }}</span>
                </div>
                <div class="info-detail">
                    <span class="info-label">
                        <i class="bi bi-currency-euro"></i> Totale:
                    </span>
                    <span class="info-value" style="font-weight: 700; color: #28a745;">€{{ number_format($vendita->totale_finale, 2, ',', '.') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-detail">
                    <span class="info-label">
                        <i class="bi bi-geo-alt"></i> Indirizzo Cliente:
                    </span>
                    <div class="info-value">
                        @if($vendita->cliente)
                        {{ $vendita->cliente->indirizzo }}<br>
                        {{ $vendita->cliente->cap }} {{ $vendita->cliente->citta }}
                        @else
                        <em style="color: #6c757d;">Indirizzo non disponibile</em>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Form DDT -->
    <div class="form-card">
        <form method="POST" action="{{ route('ddts.store') }}" id="ddt-form">
            @csrf
            <input type="hidden" name="vendita_id" value="{{ $vendita->id }}">
            
            <!-- Dati DDT -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="bi bi-file-earmark-text"></i> Dati DDT
                </h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="data_ddt" class="form-label">Data DDT <span class="required">*</span></label>
                            <input type="date" class="modern-input" id="data_ddt" name="data_ddt" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="causale" class="form-label">Causale</label>
                            <select class="modern-select" id="causale" name="causale">
                                <option value="Vendita" selected>Vendita</option>
                                <option value="Reso">Reso</option>
                                <option value="Riparazione">Riparazione</option>
                                <option value="Altro">Altro</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Destinatario -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="bi bi-geo-alt"></i> Destinatario
                </h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="destinatario_nome" class="form-label">Nome <span class="required">*</span></label>
                            <input type="text" class="modern-input" id="destinatario_nome" name="destinatario_nome" 
                            value="{{ $vendita->cliente ? $vendita->cliente->nome : '' }}" required placeholder="Inserisci il nome">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="destinatario_cognome" class="form-label">Cognome <span class="required">*</span></label>
                            <input type="text" class="modern-input" id="destinatario_cognome" name="destinatario_cognome" 
                            value="{{ $vendita->cliente ? $vendita->cliente->cognome : '' }}" required placeholder="Inserisci il cognome">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="destinatario_indirizzo" class="form-label">Indirizzo <span class="required">*</span></label>
                            <input type="text" class="modern-input" id="destinatario_indirizzo" name="destinatario_indirizzo" 
                            value="{{ $vendita->cliente ? $vendita->cliente->indirizzo : '' }}" required placeholder="Via, numero civico">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="destinatario_cap" class="form-label">CAP <span class="required">*</span></label>
                            <input type="text" class="modern-input" id="destinatario_cap" name="destinatario_cap" 
                            value="{{ $vendita->cliente ? $vendita->cliente->cap : '' }}" required placeholder="es. 00100" maxlength="5">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="destinatario_citta" class="form-label">Città <span class="required">*</span></label>
                            <input type="text" class="modern-input" id="destinatario_citta" name="destinatario_citta" 
                            value="{{ $vendita->cliente ? $vendita->cliente->citta : '' }}" required placeholder="es. Roma">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Trasporto e Note -->
            <div class="form-section">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="section-title">
                            <i class="bi bi-truck"></i> Trasporto
                        </h3>
                        <div class="mb-3">
                            <label for="trasportatore" class="form-label">Trasportatore</label>
                            <input type="text" class="modern-input" id="trasportatore" name="trasportatore" 
                            placeholder="Es: Corriere Express, Bartolini, SDA, ecc.">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h3 class="section-title">
                            <i class="bi bi-chat-text"></i> Note Aggiuntive
                        </h3>
                        <div class="mb-3">
                            <label for="note" class="form-label">Note</label>
                            <textarea class="modern-textarea" id="note" name="note" rows="3" 
                            placeholder="Inserisci note aggiuntive per questo DDT..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pulsanti Azione -->
            <div class="d-flex gap-3 justify-content-end">
                <a href="{{ route('ddts.create') }}" class="modern-btn secondary">
                    <i class="bi bi-arrow-left"></i> Annulla
                </a>
                <button type="submit" class="modern-btn success">
                    <i class="bi bi-check-circle"></i> Crea DDT
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animazioni di caricamento
        const elements = document.querySelectorAll('.page-header, .form-card, .info-card');
        elements.forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            element.style.transition = 'all 0.6s ease';
            
            setTimeout(() => {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, index * 200);
        });
        
        // Formattazione CAP
        const capInput = document.getElementById('destinatario_cap');
        capInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').substring(0, 5);
        });
        
        // Animazione submit
        document.getElementById('ddt-form').addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Creando DDT...';
            submitBtn.disabled = true;
        });
    });
</script>
@endsection