<?php

namespace App\Services;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ReportService
{
    /**
     * Generar reporte de citas por rango de fechas
     * 
     * @param string $startDate Fecha inicial (Y-m-d)
     * @param string $endDate Fecha final (Y-m-d)
     * @param array $options Opciones adicionales (status, user_id, client_id)
     * @return array
     */
    public function generateAppointmentsReport(string $startDate, string $endDate, array $options = []): array
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Query base
        $query = Appointment::with(['client', 'pet', 'user'])
            ->whereBetween('appointment_date', [$start, $end]);

        // Filtros opcionales
        if (!empty($options['status'])) {
            $query->where('status', $options['status']);
        }

        if (!empty($options['user_id'])) {
            $query->where('user_id', $options['user_id']);
        }

        if (!empty($options['client_id'])) {
            $query->where('client_id', $options['client_id']);
        }

        // Obtener citas
        $appointments = $query->orderBy('appointment_date', 'asc')->get();

        // Generar estadísticas
        $statistics = $this->generateStatistics($appointments);

        // Agrupar por estado
        $byStatus = $this->groupByStatus($appointments);

        // Agrupar por veterinario
        $byVeterinarian = $this->groupByVeterinarian($appointments);

        // Generar resumen diario
        $dailySummary = $this->generateDailySummary($appointments);

        return [
            'period' => [
                'start' => $start->format('d/m/Y'),
                'end' => $end->format('d/m/Y'),
                'days' => $start->diffInDays($end) + 1,
            ],
            'summary' => $statistics,
            'appointments' => $appointments,
            'by_status' => $byStatus,
            'by_veterinarian' => $byVeterinarian,
            'daily_summary' => $dailySummary,
        ];
    }

    /**
     * Generar estadísticas generales
     */
    protected function generateStatistics(Collection $appointments): array
    {
        $total = $appointments->count();
        $byStatus = $appointments->groupBy('status');

        return [
            'total' => $total,
            'confirmed' => $byStatus->get('confirmed', collect())->count(),
            'pending' => $byStatus->get('pending', collect())->count(),
            'completed' => $byStatus->get('completed', collect())->count(),
            'cancelled' => $byStatus->get('cancelled', collect())->count(),
            'total_duration' => $appointments->sum('duration'),
            'average_duration' => $total > 0 ? round($appointments->avg('duration'), 2) : 0,
        ];
    }

    /**
     * Agrupar citas por estado
     */
    protected function groupByStatus(Collection $appointments): array
    {
        return $appointments->groupBy('status')->map(function ($group) {
            return [
                'count' => $group->count(),
                'appointments' => $group->values(),
            ];
        })->toArray();
    }

    /**
     * Agrupar citas por veterinario
     */
    protected function groupByVeterinarian(Collection $appointments): array
    {
        return $appointments->groupBy('user_id')->map(function ($group) {
            $vet = $group->first()->user;
            $byStatus = $group->groupBy('status');
            
            return [
                'id' => $vet->id,
                'name' => $vet->name,
                'email' => $vet->email,
                'total' => $group->count(),
                'total_duration' => $group->sum('duration'),
                'confirmed' => $byStatus->get('confirmed', collect())->count(),
                'pending' => $byStatus->get('pending', collect())->count(),
                'completed' => $byStatus->get('completed', collect())->count(),
                'cancelled' => $byStatus->get('cancelled', collect())->count(),
                'appointments' => $group->values(),
            ];
        })->values()->toArray();
    }

    /**
     * Generar resumen diario
     */
    protected function generateDailySummary(Collection $appointments): array
    {
        return $appointments->groupBy(function ($appointment) {
            return $appointment->appointment_date->format('Y-m-d');
        })->map(function ($group, $date) {
            return [
                'date' => Carbon::parse($date)->format('d/m/Y'),
                'day_name' => Carbon::parse($date)->locale('es')->dayName,
                'total' => $group->count(),
                'confirmed' => $group->where('status', 'confirmed')->count(),
                'pending' => $group->where('status', 'pending')->count(),
                'completed' => $group->where('status', 'completed')->count(),
                'cancelled' => $group->where('status', 'cancelled')->count(),
                'total_duration' => $group->sum('duration'),
            ];
        })->values()->toArray();
    }

    /**
     * Exportar reporte a formato de texto plano
     */
    public function exportToText(array $report): string
    {
        $output = "";
        
        $output .= "═══════════════════════════════════════════════════════\n";
        $output .= "  REPORTE DE CITAS - VETEHUB\n";
        $output .= "═══════════════════════════════════════════════════════\n\n";
        
        $output .= "📅 PERÍODO:\n";
        $output .= "   Desde: {$report['period']['start']}\n";
        $output .= "   Hasta: {$report['period']['end']}\n";
        $output .= "   Total días: {$report['period']['days']}\n\n";
        
        $output .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        $output .= "📊 RESUMEN GENERAL:\n";
        $output .= "   Total de citas: {$report['summary']['total']}\n";
        $output .= "   Confirmadas: {$report['summary']['confirmed']}\n";
        $output .= "   Pendientes: {$report['summary']['pending']}\n";
        $output .= "   Completadas: {$report['summary']['completed']}\n";
        $output .= "   Canceladas: {$report['summary']['cancelled']}\n";
        $output .= "   Duración total: {$report['summary']['total_duration']} minutos\n";
        $output .= "   Duración promedio: {$report['summary']['average_duration']} minutos\n\n";
        
        $output .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        $output .= "📋 RESUMEN POR VETERINARIO:\n\n";
        foreach ($report['by_veterinarian'] as $vet) {
            $output .= "   👨‍⚕️ {$vet['name']}\n";
            $output .= "      Citas: {$vet['total']}\n";
            $output .= "      Duración total: {$vet['total_duration']} minutos\n\n";
        }
        
        $output .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        $output .= "📅 RESUMEN DIARIO:\n\n";
        foreach ($report['daily_summary'] as $day) {
            $output .= "   {$day['date']} ({$day['day_name']})\n";
            $output .= "      Total: {$day['total']} citas\n";
            $output .= "      Confirmadas: {$day['confirmed']} | Pendientes: {$day['pending']}\n";
            $output .= "      Completadas: {$day['completed']} | Canceladas: {$day['cancelled']}\n\n";
        }
        
        $output .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        $output .= "📝 DETALLE DE CITAS:\n\n";
        foreach ($report['appointments'] as $index => $appointment) {
            $num = $index + 1;
            $date = $appointment->appointment_date->format('d/m/Y H:i');
            $output .= "   {$num}. Cita #{$appointment->id}\n";
            $output .= "      Fecha: {$date}\n";
            $output .= "      Cliente: {$appointment->client->name}\n";
            $output .= "      Mascota: {$appointment->pet->name} ({$appointment->pet->species})\n";
            $output .= "      Veterinario: {$appointment->user->name}\n";
            $output .= "      Estado: {$appointment->status}\n";
            $output .= "      Motivo: " . ($appointment->reason ?? 'N/A') . "\n";
            $output .= "      Duración: {$appointment->duration} minutos\n\n";
        }
        
        $output .= "═══════════════════════════════════════════════════════\n";
        $output .= "  Reporte generado el: " . Carbon::now()->format('d/m/Y H:i:s') . "\n";
        $output .= "═══════════════════════════════════════════════════════\n";
        
        return $output;
    }

    /**
     * Exportar reporte a CSV
     */
    public function exportToCSV(array $report): string
    {
        $csv = "ID,Fecha,Hora,Cliente,Mascota,Especie,Veterinario,Estado,Motivo,Duración\n";
        
        foreach ($report['appointments'] as $appointment) {
            $date = $appointment->appointment_date->format('d/m/Y');
            $time = $appointment->appointment_date->format('H:i');
            
            $csv .= implode(',', [
                $appointment->id,
                $date,
                $time,
                '"' . $appointment->client->name . '"',
                '"' . $appointment->pet->name . '"',
                $appointment->pet->species,
                '"' . $appointment->user->name . '"',
                $appointment->status,
                '"' . ($appointment->reason ?? '') . '"',
                $appointment->duration,
            ]) . "\n";
        }
        
        return $csv;
    }
}
