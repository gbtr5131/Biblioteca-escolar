@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 animate-fade-up">
            <div class="card shadow border-0">
                {{-- HEADER CORREGIDO: fondo azul y texto blanco --}}
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0 fw-bold">✏️ Editar Estudiante</h4>
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

                    <form action="{{ route('estudiantes.update', $estudiante->id) }}" method="POST" id="editFormEstudiante" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nombre Completo</label>
                            <input type="text" name="nombre_completo" 
                                   class="form-control @error('nombre_completo') is-invalid @enderror" 
                                   value="{{ old('nombre_completo', $estudiante->nombre_completo) }}" required>
                            @error('nombre_completo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Cédula / ID</label>
                            <input type="text" name="cedula" 
                                   class="form-control @error('cedula') is-invalid @enderror" 
                                   value="{{ old('cedula', $estudiante->cedula) }}" required>
                            @error('cedula')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Grado y Sección</label>
                            <input type="text" name="grado" 
                                   class="form-control @error('grado') is-invalid @enderror" 
                                   value="{{ old('grado', $estudiante->grado) }}" required>
                            @error('grado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Docente Guía</label>
                            <input type="text" name="docente_guia" 
                                   class="form-control @error('docente_guia') is-invalid @enderror" 
                                   value="{{ old('docente_guia', $estudiante->docente_guia) }}" required>
                            @error('docente_guia')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('estudiantes.index') }}" class="btn btn-light border rounded-pill px-4">Cancelar</a>
                            <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4" id="btn-submit">Actualizar Datos</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('editFormEstudiante').addEventListener('submit', function(e) {
        let isValid = true;
        const requiredInputs = this.querySelectorAll('[required]');
        requiredInputs.forEach(input => {
            input.classList.remove('is-invalid');
            if (input.value.trim() === "") {
                input.classList.add('is-invalid');
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Campos incompletos',
                text: 'Por favor, completa todos los campos obligatorios.',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            const btn = document.getElementById('btn-submit');
            btn.disabled = true;
            btn.innerText = "Actualizando...";
        }
    });
</script>
@endsection