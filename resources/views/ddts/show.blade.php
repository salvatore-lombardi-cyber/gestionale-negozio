@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-file-earmark-text"></i> DDT {{ $ddt->numero_ddt }}
                </h1>
                <div>
                    <a href="{{ route('ddts.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> {{ __('app.back_to_ddts') }}
                    </a>
                    <form method="POST" action="{{ route('ddts.email', $ddt) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-envelope"></i> {{ __('app.send_email') }}
                        </button>
                    </form>
                    <a href="{{ route('ddts.pdf', $ddt) }}" class="btn btn-primary">
                        <i class="bi bi-printer"></i> {{ __('app.print_pdf') }}
                    </a>
                </div>
            </div>

            <!-- Header DDT -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{ __('app.ddt_information') }}</h5>
                            <strong>{{ __('app.number') }}:</strong> {{ $ddt->numero_ddt }}<br>
                            <strong>{{ __('app.date') }}:</strong> {{ $ddt->data_ddt->format('d/m/Y') }}<br>
                            <strong>{{ __('app.reason') }}:</strong> {{ $ddt->causale }}<br>
                            <strong>{{ __('app.status') }}:</strong>
                            @if($ddt->stato == 'bozza')
                                <span class="badge bg-secondary">{{ __('app.draft') }}</span>
                            @elseif($ddt->stato == 'confermato')
                                <span class="badge bg-primary">{{ __('app.confirmed') }}</span>
                            @elseif($ddt->stato == 'spedito')
                                <span class="badge bg-warning">{{ __('app.shipped') }}</span>
                            @else
                                <span class="badge bg-success">{{ __('app.delivered') }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h5>{{ __('app.customer') }}</h5>
                            <strong>{{ $ddt->cliente->nome_completo }}</strong><br>
                            {{ $ddt->cliente->indirizzo }}<br>
                            {{ $ddt->cliente->cap }} {{ $ddt->cliente->citta }}<br>
                            @if($ddt->cliente->telefono)
                                {{ __('app.tel') }}: {{ $ddt->cliente->telefono }}<br>
                            @endif
                            @if($ddt->cliente->email)
                                {{ __('app.email') }}: {{ $ddt->cliente->email }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Destinatario -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('app.recipient') }}</h5>
                </div>
                <div class="card-body">
                    <strong>{{ $ddt->destinatario_completo }}</strong><br>
                    {{ $ddt->indirizzo_completo }}
                    @if($ddt->trasportatore)
                        <br><strong>{{ __('app.carrier') }}:</strong> {{ $ddt->trasportatore }}
                    @endif
                </div>
            </div>

            <!-- Prodotti -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('app.products') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('app.product') }}</th>
                                    <th>{{ __('app.size') }}</th>
                                    <th>{{ __('app.color') }}</th>
                                    <th>{{ __('app.quantity') }}</th>
                                    <th>{{ __('app.unit_price') }}</th>
                                    <th>{{ __('app.subtotal') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ddt->vendita->dettagli as $dettaglio)
                                <tr>
                                    <td>{{ $dettaglio->prodotto->nome }}</td>
                                    <td><span class="badge bg-secondary">{{ $dettaglio->taglia }}</span></td>
                                    <td><span class="badge bg-info">{{ $dettaglio->colore }}</span></td>
                                    <td>{{ $dettaglio->quantita }}</td>
                                    <td>€{{ number_format($dettaglio->prezzo_unitario, 2, ',', '.') }}</td>
                                    <td>€{{ number_format($dettaglio->subtotale, 2, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <th colspan="5">{{ __('app.ddt_total') }}</th>
                                    <th>€{{ number_format($ddt->vendita->totale_finale, 2, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            @if($ddt->note)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('app.notes') }}</h5>
                </div>
                <div class="card-body">
                    {{ $ddt->note }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection