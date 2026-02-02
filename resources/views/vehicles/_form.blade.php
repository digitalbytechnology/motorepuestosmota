@php $vehiculo = $vehiculo ?? null; @endphp

<div class="row">
  <div class="col-md-4">
    <div class="form-group">
      <label>Placa</label>
      <input type="text" name="placa" class="form-control @error('placa') is-invalid @enderror"
             value="{{ old('placa', $vehiculo->placa ?? '') }}" maxlength="20">
      @error('placa') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Chasis</label>
      <input type="text" name="chasis" class="form-control @error('chasis') is-invalid @enderror"
             value="{{ old('chasis', $vehiculo->chasis ?? '') }}" maxlength="60">
      @error('chasis') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Uso</label>
      <input type="text" name="uso" class="form-control @error('uso') is-invalid @enderror"
             value="{{ old('uso', $vehiculo->uso ?? '') }}" maxlength="50">
      @error('uso') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Tipo</label>
      <input type="text" name="tipo" class="form-control @error('tipo') is-invalid @enderror"
             value="{{ old('tipo', $vehiculo->tipo ?? '') }}" maxlength="50">
      @error('tipo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Accionado</label>
      <input type="text" name="accionado" class="form-control @error('accionado') is-invalid @enderror"
             value="{{ old('accionado', $vehiculo->accionado ?? '') }}" maxlength="50">
      @error('accionado') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Marca</label>
      <input type="text" name="marca" class="form-control @error('marca') is-invalid @enderror"
             value="{{ old('marca', $vehiculo->marca ?? '') }}" maxlength="60">
      @error('marca') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Modelo</label>
      <input type="text" name="modelo" class="form-control @error('modelo') is-invalid @enderror"
             value="{{ old('modelo', $vehiculo->modelo ?? '') }}" maxlength="60">
      @error('modelo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Línea</label>
      <input type="text" name="linea" class="form-control @error('linea') is-invalid @enderror"
             value="{{ old('linea', $vehiculo->linea ?? '') }}" maxlength="60">
      @error('linea') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>No. de serie</label>
      <input type="text" name="no_serie" class="form-control @error('no_serie') is-invalid @enderror"
             value="{{ old('no_serie', $vehiculo->no_serie ?? '') }}" maxlength="80">
      @error('no_serie') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>No. de motor</label>
      <input type="text" name="no_motor" class="form-control @error('no_motor') is-invalid @enderror"
             value="{{ old('no_motor', $vehiculo->no_motor ?? '') }}" maxlength="80">
      @error('no_motor') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="col-md-4">
    <div class="form-group">
      <label>Color</label>
      <input type="text" name="color" class="form-control @error('color') is-invalid @enderror"
             value="{{ old('color', $vehiculo->color ?? '') }}" maxlength="40">
      @error('color') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group">
      <label>Cilindrada (cc)</label>
      <input type="number" name="cilindrada" class="form-control @error('cilindrada') is-invalid @enderror"
             value="{{ old('cilindrada', $vehiculo->cilindrada ?? '') }}" min="1">
      @error('cilindrada') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group">
      <label>Cilindros</label>
      <input type="number" name="cilindros" class="form-control @error('cilindros') is-invalid @enderror"
             value="{{ old('cilindros', $vehiculo->cilindros ?? '') }}" min="1">
      @error('cilindros') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group">
      <label>Tonelaje</label>
      <input type="number" step="0.01" name="tonelaje" class="form-control @error('tonelaje') is-invalid @enderror"
             value="{{ old('tonelaje', $vehiculo->tonelaje ?? '') }}" min="0">
      @error('tonelaje') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group">
      <label>Asientos</label>
      <input type="number" name="asientos" class="form-control @error('asientos') is-invalid @enderror"
             value="{{ old('asientos', $vehiculo->asientos ?? '') }}" min="0">
      @error('asientos') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group">
      <label>Ejes</label>
      <input type="number" name="ejes" class="form-control @error('ejes') is-invalid @enderror"
             value="{{ old('ejes', $vehiculo->ejes ?? '') }}" min="0">
      @error('ejes') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group">
      <label>Puertas</label>
      <input type="number" name="puertas" class="form-control @error('puertas') is-invalid @enderror"
             value="{{ old('puertas', $vehiculo->puertas ?? '') }}" min="0">
      @error('puertas') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
  </div>
</div>
