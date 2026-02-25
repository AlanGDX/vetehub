# 🛡️ Configurar Windows Defender para SMTP

## ✅ Estado Actual

**Firewall de Windows:** ✅ Configurado correctamente
- Puerto 587 (STARTTLS) - Habilitado
- Puerto 2525 (Alternativo) - Habilitado  
- Puerto 465 (SSL/TLS) - Habilitado

**Windows Defender:** ⚠️ Bloqueando conexiones SMTP salientes

---

## 🔧 Solución: Agregar Excepción en Windows Defender

### Método 1: Permitir aplicación a través del Firewall

1. **Abre Windows Security:**
   - Presiona `Win + I` (Configuración)
   - Busca "Seguridad de Windows"
   - O busca "Windows Security" en el menú Inicio

2. **Ve a Firewall:**
   - Click en "Firewall y protección de red"
   - Click en "Permitir una aplicación a través del firewall"

3. **Permitir PHP:**
   - Click en "Cambiar configuración" (requiere admin)
   - Click en "Permitir otra aplicación"
   - Click en "Examinar"
   - Navega a la ubicación de PHP:
     - Si usas Herd: `C:\Users\[TuUsuario]\AppData\Local\Herd\bin\php.exe`
     - Si usas XAMPP: `C:\xampp\php\php.exe`
     - O busca donde está instalado PHP
   - Selecciona `php.exe`
   - Click en "Agregar"
   - **IMPORTANTE:** Marca ambas casillas (Privada y Pública)
   - Click en "Aceptar"

---

### Método 2: Crear Regla Avanzada (Alternativo)

Si el Método 1 no funciona:

1. **Abre Firewall Avanzado:**
   - Presiona `Win + R`
   - Escribe: `wf.msc`
   - Enter

2. **Crear Regla de Salida:**
   - Click en "Reglas de salida"
   - Click en "Nueva regla"
   - Selecciona "Programa" → Siguiente
   - Selecciona "Esta ruta de acceso del programa"
   - Busca y selecciona `php.exe` → Siguiente
   - Selecciona "Permitir la conexión" → Siguiente
   - Marca todos los perfiles → Siguiente
   - Nombre: "PHP - VeteHub SMTP" → Finalizar

---

### Método 3: Desactivar temporalmente (Solo para pruebas)

**ADVERTENCIA:** Solo para probar, no recomendado para uso permanente.

```powershell
# Desactivar firewall temporalmente
Set-NetFirewallProfile -Profile Domain,Public,Private -Enabled False

# Probar conexión
php test_smtp_connection.php

# IMPORTANTE: Reactivar después
Set-NetFirewallProfile -Profile Domain,Public,Private -Enabled True
```

---

## 🧪 Verificar que Funciona

Después de configurar Windows Defender:

1. **Cambiar a modo SMTP:**
   ```bash
   # Edita .env
   MAIL_MAILER=smtp  # (cambiar de "log" a "smtp")
   ```

2. **Limpiar configuración:**
   ```bash
   php artisan config:clear
   ```

3. **Probar conexión:**
   ```bash
   php test_smtp_connection.php
   ```

   Deberías ver:
   ```
   ✅ ¡Correo enviado exitosamente!
   ✓ La conexión con Brevo está funcionando correctamente.
   ```

4. **Enviar recordatorios:**
   ```bash
   php enviar_recordatorios.php
   ```

---

## 📋 Solución de Problemas

### Sigue sin funcionar después de configurar Defender:

1. **Verifica que las reglas estén activas:**
   ```powershell
   Get-NetFirewallRule -DisplayName "*VeteHub*" | Select-Object DisplayName, Enabled
   ```

2. **Verifica otros antivirus:**
   - Kaspersky, Norton, Avast, McAfee, etc.
   - Busca configuración de "Firewall" o "Control de red"
   - Agrega excepción para `smtp-relay.brevo.com`

3. **Red corporativa:**
   - Contacta al administrador de red
   - Solicita habilitar SMTP saliente

4. **Verificar credenciales Brevo:**
   - Inicia sesión en https://app.brevo.com
   - Ve a "Settings" → "SMTP & API"
   - Verifica que las credenciales en `.env` coincidan

---

## 💡 Alternativa: Seguir usando modo LOG

Si no puedes/quieres configurar SMTP:

**Modo LOG funciona perfectamente para:**
- ✅ Desarrollo local
- ✅ Pruebas
- ✅ Ver el contenido exacto de los correos
- ✅ Sin límites de envío
- ✅ Sin problemas de conectividad

**Para producción:**
- Configura Windows Defender como se indica arriba
- O usa un servidor con menos restricciones

---

## 🚀 Resumen

1. ✅ **Firewall Windows:** Ya configurado
2. ⚠️ **Windows Defender:** Sigue los pasos del Método 1 o 2
3. 🧪 **Prueba:** `php test_smtp_connection.php`
4. 📧 **Envía:** `php enviar_recordatorios.php`

**Estado actual:** Todo funciona en modo LOG. SMTP real solo requiere configurar Windows Defender.
