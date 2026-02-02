@extends('layouts.admin')

@section('title', 'Mano de Obra')

@section('content')
<div class="container-fluid">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <a href="{{ route('labors.create') }}" class="btn btn-primary">
      Nuevo servicio
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-body table-responsive p-0">
      <table id="laborsTable" class="table table-hover text-nowrap w-100">
        <thead>
          <tr>
            <th>Servicio</th>
            <th>Descripción</th>
            <th>Precio</th>
            <th>Estado</th>
            <th style="width:160px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($labors as $l)
            <tr>
              <td>{{ $l->nombre }}</td>
              <td>{{ $l->descripcion ?? '-' }}</td>
              <td>Q {{ number_format($l->precio, 2) }}</td>

              {{-- ESTADO --}}
              <td>
                @if($l->activo)
                  <span class="badge badge-success">Activo</span>
                @else
                  <span class="badge badge-danger">Inactivo</span>
                @endif
              </td>

              <td class="text-nowrap">
                <a href="{{ route('labors.edit', $l) }}"
                   class="btn btn-sm btn-warning">Editar</a>

                <form action="{{ route('labors.destroy', $l) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('¿Eliminar servicio?')">
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
