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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id('id_pedido');
            $table->timestamp('fecha_pedido')->useCurrent();
            $table->enum('estado', ['pendiente', 'en_preparacion', 'listo', 'entregado', 'cancelado'])->default('pendiente');
            $table->decimal('total', 10, 2)->default(0);
            $table->text('observaciones')->nullable();
            $table->unsignedBigInteger('id_cliente')->nullable();
            $table->unsignedBigInteger('id_mesero');
            $table->unsignedBigInteger('id_mesa');
            $table->timestamps();
            
            $table->foreign('id_cliente')->references('id_usuario')->on('usuarios')->nullOnDelete();
            $table->foreign('id_mesero')->references('id_usuario')->on('usuarios')->restrictOnDelete();
            $table->foreign('id_mesa')->references('id_mesa')->on('mesas')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
