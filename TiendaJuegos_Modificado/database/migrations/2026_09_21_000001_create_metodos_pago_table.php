<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metodos_pago', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 80)->unique();
            $table->string('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        $ahora = now();

        DB::table('metodos_pago')->insert([
            [
                'nombre' => 'Tarjeta',
                'descripcion' => 'Pago con tarjeta de débito o crédito',
                'activo' => true,
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
            [
                'nombre' => 'QR / Pago móvil',
                'descripcion' => 'Pago mediante QR o aplicación bancaria',
                'activo' => true,
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
            [
                'nombre' => 'Transferencia bancaria',
                'descripcion' => 'Transferencia desde una cuenta bancaria',
                'activo' => true,
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('metodos_pago');
    }
};
