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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id('id_menu');
            $table->string('nombre', 100);
            $table->string('ruta');
            $table->string('icono', 50)->nullable();
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
            
            $table->foreign('parent_id')->references('id_menu')->on('menu_items')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
