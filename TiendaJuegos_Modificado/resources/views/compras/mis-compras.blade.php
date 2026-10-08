@extends('layouts.app')

@section('title', 'Mis Compras')

@section('content')
<div class="container" style="padding: 40px 20px;">
    <h1 class="titulo">🎮 Mis Juegos Comprados</h1>

    @if($compras->count() > 0)
        @foreach($compras as $compra)
            <div class="form-card" style="margin-bottom: 30px;">
                <div style="background: #323944; border-left: 4px solid #dc2626; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                    <strong>Compra #{{ $compra->id }}</strong> - Fecha: {{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y H:i') }}
                    <span style="float: right; color: #ef4444; font-weight: bold;">Total: Bs {{ number_format($compra->total, 2) }}</span>
                    @if($compra->pago)
                        <div style="margin-top: 8px; color: #d1d5db; font-size: 14px;">
                            💳 {{ $compra->pago->metodoPago->nombre ?? 'Método no disponible' }} · Estado: {{ ucfirst($compra->pago->estado) }} · Ref: {{ $compra->pago->referencia }}
                        </div>
                    @endif
                </div>

                @foreach($compra->detalles as $detalle)
                <div style="display: flex; gap: 20px; flex-wrap: wrap; border-bottom: 1px solid #4b5563; padding: 15px 0;">
                    <img src="{{ $detalle->juego->imagen ?? 'https://via.placeholder.com/100x100?text=Juego' }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 10px;">
                    <div style="flex: 1;">
                        <h3 style="color: #ef4444;">{{ $detalle->juego->nombre }}</h3>
                        <p>🎮 {{ $detalle->juego->consola }} | 📅 {{ $detalle->juego->anio_lanzamiento }}</p>
                        <p>💰 Precio: Bs {{ number_format($detalle->precio, 2) }}</p>
                        <div style="background: #1f232b; border: 1px solid #4b5563; padding: 12px; border-radius: 10px; margin-top: 10px;">
                            <strong style="color: #ef4444;">🎮 CÓDIGO DE ACCESO:</strong>
                            <code style="font-size: 18px; margin-left: 10px;">{{ $detalle->codigo_entregado }}</code>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endforeach

        <div class="pagination" style="display: flex; justify-content: center; margin-top: 30px;">{{ $compras->links() }}</div>
    @else
        <div style="text-align: center; padding: 60px; background: #2d333d; border: 1px solid #4b5563; border-radius: 15px;">
            <p style="font-size: 24px; color: #d0d5dd;">No has realizado ninguna compra todavía 😢</p>
            <a href="{{ route('juegos.index') }}" class="btn-catalogo" style="display: inline-block; margin-top: 20px;">Ver Catálogo</a>
        </div>
    @endif
</div>
@endsection
