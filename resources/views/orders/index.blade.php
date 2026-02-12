@extends('layouts.admin')

@section('title', 'Órdenes')

@section('content')
<div class="container-fluid">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <a href="{{ route('orders.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i> Nueva orden
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-body table-responsive p-0">
      <table id="ordersTable" class="table table-hover text-nowrap w-100">
        <thead>
          <tr>
            <th>#</th>
            <th>Fecha</th>
            <th>Cliente</th>
            <th>Vehículo</th>
            <th>Estado</th>
            <th>Total</th>
            <th style="width:220px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($orders as $o)
            <tr>
              <td>{{ $o->id }}</td>
              <td>{{ $o->created_at->format('d/m/Y H:i') }}</td>
              <td>{{ $o->client->name ?? '-' }}</td>
              <td>
                @if($o->vehicle)
                  {{ $o->vehicle->placa ?? '-' }} — {{ $o->vehicle->marca ?? '' }} {{ $o->vehicle->modelo ?? '' }}
                @else
                  -
                @endif
              </td>
              <td>
                @php
                  $badge = match($o->status) {
                    'abierta' => 'secondary',
                    'proceso' => 'info',
                    'finalizada' => 'warning',
                    'entregada' => 'success',
                    default => 'secondary'
                  };
                @endphp
                <span class="badge badge-{{ $badge }}">{{ strtoupper($o->status) }}</span>
              </td>
              <td>Q {{ number_format($o->grand_total, 2) }}</td>
              <td class="text-nowrap">
                <a href="{{ route('orders.edit', $o) }}" class="btn btn-sm btn-warning">
                  <i class="fas fa-edit"></i> Editar
                </a>

                <a href="{{ route('orders.inspection.edit', $o) }}" class="btn btn-sm btn-info">
                  <i class="fas fa-camera"></i> Inspección
                </a>

                <form action="{{ route('orders.destroy', $o) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('¿Eliminar orden?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

</div>
@endsection

@push('scripts')
  {{-- DataTables Orders via Vite --}}
  @vite('resources/js/datatables-orders.js')
@endpush
