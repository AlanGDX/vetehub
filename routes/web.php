<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AppointmentController;

// Ruta de inicio - redirige al login o dashboard según autenticación
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
})->name('home');

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
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
});
