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
        Schema::create('rol_menu', function (Blueprint $table) {
            $table->id('id_rol_menu');
            $table->unsignedBigInteger('id_rol');
            $table->unsignedBigInteger('id_menu');
            $table->timestamps();
            
            $table->foreign('id_rol')->references('id_rol')->on('roles')->cascadeOnDelete();
            $table->foreign('id_menu')->references('id_menu')->on('menu_items')->cascadeOnDelete();
            $table->unique(['id_rol', 'id_menu']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rol_menu');
    }
};
