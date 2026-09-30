<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;

    // Campos asignables de forma masiva
    protected $fillable = [
        'estudiante_id',
        'libro_id',
        'fecha_prestamo',
        'fecha_devolucion',
        'estado'
    ];

    /**
     * Relación con el libro que fue prestado.
     */
    public function libro()
    {
        return $this->belongsTo(Libro::class, 'libro_id');
    }

    /**
     * Relación con el estudiante que realizó el préstamo.
     */
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'estudiante_id');
    }
}