<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name') }} | Auth</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <b>Motorepuestos</b>Mota
    </div>

    <div class="card">
        <div class="card-body login-card-body">
            @yield('content')
        </div>
    </div>
</div>
</body>
</html>
