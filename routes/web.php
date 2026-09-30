<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistema de Biblioteca
|--------------------------------------------------------------------------
*/

// ==========================================================
// 1. ACCESO PÚBLICO
// ==========================================================

// Página de inicio del sistema (Landing Page)
Route::get('/', function () {
    return view('welcome');
})->name('inicio');

// Autenticación de la Profesora
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Ruta temporal de mantenimiento (no afecta al sistema)
Route::get('/expirado', function () {
    return view('expirado');
});

// ==========================================================
// 2. PANEL ADMINISTRATIVO (Requiere Inicio de Sesión)
// ==========================================================
Route::middleware(['auth'])->group(function () {

    // Inicio del Panel
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Control de Inventario (Libros) ---
    Route::get('/libros', [LibroController::class, 'index'])->name('libros.index');
    Route::get('/libros/reporte-pdf', [LibroController::class, 'generarPdf'])->name('libros.pdf');
    Route::get('/libros/nuevo', [LibroController::class, 'create'])->name('libros.create');
    Route::post('/libros/guardar', [LibroController::class, 'store'])->name('libros.store');
    Route::get('/libros/{id}/editar', [LibroController::class, 'edit'])->name('libros.edit');
    Route::put('/libros/{id}', [LibroController::class, 'update'])->name('libros.update');
    Route::delete('/libros/{id}', [LibroController::class, 'destroy'])->name('libros.destroy');
    Route::get('/reportes', [LibroController::class, 'reportes'])->name('reportes.index');
    
    // --- Registro de Estudiantes ---
    Route::get('/estudiantes', [EstudianteController::class, 'index'])->name('estudiantes.index');
    Route::get('/estudiantes/nuevo', [EstudianteController::class, 'create'])->name('estudiantes.create');
    Route::post('/estudiantes/guardar', [EstudianteController::class, 'store'])->name('estudiantes.store'); 
    Route::get('/estudiantes/{id}/editar', [EstudianteController::class, 'edit'])->name('estudiantes.edit');
    Route::put('/estudiantes/{id}', [EstudianteController::class, 'update'])->name('estudiantes.update');
    Route::delete('/estudiantes/{id}', [EstudianteController::class, 'destroy'])->name('estudiantes.destroy');

    // --- Gestión de Préstamos ---
    Route::get('/prestamos', [PrestamoController::class, 'index'])->name('prestamos.index');
    Route::get('/prestamos/{id}/ticket', [PrestamoController::class, 'comprobante'])->name('prestamos.pdf');
    Route::get('/prestamos/nuevo', [PrestamoController::class, 'create'])->name('prestamos.create');
    Route::post('/prestamos/guardar', [PrestamoController::class, 'store'])->name('prestamos.store');
    Route::post('/prestamos/{id}/devolver', [PrestamoController::class, 'devolver'])->name('prestamos.devolver');
    Route::post('/prestamos/{id}/extender', [PrestamoController::class, 'extender'])->name('prestamos.extender');
    
});