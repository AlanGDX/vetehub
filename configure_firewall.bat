@echo off
echo ========================================
echo  Configuracion de Firewall para Brevo
echo ========================================
echo.
echo Este script configurara el Firewall de Windows para permitir
echo conexiones SMTP salientes a Brevo (smtp-relay.brevo.com)
echo.
echo NOTA: Requiere permisos de administrador
echo.
pause

echo.
echo Creando regla de firewall...
echo.

netsh advfirewall firewall add rule name="VeteHub - Brevo SMTP Saliente (587)" dir=out action=allow protocol=TCP remoteport=587 program="%~dp0\php-8.3.11-nts-Win32-vs16-x64\php.exe" enable=yes

netsh advfirewall firewall add rule name="VeteHub - Brevo SMTP Saliente (2525)" dir=out action=allow protocol=TCP remoteport=2525 program="%~dp0\php-8.3.11-nts-Win32-vs16-x64\php.exe" enable=yes

netsh advfirewall firewall add rule name="VeteHub - Brevo SMTP Saliente (465)" dir=out action=allow protocol=TCP remoteport=465 program="%~dp0\php-8.3.11-nts-Win32-vs16-x64\php.exe" enable=yes

echo.
echo ========================================
if %errorlevel% == 0 (
    echo [OK] Reglas de firewall creadas exitosamente
    echo.
    echo Puertos habilitados:
    echo   - 587 ^(TLS^)
    echo   - 2525 ^(Alternativo^)
    echo   - 465 ^(SSL^)
) else (
    echo [ERROR] No se pudieron crear las reglas
    echo.
    echo Por favor, ejecuta este archivo como Administrador:
    echo 1. Click derecho en el archivo
    echo 2. Selecciona "Ejecutar como administrador"
)
echo ========================================
echo.
pause
