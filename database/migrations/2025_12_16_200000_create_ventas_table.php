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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id('id_venta');
            $table->dateTime('fecha_venta');
            $table->decimal('total', 10, 2);
            $table->string('estado')->default('completada'); // completada, anulada
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario'); // Cajero/Admin que registró
            $table->foreignId('id_cliente')->nullable()->constrained('usuarios', 'id_usuario'); // Cliente opcional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
