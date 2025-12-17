<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';
    
    protected $fillable = [
        'fecha_pago',
        'monto',
        'estado',
        'id_pedido',
        'id_metodo_pago',
    ];

    protected $casts = [
        'fecha_pago' => 'datetime',
        'monto' => 'decimal:2',
    ];

    /**
     * Pedido asociado
     */
    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }

    /**
     * Método de pago utilizado
     */
    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class, 'id_metodo_pago', 'id_metodo_pago');
    }

    /**
     * Scope para pagos completados
     */
    public function scopeCompletado($query)
    {
        return $query->where('estado', 'completado');
    }

    /**
     * Scope para pagos pendientes
     */
    public function scopePendiente($query)
    {
        return $query->where('estado', 'pendiente');
    }
}
