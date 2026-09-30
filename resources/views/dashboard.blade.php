@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="mb-4 px-2 animate-fade-up">
        <h3 class="text-dark fw-bold"><i class="bi bi-speedometer2 me-2 text-primary"></i>Panel de Control</h3>
        <p class="text-muted small">Resumen general de la Biblioteca Escolar</p>
    </div>

    <div class="row g-4 mb-4">
        <!-- Tarjeta 1: Libros (Títulos y Ejemplares) -->
        <div class="col-12 col-sm-6 col-xl-3 animate-fade-up delay-100">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #1e293b, #0f172a);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 50px; height: 50px; background: rgba(59,130,246,0.2);">
                        <i class="bi bi-book-half text-primary" style="font-size: 1.5rem;"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-white mb-0 small fw-bold text-uppercase">Fondos Bibliográficos</h6>
                        <div class="mt-1">
                            <div class="d-flex justify-content-between align-items-baseline">
                                <span class="text-white-50 small">Títulos</span>
                                <span class="text-white fs-4 fw-bold ms-2">{{ $totalLibros }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-baseline mt-1">
                                <span class="text-white-50 small">Ejemplares</span>
                                <span class="text-white fs-4 fw-bold ms-2">{{ $totalEjemplares }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta 2: Préstamos activos -->
        <div class="col-12 col-sm-6 col-xl-3 animate-fade-up delay-200">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #1e293b, #0f172a);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 50px; height: 50px; background: rgba(16,185,129,0.2);">
                        <i class="bi bi-arrow-left-right text-success" style="font-size: 1.5rem;"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-white mb-0 small fw-bold text-uppercase">En Préstamo</h6>
                        <div class="mt-1">
                            <span class="text-white display-6 fw-bold">{{ $prestamosActivos }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta 3: Préstamos vencidos -->
        <div class="col-12 col-sm-6 col-xl-3 animate-fade-up delay-300">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #1e293b, #0f172a);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 50px; height: 50px; background: {{ $prestamosVencidos > 0 ? 'rgba(239,68,68,0.2)' : 'rgba(245,158,11,0.2)' }};">
                        <i class="bi bi-clock-history {{ $prestamosVencidos > 0 ? 'text-danger' : 'text-warning' }}" style="font-size: 1.5rem;"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-white mb-0 small fw-bold text-uppercase">Vencidos</h6>
                        <div class="mt-1">
                            <span class="text-white display-6 fw-bold">{{ $prestamosVencidos }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta 4: Estudiantes -->
        <div class="col-12 col-sm-6 col-xl-3 animate-fade-up delay-400">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #1e293b, #0f172a);">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 50px; height: 50px; background: rgba(139,92,246,0.2);">
                        <i class="bi bi-people text-info" style="font-size: 1.5rem;"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-white mb-0 small fw-bold text-uppercase">Estudiantes</h6>
                        <div class="mt-1">
                            <span class="text-white display-6 fw-bold">{{ $totalEstudiantes }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 animate-fade-up delay-200">
        <div class="col-12">
            <div class="card border-0 shadow overflow-hidden" style="background: linear-gradient(145deg, #1e293b, #0f172a); border-radius: 20px;">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-star-fill text-warning" style="font-size: 2.5rem;"></i>
                    </div>
                    <h2 class="text-white fw-bold">¡Bienvenido al Sistema de Gestión!</h2>
                    <p class="text-light px-md-5">
                        Administra fácilmente el inventario de libros, el registro de estudiantes y el control de préstamos desde un solo lugar.
                    </p>
                    <div class="d-flex justify-content-center gap-3 mt-4">
                        <a href="{{ route('libros.index') }}" class="btn btn-outline-info fw-bold px-4 py-2 rounded-pill">
                            <i class="bi bi-journal-text me-2"></i> Inventario
                        </a>
                        <a href="{{ route('prestamos.index') }}" class="btn btn-primary fw-bold px-4 py-2 rounded-pill shadow-sm">
                            <i class="bi bi-arrow-repeat me-2"></i> Ver Préstamos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection