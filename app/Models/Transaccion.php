<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaccion extends Model
{
    protected $table = 'transacciones';

    protected $fillable = [
        'folio',
        'tipo',
        'fecha',
        'contacto_id',
        'referencia_tipo',
        'referencia_nombre',
        'referencia_datos',
        'factura_tipo',
        'factura_numero',
        'factura_datos',
        'factura_archivos',
        'metodo_pago_id',
        'referencia_pago',
        'total',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'total' => 'decimal:4',
        'referencia_datos' => 'array',
        'factura_datos' => 'array',
        'factura_archivos' => 'array',
    ];

    public function contacto(): BelongsTo
    {
        return $this->belongsTo(Contacto::class);
    }

    public function metodoPago(): BelongsTo
    {
        return $this->belongsTo(MetodoPago::class);
    }

    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    public function scopeIngresos($query)
    {
        return $query->where('tipo', 'ingreso');
    }

    public function scopeEgresos($query)
    {
        return $query->where('tipo', 'egreso');
    }

    public function scopeFecha($query, $fecha)
    {
        return $query->whereDate('fecha', $fecha);
    }

    public function scopeEntreFechas($query, $fechaInicio, $fechaFin)
    {
        return $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
    }
}
