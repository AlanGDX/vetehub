<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'VeteHub - Sistema de Gestión Veterinaria')</title>
    <script>
        (function () {
            const storedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (storedTheme === 'dark' || (!storedTheme && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        html.dark body {
            background-color: #0f172a;
            color: #e5e7eb;
        }

        html.dark .bg-gray-100 {
            background-color: #0f172a !important;
        }

        html.dark .bg-gray-50 {
            background-color: #1f2937 !important;
        }

        html.dark .bg-gray-200 {
            background-color: #374151 !important;
        }

        html.dark .bg-white {
            background-color: #111827 !important;
        }

        html.dark .bg-gray-300 {
            background-color: #374151 !important;
            color: #e5e7eb !important;
        }

        html.dark .text-gray-600,
        html.dark .text-gray-700 {
            color: #d1d5db !important;
        }

        html.dark .text-gray-500 {
            color: #9ca3af !important;
        }

        html.dark .text-gray-800,
        html.dark .text-gray-900,
        html.dark .text-black {
            color: #f3f4f6 !important;
        }

        html.dark .border,
        html.dark .border-gray-300,
        html.dark .border-gray-400 {
            border-color: #4b5563 !important;
        }

        html.dark .divide-gray-200 > :not([hidden]) ~ :not([hidden]) {
            border-color: #374151 !important;
        }

        html.dark input,
        html.dark select,
        html.dark textarea {
            background-color: #1f2937 !important;
            color: #f3f4f6 !important;
            border-color: #4b5563 !important;
        }

        html.dark input::placeholder,
        html.dark textarea::placeholder {
            color: #9ca3af !important;
        }

        html.dark footer.bg-gray-800 {
            background-color: #030712 !important;
        }

        .theme-toggle-button {
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.4rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
        }

        .theme-toggle-button:hover {
            background-color: rgba(255, 255, 255, 0.12);
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Barra de navegación -->
    @auth
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold">🐾 VeteHub</a>
                    <div class="space-x-4">
                        <a href="{{ route('dashboard') }}" class="hover:text-blue-200">Dashboard</a>
                        <a href="{{ route('clients.index') }}" class="hover:text-blue-200">Clientes</a>
                        <a href="{{ route('pets.index') }}" class="hover:text-blue-200">Mascotas</a>
                        <a href="{{ route('appointments.index') }}" class="hover:text-blue-200">Citas</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button type="button" data-theme-toggle class="theme-toggle-button" aria-label="Cambiar tema">
                        🌙 Modo oscuro
                    </button>
                    <span>{{ Auth::user()->name }}</span>
                    
                    <!-- Botón de configuración -->
                    <a href="{{ route('profile.edit') }}" class="hover:text-blue-200 focus:outline-none" title="Configuración">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </a>
                    
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-blue-700 px-4 py-2 rounded hover:bg-blue-800">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    @endauth

    @guest
    <div class="fixed top-4 right-4 z-50">
        <button type="button" data-theme-toggle class="bg-gray-800 text-white px-3 py-2 rounded-lg text-sm hover:bg-gray-900" aria-label="Cambiar tema">
            🌙 Modo oscuro
        </button>
    </div>
    @endguest

    <!-- Contenido principal -->
    <main class="container mx-auto px-4 py-8">
        <!-- Mensajes de éxito -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <!-- Mensajes de error -->
        @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4 mt-8">
        <p>&copy; {{ date('Y') }} VeteHub. Sistema de Gestión Veterinaria.</p>
    </footer>

    <script>
        (function () {
            const html = document.documentElement;

            function isDarkMode() {
                return html.classList.contains('dark');
            }

            function updateButtons() {
                document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
                    button.textContent = isDarkMode() ? '☀️ Modo claro' : '🌙 Modo oscuro';
                });
            }

            function toggleTheme() {
                html.classList.toggle('dark');
                localStorage.setItem('theme', isDarkMode() ? 'dark' : 'light');
                updateButtons();
            }

            document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
                button.addEventListener('click', toggleTheme);
            });

            updateButtons();
        })();
    </script>
</body>
</html>
