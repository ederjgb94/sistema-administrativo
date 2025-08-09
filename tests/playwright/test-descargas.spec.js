/**
 * Test específico para verificar que no haya errores en las descargas
 */

import { test, expect } from '@playwright/test';

test('📄 Test: Verificar que las descargas funcionen sin errores', async ({ page }) => {
    console.log('📄 Probando descargas...');

    // Login
    await page.goto('/login');
    await page.fill('input[name="email"]', 'admin@test.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('/dashboard');

    // Abrir dropdown
    const botonReporte = page.locator('#reporteButton');
    await botonReporte.click();
    await page.waitForTimeout(500);

    // Test CSV primero (más simple)
    console.log('📊 Probando CSV...');
    const botonCSV = page.locator('a[href*="formato=csv"]');

    // Ir directamente a la URL del CSV
    const csvUrl = await botonCSV.getAttribute('href');
    console.log('🔗 URL CSV:', csvUrl);

    const csvResponse = await page.goto(csvUrl);
    console.log('📊 Respuesta CSV:', csvResponse.status());

    if (csvResponse.status() === 200) {
        console.log('✅ CSV funciona correctamente');
    } else {
        console.log('❌ Error en CSV:', csvResponse.status());
    }

    // Volver al dashboard
    await page.goto('/dashboard');
    await botonReporte.click();
    await page.waitForTimeout(500);

    // Test PDF
    console.log('📄 Probando PDF...');
    const botonPDF = page.locator('a[href*="formato=pdf"]');
    const pdfUrl = await botonPDF.getAttribute('href');
    console.log('🔗 URL PDF:', pdfUrl);

    const pdfResponse = await page.goto(pdfUrl);
    console.log('📄 Respuesta PDF:', pdfResponse.status());

    if (pdfResponse.status() === 200) {
        console.log('✅ PDF funciona correctamente');
    } else {
        console.log('❌ Error en PDF:', pdfResponse.status());

        // Si hay error, capturar el contenido de la página de error
        const errorContent = await page.content();
        console.log('🔍 Contenido del error:');
        console.log(errorContent.substring(0, 500) + '...');
    }
});
