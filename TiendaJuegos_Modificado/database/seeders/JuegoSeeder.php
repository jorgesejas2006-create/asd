<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Juego;

class JuegoSeeder extends Seeder
{
    public function run(): void
    {
        Juego::create([
            'nombre' => 'Grand Theft Auto V',
            'descripcion' => 'Juego de mundo abierto desarrollado por Rockstar Games.',
            'anio_lanzamiento' => 2013,
            'categoria' => 'Acción',
            'consola' => 'PC',
            'stock' => 15,
            'precio' => 250,
            'imagen' => null,
            'codigo_acceso' => 'GTA5-PC-001',
            'en_venta' => true
        ]);

        Juego::create([
            'nombre' => 'Elden Ring',
            'descripcion' => 'RPG de acción desarrollado por FromSoftware.',
            'anio_lanzamiento' => 2022,
            'categoria' => 'RPG',
            'consola' => 'PlayStation 5',
            'stock' => 10,
            'precio' => 420,
            'imagen' => null,
            'codigo_acceso' => 'ELDEN-PS5-002',
            'en_venta' => true
        ]);

        Juego::create([
            'nombre' => 'EA Sports FC 26',
            'descripcion' => 'Simulador de fútbol.',
            'anio_lanzamiento' => 2026,
            'categoria' => 'Deportes',
            'consola' => 'Xbox Series X',
            'stock' => 20,
            'precio' => 390,
            'imagen' => null,
            'codigo_acceso' => 'FC26-XBOX-003',
            'en_venta' => true
        ]);

        Juego::create([
            'nombre' => 'Call of Duty Black Ops',
            'descripcion' => 'Shooter en primera persona.',
            'anio_lanzamiento' => 2025,
            'categoria' => 'FPS',
            'consola' => 'PC',
            'stock' => 18,
            'precio' => 350,
            'imagen' => null,
            'codigo_acceso' => 'COD-PC-004',
            'en_venta' => true
        ]);

        Juego::create([
            'nombre' => 'The Legend of Zelda',
            'descripcion' => 'Aventura épica de Nintendo.',
            'anio_lanzamiento' => 2023,
            'categoria' => 'Aventura',
            'consola' => 'Nintendo Switch',
            'stock' => 12,
            'precio' => 450,
            'imagen' => null,
            'codigo_acceso' => 'ZELDA-SWITCH-005',
            'en_venta' => true
        ]);
    }
}
