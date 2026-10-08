@extends('layouts.app')

@section('title', 'Editar Juego')

@section('content')
<div class="container game-form-page">
    <div class="form-page-header">
        <span class="section-badge">✏️ Administración</span>
        <h1 class="titulo">Editar {{ $juego->nombre }}</h1>
        <p>Actualiza la información y el stock del videojuego.</p>
    </div>

    @if($errors->any())
        <div class="validation-summary">
            <strong>Hay datos que debes corregir:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-card game-form-card">
        <form method="POST" action="{{ route('juegos.update', $juego->id) }}" novalidate>
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nombre">Nombre del juego *</label>
                <input id="nombre" type="text" name="nombre" value="{{ old('nombre', $juego->nombre) }}" minlength="2" maxlength="100" required autocomplete="off">
                @error('nombre') <small class="field-error">{{ $message }}</small> @enderror
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción *</label>
                <textarea id="descripcion" name="descripcion" rows="5" minlength="10" maxlength="1500" required>{{ old('descripcion', $juego->descripcion) }}</textarea>
                <div class="field-help">Entre 10 y 1500 caracteres.</div>
                @error('descripcion') <small class="field-error">{{ $message }}</small> @enderror
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label for="anio_lanzamiento">Año de lanzamiento *</label>
                    <input id="anio_lanzamiento" type="number" name="anio_lanzamiento" value="{{ old('anio_lanzamiento', $juego->anio_lanzamiento) }}" min="1980" max="{{ date('Y') + 1 }}" required>
                    @error('anio_lanzamiento') <small class="field-error">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="stock">Stock *</label>
                    <input id="stock" type="number" name="stock" value="{{ old('stock', $juego->stock) }}" min="0" max="100000" required>
                    <div class="field-help">Con 0 unidades el usuario verá “SIN STOCK”.</div>
                    @error('stock') <small class="field-error">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label for="categoria">Categoría *</label>
                    <select id="categoria" name="categoria" required>
                        @foreach(['Acción' => '⚔️ Acción', 'Fantasía' => '🧙 Fantasía', 'FPS' => '🔫 FPS', 'Survival' => '🏕️ Survival'] as $valor => $texto)
                            <option value="{{ $valor }}" {{ old('categoria', $juego->categoria) === $valor ? 'selected' : '' }}>{{ $texto }}</option>
                        @endforeach
                    </select>
                    @error('categoria') <small class="field-error">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="consola">Consola *</label>
                    <select id="consola" name="consola" required>
                        @foreach(['PC' => '🖥️ PC', 'Play' => '🎮 PlayStation', 'XBOX' => '🎮 XBOX'] as $valor => $texto)
                            <option value="{{ $valor }}" {{ old('consola', $juego->consola) === $valor ? 'selected' : '' }}>{{ $texto }}</option>
                        @endforeach
                    </select>
                    @error('consola') <small class="field-error">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label for="precio">Precio (Bs) *</label>
                    <input id="precio" type="number" name="precio" value="{{ old('precio', $juego->precio) }}" step="0.01" min="1" max="999999.99" required>
                    @error('precio') <small class="field-error">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label for="codigo_acceso">Código de acceso *</label>
                    <input id="codigo_acceso" type="text" name="codigo_acceso" value="{{ old('codigo_acceso', $juego->codigo_acceso) }}" minlength="4" maxlength="80" pattern="[A-Za-z0-9_-]+" required autocomplete="off">
                    <div class="field-help">Debe ser único. Letras, números, guiones y guiones bajos.</div>
                    @error('codigo_acceso') <small class="field-error">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="imagen">URL de la imagen</label>
                <input id="imagen" type="url" name="imagen" value="{{ old('imagen', $juego->imagen) }}" maxlength="2048" placeholder="https://ejemplo.com/imagen.jpg">
                @error('imagen') <small class="field-error">{{ $message }}</small> @enderror
            </div>

            <input type="hidden" name="en_venta" value="0">
            <label class="checkbox-row">
                <input type="checkbox" name="en_venta" value="1" {{ old('en_venta', $juego->en_venta) ? 'checked' : '' }}>
                <span>Mostrar el juego a la venta</span>
            </label>

            <div class="form-actions">
                <button type="submit" class="btn-hero-primary">💾 Guardar cambios</button>
                <a href="{{ route('juegos.index') }}" class="btn-secondary-gaming">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
