<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GameStore - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <a href="{{ route('inicio') }}">🎮 GameStore</a>
        </div>

        <div class="menu">
            <a href="{{ route('inicio') }}">Inicio</a>
            <a href="{{ route('juegos.index') }}">Catálogo</a>

            @auth
                @if(auth()->user()->rol === 'admin')
                    <a href="{{ route('admin.dashboard') }}">🛡️ Panel Admin</a>
                    <a href="{{ route('juegos.create') }}">➕ Nuevo Juego</a>
                    <a href="{{ route('admin.ventas') }}">📊 Ventas</a>
                    <a href="{{ route('admin.clientes') }}">👥 Clientes</a>
                @else
                    <a href="{{ route('mis-compras') }}">🎮 Mis Compras</a>
                    <a href="{{ route('profile.edit') }}">👤 Mi Perfil</a>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="nav-logout-form">
                    @csrf
                    <button type="submit" class="nav-logout">🚪 Salir</button>
                </form>
            @else
                <a href="{{ route('login') }}">🔐 Iniciar sesión</a>
                <a href="{{ route('register') }}">📝 Registro</a>
            @endauth
        </div>
    </nav>

    @auth
        @if(auth()->user()->rol === 'admin' && isset($alertasStockAdmin) && $alertasStockAdmin->isNotEmpty())
            <div class="admin-stock-banner">
                <div>
                    <strong>⚠️ Alerta de inventario:</strong>
                    {{ $alertasStockAdmin->count() }} {{ $alertasStockAdmin->count() === 1 ? 'juego necesita' : 'juegos necesitan' }} atención.
                </div>
                <div class="admin-stock-banner-items">
                    @foreach($alertasStockAdmin->take(5) as $alerta)
                        <a href="{{ route('juegos.edit', $alerta->id) }}">
                            {{ $alerta->nombre }} — {{ $alerta->stock <= 0 ? 'SIN STOCK' : $alerta->stock . ' unidades' }}
                        </a>
                    @endforeach
                    @if($alertasStockAdmin->count() > 5)
                        <a href="{{ route('admin.dashboard') }}">Ver todas las alertas</a>
                    @endif
                </div>
            </div>
        @endif
    @endauth

    @if(session('success'))
        <div class="flash-message flash-success">✅ {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="flash-message flash-error">⚠️ {{ session('error') }}</div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="footer-grid">
            <div>
                <h4>📞 Contacto</h4>
                <p>📧 tienda@gamestore.com</p>
                <p>📱 +123 456 7890</p>
                <p>📍 Av. Principal 123, Ciudad</p>
            </div>
            <div>
                <h4>🔗 Enlaces</h4>
                <p><a href="{{ route('inicio') }}">Inicio</a></p>
                <p><a href="{{ route('juegos.index') }}">Catálogo</a></p>
            </div>
            <div>
                <h4>📱 Redes Sociales</h4>
                <p>🐦 Twitter | 📷 Instagram | 📘 Facebook</p>
            </div>
        </div>
        <p>© 2026 GameStore - Todos los derechos reservados</p>
    </footer>

    @stack('scripts')
</body>
</html>
