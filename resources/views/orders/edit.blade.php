@extends('layouts.admin')
@section('title','Editar orden #'.$order->id)

@section('content')
<div class="container-fluid">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <div><b>Orden:</b> #{{ $order->id }}</div>
      <div class="text-muted">
        <b>Total:</b> Q {{ number_format($order->grand_total,2) }}
      </div>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('orders.inspection.edit', $order) }}" class="btn btn-info">
        <i class="fas fa-camera"></i> Inspección
      </a>
      <a href="{{ route('orders.index') }}" class="btn btn-secondary">Volver</a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-body">

      <form method="POST" action="{{ route('orders.update', $order) }}">
        @csrf @method('PUT')

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Cliente</label>
              <select name="client_id" class="form-control">
                <option value="">-- Seleccionar --</option>
                @foreach($clients as $c)
                  <option value="{{ $c->id }}" {{ old('client_id',$order->client_id) == $c->id ? 'selected':'' }}>
                    {{ $c->name }} ({{ $c->document_number }})
                  </option>
                @endforeach
              </select>
              @error('client_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
              <label>Vehículo</label>
              <select name="vehicle_id" class="form-control">
                <option value="">-- Seleccionar --</option>
                @foreach($vehicles as $v)
                  <option value="{{ $v->id }}" {{ old('vehicle_id',$order->vehicle_id) == $v->id ? 'selected':'' }}>
                    {{ $v->placa ?? '-' }} — {{ $v->marca ?? '' }} {{ $v->modelo ?? '' }}
                  </option>
                @endforeach
              </select>
              @error('vehicle_id') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label>Estado</label>
              <select name="status" class="form-control">
                @foreach(['abierta','proceso','finalizada','entregada'] as $st)
                  <option value="{{ $st }}" {{ old('status',$order->status) === $st ? 'selected':'' }}>
                    {{ strtoupper($st) }}
                  </option>
                @endforeach
              </select>
              @error('status') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
          </div>

          <div class="col-md-12">
            <div class="form-group">
              <label>Notas / Problemas del vehículo</label>
              <textarea name="notes" rows="4" class="form-control">{{ old('notes',$order->notes) }}</textarea>
              @error('notes') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
          </div>
        </div>

        <button class="btn btn-primary">
          <i class="fas fa-save"></i> Guardar cambios
        </button>
      </form>

      <hr>
      <div class="alert alert-info mb-0">
        Próximo paso: aquí agregaremos <b>Mano de obra</b> y <b>Repuestos</b> dentro de la orden con totales automáticos.
      </div>

    </div>
  </div>

</div>
@endsection
