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
        Schema::create('movimiento_insumos', function (Blueprint $table) {
            $table->id('id_movimiento');
            $table->enum('tipo_movimiento', ['entrada', 'salida']);
            $table->decimal('cantidad', 10, 2);
            $table->timestamp('fecha_movimiento')->useCurrent();
            $table->text('observaciones')->nullable();
            $table->unsignedBigInteger('id_insumo');
            $table->unsignedBigInteger('id_usuario');
            $table->timestamps();
            
            $table->foreign('id_insumo')->references('id_insumo')->on('insumos')->cascadeOnDelete();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_insumos');
    }
};
