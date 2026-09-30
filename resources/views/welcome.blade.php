<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibrarySys | Gestión Bibliotecaria</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #020617; }
        .glass-card {
            background-color: rgba(30, 41, 59, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            transition: all 0.4s ease;
        }
        .glass-card:hover {
            background-color: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(59, 130, 246, 0.3);
            transform: translateY(-8px);
        }
        .text-gradient {
            background: linear-gradient(to bottom, #ffffff 0%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .glow-effect {
            position: absolute;
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.15) 0%, transparent 70%);
            z-index: -1;
        }
    </style>
</head>
<body class="text-slate-200 min-h-screen flex flex-col selection:bg-blue-500/30">

    <!-- Navbar -->
    <nav class="w-full py-6 px-10 flex justify-between items-center z-50">
        <div class="flex items-center gap-3">
            <span class="text-xl font-bold tracking-widest text-white">SISTEMA BIBLIOTECARIO</span>
        </div>
        <div>
            @auth
                <a href="{{ route('dashboard') }}" class="text-xs font-bold text-blue-400 hover:text-white transition-colors tracking-widest uppercase">Panel</a>
            @else
                <a href="{{ route('login') }}" class="border border-white/10 bg-white/5 hover:bg-white/10 px-6 py-2 rounded-full text-xs font-bold text-white transition-all duration-300">
                    Ingresar
                </a>
            @endauth
        </div>
    </nav>

    <main class="flex-grow flex items-center justify-center px-6 py-12">
        <div class="max-w-5xl w-full text-center relative">
            <div class="glow-effect"></div>
            
            <!-- Hero Content -->
            <div class="mb-16">
                <!-- Estrella restaurada con animación -->
                <div class="mb-8 flex justify-center">
                    <div class="text-yellow-400 text-5xl animate-pulse filter drop-shadow-[0_0_15px_rgba(250,204,21,0.5)]">
                        ⭐
                    </div>
                </div>

                <h1 class="text-5xl md:text-7xl font-bold text-gradient mb-6 tracking-tight">
                    Gestión Inteligente de Recursos
                </h1>
                <p class="text-lg md:text-xl text-slate-400 max-w-xl mx-auto leading-relaxed font-light">
                    Plataforma profesional diseñada para la optimización total de flujos bibliotecarios y control de activos.
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid md:grid-cols-3 gap-6">
                <div class="glass-card p-8 rounded-2xl text-left">
                    <div class="text-blue-400 text-2xl mb-4">📖</div>
                    <h3 class="font-semibold text-white mb-2">Control Interno</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">Inventario digitalizado con validación precisa de datos.</p>
                </div>

                <div class="glass-card p-8 rounded-2xl text-left">
                    <div class="text-emerald-400 text-2xl mb-4">🔄</div>
                    <h3 class="font-semibold text-white mb-2">Préstamos</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">Gestión automatizada de tiempos, estados y disponibilidad.</p>
                </div>

                <div class="glass-card p-8 rounded-2xl text-left">
                    <div class="text-purple-400 text-2xl mb-4">📊</div>
                    <h3 class="font-semibold text-white mb-2">Reportes PDF</h3>
                    <p class="text-sm text-slate-400 leading-relaxed">Estadísticas detalladas y comprobantes generados al instante.</p>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-10 text-center">
        <p class="text-slate-600 text-xs tracking-widest uppercase">
            Sistema de Gestión Bibliotecaria &copy; 2026
        </p>
    </footer>

</body>
</html>