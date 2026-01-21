@extends('layouts.admin')
@section('title', 'Configurar límite por día')

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Límite de citas por día (independiente)</h3>
        </div>

        <div class="card-body">

          <div class="alert alert-success d-none" id="okMsg"></div>
          <div class="alert alert-danger d-none" id="errMsg"></div>

          <form id="formDayLimit">
            @csrf

            <div class="form-group">
              <label for="date">Día</label>
              <input type="date" id="date" name="date" class="form-control" required>
              <small class="text-muted" id="hint"></small>
            </div>

            <div class="form-group">
              <label for="max_per_day">Máximo de citas para este día</label>
              <input type="number"
                     id="max_per_day"
                     name="max_per_day"
                     class="form-control"
                     min="0" max="500"
                     placeholder="0 = bloquear el día"
                     required>
            </div>

            <div class="d-flex justify-content-between">
              <a href="{{ route('citas.index') }}" class="btn btn-secondary">Volver</a>
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>

          <hr>

          <button class="btn btn-outline-danger btn-sm" id="btnDelete" disabled>
            Quitar límite especial del día (volver al default)
          </button>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
  {{-- Vite JS separado --}}
  @vite('resources/js/day_limits.js')
@endpush
