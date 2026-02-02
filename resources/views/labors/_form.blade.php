@php $labor = $labor ?? null; @endphp

<div class="form-group">
  <label>Servicio / Mano de obra <span class="text-danger">*</span></label>
  <input type="text"
         name="nombre"
         class="form-control @error('nombre') is-invalid @enderror"
         value="{{ old('nombre', $labor->nombre ?? '') }}"
         maxlength="100"
         required>
  @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="form-group">
  <label>Descripción</label>
  <textarea name="descripcion"
            class="form-control @error('descripcion') is-invalid @enderror"
            rows="3">{{ old('descripcion', $labor->descripcion ?? '') }}</textarea>
  @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="form-group">
  <label>Precio (Q) <span class="text-danger">*</span></label>
  <input type="number"
         step="0.01"
         min="0"
         name="precio"
         class="form-control @error('precio') is-invalid @enderror"
         value="{{ old('precio', $labor->precio ?? '') }}"
         required>
  @error('precio') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
@if($labor)
  <div class="form-group">
    <label>Estado</label>

    {{-- Esto asegura que SIEMPRE se mande algo --}}
    <input type="hidden" name="activo" value="0">

    <div class="custom-control custom-switch">
      <input type="checkbox"
             class="custom-control-input"
             id="activoSwitch"
             name="activo"
             value="1"
             {{ old('activo', (int)($labor->activo ?? 1)) == 1 ? 'checked' : '' }}>
      <label class="custom-control-label" for="activoSwitch">Activo</label>
    </div>

  </div>
@endif

