@extends('layouts.app')

@section('title', 'Catálogo de Juegos')

@section('content')
<div class="container catalog-page">
    <div class="catalog-header">
        <span class="section-badge">🎮 GameStore</span>
        <h1 class="titulo">Catálogo de Juegos</h1>
        <p>Explora el catálogo, filtra por plataforma y encuentra tu próximo juego.</p>
    </div>

    @if($errors->any())
        <div class="validation-summary">
            <strong>Revisa los datos:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="quick-filters">
        <a href="{{ route('juegos.index', ['consola' => 'PC']) }}" class="categoria-card">🖥️ PC</a>
        <a href="{{ route('juegos.index', ['consola' => 'XBOX']) }}" class="categoria-card">🎮 XBOX</a>
        <a href="{{ route('juegos.index', ['consola' => 'Play']) }}" class="categoria-card">🎮 PLAY</a>
        <a href="{{ route('juegos.index', ['categoria' => 'FPS']) }}" class="categoria-card">🔫 FPS</a>
        <a href="{{ route('juegos.index', ['categoria' => 'Survival']) }}" class="categoria-card">🏕️ SURVIVAL</a>
        <a href="{{ route('juegos.index', ['categoria' => 'Acción']) }}" class="categoria-card">⚔️ ACCIÓN</a>
    </div>

    <form method="GET" action="{{ route('juegos.index') }}" class="buscador catalog-search">
        <input type="text" name="buscar" placeholder="Buscar juego..." value="{{ request('buscar') }}" maxlength="100">
        <select name="categoria">
            <option value="">Todas las categorías</option>
            <option value="Acción" {{ request('categoria') == 'Acción' ? 'selected' : '' }}>Acción</option>
            <option value="Fantasía" {{ request('categoria') == 'Fantasía' ? 'selected' : '' }}>Fantasía</option>
            <option value="FPS" {{ request('categoria') == 'FPS' ? 'selected' : '' }}>FPS</option>
            <option value="Survival" {{ request('categoria') == 'Survival' ? 'selected' : '' }}>Survival</option>
        </select>
        <select name="consola">
            <option value="">Todas las consolas</option>
            <option value="PC" {{ request('consola') == 'PC' ? 'selected' : '' }}>PC</option>
            <option value="Play" {{ request('consola') == 'Play' ? 'selected' : '' }}>PlayStation</option>
            <option value="XBOX" {{ request('consola') == 'XBOX' ? 'selected' : '' }}>XBOX</option>
        </select>
        <button type="submit">🔍 Buscar</button>
        @if(request()->anyFilled(['buscar', 'categoria', 'consola']))
            <a href="{{ route('juegos.index') }}" class="btn-filter-clear">Limpiar filtros</a>
        @endif
    </form>

    @auth
        @if(auth()->user()->rol === 'admin')
            <div class="catalog-admin-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn-secondary-gaming">🛡️ Ver panel de inventario</a>
                <a href="{{ route('juegos.create') }}" class="btn-crear">➕ Registrar nuevo juego</a>
            </div>
        @endif
    @endauth

    @if($juegos->count() > 0)
        <div class="grid-juegos">
            @foreach($juegos as $juego)
                <article class="card-juego {{ $juego->sin_stock ? 'card-out-of-stock' : '' }}">
                    <div class="card-image">
                        <img src="{{ $juego->imagen ?? 'https://picsum.photos/id/1/600/450' }}" alt="{{ $juego->nombre }}">
                        <div class="card-badge">{{ $juego->consola }}</div>

                        @if(!$juego->en_venta)
                            <div class="stock-overlay stock-overlay-off">FUERA DE VENTA</div>
                        @elseif($juego->sin_stock)
                            <div class="stock-overlay">SIN STOCK</div>
                        @elseif($juego->stock_bajo)
                            <div class="stock-corner-warning">⚠️ Últimas {{ $juego->stock }} unidades</div>
                        @endif
                    </div>

                    <div class="card-body">
                        <h3>{{ $juego->nombre }}</h3>
                        <p class="categoria">{{ $juego->categoria }}</p>
                        <p class="descripcion">{{ Str::limit($juego->descripcion, 90) }}</p>

                        <div class="game-meta-row">
                            <span>📅 {{ $juego->anio_lanzamiento }}</span>
                            <span class="{{ $juego->sin_stock ? 'text-danger' : ($juego->stock_bajo ? 'text-warning' : '') }}">
                                📦 {{ $juego->sin_stock ? 'Sin stock' : 'Stock: ' . $juego->stock }}
                            </span>
                        </div>

                        <p class="precio">Bs {{ number_format($juego->precio, 2, ',', '.') }}</p>

                        <div class="card-actions-row">
                            <a href="{{ route('juegos.show', $juego->id) }}" class="btn-info">Ver más</a>

                            @auth
                                @if(auth()->user()->rol === 'admin')
                                    <a href="{{ route('juegos.edit', $juego->id) }}" class="btn-editar" title="Editar">✏️</a>
                                    <form method="POST" action="{{ route('juegos.destroy', $juego->id) }}" class="inline-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-eliminar" onclick="return confirm('¿Seguro que deseas eliminar {{ addslashes($juego->nombre) }}?')" title="Eliminar">🗑️</button>
                                    </form>
                                @elseif($juego->sin_stock)
                                    <span class="btn-disabled-stock">⛔ Sin stock</span>
                                @elseif($juego->en_venta)
                                    <a href="{{ route('comprar.confirmar', $juego->id) }}" class="btn-comprar">Comprar</a>
                                @endif
                            @else
                                @if($juego->sin_stock)
                                    <span class="btn-disabled-stock">⛔ Sin stock</span>
                                @else
                                    <a href="{{ route('login') }}" class="btn-comprar">Inicia sesión</a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="pagination-info">
            Mostrando {{ $juegos->firstItem() }} a {{ $juegos->lastItem() }} de {{ $juegos->total() }} resultados
        </div>
        <div class="pagination">{{ $juegos->links() }}</div>
    @else
        <div class="empty-catalog">
            <p>No se encontraron juegos con esos filtros.</p>
            <a href="{{ route('juegos.index') }}" class="btn-catalogo">Ver todos</a>
        </div>
    @endif
</div>
@endsection
