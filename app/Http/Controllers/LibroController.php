<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Prestamo;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LibroController extends Controller
{
    /**
     * Muestra el inventario de libros con búsqueda por título, autor, ISBN o categoría.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Obtener término de búsqueda
        $buscar = $request->get('buscar');

        // Aplicar filtros si hay búsqueda
        $libros = Libro::when($buscar, function ($query, $buscar) {
            return $query->where('titulo', 'ILIKE', "%{$buscar}%")
                         ->orWhere('autor', 'ILIKE', "%{$buscar}%")
                         ->orWhere('isbn', 'ILIKE', "%{$buscar}%")
                         ->orWhere('categoria', 'ILIKE', "%{$buscar}%");
        })
        ->orderBy('categoria', 'asc')
        ->orderBy('titulo', 'asc')
        ->get();

        // Retornar vista con libros y término de búsqueda
        return view('libros.index', compact('libros', 'buscar'));
    }

    /**
     * Genera reportes de estadísticas de préstamos (ranking, popular del mes, movimientos).
     *
     * @return \Illuminate\View\View
     */
    public function reportes()
    {
        // Establecer localización en español para nombres de meses
        \App::setLocale('es');

        // Obtener mes y año actuales
        $ahora = Carbon::now();
        $mesActual = $ahora->month;
        $anioActual = $ahora->year;

        // Ranking general: libros más prestados de todos los tiempos
        $rankingGeneral = DB::table('prestamos')
            ->join('libros', 'prestamos.libro_id', '=', 'libros.id')
            ->select('libros.titulo', 'libros.categoria', DB::raw('count(prestamos.id) as total_prestamos'))
            ->groupBy('libros.titulo', 'libros.categoria')
            ->orderBy('total_prestamos', 'desc')
            ->get();

        // Libro más popular en el mes actual
        $libroMasPopularMes = DB::table('prestamos')
            ->join('libros', 'prestamos.libro_id', '=', 'libros.id')
            ->select('libros.titulo', DB::raw('count(prestamos.id) as total'))
            ->whereMonth('fecha_prestamo', $mesActual)
            ->whereYear('fecha_prestamo', $anioActual)
            ->groupBy('libros.titulo')
            ->orderBy('total', 'desc')
            ->first();

        // Datos mensuales de préstamos del año actual
        $datosMensuales = DB::table('prestamos')
            ->select(DB::raw("EXTRACT(MONTH FROM fecha_prestamo) as mes_num"), DB::raw('count(id) as total'))
            ->whereYear('fecha_prestamo', $anioActual)
            ->groupBy('mes_num')
            ->orderBy('mes_num', 'asc')
            ->get();

        // Convertir números de mes a nombres de mes en español
        $historialMensual = $datosMensuales->map(function($item) {
            return (object)[
                'mes_nombre' => Carbon::create()->month((int)$item->mes_num)->translatedFormat('F'),
                'total' => $item->total
            ];
        });

        // Total anual de préstamos
        $totalAnual = $historialMensual->sum('total');

        // Retornar vista con todas las estadísticas
        return view('reportes.index', compact('rankingGeneral', 'libroMasPopularMes', 'historialMensual', 'totalAnual'));
    }

    /**
     * Muestra el formulario para registrar un nuevo libro.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('libros.create');
    }

    /**
     * Guarda un nuevo libro en la base de datos.
     * Validaciones: autor con letras, espacios y guiones; stock mínimo 1;
     * ISBN único, sin restricción de formato.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Reglas de validación
        $rules = [
            'titulo'        => 'required|string|max:255',
            'autor'         => 'required|string|max:255|regex:/^[A-Za-záéíóúñÑ\s\-]+$/',
            'categoria'     => 'required|string',
            'stock_total'   => 'required|integer|min:1',
            'isbn'          => 'nullable|string|unique:libros,isbn|regex:/^[\d\-]+$/',
            'observaciones' => 'nullable|string|max:1000',
        ];

        // Mensajes personalizados
        $messages = [
            'autor.regex'        => 'El nombre del autor solo puede contener letras, espacios y guiones (sin números).',
            'stock_total.min'    => 'El stock debe ser al menos 1 unidad para registrar el libro.',
            'isbn.unique'        => 'Este ISBN ya está registrado en el sistema.',
            'isbn.regex'         => 'El ISBN solo puede contener números y guiones (-).',
        ];

        // Validar los datos
        $data = $request->validate($rules, $messages);

        // Crear el libro en la base de datos
        Libro::create($data);

        // Redirigir al índice con mensaje de éxito
        return redirect()->route('libros.index')->with('success', '¡Libro guardado con éxito!');
    }

    /**
     * Muestra el formulario para editar un libro existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Buscar libro o lanzar error 404
        $libro = Libro::findOrFail($id);
        return view('libros.edit', compact('libro'));
    }

    /**
     * Actualiza los datos de un libro.
     * Permite stock 0 (agotado), ISBN se valida excepto el propio.
     * No permite reducir el stock por debajo de los ejemplares actualmente prestados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Buscar libro
        $libro = Libro::findOrFail($id);

        // Reglas de validación (ISBN único excepto para este registro)
        $rules = [
            'titulo'        => 'required|string|max:255',
            'autor'         => 'required|string|max:255|regex:/^[A-Za-záéíóúñÑ\s\-]+$/',
            'categoria'     => 'required|string',
            'stock_total'   => 'required|integer|min:0',
            'isbn'          => 'nullable|string|unique:libros,isbn|regex:/^[\d\-]+$/' . $id,
            'observaciones' => 'nullable|string|max:1000',
        ];

        $messages = [
            'autor.regex'        => 'El nombre del autor solo puede contener letras, espacios y guiones (sin números).',
            
            'isbn.unique'        => 'Este ISBN ya está registrado en el sistema.',
            'isbn.regex'         => 'El ISBN solo puede contener números y guiones (-).'
        ];

        // Validar datos
        $validated = $request->validate($rules, $messages);

        // Obtener cantidad de ejemplares prestados actualmente
        $prestados = Prestamo::where('libro_id', $libro->id)
            ->where('estado', 'Prestado')
            ->count();

        // Validación adicional: no permitir reducir stock por debajo de los prestados
        if ($request->stock_total < $prestados) {
            return redirect()->back()
                ->with('error', "❌ No se puede reducir el stock a {$request->stock_total} porque actualmente hay {$prestados} ejemplares prestados.")
                ->withInput();
        }

        // Actualizar libro
        $libro->update($validated);

        // Redirigir al índice con mensaje de éxito
        return redirect()->route('libros.index')->with('success', '¡Libro actualizado correctamente!');
    }

    /**
     * Elimina un libro de la base de datos.
     * Previene la eliminación si el libro tiene préstamos activos (no devueltos).
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Buscar libro
        $libro = Libro::findOrFail($id);

        // Verificar si tiene préstamos activos
        if ($libro->prestamos()->where('estado', '!=', 'Devuelto')->exists()) {
            return redirect()->route('libros.index')
                ->with('error', 'No se puede eliminar el libro porque tiene préstamos pendientes.');
        }

        // Eliminar libro
        $libro->delete();

        // Redirigir al índice con mensaje de éxito
        return redirect()->route('libros.index')->with('success', '¡Libro eliminado del inventario!');
    }

    /**
     * Genera el reporte PDF del inventario completo.
     *
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generarPdf()
    {
        // Obtener todos los libros ordenados por categoría
        $libros = Libro::orderBy('categoria', 'asc')->get();

        // Cargar la vista PDF y retornar la descarga
        $pdf = Pdf::loadView('pdf.inventario', compact('libros'));
        return $pdf->download('reporte-biblioteca.pdf');
    }
}