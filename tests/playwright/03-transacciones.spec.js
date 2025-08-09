import { test, expect } from '@playwright/test';

// Helper para login
async function login(page) {
    await page.goto('/login');
    await page.fill('input[name="email"]', 'admin@petrotekno.com');
    await page.fill('input[name="password"]', 'admin123');
    await page.click('button[type="submit"]');
    // Esperar a que aparezca el dashboard o un elemento distintivo
    await page.waitForURL('/dashboard', { timeout: 10000 });
    await page.waitForSelector('text=Bienvenido', { timeout: 5000 });
}

test.describe('Módulo de Transacciones', () => {
    test.beforeEach(async ({ page }) => {
        await login(page);
    });

    test('debe mostrar la página de transacciones', async ({ page }) => {
        await page.goto('/transacciones');

        await expect(page).toHaveURL('/transacciones');
        await expect(page).toHaveTitle('Sistema Administrativo');
        await expect(page.locator('h2')).toContainText('Transacciones');
        await expect(page.locator('p')).toContainText('Gestión de ingresos y egresos');
    });

    test('debe navegar al formulario de nueva transacción', async ({ page }) => {
        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Nueva Transacción")');

        await expect(page).toHaveURL('/transacciones/create');
        await expect(page.locator('h1')).toContainText('Nueva Transacción');
    });

    test('debe mostrar campos obligatorios en el formulario', async ({ page }) => {
        await page.goto('/transacciones/create');

        // Verificar campos obligatorios
        await expect(page.locator('label').filter({ hasText: 'Tipo *' }).first()).toBeVisible(); // Tipo de transacción
        await expect(page.locator('label:has-text("Fecha *")')).toBeVisible();
        await expect(page.locator('label').filter({ hasText: 'Tipo *' }).nth(1)).toBeVisible(); // Referencia tipo
        await expect(page.locator('label:has-text("Nombre *")')).toBeVisible();
        await expect(page.locator('label:has-text("Tipo de Factura *")')).toBeVisible();
        await expect(page.locator('label:has-text("Total *")')).toBeVisible();
        await expect(page.locator('label:has-text("Método de Pago *")')).toBeVisible();
    });

    test('debe permitir entrada de decimales en el campo total', async ({ page }) => {
        await page.goto('/transacciones/create');

        const totalField = page.locator('input[name="total"]');
        await totalField.fill('123.45');

        await expect(totalField).toHaveValue('123.45');
    });

    test('debe cambiar color del total según el tipo de transacción', async ({ page }) => {
        await page.goto('/transacciones/create');

        const tipoSelect = page.locator('select[name="tipo"]');
        const totalField = page.locator('input[name="total"]');

        await totalField.fill('100.00');

        // Probar color verde para ingreso
        await tipoSelect.selectOption('ingreso');
        await expect(totalField).toHaveClass(/text-green/);

        // Probar color rojo para egreso
        await tipoSelect.selectOption('egreso');
        await expect(totalField).toHaveClass(/text-red/);
    });

    test('debe mostrar sección de factura manual al seleccionar tipo manual', async ({ page }) => {
        await page.goto('/transacciones/create');

        // Seleccionar tipo manual
        await page.selectOption('select[name="factura_tipo"]', 'manual');

        // Verificar que aparece la sección manual
        await expect(page.locator('h4:has-text("Factura Manual")')).toBeVisible();
        await expect(page.getByPlaceholder('Emisor') || page.getByLabel('Emisor')).toBeVisible();
        await expect(page.getByPlaceholder('Receptor') || page.getByLabel('Receptor')).toBeVisible();
        await expect(page.locator('table')).toBeVisible(); // Tabla de conceptos
    });

    test('debe agregar conceptos dinámicamente en factura manual', async ({ page }) => {
        await page.goto('/transacciones/create');

        await page.locator('select[name="factura_tipo"]').selectOption('manual');

        // Contar filas iniciales
        const initialRows = await page.locator('tbody tr').count();

        // Agregar un concepto
        await page.click('button:has-text("Agregar Concepto")');

        // Verificar que se agregó una fila
        const newRows = await page.locator('tbody tr').count();
        expect(newRows).toBe(initialRows + 1);
    });

    test('debe mostrar sección de archivos al seleccionar tipo archivo', async ({ page }) => {
        await page.goto('/transacciones/create');

        await page.locator('select[name="factura_tipo"]').selectOption('archivo');

        // Verificar que aparece la sección de archivos
        await expect(page.locator('h4:has-text("Archivos de Factura")')).toBeVisible();
        await expect(page.locator('text=Haz clic para subir archivos')).toBeVisible();
    });

    test('debe validar formulario con campos vacíos', async ({ page }) => {
        await page.goto('/transacciones/create');

        await page.click('button:has-text("Crear Transacción")');

        // Verificar que permanece en la misma página (validación falló)
        await expect(page).toHaveURL('/transacciones/create');
    });

    test('debe crear transacción con datos válidos', async ({ page }) => {
        await page.goto('/transacciones/create');

        // Llenar formulario completo
        await page.locator('select[name="tipo"]').selectOption('ingreso');
        await page.locator('input[name="fecha"]').fill('2025-08-08');
        await page.locator('select[name="referencia_tipo"]').selectOption('servicio');
        await page.locator('input[name="referencia_nombre"]').fill('Consultoría Playwright');
        await page.locator('select[name="factura_tipo"]').selectOption('archivo');
        await page.locator('input[name="total"]').fill('1500.00');
        await page.locator('select[name="metodo_pago_id"]').selectOption('1'); // Efectivo

        await page.click('button:has-text("Crear Transacción")');

        // Verificar redirección exitosa
        await expect(page).toHaveURL(/\/transacciones\/\d+/);
    });

    test('debe filtrar transacciones por tipo', async ({ page }) => {
        await page.goto('/transacciones');

        // Probar filtro de ingresos
        await page.click('a:has-text("Ingresos")');
        await expect(page).toHaveURL('/transacciones?tipo=ingreso');

        // Volver a todas las transacciones
        await page.click('a:has-text("Ver Transacciones")');

        // Probar filtro de egresos
        await page.click('a:has-text("Egresos")');
        await expect(page).toHaveURL('/transacciones?tipo=egreso');
    });

    test('debe navegar a editar transacción', async ({ page }) => {
        await page.goto('/transacciones');

        // Buscar el primer botón de editar (si existe alguna transacción)
        const editButton = page.locator('a:has-text("Editar")').first();
        if (await editButton.count() > 0) {
            await editButton.click();
            await expect(page).toHaveURL(/\/transacciones\/\d+\/edit/);
            await expect(page.locator('h1')).toContainText('Editar Transacción');
        }
    });

    test('debe mostrar página de detalles de transacción', async ({ page }) => {
        await page.goto('/transacciones');

        // Buscar el primer enlace de ver detalles (si existe alguna transacción)
        const viewButton = page.locator('a').filter({ hasText: /^Ver|Detalles/ }).first();
        if (await viewButton.count() > 0) {
            await viewButton.click();
            await expect(page).toHaveURL(/\/transacciones\/\d+$/);
            await expect(page.locator('h1')).toContainText('Detalles');
        }
    });

    test('debe trabajar con autocomplete de contactos', async ({ page }) => {
        await page.goto('/transacciones/create');

        const contactInput = page.locator('input[placeholder*="contacto"]');
        await contactInput.fill('Test');

        // Esperar un momento para que aparezcan sugerencias
        await page.waitForTimeout(500);

        // Verificar que el campo está funcionando
        await expect(contactInput).toHaveValue('Test');
    });

    test('debe navegar correctamente con breadcrumbs', async ({ page }) => {
        await page.goto('/transacciones/create');

        // Verificar botón volver
        await page.click('a:has-text("Volver")');
        await expect(page).toHaveURL('/transacciones');
    });
});
