<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JuegoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\AdminController;
use App\Models\Juego;
use App\Models\User;
use App\Models\Compra;

/*
|--------------------------------------------------------------------------
| Página Principal
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $juegosDestacados = Juego::where('en_venta', true)->latest()->take(4)->get();
    $totalJuegos = Juego::where('en_venta', true)->count();
    $totalClientes = User::where('rol', 'cliente')->count();
    $totalVentas = Compra::count();

    return view('inicio.inicio', compact('juegosDestacados', 'totalJuegos', 'totalClientes', 'totalVentas'));
})->name('inicio');

/*
|--------------------------------------------------------------------------
| CATÁLOGO PÚBLICO
|--------------------------------------------------------------------------
*/

Route::get('/juegos', [JuegoController::class, 'index'])->name('juegos.index');
Route::get('/juegos/{juego}', [JuegoController::class, 'show'])->name('juegos.show');


/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (requieren login)
|--------------------------------------------------------------------------
*/


Route::middleware(['auth'])->group(function () {

    // Rutas para ADMIN (CRUD de juegos + ventas + clientes)
    Route::middleware(['admin'])->group(function () {
        // CRUD de juegos
       
        Route::get('/juegos/create/nuevo', [JuegoController::class, 'create'])->name('juegos.create');

        Route::post('/juegos', [JuegoController::class, 'store'])->name('juegos.store');
        Route::put('/juegos/{juego}', [JuegoController::class, 'update'])->name('juegos.update');
        Route::delete('/juegos/{juego}', [JuegoController::class, 'destroy'])->name('juegos.destroy');
        Route::get('/juegos/{juego}/edit', [JuegoController::class, 'edit'])->name('juegos.edit');

       
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/ventas', [AdminController::class, 'ventas'])->name('admin.ventas');
        Route::get('/admin/clientes', [AdminController::class, 'clientes'])->name('admin.clientes');
    });

    // Rutas para CLIENTE (compras)
    Route::post('/comprar/{juego}', [CompraController::class, 'store'])->name('comprar.store');
    Route::get('/mis-compras', [CompraController::class, 'index'])->name('mis-compras');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/comprar/confirmar/{juego}', [CompraController::class, 'confirmar'])->name('comprar.confirmar');

});

/*
|--------------------------------------------------------------------------
| DASHBOARD (redirige según rol)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    if (auth()->user()->rol === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('juegos.index');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

