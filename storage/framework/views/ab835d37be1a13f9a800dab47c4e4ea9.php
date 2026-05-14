<?php $__env->startSection('title', 'Reporte de Ventas - VeteHub'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    <div class="mb-6 no-print">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">🧾 Reporte de Ventas</h1>
                <p class="text-gray-600 mt-2">Periodo: <?php echo e($report['period']['start']); ?> - <?php echo e($report['period']['end']); ?></p>
            </div>
            <div class="flex space-x-3">
                <form action="<?php echo e(route('sales.report.generate')); ?>" method="POST" id="pdf-form">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="start_date" value="<?php echo e($reportParams['start_date']); ?>">
                    <input type="hidden" name="end_date" value="<?php echo e($reportParams['end_date']); ?>">
                    <input type="hidden" name="format" value="pdf">
                    <?php if($reportParams['seller_id']): ?>
                        <input type="hidden" name="seller_id" value="<?php echo e($reportParams['seller_id']); ?>">
                    <?php endif; ?>
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Descargar PDF
                    </button>
                </form>
                <form action="<?php echo e(route('sales.report.generate')); ?>" method="POST" id="csv-form">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="start_date" value="<?php echo e($reportParams['start_date']); ?>">
                    <input type="hidden" name="end_date" value="<?php echo e($reportParams['end_date']); ?>">
                    <input type="hidden" name="format" value="csv">
                    <?php if($reportParams['seller_id']): ?>
                        <input type="hidden" name="seller_id" value="<?php echo e($reportParams['seller_id']); ?>">
                    <?php endif; ?>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Descargar CSV
                    </button>
                </form>
                <a href="<?php echo e(route('sales.report')); ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                    ← Nuevo Reporte
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-gray-500 text-sm font-medium">Ventas Totales</div>
            <div class="text-3xl font-bold text-gray-900 mt-2">$<?php echo e($report['summary']['total_sales']); ?></div>
        </div>
        <div class="bg-blue-50 rounded-lg shadow p-6">
            <div class="text-blue-700 text-sm font-medium">Pedidos</div>
            <div class="text-3xl font-bold text-blue-900 mt-2"><?php echo e($report['summary']['total_orders']); ?></div>
        </div>
        <div class="bg-green-50 rounded-lg shadow p-6">
            <div class="text-green-700 text-sm font-medium">Productos Vendidos</div>
            <div class="text-3xl font-bold text-green-900 mt-2"><?php echo e($report['summary']['total_items']); ?></div>
        </div>
        <div class="bg-yellow-50 rounded-lg shadow p-6">
            <div class="text-yellow-700 text-sm font-medium">Ticket Promedio</div>
            <div class="text-3xl font-bold text-yellow-900 mt-2">$<?php echo e($report['summary']['average_ticket']); ?></div>
        </div>
    </div>

    <?php if($report['summary']['total_orders'] > 0): ?>
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">🧍 Resumen por vendedor</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php $__currentLoopData = $report['by_seller']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border rounded-lg p-4">
                        <div class="font-semibold text-gray-900"><?php echo e($seller['name']); ?></div>
                        <div class="mt-2 text-sm text-gray-600">Ventas: $<?php echo e($seller['total_sales']); ?></div>
                        <div class="text-sm text-gray-600">Pedidos: <?php echo e($seller['total_orders']); ?></div>
                        <div class="text-sm text-gray-600">Items: <?php echo e($seller['total_items']); ?></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <?php if(count($report['daily_summary']) > 0 && count($report['daily_summary']) <= 60): ?>
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">📅 Resumen Diario</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php $__currentLoopData = $report['daily_summary']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border rounded-lg p-4">
                            <div class="font-semibold text-gray-900"><?php echo e($day['date']); ?></div>
                            <div class="text-sm text-gray-500"><?php echo e($day['day_name']); ?></div>
                            <div class="mt-2 text-sm text-gray-600">Ventas: $<?php echo e($day['total_sales']); ?></div>
                            <div class="text-sm text-gray-600">Pedidos: <?php echo e($day['total_orders']); ?></div>
                            <div class="text-sm text-gray-600">Items: <?php echo e($day['total_items']); ?></div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">🧾 Detalle de Ventas (<?php echo e($report['summary']['total_orders']); ?>)</h3>
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
                        <?php $__currentLoopData = $report['sales']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">#<?php echo e($sale->id); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php echo e($sale->sold_at?->format('d/m/Y H:i') ?? 'N/A'); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php echo e($sale->seller?->name ?? 'Sin vendedor'); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php echo e($sale->items_count > 0 ? $sale->items_count : $sale->items->sum('quantity')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">$<?php echo e($sale->total); ?></td>
                                <td class="px-6 py-4 text-sm"><?php echo e($sale->notes ?? 'N/A'); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
            <div class="text-6xl mb-4">📭</div>
            <div class="text-xl font-semibold text-gray-900 mb-2">No se encontraron ventas</div>
            <div class="text-gray-600">No hay ventas registradas en el rango de fechas y filtros seleccionados.</div>
            <a href="<?php echo e(route('sales.report')); ?>" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                Intentar con otros filtros
            </a>
        </div>
    <?php endif; ?>

    <div class="mt-6 text-center text-gray-500 text-sm">
        Reporte generado el <?php echo e(now()->format('d/m/Y H:i:s')); ?>

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Repos\vetehub\resources\views/sales/report-view.blade.php ENDPATH**/ ?>