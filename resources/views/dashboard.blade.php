@extends('layouts.app')

@section('title', 'Dashboard - VeteHub')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Bienvenido, {{ Auth::user()->name }}</h1>
        @if(Auth::user()->clinic_name)
            <h2 class="text-2xl font-semibold text-blue-600">{{ Auth::user()->clinic_name }}</h2>
        @endif
    </div>

    <!-- Estadísticas rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card Clientes -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total de Clientes</p>
                    <p class="text-3xl font-bold text-blue-600">{{ \App\Models\Client::where('user_id', Auth::id())->count() }}</p>
                </div>
                <div class="bg-blue-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('clients.index') }}" class="text-blue-600 hover:underline">Ver todos los clientes →</a>
            </div>
        </div>

        <!-- Card Mascotas -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total de Mascotas</p>
                    <p class="text-3xl font-bold text-green-600">
                        {{ \App\Models\Pet::whereHas('client', function($q) { $q->where('user_id', Auth::id()); })->count() }}
                    </p>
                </div>
                <div class="bg-green-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('pets.index') }}" class="text-green-600 hover:underline">Ver todas las mascotas →</a>
            </div>
        </div>

        <!-- Card Citas Hoy -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Citas de Hoy</p>
                    <p class="text-3xl font-bold text-purple-600">
                        {{ \App\Models\Appointment::where('user_id', Auth::id())->whereDate('appointment_date', today())->count() }}
                    </p>
                </div>
                <div class="bg-purple-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('appointments.index') }}" class="text-purple-600 hover:underline">Ver agenda completa →</a>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Acciones Rápidas</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('clients.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 text-center transition duration-200">
                + Registrar Nuevo Cliente
            </a>
            <a href="{{ route('pets.create') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 text-center transition duration-200">
                + Registrar Nueva Mascota
            </a>
            <a href="{{ route('appointments.create') }}" class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 text-center transition duration-200">
                + Agendar Nueva Cita
            </a>
        </div>
    </div>
</div>
@endsection
