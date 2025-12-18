<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reserva extends Model
{
    protected $table = 'reservas';
    protected $primaryKey = 'id_reserva';
    
    protected $fillable = [
        'fecha_reserva',
        'hora_inicio',
        'hora_fin',
        'numero_personas',
        'estado',
        'observaciones',
        'id_cliente',
        'id_mesa',
    ];

    protected $casts = [
        'fecha_reserva' => 'date',
        'numero_personas' => 'integer',
        'hora_inicio' => 'datetime', // Or string, careful with casting time types in Laravel
        'hora_fin' => 'datetime',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_cliente', 'id_usuario');
    }

    public function mesa(): BelongsTo
    {
        return $this->belongsTo(Mesa::class, 'id_mesa', 'id_mesa');
    }
}
