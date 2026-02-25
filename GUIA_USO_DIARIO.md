# 📧 Guía de Uso Diario - Sistema de Recordatorios VeteHub

## ✅ Estado del Sistema

El sistema de recordatorios de citas está **completamente funcional** usando la API de Brevo.

### Implementación Actual
- ✅ **Método de envío**: API de Brevo (HTTPS - puerto 443)
- ✅ **Conexión verificada**: alaned.gsilva@gmail.com
- ✅ **Correos probados**: Enviados y recibidos exitosamente
- ⚠️ **SMTP alternativo**: Disponible pero bloqueado (puertos 587, 2525, 465)

---

## 🚀 Uso Diario

### Opción 1: Script API (RECOMENDADO ⭐)

Este es el método **más confiable** porque usa HTTPS (puerto 443) que nunca está bloqueado:

```bash
php enviar_recordatorios_api.php
```

**Salida esperada:**
```
🔍 Buscando citas para el 26/02/2026...
✅ Se encontró 1 cita(s) para enviar recordatorios

📧 Enviando recordatorios...
✅ Correos enviados correctamente
   → Cliente: correo@cliente.com
   → Veterinario: correo@veterinario.com

📊 Resumen:
   • Correos enviados: 2
   • Fallos: 0
```

### Opción 2: Comando Artisan (Alternativo)

```bash
php artisan appointments:send-reminders
php artisan queue:work --stop-when-empty
```

**Nota:** Actualmente usa `MAIL_MAILER=log` (solo guarda en logs). Para enviar realmente, usa la Opción 1.

---

## 📅 Automatización (Opcional)

El sistema ya está configurado para ejecutarse automáticamente todos los días a las 08:00 AM.

### Verificar programación:
```bash
php artisan schedule:list
```

### Ejecutar manualmente el programador:
```bash
php artisan schedule:run
```

### Para producción en servidor:
Agregar al cron de Linux:
```bash
* * * * * cd /ruta/vetehub && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🔧 Verificación de Configuración

### Probar conexión API:
```bash
php configurar_brevo_api.php
```

### Ver configuración actual:
```bash
cat .env | Select-String "BREVO"
```

**Debe mostrar:**
```
BREVO_API_KEY=xkeysib-390dfda0180b8f8a37d228dbafd956250f558bcff8c72623bb433b6b87385c191-lopzmUnAHkOqLtHH
```

---

## 📝 Logs y Monitoreo

### Ver logs de correos enviados:
```bash
Get-Content storage\logs\laravel.log -Tail 50
```

### Limpiar logs antiguos:
```bash
Remove-Item storage\logs\laravel.log
```

---

## ❓ Solución de Problemas

### Error: "API Key no configurada"
```bash
.\Configurar-BrevoAPI.ps1
```
Ingresa tu API Key cuando te lo solicite.

### Error: "No se encontraron citas"
Verifica que existan citas para mañana:
```bash
php check_appointments.php
```

### Los correos no llegan
1. Verifica la bandeja de **Spam/Correo no deseado**
2. Revisa los logs: `storage/logs/laravel.log`
3. Verifica la API Key en Brevo: https://app.brevo.com/settings/keys/api

### Verificar estado de la cola:
```bash
php artisan queue:failed
```

---

## 📚 Archivos de Referencia

- `README_RECORDATORIOS.md` - Documentación completa del sistema
- `BREVO_CONNECTION_GUIDE.md` - Guía de conexión con Brevo
- `SOLUCION_RAPIDA.md` - Solución temporal (modo LOG)
- `CONFIGURAR_WINDOWS_DEFENDER.md` - Configuración de firewall

---

## 🎯 Ejemplo de Uso Real

**Cita enviada hoy (25/02/2026):**
- **Cita #3**: 26/02/2026 a las 13:00
- **Mascota**: Akro (Gato)
- **Cliente**: Alan Garcia (darckrise57@gmail.com) ✅ Correo enviado
- **Veterinario**: Dr. Alan Garcia (alaned.gsilva@gmail.com) ✅ Correo enviado
- **Estado**: Confirmed

---

## 💡 Notas Importantes

1. **Enviar solo una vez al día**: Los recordatorios se envían para citas del día siguiente
2. **Puerto 443 (HTTPS)**: No está bloqueado, por eso la API funciona perfectamente
3. **Límite de envío**: Brevo permite 300 correos/día en plan gratuito
4. **Personalización**: Cada correo incluye nombre de mascota, hora y datos del cliente
5. **Bilingüe**: Correos en español para clientes y veterinarios

---

## 🔄 Futuro: Habilitar SMTP

Si en el futuro los puertos SMTP se desbloquean:

1. Cambiar en `.env`:
```
MAIL_MAILER=smtp
```

2. Reiniciar:
```bash
php artisan config:clear
```

3. Probar:
```bash
php test_smtp_connection.php
```

---

**✅ Sistema listo para uso diario - Última actualización: 25/02/2026**
