<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VeteHub')</title>
    <style>
        /* Reset */
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f4f8;
            color: #334155;
            line-height: 1.6;
        }

        .email-wrapper {
            width: 100%;
            background-color: #f0f4f8;
            padding: 40px 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }

        /* Header */
        .email-header {
            background: linear-gradient(135deg, #0ea5e9, #0284c7, #0369a1);
            padding: 32px 40px;
            text-align: center;
        }

        .email-header .logo {
            font-size: 28px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.5px;
        }

        .email-header .logo-icon {
            font-size: 32px;
            margin-right: 8px;
        }

        .email-header .tagline {
            color: rgba(255, 255, 255, 0.85);
            font-size: 13px;
            margin-top: 4px;
            letter-spacing: 0.5px;
        }

        /* Body */
        .email-body {
            padding: 40px;
        }

        .email-body h1 {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 16px;
        }

        .email-body p {
            font-size: 15px;
            color: #475569;
            margin-bottom: 16px;
            line-height: 1.7;
        }

        /* Info Card */
        .info-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #0ea5e9;
            border-radius: 8px;
            padding: 20px 24px;
            margin: 24px 0;
        }

        .info-card .info-row {
            display: flex;
            padding: 6px 0;
            font-size: 14px;
        }

        .info-card .info-label {
            font-weight: 600;
            color: #334155;
            min-width: 130px;
        }

        .info-card .info-value {
            color: #64748b;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 8px 0;
            font-size: 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-table td:first-child {
            font-weight: 600;
            color: #334155;
            width: 140px;
        }

        .info-table td:last-child {
            color: #64748b;
        }

        .info-table tr:last-child td {
            border-bottom: none;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .status-confirmed {
            background-color: #d1fae5;
            color: #065f46;
        }

        .status-completed {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }

        /* CTA Button */
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #0ea5e9, #0284c7);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            margin: 24px 0;
            text-align: center;
            transition: background 0.3s;
        }

        /* Divider */
        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 24px 0;
        }

        /* Footer */
        .email-footer {
            background-color: #f8fafc;
            padding: 24px 40px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .email-footer p {
            font-size: 12px;
            color: #94a3b8;
            margin: 4px 0;
        }

        .email-footer .footer-brand {
            font-weight: 600;
            color: #64748b;
            font-size: 13px;
        }

        /* Responsive */
        @media only screen and (max-width: 620px) {
            .email-body {
                padding: 24px 20px;
            }
            .email-header {
                padding: 24px 20px;
            }
            .email-footer {
                padding: 20px;
            }
            .info-card {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            {{-- Header --}}
            <div class="email-header">
                <div class="logo">
                    <span class="logo-icon">🐾</span> VeteHub
                </div>
                <div class="tagline">Gestión Veterinaria Profesional</div>
            </div>

            {{-- Body --}}
            <div class="email-body">
                @yield('content')
            </div>

            {{-- Footer --}}
            <div class="email-footer">
                <p class="footer-brand">🐾 VeteHub</p>
                <p>Gestión veterinaria moderna y confiable</p>
                <p style="margin-top: 12px;">Este es un correo automático, por favor no responder.</p>
                <p>&copy; {{ date('Y') }} VeteHub. Todos los derechos reservados.</p>
            </div>
        </div>
    </div>
</body>
</html>
