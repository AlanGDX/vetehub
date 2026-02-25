@extends('layouts.app')

@section('title', 'Agenda de Citas - VeteHub')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Agenda de Citas</h1>
        <a href="{{ route('appointments.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
            + Nueva Cita
        </a>
    </div>

    <!-- Controles de navegación -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <!-- Botones de vista -->
                <div class="flex space-x-2">
                    <a href="{{ route('appointments.index', ['view' => 'week', 'date' => $currentDate->format('Y-m-d')]) }}" 
                       class="px-4 py-2 rounded {{ $view === 'week' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                        Semana
                    </a>
                    <a href="{{ route('appointments.index', ['view' => 'month', 'date' => $currentDate->format('Y-m-d')]) }}" 
                       class="px-4 py-2 rounded {{ $view === 'month' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">
                        Mes
                    </a>
                </div>

                <!-- Navegación de fechas -->
                <div class="flex items-center space-x-2">
                    @if($view === 'week')
                        <a href="{{ route('appointments.index', ['view' => $view, 'date' => $currentDate->copy()->subWeek()->format('Y-m-d')]) }}" 
                           class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                            ←
                        </a>
                        <span class="px-4 font-medium">
                            {{ $startDate->format('d M') }} - {{ $endDate->format('d M Y') }}
                        </span>
                        <a href="{{ route('appointments.index', ['view' => $view, 'date' => $currentDate->copy()->addWeek()->format('Y-m-d')]) }}" 
                           class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                            →
                        </a>
                    @else
                        <a href="{{ route('appointments.index', ['view' => $view, 'date' => $currentDate->copy()->subMonth()->format('Y-m-d')]) }}" 
                           class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                            ←
                        </a>
                        <span class="px-4 font-medium">
                            {{ $currentDate->format('F Y') }}
                        </span>
                        <a href="{{ route('appointments.index', ['view' => $view, 'date' => $currentDate->copy()->addMonth()->format('Y-m-d')]) }}" 
                           class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                            →
                        </a>
                    @endif
                </div>
            </div>

            <!-- Botón hoy -->
            <a href="{{ route('appointments.index', ['view' => $view]) }}" 
               class="px-4 py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                Hoy
            </a>
        </div>
    </div>

    <!-- Vista de calendario -->
    @if($view === 'week')
        <!-- Vista Semanal -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="grid grid-cols-8 border-b">
                <div class="p-3 text-center font-medium bg-gray-50">Hora</div>
                @for($i = 0; $i < 7; $i++)
                    @php
                        $day = $startDate->copy()->addDays($i);
                        $isToday = $day->isToday();
                    @endphp
                    <div class="p-3 text-center font-medium bg-gray-50 {{ $isToday ? 'bg-blue-100 text-blue-800' : '' }}">
                        <div>{{ $day->format('D') }}</div>
                        <div class="text-2xl">{{ $day->format('d') }}</div>
                    </div>
                @endfor
            </div>

            <!-- Horas del día -->
            @for($hour = 8; $hour <= 18; $hour++)
                <div class="grid grid-cols-8 border-b hover:bg-gray-50">
                    <div class="p-2 text-center text-sm text-gray-600 border-r">
                        {{ sprintf('%02d:00', $hour) }}
                    </div>
                    @for($i = 0; $i < 7; $i++)
                        @php
                            $day = $startDate->copy()->addDays($i);
                            $dayAppointments = $appointments->filter(function($apt) use ($day, $hour) {
                                return $apt->appointment_date->isSameDay($day) && 
                                       $apt->appointment_date->hour == $hour;
                            });
                        @endphp
                        <div class="p-1 border-r min-h-[60px]">
                            @foreach($dayAppointments as $appt)
                                <a href="{{ route('appointments.show', $appt) }}" 
                                   class="block text-xs p-2 mb-1 rounded {{ $appt->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : ($appt->status === 'completed' ? 'bg-green-100 text-green-800' : ($appt->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }} hover:shadow">
                                    <div class="font-semibold">{{ $appt->appointment_date->format('H:i') }}</div>
                                    <div>{{ $appt->pet->name }}</div>
                                    <div class="truncate">{{ $appt->client->name }}</div>
                                </a>
                            @endforeach
                        </div>
                    @endfor
                </div>
            @endfor
        </div>
    @else
        <!-- Vista Mensual -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Días de la semana -->
            <div class="grid grid-cols-7 border-b">
                @foreach(['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'] as $dayName)
                    <div class="p-3 text-center font-medium bg-gray-50">{{ $dayName }}</div>
                @endforeach
            </div>

            <!-- Días del mes -->
            @php
                $firstDay = $startDate->copy()->startOfMonth();
                $lastDay = $endDate->copy()->endOfMonth();
                $startDayOfWeek = $firstDay->dayOfWeek;
                $daysInMonth = $firstDay->daysInMonth;
                $weeks = ceil(($daysInMonth + $startDayOfWeek) / 7);
            @endphp

            @for($week = 0; $week < $weeks; $week++)
                <div class="grid grid-cols-7 border-b">
                    @for($dayOfWeek = 0; $dayOfWeek < 7; $dayOfWeek++)
                        @php
                            $dayNumber = ($week * 7 + $dayOfWeek) - $startDayOfWeek + 1;
                            $isValidDay = $dayNumber > 0 && $dayNumber <= $daysInMonth;
                            $currentDay = $isValidDay ? $firstDay->copy()->addDays($dayNumber - 1) : null;
                            $isToday = $currentDay && $currentDay->isToday();
                            $dayAppointments = $currentDay ? $appointments->filter(function($apt) use ($currentDay) {
                                return $apt->appointment_date->isSameDay($currentDay);
                            }) : collect();
                        @endphp
                        <div class="p-2 border-r min-h-[120px] {{ $isToday ? 'bg-blue-50' : '' }}">
                            @if($isValidDay)
                                <div class="text-sm font-medium mb-2 {{ $isToday ? 'text-blue-600' : 'text-gray-700' }}">
                                    {{ $dayNumber }}
                                </div>
                                <div class="space-y-1">
                                    @foreach($dayAppointments->take(3) as $appt)
                                        <a href="{{ route('appointments.show', $appt) }}" 
                                           class="block text-xs p-1 rounded {{ $appt->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : ($appt->status === 'completed' ? 'bg-green-100 text-green-800' : ($appt->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }} hover:shadow">
                                            <div>{{ $appt->appointment_date->format('H:i') }}</div>
                                            <div class="truncate">{{ $appt->pet->name }}</div>
                                        </a>
                                    @endforeach
                                    @if($dayAppointments->count() > 3)
                                        <div class="text-xs text-gray-500 text-center">
                                            +{{ $dayAppointments->count() - 3 }} más
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>
            @endfor
        </div>
    @endif

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
@endsection
