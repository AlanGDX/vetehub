# Script para configurar el Programador de Tareas de Windows
# Ejecuta el Laravel Scheduler cada minuto para enviar recordatorios automáticos

Write-Host "`n╔══════════════════════════════════════════════════════════╗" -ForegroundColor Cyan
Write-Host "║                                                          ║" -ForegroundColor Cyan
Write-Host "║    🔧 CONFIGURAR SCHEDULER AUTOMÁTICO - VETEHUB         ║" -ForegroundColor Cyan
Write-Host "║                                                          ║" -ForegroundColor Cyan
Write-Host "╚══════════════════════════════════════════════════════════╝`n" -ForegroundColor Cyan

# Verificar si se ejecuta como administrador
$isAdmin = ([Security.Principal.WindowsPrincipal][Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)

if (-not $isAdmin) {
    Write-Host "⚠️  ADVERTENCIA: Este script requiere permisos de administrador`n" -ForegroundColor Yellow
    Write-Host "Por favor, ejecuta PowerShell como administrador y vuelve a ejecutar este script.`n" -ForegroundColor White
    Write-Host "Presiona Enter para salir..." -ForegroundColor Gray
    Read-Host
    exit 1
}

Write-Host "✅ Ejecutando como administrador`n" -ForegroundColor Green

# Obtener la ruta actual del proyecto
$projectPath = $PSScriptRoot
$phpPath = "php" # Asume que PHP está en el PATH

Write-Host "📋 Configuración:`n" -ForegroundColor Cyan
Write-Host "   Proyecto: $projectPath" -ForegroundColor White
Write-Host "   Comando: php artisan schedule:run" -ForegroundColor White
Write-Host "   Frecuencia: Cada minuto" -ForegroundColor White
Write-Host "   Horario de recordatorios: 8:00 AM diarios`n" -ForegroundColor White

# Preguntar confirmación
Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━`n" -ForegroundColor Gray
$confirm = Read-Host "¿Deseas crear la tarea programada? (S/N)"

if ($confirm -ne "S" -and $confirm -ne "s") {
    Write-Host "`n❌ Operación cancelada`n" -ForegroundColor Red
    exit 0
}

try {
    # Nombre de la tarea
    $taskName = "Laravel Scheduler - VeteHub"
    
    # Verificar si la tarea ya existe
    $existingTask = Get-ScheduledTask -TaskName $taskName -ErrorAction SilentlyContinue
    
    if ($existingTask) {
        Write-Host "`n⚠️  La tarea '$taskName' ya existe" -ForegroundColor Yellow
        $overwrite = Read-Host "¿Deseas sobrescribirla? (S/N)"
        
        if ($overwrite -eq "S" -or $overwrite -eq "s") {
            Unregister-ScheduledTask -TaskName $taskName -Confirm:$false
            Write-Host "✅ Tarea anterior eliminada`n" -ForegroundColor Green
        } else {
            Write-Host "`n❌ Operación cancelada`n" -ForegroundColor Red
            exit 0
        }
    }
    
    # Crear la acción (ejecutar el comando)
    $action = New-ScheduledTaskAction `
        -Execute "cmd.exe" `
        -Argument "/c php `"$projectPath\artisan`" schedule:run >> NUL 2>&1" `
        -WorkingDirectory $projectPath
    
    # Crear el trigger (cada minuto)
    $trigger = New-ScheduledTaskTrigger -Once -At (Get-Date) -RepetitionInterval (New-TimeSpan -Minutes 1)
    
    # Configurar las opciones de la tarea
    $settings = New-ScheduledTaskSettingsSet `
        -AllowStartIfOnBatteries `
        -DontStopIfGoingOnBatteries `
        -StartWhenAvailable `
        -RunOnlyIfNetworkAvailable `
        -DontStopOnIdleEnd
    
    # Registrar la tarea
    Register-ScheduledTask `
        -TaskName $taskName `
        -Action $action `
        -Trigger $trigger `
        -Settings $settings `
        -Description "Ejecuta el scheduler de Laravel cada minuto para enviar recordatorios de citas automáticamente a las 8:00 AM" `
        -User $env:USERNAME `
        -RunLevel Highest
    
    Write-Host "`n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━`n" -ForegroundColor Gray
    Write-Host "✅ ¡Tarea programada creada exitosamente!`n" -ForegroundColor Green
    
    Write-Host "📋 DETALLES DE LA TAREA:`n" -ForegroundColor Cyan
    Write-Host "   Nombre: $taskName" -ForegroundColor White
    Write-Host "   Estado: Activa" -ForegroundColor Green
    Write-Host "   Ejecuta: Cada 1 minuto" -ForegroundColor White
    Write-Host "   Comando: php artisan schedule:run" -ForegroundColor White
    Write-Host "   Usuario: $env:USERNAME`n" -ForegroundColor White
    
    Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━`n" -ForegroundColor Gray
    Write-Host "⏰ FUNCIONAMIENTO:`n" -ForegroundColor Yellow
    Write-Host "   • La tarea se ejecuta cada minuto" -ForegroundColor White
    Write-Host "   • Laravel verifica si hay comandos programados" -ForegroundColor White
    Write-Host "   • A las 8:00 AM enviará los recordatorios automáticamente" -ForegroundColor White
    Write-Host "   • Procesa citas de las próximas 24 horas`n" -ForegroundColor White
    
    Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━`n" -ForegroundColor Gray
    Write-Host "🔧 ADMINISTRACIÓN:`n" -ForegroundColor Cyan
    Write-Host "   Ver tareas programadas:" -ForegroundColor White
    Write-Host "   → taskschd.msc`n" -ForegroundColor Gray
    
    Write-Host "   Desactivar temporalmente:" -ForegroundColor White
    Write-Host "   → Disable-ScheduledTask -TaskName '$taskName'`n" -ForegroundColor Gray
    
    Write-Host "   Reactivar:" -ForegroundColor White
    Write-Host "   → Enable-ScheduledTask -TaskName '$taskName'`n" -ForegroundColor Gray
    
    Write-Host "   Eliminar:" -ForegroundColor White
    Write-Host "   → Unregister-ScheduledTask -TaskName '$taskName' -Confirm:`$false`n" -ForegroundColor Gray
    
    Write-Host "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━`n" -ForegroundColor Gray
    Write-Host "✅ La tarea comenzará a ejecutarse en el próximo minuto" -ForegroundColor Green
    Write-Host "   Los recordatorios se enviarán automáticamente a las 8:00 AM`n" -ForegroundColor White
    
    # Abrir el Programador de tareas
    $openScheduler = Read-Host "¿Deseas abrir el Programador de Tareas para verificar? (S/N)"
    if ($openScheduler -eq "S" -or $openScheduler -eq "s") {
        Start-Process "taskschd.msc"
    }
    
} catch {
    Write-Host "`n❌ ERROR al crear la tarea programada:`n" -ForegroundColor Red
    Write-Host "   $($_.Exception.Message)`n" -ForegroundColor White
    Write-Host "Presiona Enter para salir..." -ForegroundColor Gray
    Read-Host
    exit 1
}

Write-Host "`n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━`n" -ForegroundColor Gray
Write-Host "Presiona Enter para salir..." -ForegroundColor Gray
Read-Host
