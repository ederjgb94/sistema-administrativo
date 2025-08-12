<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaccion;
use App\Models\Contacto;
use App\Models\MetodoPago;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function index()
    {
        // Obtener estadísticas generales para la página principal de reportes
        $hoy = Carbon::today();
        $mesActual = Carbon::now()->startOfMonth();
        $anoActual = Carbon::now()->startOfYear();

        $estadisticas = [
            'hoy' => [
                'ingresos' => Transaccion::where('tipo', 'ingreso')->whereDate('fecha', $hoy)->sum('total'),
                'egresos' => Transaccion::where('tipo', 'egreso')->whereDate('fecha', $hoy)->sum('total'),
                'transacciones' => Transaccion::whereDate('fecha', $hoy)->count(),
            ],
            'mes' => [
                'ingresos' => Transaccion::where('tipo', 'ingreso')->where('fecha', '>=', $mesActual)->sum('total'),
                'egresos' => Transaccion::where('tipo', 'egreso')->where('fecha', '>=', $mesActual)->sum('total'),
                'transacciones' => Transaccion::where('fecha', '>=', $mesActual)->count(),
            ],
            'ano' => [
                'ingresos' => Transaccion::where('tipo', 'ingreso')->where('fecha', '>=', $anoActual)->sum('total'),
                'egresos' => Transaccion::where('tipo', 'egreso')->where('fecha', '>=', $anoActual)->sum('total'),
                'transacciones' => Transaccion::where('fecha', '>=', $anoActual)->count(),
            ],
        ];

        // Transacciones recientes para vista rápida
        $transaccionesRecientes = Transaccion::with(['contacto', 'metodoPago'])
            ->orderBy('fecha', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('reportes.index', compact('estadisticas', 'transaccionesRecientes'));
    }

    public function reporteDiario(Request $request)
    {
        $fecha = $request->get('fecha', now()->format('Y-m-d'));
        $formato = $request->get('formato', 'pdf');
        $tipoTransaccion = $request->get('tipo_transaccion');

        // Obtener transacciones del día especificado
        $fechaCarbon = Carbon::parse($fecha);
        $query = Transaccion::with(['metodoPago', 'contacto'])
            ->whereDate('fecha', $fechaCarbon);

        // Filtrar por tipo de transacción si se especifica
        if ($tipoTransaccion && in_array($tipoTransaccion, ['ingreso', 'egreso'])) {
            $query->where('tipo', $tipoTransaccion);
        }

        $transacciones = $query->orderBy('fecha', 'desc')
            ->orderBy('total', 'desc')
            ->get();

        // Calcular resumen
        $ingresos = $transacciones->where('tipo', 'ingreso')->sum('total');
        $egresos = $transacciones->where('tipo', 'egreso')->sum('total');

        $resumen = [
            'fecha' => $fechaCarbon->format('d/m/Y'),
            'total_ingresos' => $ingresos,
            'total_egresos' => $egresos,
            'balance' => $ingresos - $egresos,
            'total_transacciones' => $transacciones->count(),
        ];

        if ($formato === 'csv') {
            return $this->generarCSVDiario($transacciones, $resumen, $fechaCarbon);
        }

        return $this->generarPDFDiario($transacciones, $resumen, $fechaCarbon);
    }

    public function reportePeriodo(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));
        $formato = $request->get('formato', 'pdf');
        $tipoTransaccion = $request->get('tipo_transaccion');

        $fechaInicioCarbon = Carbon::parse($fechaInicio);
        $fechaFinCarbon = Carbon::parse($fechaFin);

        // Obtener transacciones del período
        $query = Transaccion::with(['metodoPago', 'contacto'])
            ->whereBetween('fecha', [$fechaInicioCarbon, $fechaFinCarbon]);

        // Filtrar por tipo de transacción si se especifica
        if ($tipoTransaccion && in_array($tipoTransaccion, ['ingreso', 'egreso'])) {
            $query->where('tipo', $tipoTransaccion);
        }

        $transacciones = $query->orderBy('fecha', 'desc')
            ->orderBy('total', 'desc')
            ->get();

        // Calcular resumen
        $ingresos = $transacciones->where('tipo', 'ingreso')->sum('total');
        $egresos = $transacciones->where('tipo', 'egreso')->sum('total');

        // Resumen por día
        $resumenPorDia = $transacciones->groupBy(function ($transaccion) {
            return $transaccion->fecha->format('Y-m-d');
        })->map(function ($transaccionesDia) {
            return [
                'fecha' => $transaccionesDia->first()->fecha->format('d/m/Y'),
                'ingresos' => $transaccionesDia->where('tipo', 'ingreso')->sum('total'),
                'egresos' => $transaccionesDia->where('tipo', 'egreso')->sum('total'),
                'total' => $transaccionesDia->count(),
            ];
        })->sortByDesc('fecha');

        $resumen = [
            'fecha_inicio' => $fechaInicioCarbon->format('d/m/Y'),
            'fecha_fin' => $fechaFinCarbon->format('d/m/Y'),
            'total_ingresos' => $ingresos,
            'total_egresos' => $egresos,
            'balance' => $ingresos - $egresos,
            'total_transacciones' => $transacciones->count(),
            'dias' => $resumenPorDia,
        ];

        if ($formato === 'csv') {
            return $this->generarCSVPeriodo($transacciones, $resumen);
        }

        return $this->generarPDFPeriodo($transacciones, $resumen);
    }

    public function reporteContactos(Request $request)
    {
        $formato = $request->get('formato', 'pdf');
        $tipo = $request->get('tipo', ''); // cliente, proveedor, o vacío para todos
        $tipoTransaccion = $request->get('tipo_transaccion');

        $query = Contacto::query();

        if ($tipo) {
            $query->where('tipo', $tipo);
        }

        $contactos = $query->get()->map(function ($contacto) use ($tipoTransaccion) {
            // Obtener transacciones del contacto
            $transaccionesQuery = $contacto->transacciones();

            // Filtrar por tipo de transacción si se especifica
            if ($tipoTransaccion && in_array($tipoTransaccion, ['ingreso', 'egreso'])) {
                $transaccionesQuery->where('tipo', $tipoTransaccion);
            }

            $transacciones = $transaccionesQuery->get();

            $ingresos = $transacciones->where('tipo', 'ingreso')->sum('total');
            $egresos = $transacciones->where('tipo', 'egreso')->sum('total');
            $totalTransacciones = $transacciones->count();

            $contacto->reporte_ingresos = $ingresos;
            $contacto->reporte_egresos = $egresos;
            $contacto->reporte_balance = $ingresos - $egresos;
            $contacto->reporte_total_transacciones = $totalTransacciones;

            return $contacto;
        })->filter(function ($contacto) {
            // Solo incluir contactos que tengan al menos una transacción
            return $contacto->reporte_total_transacciones > 0;
        })->sortByDesc('reporte_total_transacciones');

        $resumen = [
            'tipo' => $tipo ? ucfirst($tipo) : 'Todos',
            'tipo_transaccion' => $tipoTransaccion ? ucfirst($tipoTransaccion) : 'Todas',
            'total_contactos' => $contactos->count(),
            'total_ingresos' => $contactos->sum('reporte_ingresos'),
            'total_egresos' => $contactos->sum('reporte_egresos'),
            'total_transacciones' => $contactos->sum('reporte_total_transacciones'),
        ];

        if ($formato === 'csv') {
            return $this->generarCSVContactos($contactos, $resumen);
        }

        return $this->generarPDFContactos($contactos, $resumen);
    }

    public function reporteMetodosPago(Request $request)
    {
        $formato = $request->get('formato', 'pdf');
        $tipoTransaccion = $request->get('tipo_transaccion');

        $metodosPago = MetodoPago::all()->map(function ($metodo) use ($tipoTransaccion) {
            // Obtener transacciones del método de pago
            $transaccionesQuery = $metodo->transacciones();

            // Filtrar por tipo de transacción si se especifica
            if ($tipoTransaccion && in_array($tipoTransaccion, ['ingreso', 'egreso'])) {
                $transaccionesQuery->where('tipo', $tipoTransaccion);
            }

            $transacciones = $transaccionesQuery->get();

            $ingresos = $transacciones->where('tipo', 'ingreso')->sum('total');
            $egresos = $transacciones->where('tipo', 'egreso')->sum('total');
            $totalTransacciones = $transacciones->count();

            $metodo->reporte_ingresos = $ingresos;
            $metodo->reporte_egresos = $egresos;
            $metodo->reporte_total = $ingresos + $egresos;
            $metodo->reporte_total_transacciones = $totalTransacciones;

            return $metodo;
        })->filter(function ($metodo) {
            // Solo incluir métodos que tengan al menos una transacción
            return $metodo->reporte_total_transacciones > 0;
        })->sortByDesc('reporte_total');

        $resumen = [
            'total_metodos' => $metodosPago->count(),
            'tipo_transaccion' => $tipoTransaccion ? ucfirst($tipoTransaccion) : 'Todas',
            'total_ingresos' => $metodosPago->sum('reporte_ingresos'),
            'total_egresos' => $metodosPago->sum('reporte_egresos'),
            'total_transacciones' => $metodosPago->sum('reporte_total_transacciones'),
        ];

        if ($formato === 'csv') {
            return $this->generarCSVMetodosPago($metodosPago, $resumen);
        }

        return $this->generarPDFMetodosPago($metodosPago, $resumen);
    }

    public function reporteMensual(Request $request)
    {
        $mes = $request->get('mes', now()->month);
        $ano = $request->get('ano', now()->year);
        $formato = $request->get('formato', 'pdf');
        $tipoTransaccion = $request->get('tipo_transaccion');

        $fechaInicio = Carbon::createFromDate($ano, $mes, 1)->startOfMonth();
        $fechaFin = Carbon::createFromDate($ano, $mes, 1)->endOfMonth();

        // Nombres de meses en español
        $mesesEspanol = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        // Obtener transacciones del mes
        $query = Transaccion::with(['metodoPago', 'contacto'])
            ->whereBetween('fecha', [$fechaInicio, $fechaFin]);

        // Filtrar por tipo de transacción si se especifica
        if ($tipoTransaccion && in_array($tipoTransaccion, ['ingreso', 'egreso'])) {
            $query->where('tipo', $tipoTransaccion);
        }

        $transacciones = $query->orderBy('fecha', 'desc')
            ->orderBy('total', 'desc')
            ->get();

        // Calcular resumen
        $ingresos = $transacciones->where('tipo', 'ingreso')->sum('total');
        $egresos = $transacciones->where('tipo', 'egreso')->sum('total');

        // Resumen por día
        $resumenPorDia = $transacciones->groupBy(function ($transaccion) {
            return $transaccion->fecha->format('Y-m-d');
        })->map(function ($transaccionesDia) {
            return [
                'fecha' => $transaccionesDia->first()->fecha->format('d/m/Y'),
                'ingresos' => $transaccionesDia->where('tipo', 'ingreso')->sum('total'),
                'egresos' => $transaccionesDia->where('tipo', 'egreso')->sum('total'),
                'total' => $transaccionesDia->count(),
            ];
        })->sortBy('fecha');

        // Resumen por semana
        $resumenPorSemana = $transacciones->groupBy(function ($transaccion) {
            return $transaccion->fecha->format('W');
        })->map(function ($transaccionesSemana, $semana) use ($ano) {
            $primerDia = Carbon::now()->setISODate($ano, $semana)->startOfWeek();
            $ultimoDia = Carbon::now()->setISODate($ano, $semana)->endOfWeek();

            return [
                'semana' => $semana,
                'periodo' => $primerDia->format('d/m') . ' - ' . $ultimoDia->format('d/m'),
                'ingresos' => $transaccionesSemana->where('tipo', 'ingreso')->sum('total'),
                'egresos' => $transaccionesSemana->where('tipo', 'egreso')->sum('total'),
                'total' => $transaccionesSemana->count(),
            ];
        })->sortBy('semana');

        $resumen = [
            'mes' => $mesesEspanol[$mes],
            'ano' => $ano,
            'mes_completo' => $mesesEspanol[$mes] . ' ' . $ano,
            'total_ingresos' => $ingresos,
            'total_egresos' => $egresos,
            'balance' => $ingresos - $egresos,
            'total_transacciones' => $transacciones->count(),
            'dias' => $resumenPorDia,
            'semanas' => $resumenPorSemana,
        ];

        if ($formato === 'csv') {
            return $this->generarCSVMensual($transacciones, $resumen);
        }

        return $this->generarPDFMensual($transacciones, $resumen);
    }

    public function reporteContacto(Request $request)
    {
        $contactoId = $request->get('contacto_id');
        $formato = $request->get('formato', 'pdf');
        $tipoTransaccion = $request->get('tipo_transaccion');
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');

        if (!$contactoId) {
            return back()->with('error', 'Debe seleccionar un contacto para generar el reporte.');
        }

        $contacto = Contacto::findOrFail($contactoId);

        // Obtener transacciones del contacto
        $query = $contacto->transacciones()
            ->with(['metodoPago']);

        // Filtrar por tipo de transacción si se especifica
        if ($tipoTransaccion && in_array($tipoTransaccion, ['ingreso', 'egreso'])) {
            $query->where('tipo', $tipoTransaccion);
        }

        // Filtrar por rango de fechas si se especifica
        if ($fechaInicio && $fechaFin) {
            $fechaInicioCarbon = Carbon::parse($fechaInicio);
            $fechaFinCarbon = Carbon::parse($fechaFin);
            $query->whereBetween('fecha', [$fechaInicioCarbon, $fechaFinCarbon]);
        } elseif ($fechaInicio) {
            $fechaInicioCarbon = Carbon::parse($fechaInicio);
            $query->where('fecha', '>=', $fechaInicioCarbon);
        } elseif ($fechaFin) {
            $fechaFinCarbon = Carbon::parse($fechaFin);
            $query->where('fecha', '<=', $fechaFinCarbon);
        }

        $transacciones = $query->orderBy('fecha', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($transacciones->isEmpty()) {
            $mensaje = 'El contacto seleccionado no tiene';

            if ($tipoTransaccion) {
                $mensaje .= " {$tipoTransaccion}s";
            } else {
                $mensaje .= ' transacciones';
            }

            if ($fechaInicio && $fechaFin) {
                $fechaInicioFormat = Carbon::parse($fechaInicio)->format('d/m/Y');
                $fechaFinFormat = Carbon::parse($fechaFin)->format('d/m/Y');
                $mensaje .= " en el período del {$fechaInicioFormat} al {$fechaFinFormat}";
            } elseif ($fechaInicio) {
                $fechaInicioFormat = Carbon::parse($fechaInicio)->format('d/m/Y');
                $mensaje .= " desde el {$fechaInicioFormat}";
            } elseif ($fechaFin) {
                $fechaFinFormat = Carbon::parse($fechaFin)->format('d/m/Y');
                $mensaje .= " hasta el {$fechaFinFormat}";
            } else {
                $mensaje .= ' registradas';
            }

            $mensaje .= '.';

            // Crear resumen vacío pero con mensaje informativo
            $resumen = [
                'contacto' => $contacto,
                'total_ingresos' => 0,
                'total_egresos' => 0,
                'balance' => 0,
                'total_transacciones' => 0,
                'meses' => collect([]),
                'metodos_pago' => collect([]),
                'metodos' => collect([]),
                'primera_transaccion' => null,
                'ultima_transaccion' => null,
                'mensaje_vacio' => $mensaje
            ];

            // Agregar información de filtros aplicados
            if ($tipoTransaccion) {
                $resumen['tipo_transaccion'] = $tipoTransaccion;
            }

            if ($fechaInicio && $fechaFin) {
                $resumen['periodo'] = "del " . Carbon::parse($fechaInicio)->format('d/m/Y') . " al " . Carbon::parse($fechaFin)->format('d/m/Y');
            } elseif ($fechaInicio) {
                $resumen['periodo'] = "desde el " . Carbon::parse($fechaInicio)->format('d/m/Y');
            } elseif ($fechaFin) {
                $resumen['periodo'] = "hasta el " . Carbon::parse($fechaFin)->format('d/m/Y');
            }

            if ($formato === 'csv') {
                return $this->generarCSVContacto($transacciones, $resumen);
            }

            return $this->generarPDFContacto($transacciones, $resumen);
        }        // Calcular estadísticas
        $ingresos = $transacciones->where('tipo', 'ingreso')->sum('total');
        $egresos = $transacciones->where('tipo', 'egreso')->sum('total');

        // Resumen por mes
        $resumenPorMes = $transacciones->groupBy(function ($transaccion) {
            return $transaccion->fecha->format('Y-m');
        })->map(function ($transaccionesMes, $mesKey) {
            $fecha = Carbon::createFromFormat('Y-m', $mesKey);
            $mesesEspanol = [
                1 => 'Enero',
                2 => 'Febrero',
                3 => 'Marzo',
                4 => 'Abril',
                5 => 'Mayo',
                6 => 'Junio',
                7 => 'Julio',
                8 => 'Agosto',
                9 => 'Septiembre',
                10 => 'Octubre',
                11 => 'Noviembre',
                12 => 'Diciembre'
            ];
            return [
                'mes' => $mesesEspanol[$fecha->month] . ' ' . $fecha->year,
                'ingresos' => $transaccionesMes->where('tipo', 'ingreso')->sum('total'),
                'egresos' => $transaccionesMes->where('tipo', 'egreso')->sum('total'),
                'total' => $transaccionesMes->count(),
            ];
        })->sortByDesc(function ($item, $key) {
            return $key;
        });

        // Resumen por método de pago
        $resumenPorMetodo = $transacciones->groupBy('metodo_pago_id')->map(function ($transaccionesMetodo) {
            $metodo = $transaccionesMetodo->first()->metodoPago;
            return [
                'metodo' => $metodo ? $metodo->nombre : 'Sin método',
                'ingresos' => $transaccionesMetodo->where('tipo', 'ingreso')->sum('total'),
                'egresos' => $transaccionesMetodo->where('tipo', 'egreso')->sum('total'),
                'total' => $transaccionesMetodo->count(),
            ];
        })->sortByDesc('total');

        $resumen = [
            'contacto' => $contacto,
            'total_ingresos' => $ingresos,
            'total_egresos' => $egresos,
            'balance' => $ingresos - $egresos,
            'total_transacciones' => $transacciones->count(),
            'primera_transaccion' => $transacciones->sortBy('fecha')->first()?->fecha,
            'ultima_transaccion' => $transacciones->sortByDesc('fecha')->first()?->fecha,
            'fecha_inicio' => $fechaInicio ? Carbon::parse($fechaInicio)->format('d/m/Y') : null,
            'fecha_fin' => $fechaFin ? Carbon::parse($fechaFin)->format('d/m/Y') : null,
            'meses' => $resumenPorMes,
            'metodos' => $resumenPorMetodo,
        ];

        if ($formato === 'csv') {
            return $this->generarCSVContacto($transacciones, $resumen);
        }

        return $this->generarPDFContacto($transacciones, $resumen);
    }    // Métodos privados para generar CSV

    private function generarCSVDiario($transacciones, $resumen, $fecha)
    {
        $tipoTransaccion = request()->get('tipo_transaccion');
        $sufijo = $tipoTransaccion ? '_' . $tipoTransaccion : '';
        $filename = 'reporte_diario_' . $fecha->format('Y-m-d') . $sufijo . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($transacciones, $resumen, $tipoTransaccion) {
            $file = fopen('php://output', 'w');

            // Escribir BOM para UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Encabezado del reporte
            fputcsv($file, ['REPORTE DIARIO DE TRANSACCIONES']);
            fputcsv($file, ['Fecha:', $resumen['fecha']]);
            if ($tipoTransaccion) {
                fputcsv($file, ['Tipo de Transacción:', ucfirst($tipoTransaccion)]);
            }
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
                'Contacto',
                'Descripción',
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
                    $transaccion->contacto->nombre ?? 'Sin contacto',
                    $transaccion->descripcion ?? '',
                    $transaccion->metodoPago->nombre ?? 'N/A',
                    '$' . number_format($transaccion->total, 2),
                    $transaccion->observaciones ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function generarCSVPeriodo($transacciones, $resumen)
    {
        $tipoTransaccion = request()->get('tipo_transaccion');
        $sufijo = $tipoTransaccion ? '_' . $tipoTransaccion : '';
        $filename = 'reporte_periodo_' . str_replace('/', '-', $resumen['fecha_inicio']) . '_' . str_replace('/', '-', $resumen['fecha_fin']) . $sufijo . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($transacciones, $resumen) {
            $tipoTransaccion = request()->get('tipo_transaccion');
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['REPORTE DE PERÍODO']);
            fputcsv($file, ['Desde:', $resumen['fecha_inicio']]);
            fputcsv($file, ['Hasta:', $resumen['fecha_fin']]);
            if ($tipoTransaccion) {
                fputcsv($file, ['Tipo de Transacción:', ucfirst($tipoTransaccion)]);
            }
            fputcsv($file, []);

            fputcsv($file, ['RESUMEN GENERAL']);
            fputcsv($file, ['Total Ingresos:', '$' . number_format($resumen['total_ingresos'], 2)]);
            fputcsv($file, ['Total Egresos:', '$' . number_format($resumen['total_egresos'], 2)]);
            fputcsv($file, ['Balance:', '$' . number_format($resumen['balance'], 2)]);
            fputcsv($file, ['Transacciones:', $resumen['total_transacciones']]);
            fputcsv($file, []);

            fputcsv($file, ['RESUMEN POR DÍA']);
            fputcsv($file, ['Fecha', 'Ingresos', 'Egresos', 'Balance', 'Transacciones']);
            foreach ($resumen['dias'] as $dia) {
                $balance = $dia['ingresos'] - $dia['egresos'];
                fputcsv($file, [
                    $dia['fecha'],
                    '$' . number_format($dia['ingresos'], 2),
                    '$' . number_format($dia['egresos'], 2),
                    '$' . number_format($balance, 2),
                    $dia['total']
                ]);
            }
            fputcsv($file, []);

            fputcsv($file, ['DETALLE DE TRANSACCIONES']);
            fputcsv($file, [
                'Folio',
                'Tipo',
                'Fecha',
                'Contacto',
                'Descripción',
                'Método de Pago',
                'Total',
                'Observaciones'
            ]);

            foreach ($transacciones as $transaccion) {
                fputcsv($file, [
                    $transaccion->folio,
                    ucfirst($transaccion->tipo),
                    $transaccion->fecha->format('d/m/Y'),
                    $transaccion->contacto->nombre ?? 'Sin contacto',
                    $transaccion->descripcion ?? '',
                    $transaccion->metodoPago->nombre ?? 'N/A',
                    '$' . number_format($transaccion->total, 2),
                    $transaccion->observaciones ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function generarCSVContactos($contactos, $resumen)
    {
        $sufijo = '';
        if (isset($resumen['tipo_transaccion']) && $resumen['tipo_transaccion'] !== 'Todas') {
            $sufijo = '_' . strtolower($resumen['tipo_transaccion']);
        }
        $filename = 'reporte_contactos_' . strtolower($resumen['tipo']) . $sufijo . '_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($contactos, $resumen) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['REPORTE DE CONTACTOS']);
            fputcsv($file, ['Tipo:', $resumen['tipo']]);
            if (isset($resumen['tipo_transaccion'])) {
                fputcsv($file, ['Transacciones:', $resumen['tipo_transaccion']]);
            }
            fputcsv($file, ['Fecha Generación:', now()->format('d/m/Y H:i:s')]);
            fputcsv($file, []);

            fputcsv($file, ['RESUMEN GENERAL']);
            fputcsv($file, ['Total Contactos:', $resumen['total_contactos']]);
            fputcsv($file, ['Total Ingresos:', '$' . number_format($resumen['total_ingresos'], 2)]);
            fputcsv($file, ['Total Egresos:', '$' . number_format($resumen['total_egresos'], 2)]);
            fputcsv($file, ['Total Transacciones:', $resumen['total_transacciones']]);
            fputcsv($file, []);

            fputcsv($file, ['DETALLE POR CONTACTO']);
            fputcsv($file, [
                'Nombre',
                'Tipo',
                'Email',
                'Teléfono',
                'Total Ingresos',
                'Total Egresos',
                'Balance',
                'Transacciones',
                'Estado'
            ]);

            foreach ($contactos as $contacto) {
                fputcsv($file, [
                    $contacto->nombre,
                    ucfirst($contacto->tipo),
                    $contacto->email ?? '',
                    $contacto->telefono ?? '',
                    '$' . number_format($contacto->reporte_ingresos, 2),
                    '$' . number_format($contacto->reporte_egresos, 2),
                    '$' . number_format($contacto->reporte_balance, 2),
                    $contacto->reporte_total_transacciones,
                    $contacto->activo ? 'Activo' : 'Inactivo'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function generarCSVMetodosPago($metodosPago, $resumen)
    {
        $sufijo = '';
        if (isset($resumen['tipo_transaccion']) && $resumen['tipo_transaccion'] !== 'Todas') {
            $sufijo = '_' . strtolower($resumen['tipo_transaccion']);
        }
        $filename = 'reporte_metodos_pago' . $sufijo . '_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($metodosPago, $resumen) {
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['REPORTE DE MÉTODOS DE PAGO']);
            if (isset($resumen['tipo_transaccion'])) {
                fputcsv($file, ['Transacciones:', $resumen['tipo_transaccion']]);
            }
            fputcsv($file, ['Fecha Generación:', now()->format('d/m/Y H:i:s')]);
            fputcsv($file, []);

            fputcsv($file, ['RESUMEN GENERAL']);
            fputcsv($file, ['Total Métodos:', $resumen['total_metodos']]);
            fputcsv($file, ['Total Ingresos:', '$' . number_format($resumen['total_ingresos'], 2)]);
            fputcsv($file, ['Total Egresos:', '$' . number_format($resumen['total_egresos'], 2)]);
            fputcsv($file, ['Total Transacciones:', $resumen['total_transacciones']]);
            fputcsv($file, []);

            fputcsv($file, ['DETALLE POR MÉTODO']);
            fputcsv($file, [
                'Método de Pago',
                'Total Ingresos',
                'Total Egresos',
                'Monto Total',
                'Transacciones',
                'Estado'
            ]);

            foreach ($metodosPago as $metodo) {
                fputcsv($file, [
                    $metodo->nombre,
                    '$' . number_format($metodo->reporte_ingresos, 2),
                    '$' . number_format($metodo->reporte_egresos, 2),
                    '$' . number_format($metodo->reporte_total, 2),
                    $metodo->reporte_total_transacciones,
                    $metodo->activo ? 'Activo' : 'Inactivo'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Métodos privados para generar PDF

    private function generarPDFDiario($transacciones, $resumen, $fecha)
    {
        $tipoTransaccion = request()->get('tipo_transaccion');

        // Estimar número de páginas basado en transacciones de manera más precisa
        $transaccionesPorPagina = 8; // Más conservador: ~8 transacciones por página
        $paginasEstimadas = max(1, ceil($transacciones->count() / $transaccionesPorPagina));

        $datos = [
            'transacciones' => $transacciones,
            'fechaFormateada' => $resumen['fecha'],
            'totalIngresos' => $resumen['total_ingresos'],
            'totalEgresos' => $resumen['total_egresos'],
            'balance' => $resumen['balance'],
            'totalTransacciones' => $resumen['total_transacciones'],
            'tipoTransaccion' => $tipoTransaccion,
            'totalPaginas' => $paginasEstimadas
        ];

        $pdf = PDF::loadView('reportes.diario', $datos);
        $sufijo = $tipoTransaccion ? '_' . $tipoTransaccion : '';
        $filename = 'reporte_diario_' . $fecha->format('Y-m-d') . $sufijo . '.pdf';

        return $pdf->download($filename);
    }

    private function generarPDFPeriodo($transacciones, $resumen)
    {
        $tipoTransaccion = request()->get('tipo_transaccion');

        // Estimar número de páginas basado en transacciones de manera más precisa
        $transaccionesPorPagina = 8; // Más conservador: ~8 transacciones por página
        $paginasEstimadas = max(1, ceil($transacciones->count() / $transaccionesPorPagina));

        $datos = [
            'transacciones' => $transacciones,
            'resumen' => $resumen,
            'tipoTransaccion' => $tipoTransaccion,
            'totalPaginas' => $paginasEstimadas
        ];

        $pdf = PDF::loadView('reportes.periodo', $datos);
        $sufijo = $tipoTransaccion ? '_' . $tipoTransaccion : '';
        $filename = 'reporte_periodo_' . str_replace('/', '-', $resumen['fecha_inicio']) . '_' . str_replace('/', '-', $resumen['fecha_fin']) . $sufijo . '.pdf';

        return $pdf->download($filename);
    }

    private function generarPDFContactos($contactos, $resumen)
    {
        // Estimar número de páginas basado en contactos
        $contactosPorPagina = 6; // Aproximadamente 6 contactos por página
        $paginasEstimadas = max(1, ceil($contactos->count() / $contactosPorPagina));

        $datos = [
            'contactos' => $contactos,
            'resumen' => $resumen,
            'totalPaginas' => $paginasEstimadas
        ];

        $pdf = PDF::loadView('reportes.contactos', $datos);
        $sufijo = '';
        if (isset($resumen['tipo_transaccion']) && $resumen['tipo_transaccion'] !== 'Todas') {
            $sufijo = '_' . strtolower($resumen['tipo_transaccion']);
        }
        $filename = 'reporte_contactos_' . strtolower($resumen['tipo']) . $sufijo . '_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    private function generarPDFMetodosPago($metodosPago, $resumen)
    {
        // Estimar número de páginas basado en métodos de pago
        $metodosPorPagina = 5; // Aproximadamente 5 métodos de pago por página
        $paginasEstimadas = max(1, ceil($metodosPago->count() / $metodosPorPagina));

        $datos = [
            'metodosPago' => $metodosPago,
            'resumen' => $resumen,
            'totalPaginas' => $paginasEstimadas
        ];

        $pdf = PDF::loadView('reportes.metodos-pago', $datos);
        $sufijo = '';
        if (isset($resumen['tipo_transaccion']) && $resumen['tipo_transaccion'] !== 'Todas') {
            $sufijo = '_' . strtolower($resumen['tipo_transaccion']);
        }
        $filename = 'reporte_metodos_pago' . $sufijo . '_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    private function generarCSVMensual($transacciones, $resumen)
    {
        $tipoTransaccion = request()->get('tipo_transaccion');
        $sufijo = $tipoTransaccion ? '_' . $tipoTransaccion : '';
        $filename = 'reporte_mensual_' . strtolower($resumen['mes']) . '_' . $resumen['ano'] . $sufijo . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($transacciones, $resumen) {
            $tipoTransaccion = request()->get('tipo_transaccion');
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['REPORTE MENSUAL']);
            fputcsv($file, ['Mes:', $resumen['mes_completo']]);
            if ($tipoTransaccion) {
                fputcsv($file, ['Tipo de Transacción:', ucfirst($tipoTransaccion)]);
            }
            fputcsv($file, []);

            fputcsv($file, ['RESUMEN GENERAL']);
            fputcsv($file, ['Total Ingresos:', '$' . number_format($resumen['total_ingresos'], 2)]);
            fputcsv($file, ['Total Egresos:', '$' . number_format($resumen['total_egresos'], 2)]);
            fputcsv($file, ['Balance:', '$' . number_format($resumen['balance'], 2)]);
            fputcsv($file, ['Transacciones:', $resumen['total_transacciones']]);
            fputcsv($file, []);

            fputcsv($file, ['RESUMEN POR DÍA']);
            fputcsv($file, ['Fecha', 'Ingresos', 'Egresos', 'Balance', 'Transacciones']);
            foreach ($resumen['dias'] as $dia) {
                $balance = $dia['ingresos'] - $dia['egresos'];
                fputcsv($file, [
                    $dia['fecha'],
                    '$' . number_format($dia['ingresos'], 2),
                    '$' . number_format($dia['egresos'], 2),
                    '$' . number_format($balance, 2),
                    $dia['total']
                ]);
            }
            fputcsv($file, []);

            fputcsv($file, ['RESUMEN POR SEMANA']);
            fputcsv($file, ['Semana', 'Período', 'Ingresos', 'Egresos', 'Balance', 'Transacciones']);
            foreach ($resumen['semanas'] as $semana) {
                $balance = $semana['ingresos'] - $semana['egresos'];
                fputcsv($file, [
                    'Semana ' . $semana['semana'],
                    $semana['periodo'],
                    '$' . number_format($semana['ingresos'], 2),
                    '$' . number_format($semana['egresos'], 2),
                    '$' . number_format($balance, 2),
                    $semana['total']
                ]);
            }
            fputcsv($file, []);

            fputcsv($file, ['DETALLE DE TRANSACCIONES']);
            fputcsv($file, [
                'Folio',
                'Tipo',
                'Fecha',
                'Contacto',
                'Descripción',
                'Método de Pago',
                'Total',
                'Observaciones'
            ]);

            foreach ($transacciones as $transaccion) {
                fputcsv($file, [
                    $transaccion->folio,
                    ucfirst($transaccion->tipo),
                    $transaccion->fecha->format('d/m/Y'),
                    $transaccion->contacto->nombre ?? 'Sin contacto',
                    $transaccion->descripcion ?? '',
                    $transaccion->metodoPago->nombre ?? 'N/A',
                    '$' . number_format($transaccion->total, 2),
                    $transaccion->observaciones ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function generarCSVContacto($transacciones, $resumen)
    {
        $tipoTransaccion = request()->get('tipo_transaccion');
        $fechaInicio = request()->get('fecha_inicio');
        $fechaFin = request()->get('fecha_fin');

        $sufijo = $tipoTransaccion ? '_' . $tipoTransaccion : '';

        if ($fechaInicio && $fechaFin) {
            $sufijo .= '_' . str_replace('/', '-', Carbon::parse($fechaInicio)->format('d-m-Y')) . '_' . str_replace('/', '-', Carbon::parse($fechaFin)->format('d-m-Y'));
        } elseif ($fechaInicio) {
            $sufijo .= '_desde_' . str_replace('/', '-', Carbon::parse($fechaInicio)->format('d-m-Y'));
        } elseif ($fechaFin) {
            $sufijo .= '_hasta_' . str_replace('/', '-', Carbon::parse($fechaFin)->format('d-m-Y'));
        }

        $filename = 'reporte_contacto_' . str_replace(' ', '_', strtolower($resumen['contacto']->nombre)) . $sufijo . '_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($transacciones, $resumen) {
            $tipoTransaccion = request()->get('tipo_transaccion');
            $file = fopen('php://output', 'w');

            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, ['REPORTE POR CONTACTO']);
            fputcsv($file, ['Contacto:', $resumen['contacto']->nombre]);
            fputcsv($file, ['Tipo:', ucfirst($resumen['contacto']->tipo)]);
            fputcsv($file, ['Email:', $resumen['contacto']->email ?? 'N/A']);
            fputcsv($file, ['Teléfono:', $resumen['contacto']->telefono ?? 'N/A']);
            if ($tipoTransaccion) {
                fputcsv($file, ['Tipo de Transacción:', ucfirst($tipoTransaccion)]);
            }
            if (isset($resumen['periodo'])) {
                fputcsv($file, ['Período:', $resumen['periodo']]);
            }
            fputcsv($file, ['Generado el:', now()->format('d/m/Y H:i:s')]);
            fputcsv($file, []);

            // Mensaje informativo si no hay transacciones
            if (isset($resumen['mensaje_vacio'])) {
                fputcsv($file, ['INFORMACIÓN']);
                fputcsv($file, [$resumen['mensaje_vacio']]);
                fputcsv($file, []);
            }

            fputcsv($file, ['RESUMEN GENERAL']);
            fputcsv($file, ['Total Ingresos:', '$' . number_format($resumen['total_ingresos'], 2)]);
            fputcsv($file, ['Total Egresos:', '$' . number_format($resumen['total_egresos'], 2)]);
            fputcsv($file, ['Balance:', '$' . number_format($resumen['balance'], 2)]);
            fputcsv($file, ['Total Transacciones:', $resumen['total_transacciones']]);
            fputcsv($file, ['Primera Transacción:', $resumen['primera_transaccion']?->format('d/m/Y') ?? 'N/A']);
            fputcsv($file, ['Última Transacción:', $resumen['ultima_transaccion']?->format('d/m/Y') ?? 'N/A']);
            fputcsv($file, []);

            // Solo mostrar resúmenes si hay datos
            if ($resumen['meses']->count() > 0) {
                fputcsv($file, ['RESUMEN POR MES']);
                fputcsv($file, ['Mes', 'Ingresos', 'Egresos', 'Balance', 'Transacciones']);
                foreach ($resumen['meses'] as $mes) {
                    $balance = $mes['ingresos'] - $mes['egresos'];
                    fputcsv($file, [
                        $mes['mes'],
                        '$' . number_format($mes['ingresos'], 2),
                        '$' . number_format($mes['egresos'], 2),
                        '$' . number_format($balance, 2),
                        $mes['total']
                    ]);
                }
                fputcsv($file, []);
            }

            if ($resumen['metodos_pago']->count() > 0) {
                fputcsv($file, ['RESUMEN POR MÉTODO DE PAGO']);
                fputcsv($file, ['Método', 'Ingresos', 'Egresos', 'Total', 'Transacciones']);
                foreach ($resumen['metodos_pago'] as $metodo) {
                    fputcsv($file, [
                        $metodo['metodo'],
                        '$' . number_format($metodo['ingresos'], 2),
                        '$' . number_format($metodo['egresos'], 2),
                        '$' . number_format($metodo['ingresos'] + $metodo['egresos'], 2),
                        $metodo['total']
                    ]);
                }
                fputcsv($file, []);
            }

            // Solo mostrar detalle si hay transacciones
            if ($transacciones->count() > 0) {
                fputcsv($file, ['DETALLE DE TRANSACCIONES']);
                fputcsv($file, [
                    'Folio',
                    'Tipo',
                    'Fecha',
                    'Descripción',
                    'Método de Pago',
                    'Total',
                    'Observaciones'
                ]);

                foreach ($transacciones as $transaccion) {
                    fputcsv($file, [
                        $transaccion->folio,
                        ucfirst($transaccion->tipo),
                        $transaccion->fecha->format('d/m/Y'),
                        $transaccion->descripcion ?? '',
                        $transaccion->metodoPago->nombre ?? 'N/A',
                        '$' . number_format($transaccion->total, 2),
                        $transaccion->observaciones ?? ''
                    ]);
                }
            } else {
                fputcsv($file, ['DETALLE DE TRANSACCIONES']);
                fputcsv($file, ['No se encontraron transacciones con los filtros aplicados.']);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function generarPDFMensual($transacciones, $resumen)
    {
        $tipoTransaccion = request()->get('tipo_transaccion');

        // Estimar número de páginas basado en transacciones de manera más precisa
        $transaccionesPorPagina = 8; // Más conservador: ~8 transacciones por página
        $paginasEstimadas = max(1, ceil($transacciones->count() / $transaccionesPorPagina));

        $datos = [
            'transacciones' => $transacciones,
            'resumen' => $resumen,
            'tipoTransaccion' => $tipoTransaccion,
            'totalPaginas' => $paginasEstimadas
        ];

        $pdf = PDF::loadView('reportes.mensual', $datos);
        $sufijo = $tipoTransaccion ? '_' . $tipoTransaccion : '';
        $filename = 'reporte_mensual_' . strtolower($resumen['mes']) . '_' . $resumen['ano'] . $sufijo . '.pdf';

        return $pdf->download($filename);
    }

    private function generarPDFContacto($transacciones, $resumen)
    {
        $tipoTransaccion = request()->get('tipo_transaccion');
        $fechaInicio = request()->get('fecha_inicio');
        $fechaFin = request()->get('fecha_fin');

        // Estimar número de páginas basado en transacciones de manera más precisa
        $transaccionesPorPagina = 8; // Más conservador: ~8 transacciones por página
        $paginasEstimadas = max(1, ceil($transacciones->count() / $transaccionesPorPagina));

        $datos = [
            'transacciones' => $transacciones,
            'resumen' => $resumen,
            'tipoTransaccion' => $tipoTransaccion,
            'totalPaginas' => $paginasEstimadas
        ];

        $pdf = PDF::loadView('reportes.contacto', $datos);

        $sufijo = $tipoTransaccion ? '_' . $tipoTransaccion : '';

        if ($fechaInicio && $fechaFin) {
            $sufijo .= '_' . str_replace('/', '-', Carbon::parse($fechaInicio)->format('d-m-Y')) . '_' . str_replace('/', '-', Carbon::parse($fechaFin)->format('d-m-Y'));
        } elseif ($fechaInicio) {
            $sufijo .= '_desde_' . str_replace('/', '-', Carbon::parse($fechaInicio)->format('d-m-Y'));
        } elseif ($fechaFin) {
            $sufijo .= '_hasta_' . str_replace('/', '-', Carbon::parse($fechaFin)->format('d-m-Y'));
        }

        $filename = 'reporte_contacto_' . str_replace(' ', '_', strtolower($resumen['contacto']->nombre)) . $sufijo . '_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}
