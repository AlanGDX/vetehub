@extends('layouts.app')

@section('title', 'Registro - VeteHub')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-blue-600">🐾 VeteHub</h1>
            <p class="text-gray-600 mt-2">Sistema de Gestión Veterinaria</p>
        </div>

        <h2 class="text-2xl font-semibold mb-6 text-center">Crear Cuenta</h2>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-2">Nombre Completo</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                    required
                >
            </div>

            <div class="mb-4">
                <label for="clinic_name" class="block text-gray-700 font-medium mb-2">Nombre de la Veterinaria (opcional)</label>
                <input 
                    type="text" 
                    id="clinic_name" 
                    name="clinic_name" 
                    value="{{ old('clinic_name') }}"
                    placeholder="Deja vacío si no tienes veterinaria"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('clinic_name') border-red-500 @enderror"
                >
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-2">Correo Electrónico</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                    required
                >
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium mb-2">Contraseña</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                    required
                >
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">Confirmar Contraseña</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
            </div>

            <button 
                type="submit" 
                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-200"
            >
                Registrarse
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-600">
                ¿Ya tienes una cuenta? 
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Inicia sesión aquí</a>
            </p>
        </div>
    </div>
</div>
@endsection
