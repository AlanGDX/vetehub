@extends('layouts.app')

@section('title', 'Reporte de Ventas - VeteHub')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 no-print">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">🧾 Reporte de Ventas</h1>
                <p class="text-gray-600 mt-2">Periodo: {{ $report['period']['start'] }} - {{ $report['period']['end'] }}</p>
            </div>
            <div class="flex space-x-3">
                <form action="{{ route('sales.report.generate') }}" method="POST" id="pdf-form">
                    @csrf
                    <input type="hidden" name="start_date" value="{{ $reportParams['start_date'] }}">
                    <input type="hidden" name="end_date" value="{{ $reportParams['end_date'] }}">
                    <input type="hidden" name="format" value="pdf">
                    @if($reportParams['seller_id'])
                        <input type="hidden" name="seller_id" value="{{ $reportParams['seller_id'] }}">
                    @endif
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Descargar PDF
                    </button>
                </form>
                <form action="{{ route('sales.report.generate') }}" method="POST" id="csv-form">
                    @csrf
                    <input type="hidden" name="start_date" value="{{ $reportParams['start_date'] }}">
                    <input type="hidden" name="end_date" value="{{ $reportParams['end_date'] }}">
                    <input type="hidden" name="format" value="csv">
                    @if($reportParams['seller_id'])
                        <input type="hidden" name="seller_id" value="{{ $reportParams['seller_id'] }}">
                    @endif
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Descargar CSV
                    </button>
                </form>
                <a href="{{ route('sales.report') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                    ← Nuevo Reporte
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm font-medium">Ventas Totales</div>
            <div class="text-3xl font-bold text-gray-900 mt-2">${{ $report['summary']['total_sales'] }}</div>
        </div>
        <div class="bg-blue-50 rounded-lg shadow p-6">
            <div class="text-blue-700 text-sm font-medium">Pedidos</div>
            <div class="text-3xl font-bold text-blue-900 mt-2">{{ $report['summary']['total_orders'] }}</div>
        </div>
        <div class="bg-green-50 rounded-lg shadow p-6">
            <div class="text-green-700 text-sm font-medium">Productos Vendidos</div>
            <div class="text-3xl font-bold text-green-900 mt-2">{{ $report['summary']['total_items'] }}</div>
        </div>
        <div class="bg-yellow-50 rounded-lg shadow p-6">
            <div class="text-yellow-700 text-sm font-medium">Ticket Promedio</div>
            <div class="text-3xl font-bold text-yellow-900 mt-2">${{ $report['summary']['average_ticket'] }}</div>
        </div>
    </div>

    @if($report['summary']['total_orders'] > 0)
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">🧍 Resumen por vendedor</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($report['by_seller'] as $seller)
                    <div class="border rounded-lg p-4">
                        <div class="font-semibold text-gray-900">{{ $seller['name'] }}</div>
                        <div class="mt-2 text-sm text-gray-600">Ventas: ${{ $seller['total_sales'] }}</div>
                        <div class="text-sm text-gray-600">Pedidos: {{ $seller['total_orders'] }}</div>
                        <div class="text-sm text-gray-600">Items: {{ $seller['total_items'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        @if(count($report['daily_summary']) > 0 && count($report['daily_summary']) <= 60)
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">📅 Resumen Diario</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($report['daily_summary'] as $day)
                        <div class="border rounded-lg p-4">
                            <div class="font-semibold text-gray-900">{{ $day['date'] }}</div>
                            <div class="text-sm text-gray-500">{{ $day['day_name'] }}</div>
                            <div class="mt-2 text-sm text-gray-600">Ventas: ${{ $day['total_sales'] }}</div>
                            <div class="text-sm text-gray-600">Pedidos: {{ $day['total_orders'] }}</div>
                            <div class="text-sm text-gray-600">Items: {{ $day['total_items'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">🧾 Detalle de Ventas ({{ $report['summary']['total_orders'] }})</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendedor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notas</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($report['sales'] as $sale)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">#{{ $sale->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $sale->sold_at?->format('d/m/Y H:i') ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $sale->seller?->name ?? 'Sin vendedor' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    {{ $sale->items_count > 0 ? $sale->items_count : $sale->items->sum('quantity') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">${{ $sale->total }}</td>
                                <td class="px-6 py-4 text-sm">{{ $sale->notes ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
            <div class="text-6xl mb-4">📭</div>
            <div class="text-xl font-semibold text-gray-900 mb-2">No se encontraron ventas</div>
            <div class="text-gray-600">No hay ventas registradas en el rango de fechas y filtros seleccionados.</div>
            <a href="{{ route('sales.report') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Intentar con otros filtros
            </a>
        </div>
    @endif

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
