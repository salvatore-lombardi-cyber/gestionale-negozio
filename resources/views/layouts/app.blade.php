<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionale Negozio</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        /* Navbar Moderna */
        .modern-navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(102, 126, 234, 0.2);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        
        .modern-navbar .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .modern-navbar .nav-link {
            color: #4a5568 !important;
            font-weight: 600;
            padding: 8px 16px !important;
            border-radius: 10px;
            margin: 0 4px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .modern-navbar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            transition: all 0.3s ease;
            z-index: -1;
            border-radius: 10px;
        }
        
        .modern-navbar .nav-link:hover::before {
            left: 0;
        }
        
        .modern-navbar .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }
        
        .modern-navbar .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(102, 126, 234, 0.2);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .modern-navbar .dropdown-item {
            border-radius: 10px;
            margin: 2px;
            transition: all 0.3s ease;
        }
        
        .modern-navbar .dropdown-item:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        /* Dark Mode per Navbar */
        [data-bs-theme="dark"] .modern-navbar {
            background: rgba(33, 37, 41, 0.95) !important;
            border-bottom-color: rgba(102, 126, 234, 0.3);
        }
        
        [data-bs-theme="dark"] .modern-navbar .nav-link {
            color: #e2e8f0 !important;
        }
        
        [data-bs-theme="dark"] .modern-navbar .dropdown-menu {
            background: rgba(33, 37, 41, 0.95);
        }
        
        /* Body background */
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        /* Dark Mode generale */
        [data-bs-theme="dark"] {
            background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%) !important;
        }
        
        [data-bs-theme="dark"] .dashboard-header {
            background: rgba(45, 55, 72, 0.95) !important;
            color: #e2e8f0 !important;
        }
        
        [data-bs-theme="dark"] .dashboard-title {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        [data-bs-theme="dark"] .welcome-text {
            color: #a0aec0 !important;
        }
        
        [data-bs-theme="dark"] .stats-card {
            background: rgba(45, 55, 72, 0.95) !important;
            color: #e2e8f0 !important;
        }
        
        [data-bs-theme="dark"] .stats-number {
            color: #e2e8f0 !important;
        }
        
        [data-bs-theme="dark"] .stats-label {
            color: #a0aec0 !important;
        }
        
        [data-bs-theme="dark"] .card {
            background: rgba(45, 55, 72, 0.95) !important;
            border-color: rgba(102, 126, 234, 0.2) !important;
            color: #e2e8f0 !important;
        }
        
        [data-bs-theme="dark"] .card-header {
            background: rgba(45, 55, 72, 0.8) !important;
            border-color: rgba(102, 126, 234, 0.2) !important;
            color: #e2e8f0 !important;
        }
        
        [data-bs-theme="dark"] .table {
            --bs-table-bg: rgba(45, 55, 72, 0.8) !important;
            --bs-table-color: #e2e8f0 !important;
        }
        
        [data-bs-theme="dark"] .form-control {
            background: rgba(45, 55, 72, 0.8) !important;
            border-color: rgba(102, 126, 234, 0.3) !important;
            color: #e2e8f0 !important;
        }
        
        [data-bs-theme="dark"] .form-control:focus {
            background: rgba(45, 55, 72, 0.9) !important;
            border-color: #667eea !important;
            color: #e2e8f0 !important;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
        }
        
        [data-bs-theme="dark"] .list-group-item {
            background: rgba(45, 55, 72, 0.8) !important;
            border-color: rgba(102, 126, 234, 0.2) !important;
            color: #e2e8f0 !important;
        }
        
        /* Contenuto principale */
        main {
            padding: 0 !important;
        }
        
        /* Navbar toggler personalizzato */
        .navbar-toggler {
            border: none;
            padding: 4px 8px;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28102, 126, 234, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='m4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        [data-bs-theme="dark"] .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28226, 232, 240, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='m4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        /* Responsive Navbar */
        @media (max-width: 991.98px) {
            .modern-navbar .navbar-collapse {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(10px);
                border-radius: 15px;
                margin-top: 10px;
                padding: 15px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            }
            
            [data-bs-theme="dark"] .modern-navbar .navbar-collapse {
                background: rgba(33, 37, 41, 0.98);
            }
            
            .modern-navbar .nav-link {
                margin: 2px 0;
            }
        }
        
        /* Smooth transitions */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Navbar Moderna -->
    <nav class="navbar navbar-expand-lg modern-navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-shop"></i>
                {{ __('app.store_management') }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="bi bi-speedometer2"></i> {{ __('app.dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('prodotti.index') }}">
                            <i class="bi bi-box-seam"></i> {{ __('app.products') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('clienti.index') }}">
                            <i class="bi bi-people"></i> {{ __('app.clients') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('vendite.index') }}">
                            <i class="bi bi-cart-check"></i> {{ __('app.sales') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('ddts.index') }}">
                            <i class="bi bi-file-earmark-text"></i> {{ __('app.ddts') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('magazzino.index') }}">
                            <i class="bi bi-archive"></i> {{ __('app.warehouse') }}
                        </a>
                    </li>
                </ul>
                
                <!-- User menu -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" onclick="toggleDarkMode(); return false;">
                                <i class="bi bi-moon" id="dark-mode-icon"></i> <span id="dark-mode-text">{{ __('app.dark_mode') }}</span>
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('language.change', 'it') }}">
                                <i class="bi bi-globe"></i> Italiano
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('language.change', 'en') }}">
                                <i class="bi bi-globe"></i> English
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="bi bi-person-circle"></i> {{ __('app.profile') }}
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> {{ __('app.logout') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Contenuto principale -->
    <main>
        @yield('content')
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function toggleDarkMode() {
            const html = document.documentElement;
            const icon = document.getElementById('dark-mode-icon');
            const text = document.getElementById('dark-mode-text');
            
            if (html.getAttribute('data-bs-theme') === 'dark') {
                html.setAttribute('data-bs-theme', 'light');
                icon.className = 'bi bi-moon';
                text.textContent = '{{ __("app.dark_mode") }}';
                localStorage.setItem('darkMode', 'light');
            } else {
                html.setAttribute('data-bs-theme', 'dark');
                icon.className = 'bi bi-sun';
                text.textContent = '{{ __("app.light_mode") }}';
                localStorage.setItem('darkMode', 'dark');
            }
        }
        
        // Carica preferenza al caricamento pagina
        document.addEventListener('DOMContentLoaded', function() {
            const savedMode = localStorage.getItem('darkMode');
            if (savedMode === 'dark') {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
                const icon = document.getElementById('dark-mode-icon');
                const text = document.getElementById('dark-mode-text');
                if (icon) icon.className = 'bi bi-sun';
                if (text) text.textContent = '{{ __("app.light_mode") }}';
            }
        });
        
        // Effetto scroll navbar
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.modern-navbar');
            if (window.scrollY > 50) {
                navbar.style.boxShadow = '0 8px 32px rgba(0, 0, 0, 0.15)';
            } else {
                navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.1)';
            }
        });
    </script>
</body>
</html>