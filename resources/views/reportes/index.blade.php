@extends('layouts.app')

@section('content')
<div class="container-fluid px-2 py-3 animate-fade-up">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <h2 class="text-dark fw-bold mb-2 mb-md-0" style="font-size: 1.6rem;">
            <i class="bi bi-bar-chart-steps me-2 text-primary"></i>Panel de Estadísticas
        </h2>
        <span class="badge bg-dark px-3 py-2 rounded-pill">Año {{ now()->year }}</span>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm mb-4 animate-fade-up delay-100" style="background: linear-gradient(135deg, #2563eb, #4f46e5); border-radius: 20px;">
                <div class="card-body p-4 text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white fw-bold text-uppercase small mb-1 opacity-75">📖 Más popular en {{ now()->translatedFormat('F') }}</h6>
                            <h3 class="fw-bold mb-0 text-white">{{ $libroMasPopularMes ? $libroMasPopularMes->titulo : 'Sin registros' }}</h3>
                        </div>
                        <i class="bi bi-fire h1 mb-0 opacity-50 d-none d-sm-block"></i>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 animate-fade-up delay-200" style="background-color: #1e293b !important; border-radius: 20px; overflow: hidden;">
                <div class="card-header border-0 py-3" style="background-color: #334155 !important;">
                    <h5 class="mb-0 text-white fw-bold"><i class="bi bi-trophy me-2 text-warning"></i>Ranking General</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="background: #0f172a; color: #ffffff !important; border-collapse: separate; border-spacing: 0;">
                            <thead style="background-color: #1e293b;">
                                <tr>
                                    <th class="ps-3 py-2" style="color: #ffffff; background-color: #0f172a; border-bottom: 2px solid #334155;">Pos.</th>
                                    <th class="py-2" style="color: #ffffff; background-color: #0f172a; border-bottom: 2px solid #334155;">Libro</th>
                                    <th class="text-center py-2" style="color: #ffffff; background-color: #0f172a; border-bottom: 2px solid #334155;">Préstamos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rankingGeneral as $index => $libro)
                                <tr class="animate-fade-up" style="animation-delay: {{ $index * 0.03 }}s; border-bottom: 1px solid #334155;">
                                    <td class="ps-3 py-2" style="color: #040505;">#{{ $index + 1 }}</td>
                                    <td class="py-2 fw-bold" style="color: #000000;">
                                        {{ $libro->titulo }}
                                        <div class="d-block d-md-none small" style="color: #94a3b8;">{{ $libro->categoria }}</div>
                                    </td>
                                    <td class="text-center py-2"><span class="badge bg-primary rounded-pill">{{ $libro->total_prestamos }}</span></td>
                                </tr>
                                @empty
                                <td><td colspan="3" class="text-center py-4" style="color: #94a3b8;">No hay datos.
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 text-center py-3 mb-4 animate-fade-up delay-300" style="background: linear-gradient(145deg, #1e293b, #0f172a); border-radius: 20px;">
                <div class="card-body py-2">
                    <h6 class="text-uppercase fw-bold small mb-1" style="color: #94a3b8;">Total Anual</h6>
                    <h2 class="mb-0" style="font-size: 2.8rem; color: #ffffff;">{{ $totalAnual }}</h2>
                </div>
            </div>

            <div class="card shadow-sm border-0 animate-fade-up delay-400" style="background-color: #1e293b !important; border-radius: 20px;">
                <div class="card-header border-0 py-3" style="background-color: #334155 !important;">
                    <h6 class="mb-0 fw-bold text-white"><i class="bi bi-calendar3 me-2"></i>Movimiento Mensual</h6>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($historialMensual as $mes)
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center py-3" style="background: transparent !important; border-bottom: 1px solid #334155;">
                            <span class="text-capitalize" style="color: #ffffff;">{{ $mes->mes_nombre }}</span>
                            <span class="badge bg-success rounded-pill">{{ $mes->total }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection