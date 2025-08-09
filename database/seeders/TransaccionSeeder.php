<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaccion;
use App\Models\MetodoPago;
use Carbon\Carbon;

class TransaccionSeeder extends Seeder
{
    public function run()
    {
        // Crear algunos métodos de pago si no existen
        $metodoPago1 = MetodoPago::firstOrCreate(['nombre' => 'Efectivo']);
        $metodoPago2 = MetodoPago::firstOrCreate(['nombre' => 'Tarjeta']);
        $metodoPago3 = MetodoPago::firstOrCreate(['nombre' => 'Transferencia']);

        // Crear transacciones para hoy
        $hoy = Carbon::today();

        // Ingresos de hoy
        Transaccion::create([
            'folio' => 'ING-' . date('Ymd') . '-001',
            'tipo' => 'ingreso',
            'fecha' => $hoy,
            'referencia_tipo' => 'producto',
            'referencia_nombre' => 'Venta de productos',
            'factura_tipo' => 'manual',
            'metodo_pago_id' => $metodoPago1->id,
            'total' => 1500.00,
            'observaciones' => 'Venta del día',
        ]);

        Transaccion::create([
            'folio' => 'ING-' . date('Ymd') . '-002',
            'tipo' => 'ingreso',
            'fecha' => $hoy,
            'referencia_tipo' => 'servicio',
            'referencia_nombre' => 'Servicio de consultoría',
            'factura_tipo' => 'manual',
            'metodo_pago_id' => $metodoPago2->id,
            'total' => 2800.00,
            'observaciones' => 'Consultoría empresarial',
        ]);

        Transaccion::create([
            'folio' => 'ING-' . date('Ymd') . '-003',
            'tipo' => 'ingreso',
            'fecha' => $hoy,
            'referencia_tipo' => 'producto',
            'referencia_nombre' => 'Venta online',
            'factura_tipo' => 'manual',
            'metodo_pago_id' => $metodoPago3->id,
            'total' => 950.00,
            'observaciones' => 'Venta por internet',
        ]);

        // Egresos de hoy
        Transaccion::create([
            'folio' => 'EGR-' . date('Ymd') . '-001',
            'tipo' => 'egreso',
            'fecha' => $hoy,
            'referencia_tipo' => 'otro',
            'referencia_nombre' => 'Compra de materiales',
            'factura_tipo' => 'manual',
            'metodo_pago_id' => $metodoPago1->id,
            'total' => 450.00,
            'observaciones' => 'Materiales de oficina',
        ]);

        Transaccion::create([
            'folio' => 'EGR-' . date('Ymd') . '-002',
            'tipo' => 'egreso',
            'fecha' => $hoy,
            'referencia_tipo' => 'servicio',
            'referencia_nombre' => 'Pago de servicios',
            'factura_tipo' => 'manual',
            'metodo_pago_id' => $metodoPago2->id,
            'total' => 320.00,
            'observaciones' => 'Servicios básicos',
        ]);

        // Transacciones de ayer para comparación
        $ayer = Carbon::yesterday();

        Transaccion::create([
            'folio' => 'ING-' . $ayer->format('Ymd') . '-001',
            'tipo' => 'ingreso',
            'fecha' => $ayer,
            'referencia_tipo' => 'producto',
            'referencia_nombre' => 'Venta del día anterior',
            'factura_tipo' => 'manual',
            'metodo_pago_id' => $metodoPago1->id,
            'total' => 1200.00,
            'observaciones' => 'Venta de ayer',
        ]);

        Transaccion::create([
            'folio' => 'EGR-' . $ayer->format('Ymd') . '-001',
            'tipo' => 'egreso',
            'fecha' => $ayer,
            'referencia_tipo' => 'otro',
            'referencia_nombre' => 'Gasto del día anterior',
            'factura_tipo' => 'manual',
            'metodo_pago_id' => $metodoPago1->id,
            'total' => 200.00,
            'observaciones' => 'Gasto menor',
        ]);
    }
}
