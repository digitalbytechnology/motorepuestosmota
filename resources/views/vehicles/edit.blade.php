@extends('layouts.admin')

@section('title', 'Editar vehículo')

@section('content')
<div class="container-fluid">

  <div class="card">
    <div class="card-header">
      <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Volver
      </a>
    </div>

    <form action="{{ route('vehiculos.update', $vehiculo) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="card-body">
        @include('vehicles._form', ['vehiculo' => $vehiculo])
      </div>

      <div class="card-footer d-flex justify-content-end">
        <button class="btn btn-primary">
          <i class="fas fa-save"></i> Actualizar
        </button>
      </div>
    </form>
  </div>

</div>
@endsection
