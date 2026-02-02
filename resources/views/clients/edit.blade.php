@extends('layouts.admin')
@section('title', 'Editar Cliente')

@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Editar cliente</h3>
        </div>

        <form method="POST" action="{{ route('clientes.update', $client) }}">
            @csrf
            @method('PUT')

            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-3">
                        <label>Tipo documento</label>
                        <select name="document_type" class="form-control">
                            <option value="NIT" {{ old('document_type', $client->document_type) == 'NIT' ? 'selected' : '' }}>NIT</option>
                            <option value="DPI" {{ old('document_type', $client->document_type) == 'DPI' ? 'selected' : '' }}>DPI</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>NIT / DPI</label>
                        <input type="text" name="document_number" class="form-control"
                               value="{{ old('document_number', $client->document_number) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label>Nombre / Razón social</label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name', $client->name) }}" required>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label>Dirección</label>
                        <input type="text" name="address" class="form-control"
                               value="{{ old('address', $client->address) }}">
                    </div>

                    <div class="col-md-3">
                        <label>Teléfono</label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ old('phone', $client->phone) }}">
                    </div>

                    <div class="col-md-3">
                        <label>Correo</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $client->email) }}">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label>Observaciones</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $client->notes) }}</textarea>
                    </div>

                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check mt-4">
                            <input type="checkbox" name="is_active" class="form-check-input"
                                   id="is_active" value="1"
                                   {{ old('is_active', $client->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Cliente activo</label>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Volver</a>
                <button type="submit" class="btn btn-success">Guardar cambios</button>
            </div>
        </form>
    </div>

</div>
@endsection
