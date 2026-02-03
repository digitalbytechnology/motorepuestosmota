@extends('layouts.admin')
@section('title','Categorías')

@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">Nueva categoría</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-body table-responsive p-0">
      <table id="categoriesTable" class="table table-hover text-nowrap w-100">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Estado</th>
            <th style="width:160px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($categories as $c)
            <tr>
              <td>{{ $c->nombre }}</td>
              <td>
                @if($c->activo)
                  <span class="badge badge-success">Activo</span>
                @else
                  <span class="badge badge-danger">Inactivo</span>
                @endif
              </td>
              <td class="text-nowrap">
                <a class="btn btn-sm btn-warning" href="{{ route('categories.edit',$c) }}">Editar</a>
                <form class="d-inline" method="POST" action="{{ route('categories.destroy',$c) }}"
                      onsubmit="return confirm('¿Eliminar categoría?')">
                  @csrf @method('DELETE')
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
