<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Appointment;
use App\Notifications\AppointmentReminder;
use Carbon\Carbon;

// Establecer temporalmente el mailer a 'log' para probar
config(['mail.default' => 'log']);

$now = Carbon::now();
$tomorrow = $now->copy()->addDay();

echo "Buscando citas entre {$now} y {$tomorrow}\n\n";

// Buscar citas para las próximas 24 horas
$appointments = Appointment::with(['client', 'pet', 'user'])
    ->whereBetween('appointment_date', [$now, $tomorrow])
    ->whereNotIn('status', ['cancelled', 'completed'])
    ->get();

if ($appointments->isEmpty()) {
    echo "❌ No hay citas para recordar en las próximas 24 horas.\n";
    exit;
}

echo "✓ Encontradas {$appointments->count()} cita(s)\n\n";

foreach ($appointments as $appointment) {
    echo "📧 Cita #{$appointment->id}:\n";
    echo "   Cliente: {$appointment->client->name} <{$appointment->client->email}>\n";
    echo "   Mascota: {$appointment->pet->name}\n";
    echo "   Veterinario: {$appointment->user->name} <{$appointment->user->email}>\n";
    echo "   Fecha: {$appointment->appointment_date->format('d/m/Y H:i')}\n";
    echo "   Estado: {$appointment->status}\n";
    
    try {
        // Notificar al cliente
        $appointment->client->notify(
            new AppointmentReminder($appointment, 'client')
        );
        echo "   ✓ Notificación enviada al cliente\n";
        
        // Notificar al veterinario
        $appointment->user->notify(
            new AppointmentReminder($appointment, 'user')
        );
        echo "   ✓ Notificación enviada al veterinario\n";
        
    } catch (\Exception $e) {
        echo "   ❌ Error: {$e->getMessage()}\n";
    }
    
    echo "\n";
}

echo "✅ Proceso completado. Los correos se guardaron en: storage/logs/laravel.log\n";
echo "\nPara ver los correos generados, busca '[mail]' en el archivo de log.\n";
