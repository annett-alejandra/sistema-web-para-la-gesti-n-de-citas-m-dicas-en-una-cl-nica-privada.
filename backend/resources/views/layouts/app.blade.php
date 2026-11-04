<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="referrer" content="no-referrer">
    <title>@yield('title', 'Clínica Vida Salud — Excelencia Médica & Hospitalidad Terapéutica')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <div class="bg-ambient-glow" aria-hidden="true"></div>
    <a class="skip-link" href="#contenido">Ir al contenido</a>

    <header class="site-header">
        <div class="container">
            <div class="site-header-inner">
                <a class="brand" href="{{ route('inicio') }}">
                    <span>Clínica Vida Salud</span><span class="brand-dot" aria-hidden="true"></span>
                </a>
                @if (request()->routeIs('inicio'))
                    <button id="toggle-motion-btn" type="button" class="btn-minimal" aria-pressed="false" hidden>
                        Pausar animaciones
                    </button>
                @endif
            </div>
            @if (request()->routeIs('inicio'))
                <nav class="portal-nav" aria-label="Capítulos de la clínica">
                    <a href="#capitulo-01">Complejo</a>
                    <a href="#capitulo-02">Unidades y suites</a>
                    <a href="#capitulo-03">Vocación</a>
                    <a href="#capitulo-04">Instalaciones</a>
                    <a href="#admisiones">Admisiones</a>
                </nav>
            @endif
            <x-portal-nav />
        </div>
    </header>

    <main id="contenido" class="main-content" tabindex="-1">
        <div class="container">
            @include('components.alerts')
        </div>
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
