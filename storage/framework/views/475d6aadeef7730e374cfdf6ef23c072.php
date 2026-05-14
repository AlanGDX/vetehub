<?php $__env->startSection('title', 'Agenda de Citas - VeteHub'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Agenda de Citas</h1>
        <div class="flex space-x-3">
            <a href="<?php echo e(route('appointments.report')); ?>" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Generar Reporte
            </a>
            <a href="<?php echo e(route('appointments.create')); ?>" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                + Nueva Cita
            </a>
        </div>
    </div>

    <!-- Controles de navegación -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <!-- Botones de vista -->
                <div class="flex space-x-2">
                    <a href="<?php echo e(route('appointments.index', ['view' => 'week', 'date' => $currentDate->format('Y-m-d')])); ?>" 
                       class="px-4 py-2 rounded <?php echo e($view === 'week' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'); ?>">
                        Semana
                    </a>
                    <a href="<?php echo e(route('appointments.index', ['view' => 'month', 'date' => $currentDate->format('Y-m-d')])); ?>" 
                       class="px-4 py-2 rounded <?php echo e($view === 'month' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'); ?>">
                        Mes
                    </a>
                </div>

                <!-- Navegación de fechas -->
                <div class="flex items-center space-x-2">
                    <?php if($view === 'week'): ?>
                        <a href="<?php echo e(route('appointments.index', ['view' => $view, 'date' => $currentDate->copy()->subWeek()->format('Y-m-d')])); ?>" 
                           class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                            ←
                        </a>
                        <span class="px-4 font-medium">
                            <?php echo e($startDate->format('d M')); ?> - <?php echo e($endDate->format('d M Y')); ?>

                        </span>
                        <a href="<?php echo e(route('appointments.index', ['view' => $view, 'date' => $currentDate->copy()->addWeek()->format('Y-m-d')])); ?>" 
                           class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                            →
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('appointments.index', ['view' => $view, 'date' => $currentDate->copy()->subMonth()->format('Y-m-d')])); ?>" 
                           class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                            ←
                        </a>
                        <span class="px-4 font-medium">
                            <?php echo e($currentDate->format('F Y')); ?>

                        </span>
                        <a href="<?php echo e(route('appointments.index', ['view' => $view, 'date' => $currentDate->copy()->addMonth()->format('Y-m-d')])); ?>" 
                           class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                            →
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Botón hoy -->
            <a href="<?php echo e(route('appointments.index', ['view' => $view])); ?>" 
               class="px-4 py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                Hoy
            </a>
        </div>
    </div>

    <!-- Vista de calendario -->
    <?php if($view === 'week'): ?>
        <!-- Vista Semanal -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="grid grid-cols-8 border-b">
                <div class="p-3 text-center font-medium bg-gray-50">Hora</div>
                <?php for($i = 0; $i < 7; $i++): ?>
                    <?php
                        $day = $startDate->copy()->addDays($i);
                        $isToday = $day->isToday();
                    ?>
                    <div class="p-3 text-center font-medium bg-gray-50 <?php echo e($isToday ? 'bg-blue-100 text-blue-800' : ''); ?>">
                        <div><?php echo e($day->format('D')); ?></div>
                        <div class="text-2xl"><?php echo e($day->format('d')); ?></div>
                    </div>
                <?php endfor; ?>
            </div>

            <!-- Horas del día -->
            <?php for($hour = 8; $hour <= 18; $hour++): ?>
                <div class="grid grid-cols-8 border-b hover:bg-gray-50">
                    <div class="p-2 text-center text-sm text-gray-600 border-r">
                        <?php echo e(sprintf('%02d:00', $hour)); ?>

                    </div>
                    <?php for($i = 0; $i < 7; $i++): ?>
                        <?php
                            $day = $startDate->copy()->addDays($i);
                            $dayAppointments = $appointments->filter(function($apt) use ($day, $hour) {
                                return $apt->appointment_date->isSameDay($day) && 
                                       $apt->appointment_date->hour == $hour;
                            });
                        ?>
                        <div class="p-1 border-r min-h-[60px]">
                            <?php $__currentLoopData = $dayAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('appointments.show', $appt)); ?>" 
                                   class="block text-xs p-2 mb-1 rounded <?php echo e($appt->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : ($appt->status === 'completed' ? 'bg-green-100 text-green-800' : ($appt->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'))); ?> hover:shadow">
                                    <div class="font-semibold"><?php echo e($appt->appointment_date->format('H:i')); ?></div>
                                    <div><?php echo e($appt->pet->name); ?></div>
                                    <div class="truncate"><?php echo e($appt->client->name); ?></div>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endfor; ?>
        </div>
    <?php else: ?>
        <!-- Vista Mensual -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Días de la semana -->
            <div class="grid grid-cols-7 border-b">
                <?php $__currentLoopData = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-3 text-center font-medium bg-gray-50"><?php echo e($dayName); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Días del mes -->
            <?php
                $firstDay = $startDate->copy()->startOfMonth();
                $lastDay = $endDate->copy()->endOfMonth();
                $startDayOfWeek = $firstDay->dayOfWeek;
                $daysInMonth = $firstDay->daysInMonth;
                $weeks = ceil(($daysInMonth + $startDayOfWeek) / 7);
            ?>

            <?php for($week = 0; $week < $weeks; $week++): ?>
                <div class="grid grid-cols-7 border-b">
                    <?php for($dayOfWeek = 0; $dayOfWeek < 7; $dayOfWeek++): ?>
                        <?php
                            $dayNumber = ($week * 7 + $dayOfWeek) - $startDayOfWeek + 1;
                            $isValidDay = $dayNumber > 0 && $dayNumber <= $daysInMonth;
                            $currentDay = $isValidDay ? $firstDay->copy()->addDays($dayNumber - 1) : null;
                            $isToday = $currentDay && $currentDay->isToday();
                            $dayAppointments = $currentDay ? $appointments->filter(function($apt) use ($currentDay) {
                                return $apt->appointment_date->isSameDay($currentDay);
                            }) : collect();
                        ?>
                        <div class="p-2 border-r min-h-[120px] <?php echo e($isToday ? 'bg-blue-50' : ''); ?>">
                            <?php if($isValidDay): ?>
                                <div class="text-sm font-medium mb-2 <?php echo e($isToday ? 'text-blue-600' : 'text-gray-700'); ?>">
                                    <?php echo e($dayNumber); ?>

                                </div>
                                <div class="space-y-1">
                                    <?php $__currentLoopData = $dayAppointments->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <a href="<?php echo e(route('appointments.show', $appt)); ?>" 
                                           class="block text-xs p-1 rounded <?php echo e($appt->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : ($appt->status === 'completed' ? 'bg-green-100 text-green-800' : ($appt->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'))); ?> hover:shadow">
                                            <div><?php echo e($appt->appointment_date->format('H:i')); ?></div>
                                            <div class="truncate"><?php echo e($appt->pet->name); ?></div>
                                        </a>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($dayAppointments->count() > 3): ?>
                                        <div class="text-xs text-gray-500 text-center">
                                            +<?php echo e($dayAppointments->count() - 3); ?> más
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

    <!-- Leyenda de estados -->
    <div class="mt-6 flex justify-center space-x-6 text-sm">
        <div class="flex items-center">
            <div class="w-4 h-4 bg-yellow-100 border border-yellow-300 rounded mr-2"></div>
            <span>Pendiente</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 bg-blue-100 border border-blue-300 rounded mr-2"></div>
            <span>Confirmada</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 bg-green-100 border border-green-300 rounded mr-2"></div>
            <span>Completada</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 bg-red-100 border border-red-300 rounded mr-2"></div>
            <span>Cancelada</span>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Repos\vetehub\resources\views/appointments/index.blade.php ENDPATH**/ ?>