@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 animate-fade-up">
            <div class="card shadow border-0">
                {{-- HEADER CORREGIDO: fondo azul y texto blanco --}}
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0 fw-bold">✏️ Editar Libro: {{ $libro->titulo }}</h4>
                </div>
                <div class="card-body p-4">
                    {{-- Mostrar errores generales si los hay --}}
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

                    <form action="{{ route('libros.update', $libro->id) }}" method="POST" id="editForm" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Título del Libro</label>
                                <input type="text" name="titulo" 
                                       class="form-control @error('titulo') is-invalid @enderror" 
                                       value="{{ old('titulo', $libro->titulo) }}" required>
                                @error('titulo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Autor</label>
                                <input type="text" name="autor" 
                                       class="form-control @error('autor') is-invalid @enderror" 
                                       value="{{ old('autor', $libro->autor) }}" required>
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
                                       value="{{ old('isbn', $libro->isbn) }}" required>
                                @error('isbn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Categoría (Dewey)</label>
                                <select name="categoria" class="form-select @error('categoria') is-invalid @enderror" required>
                                    @php
                                        $categorias = [
                                            'Obras Generales' => '000 - Obras Generales',
                                            'Filosofía y Psicología' => '100 - Filosofía y Psicología',
                                            'Religión' => '200 - Religión',
                                            'Ciencias Sociales' => '300 - Ciencias Sociales',
                                            'Lenguaje' => '400 - Lenguaje',
                                            'Ciencias Puras' => '500 - Ciencias Puras',
                                            'Ciencias Aplicadas y Artes Útiles' => '600 - Ciencias Aplicadas y Artes Útiles',
                                            'Arte y Recreación' => '700 - Arte y Recreación',
                                            'Literatura' => '800 - Literatura',
                                            'Historia, Geografía y Biografía' => '900 - Historia, Geografía y Biografía'
                                        ];
                                    @endphp
                                    @foreach($categorias as $valor => $texto)
                                        <option value="{{ $valor }}" {{ old('categoria', $libro->categoria) == $valor ? 'selected' : '' }}>
                                            {{ $texto }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoria')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Stock Total (Inventario)</label>
                            <input type="number" name="stock_total" 
                                   class="form-control @error('stock_total') is-invalid @enderror" 
                                   value="{{ old('stock_total', $libro->stock_total) }}" required min="0">
                            @error('stock_total')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Observaciones</label>
                            <textarea name="observaciones" class="form-control @error('observaciones') is-invalid @enderror" rows="2">{{ old('observaciones', $libro->observaciones) }}</textarea>
                            @error('observaciones')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('libros.index') }}" class="btn btn-light border rounded-pill px-4">Cancelar</a>
                            <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4" id="btn-submit">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('editForm').addEventListener('submit', function(e) {
        let isValid = true;
        const requiredInputs = this.querySelectorAll('[required]');
        requiredInputs.forEach(input => {
            if (input.value.trim() === "") {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
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
            btn.innerText = "Guardando...";
        }
    });
</script>
@endsection