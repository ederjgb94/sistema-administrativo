<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Transacciones</title>
    <style>
        @page {
            margin: 15mm 15mm 25mm 15mm;
            /* Configuración para el footer personalizado en cada página */
            footer: html_footer;
        }
        
        body {
            font-family: helvetica, sans-serif;
            font-size: 12px;
            line-height: 1.3;
            margin: 0;
            padding: 15px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #1f2937;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #6b7280;
        }
        .header-small {
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header-small h2 {
            margin: 0;
            color: #1f2937;
            font-size: 18px;
        }
        .header-small p {
            margin: 3px 0;
            color: #6b7280;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-collapse: separate;
            border-spacing: 10px;
        }
        .stat-card {
            display: table-cell;
            width: 25%;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
            vertical-align: middle;
        }
        .stat-label {
            font-size: 9px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 5px;
            font-weight: bold;
            display: block;
        }
        .stat-value {
            font-size: 14px;
            font-weight: bold;
            color: #1f2937;
            line-height: 1.2;
            display: block;
        }
        .stat-value.ingreso {
            color: #059669;
        }
        .stat-value.egreso {
            color: #dc2626;
        }
        .filters {
            background: #f3f4f6;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .filters h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #374151;
        }
        .filter-item {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 5px;
        }
        .filter-label {
            font-weight: bold;
            color: #374151;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
            border: 1px solid #e5e7eb;
        }
        .table th {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            color: #374151;
            text-transform: uppercase;
        }
        .table td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            font-size: 11px;
            vertical-align: top;
        }
        .table tr:nth-child(even) {
            background: #f9fafb;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge.ingreso {
            background: #d1fae5;
            color: #065f46;
        }
        .badge.egreso {
            background: #fee2e2;
            color: #991b1b;
        }
        .total {
            font-weight: bold;
        }
        .total.positive {
            color: #059669;
        }
        .total.negative {
            color: #dc2626;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
            /* Eliminamos position: fixed para evitar que se superponga con el contenido */
            width: 100%;
            clear: both;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Sistema Administrativo</h1>
        <p><strong>Ingresos y Egresos</strong></p>
        <p>Generado el: {{ $fecha_generacion }}</p>
        @if(!empty(array_filter($filtros)))
            <div style="font-size: 10px; color: #059669; margin-top: 8px;">
                <strong>Filtros aplicados:</strong>
                @if(!empty($filtros['search']))
                    Búsqueda: "{{ $filtros['search'] }}" •
                @endif
                @if(!empty($filtros['tipo']))
                    Tipo: {{ ucfirst($filtros['tipo']) }} •
                @endif
                @if(!empty($filtros['fecha_desde']))
                    Desde: {{ \Carbon\Carbon::parse($filtros['fecha_desde'])->format('d/m/Y') }} •
                @endif
                @if(!empty($filtros['fecha_hasta']))
                    Hasta: {{ \Carbon\Carbon::parse($filtros['fecha_hasta'])->format('d/m/Y') }} •
                @endif
                @if(!empty($filtros['contacto_id']))
                    Contacto ID: {{ $filtros['contacto_id'] }} •
                @endif
                @if(!empty($filtros['metodo_pago_id']))
                    Método de Pago ID: {{ $filtros['metodo_pago_id'] }} •
                @endif
                @if(!empty($filtros['referencia_tipo']))
                    Tipo de Referencia: {{ ucfirst($filtros['referencia_tipo']) }} •
                @endif
                @if(!empty($filtros['factura_tipo']))
                    Tipo de Factura: {{ ucfirst($filtros['factura_tipo']) }}
                @endif
            </div>
        @else
            <p style="font-size: 11px; color: #6b7280;">
                Todas las transacciones
            </p>
        @endif
    </div>

    <!-- Estadísticas compactas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Registros</div>
            <div class="stat-value">{{ number_format($total_registros) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Ingresos</div>
            <div class="stat-value ingreso">${{ number_format($total_ingresos, 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Egresos</div>
            <div class="stat-value egreso">${{ number_format($total_egresos, 2) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Balance</div>
            <div class="stat-value {{ ($total_ingresos - $total_egresos) >= 0 ? 'ingreso' : 'egreso' }}">
                ${{ number_format($total_ingresos - $total_egresos, 2) }}
            </div>
        </div>
    </div>

    <!-- Tabla de transacciones -->
    <table class="table">
        <thead>
            <tr>
                <th style="width: 10%">Folio</th>
                <th style="width: 8%">Tipo</th>
                <th style="width: 10%">Fecha</th>
                <th style="width: 20%">Contacto</th>
                <th style="width: 25%">Referencia</th>
                <th style="width: 12%">Total</th>
                <th style="width: 15%">Método Pago</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transacciones as $index => $transaccion)
                @if($index > 0 && $index % 20 == 0)
                    </tbody>
                </table>
                
                <div class="page-break"></div>
                
                <!-- Header repetido para cada página nueva -->
                <div class="header-small">
                    <h2>Sistema Administrativo</h2>
                    <p><strong>Ingresos y Egresos</strong></p>
                </div>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 10%">Folio</th>
                            <th style="width: 8%">Tipo</th>
                            <th style="width: 10%">Fecha</th>
                            <th style="width: 20%">Contacto</th>
                            <th style="width: 25%">Referencia</th>
                            <th style="width: 12%">Total</th>
                            <th style="width: 15%">Método Pago</th>
                        </tr>
                    </thead>
                    <tbody>
                @endif
                
                <tr>
                    <td>{{ $transaccion->folio }}</td>
                    <td>
                        <span class="badge {{ $transaccion->tipo }}">
                            {{ ucfirst($transaccion->tipo) }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($transaccion->fecha)->format('d/m/Y') }}</td>
                    <td>
                        @if($transaccion->contacto)
                            <strong>{{ $transaccion->contacto->nombre }}</strong><br>
                            <small>{{ ucfirst($transaccion->contacto->tipo) }}</small>
                        @else
                            <em>Sin contacto</em>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $transaccion->referencia_nombre }}</strong><br>
                        <small>{{ ucfirst($transaccion->referencia_tipo) }}</small>
                    </td>
                    <td class="total {{ $transaccion->tipo === 'ingreso' ? 'positive' : 'negative' }}">
                        ${{ number_format($transaccion->total, 2) }}
                    </td>
                    <td>
                        @if($transaccion->metodoPago)
                            {{ $transaccion->metodoPago->nombre }}
                            @if($transaccion->referencia_pago)
                                <br><small>{{ $transaccion->referencia_pago }}</small>
                            @endif
                        @else
                            <em>No especificado</em>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #6b7280;">
                        No se encontraron transacciones con los filtros aplicados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="height: 50px;"></div> <!-- Espacio adicional después de la tabla -->
    <!-- HTML Footer para cada página -->
    <htmlpagefooter name="footer">
        <div style="border-top: 1px solid #e5e7eb; padding-top: 10px; text-align: center; font-size: 10px; color: #6b7280;">
            <p>Sistema Administrativo • Total: {{ number_format($total_registros) }} transacciones • Balance: 
                <strong style="color: {{ ($total_ingresos - $total_egresos) >= 0 ? '#059669' : '#dc2626' }}">
                    ${{ number_format($total_ingresos - $total_egresos, 2) }}
                </strong>
            </p>
        </div>
    </htmlpagefooter>
    <sethtmlpagefooter name="footer" page="all" value="on" />
</body>
</html>
