/**
 * Test para el dropdown de reporte diario
 * Verifica que el dropdown funcione correctamente y permita descargar reportes
 */

import { test, expect } from '@playwright/test';

test.describe('Dropdown Reporte Diario', () => {
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

    test('Dropdown aparece al hacer clic en el botón de reporte', async ({ page }) => {
        // Verificar que el botón existe
        const reporteButton = page.locator('#reporteButton');
        await expect(reporteButton).toBeVisible();

        // Verificar que el dropdown está oculto inicialmente
        const dropdown = page.locator('#reporteDropdown');
        await expect(dropdown).not.toBeVisible();

        // Hacer clic en el botón
        await reporteButton.click();

        // Verificar que el dropdown ahora es visible
        await expect(dropdown).toBeVisible();
    });

    test('Dropdown contiene opciones de PDF y CSV', async ({ page }) => {
        // Abrir el dropdown
        await page.click('#reporteButton');

        // Verificar que las opciones están presentes
        const pdfOption = page.locator('a[href*="formato=pdf"]');
        const csvOption = page.locator('a[href*="formato=csv"]');

        await expect(pdfOption).toBeVisible();
        await expect(csvOption).toBeVisible();

        // Verificar el texto de las opciones
        await expect(pdfOption).toContainText('Descargar PDF');
        await expect(csvOption).toContainText('Descargar CSV');
    });

    test('Dropdown se cierra al hacer clic fuera', async ({ page }) => {
        // Abrir el dropdown
        await page.click('#reporteButton');
        const dropdown = page.locator('#reporteDropdown');
        await expect(dropdown).toBeVisible();

        // Hacer clic en otro lugar
        await page.click('body');

        // Verificar que el dropdown se cerró
        await expect(dropdown).not.toBeVisible();
    });

    test('Dropdown se cierra al presionar Escape', async ({ page }) => {
        // Abrir el dropdown
        await page.click('#reporteButton');
        const dropdown = page.locator('#reporteDropdown');
        await expect(dropdown).toBeVisible();

        // Presionar Escape
        await page.keyboard.press('Escape');

        // Verificar que el dropdown se cerró
        await expect(dropdown).not.toBeVisible();
    });

    test('Puede iniciar descarga de PDF', async ({ page }) => {
        // Abrir dropdown primero
        await page.click('#reporteButton');

        // Configurar el listener para descargas
        const downloadPromise = page.waitForEvent('download');

        // Hacer clic en PDF
        await page.click('a[href*="formato=pdf"]');

        // Esperar y verificar la descarga
        const download = await downloadPromise;
        expect(download.suggestedFilename()).toMatch(/reporte_diario_.*\.pdf/);
    });

    test('Puede iniciar descarga de CSV', async ({ page }) => {
        // Abrir dropdown primero
        await page.click('#reporteButton');

        // Configurar el listener para descargas
        const downloadPromise = page.waitForEvent('download');

        // Hacer clic en CSV
        await page.click('a[href*="formato=csv"]');

        // Esperar y verificar la descarga
        const download = await downloadPromise;
        expect(download.suggestedFilename()).toMatch(/reporte_diario_.*\.csv/);
    });

    test('Dropdown se cierra después de hacer clic en una opción', async ({ page }) => {
        // Abrir el dropdown
        await page.click('#reporteButton');
        const dropdown = page.locator('#reporteDropdown');
        await expect(dropdown).toBeVisible();

        // Interceptar la navegación para evitar descarga
        await page.route('**/reporte-diario*', route => route.abort());

        // Hacer clic en una opción
        await page.click('a[href*="formato=pdf"]');

        // Verificar que el dropdown se cerró (simplificado)
        await page.waitForTimeout(500); // Dar tiempo para que el JS actúe
        await expect(dropdown).not.toBeVisible();
    });

    test('Elementos del dropdown tienen estilos correctos', async ({ page }) => {
        // Abrir el dropdown
        await page.click('#reporteButton');

        // Verificar estilos del dropdown
        const dropdown = page.locator('#reporteDropdown');
        await expect(dropdown).toHaveClass(/bg-white/);
        await expect(dropdown).toHaveClass(/shadow-xl/);

        // Verificar que las opciones tienen hover effects
        const pdfOption = page.locator('a[href*="formato=pdf"]');
        await expect(pdfOption).toHaveClass(/hover:bg-indigo-50/);
    });

    test('Métricas del día se muestran correctamente', async ({ page }) => {
        // Verificar que las métricas están visibles (ser más específicos)
        await expect(page.locator('p:has-text("Ingresos")').first()).toBeVisible();
        await expect(page.locator('p:has-text("Egresos")').first()).toBeVisible();
        await expect(page.locator('p:has-text("Balance")').first()).toBeVisible();

        // Verificar que tienen valores numéricos (formato $x,xxx)
        await expect(page.locator('text=/\\$[\\d,]+/')).toHaveCount({ min: 3 });
    });
});
