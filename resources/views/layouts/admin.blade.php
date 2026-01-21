<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF para fetch/AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    {{-- Vite global --}}
    @vite(['resources/css/app.css','resources/js/app.js'])

    {{-- Para CSS extra por vista (ej: citas) --}}
    @stack('css')
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

    {{-- NAVBAR --}}
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link">Salir</button>
                </form>
            </li>
        </ul>
    </nav>

    {{-- SIDEBAR --}}
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('dashboard') }}" class="brand-link text-center">
            <span class="brand-text fw-light">{{ config('app.name') }}</span>
        </a>

        <div class="sidebar">
            <nav class="mt-3">
                <ul class="nav nav-pills nav-sidebar flex-column"
                    data-widget="treeview"
                    role="menu"
                    data-accordion="false">

                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    {{-- CITAS (visible para roles que quieras) --}}
                    @hasanyrole('admin|vendedor|mecanico')
                    <li class="nav-item">
                        <a href="{{ route('citas.index') }}"
                           class="nav-link {{ request()->routeIs('citas.*') ? 'active' : '' }}">
                            <i class="nav-icon far fa-calendar-alt"></i>
                            <p>Citas</p>
                        </a>
                    </li>
                    @endhasanyrole

                    @role('admin')
                    <li class="nav-item">
                        <a href="{{ route('admin.index') }}"
                           class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Administración</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('usuarios.index') }}"
                           class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Usuarios</p>
                        </a>
                    </li>
                    @endrole

                    @hasanyrole('vendedor|admin')
                    <li class="nav-item">
                        <a href="{{ route('ventas.index') }}"
                           class="nav-link {{ request()->routeIs('ventas.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>Ventas</p>
                        </a>
                    </li>
                    @endhasanyrole

                    @hasanyrole('mecanico|admin')
                    <li class="nav-item">
                        <a href="{{ route('taller.index') }}"
                           class="nav-link {{ request()->routeIs('taller.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tools"></i>
                            <p>Taller</p>
                        </a>
                    </li>
                    @endhasanyrole

                </ul>
            </nav>
        </div>
    </aside>

    {{-- CONTENIDO --}}
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('title')</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    <div class="sidebar-overlay"></div>

    {{-- FOOTER --}}
    <footer class="main-footer text-center">
        <strong>Motorepuestos Mota</strong> © {{ date('Y') }}
    </footer>

</div>

{{-- Para JS extra por vista (ej: citas) --}}
@stack('js')
</body>
</html>
