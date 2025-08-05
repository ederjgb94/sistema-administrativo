<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetodoPago extends Model
{
    protected $table = 'metodos_pago';

    protected $fillable = [
        'nombre',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function transacciones(): HasMany
    {
        return $this->hasMany(Transaccion::class);
    }

    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }
}
