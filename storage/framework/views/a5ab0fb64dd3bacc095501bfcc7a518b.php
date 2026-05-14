<?php $__env->startSection('title', 'Editar Perfil - VeteHub'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">Editar Perfil</h1>

    <div class="bg-white rounded-lg shadow" x-data="{ tab: 'general' }">
        <!-- Pestañas -->
        <div class="border-b">
            <nav class="flex">
                <button 
                    @click="tab = 'general'" 
                    :class="tab === 'general' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="px-6 py-3 border-b-2 font-medium text-sm focus:outline-none">
                    General
                </button>
                <button 
                    @click="tab = 'password'" 
                    :class="tab === 'password' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="px-6 py-3 border-b-2 font-medium text-sm focus:outline-none">
                    Cambiar Contraseña
                </button>
            </nav>
        </div>

        <form action="<?php echo e(route('profile.update')); ?>" method="POST" class="p-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!-- Pestaña General -->
            <div x-show="tab === 'general'">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-medium mb-2">Nombre Completo *</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="<?php echo e(old('name', Auth::user()->name)); ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        required
                    >
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-4">
                    <label for="clinic_name" class="block text-gray-700 font-medium mb-2">Nombre de la Veterinaria (opcional)</label>
                    <input 
                        type="text" 
                        id="clinic_name" 
                        name="clinic_name" 
                        value="<?php echo e(old('clinic_name', Auth::user()->clinic_name)); ?>"
                        placeholder="Deja vacío si no tienes veterinaria"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['clinic_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    >
                    <?php $__errorArgs = ['clinic_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-6">
                    <label for="email" class="block text-gray-700 font-medium mb-2">Correo Electrónico *</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="<?php echo e(old('email', Auth::user()->email)); ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        required
                    >
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <!-- Pestaña Cambiar Contraseña -->
            <div x-show="tab === 'password'">
                <p class="text-sm text-gray-600 mb-4">Deja estos campos vacíos si no deseas cambiar tu contraseña</p>

                <div class="mb-4">
                    <label for="current_password" class="block text-gray-700 font-medium mb-2">Contraseña Actual</label>
                    <input 
                        type="password" 
                        id="current_password" 
                        name="current_password" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    >
                    <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-medium mb-2">Nueva Contraseña</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    >
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 font-medium mb-2">Confirmar Nueva Contraseña</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6 pt-4 border-t">
                <a href="<?php echo e(route('dashboard')); ?>" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Repos\vetehub\resources\views/auth/profile.blade.php ENDPATH**/ ?>