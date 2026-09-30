<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'autor', 'isbn', 'categoria', 'stock_total', 'observaciones'];

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class, 'libro_id');
    }

    /**
     * Devuelve el color de la categoría para la interfaz visual.
     */
    public function obtenerColorCategoria()
    {
        $colores = [
            'Obras Generales'                       => '#555555',
            'Filosofía y Psicología'                => '#7a5230',
            'Religión'                              => '#9b59b6',
            'Ciencias Sociales'                     => '#3498db',
            'Lenguaje'                              => '#f1c40f',
            'Ciencias Puras'                        => '#2ecc71',
            'Ciencias Aplicadas y Artes Útiles'     => '#1abc9c',
            'Arte y Recreación'                     => '#e67e22',
            'Literatura'                            => '#e74c3c',
            'Historia, Geografía y Biografía'       => '#2c3e50',
        ];
        return $colores[$this->categoria] ?? '#bdc3c7';
    }
}