<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fattura {{ $numeroFormattato }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 2px solid #029D7E;
            padding-bottom: 15px;
        }
        
        .logo-section {
            display: table-cell;
            width: 30%;
            vertical-align: top;
        }
        
        .company-section {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            padding-left: 20px;
        }
        
        .invoice-section {
            display: table-cell;
            width: 30%;
            text-align: right;
            vertical-align: top;
        }
        
        .logo {
            max-width: 120px;
            height: auto;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #029D7E;
            margin-bottom: 8px;
        }
        
        .company-details {
            font-size: 10px;
            line-height: 1.4;
            color: #666;
        }
        
        .invoice-title {
            font-size: 20px;
            font-weight: bold;
            color: #029D7E;
            margin-bottom: 10px;
        }
        
        .invoice-number {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .invoice-date {
            font-size: 11px;
            color: #666;
            margin-bottom: 10px;
        }
        
        .invoice-type {
            background: #029D7E;
            color: white;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            display: inline-block;
        }
        
        .info-section {
            margin-bottom: 20px;
        }
        
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .info-box {
            display: table-cell;
            width: 48%;
            border: 1px solid #ddd;
            padding: 12px;
            vertical-align: top;
        }
        
        .info-box:first-child {
            margin-right: 4%;
        }
        
        .info-box h4 {
            margin: 0 0 10px 0;
            background: #f5f5f5;
            padding: 8px;
            margin: -12px -12px 10px -12px;
            font-size: 12px;
            color: #029D7E;
            font-weight: bold;
        }
        
        .info-field {
            margin-bottom: 5px;
            font-size: 11px;
        }
        
        .info-label {
            font-weight: bold;
            color: #666;
            display: inline-block;
            width: 80px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #029D7E;
            color: white;
            font-weight: bold;
            font-size: 11px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .total-row {
            background-color: #f9f9f9;
            font-weight: bold;
        }
        
        .grand-total {
            background-color: #029D7E;
            color: white;
            font-weight: bold;
        }
        
        .empty-items {
            text-align: center;
            padding: 30px;
            color: #666;
            font-style: italic;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(2, 157, 126, 0.1);
            font-weight: bold;
            z-index: -1;
        }
        
        .notes-section {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #029D7E;
        }
        
        .notes-title {
            font-weight: bold;
            color: #029D7E;
            margin-bottom: 8px;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    @if($tipo === 'potenziale')
        <div class="watermark">BOZZA</div>
    @endif

    <div class="header">
        <div class="logo-section">
            <img src="{{ public_path('finson_logo.png') }}" alt="Finson Logo" class="logo">
        </div>
        
        <div class="company-section">
            <div class="company-name">{{ $azienda['nome'] }}</div>
            <div class="company-details">
                {{ $azienda['indirizzo'] }}<br>
                {{ $azienda['citta'] }}<br>
                P.IVA: {{ $azienda['partita_iva'] }}<br>
                C.F.: {{ $azienda['codice_fiscale'] }}<br>
                Tel: {{ $azienda['telefono'] }}<br>
                Email: {{ $azienda['email'] }}
                @if($azienda['pec'])
                    <br>PEC: {{ $azienda['pec'] }}
                @endif
            </div>
        </div>
        
        <div class="invoice-section">
            <div class="invoice-title">FATTURA</div>
            <div class="invoice-number">N. {{ $numeroFormattato }}</div>
            <div class="invoice-date">Data: {{ $dataEmissione }}</div>
            <div class="invoice-type">
                {{ $tipo === 'definitivo' ? 'Definitiva' : 'Potenziale' }}
            </div>
        </div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <div class="info-box">
                <h4>Informazioni Fattura</h4>
                <div class="info-field">
                    <span class="info-label">Numero:</span> {{ $numeroFormattato }}
                </div>
                <div class="info-field">
                    <span class="info-label">Data:</span> {{ $dataEmissione }}
                </div>
                <div class="info-field">
                    <span class="info-label">Tipo:</span> {{ $tipo === 'definitivo' ? 'Definitiva' : 'Potenziale' }}
                </div>
                <div class="info-field">
                    <span class="info-label">Pagamento:</span> {{ $fattura->metodo_pagamento ?? 'Da definire' }}
                </div>
            </div>
            
            <div class="info-box">
                <h4>Cliente</h4>
                @if($fattura->cliente)
                    <div class="info-field">
                        <strong>{{ $fattura->cliente->nome }} {{ $fattura->cliente->cognome }}</strong>
                    </div>
                    @if($fattura->cliente->email)
                        <div class="info-field">Email: {{ $fattura->cliente->email }}</div>
                    @endif
                    @if($fattura->cliente->telefono)
                        <div class="info-field">Tel: {{ $fattura->cliente->telefono }}</div>
                    @endif
                @else
                    <div class="info-field"><strong>Cliente occasionale</strong></div>
                @endif
                
                @if(!empty($datiCliente))
                    @if(!empty($datiCliente['indirizzo']))
                        <div class="info-field">{{ $datiCliente['indirizzo'] }}</div>
                    @endif
                    @if(!empty($datiCliente['citta']))
                        <div class="info-field">
                            {{ $datiCliente['citta'] }}
                            @if(!empty($datiCliente['cap'])) {{ $datiCliente['cap'] }} @endif
                            @if(!empty($datiCliente['provincia'])) ({{ $datiCliente['provincia'] }}) @endif
                        </div>
                    @endif
                    @if(!empty($datiCliente['codice_fiscale']))
                        <div class="info-field">C.F.: {{ $datiCliente['codice_fiscale'] }}</div>
                    @endif
                    @if(!empty($datiCliente['partita_iva']))
                        <div class="info-field">P.IVA: {{ $datiCliente['partita_iva'] }}</div>
                    @endif
                    @if(!empty($datiCliente['codice_sdi']))
                        <div class="info-field">Cod. SDI: {{ $datiCliente['codice_sdi'] }}</div>
                    @endif
                    @if(!empty($datiCliente['pec']))
                        <div class="info-field">PEC: {{ $datiCliente['pec'] }}</div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Descrizione</th>
                <th class="text-right">Quantità</th>
                <th class="text-right">Prezzo Unit.</th>
                <th class="text-right">IVA</th>
                <th class="text-right">Totale</th>
            </tr>
        </thead>
        <tbody>
            <!-- Placeholder per articoli futuri -->
            <tr>
                <td colspan="5">
                    <div class="empty-items">
                        <p><strong>Nessun articolo specificato</strong></p>
                        <p>I dettagli degli articoli verranno visualizzati qui quando saranno aggiunti alla fattura.</p>
                        <p><em>Per aggiungere articoli, modifica la fattura dalla dashboard.</em></p>
                    </div>
                </td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4"><strong>Subtotale</strong></td>
                <td class="text-right"><strong>€{{ number_format($fattura->subtotale ?? 0, 2, ',', '.') }}</strong></td>
            </tr>
            <tr class="total-row">
                <td colspan="4"><strong>IVA</strong></td>
                <td class="text-right"><strong>€{{ number_format($fattura->iva ?? 0, 2, ',', '.') }}</strong></td>
            </tr>
            @if($fattura->sconto && $fattura->sconto > 0)
            <tr class="total-row">
                <td colspan="4"><strong>Sconto</strong></td>
                <td class="text-right"><strong>-€{{ number_format($fattura->sconto, 2, ',', '.') }}</strong></td>
            </tr>
            @endif
            <tr class="grand-total">
                <td colspan="4"><strong>TOTALE FATTURA</strong></td>
                <td class="text-right"><strong>€{{ number_format($fattura->totale, 2, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    @if($fattura->note)
    <div class="notes-section">
        <div class="notes-title">Note:</div>
        <div>{{ $fattura->note }}</div>
    </div>
    @endif

    <div class="footer">
        <p>Documento generato automaticamente il {{ now()->format('d/m/Y H:i') }} - Valido ai sensi di legge</p>
    </div>
</body>
</html>