<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contacto extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tipo',
        'nombre',
        'email',
        'telefono',
        'direccion',
        'rfc',
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

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopeClientes($query)
    {
        return $query->whereIn('tipo', ['cliente', 'ambos']);
    }

    public function scopeProveedores($query)
    {
        return $query->whereIn('tipo', ['proveedor', 'ambos']);
    }
}
