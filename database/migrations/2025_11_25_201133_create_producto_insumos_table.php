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
        Schema::create('producto_insumos', function (Blueprint $table) {
            $table->id('id_producto_insumo');
            $table->decimal('cantidad_necesaria', 10, 2);
            $table->unsignedBigInteger('id_producto');
            $table->unsignedBigInteger('id_insumo');
            $table->timestamps();
            
            $table->foreign('id_producto')->references('id_producto')->on('productos')->cascadeOnDelete();
            $table->foreign('id_insumo')->references('id_insumo')->on('insumos')->cascadeOnDelete();
            $table->unique(['id_producto', 'id_insumo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto_insumos');
    }
};
