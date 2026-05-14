<?php $__env->startSection('title', 'Dashboard - VeteHub'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Bienvenido, <?php echo e(Auth::user()->name); ?></h1>
        <?php if(Auth::user()->clinic_name): ?>
            <h2 class="text-2xl font-semibold text-blue-600"><?php echo e(Auth::user()->clinic_name); ?></h2>
        <?php endif; ?>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card Clientes -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total de Clientes</p>
                    <p class="text-3xl font-bold text-blue-600"><?php echo e(\App\Models\Client::where('user_id', Auth::id())->count()); ?></p>
                </div>
                <div class="bg-blue-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="<?php echo e(route('clients.index')); ?>" class="text-blue-600 hover:underline">Ver todos los clientes →</a>
            </div>
        </div>

        <!-- Card Mascotas -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Total de Mascotas</p>
                    <p class="text-3xl font-bold text-green-600">
                        <?php echo e(\App\Models\Pet::whereHas('client', function($q) { $q->where('user_id', Auth::id()); })->count()); ?>

                    </p>
                </div>
                <div class="bg-green-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="<?php echo e(route('pets.index')); ?>" class="text-green-600 hover:underline">Ver todas las mascotas →</a>
            </div>
        </div>

        <!-- Card Citas Hoy -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Citas de Hoy</p>
                    <p class="text-3xl font-bold text-purple-600">
                        <?php echo e(\App\Models\Appointment::where('user_id', Auth::id())->whereDate('appointment_date', today())->count()); ?>

                    </p>
                </div>
                <div class="bg-purple-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="<?php echo e(route('appointments.index')); ?>" class="text-purple-600 hover:underline">Ver agenda completa →</a>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Acciones Rápidas</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="<?php echo e(route('clients.create')); ?>" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 text-center transition duration-200">
                + Registrar Nuevo Cliente
            </a>
            <a href="<?php echo e(route('pets.create')); ?>" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 text-center transition duration-200">
                + Registrar Nueva Mascota
            </a>
            <a href="<?php echo e(route('appointments.create')); ?>" class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 text-center transition duration-200">
                + Agendar Nueva Cita
            </a>
        </div>
    </div>

    <!-- Previsualizacion de ventas -->
    <div class="bg-white rounded-lg shadow-lg p-6 mt-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">Ventas</h2>
            <?php if(Route::has('sales.inventory')): ?>
                <a href="<?php echo e(route('sales.inventory')); ?>" class="text-sm text-blue-600 hover:underline">
                    Ir a ventas
                </a>
            <?php endif; ?>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="border rounded-lg p-5">
                <p class="text-gray-600 text-sm">Articulos disponibles</p>
                <p class="text-3xl font-bold text-blue-600 mt-2">
                    <?php echo e(\App\Models\Product::where('user_id', Auth::id())->where('is_active', true)->count()); ?>

                </p>
            </div>
            <div class="border rounded-lg p-5">
                <p class="text-gray-600 text-sm">Ventas del dia</p>
                <p class="text-3xl font-bold text-green-600 mt-2">
                    $<?php echo e(number_format((float) \App\Models\Sale::where('seller_id', Auth::id())
                        ->whereDate('sold_at', today())
                        ->sum('total'), 2)); ?>

                </p>
                <p class="text-sm text-gray-500 mt-2">
                    <?php echo e(\App\Models\Sale::where('seller_id', Auth::id())
                        ->whereDate('sold_at', today())
                        ->count()); ?> ventas realizadas
                </p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Repos\vetehub\resources\views/dashboard.blade.php ENDPATH**/ ?>