@echo off
REM Script para activar el Scheduler de VeteHub con permisos de administrador
REM Este archivo abrirá PowerShell como administrador y ejecutará la configuración

echo.
echo ╔══════════════════════════════════════════════════════════╗
echo ║                                                          ║
echo ║     🔧 ACTIVAR SCHEDULER AUTOMÁTICO - VETEHUB           ║
echo ║                                                          ║
echo ╚══════════════════════════════════════════════════════════╝
echo.
echo Este script abrirá PowerShell como ADMINISTRADOR
echo y configurará el envío automático de recordatorios.
echo.
echo Presiona cualquier tecla para continuar...
pause > nul

REM Ejecutar PowerShell como administrador
powershell -Command "Start-Process powershell -Verb RunAs -ArgumentList '-ExecutionPolicy Bypass -NoExit -File \"%~dp0Configurar-Scheduler.ps1\"'"

echo.
echo Se abrió una ventana de PowerShell como administrador.
echo Sigue las instrucciones en esa ventana.
echo.
echo Presiona cualquier tecla para cerrar...
pause > nul
