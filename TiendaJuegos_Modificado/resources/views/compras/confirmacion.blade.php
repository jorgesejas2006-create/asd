@extends('layouts.app')

@section('title', 'Confirmar Compra')

@section('content')
<div style="min-height: 100vh; background: linear-gradient(135deg, #2a3038 0%, #47252b 100%); position: relative; overflow: hidden;">
    <div style="position: absolute; inset: 0; background-image: radial-gradient(circle at 25% 50%, rgba(220, 38, 38, 0.12) 0%, transparent 50%); pointer-events: none;"></div>

    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 60px 20px; position: relative; z-index: 2;">
        <div style="display: flex; justify-content: center; gap: 80px; margin-bottom: 50px; flex-wrap: wrap;">
            <div style="text-align: center;">
                <div style="width: 50px; height: 50px; background: #dc2626; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; box-shadow: 0 0 20px rgba(220,38,38,0.45);">✓</div>
                <p style="color: #ef4444; font-size: 14px;">1. Juego</p>
            </div>
            <div style="text-align: center;">
                <div style="width: 50px; height: 50px; background: #dc2626; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; box-shadow: 0 0 20px rgba(220,38,38,0.45);">2</div>
                <p style="color: #ef4444; font-size: 14px;">2. Método de pago</p>
            </div>
            <div style="text-align: center;">
                <div style="width: 50px; height: 50px; background: #4b5563; border: 1px solid #dc2626; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">3</div>
                <p style="color: #d0d5dd; font-size: 14px;">3. Confirmación</p>
            </div>
        </div>

        <div style="background: rgba(48, 55, 65, 0.96); backdrop-filter: blur(20px); border-radius: 32px; padding: 40px; border: 1px solid #4b5563; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.75);">
            <div style="display: flex; flex-wrap: wrap; gap: 40px;">
                <div style="flex: 1; min-width: 280px;">
                    <div style="background: #1f232b; border-radius: 24px; overflow: hidden; border: 1px solid #4b5563;">
                        <div style="position: relative;">
                            <img src="{{ $juego->imagen ?? 'https://picsum.photos/id/1/400/400' }}" alt="{{ $juego->nombre }}" style="width: 100%; height: 320px; object-fit: cover;">
                            <div style="position: absolute; top: 20px; right: 20px; background: #dc2626; padding: 6px 15px; border-radius: 30px; font-size: 12px; font-weight: bold;">DISPONIBLE</div>
                        </div>
                        <div style="padding: 24px;">
                            <h3 style="font-size: 24px; color: white; margin-bottom: 8px;">{{ $juego->nombre }}</h3>
                            <p style="color: #ef4444; margin-bottom: 18px;">{{ $juego->categoria }} • {{ $juego->consola }}</p>
                            <div style="display: flex; justify-content: space-between; padding-top: 15px; border-top: 1px solid #4b5563;">
                                <span style="color: #d0d5dd;">Stock</span>
                                <span style="color: #fca5a5;">{{ $juego->stock }} unidades</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="flex: 1.2; min-width: 300px;">
                    <h2 style="font-size: 32px; color: white; margin-bottom: 8px;">Confirma tu compra</h2>
                    <p style="color: #d0d5dd; margin-bottom: 28px;">Selecciona una forma de pago y revisa el total.</p>

                    <div style="background: #1f232b; border: 1px solid #4b5563; border-radius: 20px; padding: 22px; margin-bottom: 28px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                            <span style="color: #d0d5dd;">Subtotal</span>
                            <span>Bs {{ number_format($juego->precio, 2, ',', '.') }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding-top: 15px; border-top: 1px solid #4b5563;">
                            <strong>Total a pagar</strong>
                            <strong style="font-size: 28px; color: #ef4444;">Bs {{ number_format($juego->precio, 2, ',', '.') }}</strong>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('comprar.store', $juego->id) }}" novalidate>
                        @csrf

                        <h3 style="color: white; margin-bottom: 15px;">💳 Método de pago</h3>

                        @error('metodo_pago_id')
                            <div style="background: #7f1d1d; border: 1px solid #ef4444; color: white; padding: 12px 15px; border-radius: 10px; margin-bottom: 15px;">{{ $message }}</div>
                        @enderror

                        @error('stock')
                            <div style="background: #7f1d1d; border: 1px solid #ef4444; color: white; padding: 12px 15px; border-radius: 10px; margin-bottom: 15px;">{{ $message }}</div>
                        @enderror

                        <div class="metodos-pago-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; margin-bottom: 30px;">
                            @forelse($metodosPago as $metodo)
                                <label class="metodo-pago-opcion" style="display: block; cursor: pointer;">
                                    <input type="radio" name="metodo_pago_id" value="{{ $metodo->id }}" {{ old('metodo_pago_id') == $metodo->id ? 'checked' : '' }} required style="position: absolute; opacity: 0; pointer-events: none;">
                                    <div class="metodo-pago-card" style="height: 100%; background: #272c34; border: 1px solid #5b6574; border-radius: 14px; padding: 16px; transition: .25s;">
                                        <div style="font-size: 25px; margin-bottom: 8px;">{{ str_contains(strtolower($metodo->nombre), 'tarjeta') ? '💳' : (str_contains(strtolower($metodo->nombre), 'transferencia') ? '🏦' : '📱') }}</div>
                                        <strong style="display: block; color: white; margin-bottom: 5px;">{{ $metodo->nombre }}</strong>
                                        <small style="color: #d0d5dd;">{{ $metodo->descripcion }}</small>
                                    </div>
                                </label>
                            @empty
                                <div style="grid-column: 1 / -1; background: #7f1d1d; padding: 14px; border-radius: 10px;">
                                    No hay métodos de pago disponibles. Ejecuta las migraciones del proyecto.
                                </div>
                            @endforelse
                        </div>

                        <div style="background: #1f232b; border: 1px solid #4b5563; border-radius: 16px; padding: 18px; margin-bottom: 25px;">
                            <h3 style="margin-bottom: 10px;">👤 Datos del comprador</h3>
                            <p><strong>{{ auth()->user()->name }}</strong></p>
                            <p style="color: #d0d5dd; margin-top: 4px;">{{ auth()->user()->email }}</p>
                        </div>

                        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                            <button type="submit" style="flex: 1; min-width: 220px; background: linear-gradient(135deg, #dc2626, #991b1b); color: white; padding: 16px 30px; border-radius: 50px; font-size: 18px; font-weight: bold; border: 1px solid #ef4444; cursor: pointer; box-shadow: 0 10px 25px -5px rgba(220,38,38,0.35);">
                                ✅ Confirmar y pagar
                            </button>
                            <a href="{{ route('juegos.show', $juego->id) }}" style="background: #323944; color: white; padding: 16px 30px; border-radius: 50px; text-decoration: none; text-align: center; border: 1px solid #5b6574;">← Volver</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .metodo-pago-opcion input:checked + .metodo-pago-card {
        border-color: #ef4444 !important;
        background: #47252b !important;
        box-shadow: 0 0 0 2px rgba(220, 38, 38, .2), 0 10px 25px rgba(220, 38, 38, .16);
    }

    .metodo-pago-card:hover {
        border-color: #dc2626 !important;
        transform: translateY(-2px);
    }
</style>
@endsection
