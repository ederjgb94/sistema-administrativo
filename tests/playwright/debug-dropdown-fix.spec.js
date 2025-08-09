/**
 * Debug del dropdown de reportes
 */

import { test, expect } from '@playwright/test';

test('🔍 Debug: Verificar dropdown de reportes', async ({ page }) => {
    console.log('🔍 Iniciando debug del dropdown...');

    // Login
    await page.goto('/login');
    await page.fill('input[name="email"]', 'admin@test.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('/dashboard');

    console.log('✅ Login exitoso, verificando elementos...');

    // Verificar que los elementos existen
    const reporteButton = page.locator('#reporteButton');
    const reporteDropdown = page.locator('#reporteDropdown');
    const reporteContainer = page.locator('#reporteDropdownContainer');

    console.log('🔍 Verificando existencia de elementos...');
    await expect(reporteButton).toBeVisible();
    console.log('✅ Botón de reporte visible');

    await expect(reporteContainer).toBeVisible();
    console.log('✅ Container visible');

    // Verificar si el dropdown existe pero está oculto
    const dropdownExists = await reporteDropdown.count();
    console.log(`🔍 Dropdown existe: ${dropdownExists > 0 ? 'SÍ' : 'NO'}`);

    if (dropdownExists > 0) {
        const isHidden = await reporteDropdown.getAttribute('class');
        console.log(`🔍 Clases del dropdown: ${isHidden}`);

        // Verificar estado inicial (debe estar oculto)
        await expect(reporteDropdown).not.toBeVisible();
        console.log('✅ Dropdown inicialmente oculto');

        // Hacer clic en el botón
        console.log('🖱️ Haciendo clic en el botón...');
        await reporteButton.click();

        // Esperar un momento
        await page.waitForTimeout(500);

        // Verificar si se muestra
        const isVisible = await reporteDropdown.isVisible();
        console.log(`🔍 ¿Dropdown visible después del clic? ${isVisible ? 'SÍ' : 'NO'}`);

        if (isVisible) {
            console.log('✅ ¡Dropdown funciona correctamente!');

            // Verificar enlaces
            const pdfLink = page.locator('a[href*="formato=pdf"]');
            const csvLink = page.locator('a[href*="formato=csv"]');

            await expect(pdfLink).toBeVisible();
            await expect(csvLink).toBeVisible();
            console.log('✅ Enlaces PDF y CSV visibles');

        } else {
            console.log('❌ Dropdown NO se muestra al hacer clic');

            // Obtener clases después del clic
            const classesAfterClick = await reporteDropdown.getAttribute('class');
            console.log(`🔍 Clases después del clic: ${classesAfterClick}`);

            // Verificar errores en consola
            const consoleLogs = [];
            page.on('console', msg => consoleLogs.push(msg.text()));

            // Intentar forzar el dropdown
            await page.evaluate(() => {
                const dropdown = document.getElementById('reporteDropdown');
                if (dropdown) {
                    dropdown.classList.remove('hidden');
                    console.log('🔧 Forzando mostrar dropdown');
                }
            });

            await page.waitForTimeout(500);

            const isForcedVisible = await reporteDropdown.isVisible();
            console.log(`🔍 ¿Visible después de forzar? ${isForcedVisible ? 'SÍ' : 'NO'}`);
        }
    } else {
        console.log('❌ Dropdown no existe en el DOM');
    }

    // Tomar screenshot para inspección visual
    await page.screenshot({ path: 'debug-dropdown.png', fullPage: true });
    console.log('📸 Screenshot guardado: debug-dropdown.png');

    // Ejecutar JavaScript para debug adicional
    const debugInfo = await page.evaluate(() => {
        const button = document.getElementById('reporteButton');
        const dropdown = document.getElementById('reporteDropdown');
        const container = document.getElementById('reporteDropdownContainer');

        return {
            buttonExists: !!button,
            dropdownExists: !!dropdown,
            containerExists: !!container,
            buttonClasses: button?.className || 'No existe',
            dropdownClasses: dropdown?.className || 'No existe',
            containerClasses: container?.className || 'No existe',
            hasEventListeners: button ? 'Se verificará en devtools' : 'Botón no existe'
        };
    });

    console.log('🔍 Debug Info:', JSON.stringify(debugInfo, null, 2));
});
