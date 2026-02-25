# 🔧 Guía de Conexión con Brevo (SMTP)

## ✅ Estado Actual

### Lo que funciona:
- ✓ Configuración de Brevo correcta en `.env`
- ✓ Notificación `AppointmentReminder` implementada
- ✓ Comando `appointments:send-reminders` funcionando
- ✓ Sistema de colas configurado
- ✓ Contenido de correos generándose correctamente

### Problema identificado:
**❌ El puerto 587 y 2525 están bloqueados por firewall/red**

Error: `Connection could not be established with host "smtp-relay.brevo.com:587"`

---

## 🔍 Verificación de la Conexión

La configuración actual en `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=alaned.gsilva@gmail.com
MAIL_PASSWORD=QEDX2ZL7OWsR8f0B
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="vetehub@gmail.com"
MAIL_FROM_NAME="VeteHub"
```

---

## 🚀 Soluciones al Problema de Conectividad

### **Solución 1: Configurar el Firewall (Recomendado)**

Permitir conexiones salientes SMTP:

**Windows Defender Firewall:**
1. Abrir "Windows Defender Firewall con seguridad avanzada"
2. Clic en "Reglas de salida" → "Nueva regla"
3. Tipo: Puerto
4. TCP específico: `587, 2525`
5. Acción: Permitir la conexión
6. Nombre: "Brevo SMTP Saliente"

**Antivirus (Kaspersky, Norton, Avast, etc.):**
- Buscar configuración de "Firewall" o "Control de red"
- Agregar excepción para `smtp-relay.brevo.com` en puertos 587 y 2525

---

### **Solución 2: Probar Puerto Alternativo**

Brevo ofrece varios puertos. Edita `.env`:

```env
MAIL_PORT=2525
# Otros puertos disponibles: 587, 465, 2525
```

Luego limpia el caché:
```bash
php artisan config:clear
```

---

### **Solución 3: Contactar al Administrador de Red**

Si estás en una red corporativa:
- Solicitar habilitar conexiones SMTP salientes
- Puertos necesarios: 587, 2525, o 465
- Destino: smtp-relay.brevo.com

---

### **Solución 4: Modo Desarrollo (Temporal)**

Para desarrollo local, usa modo log:

```env
MAIL_MAILER=log
```

Los correos se guardarán en `storage/logs/laravel.log`

---

## 📧 Prueba de Envío de Recordatorios

### Para la cita del 26 de febrero de 2026:

```bash
# Enviar recordatorios
php artisan appointments:send-reminders

# Procesar la cola
php artisan queue:work --stop-when-empty
```

### Script de prueba (modo log):
```bash
php test_reminders.php
```

---

## 📊 Cita Encontrada

**Cita #3:**
- Cliente: Alan Garcia (darckrise57@gmail.com)
- Mascota: Akro (Gato)
- Veterinario: Dr. Alan Garcia (alaned.gsilva@gmail.com)
- Fecha: 26/02/2026 a las 13:00
- Estado: confirmed

---

## 🔄 Comandos Útiles

```bash
# Limpiar trabajos fallidos de la cola
php artisan queue:flush

# Ver la cola
php artisan queue:work --once

# Limpiar caché de configuración
php artisan config:clear

# Probar conectividad
Test-NetConnection -ComputerName smtp-relay.brevo.com -Port 587
```

---

## 📝 Contenido del Correo Generado

### Para el cliente:
- **Asunto:** Recordatorio: Cita para Akro
- **Contenido:**
  - Saludo personalizado
  - Fecha y hora de la cita
  - Nombre del veterinario
  - Motivo de consulta
  - Recordatorio de llegar 10 minutos antes
  - Botón para ver detalles

### Para el veterinario:
- **Asunto:** Recordatorio: Cita con Alan Garcia
- **Contenido:**
  - Datos del cliente
  - Información de la mascota
  - Fecha y hora
  - Recordatorio de revisar historial

---

## ✨ Una vez resuelto el problema de conectividad:

1. Asegúrate que `MAIL_MAILER=smtp` en `.env`
2. Ejecuta: `php artisan config:clear`
3. Prueba: `php artisan appointments:send-reminders`
4. Procesa la cola: `php artisan queue:work --stop-when-empty`

Los correos llegarán automáticamente a los destinatarios.

---

## 🆘 Soporte

Si los problemas persisten:
- Verifica credenciales en Brevo Dashboard
- Revisa límites de envío de tu cuenta Brevo
- Consulta logs: `storage/logs/laravel.log`
