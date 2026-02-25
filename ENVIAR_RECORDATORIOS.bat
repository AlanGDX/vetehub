@echo off
chcp 65001 > nul
cls
echo.
echo ╔════════════════════════════════════════════════════════╗
echo ║                                                        ║
echo ║        ENVÍO DE RECORDATORIOS - VeteHub               ║
echo ║                                                        ║
echo ╚════════════════════════════════════════════════════════╝
echo.
echo Este es el comando más simple para enviar recordatorios.
echo.
echo Ejecutando...
echo.

php enviar_recordatorios.php

echo.
echo ════════════════════════════════════════════════════════
echo.
echo ¿Si viste un error de conexión SMTP?
echo.
echo SOLUCIÓN RÁPIDA (30 segundos):
echo 1. Abre .env con un editor de texto
echo 2. Busca: MAIL_MAILER=smtp
echo 3. Cambia a: MAIL_MAILER=log
echo 4. Guarda y ejecuta este archivo de nuevo
echo.
echo Ver README_RECORDATORIOS.md para más información
echo.
pause
