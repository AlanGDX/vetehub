@extends('layouts.app')

@section('title', 'Editar Cita - VeteHub')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Editar Cita</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('appointments.update', $appointment) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="client_id" class="block text-gray-700 font-medium mb-2">Cliente *</label>
                <select 
                    id="client_id" 
                    name="client_id" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('client_id') border-red-500 @enderror"
                    required
                >
                    <option value="">Seleccione un cliente</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id', $appointment->client_id) == $client->id ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                    @endforeach
                </select>
                @error('client_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="pet_id" class="block text-gray-700 font-medium mb-2">Mascota *</label>
                <select 
                    id="pet_id" 
                    name="pet_id" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('pet_id') border-red-500 @enderror"
                    required
                >
                    <option value="">Seleccione una mascota</option>
                    @foreach($clients->firstWhere('id', old('client_id', $appointment->client_id))?->pets ?? [] as $pet)
                        <option value="{{ $pet->id }}" {{ old('pet_id', $appointment->pet_id) == $pet->id ? 'selected' : '' }}>
                            {{ $pet->name }} ({{ $pet->species }})
                        </option>
                    @endforeach
                </select>
                @error('pet_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="appointment_date" class="block text-gray-700 font-medium mb-2">Fecha y Hora *</label>
                    <input 
                        type="datetime-local" 
                        id="appointment_date" 
                        name="appointment_date" 
                        value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d\TH:i')) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('appointment_date') border-red-500 @enderror"
                        required
                    >
                    @error('appointment_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="duration" class="block text-gray-700 font-medium mb-2">Duración (minutos) *</label>
                    <select 
                        id="duration" 
                        name="duration" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('duration') border-red-500 @enderror"
                        required
                    >
                        <option value="15" {{ old('duration', $appointment->duration) == 15 ? 'selected' : '' }}>15 min</option>
                        <option value="30" {{ old('duration', $appointment->duration) == 30 ? 'selected' : '' }}>30 min</option>
                        <option value="45" {{ old('duration', $appointment->duration) == 45 ? 'selected' : '' }}>45 min</option>
                        <option value="60" {{ old('duration', $appointment->duration) == 60 ? 'selected' : '' }}>1 hora</option>
                        <option value="90" {{ old('duration', $appointment->duration) == 90 ? 'selected' : '' }}>1.5 horas</option>
                        <option value="120" {{ old('duration', $appointment->duration) == 120 ? 'selected' : '' }}>2 horas</option>
                    </select>
                    @error('duration')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="reason" class="block text-gray-700 font-medium mb-2">Motivo de la Consulta *</label>
                <input 
                    type="text" 
                    id="reason" 
                    name="reason" 
                    value="{{ old('reason', $appointment->reason) }}"
                    placeholder="Ej: Vacunación, Consulta general, Revisión..."
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('reason') border-red-500 @enderror"
                    required
                >
                @error('reason')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="status" class="block text-gray-700 font-medium mb-2">Estado *</label>
                <select 
                    id="status" 
                    name="status" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                    required
                >
                    <option value="pending" {{ old('status', $appointment->status) == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="confirmed" {{ old('status', $appointment->status) == 'confirmed' ? 'selected' : '' }}>Confirmada</option>
                    <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>Completada</option>
                    <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="notes" class="block text-gray-700 font-medium mb-2">Notas</label>
                <textarea 
                    id="notes" 
                    name="notes" 
                    rows="4"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror"
                >{{ old('notes', $appointment->notes) }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('appointments.show', $appointment) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Actualizar Cita
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('client_id').addEventListener('change', function() {
        const clientId = this.value;
        const petSelect = document.getElementById('pet_id');
        
        if (!clientId) {
            petSelect.disabled = true;
            petSelect.innerHTML = '<option value="">Primero seleccione un cliente</option>';
            return;
        }
        
        fetch(`/clients/${clientId}/pets`)
            .then(response => response.json())
            .then(pets => {
                petSelect.disabled = false;
                petSelect.innerHTML = '<option value="">Seleccione una mascota</option>';
                pets.forEach(pet => {
                    const option = document.createElement('option');
                    option.value = pet.id;
                    option.textContent = `${pet.name} (${pet.species})`;
                    petSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                petSelect.disabled = true;
                petSelect.innerHTML = '<option value="">Error al cargar mascotas</option>';
            });
    });
</script>
@endsection
