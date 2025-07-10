@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">
                    <i class="bi bi-pencil"></i> Modifica Profilo
                </h1>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Torna al Profilo
                </a>
            </div>

            <div class="row">
                <!-- Modifica Informazioni -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Informazioni Personali</h5>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                @method('PATCH')
                                
                                <div class="mb-3">
                                    <label class="form-label">Nome Completo *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           name="name" value="{{ old('name', Auth::user()->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email', Auth::user()->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check"></i> Salva Modifiche
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Cambia Password -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Cambia Password</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('password.update') }}">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label class="form-label">Password Attuale *</label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                           name="current_password" required>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Nuova Password *</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Minimo 8 caratteri</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Conferma Nuova Password *</label>
                                    <input type="password" class="form-control" 
                                           name="password_confirmation" required>
                                </div>

                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-key"></i> Cambia Password
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Reset Password via Email -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Password Dimenticata?</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Se non ricordi la password, puoi richiedere un link di reset via email.</p>
                            <a href="{{ route('password.request') }}" class="btn btn-outline-danger">
                                <i class="bi bi-envelope"></i> Invia Link Reset Password
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection