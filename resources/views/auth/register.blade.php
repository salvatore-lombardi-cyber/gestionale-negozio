<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('app.register') }} - Gestionale Negozio</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow: hidden;
        }
        
        /* Marquee identico alla home */
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
        
        /* Icone fluttuanti identiche alla home */
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
        
        .register-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 450px; /* Leggermente più largo per i campi aggiuntivi */
            width: 100%;
            margin: 20px;
            position: relative;
            z-index: 2;
        }
        
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        /* Brand title identico alla home */
        .brand-title {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #ffffff, #e3f2fd);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .brand-subtitle {
            margin: 0.5rem 0 0 0;
            opacity: 0.8;
            font-size: 0.9rem;
            font-weight: 300;
        }
        
        .register-body {
            padding: 2rem;
            max-height: 60vh; /* Limita altezza per evitare scroll eccessivo */
            overflow-y: auto; /* Scroll interno se necessario */
        }
        
        .form-floating {
            margin-bottom: 1rem;
        }
        
        .form-floating input {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .form-floating input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .form-floating input.is-invalid {
            border-color: #dc3545;
        }
        
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .login-link {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .login-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }
        
        .language-selector {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
        }
        
        .language-selector .dropdown-toggle {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 25px;
            padding: 8px 15px;
            font-size: 0.9rem;
        }
        
        .language-selector .dropdown-toggle:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.4);
        }
        
        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .password-strength {
            font-size: 0.8rem;
            margin-top: 0.5rem;
            padding: 0.5rem;
            border-radius: 8px;
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }
        
        .password-strength ul {
            margin: 0;
            padding-left: 1rem;
        }
        
        .password-strength li {
            margin-bottom: 0.25rem;
        }
        
        .form-row {
            display: flex;
            gap: 1rem;
        }
        
        .form-row .form-floating {
            flex: 1;
        }
        
        @media (max-width: 576px) {
            .register-container {
                margin: 10px;
                border-radius: 15px;
                max-width: none;
            }
            
            .register-header {
                padding: 1.5rem;
            }
            
            .brand-title {
                font-size: 1.5rem;
            }
            
            .register-body {
                padding: 1.5rem;
                max-height: 70vh;
            }
            
            .language-selector {
                top: 15px;
                right: 15px;
            }
            
            .marquee-content {
                font-size: 6rem;
            }
            
            .floating-icon {
                font-size: 1.5rem;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
        
        /* Scrollbar personalizzata */
        .register-body::-webkit-scrollbar {
            width: 6px;
        }
        
        .register-body::-webkit-scrollbar-track {
            background: rgba(102, 126, 234, 0.1);
            border-radius: 3px;
        }
        
        .register-body::-webkit-scrollbar-thumb {
            background: rgba(102, 126, 234, 0.3);
            border-radius: 3px;
        }
        
        .register-body::-webkit-scrollbar-thumb:hover {
            background: rgba(102, 126, 234, 0.5);
        }
    </style>
</head>
<body>
    <!-- Icone fluttuanti identiche alla home -->
    <div class="floating-icons">
        <i class="bi bi-handbag floating-icon"></i>
        <i class="bi bi-suit-heart floating-icon"></i>
        <i class="bi bi-eyeglasses floating-icon"></i>
        <i class="bi bi-watch floating-icon"></i>
        <i class="bi bi-gem floating-icon"></i>
        <i class="bi bi-award floating-icon"></i>
    </div>
    
    <!-- Marquee di abbigliamento identico alla home -->
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

    <!-- Language Selector -->
    <div class="language-selector">
        <div class="dropdown">
            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-globe"></i> {{ app()->getLocale() == 'it' ? 'Italiano' : 'English' }}
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('language.change', ['locale' => 'it']) }}">
                    <i class="bi bi-globe"></i> Italiano
                </a></li>
                <li><a class="dropdown-item" href="{{ route('language.change', ['locale' => 'en']) }}">
                    <i class="bi bi-globe"></i> English
                </a></li>
            </ul>
        </div>
    </div>

    <div class="register-container">
        <!-- Header -->
        <div class="register-header">
            <h1 class="brand-title">
                <i class="bi bi-person-plus"></i>
                Registrazione
            </h1>
            <p class="brand-subtitle">Crea il tuo account per il gestionale</p>
        </div>
        
        <!-- Body -->
        <div class="register-body">
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <!-- Name -->
                <div class="form-floating">
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           autofocus 
                           autocomplete="name"
                           placeholder="Nome completo">
                    <label for="name">
                        <i class="bi bi-person"></i> Nome completo
                    </label>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="form-floating">
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autocomplete="username"
                           placeholder="Email">
                    <label for="email">
                        <i class="bi bi-envelope"></i> Indirizzo Email
                    </label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Row -->
                <div class="form-row">
                    <!-- Password -->
                    <div class="form-floating">
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required 
                               autocomplete="new-password"
                               placeholder="Password"
                               minlength="8">
                        <label for="password">
                            <i class="bi bi-lock"></i> Password
                        </label>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-floating">
                        <input type="password" 
                               class="form-control @error('password_confirmation') is-invalid @enderror" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               required 
                               autocomplete="new-password"
                               placeholder="Conferma Password">
                        <label for="password_confirmation">
                            <i class="bi bi-lock-fill"></i> Conferma
                        </label>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Password Strength Indicator -->
                <div class="password-strength" id="passwordStrength" style="display: none;">
                    <strong>Requisiti password:</strong>
                    <ul>
                        <li id="length">Almeno 8 caratteri</li>
                        <li id="lowercase">Una lettera minuscola</li>
                        <li id="uppercase">Una lettera maiuscola</li>
                        <li id="number">Un numero</li>
                    </ul>
                </div>

                <!-- Privacy Agreement -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="privacy" name="privacy" required>
                    <label class="form-check-label" for="privacy">
                        Accetto i <a href="#" class="login-link">termini di servizio</a> e la <a href="#" class="login-link">privacy policy</a>
                    </label>
                </div>

                <!-- Register Button -->
                <div class="d-grid gap-2 mb-3">
                    <button type="submit" class="btn btn-primary btn-register">
                        <i class="bi bi-person-plus-fill me-2"></i>Crea Account
                    </button>
                </div>

                <!-- Login Link -->
                <div class="text-center">
                    <span class="text-muted">Hai già un account?</span>
                    <a class="login-link ms-1" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right"></i> Accedi qui
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const strengthIndicator = document.getElementById('passwordStrength');
            const registerForm = document.getElementById('registerForm');
            
            // Password strength checker
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                
                if (password.length > 0) {
                    strengthIndicator.style.display = 'block';
                    
                    // Check length
                    const lengthCheck = document.getElementById('length');
                    if (password.length >= 8) {
                        lengthCheck.style.color = '#28a745';
                        lengthCheck.innerHTML = '✓ Almeno 8 caratteri';
                    } else {
                        lengthCheck.style.color = '#dc3545';
                        lengthCheck.innerHTML = '✗ Almeno 8 caratteri';
                    }
                    
                    // Check lowercase
                    const lowercaseCheck = document.getElementById('lowercase');
                    if (/[a-z]/.test(password)) {
                        lowercaseCheck.style.color = '#28a745';
                        lowercaseCheck.innerHTML = '✓ Una lettera minuscola';
                    } else {
                        lowercaseCheck.style.color = '#dc3545';
                        lowercaseCheck.innerHTML = '✗ Una lettera minuscola';
                    }
                    
                    // Check uppercase
                    const uppercaseCheck = document.getElementById('uppercase');
                    if (/[A-Z]/.test(password)) {
                        uppercaseCheck.style.color = '#28a745';
                        uppercaseCheck.innerHTML = '✓ Una lettera maiuscola';
                    } else {
                        uppercaseCheck.style.color = '#dc3545';
                        uppercaseCheck.innerHTML = '✗ Una lettera maiuscola';
                    }
                    
                    // Check number
                    const numberCheck = document.getElementById('number');
                    if (/[0-9]/.test(password)) {
                        numberCheck.style.color = '#28a745';
                        numberCheck.innerHTML = '✓ Un numero';
                    } else {
                        numberCheck.style.color = '#dc3545';
                        numberCheck.innerHTML = '✗ Un numero';
                    }
                } else {
                    strengthIndicator.style.display = 'none';
                }
            });
            
            // Password confirmation checker
            confirmPasswordInput.addEventListener('input', function() {
                const password = passwordInput.value;
                const confirmPassword = this.value;
                
                if (confirmPassword.length > 0) {
                    if (password === confirmPassword) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else {
                        this.classList.remove('is-valid');
                        this.classList.add('is-invalid');
                    }
                }
            });
            
            // Form animation on submit
            registerForm.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Creando account...';
                submitBtn.disabled = true;
            });
            
            // Input animations
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>