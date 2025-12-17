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
        Schema::create('insumos', function (Blueprint $table) {
            $table->id('id_insumo');
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->string('unidad_medida', 20);
            $table->decimal('stock_actual', 10, 2)->default(0);
            $table->decimal('stock_minimo', 10, 2);
            $table->decimal('precio_unitario', 10, 2)->nullable();
            $table->unsignedBigInteger('id_proveedor')->nullable();
            $table->timestamps();
            
            $table->foreign('id_proveedor')->references('id_proveedor')->on('proveedores')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insumos');
    }
};
