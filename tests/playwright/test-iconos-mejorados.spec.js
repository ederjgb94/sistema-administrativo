import { test, expect } from '@playwright/test';

test.describe('🎨 Test: Iconos mejorados PDF y CSV', () => {
    test('✨ Verificar iconos específicos en dropdown', async ({ page }) => {
        console.log('🎨 Probando iconos mejorados...');

        // Login
        await page.goto('/login');
        await page.fill('input[name="email"]', 'admin@test.com');
        await page.fill('input[name="password"]', 'password');
        await page.click('button[type="submit"]');
        await page.waitForURL('/dashboard');
        console.log('✅ Login exitoso');

        // Abrir dropdown
        console.log('🖱️ Abriendo dropdown...');
        const botonReporte = page.locator('#reporteButton');
        await botonReporte.click();
        await page.waitForTimeout(500);

        // Verificar que el dropdown sea visible
        const dropdown = page.locator('#reporteDropdown');
        await expect(dropdown).toBeVisible();
        console.log('✅ Dropdown visible');

        // Verificar iconos específicos de PDF
        const iconoPDF = dropdown.locator('svg').first();
        await expect(iconoPDF).toBeVisible();
        console.log('✅ Icono PDF visible');

        // Verificar iconos específicos de CSV  
        const iconoCSV = dropdown.locator('svg').nth(1);
        await expect(iconoCSV).toBeVisible();
        console.log('✅ Icono CSV visible');

        // Verificar texto actualizado
        const textoPDF = page.locator('text=📕 Descargar PDF');
        const textoCSV = page.locator('text=📊 Descargar CSV');

        await expect(textoPDF).toBeVisible();
        await expect(textoCSV).toBeVisible();
        console.log('✅ Textos con emojis correctos');

        // Screenshot final con los nuevos iconos
        await page.screenshot({
            path: 'iconos-mejorados-final.png',
            fullPage: true
        });
        console.log('📸 Screenshot con iconos mejorados');

        console.log('🎉 ¡Iconos PDF y CSV mejorados correctamente!');
    });
});
