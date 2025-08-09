<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaccion;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function reporteDiario(Request $request)
    {
        $formato = $request->get('formato', 'pdf');

        // Obtener transacciones del día actual
        $hoy = Carbon::today();
        $transacciones = Transaccion::with(['metodoPago', 'contacto'])
            ->whereDate('fecha', $hoy)
            ->orderBy('fecha', 'desc')
            ->orderBy('total', 'desc')
            ->get();

        // Calcular resumen
        $ingresos = $transacciones->where('tipo', 'ingreso')->sum('total');
        $egresos = $transacciones->where('tipo', 'egreso')->sum('total');

        $resumen = [
            'fecha' => $hoy->format('d/m/Y'),
            'total_ingresos' => $ingresos,
            'total_egresos' => $egresos,
            'balance' => $ingresos - $egresos,
            'total_transacciones' => $transacciones->count(),
        ];

        if ($formato === 'csv') {
            return $this->generarCSV($transacciones, $resumen);
        }

        return $this->generarPDF($transacciones, $resumen);
    }

    private function generarCSV($transacciones, $resumen)
    {
        $filename = 'reporte_diario_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($transacciones, $resumen) {
            $file = fopen('php://output', 'w');

            // Escribir BOM para UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Encabezado del reporte
            fputcsv($file, ['REPORTE DIARIO DE TRANSACCIONES']);
            fputcsv($file, ['Fecha:', now()->format('d/m/Y')]);
            fputcsv($file, []);

            // Resumen
            fputcsv($file, ['RESUMEN FINANCIERO']);
            fputcsv($file, ['Total Ingresos:', '$' . number_format($resumen['total_ingresos'], 2)]);
            fputcsv($file, ['Total Egresos:', '$' . number_format($resumen['total_egresos'], 2)]);
            fputcsv($file, ['Balance:', '$' . number_format($resumen['balance'], 2)]);
            fputcsv($file, ['Transacciones:', $resumen['total_transacciones']]);
            fputcsv($file, []);

            // Encabezados de la tabla
            fputcsv($file, [
                'Folio',
                'Tipo',
                'Fecha',
                'Referencia',
                'Método de Pago',
                'Total',
                'Observaciones'
            ]);

            // Datos de transacciones
            foreach ($transacciones as $transaccion) {
                fputcsv($file, [
                    $transaccion->folio,
                    ucfirst($transaccion->tipo),
                    $transaccion->fecha->format('d/m/Y'),
                    $transaccion->referencia_nombre,
                    $transaccion->metodoPago->nombre ?? 'N/A',
                    '$' . number_format($transaccion->total, 2),
                    $transaccion->observaciones ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function generarPDF($transacciones, $resumen)
    {
        $datos = [
            'transacciones' => $transacciones,
            'fechaFormateada' => $resumen['fecha'],
            'totalIngresos' => $resumen['total_ingresos'],
            'totalEgresos' => $resumen['total_egresos'],
            'balance' => $resumen['balance'],
            'totalTransacciones' => $resumen['total_transacciones']
        ];

        $pdf = PDF::loadView('reportes.diario', $datos);

        $filename = 'reporte_diario_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}
