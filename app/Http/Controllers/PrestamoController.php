<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prestamo;
use App\Models\Libro;
use App\Models\Estudiante;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class PrestamoController extends Controller
{
    /**
     * Muestra la lista de todos los préstamos (activos y devueltos), ordenados por fecha de creación descendente.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtener todos los préstamos con sus relaciones libro y estudiante
        $prestamos = Prestamo::with(['libro', 'estudiante'])->orderBy('created_at', 'desc')->get();
        return view('prestamos.index', compact('prestamos'));
    }

    /**
     * Muestra el formulario para registrar un nuevo préstamo.
     * Solo muestra libros con stock disponible > 0.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Libros con stock mayor a 0
        $libros = Libro::where('stock_total', '>', 0)->orderBy('titulo', 'asc')->get();

        // Todos los estudiantes ordenados por nombre
        $estudiantes = Estudiante::orderBy('nombre_completo', 'asc')->get();

        return view('prestamos.create', compact('libros', 'estudiantes'));
    }

    /**
     * Guarda un nuevo préstamo en la base de datos.
     * Usa transacción y bloqueo pesimista para evitar sobreprestar.
     * Asigna automáticamente 7 días para la devolución.
     * Validaciones adicionales:
     * - El estudiante no puede tener el mismo libro ya prestado.
     * - El estudiante no puede tener más de 3 préstamos activos al mismo tiempo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validar existencia de libro y estudiante
        $request->validate([
            'libro_id'      => 'required|exists:libros,id',
            'estudiante_id' => 'required|exists:estudiantes,id',
        ], [
            'libro_id.exists'      => 'El libro seleccionado no es válido.',
            'estudiante_id.exists' => 'El estudiante seleccionado no es válido.'
        ]);

        // Usar transacción para asegurar consistencia
        return DB::transaction(function () use ($request) {
            // Bloquear la fila del libro para evitar préstamos simultáneos
            $libro = Libro::lockForUpdate()->find($request->libro_id);

            // Verificar que el libro existe y tiene stock disponible
            if (!$libro || $libro->stock_total <= 0) {
                return redirect()->back()->with('error', '❌ ¡Atención! No quedan unidades disponibles para este libro.');
            }

            // Validación 1: El estudiante no puede tener el mismo libro ya prestado
            $mismoLibroPrestado = Prestamo::where('estudiante_id', $request->estudiante_id)
                ->where('libro_id', $request->libro_id)
                ->where('estado', 'Prestado')
                ->exists();

            if ($mismoLibroPrestado) {
                return redirect()->back()->with('error', '❌ El estudiante ya tiene este libro en préstamo y no lo ha devuelto.');
            }

            // Validación 2: El estudiante no puede tener más de 3 préstamos activos
            $limitePrestamos = 3;
            $prestamosActivos = Prestamo::where('estudiante_id', $request->estudiante_id)
                ->where('estado', 'Prestado')
                ->count();

            if ($prestamosActivos >= $limitePrestamos) {
                return redirect()->back()->with('error', "❌ El estudiante ya tiene {$limitePrestamos} préstamos activos. Debe devolver uno antes de tomar otro libro.");
            }

            // Calcular fechas: préstamo hoy, devolución en 7 días
            $fechaPrestamo = Carbon::now();
            $fechaDevolucion = $fechaPrestamo->copy()->addDays(7);

            // Crear el préstamo
            Prestamo::create([
                'libro_id'         => $request->libro_id,
                'estudiante_id'    => $request->estudiante_id,
                'fecha_prestamo'   => $fechaPrestamo,
                'fecha_devolucion' => $fechaDevolucion,
                'estado'           => 'Prestado'
            ]);

            // Disminuir el stock del libro en 1
            $libro->decrement('stock_total');

            // Redirigir al índice con mensaje de éxito
            return redirect()->route('prestamos.index')
                ->with('success', '¡Préstamo exitoso! Devolución programada: ' . $fechaDevolucion->format('d/m/Y'));
        });
    }

    /**
     * Marca un préstamo como devuelto y aumenta el stock del libro.
     * Usa transacción para mantener consistencia.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function devolver($id)
    {
        // Usar transacción
        return DB::transaction(function () use ($id) {
            // Buscar el préstamo
            $prestamo = Prestamo::findOrFail($id);

            // Solo si aún no está devuelto
            if ($prestamo->estado != 'Devuelto') {
                // Cambiar estado a Devuelto
                $prestamo->update(['estado' => 'Devuelto']);

                // Aumentar el stock del libro asociado
                $prestamo->libro()->increment('stock_total');

                return redirect()->back()->with('success', 'Libro devuelto correctamente. El stock se ha actualizado.');
            }

            return redirect()->back()->with('info', 'Este libro ya fue marcado como devuelto anteriormente.');
        });
    }

    /**
     * Extiende el plazo de devolución del préstamo por 7 días adicionales.
     * Permite múltiples extensiones sin límite (solo valida que la nueva fecha sea posterior a hoy).
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function extender($id)
    {
        // Buscar el préstamo
        $prestamo = Prestamo::findOrFail($id);

        // No se puede extender un préstamo ya devuelto
        if ($prestamo->estado == 'Devuelto') {
            return redirect()->back()->with('error', 'No se puede extender un préstamo que ya ha sido devuelto.');
        }

        // Calcular nueva fecha de devolución: actual + 7 días
        $nuevaFecha = Carbon::parse($prestamo->fecha_devolucion)->addDays(7);

        // Validar que la nueva fecha sea posterior a hoy (evitar fechas pasadas)
        if ($nuevaFecha <= Carbon::now()) {
            return redirect()->back()->with('error', 'La nueva fecha debe ser posterior a hoy.');
        }

        // Actualizar la fecha de devolución
        $prestamo->update(['fecha_devolucion' => $nuevaFecha]);

        // Redirigir con mensaje de éxito
        return redirect()->back()->with('success', 'Plazo extendido por 7 días adicionales. Nueva fecha: ' . $nuevaFecha->format('d/m/Y'));
    }

    /**
     * Genera el ticket PDF del préstamo (comprobante).
     *
     * @param  int  $id
     * @return \Barryvdh\DomPDF\PDF
     */
    public function comprobante($id)
    {
        // Obtener préstamo con sus relaciones
        $prestamo = Prestamo::with(['libro', 'estudiante'])->findOrFail($id);

        // Establecer localización en español para fechas
        \App::setLocale('es');

        // Cargar vista PDF y mostrarlo en el navegador (stream)
        $pdf = Pdf::loadView('pdf.comprobante_prestamo', compact('prestamo'));
        return $pdf->stream('ticket-prestamo-'.$id.'.pdf');
    }
}