const { test, expect } = require('@playwright/test');

test.describe('Funcionalidad de Exportar PDF', () => {
    test.beforeEach(async ({ page }) => {
        // Ir a la página de login
        await page.goto('/login');

        // Hacer login (asumiendo que hay un usuario admin)
        await page.fill('input[name="email"]', 'admin@admin.com');
        await page.fill('input[name="password"]', 'password');
        await page.click('button[type="submit"]');

        // Esperar redirección al dashboard
        await expect(page).toHaveURL('/dashboard');

        // Ir a transacciones
        await page.goto('/transacciones');
    });

    test('debe mostrar el botón de exportar PDF', async ({ page }) => {
        // Verificar que el botón de exportar esté presente
        const exportButton = page.locator('button:has-text("Exportar PDF")');
        await expect(exportButton).toBeVisible();

        // Verificar el tooltip
        await expect(exportButton).toHaveAttribute('title', /Generar PDF con todas las transacciones/);
    });

    test('debe generar y descargar PDF sin filtros', async ({ page }) => {
        // Preparar para interceptar la descarga
        const downloadPromise = page.waitForEvent('download');

        // Hacer clic en el botón de exportar PDF
        await page.click('button:has-text("Exportar PDF")');

        // Confirmar en el dialog
        page.on('dialog', dialog => dialog.accept());

        // Esperar la descarga
        const download = await downloadPromise;

        // Verificar que el archivo se descargó
        expect(download.suggestedFilename()).toMatch(/transacciones_.*\.pdf$/);

        console.log(`✅ PDF descargado: ${download.suggestedFilename()}`);
    });

    test('debe generar PDF con filtros aplicados', async ({ page }) => {
        // Aplicar filtro por tipo
        await page.selectOption('select[name="tipo"]', 'ingreso');
        await page.click('button:has-text("Filtrar")');

        // Esperar que la página se actualice con los filtros
        await expect(page).toHaveURL(/tipo=ingreso/);

        // Preparar para interceptar la descarga
        const downloadPromise = page.waitForEvent('download');

        // Hacer clic en el botón de exportar PDF
        await page.click('button:has-text("Exportar PDF")');

        // Confirmar en el dialog
        page.on('dialog', dialog => dialog.accept());

        // Esperar la descarga
        const download = await downloadPromise;

        // Verificar que el archivo se descargó
        expect(download.suggestedFilename()).toMatch(/transacciones_.*\.pdf$/);

        console.log(`✅ PDF con filtros descargado: ${download.suggestedFilename()}`);
    });

    test('debe mostrar estado de carga durante la generación', async ({ page }) => {
        // Hacer clic en el botón de exportar PDF
        const exportButton = page.locator('button:has-text("Exportar PDF")');

        // Confirmar en el dialog que aparecerá
        page.on('dialog', dialog => dialog.accept());

        await exportButton.click();

        // Verificar que el botón muestra el estado de carga
        await expect(page.locator('button:has-text("Generando PDF...")')).toBeVisible();

        // Verificar que el botón está deshabilitado durante la carga
        await expect(exportButton).toBeDisabled();
    });

    test('debe aplicar filtros de fecha al PDF', async ({ page }) => {
        // Aplicar filtros de fecha
        const fechaDesde = page.locator('input[name="fecha_desde"]');
        const fechaHasta = page.locator('input[name="fecha_hasta"]');

        await fechaDesde.fill('2024-01-01');
        await fechaHasta.fill('2024-12-31');

        // Filtrar
        await page.click('button:has-text("Filtrar")');

        // Esperar que la URL se actualice con los filtros
        await expect(page).toHaveURL(/fecha_desde=2024-01-01/);

        // Preparar para interceptar la descarga
        const downloadPromise = page.waitForEvent('download');

        // Confirmar en el dialog
        page.on('dialog', dialog => dialog.accept());

        // Exportar
        await page.click('button:has-text("Exportar PDF")');

        // Verificar descarga
        const download = await downloadPromise;
        expect(download.suggestedFilename()).toMatch(/transacciones_.*\.pdf$/);

        console.log(`✅ PDF con filtros de fecha descargado: ${download.suggestedFilename()}`);
    });
});
