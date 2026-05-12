<?php

namespace App\Services;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SalesReportService
{
    public function generateSalesReport(string $startDate, string $endDate, array $options = []): array
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $query = Sale::with(['items.product', 'seller'])
            ->whereBetween('sold_at', [$start, $end]);

        if (!empty($options['seller_id'])) {
            $query->where('seller_id', $options['seller_id']);
        }

        $sales = $query->orderBy('sold_at', 'asc')->get();

        $summary = $this->generateSummary($sales);
        $dailySummary = $this->generateDailySummary($sales);
        $bySeller = $this->groupBySeller($sales);

        return [
            'period' => [
                'start' => $start->format('d/m/Y'),
                'end' => $end->format('d/m/Y'),
                'days' => $start->diffInDays($end) + 1,
            ],
            'summary' => $summary,
            'sales' => $sales,
            'daily_summary' => $dailySummary,
            'by_seller' => $bySeller,
        ];
    }

    protected function generateSummary(Collection $sales): array
    {
        $orders = $sales->count();
        $totalSales = round($sales->sum('total'), 2);

        $totalItems = $sales->sum(function ($sale) {
            return $sale->items_count > 0
                ? $sale->items_count
                : $sale->items->sum('quantity');
        });

        return [
            'total_sales' => $totalSales,
            'total_orders' => $orders,
            'total_items' => $totalItems,
            'average_ticket' => $orders > 0 ? round($totalSales / $orders, 2) : 0,
        ];
    }

    protected function groupBySeller(Collection $sales): array
    {
        return $sales->groupBy('seller_id')->map(function ($group) {
            $seller = $group->first()->seller;

            return [
                'id' => $seller?->id,
                'name' => $seller?->name ?? 'Sin vendedor',
                'total_sales' => round($group->sum('total'), 2),
                'total_orders' => $group->count(),
                'total_items' => $group->sum(function ($sale) {
                    return $sale->items_count > 0
                        ? $sale->items_count
                        : $sale->items->sum('quantity');
                }),
            ];
        })->values()->toArray();
    }

    protected function generateDailySummary(Collection $sales): array
    {
        return $sales->groupBy(function ($sale) {
            return $sale->sold_at->format('Y-m-d');
        })->map(function ($group, $date) {
            $totalSales = round($group->sum('total'), 2);
            $totalItems = $group->sum(function ($sale) {
                return $sale->items_count > 0
                    ? $sale->items_count
                    : $sale->items->sum('quantity');
            });

            return [
                'date' => Carbon::parse($date)->format('d/m/Y'),
                'day_name' => Carbon::parse($date)->locale('es')->dayName,
                'total_sales' => $totalSales,
                'total_orders' => $group->count(),
                'total_items' => $totalItems,
            ];
        })->values()->toArray();
    }

    public function exportToCSV(array $report): string
    {
        $csv = "ID,Fecha,Vendedor,Total,Items,Notas\n";

        foreach ($report['sales'] as $sale) {
            $date = $sale->sold_at?->format('d/m/Y H:i');
            $seller = $sale->seller?->name ?? 'Sin vendedor';
            $items = $sale->items_count > 0
                ? $sale->items_count
                : $sale->items->sum('quantity');

            $csv .= implode(',', [
                $sale->id,
                '"' . $date . '"',
                '"' . $seller . '"',
                $sale->total,
                $items,
                '"' . ($sale->notes ?? '') . '"',
            ]) . "\n";
        }

        return $csv;
    }
}
