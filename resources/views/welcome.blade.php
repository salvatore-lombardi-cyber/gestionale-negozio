<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestionale Negozio Abbigliamento</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        
        .fashion-marquee {
            position: absolute;
            top: 50%;
            left: 0;
            width: 200%;
            transform: translateY(-50%);
            opacity: 0.1;
            pointer-events: none;
        }
        
        .marquee-content {
            display: flex;
            animation: scroll 30s linear infinite;
            font-size: 8rem;
            white-space: nowrap;
        }
        
        .marquee-content i {
            margin: 0 3rem;
            color: white;
        }
        
        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .brand-title {
            font-size: 3.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }
        
        .subtitle {
            font-size: 1.4rem;
            color: #6c757d;
            margin-bottom: 2rem;
        }
        
        .auth-buttons .btn {
            border-radius: 50px;
            padding: 1rem 2.5rem;
            font-weight: 600;
            font-size: 1.1rem;
            margin: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: white;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-register {
            background: transparent;
            border: 2px solid #667eea;
            color: #667eea;
        }
        
        .btn-register:hover {
            background: #667eea;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 3rem;
        }
        
        .feature-card {
            text-align: center;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
        
        .floating-icons {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }
        
        .floating-icon {
            position: absolute;
            color: rgba(255, 255, 255, 0.1);
            font-size: 2rem;
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-icon:nth-child(1) { top: 20%; left: 10%; animation-delay: 0s; }
        .floating-icon:nth-child(2) { top: 60%; left: 80%; animation-delay: 1s; }
        .floating-icon:nth-child(3) { top: 30%; left: 70%; animation-delay: 2s; }
        .floating-icon:nth-child(4) { top: 80%; left: 20%; animation-delay: 3s; }
        .floating-icon:nth-child(5) { top: 10%; left: 60%; animation-delay: 4s; }
        .floating-icon:nth-child(6) { top: 70%; left: 40%; animation-delay: 5s; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
    </style>
</head>
<body>
    <div class="hero-section">
        <!-- Icone fluttuanti -->
        <div class="floating-icons">
            <i class="bi bi-handbag floating-icon"></i>
            <i class="bi bi-suit-heart floating-icon"></i>
            <i class="bi bi-eyeglasses floating-icon"></i>
            <i class="bi bi-watch floating-icon"></i>
            <i class="bi bi-gem floating-icon"></i>
            <i class="bi bi-award floating-icon"></i>
        </div>
        
        <!-- Marquee di abbigliamento -->
        <div class="fashion-marquee">
            <div class="marquee-content">
                <i class="bi bi-handbag"></i>
                <i class="bi bi-suit-heart"></i>
                <i class="bi bi-eyeglasses"></i>
                <i class="bi bi-watch"></i>
                <i class="bi bi-gem"></i>
                <i class="bi bi-award"></i>
                <i class="bi bi-suit-spade"></i>
                <i class="bi bi-suit-club"></i>
                <i class="bi bi-handbag"></i>
                <i class="bi bi-suit-heart"></i>
                <i class="bi bi-eyeglasses"></i>
                <i class="bi bi-watch"></i>
                <i class="bi bi-gem"></i>
                <i class="bi bi-award"></i>
            </div>
        </div>
        
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="hero-content text-center">
                        <h1 class="brand-title">
                            <i class="bi bi-shop"></i>
                            Gestionale Negozio
                        </h1>
                        <p class="subtitle">
                            Il sistema completo per la gestione del tuo negozio di abbigliamento
                        </p>
                        
                        @if (Route::has('login'))
                            <div class="auth-buttons">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="btn btn-login">
                                        <i class="bi bi-speedometer2"></i> Vai alla Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-login">
                                        <i class="bi bi-box-arrow-in-right"></i> Accedi
                                    </a>
                                    
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="btn btn-register">
                                            <i class="bi bi-person-plus"></i> Registrati
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                        
                        <div class="features">
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                                <h5>Gestione Prodotti</h5>
                                <p class="text-muted">Catalogo completo con varianti</p>
                            </div>
                            
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="bi bi-people"></i>
                                </div>
                                <h5>Clienti</h5>
                                <p class="text-muted">Anagrafica e storico acquisti</p>
                            </div>
                            
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="bi bi-cart-check"></i>
                                </div>
                                <h5>Vendite</h5>
                                <p class="text-muted">Gestione ordini e sconti</p>
                            </div>
                            
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="bi bi-archive"></i>
                                </div>
                                <h5>Magazzino</h5>
                                <p class="text-muted">Controllo scorte in tempo reale</p>
                            </div>
                            
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <h5>DDT</h5>
                                <p class="text-muted">Documenti di trasporto automatici</p>
                            </div>
                            
                            <div class="feature-card">
                                <div class="feature-icon">
                                    <i class="bi bi-graph-up"></i>
                                </div>
                                <h5>Analytics</h5>
                                <p class="text-muted">Statistiche e reportistica</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>