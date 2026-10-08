<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Juego;
use App\Models\MetodoPago;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CompraController extends Controller
{
    public function index()
    {
        $compras = Compra::where('user_id', auth()->id())
            ->with(['detalles.juego', 'pago.metodoPago'])
            ->latest()
            ->paginate(10);

        return view('compras.mis-compras', compact('compras'));
    }

    public function confirmar(Juego $juego)
    {
        if (!$juego->en_venta) {
            return redirect()->route('juegos.show', $juego)
                ->with('error', 'Este juego no está disponible para la venta.');
        }

        if ($juego->stock <= 0) {
            return redirect()->route('juegos.show', $juego)
                ->with('error', 'Este juego está sin stock por el momento.');
        }

        if (auth()->user()->rol !== 'cliente') {
            return redirect()->route('juegos.show', $juego)
                ->with('error', 'Solo los clientes pueden realizar compras.');
        }

        $metodosPago = MetodoPago::where('activo', true)->orderBy('id')->get();

        return view('compras.confirmacion', compact('juego', 'metodosPago'));
    }

    public function store(Request $request, Juego $juego)
    {
        if (auth()->user()->rol !== 'cliente') {
            return redirect()->back()->with('error', 'Solo los clientes pueden realizar compras.');
        }

        $datos = $request->validate([
            'metodo_pago_id' => [
                'required',
                'integer',
                Rule::exists('metodos_pago', 'id')->where(fn ($query) => $query->where('activo', true)),
            ],
        ], [
            'metodo_pago_id.required' => 'Debes seleccionar un método de pago.',
            'metodo_pago_id.integer' => 'El método de pago seleccionado no es válido.',
            'metodo_pago_id.exists' => 'El método de pago seleccionado no está disponible.',
        ]);

        try {
            DB::transaction(function () use ($juego, $datos) {
                $juegoBloqueado = Juego::whereKey($juego->id)->lockForUpdate()->firstOrFail();

                if (!$juegoBloqueado->en_venta) {
                    throw ValidationException::withMessages([
                        'stock' => 'Este juego ya no está disponible para la venta.',
                    ]);
                }

                if ($juegoBloqueado->stock <= 0) {
                    throw ValidationException::withMessages([
                        'stock' => 'Lo sentimos, este juego acaba de quedarse sin stock.',
                    ]);
                }

                $compra = Compra::create([
                    'user_id' => auth()->id(),
                    'fecha_compra' => now(),
                    'total' => $juegoBloqueado->precio,
                ]);

                DetalleCompra::create([
                    'compra_id' => $compra->id,
                    'juego_id' => $juegoBloqueado->id,
                    'precio' => $juegoBloqueado->precio,
                    'codigo_entregado' => $juegoBloqueado->codigo_acceso,
                ]);

                Pago::create([
                    'compra_id' => $compra->id,
                    'metodo_pago_id' => $datos['metodo_pago_id'],
                    'monto' => $juegoBloqueado->precio,
                    'estado' => 'aprobado',
                    'referencia' => 'PAY-' . now()->format('YmdHis') . '-' . $compra->id,
                    'fecha_pago' => now(),
                ]);

                $juegoBloqueado->decrement('stock');
            }, 3);

            return redirect()->route('mis-compras')
                ->with('success', 'Compra realizada correctamente. Tu código de acceso ya está disponible.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            return redirect()->back()
                ->withInput()
                ->with('error', 'No se pudo procesar la compra. Inténtalo nuevamente.');
        }
    }
}
