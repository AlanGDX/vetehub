<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Mail;
use App\Models\Appointment;

echo "🔍 Verificando configuración SMTP...\n\n";

$config = [
    'Driver' => config('mail.default'),
    'Host' => config('mail.mailers.smtp.host'),
    'Port' => config('mail.mailers.smtp.port'),
    'Username' => config('mail.mailers.smtp.username'),
    'Encryption' => config('mail.mailers.smtp.encryption'),
    'From Address' => config('mail.from.address'),
    'From Name' => config('mail.from.name'),
];

foreach ($config as $key => $value) {
    echo "  ✓ {$key}: {$value}\n";
}

echo "\n📧 Intentando enviar correo de prueba...\n\n";

try {
    // Buscar la cita del 26 de febrero
    $appointment = Appointment::with(['client', 'pet', 'user'])
        ->whereDate('appointment_date', '2026-02-26')
        ->first();
    
    if (!$appointment) {
        echo "❌ No se encontró la cita.\n";
        exit(1);
    }
    
    echo "📋 Cita encontrada:\n";
    echo "   Cliente: {$appointment->client->name} <{$appointment->client->email}>\n";
    echo "   Mascota: {$appointment->pet->name}\n";
    echo "   Fecha: {$appointment->appointment_date->format('d/m/Y H:i')}\n\n";
    
    // Intentar enviar un correo de prueba
    echo "📤 Enviando correo de prueba al cliente...\n";
    
    Mail::raw("Este es un correo de prueba de VeteHub.\n\nSi recibes este mensaje, la conexión con Brevo funciona correctamente.", function ($message) use ($appointment) {
        $message->to($appointment->client->email)
                ->subject('Prueba de Conexión - VeteHub');
    });
    
    echo "✅ ¡Correo enviado exitosamente!\n";
    echo "✓ La conexión con Brevo está funcionando correctamente.\n";
    echo "✓ Revisa la bandeja de entrada de: {$appointment->client->email}\n\n";
    
} catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
    echo "❌ Error de conexión SMTP:\n";
    echo "   " . $e->getMessage() . "\n\n";
    
    echo "🔧 Soluciones:\n";
    echo "   1. Verifica que el puerto 2525 no esté bloqueado\n";
    echo "   2. Configura tu firewall para permitir conexiones salientes\n";
    echo "   3. Desactiva temporalmente el antivirus para probar\n";
    echo "   4. Contacta al administrador de red si estás en una red corporativa\n\n";
    
    exit(1);
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
