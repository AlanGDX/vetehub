@extends('layouts.app')

@section('title', 'Editar Mascota - VeteHub')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Editar Mascota</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('pets.update', $pet) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                @php
                    $selectedClient = $clients->firstWhere('id', old('client_id', $pet->client_id));
                    $selectedLabel = $selectedClient ? ($selectedClient->name . ' - ' . $selectedClient->email) : old('client_search');
                @endphp
                <label for="client_search" class="block text-gray-700 font-medium mb-2">Cliente (Dueño) *</label>
                <div class="relative">
                    <input
                        id="client_search"
                        name="client_search"
                        type="text"
                        value="{{ $selectedLabel }}"
                        placeholder="Escribe el nombre o correo"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('client_id') border-red-500 @enderror"
                        required
                        autocomplete="off"
                    >
                    <div id="client-suggestions" class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg hidden"></div>
                </div>
                <input type="hidden" id="client_id" name="client_id" value="{{ old('client_id', $pet->client_id) }}">
                <p class="text-xs text-gray-500 mt-2" id="client-helper">Selecciona un cliente de las sugerencias.</p>
                @error('client_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-2">Nombre de la Mascota *</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $pet->name) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('name') border-red-500 @enderror"
                    required
                >
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="species" class="block text-gray-700 font-medium mb-2">Especie *</label>
                <input 
                    type="text" 
                    id="species" 
                    name="species" 
                    value="{{ old('species', $pet->species) }}"
                    placeholder="Ej: Perro, Gato, Conejo, etc."
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('species') border-red-500 @enderror"
                    required
                >
                @error('species')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="breed" class="block text-gray-700 font-medium mb-2">Raza</label>
                <input 
                    type="text" 
                    id="breed" 
                    name="breed" 
                    value="{{ old('breed', $pet->breed) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('breed') border-red-500 @enderror"
                >
                @error('breed')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="birth_date" class="block text-gray-700 font-medium mb-2">Fecha de Nacimiento</label>
                    <input 
                        type="date" 
                        id="birth_date" 
                        name="birth_date" 
                        value="{{ old('birth_date', $pet->birth_date?->format('Y-m-d')) }}"
                        max="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('birth_date') border-red-500 @enderror"
                    >
                    @error('birth_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="gender" class="block text-gray-700 font-medium mb-2">Sexo</label>
                    <select 
                        id="gender" 
                        name="gender" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('gender') border-red-500 @enderror"
                    >
                        <option value="">Seleccione</option>
                        <option value="male" {{ old('gender', $pet->gender) == 'male' ? 'selected' : '' }}>Macho</option>
                        <option value="female" {{ old('gender', $pet->gender) == 'female' ? 'selected' : '' }}>Hembra</option>
                    </select>
                    @error('gender')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="color" class="block text-gray-700 font-medium mb-2">Color</label>
                    <input 
                        type="text" 
                        id="color" 
                        name="color" 
                        value="{{ old('color', $pet->color) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('color') border-red-500 @enderror"
                    >
                    @error('color')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="weight" class="block text-gray-700 font-medium mb-2">Peso (kg)</label>
                    <input 
                        type="number" 
                        id="weight" 
                        name="weight" 
                        value="{{ old('weight', $pet->weight) }}"
                        step="0.01"
                        min="0"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('weight') border-red-500 @enderror"
                    >
                    @error('weight')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="medical_notes" class="block text-gray-700 font-medium mb-2">Notas Médicas</label>
                <textarea 
                    id="medical_notes" 
                    name="medical_notes" 
                    rows="4"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('medical_notes') border-red-500 @enderror"
                >{{ old('medical_notes', $pet->medical_notes) }}</textarea>
                @error('medical_notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('pets.show', $pet) }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                    Cancelar
                </a>
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                    Actualizar Mascota
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        const input = document.getElementById('client_search');
        const hidden = document.getElementById('client_id');
        const helper = document.getElementById('client-helper');
        const suggestions = document.getElementById('client-suggestions');
        const clients = @json($clients->map(fn ($client) => [
            'id' => $client->id,
            'label' => $client->name . ' - ' . $client->email,
        ]));

        if (!input || !hidden || !suggestions) {
            return;
        }

        const renderSuggestions = (items) => {
            suggestions.innerHTML = '';
            if (items.length === 0) {
                suggestions.classList.add('hidden');
                return;
            }
            items.forEach((item) => {
                const option = document.createElement('button');
                option.type = 'button';
                option.className = 'w-full text-left px-4 py-2 text-sm hover:bg-blue-50';
                option.textContent = item.label;
                option.addEventListener('click', () => {
                    input.value = item.label;
                    hidden.value = item.id;
                    suggestions.classList.add('hidden');
                    if (helper) {
                        helper.textContent = 'Cliente seleccionado.';
                    }
                });
                suggestions.appendChild(option);
            });
            suggestions.classList.remove('hidden');
        };

        const syncClient = () => {
            const query = input.value.trim().toLowerCase();
            if (query.length === 0) {
                hidden.value = '';
                suggestions.classList.add('hidden');
                if (helper) {
                    helper.textContent = 'Selecciona un cliente de las sugerencias.';
                }
                return;
            }

            const match = clients.find((client) => client.label === input.value);
            if (match) {
                hidden.value = match.id;
                if (helper) {
                    helper.textContent = 'Cliente seleccionado.';
                }
            } else {
                hidden.value = '';
                if (helper) {
                    helper.textContent = 'Selecciona un cliente de las sugerencias.';
                }
            }

            const filtered = clients
                .filter((client) => client.label.toLowerCase().includes(query))
                .slice(0, 5);
            renderSuggestions(filtered);
        };

        input.addEventListener('input', syncClient);
        input.addEventListener('focus', syncClient);
        input.addEventListener('blur', () => {
            setTimeout(() => suggestions.classList.add('hidden'), 120);
        });
        syncClient();
    })();
</script>
@endsection
