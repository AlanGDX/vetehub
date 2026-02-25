<?php

/**
 * Script para verificar el estado de los correos enviados a través de Brevo
 * Muestra las estadísticas de entrega de los últimos correos
 */

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\HttpClient\HttpClient;

// Cargar configuración
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$apiKey = $_ENV['BREVO_API_KEY'] ?? null;

if (!$apiKey) {
    echo "❌ Error: BREVO_API_KEY no configurada en .env\n";
    exit(1);
}

$client = HttpClient::create();

echo "\n";
echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║                                                          ║\n";
echo "║      📊 VERIFICAR ESTADO DE CORREOS EN BREVO           ║\n";
echo "║                                                          ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n";
echo "\n";

try {
    // Obtener estadísticas de la cuenta
    echo "🔍 Consultando estadísticas de Brevo...\n\n";
    
    $response = $client->request('GET', 'https://api.brevo.com/v3/smtp/statistics/events', [
        'headers' => [
            'api-key' => $apiKey,
            'Content-Type' => 'application/json',
        ],
        'query' => [
            'limit' => 10,
            'offset' => 0,
            'startDate' => date('Y-m-d'),
            'endDate' => date('Y-m-d'),
        ],
    ]);

    if ($response->getStatusCode() === 200) {
        $data = $response->toArray();
        
        echo "✅ Conexión exitosa con Brevo API\n\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        // Obtener información de la cuenta
        $accountResponse = $client->request('GET', 'https://api.brevo.com/v3/account', [
            'headers' => [
                'api-key' => $apiKey,
                'Content-Type' => 'application/json',
            ],
        ]);
        
        if ($accountResponse->getStatusCode() === 200) {
            $accountData = $accountResponse->toArray();
            
            echo "📧 INFORMACIÓN DE LA CUENTA:\n";
            echo "   • Email: " . ($accountData['email'] ?? 'N/A') . "\n";
            echo "   • Nombre: " . ($accountData['firstName'] ?? '') . " " . ($accountData['lastName'] ?? '') . "\n";
            
            if (isset($accountData['plan'])) {
                echo "   • Plan: " . $accountData['plan'][0]['type'] . "\n";
                if (isset($accountData['plan'][0]['credits'])) {
                    echo "   • Créditos restantes: " . $accountData['plan'][0]['credits'] . "\n";
                }
            }
            
            echo "\n";
        }
        
        // Obtener estadísticas de correos transaccionales
        echo "📊 ESTADÍSTICAS DE HOY:\n";
        
        $statsResponse = $client->request('GET', 'https://api.brevo.com/v3/smtp/statistics/aggregatedReport', [
            'headers' => [
                'api-key' => $apiKey,
                'Content-Type' => 'application/json',
            ],
            'query' => [
                'startDate' => date('Y-m-d'),
                'endDate' => date('Y-m-d'),
            ],
        ]);
        
        if ($statsResponse->getStatusCode() === 200) {
            $statsData = $statsResponse->toArray();
            
            if (isset($statsData['range']) && !empty($statsData['range'])) {
                $todayStats = $statsData['range'][date('Y-m-d')] ?? null;
                
                if ($todayStats) {
                    echo "   • Enviados: " . ($todayStats['requests'] ?? 0) . "\n";
                    echo "   • Entregados: " . ($todayStats['delivered'] ?? 0) . "\n";
                    echo "   • Rebotados (hard): " . ($todayStats['hardBounces'] ?? 0) . "\n";
                    echo "   • Rebotados (soft): " . ($todayStats['softBounces'] ?? 0) . "\n";
                    echo "   • Abiertos: " . ($todayStats['uniqueOpens'] ?? 0) . "\n";
                    echo "   • Clicks: " . ($todayStats['uniqueClicks'] ?? 0) . "\n";
                    echo "   • Spam: " . ($todayStats['complaints'] ?? 0) . "\n";
                } else {
                    echo "   • No hay estadísticas disponibles para hoy todavía\n";
                    echo "   • (Los datos pueden tardar unos minutos en actualizarse)\n";
                }
            } else {
                echo "   • No hay estadísticas disponibles para hoy todavía\n";
                echo "   • (Los datos pueden tardar unos minutos en actualizarse)\n";
            }
        }
        
        echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        // Información importante
        echo "⚠️  IMPORTANTE:\n";
        echo "   1. Las estadísticas pueden tardar 5-15 minutos en actualizarse\n";
        echo "   2. Revisa la carpeta de SPAM en Gmail\n";
        echo "   3. Verifica que el remitente esté confirmado en Brevo\n\n";
        
        echo "🌐 PARA VER DETALLES COMPLETOS:\n";
        echo "   → Abre: https://app.brevo.com/log\n";
        echo "   → Busca los correos enviados a las 20:51 horas\n\n";
        
    } else {
        echo "⚠️  No se pudieron obtener las estadísticas\n";
        echo "   Código de respuesta: " . $response->getStatusCode() . "\n\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error al consultar Brevo API:\n";
    echo "   " . $e->getMessage() . "\n\n";
}

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "\n";
