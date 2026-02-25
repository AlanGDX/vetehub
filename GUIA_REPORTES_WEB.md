# 🌐 Sistema de Reportes Web - VeteHub

## ✨ Nueva Funcionalidad Agregada

Se ha implementado un **sistema completo de reportes accesible desde la interfaz web** en el apartado de citas.

---

## 📍 Acceso al Sistema

### Botón "Generar Reporte"

En la página de **Agenda de Citas** (`/appointments`), ahora encontrarás un botón verde con el ícono 📊:

```
[📊 Generar Reporte]  [+ Nueva Cita]
```

### Ubicación
- **Ruta Web**: `http://127.0.0.1:8000/appointments-report`
- **Ubicación Visual**: Esquina superior derecha, junto al botón "Nueva Cita"
- **Color**: Verde (para diferenciarlo de otras acciones)

---

## 🎯 Características del Sistema Web

### 1. Formulario Interactivo

El formulario de generación de reportes incluye:

#### 📅 Rango de Fechas (Obligatorio)
- **Fecha de Inicio**: Selector de calendario
- **Fecha de Fin**: Selector de calendario
- **Valores por Defecto**: Últimos 30 días
- **Botones Rápidos**:
  - Esta semana
  - Este mes
  - Mes pasado

#### 🔍 Filtros Opcionales
- **Estado de la Cita**:
  - Todas las citas (por defecto)
  - Confirmadas
  - Pendientes
  - Completadas
  - Canceladas
  
- **Cliente Específico**:
  - Todos los clientes (por defecto)
  - Cualquier cliente de tu lista

**Nota**: El reporte siempre mostrará únicamente las citas donde eres el veterinario asignado.

#### 📄 Formato de Salida
- **Vista en Pantalla** (HTML) - Ver el reporte en el navegador
- **Descargar CSV** - Compatible con Excel y Google Sheets

---

### 2. Vista del Reporte en Pantalla

Cuando seleccionas "Vista en Pantalla", verás:

#### Tarjetas de Resumen (Top)
```
┌──────────────┬──────────────┬──────────────┬──────────────┐
│ Total Citas  │ Confirmadas  │ Pendientes   │ Completadas  │
│     15       │      12      │      2       │      1       │
└──────────────┴──────────────┴──────────────┴──────────────┘
```

#### Secciones del Reporte

1. **📈 Estadísticas Generales**
   - Duración total de citas
   - Duración promedio
   - Citas canceladas

2. **👨‍⚕️ Resumen por Veterinario**
   - Tabla con totales por veterinario
   - Columnas: Nombre, Total Citas, Duración, Confirmadas, Pendientes, Completadas

3. **📅 Resumen Diario**
   - Tarjetas por día con:
     - Fecha y día de la semana
     - Total de citas
     - Desglose por estado (✓ ⏳ ✅ ❌)

4. **📝 Detalle Completo de Citas**
   - Tabla con todas las citas
   - Columnas: ID, Fecha y Hora, Cliente, Mascota, Veterinario, Estado, Motivo, Duración
   - Estados con colores (badges):
     - Verde: Confirmada
     - Amarillo: Pendiente
     - Azul: Completada
     - Rojo: Cancelada

#### Acciones Disponibles
- **🖨️ Imprimir**: Imprime el reporte (oculta controles de navegación)
- **← Nuevo Reporte**: Volver al formulario para generar otro reporte

---

### 3. Descarga CSV

Cuando seleccionas "Descargar CSV":

#### Contenido del Archivo
```csv
ID,Fecha,Hora,Cliente,Mascota,Especie,Veterinario,Estado,Motivo,Duración
1,25/02/2026,14:46,"Jorge Gutierrez","Firulais",Perro,"Alan Garcia",confirmed,"Vacunacion",30
```

#### Características
- **Formato**: UTF-8 con BOM (compatibilidad con Excel)
- **Separador**: Coma (`,`)
- **Nombre de archivo**: `reporte_citas_YYYY-MM-DD_HHMMSS.csv`
- **Descarga automática**: Se descarga inmediatamente al navegador

#### Uso Posterior
- Abrir en **Microsoft Excel**
- Importar a **Google Sheets**
- Análisis en **Power BI**
- Procesamiento con **Python/R**

---

## 🚀 Flujo de Uso Típico

### Escenario 1: Reporte Mensual Rápido

```
1. Ir a "Agenda de Citas"
2. Click en "📊 Generar Reporte"
3. Click botón "Este mes"
4. Seleccionar "Vista en Pantalla"
5. Click "Generar Reporte"
6. Ver resumen completo
7. [Opcional] Click "🖨️ Imprimir"
```

### Escenario 2: Análisis de Citas Canceladas

```
1. Ir a "Agenda de Citas"
2. Click en "📊 Generar Reporte"
3. Seleccionar rango de fechas (ej: último mes)
4. Estado: Seleccionar "Canceladas"
5. Formato: "Vista en Pantalla"
6. Click "Generar Reporte"
7. Analizar los resultados
```

### Escenario 3: Exportar para Contabilidad

```
1. Ir a "Agenda de Citas"
2. Click en "📊 Generar Reporte"
3. Configurar rango de fechas del mes
4. Estado: "Completadas"
5. Formato: "Descargar CSV"
6. Click "Generar Reporte"
7. Archivo se descarga automáticamente
8. Abrir en Excel para facturación
```

### Escenario 4: Reporte de un Cliente Específico

```
1. Ir a "Agenda de Citas"
2. Click en "📊 Generar Reporte"
3. Seleccionar rango amplio (ej: últimos 6 meses)
4. Cliente: Seleccionar el cliente deseado
5. Formato: "Vista en Pantalla"
6. Click "Generar Reporte"
7. Ver todo el historial del cliente
```

---

## 🎨 Interfaz Visual

### Colores y Estados

- **Verde** (`bg-green-600`): Botón principal, citas confirmadas
- **Amarillo** (`bg-yellow-100`): Citas pendientes
- **Azul** (`bg-blue-600`): Citas completadas, botón "Imprimir"
- **Rojo** (`bg-red-100`): Citas canceladas
- **Gris** (`bg-gray-200`): Botones secundarios

### Iconos SVG

- **📊** Reporte general
- **📅** Calendario/fechas
- **🔍** Filtros
- **📄** Formato de salida
- **🖨️** Imprimir
- **📈** Estadísticas

---

## 💡 Validaciones Implementadas

### Lado del Cliente (JavaScript)
- La fecha de inicio no puede ser mayor que la fecha de fin
- Botones rápidos pre-rellenan fechas correctamente

### Lado del Servidor (Laravel)
- `start_date`: Requerido, debe ser fecha válida
- `end_date`: Requerido, fecha válida, debe ser posterior o igual a start_date
- `format`: Requerido, solo acepta 'text' o 'csv'
- `status`: Opcional, solo valores válidos (confirmed, pending, completed, cancelled)
- `client_id`: Opcional, debe existir en la tabla de clientes

---

## 🔒 Seguridad

### Filtrado Automático
- **Aislamiento por Usuario**: Solo se muestran citas donde el usuario autenticado es el veterinario
- **No hay opción de ver citas de otros**: El filtro por veterinario fue removido intencionalmente
- **Validación de Clientes**: Solo se pueden filtrar clientes que pertenecen al veterinario

### Protección de Datos
- Reportes CSV no se guardan en el repositorio (`.gitignore`)
- Archivos temporales se descargan directamente al navegador
- No se almacenan reportes en el servidor

---

## 🛠️ Archivos Modificados/Creados

### Backend
1. **AppointmentController.php**
   - Método `showReportForm()`: Muestra formulario
   - Método `generateReport()`: Genera y descarga reporte

2. **routes/web.php**
   - Rutas: `appointments-report` (GET)
   - Rutas: `appointments-report/generate` (POST)

### Frontend
3. **appointments/index.blade.php**
   - Botón "Generar Reporte" agregado

4. **appointments/report.blade.php** (NUEVO)
   - Formulario interactivo de generación

5. **appointments/report-view.blade.php** (NUEVO)
   - Vista HTML del reporte generado

### Documentación
6. **GUIA_REPORTES_WEB.md** (este archivo)

---

## 📊 Comparación: CLI vs Web

| Característica | CLI (Artisan) | Web |
|---------------|---------------|-----|
| **Acceso** | Terminal | Navegador |
| **Usuarios** | Técnicos/Admins | Todos |
| **Interfaz** | Texto plano | HTML con estilos |
| **Filtros** | Flags | Formularios visuales |
| **Formato CSV** | Archivo local | Descarga directa |
| **Formato Texto** | Terminal | Página web imprimible |
| **Automatización** | Fácil (cron) | Manual o API |
| **Experiencia** | Desarrollador | Usuario final |

---

## 🎯 Ventajas del Sistema Web

1. **Accesibilidad**: No requiere conocimientos técnicos
2. **Visual**: Interfaz limpia y clara con colores
3. **Interactivo**: Botones rápidos y validación en tiempo real
4. **Imprimible**: Se puede imprimir directamente desde el navegador
5. **Responsive**: Funciona en desktop, tablet y móvil
6. **Seguro**: Solo muestra datos del usuario autenticado
7. **Integrado**: Botón directo desde la agenda de citas

---

## 🚧 Limitaciones Actuales

- No hay filtro por veterinario (solo ves tus propias citas)
- No hay opción de programar reportes automáticos desde web
- No hay exportación a PDF (solo HTML y CSV)
- Resumen diario solo se muestra si hay menos de 60 días

---

## 🔮 Mejoras Futuras Sugeridas

1. **Exportación a PDF** con logo y formato profesional
2. **Gráficos interactivos** (Chart.js) para visualización
3. **Comparación de períodos** (este mes vs mes anterior)
4. **Envío por email** del reporte generado
5. **Reportes programados** (semanal/mensual automático)
6. **Filtros avanzados** (rango de horas, duración, etc.)
7. **Guardado de reportes** favoritos

---

**Fecha de implementación**: 25/02/2026  
**Autor**: Sistema VeteHub  
**Versión**: 1.0
