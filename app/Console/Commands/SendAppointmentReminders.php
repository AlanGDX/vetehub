<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Notifications\AppointmentReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * The console command description.
     */
    protected $description = 'Enviar recordatorios de citas programadas para las próximas 24 horas';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = Carbon::now();
        $tomorrow = $now->copy()->addDay();

        // Buscar citas entre ahora y 24 horas que no estén canceladas ni completadas
        $appointments = Appointment::with(['client', 'pet', 'user'])
            ->whereBetween('appointment_date', [$now, $tomorrow])
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->get();

        if ($appointments->isEmpty()) {
            $this->info('No hay citas para recordar en las próximas 24 horas.');
            return Command::SUCCESS;
        }

        $count = 0;
        $errors = 0;

        /** @var \App\Models\Appointment $appointment */
        foreach ($appointments as $appointment) {
            try {
                $this->line("Procesando cita #{$appointment->id}: {$appointment->client->name} - {$appointment->pet->name}");
                
                // Notificar al cliente (dueño de la mascota)
                $appointment->client->notify(
                    new AppointmentReminder($appointment, 'client')
                );

                // Notificar al veterinario (usuario)
                $appointment->user->notify(
                    new AppointmentReminder($appointment, 'user')
                );

                $count++;
                $this->info("  ✓ Recordatorios enviados correctamente");
            } catch (\Exception $e) {
                $errors++;
                $this->error("  ✗ Error al enviar recordatorios: " . $e->getMessage());
                $this->warn("  → Los trabajos se añadieron a la cola. Ejecuta: php artisan queue:work");
            }
        }

        $this->newLine();
        $this->info("Resumen: {$count} cita(s) procesada(s), {$errors} error(es)");
        
        if ($errors > 0) {
            $this->newLine();
            $this->warn("⚠ Hay errores de envío. Posible problema de conexión SMTP.");
            $this->warn("  Soluciones:");
            $this->warn("  1. Verifica tu conexión a Internet");
            $this->warn("  2. Revisa la configuración en .env (MAIL_HOST, MAIL_PORT, etc.)");
            $this->warn("  3. Configura el firewall para permitir SMTP saliente");
            $this->warn("  4. Procesa la cola: php artisan queue:work --stop-when-empty");
            $this->warn("  5. Modo desarrollo: Cambia MAIL_MAILER=log en .env");
        }

        return Command::SUCCESS;
    }
}
