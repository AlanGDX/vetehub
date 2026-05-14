@extends('layouts.app')

@section('title', 'Inventario de Ventas - VeteHub')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold">🧾 Inventario de Ventas</h1>
                <p class="text-gray-600 mt-2">Articulos disponibles para vender a tus clientes</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if(Route::has('products.create'))
                    <a href="{{ route('products.create') }}" class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700" title="Agregar articulo" aria-label="Agregar articulo">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </a>
                @endif
                @if(Route::has('sales.report'))
                    <a href="{{ route('sales.report') }}" class="bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800">
                        Reporte de ventas
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('sales.inventory') }}" class="flex flex-col gap-4 md:flex-row md:items-end">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Buscar articulo</label>
                <input
                    id="search"
                    name="search"
                    type="text"
                    value="{{ request('search') }}"
                    placeholder="Nombre o SKU"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Buscar
                </button>
                <a href="{{ route('sales.inventory') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold">Articulos</h2>
            <span class="text-sm text-gray-500">{{ $products->total() }} articulos (10 por pagina)</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $product->sku ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">${{ number_format((float) $product->price, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $product->stock }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($product->is_active)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Activo</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="inline-flex items-center gap-2">
                                    @if(Route::has('products.edit'))
                                        <a href="{{ route('products.edit', $product) }}" class="p-2 rounded-lg border border-gray-200 text-gray-600 hover:text-blue-600 hover:border-blue-200" title="Editar articulo" aria-label="Editar articulo">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h6m2 0a2 2 0 012 2v6m0 4a2 2 0 01-2 2h-6m-4 0a2 2 0 01-2-2v-6m0-4a2 2 0 012-2h6m-7.172 7.172a4 4 0 015.656 5.656L7 19l-4 1 1-4 3.828-3.828z" />
                                            </svg>
                                        </a>
                                    @endif
                                    @if(Route::has('products.destroy'))
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Seguro que deseas eliminar este articulo?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg border border-gray-200 text-gray-600 hover:text-red-600 hover:border-red-200" title="Eliminar articulo" aria-label="Eliminar articulo">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 7h12m-9 4v6m6-6v6M9 7V4h6v3m-9 0h12l-1 13a2 2 0 01-2 2H9a2 2 0 01-2-2L6 7z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-6 text-center text-sm text-gray-500">
                                No hay articulos para mostrar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
