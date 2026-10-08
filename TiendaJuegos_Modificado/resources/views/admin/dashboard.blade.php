@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('content')
<div class="container admin-dashboard">
    <div class="admin-header">
        <div>
            <span class="section-badge">🛡️ Administración</span>
            <h1 class="titulo admin-title">Panel de control</h1>
            <p class="admin-subtitle">Ventas, inventario y rendimiento de la tienda en un solo lugar.</p>
        </div>
        <a href="{{ route('juegos.create') }}" class="btn-crear">➕ Registrar juego</a>
    </div>

    <div class="admin-kpis">
        <div class="admin-kpi-card">
            <span>🧾 Ventas realizadas</span>
            <strong>{{ $totalVentas }}</strong>
        </div>
        <div class="admin-kpi-card">
            <span>💰 Ingresos aprobados</span>
            <strong>Bs {{ number_format($ingresos, 2, ',', '.') }}</strong>
        </div>
        <div class="admin-kpi-card">
            <span>👥 Clientes</span>
            <strong>{{ $totalClientes }}</strong>
        </div>
        <div class="admin-kpi-card {{ $sinStock->isNotEmpty() ? 'kpi-danger' : '' }}">
            <span>⛔ Juegos sin stock</span>
            <strong>{{ $sinStock->count() }}</strong>
        </div>
    </div>

    <div class="admin-panels-grid">
        <section class="admin-panel">
            <div class="admin-panel-heading">
                <div>
                    <h2>🔥 Más vendidos</h2>
                    <p>Ranking calculado según las compras registradas.</p>
                </div>
            </div>

            <div class="ranking-list">
                @forelse($masVendidos as $index => $juego)
                    <div class="ranking-item">
                        <span class="ranking-position">#{{ $index + 1 }}</span>
                        <img src="{{ $juego->imagen ?? 'https://picsum.photos/seed/game/80/80' }}" alt="{{ $juego->nombre }}">
                        <div class="ranking-info">
                            <strong>{{ $juego->nombre }}</strong>
                            <small>{{ $juego->categoria }} · {{ $juego->consola }}</small>
                        </div>
                        <span class="ranking-sales">{{ $juego->detalles_count }} {{ $juego->detalles_count === 1 ? 'venta' : 'ventas' }}</span>
                    </div>
                @empty
                    <div class="empty-admin-state">Todavía no hay ventas registradas.</div>
                @endforelse
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-panel-heading">
                <div>
                    <h2>⚠️ Stock que necesita atención</h2>
                    <p>Se considera stock bajo cuando quedan {{ \App\Models\Juego::STOCK_BAJO }} unidades o menos.</p>
                </div>
            </div>

            @if($sinStock->isNotEmpty())
                <div class="inventory-alert inventory-alert-danger">
                    <strong>⛔ Sin stock:</strong>
                    {{ $sinStock->pluck('nombre')->join(', ') }}.
                </div>
            @endif

            @if($stockBajo->isNotEmpty())
                <div class="inventory-alert inventory-alert-warning">
                    <strong>📦 Stock bajo:</strong>
                    {{ $stockBajo->map(fn($juego) => $juego->nombre . ' (' . $juego->stock . ')')->join(', ') }}.
                </div>
            @endif

            @if($sinStock->isEmpty() && $stockBajo->isEmpty())
                <div class="inventory-alert inventory-alert-ok">
                    ✅ Todo el inventario está en un nivel saludable.
                </div>
            @endif
        </section>
    </div>

    <section class="admin-panel inventory-panel">
        <div class="admin-panel-heading">
            <div>
                <h2>📦 Inventario completo</h2>
                <p>Estado actual del stock de cada videojuego.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="tabla admin-inventory-table">
                <thead>
                    <tr>
                        <th>Juego</th>
                        <th>Consola</th>
                        <th>Stock</th>
                        <th>Ventas</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inventario as $juego)
                        <tr>
                            <td class="game-cell">
                                <img src="{{ $juego->imagen ?? 'https://picsum.photos/seed/game' . $juego->id . '/60/60' }}" alt="{{ $juego->nombre }}">
                                <span>{{ $juego->nombre }}</span>
                            </td>
                            <td>{{ $juego->consola }}</td>
                            <td><strong>{{ $juego->stock }}</strong></td>
                            <td>{{ $juego->detalles_count }}</td>
                            <td>
                                @if($juego->sin_stock)
                                    <span class="stock-pill stock-pill-out">SIN STOCK</span>
                                @elseif($juego->stock_bajo)
                                    <span class="stock-pill stock-pill-low">STOCK BAJO</span>
                                @else
                                    <span class="stock-pill stock-pill-ok">DISPONIBLE</span>
                                @endif
                            </td>
                            <td><a href="{{ route('juegos.edit', $juego->id) }}" class="table-action">Editar</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
