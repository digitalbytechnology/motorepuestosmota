@php $category = $category ?? null; @endphp

<div class="form-group">
  <label>Nombre</label>
  <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
         value="{{ old('nombre',$category->nombre ?? '') }}" maxlength="80" required>
  @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

@if($category)
  <div class="form-group">
    <label>Estado</label>
    <input type="hidden" name="activo" value="0">
    <div class="custom-control custom-switch">
      <input type="checkbox" class="custom-control-input" id="catActivo"
             name="activo" value="1"
             {{ old('activo',(int)($category->activo ?? 1))==1 ? 'checked' : '' }}>
      <label class="custom-control-label" for="catActivo">Activo</label>
    </div>
  </div>
@endif
