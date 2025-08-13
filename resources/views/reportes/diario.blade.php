<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Diario - {{ $fechaFormateada }}</title>
    <style>
        @page {
            margin: 150px 50px 120px 50px;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        
        /* Header */
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
        
        .logo-placeholder {
            width: 150px;
            height: 60px;
            border: 2px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 10px;
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
        
        /* Contenido principal */
        .main-content {
            margin-top: 0;
        }
        
        .titulo-principal {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: #1F2937;
            margin-bottom: 30px;
            padding: 15px;
            background-color: #F3F4F6;
            border-radius: 8px;
        }
        
        .resumen-container {
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .resumen-titulo {
            font-size: 16px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 15px;
            border-bottom: 2px solid #3B82F6;
            padding-bottom: 5px;
        }
        
        .resumen-grid {
            display: table;
            width: 100%;
        }
        
        .resumen-item {
            display: table-row;
        }
        
        .resumen-label {
            display: table-cell;
            font-weight: bold;
            padding: 8px 0;
            width: 40%;
        }
        
        .resumen-valor {
            display: table-cell;
            text-align: right;
            padding: 8px 0;
            font-weight: bold;
        }
        
        .valor-ingreso { color: #059669; }
        .valor-egreso { color: #DC2626; }
        .valor-balance { color: #2563EB; }
        .valor-balance.negativo { color: #EA580C; }
        
        /* Tabla de transacciones */
        .transacciones-titulo {
            font-size: 16px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 15px;
            border-bottom: 2px solid #3B82F6;
            padding-bottom: 5px;
        }
        
        .tabla-transacciones {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .tabla-transacciones th {
            background-color: #F3F4F6;
            border: 1px solid #D1D5DB;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            color: #374151;
        }
        
        .tabla-transacciones td {
            border: 1px solid #E5E7EB;
            padding: 8px;
            font-size: 10px;
        }
        
        .tabla-transacciones tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        
        .tipo-ingreso {
            color: #059669;
            font-weight: bold;
        }
        
        .tipo-egreso {
            color: #DC2626;
            font-weight: bold;
        }
        
        .monto {
            text-align: right;
            font-weight: bold;
        }
        
        .sin-transacciones {
            text-align: center;
            color: #6B7280;
            font-style: italic;
            padding: 40px 0;
            background-color: #F9FAFB;
            border-radius: 8px;
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
    <!-- Header -->
    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <!-- Espacio para logo - se deja vacío si no hay logo -->
                <div class="logo-placeholder" style="display: none;">
                    Logo
                </div>
            </div>
            <div class="header-right">
                <div class="fecha-reporte">{{ $fechaFormateada }}</div>
                <div class="fecha-generacion">Generado: {{ now()->format('d/m/Y H:i:s') }}</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <!-- Dirección - se deja vacío si no hay dirección -->
            </div>
            <div class="footer-section footer-center">
                <!-- Numeración de páginas HTML + CSS -->
                <div class="page-number">
                    <span class="pagenum"></span> de {{ isset($totalPaginas) ? $totalPaginas : 1 }}
                </div>
            </div>
            <div class="footer-section footer-right">
                <!-- Correo - se deja vacío si no hay correo -->
            </div>
        </div>
    </div>



    <!-- Contenido principal -->
    <div class="main-content">
        <div class="titulo-principal">
            REPORTE DIARIO DE TRANSACCIONES
        </div>

        <!-- Resumen -->
        <div class="resumen-container">
            <div class="resumen-titulo">Resumen del Día</div>
            <div class="resumen-grid">
                <div class="resumen-item">
                    <div class="resumen-label">Total de Ingresos:</div>
                    <div class="resumen-valor valor-ingreso">${{ number_format($totalIngresos, 2) }}</div>
                </div>
                <div class="resumen-item">
                    <div class="resumen-label">Total de Egresos:</div>
                    <div class="resumen-valor valor-egreso">${{ number_format($totalEgresos, 2) }}</div>
                </div>
                <div class="resumen-item">
                    <div class="resumen-label">Balance del Día:</div>
                    <div class="resumen-valor valor-balance {{ $balance < 0 ? 'negativo' : '' }}">
                        {{ $balance >= 0 ? '+' : '' }}${{ number_format($balance, 2) }}
                    </div>
                </div>
                <div class="resumen-item">
                    <div class="resumen-label">Total de Transacciones:</div>
                    <div class="resumen-valor">{{ $transacciones->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Detalle de transacciones -->
        <div class="transacciones-titulo">Detalle de Transacciones</div>
        
        @if($transacciones->count() > 0)
            <table class="tabla-transacciones">
                <thead>
                    <tr>
                        <th style="width: 12%;">Folio</th>
                        <th style="width: 10%;">Tipo</th>
                        <th style="width: 20%;">Contacto</th>
                        <th style="width: 25%;">Descripción</th>
                        <th style="width: 15%;">Método Pago</th>
                        <th style="width: 12%;">Monto</th>
                        <th style="width: 6%;">Hora</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transacciones as $transaccion)
                    <tr>
                        <td>{{ $transaccion->folio }}</td>
                        <td class="{{ $transaccion->tipo === 'ingreso' ? 'tipo-ingreso' : 'tipo-egreso' }}">
                            {{ ucfirst($transaccion->tipo) }}
                        </td>
                        <td>{{ $transaccion->contacto->nombre ?? 'Sin contacto' }}</td>
                        <td>{{ $transaccion->descripcion ?: 'Sin descripción' }}</td>
                        <td>{{ $transaccion->metodoPago->nombre ?? 'Sin método' }}</td>
                        <td class="monto {{ $transaccion->tipo === 'ingreso' ? 'tipo-ingreso' : 'tipo-egreso' }}">
                            ${{ number_format($transaccion->total, 2) }}
                        </td>
                        <td>{{ $transaccion->created_at->format('H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="sin-transacciones">
                No se registraron transacciones en esta fecha
            </div>
        @endif
    </div>
</body>
</html>
