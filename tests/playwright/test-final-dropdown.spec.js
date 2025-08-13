/**
 * Test final para verificar que el dropdown funciona perfectamente
 */

import { test, expect } from '@playwright/test';

test('🎯 Test Final: Dropdown de reportes funcional', async ({ page }) => {
    console.log('🎯 Verificación final del dropdown...');

    // Login
    await page.goto('/login');
    await page.fill('input[name="email"]', 'admin@test.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('/dashboard');

    console.log('✅ Login exitoso');

    // Verificar elementos
    const reporteButton = page.locator('#reporteButton');
    const reporteDropdown = page.locator('#reporteDropdown');

    await expect(reporteButton).toBeVisible();
    console.log('✅ Botón visible');

    // Estado inicial - dropdown oculto
    await expect(reporteDropdown).not.toBeVisible();
    console.log('✅ Dropdown inicialmente oculto');

    // Hacer clic en el botón
    await reporteButton.click();
    console.log('🖱️ Clic en botón ejecutado');

    // Verificar que el dropdown se muestra
    await expect(reporteDropdown).toBeVisible();
    console.log('✅ Dropdown se muestra correctamente');

    // Verificar que los enlaces están presentes
    const pdfLink = page.locator('a[href*="formato=pdf"]');
    const csvLink = page.locator('a[href*="formato=csv"]');

    await expect(pdfLink).toBeVisible();
    await expect(csvLink).toBeVisible();
    console.log('✅ Enlaces PDF y CSV visibles');

    // Verificar textos
    await expect(pdfLink).toContainText('Descargar PDF');
    await expect(csvLink).toContainText('Descargar CSV');
    console.log('✅ Textos de enlaces correctos');

    // Cerrar dropdown haciendo clic fuera
    await page.click('h1'); // Hacer clic en el título
    await expect(reporteDropdown).not.toBeVisible();
    console.log('✅ Dropdown se cierra al hacer clic fuera');

    // Abrir dropdown de nuevo
    await reporteButton.click();
    await expect(reporteDropdown).toBeVisible();
    console.log('✅ Dropdown se puede abrir de nuevo');

    // Cerrar con Escape
    await page.keyboard.press('Escape');
    await expect(reporteDropdown).not.toBeVisible();
    console.log('✅ Dropdown se cierra con Escape');

    // Test de funciones globales de debug
    const debugResult = await page.evaluate(() => {
        if (window.toggleReporteDropdown && window.closeReporteDropdown) {
            window.toggleReporteDropdown(true);
            const isVisible = !document.getElementById('reporteDropdown').classList.contains('hidden');
            window.closeReporteDropdown();
            const isClosed = document.getElementById('reporteDropdown').classList.contains('hidden');
            return { functionsExist: true, toggleWorks: isVisible, closeWorks: isClosed };
        }
        return { functionsExist: false };
    });

    console.log('🔧 Debug functions test:', debugResult);

    // Screenshot final
    await reporteButton.click(); // Mostrar dropdown para screenshot
    await page.screenshot({ path: 'dropdown-funcionando.png', fullPage: true });
    console.log('📸 Screenshot guardado: dropdown-funcionando.png');

    console.log('🎉 ¡DROPDOWN FUNCIONANDO PERFECTAMENTE!');
});
