@extends('layouts.admin')
@section('title', 'Inspección de Recepción')

@section('content')
<div class="container-fluid">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <div><b>Orden:</b> #{{ $order->id }}</div>
      <div class="text-muted"><b>Vehículo:</b> {{ $order->vehicle->placa ?? '-' }} | <b>Cliente:</b> {{ $order->client->name ?? '-' }}</div>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- Tabs --}}
  <div class="card">
    <div class="card-header p-2">
      <ul class="nav nav-pills">
        <li class="nav-item"><a class="nav-link active" href="#tab1" data-toggle="tab">Checklist</a></li>
        <li class="nav-item"><a class="nav-link" href="#tab2" data-toggle="tab">Niveles / Estado</a></li>
        <li class="nav-item"><a class="nav-link" href="#tab3" data-toggle="tab">Fotos y Daños</a></li>
        <li class="nav-item"><a class="nav-link" href="#tab4" data-toggle="tab">Firma</a></li>
      </ul>
    </div>

    <div class="card-body">
      <div class="tab-content">

        {{-- TAB 1: Checklist --}}
        <div class="tab-pane active" id="tab1">
          <form method="POST" action="{{ route('orders.inspection.update', $order) }}">
            @csrf @method('PUT')

            <div class="row">
              @php
                $items = [
                  ['luz_silvin_bajas','Luz silvín (bajas)'],
                  ['luz_silvin_altas','Luz silvín (altas)'],
                  ['luz_stop','Luz de stop'],
                  ['pidevias','Pidevías / direccionales'],
                  ['bocina','Bocina'],
                  ['neblineras','Neblineras'],
                  ['alarma','Alarma'],
                ];
              @endphp

              @foreach($items as [$key,$label])
                <div class="col-md-4">
                  <div class="form-group">
                    <label>{{ $label }}</label>
                    <select name="{{ $key }}" class="form-control">
                      <option value="">N/A</option>
                      <option value="1" {{ old($key, data_get($inspection,$key)) === true ? 'selected' : '' }}>OK</option>
                      <option value="0" {{ old($key, data_get($inspection,$key)) === false ? 'selected' : '' }}>NO</option>
                    </select>
                  </div>
                </div>
              @endforeach
            </div>

            <hr>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Frenos</label>
                  <select name="frenos" class="form-control">
                    <option value="">--</option>
                    <option value="ok" {{ old('frenos',$inspection->frenos) === 'ok' ? 'selected':'' }}>OK</option>
                    <option value="regular" {{ old('frenos',$inspection->frenos) === 'regular' ? 'selected':'' }}>Regular</option>
                    <option value="malo" {{ old('frenos',$inspection->frenos) === 'malo' ? 'selected':'' }}>Malo</option>
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Llantas</label>
                  <select name="llantas" class="form-control">
                    <option value="">--</option>
                    <option value="ok" {{ old('llantas',$inspection->llantas) === 'ok' ? 'selected':'' }}>OK</option>
                    <option value="gastadas" {{ old('llantas',$inspection->llantas) === 'gastadas' ? 'selected':'' }}>Gastadas</option>
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Profundidad llantas (opcional)</label>
                  <input type="text" name="llantas_profundidad" class="form-control"
                         value="{{ old('llantas_profundidad',$inspection->llantas_profundidad) }}" placeholder="Ej: 3mm">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Espejos</label>
                  <select name="espejos" class="form-control">
                    <option value="">N/A</option>
                    <option value="1" {{ old('espejos',$inspection->espejos) === true ? 'selected':'' }}>OK</option>
                    <option value="0" {{ old('espejos',$inspection->espejos) === false ? 'selected':'' }}>NO</option>
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Documento: tarjeta</label>
                  <select name="doc_tarjeta" class="form-control">
                    <option value="">N/A</option>
                    <option value="1" {{ old('doc_tarjeta',$inspection->doc_tarjeta) === true ? 'selected':'' }}>Sí</option>
                    <option value="0" {{ old('doc_tarjeta',$inspection->doc_tarjeta) === false ? 'selected':'' }}>No</option>
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Documento: copia llave</label>
                  <select name="doc_copia_llave" class="form-control">
                    <option value="">N/A</option>
                    <option value="1" {{ old('doc_copia_llave',$inspection->doc_copia_llave) === true ? 'selected':'' }}>Sí</option>
                    <option value="0" {{ old('doc_copia_llave',$inspection->doc_copia_llave) === false ? 'selected':'' }}>No</option>
                  </select>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label>Accesorios recibidos</label>
                  <textarea name="accesorios_recibidos" rows="2" class="form-control">{{ old('accesorios_recibidos',$inspection->accesorios_recibidos) }}</textarea>
                </div>
              </div>
            </div>

            <button class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
          </form>
        </div>

        {{-- TAB 2: Niveles --}}
        <div class="tab-pane" id="tab2">
          <form method="POST" action="{{ route('orders.inspection.update', $order) }}">
            @csrf @method('PUT')

            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Aceite</label>
                  <select name="aceite_pct" class="form-control">
                    <option value="">--</option>
                    @foreach([0,25,50,75,100] as $p)
                      <option value="{{ $p }}" {{ (string)old('aceite_pct',$inspection->aceite_pct) === (string)$p ? 'selected':'' }}>
                        {{ $p }}%
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Gasolina</label>
                  <select name="gasolina_level" class="form-control">
                    <option value="">--</option>
                    <option value="E"  {{ old('gasolina_level',$inspection->gasolina_level) === 'E'  ? 'selected':'' }}>E</option>
                    <option value="Q1" {{ old('gasolina_level',$inspection->gasolina_level) === 'Q1' ? 'selected':'' }}>1/4</option>
                    <option value="H"  {{ old('gasolina_level',$inspection->gasolina_level) === 'H'  ? 'selected':'' }}>1/2</option>
                    <option value="Q3" {{ old('gasolina_level',$inspection->gasolina_level) === 'Q3' ? 'selected':'' }}>3/4</option>
                    <option value="F"  {{ old('gasolina_level',$inspection->gasolina_level) === 'F'  ? 'selected':'' }}>Full</option>
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Arranca</label>
                  <select name="arranca" class="form-control">
                    <option value="">--</option>
                    <option value="1" {{ old('arranca',$inspection->arranca) === true ? 'selected':'' }}>Sí</option>
                    <option value="0" {{ old('arranca',$inspection->arranca) === false ? 'selected':'' }}>No</option>
                  </select>
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Check engine / testigos</label>
                  <select name="check_engine" class="form-control">
                    <option value="">--</option>
                    <option value="1" {{ old('check_engine',$inspection->check_engine) === true ? 'selected':'' }}>Sí</option>
                    <option value="0" {{ old('check_engine',$inspection->check_engine) === false ? 'selected':'' }}>No</option>
                  </select>
                </div>
              </div>

              <div class="col-md-8">
                <div class="form-group">
                  <label>¿Cuál testigo?</label>
                  <input type="text" name="check_engine_detalle" class="form-control"
                         value="{{ old('check_engine_detalle',$inspection->check_engine_detalle) }}" maxlength="120">
                </div>
              </div>

              <div class="col-md-4">
                <div class="form-group">
                  <label>Odómetro (km)</label>
                  <input type="number" name="odometro" class="form-control"
                         value="{{ old('odometro',$inspection->odometro) }}" min="0">
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  <label>Observaciones / problemas del vehículo</label>
                  <textarea name="observaciones" rows="4" class="form-control">{{ old('observaciones',$inspection->observaciones) }}</textarea>
                </div>
              </div>
            </div>

            <button class="btn btn-primary"><i class="fas fa-save"></i> Guardar</button>
          </form>
        </div>

        {{-- TAB 3: Fotos --}}
        <div class="tab-pane" id="tab3">
          <form method="POST" action="{{ route('orders.inspection.photos.store', $order) }}" enctype="multipart/form-data" class="mb-3">
            @csrf
            <div class="d-flex align-items-center gap-2">
              <input type="file" name="photos[]" accept="image/*" capture="environment" multiple class="form-control">
              <button class="btn btn-primary"><i class="fas fa-camera"></i> Subir</button>
            </div>
            <small class="text-muted">Puedes tomar varias fotos desde el celular.</small>
          </form>

          <div class="row">
            @foreach($inspection->photos as $photo)
              <div class="col-md-3 mb-3">
                <div class="card">
                  <img src="{{ Storage::url($photo->path) }}" class="card-img-top" style="object-fit:cover;height:160px;">
                  <div class="card-body p-2">
                    <button
                      class="btn btn-sm btn-info btn-open-annotator w-100"
                      data-photo-id="{{ $photo->id }}"
                      data-photo-url="{{ Storage::url($photo->path) }}"
                      data-annotations='@json($photo->annotations ?? [])'
                    >
                      Marcar daños
                    </button>

                    <form action="{{ route('orders.inspection.photos.destroy', [$order,$photo]) }}" method="POST" class="mt-2"
                          onsubmit="return confirm('¿Eliminar esta foto?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-danger w-100">Eliminar</button>
                    </form>
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          {{-- Modal annotator --}}
          <div class="modal fade" id="annotatorModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Marcar daños</h5>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                  <div class="d-flex flex-wrap gap-2 mb-2">
                    <select id="damageType" class="form-control" style="width:220px;">
                      <option value="golpe">Golpe</option>
                      <option value="rayon">Rayón</option>
                      <option value="abolladura">Abolladura</option>
                      <option value="quebrado">Quebrado</option>
                      <option value="oxido">Óxido</option>
                      <option value="otro">Otro</option>
                    </select>

                    <select id="toolType" class="form-control" style="width:220px;">
                      <option value="rect">Rectángulo</option>
                      <option value="circle">Círculo</option>
                      <option value="arrow">Flecha</option>
                      <option value="text">Texto</option>
                    </select>

                    <input id="damageNote" class="form-control" style="max-width:320px;" placeholder="Comentario corto (opcional)">
                    <button id="btnSaveAnnotations" class="btn btn-primary">Guardar marcas</button>
                    <button id="btnClearAnnotations" class="btn btn-outline-danger">Limpiar</button>
                  </div>

                  <div id="konvaContainer" style="width:100%;border:1px solid #ddd;min-height:520px;"></div>
                </div>
              </div>
            </div>
          </div>

        </div>

        {{-- TAB 4: Firma --}}
        <div class="tab-pane" id="tab4">
          <div class="row">
            <div class="col-md-6">
              <p class="text-muted">Firma del cliente (dibujar con dedo/mouse):</p>
              <div class="border p-2">
                <canvas id="signaturePad" width="500" height="220" style="width:100%;height:220px;"></canvas>
              </div>
              <div class="mt-2 d-flex gap-2">
                <button class="btn btn-outline-danger" id="sigClear">Limpiar</button>
                <button class="btn btn-primary" id="sigSave">Guardar firma</button>
              </div>
            </div>
            <div class="col-md-6">
              <p class="text-muted">Firma guardada:</p>
              @if($inspection->firma_path)
                <img src="{{ Storage::url($inspection->firma_path) }}" class="img-fluid border">
              @else
                <div class="alert alert-warning">Aún no hay firma guardada.</div>
              @endif
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
  window.__INSPECTION = {
    orderId: {{ $order->id }},
    saveAnnotationsUrlTemplate: @json(route('orders.inspection.photos.annotations', [$order, 999999])),
    saveSignatureUrl: @json(route('orders.inspection.signature', $order)),
    csrf: @json(csrf_token()),
  };
</script>
@endpush
