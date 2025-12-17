<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreference extends Model
{
    protected $table = 'user_preferences';
    protected $primaryKey = 'id_preferencia';
    
    protected $fillable = [
        'id_usuario',
        'tema',
        'tamano_letra',
        'alto_contraste',
    ];

    protected $casts = [
        'alto_contraste' => 'boolean',
    ];

    /**
     * Usuario dueño de estas preferencias
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
