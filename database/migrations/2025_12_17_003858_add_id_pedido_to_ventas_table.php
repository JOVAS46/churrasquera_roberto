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
        Schema::table('ventas', function (Blueprint $table) {
            // Asumiendo que la PK de pedidos es id_pedido
            $table->unsignedBigInteger('id_pedido')->nullable()->after('id_cliente');
            $table->foreign('id_pedido')->references('id_pedido')->on('pedidos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign(['id_pedido']);
            $table->dropColumn('id_pedido');
        });
    }
};
