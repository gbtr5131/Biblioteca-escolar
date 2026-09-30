<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Prestamo;
use App\Models\Estudiante;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Número de títulos distintos
        $totalLibros = Libro::count();

        // Número total de ejemplares (stock)
        $totalEjemplares = Libro::sum('stock_total');

        $totalEstudiantes = Estudiante::count();

        $prestamosActivos = Prestamo::where('estado', 'Prestado')->count();

        $prestamosVencidos = Prestamo::where('estado', 'Prestado')
                            ->where('fecha_devolucion', '<', Carbon::now())
                            ->count();

        return view('dashboard', compact(
            'totalLibros',
            'totalEjemplares',
            'totalEstudiantes',
            'prestamosActivos',
            'prestamosVencidos'
        ));
    }
}