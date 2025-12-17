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
        Schema::create('reservas', function (Blueprint $table) {
            $table->id('id_reserva');
            $table->date('fecha_reserva');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->integer('numero_personas');
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'completada'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_mesa');
            $table->timestamps();
            
            $table->foreign('id_cliente')->references('id_usuario')->on('usuarios')->cascadeOnDelete();
            $table->foreign('id_mesa')->references('id_mesa')->on('mesas')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
