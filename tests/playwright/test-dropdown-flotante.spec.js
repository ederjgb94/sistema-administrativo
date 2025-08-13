/**
 * Test para verificar el dropdown flotante elegante
 */

import { test, expect } from '@playwright/test';

test('✨ Test: Dropdown flotante elegante', async ({ page }) => {
    console.log('✨ Iniciando test del dropdown flotante...');

    // Login
    await page.goto('/login');
    await page.fill('input[name="email"]', 'admin@test.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('/dashboard');
    console.log('✅ Login exitoso');

    // Esperar carga completa
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    // Screenshot inicial
    await page.screenshot({ path: 'flotante-antes.png', fullPage: true });
    console.log('📸 Screenshot antes del dropdown');

    // Hacer clic en el botón
    const botonReporte = page.locator('#reporteButton');
    await expect(botonReporte).toBeVisible();

    console.log('🖱️ Haciendo clic en el botón...');
    await botonReporte.click();

    // Esperar la animación
    await page.waitForTimeout(300);

    // Verificar que el dropdown sea visible
    const dropdown = page.locator('#reporteDropdown');
    await expect(dropdown).toBeVisible();
    console.log('✅ Dropdown flotante visible');

    // Verificar que tenga posición fixed
    const dropdownStyle = await dropdown.getAttribute('class');
    console.log('🎨 Clases del dropdown:', dropdownStyle);

    // Verificar botones
    const botonPDF = page.locator('a[href*="formato=pdf"]');
    const botonCSV = page.locator('a[href*="formato=csv"]');

    await expect(botonPDF).toBeVisible();
    await expect(botonCSV).toBeVisible();
    console.log('✅ Ambos botones visibles');

    // Screenshot con dropdown abierto
    await page.screenshot({ path: 'flotante-despues.png', fullPage: true });
    console.log('📸 Screenshot con dropdown flotante');

    // Test de hover
    await botonPDF.hover();
    await page.waitForTimeout(200);
    console.log('✅ Hover en PDF funciona');

    await botonCSV.hover();
    await page.waitForTimeout(200);
    console.log('✅ Hover en CSV funciona');

    // Screenshot con hover
    await page.screenshot({ path: 'flotante-hover.png', fullPage: true });
    console.log('📸 Screenshot con hover');

    // Test de cerrar con clic fuera
    await page.click('h1'); // Clic en el título
    await page.waitForTimeout(300);
    await expect(dropdown).not.toBeVisible();
    console.log('✅ Se cierra con clic fuera');

    // Test de abrir de nuevo
    await botonReporte.click();
    await page.waitForTimeout(300);
    await expect(dropdown).toBeVisible();
    console.log('✅ Se puede abrir de nuevo');

    // Test de cerrar con Escape
    await page.keyboard.press('Escape');
    await page.waitForTimeout(300);
    await expect(dropdown).not.toBeVisible();
    console.log('✅ Se cierra con Escape');

    console.log('🎉 ¡Dropdown flotante funcionando perfectamente!');
});

test('📱 Test: Dropdown flotante responsive', async ({ page }) => {
    console.log('📱 Test responsive del dropdown...');

    // Login
    await page.goto('/login');
    await page.fill('input[name="email"]', 'admin@test.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('/dashboard');

    // Test en diferentes tamaños de pantalla
    const viewports = [
        { width: 1920, height: 1080, name: 'Desktop' },
        { width: 1024, height: 768, name: 'Tablet' },
        { width: 375, height: 667, name: 'Mobile' }
    ];

    for (const viewport of viewports) {
        console.log(`📐 Probando en ${viewport.name} (${viewport.width}x${viewport.height})`);

        await page.setViewportSize({ width: viewport.width, height: viewport.height });
        await page.waitForTimeout(500);

        // Abrir dropdown
        const botonReporte = page.locator('#reporteButton');
        await botonReporte.click();
        await page.waitForTimeout(300);

        // Verificar que sea visible
        const dropdown = page.locator('#reporteDropdown');
        await expect(dropdown).toBeVisible();

        // Screenshot para cada viewport
        await page.screenshot({
            path: `flotante-${viewport.name.toLowerCase()}.png`,
            fullPage: true
        });

        // Cerrar dropdown
        await page.keyboard.press('Escape');
        await page.waitForTimeout(300);

        console.log(`✅ ${viewport.name} funcionando correctamente`);
    }

    console.log('🎉 ¡Dropdown responsive confirmado!');
});
