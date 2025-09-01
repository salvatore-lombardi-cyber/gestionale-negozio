@extends('layouts.app')

@section('title', 'Fattura N. ' . $vendita->numero_documento . ' - Gestionale Negozio')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .fattura-container {
        padding: 2rem;
        min-height: calc(100vh - 76px);
    }
    
    .fattura-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .fattura-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .fattura-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
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
    
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #495057;
    }
    
    .info-value {
        color: #212529;
        text-align: right;
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
        display: inline-block;
        margin-right: 1rem;
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
    
    .modern-btn.warning {
        background: linear-gradient(135deg, #ffc107, #e0a800);
    }
    
    .modern-btn.warning:hover {
        box-shadow: 0 15px 35px rgba(255, 193, 7, 0.4);
    }
    
    .modern-btn.danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
    }
    
    .modern-btn.danger:hover {
        box-shadow: 0 15px 35px rgba(220, 53, 69, 0.4);
    }
    
    .badge-custom {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .badge-definitivo {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    
    .badge-potenziale {
        background: linear-gradient(135deg, #ffc107, #e0a800);
        color: white;
    }
    
    @media (max-width: 768px) {
        .fattura-container {
            padding: 1rem;
        }
        
        .fattura-header, .fattura-card {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .fattura-title {
            font-size: 2rem;
        }
        
        .modern-btn {
            padding: 12px 24px;
            font-size: 0.9rem;
            display: block;
            margin-bottom: 1rem;
            text-align: center;
        }
    }
    
    @media (max-width: 576px) {
        .fattura-header .d-flex {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
        
        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .info-value {
            text-align: left;
        }
    }
</style>

<div class="fattura-container">
    <!-- Header della Fattura -->
    <div class="fattura-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="fattura-title">
                    <i class="bi bi-file-earmark-text"></i> Fattura N. {{ $vendita->numero_documento }}
                </h1>
                @php
                    $datiCliente = json_decode($vendita->prodotti_vendita, true);
                    $tipo = $datiCliente['tipo'] ?? 'definitivo';
                @endphp
                <span class="badge-custom {{ $tipo === 'definitivo' ? 'badge-definitivo' : 'badge-potenziale' }}">
                    {{ ucfirst($tipo) }}
                </span>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('fatturazione.edit', $vendita) }}" class="modern-btn warning">
                    <i class="bi bi-pencil"></i> {{ __('app.edit') }}
                </a>
                <a href="{{ route('fatturazione.index') }}" class="modern-btn secondary">
                    <i class="bi bi-arrow-left"></i> {{ __('app.torna_indietro') }}
                </a>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Dati Principali Fattura -->
        <div class="col-lg-6">
            <div class="fattura-card">
                <h3 class="section-title">
                    <i class="bi bi-file-text"></i> {{ __('app.dati_fattura') }}
                </h3>
                
                <div class="info-row">
                    <span class="info-label">{{ __('app.numero_fattura') }}:</span>
                    <span class="info-value"><strong>{{ $vendita->numero_documento }}</strong></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">{{ __('app.data_fattura') }}:</span>
                    <span class="info-value">{{ $vendita->data_documento ? $vendita->data_documento->format('d/m/Y') : '-' }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">{{ __('app.tipo') }}:</span>
                    <span class="info-value">
                        <span class="badge-custom {{ $tipo === 'definitivo' ? 'badge-definitivo' : 'badge-potenziale' }}">
                            {{ ucfirst($tipo) }}
                        </span>
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Subtotale:</span>
                    <span class="info-value">€{{ number_format($vendita->subtotale, 2, ',', '.') }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">IVA:</span>
                    <span class="info-value">€{{ number_format($vendita->iva, 2, ',', '.') }}</span>
                </div>
                
                <div class="info-row">
                    <span class="info-label"><strong>Totale:</strong></span>
                    <span class="info-value"><strong class="text-success fs-5">€{{ number_format($vendita->totale, 2, ',', '.') }}</strong></span>
                </div>
            </div>
        </div>
        
        <!-- Dati Cliente -->
        <div class="col-lg-6">
            <div class="fattura-card">
                <h3 class="section-title">
                    <i class="bi bi-person"></i> {{ __('app.dati_cliente') }}
                </h3>
                
                @if($vendita->cliente)
                    <div class="info-row">
                        <span class="info-label">{{ __('app.customer') }}:</span>
                        <span class="info-value"><strong>{{ $vendita->cliente->nome }} {{ $vendita->cliente->cognome }}</strong></span>
                    </div>
                    
                    @if($vendita->cliente->email)
                        <div class="info-row">
                            <span class="info-label">{{ __('app.email') }}:</span>
                            <span class="info-value">{{ $vendita->cliente->email }}</span>
                        </div>
                    @endif
                    
                    @if($vendita->cliente->telefono)
                        <div class="info-row">
                            <span class="info-label">{{ __('app.phone') }}:</span>
                            <span class="info-value">{{ $vendita->cliente->telefono }}</span>
                        </div>
                    @endif
                    
                    @if($vendita->cliente->indirizzo)
                        <div class="info-row">
                            <span class="info-label">{{ __('app.address') }}:</span>
                            <span class="info-value">{{ $vendita->cliente->indirizzo }}</span>
                        </div>
                    @endif
                @else
                    <div class="info-row">
                        <span class="info-label">{{ __('app.customer') }}:</span>
                        <span class="info-value text-muted">Cliente occasionale</span>
                    </div>
                @endif
                
                @if(isset($datiCliente['dati_cliente']))
                    @php $datiExtra = $datiCliente['dati_cliente']; @endphp
                    
                    @if(!empty($datiExtra['indirizzo']))
                        <div class="info-row">
                            <span class="info-label">{{ __('app.indirizzo') }}:</span>
                            <span class="info-value">{{ $datiExtra['indirizzo'] }}</span>
                        </div>
                    @endif
                    
                    @if(!empty($datiExtra['citta']))
                        <div class="info-row">
                            <span class="info-label">{{ __('app.citta') }} / {{ __('app.provincia') }}:</span>
                            <span class="info-value">{{ $datiExtra['citta'] }} {{ $datiExtra['provincia'] ? '(' . $datiExtra['provincia'] . ')' : '' }}</span>
                        </div>
                    @endif
                    
                    @if(!empty($datiExtra['codice_fiscale']))
                        <div class="info-row">
                            <span class="info-label">{{ __('app.codice_fiscale') }}:</span>
                            <span class="info-value">{{ $datiExtra['codice_fiscale'] }}</span>
                        </div>
                    @endif
                    
                    @if(!empty($datiExtra['partita_iva']))
                        <div class="info-row">
                            <span class="info-label">{{ __('app.partita_iva') }}:</span>
                            <span class="info-value">{{ $datiExtra['partita_iva'] }}</span>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
    
    <!-- Note -->
    @if($vendita->note)
    <div class="row">
        <div class="col-12">
            <div class="fattura-card">
                <h3 class="section-title">
                    <i class="bi bi-chat-text"></i> {{ __('app.note') }}
                </h3>
                <div class="bg-light p-3 rounded">
                    {{ $vendita->note }}
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Pulsanti di Azione -->
    <div class="row">
        <div class="col-12">
            <div class="fattura-card">
                <div class="text-center">
                    <a href="{{ route('fatturazione.edit', $vendita) }}" class="modern-btn warning">
                        <i class="bi bi-pencil"></i> {{ __('app.edit') }} Fattura
                    </a>
                    <a href="{{ route('fatturazione.index') }}" class="modern-btn secondary">
                        <i class="bi bi-list-ul"></i> Lista Fatture
                    </a>
                    <form action="{{ route('fatturazione.destroy', $vendita) }}" method="POST" class="d-inline" 
                          onsubmit="return confirm('Sei sicuro di voler eliminare questa fattura?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="modern-btn danger">
                            <i class="bi bi-trash"></i> {{ __('app.delete') }} Fattura
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection