@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="bi bi-box-seam"></i> {{ __('app.warehouse_details') }}
                    </h1>
                    <p class="text-muted">{{ $prodotto->nome }}</p>
                </div>
                <div>
                    <a href="{{ route('magazzino.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> {{ __('app.back_to_warehouse') }}
                    </a>
                    <a href="{{ route('magazzino.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> {{ __('app.add_stock') }}
                    </a>
                </div>
            </div>

            <!-- Info Prodotto -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="card-title">{{ $prodotto->nome }}</h5>
                            <p class="text-muted">{{ $prodotto->descrizione }}</p>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-6">
                                    <strong>{{ __('app.price') }}:</strong> €{{ number_format($prodotto->prezzo, 2, ',', '.') }}
                                </div>
                                <div class="col-6">
                                    <strong>{{ __('app.category') }}:</strong> {{ $prodotto->categoria }}
                                </div>
                                <div class="col-6">
                                    <strong>{{ __('app.brand') }}:</strong> {{ $prodotto->brand }}
                                </div>
                                <div class="col-6">
                                    <strong>{{ __('app.code') }}:</strong> {{ $prodotto->codice_prodotto }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Varianti in Magazzino -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-grid-3x3-gap"></i> {{ __('app.warehouse_variants') }}
                    </h5>
                </div>
                <div class="card-body">
                    @if($prodotto->magazzino->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('app.size') }}</th>
                                    <th>{{ __('app.color') }}</th>
                                    <th>{{ __('app.quantity') }}</th>
                                    <th>{{ __('app.min_stock') }}</th>
                                    <th>{{ __('app.status') }}</th>
                                    <th>{{ __('app.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prodotto->magazzino as $variante)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">{{ $variante->taglia }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $variante->colore }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $variante->quantita }}</strong>
                                    </td>
                                    <td>
                                        {{ $variante->scorta_minima }}
                                    </td>
                                    <td>
                                        @if($variante->quantita <= $variante->scorta_minima)
                                            <span class="badge bg-danger">
                                                <i class="bi bi-exclamation-triangle"></i> {{ __('app.low_stock') }}
                                            </span>
                                        @else
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> OK
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('magazzino.edit', $variante->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i> {{ __('app.edit') }}
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Statistiche -->
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $prodotto->magazzino->count() }}</h4>
                                    <small>{{ __('app.total_variants') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $prodotto->magazzino->sum('quantita') }}</h4>
                                    <small>{{ __('app.total_pieces') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $prodotto->magazzino->where('quantita', '<=', 'scorta_minima')->count() }}</h4>
                                    <small>{{ __('app.low_stock') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h4>€{{ number_format($prodotto->magazzino->sum('quantita') * $prodotto->prezzo, 2, ',', '.') }}</h4>
                                    <small>{{ __('app.total_value') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-box fs-1 text-muted"></i>
                        <h5 class="text-muted mt-3">{{ __('app.no_variants_in_warehouse') }}</h5>
                        <p class="text-muted">{{ __('app.product_no_variants_loaded') }}</p>
                        <a href="{{ route('magazzino.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus"></i> {{ __('app.add_first_variant') }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection