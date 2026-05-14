<?php $__env->startSection('title', 'Nueva Mascota - VeteHub'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto" x-data="{ showClientModal: <?php echo e($errors->has('quickClient') ? 'true' : 'false'); ?> }">
    <h1 class="text-3xl font-bold mb-6">Registrar Nueva Mascota</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <form action="<?php echo e(route('pets.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="mb-4">
                <label for="client_id" class="block text-gray-700 font-medium mb-2">Cliente (Dueño) *</label>
                <select 
                    id="client_id" 
                    name="client_id" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    required
                    @change="if ($event.target.value === '__new__') { $event.target.value = ''; showClientModal = true; }"
                >
                    <option value="" disabled hidden <?php echo e(old('client_id', $selectedClientId ?? '') ? '' : 'selected'); ?>>Seleccione un cliente</option>
                    <option value="__new__">+ Registrar cliente nuevo</option>
                    <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($client->id); ?>" <?php echo e(old('client_id', $selectedClientId ?? '') == $client->id ? 'selected' : ''); ?>>
                            <?php echo e($client->name); ?> - <?php echo e($client->email); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <?php if($clients->count() === 0): ?>
                    <div class="mt-3 bg-yellow-100 border border-yellow-300 text-yellow-800 px-3 py-2 rounded">
                        No hay clientes registrados. Selecciona "Registrar cliente nuevo" para continuar.
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-2">Nombre de la Mascota *</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="<?php echo e(old('name')); ?>"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 <?php $__errorArgs = ['name'];
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
                <label for="species" class="block text-gray-700 font-medium mb-2">Especie *</label>
                <input 
                    type="text" 
                    id="species" 
                    name="species" 
                    value="<?php echo e(old('species')); ?>"
                    placeholder="Ej: Perro, Gato, Conejo, etc."
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 <?php $__errorArgs = ['species'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    required
                >
                <?php $__errorArgs = ['species'];
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
                <label for="breed" class="block text-gray-700 font-medium mb-2">Raza</label>
                <input 
                    type="text" 
                    id="breed" 
                    name="breed" 
                    value="<?php echo e(old('breed')); ?>"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 <?php $__errorArgs = ['breed'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                >
                <?php $__errorArgs = ['breed'];
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="birth_date" class="block text-gray-700 font-medium mb-2">Fecha de Nacimiento</label>
                    <input 
                        type="date" 
                        id="birth_date" 
                        name="birth_date" 
                        value="<?php echo e(old('birth_date')); ?>"
                        max="<?php echo e(date('Y-m-d')); ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 <?php $__errorArgs = ['birth_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    >
                    <?php $__errorArgs = ['birth_date'];
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

                <div>
                    <label for="gender" class="block text-gray-700 font-medium mb-2">Sexo</label>
                    <select 
                        id="gender" 
                        name="gender" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    >
                        <option value="" disabled hidden <?php echo e(old('gender') ? '' : 'selected'); ?>>Seleccione sexo</option>
                        <option value="male" <?php echo e(old('gender') == 'male' ? 'selected' : ''); ?>>Macho</option>
                        <option value="female" <?php echo e(old('gender') == 'female' ? 'selected' : ''); ?>>Hembra</option>
                    </select>
                    <?php $__errorArgs = ['gender'];
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="color" class="block text-gray-700 font-medium mb-2">Color</label>
                    <input 
                        type="text" 
                        id="color" 
                        name="color" 
                        value="<?php echo e(old('color')); ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 <?php $__errorArgs = ['color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    >
                    <?php $__errorArgs = ['color'];
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

                <div>
                    <label for="weight" class="block text-gray-700 font-medium mb-2">Peso (kg)</label>
                    <input 
                        type="number" 
                        id="weight" 
                        name="weight" 
                        value="<?php echo e(old('weight')); ?>"
                        step="0.01"
                        min="0"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 <?php $__errorArgs = ['weight'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                    >
                    <?php $__errorArgs = ['weight'];
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

            <div class="mb-6">
                <label for="medical_notes" class="block text-gray-700 font-medium mb-2">Notas Médicas</label>
                <textarea 
                    id="medical_notes" 
                    name="medical_notes" 
                    rows="4"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 <?php $__errorArgs = ['medical_notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                ><?php echo e(old('medical_notes')); ?></textarea>
                <?php $__errorArgs = ['medical_notes'];
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

            <div class="flex justify-end space-x-4">
                <?php
                    $backClientId = old('client_id', $selectedClientId ?? null);
                ?>
                <a href="<?php echo e($backClientId ? route('clients.show', $backClientId) : route('pets.index')); ?>" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                    Cancelar
                </a>
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700">
                    Registrar Mascota
                </button>
            </div>
        </form>
    </div>

    <div x-show="showClientModal" class="fixed inset-0 z-50 flex items-center justify-center" x-cloak>
        <div class="absolute inset-0 bg-black/40" @click="showClientModal = false"></div>
        <div class="relative bg-white w-full max-w-lg mx-4 rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Registro rapido de cliente</h2>
                <button type="button" class="text-gray-500 hover:text-gray-700" @click="showClientModal = false" aria-label="Cerrar">&times;</button>
            </div>

            <form action="<?php echo e(route('clients.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="redirect_to" value="<?php echo e(route('pets.create')); ?>">

                <div class="mb-4">
                    <label for="client_name" class="block text-gray-700 font-medium mb-2">Nombre Completo *</label>
                    <input
                        type="text"
                        id="client_name"
                        name="client_name"
                        value="<?php echo e(old('client_name')); ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php if($errors->quickClient->has('client_name')): ?> border-red-500 <?php endif; ?>"
                        required
                    >
                    <?php if($errors->quickClient->has('client_name')): ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($errors->quickClient->first('client_name')); ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label for="client_email" class="block text-gray-700 font-medium mb-2">Correo Electronico *</label>
                    <input
                        type="email"
                        id="client_email"
                        name="client_email"
                        value="<?php echo e(old('client_email')); ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php if($errors->quickClient->has('client_email')): ?> border-red-500 <?php endif; ?>"
                        required
                    >
                    <?php if($errors->quickClient->has('client_email')): ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($errors->quickClient->first('client_email')); ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label for="client_phone" class="block text-gray-700 font-medium mb-2">Telefono *</label>
                    <input
                        type="text"
                        id="client_phone"
                        name="client_phone"
                        value="<?php echo e(old('client_phone')); ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php if($errors->quickClient->has('client_phone')): ?> border-red-500 <?php endif; ?>"
                        required
                    >
                    <?php if($errors->quickClient->has('client_phone')): ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($errors->quickClient->first('client_phone')); ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label for="client_address" class="block text-gray-700 font-medium mb-2">Direccion</label>
                    <input
                        type="text"
                        id="client_address"
                        name="client_address"
                        value="<?php echo e(old('client_address')); ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php if($errors->quickClient->has('client_address')): ?> border-red-500 <?php endif; ?>"
                    >
                    <?php if($errors->quickClient->has('client_address')): ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($errors->quickClient->first('client_address')); ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-6">
                    <label for="client_city" class="block text-gray-700 font-medium mb-2">Ciudad</label>
                    <input
                        type="text"
                        id="client_city"
                        name="client_city"
                        value="<?php echo e(old('client_city')); ?>"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 <?php if($errors->quickClient->has('client_city')): ?> border-red-500 <?php endif; ?>"
                    >
                    <?php if($errors->quickClient->has('client_city')): ?>
                        <p class="text-red-500 text-sm mt-1"><?php echo e($errors->quickClient->first('client_city')); ?></p>
                    <?php endif; ?>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400" @click="showClientModal = false">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Guardar cliente
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Repos\vetehub\resources\views/pets/create.blade.php ENDPATH**/ ?>