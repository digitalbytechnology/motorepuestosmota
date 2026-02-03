@extends('layouts.admin')
@section('title','Repuestos')

@section('content')
<div class="container-fluid">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div></div>
    <a href="{{ route('parts.create') }}" class="btn btn-primary">
      <i class="fas fa-plus"></i> Nuevo repuesto
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-body table-responsive p-0">
      <table id="partsTable" class="table table-hover text-nowrap w-100">
        <thead>
          <tr>
            <th>SKU</th>
            <th>Repuesto</th>
            <th>Categoría</th>
            <th>Marca</th>
            <th>Aplica a</th>
            <th>Stock</th>
            <th>Mínimo</th>
            <th>Estado</th>
            <th style="width:220px;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($parts as $p)
            @php $low = ($p->stock <= $p->stock_min); @endphp
            <tr>
              <td>{{ $p->sku ?? '-' }}</td>
              <td>
                {{ $p->nombre }}
                @if($p->descripcion)
                  <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($p->descripcion, 60) }}</small>
                @endif
              </td>
              <td>{{ $p->category->nombre ?? '-' }}</td>
              <td>{{ $p->marca ?? '-' }}</td>

              {{-- Esto hace que DataTables encuentre "Candela xr150" --}}
              <td>{{ $p->aplica_a ?? '-' }}</td>

              <td>
                <span class="badge {{ $low ? 'badge-danger' : 'badge-success' }}">
                  {{ $p->stock }}
                </span>
              </td>
              <td>{{ $p->stock_min }}</td>
              <td>
                @if($p->activo)
                  <span class="badge badge-success">Activo</span>
                @else
                  <span class="badge badge-danger">Inactivo</span>
                @endif
              </td>
              <td class="text-nowrap">
                <a class="btn btn-sm btn-info" href="{{ route('inventory.kardex',$p) }}">Kardex</a>
                <a class="btn btn-sm btn-secondary" href="{{ route('inventory.create',$p) }}">Movimiento</a>
                <a class="btn btn-sm btn-warning" href="{{ route('parts.edit',$p) }}">Editar</a>

                <form class="d-inline" method="POST" action="{{ route('parts.destroy',$p) }}"
                      onsubmit="return confirm('¿Eliminar repuesto?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger">Eliminar</button>
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
