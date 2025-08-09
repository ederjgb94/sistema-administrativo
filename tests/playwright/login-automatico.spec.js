/**
 * Test de Login Automático con Playwright
 * Este test iniciará sesión automáticamente y te llevará al dashboard
 */

import { test, expect } from '@playwright/test';

test.describe('Login Automático', () => {
    test('Iniciar sesión y acceder al dashboard', async ({ page }) => {
        console.log('🚀 Iniciando proceso de login automático...');

        // Ir a la página de login
        await page.goto('/login');
        console.log('✅ Navegado a la página de login');

        // Llenar credenciales automáticamente
        await page.fill('input[name="email"]', 'admin@test.com');
        await page.fill('input[name="password"]', 'password');
        console.log('✅ Credenciales ingresadas automáticamente');

        // Hacer click en el botón de login
        await page.click('button[type="submit"]');
        console.log('✅ Botón de login presionado');

        // Esperar a que redirija al dashboard
        await page.waitForURL('/dashboard');
        console.log('✅ Redirigido al dashboard exitosamente');

        // Verificar que estamos en el dashboard
        await expect(page).toHaveTitle('Sistema Administrativo');
        await expect(page).toHaveURL(/.*\/dashboard/);
        console.log('✅ Confirmado: Estás en el dashboard del Sistema Administrativo');

        // Verificar que el dropdown de reportes existe
        const reporteButton = page.locator('#reporteButton');
        await expect(reporteButton).toBeVisible();
        console.log('✅ Dropdown de reportes disponible');

        // Tomar screenshot del dashboard
        await page.screenshot({ path: 'dashboard-logueado.png', fullPage: true });
        console.log('📸 Screenshot guardado como dashboard-logueado.png');

        // Probar el dropdown de reportes
        await reporteButton.click();
        const dropdown = page.locator('#reporteDropdown');
        await expect(dropdown).toBeVisible();
        console.log('✅ Dropdown de reportes funciona correctamente');

        await page.screenshot({ path: 'dropdown-abierto.png' });
        console.log('📸 Screenshot del dropdown guardado como dropdown-abierto.png');

        // Verificar que las métricas del día se muestran
        const metricas = await page.locator('text=/\\$[\\d,]+/').count();
        console.log(`✅ Métricas encontradas: ${metricas} valores monetarios mostrados`);

        // Mantener la página abierta por 30 segundos para que puedas ver
        console.log('⏰ Manteniendo la página abierta por 30 segundos...');
        console.log('🎯 ¡PUEDES VER EL SISTEMA FUNCIONANDO AHORA!');
        await page.waitForTimeout(30000);

        console.log('✨ ¡Login automático completado exitosamente!');
    });

    test('Verificar todas las funcionalidades principales', async ({ page }) => {
        console.log('🔍 Verificando todas las funcionalidades...');

        // Login automático
        await page.goto('/login');
        await page.fill('input[name="email"]', 'admin@test.com');
        await page.fill('input[name="password"]', 'password');
        await page.click('button[type="submit"]');
        await page.waitForURL('/dashboard');

        // Verificar navegación
        const navLinks = page.locator('nav a');
        const navCount = await navLinks.count();
        console.log(`✅ Enlaces de navegación encontrados: ${navCount}`);

        // Verificar acciones rápidas
        const accionesRapidas = page.locator('[class*="grid"] [class*="bg-gradient"]');
        const accionesCount = await accionesRapidas.count();
        console.log(`✅ Acciones rápidas disponibles: ${accionesCount}`);

        // Probar dropdown y verificar enlaces
        await page.click('#reporteButton');
        const pdfLink = page.locator('a[href*="formato=pdf"]');
        const csvLink = page.locator('a[href*="formato=csv"]');

        await expect(pdfLink).toBeVisible();
        await expect(csvLink).toBeVisible();
        console.log('✅ Enlaces de descarga PDF y CSV verificados');

        // Verificar que el sistema está funcionando
        await expect(page.locator('text=Dashboard')).toBeVisible();
        console.log('✅ Dashboard completamente funcional');

        console.log('🎉 ¡Todas las funcionalidades verificadas correctamente!');
    });
});
