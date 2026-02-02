@extends('layouts.admin')

@section('title', 'Nuevo vehículo')

@section('content')
<div class="container-fluid">

  <div class="card">
    <div class="card-header">
      <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Volver
      </a>
    </div>

    <form action="{{ route('vehiculos.store') }}" method="POST">
      @csrf
      <div class="card-body">
        @include('vehicles._form')
      </div>

      <div class="card-footer d-flex justify-content-end">
        <button class="btn btn-primary">
          <i class="fas fa-save"></i> Guardar
        </button>
      </div>
    </form>
  </div>

</div>
@endsection
