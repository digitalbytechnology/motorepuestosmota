@extends('layouts.admin')
@section('title', 'Detalle Cliente')

@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h3 class="card-title">Detalle del cliente</h3>
            <div>
                <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-warning">Editar</a>
                <a href="{{ route('clients.index') }}" class="btn btn-sm btn-secondary">Volver</a>
            </div>
        </div>

        <div class="card-body">
            <p><strong>Documento:</strong> {{ $client->document_type }} - {{ $client->document_number }}</p>
            <p><strong>Nombre:</strong> {{ $client->name }}</p>
            <p><strong>Dirección:</strong> {{ $client->address }}</p>
            <p><strong>Teléfono:</strong> {{ $client->phone }}</p>
            <p><strong>Correo:</strong> {{ $client->email }}</p>
            <p><strong>Estado:</strong>
                @if($client->is_active)
                    <span class="badge badge-success">Activo</span>
                @else
                    <span class="badge badge-danger">Inactivo</span>
                @endif
            </p>
            <p><strong>Notas:</strong> {{ $client->notes }}</p>
            <p class="text-muted mb-0">
                <small>Creado: {{ $client->created_at }} | Actualizado: {{ $client->updated_at }}</small>
            </p>
        </div>
    </div>

</div>
@endsection
