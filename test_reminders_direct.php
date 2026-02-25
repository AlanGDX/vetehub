<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Messages\MailMessage;

// Establecer temporalmente el mailer a 'log'
config(['mail.default' => 'log']);

$now = Carbon::now();
$tomorrow = $now->copy()->addDay();

echo "Buscando citas entre {$now->format('d/m/Y H:i')} y {$tomorrow->format('d/m/Y H:i')}\n\n";

$appointments = Appointment::with(['client', 'pet', 'user'])
    ->whereBetween('appointment_date', [$now, $tomorrow])
    ->whereNotIn('status', ['cancelled', 'completed'])
    ->get();

if ($appointments->isEmpty()) {
    echo "❌ No hay citas para recordar.\n";
    exit;
}

echo "✓ Encontradas {$appointments->count()} cita(s)\n\n";

foreach ($appointments as $appointment) {
    $formattedDate = $appointment->appointment_date->format('d/m/Y');
    $formattedTime = $appointment->appointment_date->format('H:i');
    
    echo "📧 Cita #{$appointment->id}:\n";
    echo "   Cliente: {$appointment->client->name} <{$appointment->client->email}>\n";
    echo "   Mascota: {$appointment->pet->name}\n";
    echo "   Veterinario: {$appointment->user->name} <{$appointment->user->email}>\n";
    echo "   Fecha: {$formattedDate} a las {$formattedTime}\n\n";
    
    // Crear y enviar correo al cliente
    $mailToClient = (new MailMessage)
        ->subject('Recordatorio: Cita para ' . $appointment->pet->name)
        ->greeting('¡Hola ' . $appointment->client->name . '!')
        ->line('Te recordamos que tienes una cita programada para tu mascota ' . $appointment->pet->name . '.')
        ->line('**Fecha:** ' . $formattedDate)
        ->line('**Hora:** ' . $formattedTime)
        ->line('**Veterinario:** Dr./Dra. ' . $appointment->user->name)
        ->line('**Motivo:** ' . $appointment->reason)
        ->line('Por favor, llega 10 minutos antes de tu cita.')
        ->action('Ver Detalles', url('/dashboard'))
        ->line('Si necesitas cancelar o reprogramar, contáctanos lo antes posible.')
        ->salutation('¡Nos vemos pronto! - Equipo VeteHub');
    
    Mail::to($appointment->client->email)->send(
        new \Illuminate\Mail\Mailable(
            new class($mailToClient) {
                public $mailMessage;
                public function __construct($mailMessage) {
                    $this->mailMessage = $mailMessage;
                }
            }
        )
    );
    
    echo "   ✅ Correo preparado para: {$appointment->client->name}\n";
    echo "   Asunto: Recordatorio: Cita para {$appointment->pet->name}\n";
    echo "   Contenido: Recordatorio de cita el {$formattedDate} a las {$formattedTime}\n\n";
}

echo "\n✅ Proceso completado\n";
echo "📝 Nota: Los correos están en modo 'log' porque hay un problema de conectividad con Brevo.\n\n";
echo "🔧 SOLUCIÓN al problema de conectividad:\n";
echo "   El puerto 587 está bloqueado por firewall/antivirus.\n";
echo "   Para resolver:\n";
echo "   1. Configura tu firewall para permitir conexiones salientes al puerto 587\n";
echo "   2. O contacta al administrador de red para habilitar SMTP saliente\n";
echo "   3. O usa el puerto alternativo 2525 de Brevo\n\n";
echo "Para cambiar al puerto 2525, edita el .env:\n";
echo "   MAIL_PORT=2525\n";
