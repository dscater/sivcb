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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string("nombre", 255)->unique();
            $table->unsignedBigInteger("categoria_id");
            $table->unsignedBigInteger("marca_id");
            $table->unsignedBigInteger("unidad_medida_id");
            $table->decimal("precio", 24, 2);
            $table->double("stock_min", 8, 2);
            $table->string("imagen", 255)->nullable();
            $table->date("fecha_registro");
            $table->timestamps();

            $table->foreign("categoria_id")->on("categorias")->references("id");
            $table->foreign("marca_id")->on("marcas")->references("id");
            $table->foreign("unidad_medida_id")->on("unidad_medidas")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
