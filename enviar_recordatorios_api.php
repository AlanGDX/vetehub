<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Appointment;
use App\Services\BrevoMailService;
use Carbon\Carbon;

echo "═══════════════════════════════════════════════════════\n";
echo "  ENVÍO DE RECORDATORIOS - VeteHub (API Brevo)\n";
echo "═══════════════════════════════════════════════════════\n\n";

// Verificar configuración
$brevoService = new BrevoMailService();

if (!$brevoService->isConfigured()) {
    echo "❌ ERROR: Brevo API no está configurada\n\n";
    echo "Por favor, ejecuta primero: php configurar_brevo_api.php\n\n";
    exit(1);
}

echo "📋 Configuración:\n";
echo "   Método: Brevo API (HTTPS)\n";
echo "   Estado: ✅ Configurado\n\n";

$now = Carbon::now();
$tomorrow = $now->copy()->addDay();

echo "🔍 Buscando citas entre {$now->format('d/m/Y H:i')} y {$tomorrow->format('d/m/Y H:i')}...\n\n";

// Buscar citas
$appointments = Appointment::with(['client', 'pet', 'user'])
    ->whereBetween('appointment_date', [$now, $tomorrow])
    ->whereNotIn('status', ['cancelled', 'completed'])
    ->get();

if ($appointments->isEmpty()) {
    echo "❌ No hay citas programadas para las próximas 24 horas.\n\n";
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
echo "📧 Enviando recordatorios vía Brevo API...\n\n";

$sent = 0;
$failed = 0;

foreach ($appointments as $appointment) {
    echo "Procesando cita #{$appointment->id}...\n";
    
    $formattedDate = $appointment->appointment_date->format('d/m/Y');
    $formattedTime = $appointment->appointment_date->format('H:i');
    
    // Correo para el cliente
    $clientHtml = "
    <h1>¡Hola {$appointment->client->name}!</h1>
    <p>Te recordamos que tienes una cita programada para tu mascota <strong>{$appointment->pet->name}</strong>.</p>
    <ul>
        <li><strong>Fecha:</strong> {$formattedDate}</li>
        <li><strong>Hora:</strong> {$formattedTime}</li>
        <li><strong>Veterinario:</strong> Dr./Dra. {$appointment->user->name}</li>
        <li><strong>Motivo:</strong> " . ($appointment->reason ?? 'Consulta general') . "</li>
    </ul>
    <p>Por favor, llega 10 minutos antes de tu cita.</p>
    <p>Si necesitas cancelar o reprogramar, contáctanos lo antes posible.</p>
    <p>¡Nos vemos pronto!</p>
    <p><em>Equipo VeteHub</em></p>
    ";
    
    // Enviar al cliente con replyTo del veterinario
    $clientResult = $brevoService->sendAppointmentReminder(
        $appointment->client->email,
        $appointment->client->name,
        "Recordatorio: Cita para {$appointment->pet->name}",
        $clientHtml,
        null, // textContent
        [ // replyTo - para que las respuestas lleguen al veterinario
            'email' => $appointment->user->email,
            'name' => "Dr./Dra. {$appointment->user->name}"
        ]
    );
    
    // Correo para el veterinario
    $vetHtml = "
    <h1>¡Hola Dr./Dra. {$appointment->user->name}!</h1>
    <p>Recordatorio de cita programada:</p>
    <ul>
        <li><strong>Cliente:</strong> {$appointment->client->name}</li>
        <li><strong>Mascota:</strong> {$appointment->pet->name} ({$appointment->pet->species})</li>
        <li><strong>Fecha:</strong> {$formattedDate}</li>
        <li><strong>Hora:</strong> {$formattedTime}</li>
        <li><strong>Motivo:</strong> " . ($appointment->reason ?? 'Consulta general') . "</li>
    </ul>
    <p>Revisa el historial de la mascota antes de la cita.</p>
    <p><em>Equipo VeteHub</em></p>
    ";
    
    // Enviar al veterinario con replyTo del cliente
    $vetResult = $brevoService->sendAppointmentReminder(
        $appointment->user->email,
        $appointment->user->name,
        "Recordatorio: Cita con {$appointment->client->name}",
        $vetHtml,
        null, // textContent
        [ // replyTo - para que las respuestas lleguen al cliente
            'email' => $appointment->client->email,
            'name' => $appointment->client->name
        ]
    );
    
    if ($clientResult['success'] && $vetResult['success']) {
        echo "  ✅ Correos enviados correctamente\n";
        echo "     → Cliente: {$appointment->client->email}\n";
        echo "     → Veterinario: {$appointment->user->email}\n\n";
        $sent++;
    } else {
        echo "  ❌ Error al enviar correos\n";
        if (!$clientResult['success']) {
            echo "     → Cliente: {$clientResult['error']}\n";
        }
        if (!$vetResult['success']) {
            echo "     → Veterinario: {$vetResult['error']}\n";
        }
        echo "\n";
        $failed++;
    }
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
echo "📊 RESUMEN:\n";
echo "   ✅ Enviados: {$sent}\n";
echo "   ❌ Fallidos: {$failed}\n\n";

if ($sent > 0) {
    echo "✅ ¡Correos enviados exitosamente vía Brevo API!\n";
    echo "   Los destinatarios recibirán los correos en breve.\n\n";
}

echo "═══════════════════════════════════════════════════════\n";
