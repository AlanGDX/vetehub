@extends('emails.layout')

@section('title', 'Confirmación de Cita — VeteHub')

@section('content')
    <h1>✅ Cita Confirmada</h1>

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
            <tr>
                <td>📌 Estado</td>
                <td>
                    <span class="status-badge status-{{ $appointment->status }}">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    @if($appointment->notes)
        <p><strong>Notas:</strong> {{ $appointment->notes }}</p>
    @endif

    <p style="color: #94a3b8; font-size: 13px; margin-top: 24px;">
        Si necesitas modificar o cancelar esta cita, por favor comunícate con la clínica.
    </p>
@endsection
