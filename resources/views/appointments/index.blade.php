@extends('layouts.admin')
@section('title', 'Citas')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h3 class="card-title mb-0">Calendario de Citas</h3>

          {{-- Este botón abrirá el modal de límite por día --}}
          <button type="button" class="btn btn-sm btn-primary" id="btnOpenLimitModal">
            Configurar límite por día
          </button>
        </div>

        <div class="card-body">
          <div id="calendarAlert" class="alert alert-danger d-none" role="alert"></div>
          <div id="calendar"></div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- MODAL CITA --}}
<div class="modal fade" id="modalCita" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Agendar cita</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="formCita">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label>Fecha</label>
            <input type="date" class="form-control" name="date" id="cita_date" required>
            <small class="text-muted" id="cita_date_display"></small>
          </div>

          <div class="form-group">
            <label>Hora</label>
            <input type="time" class="form-control" name="time" id="cita_time" required>
          </div>

          <div class="form-group">
            <label>Nombre</label>
            <input type="text" class="form-control" name="name" id="cita_name" maxlength="150" required>
          </div>

          <div class="form-group">
            <label>Teléfono</label>
            <input type="text" class="form-control" name="phone" id="cita_phone" maxlength="30" required>
          </div>

          <div class="form-group">
            <label>Observaciones</label>
            <textarea class="form-control" name="observations" id="cita_obs" rows="3"></textarea>
          </div>

          <div class="alert alert-danger d-none" id="cita_error"></div>
          <div class="alert alert-success d-none" id="cita_success"></div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar cita</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- MODAL LÍMITE POR DÍA --}}
<div class="modal fade" id="modalLimiteDia" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Límite de citas del día</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="formLimiteDia">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label>Fecha</label>
            <input type="date" class="form-control" id="limit_date" name="date" required>
            <small class="text-muted" id="limit_hint"></small>
          </div>

          <div class="form-group">
            <label>Máximo de citas (0 = bloquear)</label>
            <input type="number" class="form-control" id="limit_value" name="max_per_day" min="0" max="500" required>
          </div>

          <div class="alert alert-danger d-none" id="limit_error"></div>
          <div class="alert alert-success d-none" id="limit_success"></div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" id="btnRemoveOverride">
            Quitar límite del día
          </button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- MODAL DETALLE CITA --}}
<div class="modal fade" id="modalCitaDetalle" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detalle de la cita</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="detail_id">
        <input type="hidden" id="detail_date_iso">
        <p class="mb-1"><strong>Nombre:</strong> <span id="detail_name"></span></p>
        <p class="mb-1"><strong>Teléfono:</strong> <span id="detail_phone"></span></p>
        <p class="mb-1"><strong>Fecha:</strong> <span id="detail_date"></span></p>
        <p class="mb-1"><strong>Hora:</strong> <span id="detail_time"></span></p>
        <p class="mb-1"><strong>Observaciones:</strong></p>
        <div class="border rounded p-2" id="detail_obs"></div>

        <div class="alert alert-danger d-none mt-2" id="detail_error"></div>
        <div class="alert alert-success d-none mt-2" id="detail_success"></div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-outline-success" id="btnToggleAttended">
          Ya vino
        </button>

        <button type="button" class="btn btn-warning" id="btnEditCita">
          Editar
        </button>

        <button type="button" class="btn btn-danger" id="btnDeleteCita">
          Eliminar
        </button>

        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          Cerrar
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

{{-- Vite separado --}}
@push('css')
  @vite('resources/css/appointments.css')
@endpush

@push('js')
  @vite('resources/js/appointments.js')
@endpush
