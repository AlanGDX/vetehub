@extends('emails.layout')

@section('title', 'Recordatorio de Cita — VeteHub')

@section('content')
    <h1>⏰ Recordatorio de Cita</h1>

    <p>Hola <strong>{{ $recipientName }}</strong>,</p>

    <p>{{ $messageIntro }}</p>

    <div class="info-card">
        <table class="info-table">
            <tr>
                <td>📅 Fecha</td>
                <td>{{ $appointment->appointment_date->translatedFormat('l, d \d\e F \d\e Y') }}</td>
            </tr>
            <tr>
                <td>🕐 Hora</td>
                <td>{{ $appointment->appointment_date->format('h:i A') }}</td>
            </tr>
            <tr>
                <td>⏱️ Duración</td>
                <td>{{ $appointment->duration }} minutos</td>
            </tr>
            <tr>
                <td>🐾 Mascota</td>
                <td>{{ $appointment->pet->name }} ({{ $appointment->pet->species }})</td>
            </tr>
            <tr>
                <td>👤 Cliente</td>
                <td>{{ $appointment->client->name }}</td>
            </tr>
            <tr>
                <td>📋 Motivo</td>
                <td>{{ $appointment->reason }}</td>
            </tr>
        </table>
    </div>

    <p>Por favor, asegúrate de estar disponible a la hora indicada. Si necesitas reprogramar, comunícate con la clínica lo antes posible.</p>

    <p style="color: #94a3b8; font-size: 13px; margin-top: 24px;">
        Este es un recordatorio automático enviado 24 horas antes de tu cita.
    </p>
@endsection
