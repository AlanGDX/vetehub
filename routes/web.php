<?php

use Illuminate\Support\Facades\Route;
use App\Services\JwtService;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SalesReportController;

// Ruta de inicio - redirige al login o dashboard según autenticación
Route::get('/', function () {
    return app(JwtService::class)->userFromRequest(request())
        ? redirect()->route('dashboard')
        : redirect()->route('login');
})->name('home');

// Rutas de autenticación
Route::middleware('jwt.guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('jwt.auth')->name('logout');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('jwt.auth')->name('dashboard');

// Rutas protegidas por autenticación
Route::middleware('jwt.auth')->group(function () {
    // Perfil de usuario
    Route::get('/profile', [AuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    
    // Rutas de clientes
    Route::resource('clients', ClientController::class);
    
    // Rutas de mascotas
    Route::resource('pets', PetController::class);
    
    // Rutas de citas
    Route::resource('appointments', AppointmentController::class);
    Route::get('/clients/{client}/pets', [AppointmentController::class, 'getPets'])->name('clients.pets');
    
    // Rutas de reportes de citas
    Route::get('/appointments-report', [AppointmentController::class, 'showReportForm'])->name('appointments.report');
    Route::post('/appointments-report/generate', [AppointmentController::class, 'generateReport'])->name('appointments.report.generate');

    // Rutas de reportes de ventas
    Route::get('/sales-report', [SalesReportController::class, 'showReportForm'])->name('sales.report');
    Route::post('/sales-report/generate', [SalesReportController::class, 'generateReport'])->name('sales.report.generate');

    // Registro de ventas
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
});
