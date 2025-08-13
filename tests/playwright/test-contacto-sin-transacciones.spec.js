import { test, expect } from '@playwright/test';
import fs from 'fs';
import path from 'path';

test.describe('Reporte de Contacto Sin Transacciones', () => {
    test.beforeEach(async ({ page }) => {
        // Navegar a la página de reportes
        await page.goto('/reportes');
        await page.waitForLoadState('networkidle');
    });

    test('debe generar PDF cuando contacto no tiene transacciones', async ({ page }) => {
        console.log('🎯 Probando generación de PDF para contacto sin transacciones...');

        // Buscar el formulario de "Reporte por Contacto"
        await page.waitForSelector('text=Reporte por Contacto');

        // Hacer clic en el campo de búsqueda de contacto
        const searchInput = page.locator('input[placeholder*="Buscar contacto"]');
        await searchInput.click();

        // Buscar un contacto (asumiendo que existe uno sin transacciones)
        await searchInput.fill('Test');
        await page.waitForTimeout(1000);

        // Esperar que aparezcan resultados
        await page.waitForSelector('.cursor-pointer.select-none', { timeout: 5000 });

        // Seleccionar el primer contacto de la lista
        const primerContacto = page.locator('.cursor-pointer.select-none').first();
        await primerContacto.click();

        // Verificar que se haya seleccionado un contacto
        const contactoSeleccionado = page.locator('[x-show="selectedContacto"]');
        await expect(contactoSeleccionado).toBeVisible();

        // Preparar para interceptar la descarga
        const downloadPromise = page.waitForEvent('download');

        // Hacer clic en el botón PDF
        const botonPDF = page.locator('button[name="formato"][value="pdf"]');
        await botonPDF.click();

        // Esperar la descarga
        const download = await downloadPromise;

        // Verificar que el archivo se descargó
        expect(download.suggestedFilename()).toMatch(/reporte_contacto_.*\.pdf$/);

        console.log(`✅ PDF descargado: ${download.suggestedFilename()}`);

        // Guardar el archivo para verificación manual
        const downloadPath = path.join(__dirname, '../test-results', download.suggestedFilename());
        await download.saveAs(downloadPath);

        // Verificar que el archivo existe y tiene contenido
        expect(fs.existsSync(downloadPath)).toBeTruthy();
        const stats = fs.statSync(downloadPath);
        expect(stats.size).toBeGreaterThan(1000); // PDF debe tener al menos 1KB

        console.log(`✅ Archivo guardado: ${downloadPath} (${stats.size} bytes)`);
    });

    test('debe generar CSV cuando contacto no tiene transacciones', async ({ page }) => {
        console.log('🎯 Probando generación de CSV para contacto sin transacciones...');

        // Buscar el formulario de "Reporte por Contacto"
        await page.waitForSelector('text=Reporte por Contacto');

        // Hacer clic en el campo de búsqueda de contacto
        const searchInput = page.locator('input[placeholder*="Buscar contacto"]');
        await searchInput.click();

        // Buscar un contacto
        await searchInput.fill('Test');
        await page.waitForTimeout(1000);

        // Esperar que aparezcan resultados
        await page.waitForSelector('.cursor-pointer.select-none', { timeout: 5000 });

        // Seleccionar el primer contacto de la lista
        const primerContacto = page.locator('.cursor-pointer.select-none').first();
        await primerContacto.click();

        // Verificar que se haya seleccionado un contacto
        const contactoSeleccionado = page.locator('[x-show="selectedContacto"]');
        await expect(contactoSeleccionado).toBeVisible();

        // Preparar para interceptar la descarga
        const downloadPromise = page.waitForEvent('download');

        // Hacer clic en el botón CSV
        const botonCSV = page.locator('button[name="formato"][value="csv"]');
        await botonCSV.click();

        // Esperar la descarga
        const download = await downloadPromise;

        // Verificar que el archivo se descargó
        expect(download.suggestedFilename()).toMatch(/reporte_contacto_.*\.csv$/);

        console.log(`✅ CSV descargado: ${download.suggestedFilename()}`);

        // Guardar el archivo para verificación manual
        const downloadPath = path.join(__dirname, '../test-results', download.suggestedFilename());
        await download.saveAs(downloadPath);

        // Verificar que el archivo existe y tiene contenido
        expect(fs.existsSync(downloadPath)).toBeTruthy();
        const stats = fs.statSync(downloadPath);
        expect(stats.size).toBeGreaterThan(100); // CSV debe tener al menos 100 bytes

        // Leer el contenido del CSV para verificar que tiene el mensaje informativo
        const csvContent = fs.readFileSync(downloadPath, 'utf8');
        expect(csvContent).toContain('INFORMACIÓN');
        expect(csvContent).toContain('no tiene transacciones');

        console.log(`✅ Archivo guardado: ${downloadPath} (${stats.size} bytes)`);
        console.log('✅ CSV contiene mensaje informativo para contacto sin transacciones');
    });

    test('debe generar reporte con filtros de fecha aplicados', async ({ page }) => {
        console.log('🎯 Probando generación con filtros de fecha...');

        // Buscar el formulario de "Reporte por Contacto"
        await page.waitForSelector('text=Reporte por Contacto');

        // Hacer clic en el campo de búsqueda de contacto
        const searchInput = page.locator('input[placeholder*="Buscar contacto"]');
        await searchInput.click();

        // Buscar un contacto
        await searchInput.fill('Test');
        await page.waitForTimeout(1000);

        // Esperar que aparezcan resultados
        await page.waitForSelector('.cursor-pointer.select-none', { timeout: 5000 });

        // Seleccionar el primer contacto de la lista
        const primerContacto = page.locator('.cursor-pointer.select-none').first();
        await primerContacto.click();

        // Establecer fechas
        const fechaInicio = page.locator('input[name="fecha_inicio"]');
        await fechaInicio.fill('2024-01-01');

        const fechaFin = page.locator('input[name="fecha_fin"]');
        await fechaFin.fill('2024-12-31');

        // Seleccionar tipo de transacción
        const tipoTransaccion = page.locator('select[name="tipo_transaccion"]');
        await tipoTransaccion.selectOption('ingreso');

        // Preparar para interceptar la descarga
        const downloadPromise = page.waitForEvent('download');

        // Hacer clic en el botón CSV
        const botonCSV = page.locator('button[name="formato"][value="csv"]');
        await botonCSV.click();

        // Esperar la descarga
        const download = await downloadPromise;

        // Verificar nombre de archivo incluye filtros
        const filename = download.suggestedFilename();
        expect(filename).toMatch(/reporte_contacto_.*ingreso.*2024.*\.csv$/);

        console.log(`✅ CSV con filtros descargado: ${filename}`);

        // Guardar el archivo para verificación manual
        const downloadPath = path.join(__dirname, '../test-results', filename);
        await download.saveAs(downloadPath);

        // Leer contenido para verificar que incluye información de filtros
        const csvContent = fs.readFileSync(downloadPath, 'utf8');
        expect(csvContent).toContain('Tipo de Transacción: Ingreso');
        expect(csvContent).toContain('Período:');

        console.log('✅ CSV contiene información de filtros aplicados');
    });
});
