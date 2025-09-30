<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archivio {{ $tipoPlural }} - {{ $dataExport }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #029D7E;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #029D7E;
            font-size: 20px;
            margin: 0 0 10px 0;
            font-weight: bold;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 12px;
        }
        
        .stats {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .stats h3 {
            margin: 0 0 10px 0;
            color: #029D7E;
            font-size: 14px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9px;
        }
        
        .table th {
            background: #029D7E;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #027d66;
        }
        
        .table td {
            padding: 6px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        .table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .table tbody tr:hover {
            background: #e8f5f0;
        }
        
        .code {
            font-family: 'Courier New', monospace;
            background: #e8f5f0;
            padding: 2px 4px;
            border-radius: 3px;
            font-weight: bold;
            color: #029D7E;
        }
        
        .status-active {
            color: #28a745;
            font-weight: bold;
        }
        
        .status-inactive {
            color: #dc3545;
            font-weight: bold;
        }
        
        .footer {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        /* Ottimizzazioni per la stampa */
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            
            .header {
                margin-bottom: 20px;
            }
            
            .table {
                font-size: 8px;
            }
            
            .table th,
            .table td {
                padding: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Archivio {{ $tipoPlural }}</h1>
        <p><strong>Data Export:</strong> {{ $dataExport }}</p>
        <p><strong>Sistema Gestionale Negozio Verde</strong></p>
    </div>

    <div class="stats">
        <h3>📈 Statistiche Archivio</h3>
        <p><strong>Totale {{ $tipoPlural }}:</strong> {{ $anagrafiche->count() }}</p>
        <p><strong>Attivi:</strong> {{ $anagrafiche->where('attivo', true)->count() }} | 
           <strong>Inattivi:</strong> {{ $anagrafiche->where('attivo', false)->count() }}</p>
    </div>

    @if($anagrafiche->count() > 0)
    <table class="table">
        <thead>
            <tr>
                @if($tipo === 'cliente' || $tipo === 'fornitore')
                    <th style="width: 12%">Codice</th>
                    <th style="width: 20%">Nome/Ragione Sociale</th>
                    <th style="width: 18%">Email</th>
                    <th style="width: 12%">Telefono</th>
                    <th style="width: 15%">Città</th>
                    <th style="width: 6%">Prov.</th>
                    <th style="width: 10%">P.IVA/CF</th>
                    <th style="width: 7%">Stato</th>
                @elseif($tipo === 'vettore')
                    <th style="width: 15%">Codice</th>
                    <th style="width: 25%">Nome Vettore</th>
                    <th style="width: 20%">Email</th>
                    <th style="width: 15%">Telefono</th>
                    <th style="width: 15%">Tipo Trasporto</th>
                    <th style="width: 10%">Stato</th>
                @elseif($tipo === 'agente')
                    <th style="width: 15%">Codice</th>
                    <th style="width: 25%">Nome Agente</th>
                    <th style="width: 20%">Email</th>
                    <th style="width: 15%">Telefono</th>
                    <th style="width: 15%">Tipo Contratto</th>
                    <th style="width: 10%">Stato</th>
                @elseif($tipo === 'articolo')
                    <th style="width: 15%">Codice</th>
                    <th style="width: 30%">Nome Articolo</th>
                    <th style="width: 20%">Categoria</th>
                    <th style="width: 15%">Prezzo Vendita</th>
                    <th style="width: 10%">Scorta Min.</th>
                    <th style="width: 10%">Stato</th>
                @else
                    <th style="width: 15%">Codice</th>
                    <th style="width: 30%">Nome Servizio</th>
                    <th style="width: 20%">Categoria</th>
                    <th style="width: 15%">Tariffa Oraria</th>
                    <th style="width: 10%">Durata Std.</th>
                    <th style="width: 10%">Stato</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($anagrafiche as $index => $anagrafica)
                @if($index > 0 && $index % 35 == 0)
                    </tbody>
                    </table>
                    <div class="page-break"></div>
                    <table class="table">
                        <thead>
                            <tr>
                                @if($tipo === 'cliente' || $tipo === 'fornitore')
                                    <th style="width: 12%">Codice</th>
                                    <th style="width: 20%">Nome/Ragione Sociale</th>
                                    <th style="width: 18%">Email</th>
                                    <th style="width: 12%">Telefono</th>
                                    <th style="width: 15%">Città</th>
                                    <th style="width: 6%">Prov.</th>
                                    <th style="width: 10%">P.IVA/CF</th>
                                    <th style="width: 7%">Stato</th>
                                @elseif($tipo === 'vettore')
                                    <th style="width: 15%">Codice</th>
                                    <th style="width: 25%">Nome Vettore</th>
                                    <th style="width: 20%">Email</th>
                                    <th style="width: 15%">Telefono</th>
                                    <th style="width: 15%">Tipo Trasporto</th>
                                    <th style="width: 10%">Stato</th>
                                @elseif($tipo === 'agente')
                                    <th style="width: 15%">Codice</th>
                                    <th style="width: 25%">Nome Agente</th>
                                    <th style="width: 20%">Email</th>
                                    <th style="width: 15%">Telefono</th>
                                    <th style="width: 15%">Tipo Contratto</th>
                                    <th style="width: 10%">Stato</th>
                                @elseif($tipo === 'articolo')
                                    <th style="width: 15%">Codice</th>
                                    <th style="width: 30%">Nome Articolo</th>
                                    <th style="width: 20%">Categoria</th>
                                    <th style="width: 15%">Prezzo Vendita</th>
                                    <th style="width: 10%">Scorta Min.</th>
                                    <th style="width: 10%">Stato</th>
                                @else
                                    <th style="width: 15%">Codice</th>
                                    <th style="width: 30%">Nome Servizio</th>
                                    <th style="width: 20%">Categoria</th>
                                    <th style="width: 15%">Tariffa Oraria</th>
                                    <th style="width: 10%">Durata Std.</th>
                                    <th style="width: 10%">Stato</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                @endif
                
                <tr>
                    @if($tipo === 'cliente' || $tipo === 'fornitore')
                        <td><span class="code">{{ $anagrafica->codice_interno }}</span></td>
                        <td><strong>{{ $anagrafica->nome_completo }}</strong></td>
                        <td>{{ $anagrafica->email ?? '-' }}</td>
                        <td>{{ $anagrafica->telefono_1 ?? '-' }}</td>
                        <td>{{ $anagrafica->comune ?? '-' }}</td>
                        <td>{{ $anagrafica->provincia ?? '-' }}</td>
                        <td>{{ $anagrafica->partita_iva ?? $anagrafica->codice_fiscale ?? '-' }}</td>
                        <td><span class="{{ $anagrafica->attivo ? 'status-active' : 'status-inactive' }}">
                            {{ $anagrafica->attivo ? 'Attivo' : 'Inattivo' }}
                        </span></td>
                    @elseif($tipo === 'vettore')
                        <td><span class="code">{{ $anagrafica->codice_interno }}</span></td>
                        <td><strong>{{ $anagrafica->nome_completo }}</strong></td>
                        <td>{{ $anagrafica->email ?? '-' }}</td>
                        <td>{{ $anagrafica->telefono_1 ?? '-' }}</td>
                        <td>{{ $anagrafica->tipo_trasporto ?? '-' }}</td>
                        <td><span class="{{ $anagrafica->attivo ? 'status-active' : 'status-inactive' }}">
                            {{ $anagrafica->attivo ? 'Attivo' : 'Inattivo' }}
                        </span></td>
                    @elseif($tipo === 'agente')
                        <td><span class="code">{{ $anagrafica->codice_interno }}</span></td>
                        <td><strong>{{ $anagrafica->nome_completo }}</strong></td>
                        <td>{{ $anagrafica->email ?? '-' }}</td>
                        <td>{{ $anagrafica->telefono_1 ?? '-' }}</td>
                        <td>{{ $anagrafica->tipo_contratto ?? '-' }}</td>
                        <td><span class="{{ $anagrafica->attivo ? 'status-active' : 'status-inactive' }}">
                            {{ $anagrafica->attivo ? 'Attivo' : 'Inattivo' }}
                        </span></td>
                    @elseif($tipo === 'articolo')
                        <td><span class="code">{{ $anagrafica->codice_articolo ?? $anagrafica->codice_interno }}</span></td>
                        <td><strong>{{ $anagrafica->nome }}</strong></td>
                        <td>{{ $anagrafica->categoria_articolo ?? '-' }}</td>
                        <td>€{{ number_format($anagrafica->prezzo_vendita ?? 0, 2) }}</td>
                        <td>{{ $anagrafica->scorta_minima ?? 0 }}</td>
                        <td><span class="{{ $anagrafica->attivo ? 'status-active' : 'status-inactive' }}">
                            {{ $anagrafica->attivo ? 'Attivo' : 'Inattivo' }}
                        </span></td>
                    @else
                        <td><span class="code">{{ $anagrafica->codice_servizio ?? $anagrafica->codice_interno }}</span></td>
                        <td><strong>{{ $anagrafica->nome }}</strong></td>
                        <td>{{ $anagrafica->categoria_servizio ?? '-' }}</td>
                        <td>€{{ number_format($anagrafica->tariffa_oraria ?? 0, 2) }}</td>
                        <td>{{ $anagrafica->durata_standard_minuti ? $anagrafica->durata_standard_minuti . ' min' : '-' }}</td>
                        <td><span class="{{ $anagrafica->attivo ? 'status-active' : 'status-inactive' }}">
                            {{ $anagrafica->attivo ? 'Attivo' : 'Inattivo' }}
                        </span></td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 8px;">
        <h3 style="color: #666;">📋 Nessun elemento trovato</h3>
        <p>Non sono presenti {{ strtolower($tipoPlural) }} nell'archivio.</p>
    </div>
    @endif

    <div class="footer">
        <p>🌱 <strong>Sistema Gestionale Negozio Verde</strong> | Export generato il {{ $dataExport }} | Pagina <span class="pagenum"></span></p>
    </div>
</body>
</html>