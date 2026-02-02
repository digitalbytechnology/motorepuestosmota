@extends('layouts.admin')
@section('title', 'Clientes')

@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Listado de clientes</h3>

            <a href="{{ route('clientes.create') }}" class="btn btn-sm btn-success">
                + Nuevo cliente
            </a>
        </div>

        <div class="card-body table-responsive">
            <table id="clientsTable" class="table table-bordered table-hover w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Documento</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($clients as $c)
                    <tr>
                        <td>{{ $c->id }}</td>
                        <td>{{ $c->document_type }}: {{ $c->document_number }}</td>
                        <td>{{ $c->name }}</td>
                        <td>{{ $c->phone }}</td>
                        <td>{{ $c->email }}</td>
                        <td>
                            @if($c->is_active)
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-danger">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-nowrap">
                            <a class="btn btn-sm btn-info" href="{{ route('clientes.show', $c) }}">Ver</a>
                            <a class="btn btn-sm btn-warning" href="{{ route('clientes.edit', $c) }}">Editar</a>

                            <form method="POST" action="{{ route('clientes.toggle-status', $c) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="btn btn-sm {{ $c->is_active ? 'btn-secondary' : 'btn-success' }}">
                                    {{ $c->is_active ? 'Inactivar' : 'Activar' }}
                                </button>
                            </form>

                            <form method="POST" action="{{ route('clientes.destroy', $c) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('¿Eliminar este cliente?')">
                                @csrf
                                @method('DELETE')
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

@push('css')
@endpush

{{-- OJO: tu layout usa @stack('scripts'), así que este push debe llamarse scripts --}}
@push('scripts')
    @vite('resources/js/clients.js')
@endpush
