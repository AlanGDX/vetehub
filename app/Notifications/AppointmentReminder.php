<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;
    protected $recipientType;

    /**
     * Create a new notification instance.
     */
    public function __construct(Appointment $appointment, string $recipientType)
    {
        $this->appointment = $appointment;
        $this->recipientType = $recipientType;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $appointment = $this->appointment;
        $formattedDate = $appointment->appointment_date->format('d/m/Y');
        $formattedTime = $appointment->appointment_date->format('H:i');

        if ($this->recipientType === 'client') {
            return (new MailMessage)
                ->subject('Recordatorio: Cita para ' . $appointment->pet->name)
                ->greeting('¡Hola ' . $appointment->client->name . '!')
                ->line('Te recordamos que tienes una cita programada para tu mascota ' . $appointment->pet->name . '.')
                ->line('**Fecha:** ' . $formattedDate)
                ->line('**Hora:** ' . $formattedTime)
                ->line('**Veterinario:** Dr./Dra. ' . $appointment->user->name)
                ->line('**Motivo:** ' . $appointment->reason)
                ->line('Por favor, llega 10 minutos antes de tu cita.')
                ->action('Ver Detalles', url('/dashboard'))
                ->line('Si necesitas cancelar o reprogramar, contáctanos lo antes posible.')
                ->salutation('¡Nos vemos pronto! - Equipo VeteHub');
        } else {
            // Para el veterinario/usuario
            return (new MailMessage)
                ->subject('Recordatorio: Cita con ' . $appointment->client->name)
                ->greeting('¡Hola Dr./Dra. ' . $appointment->user->name . '!')
                ->line('Recordatorio de cita programada:')
                ->line('**Cliente:** ' . $appointment->client->name)
                ->line('**Mascota:** ' . $appointment->pet->name . ' (' . $appointment->pet->species . ')')
                ->line('**Fecha:** ' . $formattedDate)
                ->line('**Hora:** ' . $formattedTime)
                ->line('**Motivo:** ' . $appointment->reason)
                ->action('Ver Detalles', url('/dashboard'))
                ->line('Revisa el historial de la mascota antes de la cita.')
                ->salutation('Equipo VeteHub');
        }
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'appointment_date' => $this->appointment->appointment_date,
            'recipient_type' => $this->recipientType,
        ];
    }
}
