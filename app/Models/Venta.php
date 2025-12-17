<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    protected $table = 'ventas';
    protected $primaryKey = 'id_venta';
    
    protected $fillable = [
        'fecha_venta',
        'total',
        'estado',
        'id_usuario',
        'id_cliente',
        'id_pedido',
    ];

    protected $casts = [
        'fecha_venta' => 'datetime',
        'total' => 'decimal:2',
    ];

    /**
     * Pedido asociado
     */
    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }

    /**
     * Usuario que registró la venta (Cajero/Admin/Mesero)
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Cliente asociado (opcional)
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_cliente', 'id_usuario');
    }

    /**
     * Detalles de la venta
     */
    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleVenta::class, 'id_venta', 'id_venta');
    }
}
