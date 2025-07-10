@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-file-earmark-plus"></i> Crea DDT per Vendita #{{ $vendita->id }}
                </h1>
                <a href="{{ route('ddts.create') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Torna alla Lista
                </a>
            </div>

            <!-- Info Vendita -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informazioni Vendita</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Cliente:</strong> {{ $vendita->cliente->nome_completo }}<br>
                            <strong>Data Vendita:</strong> {{ $vendita->data_vendita->format('d/m/Y') }}<br>
                            <strong>Totale:</strong> €{{ number_format($vendita->totale_finale, 2, ',', '.') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Indirizzo Cliente:</strong><br>
                            {{ $vendita->cliente->indirizzo }}<br>
                            {{ $vendita->cliente->cap }} {{ $vendita->cliente->citta }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form DDT -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Dati DDT</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('ddts.store') }}">
                        @csrf
                        <input type="hidden" name="vendita_id" value="{{ $vendita->id }}">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Data DDT *</label>
                                <input type="date" class="form-control" name="data_ddt" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Causale</label>
                                <select class="form-control" name="causale">
                                    <option value="Vendita">Vendita</option>
                                    <option value="Reso">Reso</option>
                                    <option value="Riparazione">Riparazione</option>
                                    <option value="Altro">Altro</option>
                                </select>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Destinatario</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Nome *</label>
                                        <input type="text" class="form-control" name="destinatario_nome" value="{{ $vendita->cliente->nome }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Cognome *</label>
                                        <input type="text" class="form-control" name="destinatario_cognome" value="{{ $vendita->cliente->cognome }}" required>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Indirizzo *</label>
                                        <input type="text" class="form-control" name="destinatario_indirizzo" value="{{ $vendita->cliente->indirizzo }}" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">CAP *</label>
                                        <input type="text" class="form-control" name="destinatario_cap" value="{{ $vendita->cliente->cap }}" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Città *</label>
                                        <input type="text" class="form-control" name="destinatario_citta" value="{{ $vendita->cliente->citta }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Trasportatore</label>
                                <input type="text" class="form-control" name="trasportatore" placeholder="Es: Corriere Express, Bartolini, ecc.">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Note</label>
                                <textarea class="form-control" name="note" rows="3" placeholder="Note aggiuntive..."></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('ddts.create') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Annulla
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check"></i> Crea DDT
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection