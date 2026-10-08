<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'edad',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relación con compras
    public function compras()
    {
        return $this->hasMany(Compra::class);
    }

    // Verificar si es admin
    public function isAdmin()
    {
        return $this->rol === 'admin';
    }

    // Verificar si es cliente
    public function isCliente()
    {
        return $this->rol === 'cliente';
    }
}