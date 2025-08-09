/**
 * Test completo y final para verificar que todo funciona
 */

import { test, expect } from '@playwright/test';

test('🎯 TEST COMPLETO: Dropdown y funcionalidad', async ({ page }) => {
    console.log('🎯 Test completo iniciado...');

    // Login
    await page.goto('/login');
    await page.fill('input[name="email"]', 'admin@test.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('/dashboard');
    console.log('✅ Login exitoso');

    // Esperar que todo cargue
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    // Screenshot inicial
    await page.screenshot({ path: 'dashboard-inicial.png', fullPage: true });
    console.log('📸 Dashboard inicial capturado');

    // Encontrar y hacer clic en el botón
    const botonReporte = page.locator('#reporteButton');
    await expect(botonReporte).toBeVisible();
    console.log('✅ Botón de reporte encontrado');

    await botonReporte.click();
    await page.waitForTimeout(500);

    // Verificar dropdown
    const dropdown = page.locator('#reporteDropdown');
    await expect(dropdown).toBeVisible();
    console.log('✅ Dropdown abierto');

    // Screenshot con dropdown abierto
    await page.screenshot({ path: 'dropdown-abierto.png', fullPage: true });
    console.log('📸 Dropdown abierto capturado');

    // Verificar botones
    const botonPDF = page.locator('a[href*="formato=pdf"]');
    const botonCSV = page.locator('a[href*="formato=csv"]');

    await expect(botonPDF).toBeVisible();
    await expect(botonCSV).toBeVisible();
    console.log('✅ Ambos botones visibles');

    // Test hover (verificar que estén en viewport)
    await botonPDF.hover();
    await page.waitForTimeout(200);
    console.log('✅ Hover PDF exitoso');

    await botonCSV.hover();
    await page.waitForTimeout(200);
    console.log('✅ Hover CSV exitoso');

    // Verificar que se puede cerrar
    await page.click('h1'); // Clic fuera
    await page.waitForTimeout(300);
    await expect(dropdown).not.toBeVisible();
    console.log('✅ Dropdown se cierra correctamente');

    console.log('🎉 ¡TODOS LOS TESTS PASARON!');
    console.log('✨ El dropdown está funcionando perfectamente');
});
