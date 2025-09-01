<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>DDT {{ $ddt->numero_ddt }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        .info-box {
            width: 48%;
            border: 1px solid #ddd;
            padding: 10px;
        }
        .info-box h4 {
            margin: 0 0 10px 0;
            background: #f5f5f5;
            padding: 5px;
            margin: -10px -10px 10px -10px;
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
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .total-row {
            background-color: #f9f9f9;
            font-weight: bold;
        }
        .badge {
            background: #6c757d;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="display: table; width: 100%;">
            <div style="display: table-cell; width: 30%; vertical-align: top;">
                <img src="{{ public_path('finson_logo.png') }}" alt="Finson Logo" style="max-width: 120px; height: auto;">
            </div>
            <div style="display: table-cell; width: 70%; text-align: center; vertical-align: middle;">
                <h1>DOCUMENTO DI TRASPORTO</h1>
                <h2>{{ $ddt->numero_ddt }}</h2>
            </div>
        </div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <div class="info-box">
                <h4>Informazioni DDT</h4>
                <strong>Numero:</strong> {{ $ddt->numero_ddt }}<br>
                <strong>Data:</strong> {{ $ddt->data_ddt->format('d/m/Y') }}<br>
                <strong>Causale:</strong> {{ $ddt->causale }}<br>
                <strong>Stato:</strong> {{ ucfirst($ddt->stato) }}
                @if($ddt->trasportatore)
                    <br><strong>Trasportatore:</strong> {{ $ddt->trasportatore }}
                @endif
            </div>
            
            <div class="info-box">
                <h4>Cliente</h4>
                <strong>{{ $ddt->cliente->nome_completo }}</strong><br>
                {{ $ddt->cliente->indirizzo }}<br>
                {{ $ddt->cliente->cap }} {{ $ddt->cliente->citta }}
                @if($ddt->cliente->telefono)
                    <br>Tel: {{ $ddt->cliente->telefono }}
                @endif
                @if($ddt->cliente->email)
                    <br>Email: {{ $ddt->cliente->email }}
                @endif
            </div>
        </div>

        <div class="info-box">
            <h4>Destinatario</h4>
            <strong>{{ $ddt->destinatario_completo }}</strong><br>
            {{ $ddt->indirizzo_completo }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Prodotto</th>
                <th>Taglia</th>
                <th>Colore</th>
                <th class="text-right">Quantità</th>
                <th class="text-right">Prezzo Unit.</th>
                <th class="text-right">Subtotale</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ddt->vendita->dettagli as $dettaglio)
            <tr>
                <td>{{ $dettaglio->prodotto->nome }}</td>
                <td><span class="badge">{{ $dettaglio->taglia }}</span></td>
                <td><span class="badge">{{ $dettaglio->colore }}</span></td>
                <td class="text-right">{{ $dettaglio->quantita }}</td>
                <td class="text-right">€{{ number_format($dettaglio->prezzo_unitario, 2, ',', '.') }}</td>
                <td class="text-right">€{{ number_format($dettaglio->subtotale, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5"><strong>TOTALE DDT</strong></td>
                <td class="text-right"><strong>€{{ number_format($ddt->vendita->totale_finale, 2, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>

    @if($ddt->note)
    <div style="margin-top: 20px;">
        <h4>Note:</h4>
        <p>{{ $ddt->note }}</p>
    </div>
    @endif

    <div style="margin-top: 40px; font-size: 10px; color: #666;">
        <p>Documento generato il {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>