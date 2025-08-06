@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Encabezado -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Crear Nuevo Contacto</h1>
                        <p class="text-gray-600 mt-1">Agregar un nuevo contacto al sistema</p>
                    </div>
                    <a href="{{ route('contactos.index') }}" 
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
        <form method="POST" action="{{ route('contactos.store') }}" id="contacto-form">
            @csrf
            
            <!-- Grid de dos columnas principal -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Columna izquierda (2/3 del ancho) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Datos Básicos -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Datos Básicos</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nombre -->
                                <div>
                                    <label for="nombre" class="block text-sm font-medium text-gray-700">
                                        Nombre *
                                    </label>
                                    <input type="text" 
                                           id="nombre" 
                                           name="nombre" 
                                           value="{{ old('nombre') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nombre') border-red-300 @enderror"
                                           placeholder="Nombre completo del contacto"
                                           required>
                                    @error('nombre')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tipo -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tipo *
                                    </label>
                                    <div class="flex space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="tipo" value="cliente" 
                                                   class="form-radio text-indigo-600" 
                                                   {{ old('tipo') == 'cliente' ? 'checked' : '' }} required>
                                            <span class="ml-2">Cliente</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="tipo" value="proveedor" 
                                                   class="form-radio text-indigo-600"
                                                   {{ old('tipo') == 'proveedor' ? 'checked' : '' }}>
                                            <span class="ml-2">Proveedor</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" name="tipo" value="ambos" 
                                                   class="form-radio text-indigo-600"
                                                   {{ old('tipo') == 'ambos' ? 'checked' : '' }}>
                                            <span class="ml-2">Ambos</span>
                                        </label>
                                    </div>
                                    @error('tipo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Contacto -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Contacto</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">
                                        Correo Electrónico *
                                    </label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('email') border-red-300 @enderror"
                                           placeholder="correo@ejemplo.com"
                                           required>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Teléfono -->
                                <div>
                                    <label for="telefono" class="block text-sm font-medium text-gray-700">
                                        Teléfono *
                                    </label>
                                    <input type="text" 
                                           id="telefono" 
                                           name="telefono" 
                                           value="{{ old('telefono') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('telefono') border-red-300 @enderror"
                                           placeholder="55 1234 5678"
                                           required>
                                    @error('telefono')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Datos Adicionales -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Datos Adicionales</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Dirección -->
                                <div>
                                    <label for="direccion" class="block text-sm font-medium text-gray-700">
                                        Dirección
                                    </label>
                                    <textarea id="direccion" 
                                              name="direccion" 
                                              rows="3"
                                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('direccion') border-red-300 @enderror"
                                              placeholder="Dirección completa">{{ old('direccion') }}</textarea>
                                    @error('direccion')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- RFC -->
                                <div>
                                    <label for="rfc" class="block text-sm font-medium text-gray-700">
                                        RFC
                                    </label>
                                    <input type="text" 
                                           id="rfc" 
                                           name="rfc" 
                                           value="{{ old('rfc') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('rfc') border-red-300 @enderror"
                                           placeholder="ABCD123456EFG">
                                    @error('rfc')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Estado -->
                            <div class="mt-6">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="activo" 
                                           name="activo" 
                                           value="1"
                                           class="form-checkbox text-indigo-600 rounded"
                                           {{ old('activo', true) ? 'checked' : '' }}>
                                    <label for="activo" class="ml-2 text-sm text-gray-700">
                                        Contacto activo
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha (1/3 del ancho) -->
                <div class="lg:col-span-1">
                    
                    <!-- Resumen -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumen</h3>
                            
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Estado:</span>
                                    <span class="font-medium text-green-600">Activo</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tipo:</span>
                                    <span class="font-medium" id="tipo-preview">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Validación:</span>
                                    <span class="font-medium" id="validacion-preview">Pendiente</span>
                                </div>
                            </div>
                            
                            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                                <h4 class="text-sm font-medium text-blue-900 mb-2">Información</h4>
                                <p class="text-xs text-blue-800">
                                    Los campos marcados con asterisco (*) son obligatorios. 
                                    El contacto se creará en estado activo por defecto.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones</h3>
                            
                            <div class="space-y-3">
                                <button type="submit" 
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition duration-150 ease-in-out">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Crear Contacto
                                </button>
                                
                                <a href="{{ route('contactos.index') }}" 
                                   class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-md transition duration-150 ease-in-out">
                                    Cancelar
                                </a>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript para validaciones y preview -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoInputs = document.querySelectorAll('input[name="tipo"]');
    const tipoPreview = document.getElementById('tipo-preview');
    const validacionPreview = document.getElementById('validacion-preview');
    const nombreInput = document.getElementById('nombre');
    const emailInput = document.getElementById('email');
    const telefonoInput = document.getElementById('telefono');

    // Actualizar preview del tipo
    function actualizarTipoPreview() {
        const tipoSeleccionado = document.querySelector('input[name="tipo"]:checked');
        if (tipoSeleccionado) {
            tipoPreview.textContent = tipoSeleccionado.value.charAt(0).toUpperCase() + tipoSeleccionado.value.slice(1);
            tipoPreview.className = 'font-medium text-indigo-600';
        } else {
            tipoPreview.textContent = '-';
            tipoPreview.className = 'font-medium text-gray-400';
        }
        actualizarValidacion();
    }

    // Actualizar validación
    function actualizarValidacion() {
        const nombre = nombreInput.value.trim();
        const email = emailInput.value.trim();
        const telefono = telefonoInput.value.trim();
        const tipo = document.querySelector('input[name="tipo"]:checked');
        
        if (nombre && email && telefono && tipo) {
            // Validar formato de email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (emailRegex.test(email)) {
                validacionPreview.textContent = 'Completo';
                validacionPreview.className = 'font-medium text-green-600';
            } else {
                validacionPreview.textContent = 'Email inválido';
                validacionPreview.className = 'font-medium text-red-600';
            }
        } else {
            validacionPreview.textContent = 'Pendiente';
            validacionPreview.className = 'font-medium text-yellow-600';
        }
    }

    // Event listeners
    tipoInputs.forEach(input => {
        input.addEventListener('change', actualizarTipoPreview);
    });

    [nombreInput, emailInput, telefonoInput].forEach(input => {
        input.addEventListener('input', actualizarValidacion);
    });

    // Validación del formulario
    document.getElementById('contacto-form').addEventListener('submit', function(e) {
        const nombre = nombreInput.value.trim();
        const email = emailInput.value.trim();
        const telefono = telefonoInput.value.trim();
        const tipo = document.querySelector('input[name="tipo"]:checked');
        
        if (!nombre || !email || !telefono || !tipo) {
            e.preventDefault();
            alert('Por favor, complete todos los campos requeridos.');
            return false;
        }
        
        // Validar formato de email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Por favor, ingrese un email válido.');
            return false;
        }
    });

    // Inicializar
    actualizarTipoPreview();
});
</script>
@endsection
