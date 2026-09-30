@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 animate-fade-up">
        <div class="card shadow border-0">
            <div class="card-header bg-success text-white py-3">
                <h5 class="mb-0 text-white">Registrar Nuevo Estudiante</h5>
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Errores:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('estudiantes.store') }}" method="POST" id="form-estudiante" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre Completo</label>
                        <input type="text" name="nombre_completo" class="form-control" value="{{ old('nombre_completo') }}" placeholder="Ej: Juan Pérez" required>
                        <div class="text-danger small fw-bold mt-1 error-msg" style="display:none;">Este campo es obligatorio</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Cédula</label>
                        <input type="text" name="cedula" class="form-control" value="{{ old('cedula') }}" placeholder="Ej: 12345678" required>
                        <div class="text-danger small fw-bold mt-1 error-msg" style="display:none;">Este campo es obligatorio</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Grado y Sección</label>
                        <input type="text" name="grado" class="form-control" value="{{ old('grado') }}" placeholder="Ej: 5to Grado B" required>
                        <div class="text-danger small fw-bold mt-1 error-msg" style="display:none;">Este campo es obligatorio</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Docente Guía</label>
                        <input type="text" name="docente_guia" class="form-control" value="{{ old('docente_guia') }}" placeholder="Nombre del profesor" required>
                        <div class="text-danger small fw-bold mt-1 error-msg" style="display:none;">El nombre del docente es obligatorio</div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg fw-bold rounded-pill" id="btn-estudiante">
                            <span id="text-estudiante">Guardar Datos</span>
                            <span id="spinner-estudiante" class="spinner-border spinner-border-sm d-none"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('form-estudiante').addEventListener('submit', function(e) {
        let isValid = true;
        const requiredInputs = this.querySelectorAll('[required]');
        requiredInputs.forEach(input => {
            const errorMsg = input.parentElement.querySelector('.error-msg');
            if (input.value.trim() === "") {
                input.style.borderColor = "#dc3545";
                if (errorMsg) errorMsg.style.display = "block";
                isValid = false;
            } else {
                input.style.borderColor = "";
                if (errorMsg) errorMsg.style.display = "none";
            }
        });
        if (isValid) {
            const btn = document.getElementById('btn-estudiante');
            btn.disabled = true;
            document.getElementById('text-estudiante').innerText = "Registrando...";
            document.getElementById('spinner-estudiante').classList.remove('d-none');
        } else {
            e.preventDefault();
        }
    });
</script>
@endsection