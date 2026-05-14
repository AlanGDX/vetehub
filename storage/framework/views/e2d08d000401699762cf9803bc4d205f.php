<?php $__env->startSection('title', 'Editar Articulo - VeteHub'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">✏️ Editar Articulo</h1>
                <p class="text-gray-600 mt-2">Actualiza la informacion del articulo</p>
            </div>
            <a href="<?php echo e(route('sales.inventory')); ?>" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                Volver
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="<?php echo e(route('products.update', $product)); ?>" class="space-y-5">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                <input id="name" name="name" type="text" value="<?php echo e(old('name', $product->name)); ?>" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">SKU</label>
                <input id="sku" name="sku" type="text" value="<?php echo e(old('sku', $product->sku)); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Precio *</label>
                    <input id="price" name="price" type="number" step="0.01" min="0" value="<?php echo e(old('price', $product->price)); ?>" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stock *</label>
                    <input id="stock" name="stock" type="number" min="0" value="<?php echo e(old('stock', $product->stock)); ?>" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="flex items-center">
                <input id="is_active" name="is_active" type="checkbox" value="1" class="h-4 w-4 text-blue-600" <?php echo e(old('is_active', $product->is_active) ? 'checked' : ''); ?>>
                <label for="is_active" class="ml-2 text-sm text-gray-700">Activo</label>
            </div>

            <div class="flex justify-end gap-2">
                <a href="<?php echo e(route('sales.inventory')); ?>" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Repos\vetehub\resources\views/products/edit.blade.php ENDPATH**/ ?>