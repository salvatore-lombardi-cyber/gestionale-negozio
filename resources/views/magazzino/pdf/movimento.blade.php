<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movimento Magazzino #{{ $movimento->id }} - {{ ucfirst($movimento->tipo_movimento) }}</title>
    <style>
        @page {
            margin: 25mm 20mm 20mm 20mm;
            @bottom-center {
                content: "Pagina " counter(page) " di " counter(pages);
            }
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            background: #ffffff;
            padding: 0 10mm;
        }
        
        .header {
            border-bottom: 3px solid #029D7E;
            padding-bottom: 15px;
            margin-bottom: 25px;
            display: table;
            width: 100%;
        }
        
        .logo-container {
            display: table-cell;
            width: 150px;
            vertical-align: middle;
        }
        
        .logo {
            max-width: 140px;
            max-height: 60px;
            height: auto;
        }
        
        .header-info {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            padding-left: 20px;
        }
        
        .document-title {
            font-size: 16px;
            color: #029D7E;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .document-subtitle {
            font-size: 12px;
            color: #666;
            font-weight: normal;
        }
        
        .movimento-info {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 20px;
            margin: 0 5mm 20px 5mm;
        }
        
        .tipo-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
            color: white;
        }
        
        .badge-carico {
            background: #10b981;
        }
        
        .badge-scarico {
            background: #ef4444;
        }
        
        .badge-trasferimento {
            background: #f59e0b;
        }
        
        .main-info {
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 5px;
        }
        
        .movimento-id {
            font-size: 12px;
            color: #666;
            font-weight: bold;
        }
        
        .sections {
            display: block;
            margin: 0 5mm;
        }
        
        .section {
            margin-bottom: 25px;
            break-inside: avoid;
        }
        
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #029D7E;
            border-bottom: 2px solid #029D7E;
            padding-bottom: 5px;
            margin-bottom: 12px;
            text-transform: uppercase;
        }
        
        .data-grid {
            display: table;
            width: 100%;
        }
        
        .data-row {
            display: table-row;
            margin-bottom: 8px;
        }
        
        .data-label {
            display: table-cell;
            width: 30%;
            font-weight: bold;
            color: #495057;
            padding: 3px 10px 3px 0;
            vertical-align: top;
        }
        
        .data-value {
            display: table-cell;
            width: 70%;
            padding: 3px 0;
            border-bottom: 1px solid #e9ecef;
            vertical-align: top;
        }
        
        .quantita-value {
            font-weight: bold;
            font-size: 14px;
        }
        
        .quantita-positiva {
            color: #10b981;
        }
        
        .quantita-negativa {
            color: #ef4444;
        }
        
        .footer {
            position: fixed;
            bottom: 15mm;
            left: 20mm;
            right: 20mm;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 9px;
            color: #666;
            text-align: center;
        }
        
        .empty-value {
            color: #999;
            font-style: italic;
        }
        
        .two-columns {
            display: table;
            width: 100%;
            table-layout: fixed;
        }
        
        .column {
            display: table-cell;
            width: 50%;
            padding-right: 10px;
        }
        
        .column:last-child {
            padding-right: 0;
            padding-left: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo-container">
            <img src="{{ public_path('finson_logo.png') }}" alt="Finson Logo" class="logo">
        </div>
        <div class="header-info">
            <div class="document-title">Movimento di Magazzino</div>
            <div class="document-subtitle">#{{ $movimento->id }}</div>
        </div>
    </div>
    
    <!-- Informazioni principali movimento -->
    <div class="movimento-info">
        @if($movimento->tipo_movimento === 'carico')
            <div class="tipo-badge badge-carico">Carico</div>
        @elseif($movimento->tipo_movimento === 'scarico')
            <div class="tipo-badge badge-scarico">Scarico</div>
        @elseif(in_array($movimento->tipo_movimento, ['trasferimento_uscita', 'trasferimento_ingresso']))
            <div class="tipo-badge badge-trasferimento">
                {{ $movimento->tipo_movimento === 'trasferimento_uscita' ? 'Trasferimento Uscita' : 'Trasferimento Ingresso' }}
            </div>
        @else
            <div class="tipo-badge" style="background: #6c757d;">{{ ucfirst($movimento->tipo_movimento) }}</div>
        @endif
        
        <div class="main-info">
            @if($movimento->prodotto)
                {{ $movimento->prodotto->descrizione }}
            @else
                Movimento #{{ $movimento->id }}
            @endif
        </div>
        <div class="movimento-id">Data: {{ $movimento->data_movimento ? $movimento->data_movimento->format('d/m/Y') : 'Non specificata' }}</div>
    </div>
    
    <div class="sections">
        <!-- Dati Movimento -->
        <div class="section">
            <div class="section-title">📋 Dati Movimento</div>
            <div class="data-grid">
                <div class="data-row">
                    <div class="data-label">Tipo Movimento:</div>
                    <div class="data-value">{{ ucfirst(str_replace('_', ' ', $movimento->tipo_movimento)) }}</div>
                </div>
                <div class="data-row">
                    <div class="data-label">Data Movimento:</div>
                    <div class="data-value">{{ $movimento->data_movimento ? $movimento->data_movimento->format('d/m/Y') : 'Non specificata' }}</div>
                </div>
                <div class="data-row">
                    <div class="data-label">Causale:</div>
                    <div class="data-value">{{ $movimento->causale ? $movimento->causale->descrizione : 'Non specificata' }}</div>
                </div>
                <div class="data-row">
                    <div class="data-label">Quantità:</div>
                    <div class="data-value">
                        <span class="quantita-value {{ in_array($movimento->tipo_movimento, ['carico', 'trasferimento_ingresso']) ? 'quantita-positiva' : 'quantita-negativa' }}">
                            {{ in_array($movimento->tipo_movimento, ['carico', 'trasferimento_ingresso']) ? '+' : '-' }}{{ number_format($movimento->quantita, 0) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Articolo -->
        @if($movimento->prodotto)
        <div class="section">
            <div class="section-title">📦 Articolo</div>
            <div class="data-grid">
                <div class="data-row">
                    <div class="data-label">Descrizione:</div>
                    <div class="data-value">{{ $movimento->prodotto->descrizione }}</div>
                </div>
                @if($movimento->prodotto->codice_articolo || $movimento->prodotto->codice_interno)
                <div class="data-row">
                    <div class="data-label">Codice:</div>
                    <div class="data-value">{{ $movimento->prodotto->codice_articolo ?? $movimento->prodotto->codice_interno }}</div>
                </div>
                @endif
                @if($movimento->prodotto->unita_misura)
                <div class="data-row">
                    <div class="data-label">Unità di Misura:</div>
                    <div class="data-value">{{ $movimento->prodotto->unita_misura }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Deposito -->
        @if($movimento->deposito)
        <div class="section">
            <div class="section-title">🏢 Deposito</div>
            <div class="data-grid">
                <div class="data-row">
                    <div class="data-label">Deposito:</div>
                    <div class="data-value">{{ $movimento->deposito->description }}</div>
                </div>
                @if($movimento->deposito->address)
                <div class="data-row">
                    <div class="data-label">Indirizzo:</div>
                    <div class="data-value">{{ $movimento->deposito->address }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Trasferimento tra depositi -->
        @if(in_array($movimento->tipo_movimento, ['trasferimento_uscita', 'trasferimento_ingresso']))
        <div class="section">
            <div class="section-title">🔄 Dettagli Trasferimento</div>
            <div class="data-grid">
                @if($movimento->depositoSorgente)
                <div class="data-row">
                    <div class="data-label">Deposito Sorgente:</div>
                    <div class="data-value">{{ $movimento->depositoSorgente->description }}</div>
                </div>
                @endif
                @if($movimento->depositoDestinazione)
                <div class="data-row">
                    <div class="data-label">Deposito Destinazione:</div>
                    <div class="data-value">{{ $movimento->depositoDestinazione->description }}</div>
                </div>
                @endif
                @if($movimento->movimento_collegato_uuid)
                <div class="data-row">
                    <div class="data-label">UUID Collegamento:</div>
                    <div class="data-value">{{ $movimento->movimento_collegato_uuid }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Cliente/Fornitore -->
        @if($movimento->cliente || $movimento->fornitore)
        <div class="section">
            <div class="section-title">👥 {{ $movimento->cliente ? 'Cliente' : 'Fornitore' }}</div>
            <div class="data-grid">
                @if($movimento->cliente)
                <div class="data-row">
                    <div class="data-label">Cliente:</div>
                    <div class="data-value">{{ $movimento->cliente->descrizione }}</div>
                </div>
                @if($movimento->cliente->codice_interno)
                <div class="data-row">
                    <div class="data-label">Codice Cliente:</div>
                    <div class="data-value">{{ $movimento->cliente->codice_interno }}</div>
                </div>
                @endif
                @endif
                
                @if($movimento->fornitore)
                <div class="data-row">
                    <div class="data-label">Fornitore:</div>
                    <div class="data-value">{{ $movimento->fornitore->descrizione }}</div>
                </div>
                @if($movimento->fornitore->codice_interno)
                <div class="data-row">
                    <div class="data-label">Codice Fornitore:</div>
                    <div class="data-value">{{ $movimento->fornitore->codice_interno }}</div>
                </div>
                @endif
                @endif
            </div>
        </div>
        @endif
        
        <!-- Informazioni Sistema -->
        <div class="section">
            <div class="section-title">ℹ️ Informazioni Sistema</div>
            <div class="data-grid">
                @if($movimento->user)
                <div class="data-row">
                    <div class="data-label">Registrato da:</div>
                    <div class="data-value">{{ $movimento->user->name ?? 'Utente non trovato' }}</div>
                </div>
                @endif
                <div class="data-row">
                    <div class="data-label">Data Registrazione:</div>
                    <div class="data-value">{{ $movimento->created_at ? $movimento->created_at->format('d/m/Y H:i:s') : 'Non disponibile' }}</div>
                </div>
                @if($movimento->uuid)
                <div class="data-row">
                    <div class="data-label">UUID Movimento:</div>
                    <div class="data-value">{{ $movimento->uuid }}</div>
                </div>
                @endif
                @if($movimento->note)
                <div class="data-row">
                    <div class="data-label">Note:</div>
                    <div class="data-value">{{ $movimento->note }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <div>Generato il {{ now()->format('d/m/Y H:i') }} - Gestionale Negozio Verde</div>
    </div>
</body>
</html>