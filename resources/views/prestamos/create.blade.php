@extends('layouts.app')

@section('content')
<style>
    .select2-container--default .select2-selection--single {
        background-color: #1e293b !important;
        border: 1px solid #475569 !important;
        height: 42px !important;
        border-radius: 12px !important;
        color: white;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: white !important;
        line-height: 40px;
    }
    .select2-dropdown {
        background-color: #1e293b !important;
        border-color: #475569 !important;
        color: white;
    }
    .select2-results__option {
        background-color: #1e293b;
        color: white;
        padding: 8px 12px;
    }
    .select2-results__option--highlighted {
        background-color: #3b82f6 !important;
    }
    /* Resaltar coincidencia de búsqueda */
    .select2-results__option .match {
        font-weight: bold;
        background-color: #fbbf24;
        color: #1e293b;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-6 animate-fade-up">
        <div class="card shadow border-0" style="background-color: #1e293b;">
            <div class="card-header border-0 py-3" style="background: transparent;">
                <h4 class="mb-0 text-white fw-bold"><i class="bi bi-book-half me-2"></i> Registrar Nuevo Préstamo</h4>
            </div>
            <div class="card-body p-4" style="background-color: #0f172a; border-radius: 0 0 10px 10px;">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Error:</strong> {{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('prestamos.store') }}" method="POST" id="form-prestamo" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold text-white">Seleccionar Estudiante</label>
                        <select name="estudiante_id" class="form-select select2-buscable" required id="select-estudiante" style="width: 100%;">
                            <option value="">-- Buscar estudiante por nombre o cédula --</option>
                            @foreach($estudiantes as $estudiante)
                                <option value="{{ $estudiante->id }}" data-cedula="{{ $estudiante->cedula }}">
                                    {{ $estudiante->nombre_completo }} ({{ $estudiante->cedula }})
                                </option>
                            @endforeach
                        </select>
                        <div class="text-danger small fw-bold mt-1 error-msg" style="display:none;">Debe seleccionar un estudiante</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-white">Seleccionar Libro</label>
                        <select name="libro_id" class="form-select select2-buscable" required id="select-libro" style="width: 100%;">
                            <option value="">-- Buscar libro por título, autor o ISBN --</option>
                            @foreach($libros as $libro)
                                <option value="{{ $libro->id }}" data-autor="{{ $libro->autor }}" data-isbn="{{ $libro->isbn }}">
                                    {{ $libro->titulo }} - {{ $libro->autor }} ({{ $libro->isbn ?? 'sin ISBN' }})
                                </option>
                            @endforeach
                        </select>
                        <div class="text-danger small fw-bold mt-1 error-msg" style="display:none;">Debe seleccionar un libro</div>
                    </div>

                    <div class="alert alert-info border-0 shadow-sm" style="background: #0f172a; color: #60a5fa;">
                        <i class="bi bi-info-circle-fill me-2"></i> El sistema asignará <strong>7 días</strong> automáticamente para la devolución.
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-pill" id="btn-submit">
                            <span id="btn-text">Confirmar Préstamo</span>
                            <span id="btn-spinner" class="spinner-border spinner-border-sm d-none"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Mejorar Select2 con búsqueda personalizada que incluye cédula/ISBN/autor
        function customMatcher(params, data) {
            // Si no hay término de búsqueda, muestra todo
            if ($.trim(params.term) === '') {
                return data;
            }
            // Término en minúsculas para comparar
            var term = params.term.toLowerCase();
            // Texto visible del option
            var text = data.text.toLowerCase();
            // Buscar también en atributos personalizados (cedula, autor, isbn)
            var $option = $(data.element);
            var extraFields = [];
            if ($option.data('cedula')) extraFields.push($option.data('cedula').toLowerCase());
            if ($option.data('autor')) extraFields.push($option.data('autor').toLowerCase());
            if ($option.data('isbn')) extraFields.push($option.data('isbn').toLowerCase());
            
            // Si coincide en el texto visible o en algún campo extra, lo muestra
            if (text.indexOf(term) > -1 || extraFields.some(field => field.indexOf(term) > -1)) {
                // Opcional: resaltar el término (requiere modificar el renderizado)
                return data;
            }
            return null;
        }

        $('.select2-buscable').each(function() {
            var $select = $(this);
            $select.select2({
                placeholder: "Seleccione una opción...",
                allowClear: true,
                width: '100%',
                matcher: customMatcher,   // <-- búsqueda personalizada
                language: {
                    noResults: function() {
                        return "No se encontraron coincidencias";
                    },
                    searching: function() {
                        return "Buscando...";
                    }
                }
            });
        });

        // Validación personalizada para Select2
        document.getElementById('form-prestamo').addEventListener('submit', function(e) {
            let isValid = true;
            const selects = this.querySelectorAll('select[required]');
            selects.forEach(select => {
                const errorMsg = select.parentElement.querySelector('.error-msg');
                if (select.value === "") {
                    const container = select.nextElementSibling;
                    if (container && container.classList.contains('select2-container')) {
                        container.style.border = "1px solid #ff7675";
                        container.style.borderRadius = "12px";
                    }
                    if (errorMsg) errorMsg.style.display = "block";
                    isValid = false;
                } else {
                    const container = select.nextElementSibling;
                    if (container && container.classList.contains('select2-container')) {
                        container.style.border = "";
                    }
                    if (errorMsg) errorMsg.style.display = "none";
                }
            });

            if (isValid) {
                const btn = document.getElementById('btn-submit');
                btn.disabled = true;
                document.getElementById('btn-text').innerText = "Procesando...";
                document.getElementById('btn-spinner').classList.remove('d-none');
            } else {
                e.preventDefault();
            }
        });
    });
</script>
@endsection