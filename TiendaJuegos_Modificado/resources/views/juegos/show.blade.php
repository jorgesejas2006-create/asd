@extends('layouts.app')

@section('title', $juego->nombre)

@section('content')
<section class="game-detail-page" style="--game-cover: url('{{ $juego->imagen ?? 'https://picsum.photos/id/1/1920/1080' }}');">
    <div class="game-detail-backdrop"></div>

    <div class="container game-detail-container">
        <div class="game-detail-grid">
            <div class="game-cover-column">
                <div class="game-cover-card">
                    <img src="{{ $juego->imagen ?? 'https://picsum.photos/id/1/700/800' }}" alt="{{ $juego->nombre }}">
                    @if($juego->sin_stock)
                        <div class="stock-overlay">SIN STOCK</div>
                    @elseif($juego->stock_bajo)
                        <div class="stock-corner-warning">⚠️ Últimas {{ $juego->stock }} unidades</div>
                    @endif
                </div>

                <div class="detail-tags">
                    <span>🎮 {{ $juego->consola }}</span>
                    <span>⭐ EDICIÓN ESTÁNDAR</span>
                    <span>📅 {{ $juego->anio_lanzamiento }}</span>
                </div>
            </div>

            <div class="game-detail-info">
                <span class="section-badge">{{ $juego->categoria }} · {{ $juego->consola }}</span>
                <h1>{{ $juego->nombre }}</h1>
                <p class="detail-description">{{ $juego->descripcion }}</p>

                <div class="detail-price-row">
                    <div>
                        <small>Precio</small>
                        <strong>Bs {{ number_format($juego->precio, 2, ',', '.') }}</strong>
                    </div>
                    <div class="detail-stock-state">
                        @if($juego->sin_stock)
                            <span class="stock-pill stock-pill-out">⛔ SIN STOCK</span>
                            <small>Temporalmente agotado</small>
                        @elseif($juego->stock_bajo)
                            <span class="stock-pill stock-pill-low">⚠️ STOCK BAJO</span>
                            <small>Solo quedan {{ $juego->stock }} unidades</small>
                        @else
                            <span class="stock-pill stock-pill-ok">✅ DISPONIBLE</span>
                            <small>{{ $juego->stock }} unidades disponibles</small>
                        @endif
                    </div>
                </div>

                @if($errors->has('stock'))
                    <div class="validation-summary compact-validation">{{ $errors->first('stock') }}</div>
                @endif

                <div class="detail-actions">
                    @if($juego->sin_stock)
                        <span class="btn-disabled-stock detail-disabled">⛔ Producto sin stock</span>
                    @else
                        @auth
                            @if(auth()->user()->rol === 'cliente' && $juego->en_venta)
                                <a href="{{ route('comprar.confirmar', $juego->id) }}" class="btn-hero-primary">💳 Comprar ahora</a>
                            @elseif(auth()->user()->rol === 'admin')
                                <a href="{{ route('juegos.edit', $juego->id) }}" class="btn-hero-primary">✏️ Editar juego</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-hero-primary">🔐 Iniciar sesión para comprar</a>
                        @endauth
                    @endif

                    <button type="button" onclick="window.history.back()" class="btn-secondary-gaming">← Volver</button>
                </div>

                <div class="detail-feature-grid">
                    <div class="detail-feature-card">
                        <span>🎮</span>
                        <strong>Plataforma</strong>
                        <small>{{ $juego->consola }}</small>
                    </div>
                    <div class="detail-feature-card">
                        <span>🗂️</span>
                        <strong>Categoría</strong>
                        <small>{{ $juego->categoria }}</small>
                    </div>
                    <div class="detail-feature-card">
                        <span>📦</span>
                        <strong>Inventario</strong>
                        <small>{{ $juego->stock }} unidades</small>
                    </div>
                </div>

                <div class="detail-panel">
                    <h3>✨ Características</h3>
                    <div class="detail-checks">
                        <span>✓ Entrega digital</span>
                        <span>✓ Código de acceso incluido</span>
                        <span>✓ Versión para {{ $juego->consola }}</span>
                        <span>✓ Compra registrada en tu cuenta</span>
                    </div>
                </div>

                @auth
                    @if(auth()->user()->rol === 'admin')
                        <div class="admin-code-panel">
                            <small>🔑 CÓDIGO DE ACCESO — SOLO ADMIN</small>
                            <code>{{ $juego->codigo_acceso }}</code>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</section>
@endsection
