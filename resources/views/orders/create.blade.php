@extends('layouts.admin')
@section('title','Nueva orden')

@section('content')
<div class="container-fluid">

  <div class="card">
    <div class="card-body">

      <form method="POST" action="{{ route('orders.store') }}">
        @csrf

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Cliente</label>
              <select name="client_id" class="form-control">
                <option value="">-- Seleccionar --</option>
                @foreach($clients as $c)
                  <option value="{{ $c->id }}" {{ old('client_id') == $c->id ? 'selected':'' }}>
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
                  <option value="{{ $v->id }}" {{ old('vehicle_id') == $v->id ? 'selected':'' }}>
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
                  <option value="{{ $st }}" {{ old('status','abierta') === $st ? 'selected':'' }}>
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
              <textarea name="notes" rows="4" class="form-control">{{ old('notes') }}</textarea>
              @error('notes') <small class="text-danger">{{ $message }}</small> @enderror
            </div>
          </div>
        </div>

        <button class="btn btn-primary">
          <i class="fas fa-save"></i> Crear orden
        </button>

        <a href="{{ route('orders.index') }}" class="btn btn-secondary">Cancelar</a>
      </form>

    </div>
  </div>

</div>
@endsection
