/**
 * Test simple para debuggear el dropdown
 */

import { test, expect } from '@playwright/test';

test.describe('Debug Dropdown', () => {
    test.beforeEach(async ({ page }) => {
        // Navegar al login
        await page.goto('/login');

        // Hacer login
        await page.fill('input[name="email"]', 'admin@test.com');
        await page.fill('input[name="password"]', 'password');
        await page.click('button[type="submit"]');

        // Esperar a que redirija al dashboard
        await page.waitForURL('/dashboard');
        await expect(page).toHaveTitle('Sistema Administrativo');
    });

    test('Debug: Verificar elementos del dropdown', async ({ page }) => {
        // Tomar screenshot inicial
        await page.screenshot({ path: 'debug-before.png' });

        // Verificar que el botón existe
        const reporteButton = page.locator('#reporteButton');
        await expect(reporteButton).toBeVisible();
        console.log('Botón encontrado');

        // Verificar que el dropdown existe
        const dropdown = page.locator('#reporteDropdown');
        await expect(dropdown).toBeAttached();
        console.log('Dropdown encontrado');

        // Verificar clases iniciales del dropdown
        const initialClasses = await dropdown.getAttribute('class');
        console.log('Clases iniciales del dropdown:', initialClasses);

        // Verificar si está visible o no
        const isVisible = await dropdown.isVisible();
        console.log('¿Dropdown visible inicialmente?', isVisible);

        // Hacer clic en el botón
        await reporteButton.click();

        // Tomar screenshot después del clic
        await page.screenshot({ path: 'debug-after-click.png' });

        // Verificar clases después del clic
        const finalClasses = await dropdown.getAttribute('class');
        console.log('Clases después del clic:', finalClasses);

        // Verificar si está visible después del clic
        const isVisibleAfter = await dropdown.isVisible();
        console.log('¿Dropdown visible después del clic?', isVisibleAfter);

        // Verificar que los enlaces están presentes
        const pdfLink = page.locator('a[href*="formato=pdf"]');
        const csvLink = page.locator('a[href*="formato=csv"]');

        await expect(pdfLink).toBeAttached();
        await expect(csvLink).toBeAttached();
        console.log('Enlaces PDF y CSV encontrados');
    });
});
