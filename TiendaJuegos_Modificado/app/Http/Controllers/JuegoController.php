<?php

namespace App\Http\Controllers;

use App\Http\Requests\JuegoRequest;
use App\Models\Juego;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class JuegoController extends Controller
{
    public function index(Request $request)
    {
        $filtros = $request->validate([
            'buscar' => ['nullable', 'string', 'max:100'],
            'categoria' => ['nullable', Rule::in(['Acción', 'Fantasía', 'FPS', 'Survival'])],
            'consola' => ['nullable', Rule::in(['PC', 'Play', 'XBOX'])],
        ], [
            'buscar.max' => 'La búsqueda no puede superar los 100 caracteres.',
            'categoria.in' => 'La categoría seleccionada no es válida.',
            'consola.in' => 'La consola seleccionada no es válida.',
        ]);

        $query = Juego::query();

        if (!Auth::check() || Auth::user()->rol !== 'admin') {
            $query->where('en_venta', true);
        }

        if (!empty($filtros['buscar'])) {
            $query->where('nombre', 'like', '%' . trim($filtros['buscar']) . '%');
        }

        if (!empty($filtros['categoria'])) {
            $query->where('categoria', $filtros['categoria']);
        }

        if (!empty($filtros['consola'])) {
            $query->where('consola', $filtros['consola']);
        }

        $juegos = $query->latest()->paginate(6)->withQueryString();

        return view('juegos.index', compact('juegos'));
    }

    public function create()
    {
        return view('juegos.create');
    }

    public function store(JuegoRequest $request)
    {
        $datos = $request->validated();
        $datos['en_venta'] = $request->boolean('en_venta');

        Juego::create($datos);

        return redirect()
            ->route('juegos.index')
            ->with('success', 'Juego registrado correctamente.');
    }

    public function show(Juego $juego)
    {
        if (!$juego->en_venta && (!Auth::check() || Auth::user()->rol !== 'admin')) {
            abort(404);
        }

        return view('juegos.show', compact('juego'));
    }

    public function edit(Juego $juego)
    {
        return view('juegos.edit', compact('juego'));
    }

    public function update(JuegoRequest $request, Juego $juego)
    {
        $datos = $request->validated();
        $datos['en_venta'] = $request->boolean('en_venta');

        $juego->update($datos);

        return redirect()
            ->route('juegos.index')
            ->with('success', 'Juego actualizado correctamente.');
    }

    public function destroy(Juego $juego)
    {
        if ($juego->detalles()->exists()) {
            return redirect()
                ->route('juegos.index')
                ->with('error', 'No se puede eliminar este juego porque tiene ventas registradas. Puedes editarlo y desactivar la opción “A la venta”.');
        }

        $juego->delete();

        return redirect()
            ->route('juegos.index')
            ->with('success', 'Juego eliminado correctamente.');
    }
}
