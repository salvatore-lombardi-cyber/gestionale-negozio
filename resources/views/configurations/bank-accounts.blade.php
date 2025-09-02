@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .config-container {
        padding: 2rem;
    }
    
    .config-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .config-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #4ecdc4, #44a08d);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
    }
    
    .config-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .bank-card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .bank-card:hover {
        border-color: #4ecdc4;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(78, 205, 196, 0.2);
    }
    
    .bank-name {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    
    .bank-details {
        color: #718096;
        font-size: 0.9rem;
    }
    
    .alert {
        border-radius: 15px;
        border: none;
        padding: 20px;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #4ecdc4, #44a08d);
        color: white;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #4ecdc4, #44a08d);
        border: none;
        border-radius: 15px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(78, 205, 196, 0.3);
    }
    
    .form-control {
        border-radius: 15px;
        padding: 15px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.8);
    }
    
    .form-control:focus {
        border-color: #4ecdc4;
        box-shadow: 0 0 0 0.2rem rgba(78, 205, 196, 0.25);
        background: white;
    }
</style>

<div class="container-fluid config-container">
    <!-- Header -->
    <div class="config-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="config-title">
                    <i class="bi bi-bank"></i> {{ __('app.bank_accounts') }}
                </h1>
            </div>
            <a href="{{ route('configurations.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> {{ __('app.back') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Conti Bancari Esistenti -->
    <div class="config-card">
        <h3 class="mb-4">
            <i class="bi bi-list-ul"></i> Coordinate Bancarie Configurate
        </h3>
        
        @forelse($accounts as $account)
            <div class="bank-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="bank-name">{{ $account->nome_banca }}</div>
                        <div class="bank-details">
                            <strong>IBAN:</strong> {{ $account->iban }}<br>
                            <strong>C/C:</strong> {{ $account->conto_corrente }}
                            @if($account->abi && $account->cab)
                                <br><strong>ABI:</strong> {{ $account->abi }} - <strong>CAB:</strong> {{ $account->cab }}
                            @endif
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteBankAccount('{{ $account->uuid }}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-4">
                <i class="bi bi-bank display-4 text-muted"></i>
                <p class="text-muted mt-3">Nessuna coordinata bancaria configurata</p>
            </div>
        @endforelse
    </div>

    <!-- Form Nuova Coordinata Bancaria -->
    <div class="config-card">
        <h3 class="mb-4">
            <i class="bi bi-plus-circle"></i> Aggiungi Nuova Coordinata Bancaria
        </h3>
        
        <form action="{{ route('configurations.bank-accounts.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nome_banca" class="form-label">Nome Banca *</label>
                        <input type="text" class="form-control @error('nome_banca') is-invalid @enderror" 
                               id="nome_banca" name="nome_banca" 
                               value="{{ old('nome_banca') }}" required>
                        @error('nome_banca')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="conto_corrente" class="form-label">Conto Corrente *</label>
                        <input type="text" class="form-control @error('conto_corrente') is-invalid @enderror" 
                               id="conto_corrente" name="conto_corrente" 
                               value="{{ old('conto_corrente') }}" required>
                        @error('conto_corrente')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="iban" class="form-label">IBAN *</label>
                        <input type="text" class="form-control @error('iban') is-invalid @enderror" 
                               id="iban" name="iban" 
                               value="{{ old('iban') }}" 
                               placeholder="IT60 X054 2811 1010 0000 0123 456" required>
                        @error('iban')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="abi" class="form-label">ABI</label>
                        <input type="text" class="form-control @error('abi') is-invalid @enderror" 
                               id="abi" name="abi" 
                               value="{{ old('abi') }}">
                        @error('abi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="cab" class="form-label">CAB</label>
                        <input type="text" class="form-control @error('cab') is-invalid @enderror" 
                               id="cab" name="cab" 
                               value="{{ old('cab') }}">
                        @error('cab')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="swift" class="form-label">SWIFT</label>
                        <input type="text" class="form-control @error('swift') is-invalid @enderror" 
                               id="swift" name="swift" 
                               value="{{ old('swift') }}">
                        @error('swift')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle"></i> Aggiungi Coordinata Bancaria
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function deleteBankAccount(uuid) {
    if (confirm('Sei sicuro di voler eliminare questa coordinata bancaria?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/configurations/bank-accounts/${uuid}`;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = '{{ csrf_token() }}';
        
        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection