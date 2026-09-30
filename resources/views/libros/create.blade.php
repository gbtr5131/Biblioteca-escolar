@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 animate-fade-up">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0 text-white">📦 Registro de Libros</h4>
                </div>
                <div class="card-body p-4">
                    {{-- Mostrar errores generales (opcional) --}}
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

                    <form action="{{ route('libros.store') }}" method="POST" id="form-libro" novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Título</label>
                                <input type="text" name="titulo" 
                                       class="form-control @error('titulo') is-invalid @enderror" 
                                       value="{{ old('titulo') }}" required>
                                @error('titulo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Autor</label>
                                <input type="text" name="autor" 
                                       class="form-control @error('autor') is-invalid @enderror" 
                                       value="{{ old('autor') }}" required>
                                @error('autor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">ISBN</label>
                                <input type="text" name="isbn" 
                                       class="form-control @error('isbn') is-invalid @enderror" 
                                       value="{{ old('isbn') }}" placeholder="Ej: 978...">
                                @error('isbn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Categoría (Sistema Dewey)</label>
                                <select name="categoria" class="form-select @error('categoria') is-invalid @enderror" required>
                                    <option value="" selected disabled>Seleccione una categoría...</option>
                                    <option value="Obras Generales" {{ old('categoria') == 'Obras Generales' ? 'selected' : '' }}>000 - Obras Generales</option>
                                    <option value="Filosofía y Psicología" {{ old('categoria') == 'Filosofía y Psicología' ? 'selected' : '' }}>100 - Filosofía y Psicología</option>
                                    <option value="Religión" {{ old('categoria') == 'Religión' ? 'selected' : '' }}>200 - Religión</option>
                                    <option value="Ciencias Sociales" {{ old('categoria') == 'Ciencias Sociales' ? 'selected' : '' }}>300 - Ciencias Sociales</option>
                                    <option value="Lenguaje" {{ old('categoria') == 'Lenguaje' ? 'selected' : '' }}>400 - Lenguaje</option>
                                    <option value="Ciencias Puras" {{ old('categoria') == 'Ciencias Puras' ? 'selected' : '' }}>500 - Ciencias Puras</option>
                                    <option value="Ciencias Aplicadas y Artes Útiles" {{ old('categoria') == 'Ciencias Aplicadas y Artes Útiles' ? 'selected' : '' }}>600 - Ciencias Aplicadas y Artes Útiles</option>
                                    <option value="Arte y Recreación" {{ old('categoria') == 'Arte y Recreación' ? 'selected' : '' }}>700 - Arte y Recreación</option>
                                    <option value="Literatura" {{ old('categoria') == 'Literatura' ? 'selected' : '' }}>800 - Literatura</option>
                                    <option value="Historia, Geografía y Biografía" {{ old('categoria') == 'Historia, Geografía y Biografía' ? 'selected' : '' }}>900 - Historia, Geografía y Biografía</option>
                                </select>
                                @error('categoria')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Cantidad de Ejemplares (Stock)</label>
                            <input type="number" name="stock_total" 
                                   class="form-control @error('stock_total') is-invalid @enderror" 
                                   value="{{ old('stock_total', 1) }}" min="1" required>
                            @error('stock_total')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Observaciones</label>
                            <textarea name="observaciones" class="form-control @error('observaciones') is-invalid @enderror" rows="2">{{ old('observaciones') }}</textarea>
                            @error('observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-pill" id="btn-libro">
                                <span id="text-libro">Guardar Libro en Sistema</span>
                                <span id="spinner-libro" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Validación cliente (similar al de estudiantes)
    document.getElementById('form-libro').addEventListener('submit', function(e) {
        let isValid = true;
        const requiredInputs = this.querySelectorAll('[required]');
        requiredInputs.forEach(input => {
            input.classList.remove('is-invalid');
            if (input.value.trim() === "") {
                input.classList.add('is-invalid');
                isValid = false;
            }
        });

        if (isValid) {
            const btn = document.getElementById('btn-libro');
            const text = document.getElementById('text-libro');
            const spinner = document.getElementById('spinner-libro');
            btn.disabled = true;
            text.innerText = "Guardando...";
            spinner.classList.remove('d-none');
        } else {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Campos incompletos',
                text: 'Por favor, completa todos los campos obligatorios.',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
</script>
@endsection