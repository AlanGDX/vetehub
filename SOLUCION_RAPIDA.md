# 🚀 Solución Rápida - Envío de Recordatorios VeteHub

## ⚠️ Problema Detectado
**Los puertos SMTP están bloqueados por el firewall o antivirus**

## ✅ SOLUCIÓN INMEDIATA - Opción 1: Configurar Firewall (RECOMENDADO)

### Windows:

**Método Automático (Más Fácil):**
```powershell
# Ejecuta PowerShell como Administrador y luego:
cd C:\Users\HP\Herd\vetehub
.\Configure-Firewall.ps1
```

**Método Manual:**
1. Abre "Windows Defender Firewall con seguridad avanzada"
2. Click en "Reglas de salida" (lado izquierdo)
3. Click en "Nueva regla" (lado derecho)
4. Selecciona "Puerto" → Siguiente
5. Selecciona "TCP" y escribe: `587, 2525, 465`
6. Selecciona "Permitir la conexión" → Siguiente
7. Marca todos los perfiles (Dominio, Privado, Público) → Siguiente
8. Nombre: "Brevo SMTP VeteHub" → Finalizar

### Antivirus:
Si tienes antivirus (Kaspersky, Norton, Avast, McAfee, etc.):
- Busca configuración de "Firewall" o "Control de red"
- Agrega excepción para: `smtp-relay.brevo.com`
- Puertos: 587, 2525, 465

---

## ✅ SOLUCIÓN INMEDIATA - Opción 2: Modo Desarrollo

Si no puedes configurar el firewall ahora, usa modo desarrollo:

```bash
# Edita el archivo .env y cambia:
MAIL_MAILER=log

# Limpia la configuración:
php artisan config:clear

# Envía recordatorios:
php artisan appointments:send-reminders

# Procesa la cola:
php artisan queue:work --stop-when-empty

# Los correos se guardarán en: storage/logs/laravel.log
```

---

## 📧 Enviar Recordatorios para el 26 de Febrero

Una vez configurado el firewall:

```bash
# 1. Asegúrate que MAIL_MAILER=smtp en .env
# 2. Limpia caché
php artisan config:clear

# 3. Envía recordatorios
php artisan appointments:send-reminders

# 4. Procesa la cola
php artisan queue:work --stop-when-empty
```

---

## 🧪 Probar Conexión

```bash
# Prueba la conexión SMTP
php test_smtp_connection.php
```

Deberías ver:
```
✅ ¡Correo enviado exitosamente!
✓ La conexión con Brevo está funcionando correctamente.
```

---

## 📋 Información de la Cita

**Cita encontrada para el 26/02/2026:**
- **ID:** 3
- **Cliente:** Alan Garcia (darckrise57@gmail.com)
- **Mascota:** Akro (Gato)
- **Veterinario:** Dr. Alan Garcia (alaned.gsilva@gmail.com)
- **Fecha:** 26/02/2026 a las 13:00
- **Estado:** Confirmada

---

## 🔍 Verificar que Funcionó

### Si usas SMTP (Brevo):
- Revisa la bandeja de entrada del cliente: darckrise57@gmail.com
- Revisa la bandeja del veterinario: alaned.gsilva@gmail.com
- El asunto será: "Recordatorio: Cita para Akro"

### Si usas modo LOG:
```bash
# Ver los correos generados:
Get-Content storage/logs/laravel.log | Select-Object -Last 200
```

---

## ❓ ¿Sigue sin funcionar?

### Red Corporativa:
Contacta al administrador de red y solicita:
- Habilitar SMTP saliente
- Destino: smtp-relay.brevo.com
- Puertos: 587, 2525, o 465

### Verificar Credenciales:
1. Inicia sesión en https://app.brevo.com
2. Ve a "Settings" → "SMTP & API"
3. Verifica que la API activa coincida con MAIL_PASSWORD en .env

---

## 📱 Soporte

Si necesitas ayuda adicional, revisa:
- Logs: `storage/logs/laravel.log`
- Documentación completa: `BREVO_CONNECTION_GUIDE.md`
