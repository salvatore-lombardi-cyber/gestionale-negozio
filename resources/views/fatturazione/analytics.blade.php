@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .analytics-container {
        padding: 2rem;
    }
    
    .analytics-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .analytics-title {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #ffd60a 0%, #ff8500 100%);
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
        margin: 0;
    }
    
    .stats-label {
        font-size: 0.9rem;
        color: #718096;
        font-weight: 600;
        margin-top: 0.5rem;
    }
    
    .chart-container {
        position: relative;
        height: 400px;
        margin-top: 1rem;
    }
    
    .growth-indicator {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-left: 0.5rem;
    }
    
    .growth-positive {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }
    
    .growth-negative {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }
    
    .growth-neutral {
        background-color: rgba(108, 117, 125, 0.1);
        color: #6c757d;
    }
    
    .best-month-badge {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        display: inline-block;
        margin-top: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .analytics-container {
            padding: 1rem;
        }
        
        .analytics-title {
            font-size: 2rem;
        }
        
        .stats-number {
            font-size: 1.5rem;
        }
        
        .chart-container {
            height: 300px;
        }
    }
</style>

<div class="container-fluid analytics-container">
    <!-- Header -->
    <div class="analytics-header text-center">
        <h1 class="analytics-title">
            <i class="bi bi-graph-up-arrow"></i> Analytics Fatturazione
        </h1>
        <p class="text-muted">Analisi avanzate delle tue performance commerciali per l'anno {{ $annoCorrente }}</p>
    </div>
    
    <!-- Statistiche Generali -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-number text-primary">€{{ number_format($statistiche['fatturato_anno_corrente'], 0, ',', '.') }}</div>
                <div class="stats-label">Fatturato {{ $annoCorrente }}</div>
                @if($statistiche['crescita_percentuale'] > 0)
                    <span class="growth-indicator growth-positive">
                        <i class="bi bi-arrow-up"></i> +{{ number_format($statistiche['crescita_percentuale'], 1) }}%
                    </span>
                @elseif($statistiche['crescita_percentuale'] < 0)
                    <span class="growth-indicator growth-negative">
                        <i class="bi bi-arrow-down"></i> {{ number_format($statistiche['crescita_percentuale'], 1) }}%
                    </span>
                @else
                    <span class="growth-indicator growth-neutral">
                        <i class="bi bi-dash"></i> 0%
                    </span>
                @endif
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-number text-info">€{{ number_format($statistiche['fatturato_anno_precedente'], 0, ',', '.') }}</div>
                <div class="stats-label">Fatturato {{ $annoCorrente - 1 }}</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-number text-success">€{{ number_format($statistiche['media_mensile'], 0, ',', '.') }}</div>
                <div class="stats-label">Media Mensile {{ $annoCorrente }}</div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-number text-warning">€{{ number_format($statistiche['valore_mese_migliore'], 0, ',', '.') }}</div>
                <div class="stats-label">Mese Migliore</div>
                <div class="best-month-badge">
                    @php
                        $mesi = ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'];
                    @endphp
                    {{ $mesi[$statistiche['mese_migliore'] - 1] }} {{ $annoCorrente }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Grafico Fatturato Mensile -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-bar-chart-line"></i> Fatturato Mensile - Confronto {{ $annoCorrente - 1 }} vs {{ $annoCorrente }}</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="fatturatoMensileChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Analisi Dettagliata -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-pie-chart"></i> Distribuzione Fatturato {{ $annoCorrente }}</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="distribuzioneFatturatoChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-graph-up"></i> Trend e Previsioni</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tabella Riepilogo Mensile -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-table"></i> Dettaglio Mensile {{ $annoCorrente }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mese</th>
                                    <th>Fatturato {{ $annoCorrente }}</th>
                                    <th>Fatturato {{ $annoCorrente - 1 }}</th>
                                    <th>Variazione</th>
                                    <th>% Crescita</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $mesi = ['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 
                                            'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'];
                                @endphp
                                @foreach($fatturateMensili as $index => $fatturato)
                                    @php
                                        $fatturatoPassato = $fatturatoAnnoPrecedente[$index] ?? 0;
                                        $variazione = $fatturato - $fatturatoPassato;
                                        $percentualeCrescita = $fatturatoPassato > 0 ? (($variazione / $fatturatoPassato) * 100) : 0;
                                    @endphp
                                    <tr class="{{ $index + 1 == $statistiche['mese_migliore'] ? 'table-warning' : '' }}">
                                        <td>
                                            <strong>{{ $mesi[$index] }}</strong>
                                            @if($index + 1 == $statistiche['mese_migliore'])
                                                <span class="badge bg-warning ms-2">Top</span>
                                            @endif
                                        </td>
                                        <td><strong class="text-primary">€{{ number_format($fatturato, 2, ',', '.') }}</strong></td>
                                        <td class="text-muted">€{{ number_format($fatturatoPassato, 2, ',', '.') }}</td>
                                        <td>
                                            @if($variazione > 0)
                                                <span class="text-success">+€{{ number_format($variazione, 2, ',', '.') }}</span>
                                            @elseif($variazione < 0)
                                                <span class="text-danger">€{{ number_format($variazione, 2, ',', '.') }}</span>
                                            @else
                                                <span class="text-muted">€0,00</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($percentualeCrescita > 0)
                                                <span class="growth-indicator growth-positive">
                                                    <i class="bi bi-arrow-up"></i> +{{ number_format($percentualeCrescita, 1) }}%
                                                </span>
                                            @elseif($percentualeCrescita < 0)
                                                <span class="growth-indicator growth-negative">
                                                    <i class="bi bi-arrow-down"></i> {{ number_format($percentualeCrescita, 1) }}%
                                                </span>
                                            @else
                                                <span class="growth-indicator growth-neutral">0%</span>
                                            @endif
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
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dati per i grafici
    const mesi = ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'];
    const fatturateMensili = @json($fatturateMensili);
    const fatturatoAnnoPrecedente = @json($fatturatoAnnoPrecedente);
    
    // Grafico Fatturato Mensile Confronto
    const ctx1 = document.getElementById('fatturatoMensileChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: mesi,
            datasets: [{
                label: '{{ $annoCorrente }}',
                data: fatturateMensili,
                backgroundColor: 'rgba(2, 157, 126, 0.8)',
                borderColor: 'rgb(2, 157, 126)',
                borderWidth: 2,
                borderRadius: 8
            }, {
                label: '{{ $annoCorrente - 1 }}',
                data: fatturatoAnnoPrecedente,
                backgroundColor: 'rgba(255, 214, 10, 0.8)',
                borderColor: 'rgb(255, 214, 10)',
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
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
            }
        }
    });
    
    // Grafico Distribuzione Fatturato (Pie Chart)
    const ctx2 = document.getElementById('distribuzioneFatturatoChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: mesi,
            datasets: [{
                data: fatturateMensili,
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                ],
                borderWidth: 2,
                borderColor: 'white'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                }
            }
        }
    });
    
    // Grafico Trend
    const ctx3 = document.getElementById('trendChart').getContext('2d');
    new Chart(ctx3, {
        type: 'line',
        data: {
            labels: mesi,
            datasets: [{
                label: 'Trend {{ $annoCorrente }}',
                data: fatturateMensili,
                borderColor: 'rgb(255, 214, 10)',
                backgroundColor: 'rgba(255, 214, 10, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgb(255, 214, 10)',
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
                    display: false
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
            }
        }
    });
});
</script>

@endsection