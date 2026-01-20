@extends('layouts.auth')

@section('content')
<p class="login-box-msg">Inicia sesión</p>

@if ($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="input-group mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
        <div class="input-group-append">
            <div class="input-group-text">@</div>
        </div>
    </div>

    <div class="input-group mb-3">
        <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
        <div class="input-group-append">
            <div class="input-group-text">🔒</div>
        </div>
    </div>

    <div class="row">
        <div class="col-8">
            <div class="icheck-primary">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Recordarme</label>
            </div>
        </div>
        <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
        </div>
    </div>
</form>

<p class="mb-1 mt-3">
    <a href="{{ route('password.request') }}">Olvidé mi contraseña</a>
</p>
<p class="mb-0">
    <a href="{{ route('register') }}" class="text-center">Registrar usuario</a>
</p>
@endsection
