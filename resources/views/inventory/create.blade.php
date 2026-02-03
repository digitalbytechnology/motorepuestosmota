@extends('layouts.admin')
@section('title','Movimiento de inventario')

@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header">
      <a href="{{ route('parts.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Volver
      </a>
    </div>

    <div class="card-body">
      <p><b>Repuesto:</b> {{ $part->nombre }} | <b>Stock:</b> {{ $part->stock }} | <b>Mínimo:</b> {{ $part->stock_min }}</p>

      <form method="POST" action="{{ route('inventory.store',$part) }}">
        @csrf

        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>Tipo</label>
              <select name="type" class="form-control" required>
                <option value="in">Entrada</option>
                <option value="out">Salida</option>
                <option value="adjust">Ajuste (+)</option>
              </select>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label>Cantidad</label>
              <input type="number" name="qty" min="1" class="form-control" required>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label>Costo unitario (opcional)</label>
              <input type="number" step="0.01" min="0" name="unit_cost" class="form-control">
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label>Nota</label>
              <input type="text" name="note" class="form-control" maxlength="255">
            </div>
          </div>
        </div>

        <button class="btn btn-primary"><i class="fas fa-check"></i> Aplicar</button>
      </form>
    </div>
  </div>
</div>
@endsection
