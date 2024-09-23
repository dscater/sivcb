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
        Schema::create('ingreso_productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("producto_id");
            $table->unsignedBigInteger("proveedor_id");
            $table->decimal("precio", 24, 2);
            $table->double("cantidad", 8, 2);
            $table->unsignedBigInteger("tipo_ingreso_id");
            $table->string("descripcion", 600)->nullable();
            $table->string("lugar");
            $table->unsignedBigInteger("sucursal_id")->nullable();
            $table->date("fecha_ingreso");
            $table->date("fecha_registro");
            $table->timestamps();

            $table->foreign("producto_id")->on("productos")->references("id");
            $table->foreign("proveedor_id")->on("proveedors")->references("id");
            $table->foreign("tipo_ingreso_id")->on("tipo_ingresos")->references("id");
            $table->foreign("sucursal_id")->on("sucursals")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingreso_productos');
    }
};
