# Script para configurar la API Key de Brevo de manera interactiva

Write-Host "`n╔══════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║                                                          ║" -ForegroundColor Cyan
Write-Host "║       CONFIGURACIÓN DE BREVO API - INTERACTIVA          ║" -ForegroundColor Cyan
Write-Host "║                                                          ║" -ForegroundColor Cyan
Write-Host "╚══════════════════════════════════════════════════════════╝`n" -ForegroundColor Cyan

Write-Host "📋 PASOS PARA OBTENER TU API KEY:`n" -ForegroundColor Yellow

Write-Host "1️⃣  Abre tu navegador: https://app.brevo.com" -ForegroundColor White
Write-Host "   Inicia sesión con: alaned.gsilva@gmail.com`n" -ForegroundColor Gray

Write-Host "2️⃣  Ve a la sección:" -ForegroundColor White
Write-Host "   Settings (Configuración) → SMTP & API → API Keys`n" -ForegroundColor Gray

Write-Host "3️⃣  Si no tienes una API Key:" -ForegroundColor White
Write-Host "   Click en 'Generate a new API key'" -ForegroundColor Gray
Write-Host "   Nombre: VeteHub" -ForegroundColor Gray
Write-Host "   Click en 'Generate'`n" -ForegroundColor Gray

Write-Host "4️⃣  Copia la API Key (empieza con 'xkeysib-')`n" -ForegroundColor White

Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━`n" -ForegroundColor Gray

Write-Host "✏️  INGRESA TU API KEY:`n" -ForegroundColor Cyan

$apiKey = Read-Host "Pega tu API Key aquí"

if ([string]::IsNullOrWhiteSpace($apiKey)) {
    Write-Host "`n❌ No se ingresó ninguna API Key" -ForegroundColor Red
    Write-Host "Ejecuta este script de nuevo cuando tengas la API Key`n" -ForegroundColor Yellow
    exit 1
}

if (-not $apiKey.StartsWith("xkeysib-")) {
    Write-Host "`n⚠️  ADVERTENCIA: La API Key normalmente empieza con 'xkeysib-'" -ForegroundColor Yellow
    $continue = Read-Host "¿Estás seguro que es correcta? (s/n)"
    if ($continue -ne "s") {
        Write-Host "`nCancelado. Ejecuta el script de nuevo.`n" -ForegroundColor Gray
        exit 1
    }
}

Write-Host "`n🔄 Guardando API Key en .env...`n" -ForegroundColor Cyan

# Leer el archivo .env
$envContent = Get-Content .env -Raw

# Actualizar o agregar BREVO_API_KEY
if ($envContent -match "BREVO_API_KEY=.*") {
    $envContent = $envContent -replace "BREVO_API_KEY=.*", "BREVO_API_KEY=$apiKey"
} else {
    $envContent += "`nBREVO_API_KEY=$apiKey`n"
}

# Guardar el archivo
$envContent | Set-Content .env

Write-Host "✅ API Key guardada en .env" -ForegroundColor Green

# Limpiar caché
Write-Host "🔄 Limpiando caché de configuración...`n" -ForegroundColor Cyan
php artisan config:clear | Out-Null

Write-Host "✅ Caché limpiado" -ForegroundColor Green

Write-Host "`n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━`n" -ForegroundColor Gray

Write-Host "🧪 Probando conexión con Brevo API...`n" -ForegroundColor Cyan

# Ejecutar script de configuración
php configurar_brevo_api.php

Write-Host "`n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━`n" -ForegroundColor Gray

Write-Host "✨ ¡Configuración completada!" -ForegroundColor Green
Write-Host "`nPara enviar recordatorios, usa:" -ForegroundColor White
Write-Host "   php enviar_recordatorios_api.php`n" -ForegroundColor Yellow
