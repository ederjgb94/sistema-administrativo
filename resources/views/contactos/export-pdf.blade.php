<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Directorio de Contactos</title>
    <style>
        @page {
            margin: 15mm 15mm 25mm 15mm;
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
            width: 16.66%;
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
        .stat-value.cliente {
            color: #059669;
        }
        .stat-value.proveedor {
            color: #7e22ce;
        }
        .stat-value.activo {
            color: #2563eb;
        }
        .stat-value.inactivo {
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
        .badge.cliente {
            background: #d1fae5;
            color: #065f46;
        }
        .badge.proveedor {
            background: #f3e8ff;
            color: #6b21a8;
        }
        .badge.ambos {
            background: #dbeafe;
            color: #1e40af;
        }
        .badge.activo {
            background: #dcfce7;
            color: #166534;
        }
        .badge.inactivo {
            background: #fee2e2;
            color: #991b1b;
        }
        .page-break {
            page-break-before: always;
        }
        .info-icon {
            display: inline-block;
            width: 14px;
            height: 14px;
            line-height: 14px;
            text-align: center;
            background: #e5e7eb;
            color: #4b5563;
            border-radius: 50%;
            margin-right: 4px;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Sistema Administrativo</h1>
        <p><strong>Directorio de Contactos</strong></p>
        <p>Generado el: {{ $fecha_generacion }}</p>
        @if(!empty(array_filter($filtros)))
            <div style="font-size: 10px; color: #059669; margin-top: 8px;">
                <strong>Filtros aplicados:</strong>
                @if(!empty($filtros['search']))
                    Búsqueda: "{{ $filtros['search'] }}" •
                @endif
                @if(!empty($filtros['tipo']) && $filtros['tipo'] !== 'todos')
                    Tipo: {{ ucfirst($filtros['tipo']) }} •
                @endif
                @if(!empty($filtros['estado']))
                    Estado: {{ ucfirst($filtros['estado']) }}
                @endif
            </div>
        @else
            <p style="font-size: 11px; color: #6b7280;">
                Todos los contactos
            </p>
        @endif
    </div>

    <!-- Estadísticas compactas -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Contactos</div>
            <div class="stat-value">{{ number_format($stats['total']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Clientes</div>
            <div class="stat-value cliente">{{ number_format($stats['clientes']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Proveedores</div>
            <div class="stat-value proveedor">{{ number_format($stats['proveedores']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Ambos</div>
            <div class="stat-value">{{ number_format($stats['ambos']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Activos</div>
            <div class="stat-value activo">{{ number_format($stats['activos']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Inactivos</div>
            <div class="stat-value inactivo">{{ number_format($stats['inactivos']) }}</div>
        </div>
    </div>

    <!-- Tabla de contactos -->
    <table class="table">
        <thead>
            <tr>
                <th style="width: 5%">ID</th>
                <th style="width: 25%">Nombre</th>
                <th style="width: 10%">Tipo</th>
                <th style="width: 15%">RFC</th>
                <th style="width: 15%">Teléfono</th>
                <th style="width: 20%">Email</th>
                <th style="width: 10%">Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contactos as $index => $contacto)
                @if($index > 0 && $index % 20 == 0)
                    </tbody>
                </table>
                
                <div class="page-break"></div>
                
                <!-- Header repetido para cada página nueva -->
                <div class="header-small">
                    <h2>Sistema Administrativo</h2>
                    <p><strong>Directorio de Contactos</strong></p>
                </div>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 5%">ID</th>
                            <th style="width: 25%">Nombre</th>
                            <th style="width: 10%">Tipo</th>
                            <th style="width: 15%">RFC</th>
                            <th style="width: 15%">Teléfono</th>
                            <th style="width: 20%">Email</th>
                            <th style="width: 10%">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                @endif
                
                <tr>
                    <td>{{ $contacto->id }}</td>
                    <td>
                        <strong>{{ $contacto->nombre }}</strong>
                        @if($contacto->notas)
                            <br><small>{{ Str::limit($contacto->notas, 50) }}</small>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $contacto->tipo }}">
                            {{ ucfirst($contacto->tipo) }}
                        </span>
                    </td>
                    <td>{{ $contacto->rfc ?: '-' }}</td>
                    <td>{{ $contacto->telefono ?: '-' }}</td>
                    <td>{{ $contacto->email ?: '-' }}</td>
                    <td>
                        <span class="badge {{ $contacto->activo ? 'activo' : 'inactivo' }}">
                            {{ $contacto->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #6b7280;">
                        No se encontraron contactos con los filtros aplicados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="height: 50px;"></div> <!-- Espacio adicional después de la tabla -->
    
    <!-- HTML Footer para cada página -->
    <htmlpagefooter name="footer">
        <div style="border-top: 1px solid #e5e7eb; padding-top: 10px; text-align: center; font-size: 10px; color: #6b7280;">
            <p>Sistema Administrativo • Total: {{ number_format($stats['total']) }} contactos • 
                Activos: {{ number_format($stats['activos']) }} •
                Clientes: {{ number_format($stats['clientes']) }} •
                Proveedores: {{ number_format($stats['proveedores']) }}
            </p>
        </div>
    </htmlpagefooter>
    <sethtmlpagefooter name="footer" page="all" value="on" />
</body>
</html>
