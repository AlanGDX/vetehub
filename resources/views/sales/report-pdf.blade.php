<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ventas - VeteHub</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #1f2937;
        }
        h1 {
            font-size: 18px;
            margin-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background: #f3f4f6;
            text-transform: uppercase;
            font-size: 10px;
        }
        .summary {
            margin: 12px 0;
        }
        .summary span {
            display: inline-block;
            margin-right: 16px;
        }
    </style>
</head>
<body>
    <h1>🧾 Reporte de Ventas - VeteHub</h1>
    <div>Periodo: {{ $report['period']['start'] }} - {{ $report['period']['end'] }}</div>

    <div class="summary">
        <span>Ventas Totales: ${{ $report['summary']['total_sales'] }}</span>
        <span>Pedidos: {{ $report['summary']['total_orders'] }}</span>
        <span>Items: {{ $report['summary']['total_items'] }}</span>
        <span>Ticket Promedio: ${{ $report['summary']['average_ticket'] }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Vendedor</th>
                <th>Items</th>
                <th>Total</th>
                <th>Notas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report['sales'] as $sale)
                <tr>
                    <td>#{{ $sale->id }}</td>
                    <td>{{ $sale->sold_at?->format('d/m/Y H:i') ?? 'N/A' }}</td>
                    <td>{{ $sale->seller?->name ?? 'Sin vendedor' }}</td>
                    <td>{{ $sale->items_count > 0 ? $sale->items_count : $sale->items->sum('quantity') }}</td>
                    <td>${{ $sale->total }}</td>
                    <td>{{ $sale->notes ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 10px; font-size: 10px; color: #6b7280;">
        Reporte generado el {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
