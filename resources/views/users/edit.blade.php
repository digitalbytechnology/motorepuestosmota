@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1>Editar usuario</h1>

    <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
        @csrf @method('PUT')

        <div class="mb-3">
            <label>Nombre</label>
            <input name="name" class="form-control" value="{{ $usuario->name }}" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input name="email" type="email" class="form-control" value="{{ $usuario->email }}" required>
        </div>

        <div class="mb-3">
            <label>Nueva contraseña (opcional)</label>
            <input name="password" type="password" class="form-control">
        </div>

        <div class="mb-3">
            <label>Rol</label>
            <select name="role" class="form-control" required>
                @foreach($roles as $r)
                    <option value="{{ $r->name }}" {{ $usuario->hasRole($r->name) ? 'selected' : '' }}>
                        {{ ucfirst($r->name) }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Actualizar</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
