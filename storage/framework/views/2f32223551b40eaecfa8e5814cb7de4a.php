<?php $__env->startSection('title', 'Generar Reporte de Ventas - VeteHub'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">🧾 Generar Reporte de Ventas</h1>
                <p class="text-gray-600 mt-2">Genera reportes de ventas por periodo y vendedor</p>
            </div>
            <a href="<?php echo e(route('dashboard')); ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                ← Volver
            </a>
        </div>
    </div>

    <?php if($errors->any()): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Error:</strong> <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <strong>Exito:</strong> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="<?php echo e(route('sales.report.generate')); ?>" method="POST">
            <?php echo csrf_field(); ?>

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
                               value="<?php echo e(old('start_date', now()->subDay()->format('Y-m-d'))); ?>"
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
                               value="<?php echo e(old('end_date', now()->format('Y-m-d'))); ?>"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               required>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-2">* Campos obligatorios</p>
            </div>

            <hr class="my-6">

            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">🔍 Filtros Opcionales</h3>
                <div>
                    <label for="seller_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Vendedor
                    </label>
                    <select id="seller_id"
                            name="seller_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos los vendedores</option>
                        <?php $__currentLoopData = $sellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($seller->id); ?>" <?php echo e(old('seller_id') == $seller->id ? 'selected' : ''); ?>>
                                <?php echo e($seller->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <hr class="my-6">

            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">📄 Formato de Salida</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

                    <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-500 transition-colors">
                        <input type="radio"
                               name="format"
                               value="pdf"
                               class="w-5 h-5 text-blue-600">
                        <div class="ml-3">
                            <div class="font-medium text-gray-900">Descargar PDF</div>
                            <div class="text-sm text-gray-500">Formato profesional para imprimir</div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a href="<?php echo e(route('dashboard')); ?>"
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        id="submit-btn"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
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
</div>

<script>
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submit-btn');
    const btnText = document.getElementById('btn-text');
    const btnLoading = document.getElementById('btn-loading');

    form.addEventListener('submit', function (e) {
        const startDate = new Date(document.getElementById('start_date').value);
        const endDate = new Date(document.getElementById('end_date').value);

        if (startDate > endDate) {
            e.preventDefault();
            alert('La fecha de inicio no puede ser mayor que la fecha de fin');
            return false;
        }

        submitBtn.disabled = true;
        btnText.textContent = 'Generando...';
        btnLoading.classList.remove('hidden');
        submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Repos\vetehub\resources\views/sales/report.blade.php ENDPATH**/ ?>