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

    {{-- Para CSS extra por vista --}}
    @stack('css')

    {{-- DataTables CSS (CDN) --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
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

    {{-- Dashboard --}}
    <li class="nav-item">
        <a href="{{ route('dashboard') }}"
           class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="nav-icon fas fa-home"></i>
            <p>Dashboard</p>
        </a>
    </li>

    {{-- Citas --}}
    @hasanyrole('admin|vendedor|mecanico')
    <li class="nav-item">
        <a href="{{ route('citas.index') }}"
           class="nav-link {{ request()->routeIs('citas.*') ? 'active' : '' }}">
            <i class="nav-icon far fa-calendar-alt"></i>
            <p>Citas</p>
        </a>
    </li>
    @endhasanyrole

    {{-- Clientes --}}
    @hasanyrole('admin|vendedor')
    <li class="nav-item">
        <a href="{{ route('clientes.index') }}"
           class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-friends"></i>
            <p>Clientes</p>
        </a>
    </li>
    @endhasanyrole

    {{-- vehiculos --}}
   @hasanyrole('admin|vendedor')
<li class="nav-item">
    <a href="{{ route('vehiculos.index') }}"
       class="nav-link {{ request()->routeIs('vehiculos.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-car"></i>
        <p>Vehículos</p>
    </a>
</li>
@endhasanyrole

@hasanyrole('admin|vendedor')
<li class="nav-item">
    <a href="{{ route('labors.index') }}"
       class="nav-link {{ request()->routeIs('labors.*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-wrench"></i>
        <p>Mano de Obra</p>
    </a>
</li>
@endhasanyrole



    {{-- Usuarios (solo admin) --}}
    @role('admin')
    <li class="nav-item">
        <a href="{{ route('usuarios.index') }}"
           class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-users"></i>
            <p>Usuarios</p>
        </a>
    </li>
    @endrole

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

{{-- SCRIPTS al final del body (CORRECTO) --}}

{{-- DataTables core --}}
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

{{-- Buttons --}}
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>

{{-- Excel / PDF deps --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

{{-- Buttons exports --}}
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

{{-- Stack correcto para tus vistas --}}
@stack('scripts')

</body>
</html>
