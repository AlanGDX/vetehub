# ==============================================
# Configuración de Firewall para VeteHub + Brevo
# ==============================================
# Este script configura Windows Firewall para permitir
# conexiones SMTP salientes requeridas por Brevo

Write-Host "============================================" -ForegroundColor Cyan
Write-Host " Configuración de Firewall para VeteHub" -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan
Write-Host ""

# Verificar si se ejecuta como administrador
$isAdmin = ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)

if (-not $isAdmin) {
    Write-Host "❌ ERROR: Este script requiere permisos de administrador" -ForegroundColor Red
    Write-Host ""
    Write-Host "Por favor, ejecuta PowerShell como Administrador:" -ForegroundColor Yellow
    Write-Host "1. Busca 'PowerShell' en el menú de inicio" -ForegroundColor Yellow
    Write-Host "2. Click derecho → 'Ejecutar como administrador'" -ForegroundColor Yellow
    Write-Host "3. Navega a esta carpeta y ejecuta el script de nuevo" -ForegroundColor Yellow
    Write-Host ""
    Pause
    exit 1
}

Write-Host "✓ Permisos de administrador verificados" -ForegroundColor Green
Write-Host ""

# Puertos SMTP de Brevo
$ports = @(587, 2525, 465)
$portNames = @{
    587 = "STARTTLS"
    2525 = "Alternativo"
    465 = "SSL/TLS"
}

Write-Host "Configurando reglas de firewall para Brevo..." -ForegroundColor Yellow
Write-Host ""

$success = 0
$failed = 0

foreach ($port in $ports) {
    $ruleName = "VeteHub - Brevo SMTP Saliente ($port - $($portNames[$port]))"
    
    try {
        # Eliminar regla existente si existe
        Remove-NetFirewallRule -DisplayName $ruleName -ErrorAction SilentlyContinue
        
        # Crear nueva regla
        New-NetFirewallRule -DisplayName $ruleName `
                           -Direction Outbound `
                           -Action Allow `
                           -Protocol TCP `
                           -RemotePort $port `
                           -RemoteAddress Any `
                           -Profile Any `
                           -Enabled True `
                           -Description "Permite conexiones SMTP salientes a Brevo para VeteHub" | Out-Null
        
        Write-Host "  ✓ Puerto $port ($($portNames[$port])) - Configurado" -ForegroundColor Green
        $success++
    }
    catch {
        Write-Host "  ✗ Puerto $port - Error: $($_.Exception.Message)" -ForegroundColor Red
        $failed++
    }
}

Write-Host ""
Write-Host "============================================" -ForegroundColor Cyan
Write-Host " Resumen" -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan
Write-Host "Reglas creadas: $success" -ForegroundColor Green
Write-Host "Errores: $failed" -ForegroundColor $(if ($failed -eq 0) { "Green" } else { "Red" })
Write-Host ""

if ($success -gt 0) {
    Write-Host "✅ Configuración completada exitosamente" -ForegroundColor Green
    Write-Host ""
    Write-Host "Puedes probar la conexión ejecutando:" -ForegroundColor Yellow
    Write-Host "  php test_smtp_connection.php" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Para enviar recordatorios:" -ForegroundColor Yellow
    Write-Host "  php artisan appointments:send-reminders" -ForegroundColor Cyan
    Write-Host "  php artisan queue:work --stop-when-empty" -ForegroundColor Cyan
} else {
    Write-Host "❌ No se pudieron configurar las reglas" -ForegroundColor Red
    Write-Host ""
    Write-Host "Alternativa: Configura tu antivirus para permitir PHP/Herd" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Presiona cualquier tecla para cerrar..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")
