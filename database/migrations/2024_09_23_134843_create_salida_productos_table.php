<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salida_productos', function (Blueprint $table) {
            $table->id();
            $table->string("origen");
            $table->unsignedBigInteger("producto_id");
            $table->double("cantidad", 8, 2);
            $table->date("fecha_salida");
            $table->unsignedBigInteger("tipo_salida_id");
            $table->string("descripcion", 600)->nullable();
            $table->string("lugar", 255);
            $table->unsignedBigInteger("sucursal_id")->nullable();
            $table->date("fecha_registro");
            $table->timestamps();

            $table->foreign("producto_id")->on("productos")->references("id");
            $table->foreign("tipo_salida_id")->on("tipo_salidas")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salida_productos');
    }
};
