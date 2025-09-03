<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestionale Negozio</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        /* Sidebar Moderna - parte da sotto la navbar */
        .modern-sidebar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(102, 126, 234, 0.2);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: fixed;
            top: 70px;
            left: 0;
            height: calc(100vh - 70px);
            width: 210px;
            z-index: 1025;
            overflow-y: auto;
            padding: 20px 0;
        }
        
        
        .modern-sidebar .nav-link {
            color: #4a5568 !important;
            font-weight: 600;
            padding: 12px 20px !important;
            border-radius: 0 25px 25px 0;
            margin: 2px 0 2px 15px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        
        .modern-sidebar .nav-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }
        
        .modern-sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
            transition: all 0.3s ease;
            z-index: -1;
            border-radius: 0 25px 25px 0;
        }
        
        .modern-sidebar .nav-link:hover::before {
            left: 0;
        }
        
        .modern-sidebar .nav-link:hover {
            color: white !important;
            transform: translateX(5px);
        }
        
        /* Dark Mode per Sidebar */
        [data-bs-theme="dark"] .modern-sidebar {
            background: rgba(33, 37, 41, 0.95) !important;
            border-right-color: rgba(102, 126, 234, 0.3);
        }
        
        [data-bs-theme="dark"] .modern-sidebar .nav-link {
            color: #e2e8f0 !important;
        }
        
        /* Navbar orizzontale in alto - tutta la larghezza */
        .top-navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(102, 126, 234, 0.2);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            height: 70px;
            z-index: 1030;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }
        
        .top-navbar .navbar-brand {
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .top-navbar .navbar-brand img {
            height: 40px;
            max-width: 200px;
            object-fit: contain;
            transition: all 0.3s ease;
        }
        
        .top-navbar .navbar-brand:hover img {
            transform: scale(1.05);
        }
        
        .top-navbar .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(102, 126, 234, 0.2);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .top-navbar .dropdown-item {
            border-radius: 10px;
            margin: 2px;
            transition: all 0.3s ease;
        }
        
        .top-navbar .dropdown-item:hover {
            background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
            color: white;
        }
        
        .top-navbar .nav-link {
            color: #4a5568 !important;
            font-weight: 600;
            padding: 8px 16px !important;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .top-navbar .nav-link:hover {
            background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
            color: white !important;
        }
        
        /* Dark Mode per top navbar */
        [data-bs-theme="dark"] .top-navbar {
            background: rgba(33, 37, 41, 0.95) !important;
            border-bottom-color: rgba(102, 126, 234, 0.3);
        }
        
        [data-bs-theme="dark"] .top-navbar .nav-link {
            color: #e2e8f0 !important;
        }
        
        [data-bs-theme="dark"] .top-navbar .dropdown-menu {
            background: rgba(33, 37, 41, 0.95);
        }
        
        /* Body background */
        body {
            background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
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
        
        /* Contenuto principale con offset per sidebar e navbar */
        main {
            margin-left: 210px;
            margin-top: 70px;
            padding: 0 !important;
            min-height: calc(100vh - 70px);
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
        
        /* Mobile toggle button - posizionato nella navbar */
        .sidebar-toggle {
            display: none;
            background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            color: white;
            font-size: 16px;
            margin-right: 15px;
        }
        
        /* Responsive Layout */
        @media (max-width: 991.98px) {
            .modern-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                top: 70px;
            }
            
            .modern-sidebar.active {
                transform: translateX(0);
            }
            
            .sidebar-toggle {
                display: inline-block;
            }
            
            main {
                margin-left: 0;
                margin-top: 70px;
            }
            
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 70px;
                left: 0;
                width: 100%;
                height: calc(100vh - 70px);
                background: rgba(0, 0, 0, 0.5);
                z-index: 1020;
            }
            
            .sidebar-overlay.active {
                display: block;
            }
        }
        
        /* Smooth transitions */
        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <nav class="top-navbar">
        <!-- Sidebar toggle button (mobile) -->
        <button class="sidebar-toggle" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
        
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <img src="{{ asset('finson_logo.png') }}" alt="Finson" style="height: 40px; max-width: 200px;">
        </a>
        
        <div class="ms-auto">
            <div class="dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person"></i>
                    {{ Auth::user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#" onclick="toggleDarkMode(); return false;">
                        <i class="bi bi-moon" id="dark-mode-icon"></i> 
                        <span id="dark-mode-text">{{ __('app.dark_mode') }}</span>
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
            </div>
        </div>
    </nav>
    
    <!-- Overlay per mobile -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    
    <!-- Sidebar Moderna -->
    <nav class="modern-sidebar" id="sidebar">
        <!-- Navigation Links -->
        <div class="nav flex-column pt-4">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                {{ __('app.dashboard') }}
            </a>
            <a class="nav-link" href="{{ route('anagrafiche.index') }}">
                <i class="bi bi-person-lines-fill"></i>
                {{ __('app.anagrafiche') }}
            </a>
            <a class="nav-link" href="{{ route('magazzino-overview.index') }}">
                <i class="bi bi-boxes"></i>
                {{ __('app.warehouse_overview') }}
            </a>
            <a class="nav-link" href="{{ route('configurations.index') }}">
                <i class="bi bi-gear"></i>
                {{ __('app.configurations') }}
            </a>
            <a class="nav-link" href="{{ route('vendite.index') }}">
                <i class="bi bi-cart-check"></i>
                {{ __('app.sales') }}
            </a>
            <a class="nav-link" href="{{ route('fatturazione.index') }}">
                <i class="bi bi-receipt"></i>
                {{ __('app.fatturazione') }}
            </a>
            <a class="nav-link" href="{{ route('ddts.index') }}">
                <i class="bi bi-file-earmark-text"></i>
                {{ __('app.ddts') }}
            </a>
            <a class="nav-link" href="{{ route('ai.index') }}">
                <i class="bi bi-robot"></i>
                {{ __('app.ai_assistant') }}
            </a>
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
        
        // Toggle sidebar per mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }
        
        // Chiudi sidebar quando si clicca su un link (mobile)
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('.modern-sidebar .nav-link:not(.dropdown-toggle)');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 991.98) {
                        toggleSidebar();
                    }
                });
            });
        });
    </script>
    
    <!-- Calcolatrice Finson Pro Globale -->
    <button type="button" id="globalCalcToggleBtn" class="calculator-toggle-btn-global" title="Calcolatrice Finson Pro" 
            onclick="document.getElementById('globalCalculatorWidget').style.display = document.getElementById('globalCalculatorWidget').style.display === 'block' ? 'none' : 'block';">
        <i class="bi bi-calculator"></i>
    </button>

    <!-- Calculator Widget Globale -->
    <div id="globalCalculatorWidget" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 340px; background: linear-gradient(145deg, #ffffff, #f8f9fa); border: none; border-radius: 20px; z-index: 99999; box-shadow: 0 25px 60px rgba(0,0,0,0.25), 0 0 0 1px rgba(255,255,255,0.1); backdrop-filter: blur(20px);">
        <div style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 20px; border-radius: 18px 18px 0 0; text-align: center; font-weight: bold; font-size: 18px; position: relative; box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);">
            <i class="bi bi-calculator" style="margin-right: 8px;"></i>Calcolatrice Finson Pro
            <button type="button" onclick="document.getElementById('globalCalculatorWidget').style.display='none'" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: rgba(255,255,255,0.2); border: none; color: white; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; transition: all 0.2s ease;" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='translateY(-50%) scale(1.1)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(-50%)'">×</button>
        </div>
        
        <div style="padding: 20px;">
            <input type="text" id="globalCalcDisplay" readonly value="0" style="width: 100%; padding: 15px; font-size: 24px; text-align: right; border: 2px solid #e9ecef; border-radius: 12px; margin-bottom: 20px; font-family: 'Courier New', monospace; background: linear-gradient(145deg, #f8f9fa, #ffffff); color: #2d3748; font-weight: 600; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
            
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;">
                <button onclick="globalClearAll()" style="padding: 12px; border: none; background: #ff6b6b; color: white; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">AC</button>
                <button onclick="globalClearEntry()" style="padding: 12px; border: none; background: #ffa500; color: white; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">C</button>
                <button onclick="globalAppendPercentage()" style="padding: 12px; border: none; background: #667eea; color: white; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">%</button>
                <button onclick="globalAppendOperator('/')" style="padding: 12px; border: none; background: #667eea; color: white; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">÷</button>
                
                <button onclick="globalAppendNumber('7')" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">7</button>
                <button onclick="globalAppendNumber('8')" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">8</button>
                <button onclick="globalAppendNumber('9')" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">9</button>
                <button onclick="globalAppendOperator('*')" style="padding: 12px; border: none; background: #667eea; color: white; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">×</button>
                
                <button onclick="globalAppendNumber('4')" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">4</button>
                <button onclick="globalAppendNumber('5')" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">5</button>
                <button onclick="globalAppendNumber('6')" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">6</button>
                <button onclick="globalAppendOperator('-')" style="padding: 12px; border: none; background: #667eea; color: white; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">−</button>
                
                <button onclick="globalAppendNumber('1')" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">1</button>
                <button onclick="globalAppendNumber('2')" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">2</button>
                <button onclick="globalAppendNumber('3')" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">3</button>
                <button onclick="globalAppendOperator('+')" style="padding: 12px; border: none; background: #667eea; color: white; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">+</button>
                
                <button onclick="globalAppendNumber('0')" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; grid-column: span 2; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">0</button>
                <button onclick="globalAppendDecimal()" style="padding: 12px; border: none; background: #f8f9fa; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); color: #333;" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)'; this.style.background='#e9ecef'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#f8f9fa'">.</button>
                <button onclick="globalCalculate()" style="padding: 12px; border: none; background: #029D7E; color: white; border-radius: 8px; cursor: pointer; font-weight: bold; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1);" onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'; this.style.background='#027a65'" onmouseout="this.style.transform=''; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'; this.style.background='#029D7E'">=</button>
            </div>
            
            <!-- Mini Chat Interface -->
            <div class="calculator-chat" style="padding: 15px; border-top: 1px solid #e9ecef; background: #f8f9fa;">
                <div class="chat-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
                    <span style="font-size: 0.85rem; color: #6c757d; font-weight: 600;">💬 Assistente Matematico</span>
                    <button onclick="clearCalculatorChat()" style="background: none; border: none; color: #6c757d; font-size: 0.75rem; cursor: pointer; padding: 2px 6px; border-radius: 4px;" title="Cancella cronologia">🗑️</button>
                </div>
                
                <!-- Chat Messages -->
                <div id="calcChatMessages" class="chat-messages" style="margin-bottom: 10px; border-radius: 8px; background: white; padding: 8px; border: 1px solid #dee2e6;">
                    <div class="welcome-message" style="font-size: 0.8rem; color: #6c757d; text-align: center; padding: 8px;">
                        💬 Chiedi calcoli, percentuali, IVA, sconti...
                    </div>
                </div>
                
                <!-- Chat Input -->
                <div style="display: flex; gap: 8px;">
                    <input type="text" id="calcChatInput" placeholder="Es: Calcola il 15% di 1200€..." 
                           style="flex: 1; padding: 8px 12px; border: 1px solid #ced4da; border-radius: 20px; font-size: 0.85rem; outline: none; transition: all 0.2s ease;"
                           onkeypress="if(event.key==='Enter') sendCalculatorMessage()"
                           onfocus="this.style.borderColor='#667eea'; this.style.boxShadow='0 0 0 0.2rem rgba(102, 126, 234, 0.25)'"
                           onblur="this.style.borderColor='#ced4da'; this.style.boxShadow='none'">
                    <button onclick="sendCalculatorMessage()" 
                            style="padding: 8px 16px; background: #667eea; color: white; border: none; border-radius: 20px; cursor: pointer; font-size: 0.85rem; transition: all 0.2s ease;"
                            onmouseover="this.style.background='#5a67d8'" 
                            onmouseout="this.style.background='#667eea'">
                        ▶️
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stili Calcolatrice Globale -->
    <style>
        .calculator-toggle-btn-global {
            position: fixed;
            right: 30px;
            bottom: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 50%;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            transition: all 0.3s ease;
            z-index: 9999;
        }
        
        .calculator-toggle-btn-global:hover {
            transform: scale(1.1);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }
        
        .calculator-toggle-btn-global:active {
            transform: scale(0.95);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.6);
        }
        
        /* Dark Mode */
        [data-bs-theme="dark"] #globalCalculatorWidget {
            background: linear-gradient(145deg, #2d3748, #4a5568) !important;
        }
        
        [data-bs-theme="dark"] #globalCalculatorWidget input {
            background: linear-gradient(145deg, #1a202c, #2d3748) !important;
            color: #e2e8f0 !important;
        }
        
        [data-bs-theme="dark"] #globalCalculatorWidget button {
            color: #e2e8f0 !important;
        }
        
        /* Calculator Chat Styles */
        .calculator-chat {
            border-top: 1px solid #e9ecef;
            background: #f8f9fa;
            border-radius: 0 0 20px 20px;
        }
        
        .calculator-chat .chat-messages {
            max-height: 160px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #667eea #f8f9fa;
        }
        
        .calculator-chat .chat-messages::-webkit-scrollbar {
            width: 6px;
        }
        
        .calculator-chat .chat-messages::-webkit-scrollbar-track {
            background: #f8f9fa;
            border-radius: 3px;
        }
        
        .calculator-chat .chat-messages::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 3px;
        }
        
        .calculator-chat .chat-messages::-webkit-scrollbar-thumb:hover {
            background: #5a67d8;
        }
        
        /* Mobile */
        @media (max-width: 768px) {
            #globalCalculatorWidget {
                width: 90% !important;
            }
            
            .calculator-toggle-btn-global {
                right: 20px;
                bottom: 20px;
                width: 55px;
                height: 55px;
                font-size: 1.3rem;
            }
            
            .calculator-chat .chat-messages {
                max-height: 140px;
            }
        }
    </style>
    
    <!-- Script Calcolatrice Globale -->
    <script>
        // Variabili globali calcolatrice
        let globalCalculatorCurrentInput = '0';
        let globalCalculatorPreviousInput = null;
        let globalCalculatorOperator = null;
        let globalCalculatorWaitingForOperand = false;

        // === FUNZIONI CALCOLATRICE GLOBALI ===
        function globalUpdateCalculatorDisplay() {
            document.getElementById('globalCalcDisplay').value = globalCalculatorCurrentInput;
        }

        function globalAppendNumber(number) {
            if (globalCalculatorWaitingForOperand) {
                globalCalculatorCurrentInput = number;
                globalCalculatorWaitingForOperand = false;
            } else {
                globalCalculatorCurrentInput = globalCalculatorCurrentInput === '0' ? number : globalCalculatorCurrentInput + number;
            }
            globalUpdateCalculatorDisplay();
        }

        function globalAppendDecimal() {
            if (globalCalculatorWaitingForOperand) {
                globalCalculatorCurrentInput = '0.';
                globalCalculatorWaitingForOperand = false;
            } else if (globalCalculatorCurrentInput.indexOf('.') === -1) {
                globalCalculatorCurrentInput += '.';
            }
            globalUpdateCalculatorDisplay();
        }

        function globalAppendOperator(operator) {
            const inputValue = parseFloat(globalCalculatorCurrentInput);

            if (globalCalculatorPreviousInput === null) {
                globalCalculatorPreviousInput = inputValue;
            } else if (globalCalculatorOperator) {
                const result = globalPerformCalculation();
                globalCalculatorCurrentInput = String(result);
                globalCalculatorPreviousInput = result;
                globalUpdateCalculatorDisplay();
            }
            
            globalCalculatorWaitingForOperand = true;
            globalCalculatorOperator = operator;
        }

        function globalCalculate() {
            const inputValue = parseFloat(globalCalculatorCurrentInput);
            
            if (globalCalculatorPreviousInput === null || globalCalculatorOperator === null) {
                return;
            }
            
            const result = globalPerformCalculation();
            globalCalculatorCurrentInput = String(result);
            globalCalculatorPreviousInput = null;
            globalCalculatorOperator = null;
            globalCalculatorWaitingForOperand = true;
            globalUpdateCalculatorDisplay();
        }

        function globalPerformCalculation() {
            const prev = globalCalculatorPreviousInput;
            const current = parseFloat(globalCalculatorCurrentInput);
            
            switch (globalCalculatorOperator) {
                case '+': return prev + current;
                case '-': return prev - current;
                case '*': return prev * current;
                case '/': return current !== 0 ? prev / current : 0;
                default: return current;
            }
        }

        function globalClearAll() {
            globalCalculatorCurrentInput = '0';
            globalCalculatorPreviousInput = null;
            globalCalculatorOperator = null;
            globalCalculatorWaitingForOperand = false;
            globalUpdateCalculatorDisplay();
        }

        function globalClearEntry() {
            globalCalculatorCurrentInput = '0';
            globalUpdateCalculatorDisplay();
        }

        // Funzione percentuale professionale
        function globalAppendPercentage() {
            if (globalCalculatorPreviousInput !== null && globalCalculatorOperator !== null) {
                // Calcola percentuale del numero precedente (es: 100 + 20% = 100 + 20)
                const percentage = (globalCalculatorPreviousInput * parseFloat(globalCalculatorCurrentInput)) / 100;
                globalCalculatorCurrentInput = String(percentage);
                globalUpdateCalculatorDisplay();
            } else {
                // Converte il numero corrente in percentuale (es: 50% = 0.5)
                const percentage = parseFloat(globalCalculatorCurrentInput) / 100;
                globalCalculatorCurrentInput = String(percentage);
                globalUpdateCalculatorDisplay();
            }
        }

        // Shortcut da tastiera globali
        document.addEventListener('keydown', function(e) {
            // F9 per toggle calcolatrice globale
            if (e.key === 'F9') {
                e.preventDefault();
                const widget = document.getElementById('globalCalculatorWidget');
                widget.style.display = widget.style.display === 'block' ? 'none' : 'block';
            }
            
            // Controlli da tastiera quando calcolatrice globale è attiva
            if (document.getElementById('globalCalculatorWidget').style.display === 'block') {
                // Non intercettare se l'utente sta scrivendo nella chat
                const activeElement = document.activeElement;
                const isTypingInChat = activeElement && activeElement.id === 'calcChatInput';
                
                if (!isTypingInChat) {
                    if (e.key >= '0' && e.key <= '9') {
                        e.preventDefault();
                        globalAppendNumber(e.key);
                    } else if (e.key === '.') {
                        e.preventDefault();
                        globalAppendDecimal();
                    } else if (e.key === '+' || e.key === '-' || e.key === '*' || e.key === '/') {
                        e.preventDefault();
                        globalAppendOperator(e.key);
                    } else if (e.key === 'Enter' || e.key === '=') {
                        e.preventDefault();
                        globalCalculate();
                    } else if (e.key === 'Delete') {
                        e.preventDefault();
                        globalClearAll();
                    } else if (e.key === 'Backspace') {
                        e.preventDefault();
                        globalClearEntry();
                    }
                }
                
                // Escape chiude sempre la calcolatrice
                if (e.key === 'Escape') {
                    e.preventDefault();
                    document.getElementById('globalCalculatorWidget').style.display = 'none';
                }
            }
        });

        // Inizializza display dopo caricamento DOM
        document.addEventListener('DOMContentLoaded', function() {
            globalUpdateCalculatorDisplay();
            loadCalculatorChatHistory();
        });

        // === FUNZIONI CALCULATOR CHAT ===
        let calcChatHistory = [];

        function loadCalculatorChatHistory() {
            const saved = localStorage.getItem('calculator_chat_history');
            if (saved) {
                calcChatHistory = JSON.parse(saved);
                renderChatMessages();
            }
        }

        function saveChatHistory() {
            // Mantieni solo gli ultimi 10 messaggi per evitare troppo storage
            if (calcChatHistory.length > 10) {
                calcChatHistory = calcChatHistory.slice(-10);
            }
            localStorage.setItem('calculator_chat_history', JSON.stringify(calcChatHistory));
        }

        function addChatMessage(message, isUser = true, result = null) {
            const timestamp = new Date().toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });
            calcChatHistory.push({
                message: message,
                isUser: isUser,
                result: result,
                timestamp: timestamp
            });
            saveChatHistory();
            renderChatMessages();
        }

        function renderChatMessages() {
            const container = document.getElementById('calcChatMessages');
            
            if (calcChatHistory.length === 0) {
                container.innerHTML = `
                    <div class="welcome-message" style="font-size: 0.8rem; color: #6c757d; text-align: center; padding: 8px;">
                        💬 Chiedi calcoli, percentuali, IVA, sconti...
                    </div>
                `;
                return;
            }

            let html = '';
            calcChatHistory.forEach((chat, index) => {
                if (chat.isUser) {
                    html += `
                        <div style="margin-bottom: 8px; text-align: right;">
                            <div style="display: inline-block; background: #667eea; color: white; padding: 6px 12px; border-radius: 15px 15px 5px 15px; font-size: 0.8rem; max-width: 80%;">
                                ${chat.message}
                            </div>
                            <div style="font-size: 0.65rem; color: #adb5bd; margin-top: 2px;">${chat.timestamp}</div>
                        </div>
                    `;
                } else {
                    html += `
                        <div style="margin-bottom: 8px; text-align: left;">
                            <div style="display: inline-block; background: #f8f9fa; color: #333; padding: 6px 12px; border-radius: 15px 15px 15px 5px; font-size: 0.8rem; max-width: 80%; border: 1px solid #e9ecef;">
                                💡 ${chat.message}
                                ${chat.result ? `<div style="font-weight: bold; color: #28a745; margin-top: 4px;">${chat.result}</div>` : ''}
                            </div>
                            <div style="font-size: 0.65rem; color: #adb5bd; margin-top: 2px;">${chat.timestamp}</div>
                        </div>
                    `;
                }
            });
            container.innerHTML = html;
            
            // Scroll to bottom
            container.scrollTop = container.scrollHeight;
        }

        async function sendCalculatorMessage() {
            const input = document.getElementById('calcChatInput');
            const message = input.value.trim();
            
            if (!message) return;

            // Aggiungi messaggio utente
            addChatMessage(message, true);
            input.value = '';

            // Mostra indicatore di digitazione
            showTypingIndicator();

            try {
                // Invia richiesta al backend
                const response = await fetch('/api/calculator-chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        message: message
                    })
                });

                // Rimuovi indicatore di digitazione
                hideTypingIndicator();

                if (response.ok) {
                    const data = await response.json();
                    addChatMessage(data.explanation || 'Calcolo completato', false, data.result);
                    
                    // Se c'è un risultato numerico, aggiornalo anche nella calcolatrice principale
                    if (data.numeric_result !== undefined) {
                        globalCalculatorCurrentInput = String(data.numeric_result);
                        globalUpdateCalculatorDisplay();
                    }
                } else {
                    const errorData = await response.json();
                    addChatMessage(errorData.error || 'Errore nel calcolo. Riprova con una domanda più specifica.', false);
                }
            } catch (error) {
                // Rimuovi indicatore di digitazione
                hideTypingIndicator();
                console.error('Calculator chat error:', error);
                addChatMessage('Errore di connessione. Controlla la tua connessione internet e riprova.', false);
            }
        }

        function showTypingIndicator() {
            const container = document.getElementById('calcChatMessages');
            const indicator = document.createElement('div');
            indicator.id = 'typing-indicator';
            indicator.innerHTML = `
                <div style="margin-bottom: 8px; text-align: left;">
                    <div style="display: inline-block; background: #f8f9fa; color: #6c757d; padding: 8px 12px; border-radius: 15px 15px 15px 5px; font-size: 0.75rem; border: 1px solid #e9ecef; animation: pulse 1.5s infinite;">
                        💭 Sto calcolando<span class="dots">...</span>
                    </div>
                </div>
            `;
            container.appendChild(indicator);
            container.scrollTop = container.scrollHeight;
            
            // Animazione puntini
            let dotCount = 0;
            window.typingAnimation = setInterval(() => {
                const dotsElement = container.querySelector('.dots');
                if (dotsElement) {
                    dotCount = (dotCount + 1) % 4;
                    dotsElement.textContent = '.'.repeat(dotCount);
                }
            }, 500);
        }

        function hideTypingIndicator() {
            const indicator = document.getElementById('typing-indicator');
            if (indicator) {
                indicator.remove();
            }
            if (window.typingAnimation) {
                clearInterval(window.typingAnimation);
                window.typingAnimation = null;
            }
        }

        function clearCalculatorChat() {
            if (confirm('Vuoi cancellare tutta la cronologia delle domande?')) {
                calcChatHistory = [];
                localStorage.removeItem('calculator_chat_history');
                renderChatMessages();
            }
        }
    </script>
</body>
</html>