@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <div class="text-sm text-gray-600">
            <span>Bienvenido, {{ Auth::user()->name }}</span>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Estadísticas principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Contactos -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Total Contactos
                                    </dt>
                                    <dd class="text-2xl font-semibold text-gray-900">
                                        {{ \App\Models\Contacto::count() }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Clientes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Clientes
                                    </dt>
                                    <dd class="text-2xl font-semibold text-gray-900">
                                        {{ \App\Models\Contacto::whereIn('tipo', ['cliente', 'ambos'])->count() }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Proveedores -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Proveedores
                                    </dt>
                                    <dd class="text-2xl font-semibold text-gray-900">
                                        {{ \App\Models\Contacto::whereIn('tipo', ['proveedor', 'ambos'])->count() }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transacciones del Mes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-purple-500">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">
                                        Transacciones del Mes
                                    </dt>
                                    <dd class="text-2xl font-semibold text-gray-900">
                                        {{ \App\Models\Transaccion::whereMonth('fecha', now()->month)->count() }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Transacciones Recientes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Transacciones Recientes</h3>
                    </div>
                    <div class="p-6">
                        @php
                            $transaccionesRecientes = \App\Models\Transaccion::with('contacto', 'metodoPago')
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp

                        @if($transaccionesRecientes->count() > 0)
                            <div class="space-y-4">
                                @foreach($transaccionesRecientes as $transaccion)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    @if($transaccion->tipo === 'ingreso')
                                                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                                    @else
                                                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">
                                                        {{ $transaccion->folio }}
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $transaccion->contacto->nombre ?? 'Sin contacto' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-medium {{ $transaccion->tipo === 'ingreso' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $transaccion->tipo === 'ingreso' ? '+' : '-' }}${{ number_format($transaccion->total, 2) }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $transaccion->fecha->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No hay transacciones recientes</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Resumen Financiero -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Resumen Financiero</h3>
                    </div>
                    <div class="p-6">
                        @php
                            $ingresosMes = \App\Models\Transaccion::where('tipo', 'ingreso')
                                ->whereMonth('fecha', now()->month)
                                ->sum('total');
                            $egresosMes = \App\Models\Transaccion::where('tipo', 'egreso')
                                ->whereMonth('fecha', now()->month)
                                ->sum('total');
                            $balanceMes = $ingresosMes - $egresosMes;
                        @endphp

                        <div class="space-y-6">
                            <!-- Ingresos del Mes -->
                            <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-green-800">Ingresos del Mes</p>
                                        <p class="text-xs text-green-600">{{ now()->format('F Y') }}</p>
                                    </div>
                                </div>
                                <p class="text-lg font-bold text-green-600">
                                    ${{ number_format($ingresosMes, 2) }}
                                </p>
                            </div>

                            <!-- Egresos del Mes -->
                            <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-red-800">Egresos del Mes</p>
                                        <p class="text-xs text-red-600">{{ now()->format('F Y') }}</p>
                                    </div>
                                </div>
                                <p class="text-lg font-bold text-red-600">
                                    ${{ number_format($egresosMes, 2) }}
                                </p>
                            </div>

                            <!-- Balance del Mes -->
                            <div class="flex items-center justify-between p-4 {{ $balanceMes >= 0 ? 'bg-blue-50' : 'bg-orange-50' }} rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 {{ $balanceMes >= 0 ? 'text-blue-600' : 'text-orange-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium {{ $balanceMes >= 0 ? 'text-blue-800' : 'text-orange-800' }}">Balance del Mes</p>
                                        <p class="text-xs {{ $balanceMes >= 0 ? 'text-blue-600' : 'text-orange-600' }}">{{ now()->format('F Y') }}</p>
                                    </div>
                                </div>
                                <p class="text-lg font-bold {{ $balanceMes >= 0 ? 'text-blue-600' : 'text-orange-600' }}">
                                    {{ $balanceMes >= 0 ? '+' : '' }}${{ number_format($balanceMes, 2) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="mt-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Acciones Rápidas</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Nueva Transacción -->
                            <a href="{{ route('transacciones.create') }}" class="group relative bg-gradient-to-r from-blue-500 to-blue-600 p-6 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 rounded-lg text-center hover:from-blue-600 hover:to-blue-700 transition duration-200">
                                <div>
                                    <span class="rounded-lg inline-flex p-3 bg-white bg-opacity-20">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="mt-4">
                                    <h3 class="text-lg font-medium text-white">
                                        Nueva Transacción
                                    </h3>
                                    <p class="mt-2 text-sm text-blue-100">
                                        Registrar ingreso o egreso
                                    </p>
                                </div>
                            </a>

                            <!-- Nuevo Contacto -->
                            <a href="{{ route('contactos.create') }}" class="group relative bg-gradient-to-r from-green-500 to-green-600 p-6 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 rounded-lg text-center hover:from-green-600 hover:to-green-700 transition duration-200">
                                <div>
                                    <span class="rounded-lg inline-flex p-3 bg-white bg-opacity-20">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="mt-4">
                                    <h3 class="text-lg font-medium text-white">
                                        Nuevo Contacto
                                    </h3>
                                    <p class="mt-2 text-sm text-green-100">
                                        Agregar cliente o proveedor
                                    </p>
                                </div>
                            </a>

                            <!-- Ver Transacciones -->
                            <a href="{{ route('transacciones.index') }}" class="group relative bg-gradient-to-r from-purple-500 to-purple-600 p-6 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 rounded-lg text-center hover:from-purple-600 hover:to-purple-700 transition duration-200">
                                <div>
                                    <span class="rounded-lg inline-flex p-3 bg-white bg-opacity-20">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="mt-4">
                                    <h3 class="text-lg font-medium text-white">
                                        Ver Transacciones
                                    </h3>
                                    <p class="mt-2 text-sm text-purple-100">
                                        Gestión de ingresos y egresos
                                    </p>
                                </div>
                            </a>

                            <!-- Ver Contactos -->
                            <a href="{{ route('contactos.index') }}" class="group relative bg-gradient-to-r from-gray-500 to-gray-600 p-6 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 rounded-lg text-center hover:from-gray-600 hover:to-gray-700 transition duration-200">
                                <div>
                                    <span class="rounded-lg inline-flex p-3 bg-white bg-opacity-20">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="mt-4">
                                    <h3 class="text-lg font-medium text-white">
                                        Ver Contactos
                                    </h3>
                                    <p class="mt-2 text-sm text-gray-100">
                                        Gestión de clientes y proveedores
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
