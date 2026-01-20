@extends('layouts.auth')

@section('content')
<p class="login-box-msg">Registrar usuario</p>

@if ($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="input-group mb-3">
        <input type="text" name="name" class="form-control" placeholder="Nombre" required>
        <div class="input-group-append">
            <div class="input-group-text">👤</div>
        </div>
    </div>

    <div class="input-group mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
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

    <div class="input-group mb-3">
        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmar contraseña" required>
        <div class="input-group-append">
            <div class="input-group-text">🔒</div>
        </div>
    </div>

    <div class="row">
        <div class="col-8">
            <a href="{{ route('login') }}">Ya tengo cuenta</a>
        </div>
        <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Registrar</button>
        </div>
    </div>
</form>
@endsection
