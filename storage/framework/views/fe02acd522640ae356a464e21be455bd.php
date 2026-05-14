<?php $__env->startSection('title', 'Mascotas - VeteHub'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Mascotas</h1>
        <a href="<?php echo e(route('pets.create')); ?>" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
            + Nueva Mascota
        </a>
    </div>

    <?php if($pets->count() > 0): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__currentLoopData = $pets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-6">
            <div class="flex justify-between items-start mb-3">
                <h3 class="text-xl font-bold"><?php echo e($pet->name); ?></h3>
                <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded"><?php echo e($pet->species); ?></span>
            </div>

            <div class="space-y-2 mb-4">
                <?php if($pet->breed): ?>
                <p class="text-gray-600 text-sm">
                    <span class="font-medium">Raza:</span> <?php echo e($pet->breed); ?>

                </p>
                <?php endif; ?>
                <?php if($pet->birth_date): ?>
                <p class="text-gray-600 text-sm">
                    <span class="font-medium">Nacimiento:</span> <?php echo e($pet->birth_date->format('d/m/Y')); ?>

                </p>
                <?php endif; ?>
                <p class="text-gray-600 text-sm">
                    <span class="font-medium">Edad:</span> <?php echo e($pet->age); ?>

                </p>
                <?php if($pet->gender): ?>
                <p class="text-gray-600 text-sm">
                    <span class="font-medium">Sexo:</span> <?php echo e($pet->gender === 'male' ? 'Macho' : 'Hembra'); ?>

                </p>
                <?php endif; ?>
                <?php if($pet->weight): ?>
                <p class="text-gray-600 text-sm">
                    <span class="font-medium">Peso:</span> <?php echo e($pet->weight); ?> kg
                </p>
                <?php endif; ?>
                <p class="text-gray-600 text-sm">
                    <span class="font-medium">Dueño:</span> <?php echo e($pet->client->name); ?>

                </p>
            </div>

            <div class="flex justify-between items-center pt-4 border-t">
                <a href="<?php echo e(route('pets.show', $pet)); ?>" class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                    Ver detalles →
                </a>
                <div class="flex items-center space-x-2">
                    <a href="<?php echo e(route('pets.edit', $pet)); ?>" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                    <form action="<?php echo e(route('pets.destroy', $pet)); ?>" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta mascota?');">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="mt-6">
        <?php echo e($pets->links()); ?>

    </div>
    <?php else: ?>
    <div class="bg-white rounded-lg shadow p-8 text-center">
        <p class="text-gray-600 mb-4">No hay mascotas registradas aún.</p>
        <a href="<?php echo e(route('pets.create')); ?>" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 inline-block">
            + Registrar Primera Mascota
        </a>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Repos\vetehub\resources\views/pets/index.blade.php ENDPATH**/ ?>