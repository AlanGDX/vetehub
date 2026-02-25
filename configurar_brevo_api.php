<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\BrevoMailService;
use App\Models\Appointment;
use Illuminate\Support\Facades\Mail;

echo "═══════════════════════════════════════════════════════\n";
echo "  CONFIGURACIÓN Y PRUEBA DE BREVO API\n";
echo "═══════════════════════════════════════════════════════\n\n";

// Verificar si la API key está configurada
$apiKey = env('BREVO_API_KEY');

if (empty($apiKey)) {
    echo "⚠️  API KEY NO CONFIGURADA\n\n";
    echo "Para configurar Brevo API:\n\n";
    echo "1. Ve a: https://app.brevo.com\n";
    echo "2. Inicia sesión con: " . env('MAIL_USERNAME') . "\n";
    echo "3. Navega a: Settings → SMTP & API → API Keys\n";
    echo "4. Copia tu API Key (empieza con 'xkeysib-')\n";
    echo "5. Edita el archivo .env:\n";
    echo "   BREVO_API_KEY=tu_api_key_aqui\n\n";
    echo "6. Ejecuta: php artisan config:clear\n";
    echo "7. Ejecuta este script de nuevo\n\n";
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    echo "💡 NOTA: La API key es diferente de la contraseña SMTP\n";
    echo "   Es una clave que empieza con 'xkeysib-'\n\n";
    exit(1);
}

echo "✓ API Key encontrada: " . substr($apiKey, 0, 20) . "...\n\n";

// Probar conexión con Brevo
echo "🔍 Probando conexión con Brevo API...\n\n";

$brevoService = new BrevoMailService();
$testResult = $brevoService->testConnection();

if ($testResult['success']) {
    echo "✅ Conexión exitosa con Brevo API\n";
    echo "   Cuenta: " . ($testResult['account'] ?? 'Verificada') . "\n\n";
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    // Buscar cita para prueba
    $appointment = Appointment::with(['client', 'pet', 'user'])
        ->whereDate('appointment_date', '2026-02-26')
        ->first();
    
    if ($appointment) {
        echo "📧 ¿Quieres enviar un correo de prueba?\n\n";
        echo "Se enviará a: " . $appointment->client->email . "\n";
        echo "Asunto: Recordatorio: Cita para " . $appointment->pet->name . "\n\n";
        
        echo "Presiona Enter para enviar o Ctrl+C para cancelar...\n";
        fgets(STDIN);
        
        echo "\n📤 Enviando correo de prueba...\n\n";
        
        // Crear contenido del correo
        $htmlContent = "
        <h1>¡Hola " . $appointment->client->name . "!</h1>
        <p>Te recordamos que tienes una cita programada para tu mascota <strong>" . $appointment->pet->name . "</strong>.</p>
        <ul>
            <li><strong>Fecha:</strong> " . $appointment->appointment_date->format('d/m/Y') . "</li>
            <li><strong>Hora:</strong> " . $appointment->appointment_date->format('H:i') . "</li>
            <li><strong>Veterinario:</strong> Dr./Dra. " . $appointment->user->name . "</li>
            <li><strong>Motivo:</strong> " . ($appointment->reason ?? 'Consulta general') . "</li>
        </ul>
        <p>Por favor, llega 10 minutos antes de tu cita.</p>
        <p>Si necesitas cancelar o reprogramar, contáctanos lo antes posible.</p>
        <p>¡Nos vemos pronto!</p>
        <p><em>Equipo VeteHub</em></p>
        ";
        
        $result = $brevoService->sendAppointmentReminder(
            $appointment->client->email,
            $appointment->client->name,
            'Recordatorio: Cita para ' . $appointment->pet->name,
            $htmlContent
        );
        
        if ($result['success']) {
            echo "✅ ¡Correo enviado exitosamente!\n";
            echo "   Message ID: " . ($result['message_id'] ?? 'N/A') . "\n\n";
            echo "Revisa la bandeja de entrada de: " . $appointment->client->email . "\n\n";
        } else {
            echo "❌ Error al enviar correo:\n";
            echo "   " . $result['error'] . "\n\n";
        }
        
    } else {
        echo "ℹ️  No hay citas para probar\n\n";
    }
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    echo "✅ Sistema de Brevo API configurado correctamente\n";
    echo "   Puedes usar: php enviar_recordatorios_api.php\n\n";
    
} else {
    echo "❌ Error de conexión con Brevo API:\n";
    echo "   " . $testResult['error'] . "\n\n";
    echo "Verifica que:\n";
    echo "  1. La API Key sea correcta\n";
    echo "  2. La API Key esté activa en tu cuenta de Brevo\n";
    echo "  3. Tengas conexión a Internet\n\n";
}
