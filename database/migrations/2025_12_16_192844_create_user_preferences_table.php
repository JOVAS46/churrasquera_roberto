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
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id('id_preferencia');
            $table->unsignedBigInteger('id_usuario')->unique();
            $table->enum('tema', ['claro', 'oscuro', 'alto_contraste'])->default('claro');
            $table->enum('tamano_letra', ['pequeno', 'mediano', 'grande'])->default('mediano');
            $table->boolean('alto_contraste')->default(false);
            $table->timestamps();
            
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};
