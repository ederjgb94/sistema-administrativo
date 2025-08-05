@extends('layo            Ver
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">tion('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Editar Contacto: {{ $contacto->nombre }}</h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('contactos.show', $contacto) }}" 
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Ver
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
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200">
                <div class="p-6">
                    <form method="POST" action="{{ route('contactos.update', $contacto) }}" id="contacto-form">
                        @csrf
                        @method('PUT')

                        <!-- Tipo de contacto -->
                        <div class="mb-6">
                            <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de Contacto <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="relative cursor-pointer">
                                    <input type="radio" 
                                           name="tipo" 
                                           value="cliente" 
                                           {{ old('tipo', $contacto->tipo) === 'cliente' ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 transition-all duration-200">
                                        <div class="flex items-center justify-center mb-2">
                                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <div class="font-medium text-gray-900">Cliente</div>
                                            <div class="text-sm text-gray-600">Persona que compra</div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer">
                                    <input type="radio" 
                                           name="tipo" 
                                           value="proveedor" 
                                           {{ old('tipo', $contacto->tipo) === 'proveedor' ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all duration-200">
                                        <div class="flex items-center justify-center mb-2">
                                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <div class="font-medium text-gray-900">Proveedor</div>
                                            <div class="text-sm text-gray-600">Persona que vende</div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer">
                                    <input type="radio" 
                                           name="tipo" 
                                           value="ambos" 
                                           {{ old('tipo', $contacto->tipo) === 'ambos' ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200">
                                        <div class="flex items-center justify-center mb-2">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <div class="font-medium text-gray-900">Ambos</div>
                                            <div class="text-sm text-gray-600">Cliente y proveedor</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('tipo')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Información básica -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Nombre -->
                            <div>
                                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre Completo <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="nombre" 
                                       id="nombre" 
                                       value="{{ old('nombre', $contacto->nombre) }}"
                                       placeholder="Ej: Juan Pérez Rodríguez"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('nombre') border-red-300 @enderror">
                                @error('nombre')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- RFC -->
                            <div>
                                <label for="rfc" class="block text-sm font-medium text-gray-700 mb-2">
                                    RFC
                                </label>
                                <input type="text" 
                                       name="rfc" 
                                       id="rfc" 
                                       value="{{ old('rfc', $contacto->rfc) }}"
                                       placeholder="Ej: PERJ850101ABC"
                                       maxlength="13"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('rfc') border-red-300 @enderror">
                                @error('rfc')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Información de contacto -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Correo Electrónico
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <input type="email" 
                                           name="email" 
                                           id="email" 
                                           value="{{ old('email', $contacto->email) }}"
                                           placeholder="ejemplo@correo.com"
                                           class="w-full pl-10 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-300 @enderror">
                                </div>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Teléfono -->
                            <div>
                                <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                                    Teléfono
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                    <input type="tel" 
                                           name="telefono" 
                                           id="telefono" 
                                           value="{{ old('telefono', $contacto->telefono) }}"
                                           placeholder="+52 55 1234 5678"
                                           class="w-full pl-10 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('telefono') border-red-300 @enderror">
                                </div>
                                @error('telefono')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div class="mb-6">
                            <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                                Dirección
                            </label>
                            <textarea name="direccion" 
                                      id="direccion" 
                                      rows="3"
                                      placeholder="Calle, número, colonia, ciudad, estado, código postal..."
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('direccion') border-red-300 @enderror">{{ old('direccion', $contacto->direccion) }}</textarea>
                            @error('direccion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Estado -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="activo" 
                                       id="activo" 
                                       value="1"
                                       {{ old('activo', $contacto->activo) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="activo" class="ml-2 block text-sm text-gray-900">
                                    Contacto activo
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-600">Los contactos activos aparecen en las búsquedas y transacciones.</p>
                        </div>

                        <!-- Información de auditoría -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Información del registro</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                                <div>
                                    <span class="font-medium">Creado:</span> 
                                    {{ $contacto->created_at->format('d/m/Y H:i') }}
                                </div>
                                <div>
                                    <span class="font-medium">Última actualización:</span> 
                                    {{ $contacto->updated_at->format('d/m/Y H:i') }}
                                </div>
                                @if($contacto->deleted_at)
                                    <div class="md:col-span-2">
                                        <span class="font-medium text-red-600">Eliminado:</span> 
                                        {{ $contacto->deleted_at->format('d/m/Y H:i') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                            <a href="{{ route('contactos.show', $contacto) }}" 
                               class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-md transition duration-200">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition duration-200 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Actualizar Contacto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Formateo automático del RFC
        document.getElementById('rfc').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        });

        // Formateo automático del teléfono
        document.getElementById('telefono').addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, '');
            if (value.length >= 10) {
                if (value.startsWith('52')) {
                    value = '+52 ' + value.substring(2, 4) + ' ' + value.substring(4, 8) + ' ' + value.substring(8, 12);
                } else {
                    value = value.substring(0, 2) + ' ' + value.substring(2, 6) + ' ' + value.substring(6, 10);
                }
            }
            this.value = value;
        });

        // Validación del formulario
        document.getElementById('contacto-form').addEventListener('submit', function(e) {
            const tipo = document.querySelector('input[name="tipo"]:checked');
            const nombre = document.getElementById('nombre').value.trim();

            if (!tipo) {
                e.preventDefault();
                alert('Por favor selecciona el tipo de contacto.');
                return;
            }

            if (!nombre) {
                e.preventDefault();
                alert('Por favor ingresa el nombre del contacto.');
                document.getElementById('nombre').focus();
                return;
            }
        });
    </script>
</div>
@endsection
