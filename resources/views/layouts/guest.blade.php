<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex">
            <!-- Left Panel - Login Form -->
            <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24">
                <div class="mx-auto w-full max-w-sm lg:w-96">
                    <div>
                        <div class="flex justify-center mb-8">
                            <x-application-logo class="w-16 h-16 fill-current text-indigo-600" />
                        </div>
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900 text-center mb-2">
                            Sistema Administrativo
                        </h2>
                        <p class="text-center text-sm text-gray-600 mb-8">
                            Inicia sesión para acceder al sistema
                        </p>
                    </div>

                    <div class="bg-white py-8 px-6 shadow-xl rounded-xl border border-gray-100">
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <!-- Right Panel - Background Image/Pattern -->
            <div class="hidden lg:block relative flex-1">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-purple-600 to-blue-600">
                    <div class="absolute inset-0 bg-black opacity-20"></div>
                    <!-- Pattern overlay -->
                    <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.1\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"4\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                </div>
                
                <!-- Content overlay -->
                <div class="relative z-10 flex flex-col justify-center h-full px-16 text-white">
                    <h1 class="text-4xl font-bold mb-6">
                        Gestiona tu negocio de manera eficiente
                    </h1>
                    <p class="text-xl mb-8 text-white/90">
                        Control completo de contactos, transacciones y métodos de pago en una sola plataforma.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Gestión de contactos (clientes/proveedores)</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Control de transacciones e ingresos</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Métodos de pago flexibles</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Reportes y análisis detallados</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
