@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
    <!-- HERO SECTION MODERNO -->
    <section class="hero-moderno">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="hero-badge">🎮 Desde 2024</div>
            <h1 class="hero-title">
                Bienvenido a <span class="gradient-text">GameStore</span>
            </h1>
            <p class="hero-subtitle">
                Descubre los mejores videojuegos para PC, PlayStation, Xbox y más
            </p>
            <div class="hero-buttons">
                <a href="{{ route('juegos.index') }}" class="btn-hero-primary">
                    🎮 Ver Catálogo
                    <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
                <a href="#destacados" class="btn-hero-secondary">
                    🔥 Explorar
                </a>
            </div>
        </div>
        <div class="hero-scroll">
            <span>Desplázate</span>
            <div class="scroll-dot"></div>
        </div>
    </section>

    <!-- JUEGOS DESTACADOS -->
    <section id="destacados" class="destacados-modernos">
        <div class="container-moderno">
            <div class="section-header">
                <div class="section-badge">🔥 Top Juegos</div>
                <h2 class="section-title">Juegos <span class="gradient-text">Destacados</span></h2>
                <p class="section-subtitle">Los más populares y mejor valorados de esta semana</p>
            </div>
            
            <div class="catalogo-moderno">
                @forelse($juegosDestacados as $juego)
                <div class="card-moderno">
                    <div class="card-image">
                        <img src="{{ $juego->imagen ?? 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=300&h=350&fit=crop' }}" alt="{{ $juego->nombre }}">
                        <div class="card-overlay">
                            <div class="card-actions">
                                <a href="{{ route('juegos.show', $juego->id) }}" class="card-btn">Ver detalles</a>
                            </div>
                        </div>
                        <div class="card-badge">{{ $juego->consola }}</div>
                    </div>
                    <div class="card-content">
                        <h3>{{ $juego->nombre }}</h3>
                        <div class="card-meta">
                            <span class="meta-category">{{ $juego->categoria }}</span>
                            <span class="meta-year">📅 {{ $juego->anio_lanzamiento }}</span>
                        </div>
                        <p class="card-description">{{ Str::limit($juego->descripcion, 70) }}</p>
                        <div class="card-footer">
                            <span class="precio-moderno">Bs {{ number_format($juego->precio, 2, ',', '.') }}</span>
                            <span class="stock {{ $juego->stock > 0 ? 'in-stock' : 'out-stock' }}">
                                {{ $juego->stock > 0 ? "📦 {$juego->stock} disponibles" : '❌ Agotado' }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center" style="grid-column: 1/-1; padding: 60px;">
                    <p style="color: #d0d5dd;">No hay juegos disponibles aún. ¡Agrega algunos!</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- CATEGORÍAS POPULARES -->
    <section class="categorias-modernas">
        <div class="container-moderno">
            <div class="section-header">
                <div class="section-badge">📂 Explora por</div>
                <h2 class="section-title">Categorías <span class="gradient-text">Populares</span></h2>
                <p class="section-subtitle">Encuentra tu género favorito</p>
            </div>
            <div class="grid-categorias">
                <a href="{{ route('juegos.index', ['categoria' => 'Acción']) }}" class="categoria-moderna" style="background: linear-gradient(135deg, #dc2626 0%, #7f1d1d 100%);">
                    <div class="categoria-icon">⚔️</div>
                    <h3>Acción</h3>
                    <p>Juegos llenos de adrenalina</p>
                </a>
                <a href="{{ route('juegos.index', ['categoria' => 'Fantasía']) }}" class="categoria-moderna" style="background: linear-gradient(135deg, #ef4444 0%, #991b1b 100%);">
                    <div class="categoria-icon">🧙</div>
                    <h3>Fantasía</h3>
                    <p>Mundos mágicos y épicos</p>
                </a>
                <a href="{{ route('juegos.index', ['categoria' => 'FPS']) }}" class="categoria-moderna" style="background: linear-gradient(135deg, #b91c1c 0%, #450a0a 100%);">
                    <div class="categoria-icon">🔫</div>
                    <h3>FPS</h3>
                    <p>Acción en primera persona</p>
                </a>
                <a href="{{ route('juegos.index', ['categoria' => 'Survival']) }}" class="categoria-moderna" style="background: linear-gradient(135deg, #dc2626 0%, #5a2a31 100%);">
                    <div class="categoria-icon">🏕️</div>
                    <h3>Survival</h3>
                    <p>Supervivencia extrema</p>
                </a>
            </div>
        </div>
    </section>

    <!-- ESTADÍSTICAS DINÁMICAS -->
    <section class="estadisticas-modernas">
        <div class="container-moderno">
            <div class="stats-grid">
                <div class="stat-moderna">
                    <div class="stat-icon">🎮</div>
                    <div class="stat-number" data-count="{{ $totalJuegos ?? 0 }}">{{ $totalJuegos ?? 0 }}</div>
                    <div class="stat-label">Juegos Disponibles</div>
                </div>
                <div class="stat-moderna">
                    <div class="stat-icon">👥</div>
                    <div class="stat-number" data-count="{{ $totalClientes ?? 0 }}">{{ $totalClientes ?? 0 }}</div>
                    <div class="stat-label">Clientes Felices</div>
                </div>
                <div class="stat-moderna">
                    <div class="stat-icon">💰</div>
                    <div class="stat-number" data-count="{{ $totalVentas ?? 0 }}">{{ $totalVentas ?? 0 }}</div>
                    <div class="stat-label">Ventas Realizadas</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION -->
    <section class="cta-section">
        <div class="container-moderno">
            <div class="cta-content">
                <h2>¿Listo para la aventura?</h2>
                <p>Únete a nuestra comunidad y empieza a jugar hoy mismo</p>
                <a href="{{ route('register') }}" class="btn-cta">
                    Comenzar ahora 🚀
                </a>
            </div>
        </div>
    </section>
@endsection