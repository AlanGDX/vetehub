<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Appointment;
use App\Notifications\AppointmentReminder;
use Carbon\Carbon;

echo "═══════════════════════════════════════════════════════\n";
echo "  ENVÍO DE RECORDATORIOS - VeteHub\n";
echo "═══════════════════════════════════════════════════════\n\n";

// Verificar configuración
$mailer = config('mail.default');
$host = config('mail.mailers.smtp.host');
$port = config('mail.mailers.smtp.port');

echo "📋 Configuración actual:\n";
echo "   Mailer: {$mailer}\n";
if ($mailer === 'smtp') {
    echo "   Host: {$host}\n";
    echo "   Puerto: {$port}\n";
}
echo "\n";

$now = Carbon::now();
$tomorrow = $now->copy()->addDay();

echo "🔍 Buscando citas entre {$now->format('d/m/Y H:i')} y {$tomorrow->format('d/m/Y H:i')}...\n\n";

// Buscar citas
$appointments = Appointment::with(['client', 'pet', 'user'])
    ->whereBetween('appointment_date', [$now, $tomorrow])
    ->whereNotIn('status', ['cancelled', 'completed'])
    ->get();

if ($appointments->isEmpty()) {
    echo "❌ No hay citas programadas para las próximas 24 horas.\n";
    exit(0);
}

echo "✅ Encontradas {$appointments->count()} cita(s):\n\n";

foreach ($appointments as $appointment) {
    $formattedDate = $appointment->appointment_date->format('d/m/Y H:i');
    echo "📌 Cita #{$appointment->id}:\n";
    echo "   Cliente: {$appointment->client->name} <{$appointment->client->email}>\n";
    echo "   Mascota: {$appointment->pet->name} ({$appointment->pet->species})\n";
    echo "   Veterinario: {$appointment->user->name} <{$appointment->user->email}>\n";
    echo "   Fecha: {$formattedDate}\n";
    echo "   Estado: {$appointment->status}\n\n";
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
echo "📧 Enviando recordatorios...\n\n";

$sent = 0;
$failed = 0;
$queued = 0;

foreach ($appointments as $appointment) {
    echo "Procesando cita #{$appointment->id}...\n";
    
    try {
        // Crear temporalmente las notificaciones sin cola
        $clientNotification = new class($appointment, 'client') extends AppointmentReminder {
            public function __construct($appointment, $recipientType) {
                parent::__construct($appointment, $recipientType);
            }
        };
        
        $userNotification = new class($appointment, 'user') extends AppointmentReminder {
            public function __construct($appointment, $recipientType) {
                parent::__construct($appointment, $recipientType);
            }
        };
        
        // Intentar envío directo
        $appointment->client->notifyNow($clientNotification);
        $appointment->user->notifyNow($userNotification);
        
        echo "  ✅ Correos enviados correctamente\n";
        echo "     → Cliente: {$appointment->client->email}\n";
        echo "     → Veterinario: {$appointment->user->email}\n\n";
        $sent++;
        
    } catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
        echo "  ⚠️  Error de conexión SMTP\n";
        echo "     → Agregando a la cola de trabajos...\n";
        
        // Si falla, agregar a la cola
        try {
            $appointment->client->notify(new AppointmentReminder($appointment, 'client'));
            $appointment->user->notify(new AppointmentReminder($appointment, 'user'));
            echo "  ✅ Agregado a la cola correctamente\n\n";
            $queued++;
        } catch (\Exception $qe) {
            echo "  ❌ Error al agregar a la cola: {$qe->getMessage()}\n\n";
            $failed++;
        }
        
    } catch (\Exception $e) {
        echo "  ❌ Error inesperado: {$e->getMessage()}\n\n";
        $failed++;
    }
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
echo "📊 RESUMEN:\n";
echo "   ✅ Enviados: {$sent}\n";
echo "   ⏳ En cola: {$queued}\n";
echo "   ❌ Fallidos: {$failed}\n\n";

if ($queued > 0) {
    echo "⚠️  HAY TRABAJOS EN COLA\n\n";
    echo "Para procesar los trabajos en cola, ejecuta:\n";
    echo "   php artisan queue:work --stop-when-empty\n\n";
    
    if ($mailer === 'smtp') {
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "🔧 PROBLEMA DE CONEXIÓN SMTP DETECTADO\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        echo "El puerto {$port} está bloqueado por firewall o antivirus.\n\n";
        echo "SOLUCIÓN 1 - Configurar Firewall (RECOMENDADO):\n";
        echo "   Ejecuta como Administrador:\n";
        echo "   → PowerShell: .\\Configure-Firewall.ps1\n";
        echo "   → Batch: configure_firewall.bat\n\n";
        echo "SOLUCIÓN 2 - Modo Desarrollo (Temporal):\n";
        echo "   1. Edita .env y cambia: MAIL_MAILER=log\n";
        echo "   2. Ejecuta: php artisan config:clear\n";
        echo "   3. Ejecuta este script de nuevo\n";
        echo "   4. Los correos se guardarán en: storage/logs/laravel.log\n\n";
        echo "Más información: Ver SOLUCION_RAPIDA.md\n\n";
    }
}

if ($sent > 0) {
    echo "✅ ¡Correos enviados exitosamente!\n\n";
    if ($mailer === 'log') {
        echo "Los correos se guardaron en: storage/logs/laravel.log\n";
        echo "Para verlos, ejecuta:\n";
        echo "   Get-Content storage/logs/laravel.log | Select-Object -Last 200\n\n";
    } else {
        echo "Los destinatarios deberían recibir los correos en breve.\n\n";
    }
}

echo "═══════════════════════════════════════════════════════\n";
