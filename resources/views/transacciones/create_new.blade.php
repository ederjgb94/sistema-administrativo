@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Encabezado -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Nueva Transacción</h1>
                        <p class="text-gray-600 mt-1">Crear un nuevo ingreso o egreso</p>
                    </div>
                    <a href="{{ route('transacciones.index') }}" 
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
        <form action="{{ route('transacciones.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Grid de dos columnas principal -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Columna izquierda (2/3 del ancho) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Información General -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información General</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Folio (solo mostrar) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Folio</label>
                                    <div class="mt-1 text-sm text-gray-900 bg-gray-50 px-3 py-2 rounded-md">
                                        Se generará automáticamente
                                    </div>
                                </div>

                                <!-- Tipo -->
                                <div>
                                    <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo *</label>
                                    <select name="tipo" id="tipo" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('tipo') border-red-300 @enderror">
                                        <option value="">Seleccionar tipo</option>
                                        <option value="ingreso" {{ old('tipo') === 'ingreso' ? 'selected' : '' }}>Ingreso</option>
                                        <option value="egreso" {{ old('tipo') === 'egreso' ? 'selected' : '' }}>Egreso</option>
                                    </select>
                                    @error('tipo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Fecha -->
                                <div>
                                    <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha *</label>
                                    <input type="date" name="fecha" id="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required
                                           max="{{ date('Y-m-d') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('fecha') border-red-300 @enderror">
                                    @error('fecha')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Contacto -->
                                <div>
                                    <label for="contacto_search" class="block text-sm font-medium text-gray-700">Contacto</label>
                                    <div class="relative" x-data="contactoSearch()">
                                        <input type="text" 
                                               x-model="search"
                                               @input="buscarContactos()"
                                               @focus="showDropdown = true"
                                               @click.away="showDropdown = false"
                                               id="contacto_search"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('contacto_id') border-red-300 @enderror"
                                               placeholder="Buscar contacto..."
                                               autocomplete="off">
                                        
                                        <!-- Hidden input para el ID -->
                                        <input type="hidden" name="contacto_id" x-model="selectedId" id="contacto_id">
                                        
                                        <!-- Dropdown de sugerencias -->
                                        <div x-show="showDropdown && resultados.length > 0" 
                                             x-transition
                                             class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                                            <template x-for="contacto in resultados" :key="contacto.id">
                                                <div @click="seleccionarContacto(contacto)"
                                                     class="cursor-pointer select-none relative py-3 px-4 hover:bg-indigo-50 border-b border-gray-100 last:border-b-0">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex-1">
                                                            <div class="flex items-center space-x-2">
                                                                <span class="font-medium text-gray-900" x-text="contacto.nombre"></span>
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                                      :class="{
                                                                          'bg-green-100 text-green-800': contacto.tipo === 'Cliente',
                                                                          'bg-purple-100 text-purple-800': contacto.tipo === 'Proveedor',
                                                                          'bg-blue-100 text-blue-800': contacto.tipo === 'Ambos'
                                                                      }"
                                                                      x-text="contacto.tipo">
                                                                </span>
                                                            </div>
                                                            <p class="text-sm text-gray-500 mt-1" x-text="contacto.descripcion"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                        
                                        <!-- Contacto seleccionado -->
                                        <div x-show="selectedContacto" 
                                             class="mt-2 p-3 bg-indigo-50 rounded-md border border-indigo-200">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-2">
                                                    <span class="font-medium text-indigo-900" x-text="selectedContacto?.nombre"></span>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800" 
                                                          x-text="selectedContacto?.tipo"></span>
                                                </div>
                                                <button type="button" @click="limpiarSeleccion()" 
                                                        class="text-indigo-600 hover:text-indigo-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                            <p class="text-sm text-indigo-700 mt-1" x-text="selectedContacto?.descripcion"></p>
                                        </div>
                                    </div>
                                    @error('contacto_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Referencia -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Referencia</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Tipo -->
                                <div>
                                    <label for="referencia_tipo" class="block text-sm font-medium text-gray-700">Tipo *</label>
                                    <select name="referencia_tipo" id="referencia_tipo" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('referencia_tipo') border-red-300 @enderror">
                                        <option value="">Seleccionar tipo</option>
                                        <option value="obra" {{ old('referencia_tipo') === 'obra' ? 'selected' : '' }}>Obra</option>
                                        <option value="producto" {{ old('referencia_tipo') === 'producto' ? 'selected' : '' }}>Producto</option>
                                        <option value="servicio" {{ old('referencia_tipo') === 'servicio' ? 'selected' : '' }}>Servicio</option>
                                        <option value="otro" {{ old('referencia_tipo') === 'otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                    @error('referencia_tipo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Nombre -->
                                <div>
                                    <label for="referencia_nombre" class="block text-sm font-medium text-gray-700">Nombre *</label>
                                    <input type="text" name="referencia_nombre" id="referencia_nombre" value="{{ old('referencia_nombre') }}" required
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('referencia_nombre') border-red-300 @enderror"
                                           placeholder="Descripción de la referencia">
                                    @error('referencia_nombre')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Datos adicionales -->
                            <div class="mt-6">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Datos Adicionales (Opcional)</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="referencia_descripcion" class="block text-sm font-medium text-gray-600">Descripción</label>
                                        <textarea name="referencia_datos[descripcion]" id="referencia_descripcion" rows="2"
                                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                  placeholder="Descripción detallada">{{ old('referencia_datos.descripcion') }}</textarea>
                                    </div>
                                    <div>
                                        <label for="referencia_ubicacion" class="block text-sm font-medium text-gray-600">Ubicación</label>
                                        <input type="text" name="referencia_datos[ubicacion]" id="referencia_ubicacion" value="{{ old('referencia_datos.ubicacion') }}"
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                               placeholder="Ubicación o dirección">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Facturación -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Facturación</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Tipo de Factura -->
                                <div>
                                    <label for="factura_tipo" class="block text-sm font-medium text-gray-700">Tipo de Factura *</label>
                                    <select name="factura_tipo" id="factura_tipo" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('factura_tipo') border-red-300 @enderror">
                                        <option value="">Seleccionar tipo</option>
                                        <option value="manual" {{ old('factura_tipo') === 'manual' ? 'selected' : '' }}>Manual</option>
                                        <option value="archivo" {{ old('factura_tipo') === 'archivo' ? 'selected' : '' }}>Archivo</option>
                                    </select>
                                    @error('factura_tipo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Número de Factura -->
                                <div>
                                    <label for="factura_numero" class="block text-sm font-medium text-gray-700">Número de Factura</label>
                                    <input type="text" name="factura_numero" id="factura_numero" value="{{ old('factura_numero') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('factura_numero') border-red-300 @enderror"
                                           placeholder="Número de factura">
                                    @error('factura_numero')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Datos manuales de factura -->
                            <div id="datos-manuales" class="mt-6" style="display: none;">
                                <!-- Datos del Emisor -->
                                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                    <h5 class="text-sm font-medium text-gray-700 mb-3">Información del Emisor</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label for="factura_emisor_nombre" class="block text-xs font-medium text-gray-600">Nombre/Razón Social</label>
                                            <input type="text" name="factura_datos[emisor][nombre]" id="factura_emisor_nombre" value="{{ old('factura_datos.emisor.nombre') }}"
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                   placeholder="Nombre del emisor">
                                        </div>
                                        <div>
                                            <label for="factura_emisor_rfc" class="block text-xs font-medium text-gray-600">RFC</label>
                                            <input type="text" name="factura_datos[emisor][rfc]" id="factura_emisor_rfc" value="{{ old('factura_datos.emisor.rfc') }}"
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                   placeholder="RFC">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label for="factura_emisor_direccion" class="block text-xs font-medium text-gray-600">Dirección</label>
                                            <input type="text" name="factura_datos[emisor][direccion]" id="factura_emisor_direccion" value="{{ old('factura_datos.emisor.direccion') }}"
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                   placeholder="Dirección completa">
                                        </div>
                                    </div>
                                </div>

                                <!-- Datos del Receptor -->
                                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                    <h5 class="text-sm font-medium text-gray-700 mb-3">Información del Receptor</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label for="factura_receptor_nombre" class="block text-xs font-medium text-gray-600">Nombre/Razón Social</label>
                                            <input type="text" name="factura_datos[receptor][nombre]" id="factura_receptor_nombre" value="{{ old('factura_datos.receptor.nombre') }}"
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                   placeholder="Nombre del receptor">
                                        </div>
                                        <div>
                                            <label for="factura_receptor_rfc" class="block text-xs font-medium text-gray-600">RFC</label>
                                            <input type="text" name="factura_datos[receptor][rfc]" id="factura_receptor_rfc" value="{{ old('factura_datos.receptor.rfc') }}"
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                   placeholder="RFC">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label for="factura_receptor_direccion" class="block text-xs font-medium text-gray-600">Dirección</label>
                                            <input type="text" name="factura_datos[receptor][direccion]" id="factura_receptor_direccion" value="{{ old('factura_datos.receptor.direccion') }}"
                                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                   placeholder="Dirección completa">
                                        </div>
                                    </div>
                                </div>

                                <!-- Fecha de Emisión -->
                                <div class="mb-4">
                                    <label for="factura_fecha_emision" class="block text-sm font-medium text-gray-700">Fecha de Emisión</label>
                                    <input type="date" name="factura_datos[fecha_emision]" id="factura_fecha_emision" value="{{ old('factura_datos.fecha_emision') }}"
                                           class="mt-1 block w-full md:w-1/3 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>

                                <!-- Conceptos/Productos -->
                                <div class="mb-6">
                                    <div class="flex justify-between items-center mb-3">
                                        <h5 class="text-sm font-medium text-gray-700">Conceptos</h5>
                                        <button type="button" onclick="agregarConcepto()" 
                                                class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Agregar Concepto
                                        </button>
                                    </div>
                                    
                                    <!-- Tabla de conceptos -->
                                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Cantidad</th>
                                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Precio</th>
                                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Total</th>
                                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="conceptos-tbody" class="bg-white divide-y divide-gray-200">
                                                <!-- Los conceptos se agregarán dinámicamente aquí -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Totales -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h5 class="text-sm font-medium text-gray-700 mb-3">Totales</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <div>
                                            <label for="factura_subtotal" class="block text-xs font-medium text-gray-600">Subtotal</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">$</span>
                                                </div>
                                                <input type="number" name="factura_datos[subtotal]" id="factura_subtotal" value="{{ old('factura_datos.subtotal', '0') }}" step="0.01" readonly
                                                       class="block w-full pl-7 pr-3 py-2 border-gray-300 rounded-md shadow-sm bg-gray-100 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            </div>
                                        </div>
                                        <div>
                                            <label for="factura_impuestos" class="block text-xs font-medium text-gray-600">Impuestos</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">$</span>
                                                </div>
                                                <input type="number" name="factura_datos[impuestos]" id="factura_impuestos" value="{{ old('factura_datos.impuestos', '0') }}" step="0.01"
                                                       class="block w-full pl-7 pr-3 py-2 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                       onchange="calcularTotalFactura()">
                                            </div>
                                        </div>
                                        <div>
                                            <label for="factura_total" class="block text-xs font-medium text-gray-600">Total</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 sm:text-sm">$</span>
                                                </div>
                                                <input type="number" name="factura_datos[total]" id="factura_total" value="{{ old('factura_datos.total', '0') }}" step="0.01" readonly
                                                       class="block w-full pl-7 pr-3 py-2 border-gray-300 rounded-md shadow-sm bg-gray-100 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm font-medium">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Condiciones y Notas -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label for="factura_condiciones" class="block text-xs font-medium text-gray-600">Condiciones de Pago</label>
                                        <textarea name="factura_datos[condiciones]" id="factura_condiciones" rows="2"
                                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                  placeholder="Condiciones de pago o entrega">{{ old('factura_datos.condiciones') }}</textarea>
                                    </div>
                                    <div>
                                        <label for="factura_notas" class="block text-xs font-medium text-gray-600">Notas Adicionales</label>
                                        <textarea name="factura_datos[notas]" id="factura_notas" rows="2"
                                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                  placeholder="Notas adicionales">{{ old('factura_datos.notas') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Archivos de factura -->
                            <div id="archivos-factura" class="mt-6" style="display: none;">
                                <h4 class="text-md font-medium text-gray-800 mb-3">Archivos de Factura</h4>
                                <div>
                                    <label for="factura_archivos" class="block text-sm font-medium text-gray-700 mb-2">Subir Archivos</label>
                                    <div class="relative">
                                        <input type="file" name="factura_archivos[]" id="factura_archivos" multiple
                                               accept="image/jpeg,image/jpg,image/png,.pdf,.doc,.docx"
                                               class="hidden"
                                               onchange="mostrarArchivosSeleccionados(this)">
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-indigo-500 hover:bg-indigo-50 transition-colors cursor-pointer"
                                             onclick="document.getElementById('factura_archivos').click()">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="mt-4">
                                                <p class="text-sm text-gray-600">
                                                    <span class="font-medium text-indigo-600 hover:text-indigo-500">Haz clic para subir archivos</span>
                                                    o arrastra y suelta
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">JPG, PNG, PDF, DOC, DOCX hasta 10MB cada uno</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="archivos-seleccionados" class="mt-3"></div>
                                    @error('factura_archivos.*')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Observaciones</h3>
                            <div>
                                <label for="observaciones" class="block text-sm font-medium text-gray-700">Observaciones</label>
                                <textarea name="observaciones" id="observaciones" rows="3"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('observaciones') border-red-300 @enderror"
                                          placeholder="Notas adicionales...">{{ old('observaciones') }}</textarea>
                                @error('observaciones')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha (1/3 del ancho) -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Resumen Financiero -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumen Financiero</h3>
                            
                            <!-- Total -->
                            <div class="mb-4">
                                <label for="total" class="block text-sm font-medium text-gray-700">Total *</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="total" id="total" value="{{ old('total') }}" required
                                           step="0.01" min="0.01"
                                           class="pl-7 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('total') border-red-300 @enderror text-2xl font-bold text-green-600"
                                           placeholder="0.00">
                                </div>
                                @error('total')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Método de Pago -->
                            <div class="mb-4">
                                <label for="metodo_pago_id" class="block text-sm font-medium text-gray-700">Método de Pago *</label>
                                <select name="metodo_pago_id" id="metodo_pago_id" required
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('metodo_pago_id') border-red-300 @enderror">
                                    <option value="">Seleccionar método</option>
                                    @foreach($metodosPago as $id => $nombre)
                                        <option value="{{ $id }}" {{ old('metodo_pago_id') == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                    @endforeach
                                </select>
                                @error('metodo_pago_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Referencia de Pago -->
                            <div>
                                <label for="referencia_pago" class="block text-sm font-medium text-gray-700">Referencia de Pago</label>
                                <input type="text" name="referencia_pago" id="referencia_pago" value="{{ old('referencia_pago') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('referencia_pago') border-red-300 @enderror"
                                       placeholder="Número de comprobante...">
                                @error('referencia_pago')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones</h3>
                            
                            <div class="space-y-3">
                                <button type="submit" 
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Crear Transacción
                                </button>
                                
                                <a href="{{ route('transacciones.index') }}" 
                                   class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
            this.search = contacto.texto_completo;
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

document.addEventListener('DOMContentLoaded', function() {
    const facturaTypo = document.getElementById('factura_tipo');
    const datosManuales = document.getElementById('datos-manuales');
    const archivosFactura = document.getElementById('archivos-factura');

    function toggleFacturaFields() {
        const value = facturaTypo.value;
        
        // Ocultar todos primero
        datosManuales.style.display = 'none';
        archivosFactura.style.display = 'none';
        
        // Mostrar según el tipo
        if (value === 'manual') {
            datosManuales.style.display = 'block';
            // Asegurarse de que haya al menos una fila de concepto
            const tbody = document.getElementById('conceptos-tbody');
            if (tbody.children.length === 0) {
                agregarConcepto();
            }
        }
        
        if (value === 'archivo') {
            archivosFactura.style.display = 'block';
        }
    }

    facturaTypo.addEventListener('change', toggleFacturaFields);
    
    // Ejecutar al cargar por si hay valor previo
    toggleFacturaFields();
});

// Función para mostrar archivos seleccionados
function mostrarArchivosSeleccionados(input) {
    const container = document.getElementById('archivos-seleccionados');
    container.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        const div = document.createElement('div');
        div.className = 'bg-gray-50 border border-gray-200 rounded-lg p-3';
        
        const title = document.createElement('p');
        title.className = 'text-sm font-medium text-gray-900 mb-2';
        title.textContent = `${input.files.length} archivo${input.files.length > 1 ? 's' : ''} seleccionado${input.files.length > 1 ? 's' : ''}:`;
        div.appendChild(title);
        
        const list = document.createElement('ul');
        list.className = 'text-sm text-gray-600 space-y-1';
        
        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            const li = document.createElement('li');
            li.className = 'flex items-center space-x-2';
            
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            li.innerHTML = `
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span>${file.name} (${fileSize} MB)</span>
            `;
            list.appendChild(li);
        }
        
        div.appendChild(list);
        container.appendChild(div);
    }
}

// Variables para el manejo de conceptos
let conceptoIndex = 0;

// Función para agregar un nuevo concepto
function agregarConcepto() {
    const tbody = document.getElementById('conceptos-tbody');
    const newRow = document.createElement('tr');
    newRow.setAttribute('data-index', conceptoIndex);
    newRow.innerHTML = `
        <td class="px-3 py-2">
            <input type="text" name="factura_datos[conceptos][${conceptoIndex}][descripcion]" 
                   class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                   placeholder="Descripción del producto/servicio" required>
        </td>
        <td class="px-3 py-2">
            <input type="number" name="factura_datos[conceptos][${conceptoIndex}][cantidad]" 
                   class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm cantidad-input"
                   min="1" step="1" value="1" onchange="calcularTotalConcepto(this)" required>
        </td>
        <td class="px-3 py-2">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                    <span class="text-gray-500 text-sm">$</span>
                </div>
                <input type="number" name="factura_datos[conceptos][${conceptoIndex}][precio]" 
                       class="block w-full pl-6 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm precio-input"
                       min="0" step="0.01" value="0.00" onchange="calcularTotalConcepto(this)" required>
            </div>
        </td>
        <td class="px-3 py-2">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                    <span class="text-gray-500 text-sm">$</span>
                </div>
                <input type="number" name="factura_datos[conceptos][${conceptoIndex}][total]" 
                       class="block w-full pl-6 border-gray-300 rounded-md shadow-sm bg-gray-100 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm total-concepto"
                       readonly value="0.00">
            </div>
        </td>
        <td class="px-3 py-2">
            <button type="button" onclick="eliminarConcepto(this)" 
                    class="text-red-600 hover:text-red-900 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </td>
    `;
    
    tbody.appendChild(newRow);
    conceptoIndex++;
    
    // Si es el primer concepto, calcular inmediatamente
    if (tbody.children.length === 1) {
        calcularSubtotal();
    }
}

// Función para eliminar un concepto
function eliminarConcepto(button) {
    const row = button.closest('tr');
    row.remove();
    calcularSubtotal();
}

// Función para calcular el total de un concepto individual
function calcularTotalConcepto(input) {
    const row = input.closest('tr');
    const cantidadInput = row.querySelector('.cantidad-input');
    const precioInput = row.querySelector('.precio-input');
    const totalInput = row.querySelector('.total-concepto');
    
    const cantidad = parseFloat(cantidadInput.value) || 0;
    const precio = parseFloat(precioInput.value) || 0;
    const total = cantidad * precio;
    
    totalInput.value = total.toFixed(2);
    
    // Recalcular subtotal
    calcularSubtotal();
}

// Función para calcular el subtotal
function calcularSubtotal() {
    const totalesConceptos = document.querySelectorAll('.total-concepto');
    let subtotal = 0;
    
    totalesConceptos.forEach(input => {
        subtotal += parseFloat(input.value) || 0;
    });
    
    document.getElementById('factura_subtotal').value = subtotal.toFixed(2);
    calcularTotalFactura();
}

// Función para calcular el total final de la factura
function calcularTotalFactura() {
    const subtotal = parseFloat(document.getElementById('factura_subtotal').value) || 0;
    const impuestos = parseFloat(document.getElementById('factura_impuestos').value) || 0;
    const total = subtotal + impuestos;
    
    document.getElementById('factura_total').value = total.toFixed(2);
}

// Agregar un concepto por defecto al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Solo agregar un concepto por defecto si estamos en modo manual
    const facturaTypo = document.getElementById('factura_tipo');
    if (facturaTypo && facturaTypo.value === 'manual') {
        // Verificar si hay conceptos antiguos para restaurar
        const oldConceptos = @json(old('factura_datos.conceptos', []));
        
        if (oldConceptos && oldConceptos.length > 0) {
            // Restaurar conceptos de la validación fallida
            oldConceptos.forEach((concepto, index) => {
                agregarConcepto();
                // Rellenar los valores
                const row = document.querySelector(`tr[data-index="${index}"]`);
                if (row) {
                    row.querySelector('input[name*="[descripcion]"]').value = concepto.descripcion || '';
                    row.querySelector('input[name*="[cantidad]"]').value = concepto.cantidad || '1';
                    row.querySelector('input[name*="[precio]"]').value = concepto.precio || '0.00';
                    row.querySelector('input[name*="[total]"]').value = concepto.total || '0.00';
                }
            });
        } else {
            // Agregar un concepto vacío por defecto
            agregarConcepto();
        }
        
        // Restaurar totales si existen
        const oldSubtotal = @json(old('factura_datos.subtotal', ''));
        const oldImpuestos = @json(old('factura_datos.impuestos', ''));
        const oldTotal = @json(old('factura_datos.total', ''));
        
        if (oldSubtotal) document.getElementById('factura_subtotal').value = oldSubtotal;
        if (oldImpuestos) document.getElementById('factura_impuestos').value = oldImpuestos;
        if (oldTotal) document.getElementById('factura_total').value = oldTotal;
    }
});
</script>
@endsection
