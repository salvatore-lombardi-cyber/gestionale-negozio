@extends('layouts.app')

@section('title', ucfirst($tipo) . ' - Anagrafiche')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .lista-container {
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
    }
    
    .header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .page-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .header-actions {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .modern-btn {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        border: none;
        border-radius: 15px;
        padding: 12px 24px;
        color: white;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }
    
    .modern-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(2, 157, 126, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .modern-btn.secondary {
        background: linear-gradient(135deg, #6c757d, #495057);
    }
    
    .modern-btn.warning {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
    }
    
    .modern-btn.warning:hover {
        background: linear-gradient(135deg, #ff8500, #ffd60a);
        color: white;
    }
    
    .modern-btn.primary {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
    }
    
    .modern-btn.primary:hover {
        background: linear-gradient(135deg, #4DC9A5, #029D7E);
        color: white;
    }
    
    .modern-btn.danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
    }
    
    .search-filter-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .search-box {
        position: relative;
        margin-bottom: 1rem;
    }
    
    .search-input {
        width: 100%;
        padding: 15px 50px 15px 20px;
        border: 2px solid rgba(2, 157, 126, 0.2);
        border-radius: 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
    }
    
    .search-input:focus {
        outline: none;
        border-color: #029D7E;
        box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
        background: white;
    }
    
    .search-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #029D7E;
        font-size: 1.2rem;
    }
    
    .filters-row {
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .filter-label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 600;
    }
    
    .filter-select {
        border: 1px solid rgba(2, 157, 126, 0.2);
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 0.9rem;
        background: white;
        min-width: 150px;
    }
    
    .results-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 0;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: visible !important;
        position: relative;
        z-index: 10;
    }
    
    .results-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
        overflow: visible !important;
        position: relative;
        z-index: 100;
    }
    
    .banner-title h3 {
        font-size: 1.4rem;
        font-weight: 700;
        color: #029D7E;
        margin: 0 0 0.25rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .banner-title p {
        margin: 0;
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .banner-controls {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
        overflow: visible !important;
    }
    
    .export-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    
    /* Dropdown Export Styles */
    .export-dropdown-menu {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(2, 157, 126, 0.2);
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        padding: 0.5rem;
        min-width: 280px;
        margin-top: 0.5rem;
        z-index: 1050 !important;
        position: relative;
    }
    
    .dropdown {
        position: relative;
        z-index: 1050;
    }
    
    .banner-controls {
        overflow: visible !important;
        z-index: 1050;
        position: relative;
    }
    
    .export-item {
        display: flex !important;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        border: none !important;
        transition: all 0.3s ease;
        text-decoration: none !important;
        color: #2d3748 !important;
    }
    
    .export-item:hover {
        background: linear-gradient(135deg, rgba(2, 157, 126, 0.1), rgba(77, 201, 165, 0.1));
        transform: translateX(5px);
        color: #029D7E !important;
    }
    
    .export-item i {
        font-size: 1.5rem;
        flex-shrink: 0;
    }
    
    .export-text {
        display: flex;
        flex-direction: column;
        gap: 0.2rem;
    }
    
    .export-text strong {
        font-weight: 600;
        font-size: 0.95rem;
    }
    
    .export-text small {
        color: #6c757d;
        font-size: 0.8rem;
    }
    
    .dropdown-toggle::after {
        margin-left: 0.5rem;
    }
    
    .table-responsive {
        margin: 0;
    }
    
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin: 0;
    }
    
    .modern-table thead th {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        color: white;
        font-weight: 600;
        border: none;
        padding: 1rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }
    
    .modern-table td {
        padding: 1rem;
        border-bottom: 1px solid rgba(226, 232, 240, 0.3);
        background: rgba(255, 255, 255, 0.8);
        vertical-align: middle;
    }
    
    .modern-table tbody tr {
        transition: all 0.3s ease;
        border: none;
    }
    
    .modern-table tbody tr:hover {
        background: rgba(72, 202, 228, 0.05);
        transform: scale(1.01);
    }
    
    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* Modern Card - come sistema V2 */
    .modern-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 2rem;
    }
    
    .modern-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }
    
    /* Action buttons */
    .action-btn {
        border: none;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        margin: 0 2px;
        transition: all 0.3s ease;
        text-decoration: none;
        cursor: pointer;
        padding: 8px;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .action-btn.view {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
    }
    
    .action-btn.edit {
        background: transparent;
        color: #ff8500;
        border: 1px solid #ffd60a;
    }
    
    .action-btn.delete {
        background: transparent;
        color: #f72585;
        border: 1px solid #f72585;
    }
    
    .action-btn.clone {
        background: transparent;
        color: #029D7E;
        border: 1px solid #029D7E;
    }
    
    .action-btn.pdf {
        background: linear-gradient(135deg, #9c27b0, #7b1fa2);
        color: white;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        text-decoration: none;
        color: inherit;
    }

    .badge-status {
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge-attivo {
        background: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }
    
    .badge-inattivo {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.25rem;
    }
    
    .action-btn {
        padding: 6px 8px;
        border: none;
        border-radius: 8px;
        background: rgba(2, 157, 126, 0.1);
        color: #029D7E;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
    }
    
    .action-btn.view:hover {
        background: linear-gradient(135deg, #0077b6, #48cae4);
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 119, 182, 0.3);
    }

    .action-btn.pdf:hover {
        background: linear-gradient(135deg, #7b1fa2, #9c27b0);
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(123, 31, 162, 0.3);
    }
    
    .action-btn.edit { 
        background: linear-gradient(135deg, #ffd60a, #ff8500); 
        color: white;
        border: none;
    }
    .action-btn.edit:hover { 
        background: linear-gradient(135deg, #ff8500, #ffd60a); 
        color: white; 
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 133, 0, 0.3);
    }
    
    .action-btn.delete { 
        background: linear-gradient(135deg, #f72585, #c5025a); 
        color: white;
        border: none;
    }
    .action-btn.delete:hover { 
        background: linear-gradient(135deg, #c5025a, #f72585); 
        color: white; 
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(247, 37, 133, 0.3);
    }
    
    .action-btn.clone { 
        background: linear-gradient(135deg, #029D7E, #4DC9A5); 
        color: white;
        border: none;
    }
    .action-btn.clone:hover { 
        background: linear-gradient(135deg, #4DC9A5, #029D7E); 
        color: white; 
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(2, 157, 126, 0.3);
    }
    
    .pagination-container {
        padding: 1.5rem 2rem;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .per-page-selector {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .per-page-select {
        border: 1px solid rgba(2, 157, 126, 0.2);
        border-radius: 5px;
        padding: 4px 8px;
        font-size: 0.8rem;
    }
    
    .pagination {
        margin: 0;
    }
    
    .page-link {
        border: 1px solid rgba(2, 157, 126, 0.2);
        color: #029D7E;
        padding: 8px 12px;
    }
    
    .page-link:hover {
        background: rgba(2, 157, 126, 0.1);
        border-color: #029D7E;
        color: #029D7E;
    }
    
    .page-item.active .page-link {
        background: #029D7E;
        border-color: #029D7E;
        color: white;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    /* Table responsive wrapper */
    .table-responsive {
        border-radius: 15px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 8px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        border-radius: 8px;
    }
    
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #4DC9A5, #029D7E);
    }
    
    

    /* Mobile Cards - Sistema Banche */
    .mobile-cards {
        display: none;
    }
    
    .anagrafica-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .anagrafica-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .anagrafica-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }
    
    .anagrafica-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        margin: 0;
        flex: 1;
        min-width: 0;
    }
    
    .anagrafica-card-codes {
        display: flex;
        flex-direction: column;
        align-items: end;
        gap: 0.3rem;
        flex-shrink: 0;
    }
    
    .anagrafica-code {
        font-family: 'Courier New', monospace;
        background: rgba(72, 202, 228, 0.1);
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.8rem;
        color: #023e8a;
        font-weight: 600;
    }
    
    .anagrafica-card-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .anagrafica-detail {
        display: flex;
        flex-direction: column;
    }
    
    .anagrafica-detail-label {
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 600;
        margin-bottom: 0.2rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .anagrafica-detail-value {
        font-weight: 500;
        color: #2d3748;
    }
    
    .anagrafica-card-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .mobile-action-btn {
        border: none;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        margin: 0 2px;
        transition: all 0.3s ease;
        text-decoration: none;
        cursor: pointer;
        padding: 8px;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .mobile-action-btn.view {
        background: linear-gradient(135deg, #48cae4, #0077b6);
        color: white;
    }
    
    .mobile-action-btn.edit {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
    }
    
    .mobile-action-btn.delete {
        background: linear-gradient(135deg, #f72585, #c5025a);
        color: white;
    }
    
    .mobile-action-btn.clone {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
    }
    
    .mobile-action-btn.pdf {
        background: linear-gradient(135deg, #9c27b0, #7b1fa2);
        color: white;
    }
    
    .mobile-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        color: white;
    }
    
    /* Assicurati che anche i button abbiano le stesse dimensioni dei link */
    button.mobile-action-btn {
        border: none;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        margin: 0 2px;
        transition: all 0.3s ease;
        cursor: pointer;
        padding: 8px;
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Responsive Mobile - Come Sistema Banche */
    @media (max-width: 768px) {
        .lista-container {
            padding: 1rem;
        }
        
        .modern-banner {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .banner-title h3 {
            font-size: 1.8rem;
        }
        
        .modern-btn {
            padding: 10px 16px;
            font-size: 0.9rem;
        }
        
        /* Nasconde tabella su mobile */
        .table-responsive {
            display: none !important;
        }
        
        .mobile-cards {
            display: block;
            margin-top: 2rem; /* Margine tra banner e prime card */
        }
        
        .header-actions {
            justify-content: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .filters-row {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }
        
        .filter-group {
            width: 100%;
        }
    }
    
    @media (max-width: 576px) {
        .anagrafica-card {
            padding: 1rem;
        }
        
        .anagrafica-card-details {
            grid-template-columns: 1fr;
            gap: 0.8rem;
        }
        
        .mobile-action-btn {
            padding: 6px 3px;
            font-size: 0.6rem;
            min-width: 45px;
        }
        
        .mobile-action-btn i {
            font-size: 0.85rem;
        }
        
        .anagrafica-card-actions {
            gap: 0.3rem;
        }
        
    }
</style>

<div class="container-fluid lista-container">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Attenzione!</strong> Sono stati rilevati dei problemi:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header -->
    <div class="page-header">
        <div class="header-row">
            <div>
                <h1 class="page-title">
                    <i class="bi bi-{{ $tipo === 'cliente' ? 'people' : ($tipo === 'fornitore' ? 'building' : ($tipo === 'vettore' ? 'truck' : ($tipo === 'agente' ? 'person-badge' : ($tipo === 'articolo' ? 'box-seam' : 'tools')))) }}"></i>
                    {{ $tipo === 'cliente' ? 'Clienti' : ($tipo === 'fornitore' ? 'Fornitori' : ($tipo === 'vettore' ? 'Vettori' : ($tipo === 'agente' ? 'Agenti' : ($tipo === 'articolo' ? 'Articoli' : 'Servizi')))) }}
                </h1>
                <p class="mb-0 text-muted">Gestione completa {{ $tipo === 'cliente' ? 'clienti' : ($tipo === 'fornitore' ? 'fornitori' : ($tipo === 'vettore' ? 'vettori' : ($tipo === 'agente' ? 'agenti' : ($tipo === 'articolo' ? 'articoli' : 'servizi')))) }}</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('anagrafiche.index') }}" class="modern-btn secondary">
                    <i class="bi bi-arrow-left"></i> Indietro
                </a>
                <a href="{{ route('anagrafiche.create', $tipo) }}" class="modern-btn">
                    <i class="bi bi-plus-lg"></i> Nuovo {{ ucfirst($tipo) }}
                </a>
            </div>
        </div>
    </div>
    
    <!-- Ricerca e Filtri -->
    <div class="search-filter-container">
        <form method="GET" action="{{ route('anagrafiche.lista', $tipo) }}">
            <div class="search-box">
                <input 
                    type="text" 
                    name="search" 
                    class="search-input" 
                    placeholder="Cerca per nome, codice, email, P.IVA o codice fiscale..."
                    value="{{ request('search') }}"
                >
                <i class="bi bi-search search-icon"></i>
            </div>
            
            @if(count($filtri) > 0)
            <div class="filters-row">
                @foreach($filtri as $filtro)
                <div class="filter-group">
                    <label class="filter-label">{{ ucfirst(str_replace('_', ' ', $filtro)) }}</label>
                    <select name="{{ $filtro }}" class="filter-select">
                        <option value="">Tutti</option>
                        @if($filtro === 'tipo_soggetto')
                            <option value="definitivo" {{ request($filtro) === 'definitivo' ? 'selected' : '' }}>Definitivo</option>
                            <option value="potenziale" {{ request($filtro) === 'potenziale' ? 'selected' : '' }}>Potenziale</option>
                        @elseif($filtro === 'tipo_trasporto')
                            <option value="proprio" {{ request($filtro) === 'proprio' ? 'selected' : '' }}>Proprio</option>
                            <option value="terzi" {{ request($filtro) === 'terzi' ? 'selected' : '' }}>Terzi</option>
                        @elseif($filtro === 'tipo_contratto')
                            <option value="dipendente" {{ request($filtro) === 'dipendente' ? 'selected' : '' }}>Dipendente</option>
                            <option value="collaboratore" {{ request($filtro) === 'collaboratore' ? 'selected' : '' }}>Collaboratore</option>
                            <option value="esterno" {{ request($filtro) === 'esterno' ? 'selected' : '' }}>Esterno</option>
                        @endif
                    </select>
                </div>
                @endforeach
                
                <div class="filter-group">
                    <label class="filter-label">&nbsp;</label>
                    <button type="submit" class="modern-btn" style="height: 42px;">
                        <i class="bi bi-funnel"></i> Filtra
                    </button>
                </div>
            </div>
            @endif
        </form>
    </div>
    
    <!-- Banner Unificato -->
    <div class="results-container">
        <div class="results-header">
            <div class="banner-title">
                <h3>
                    @if($tipo === 'cliente')
                        <i class="bi bi-people-fill"></i> Archivio Clienti
                    @elseif($tipo === 'fornitore') 
                        <i class="bi bi-building-fill"></i> Gestione Fornitori
                    @elseif($tipo === 'vettore')
                        <i class="bi bi-truck"></i> Registro Vettori
                    @elseif($tipo === 'agente')
                        <i class="bi bi-person-badge-fill"></i> Team Agenti
                    @elseif($tipo === 'articolo')
                        <i class="bi bi-box-seam-fill"></i> Catalogo Articoli
                    @else
                        <i class="bi bi-tools"></i> Listino Servizi
                    @endif
                </h3>
                <p><strong>{{ $anagrafiche->total() }}</strong> elementi • Pagina {{ $anagrafiche->currentPage() }} di {{ $anagrafiche->lastPage() }}</p>
            </div>
            <div class="banner-controls">
                <div class="per-page-selector">
                    <span>Mostra</span>
                    <select class="per-page-select" onchange="changePerPage(this.value)">
                        @foreach([10, 20, 50, 100] as $num)
                        <option value="{{ $num }}" {{ request('per_page', 20) == $num ? 'selected' : '' }}>
                            {{ $num }}
                        </option>
                        @endforeach
                    </select>
                    <span>per pagina</span>
                </div>
                <div class="export-actions">
                    <div class="dropdown">
                        <button class="modern-btn primary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-download"></i> Esporta
                        </button>
                        <ul class="dropdown-menu export-dropdown-menu" aria-labelledby="exportDropdown">
                            <li>
                                <a class="dropdown-item export-item" href="{{ route('anagrafiche.excel-export', $tipo) }}?formato=excel">
                                    <i class="bi bi-file-earmark-excel text-success"></i>
                                    <div class="export-text">
                                        <strong>Excel</strong>
                                        <small>Foglio di calcolo (.xlsx)</small>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item export-item" href="{{ route('anagrafiche.excel-export', $tipo) }}?formato=csv">
                                    <i class="bi bi-file-earmark-text text-warning"></i>
                                    <div class="export-text">
                                        <strong>CSV</strong>
                                        <small>Valori separati da virgola (.csv)</small>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item export-item" href="{{ route('anagrafiche.export', ['tipo' => $tipo, 'format' => 'pdf-all']) }}">
                                    <i class="bi bi-file-earmark-pdf text-danger"></i>
                                    <div class="export-text">
                                        <strong>PDF Completo</strong>
                                        <small>Archivio completo (.pdf)</small>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="pagination-container">
            {{ $anagrafiche->links() }}
        </div>
    </div>
        
    @if($anagrafiche->count() > 0)
    <!-- Tabella su sfondo verde -->
    <div class="table-responsive" style="margin: 3rem 0 2rem 0;">
        <table class="table modern-table">
                <thead>
                    <tr>
                        @foreach($colonne as $colonna)
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort' => $colonna, 'direction' => request('sort') === $colonna && request('direction') === 'asc' ? 'desc' : 'asc']) }}" 
                               class="text-decoration-none text-reset">
                                <i class="bi bi-{{ $colonna === 'nome' ? 'person' : ($colonna === 'email' ? 'envelope' : ($colonna === 'telefono_1' ? 'telephone' : ($colonna === 'codice_interno' ? 'tag' : 'info-circle'))) }}"></i> 
                                {{ ucfirst(str_replace('_', ' ', $colonna)) }}
                                @if(request('sort') === $colonna)
                                    <i class="bi bi-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        @endforeach
                        <th><i class="bi bi-toggle-on"></i> Stato</th>
                        <th><i class="bi bi-gear"></i> Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($anagrafiche as $anagrafica)
                    <tr>
                        @foreach($colonne as $colonna)
                        <td>
                            @if($colonna === 'nome')
                                <strong>{{ $anagrafica->nome_completo }}</strong>
                            @elseif($colonna === 'prezzo_vendita' || $colonna === 'tariffa_oraria')
                                €{{ number_format($anagrafica->$colonna ?? 0, 2) }}
                            @elseif($colonna === 'scorta_minima')
                                {{ $anagrafica->$colonna ?? 0 }}
                            @else
                                {{ $anagrafica->$colonna ?? '-' }}
                            @endif
                        </td>
                        @endforeach
                        <td>
                            <span class="badge-status {{ $anagrafica->attivo ? 'badge-attivo' : 'badge-inattivo' }}">
                                {{ $anagrafica->attivo ? 'Attivo' : 'Inattivo' }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('anagrafiche.show', ['tipo' => $tipo, 'anagrafica' => $anagrafica->id]) }}" 
                                   class="action-btn view" title="Visualizza">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('anagrafiche.edit', ['tipo' => $tipo, 'anagrafica' => $anagrafica->id]) }}" 
                                   class="action-btn edit" title="Modifica">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('anagrafiche.export', ['tipo' => $tipo, 'format' => 'pdf']) }}?id={{ $anagrafica->id }}" 
                                   class="action-btn pdf" title="PDF">
                                    <i class="bi bi-file-pdf"></i>
                                </a>
                                <a href="{{ route('anagrafiche.duplicate', ['tipo' => $tipo, 'anagrafica' => $anagrafica->id]) }}" 
                                   class="action-btn clone" title="Duplica">
                                    <i class="bi bi-files"></i>
                                </a>
                                <form method="POST" action="{{ route('anagrafiche.destroy', ['tipo' => $tipo, 'anagrafica' => $anagrafica->id]) }}" 
                                      style="display: inline;" 
                                      onsubmit="return confirm('Sei sicuro di voler eliminare questo {{ $tipo }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete" title="Elimina">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
        </table>
    </div>

    <!-- Cards Mobile - Sistema Banche -->
    <div class="mobile-cards">
        @foreach($anagrafiche as $anagrafica)
            <div class="anagrafica-card">
                <div class="anagrafica-card-header">
                    <h3 class="anagrafica-card-title">
                        {{ $anagrafica->nome }}{{ $anagrafica->cognome ? ' ' . $anagrafica->cognome : '' }}
                    </h3>
                    <div class="anagrafica-card-codes">
                        <span class="anagrafica-code">{{ $anagrafica->codice_interno }}</span>
                        @if($anagrafica->attivo)
                            <span class="status-badge status-active" style="font-size: 0.7rem;">Attivo</span>
                        @else
                            <span class="status-badge status-inactive" style="font-size: 0.7rem;">Inattivo</span>
                        @endif
                    </div>
                </div>
                
                <div class="anagrafica-card-details">
                    @if($anagrafica->email)
                    <div class="anagrafica-detail">
                        <span class="anagrafica-detail-label">Email</span>
                        <span class="anagrafica-detail-value">{{ $anagrafica->email }}</span>
                    </div>
                    @endif
                    
                    @if($anagrafica->telefono_1)
                    <div class="anagrafica-detail">
                        <span class="anagrafica-detail-label">Telefono</span>
                        <span class="anagrafica-detail-value">{{ $anagrafica->telefono_1 }}</span>
                    </div>
                    @endif
                    
                    @if($anagrafica->comune)
                    <div class="anagrafica-detail">
                        <span class="anagrafica-detail-label">Comune</span>
                        <span class="anagrafica-detail-value">{{ $anagrafica->comune }}</span>
                    </div>
                    @endif
                    
                    @if($anagrafica->provincia)
                    <div class="anagrafica-detail">
                        <span class="anagrafica-detail-label">Provincia</span>
                        <span class="anagrafica-detail-value">{{ $anagrafica->provincia }}</span>
                    </div>
                    @endif
                    
                    @if($anagrafica->codice_fiscale)
                    <div class="anagrafica-detail">
                        <span class="anagrafica-detail-label">Codice Fiscale</span>
                        <span class="anagrafica-detail-value">{{ $anagrafica->codice_fiscale }}</span>
                    </div>
                    @endif
                    
                    @if($anagrafica->partita_iva)
                    <div class="anagrafica-detail">
                        <span class="anagrafica-detail-label">P.IVA</span>
                        <span class="anagrafica-detail-value">{{ $anagrafica->partita_iva }}</span>
                    </div>
                    @endif
                </div>
                
                <div class="anagrafica-card-actions">
                    <a href="{{ route('anagrafiche.show', [$tipo, $anagrafica->codice_interno]) }}" class="mobile-action-btn view" title="Visualizza">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('anagrafiche.edit', [$tipo, $anagrafica->codice_interno]) }}" class="mobile-action-btn edit" title="Modifica">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <a href="{{ route('anagrafiche.export', [$tipo, $anagrafica->codice_interno]) }}" class="mobile-action-btn pdf" target="_blank" title="PDF">
                        <i class="bi bi-file-pdf"></i>
                    </a>
                    <a href="{{ route('anagrafiche.duplicate', [$tipo, $anagrafica->codice_interno]) }}" class="mobile-action-btn clone" title="Duplica">
                        <i class="bi bi-files"></i>
                    </a>
                    <form method="POST" action="{{ route('anagrafiche.destroy', [$tipo, $anagrafica->codice_interno]) }}" style="display: inline;" onsubmit="return confirm('Sei sicuro di voler eliminare questa anagrafica?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="mobile-action-btn delete" title="Elimina">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    @else
    <!-- Empty State per Tabella Desktop -->
    <div class="results-container table-responsive">
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h4>Nessun risultato trovato</h4>
            <p>Non sono stati trovati {{ $tipo === 'cliente' ? 'clienti' : ($tipo === 'fornitore' ? 'fornitori' : ($tipo === 'vettore' ? 'vettori' : ($tipo === 'agente' ? 'agenti' : ($tipo === 'articolo' ? 'articoli' : 'servizi')))) }} che corrispondono ai criteri di ricerca.</p>
        </div>
    </div>
    
    <!-- Empty State per Cards Mobile -->
    <div class="mobile-cards">
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h4>Nessun risultato trovato</h4>
            <p>Non sono stati trovati {{ $tipo === 'cliente' ? 'clienti' : ($tipo === 'fornitore' ? 'fornitori' : ($tipo === 'vettore' ? 'vettori' : ($tipo === 'agente' ? 'agenti' : ($tipo === 'articolo' ? 'articoli' : 'servizi')))) }} che corrispondono ai criteri di ricerca.</p>
        </div>
    </div>
    @endif
</div>

<script>
function changePerPage(value) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', value);
    url.searchParams.set('page', 1);
    window.location.href = url.toString();
}

// Auto-submit form on filter change
document.querySelectorAll('.filter-select').forEach(select => {
    select.addEventListener('change', function() {
        this.closest('form').submit();
    });
});

// Debounced search
let searchTimeout;
document.querySelector('.search-input').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        this.closest('form').submit();
    }, 500);
});

// Auto-dismiss alerts dopo 5 secondi
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});

</script>
@endsection