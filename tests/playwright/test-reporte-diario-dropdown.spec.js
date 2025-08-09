/**
 * Test específico para verificar que el dropdown del botón "Reporte Diario" funcione
 */

import { test, expect } from '@playwright/test';

test('📋 Verificar que funciona el dropdown del botón Reporte Diario', async ({ page }) => {
    console.log('🔍 Iniciando test del botón Reporte Diario...');

    // 1. Ir al login y autenticarse
    await page.goto('/login');
    console.log('📍 Navegando a la página de login');

    await page.fill('input[name="email"]', 'admin@test.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('/dashboard');
    console.log('✅ Login exitoso - En el dashboard');

    // 2. Localizar el botón de "Reporte Diario"
    const reporteButton = page.locator('#reporteButton');
    await expect(reporteButton).toBeVisible();
    console.log('✅ Botón "Reporte Diario" encontrado y visible');

    // 3. Verificar que el texto del botón sea correcto
    await expect(reporteButton).toContainText('Reporte Diario');
    console.log('✅ Texto del botón verificado: "Reporte Diario"');

    // 4. Verificar que el dropdown existe pero esté oculto inicialmente
    const dropdown = page.locator('#reporteDropdown');
    await expect(dropdown).not.toBeVisible();
    console.log('✅ Dropdown inicialmente oculto (correcto)');

    // 5. Hacer clic en el botón de "Reporte Diario"
    console.log('🖱️ Haciendo clic en el botón "Reporte Diario"...');
    await reporteButton.click();

    // 6. Esperar un momento para que aparezca el dropdown
    await page.waitForTimeout(300);

    // 7. Verificar que el dropdown se haga visible
    await expect(dropdown).toBeVisible();
    console.log('✅ ¡DROPDOWN APARECE CORRECTAMENTE!');

    // 8. Verificar que el dropdown contiene las opciones PDF y CSV
    const pdfOption = page.locator('a[href*="formato=pdf"]');
    const csvOption = page.locator('a[href*="formato=csv"]');

    await expect(pdfOption).toBeVisible();
    await expect(csvOption).toBeVisible();
    console.log('✅ Opciones PDF y CSV visibles en el dropdown');

    // 9. Verificar los textos de las opciones
    await expect(pdfOption).toContainText('Descargar PDF');
    await expect(csvOption).toContainText('Descargar CSV');
    console.log('✅ Textos de las opciones correctos');

    // 10. Verificar que se puede cerrar haciendo clic fuera
    await page.click('h1'); // Hacer clic en el título para cerrar
    await page.waitForTimeout(200);
    await expect(dropdown).not.toBeVisible();
    console.log('✅ Dropdown se cierra al hacer clic fuera');

    // 11. Abrir dropdown nuevamente para verificar que funciona múltiples veces
    await reporteButton.click();
    await page.waitForTimeout(200);
    await expect(dropdown).toBeVisible();
    console.log('✅ Dropdown se puede abrir múltiples veces');

    // 12. Verificar que se cierra con la tecla Escape
    await page.keyboard.press('Escape');
    await page.waitForTimeout(200);
    await expect(dropdown).not.toBeVisible();
    console.log('✅ Dropdown se cierra con tecla Escape');

    // 13. Screenshot final mostrando el dropdown abierto
    await reporteButton.click();
    await page.waitForTimeout(200);
    await page.screenshot({
        path: 'reporte-diario-dropdown-funcionando.png',
        fullPage: true
    });
    console.log('📸 Screenshot guardado: reporte-diario-dropdown-funcionando.png');

    console.log('🎉 ¡TEST COMPLETADO! El botón Reporte Diario funciona perfectamente');
});

test('📱 Verificar dropdown en dispositivo móvil', async ({ page }) => {
    // Simular dispositivo móvil
    await page.setViewportSize({ width: 375, height: 667 });

    console.log('📱 Probando dropdown en móvil...');

    // Login
    await page.goto('/login');
    await page.fill('input[name="email"]', 'admin@test.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('/dashboard');

    // Localizar botón y verificar funcionamiento
    const reporteButton = page.locator('#reporteButton');
    const dropdown = page.locator('#reporteDropdown');

    await expect(reporteButton).toBeVisible();
    await expect(dropdown).not.toBeVisible();

    // Probar con touch (evento táctil)
    await reporteButton.tap();
    await page.waitForTimeout(300);

    await expect(dropdown).toBeVisible();
    console.log('✅ Dropdown funciona en móvil con tap');

    // Screenshot móvil
    await page.screenshot({
        path: 'reporte-diario-movil.png',
        fullPage: true
    });
    console.log('📸 Screenshot móvil guardado');
});
