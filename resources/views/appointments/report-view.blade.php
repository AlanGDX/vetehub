@extends('layouts.app')

@section('title', 'Reporte de Citas - VeteHub')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 no-print">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">📊 Reporte de Citas</h1>
                <p class="text-gray-600 mt-2">Período: {{ $report['period']['start'] }} - {{ $report['period']['end'] }}</p>
            </div>
            <div class="flex space-x-3">
                <form action="{{ route('appointments.report.generate') }}" method="POST" id="csv-form">
                    @csrf
                    <input type="hidden" name="start_date" value="{{ $reportParams['start_date'] }}">
                    <input type="hidden" name="end_date" value="{{ $reportParams['end_date'] }}">
                    <input type="hidden" name="format" value="csv">
                    @if($reportParams['status'])
                        <input type="hidden" name="status" value="{{ $reportParams['status'] }}">
                    @endif
                    @if($reportParams['client_id'])
                        <input type="hidden" name="client_id" value="{{ $reportParams['client_id'] }}">
                    @endif
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Descargar CSV
                    </button>
                </form>
                <a href="{{ route('appointments.report') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                    ← Nuevo Reporte
                </a>
            </div>
        </div>
    </div>

    <!-- Resumen General -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm font-medium">Total de Citas</div>
            <div class="text-3xl font-bold text-gray-900 mt-2">{{ $report['summary']['total'] }}</div>
        </div>
        <div class="bg-green-50 rounded-lg shadow p-6">
            <div class="text-green-700 text-sm font-medium">Confirmadas</div>
            <div class="text-3xl font-bold text-green-900 mt-2">{{ $report['summary']['confirmed'] }}</div>
        </div>
        <div class="bg-yellow-50 rounded-lg shadow p-6">
            <div class="text-yellow-700 text-sm font-medium">Pendientes</div>
            <div class="text-3xl font-bold text-yellow-900 mt-2">{{ $report['summary']['pending'] }}</div>
        </div>
        <div class="bg-blue-50 rounded-lg shadow p-6">
            <div class="text-blue-700 text-sm font-medium">Completadas</div>
            <div class="text-3xl font-bold text-blue-900 mt-2">{{ $report['summary']['completed'] }}</div>
        </div>
    </div>

    @if($report['summary']['total'] > 0)
        <!-- Estadísticas Adicionales -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">📈 Estadísticas</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <div class="text-gray-500 text-sm">Duración Total</div>
                    <div class="text-2xl font-bold">{{ $report['summary']['total_duration'] }} min</div>
                </div>
                <div>
                    <div class="text-gray-500 text-sm">Duración Promedio</div>
                    <div class="text-2xl font-bold">{{ $report['summary']['average_duration'] }} min</div>
                </div>
                <div>
                    <div class="text-gray-500 text-sm">Citas Canceladas</div>
                    <div class="text-2xl font-bold text-red-600">{{ $report['summary']['cancelled'] }}</div>
                </div>
            </div>
        </div>

        <!-- Resumen Diario -->
        @if(count($report['daily_summary']) > 0 && count($report['daily_summary']) <= 60)
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">📅 Resumen Diario</h3>
                <div class="flex items-center space-x-4 text-xs">
                    <span class="flex items-center text-gray-600">
                        <span class="mr-1">✓</span> Confirmadas
                    </span>
                    <span class="flex items-center text-gray-600">
                        <span class="mr-1">⏳</span> Pendientes
                    </span>
                    <span class="flex items-center text-gray-600">
                        <span class="mr-1">✅</span> Completadas
                    </span>
                    <span class="flex items-center text-gray-600">
                        <span class="mr-1">❌</span> Canceladas
                    </span>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($report['daily_summary'] as $day)
                <div class="border rounded-lg p-4">
                    <div class="font-semibold text-gray-900">{{ $day['date'] }}</div>
                    <div class="text-sm text-gray-500">{{ $day['day_name'] }}</div>
                    <div class="mt-2 flex justify-between text-sm">
                        <span class="text-gray-600">Total:</span>
                        <span class="font-semibold">{{ $day['total'] }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                        <span>✓ {{ $day['confirmed'] }}</span>
                        <span>⏳ {{ $day['pending'] }}</span>
                        <span>✅ {{ $day['completed'] }}</span>
                        <span>❌ {{ $day['cancelled'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Detalle de Citas -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">📝 Detalle de Citas ({{ $report['summary']['total'] }})</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha y Hora</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mascota</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Veterinario</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motivo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duración</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($report['appointments'] as $appointment)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">#{{ $appointment->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                {{ $appointment->appointment_date->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->client->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $appointment->pet->name }} <span class="text-gray-500 text-xs">({{ $appointment->pet->species }})</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($appointment->status == 'confirmed')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Confirmada</span>
                                @elseif($appointment->status == 'pending')
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                                @elseif($appointment->status == 'completed')
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Completada</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Cancelada</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $appointment->reason }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $appointment->duration }} min</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- No hay citas -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
            <div class="text-6xl mb-4">📭</div>
            <div class="text-xl font-semibold text-gray-900 mb-2">No se encontraron citas</div>
            <div class="text-gray-600">No hay citas registradas en el rango de fechas y filtros seleccionados.</div>
            <a href="{{ route('appointments.report') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Intentar con otros filtros
            </a>
        </div>
    @endif

    <!-- Footer del reporte -->
    <div class="mt-6 text-center text-gray-500 text-sm">
        Reporte generado el {{ now()->format('d/m/Y H:i:s') }}
    </div>
</div>

<style>
    @media print {
        .no-print {
            display: none !important;
        }
        body {
            font-size: 12px;
        }
    }
</style>
@endsection
