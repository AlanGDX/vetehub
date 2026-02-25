@extends('emails.layout')

@section('title', 'Cita Cancelada — VeteHub')

@section('content')
    <h1>❌ Cita Cancelada</h1>

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
            <tr>
                <td>📌 Estado</td>
                <td>
                    <span class="status-badge status-cancelled">Cancelada</span>
                </td>
            </tr>
        </table>
    </div>

    <p>Si deseas reagendar esta cita, por favor comunícate con la clínica para programar una nueva fecha.</p>

    <p style="color: #94a3b8; font-size: 13px; margin-top: 24px;">
        Esta notificación se envía automáticamente al cancelar una cita.
    </p>
@endsection
