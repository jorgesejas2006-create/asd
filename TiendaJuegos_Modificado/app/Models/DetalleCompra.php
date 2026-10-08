<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    use HasFactory;

    protected $table = 'detalle_compras';

    protected $fillable = [
        'compra_id',
        'juego_id',
        'precio',
        'codigo_entregado',
    ];

    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }

    public function juego()
    {
        return $this->belongsTo(Juego::class);
    }
}