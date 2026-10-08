<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('juegos', function (Blueprint $table) {

            $table->id();

            $table->string('nombre');

            $table->text('descripcion');

            $table->integer('anio_lanzamiento');

            $table->string('categoria');

            $table->string('consola');

            $table->integer('stock');

            $table->decimal('precio', 8, 2);

            $table->string('imagen')->nullable();

            $table->string('codigo_acceso');

            $table->boolean('en_venta')->default(true);

            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('juegos');
    }
};
