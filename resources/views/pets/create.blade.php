@extends('layouts.app')

@section('title', 'Nueva Mascota - VeteHub')

@section('content')
<div class="max-w-2xl mx-auto" x-data="{ showClientModal: {{ $errors->has('quickClient') ? 'true' : 'false' }} }">
    <h1 class="text-3xl font-bold mb-6">Registrar Nueva Mascota</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('pets.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                @php
                    $selectedClient = $clients->firstWhere('id', old('client_id', $selectedClientId ?? null));
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
                <input type="hidden" id="client_id" name="client_id" value="{{ old('client_id', $selectedClientId ?? '') }}">
                <p class="text-xs text-gray-500 mt-2" id="client-helper">Selecciona un cliente de las sugerencias.</p>
                @error('client_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                @if($clients->count() === 0)
                    <div class="mt-3 bg-yellow-100 border border-yellow-300 text-yellow-800 px-3 py-2 rounded">
                        No hay clientes registrados. Usa "Registrar cliente nuevo" para continuar.
                    </div>
                @endif
                <button type="button" class="mt-3 text-sm text-blue-600 hover:underline" @click="showClientModal = true">
                    + Registrar cliente nuevo
                </button>
            </div>

            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-2">Nombre de la Mascota *</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
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
                    value="{{ old('species') }}"
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
                    value="{{ old('breed') }}"
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
                        value="{{ old('birth_date') }}"
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
                        <option value="" disabled hidden {{ old('gender') ? '' : 'selected' }}>Seleccione sexo</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Macho</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Hembra</option>
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
                        value="{{ old('color') }}"
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
                        value="{{ old('weight') }}"
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
                >{{ old('medical_notes') }}</textarea>
                @error('medical_notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                @php
                    $backClientId = old('client_id', $selectedClientId ?? null);
                @endphp
                <a href="{{ $backClientId ? route('clients.show', $backClientId) : route('pets.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                    Cancelar
                </a>
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                    Registrar Mascota
                </button>
            </div>
        </form>
    </div>

    <div x-show="showClientModal" class="fixed inset-0 z-50 flex items-center justify-center" x-cloak>
        <div class="absolute inset-0 bg-black/40" @click="showClientModal = false"></div>
        <div class="relative bg-white w-full max-w-lg mx-4 rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Registro rapido de cliente</h2>
                <button type="button" class="text-gray-500 hover:text-gray-700" @click="showClientModal = false" aria-label="Cerrar">&times;</button>
            </div>

            <form action="{{ route('clients.store') }}" method="POST">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ route('pets.create') }}">

                <div class="mb-4">
                    <label for="client_name" class="block text-gray-700 font-medium mb-2">Nombre Completo *</label>
                    <input
                        type="text"
                        id="client_name"
                        name="client_name"
                        value="{{ old('client_name') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @if($errors->quickClient->has('client_name')) border-red-500 @endif"
                        required
                    >
                    @if($errors->quickClient->has('client_name'))
                        <p class="text-red-500 text-sm mt-1">{{ $errors->quickClient->first('client_name') }}</p>
                    @endif
                </div>

                <div class="mb-4">
                    <label for="client_email" class="block text-gray-700 font-medium mb-2">Correo Electronico *</label>
                    <input
                        type="email"
                        id="client_email"
                        name="client_email"
                        value="{{ old('client_email') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @if($errors->quickClient->has('client_email')) border-red-500 @endif"
                        required
                    >
                    @if($errors->quickClient->has('client_email'))
                        <p class="text-red-500 text-sm mt-1">{{ $errors->quickClient->first('client_email') }}</p>
                    @endif
                </div>

                <div class="mb-4">
                    <label for="client_phone" class="block text-gray-700 font-medium mb-2">Telefono *</label>
                    <input
                        type="text"
                        id="client_phone"
                        name="client_phone"
                        value="{{ old('client_phone') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @if($errors->quickClient->has('client_phone')) border-red-500 @endif"
                        required
                    >
                    @if($errors->quickClient->has('client_phone'))
                        <p class="text-red-500 text-sm mt-1">{{ $errors->quickClient->first('client_phone') }}</p>
                    @endif
                </div>

                <div class="mb-4">
                    <label for="client_address" class="block text-gray-700 font-medium mb-2">Direccion</label>
                    <input
                        type="text"
                        id="client_address"
                        name="client_address"
                        value="{{ old('client_address') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @if($errors->quickClient->has('client_address')) border-red-500 @endif"
                    >
                    @if($errors->quickClient->has('client_address'))
                        <p class="text-red-500 text-sm mt-1">{{ $errors->quickClient->first('client_address') }}</p>
                    @endif
                </div>

                <div class="mb-6">
                    <label for="client_city" class="block text-gray-700 font-medium mb-2">Ciudad</label>
                    <input
                        type="text"
                        id="client_city"
                        name="client_city"
                        value="{{ old('client_city') }}"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @if($errors->quickClient->has('client_city')) border-red-500 @endif"
                    >
                    @if($errors->quickClient->has('client_city'))
                        <p class="text-red-500 text-sm mt-1">{{ $errors->quickClient->first('client_city') }}</p>
                    @endif
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400" @click="showClientModal = false">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Guardar cliente
                    </button>
                </div>
            </form>
        </div>
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
