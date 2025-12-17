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
        Schema::create('recetas', function (Blueprint $table) {
            $table->id('id_receta');
            
            // Relación con Productos
            $table->unsignedBigInteger('id_producto');
            $table->foreign('id_producto')->references('id_producto')->on('productos')->onDelete('cascade');
            
            // Relación con Insumos
            $table->unsignedBigInteger('id_insumo');
            $table->foreign('id_insumo')->references('id_insumo')->on('insumos')->onDelete('cascade');
            
            $table->decimal('cantidad_requerida', 10, 2); // Ej: 0.2 (kg de carne)
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recetas');
    }
};
