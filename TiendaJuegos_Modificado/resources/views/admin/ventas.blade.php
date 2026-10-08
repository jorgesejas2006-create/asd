@extends('layouts.app')

@section('title', 'Ventas')

@section('content')
<div class="container" style="padding: 40px 20px;">
    <h1 class="titulo">📊 Listado de Ventas</h1>

    <form method="GET" action="{{ route('admin.ventas') }}" class="buscador" style="margin-bottom: 30px; display: flex; justify-content: center; gap: 15px;">
        <input type="text" name="buscar" placeholder="Buscar por cliente..." value="{{ request('buscar') }}" style="background: #323944; padding: 12px 20px; border-radius: 30px; border: 1px solid #5b6574; color: white; width: 300px;">
        <button type="submit" style="background: #dc2626; padding: 12px 25px; border-radius: 30px; border: none; color: white; cursor: pointer;">🔍 Buscar</button>
        @if(request('buscar'))
            <a href="{{ route('admin.ventas') }}" style="background: #7f1d1d; padding: 12px 25px; border-radius: 30px; color: white; text-decoration: none;">Limpiar</a>
        @endif
    </form>

    @if($ventas->count() > 0)
    <div style="overflow-x: auto;">
        <table class="tabla" style="width: 100%; border-collapse: collapse; min-width: 950px;">
            <thead>
                <tr style="background: #991b1b;">
                    <th style="padding: 15px;">Fecha</th>
                    <th style="padding: 15px;">Cliente</th>
                    <th style="padding: 15px;">Juego</th>
                    <th style="padding: 15px;">Precio</th>
                    <th style="padding: 15px;">Método de pago</th>
                    <th style="padding: 15px;">Referencia</th>
                    <th style="padding: 15px;">Código entregado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventas as $venta)
                    @foreach($venta->detalles as $detalle)
                    <tr style="background: #2d333d; border-bottom: 1px solid #4b5563;">
                        <td style="padding: 12px; text-align: center;">{{ \Carbon\Carbon::parse($venta->fecha_compra)->format('d/m/Y H:i') }}</td>
                        <td style="padding: 12px; text-align: center;">{{ $venta->user->name }}<br><small>{{ $venta->user->email }}</small></td>
                        <td style="padding: 12px; text-align: center;">{{ $detalle->juego->nombre }}</td>
                        <td style="padding: 12px; text-align: center; color: #ef4444; font-weight: bold;">Bs {{ number_format($detalle->precio, 2) }}</td>
                        <td style="padding: 12px; text-align: center;">{{ $venta->pago->metodoPago->nombre ?? 'Sin registro' }}</td>
                        <td style="padding: 12px; text-align: center;">{{ $venta->pago->referencia ?? '-' }}</td>
                        <td style="padding: 12px; text-align: center;"><code style="background: #1f232b; border: 1px solid #4b5563; padding: 5px 10px; border-radius: 5px;">{{ $detalle->codigo_entregado }}</code></td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination" style="display: flex; justify-content: center; margin-top: 40px;">{{ $ventas->links() }}</div>
    @else
    <div style="text-align: center; padding: 60px; background: #2d333d; border: 1px solid #4b5563; border-radius: 15px;">
        <p style="font-size: 24px; color: #d0d5dd;">No hay ventas registradas.</p>
    </div>
    @endif
</div>
@endsection
