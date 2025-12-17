<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetodoPago extends Model
{
    protected $table = 'metodo_pagos';
    protected $primaryKey = 'id_metodo_pago';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Pagos realizados con este método
     */
    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'id_metodo_pago', 'id_metodo_pago');
    }

    /**
     * Scope para métodos activos
     */
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }
}
