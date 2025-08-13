<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Mensual - {{ $resumen['mes_completo'] }}</title>
    <style>
        @page {
            margin: 150px 50px 120px 50px;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            position: fixed;
            top: -120px;
            left: 0;
            right: 0;
            height: 100px;
            display: table;
            width: 100%;
            border-bottom: 2px solid #3B82F6;
            padding-bottom: 20px;
        }
        
        .header-content {
            display: table-row;
        }
        
        .header-left {
            display: table-cell;
            width: 200px;
            vertical-align: middle;
        }
        
        .header-right {
            display: table-cell;
            text-align: right;
            vertical-align: middle;
        }
        
        .fecha-reporte {
            font-size: 14px;
            font-weight: bold;
            color: #1F2937;
        }
        
        .fecha-generacion {
            font-size: 10px;
            color: #6B7280;
            margin-top: 5px;
        }
        .summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .summary-item strong {
            color: #333;
        }
        .amount-positive {
            color: #28a745;
            font-weight: bold;
        }
        .amount-negative {
            color: #dc3545;
            font-weight: bold;
        }
        .amount-neutral {
            color: #333;
            font-weight: bold;
        }
        .section {
            margin-bottom: 25px;
        }
        .section h3 {
            margin: 0 0 15px 0;
            font-size: 14px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }
        td {
            padding: 6px 8px;
            font-size: 11px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-ingreso {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-egreso {
            background-color: #f8d7da;
            color: #721c24;
        }
        /* Footer con numeración mejorada */
        .footer {
            position: fixed;
            bottom: -100px;
            left: 0;
            right: 0;
            height: 80px;
            display: table;
            width: 100%;
            border-top: 1px solid #e5e5e5;
            padding-top: 20px;
        }
        
        .footer-content {
            display: table-row;
        }
        
        .footer-left {
            display: table-cell;
            width: 33%;
            vertical-align: middle;
            text-align: left;
            font-size: 10px;
            color: #666;
        }
        
        .footer-center {
            display: table-cell;
            width: 34%;
            vertical-align: middle;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .footer-right {
            display: table-cell;
            width: 33%;
            vertical-align: middle;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        
        /* Numeración de páginas estilo DomPDF */
        .page-number {
            position: fixed;
            bottom: 20px;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        /* DomPDF page counters mejorados */
        .pagenum:before {
            content: counter(page);
        }
        
        .pagecount:after {
            content: counter(pages);
        }
        
        .page-number {
            position: fixed;
            bottom: -110px;
            right: 0;
            font-size: 10px;
            color: #6B7280;
        }
    </style>
</head>
<body>
    <!-- Número de página con script mejorado -->
    <script type="text/php">
        if (isset($pdf)) {
            $font = $fontMetrics->get_font("helvetica");
            $size = 10;
            $y = 20;
            $x = 300;
            
            // Usar el total estimado del controlador con fallback seguro
            $totalPaginas = isset($totalPaginas) ? $totalPaginas : 1;
            if ($PAGE_COUNT > 0) {
                $totalPaginas = $PAGE_COUNT;
            }
            $text = $PAGE_NUM . " de " . $totalPaginas;
            
            $pdf->text($x, $y, $text, $font, $size);
        }
    </script>

    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <!-- Espacio para logo -->
            </div>
            <div class="header-right">
                <div class="fecha-reporte">{{ $resumen['mes_completo'] }}</div>
                <div class="fecha-generacion">Generado: {{ now()->format('d/m/Y H:i:s') }}</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-content">
            <div class="footer-left">
                <!-- Sistema Administrativo -->
            </div>
            <div class="footer-center">
                <!-- Numeración de páginas HTML + CSS -->
                <div class="page-number">
                    <span class="pagenum"></span> de {{ isset($totalPaginas) ? $totalPaginas : 1 }}
                </div>
            </div>
            <div class="footer-right">
                <!-- Información del mes -->
            </div>
        </div>
    </div>

    <div class="header">
        <h1>REPORTE MENSUAL</h1>
        <h2>{{ $resumen['mes_completo'] }}</h2>
        <p>Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <h3>RESUMEN FINANCIERO</h3>
        <div class="summary-grid">
            <div>
                <div class="summary-item">
                    <span>Total de Ingresos:</span>
                    <span class="amount-positive">${{ number_format($resumen['total_ingresos'], 2) }}</span>
                </div>
                <div class="summary-item">
                    <span>Total de Egresos:</span>
                    <span class="amount-negative">${{ number_format($resumen['total_egresos'], 2) }}</span>
                </div>
            </div>
            <div>
                <div class="summary-item">
                    <span>Balance del Mes:</span>
                    <span class="{{ $resumen['balance'] >= 0 ? 'amount-positive' : 'amount-negative' }}">
                        ${{ number_format($resumen['balance'], 2) }}
                    </span>
                </div>
                <div class="summary-item">
                    <span>Total de Transacciones:</span>
                    <span class="amount-neutral">{{ $resumen['total_transacciones'] }}</span>
                </div>
            </div>
        </div>
    </div>

    @if($resumen['dias']->count() > 0)
    <div class="section">
        <h3>RESUMEN POR DÍA</h3>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th class="text-right">Ingresos</th>
                    <th class="text-right">Egresos</th>
                    <th class="text-right">Balance</th>
                    <th class="text-center">Transacciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resumen['dias'] as $dia)
                @php $balance = $dia['ingresos'] - $dia['egresos']; @endphp
                <tr>
                    <td>{{ $dia['fecha'] }}</td>
                    <td class="text-right amount-positive">${{ number_format($dia['ingresos'], 2) }}</td>
                    <td class="text-right amount-negative">${{ number_format($dia['egresos'], 2) }}</td>
                    <td class="text-right {{ $balance >= 0 ? 'amount-positive' : 'amount-negative' }}">
                        ${{ number_format($balance, 2) }}
                    </td>
                    <td class="text-center">{{ $dia['total'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($resumen['semanas']->count() > 0)
    <div class="section">
        <h3>RESUMEN POR SEMANA</h3>
        <table>
            <thead>
                <tr>
                    <th>Semana</th>
                    <th>Período</th>
                    <th class="text-right">Ingresos</th>
                    <th class="text-right">Egresos</th>
                    <th class="text-right">Balance</th>
                    <th class="text-center">Transacciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resumen['semanas'] as $semana)
                @php $balance = $semana['ingresos'] - $semana['egresos']; @endphp
                <tr>
                    <td>Semana {{ $semana['semana'] }}</td>
                    <td>{{ $semana['periodo'] }}</td>
                    <td class="text-right amount-positive">${{ number_format($semana['ingresos'], 2) }}</td>
                    <td class="text-right amount-negative">${{ number_format($semana['egresos'], 2) }}</td>
                    <td class="text-right {{ $balance >= 0 ? 'amount-positive' : 'amount-negative' }}">
                        ${{ number_format($balance, 2) }}
                    </td>
                    <td class="text-center">{{ $semana['total'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($transacciones->count() > 0)
    <div class="section">
        <h3>DETALLE DE TRANSACCIONES</h3>
        <table>
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Tipo</th>
                    <th>Fecha</th>
                    <th>Contacto</th>
                    <th>Descripción</th>
                    <th>Método</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transacciones as $transaccion)
                <tr>
                    <td>{{ $transaccion->folio }}</td>
                    <td>
                        <span class="badge {{ $transaccion->tipo === 'ingreso' ? 'badge-ingreso' : 'badge-egreso' }}">
                            {{ ucfirst($transaccion->tipo) }}
                        </span>
                    </td>
                    <td>{{ $transaccion->fecha->format('d/m/Y') }}</td>
                    <td>{{ $transaccion->contacto->nombre ?? 'Sin contacto' }}</td>
                    <td>{{ Str::limit($transaccion->descripcion ?? '', 30) }}</td>
                    <td>{{ $transaccion->metodoPago->nombre ?? 'N/A' }}</td>
                    <td class="text-right {{ $transaccion->tipo === 'ingreso' ? 'amount-positive' : 'amount-negative' }}">
                        ${{ number_format($transaccion->total, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Footer final del contenido -->
    <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 15px;">
        <p>Sistema Administrativo - Reporte generado automáticamente</p>
    </div>
</body>
</html>
