<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    // Campos asignables de forma masiva
    protected $fillable = [
        'nombre_completo',
        'cedula',
        'grado',
        'docente_guia'
    ];

    /**
     * Relación: un estudiante tiene muchos préstamos.
     */
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'estudiante_id');
    }
}