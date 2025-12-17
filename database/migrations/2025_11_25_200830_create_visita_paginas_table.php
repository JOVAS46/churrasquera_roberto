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
        Schema::create('visita_paginas', function (Blueprint $table) {
            $table->id('id_visita');
            $table->string('pagina');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('fecha_visita')->useCurrent();
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->timestamps();
            
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visita_paginas');
    }
};
