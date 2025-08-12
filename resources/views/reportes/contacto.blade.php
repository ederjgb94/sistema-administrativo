<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte por Contacto - {{ $resumen['contacto']->nombre }}</title>
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
        
    /* Estilos adicionales */
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
        .contacto-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
            border-left: 4px solid #007bff;
        }
        .contacto-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .contacto-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .contacto-item strong {
            color: #333;
            margin-right: 10px;
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
        .badge-cliente {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .badge-proveedor {
            background-color: #fff3cd;
            color: #856404;
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
            
            // Forzar el total de páginas a 2 
            $text = $PAGE_NUM . " de 2";
            
            $pdf->text($x, $y, $text, $font, $size);
        }
    </script>

    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <!-- Espacio para logo -->
            </div>
            <div class="header-right">
                <div class="fecha-reporte">{{ $resumen['contacto']->nombre }}</div>
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
                    <span class="pagenum"></span> de 2
                </div>
            </div>
            <div class="footer-right">
                <!-- Contacto: {{ $resumen['contacto']->nombre }} -->
            </div>
        </div>
    </div>

    <div class="header">
        <h1>REPORTE POR CONTACTO</h1>
        <h2>{{ $resumen['contacto']->nombre }}</h2>
        <p>Generado el {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="contacto-info">
        <h3>INFORMACIÓN DEL CONTACTO</h3>
        <div class="contacto-grid">
            <div>
                <div class="contacto-item">
                    <strong>Nombre:</strong>
                    <span>{{ $resumen['contacto']->nombre }}</span>
                </div>
                <div class="contacto-item">
                    <strong>Tipo:</strong>
                    <span class="badge {{ $resumen['contacto']->tipo === 'cliente' ? 'badge-cliente' : 'badge-proveedor' }}">
                        {{ ucfirst($resumen['contacto']->tipo) }}
                    </span>
                </div>
            </div>
            <div>
                <div class="contacto-item">
                    <strong>Email:</strong>
                    <span>{{ $resumen['contacto']->email ?? 'No disponible' }}</span>
                </div>
                <div class="contacto-item">
                    <strong>Teléfono:</strong>
                    <span>{{ $resumen['contacto']->telefono ?? 'No disponible' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="summary">
        <h3>RESUMEN FINANCIERO</h3>
        
        @if(isset($resumen['mensaje_vacio']))
        <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; padding: 15px; margin-bottom: 15px; color: #856404;">
            <strong>📋 Información:</strong> {{ $resumen['mensaje_vacio'] }}
            
            @if(isset($resumen['tipo_transaccion']))
            <br><strong>Filtro aplicado:</strong> {{ ucfirst($resumen['tipo_transaccion']) }}s
            @endif
            
            @if(isset($resumen['periodo']))
            <br><strong>Período:</strong> {{ $resumen['periodo'] }}
            @endif
        </div>
        @endif
        
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
                <div class="summary-item">
                    <span>Balance:</span>
                    <span class="{{ $resumen['balance'] >= 0 ? 'amount-positive' : 'amount-negative' }}">
                        ${{ number_format($resumen['balance'], 2) }}
                    </span>
                </div>
            </div>
            <div>
                <div class="summary-item">
                    <span>Total de Transacciones:</span>
                    <span class="amount-neutral">{{ $resumen['total_transacciones'] }}</span>
                </div>
                <div class="summary-item">
                    <span>Primera Transacción:</span>
                    <span>{{ $resumen['primera_transaccion']?->format('d/m/Y') ?? 'N/A' }}</span>
                </div>
                <div class="summary-item">
                    <span>Última Transacción:</span>
                    <span>{{ $resumen['ultima_transaccion']?->format('d/m/Y') ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    @if($resumen['meses']->count() > 0)
    <div class="section">
        <h3>RESUMEN POR MES</h3>
        <table>
            <thead>
                <tr>
                    <th>Mes</th>
                    <th class="text-right">Ingresos</th>
                    <th class="text-right">Egresos</th>
                    <th class="text-right">Balance</th>
                    <th class="text-center">Transacciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resumen['meses'] as $mes)
                @php $balance = $mes['ingresos'] - $mes['egresos']; @endphp
                <tr>
                    <td>{{ $mes['mes'] }}</td>
                    <td class="text-right amount-positive">${{ number_format($mes['ingresos'], 2) }}</td>
                    <td class="text-right amount-negative">${{ number_format($mes['egresos'], 2) }}</td>
                    <td class="text-right {{ $balance >= 0 ? 'amount-positive' : 'amount-negative' }}">
                        ${{ number_format($balance, 2) }}
                    </td>
                    <td class="text-center">{{ $mes['total'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($resumen['metodos']->count() > 0)
    <div class="section">
        <h3>RESUMEN POR MÉTODO DE PAGO</h3>
        <table>
            <thead>
                <tr>
                    <th>Método de Pago</th>
                    <th class="text-right">Ingresos</th>
                    <th class="text-right">Egresos</th>
                    <th class="text-right">Monto Total</th>
                    <th class="text-center">Transacciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resumen['metodos'] as $metodo)
                <tr>
                    <td>{{ $metodo['metodo'] }}</td>
                    <td class="text-right amount-positive">${{ number_format($metodo['ingresos'], 2) }}</td>
                    <td class="text-right amount-negative">${{ number_format($metodo['egresos'], 2) }}</td>
                    <td class="text-right amount-neutral">${{ number_format($metodo['ingresos'] + $metodo['egresos'], 2) }}</td>
                    <td class="text-center">{{ $metodo['total'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($transacciones->count() > 0)
    <div class="section">
        <h3>HISTORIAL DE TRANSACCIONES</h3>
        <table>
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Tipo</th>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th>Método</th>
                    <th class="text-right">Total</th>
                    <th>Observaciones</th>
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
                    <td>{{ Str::limit($transaccion->descripcion ?? '', 25) }}</td>
                    <td>{{ $transaccion->metodoPago->nombre ?? 'N/A' }}</td>
                    <td class="text-right {{ $transaccion->tipo === 'ingreso' ? 'amount-positive' : 'amount-negative' }}">
                        ${{ number_format($transaccion->total, 2) }}
                    </td>
                    <td>{{ Str::limit($transaccion->observaciones ?? '', 20) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Footer final del contenido -->
    <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 15px;">
        <p>Sistema Administrativo - Reporte generado automáticamente</p>
        <p>Contacto: {{ $resumen['contacto']->nombre }} ({{ ucfirst($resumen['contacto']->tipo) }})</p>
    </div>
</body>
</html>
