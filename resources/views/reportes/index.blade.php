@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Encabezado -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Reportes</h2>
                        <p class="text-gray-600">Genera y descarga reportes financieros detallados</p>
                    </div>
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Última actualización: {{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Generales -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Estadísticas de Hoy -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Hoy</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Ingresos:</span>
                                    <span class="text-sm font-medium text-green-600">${{ number_format($estadisticas['hoy']['ingresos'], 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Egresos:</span>
                                    <span class="text-sm font-medium text-red-600">${{ number_format($estadisticas['hoy']['egresos'], 2) }}</span>
                                </div>
                                <div class="flex justify-between border-t pt-2">
                                    <span class="text-sm font-medium text-gray-900">Balance:</span>
                                    @php $balanceHoy = $estadisticas['hoy']['ingresos'] - $estadisticas['hoy']['egresos']; @endphp
                                    <span class="text-sm font-bold {{ $balanceHoy >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        ${{ number_format($balanceHoy, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-xs text-gray-500">{{ $estadisticas['hoy']['transacciones'] }} transacciones</p>
                </div>
            </div>

            <!-- Estadísticas del Mes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Este Mes</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Ingresos:</span>
                                    <span class="text-sm font-medium text-green-600">${{ number_format($estadisticas['mes']['ingresos'], 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Egresos:</span>
                                    <span class="text-sm font-medium text-red-600">${{ number_format($estadisticas['mes']['egresos'], 2) }}</span>
                                </div>
                                <div class="flex justify-between border-t pt-2">
                                    <span class="text-sm font-medium text-gray-900">Balance:</span>
                                    @php $balanceMes = $estadisticas['mes']['ingresos'] - $estadisticas['mes']['egresos']; @endphp
                                    <span class="text-sm font-bold {{ $balanceMes >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        ${{ number_format($balanceMes, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-xs text-gray-500">{{ $estadisticas['mes']['transacciones'] }} transacciones</p>
                </div>
            </div>

            <!-- Estadísticas del Año -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Este Año</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Ingresos:</span>
                                    <span class="text-sm font-medium text-green-600">${{ number_format($estadisticas['ano']['ingresos'], 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Egresos:</span>
                                    <span class="text-sm font-medium text-red-600">${{ number_format($estadisticas['ano']['egresos'], 2) }}</span>
                                </div>
                                <div class="flex justify-between border-t pt-2">
                                    <span class="text-sm font-medium text-gray-900">Balance:</span>
                                    @php $balanceAno = $estadisticas['ano']['ingresos'] - $estadisticas['ano']['egresos']; @endphp
                                    <span class="text-sm font-bold {{ $balanceAno >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        ${{ number_format($balanceAno, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-xs text-gray-500">{{ $estadisticas['ano']['transacciones'] }} transacciones</p>
                </div>
            </div>
        </div>

        <!-- Tipos de Reportes -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Reporte Diario -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Reporte Diario</h3>
                                <p class="text-sm text-gray-600">Transacciones de un día específico</p>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('reporte.diario') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="fecha_diario" class="block text-sm font-medium text-gray-700">Fecha</label>
                                <input type="date" name="fecha" id="fecha_diario" value="{{ now()->format('Y-m-d') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="tipo_transaccion_diario" class="block text-sm font-medium text-gray-700">Tipo de Transacción</label>
                                <select name="tipo_transaccion" id="tipo_transaccion_diario" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Todos</option>
                                    <option value="ingreso">Solo Ingresos</option>
                                    <option value="egreso">Solo Egresos</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button type="submit" name="formato" value="pdf" 
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                PDF
                            </button>
                            <button type="submit" name="formato" value="csv"
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                CSV
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Reporte de Período -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Reporte de Período</h3>
                                <p class="text-sm text-gray-600">Transacciones en un rango de fechas</p>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('reporte.periodo') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Desde</label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ now()->startOfMonth()->format('Y-m-d') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="fecha_fin" class="block text-sm font-medium text-gray-700">Hasta</label>
                                <input type="date" name="fecha_fin" id="fecha_fin" value="{{ now()->format('Y-m-d') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>
                        <div>
                            <label for="tipo_transaccion_periodo" class="block text-sm font-medium text-gray-700">Tipo de Transacción</label>
                            <select name="tipo_transaccion" id="tipo_transaccion_periodo" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Todos</option>
                                <option value="ingreso">Solo Ingresos</option>
                                <option value="egreso">Solo Egresos</option>
                            </select>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button type="submit" name="formato" value="pdf" 
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                PDF
                            </button>
                            <button type="submit" name="formato" value="csv"
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                CSV
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Reporte de Contactos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Reporte de Contactos</h3>
                                <p class="text-sm text-gray-600">Actividad por cliente o proveedor</p>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('reporte.contactos') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="tipo_contacto" class="block text-sm font-medium text-gray-700">Tipo de Contacto</label>
                                <select name="tipo" id="tipo_contacto" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Todos los contactos</option>
                                    <option value="cliente">Solo clientes</option>
                                    <option value="proveedor">Solo proveedores</option>
                                </select>
                            </div>
                            <div>
                                <label for="tipo_transaccion_contactos" class="block text-sm font-medium text-gray-700">Tipo de Transacción</label>
                                <select name="tipo_transaccion" id="tipo_transaccion_contactos" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Todos</option>
                                    <option value="ingreso">Solo Ingresos</option>
                                    <option value="egreso">Solo Egresos</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button type="submit" name="formato" value="pdf" 
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                PDF
                            </button>
                            <button type="submit" name="formato" value="csv"
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                CSV
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Reporte de Métodos de Pago -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Reporte de Métodos de Pago</h3>
                                <p class="text-sm text-gray-600">Uso de formas de pago</p>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('reporte.metodos-pago') }}" method="GET" class="space-y-4">
                        <div>
                            <label for="tipo_transaccion_metodos" class="block text-sm font-medium text-gray-700">Tipo de Transacción</label>
                            <select name="tipo_transaccion" id="tipo_transaccion_metodos" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Todos</option>
                                <option value="ingreso">Solo Ingresos</option>
                                <option value="egreso">Solo Egresos</option>
                            </select>
                        </div>
                        <div class="flex space-x-3">
                            <button type="submit" name="formato" value="pdf" 
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                PDF
                            </button>
                            <button type="submit" name="formato" value="csv"
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                CSV
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Nuevos Tipos de Reportes -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Reporte Mensual -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Reporte Mensual</h3>
                                <p class="text-sm text-gray-600">Análisis detallado por mes con navegación fácil</p>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('reporte.mensual') }}" method="GET" class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label for="mes" class="block text-sm font-medium text-gray-700">Mes</label>
                                <select name="mes" id="mes" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @php
                                        $mesesEspanol = [
                                            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                                            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                                            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                                        ];
                                    @endphp
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $i == now()->month ? 'selected' : '' }}>
                                            {{ $mesesEspanol[$i] }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label for="ano" class="block text-sm font-medium text-gray-700">Año</label>
                                <select name="ano" id="ano" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @for($year = now()->year; $year >= now()->year - 5; $year--)
                                        <option value="{{ $year }}" {{ $year == now()->year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="tipo_transaccion_mensual" class="block text-sm font-medium text-gray-700">Tipo de Transacción</label>
                            <select name="tipo_transaccion" id="tipo_transaccion_mensual" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Todos</option>
                                <option value="ingreso">Solo Ingresos</option>
                                <option value="egreso">Solo Egresos</option>
                            </select>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button type="submit" name="formato" value="pdf" 
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                PDF
                            </button>
                            <button type="submit" name="formato" value="csv"
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                CSV
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Reporte por Contacto -->
            <div class="bg-white shadow-sm sm:rounded-lg" style="overflow: visible;">
                <div class="p-6" style="overflow: visible;">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="p-3 rounded-full bg-pink-100 text-pink-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Reporte por Contacto</h3>
                                <p class="text-sm text-gray-600">Historial completo de un contacto específico</p>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('reporte.contacto') }}" method="GET" class="space-y-4">
                        <div>
                            <label for="contacto_search" class="block text-sm font-medium text-gray-700">Contacto</label>
                            <div class="relative" x-data="contactoSearch()">
                                <input type="text" 
                                       x-model="search"
                                       @input="buscarContactos()"
                                       @focus="showDropdown = true"
                                       @click.away="showDropdown = false"
                                       id="contacto_search"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                       placeholder="Buscar contacto..."
                                       autocomplete="off">
                                
                                <!-- Hidden input para el ID -->
                                <input type="hidden" name="contacto_id" x-model="selectedId" id="contacto_id">
                                
                                <!-- Dropdown de sugerencias -->
                                <div x-show="showDropdown && (resultados.length > 0 || (search.length >= 2 && resultados.length === 0))" 
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute z-[9999] mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm border border-gray-200"
                                     @click.away="showDropdown = false">
                                    
                                    <!-- Resultados de contactos -->
                                    <template x-for="contacto in resultados" :key="contacto.id">
                                        <div @click="seleccionarContacto(contacto)"
                                             class="cursor-pointer select-none relative py-2 px-3 hover:bg-blue-50 transition-colors duration-150">
                                            <div class="flex items-center justify-between">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center space-x-2">
                                                        <span class="font-medium text-gray-900 truncate" x-text="contacto.nombre"></span>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium flex-shrink-0"
                                                              :class="{
                                                                  'bg-green-100 text-green-800': contacto.tipo.toLowerCase() === 'cliente',
                                                                  'bg-purple-100 text-purple-800': contacto.tipo.toLowerCase() === 'proveedor',
                                                                  'bg-blue-100 text-blue-800': contacto.tipo.toLowerCase() === 'ambos'
                                                              }"
                                                              x-text="contacto.tipo === 'Cliente' ? 'Cliente' : (contacto.tipo === 'Proveedor' ? 'Proveedor' : 'Ambos')">
                                                        </span>
                                                    </div>
                                                    <p class="text-sm text-gray-500 truncate mt-0.5" x-text="contacto.email || 'Sin email registrado'"></p>
                                                    <div class="flex items-center space-x-2 mt-0.5" x-show="contacto.telefono">
                                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                        </svg>
                                                        <span class="text-xs text-gray-400" x-text="contacto.telefono"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <!-- Mensaje cuando no hay resultados -->
                                    <div x-show="search.length >= 2 && resultados.length === 0" class="px-3 py-4 text-center">
                                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        <p class="text-sm text-gray-500">No se encontraron contactos</p>
                                        <p class="text-xs text-gray-400 mt-1">Intenta con otro término de búsqueda</p>
                                    </div>
                                </div>
                                
                                <!-- Contacto seleccionado -->
                                <div x-show="selectedContacto" 
                                     class="mt-3 p-3 bg-indigo-50 rounded-md border border-indigo-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3 flex-1 min-w-0">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center space-x-2">
                                                    <span class="font-medium text-indigo-900 truncate" x-text="selectedContacto?.nombre"></span>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium flex-shrink-0" 
                                                          :class="{
                                                              'bg-green-100 text-green-800': selectedContacto?.tipo.toLowerCase() === 'cliente',
                                                              'bg-purple-100 text-purple-800': selectedContacto?.tipo.toLowerCase() === 'proveedor',
                                                              'bg-blue-100 text-blue-800': selectedContacto?.tipo.toLowerCase() === 'ambos'
                                                          }"
                                                          x-text="selectedContacto?.tipo === 'Cliente' ? 'Cliente' : (selectedContacto?.tipo === 'Proveedor' ? 'Proveedor' : 'Ambos')"></span>
                                                </div>
                                                <p class="text-sm text-indigo-700 truncate" x-text="selectedContacto?.email || 'Sin email registrado'"></p>
                                                <div class="flex items-center space-x-2 mt-1" x-show="selectedContacto?.telefono">
                                                    <svg class="w-3 h-3 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                    </svg>
                                                    <span class="text-xs text-indigo-600" x-text="selectedContacto?.telefono"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" @click="limpiarSeleccion()" 
                                                class="flex-shrink-0 ml-3 text-indigo-600 hover:text-indigo-500 transition-colors duration-150">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Comience escribiendo para buscar contactos</p>
                        </div>
                        
                        <div>
                            <label for="tipo_transaccion_contacto" class="block text-sm font-medium text-gray-700">Tipo de Transacción</label>
                            <select name="tipo_transaccion" id="tipo_transaccion_contacto" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Todos</option>
                                <option value="ingreso">Solo Ingresos</option>
                                <option value="egreso">Solo Egresos</option>
                            </select>
                        </div>

                        <!-- Filtro de Fechas -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Rango de Fechas (Opcional)</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="fecha_inicio_contacto" class="block text-xs font-medium text-gray-600 mb-1">
                                        Fecha de Inicio
                                    </label>
                                    <input 
                                        type="date" 
                                        name="fecha_inicio" 
                                        id="fecha_inicio_contacto"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    >
                                </div>
                                <div>
                                    <label for="fecha_fin_contacto" class="block text-xs font-medium text-gray-600 mb-1">
                                        Fecha de Fin
                                    </label>
                                    <input 
                                        type="date" 
                                        name="fecha_fin" 
                                        id="fecha_fin_contacto"
                                        value="{{ date('Y-m-d') }}"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    >
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                Selecciona un rango de fechas para filtrar las transacciones. Déjalo vacío para mostrar todas las transacciones.
                            </p>
                        </div>
                        
                        <div class="flex space-x-3">
                            <button type="submit" name="formato" value="pdf" 
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                PDF
                            </button>
                            <button type="submit" name="formato" value="csv"
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                CSV
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Transacciones Recientes -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Transacciones Recientes</h3>
                    <a href="{{ route('transacciones.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">
                        Ver todas →
                    </a>
                </div>

                @if($transaccionesRecientes->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Folio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($transaccionesRecientes as $transaccion)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $transaccion->folio }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $transaccion->tipo === 'ingreso' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($transaccion->tipo) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaccion->fecha->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaccion->contacto->nombre ?? 'Sin contacto' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium
                                    {{ $transaccion->tipo === 'ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                    ${{ number_format($transaccion->total, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay transacciones recientes</h3>
                    <p class="text-gray-500">Comienza creando tu primera transacción para ver reportes.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
// Función para el autocompletado de contactos
function contactoSearch() {
    return {
        search: '',
        resultados: [],
        showDropdown: false,
        selectedId: '',
        selectedContacto: null,
        searchTimeout: null,

        buscarContactos() {
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }

            if (this.search.length < 2) {
                this.resultados = [];
                this.showDropdown = false;
                return;
            }

            this.searchTimeout = setTimeout(() => {
                fetch(`{{ route('api.contactos.buscar') }}?q=${encodeURIComponent(this.search)}`)
                    .then(response => response.json())
                    .then(data => {
                        this.resultados = data;
                        this.showDropdown = true;
                    })
                    .catch(error => {
                        console.error('Error al buscar contactos:', error);
                        this.resultados = [];
                    });
            }, 300);
        },

        seleccionarContacto(contacto) {
            this.selectedContacto = contacto;
            this.selectedId = contacto.id;
            this.search = contacto.nombre;
            this.showDropdown = false;
            this.resultados = [];
        },

        limpiarSeleccion() {
            this.selectedContacto = null;
            this.selectedId = '';
            this.search = '';
            this.resultados = [];
            this.showDropdown = false;
        }
    }
}
</script>
@endsection
