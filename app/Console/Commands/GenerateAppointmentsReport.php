<?php

namespace App\Console\Commands;

use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateAppointmentsReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:report 
                            {--start= : Fecha de inicio (Y-m-d). Default: hace 30 días}
                            {--end= : Fecha de fin (Y-m-d). Default: hoy}
                            {--status= : Filtrar por estado (confirmed, pending, completed, cancelled)}
                            {--veterinarian= : ID del veterinario}
                            {--client= : ID del cliente}
                            {--format=text : Formato de salida (text, csv)}
                            {--output= : Archivo de salida (opcional, por defecto muestra en pantalla)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generar reporte de citas por rango de fechas';

    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        parent::__construct();
        $this->reportService = $reportService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Obtener fechas
        $startDate = $this->option('start') ?? Carbon::now()->subDays(30)->format('Y-m-d');
        $endDate = $this->option('end') ?? Carbon::now()->format('Y-m-d');

        // Validar fechas
        try {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
        } catch (\Exception $e) {
            $this->error('❌ Error: Formato de fecha inválido. Use Y-m-d (ej: 2026-02-25)');
            return 1;
        }

        if ($start->gt($end)) {
            $this->error('❌ Error: La fecha de inicio no puede ser mayor que la fecha de fin');
            return 1;
        }

        $this->info("🔍 Generando reporte de citas...\n");
        $this->line("   Período: {$start->format('d/m/Y')} - {$end->format('d/m/Y')}");

        // Preparar opciones
        $options = [];
        
        if ($status = $this->option('status')) {
            $options['status'] = $status;
            $this->line("   Filtro de estado: {$status}");
        }

        if ($vetId = $this->option('veterinarian')) {
            $options['user_id'] = $vetId;
            $this->line("   Filtro de veterinario: ID {$vetId}");
        }

        if ($clientId = $this->option('client')) {
            $options['client_id'] = $clientId;
            $this->line("   Filtro de cliente: ID {$clientId}");
        }

        $this->newLine();

        // Generar reporte
        try {
            $report = $this->reportService->generateAppointmentsReport(
                $startDate,
                $endDate,
                $options
            );
        } catch (\Exception $e) {
            $this->error("❌ Error al generar el reporte: {$e->getMessage()}");
            return 1;
        }

        // Formato de salida
        $format = $this->option('format');
        
        if ($format === 'csv') {
            $content = $this->reportService->exportToCSV($report);
        } else {
            $content = $this->reportService->exportToText($report);
        }

        // Guardar o mostrar
        if ($outputFile = $this->option('output')) {
            file_put_contents($outputFile, $content);
            $this->info("✅ Reporte guardado en: {$outputFile}");
        } else {
            $this->line($content);
        }

        // Mostrar resumen quick
        $this->newLine();
        $this->info('✅ Reporte generado exitosamente');
        $this->line("   Total de citas: {$report['summary']['total']}");
        
        return 0;
    }
}
