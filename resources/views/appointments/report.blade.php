@extends('layouts.app')

@section('title', 'Generar Reporte de Citas - VeteHub')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">📊 Generar Reporte de Citas</h1>
                <p class="text-gray-600 mt-2">Genera reportes detallados de tus citas con filtros personalizados</p>
            </div>
            <a href="{{ route('appointments.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                ← Volver
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Error:</strong> {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <strong>Éxito:</strong> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('appointments.report.generate') }}" method="POST">
            @csrf

            <!-- Rango de Fechas -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">📅 Rango de Fechas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de Inicio *
                        </label>
                        <input type="date" 
                               id="start_date" 
                               name="start_date" 
                               value="{{ old('start_date', now()->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de Fin *
                        </label>
                        <input type="date" 
                               id="end_date" 
                               name="end_date" 
                               value="{{ old('end_date', now()->addDay()->format('Y-m-d')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-2">* Campos obligatorios</p>
            </div>

            <hr class="my-6">

            <!-- Filtros Opcionales -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">🔍 Filtros Opcionales</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Estado -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Estado de la Cita
                        </label>
                        <select id="status" 
                                name="status" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos los estados</option>
                            <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmadas</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pendientes</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completadas</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Canceladas</option>
                        </select>
                    </div>

                    <!-- Cliente -->
                    <div>
                        <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Cliente Específico
                        </label>
                        <select id="client_id" 
                                name="client_id" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos los clientes</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-3 text-sm text-gray-600 bg-blue-50 p-3 rounded">
                    ℹ️ El reporte mostrará únicamente tus citas (como veterinario asignado)
                </div>
            </div>

            <hr class="my-6">

            <!-- Formato de Salida -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">📄 Formato de Salida</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                        <input type="radio" 
                               name="format" 
                               value="text" 
                               checked
                               class="w-5 h-5 text-blue-600">
                        <div class="ml-3">
                            <div class="font-medium text-gray-900">Vista en Pantalla</div>
                            <div class="text-sm text-gray-500">Ver el reporte en formato HTML</div>
                        </div>
                    </label>
                    
                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                        <input type="radio" 
                               name="format" 
                               value="csv" 
                               class="w-5 h-5 text-blue-600">
                        <div class="ml-3">
                            <div class="font-medium text-gray-900">Descargar CSV</div>
                            <div class="text-sm text-gray-500">Compatible con Excel y Google Sheets</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('appointments.index') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        id="submit-btn"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span id="btn-text">Generar Reporte</span>
                    <span id="btn-loading" class="hidden ml-2">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </form>
    </div>

    <!-- Guía de uso rápida -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h4 class="font-semibold text-blue-900 mb-2">💡 Consejos de uso:</h4>
        <ul class="text-sm text-blue-800 space-y-1 ml-4 list-disc">
            <li>Por defecto se muestran las citas del día actual y el día siguiente</li>
            <li>Usa los filtros para obtener reportes más específicos</li>
            <li>El formato CSV es ideal para análisis en Excel</li>
            <li>La vista en pantalla te permite ver el resumen antes de descargar</li>
        </ul>
    </div>
</div>

<script>
    // Validación de fechas en el cliente
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submit-btn');
    const btnText = document.getElementById('btn-text');
    const btnLoading = document.getElementById('btn-loading');
    
    form.addEventListener('submit', function(e) {
        const startDate = new Date(document.getElementById('start_date').value);
        const endDate = new Date(document.getElementById('end_date').value);
        
        if (startDate > endDate) {
            e.preventDefault();
            alert('La fecha de inicio no puede ser mayor que la fecha de fin');
            return false;
        }
        
        // Mostrar indicador de carga
        submitBtn.disabled = true;
        btnText.textContent = 'Generando...';
        btnLoading.classList.remove('hidden');
        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
    });

    // Pre-rellenar con fechas comunes
    document.addEventListener('DOMContentLoaded', function() {
        // Botones rápidos
        const quickFilters = document.createElement('div');
        quickFilters.className = 'flex space-x-2 mt-2';
        quickFilters.innerHTML = `
            <button type="button" onclick="setDateRange('week')" class="text-xs px-3 py-1 bg-gray-100 rounded hover:bg-gray-200">Esta semana</button>
            <button type="button" onclick="setDateRange('month')" class="text-xs px-3 py-1 bg-gray-100 rounded hover:bg-gray-200">Este mes</button>
            <button type="button" onclick="setDateRange('last_month')" class="text-xs px-3 py-1 bg-gray-100 rounded hover:bg-gray-200">Mes pasado</button>
        `;
        
        const dateSection = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.gap-4').parentElement;
        dateSection.appendChild(quickFilters);
    });

    function setDateRange(range) {
        const today = new Date();
        let startDate, endDate;
        
        switch(range) {
            case 'week':
                const dayOfWeek = today.getDay();
                startDate = new Date(today);
                startDate.setDate(today.getDate() - dayOfWeek);
                endDate = new Date(today);
                endDate.setDate(today.getDate() + (6 - dayOfWeek));
                break;
            case 'month':
                startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                break;
            case 'last_month':
                startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                endDate = new Date(today.getFullYear(), today.getMonth(), 0);
                break;
        }
        
        document.getElementById('start_date').value = startDate.toISOString().split('T')[0];
        document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
    }
</script>
@endsection
