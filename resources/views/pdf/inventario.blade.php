<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario de Biblioteca</title>
    <style>
        @page {
            margin: 1.2cm;
            header: page-header;
            footer: page-footer;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1e293b;
            line-height: 1.4;
            font-size: 11px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 8px;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
            color: #0f172a;
            text-transform: uppercase;
        }
        .header p {
            font-size: 10px;
            color: #475569;
            margin: 4px 0 0;
        }
        .meta {
            text-align: right;
            font-size: 9px;
            margin-bottom: 15px;
            color: #475569;
        }
        .category-banner {
            padding: 6px 12px;
            margin: 15px 0 8px 0;
            border-left: 4px solid;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
            color: white;
            background-color: #334155; /* fallback */
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .main-table th {
            background-color: #0f172a;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }
        .main-table td {
            border-bottom: 1px solid #e2e8f0;
            padding: 8px;
            vertical-align: top;
        }
        .badge {
            font-size: 8px;
            padding: 2px 5px;
            border-radius: 12px;
            display: inline-block;
            font-weight: bold;
        }
        .badge-available {
            background-color: #dcfce7;
            color: #166534;
        }
        .badge-unavailable {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }
        .page-number:before {
            content: "Página " counter(page);
        }
        /* Columna de color de categoría (similar a la vista web) */
        .category-color {
            width: 4px;
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>INVENTARIO DE BIBLIOTECA</h1>
    <p>Control de existencias por Clasificación Dewey</p>
</div>

<div class="meta">
    <strong>Fecha:</strong> {{ date('d/m/Y') }}<br>
    <strong>Generado por:</strong> Sistema Administrativo
</div>

@php
    // Función para obtener el color de la categoría (igual que en el modelo Libro)
    function obtenerColorCategoriaPDF($categoria) {
        $colores = [
            'OBRAS GENERALES'      => '#555555',
            'FILOSOFÍA Y PSICOLOGÍA' => '#7a5230',
            'RELIGIÓN'            => '#9b59b6',
            'CIENCIAS SOCIALES'   => '#3498db',
            'LENGUAJE'            => '#f1c40f',
            'CIENCIAS PURAS'      => '#2ecc71',
            'CIENCIAS APLICADAS'  => '#1abc9c',
            'ARTE Y RECREACIÓN'   => '#e67e22',
            'LITERATURA'          => '#e74c3c',
            'HISTORIA Y GEOGRAFÍA'=> '#2c3e50',
        ];
        $upper = strtoupper($categoria);
        // Mapeo flexible para coincidir con los valores que llegan
        if (strpos($upper, 'OBRAS GENERALES') !== false) return '#555555';
        if (strpos($upper, 'FILOSOF') !== false) return '#7a5230';
        if (strpos($upper, 'RELIGI') !== false) return '#9b59b6';
        if (strpos($upper, 'CIENCIAS SOCIALES') !== false) return '#3498db';
        if (strpos($upper, 'LENGUAJE') !== false) return '#f1c40f';
        if (strpos($upper, 'CIENCIAS PURAS') !== false) return '#2ecc71';
        if (strpos($upper, 'CIENCIAS APLICADAS') !== false) return '#1abc9c';
        if (strpos($upper, 'ARTE') !== false) return '#e67e22';
        if (strpos($upper, 'LITERATURA') !== false) return '#e74c3c';
        if (strpos($upper, 'HISTORIA') !== false) return '#2c3e50';
        return '#bdc3c7';
    }
@endphp

@php $categoriaActual = ''; @endphp

@foreach($libros->sortBy('categoria') as $libro)
    @if($categoriaActual != $libro->categoria)
        @if($categoriaActual != '')
            </tbody>
            </table>
        @endif

        @php
            $colorCat = obtenerColorCategoriaPDF($libro->categoria);
        @endphp
        <div class="category-banner" style="background-color: {{ $colorCat }}; border-left-color: {{ $colorCat }};">
            {{ $libro->categoria ?? 'General' }}
        </div>

        <table class="main-table">
            <thead>
                <tr>
                    <th style="width: 4px;"></th>
                    <th width="44%">Título</th>
                    <th width="25%">Autor</th>
                    <th width="15%">ISBN</th>
                    <th width="12%">Disponibles</th>
                </tr>
            </thead>
            <tbody>
        @php $categoriaActual = $libro->categoria; @endphp
    @endif

    @php
        $colorFila = obtenerColorCategoriaPDF($libro->categoria);
    @endphp
    <tr>
        <td class="category-color" style="background-color: {{ $colorFila }};"></td>
        <td><strong>{{ $libro->titulo }}</strong></td>
        <td>{{ $libro->autor }}</td>
        <td>{{ $libro->isbn ?? '—' }}</td>
        <td>
            @if($libro->stock_total > 0)
                <span class="badge badge-available">{{ $libro->stock_total }} unidades</span>
            @else
                <span class="badge badge-unavailable">0 (agotado)</span>
            @endif
        </td>
    </tr>
@endforeach

@if($categoriaActual != '')
    </tbody>
    </table>
@endif

<div class="footer">
    Documento generado automáticamente - {{ date('d/m/Y H:i') }} | Página <span class="page-number"></span>
</div>

</body>
</html>