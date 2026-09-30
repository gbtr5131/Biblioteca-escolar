@extends('layouts.app')

@section('content')
<div class="container animate-fade-up">
    <div class="card shadow border-0">
        <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-white">📋 Control de Préstamos</h4>
            <a href="{{ route('prestamos.create') }}" class="btn btn-success fw-bold shadow-sm rounded-pill">+ Nuevo Préstamo</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Estudiante</th>
                            <th>Libro</th>
                            <th class="text-center">Fecha Salida</th>
                            <th class="text-center">Fecha Entrega</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prestamos as $prestamo)
                            @php
                                $hoy = \Carbon\Carbon::today();
                                $fechaDevolucion = \Carbon\Carbon::parse($prestamo->fecha_devolucion)->startOfDay();
                                $vencido = $hoy->gt($fechaDevolucion) && ($prestamo->estado == 'Prestado' || $prestamo->estado == 'prestado');
                            @endphp
                            <tr class="{{ $vencido ? 'table-danger' : '' }} animate-fade-up" style="animation-delay: {{ $loop->index * 0.02 }}s;">
                                <td class="ps-4">
                                    <div class="fw-bold">{{ $prestamo->estudiante->nombre_completo ?? $prestamo->estudiante->nombre }}</div>
                                    <small class="text-muted">C.I: {{ $prestamo->estudiante->cedula }}</small>
                                </td>
                                <td>{{ $prestamo->libro->titulo }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($prestamo->fecha_prestamo)->format('d/m/Y') }}</td>
                                <td class="text-center {{ $vencido ? 'text-danger fw-bold' : '' }}">
                                    {{ $fechaDevolucion->format('d/m/Y') }}
                                    @if($vencido) <i class="bi bi-exclamation-circle-fill ms-1" title="Plazo vencido"></i> @endif
                                </td>
                                <td class="text-center">
                                    @if(in_array($prestamo->estado, ['Devuelto', 'devuelto', 'Devolvido']))
                                        <span class="badge bg-success rounded-pill px-3">Devuelto</span>
                                    @elseif($vencido)
                                        <span class="badge bg-danger rounded-pill px-3">Atrasado</span>
                                    @else
                                        <span class="badge bg-primary rounded-pill px-3">En curso</span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('prestamos.pdf', $prestamo->id) }}" class="btn btn-sm btn-dark rounded-pill" target="_blank">
                                            📄 Ticket
                                        </a>
                                        @if(in_array($prestamo->estado, ['Prestado', 'prestado']))
                                            <form action="{{ route('prestamos.devolver', $prestamo->id) }}" method="POST" class="form-devolver d-inline" data-id="{{ $prestamo->id }}">
                                                @csrf
                                                <button type="button" class="btn btn-sm btn-outline-success rounded-pill fw-bold btn-devolver">Devolver</button>
                                            </form>
                                            <form action="{{ route('prestamos.extender', $prestamo->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning rounded-pill text-dark fw-bold">
                                                    +7d
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-light text-dark border rounded-pill px-3">Finalizado</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3 d-flex gap-3 justify-content-end">
        <small><span class="badge bg-primary rounded-pill">●</span> En curso</small>
        <small><span class="badge bg-danger rounded-pill">●</span> Atrasado</small>
        <small><span class="badge bg-success rounded-pill">●</span> Finalizado</small>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // SweetAlert para confirmar devolución
        const botonesDevolver = document.querySelectorAll('.btn-devolver');
        botonesDevolver.forEach(boton => {
            boton.addEventListener('click', function(e) {
                const formulario = this.closest('.form-devolver');
                Swal.fire({
                    title: '¿Confirmar devolución?',
                    text: "El libro volverá a estar disponible en el inventario",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, devolver',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        formulario.submit();
                    }
                });
            });
        });

        // Autocierre de alertas (si las hay)
        const alertas = document.querySelectorAll('.alert');
        alertas.forEach(alerta => {
            setTimeout(() => {
                if (alerta) {
                    alerta.classList.remove('show');
                    setTimeout(() => alerta.remove(), 300);
                }
            }, 4000);
        });
    });
</script>
@endsection