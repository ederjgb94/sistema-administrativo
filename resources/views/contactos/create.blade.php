@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Crear Nuevo Contacto</h1>
        <a href="{{ route('contactos.index') }}" 
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver
        </a>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('contactos.store') }}" id="contacto-form">
            @csrf

            <!-- Datos Básicos -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Datos Básicos</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombre -->
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nombre" 
                               name="nombre" 
                               value="{{ old('nombre') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               placeholder="Nombre completo del contacto"
                               required>
                        @error('nombre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo <span class="text-red-500">*</span>
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

            <!-- Información de Contacto -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Contacto</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Correo Electrónico <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               placeholder="correo@ejemplo.com"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                            Teléfono <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="telefono" 
                               name="telefono" 
                               value="{{ old('telefono') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               placeholder="55 1234 5678"
                               required>
                        @error('telefono')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Dirección y RFC -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Datos Adicionales</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Dirección -->
                    <div>
                        <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                            Dirección
                        </label>
                        <textarea id="direccion" 
                                  name="direccion" 
                                  rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                  placeholder="Dirección completa">{{ old('direccion') }}</textarea>
                        @error('direccion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- RFC -->
                    <div>
                        <label for="rfc" class="block text-sm font-medium text-gray-700 mb-2">
                            RFC
                        </label>
                        <input type="text" 
                               id="rfc" 
                               name="rfc" 
                               value="{{ old('rfc') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                               placeholder="ABCD123456EFG">
                        @error('rfc')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Estado -->
            <div class="mb-8">
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

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('contactos.index') }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium transition duration-200">
                    Crear Contacto
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript para validaciones -->
<script>
document.getElementById('contacto-form').addEventListener('submit', function(e) {
    const nombre = document.getElementById('nombre').value.trim();
    const email = document.getElementById('email').value.trim();
    const telefono = document.getElementById('telefono').value.trim();
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
</script>
@endsection
