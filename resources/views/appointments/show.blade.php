@extends('layouts.app')

@section('title', 'Detalles de la Cita - VeteHub')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Detalles de la Cita</h1>
        <div class="space-x-2">
            <a href="{{ route('appointments.edit', $appointment) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Editar
            </a>
            <a href="{{ route('appointments.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">
                Volver
            </a>
        </div>
    </div>

    <!-- Información de la cita -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold mb-2">{{ $appointment->reason }}</h2>
                <span class="px-3 py-1 rounded-full text-sm font-medium
                    @if($appointment->status === 'confirmed') bg-blue-100 text-blue-800
                    @elseif($appointment->status === 'completed') bg-green-100 text-green-800
                    @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800
                    @endif">
                    @if($appointment->status === 'pending') Pendiente
                    @elseif($appointment->status === 'confirmed') Confirmada
                    @elseif($appointment->status === 'completed') Completada
                    @else Cancelada
                    @endif
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-600 text-sm">Fecha y Hora</p>
                <p class="font-medium text-lg">{{ $appointment->appointment_date->format('d/m/Y H:i') }}</p>
            </div>

            <div>
                <p class="text-gray-600 text-sm">Duración</p>
                <p class="font-medium text-lg">{{ $appointment->duration }} minutos</p>
            </div>

            <div>
                <p class="text-gray-600 text-sm">Hora de Finalización Estimada</p>
                <p class="font-medium text-lg">
                    {{ $appointment->appointment_date->copy()->addMinutes($appointment->duration)->format('H:i') }}
                </p>
            </div>

            <div>
                <p class="text-gray-600 text-sm">Registrada el</p>
                <p class="font-medium text-lg">{{ $appointment->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        @if($appointment->notes)
        <div class="mt-6 pt-6 border-t">
            <p class="text-gray-600 text-sm mb-2">Notas</p>
            <p class="text-gray-800">{{ $appointment->notes }}</p>
        </div>
        @endif
    </div>

    <!-- Información de la mascota -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Información de la Mascota</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-gray-600 text-sm">Nombre</p>
                <p class="font-medium">{{ $appointment->pet->name }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Especie</p>
                <p class="font-medium">{{ $appointment->pet->species }}</p>
            </div>
            @if($appointment->pet->breed)
            <div>
                <p class="text-gray-600 text-sm">Raza</p>
                <p class="font-medium">{{ $appointment->pet->breed }}</p>
            </div>
            @endif
            <div>
                <p class="text-gray-600 text-sm">Edad</p>
                <p class="font-medium">{{ $appointment->pet->age }}</p>
            </div>
            @if($appointment->pet->weight)
            <div>
                <p class="text-gray-600 text-sm">Peso</p>
                <p class="font-medium">{{ $appointment->pet->weight }} kg</p>
            </div>
            @endif
        </div>
        <div class="mt-4">
            <a href="{{ route('pets.show', $appointment->pet) }}" class="text-blue-600 hover:underline">
                Ver perfil completo de la mascota →
            </a>
        </div>
    </div>

    <!-- Información del cliente -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Información del Cliente</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-gray-600 text-sm">Nombre</p>
                <p class="font-medium">{{ $appointment->client->name }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Email</p>
                <p class="font-medium">{{ $appointment->client->email }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm">Teléfono</p>
                <p class="font-medium">{{ $appointment->client->phone }}</p>
            </div>
            @if($appointment->client->address)
            <div>
                <p class="text-gray-600 text-sm">Dirección</p>
                <p class="font-medium">{{ $appointment->client->address }}</p>
            </div>
            @endif
        </div>
        <div class="mt-4">
            <a href="{{ route('clients.show', $appointment->client) }}" class="text-blue-600 hover:underline">
                Ver perfil completo del cliente →
            </a>
        </div>
    </div>

    <!-- Botón de eliminar -->
    <div class="mt-6 text-center">
        <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta cita?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">
                Eliminar Cita
            </button>
        </form>
    </div>
</div>
@endsection
