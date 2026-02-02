@extends('layouts.admin')

@section('title', 'Nuevo servicio')

@section('content')
<div class="container-fluid">

  <div class="card">
    <div class="card-header">
      <a href="{{ route('labors.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Volver
      </a>
    </div>

    <form action="{{ route('labors.store') }}" method="POST">
      @csrf

      <div class="card-body">
        @include('labors._form')
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
