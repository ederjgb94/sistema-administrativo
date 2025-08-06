@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Encabezado -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Editar Contacto</h1>
                        <p class="text-gray-600 mt-1">Modificar información del contacto</p>
                    </div>
                    <a href="{{ route('contactos.show', $contacto) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <form method="POST" action="{{ route('contactos.update', $contacto) }}" id="contacto-form">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Columna principal (formulario) -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Datos Básicos -->
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900">Datos Básicos</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">
                                        Nombre *
                                    </label>
                                    <input type="text" 
                                           name="nombre" 
                                           id="nombre" 
                                           value="{{ old('nombre', $contacto->nombre) }}"
                                           placeholder="Nombre completo del contacto"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nombre') border-red-500 @enderror">
                                    @error('nombre')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Tipo *
                                    </label>
                                    <div class="flex gap-4">
                                        <div class="flex items-center">
                                            <input type="radio" 
                                                   name="tipo" 
                                                   id="tipo_cliente" 
                                                   value="cliente" 
                                                   {{ old('tipo', $contacto->tipo) == 'cliente' ? 'checked' : '' }}
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                            <label for="tipo_cliente" class="ml-2 text-sm text-gray-700">Cliente</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" 
                                                   name="tipo" 
                                                   id="tipo_proveedor" 
                                                   value="proveedor" 
                                                   {{ old('tipo', $contacto->tipo) == 'proveedor' ? 'checked' : '' }}
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                            <label for="tipo_proveedor" class="ml-2 text-sm text-gray-700">Proveedor</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" 
                                                   name="tipo" 
                                                   id="tipo_ambos" 
                                                   value="ambos" 
                                                   {{ old('tipo', $contacto->tipo) == 'ambos' ? 'checked' : '' }}
                                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                            <label for="tipo_ambos" class="ml-2 text-sm text-gray-700">Ambos</label>
                                        </div>
                                    </div>
                                    @error('tipo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Contacto -->
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900">Información de Contacto</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                        Correo Electrónico *
                                    </label>
                                    <input type="email" 
                                           name="email" 
                                           id="email" 
                                           value="{{ old('email', $contacto->email) }}"
                                           placeholder="correo@ejemplo.com"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">
                                        Teléfono *
                                    </label>
                                    <input type="text" 
                                           name="telefono" 
                                           id="telefono" 
                                           value="{{ old('telefono', $contacto->telefono) }}"
                                           placeholder="55 1234 5678"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('telefono') border-red-500 @enderror">
                                    @error('telefono')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Datos Adicionales -->
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900">Datos Adicionales</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">
                                        Dirección
                                    </label>
                                    <textarea name="direccion" 
                                              id="direccion" 
                                              rows="3"
                                              placeholder="Dirección completa"
                                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('direccion') border-red-500 @enderror">{{ old('direccion', $contacto->direccion) }}</textarea>
                                    @error('direccion')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="rfc" class="block text-sm font-medium text-gray-700 mb-1">
                                        RFC
                                    </label>
                                    <input type="text" 
                                           name="rfc" 
                                           id="rfc" 
                                           value="{{ old('rfc', $contacto->rfc) }}"
                                           placeholder="ABCD123456EFG"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('rfc') border-red-500 @enderror">
                                    @error('rfc')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="activo" 
                                       id="activo" 
                                       value="1" 
                                       {{ old('activo', $contacto->activo) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="activo" class="ml-2 text-sm text-gray-700">
                                    Contacto activo
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna lateral (resumen y acciones) -->
                <div class="space-y-6">
                    <!-- Resumen -->
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200 sticky top-6">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900">Resumen</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Estado:</span>
                                <span id="estado-preview" class="font-medium">
                                    {{ $contacto->activo ? 'Activo' : 'Inactivo' }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tipo:</span>
                                <span id="tipo-preview" class="font-medium capitalize">
                                    {{ ucfirst($contacto->tipo) }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Validación:</span>
                                <span id="validacion-preview" class="font-medium text-green-600">
                                    Completo
                                </span>
                            </div>
                            
                            <div class="pt-4 border-t border-gray-200">
                                <h4 class="text-sm font-medium text-gray-900 mb-2">Información</h4>
                                <p class="text-sm text-gray-600">
                                    Los campos marcados con asterisco (*) son obligatorios. Asegúrate de guardar los cambios.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900">Acciones</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Guardar Cambios
                            </button>
                            
                            <a href="{{ route('contactos.show', $contacto) }}" 
                               class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('contacto-form');
        const nombreInput = document.getElementById('nombre');
        const emailInput = document.getElementById('email');
        const telefonoInput = document.getElementById('telefono');
        const tipoInputs = document.querySelectorAll('input[name="tipo"]');
        const activoInput = document.getElementById('activo');
        
        const estadoPreview = document.getElementById('estado-preview');
        const tipoPreview = document.getElementById('tipo-preview');
        const validacionPreview = document.getElementById('validacion-preview');

        function actualizarResumen() {
            // Actualizar estado
            if (activoInput.checked) {
                estadoPreview.textContent = 'Activo';
                estadoPreview.className = 'font-medium text-green-600';
            } else {
                estadoPreview.textContent = 'Inactivo';
                estadoPreview.className = 'font-medium text-red-600';
            }

            // Actualizar tipo
            const tipoSeleccionado = document.querySelector('input[name="tipo"]:checked');
            if (tipoSeleccionado) {
                tipoPreview.textContent = tipoSeleccionado.value.charAt(0).toUpperCase() + tipoSeleccionado.value.slice(1);
            } else {
                tipoPreview.textContent = '-';
            }

            // Actualizar validación
            const camposRequeridos = [nombreInput.value, emailInput.value, telefonoInput.value];
            const tipoCompleto = document.querySelector('input[name="tipo"]:checked');
            
            if (camposRequeridos.every(campo => campo.trim() !== '') && tipoCompleto) {
                validacionPreview.textContent = 'Completo';
                validacionPreview.className = 'font-medium text-green-600';
            } else {
                validacionPreview.textContent = 'Pendiente';
                validacionPreview.className = 'font-medium text-yellow-600';
            }
        }

        // Event listeners para actualizar el resumen
        [nombreInput, emailInput, telefonoInput].forEach(input => {
            input.addEventListener('input', actualizarResumen);
        });

        tipoInputs.forEach(input => {
            input.addEventListener('change', actualizarResumen);
        });

        activoInput.addEventListener('change', actualizarResumen);

        // Inicializar resumen
        actualizarResumen();
    });
</script>
@endsection
