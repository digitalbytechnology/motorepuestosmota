@extends('layouts.admin')

@section('title', 'Editar servicio')

@section('content')
<div class="container-fluid">

  <div class="card">
    <div class="card-header">
      <a href="{{ route('labors.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Volver
      </a>
    </div>

    <form action="{{ route('labors.update', $labor) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="card-body">
        @include('labors._form', ['labor' => $labor])
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
