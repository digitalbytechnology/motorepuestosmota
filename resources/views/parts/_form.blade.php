@php $part = $part ?? null; @endphp

<div class="row">
  <div class="col-md-4">
    <div class="form-group">
      <label>SKU</label>
      <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror"
             value="{{ old('sku',$part->sku ?? '') }}" maxlength="50">
      @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
  </div>

  <div class="col-md-8">
    <div class="form-group">
      <label>Nombre repuesto <span class="text-danger">*</span></label>
      <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
             value="{{ old('nombre',$part->nombre ?? '') }}" maxlength="150" required>
      @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
  </div>

  <div class="col-md-12">
    <div class="form-group">
      <label>Descripción</label>
      <textarea name="descripcion" rows="3"
                class="form-control @error('descripcion') is-invalid @enderror">{{ old('descripcion',$part->descripcion ?? '') }}</textarea>
      @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
  </div>

  <div class="col-md-6">
    <div class="form-group">
      <label>Categoría</label>
      <select name="category_id" class="form-control @error('category_id') is-invalid @enderror">
        <option value="">-- Seleccionar --</option>
        @foreach($categories as $c)
          <option value="{{ $c->id }}"
            {{ (string)old('category_id',$part->category_id ?? '') === (string)$c->id ? 'selected' : '' }}>
            {{ $c->nombre }} {{ $c->activo ? '' : '(Inactiva)' }}
          </option>
        @endforeach
      </select>
      @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
  </div>

  <div class="col-md-6">
    <div class="form-group">
      <label>Marca (del repuesto)</label>
      <input type="text" name="marca" class="form-control @error('marca') is-invalid @enderror"
             value="{{ old('marca',$part->marca ?? '') }}" maxlength="80">
      @error('marca')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
  </div>

  <div class="col-md-12">
    <div class="form-group">
      <label>Aplica a (texto libre)</label>
      <input type="text" name="aplica_a" class="form-control @error('aplica_a') is-invalid @enderror"
             value="{{ old('aplica_a',$part->aplica_a ?? '') }}"
             placeholder="Ej: XR150, GN125, CB190R, AX100">
      <small class="text-muted">
        Escribe modelos separados por coma. Luego podrás buscar: <b>bujía xr150</b>.
      </small>
      @error('aplica_a')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group">
      <label>Costo (Q) <span class="text-danger">*</span></label>
      <input type="number" step="0.01" min="0" name="costo"
             class="form-control @error('costo') is-invalid @enderror"
             value="{{ old('costo',$part->costo ?? 0) }}" required>
      @error('costo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group">
      <label>Precio (Q) <span class="text-danger">*</span></label>
      <input type="number" step="0.01" min="0" name="precio"
             class="form-control @error('precio') is-invalid @enderror"
             value="{{ old('precio',$part->precio ?? 0) }}" required>
      @error('precio')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
  </div>

  <div class="col-md-3">
    <div class="form-group">
      <label>Stock mínimo <span class="text-danger">*</span></label>
      <input type="number" min="0" name="stock_min"
             class="form-control @error('stock_min') is-invalid @enderror"
             value="{{ old('stock_min',$part->stock_min ?? 1) }}" required>
      @error('stock_min')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
  </div>

  @if($part)
    <div class="col-md-3">
      <div class="form-group">
        <label>Estado</label>
        <input type="hidden" name="activo" value="0">
        <div class="custom-control custom-switch">
          <input type="checkbox" class="custom-control-input" id="partActivo"
                 name="activo" value="1"
                 {{ old('activo',(int)($part->activo ?? 1))==1 ? 'checked' : '' }}>
          <label class="custom-control-label" for="partActivo">Activo</label>
        </div>
      </div>
    </div>
  @endif
</div>
