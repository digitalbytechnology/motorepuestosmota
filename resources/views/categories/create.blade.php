@extends('layouts.admin')
@section('title','Nueva categoría')

@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Volver
      </a>
    </div>
    <form method="POST" action="{{ route('categories.store') }}">
      @csrf
      <div class="card-body">@include('categories._form')</div>
      <div class="card-footer d-flex justify-content-end">
        <button class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
      </div>
    </form>
  </div>
</div>
@endsection
