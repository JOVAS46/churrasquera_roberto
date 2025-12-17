<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitaPagina extends Model
{
    protected $table = 'visita_paginas';
    protected $primaryKey = 'id_visita';
    
    protected $fillable = [
        'pagina',
        'ip_address',
        'user_agent',
        'fecha_visita',
        'id_usuario',
    ];

    protected $casts = [
        'fecha_visita' => 'datetime',
    ];

    /**
     * Usuario que visitó (si está autenticado)
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Registrar una nueva visita
     */
    public static function registrar(string $pagina, ?int $idUsuario = null): self
    {
        return static::create([
            'pagina' => $pagina,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'id_usuario' => $idUsuario,
        ]);
    }
}
