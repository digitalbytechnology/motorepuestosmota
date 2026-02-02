@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')
<div class="container-fluid">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">Nuevo usuario</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-body table-responsive p-0">
      <table id="usersTable" class="table table-hover text-nowrap w-100">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th style="width: 160px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $u)
            <tr>
              <td>{{ $u->name }}</td>
              <td>{{ $u->email }}</td>
              <td>
                @foreach($u->roles as $r)
                  <span class="badge badge-secondary">{{ $r->name }}</span>
                @endforeach
              </td>
              <td class="text-nowrap">
                <a href="{{ route('usuarios.edit', $u) }}" class="btn btn-sm btn-warning">Editar</a>

                <form action="{{ route('usuarios.destroy', $u) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('¿Eliminar usuario?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-danger">Eliminar</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection

@push('css')

@endpush

@push('js')

@endpush
