@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Encabezado -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Detalle de Transacción</h2>
                        <p class="text-gray-600">{{ $transaccione->folio }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('transacciones.edit', $transaccione) }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar
                        </a>
                        <a href="{{ route('transacciones.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Información Principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Datos Básicos -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Información General</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Folio</label>
                                <p class="mt-1 text-sm text-gray-900 font-mono">{{ $transaccione->folio }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tipo</label>
                                <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $transaccione->tipo === 'ingreso' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($transaccione->tipo) }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fecha</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $transaccione->fecha->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Contacto</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if($transaccione->contacto)
                                        <a href="{{ route('contactos.show', $transaccione->contacto) }}" 
                                           class="text-indigo-600 hover:text-indigo-900">
                                            {{ $transaccione->contacto->nombre }}
                                        </a>
                                    @else
                                        <span class="text-gray-500">Sin contacto</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Referencia -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Referencia</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tipo</label>
                                <span class="mt-1 inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                    {{ ucfirst($transaccione->referencia_tipo) }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $transaccione->referencia_nombre }}</p>
                            </div>
                        </div>

                        @if($transaccione->referencia_datos)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Datos Adicionales</label>
                            <div class="bg-gray-50 rounded-md p-4">
                                @if(isset($transaccione->referencia_datos['descripcion']))
                                <div class="mb-3">
                                    <label class="block text-xs font-medium text-gray-600">Descripción</label>
                                    <p class="text-sm text-gray-900">{{ $transaccione->referencia_datos['descripcion'] }}</p>
                                </div>
                                @endif
                                @if(isset($transaccione->referencia_datos['ubicacion']))
                                <div class="mb-3">
                                    <label class="block text-xs font-medium text-gray-600">Ubicación</label>
                                    <p class="text-sm text-gray-900">{{ $transaccione->referencia_datos['ubicacion'] }}</p>
                                </div>
                                @endif
                                @if(isset($transaccione->referencia_datos['especificaciones']))
                                <div>
                                    <label class="block text-xs font-medium text-gray-600">Especificaciones</label>
                                    <ul class="text-sm text-gray-900 list-disc list-inside">
                                        @foreach($transaccione->referencia_datos['especificaciones'] as $spec)
                                        <li>{{ $spec }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Facturación -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Facturación</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tipo de Factura</label>
                                <span class="mt-1 inline-flex items-center px-2 py-1 rounded-full text-xs bg-purple-100 text-purple-800">
                                    {{ ucfirst($transaccione->factura_tipo) }}
                                </span>
                            </div>
                            @if($transaccione->factura_numero)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Número de Factura</label>
                                <p class="mt-1 text-sm text-gray-900 font-mono">{{ $transaccione->factura_numero }}</p>
                            </div>
                            @endif
                        </div>

                        @if($transaccione->factura_datos)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Datos Manuales</label>
                            <div class="bg-gray-50 rounded-md p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if(isset($transaccione->factura_datos['emisor']) && is_array($transaccione->factura_datos['emisor']))
                                <div>
                                    <label class="block text-xs font-medium text-gray-600">Emisor</label>
                                    <div class="text-sm text-gray-900">
                                        @if($transaccione->factura_datos['emisor']['nombre'])
                                            <p><strong>Nombre:</strong> {{ $transaccione->factura_datos['emisor']['nombre'] }}</p>
                                        @endif
                                        @if($transaccione->factura_datos['emisor']['rfc'])
                                            <p><strong>RFC:</strong> {{ $transaccione->factura_datos['emisor']['rfc'] }}</p>
                                        @endif
                                        @if($transaccione->factura_datos['emisor']['direccion'])
                                            <p><strong>Dirección:</strong> {{ $transaccione->factura_datos['emisor']['direccion'] }}</p>
                                        @endif
                                        @if(!$transaccione->factura_datos['emisor']['nombre'] && !$transaccione->factura_datos['emisor']['rfc'] && !$transaccione->factura_datos['emisor']['direccion'])
                                            <p class="text-gray-500">No especificado</p>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                @if(isset($transaccione->factura_datos['receptor']) && is_array($transaccione->factura_datos['receptor']))
                                <div>
                                    <label class="block text-xs font-medium text-gray-600">Receptor</label>
                                    <div class="text-sm text-gray-900">
                                        @if($transaccione->factura_datos['receptor']['nombre'])
                                            <p><strong>Nombre:</strong> {{ $transaccione->factura_datos['receptor']['nombre'] }}</p>
                                        @endif
                                        @if($transaccione->factura_datos['receptor']['rfc'])
                                            <p><strong>RFC:</strong> {{ $transaccione->factura_datos['receptor']['rfc'] }}</p>
                                        @endif
                                        @if($transaccione->factura_datos['receptor']['direccion'])
                                            <p><strong>Dirección:</strong> {{ $transaccione->factura_datos['receptor']['direccion'] }}</p>
                                        @endif
                                        @if(!$transaccione->factura_datos['receptor']['nombre'] && !$transaccione->factura_datos['receptor']['rfc'] && !$transaccione->factura_datos['receptor']['direccion'])
                                            <p class="text-gray-500">No especificado</p>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                @if(isset($transaccione->factura_datos['fecha_emision']))
                                <div>
                                    <label class="block text-xs font-medium text-gray-600">Fecha de Emisión</label>
                                    <p class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($transaccione->factura_datos['fecha_emision'])->format('d/m/Y') }}</p>
                                </div>
                                @endif
                                @if(isset($transaccione->factura_datos['metodo_pago_factura']))
                                <div>
                                    <label class="block text-xs font-medium text-gray-600">Método de Pago en Factura</label>
                                    <p class="text-sm text-gray-900">{{ $transaccione->factura_datos['metodo_pago_factura'] }}</p>
                                </div>
                                @endif
                                @if(isset($transaccione->factura_datos['condiciones']))
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-gray-600">Condiciones</label>
                                    <p class="text-sm text-gray-900">{{ $transaccione->factura_datos['condiciones'] }}</p>
                                </div>
                                @endif
                                @if(isset($transaccione->factura_datos['notas']))
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-gray-600">Notas</label>
                                    <p class="text-sm text-gray-900">{{ $transaccione->factura_datos['notas'] }}</p>
                                </div>
                                @endif
                                
                                {{-- Conceptos --}}
                                @if(isset($transaccione->factura_datos['conceptos']) && is_array($transaccione->factura_datos['conceptos']))
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-medium text-gray-600 mb-2">Conceptos</label>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full bg-white border border-gray-200 rounded-md">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-3 py-2 border-b text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                                                    <th class="px-3 py-2 border-b text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                                                    <th class="px-3 py-2 border-b text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                                                    <th class="px-3 py-2 border-b text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @foreach($transaccione->factura_datos['conceptos'] as $concepto)
                                                <tr>
                                                    <td class="px-3 py-2 text-sm text-gray-900">{{ $concepto['descripcion'] ?? '-' }}</td>
                                                    <td class="px-3 py-2 text-sm text-gray-900">{{ $concepto['cantidad'] ?? '-' }}</td>
                                                    <td class="px-3 py-2 text-sm text-gray-900">${{ number_format($concepto['precio'] ?? 0, 2) }}</td>
                                                    <td class="px-3 py-2 text-sm text-gray-900 font-medium">${{ number_format($concepto['total'] ?? 0, 2) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    {{-- Totales --}}
                                    <div class="mt-4 bg-gray-50 p-3 rounded-md">
                                        <div class="flex justify-between text-sm">
                                            <span>Subtotal:</span>
                                            <span>${{ number_format($transaccione->factura_datos['subtotal'] ?? 0, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span>Impuestos:</span>
                                            <span>${{ number_format($transaccione->factura_datos['impuestos'] ?? 0, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between text-lg font-semibold border-t pt-2 mt-2">
                                            <span>Total:</span>
                                            <span>${{ number_format($transaccione->factura_datos['total'] ?? 0, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($transaccione->factura_archivos && count($transaccione->factura_archivos) > 0)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Archivos de Factura</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($transaccione->factura_archivos as $index => $archivo)
                                <div class="border rounded-lg p-4 hover:bg-gray-50">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            @php
                                                $extension = pathinfo($archivo['nombre_original'], PATHINFO_EXTENSION);
                                                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png']);
                                            @endphp
                                            
                                            @if($isImage)
                                                <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-8 h-8 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            @endif
                                            
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $archivo['nombre_original'] }}</p>
                                                <p class="text-xs text-gray-500">
                                                    {{ number_format($archivo['tamaño'] / 1024, 1) }} KB • 
                                                    {{ \Carbon\Carbon::parse($archivo['subido_en'])->format('d/m/Y H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ Storage::url($archivo['ruta']) }}" 
                                               target="_blank"
                                               class="text-indigo-600 hover:text-indigo-900" 
                                               title="Ver archivo">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ Storage::url($archivo['ruta']) }}" 
                                               download="{{ $archivo['nombre_original'] }}"
                                               class="text-gray-600 hover:text-gray-900" 
                                               title="Descargar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                @if($transaccione->observaciones)
                <!-- Observaciones -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Observaciones</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-900">{{ $transaccione->observaciones }}</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Panel Lateral -->
            <div class="space-y-6">
                <!-- Resumen Financiero -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Resumen Financiero</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Total</label>
                                <p class="mt-1 text-3xl font-bold {{ $transaccione->tipo === 'ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                    ${{ number_format($transaccione->total, 2) }}
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Método de Pago</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $transaccione->metodoPago->nombre }}</p>
                            </div>
                            
                            @if($transaccione->referencia_pago)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Referencia de Pago</label>
                                <p class="mt-1 text-sm text-gray-900 font-mono">{{ $transaccione->referencia_pago }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Metadatos -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Información del Sistema</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Creado</label>
                                <p class="text-sm text-gray-900">{{ $transaccione->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Última actualización</label>
                                <p class="text-sm text-gray-900">{{ $transaccione->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Acciones</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('transacciones.edit', $transaccione) }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar Transacción
                        </a>
                        
                        <form action="{{ route('transacciones.destroy', $transaccione) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    onclick="return confirm('¿Estás seguro de que quieres eliminar esta transacción? Esta acción no se puede deshacer.')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Eliminar Transacción
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
