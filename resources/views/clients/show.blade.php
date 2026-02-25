@extends('layouts.app')

@section('title', 'Cliente: ' . $client->name . ' - VeteHub')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Detalles del Cliente</h1>
        <div class="space-x-2">
            <a href="{{ route('clients.edit', $client) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Editar
            </a>
            <a href="{{ route('clients.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
                Volver
            </a>
        </div>
    </div>

    <!-- Información del cliente -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Información Personal</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-gray-600 text-sm">Nombre</p>
                <p class="font-medium">{{ $client->name }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Email</p>
                <p class="font-medium">{{ $client->email }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Teléfono</p>
                <p class="font-medium">{{ $client->phone }}</p>
            </div>
            @if($client->address)
            <div>
                <p class="text-gray-600 text-sm">Dirección</p>
                <p class="font-medium">{{ $client->address }}</p>
            </div>
            @endif
            @if($client->city)
            <div>
                <p class="text-gray-600 text-sm">Ciudad</p>
                <p class="font-medium">{{ $client->city }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Mascotas del cliente -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Mascotas ({{ $client->pets->count() }})</h2>
            <a href="{{ route('pets.create', ['client_id' => $client->id]) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                + Agregar Mascota
            </a>
        </div>

        @if($client->pets->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($client->pets as $pet)
            <div class="border rounded-lg p-4 hover:shadow-lg transition">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-lg">{{ $pet->name }}</h3>
                    <span class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $pet->species }}</span>
                </div>
                @if($pet->breed)
                <p class="text-gray-600 text-sm mb-1">Raza: {{ $pet->breed }}</p>
                @endif
                @if($pet->birth_date)
                <p class="text-gray-600 text-sm mb-1">Nacimiento: {{ $pet->birth_date->format('d/m/Y') }}</p>
                @endif
                <p class="text-gray-600 text-sm mb-1">Edad: {{ $pet->age }}</p>
                @if($pet->gender)
                <p class="text-gray-600 text-sm mb-1">Sexo: {{ $pet->gender === 'male' ? 'Macho' : 'Hembra' }}</p>
                @endif
                @if($pet->weight)
                <p class="text-gray-600 text-sm mb-3">Peso: {{ $pet->weight }} kg</p>
                @endif
                <a href="{{ route('pets.show', $pet) }}" class="text-blue-600 hover:underline text-sm">
                    Ver detalles →
                </a>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-600 text-center py-4">Este cliente no tiene mascotas registradas.</p>
        @endif
    </div>

    <!-- Botón de eliminar -->
    <div class="mt-6 text-center">
        <form action="{{ route('clients.destroy', $client) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este cliente? Se eliminarán también todas sus mascotas.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">
                Eliminar Cliente
            </button>
        </form>
    </div>
</div>
@endsection
