@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5 animate-fade-up">
        <div class="card shadow-lg border-0 mt-5" style="background: #1e293b; border-radius: 20px; overflow: hidden;">
            <div class="card-header bg-transparent text-white text-center fw-bold py-4 border-0">
                <i class="bi bi-lock-fill me-2 fs-3"></i>
                <h4 class="mb-0">INGRESO AL SISTEMA</h4>
                
            </div>
            <div class="card-body p-4">
                <form action="{{ route('login') }}" method="POST" id="loginForm" novalidate>
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold text-light">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-light"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" id="email" class="form-control" placeholder="biblioteca@colegio.edu" required style="color: white; background-color: #0f172a;">
                        </div>
                        <div class="text-danger small fw-bold mt-1" id="error-email" style="display:none;">Ingrese un correo válido</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-light">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-secondary text-light"><i class="bi bi-key"></i></span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required style="color: white; background-color: #0f172a;">
                            <button class="btn btn-outline-secondary border-secondary" type="button" id="togglePassword" style="background-color: #0f172a; color: #cbd5e1;">
                                <i class="bi bi-eye-slash" id="toggleIcon"></i>
                            </button>
                        </div>
                        <div class="text-danger small fw-bold mt-1" id="error-password" style="display:none;">La contraseña es obligatoria</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-pill shadow-sm" id="btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>ENTRAR
                    </button>
                    @if($errors->any())
                        <div class="alert alert-danger mt-3 py-2 small text-center rounded-pill">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
                        </div>
                    @endif
                </form>
            </div>
            <div class="card-footer bg-transparent border-0 text-center pb-4">
                <small class="text-muted">Sistema de Gestión Bibliotecaria © {{ date('Y') }}</small>
            </div>
        </div>
    </div>
</div>

<script>
    // Mostrar/ocultar contraseña
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        toggleIcon.classList.toggle('bi-eye');
        toggleIcon.classList.toggle('bi-eye-slash');
    });

    // Validación del formulario (original)
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        let isValid = true;
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        
        if (email.value.trim() === "" || !email.value.includes('@')) {
            email.style.borderColor = "#dc3545";
            document.getElementById('error-email').style.display = "block";
            isValid = false;
        } else {
            email.style.borderColor = "";
            document.getElementById('error-email').style.display = "none";
        }

        if (password.value.trim() === "") {
            password.style.borderColor = "#dc3545";
            document.getElementById('error-password').style.display = "block";
            isValid = false;
        } else {
            password.style.borderColor = "";
            document.getElementById('error-password').style.display = "none";
        }

        if (!isValid) {
            e.preventDefault();
        } else {
            const btn = document.getElementById('btn-login');
            btn.innerText = "Validando...";
            btn.disabled = true;
        }
    });
</script>
@endsection