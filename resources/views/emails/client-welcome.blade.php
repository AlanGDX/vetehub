@extends('emails.layout')

@section('title', 'Bienvenido a VeteHub')

@section('content')
    <h1>🎉 ¡Bienvenido a VeteHub!</h1>

    <p>Hola <strong>{{ $client->name }}</strong>,</p>

    <p>Nos alegra informarte que has sido registrado exitosamente en nuestra plataforma de gestión veterinaria. A partir de ahora podrás recibir notificaciones y recordatorios sobre las citas de tus mascotas.</p>

    <div class="info-card">
        <table class="info-table">
            <tr>
                <td>👤 Nombre</td>
                <td>{{ $client->name }}</td>
            </tr>
            <tr>
                <td>📧 Email</td>
                <td>{{ $client->email }}</td>
            </tr>
            <tr>
                <td>📱 Teléfono</td>
                <td>{{ $client->phone }}</td>
            </tr>
            @if($client->address)
            <tr>
                <td>📍 Dirección</td>
                <td>{{ $client->address }}{{ $client->city ? ', ' . $client->city : '' }}</td>
            </tr>
            @endif
        </table>
    </div>

    <p><strong>¿Qué puedes esperar?</strong></p>
    <p>📅 Recordatorios de citas programadas<br>
       ✅ Confirmaciones de nuevas citas<br>
       📋 Notificaciones sobre cambios en tus citas</p>

    <div class="divider"></div>

    <p>Si tus datos no son correctos, comunícate con la clínica para actualizarlos.</p>

    <p style="color: #94a3b8; font-size: 13px; margin-top: 24px;">
        ¡Gracias por confiar en nosotros para el cuidado de tus mascotas! 🐾
    </p>
@endsection
