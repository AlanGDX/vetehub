@extends('layouts.app')

@section('title', 'Iniciar Sesión - VeteHub')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-blue-600">🐾 VeteHub</h1>
            <p class="text-gray-600 mt-2">Sistema de Gestión Veterinaria</p>
        </div>

        <h2 class="text-2xl font-semibold mb-6 text-center">Iniciar Sesión</h2>

        <form action="{{ route('login') }}" method="POST">
            @csrf

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
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="mr-2">
                    <span class="text-gray-700">Recordarme</span>
                </label>
            </div>

            <button 
                type="submit" 
                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-200"
            >
                Iniciar Sesión
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-600">
                ¿No tienes una cuenta? 
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Regístrate aquí</a>
            </p>
        </div>
    </div>
</div>
@endsection
