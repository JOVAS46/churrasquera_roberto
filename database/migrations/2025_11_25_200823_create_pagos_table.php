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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('id_pago');
            $table->timestamp('fecha_pago')->useCurrent();
            $table->decimal('monto', 10, 2);
            $table->enum('estado', ['pendiente', 'completado', 'cancelado'])->default('pendiente');
            $table->unsignedBigInteger('id_pedido')->unique();
            $table->unsignedBigInteger('id_metodo_pago');
            $table->timestamps();
            
            $table->foreign('id_pedido')->references('id_pedido')->on('pedidos')->cascadeOnDelete();
            $table->foreign('id_metodo_pago')->references('id_metodo_pago')->on('metodo_pagos')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
