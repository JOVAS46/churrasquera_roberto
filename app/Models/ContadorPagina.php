<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContadorPagina extends Model
{
    protected $table = 'contador_paginas';
    protected $primaryKey = 'id_contador';
    
    protected $fillable = [
        'pagina',
        'total_visitas',
    ];

    protected $casts = [
        'total_visitas' => 'integer',
    ];

    /**
     * Incrementar contador de visitas
     */
    public function incrementar(): void
    {
        $this->increment('total_visitas');
    }

    /**
     * Obtener o crear contador para una página
     */
    public static function obtenerOCrear(string $pagina): self
    {
        return static::firstOrCreate(
            ['pagina' => $pagina],
            ['total_visitas' => 0]
        );
    }
}
