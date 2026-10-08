<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Juego extends Model
{
    public const STOCK_BAJO = 10;

    protected $fillable = [
        'nombre',
        'descripcion',
        'anio_lanzamiento',
        'categoria',
        'consola',
        'stock',
        'precio',
        'imagen',
        'codigo_acceso',
        'en_venta',
    ];

    protected $casts = [
        'en_venta' => 'boolean',
        'precio' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleCompra::class);
    }

    public function getSinStockAttribute(): bool
    {
        return $this->stock <= 0;
    }

    public function getStockBajoAttribute(): bool
    {
        return $this->stock > 0 && $this->stock <= self::STOCK_BAJO;
    }
}
