@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow border-0 text-white animate-fade-up" style="background-color: #2d3436; border-radius: 10px;">
        <div class="card-header border-0 d-flex justify-content-between align-items-center py-3 px-4" style="background: transparent;">
            <h5 class="mb-0 text-white fw-bold"><i class="bi bi-people-fill me-2"></i>Gestión de Estudiantes</h5>
            <a href="{{ route('estudiantes.create') }}" class="btn btn-light btn-sm fw-bold px-3 text-primary shadow-sm d-flex align-items-center">
                <i class="bi bi-person-plus-fill me-2"></i> Registrar Estudiante
            </a>
        </div>
        <div class="card-body p-0" style="background-color: #1e272e; border-radius: 0 0 10px 10px;">
            <div class="px-4 py-3">
                <form action="{{ route('estudiantes.index') }}" method="GET" id="searchForm">
                    <div class="input-group shadow-sm">
                        <input type="text" name="buscar" id="buscarInput" class="form-control border-0 text-white" 
                               placeholder="Buscar por nombre o cédula..." 
                               value="{{ $buscar }}" 
                               style="background: #34495e; height: 38px;">
                        <button class="btn btn-primary px-4 fw-bold" type="submit">🔍 Buscar</button>
                        @if($buscar)
                            <a href="{{ route('estudiantes.index') }}" class="btn btn-secondary border-0 text-white px-3 d-flex align-items-center">Limpiar</a>
                        @endif
                    </div>
                </form>
            </div>
            <div class="table-responsive px-4 pb-4">
                <table class="table table-hover align-middle mb-0" style="background: white; color: #2d3436 !important; border-radius: 5px; overflow: hidden;">
                    <thead style="background: #f1f2f6; border-bottom: 2px solid #dee2e6;">
                        <tr>
                            <th class="ps-4 py-2 fw-bold text-uppercase" style="font-size: 0.85rem;">Nombre Completo</th>
                            <th class="py-2 fw-bold text-uppercase" style="font-size: 0.85rem;">Cédula</th>
                            <th class="py-2 fw-bold text-uppercase" style="font-size: 0.85rem;">Grado / Año</th>
                            <th class="py-2 fw-bold text-uppercase" style="font-size: 0.85rem;">Docente Guía</th>
                            <th class="text-center py-2 fw-bold text-uppercase" style="font-size: 0.85rem;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($estudiantes as $estudiante)
                        <tr style="border-bottom: 1px solid #eee;">
                            <td class="ps-4 py-1">
                                <div class="fw-bold text-dark text-capitalize" style="font-size: 0.9rem;">{{ $estudiante->nombre_completo }}</div>
                            </td>
                            <td class="py-1"><span class="badge bg-light text-dark border shadow-sm">{{ $estudiante->cedula }}</span></td>
                            <td class="py-1">
                                <span class="badge bg-info text-dark px-2 py-1 shadow-sm">
                                    <i class="bi bi-mortarboard-fill me-1"></i> {{ $estudiante->grado }}
                                </span>
                            </td>
                            <td class="text-muted py-1" style="font-size: 0.85rem;">{{ $estudiante->docente_guia ?? 'Sin asignar' }}</td>
                            <td class="text-center py-1">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('estudiantes.edit', $estudiante->id) }}" class="btn btn-warning btn-sm fw-bold text-dark py-1 px-3">Editar</a>
                                    <form action="{{ route('estudiantes.destroy', $estudiante->id) }}" method="POST" class="form-eliminar d-inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm fw-bold py-1 px-3 btn-borrar">Borrar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted small">No hay estudiantes registrados.
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
    tbody tr:hover { background-color: #f8f9fa !important; }
    .form-control:focus { background: #3d566e !important; color: white !important; box-shadow: none; border: 1px solid #3498db !important; }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.12.0/sweetalert2.all.min.js"></script>
<script>
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
        Swal.fire({ title: '¡Logrado!', text: "{{ session('success') }}", icon: 'success', confirmButtonColor: '#3085d6' });
    @endif
</script>
@endsection