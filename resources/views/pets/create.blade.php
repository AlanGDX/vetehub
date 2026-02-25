@extends('layouts.app')

@section('title', 'Nueva Mascota - VeteHub')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Registrar Nueva Mascota</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('pets.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="client_id" class="block text-gray-700 font-medium mb-2">Cliente (Dueño) *</label>
                <select 
                    id="client_id" 
                    name="client_id" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 @error('client_id') border-red-500 @enderror"
                    required
                >
                    <option value="">Seleccione un cliente</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->name }} - {{ $client->email }}
                        </option>
                    @endforeach
                </select>
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
                        <option value="">Seleccione</option>
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
                <a href="{{ route('pets.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                    Cancelar
                </a>
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                    Registrar Mascota
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
