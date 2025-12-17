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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nombre', 100);
            $table->string('apellido', 100);
            $table->string('email', 100)->unique();
            $table->string('telefono', 20)->nullable();
            $table->string('password');
            $table->timestamp('fecha_registro')->useCurrent();
            $table->boolean('estado')->default(true);
            $table->unsignedBigInteger('id_rol');
            $table->timestamps();
            
            $table->foreign('id_rol')->references('id_rol')->on('roles')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
