<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;

class EstudianteController extends Controller
{
    /**
     * Muestra la lista de estudiantes con búsqueda y filtros.
     * Permite buscar por nombre, cédula, grado o docente guía.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Obtener el término de búsqueda desde la query string
        $buscar = $request->get('buscar');

        // Aplicar filtro si existe término de búsqueda
        $estudiantes = Estudiante::when($buscar, function ($query, $buscar) {
            return $query->where('nombre_completo', 'ILIKE', "%{$buscar}%")
                         ->orWhere('cedula', 'ILIKE', "%{$buscar}%")
                         ->orWhere('grado', 'ILIKE', "%{$buscar}%")
                         ->orWhere('docente_guia', 'ILIKE', "%{$buscar}%");
        })
        ->orderBy('nombre_completo', 'asc')
        ->get();

        // Retornar vista con los estudiantes y el término de búsqueda
        return view('estudiantes.index', compact('estudiantes', 'buscar'));
    }

    /**
     * Muestra el formulario para registrar un nuevo estudiante.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('estudiantes.create');
    }

    /**
     * Guarda un nuevo estudiante en la base de datos.
     * Validaciones:
     * - nombre_completo y docente_guia solo letras, espacios y guiones.
     * - cédula solo números y única.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Reglas de validación
        $rules = [
            'nombre_completo' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúñÑ\s\-]+$/',
            'cedula'          => 'required|string|unique:estudiantes,cedula|numeric|digits_between:6,12',
            'grado'           => 'required|string',
            'docente_guia'    => 'nullable|string|max:255|regex:/^[a-zA-ZáéíóúñÑ\s\-]+$/',
        ];

        // Mensajes de error personalizados
        $messages = [
            'nombre_completo.regex' => 'El nombre del estudiante solo puede contener letras, espacios y guiones.',
            'docente_guia.regex'    => 'El nombre del docente solo puede contener letras, espacios y guiones.',
            'cedula.unique'         => 'Esta cédula ya se encuentra registrada en el sistema.',
            'cedula.numeric'        => 'La cédula debe contener solo números.',
            'cedula.digits_between' => 'La cédula debe tener entre 6 y 12 dígitos.',
        ];

        // Validar los datos
        $data = $request->validate($rules, $messages);

        // Crear el estudiante en la base de datos
        Estudiante::create($data);

        // Redirigir al índice con mensaje de éxito
        return redirect()->route('estudiantes.index')
            ->with('success', '¡Estudiante registrado con éxito!');
    }

    /**
     * Muestra el formulario para editar un estudiante existente.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Buscar el estudiante o lanzar error 404
        $estudiante = Estudiante::findOrFail($id);
        return view('estudiantes.edit', compact('estudiante'));
    }

    /**
     * Actualiza los datos de un estudiante.
     * Validaciones similares al store, pero la cédula puede ser la misma (se ignora su unicidad en la misma fila).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Buscar el estudiante
        $estudiante = Estudiante::findOrFail($id);

        // Reglas de validación (la cédula debe ser única excepto para este registro)
        $rules = [
            'nombre_completo' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúñÑ\s\-]+$/',
            'cedula'          => 'required|string|unique:estudiantes,cedula,' . $id . '|numeric|digits_between:6,12',
            'grado'           => 'required|string',
            'docente_guia'    => 'nullable|string|max:255|regex:/^[a-zA-ZáéíóúñÑ\s\-]+$/',
        ];

        $messages = [
            'nombre_completo.regex' => 'El nombre del estudiante solo puede contener letras, espacios y guiones.',
            'docente_guia.regex'    => 'El nombre del docente solo puede contener letras, espacios y guiones.',
            'cedula.regex'          => 'La cédula debe contener solo números.',
            'cedula.digits_between' => 'La cédula debe tener entre 6 y 12 dígitos.',
        ];

        // Validar datos
        $data = $request->validate($rules, $messages);

        // Actualizar el estudiante
        $estudiante->update($data);

        // Redirigir al índice con mensaje de éxito
        return redirect()->route('estudiantes.index')
            ->with('success', 'Datos del estudiante actualizados correctamente.');
    }

    /**
     * Elimina un estudiante de la base de datos.
     * Previene la eliminación si el estudiante tiene préstamos activos (no devueltos).
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Buscar estudiante
        $estudiante = Estudiante::findOrFail($id);

        // Verificar si tiene préstamos activos (usando la relación definida en el modelo)
        if ($estudiante->prestamos()->where('estado', '!=', 'Devuelto')->exists()) {
            return redirect()->route('estudiantes.index')
                ->with('error', 'No se puede eliminar el estudiante porque tiene préstamos pendientes.');
        }

        // Eliminar estudiante
        $estudiante->delete();

        // Redirigir al índice con mensaje de éxito
        return redirect()->route('estudiantes.index')
            ->with('success', 'Estudiante eliminado con éxito.');
    }
}