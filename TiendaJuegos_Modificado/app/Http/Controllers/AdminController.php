<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Juego;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $inventario = Juego::withCount('detalles')
            ->orderBy('stock')
            ->orderBy('nombre')
            ->get();

        $stockBajo = $inventario
            ->filter(fn (Juego $juego) => $juego->stock_bajo)
            ->values();

        $sinStock = $inventario
            ->filter(fn (Juego $juego) => $juego->sin_stock)
            ->values();

        $masVendidos = Juego::withCount('detalles')
            ->whereHas('detalles')
            ->orderByDesc('detalles_count')
            ->orderBy('nombre')
            ->take(5)
            ->get();

        $totalVentas = Compra::count();
        $ingresos = Pago::where('estado', 'aprobado')->sum('monto');
        $totalClientes = User::where('rol', 'cliente')->count();

        return view('admin.dashboard', compact(
            'inventario',
            'stockBajo',
            'sinStock',
            'masVendidos',
            'totalVentas',
            'ingresos',
            'totalClientes'
        ));
    }

    public function ventas(Request $request)
    {
        $filtros = $request->validate([
            'buscar' => ['nullable', 'string', 'max:100'],
        ], [
            'buscar.max' => 'La búsqueda no puede superar los 100 caracteres.',
        ]);

        $query = Compra::with(['user', 'detalles.juego', 'pago.metodoPago']);

        if (!empty($filtros['buscar'])) {
            $buscar = trim($filtros['buscar']);
            $query->whereHas('user', function ($q) use ($buscar) {
                $q->where('name', 'like', '%' . $buscar . '%')
                    ->orWhere('email', 'like', '%' . $buscar . '%');
            });
        }

        $ventas = $query->latest()->paginate(15)->withQueryString();

        return view('admin.ventas', compact('ventas'));
    }

    public function clientes(Request $request)
    {
        $filtros = $request->validate([
            'buscar' => ['nullable', 'string', 'max:100'],
        ], [
            'buscar.max' => 'La búsqueda no puede superar los 100 caracteres.',
        ]);

        $query = User::where('rol', 'cliente')->with('compras');

        if (!empty($filtros['buscar'])) {
            $buscar = trim($filtros['buscar']);
            $query->where(function ($q) use ($buscar) {
                $q->where('name', 'like', '%' . $buscar . '%')
                    ->orWhere('email', 'like', '%' . $buscar . '%');
            });
        }

        $clientes = $query->latest()->paginate(15)->withQueryString();

        return view('admin.clientes', compact('clientes'));
    }
}
