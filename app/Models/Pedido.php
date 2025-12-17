<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pedido extends Model
{
    protected $table = 'pedidos';
    protected $primaryKey = 'id_pedido';
    
    protected $fillable = [
        'fecha_pedido',
        'estado',
        'total',
        'observaciones',
        'id_cliente',
        'id_mesero',
        'id_mesa',
    ];

    protected $casts = [
        'fecha_pedido' => 'datetime',
        'total' => 'decimal:2',
    ];

    /**
     * Cliente del pedido
     */
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_cliente', 'id_usuario');
    }

    /**
     * Mesero que atendió
     */
    public function mesero(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_mesero', 'id_usuario');
    }

    /**
     * Mesa del pedido
     */
    public function mesa(): BelongsTo
    {
        return $this->belongsTo(Mesa::class, 'id_mesa', 'id_mesa');
    }

    /**
     * Pago asociado
     */
    public function pago(): HasOne
    {
        return $this->hasOne(Pago::class, 'id_pedido', 'id_pedido');
    }

    /**
     * Venta asociada (Cobro)
     */
    public function venta(): HasOne
    {
        return $this->hasOne(Venta::class, 'id_pedido', 'id_pedido');
    }

    /**
     * Detalles del pedido
     */
    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'id_pedido', 'id_pedido');
    }

    /**
     * Scope para pedidos pendientes
     */
    public function scopePendiente($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para pedidos en preparación
     */
    public function scopeEnPreparacion($query)
    {
        return $query->where('estado', 'en_preparacion');
    }

    /**
     * Scope para pedidos listos
     */
    public function scopeListo($query)
    {
        return $query->where('estado', 'listo');
    }

    /**
     * Scope para pedidos entregados
     */
    public function scopeEntregado($query)
    {
        return $query->where('estado', 'entregado');
    }

    /**
     * Scope para pedidos cancelados
     */
    public function scopeCancelado($query)
    {
        return $query->where('estado', 'cancelado');
    }

    /**
     * Cambiar estado del pedido
     */
    public function cambiarEstado(string $nuevoEstado): bool
    {
        $estadosValidos = ['pendiente', 'en_preparacion', 'listo', 'entregado', 'cancelado'];
        
        if (!in_array($nuevoEstado, $estadosValidos)) {
            return false;
        }

        $this->estado = $nuevoEstado;
        return $this->save();
    }

    /**
     * Verificar si el pedido está pagado
     */
    public function estaPagado(): bool
    {
        return $this->pago()->exists();
    }
}
