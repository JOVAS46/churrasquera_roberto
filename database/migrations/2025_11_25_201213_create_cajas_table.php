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
        Schema::create('cajas', function (Blueprint $table) {
            $table->id('id_caja');
            $table->timestamp('fecha_apertura')->useCurrent();
            $table->timestamp('fecha_cierre')->nullable();
            $table->decimal('monto_inicial', 10, 2)->default(0);
            $table->decimal('monto_final', 10, 2)->nullable();
            $table->decimal('total_ingresos', 10, 2)->default(0);
            $table->decimal('total_egresos', 10, 2)->default(0);
            $table->enum('estado', ['abierta', 'cerrada'])->default('abierta');
            $table->unsignedBigInteger('id_usuario');
            $table->timestamps();
            
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cajas');
    }
};
