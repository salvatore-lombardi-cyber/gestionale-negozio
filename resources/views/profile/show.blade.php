@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-person-circle"></i> Il Mio Profilo
                </h1>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Torna alla Dashboard
                </a>
            </div>
            
            <div class="row">
                <!-- Informazioni Utente -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-person-circle fs-1 text-primary"></i>
                            </div>
                            <h4>{{ Auth::user()->name }}</h4>
                            <p class="text-muted">{{ Auth::user()->email }}</p>
                            <p class="text-muted">
                                <small>Membro dal {{ Auth::user()->created_at->format('d/m/Y') }}</small>
                            </p>
                            <div class="mt-3">
                                <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-pencil"></i> Modifica Profilo
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Statistiche Rapide -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Le Tue Statistiche</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <h4 class="text-primary">{{ \App\Models\Vendita::count() }}</h4>
                                    <small class="text-muted">Vendite Totali</small>
                                </div>
                                <div class="col-6">
                                    <h4 class="text-success">{{ \App\Models\Ddt::count() }}</h4>
                                    <small class="text-muted">DDT Emessi</small>
                                </div>
                                <div class="col-6 mt-3">
                                    <h4 class="text-info">{{ \App\Models\Prodotto::count() }}</h4>
                                    <small class="text-muted">Prodotti</small>
                                </div>
                                <div class="col-6 mt-3">
                                    <h4 class="text-warning">{{ \App\Models\Cliente::count() }}</h4>
                                    <small class="text-muted">Clienti</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Informazioni Account -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Informazioni Account</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Nome Completo</strong></label>
                                        <p class="form-control-plaintext">{{ Auth::user()->name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Email</strong></label>
                                        <p class="form-control-plaintext">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Data Registrazione</strong></label>
                                        <p class="form-control-plaintext">{{ Auth::user()->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label"><strong>Ultimo Accesso</strong></label>
                                        <p class="form-control-plaintext">{{ Auth::user()->updated_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Attività Recenti -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Attività Recenti</h5>
                        </div>
                        <div class="card-body">
                            @if(\App\Models\Vendita::latest()->take(5)->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach(\App\Models\Vendita::with('cliente')->latest()->take(5)->get() as $vendita)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>Vendita #{{ $vendita->id }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            Cliente: {{ $vendita->cliente->nome_completo }} - 
                                            {{ $vendita->data_vendita->format('d/m/Y') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-success">€{{ number_format($vendita->totale_finale, 2, ',', '.') }}</span>
                                </div>
                                @endforeach
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('vendite.index') }}" class="btn btn-outline-primary">
                                    Vedi Tutte le Vendite
                                </a>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="bi bi-cart-x fs-1 text-muted"></i>
                                <h5 class="text-muted mt-2">Nessuna vendita ancora</h5>
                                <a href="{{ route('vendite.create') }}" class="btn btn-primary">
                                    Crea Prima Vendita
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection