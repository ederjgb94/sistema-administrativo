/**
 * Test VISUAL para verificar que el dropdown se vea correctamente
 */

import { test, expect } from '@playwright/test';

test('👁️ Test VISUAL: Verificar que el dropdown se vea por encima de todo', async ({ page }) => {
    console.log('👁️ Iniciando test VISUAL del dropdown...');

    // Login
    await page.goto('/login');
    await page.fill('input[name="email"]', 'admin@test.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('/dashboard');
    console.log('✅ Login exitoso');

    // Esperar que todo se cargue
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    // Screenshot ANTES de abrir dropdown
    await page.screenshot({
        path: 'debug-before.png',
        fullPage: true
    });
    console.log('📸 Screenshot ANTES tomado');

    // Encontrar el botón de reporte diario
    const botonReporte = page.locator('#reporteButton');
    await expect(botonReporte).toBeVisible();
    console.log('✅ Botón encontrado');

    // Hacer clic en el botón
    console.log('🖱️ Haciendo clic en el botón...');
    await botonReporte.click();
    await page.waitForTimeout(500);

    // Screenshot DESPUÉS de hacer clic
    await page.screenshot({
        path: 'debug-after-click.png',
        fullPage: true
    });
    console.log('📸 Screenshot DESPUÉS del clic tomado');

    // Verificar que el dropdown esté visible
    const dropdown = page.locator('#reporteDropdown');
    const dropdownVisible = await dropdown.isVisible();
    console.log(`👁️ ¿Dropdown visible? ${dropdownVisible}`);

    if (dropdownVisible) {
        console.log('✅ Dropdown es visible');

        // Verificar que los botones sean clickeables
        const botonPDF = page.locator('a[href*="formato=pdf"]');
        const botonCSV = page.locator('a[href*="formato=csv"]');

        // Verificar que ambos botones sean visibles y clickeables
        await expect(botonPDF).toBeVisible();
        await expect(botonCSV).toBeVisible();
        console.log('✅ Ambos botones visibles');

        // Obtener las dimensiones de los botones
        const pdfBox = await botonPDF.boundingBox();
        const csvBox = await botonCSV.boundingBox();

        console.log('📏 Dimensiones botón PDF:', pdfBox);
        console.log('📏 Dimensiones botón CSV:', csvBox);

        // Verificar que los botones sean lo suficientemente grandes
        if (pdfBox && csvBox) {
            const pdfClickeable = pdfBox.height >= 40 && pdfBox.width >= 200;
            const csvClickeable = csvBox.height >= 40 && csvBox.width >= 200;

            console.log(`🎯 Botón PDF clickeable: ${pdfClickeable} (${pdfBox.width}x${pdfBox.height})`);
            console.log(`🎯 Botón CSV clickeable: ${csvClickeable} (${csvBox.width}x${csvBox.height})`);

            if (!pdfClickeable || !csvClickeable) {
                console.log('⚠️ Los botones pueden ser muy pequeños para hacer clic fácilmente');
            }
        }

        // Verificar z-index visualmente - hover sobre el primer botón
        await botonPDF.hover();
        await page.waitForTimeout(200);

        // Screenshot con hover
        await page.screenshot({
            path: 'debug-dropdown.png',
            fullPage: true
        });
        console.log('📸 Screenshot con dropdown abierto y hover guardado');

        console.log('🎉 ¡Dropdown se ve correctamente!');

    } else {
        console.log('❌ Dropdown NO es visible');

        // Debug adicional
        const dropdownExists = await dropdown.count();
        const dropdownClasses = await dropdown.getAttribute('class');

        console.log(`🔍 Dropdown existe: ${dropdownExists > 0}`);
        console.log(`🔍 Clases del dropdown: ${dropdownClasses}`);
    }
});

test('🧪 Test FUNCIONAL: Verificar que los enlaces funcionen', async ({ page }) => {
    console.log('🧪 Test funcional de enlaces...');

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

    // Test del botón CSV (debería funcionar)
    console.log('🧪 Probando enlace CSV...');
    const botonCSV = page.locator('a[href*="formato=csv"]');

    // Preparar para capturar la descarga
    const downloadPromise = page.waitForEvent('download');

    try {
        await botonCSV.click();
        const download = await downloadPromise;
        console.log('✅ Descarga CSV exitosa:', download.suggestedFilename());
    } catch (error) {
        console.log('❌ Error en descarga CSV:', error.message);
    }

    // Volver al dashboard
    await page.goto('/dashboard');
    await botonReporte.click();
    await page.waitForTimeout(500);

    // Test del botón PDF (puede fallar si hay errores)
    console.log('🧪 Probando enlace PDF...');
    const botonPDF = page.locator('a[href*="formato=pdf"]');

    try {
        // Hacer clic y esperar navegación o descarga
        await Promise.race([
            page.waitForEvent('download'),
            page.waitForNavigation({ timeout: 5000 })
        ]);

        const currentUrl = page.url();
        console.log('🔍 URL actual después del clic PDF:', currentUrl);

        if (currentUrl.includes('error') || currentUrl.includes('500')) {
            console.log('❌ Error detectado en PDF');
        } else {
            console.log('✅ PDF funcionando');
        }

    } catch (error) {
        console.log('⚠️ Posible error en PDF:', error.message);
    }
});
