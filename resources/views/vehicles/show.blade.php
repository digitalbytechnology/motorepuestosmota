@extends('layouts.admin')

@section('title', 'Detalle vehículo')

@section('content')
<div class="container-fluid">

  <div class="card">
    <div class="card-header d-flex justify-content-between">
      <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Volver
      </a>

      <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-warning btn-sm">
        <i class="fas fa-edit"></i> Editar
      </a>
    </div>

    <div class="card-body">
      <div class="row">
        @php
          $rows = [
            'Placa' => $vehiculo->placa,
            'Chasis' => $vehiculo->chasis,
            'Uso' => $vehiculo->uso,
            'Tipo' => $vehiculo->tipo,
            'Accionado' => $vehiculo->accionado,
            'Marca' => $vehiculo->marca,
            'Modelo' => $vehiculo->modelo,
            'Línea' => $vehiculo->linea,
            'No. Serie' => $vehiculo->no_serie,
            'No. Motor' => $vehiculo->no_motor,
            'Color' => $vehiculo->color,
            'Cilindrada' => $vehiculo->cilindrada ? $vehiculo->cilindrada.' cc' : null,
            'Cilindros' => $vehiculo->cilindros,
            'Tonelaje' => $vehiculo->tonelaje,
            'Asientos' => $vehiculo->asientos,
            'Ejes' => $vehiculo->ejes,
            'Puertas' => $vehiculo->puertas,
          ];
        @endphp

        @foreach($rows as $label => $value)
          <div class="col-md-4 mb-2">
            <strong>{{ $label }}:</strong> {{ $value ?? '-' }}
          </div>
        @endforeach
      </div>
    </div>
  </div>

</div>
@endsection
