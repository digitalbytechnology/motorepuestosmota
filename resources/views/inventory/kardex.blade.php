@extends('layouts.admin')
@section('title','Kardex')

@section('content')
<div class="container-fluid">
  <div class="card">
    <div class="card-header d-flex justify-content-between">
      <a href="{{ route('parts.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Volver
      </a>
      <a href="{{ route('inventory.create',$part) }}" class="btn btn-primary btn-sm">
        Nuevo movimiento
      </a>
    </div>

    <div class="card-body">
      <p><b>Repuesto:</b> {{ $part->nombre }} | <b>Stock actual:</b> {{ $part->stock }}</p>

      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Tipo</th>
            <th>Cantidad</th>
            <th>Costo</th>
            <th>Nota</th>
          </tr>
        </thead>
        <tbody>
          @foreach($movements as $m)
            <tr>
              <td>{{ $m->created_at }}</td>
              <td>{{ $m->type }}</td>
              <td>{{ $m->qty }}</td>
              <td>{{ $m->unit_cost ?? '-' }}</td>
              <td>{{ $m->note ?? '-' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
