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
        Schema::table('pagos', function (Blueprint $table) {
            // Eliminar relación con pedidos si existe
            if (Schema::hasColumn('pagos', 'id_pedido')) {
                $table->dropForeign(['id_pedido']);
                $table->dropColumn('id_pedido');
            }
            
            // Relacionar con ventass
            $table->foreignId('id_venta')->nullable()->constrained('ventas', 'id_venta')->cascadeOnDelete();
            
            // Campos para PagoFácil QR
            $table->text('qr_image')->nullable();
            $table->string('nro_transaccion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['id_venta']);
            $table->dropColumn(['id_venta', 'qr_image', 'nro_transaccion']);
            
            $table->foreignId('id_pedido')->constrained('pedidos', 'id_pedido');
        });
    }
};
