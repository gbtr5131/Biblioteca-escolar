<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca Escolar | Gestión</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        :root {
            --bg-pagina: #f8fafc;
            --bg-oscuro: #0f172a;
            --bg-card: #1e293b;
            --texto-claro: #ffffff;
            --transition-default: all 0.25s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--bg-pagina) !important;
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            overflow-x: hidden;
        }

        /* ===== ANIMACIONES GLOBALES ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fade-up {
            animation: fadeInUp 0.5s ease forwards;
        }

        .animate-fade {
            animation: fadeIn 0.4s ease forwards;
        }

        .animate-scale {
            animation: scaleIn 0.3s ease forwards;
        }

        /* Retardos */
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }

        /* Navbar mejorada */
        .navbar {
            background-color: var(--bg-oscuro) !important;
            padding: 0.8rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(4px);
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
            font-weight: 500;
            transition: var(--transition-default);
        }
        .nav-link {
            position: relative;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #60a5fa;
            transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }
        .nav-link:hover {
            color: #60a5fa !important;
        }

        /* Tarjetas modernas */
        .card {
            background-color: var(--bg-card) !important;
            color: var(--texto-claro) !important;
            border-radius: 16px !important;
            border: none !important;
            transition: var(--transition-default);
            overflow: hidden;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.25) !important;
        }
        .card-header {
            background-color: #334155 !important;
            border-bottom: none !important;
            padding: 1rem 1.25rem;
            font-weight: 600;
        }

        /* Tablas elegantes */
        .table {
            color: #e2e8f0 !important;
            background-color: #0f172a !important;
            border-radius: 12px;
            overflow: hidden;
        }
        .table thead th {
            background-color: #1e293b;
            color: #cbd5e1;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-bottom: none;
        }
        .table tbody tr {
            transition: background-color 0.2s, transform 0.1s;
            border-bottom: 1px solid #334155;
        }
        .table tbody tr:hover {
            background-color: #1e293b !important;
            transform: scale(1.01);
        }

        /* Formularios modernos */
        .form-control, .form-select {
            background-color: #1e293b !important;
            color: #ffffff !important;
            border: 1px solid #475569 !important;
            border-radius: 12px !important;
            padding: 0.6rem 1rem;
            transition: var(--transition-default);
        }
        .form-control:focus, .form-select:focus {
            border-color: #60a5fa !important;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.2) !important;
            outline: none;
        }
        .form-control::placeholder {
            color: #94a3b8 !important;
        }
        label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #cbd5e1;
        }

        /* Botones con efecto */
        .btn {
            border-radius: 40px !important;
            padding: 0.5rem 1.25rem;
            font-weight: 500;
            transition: var(--transition-default);
            position: relative;
            overflow: hidden;
        }
        .btn-primary {
            background-color: #3b82f6 !important;
            border: none !important;
        }
        .btn-primary:hover {
            background-color: #2563eb !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 12px rgba(59,130,246,0.3);
        }
        .btn-success {
            background-color: #10b981 !important;
            border: none !important;
        }
        .btn-success:hover {
            background-color: #059669 !important;
            transform: translateY(-2px);
        }
        .btn-outline-success {
            border: 1px solid #10b981 !important;
            color: #10b981 !important;
            background: transparent;
        }
        .btn-outline-success:hover {
            background-color: #10b981 !important;
            color: white !important;
            transform: translateY(-2px);
        }
        .btn-warning {
            background-color: #f59e0b !important;
            border: none !important;
            color: #1e293b !important;
        }
        .btn-danger {
            background-color: #ef4444 !important;
            border: none !important;
        }

        /* Badges animados */
        .badge {
            border-radius: 30px !important;
            padding: 0.35rem 0.75rem;
            font-weight: 500;
            transition: var(--transition-default);
        }
        .badge:hover {
            transform: scale(1.05);
        }

        /* Spinner personalizado */
        @keyframes spinSlow {
            to { transform: rotate(360deg); }
        }
        .spinner-border {
            animation: spinSlow 0.8s linear infinite;
        }

        /* Contenido principal animado */
        .main-content {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        /* Scrollbar personalizada (opcional) */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #0f172a;
        }
        ::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #60a5fa;
        }

        /* Estadísticas Dashboard */
        .display-stats {
            font-size: calc(1.8rem + 1.5vw);
            font-weight: 800;
            margin: 10px 0;
            background: linear-gradient(135deg, #fff 0%, #94a3b8 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .card-estudiantes {
            background: linear-gradient(145deg, #334155, #1e293b) !important;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <i class="bi bi-journal-bookmark-fill me-2"></i>BIBLIOTECA
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-3 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-1"></i>Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('libros.index') }}"><i class="bi bi-book me-1"></i>Libros</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('estudiantes.index') }}"><i class="bi bi-people me-1"></i>Estudiantes</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('prestamos.index') }}"><i class="bi bi-arrow-left-right me-1"></i>Préstamos</a></li>
                    <li class="nav-item">
                        <a class="nav-link text-info fw-bold" href="{{ route('reportes.index') }}">
                            <i class="bi bi-bar-chart-steps me-1"></i>Estadísticas
                        </a>
                    </li>
                </ul>
                <div class="d-grid d-lg-flex align-items-center gap-2">
                    <a href="{{ route('prestamos.create') }}" class="btn btn-success fw-bold">
                        <i class="bi bi-plus-lg me-1"></i>NUEVO PRÉSTAMO
                    </a>
                    @auth
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-box-arrow-right me-1"></i>Salir
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="container px-3 mt-4 mb-5 main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2-buscable').select2({
                placeholder: "Seleccione una opción...",
                allowClear: true,
                width: '100%'
            });
        });

        // Confirmación elegante para eliminación
        document.querySelectorAll('.form-eliminar').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Esta acción no se puede deshacer",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) this.submit();
                });
            });
        });

        // Toast de éxito
        @if(session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000,
                background: '#1e293b',
                color: '#fff'
            });
        @endif
    </script>
</body>
</html>