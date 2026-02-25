# Comando simple para activar el scheduler
# Copia y pega esto en PowerShell como ADMINISTRADOR

$projectPath = "C:\Users\HP\Herd\vetehub"
$taskName = "Laravel Scheduler - VeteHub"

# Verificar si ya existe
$existingTask = Get-ScheduledTask -TaskName $taskName -ErrorAction SilentlyContinue
if ($existingTask) {
    Write-Host "⚠️  La tarea ya existe. Eliminando..." -ForegroundColor Yellow
    Unregister-ScheduledTask -TaskName $taskName -Confirm:$false
}

# Crear la tarea
$action = New-ScheduledTaskAction -Execute "cmd.exe" -Argument "/c php `"$projectPath\artisan`" schedule:run >> NUL 2>&1" -WorkingDirectory $projectPath
$trigger = New-ScheduledTaskTrigger -Once -At (Get-Date) -RepetitionInterval (New-TimeSpan -Minutes 1)
$settings = New-ScheduledTaskSettingsSet -AllowStartIfOnBatteries -DontStopIfGoingOnBatteries -StartWhenAvailable -RunOnlyIfNetworkAvailable -DontStopOnIdleEnd

Register-ScheduledTask -TaskName $taskName -Action $action -Trigger $trigger -Settings $settings -Description "Ejecuta el scheduler de Laravel cada minuto para enviar recordatorios de citas automáticamente a las 8:00 AM" -User $env:USERNAME -RunLevel Highest

Write-Host "`n✅ ¡Tarea creada exitosamente!" -ForegroundColor Green
Write-Host "Nombre: $taskName" -ForegroundColor White
Write-Host "Estado: Activa" -ForegroundColor Green
Write-Host "`nLos recordatorios se enviarán automáticamente a las 8:00 AM`n" -ForegroundColor White
