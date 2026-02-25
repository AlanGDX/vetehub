# 🔐 Configuración Segura de Credenciales - VeteHub

## ⚠️ IMPORTANTE: Seguridad de Credenciales

Este proyecto utiliza variables de entorno para manejar información sensible. **NUNCA** compartas tu archivo `.env` o expongas credenciales en el repositorio.

---

## 🚀 Configuración Inicial

### 1. Copiar el archivo de ejemplo

```bash
cp .env.example .env
```

### 2. Configurar Variables de Entorno

Edita el archivo `.env` y configura las siguientes variables con tus credenciales reales:

#### Configuración de Brevo API

```env
# Brevo API Configuration
# Obtén tu API Key en: https://app.brevo.com/settings/keys/api
BREVO_API_KEY=tu-api-key-aqui

# Configuración de remitente para correos del sistema
# Este email debe estar verificado en Brevo
BREVO_FROM_EMAIL="tudominio@gmail.com"
BREVO_FROM_NAME="VeteHub - Sistema de Citas"
```

#### Configuración SMTP (Opcional)

Si deseas usar SMTP en lugar de la API:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-password-brevo
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="tu-email@gmail.com"
MAIL_FROM_NAME="VeteHub"
```

---

## 🔑 Obtener Credenciales de Brevo

### API Key

1. Inicia sesión en [Brevo](https://app.brevo.com)
2. Ve a **Settings** → **API Keys**
3. Crea una nueva API Key o copia una existente
4. La key empieza con `xkeysib-`
5. Pégala en `BREVO_API_KEY` en tu archivo `.env`

### Verificar Remitente

Para evitar que los correos vayan a spam:

1. Ve a [Sender Settings](https://app.brevo.com/settings/senders)
2. Click en **Add a sender**
3. Ingresa el email que configuraste en `BREVO_FROM_EMAIL`
4. Confirma el correo de verificación que Brevo te enviará
5. Espera a que aparezca el ✅ verde

---

## 📁 Archivos Sensibles (NO subir a Git)

Los siguientes archivos están en `.gitignore` y **NO deben** subirse al repositorio:

- `.env` - Contiene todas las credenciales
- `.env.backup` - Respaldo de configuración
- `.env.production` - Configuración de producción
- `check_output.txt` - Puede contener emails reales
- Scripts de configuración que puedan contener datos de prueba

---

## ✅ Verificar Configuración

Ejecuta el siguiente comando para probar tu configuración:

```bash
php configurar_brevo_api.php
```

Deberías ver:

```
✅ Conexión exitosa con Brevo API
📧 Cuenta: tu-email@ejemplo.com
```

---

## 🔒 Mejores Prácticas de Seguridad

### ✅ HACER:

- Usa `.env` para todas las credenciales
- Mantén `.env` en `.gitignore`
- Usa `.env.example` con valores de ejemplo
- Rota tus API keys periódicamente
- Verifica remitentes en Brevo

### ❌ NO HACER:

- Hardcodear credenciales en el código
- Subir `.env` al repositorio
- Compartir API keys en documentación
- Usar credenciales de producción en desarrollo
- Exponer emails reales en ejemplos públicos

---

## 🔄 Si Expusiste Credenciales Accidentalmente

### 1. Revocar la API Key inmediatamente

1. Ve a [Brevo API Keys](https://app.brevo.com/settings/keys/api)
2. Elimina la key expuesta
3. Genera una nueva API Key
4. Actualiza tu `.env` local

### 2. Limpiar el historial de Git

```bash
# Eliminar el archivo del historial
git filter-branch --force --index-filter \
  "git rm --cached --ignore-unmatch .env" \
  --prune-empty --tag-name-filter cat -- --all

# Forzar push (CUIDADO: reescribe historial)
git push origin --force --all
```

### 3. Verificar que no haya otros archivos sensibles

```bash
git log --all --full-history -- .env
```

---

## 📚 Documentación Adicional

- [Laravel Environment Configuration](https://laravel.com/docs/11.x/configuration#environment-configuration)
- [Brevo API Documentation](https://developers.brevo.com/)
- [Git Secrets Prevention](https://git-scm.com/book/en/v2/Git-Tools-Credential-Storage)

---

## 🆘 Soporte

Si tienes problemas con la configuración:

1. Verifica que `.env` existe y tiene las variables correctas
2. Confirma que la API Key es válida en Brevo
3. Verifica que el remitente está verificado
4. Revisa los logs: `storage/logs/laravel.log`

---

**⚠️ RECUERDA: Tu seguridad y la de tus usuarios depende de mantener las credenciales privadas.**
