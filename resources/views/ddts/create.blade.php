@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-plus"></i> Nuovo DDT
                </h1>
                <a href="{{ route('ddts.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Torna ai DDT
                </a>
            </div>
            
            <div class="card">
                <div class="card-body">
                    @if($vendite->count() > 0)
                    <p class="text-info">
                        <i class="bi bi-info-circle"></i>
                        Seleziona una vendita per creare il DDT corrispondente
                    </p>
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID Vendita</th>
                                    <th>Data</th>
                                    <th>Cliente</th>
                                    <th>Totale</th>
                                    <th>Azione</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vendite as $vendita)
                                <tr>
                                    <td><strong>#{{ $vendita->id }}</strong></td>
                                    <td>{{ $vendita->data_vendita->format('d/m/Y') }}</td>
                                    <td>{{ $vendita->cliente->nome_completo }}</td>
                                    <td>€{{ number_format($vendita->totale_finale, 2, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('ddts.create') }}?vendita_id={{ $vendita->id }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-file-earmark-plus"></i> Crea DDT
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>
                        <h5 class="text-muted mt-3">Nessuna vendita disponibile</h5>
                        <p class="text-muted">Tutte le vendite hanno già un DDT associato o non ci sono vendite.</p>
                        <a href="{{ route('vendite.index') }}" class="btn btn-primary">
                            <i class="bi bi-cart"></i> Vai alle Vendite
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection