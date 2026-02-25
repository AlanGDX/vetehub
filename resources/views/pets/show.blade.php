@extends('layouts.app')

@section('title', 'Mascota: ' . $pet->name . ' - VeteHub')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Detalles de la Mascota</h1>
        <div class="space-x-2">
            <a href="{{ route('pets.edit', $pet) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                Editar
            </a>
            <a href="{{ route('pets.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
                Volver
            </a>
        </div>
    </div>

    <!-- Información de la mascota -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold mb-2">{{ $pet->name }}</h2>
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $pet->species }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($pet->breed)
            <div>
                <p class="text-gray-600 text-sm">Raza</p>
                <p class="font-medium text-lg">{{ $pet->breed }}</p>
            </div>
            @endif

            @if($pet->birth_date)
            <div>
                <p class="text-gray-600 text-sm">Fecha de Nacimiento</p>
                <p class="font-medium text-lg">{{ $pet->birth_date->format('d/m/Y') }}</p>
            </div>
            @endif

            <div>
                <p class="text-gray-600 text-sm">Edad</p>
                <p class="font-medium text-lg">{{ $pet->age }}</p>
            </div>

            @if($pet->gender)
            <div>
                <p class="text-gray-600 text-sm">Sexo</p>
                <p class="font-medium text-lg">{{ $pet->gender === 'male' ? 'Macho' : 'Hembra' }}</p>
            </div>
            @endif

            @if($pet->color)
            <div>
                <p class="text-gray-600 text-sm">Color</p>
                <p class="font-medium text-lg">{{ $pet->color }}</p>
            </div>
            @endif

            @if($pet->weight)
            <div>
                <p class="text-gray-600 text-sm">Peso</p>
                <p class="font-medium text-lg">{{ $pet->weight }} kg</p>
            </div>
            @endif

            <div>
                <p class="text-gray-600 text-sm">Registrado el</p>
                <p class="font-medium text-lg">{{ $pet->created_at->format('d/m/Y') }}</p>
            </div>
        </div>

        @if($pet->medical_notes)
        <div class="mt-6 pt-6 border-t">
            <p class="text-gray-600 text-sm mb-2">Notas Médicas</p>
            <p class="text-gray-800">{{ $pet->medical_notes }}</p>
        </div>
        @endif
    </div>

    <!-- Información del dueño -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Información del Dueño</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-gray-600 text-sm">Nombre</p>
                <p class="font-medium">{{ $pet->client->name }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Email</p>
                <p class="font-medium">{{ $pet->client->email }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Teléfono</p>
                <p class="font-medium">{{ $pet->client->phone }}</p>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('clients.show', $pet->client) }}" class="text-blue-600 hover:underline">
                Ver perfil completo del cliente →
            </a>
        </div>
    </div>

    <!-- Botón de eliminar -->
    <div class="mt-6 text-center">
        <form action="{{ route('pets.destroy', $pet) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta mascota?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">
                Eliminar Mascota
            </button>
        </form>
    </div>
</div>
@endsection
