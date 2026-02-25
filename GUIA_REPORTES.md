# 📊 Sistema de Reportes de Citas - VeteHub

Este documento explica cómo usar el sistema de reportes de citas implementado en VeteHub.

## ✨ Características

El sistema de reportes ofrece:

- **Reportes por rango de fechas**: Consulta citas entre dos fechas específicas
- **Filtros avanzados**: Por estado, veterinario o cliente
- **Múltiples formatos**: Texto (consola) o CSV (Excel)
- **Estadísticas completas**: Totales, promedios, agrupación por estado y veterinario
- **Resumen diario**: Desglose día por día

## 🚀 Métodos de Uso

### 1. Comando Artisan (Recomendado)

El comando `appointments:report` permite generar reportes directamente desde la terminal.

#### Sintaxis Básica

```bash
php artisan appointments:report
```

Este comando generará un reporte de los últimos 30 días y lo mostrará en la consola.

#### Opciones Disponibles

| Opción | Tipo | Descripción | Ejemplo |
|--------|------|-------------|---------|
| `--start` | Fecha | Fecha de inicio (YYYY-MM-DD) | `--start=2026-02-01` |
| `--end` | Fecha | Fecha de fin (YYYY-MM-DD) | `--end=2026-02-28` |
| `--status` | Texto | Filtrar por estado | `--status=confirmed` |
| `--veterinarian` | Número | ID del veterinario | `--veterinarian=1` |
| `--client` | Número | ID del cliente | `--client=5` |
| `--format` | Texto | Formato de salida (text/csv) | `--format=csv` |
| `--output` | Texto | Archivo de salida | `--output=reporte.csv` |

#### Estados Válidos

- `confirmed` - Citas confirmadas
- `pending` - Citas pendientes
- `completed` - Citas completadas
- `cancelled` - Citas canceladas

#### Ejemplos de Uso

##### Reporte Simple (Últimos 30 días)

```bash
php artisan appointments:report
```

##### Reporte de Febrero 2026

```bash
php artisan appointments:report --start=2026-02-01 --end=2026-02-28
```

##### Reporte Solo de Citas Confirmadas

```bash
php artisan appointments:report --start=2026-02-01 --end=2026-02-28 --status=confirmed
```

##### Reporte de un Veterinario Específico

```bash
php artisan appointments:report --veterinarian=1
```

##### Exportar a CSV

```bash
php artisan appointments:report --start=2026-02-01 --end=2026-02-28 --format=csv --output=febrero_2026.csv
```

##### Reporte Completo con Múltiples Filtros

```bash
php artisan appointments:report --start=2026-02-01 --end=2026-02-28 --status=confirmed --veterinarian=1 --format=csv --output=reporte_vet1_confirmadas.csv
```

### 2. Script Interactivo

Para usuarios que prefieren una interfaz guiada, existe el script `generar_reporte.php`:

```bash
php generar_reporte.php
```

Este script te guiará paso a paso:
1. Seleccionar rango de fechas
2. Aplicar filtros opcionales
3. Elegir formato de salida
4. Generar el reporte

### 3. Integración en Código

Puedes usar el `ReportService` directamente en tu código:

```php
use App\Services\ReportService;

$reportService = new ReportService();

// Generar reporte
$report = $reportService->generateAppointmentsReport(
    startDate: '2026-02-01',
    endDate: '2026-02-28',
    options: [
        'status' => 'confirmed',
        'user_id' => 1
    ]
);

// Exportar a texto
$textOutput = $reportService->exportToText($report);
echo $textOutput;

// O exportar a CSV
$csvOutput = $reportService->exportToCSV($report);
file_put_contents('reporte.csv', $csvOutput);
```

## 📋 Estructura del Reporte

### Formato Texto

El reporte en formato texto incluye:

1. **Encabezado**: Período del reporte
2. **Resumen General**: Totales y estadísticas globales
3. **Resumen por Veterinario**: Citas por doctor
4. **Resumen Diario**: Desglose día por día
5. **Detalle de Citas**: Lista completa con todos los datos

### Formato CSV

El archivo CSV contiene las siguientes columnas:

- ID
- Fecha
- Hora
- Cliente
- Mascota
- Especie
- Veterinario
- Estado
- Motivo
- Duración

Compatible con Excel, Google Sheets y cualquier software de hojas de cálculo.

## 📊 Estadísticas Incluidas

El sistema calcula automáticamente:

- **Total de citas** en el período
- **Conteo por estado**: Confirmadas, pendientes, completadas, canceladas
- **Duración total** de todas las citas
- **Duración promedio** por cita
- **Citas por veterinario** con subtotales
- **Resumen diario** con distribución por día

## 💡 Consejos de Uso

1. **Reportes Mensuales**: Usa el formato `--start=2026-02-01 --end=2026-02-28` para reportes completos del mes

2. **Análisis de Productividad**: Filtra por veterinario para ver la carga de trabajo individual:
   ```bash
   php artisan appointments:report --veterinarian=1 --format=csv
   ```

3. **Control de Cancelaciones**: Identifica citas canceladas:
   ```bash
   php artisan appointments:report --status=cancelled
   ```

4. **Exportación Regular**: Automatiza la generación semanal o mensual agregando el comando a tu scheduler

5. **Formato CSV para Análisis**: Usa CSV cuando necesites procesar los datos en Excel o herramientas de BI

## 🔒 Seguridad

- Los reportes pueden contener información sensible de clientes
- Los archivos `reporte_*.txt` y `reporte_*.csv` están excluidos del repositorio Git
- No compartas reportes con datos reales en canales públicos

## 🐛 Solución de Problemas

### Error: "Formato de fecha inválido"

Asegúrate de usar el formato `YYYY-MM-DD`:
```bash
# ✅ Correcto
php artisan appointments:report --start=2026-02-01

# ❌ Incorrecto
php artisan appointments:report --start=01/02/2026
```

### Error: "No se encontraron citas"

Verifica que:
1. El rango de fechas incluye citas existentes
2. Los filtros aplicados no son demasiado restrictivos
3. Hay citas registradas en la base de datos

### No se genera el archivo CSV

Asegúrate de:
1. Especificar `--format=csv`
2. Tener permisos de escritura en la carpeta
3. Usar la opción `--output=nombre_archivo.csv`

## 📚 Documentación Adicional

- **Código fuente**: `app/Services/ReportService.php`
- **Comando**: `app/Console/Commands/GenerateAppointmentsReport.php`
- **Script interactivo**: `generar_reporte.php`

## 🎯 Casos de Uso Comunes

### Reporte Semanal

```bash
php artisan appointments:report --start=2026-02-17 --end=2026-02-23 --format=csv --output=semana_$(date +%Y-%m-%d).csv
```

### Análisis Mensual por Veterinario

```bash
php artisan appointments:report --start=2026-02-01 --end=2026-02-28 --veterinarian=1 --format=csv --output=vet1_febrero.csv
```

### Reporte de Citas Pendientes

```bash
php artisan appointments:report --status=pending
```

### Histórico de un Cliente

```bash
php artisan appointments:report --client=5 --format=csv --output=cliente_5_historico.csv
```

---

**Última actualización**: 25/02/2026  
**Versión**: 1.0  
**Sistema**: VeteHub - Gestión de Citas Veterinarias
