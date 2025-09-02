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
        background: linear-gradient(135deg, #ffd60a, #ff8500);
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
        color: #2d3748;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
    
    .form-control, .form-select {
        border-radius: 15px;
        padding: 15px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.8);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #ffd60a;
        box-shadow: 0 0 0 0.2rem rgba(255, 214, 10, 0.25);
        background: white;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        border: none;
        border-radius: 15px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        color: #333;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(255, 214, 10, 0.3);
        color: #333;
    }
    
    .alert {
        border-radius: 15px;
        border: none;
        padding: 20px;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #4ecdc4, #44a08d);
        color: white;
    }
    
    .numbering-card {
        background: rgba(255, 214, 10, 0.1);
        border: 2px solid rgba(255, 214, 10, 0.2);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .numbering-card:hover {
        border-color: rgba(255, 214, 10, 0.4);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 214, 10, 0.2);
    }
    
    .doc-type-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .preview-number {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: #333;
        padding: 10px 15px;
        border-radius: 10px;
        font-weight: 600;
        font-family: monospace;
        font-size: 1.1rem;
        margin-top: 10px;
        display: inline-block;
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
        color: #e2e8f0 !important;
        border-bottom-color: rgba(255, 255, 255, 0.1) !important;
    }
    
    [data-bs-theme="dark"] .numbering-card {
        background: rgba(255, 214, 10, 0.15) !important;
        border-color: rgba(255, 214, 10, 0.3) !important;
    }
    
    [data-bs-theme="dark"] .doc-type-title {
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .form-control,
    [data-bs-theme="dark"] .form-select {
        background: rgba(45, 55, 72, 0.8) !important;
        border-color: rgba(255, 214, 10, 0.3) !important;
        color: #e2e8f0 !important;
    }
    
    [data-bs-theme="dark"] .form-control:focus,
    [data-bs-theme="dark"] .form-select:focus {
        background: rgba(45, 55, 72, 0.9) !important;
        border-color: #ffd60a !important;
        color: #e2e8f0 !important;
    }
</style>

<div class="container-fluid config-container">
    <!-- Header -->
    <div class="config-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="config-title">
                    <i class="bi bi-sliders"></i> {{ __('app.system_settings') }}
                </h1>
            </div>
            <a href="{{ route('configurations.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> {{ __('app.back') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('configurations.settings.update') }}" method="POST">
        @csrf

        <!-- Numeratori Documenti -->
        <div class="config-card">
            <h3 class="section-title">
                <i class="bi bi-123"></i> Numeratori Documenti
            </h3>
            
            <!-- DDT -->
            <div class="numbering-card">
                <div class="doc-type-title">
                    <i class="bi bi-file-earmark-text"></i> DDT (Documenti di Trasporto)
                </div>
                
                <div class="row">
                    <div class="col-md-2">
                        <label class="form-label">Numero Corrente</label>
                        <input type="number" class="form-control" 
                               name="numbering[ddt][current_number]" 
                               value="{{ $numbering['ddt']->current_number ?? 1 }}" 
                               min="1">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Prefisso</label>
                        <input type="text" class="form-control" 
                               name="numbering[ddt][prefix]" 
                               value="{{ $numbering['ddt']->prefix ?? '' }}" 
                               placeholder="DDT">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Separatore</label>
                        <input type="text" class="form-control" 
                               name="numbering[ddt][separator]" 
                               value="{{ $numbering['ddt']->separator ?? '/' }}" 
                               placeholder="/">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Opzioni</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="numbering[ddt][use_year]" 
                                   {{ ($numbering['ddt']->use_year ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label">Usa Anno</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="numbering[ddt][use_month]" 
                                   {{ ($numbering['ddt']->use_month ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Usa Mese</label>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Anteprima</label>
                        <div class="preview-number" id="ddt-preview">
                            @php
                                $ddt = $numbering['ddt'] ?? (object)['current_number' => 1, 'prefix' => 'DDT', 'separator' => '/', 'use_year' => true, 'use_month' => false];
                                $parts = [];
                                if($ddt->prefix) $parts[] = $ddt->prefix;
                                $parts[] = str_pad($ddt->current_number, 4, '0', STR_PAD_LEFT);
                                if($ddt->use_month) $parts[] = date('m');
                                if($ddt->use_year) $parts[] = date('Y');
                                echo implode($ddt->separator, $parts);
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fatture -->
            <div class="numbering-card">
                <div class="doc-type-title">
                    <i class="bi bi-receipt"></i> Fatture
                </div>
                
                <div class="row">
                    <div class="col-md-2">
                        <label class="form-label">Numero Corrente</label>
                        <input type="number" class="form-control" 
                               name="numbering[fatture][current_number]" 
                               value="{{ $numbering['fatture']->current_number ?? 1 }}" 
                               min="1">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Prefisso</label>
                        <input type="text" class="form-control" 
                               name="numbering[fatture][prefix]" 
                               value="{{ $numbering['fatture']->prefix ?? '' }}" 
                               placeholder="FAT">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Separatore</label>
                        <input type="text" class="form-control" 
                               name="numbering[fatture][separator]" 
                               value="{{ $numbering['fatture']->separator ?? '/' }}" 
                               placeholder="/">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Opzioni</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="numbering[fatture][use_year]" 
                                   {{ ($numbering['fatture']->use_year ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label">Usa Anno</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="numbering[fatture][use_month]" 
                                   {{ ($numbering['fatture']->use_month ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Usa Mese</label>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Anteprima</label>
                        <div class="preview-number" id="fatture-preview">
                            @php
                                $fat = $numbering['fatture'] ?? (object)['current_number' => 1, 'prefix' => 'FAT', 'separator' => '/', 'use_year' => true, 'use_month' => false];
                                $parts = [];
                                if($fat->prefix) $parts[] = $fat->prefix;
                                $parts[] = str_pad($fat->current_number, 4, '0', STR_PAD_LEFT);
                                if($fat->use_month) $parts[] = date('m');
                                if($fat->use_year) $parts[] = date('Y');
                                echo implode($fat->separator, $parts);
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preventivi -->
            <div class="numbering-card">
                <div class="doc-type-title">
                    <i class="bi bi-file-earmark-check"></i> Preventivi
                </div>
                
                <div class="row">
                    <div class="col-md-2">
                        <label class="form-label">Numero Corrente</label>
                        <input type="number" class="form-control" 
                               name="numbering[preventivi][current_number]" 
                               value="{{ $numbering['preventivi']->current_number ?? 1 }}" 
                               min="1">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Prefisso</label>
                        <input type="text" class="form-control" 
                               name="numbering[preventivi][prefix]" 
                               value="{{ $numbering['preventivi']->prefix ?? '' }}" 
                               placeholder="PREV">
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">Separatore</label>
                        <input type="text" class="form-control" 
                               name="numbering[preventivi][separator]" 
                               value="{{ $numbering['preventivi']->separator ?? '/' }}" 
                               placeholder="/">
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Opzioni</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="numbering[preventivi][use_year]" 
                                   {{ ($numbering['preventivi']->use_year ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label">Usa Anno</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="numbering[preventivi][use_month]" 
                                   {{ ($numbering['preventivi']->use_month ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Usa Mese</label>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">Anteprima</label>
                        <div class="preview-number" id="preventivi-preview">
                            @php
                                $prev = $numbering['preventivi'] ?? (object)['current_number' => 1, 'prefix' => 'PREV', 'separator' => '/', 'use_year' => true, 'use_month' => false];
                                $parts = [];
                                if($prev->prefix) $parts[] = $prev->prefix;
                                $parts[] = str_pad($prev->current_number, 4, '0', STR_PAD_LEFT);
                                if($prev->use_month) $parts[] = date('m');
                                if($prev->use_year) $parts[] = date('Y');
                                echo implode($prev->separator, $parts);
                            @endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Impostazioni Generali -->
        <div class="config-card">
            <h3 class="section-title">
                <i class="bi bi-gear"></i> Impostazioni Generali
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="company_name_setting" class="form-label">Nome Azienda Predefinito</label>
                        <input type="text" class="form-control" id="company_name_setting"
                               name="settings[default_company_name]" 
                               value="{{ $settings['default_company_name']->value ?? '' }}"
                               placeholder="Nome della tua azienda">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="currency_setting" class="form-label">Valuta Predefinita</label>
                        <input type="text" class="form-control" id="currency_setting"
                               name="settings[default_currency]" 
                               value="{{ $settings['default_currency']->value ?? 'EUR' }}"
                               placeholder="EUR">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tax_rate_setting" class="form-label">Aliquota IVA Predefinita (%)</label>
                        <input type="number" class="form-control" id="tax_rate_setting"
                               name="settings[default_tax_rate]" 
                               value="{{ $settings['default_tax_rate']->value ?? '22' }}"
                               min="0" max="100" step="0.01">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="payment_terms_setting" class="form-label">Termini di Pagamento Predefiniti (giorni)</label>
                        <input type="number" class="form-control" id="payment_terms_setting"
                               name="settings[default_payment_terms]" 
                               value="{{ $settings['default_payment_terms']->value ?? '30' }}"
                               min="0" max="365">
                    </div>
                </div>
            </div>
        </div>

        <!-- Impostazioni di Sicurezza -->
        <div class="config-card">
            <h3 class="section-title">
                <i class="bi bi-shield-check"></i> Sicurezza e Backup
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="backup_frequency" class="form-label">Frequenza Backup Automatico</label>
                        <select class="form-select" id="backup_frequency" name="settings[backup_frequency]">
                            <option value="daily" {{ ($settings['backup_frequency']->value ?? 'daily') == 'daily' ? 'selected' : '' }}>Giornaliero</option>
                            <option value="weekly" {{ ($settings['backup_frequency']->value ?? 'daily') == 'weekly' ? 'selected' : '' }}>Settimanale</option>
                            <option value="monthly" {{ ($settings['backup_frequency']->value ?? 'daily') == 'monthly' ? 'selected' : '' }}>Mensile</option>
                            <option value="manual" {{ ($settings['backup_frequency']->value ?? 'daily') == 'manual' ? 'selected' : '' }}>Solo Manuale</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="session_timeout" class="form-label">Timeout Sessione (minuti)</label>
                        <input type="number" class="form-control" id="session_timeout"
                               name="settings[session_timeout]" 
                               value="{{ $settings['session_timeout']->value ?? '120' }}"
                               min="30" max="1440">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="enable_audit_log" 
                               name="settings[enable_audit_log]" value="1"
                               {{ ($settings['enable_audit_log']->value ?? '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="enable_audit_log">
                            <strong>Abilita Log di Audit</strong>
                            <small class="text-muted d-block">Traccia tutte le operazioni sensibili per conformità e sicurezza</small>
                        </label>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <button type="button" class="btn btn-outline-info w-100" onclick="createBackup()">
                        <i class="bi bi-download"></i> Crea Backup Manuale
                    </button>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-outline-success w-100" onclick="testSecuritySettings()">
                        <i class="bi bi-shield-check"></i> Test Impostazioni Sicurezza
                    </button>
                </div>
            </div>
        </div>

        <!-- Impostazioni Email -->
        <div class="config-card">
            <h3 class="section-title">
                <i class="bi bi-envelope-at"></i> Configurazione Email
            </h3>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="smtp_host" class="form-label">Server SMTP</label>
                        <input type="text" class="form-control" id="smtp_host"
                               name="settings[smtp_host]" 
                               value="{{ $settings['smtp_host']->value ?? '' }}"
                               placeholder="smtp.gmail.com">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="smtp_port" class="form-label">Porta SMTP</label>
                        <input type="number" class="form-control" id="smtp_port"
                               name="settings[smtp_port]" 
                               value="{{ $settings['smtp_port']->value ?? '587' }}"
                               min="1" max="65535">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="smtp_encryption" class="form-label">Crittografia</label>
                        <select class="form-select" id="smtp_encryption" name="settings[smtp_encryption]">
                            <option value="tls" {{ ($settings['smtp_encryption']->value ?? 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ ($settings['smtp_encryption']->value ?? 'tls') == 'ssl' ? 'selected' : '' }}>SSL</option>
                            <option value="none" {{ ($settings['smtp_encryption']->value ?? 'tls') == 'none' ? 'selected' : '' }}>Nessuna</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="smtp_username" class="form-label">Username SMTP</label>
                        <input type="text" class="form-control" id="smtp_username"
                               name="settings[smtp_username]" 
                               value="{{ $settings['smtp_username']->value ?? '' }}">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="smtp_password" class="form-label">Password SMTP</label>
                        <input type="password" class="form-control" id="smtp_password"
                               name="settings[smtp_password]" 
                               placeholder="Lascia vuoto per mantenere la password attuale">
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i> La password viene crittografata prima del salvataggio
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="mail_from_address" class="form-label">Email Mittente</label>
                        <input type="email" class="form-control" id="mail_from_address"
                               name="settings[mail_from_address]" 
                               value="{{ $settings['mail_from_address']->value ?? '' }}"
                               placeholder="noreply@tuaazienda.com">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="mail_from_name" class="form-label">Nome Mittente</label>
                        <input type="text" class="form-control" id="mail_from_name"
                               name="settings[mail_from_name]" 
                               value="{{ $settings['mail_from_name']->value ?? '' }}"
                               placeholder="La Tua Azienda">
                    </div>
                </div>
            </div>

            <div class="text-center mt-3">
                <button type="button" class="btn btn-outline-primary" onclick="testEmailConfiguration()">
                    <i class="bi bi-send"></i> Testa Configurazione Email
                </button>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="config-card text-center">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bi bi-check-circle"></i> {{ __('app.save_changes') }}
            </button>
        </div>
    </form>
</div>

<script>
// Aggiorna anteprima numerazione in tempo reale
document.addEventListener('DOMContentLoaded', function() {
    const documentTypes = ['ddt', 'fatture', 'preventivi'];
    
    documentTypes.forEach(docType => {
        const inputs = document.querySelectorAll(`[name*="[${docType}]"]`);
        inputs.forEach(input => {
            input.addEventListener('input', () => updatePreview(docType));
        });
    });
    
    function updatePreview(docType) {
        const prefix = document.querySelector(`[name="numbering[${docType}][prefix]"]`).value || '';
        const currentNumber = document.querySelector(`[name="numbering[${docType}][current_number]"]`).value || '1';
        const separator = document.querySelector(`[name="numbering[${docType}][separator]"]`).value || '/';
        const useYear = document.querySelector(`[name="numbering[${docType}][use_year]"]`).checked;
        const useMonth = document.querySelector(`[name="numbering[${docType}][use_month]"]`).checked;
        
        let parts = [];
        if (prefix) parts.push(prefix);
        parts.push(String(currentNumber).padStart(4, '0'));
        if (useMonth) parts.push(String(new Date().getMonth() + 1).padStart(2, '0'));
        if (useYear) parts.push(String(new Date().getFullYear()));
        
        const preview = parts.join(separator);
        document.getElementById(`${docType}-preview`).textContent = preview;
    }
});

// Funzioni per i pulsanti di azione
function createBackup() {
    if (confirm('Vuoi creare un backup del database? Questa operazione potrebbe richiedere alcuni minuti.')) {
        showNotification('Creazione backup in corso...', 'info');
        
        fetch('/configurations/backup', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Backup creato con successo!', 'success');
            } else {
                showNotification('Errore nella creazione del backup: ' + data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Errore durante la creazione del backup', 'error');
            console.error('Error:', error);
        });
    }
}

function testSecuritySettings() {
    showNotification('Test delle impostazioni di sicurezza in corso...', 'info');
    
    fetch('/configurations/test-security', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Test completato: ' + data.message, 'success');
        } else {
            showNotification('Test fallito: ' + data.message, 'warning');
        }
    })
    .catch(error => {
        showNotification('Errore durante il test di sicurezza', 'error');
        console.error('Error:', error);
    });
}

function testEmailConfiguration() {
    const emailForm = {
        smtp_host: document.getElementById('smtp_host').value,
        smtp_port: document.getElementById('smtp_port').value,
        smtp_username: document.getElementById('smtp_username').value,
        mail_from_address: document.getElementById('mail_from_address').value
    };
    
    if (!emailForm.smtp_host || !emailForm.smtp_port || !emailForm.mail_from_address) {
        showNotification('Compila tutti i campi obbligatori prima di testare', 'warning');
        return;
    }
    
    showNotification('Invio email di test in corso...', 'info');
    
    fetch('/configurations/test-email', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(emailForm)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Email di test inviata con successo!', 'success');
        } else {
            showNotification('Errore nell\'invio email: ' + data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Errore durante il test email', 'error');
        console.error('Error:', error);
    });
}

function showNotification(message, type) {
    // Creare e mostrare una notifica toast
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    toast.style.top = '20px';
    toast.style.right = '20px';
    toast.style.zIndex = '9999';
    toast.style.minWidth = '300px';
    
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Rimuovi automaticamente dopo 5 secondi
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 5000);
}
</script>
@endsection