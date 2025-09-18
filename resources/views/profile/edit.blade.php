@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        min-height: 100vh;
    }
    
    .management-container {
        padding: 2rem;
        min-height: calc(100vh - 70px);
    }
    
    /* Header con pulsanti */
    .management-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .management-title {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin: 0;
        display: flex;
        align-items: center;
    }
    
    /* Pulsanti modern-btn coerenti */
    .modern-btn {
        padding: 12px 24px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .modern-btn:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(2, 157, 126, 0.3);
    }
    
    .modern-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    
    .modern-btn.btn-secondary,
    .btn-secondary.modern-btn {
        background: linear-gradient(135deg, #6c757d, #545b62);
        color: white;
        border: none;
    }
    
    .modern-btn.btn-primary,
    .btn-primary.modern-btn {
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        color: white;
        border: none;
    }
    
    .modern-btn.btn-warning,
    .btn-warning.modern-btn {
        background: linear-gradient(135deg, #ffd60a, #ff8500);
        color: white;
        border: none;
    }
    
    
    /* Card moderne */
    .modern-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 2rem;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    /* Assicura che le colonne abbiano la stessa altezza */
    .profile-row {
        display: flex;
        align-items: stretch;
    }
    
    .profile-row > [class*="col-"] {
        display: flex;
        flex-direction: column;
    }
    
    .modern-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
    }
    
    /* Avatar upload */
    .avatar-container {
        position: relative;
        display: inline-block;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .avatar-container:hover {
        transform: scale(1.05);
    }
    
    .avatar-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #029D7E;
        background: linear-gradient(135deg, #029D7E, #4DC9A5);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: white;
        margin: 0 auto;
        transition: all 0.3s ease;
    }
    
    .avatar-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(2, 157, 126, 0.8);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        cursor: pointer;
    }
    
    .avatar-container:hover .avatar-overlay {
        opacity: 1;
    }
    
    .avatar-input {
        display: none;
    }
    
    /* Form styling */
    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    
    .form-control {
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        padding: 12px 16px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #029D7E;
        box-shadow: 0 0 0 3px rgba(2, 157, 126, 0.1);
    }
    
    /* Password toggle styling */
    .password-field {
        position: relative;
    }
    
    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6c757d;
        font-size: 1.1rem;
        z-index: 10;
        padding: 5px;
        transition: color 0.3s ease;
    }
    
    .password-toggle:hover {
        color: #029D7E;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .management-container {
            padding: 1rem;
        }
        
        .management-header {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .management-title {
            font-size: 1.8rem;
        }
        
        .modern-btn {
            padding: 10px 16px;
            font-size: 0.9rem;
        }
    }
</style>

<div class="management-container">
    <!-- Header -->
    <div class="management-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center">
                <h1 class="management-title">
                    <i class="bi bi-pencil me-3" style="color: #029D7E; font-size: 2rem;"></i>
                    Modifica Profilo
                </h1>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('profile.show') }}" class="btn btn-secondary modern-btn">
                    <i class="bi bi-arrow-left"></i> Torna al Profilo
                </a>
            </div>
        </div>
        
        <p class="mt-3 mb-0 text-muted">
            Aggiorna le tue informazioni personali e la foto profilo
        </p>
    </div>

    <!-- Contenuto Principale -->
    <div class="row profile-row">
        <!-- Colonna Avatar -->
        <div class="col-lg-4">
            <div class="modern-card">
                <div class="p-4 text-center">
                    <div class="d-flex align-items-center mb-4">
                        <i class="bi bi-camera me-2" style="color: #029D7E; font-size: 1.5rem;"></i>
                        <h5 class="mb-0" style="color: #2d3748; font-weight: 700;">Foto Profilo</h5>
                    </div>
                    
                    <div class="avatar-container mb-4" onclick="document.getElementById('avatar-input').click();">
                        @if(Auth::user()->avatar)
                            <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="Avatar" class="avatar-preview" id="avatar-preview">
                        @else
                            <div class="avatar-preview" id="avatar-preview">
                                <i class="bi bi-person-circle"></i>
                            </div>
                        @endif
                        
                        <div class="avatar-overlay">
                            <i class="bi bi-camera" style="color: white; font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    
                    <p class="text-muted small mb-3">
                        Clicca sulla foto per cambiarla<br>
                        <small>Formati supportati: JPG, PNG (max 2MB)</small>
                    </p>
                    
                    <input type="file" id="avatar-input" name="avatar" accept="image/*" class="avatar-input">
                </div>
            </div>
        </div>
        
        <!-- Colonna Form -->
        <div class="col-lg-8">
            <div class="modern-card">
                <div class="p-4">
                    <div class="d-flex align-items-center mb-4">
                        <i class="bi bi-person-gear me-2" style="color: #029D7E; font-size: 1.5rem;"></i>
                        <h5 class="mb-0" style="color: #2d3748; font-weight: 700;">Informazioni Personali</h5>
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; border: none; background: rgba(40, 167, 69, 0.1); color: #155724;">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profile-form">
                        @csrf
                        @method('PATCH')
                        
                        <!-- File input per avatar (nascosto, gestito da JavaScript) -->
                        <input type="file" name="avatar" id="avatar-form-input" style="display: none;" accept="image/*">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Nome Completo *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           name="name" value="{{ old('name', Auth::user()->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email', Auth::user()->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Ruolo</label>
                                    <input type="text" class="form-control" value="{{ ucfirst(Auth::user()->role) }}" readonly 
                                           style="background-color: #f8f9fa; cursor: not-allowed;">
                                    <div class="form-text">Il ruolo può essere modificato solo dall'amministratore</div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label">Data Registrazione</label>
                                    <input type="text" class="form-control" value="{{ Auth::user()->created_at->format('d/m/Y H:i') }}" readonly 
                                           style="background-color: #f8f9fa; cursor: not-allowed;">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <button type="button" class="btn btn-secondary modern-btn" onclick="location.reload();">
                                <i class="bi bi-arrow-clockwise"></i> Annulla
                            </button>
                            <button type="submit" class="btn btn-primary modern-btn">
                                <i class="bi bi-check-lg"></i> Salva Modifiche
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sezione Cambio Password -->
    <div class="row">
        <div class="col-12">
            <div class="modern-card">
                <div class="p-4">
                    <div class="d-flex align-items-center mb-4">
                        <i class="bi bi-shield-lock me-2" style="color: #029D7E; font-size: 1.5rem;"></i>
                        <h5 class="mb-0" style="color: #2d3748; font-weight: 700;">Sicurezza Account</h5>
                    </div>
                    
                    <form method="POST" action="{{ route('profile.password.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label">Password Attuale *</label>
                                    <div class="password-field">
                                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                               name="current_password" id="current_password" required>
                                        <i class="bi bi-eye password-toggle" onclick="togglePassword('current_password')" id="toggle_current_password"></i>
                                    </div>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label">Nuova Password *</label>
                                    <div class="password-field">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               name="password" id="new_password" required>
                                        <i class="bi bi-eye password-toggle" onclick="togglePassword('new_password')" id="toggle_new_password"></i>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Minimo 8 caratteri</div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-4">
                                    <label class="form-label">Conferma Password *</label>
                                    <div class="password-field">
                                        <input type="password" class="form-control" 
                                               name="password_confirmation" id="confirm_password" required>
                                        <i class="bi bi-eye password-toggle" onclick="togglePassword('confirm_password')" id="toggle_confirm_password"></i>
                                    </div>
                                    <div class="form-text">Ripeti la nuova password</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <div>
                                <a href="{{ route('password.request') }}" class="btn btn-outline-secondary modern-btn">
                                    <i class="bi bi-envelope"></i> Reset via Email
                                </a>
                            </div>
                            <button type="submit" class="btn btn-warning modern-btn">
                                <i class="bi bi-key"></i> Cambia Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Gestione upload avatar
document.getElementById('avatar-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    
    // Controllo dimensione file (max 2MB)
    if (file.size > 2 * 1024 * 1024) {
        alert('Il file è troppo grande. Massimo 2MB consentiti.');
        e.target.value = '';
        return;
    }
    
    // Controllo tipo file
    if (!file.type.startsWith('image/')) {
        alert('Seleziona un file immagine valido (JPG, PNG, GIF).');
        e.target.value = '';
        return;
    }
    
    // Anteprima immagine
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('avatar-preview');
        preview.innerHTML = '';
        
        const img = document.createElement('img');
        img.src = e.target.result;
        img.className = 'avatar-preview';
        img.style.objectFit = 'cover';
        
        preview.appendChild(img);
        
        // Copia il file nel form input nascosto
        const formInput = document.getElementById('avatar-form-input');
        formInput.files = e.target.files;
    };
    reader.readAsDataURL(file);
    
    // Copia il file nel form input nascosto
    const formInput = document.getElementById('avatar-form-input');
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    formInput.files = dataTransfer.files;
});

// Gestione submit automatico per avatar upload separato
document.getElementById('avatar-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    
    // Mostra indicatore di caricamento
    const overlay = document.querySelector('.avatar-overlay');
    overlay.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div>';
    overlay.style.opacity = '1';
    
    // Submit automatico del form per avatar
    setTimeout(() => {
        const form = document.getElementById('profile-form');
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                overlay.innerHTML = '<i class="bi bi-check-lg" style="color: white; font-size: 1.5rem;"></i>';
                setTimeout(() => {
                    overlay.innerHTML = '<i class="bi bi-camera" style="color: white; font-size: 1.5rem;"></i>';
                    overlay.style.opacity = '0';
                    location.reload(); // Refresh per mostrare la nuova immagine
                }, 1000);
            } else {
                overlay.innerHTML = '<i class="bi bi-x-lg" style="color: white; font-size: 1.5rem;"></i>';
                setTimeout(() => {
                    overlay.innerHTML = '<i class="bi bi-camera" style="color: white; font-size: 1.5rem;"></i>';
                    overlay.style.opacity = '0';
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            overlay.innerHTML = '<i class="bi bi-x-lg" style="color: white; font-size: 1.5rem;"></i>';
            setTimeout(() => {
                overlay.innerHTML = '<i class="bi bi-camera" style="color: white; font-size: 1.5rem;"></i>';
                overlay.style.opacity = '0';
            }, 2000);
        });
    }, 500);
});

// Funzione per mostrare/nascondere password
function togglePassword(fieldId) {
    const passwordField = document.getElementById(fieldId);
    const toggleIcon = document.getElementById('toggle_' + fieldId);
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
    }
}
</script>

@endsection