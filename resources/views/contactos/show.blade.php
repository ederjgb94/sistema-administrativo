@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Detalles del Contacto</h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('contactos.edit', $contacto) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Editar
            </a>
            <a href="{{ route('contactos.index') }}" 
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Información principal del contacto -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 mb-6">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-16 w-16">
                                <div class="h-16 w-16 rounded-full {{ $contacto->tipo === 'cliente' ? 'bg-green-100' : ($contacto->tipo === 'proveedor' ? 'bg-purple-100' : 'bg-blue-100') }} flex items-center justify-center">
                                    <span class="text-xl font-bold {{ $contacto->tipo === 'cliente' ? 'text-green-600' : ($contacto->tipo === 'proveedor' ? 'text-purple-600' : 'text-blue-600') }}">
                                        {{ strtoupper(substr($contacto->nombre, 0, 2)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-6">
                                <h1 class="text-2xl font-bold text-gray-900">{{ $contacto->nombre }}</h1>
                                <div class="flex items-center gap-4 mt-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $contacto->tipo === 'cliente' ? 'bg-green-100 text-green-800' : 
                                           ($contacto->tipo === 'proveedor' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ ucfirst($contacto->tipo) }}
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        {{ $contacto->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $contacto->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                                @if($contacto->rfc)
                                    <p class="text-gray-600 mt-1">RFC: {{ $contacto->rfc }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Acciones rápidas -->
                        <div class="flex items-center gap-2">
                            @if($contacto->email)
                                <a href="mailto:{{ $contacto->email }}" 
                                   class="bg-blue-50 hover:bg-blue-100 text-blue-600 p-2 rounded-lg transition duration-200" 
                                   title="Enviar correo">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </a>
                            @endif
                            @if($contacto->telefono)
                                <a href="tel:{{ $contacto->telefono }}" 
                                   class="bg-green-50 hover:bg-green-100 text-green-600 p-2 rounded-lg transition duration-200" 
                                   title="Llamar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </a>
                            @endif

                            <!-- Toggle estado -->
                            <form method="POST" action="{{ route('contactos.toggle-status', $contacto) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="{{ $contacto->activo ? 'bg-orange-50 hover:bg-orange-100 text-orange-600' : 'bg-green-50 hover:bg-green-100 text-green-600' }} p-2 rounded-lg transition duration-200" 
                                        title="{{ $contacto->activo ? 'Desactivar' : 'Activar' }}">
                                    @if($contacto->activo)
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @endif
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Información de contacto -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Información de Contacto</h3>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 gap-6">
                                @if($contacto->email)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 mb-1">Correo Electrónico</dt>
                                        <dd class="text-sm text-gray-900">
                                            <a href="mailto:{{ $contacto->email }}" 
                                               class="flex items-center gap-2 text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $contacto->email }}
                                            </a>
                                        </dd>
                                    </div>
                                @endif

                                @if($contacto->telefono)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 mb-1">Teléfono</dt>
                                        <dd class="text-sm text-gray-900">
                                            <a href="tel:{{ $contacto->telefono }}" 
                                               class="flex items-center gap-2 text-green-600 hover:text-green-800 transition-colors duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                                {{ $contacto->telefono }}
                                            </a>
                                        </dd>
                                    </div>
                                @endif

                                @if($contacto->direccion)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 mb-1">Dirección</dt>
                                        <dd class="text-sm text-gray-900">
                                            <div class="flex items-start gap-2">
                                                <svg class="w-4 h-4 mt-0.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                                <span>{{ $contacto->direccion }}</span>
                                            </div>
                                        </dd>
                                    </div>
                                @endif

                                @if(!$contacto->email && !$contacto->telefono && !$contacto->direccion)
                                    <div class="text-center py-6">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">Sin información de contacto</h3>
                                        <p class="mt-1 text-sm text-gray-500">Agrega email, teléfono o dirección para contactar.</p>
                                        <div class="mt-6">
                                            <a href="{{ route('contactos.edit', $contacto) }}" 
                                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                                Agregar información
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Panel lateral -->
                <div class="space-y-6">
                    <!-- Información del registro -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Información del Registro</h3>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Creado</dt>
                                    <dd class="text-sm text-gray-900">{{ $contacto->created_at->format('d/m/Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Última actualización</dt>
                                    <dd class="text-sm text-gray-900">{{ $contacto->updated_at->format('d/m/Y H:i') }}</dd>
                                </div>
                                @if($contacto->deleted_at)
                                    <div>
                                        <dt class="text-sm font-medium text-red-500">Eliminado</dt>
                                        <dd class="text-sm text-red-600">{{ $contacto->deleted_at->format('d/m/Y H:i') }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Acciones</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                <a href="{{ route('contactos.edit', $contacto) }}" 
                                   class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Editar Contacto
                                </a>

                                <form method="POST" action="{{ route('contactos.toggle-status', $contacto) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="w-full {{ $contacto->activo ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-medium py-2 px-4 rounded-md transition duration-200 flex items-center justify-center gap-2">
                                        @if($contacto->activo)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                            Desactivar
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Activar
                                        @endif
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('contactos.destroy', $contacto) }}" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este contacto? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md transition duration-200 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Eliminar Contacto
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Transacciones recientes (placeholder para futuro) -->
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Transacciones Recientes</h3>
                        </div>
                        <div class="p-6">
                            <div class="text-center py-4">
                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">No hay transacciones</p>
                                <p class="text-xs text-gray-400">Las transacciones aparecerán aquí cuando se implementen</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
