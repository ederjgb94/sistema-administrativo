#!/bin/bash

# Script de configuración para Sistema Administrativo
# Implementa mejores prácticas de Context7 para Laravel

echo "🚀 Configurando Sistema Administrativo con Context7..."
echo "================================================="

# Verificar que estemos en el directorio correcto
if [ ! -f "artisan" ]; then
    echo "❌ Error: No se encontró artisan. Ejecuta desde el directorio raíz del proyecto Laravel."
    exit 1
fi

echo "📦 Instalando dependencias..."

# Instalar dependencias de PHP
composer install --no-dev --optimize-autoloader

# Instalar dependencias de Node.js
npm install

# Instalar browsers de Playwright
npx playwright install chromium

echo "🔧 Configurando ambiente..."

# Generar key de aplicación si no existe
php artisan key:generate --ansi

# Ejecutar migraciones
echo "📊 Ejecutando migraciones..."
php artisan migrate --force

# Ejecutar seeders
echo "🌱 Poblando base de datos..."
php artisan db:seed --force

# Compilar assets
echo "🎨 Compilando assets..."
npm run build

# Ejecutar tests de Laravel
echo "🧪 Ejecutando tests de Laravel..."
php artisan test

echo "✅ Configuración completada!"
echo ""
echo "🎯 Próximos pasos:"
echo "1. Iniciar servidor: php artisan serve"
echo "2. Ejecutar tests UI: npx playwright test"
echo "3. Ver reporte: npx playwright show-report"
echo ""
echo "📋 Credenciales de prueba:"
echo "Email: admin@admin.com"
echo "Password: admin"
echo ""
echo "📚 Comandos útiles:"
echo "- Tests completos: npm run test"
echo "- Tests UI modo: npx playwright test --ui"
echo "- Tests con browser: npx playwright test --headed"
echo ""
echo "🎉 ¡Sistema listo para desarrollo de CRUD!"
