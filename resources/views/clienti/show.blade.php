@extends('layouts.app')

@section('title', __('app.customer') . ': ' . $cliente->nome_completo . ' - Gestionale Negozio')

@section('content')
<style>
    .customer-detail-container {
        padding: 2rem;
        min-height: calc(100vh - 76px);
    }
    
    .page-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        position: relative;
        overflow: hidden;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
    }
    
    .customer-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .customer-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 2rem;
        flex-shrink: 0;
        border: 4px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 10px 25px rgba(2, 157, 126, 0.3);
    }
    
    .customer-info {
        flex: 1;
    }
    
    .customer-title {
        font-size: 2.2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
        margin-bottom: 0.5rem;
    }
    
    .customer-subtitle {
        color: #718096;
        font-size: 1.1rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.75rem;
        align-items: center;
    }
    
    .modern-btn {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        border: none;
        border-radius: 15px;
        padding: 12px 24px;
        font-weight: 600;
        color: white;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .modern-btn.edit {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
    }
    
    .modern-btn.secondary {
        background: linear-gradient(135deg, #6c757d, #5a6268);
    }
    
    .modern-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.2);
        transition: all 0.3s ease;
    }
    
    .modern-btn:hover::before {
        left: 0;
    }
    
    .modern-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        color: white;
    }
    
    .customer-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    .info-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
    }
    
    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }
    
    .card-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .card-title i {
        color: #029D7E;
        font-size: 1.5rem;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(2, 157, 126, 0.1);
        transition: all 0.3s ease;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-item:hover {
        background: rgba(2, 157, 126, 0.03);
        padding-left: 0.5rem;
        border-radius: 8px;
    }
    
    .info-label {
        font-weight: 600;
        color: #4a5568;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        min-width: 120px;
    }
    
    .info-label i {
        color: #029D7E;
        width: 16px;
    }
    
    .info-value {
        font-weight: 500;
        color: #2d3748;
        text-align: right;
        flex: 1;
    }
    
    .contact-value {
        font-family: 'Courier New', monospace;
        background: rgba(2, 157, 126, 0.1);
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.9rem;
        color: #029D7E;
    }
    
    .gender-badge {
        padding: 4px 12px;
        border-radius: 15px;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
    }
    
    .gender-male {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
    }
    
    .gender-female {
        background: linear-gradient(135deg, #f72585, #c5025a);
        color: white;
    }
    
    .gender-other {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
    }
    
    .date-display {
        font-weight: 600;
        color: #029D7E;
    }
    
    .address-display {
        line-height: 1.4;
        font-weight: 500;
    }
    
    .datetime-display {
        font-size: 0.95rem;
        color: #718096;
        font-weight: 500;
    }
    
    .age-badge {
        background: rgba(2, 157, 126, 0.1);
        color: #029D7E;
        padding: 4px 8px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-left: 0.5rem;
    }
    
    /* Dark Mode */
    [data-bs-theme="dark"] .page-header,
    [data-bs-theme="dark"] .info-card {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .customer-title,
    [data-bs-theme="dark"] .card-title {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .info-label,
    [data-bs-theme="dark"] .info-value {
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .contact-value {
        background: rgba(2, 157, 126, 0.2);
        color: #e2e8f0;
    }
    
    [data-bs-theme="dark"] .info-item {
        border-bottom-color: rgba(2, 157, 126, 0.2);
    }
    
    [data-bs-theme="dark"] .age-badge {
        background: rgba(2, 157, 126, 0.2);
        color: #e2e8f0;
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .customer-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .customer-detail-container {
            padding: 1rem;
        }
        
        .page-header {
            padding: 1.5rem;
            text-align: center;
        }
        
        .customer-header {
            flex-direction: column;
            text-align: center;
        }
        
        .customer-title {
            font-size: 1.8rem;
        }
        
        .action-buttons {
            justify-content: center;
            margin-top: 1rem;
            flex-wrap: wrap;
        }
        
        .customer-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
        
        .info-card {
            padding: 1.5rem;
        }
        
        .info-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .info-value {
            text-align: left;
        }
    }
    
    @media (max-width: 576px) {
        .action-buttons {
            flex-direction: column;
            width: 100%;
        }
        
        .modern-btn {
            width: 100%;
            justify-content: center;
        }
        
        .customer-avatar {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
        
        .card-title {
            font-size: 1.1rem;
        }
        
        .customer-title {
            font-size: 1.6rem;
        }
    }
</style>

<div class="customer-detail-container">
    <!-- Header con Avatar e Info -->
    <div class="page-header">
        <div class="customer-header">
            <div class="customer-avatar">
                {{ strtoupper(substr($cliente->nome, 0, 1)) }}{{ strtoupper(substr($cliente->cognome, 0, 1)) }}
            </div>
            <div class="customer-info">
                <h1 class="customer-title">{{ $cliente->nome_completo }}</h1>
                <p class="customer-subtitle">
                    <i class="bi bi-person"></i>
                    {{ __('app.customer') }} • {{ __('app.customer_since_date', ['date' => $cliente->created_at->format('d/m/Y')]) }}
                </p>
            </div>
            <div class="action-buttons">
                <a href="{{ route('clienti.edit', $cliente) }}" class="modern-btn edit">
                    <i class="bi bi-pencil"></i> {{ __('app.edit') }}
                </a>
                <a href="{{ route('clienti.index') }}" class="modern-btn secondary">
                    <i class="bi bi-arrow-left"></i> {{ __('app.back_to_list') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Grid delle Informazioni -->
    <div class="customer-grid">
        <!-- Card Dati Anagrafici -->
        <div class="info-card">
            <h3 class="card-title">
                <i class="bi bi-person-vcard"></i>
                {{ __('app.personal_data') }}
            </h3>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-person"></i>
                    {{ __('app.name') }}
                </span>
                <span class="info-value">{{ $cliente->nome }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-person-fill"></i>
                    {{ __('app.surname') }}
                </span>
                <span class="info-value">{{ $cliente->cognome }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-gender-ambiguous"></i>
                    {{ __('app.gender') }}
                </span>
                <span class="info-value">
                    @if($cliente->genere == 'M')
                        <span class="gender-badge gender-male">
                            <i class="bi bi-person"></i> {{ __('app.male') }}
                        </span>
                    @elseif($cliente->genere == 'F')
                        <span class="gender-badge gender-female">
                            <i class="bi bi-person-dress"></i> {{ __('app.female') }}
                        </span>
                    @else
                        <span class="gender-badge gender-other">
                            {{ $cliente->genere ?? 'N/A' }}
                        </span>
                    @endif
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-calendar-event"></i>
                    {{ __('app.date_of_birth') }}
                </span>
                <span class="info-value">
                    @if($cliente->data_nascita)
                        <span class="date-display">{{ $cliente->data_nascita->format('d/m/Y') }}</span>
                        <span class="age-badge">{{ __('app.years_old', ['age' => $cliente->data_nascita->age]) }}</span>
                    @else
                        <em style="color: #a0aec0;">N/A</em>
                    @endif
                </span>
            </div>
        </div>

        <!-- Card Contatti -->
        <div class="info-card">
            <h3 class="card-title">
                <i class="bi bi-telephone"></i>
                {{ __('app.contact_information') }}
            </h3>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-telephone"></i>
                    {{ __('app.phone') }}
                </span>
                <span class="info-value">
                    @if($cliente->telefono)
                        <span class="contact-value">{{ $cliente->telefono }}</span>
                    @else
                        <em style="color: #a0aec0;">N/A</em>
                    @endif
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-envelope"></i>
                    {{ __('app.email') }}
                </span>
                <span class="info-value">
                    @if($cliente->email)
                        <span class="contact-value">{{ $cliente->email }}</span>
                    @else
                        <em style="color: #a0aec0;">N/A</em>
                    @endif
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-geo-alt"></i>
                    {{ __('app.address') }}
                </span>
                <span class="info-value">
                    @if($cliente->indirizzo)
                        <div class="address-display">{{ $cliente->indirizzo }}</div>
                    @else
                        <em style="color: #a0aec0;">N/A</em>
                    @endif
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-building"></i>
                    {{ __('app.city') }}
                </span>
                <span class="info-value">
                    @if($cliente->citta)
                        {{ $cliente->citta }}
                        @if($cliente->cap)
                            <span class="contact-value" style="margin-left: 0.5rem;">{{ $cliente->cap }}</span>
                        @endif
                    @else
                        <em style="color: #a0aec0;">N/A</em>
                    @endif
                </span>
            </div>
        </div>

        <!-- Card Sistema -->
        <div class="info-card">
            <h3 class="card-title">
                <i class="bi bi-clock-history"></i>
                {{ __('app.system_information') }}
            </h3>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-plus-circle"></i>
                    {{ __('app.customer_since') }}
                </span>
                <span class="info-value">
                    <span class="datetime-display">{{ $cliente->created_at->format('d/m/Y') }}</span>
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-pencil-square"></i>
                    {{ __('app.last_modified') }}
                </span>
                <span class="info-value">
                    <span class="datetime-display">{{ $cliente->updated_at->format('d/m/Y H:i') }}</span>
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-calendar-check"></i>
                    {{ __('app.customer_for') }}
                </span>
                <span class="info-value">
                    <span class="datetime-display">{{ $cliente->created_at->diffForHumans() }}</span>
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">
                    <i class="bi bi-clock"></i>
                    {{ __('app.last_activity') }}
                </span>
                <span class="info-value">
                    <span class="datetime-display">{{ $cliente->updated_at->diffForHumans() }}</span>
                </span>
            </div>
        </div>
    </div>
</div>
@endsection