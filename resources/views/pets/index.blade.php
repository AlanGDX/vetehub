@extends('layouts.app')

@section('title', 'Mascotas - VeteHub')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Mascotas</h1>
        <a href="{{ route('pets.create') }}" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
            + Nueva Mascota
        </a>
    </div>

    @if($pets->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($pets as $pet)
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
            <div class="flex justify-between items-start mb-3">
                <h3 class="text-xl font-bold">{{ $pet->name }}</h3>
                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $pet->species }}</span>
            </div>

            <div class="space-y-2 mb-4">
                @if($pet->breed)
                <p class="text-gray-600 text-sm">
                    <span class="font-medium">Raza:</span> {{ $pet->breed }}
                </p>
                @endif
                @if($pet->birth_date)
                <p class="text-gray-600 text-sm">
                    <span class="font-medium">Nacimiento:</span> {{ $pet->birth_date->format('d/m/Y') }}
                </p>
                @endif
                <p class="text-gray-600 text-sm">
                    <span class="font-medium">Edad:</span> {{ $pet->age }}
                </p>
                @if($pet->gender)
                <p class="text-gray-600 text-sm">
                    <span class="font-medium">Sexo:</span> {{ $pet->gender === 'male' ? 'Macho' : 'Hembra' }}
                </p>
                @endif
                @if($pet->weight)
                <p class="text-gray-600 text-sm">
                    <span class="font-medium">Peso:</span> {{ $pet->weight }} kg
                </p>
                @endif
                <p class="text-gray-600 text-sm">
                    <span class="font-medium">Dueño:</span> {{ $pet->client->name }}
                </p>
            </div>

            <div class="flex justify-between items-center pt-4 border-t">
                <a href="{{ route('pets.show', $pet) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                    Ver detalles →
                </a>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('pets.edit', $pet) }}" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                    <form action="{{ route('pets.destroy', $pet) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta mascota?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $pets->links() }}
    </div>
    @else
    <div class="bg-white rounded-lg shadow p-8 text-center">
        <p class="text-gray-600 mb-4">No hay mascotas registradas aún.</p>
        <a href="{{ route('pets.create') }}" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 inline-block">
            + Registrar Primera Mascota
        </a>
    </div>
    @endif
</div>
@endsection
