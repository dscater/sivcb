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
        Schema::create('producto_barras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("producto_id");
            $table->string("codigo", 255)->unique();
            $table->string("lugar");
            $table->unsignedBigInteger("sucursal_id")->nullable();
            $table->unsignedBigInteger("ingreso_id")->nullable();
            $table->unsignedBigInteger("salida_id")->nullable();
            $table->unsignedBigInteger("venta_detalle_id")->nullable();
            $table->unsignedBigInteger("distribucion_detalle_id")->nullable();
            $table->timestamps();

            $table->foreign("producto_id")->on("productos")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_barras');
    }
};
