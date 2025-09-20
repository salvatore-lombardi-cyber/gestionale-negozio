@extends('layouts.app')

@section('title', 'Nuova Vendita - Gestionale Negozio')

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
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
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
        color: #029D7E;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .modern-input {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid rgba(2, 157, 126, 0.2);
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        margin-bottom: 0.5rem;
    }
    
    .modern-input:focus {
        outline: none;
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
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
        border: 2px solid rgba(2, 157, 126, 0.2);
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        margin-bottom: 0.5rem;
        cursor: pointer;
    }
    
    .modern-select:focus {
        outline: none;
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
        background: white;
        transform: translateY(-2px);
    }
    
    .modern-select.is-invalid {
        border-color: #f72585;
        box-shadow: 0 0 0 0.2rem rgba(247, 37, 133, 0.25);
    }
    
    .modern-textarea {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid rgba(2, 157, 126, 0.2);
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
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
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
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        border: none;
        border-radius: 15px;
        padding: 15px 30px;
        font-weight: 600;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
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
    
    .modern-btn.danger {
        background: linear-gradient(135deg, #f72585, #c5025a);
        padding: 10px 15px;
    }
    
    .modern-btn.danger:hover {
        box-shadow: 0 15px 35px rgba(247, 37, 133, 0.4);
    }
    
    .product-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(2, 157, 126, 0.1);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .product-card:hover {
        border-color: rgba(2, 157, 126, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }
    
    .product-card.first-product {
        border-color: rgba(2, 157, 126, 0.3);
        background: rgba(2, 157, 126, 0.05);
    }
    
    .invalid-feedback {
        color: #f72585;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        font-weight: 500;
    }
    
    .payment-methods {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
        margin-top: 0.5rem;
    }
    
    .payment-method {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(2, 157, 126, 0.2);
        border-radius: 15px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .payment-method:hover {
        border-color: #029D7E;
        background: rgba(2, 157, 126, 0.1);
        transform: translateY(-2px);
    }
    
    .payment-method.selected {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        border-color: transparent;
    }
    
    .payment-method input[type="radio"] {
        display: none;
    }
    
    .payment-method .icon {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .total-card {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 20px 40px rgba(72, 202, 228, 0.3);
    }
    
    .total-amount {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 1rem 0;
    }
    
    .stock-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 5px 10px;
        border-radius: 10px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .stock-high {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.3);
    }
    
    .stock-medium {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.3);
    }
    
    .stock-low {
        background: rgba(247, 37, 133, 0.1);
        color: #f72585;
        border: 1px solid rgba(247, 37, 133, 0.3);
    }
    
    /* Dark Mode */
    [data-bs-theme="dark"] .page-header,
    [data-bs-theme="dark"] .form-card,
    [data-bs-theme="dark"] .product-card {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .modern-input,
    [data-bs-theme="dark"] .modern-textarea,
    [data-bs-theme="dark"] .modern-select {
        background: rgba(45, 55, 72, 0.8);
        border-color: rgba(2, 157, 126, 0.3);
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
    
    [data-bs-theme="dark"] .payment-method {
        background: rgba(45, 55, 72, 0.8);
        color: #e2e8f0;
        border-color: rgba(2, 157, 126, 0.3);
    }
    
    [data-bs-theme="dark"] .payment-method:hover {
        background: rgba(2, 157, 126, 0.2);
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
        
        .product-card {
            padding: 1rem;
        }
        
        .total-amount {
            font-size: 2rem;
        }
    }
</style>

<div class="sales-container">
    <!-- Header della Pagina -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">
                <i class="bi bi-cart-plus"></i> Nuova Vendita
            </h1>
            <a href="{{ route('vendite.index') }}" class="modern-btn">
                <i class="bi bi-arrow-left"></i> Indietro
            </a>
        </div>
    </div>
    
    <!-- Form Card -->
    <div class="form-card">
        <form action="{{ route('vendite.store') }}" method="POST" id="vendita-form">
            @csrf
            
            <!-- Informazioni Vendita -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="bi bi-info-circle"></i> Informazioni Vendita
                </h3>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="cliente_id" class="form-label">Cliente</label>
                            <select class="modern-select @error('cliente_id') is-invalid @enderror" 
                            id="cliente_id" name="cliente_id">
                            <option value="">Cliente occasionale</option>
                            @foreach($clienti as $cliente)
                            <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nome_completo }}
                            </option>
                            @endforeach
                        </select>
                        @error('cliente_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="data_vendita" class="form-label">Data Vendita <span class="required">*</span></label>
                        <input type="date" class="modern-input @error('data_vendita') is-invalid @enderror" 
                        id="data_vendita" name="data_vendita" value="{{ old('data_vendita', date('Y-m-d')) }}" required>
                        @error('data_vendita')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Metodo Pagamento <span class="required">*</span></label>
                        <div class="payment-methods">
                            <div class="payment-method" onclick="selectPayment('contanti', this)">
                                <input type="radio" name="metodo_pagamento" value="contanti" {{ old('metodo_pagamento') == 'contanti' ? 'checked' : '' }} required>
                                <span class="icon">💵</span>
                                <div>Contanti</div>
                            </div>
                            <div class="payment-method" onclick="selectPayment('carta', this)">
                                <input type="radio" name="metodo_pagamento" value="carta" {{ old('metodo_pagamento') == 'carta' ? 'checked' : '' }} required>
                                <span class="icon">💳</span>
                                <div>Carta</div>
                            </div>
                            <div class="payment-method" onclick="selectPayment('bonifico', this)">
                                <input type="radio" name="metodo_pagamento" value="bonifico" {{ old('metodo_pagamento') == 'bonifico' ? 'checked' : '' }} required>
                                <span class="icon">🏦</span>
                                <div>Bonifico</div>
                            </div>
                            <div class="payment-method" onclick="selectPayment('assegno', this)">
                                <input type="radio" name="metodo_pagamento" value="assegno" {{ old('metodo_pagamento') == 'assegno' ? 'checked' : '' }} required>
                                <span class="icon">📝</span>
                                <div>Assegno</div>
                            </div>
                        </div>
                        @error('metodo_pagamento')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Prodotti -->
        <div class="form-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="section-title">
                    <i class="bi bi-box-seam"></i> Prodotti
                </h3>
                <button type="button" class="modern-btn success" id="aggiungi-prodotto">
                    <i class="bi bi-plus-circle"></i> Aggiungi Prodotto
                </button>
            </div>
            
            <div id="prodotti-container">
                <div class="product-card first-product prodotto-row">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Prodotto <span class="required">*</span></label>
                            <select class="modern-select prodotto-select" name="prodotti[0][id]" required>
                                <option value="">Seleziona prodotto...</option>
                                @foreach($prodotti as $prodotto)
                                <option value="{{ $prodotto->id }}" data-prezzo="{{ $prodotto->prezzo }}">
                                    {{ $prodotto->nome }} - €{{ number_format($prodotto->prezzo, 2) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Taglia <span class="required">*</span></label>
                            <select class="modern-select taglia-select" name="prodotti[0][taglia]" required disabled>
                                <option value="">Prima scegli prodotto</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Colore <span class="required">*</span></label>
                            <select class="modern-select colore-select" name="prodotti[0][colore]" required disabled>
                                <option value="">Prima scegli taglia</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Disp.</label>
                            <input type="text" class="modern-input disponibili-display" readonly placeholder="0">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Qtà <span class="required">*</span></label>
                            <input type="number" class="modern-input quantita-input" name="prodotti[0][quantita]" min="1" max="0" value="1" required disabled>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Subtotale</label>
                            <input type="text" class="modern-input subtotale-display" readonly>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="modern-btn danger rimuovi-prodotto" disabled>
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Note e Totale -->
        <div class="form-section">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="section-title">
                        <i class="bi bi-chat-text"></i> Note Aggiuntive
                    </h3>
                    <div class="mb-3">
                        <label for="note" class="form-label">Note</label>
                        <textarea class="modern-textarea" id="note" name="note" rows="3" placeholder="Inserisci note aggiuntive per questa vendita...">{{ old('note') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="sconto" class="form-label">Sconto %</label>
                        <input type="number" class="modern-input" id="sconto" name="sconto" step="0.01" min="0" max="100" value="{{ old('sconto', 0) }}" placeholder="0.00">
                    </div>
                </div>
                <div class="col-md-6">
                    <h3 class="section-title">
                        <i class="bi bi-calculator"></i> Riepilogo
                    </h3>
                    <div class="total-card">
                        <div>
                            <i class="bi bi-currency-euro" style="font-size: 2rem;"></i>
                        </div>
                        <div class="total-amount" id="totale-finale">0.00</div>
                        <div>Totale Vendita</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pulsanti Azione -->
        <div class="d-flex gap-3 justify-content-end">
            <a href="{{ route('vendite.index') }}" class="modern-btn secondary">
                <i class="bi bi-arrow-left"></i> Annulla
            </a>
            <button type="submit" class="modern-btn">
                <i class="bi bi-check-circle"></i> Registra Vendita
            </button>
        </div>
    </form>
</div>
</div>

<script>
    // Dati del magazzino dal PHP
    const magazzinoData = @json($magazzino);
    let prodottoIndex = 1;
    
    // Gestione selezione metodo pagamento
    function selectPayment(value, element) {
        // Rimuovi selezione da tutti
        document.querySelectorAll('.payment-method').forEach(method => {
            method.classList.remove('selected');
        });
        
        // Aggiungi selezione al cliccato
        element.classList.add('selected');
        
        // Seleziona il radio button
        element.querySelector('input[type="radio"]').checked = true;
    }
    
    document.getElementById('aggiungi-prodotto').addEventListener('click', function() {
        const container = document.getElementById('prodotti-container');
        const firstRow = document.querySelector('.prodotto-row');
        const newRow = firstRow.cloneNode(true);
        
        // Rimuovi la classe first-product
        newRow.classList.remove('first-product');
        
        // Aggiorna i nomi dei campi
        newRow.querySelectorAll('input, select').forEach(function(input) {
            const name = input.name;
            if (name) {
                input.name = name.replace('[0]', '[' + prodottoIndex + ']');
                if (input.type !== 'number') {
                    input.value = '';
                } else {
                    input.value = '1';
                }
            }
            if (input.classList.contains('subtotale-display') || input.classList.contains('disponibili-display')) {
                input.value = '';
            }
            // Reset dei select
            if (input.classList.contains('taglia-select') || input.classList.contains('colore-select') || input.classList.contains('quantita-input')) {
                input.disabled = true;
            }
        });
        
        // Reset delle opzioni
        newRow.querySelector('.taglia-select').innerHTML = '<option value="">Prima scegli prodotto</option>';
        newRow.querySelector('.colore-select').innerHTML = '<option value="">Prima scegli taglia</option>';
        newRow.querySelector('.prodotto-select').selectedIndex = 0;
        
        // Abilita il pulsante rimuovi
        newRow.querySelector('.rimuovi-prodotto').disabled = false;
        
        container.appendChild(newRow);
        prodottoIndex++;
        
        // Animazione di entrata
        setTimeout(() => {
            newRow.style.opacity = '0';
            newRow.style.transform = 'translateY(20px)';
            newRow.style.transition = 'all 0.5s ease';
            
            setTimeout(() => {
                newRow.style.opacity = '1';
                newRow.style.transform = 'translateY(0)';
            }, 100);
        }, 10);
        
        // Aggiungi event listeners
        aggiungiEventListeners(newRow);
    });
    
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('rimuovi-prodotto') || e.target.closest('.rimuovi-prodotto')) {
            const row = e.target.closest('.prodotto-row');
            
            // Animazione di uscita
            row.style.opacity = '0';
            row.style.transform = 'translateY(-20px)';
            
            setTimeout(() => {
                row.remove();
                calcolaTotale();
            }, 300);
        }
    });
    
    function aggiungiEventListeners(row) {
        const prodottoSelect = row.querySelector('.prodotto-select');
        const tagliaSelect = row.querySelector('.taglia-select');
        const coloreSelect = row.querySelector('.colore-select');
        const quantitaInput = row.querySelector('.quantita-input');
        const subtotaleDisplay = row.querySelector('.subtotale-display');
        const disponibiliDisplay = row.querySelector('.disponibili-display');
        
        prodottoSelect.addEventListener('change', function() {
            const prodottoId = this.value;
            
            // Reset
            tagliaSelect.innerHTML = '<option value="">Seleziona taglia...</option>';
            coloreSelect.innerHTML = '<option value="">Prima scegli taglia</option>';
            tagliaSelect.disabled = !prodottoId;
            coloreSelect.disabled = true;
            quantitaInput.disabled = true;
            disponibiliDisplay.value = '';
            
            if (prodottoId && magazzinoData[prodottoId]) {
                // Popola le taglie disponibili
                const taglie = [...new Set(magazzinoData[prodottoId].map(item => item.taglia))];
                taglie.forEach(taglia => {
                    const option = document.createElement('option');
                    option.value = taglia;
                    option.textContent = taglia;
                    tagliaSelect.appendChild(option);
                });
            }
            
            calcolaSubtotale(row);
        });
        
        tagliaSelect.addEventListener('change', function() {
            const prodottoId = prodottoSelect.value;
            const taglia = this.value;
            
            // Reset
            coloreSelect.innerHTML = '<option value="">Seleziona colore...</option>';
            coloreSelect.disabled = !taglia;
            quantitaInput.disabled = true;
            disponibiliDisplay.value = '';
            
            if (prodottoId && taglia && magazzinoData[prodottoId]) {
                // Popola i colori disponibili per questa taglia
                const colori = magazzinoData[prodottoId]
                .filter(item => item.taglia === taglia)
                .map(item => item.colore);
                
                colori.forEach(colore => {
                    const option = document.createElement('option');
                    option.value = colore;
                    option.textContent = colore;
                    coloreSelect.appendChild(option);
                });
            }
            
            calcolaSubtotale(row);
        });
        
        coloreSelect.addEventListener('change', function() {
            const prodottoId = prodottoSelect.value;
            const taglia = tagliaSelect.value;
            const colore = this.value;
            
            quantitaInput.disabled = !colore;
            
            if (prodottoId && taglia && colore && magazzinoData[prodottoId]) {
                // Trova la quantità disponibile
                const item = magazzinoData[prodottoId].find(i => i.taglia === taglia && i.colore === colore);
                if (item) {
                    disponibiliDisplay.value = item.quantita;
                    quantitaInput.max = item.quantita;
                    quantitaInput.value = Math.min(1, item.quantita);
                    
                    // Aggiungi indicatore stock
                    let stockClass = 'stock-high';
                    if (item.quantita <= 5) stockClass = 'stock-low';
                    else if (item.quantita <= 15) stockClass = 'stock-medium';
                    
                    disponibiliDisplay.className = `modern-input disponibili-display stock-indicator ${stockClass}`;
                }
            }
            
            calcolaSubtotale(row);
        });
        
        quantitaInput.addEventListener('input', function() {
            calcolaSubtotale(row);
        });
    }
    
    function calcolaSubtotale(row) {
        const prodottoSelect = row.querySelector('.prodotto-select');
        const quantitaInput = row.querySelector('.quantita-input');
        const subtotaleDisplay = row.querySelector('.subtotale-display');
        
        const selectedOption = prodottoSelect.options[prodottoSelect.selectedIndex];
        const prezzo = parseFloat(selectedOption.dataset.prezzo || 0);
        const quantita = parseInt(quantitaInput.value || 0);
        const subtotale = prezzo * quantita;
        
        subtotaleDisplay.value = '€' + subtotale.toFixed(2);
        calcolaTotale();
    }
    
    function calcolaTotale() {
        let totale = 0;
        document.querySelectorAll('.prodotto-row').forEach(function(row) {
            const prodottoSelect = row.querySelector('.prodotto-select');
            const quantitaInput = row.querySelector('.quantita-input');
            const selectedOption = prodottoSelect.options[prodottoSelect.selectedIndex];
            const prezzo = parseFloat(selectedOption.dataset.prezzo || 0);
            const quantita = parseInt(quantitaInput.value || 0);
            totale += prezzo * quantita;
        });
        
        const scontoPercentuale = parseFloat(document.getElementById('sconto').value || 0);
        const scontoEuro = (totale * scontoPercentuale) / 100;
        const totaleFinale = totale - scontoEuro;
        document.getElementById('totale-finale').textContent = totaleFinale.toFixed(2);
        
        // Animazione del totale quando cambia
        const totaleElement = document.getElementById('totale-finale');
        totaleElement.style.transform = 'scale(1.1)';
        setTimeout(() => {
            totaleElement.style.transform = 'scale(1)';
        }, 200);
    }
    
    // Aggiungi event listeners alla prima riga
    document.addEventListener('DOMContentLoaded', function() {
        aggiungiEventListeners(document.querySelector('.prodotto-row'));
        document.getElementById('sconto').addEventListener('input', calcolaTotale);
        
        // Inizializza metodo pagamento selezionato se presente
        const selectedPayment = document.querySelector('input[name="metodo_pagamento"]:checked');
        if (selectedPayment) {
            selectedPayment.closest('.payment-method').classList.add('selected');
        }
        
        // Animazioni di caricamento pagina
        const elements = document.querySelectorAll('.form-card, .page-header');
        elements.forEach((element, index) => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            element.style.transition = 'all 0.6s ease';
            
            setTimeout(() => {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }, index * 200);
        });
        
        // Validazione form avanzata
        const form = document.getElementById('vendita-form');
        form.addEventListener('submit', function(e) {
            // Controlla che ci sia almeno un prodotto valido
            const prodottiValidi = Array.from(document.querySelectorAll('.prodotto-row')).filter(row => {
                const prodottoSelect = row.querySelector('.prodotto-select');
                const tagliaSelect = row.querySelector('.taglia-select');
                const coloreSelect = row.querySelector('.colore-select');
                const quantitaInput = row.querySelector('.quantita-input');
                
                return prodottoSelect.value && tagliaSelect.value && coloreSelect.value && quantitaInput.value > 0;
            });
            
            if (prodottiValidi.length === 0) {
                e.preventDefault();
                
                // Mostra alert moderno
                const alertHtml = `
               <div class="alert alert-warning alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; background: linear-gradient(135deg, #ffd60a, #ff8500); color: white; border: none; border-radius: 15px; box-shadow: 0 15px 35px rgba(255, 133, 0, 0.4);">
                   <strong><i class="bi bi-exclamation-triangle"></i> Attenzione!</strong><br>
                   Devi aggiungere almeno un prodotto valido alla vendita!
                   <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
               </div>
           `;
                document.body.insertAdjacentHTML('beforeend', alertHtml);
                
                // Scorri al primo prodotto
                document.querySelector('.prodotto-row').scrollIntoView({ behavior: 'smooth' });
                
                return false;
            }
            
            // Controlla metodo pagamento
            const metodoPagamento = document.querySelector('input[name="metodo_pagamento"]:checked');
            if (!metodoPagamento) {
                e.preventDefault();
                
                const alertHtml = `
               <div class="alert alert-warning alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; background: linear-gradient(135deg, #ffd60a, #ff8500); color: white; border: none; border-radius: 15px; box-shadow: 0 15px 35px rgba(255, 133, 0, 0.4);">
                   <strong><i class="bi bi-exclamation-triangle"></i> Attenzione!</strong><br>
                   Seleziona un metodo di pagamento!
                   <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
               </div>
           `;
                document.body.insertAdjacentHTML('beforeend', alertHtml);
                
                // Scorri ai metodi di pagamento
                document.querySelector('.payment-methods').scrollIntoView({ behavior: 'smooth' });
                
                return false;
            }
            
            // Animazione submit
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Registrando Vendita...';
            submitBtn.disabled = true;
            
            // Animazione loading sulla card totale
            const totalCard = document.querySelector('.total-card');
            totalCard.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
            totalCard.style.transform = 'scale(1.05)';
            
            setTimeout(() => {
                totalCard.style.transform = 'scale(1)';
            }, 300);
        });
        
        // Auto-calcolo totale iniziale
        calcolaTotale();
    });
    
    // Funzioni di utilità per migliorare UX
    function evidenziaErrore(elemento) {
        elemento.style.animation = 'shake 0.5s ease-in-out';
        setTimeout(() => {
            elemento.style.animation = '';
        }, 500);
    }
    
    // Aggiungi CSS per animazione shake
    const shakeCSS = `
@keyframes shake {
   0%, 100% { transform: translateX(0); }
   25% { transform: translateX(-5px); }
   75% { transform: translateX(5px); }
}
`;
    
    const style = document.createElement('style');
    style.textContent = shakeCSS;
    document.head.appendChild(style);
</script>
@endsection