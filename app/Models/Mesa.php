<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mesa extends Model
{
    protected $table = 'mesas';
    protected $primaryKey = 'id_mesa';
    
    protected $fillable = [
        'numero_mesa',
        'capacidad',
        'estado',
    ];

    protected $casts = [
        'capacidad' => 'integer',
    ];

    /**
     * Pedidos de esta mesa
     */
    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class, 'id_mesa', 'id_mesa');
    }

    /**
     * Scope para mesas disponibles
     */
    public function scopeDisponible($query)
    {
        return $query->where('estado', 'disponible');
    }

    /**
     * Scope para mesas ocupadas
     */
    public function scopeOcupada($query)
    {
        return $query->where('estado', 'ocupada');
    }
}
