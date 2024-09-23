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
        Schema::create('kadexs', function (Blueprint $table) {
            $table->id();
            $table->string("lugar", 255)->nullable();
            $table->unsignedBigInteger("sucursal_id")->nullable();
            $table->string("tipo_registro", 255)->nullable();
            $table->unsignedBigInteger("registro_id")->nullable();
            $table->unsignedBigInteger("producto_id");
            $table->text("detalle");
            $table->decimal("precio", 24, 2)->nullable();
            $table->string("tipo_is");
            $table->double("cantidad_ingreso")->nullable();
            $table->double("cantidad_salida")->nullable();
            $table->double("cantidad_saldo");
            $table->decimal("cu", 24, 2);
            $table->decimal("monto_ingreso", 24, 2)->nullable();
            $table->decimal("monto_salida", 24, 2)->nullable();
            $table->decimal("monto_saldo", 24, 2);
            $table->date("fecha");
            $table->timestamps();
            
            $table->foreign("producto_id")->on("productos")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kadexs');
    }
};
