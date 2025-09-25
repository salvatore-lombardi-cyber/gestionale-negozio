<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anagrafica {{ ucfirst($anagrafica->tipo) }} - {{ $anagrafica->nome_completo }}</title>
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
        
        .anagrafica-info {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 5px;
            padding: 20px;
            margin: 0 5mm 20px 5mm;
        }
        
        .tipo-badge {
            display: inline-block;
            background: #029D7E;
            color: white;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        
        .main-name {
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 5px;
        }
        
        .codice-interno {
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
        
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-attivo {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        
        .status-inattivo {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
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
            <div class="document-title">Scheda Anagrafica</div>
            <div class="document-subtitle">{{ ucfirst($anagrafica->tipo) }}</div>
        </div>
    </div>
    
    <!-- Informazioni principali -->
    <div class="anagrafica-info">
        <div class="tipo-badge">{{ ucfirst($anagrafica->tipo) }}</div>
        <div class="main-name">{{ $anagrafica->nome_completo }}</div>
        <div class="codice-interno">Codice: {{ $anagrafica->codice_interno }}</div>
    </div>
    
    <div class="sections">
        <!-- Dati Base -->
        <div class="section">
            <div class="section-title">📋 Dati Base</div>
            <div class="data-grid">
                <div class="data-row">
                    <div class="data-label">Nome:</div>
                    <div class="data-value">{{ $anagrafica->nome ?: 'Non specificato' }}</div>
                </div>
                @if($anagrafica->cognome)
                <div class="data-row">
                    <div class="data-label">Cognome:</div>
                    <div class="data-value">{{ $anagrafica->cognome }}</div>
                </div>
                @endif
                @if($anagrafica->tipo_soggetto)
                <div class="data-row">
                    <div class="data-label">Tipo Soggetto:</div>
                    <div class="data-value">{{ ucfirst($anagrafica->tipo_soggetto) }}</div>
                </div>
                @endif
                <div class="data-row">
                    <div class="data-label">Stato:</div>
                    <div class="data-value">
                        <span class="status-badge {{ $anagrafica->attivo ? 'status-attivo' : 'status-inattivo' }}">
                            {{ $anagrafica->attivo ? 'Attivo' : 'Inattivo' }}
                        </span>
                    </div>
                </div>
                @if($anagrafica->altro)
                <div class="data-row">
                    <div class="data-label">Note:</div>
                    <div class="data-value">{{ $anagrafica->altro }}</div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Indirizzo -->
        @if($anagrafica->indirizzo || $anagrafica->comune || $anagrafica->provincia)
        <div class="section">
            <div class="section-title">📍 Indirizzo</div>
            <div class="data-grid">
                @if($anagrafica->indirizzo)
                <div class="data-row">
                    <div class="data-label">Via/Piazza:</div>
                    <div class="data-value">{{ $anagrafica->indirizzo }}</div>
                </div>
                @endif
                <div class="two-columns">
                    <div class="column">
                        @if($anagrafica->cap)
                        <div class="data-row">
                            <div class="data-label">CAP:</div>
                            <div class="data-value">{{ $anagrafica->cap }}</div>
                        </div>
                        @endif
                    </div>
                    <div class="column">
                        @if($anagrafica->comune)
                        <div class="data-row">
                            <div class="data-label">Comune:</div>
                            <div class="data-value">{{ $anagrafica->comune }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="two-columns">
                    <div class="column">
                        @if($anagrafica->provincia)
                        <div class="data-row">
                            <div class="data-label">Provincia:</div>
                            <div class="data-value">{{ strtoupper($anagrafica->provincia) }}</div>
                        </div>
                        @endif
                    </div>
                    <div class="column">
                        <div class="data-row">
                            <div class="data-label">Nazione:</div>
                            <div class="data-value">{{ $anagrafica->nazione ?: 'Italia' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Contatti -->
        @if($anagrafica->telefono_1 || $anagrafica->email || $anagrafica->pec)
        <div class="section">
            <div class="section-title">📞 Contatti</div>
            <div class="data-grid">
                @if($anagrafica->telefono_1)
                <div class="data-row">
                    <div class="data-label">Telefono 1:</div>
                    <div class="data-value">{{ $anagrafica->telefono_1 }}</div>
                </div>
                @endif
                @if($anagrafica->telefono_2)
                <div class="data-row">
                    <div class="data-label">Telefono 2:</div>
                    <div class="data-value">{{ $anagrafica->telefono_2 }}</div>
                </div>
                @endif
                @if($anagrafica->email)
                <div class="data-row">
                    <div class="data-label">Email:</div>
                    <div class="data-value">{{ $anagrafica->email }}</div>
                </div>
                @endif
                @if($anagrafica->pec)
                <div class="data-row">
                    <div class="data-label">PEC:</div>
                    <div class="data-value">{{ $anagrafica->pec }}</div>
                </div>
                @endif
                @if($anagrafica->sito_web)
                <div class="data-row">
                    <div class="data-label">Sito Web:</div>
                    <div class="data-value">{{ $anagrafica->sito_web }}</div>
                </div>
                @endif
                @if($anagrafica->contatto_referente)
                <div class="data-row">
                    <div class="data-label">Referente:</div>
                    <div class="data-value">{{ $anagrafica->contatto_referente }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Dati Fiscali -->
        @if($anagrafica->codice_fiscale || $anagrafica->partita_iva)
        <div class="section">
            <div class="section-title">📋 Dati Fiscali</div>
            <div class="data-grid">
                @if($anagrafica->codice_fiscale)
                <div class="data-row">
                    <div class="data-label">Codice Fiscale:</div>
                    <div class="data-value">{{ strtoupper($anagrafica->codice_fiscale) }}</div>
                </div>
                @endif
                @if($anagrafica->partita_iva)
                <div class="data-row">
                    <div class="data-label">Partita IVA:</div>
                    <div class="data-value">{{ $anagrafica->partita_iva }}</div>
                </div>
                @endif
                @if($anagrafica->codice_sdi)
                <div class="data-row">
                    <div class="data-label">Codice SDI:</div>
                    <div class="data-value">{{ $anagrafica->codice_sdi }}</div>
                </div>
                @endif
                @if($anagrafica->is_pubblica_amministrazione)
                <div class="data-row">
                    <div class="data-label">P.A.:</div>
                    <div class="data-value">{{ $anagrafica->is_pubblica_amministrazione ? 'Sì' : 'No' }}</div>
                </div>
                @endif
                @if($anagrafica->split_payment)
                <div class="data-row">
                    <div class="data-label">Split Payment:</div>
                    <div class="data-value">{{ $anagrafica->split_payment ? 'Sì' : 'No' }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Dati Specifici per Tipo -->
        @if($anagrafica->tipo === 'fornitore' && ($anagrafica->categoria_merceologica || $anagrafica->lead_time_giorni))
        <div class="section">
            <div class="section-title">🏢 Dati Fornitore</div>
            <div class="data-grid">
                @if($anagrafica->categoria_merceologica)
                <div class="data-row">
                    <div class="data-label">Categoria:</div>
                    <div class="data-value">{{ $anagrafica->categoria_merceologica }}</div>
                </div>
                @endif
                @if($anagrafica->lead_time_giorni)
                <div class="data-row">
                    <div class="data-label">Lead Time:</div>
                    <div class="data-value">{{ $anagrafica->lead_time_giorni }} giorni</div>
                </div>
                @endif
                @if($anagrafica->condizioni_pagamento)
                <div class="data-row">
                    <div class="data-label">Condizioni Pagamento:</div>
                    <div class="data-value">{{ $anagrafica->condizioni_pagamento }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        @if($anagrafica->tipo === 'articolo')
        <div class="section">
            <div class="section-title">📦 Dati Articolo</div>
            <div class="data-grid">
                @if($anagrafica->categoria_articolo)
                <div class="data-row">
                    <div class="data-label">Categoria:</div>
                    <div class="data-value">{{ $anagrafica->categoria_articolo }}</div>
                </div>
                @endif
                @if($anagrafica->prezzo_vendita)
                <div class="data-row">
                    <div class="data-label">Prezzo Vendita:</div>
                    <div class="data-value">€ {{ number_format($anagrafica->prezzo_vendita, 2, ',', '.') }}</div>
                </div>
                @endif
                @if($anagrafica->prezzo_acquisto)
                <div class="data-row">
                    <div class="data-label">Prezzo Acquisto:</div>
                    <div class="data-value">€ {{ number_format($anagrafica->prezzo_acquisto, 2, ',', '.') }}</div>
                </div>
                @endif
                @if($anagrafica->scorta_minima)
                <div class="data-row">
                    <div class="data-label">Scorta Minima:</div>
                    <div class="data-value">{{ $anagrafica->scorta_minima }}</div>
                </div>
                @endif
                @if($anagrafica->unita_misura)
                <div class="data-row">
                    <div class="data-label">Unità di Misura:</div>
                    <div class="data-value">{{ $anagrafica->unita_misura }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        @if($anagrafica->tipo === 'servizio')
        <div class="section">
            <div class="section-title">🔧 Dati Servizio</div>
            <div class="data-grid">
                @if($anagrafica->categoria_servizio)
                <div class="data-row">
                    <div class="data-label">Categoria:</div>
                    <div class="data-value">{{ $anagrafica->categoria_servizio }}</div>
                </div>
                @endif
                @if($anagrafica->tariffa_oraria)
                <div class="data-row">
                    <div class="data-label">Tariffa Oraria:</div>
                    <div class="data-value">€ {{ number_format($anagrafica->tariffa_oraria, 2, ',', '.') }}</div>
                </div>
                @endif
                @if($anagrafica->durata_standard_minuti)
                <div class="data-row">
                    <div class="data-label">Durata Standard:</div>
                    <div class="data-value">{{ $anagrafica->durata_standard_minuti }} minuti</div>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Coordinate Bancarie -->
        @if($anagrafica->banca || $anagrafica->iban)
        <div class="section">
            <div class="section-title">🏦 Coordinate Bancarie</div>
            <div class="data-grid">
                @if($anagrafica->banca)
                <div class="data-row">
                    <div class="data-label">Banca:</div>
                    <div class="data-value">{{ $anagrafica->banca }}</div>
                </div>
                @endif
                @if($anagrafica->iban)
                <div class="data-row">
                    <div class="data-label">IBAN:</div>
                    <div class="data-value">{{ strtoupper($anagrafica->iban) }}</div>
                </div>
                @endif
                @if($anagrafica->swift)
                <div class="data-row">
                    <div class="data-label">SWIFT:</div>
                    <div class="data-value">{{ strtoupper($anagrafica->swift) }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <div>Generato il {{ now()->format('d/m/Y H:i') }} - Finson Software</div>
    </div>
</body>
</html>