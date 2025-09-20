@extends('layouts.app')

@section('title', __('app.ai_assistant') . ' - Gestionale Negozio')

@section('content')
<style>
    .ai-container {
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
    }
    
    .ai-status-badge {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .ai-status-badge.online {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        color: white;
    }
    
    .ai-status-badge.offline {
        background: linear-gradient(135deg, #f72585, #c5025a);
        color: white;
    }
    
    .modern-btn {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        border: none;
        border-radius: 15px;
        padding: 12px 24px;
        font-weight: 600;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        gap: 8px;
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
        box-shadow: 0 15px 35px rgba(2, 157, 126, 0.4);
        color: white;
    }
    
    .modern-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 2rem;
    }
    
    .modern-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }
    
    .stat-card.products::before {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
    }
    
    .stat-card.customers::before {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
    }
    
    .stat-card.sales::before {
        background: linear-gradient(135deg, #48cae4, #0077b6);
    }
    
    .stat-card.low-stock::before {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
    }
    
    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px rgba(2, 157, 126, 0.2);
    }
    
    .stat-icon {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        color: white;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
    }
    
    .stat-icon.success { background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%); }
    .stat-icon.info { background: linear-gradient(135deg, #48cae4, #0077b6); }
    .stat-icon.warning { background: linear-gradient(135deg, #ffd60a, #ff8500); }
    
    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        margin: 0;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 1.1rem;
        color: #6c757d;
        font-weight: 600;
        margin-top: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .chat-container {
        height: 500px;
        overflow-y: auto;
        padding: 2rem;
        background: linear-gradient(135deg, rgba(2, 157, 126, 0.03), rgba(118, 75, 162, 0.03));
        border-radius: 20px;
        margin-bottom: 1.5rem;
    }
    
    .chat-message {
        display: flex;
        margin-bottom: 2rem;
        animation: messageSlideIn 0.5s ease-out;
    }
    
    .chat-message.user-message {
        flex-direction: row-reverse;
    }
    
    .message-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        flex-shrink: 0;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
    
    .ai-message .message-avatar {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        margin-right: 1rem;
    }
    
    .user-message .message-avatar {
        
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        margin-left: 1rem;
    }
    
    .message-content {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        max-width: 75%;
        position: relative;
        font-size: 1rem;
        line-height: 1.6;
    }
    
    .ai-message .message-content::before {
        content: '';
        position: absolute;
        left: -8px;
        top: 20px;
        width: 0;
        height: 0;
        border-top: 8px solid transparent;
        border-bottom: 8px solid transparent;
        border-right: 8px solid rgba(255, 255, 255, 0.95);
    }
    
    .user-message .message-content {

        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        color: white;
    }
    
    .user-message .message-content::before {
        content: '';
        position: absolute;
        right: -8px;
        top: 20px;
        width: 0;
        height: 0;
        border-top: 8px solid transparent;
        border-bottom: 8px solid transparent;
        border-left: 8px solid #28a745;
    }
    
    .chat-input-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .chat-input {
        border: 2px solid rgba(2, 157, 126, 0.2);
        border-radius: 15px;
        padding: 15px 20px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        width: 100%;
    }
    
    .chat-input:focus {
        outline: none;
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
        background: white;
    }
    
    .send-btn {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        border: none;
        border-radius: 15px;
        padding: 15px 20px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-left: 10px;
    }
    
    .send-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(2, 157, 126, 0.4);
    }
    
    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .quick-action-btn {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(2, 157, 126, 0.2);
        border-radius: 20px;
        padding: 2rem 1rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .quick-action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        transition: all 0.3s ease;
        z-index: 0;
    }
    
    .quick-action-btn:hover::before {
        left: 0;
    }
    
    .quick-action-btn:hover {
        color: white;
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(2, 157, 126, 0.3);
    }
    
    .quick-action-btn i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }
    
    .quick-action-btn span {
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        z-index: 1;
    }
    
    .suggestion-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 1rem;
    }
    
    .suggestion-chip {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
    }
    
    .suggestion-chip:hover {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(2, 157, 126, 0.4);
    }
    
    .loading-message {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-style: italic;
        color: #6c757d;
    }
    
    .loading-dots {
        display: inline-flex;
        gap: 4px;
    }
    
    .loading-dots span {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        animation: loadingDots 1.4s infinite ease-in-out both;
    }
    
    .loading-dots span:nth-child(1) { animation-delay: -0.32s; }
    .loading-dots span:nth-child(2) { animation-delay: -0.16s; }
    
    .character-count {
        text-align: right;
        margin-top: 0.5rem;
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .section-title i {
        color: #029D7E;
        font-size: 1.5rem;
    }
    
    /* Dark Mode */
    [data-bs-theme="dark"] .page-header,
    [data-bs-theme="dark"] .modern-card,
    [data-bs-theme="dark"] .stat-card,
    [data-bs-theme="dark"] .chat-input-container,
    [data-bs-theme="dark"] .quick-action-btn {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .ai-message .message-content {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .ai-message .message-content::before {
        border-right-color: rgba(45, 55, 72, 0.95);
    }
    
    [data-bs-theme="dark"] .chat-input {
        background: rgba(45, 55, 72, 0.8);
        border-color: rgba(2, 157, 126, 0.3);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .chat-input:focus {
        background: rgba(45, 55, 72, 0.9);
    }
    
    [data-bs-theme="dark"] .stat-label {
        color: #a0aec0;
    }
    
    [data-bs-theme="dark"] .section-title {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .loading-message {
        color: #a0aec0;
    }
    
    [data-bs-theme="dark"] .character-count {
        color: #a0aec0;
    }
    
    /* Animations */
    @keyframes messageSlideIn {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes loadingDots {
        0%, 80%, 100% { 
            transform: scale(0.8); 
            opacity: 0.5; 
        }
        40% { 
            transform: scale(1.2); 
            opacity: 1; 
        }
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .ai-container {
            padding: 1rem;
        }
        
        .page-header {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .page-title {
            font-size: 2rem;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .stat-card {
            padding: 1.5rem;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .stat-number {
            font-size: 2rem;
        }
        
        .stat-label {
            font-size: 0.9rem;
        }
        
        .chat-container {
            height: 400px;
            padding: 1.5rem;
        }
        
        .message-content {
            max-width: 85%;
            padding: 1rem 1.5rem;
        }
        
        .quick-actions-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .quick-action-btn {
            padding: 1.5rem 0.75rem;
        }
        
        .quick-action-btn i {
            font-size: 2rem;
            margin-bottom: 0.75rem;
        }
        
        .quick-action-btn span {
            font-size: 0.8rem;
        }
    }
    
    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .page-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .chat-input-container .d-flex {
            flex-direction: column;
            gap: 1rem;
        }
        
        .send-btn {
            margin-left: 0;
            width: 100%;
        }
        
        .quick-actions-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="ai-container">
    <!-- Header della Pagina -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-robot me-3"></i>{{ __('app.ai_assistant') }}
                </h1>
                <p class="text-muted mb-0 mt-2">{{ __('app.ai_assistant_description') }}</p>
            </div>
            <div class="ai-status-badge" id="ai-status">
                <i class="bi bi-circle-fill me-2"></i>
                <span>Verificando stato...</span>
            </div>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card products">
            <div class="stat-icon">
                <i class="bi bi-box-seam"></i>
            </div>
            <h2 class="stat-number">{{ $stats['products'] }}</h2>
            <p class="stat-label">{{ __('app.products') }}</p>
        </div>
        <div class="stat-card customers">
            <div class="stat-icon success">
                <i class="bi bi-people"></i>
            </div>
            <h2 class="stat-number">{{ $stats['customers'] }}</h2>
            <p class="stat-label">{{ __('app.customers') }}</p>
        </div>
        <div class="stat-card sales">
            <div class="stat-icon info">
                <i class="bi bi-cart-check"></i>
            </div>
            <h2 class="stat-number">{{ $stats['sales'] }}</h2>
            <p class="stat-label">{{ __('app.sales') }}</p>
        </div>
        <div class="stat-card low-stock">
            <div class="stat-icon warning">
                <i class="bi bi-exclamation-triangle"></i>
            </div>
            <h2 class="stat-number">{{ $stats['low_stock'] }}</h2>
            <p class="stat-label">{{ __('app.low_stock') }}</p>
        </div>
    </div>
    
    <div class="row">
        <!-- Chat Interface -->
        <div class="col-12 col-lg-8">
            <div class="modern-card">
                <div class="p-3 border-bottom">
                    <h3 class="section-title mb-0">
                        <i class="bi bi-chat-dots"></i>{{ __('app.chat_with_ai') }}
                    </h3>
                </div>
                
                <!-- Chat Messages -->
                <div class="chat-container" id="chat-messages">
                    <div class="chat-message ai-message">
                        <div class="message-avatar">
                            <i class="bi bi-robot"></i>
                        </div>
                        <div class="message-content">
                            <p>Ciao! Sono il tuo assistente AI per il gestionale negozio. Posso aiutarti ad analizzare i dati, rispondere a domande sui prodotti, clienti, vendite e magazzino.</p>
                            <div style="margin-top: 1rem; padding: 1rem; background: rgba(2, 157, 126, 0.1); border-radius: 10px; font-size: 0.9rem;">
                                <strong>🆕 Novità: Comandi Calcolatrice Integrata!</strong><br>
                                • <code>calcola 25 + 30 * 2</code> - Calcoli complessi<br>
                                • <code>calcola 20% di 500</code> - Percentuali<br>
                                • <code>aggiungi 150</code> - Somma al risultato<br>
                                • <code>apri calcolatrice</code> - Mostra widget<br>
                                • <code>azzera</code> - Reset calcolatrice
                            </div>
                            <p style="margin-top: 1rem;">Come posso aiutarti oggi? 🚀</p>
                        </div>
                    </div>
                </div>
                
                <!-- Chat Input -->
                <div class="chat-input-container">
                    <div class="d-flex align-items-end gap-2">
                        <div class="flex-grow-1">
                            <textarea class="chat-input" id="user-message" rows="1" 
                            placeholder="Scrivi la tua domanda..." maxlength="500"
                            style="resize: none; min-height: 50px;"></textarea>
                            <div class="character-count">
                                <small><span id="char-count">0</span>/500 caratteri</small>
                            </div>
                        </div>
                        <button class="send-btn" type="button" id="send-message">
                            <i class="bi bi-send me-2"></i>Invia
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="col-12 col-lg-4">
            <div class="modern-card">
                <div class="p-3 border-bottom">
                    <h3 class="section-title mb-0">
                        <i class="bi bi-lightning"></i>{{ __('app.quick_actions') }}
                    </h3>
                </div>
                
                <div class="p-3">
                    <!-- Analysis Buttons -->
                    <div class="quick-actions-grid">
                        <button class="quick-action-btn" data-action="analyze" data-type="general">
                            <i class="bi bi-graph-up"></i>
                            <span>Analisi Generale</span>
                        </button>
                        <button class="quick-action-btn" data-action="analyze" data-type="products">
                            <i class="bi bi-box-seam"></i>
                            <span>Analisi Prodotti</span>
                        </button>
                        <button class="quick-action-btn" data-action="analyze" data-type="sales">
                            <i class="bi bi-cart-check"></i>
                            <span>Analisi Vendite</span>
                        </button>
                        <button class="quick-action-btn" data-action="analyze" data-type="customers">
                            <i class="bi bi-people"></i>
                            <span>Analisi Clienti</span>
                        </button>
                        <button class="quick-action-btn" data-action="analyze" data-type="inventory">
                            <i class="bi bi-boxes"></i>
                            <span>Analisi Magazzino</span>
                        </button>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Quick Questions -->
                    <h4 class="section-title">
                        <i class="bi bi-lightbulb"></i>{{ __('app.suggested_questions') }}
                    </h4>
                    <div class="suggestion-chips" id="suggestion-chips">
                        <!-- Loaded by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    // ===== AI CALCULATOR BRIDGE =====
    class AICalculatorBridge {
        constructor() {
            this.initCalculatorCommands();
        }
        
        initCalculatorCommands() {
            // Bridge per comunicazione AI → Calcolatrice
            window.aiCalculatorBridge = {
                calculate: (expression) => this.calculate(expression),
                percentage: (number, percent) => this.calculatePercentage(number, percent),
                add: (number) => this.addToResult(number),
                subtract: (number) => this.subtractFromResult(number),
                multiply: (number) => this.multiplyResult(number),
                divide: (number) => this.divideResult(number),
                clear: () => this.clearCalculator(),
                show: () => this.showCalculator()
            };
        }
        
        calculate(expression) {
            try {
                // Sanitize expression per sicurezza
                const sanitized = expression.replace(/[^0-9+\-*/().]/g, '');
                const result = Function('"use strict"; return (' + sanitized + ')')();
                
                // Aggiorna calcolatrice globale
                if (window.globalCalculatorCurrentInput !== undefined) {
                    window.globalCalculatorCurrentInput = result.toString();
                    window.globalUpdateCalculatorDisplay();
                }
                
                this.showCalculator();
                return result;
            } catch (e) {
                return 'Errore nel calcolo';
            }
        }
        
        calculatePercentage(number, percent) {
            const result = (number * percent) / 100;
            if (window.globalCalculatorCurrentInput !== undefined) {
                window.globalCalculatorCurrentInput = result.toString();
                window.globalUpdateCalculatorDisplay();
            }
            this.showCalculator();
            return result;
        }
        
        addToResult(number) {
            const current = parseFloat(window.globalCalculatorCurrentInput || '0');
            const result = current + number;
            window.globalCalculatorCurrentInput = result.toString();
            window.globalUpdateCalculatorDisplay();
            this.showCalculator();
            return result;
        }
        
        subtractFromResult(number) {
            const current = parseFloat(window.globalCalculatorCurrentInput || '0');
            const result = current - number;
            window.globalCalculatorCurrentInput = result.toString();
            window.globalUpdateCalculatorDisplay();
            this.showCalculator();
            return result;
        }
        
        multiplyResult(number) {
            const current = parseFloat(window.globalCalculatorCurrentInput || '0');
            const result = current * number;
            window.globalCalculatorCurrentInput = result.toString();
            window.globalUpdateCalculatorDisplay();
            this.showCalculator();
            return result;
        }
        
        divideResult(number) {
            if (number === 0) return 'Impossibile dividere per zero';
            const current = parseFloat(window.globalCalculatorCurrentInput || '0');
            const result = current / number;
            window.globalCalculatorCurrentInput = result.toString();
            window.globalUpdateCalculatorDisplay();
            this.showCalculator();
            return result;
        }
        
        clearCalculator() {
            if (window.globalClearAll) {
                window.globalClearAll();
            }
            this.showCalculator();
            return 'Calcolatrice azzerata';
        }
        
        showCalculator() {
            const calculator = document.getElementById('globalCalculatorWidget');
            if (calculator) {
                calculator.style.display = 'block';
            }
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Inizializza bridge DOPO che il DOM è caricato
        const calculatorBridge = new AICalculatorBridge();
        const chatMessages = document.getElementById('chat-messages');
        const userMessageInput = document.getElementById('user-message');
        const sendButton = document.getElementById('send-message');
        const aiStatus = document.getElementById('ai-status');
        const charCount = document.getElementById('char-count');
        const suggestionChips = document.getElementById('suggestion-chips');
        
        // Auto-resize textarea
        userMessageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
            charCount.textContent = this.value.length;
        });
        
        // Check AI status
        checkAIStatus();
        
        // Load suggestions
        loadSuggestions();
        
        // Send message on Enter (Ctrl+Enter for new line)
        userMessageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.ctrlKey) {
                e.preventDefault();
                sendMessage();
            }
        });
        
        // Send button click
        sendButton.addEventListener('click', sendMessage);
        
        // Quick action buttons
        document.querySelectorAll('.quick-action-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const action = this.dataset.action;
                const type = this.dataset.type;
                
                if (action === 'analyze') {
                    analyzeData(type);
                }
            });
        });
        
        function checkAIStatus() {
            fetch('/ai-assistant/status')
            .then(response => response.json())
            .then(data => {
                if (data.ai_available) {
                    aiStatus.className = 'ai-status-badge online';
                    aiStatus.innerHTML = '<i class="bi bi-circle-fill me-2"></i><span>AI Operativo</span>';
                } else {
                    aiStatus.className = 'ai-status-badge offline';
                    aiStatus.innerHTML = '<i class="bi bi-circle-fill me-2"></i><span>AI Non Disponibile</span>';
                }
            })
            .catch(error => {
                aiStatus.className = 'ai-status-badge offline';
                aiStatus.innerHTML = '<i class="bi bi-circle-fill me-2"></i><span>Errore Connessione</span>';
            });
        }
        
        function loadSuggestions() {
            fetch('/ai-assistant/suggestions')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const generalSuggestions = data.suggestions.general;
                    suggestionChips.innerHTML = '';
                    
                    generalSuggestions.forEach(suggestion => {
                        const chip = document.createElement('button');
                        chip.className = 'suggestion-chip';
                        chip.textContent = suggestion;
                        chip.addEventListener('click', () => {
                            userMessageInput.value = suggestion;
                            userMessageInput.style.height = 'auto';
                            userMessageInput.style.height = (userMessageInput.scrollHeight) + 'px';
                            charCount.textContent = suggestion.length;
                            sendMessage();
                        });
                        suggestionChips.appendChild(chip);
                    });
                }
            });
        }
        
        function sendMessage() {
            const message = userMessageInput.value.trim();
            if (!message) return;
            
            // Add user message to chat
            addMessage(message, 'user');
            
            // Clear input
            userMessageInput.value = '';
            userMessageInput.style.height = 'auto';
            charCount.textContent = '0';
            
            // Controlla se è un comando calcolatrice
            const calculatorResult = processCalculatorCommand(message);
            if (calculatorResult) {
                addMessage(calculatorResult, 'ai');
                return;
            }
            
            // Add loading message
            const loadingId = addLoadingMessage();
            
            // Send to AI
            fetch('/ai-assistant/ask', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    question: message,
                    context_type: 'general'
                })
            })
            .then(response => response.json())
            .then(data => {
                removeLoadingMessage(loadingId);
                
                if (data.success) {
                    addMessage(data.response, 'ai');
                } else {
                    addMessage('Scusa, si è verificato un errore: ' + (data.error || 'Errore sconosciuto'), 'ai');
                }
            })
            .catch(error => {
                removeLoadingMessage(loadingId);
                addMessage('Errore di connessione. Assicurati che l\'AI sia attiva.', 'ai');
            });
        }
        
        function analyzeData(type) {
            const loadingId = addLoadingMessage();
            
            fetch('/ai-assistant/analyze', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ type: type })
            })
            .then(response => response.json())
            .then(data => {
                removeLoadingMessage(loadingId);
                
                if (data.success) {
                    addMessage(data.response, 'ai');
                } else {
                    addMessage('Errore nell\'analisi: ' + (data.error || 'Errore sconosciuto'), 'ai');
                }
            })
            .catch(error => {
                removeLoadingMessage(loadingId);
                addMessage('Errore di connessione durante l\'analisi.', 'ai');
            });
        }
        
        function addMessage(message, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `chat-message ${type}-message`;
            
            const avatar = document.createElement('div');
            avatar.className = 'message-avatar';
            avatar.innerHTML = type === 'ai' ? '<i class="bi bi-robot"></i>' : '<i class="bi bi-person"></i>';
            
            const content = document.createElement('div');
            content.className = 'message-content';
            content.innerHTML = `<p>${message.replace(/\n/g, '<br>')}</p>`;
            
            messageDiv.appendChild(avatar);
            messageDiv.appendChild(content);
            
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        function addLoadingMessage() {
            const loadingId = 'loading-' + Date.now();
            const messageDiv = document.createElement('div');
            messageDiv.className = 'chat-message ai-message';
            messageDiv.id = loadingId;
            
            const avatar = document.createElement('div');
            avatar.className = 'message-avatar';
            avatar.innerHTML = '<i class="bi bi-robot"></i>';
            
            const content = document.createElement('div');
            content.className = 'message-content';
            content.innerHTML = `
            <div class="loading-message">
                <span>L'AI sta elaborando la risposta</span>
                <div class="loading-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        `;
            
            messageDiv.appendChild(avatar);
            messageDiv.appendChild(content);
            
            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            return loadingId;
        }
        
        function removeLoadingMessage(loadingId) {
            const loadingMsg = document.getElementById(loadingId);
            if (loadingMsg) {
                loadingMsg.remove();
            }
        }
        
        // ===== PARSER COMANDI CALCOLATRICE =====
        function processCalculatorCommand(message) {
            const msg = message.toLowerCase();
            
            // Comando: "calcola X + Y" o "calcola 15% di 1200"
            if (msg.includes('calcola')) {
                if (msg.includes('% di') || msg.includes('percento di')) {
                    // Estrai percentuale: "calcola il 15% di 1200" o "calcola 15% di 1200"
                    const match = msg.match(/calcola\s+(?:il\s+)?(\d+(?:\.\d+)?)%\s+di\s+(\d+(?:\.\d+)?)/);
                    if (match) {
                        const percent = parseFloat(match[1]);
                        const number = parseFloat(match[2]);
                        const result = window.aiCalculatorBridge.calculatePercentage(number, percent);
                        return `<i class="bi bi-calculator text-primary"></i> ${percent}% di ${number} = <strong>${result}</strong>`;
                    }
                } else {
                    // Estrai espressione: "calcola 25 + 30 * 2"
                    const match = msg.match(/calcola\s+(.+)/);
                    if (match) {
                        const expression = match[1].trim();
                        const result = window.aiCalculatorBridge.calculate(expression);
                        return `<i class="bi bi-calculator text-primary"></i> ${expression} = <strong>${result}</strong>`;
                    }
                }
            }
            
            // Comando: "aggiungi X" 
            if ((msg.includes('aggiungi') || msg.includes('somma')) && msg.match(/\d+/)) {
                const number = parseFloat(msg.match(/(\d+(?:\.\d+)?)/)[1]);
                const result = window.aiCalculatorBridge.add(number);
                return `<i class="bi bi-calculator text-primary"></i> Aggiunto ${number}. Risultato: <strong>${result}</strong>`;
            }
            
            // Comando: "sottrai X"
            if (msg.includes('sottrai') && msg.match(/\d+/)) {
                const number = parseFloat(msg.match(/(\d+(?:\.\d+)?)/)[1]);
                const result = window.aiCalculatorBridge.subtract(number);
                return `<i class="bi bi-calculator text-primary"></i> Sottratto ${number}. Risultato: <strong>${result}</strong>`;
            }
            
            // Comando: "moltiplica per X"
            if ((msg.includes('moltiplica') || msg.includes('per')) && msg.match(/\d+/)) {
                const number = parseFloat(msg.match(/(\d+(?:\.\d+)?)/)[1]);
                const result = window.aiCalculatorBridge.multiply(number);
                return `<i class="bi bi-calculator text-primary"></i> Moltiplicato per ${number}. Risultato: <strong>${result}</strong>`;
            }
            
            // Comando: "dividi per X"
            if (msg.includes('dividi') && msg.match(/\d+/)) {
                const number = parseFloat(msg.match(/(\d+(?:\.\d+)?)/)[1]);
                const result = window.aiCalculatorBridge.divide(number);
                return `<i class="bi bi-calculator text-primary"></i> Diviso per ${number}. Risultato: <strong>${result}</strong>`;
            }
            
            // Comando: "azzera calcolatrice" o "clear"
            if (msg.includes('azzera') || msg.includes('clear') || msg.includes('cancella')) {
                const result = window.aiCalculatorBridge.clear();
                return `<i class="bi bi-calculator text-primary"></i> ${result}`;
            }
            
            // Comando: "apri calcolatrice" o "mostra calcolatrice"
            if (msg.includes('apri calcolatrice') || msg.includes('mostra calcolatrice') || msg.includes('calculator')) {
                window.aiCalculatorBridge.show();
                return `<i class="bi bi-calculator text-primary"></i> Calcolatrice aperta! Puoi anche usare il tasto <strong>F9</strong> per aprirla velocemente.`;
            }
            
            return null; // Non è un comando calcolatrice
        }
        
        // Animation on load
        document.querySelectorAll('.stat-card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 200);
        });
    });
</script>
@endsection