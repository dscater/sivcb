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
        Schema::create('venta_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("venta_id");
            $table->unsignedBigInteger("producto_id");
            $table->double("cantidad", 8, 2);
            $table->decimal("precio", 24, 2);
            $table->decimal("subtotal", 24, 2);
            $table->double("descuento");
            $table->decimal("subtotaltotal", 24, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_detalles');
    }
};
