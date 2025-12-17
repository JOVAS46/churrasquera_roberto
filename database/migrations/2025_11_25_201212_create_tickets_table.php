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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id('id_ticket');
            $table->string('numero_ticket', 20)->unique();
            $table->timestamp('fecha_emision')->useCurrent();
            $table->enum('tipo', ['cocina', 'cliente']);
            $table->enum('estado', ['pendiente', 'impreso', 'anulado'])->default('pendiente');
            $table->unsignedBigInteger('id_pedido');
            $table->timestamps();
            
            $table->foreign('id_pedido')->references('id_pedido')->on('pedidos')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
