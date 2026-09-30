@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow border-0 text-white" style="background-color: #2d3436; border-radius: 10px;">
        <div class="card-header border-0 d-flex justify-content-between align-items-center py-3 px-4" style="background: transparent;">
            <h5 class="mb-0 text-white fw-bold"><i class="bi bi-journal-bookmark-fill me-2"></i>Inventario de Libros</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('libros.pdf') }}" class="btn btn-outline-info btn-sm fw-bold px-3 d-flex align-items-center" style="border-width: 2px;">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> PDF
                </a>
                <a href="{{ route('libros.create') }}" class="btn btn-light btn-sm fw-bold px-3 text-primary shadow-sm">
                    <i class="bi bi-plus-circle-fill me-1"></i> Agregar Nuevo Libro
                </a>
            </div>
        </div>
        
        <div class="card-body p-0" style="background-color: #1e272e; border-radius: 0 0 10px 10px;">
            <div class="px-4 py-3">
                <form action="{{ route('libros.index') }}" method="GET" id="searchForm">
                    <div class="input-group shadow-sm">
                        <input type="text" name="buscar" id="buscarInput" class="form-control border-0 text-white" 
                               placeholder="Buscar por título, autor, categoría o ISBN..." 
                               value="{{ $buscar }}" 
                               style="background: #34495e; height: 38px;">
                        <button class="btn btn-primary px-4 fw-bold" type="submit">🔍 Buscar</button>
                        @if($buscar)
                            <a href="{{ route('libros.index') }}" class="btn btn-secondary border-0 text-white px-3 d-flex align-items-center">Limpiar</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="table-responsive px-4 pb-4">
                <table class="table table-hover align-middle mb-0" style="background: white; color: #2d3436 !important; border-radius: 5px; overflow: hidden; border-collapse: separate; border-spacing: 0;">
                    <thead style="background: #f1f2f6; border-bottom: 2px solid #dee2e6;">
                        <tr>
                            <th style="width: 4px; padding: 0; min-width: 4px;"></th>
                            <th class="ps-3 py-2 fw-bold text-uppercase" style="font-size: 0.85rem;">Título / Categoría</th>
                            <th class="py-2 fw-bold text-uppercase" style="font-size: 0.85rem;">Autor</th>
                            <th class="py-2 fw-bold text-uppercase" style="font-size: 0.85rem;">ISBN</th>
                            <th class="text-center py-2 fw-bold text-uppercase" style="font-size: 0.85rem;">Obs.</th>
                            <th class="text-center py-2 fw-bold text-uppercase" style="font-size: 0.85rem;">Stock</th>
                            <th class="text-center py-2 fw-bold text-uppercase" style="font-size: 0.85rem;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($libros as $libro)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="background-color: {{ $libro->obtenerColorCategoria() }}; padding: 0; width: 4px; min-width: 4px;"></td>
                            <td class="ps-3 py-1">
                                <div class="fw-bold text-dark text-truncate" style="max-width: 250px; font-size: 0.9rem; line-height: 1.2;">
                                    {{ $libro->titulo }}
                                </div>
                                <span class="badge" style="background-color: {{ $libro->obtenerColorCategoria() }}; font-size: 0.65rem; text-transform: uppercase; color: white; padding: 2px 5px;">
                                    {{ $libro->categoria }}
                                </span>
                            </td>
                            <td class="text-muted py-1">{{ $libro->autor }}</td>
                            <td class="text-muted py-1">{{ $libro->isbn ?? '---' }}</td>
                            <td class="text-center py-1">
                                @if($libro->observaciones)
                                    <button type="button" class="btn btn-sm p-0 border-0 bg-transparent shadow-none" 
                                            onclick="mostrarModal({{ $libro->id }})">
                                        <i class="bi bi-eye-fill text-primary" style="font-size: 1.1rem;"></i>
                                    </button>

                                    <div class="modal fade" id="modalObs{{ $libro->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $libro->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content shadow-lg" style="border-radius: 12px; border: none; overflow: hidden;">
                                                <div class="modal-header border-0 d-flex align-items-center" style="background-color: {{ $libro->obtenerColorCategoria() }};">
                                                    <h6 class="modal-title fw-bold text-white mb-0" id="modalLabel{{ $libro->id }}">
                                                        <i class="bi bi-info-circle-fill me-2"></i>Observaciones
                                                    </h6>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body py-4 px-4 bg-white text-start">
                                                    <div class="mb-3">
                                                        <label class="text-muted small text-uppercase fw-bold d-block mb-1">Título del Libro</label>
                                                        <div class="fw-bold text-dark">{{ $libro->titulo }}</div>
                                                    </div>
                                                    <hr class="my-3" style="opacity: 0.1;">
                                                    <div>
                                                        <label class="text-muted small text-uppercase fw-bold d-block mb-1">Descripción / Estado</label>
                                                        <p class="mb-0 p-3 rounded" style="white-space: pre-line; font-size: 0.95rem; color: #2d3436; background-color: #f8f9fa; border-left: 4px solid {{ $libro->obtenerColorCategoria() }};">
                                                            {{ $libro->observaciones }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">---</span>
                                @endif
                            </td>
                            <td class="text-center py-1">
                                <span class="badge bg-success shadow-sm">{{ $libro->stock_total }} unidades</span>
                            </td>
                            <td class="text-center py-1">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('libros.edit', $libro->id) }}" class="btn btn-warning btn-sm fw-bold text-dark py-1 px-3">Editar</a>
                                    <form action="{{ route('libros.destroy', $libro->id) }}" method="POST" class="form-eliminar d-inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm fw-bold py-1 px-3 btn-borrar">Borrar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">No hay libros registrados.复核
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .table td { padding: 8px 10px !important; border-top: 0; }
    .table th { padding: 12px 10px !important; }
    .table td:first-child, .table th:first-child {
        width: 4px !important;
        max-width: 4px !important;
        min-width: 4px !important;
    }
    tbody tr:hover { background-color: #f8f9fa !important; }
    .form-control:focus { background: #3d566e !important; color: white !important; box-shadow: none; border: 1px solid #3498db !important; }
    .modal-body p { line-height: 1.5; }
    .modal.show {
        display: block !important;
        background-color: rgba(0,0,0,0.5);
    }
    .modal-backdrop.show {
        opacity: 0.5;
    }
    .modal {
        z-index: 1060 !important;
    }
    .modal-backdrop {
        z-index: 1050 !important;
    }
    .modal-content {
        background-color: white !important;
        color: #333 !important;
    }
    .modal-header, .modal-footer {
        border: none !important;
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.12.0/sweetalert2.all.min.js"></script>
<script>
    function mostrarModal(id) {
        const modalElement = document.getElementById('modalObs' + id);
        if (modalElement) {
            document.body.appendChild(modalElement);
            const modal = new bootstrap.Modal(modalElement, { backdrop: true, keyboard: true });
            modal.show();
        }
    }

    document.querySelectorAll('.btn-borrar').forEach(boton => {
        boton.addEventListener('click', function(e) {
            const formulario = this.closest('.form-eliminar');
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) formulario.submit();
            });
        });
    });

    @if(session('success'))
        Swal.fire({
            title: '¡Logrado!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonColor: '#3085d6'
        });
    @endif
</script>
@endsection