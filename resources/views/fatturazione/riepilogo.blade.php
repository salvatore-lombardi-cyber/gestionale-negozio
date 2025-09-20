@extends('layouts.app')

@section('content')
<style>
    .riepilogo-page {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .riepilogo-container {
        padding: 2rem;
    }
    
    .riepilogo-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .riepilogo-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }
    
    .stats-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        text-align: center;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }
    
    .stats-number {
        font-size: 2rem;
        font-weight: 800;
        color: #2d3748;
        margin: 0;
    }
    
    .stats-label {
        font-size: 0.9rem;
        color: #718096;
        font-weight: 600;
        margin-top: 0.5rem;
    }
    
    .filter-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        border: none;
        border-radius: 15px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }
    
    .table {
        background: transparent;
    }
    
    .table thead th {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        color: white;
        border: none;
        font-weight: 600;
    }
    
    .badge {
        border-radius: 10px;
        font-size: 0.75rem;
        padding: 0.5rem 0.75rem;
    }
    
    .chart-container {
        position: relative;
        height: 300px;
        margin-top: 1rem;
    }
    
    @media (max-width: 768px) {
        .riepilogo-container {
            padding: 1rem;
        }
        
        .riepilogo-title {
            font-size: 2rem;
        }
        
        .stats-number {
            font-size: 1.5rem;
        }
    }
</style>

<div class="riepilogo-page">
<div class="container-fluid riepilogo-container">
    <!-- Header -->
    <div class="riepilogo-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="riepilogo-title">
                <i class="bi bi-bar-chart"></i> Riepilogo Fatturazione
            </h1>
            <a href="{{ route('fatturazione.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Torna Indietro
            </a>
        </div>
        <p class="text-muted">Dashboard completa delle tue fatture</p>
    </div>
    
    <!-- Filtri -->
    <div class="filter-card">
        <form method="GET" action="{{ route('fatturazione.riepilogo') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Data Inizio</label>
                    <input type="date" name="data_inizio" class="form-control" value="{{ $dataInizio }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Data Fine</label>
                    <input type="date" name="data_fine" class="form-control" value="{{ $dataFine }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Cliente</label>
                    <select name="cliente_id" class="form-control">
                        <option value="">Tutti i clienti</option>
                        @foreach($clienti as $cliente)
                            <option value="{{ $cliente->id }}" {{ $clienteId == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nome }} {{ $cliente->cognome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Stato</label>
                    <select name="stato" class="form-control">
                        <option value="">Tutti gli stati</option>
                        <option value="definitivo" {{ $stato == 'definitivo' ? 'selected' : '' }}>Definitive</option>
                        <option value="potenziale" {{ $stato == 'potenziale' ? 'selected' : '' }}>Potenziali</option>
                    </select>
                </div>
            </div>
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Filtra Risultati
                </button>
                <a href="{{ route('fatturazione.riepilogo') }}" class="btn btn-outline-secondary ms-2">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </a>
            </div>
        </form>
    </div>
    
    <!-- Statistiche -->
    <div class="row">
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="stats-card">
                <div class="stats-number text-primary">{{ $statistiche['totale_fatture'] }}</div>
                <div class="stats-label">Totale Fatture</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="stats-card">
                <div class="stats-number text-success">{{ $statistiche['fatture_definitive'] }}</div>
                <div class="stats-label">Definitive</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="stats-card">
                <div class="stats-number text-warning">{{ $statistiche['fatture_potenziali'] }}</div>
                <div class="stats-label">Potenziali</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="stats-card">
                <div class="stats-number text-success">€{{ number_format($statistiche['fatturato_totale'], 0, ',', '.') }}</div>
                <div class="stats-label">Fatturato Totale</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="stats-card">
                <div class="stats-number text-info">€{{ number_format($statistiche['fatturato_definitivo'], 0, ',', '.') }}</div>
                <div class="stats-label">Fatturato Definitivo</div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="stats-card">
                <div class="stats-number text-primary">€{{ number_format($statistiche['ticket_medio'], 0, ',', '.') }}</div>
                <div class="stats-label">Ticket Medio</div>
            </div>
        </div>
    </div>
    
    <!-- Grafici -->
    @if($fatturatoPeriodo->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-graph-up"></i> Andamento Fatturato del Periodo</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="fatturatoPeriodoChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Top Clienti -->
    @if($topClienti->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-people"></i> Top Clienti del Periodo</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Posizione</th>
                                    <th>Cliente</th>
                                    <th>N. Fatture</th>
                                    <th>Fatturato</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topClienti as $index => $item)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ $index < 3 ? 'warning' : 'secondary' }}">
                                            {{ $index + 1 }}°
                                        </span>
                                    </td>
                                    <td>
                                        @if($item['cliente'])
                                            <strong>{{ $item['cliente']->nome }} {{ $item['cliente']->cognome }}</strong>
                                            <small class="d-block text-muted">{{ $item['cliente']->email ?? '' }}</small>
                                        @else
                                            <span class="text-muted">Cliente occasionale</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $item['fatture'] }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-success">€{{ number_format($item['fatturato'], 2, ',', '.') }}</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Lista Fatture -->
    @if($fatture->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-list-ul"></i> Dettaglio Fatture ({{ $fatture->count() }} risultati)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>N. Fattura</th>
                                    <th>Data</th>
                                    <th>Cliente</th>
                                    <th>Totale</th>
                                    <th>Stato</th>
                                    <th class="text-center">Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fatture as $fattura)
                                    @php
                                        $datiCliente = json_decode($fattura->prodotti_vendita, true);
                                        $tipo = $datiCliente['tipo'] ?? 'definitivo';
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong class="text-primary">{{ $fattura->numero_documento }}</strong>
                                            <small class="d-block text-muted">{{ ucfirst($tipo) }}</small>
                                        </td>
                                        <td>{{ $fattura->data_documento ? $fattura->data_documento->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            @if($fattura->cliente)
                                                {{ $fattura->cliente->nome }} {{ $fattura->cliente->cognome }}
                                                <small class="d-block text-muted">{{ $fattura->cliente->email ?? '' }}</small>
                                            @else
                                                <span class="text-muted">Cliente occasionale</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong class="text-success">€{{ number_format($fattura->totale, 2, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            @if($tipo === 'definitivo')
                                                <span class="badge bg-success">Definitiva</span>
                                            @else
                                                <span class="badge bg-warning">Potenziale</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('fatturazione.show', $fattura) }}" class="btn btn-sm btn-outline-primary" title="Visualizza">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('fatturazione.edit', $fattura) }}" class="btn btn-sm btn-outline-warning" title="Modifica">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ route('fatturazione.pdf', $fattura) }}" class="btn btn-sm btn-outline-success" title="Scarica PDF" target="_blank">
                                                    <i class="bi bi-file-earmark-pdf"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card text-center">
                <div class="card-body py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h4 class="mt-3">Nessuna fattura trovata</h4>
                    <p class="text-muted">Non ci sono fatture per i criteri selezionati.</p>
                    <a href="{{ route('fatturazione.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Crea Prima Fattura
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
</div>

@if($fatturatoPeriodo->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('fatturatoPeriodoChart').getContext('2d');
    
    const labels = @json($fatturatoPeriodo->keys()->toArray());
    const data = @json($fatturatoPeriodo->values()->toArray());
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels.map(date => {
                const d = new Date(date);
                return d.toLocaleDateString('it-IT', {day: '2-digit', month: '2-digit'});
            }),
            datasets: [{
                label: 'Fatturato Giornaliero (€)',
                data: data,
                borderColor: 'rgb(2, 157, 126)',
                backgroundColor: 'rgba(2, 157, 126, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgb(2, 157, 126)',
                pointBorderColor: 'white',
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '€' + value.toLocaleString('it-IT');
                        }
                    }
                }
            },
            elements: {
                point: {
                    hoverRadius: 8
                }
            }
        }
    });
});
</script>
@endif

@endsection