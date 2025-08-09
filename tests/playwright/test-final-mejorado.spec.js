/**
 * Test simple para verificar que el dropdown se vea y funcione correctamente
 */

import { test, expect } from '@playwright/test';

test('🎯 Test FINAL: Dropdown visible y clickeable', async ({ page }) => {
    console.log('🎯 Test final del dropdown mejorado...');

    // Login
    await page.goto('/login');
    await page.fill('input[name="email"]', 'admin@test.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('/dashboard');

    // Esperar que todo cargue
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    // Hacer clic en el botón de reporte
    const botonReporte = page.locator('#reporteButton');
    await botonReporte.click();
    await page.waitForTimeout(500);

    // Verificar que el dropdown sea visible
    const dropdown = page.locator('#reporteDropdown');
    await expect(dropdown).toBeVisible();
    console.log('✅ Dropdown visible');

    // Verificar que los botones estén en el viewport
    const botonPDF = page.locator('a[href*="formato=pdf"]');
    const botonCSV = page.locator('a[href*="formato=csv"]');

    await expect(botonPDF).toBeVisible();
    await expect(botonCSV).toBeVisible();
    console.log('✅ Ambos botones visibles');

    // Verificar que se puedan hacer hover (están en viewport)
    await botonPDF.hover();
    console.log('✅ Hover en botón PDF exitoso');

    await botonCSV.hover();
    console.log('✅ Hover en botón CSV exitoso');

    // Screenshot final
    await page.screenshot({
        path: 'dropdown-funcionando-exito.png',
        fullPage: true
    });
    console.log('📸 Screenshot guardado: dropdown-funcionando-exito.png');

    console.log('🎉 ¡Dropdown mejorado funcionando perfectamente!');
});
