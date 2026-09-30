<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Préstamo</title>
    <style>
        @page {
            size: 80mm 130mm;
            margin: 0.3cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9px;
            line-height: 1.3;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .ticket {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 8px;
            background: #ffffff;
            height: auto;
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #3b82f6;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }
        .header h1 {
            font-size: 12px;
            margin: 0;
            color: #0f172a;
            text-transform: uppercase;
        }
        .header p {
            font-size: 8px;
            margin: 2px 0 0;
            color: #475569;
        }
        .info-row {
            margin-bottom: 5px;
            border-bottom: 0.5px dotted #e2e8f0;
            padding-bottom: 2px;
        }
        .label {
            font-weight: bold;
            font-size: 8px;
            text-transform: uppercase;
            color: #3b82f6;
        }
        .value {
            font-size: 9px;
            font-weight: 500;
            margin-top: 1px;
            color: #0f172a;
        }
        .notice {
            background: #f0f9ff;
            padding: 4px;
            text-align: center;
            border-radius: 4px;
            margin: 8px 0;
            font-size: 8px;
            color: #0369a1;
        }
        .signature {
            margin-top: 8px;
            text-align: center;
            font-size: 8px;
        }
        .footer {
            text-align: center;
            margin-top: 8px;
            font-size: 7px;
            color: #64748b;
            border-top: 0.5px dashed #cbd5e1;
            padding-top: 4px;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <h1>COMPROBANTE DE PRÉSTAMO</h1>
            <p>ID: #{{ str_pad($prestamo->id, 5, '0', STR_PAD_LEFT) }}</p>
        </div>

        <div class="info-row">
            <div class="label">ESTUDIANTE</div>
            <div class="value">{{ $prestamo->estudiante->nombre_completo }}</div>
        </div>
        <div class="info-row">
            <div class="label">CÉDULA</div>
            <div class="value">{{ $prestamo->estudiante->cedula }}</div>
        </div>
        <div class="info-row">
            <div class="label">GRADO / AÑO</div>
            <div class="value">{{ $prestamo->estudiante->grado }}</div>
        </div>
        <div class="info-row">
            <div class="label">DOCENTE GUÍA</div>
            <div class="value">{{ $prestamo->estudiante->docente_guia ?? 'Sin asignar' }}</div>
        </div>
        <div class="info-row">
            <div class="label">LIBRO</div>
            <div class="value">{{ $prestamo->libro->titulo }}</div>
        </div>
        <div class="info-row">
            <div class="label">AUTOR</div>
            <div class="value">{{ $prestamo->libro->autor }}</div>
        </div>
        <div class="info-row">
            <div class="label">FECHA SALIDA</div>
            <div class="value">{{ date('d/m/Y', strtotime($prestamo->fecha_prestamo)) }}</div>
        </div>
        <div class="info-row">
            <div class="label" style="color: #dc2626;">FECHA DEVOLUCIÓN</div>
            <div class="value" style="font-weight: bold;">{{ date('d/m/Y', strtotime($prestamo->fecha_devolucion)) }}</div>
        </div>

        <div class="notice">
            Gracias por usar nuestra biblioteca.<br>
            Recuerde devolver el libro en la fecha indicada.
        </div>

        <div class="signature">
            __________________________<br>
            Firma del Estudiante
        </div>

        <div class="footer">
            {{ date('d/m/Y H:i') }} | *{{ str_pad($prestamo->id, 5, '0', STR_PAD_LEFT) }}*
        </div>
    </div>
</body>
</html>