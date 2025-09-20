<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('app.login') }} - Gestionale Negozio</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow: hidden;
        }
        
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
            margin: 20px;
            position: relative;
            z-index: 2;
        }
        
        .login-header {
            background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
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
        
        .login-body {
            padding: 2rem;
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
            border-color: #029D7E;
            box-shadow: 0 0 0 0.2rem rgba(2, 157, 126, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(2, 157, 126, 0.3);
        }
        
        .form-check-input:checked {
            background-color: #029D7E;
            border-color: #029D7E;
        }
        
        .forgot-password {
            color: #029D7E;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .forgot-password:hover {
            color: #029D7E;
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
        
        .alert {
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 576px) {
            .login-container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .login-header {
                padding: 1.5rem;
            }
            
            .brand-title {
                font-size: 1.5rem;
            }
            
            .login-body {
                padding: 1.5rem;
            }
            
            .language-selector {
                top: 15px;
                right: 15px;
            }
            
        }
    </style>
</head>
<body>

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

    <div class="login-container">
        <!-- Header -->
        <div class="login-header">
            <h1 class="brand-title">
                <i class="bi bi-shop"></i>
                Gestionale Negozio
            </h1>
            <p class="brand-subtitle">Sistema di gestione abbigliamento</p>
        </div>
        
        <!-- Body -->
        <div class="login-body">
            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="form-floating">
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus 
                           autocomplete="username"
                           placeholder="Email">
                    <label for="email">Email</label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-floating">
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           required 
                           autocomplete="current-password"
                           placeholder="Password">
                    <label for="password">Password</label>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                    <label class="form-check-label" for="remember_me">
                        Ricordami
                    </label>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Accedi
                    </button>
                </div>

                @if (Route::has('password.request'))
                    <div class="text-center mt-3">
                        <a class="forgot-password" href="{{ route('password.request') }}">
                            Hai dimenticato la password?
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>