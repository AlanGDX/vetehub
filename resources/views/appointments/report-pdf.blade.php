<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reporte de Citas - VeteHub</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 20px;
            color: #4F46E5;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 11px;
            color: #666;
        }
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .summary-row {
            display: table-row;
        }
        .summary-item {
            display: table-cell;
            width: 25%;
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .summary-item .label {
            font-size: 9px;
            color: #666;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .summary-item .value {
            font-size: 18px;
            font-weight: bold;
        }
        .section {
            margin-bottom: 15px;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
            padding: 5px;
            background-color: #f3f4f6;
            border-left: 3px solid #4F46E5;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .stats-row {
            display: table-row;
        }
        .stats-item {
            display: table-cell;
            width: 33.33%;
            padding: 8px;
            border: 1px solid #ddd;
        }
        .stats-item .label {
            font-size: 9px;
            color: #666;
        }
        .stats-item .value {
            font-size: 14px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table thead {
            background-color: #f9fafb;
        }
        table th {
            padding: 6px 4px;
            text-align: left;
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            color: #666;
            border-bottom: 2px solid #ddd;
        }
        table td {
            padding: 5px 4px;
            font-size: 9px;
            border-bottom: 1px solid #eee;
        }
        table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .status-confirmed {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-completed {
            background-color: #dbeafe;
            color: #1e3a8a;
        }
        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .page-break {
            page-break-after: always;
        }
        .daily-grid {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        .daily-row {
            display: table-row;
        }
        .daily-item {
            display: table-cell;
            width: 33.33%;
            padding: 6px;
            border: 1px solid #ddd;
            margin-bottom: 5px;
        }
        .daily-item .date {
            font-weight: bold;
            font-size: 9px;
        }
        .daily-item .day-name {
            font-size: 8px;
            color: #666;
        }
        .daily-item .total {
            font-size: 11px;
            font-weight: bold;
            margin-top: 3px;
        }
        .daily-item .details {
            font-size: 7px;
            color: #666;
            margin-top: 2px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Reporte de Citas - VeteHub</h1>
        <p>Período: {{ $report['period']['start'] }} - {{ $report['period']['end'] }}</p>
    </div>

    <!-- Resumen General -->
    <div class="summary-grid">
        <div class="summary-row">
            <div class="summary-item">
                <div class="label">Total de Citas</div>
                <div class="value">{{ $report['summary']['total'] }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Confirmadas</div>
                <div class="value" style="color: #059669;">{{ $report['summary']['confirmed'] }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Pendientes</div>
                <div class="value" style="color: #d97706;">{{ $report['summary']['pending'] }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Completadas</div>
                <div class="value" style="color: #2563eb;">{{ $report['summary']['completed'] }}</div>
            </div>
        </div>
    </div>

    @if($report['summary']['total'] > 0)
        <!-- Estadísticas Adicionales -->
        <div class="section">
            <div class="section-title">📈 Estadísticas</div>
            <div class="stats-grid">
                <div class="stats-row">
                    <div class="stats-item">
                        <div class="label">Duración Total</div>
                        <div class="value">{{ $report['summary']['total_duration'] }} min</div>
                    </div>
                    <div class="stats-item">
                        <div class="label">Duración Promedio</div>
                        <div class="value">{{ $report['summary']['average_duration'] }} min</div>
                    </div>
                    <div class="stats-item">
                        <div class="label">Citas Canceladas</div>
                        <div class="value" style="color: #dc2626;">{{ $report['summary']['cancelled'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen Diario (solo si hay menos de 60 días) -->
        @if(count($report['daily_summary']) > 0 && count($report['daily_summary']) <= 60)
        <div class="section">
            <div class="section-title">📅 Resumen Diario</div>
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Día</th>
                        <th style="text-align: center;">Total</th>
                        <th style="text-align: center;">Confirmadas</th>
                        <th style="text-align: center;">Pendientes</th>
                        <th style="text-align: center;">Completadas</th>
                        <th style="text-align: center;">Canceladas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report['daily_summary'] as $day)
                    <tr>
                        <td>{{ $day['date'] }}</td>
                        <td>{{ $day['day_name'] }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ $day['total'] }}</td>
                        <td style="text-align: center;">{{ $day['confirmed'] }}</td>
                        <td style="text-align: center;">{{ $day['pending'] }}</td>
                        <td style="text-align: center;">{{ $day['completed'] }}</td>
                        <td style="text-align: center;">{{ $day['cancelled'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        <!-- Detalle de Citas -->
        <div class="section">
            <div class="section-title">📝 Detalle de Citas ({{ $report['summary']['total'] }})</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">ID</th>
                        <th style="width: 12%;">Fecha y Hora</th>
                        <th style="width: 15%;">Cliente</th>
                        <th style="width: 15%;">Mascota</th>
                        <th style="width: 15%;">Veterinario</th>
                        <th style="width: 10%;">Estado</th>
                        <th style="width: 20%;">Motivo</th>
                        <th style="width: 8%;">Duración</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report['appointments'] as $appointment)
                    <tr>
                        <td>#{{ $appointment->id }}</td>
                        <td>{{ $appointment->appointment_date->format('d/m/Y H:i') }}</td>
                        <td>{{ $appointment->client->name }}</td>
                        <td>{{ $appointment->pet->name }} ({{ $appointment->pet->species }})</td>
                        <td>{{ $appointment->user->name }}</td>
                        <td>
                            @if($appointment->status == 'confirmed')
                                <span class="status-badge status-confirmed">Confirmada</span>
                            @elseif($appointment->status == 'pending')
                                <span class="status-badge status-pending">Pendiente</span>
                            @elseif($appointment->status == 'completed')
                                <span class="status-badge status-completed">Completada</span>
                            @else
                                <span class="status-badge status-cancelled">Cancelada</span>
                            @endif
                        </td>
                        <td>{{ $appointment->reason }}</td>
                        <td>{{ $appointment->duration }} min</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="section" style="text-align: center; padding: 20px; background-color: #fef3c7; border: 1px solid #f59e0b;">
            <div style="font-size: 16px; font-weight: bold; margin-bottom: 5px;">No se encontraron citas</div>
            <div>No hay citas registradas en el rango de fechas y filtros seleccionados.</div>
        </div>
    @endif

    <div class="footer">
        Reporte generado el {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
