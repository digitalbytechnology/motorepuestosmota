@extends('layouts.admin')

@section('title', 'Vehículos')

@section('content')
<div class="container-fluid">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <a href="{{ route('vehiculos.create') }}" class="btn btn-primary">
      Nuevo vehículo
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-body table-responsive p-0">
      <table id="vehiclesTable" class="table table-hover text-nowrap w-100">
        <thead>
          <tr>
            <th>Placa</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Tipo</th>
            <th>Color</th>
            <th style="width: 160px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($vehicles as $v)
            <tr>
              <td>{{ $v->placa ?? '-' }}</td>
              <td>{{ $v->marca ?? '-' }}</td>
              <td>{{ $v->modelo ?? '-' }}</td>
              <td>{{ $v->tipo ?? '-' }}</td>
              <td>{{ $v->color ?? '-' }}</td>
              <td class="text-nowrap">
                <a href="{{ route('vehiculos.edit', $v) }}" class="btn btn-sm btn-warning">
                  Editar
                </a>

                <form action="{{ route('vehiculos.destroy', $v) }}"
                      method="POST"
                      class="d-inline"
                      onsubmit="return confirm('¿Eliminar vehículo?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-danger">
                    Eliminar
                  </button>
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

