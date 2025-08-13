import { test, expect } from '@playwright/test';

// Helper para login
async function login(page) {
    await page.goto('/login');
    await page.fill('input[name="email"]', 'admin@sistema.com');
    await page.fill('input[name="password"]', 'Admin123!');
    await page.click('button[type="submit"]');
    await page.waitForURL('/dashboard');
}

test.describe('Flujos E2E Completos', () => {
    test.beforeEach(async ({ page }) => {
        await login(page);
    });

    test('flujo completo: crear contacto y usarlo en transacción', async ({ page }) => {
        // 1. Crear un nuevo contacto
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Nuevo Contacto")');

        const contactName = `Cliente E2E ${Date.now()}`;
        await page.fill('input[name="nombre"]', contactName);
        await page.fill('input[name="rfc"]', 'CLIE850315ABC');
        await page.selectOption('select[name="tipo"]', 'cliente');
        await page.fill('input[name="email"]', 'e2e@test.com');
        await page.fill('input[name="telefono"]', '5551234567');

        await page.click('button:has-text("Crear Contacto")');

        // Verificar creación exitosa
        await expect(page).toHaveURL(/\/contactos\/\d+/);
        await expect(page.locator('h1')).toContainText(contactName);

        // 2. Crear transacción usando el contacto
        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Nueva Transacción")');

        // Llenar formulario de transacción
        await page.selectOption('select[name="tipo"]', 'ingreso');
        await page.fill('input[name="fecha"]', '2025-08-08');

        // Buscar y seleccionar el contacto creado
        await page.fill('input[placeholder*="contacto"]', contactName.substring(0, 5));
        await page.waitForTimeout(500);

        await page.selectOption('select[name="referencia_tipo"]', 'servicio');
        await page.fill('input[name="referencia_nombre"]', 'Servicio E2E Test');
        await page.selectOption('select[name="factura_tipo"]', 'archivo');
        await page.fill('input[name="total"]', '2500.00');
        await page.selectOption('select[name="metodo_pago_id"]', '1');

        await page.click('button:has-text("Crear Transacción")');

        // Verificar creación exitosa
        await expect(page).toHaveURL(/\/transacciones\/\d+/);
        await expect(page.locator('text=2,500.00')).toBeVisible();
    });

    test('flujo completo: crear transacción con factura manual', async ({ page }) => {
        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Nueva Transacción")');

        // Información general
        await page.selectOption('select[name="tipo"]', 'egreso');
        await page.fill('input[name="fecha"]', '2025-08-08');

        // Referencia
        await page.selectOption('select[name="referencia_tipo"]', 'producto');
        await page.fill('input[name="referencia_nombre"]', 'Producto E2E Test');

        // Facturación manual
        await page.selectOption('select[name="factura_tipo"]', 'manual');

        // Llenar datos de factura manual
        await page.fill('input[name="factura_manual[emisor]"]', 'Proveedor Test S.A.');
        await page.fill('input[name="factura_manual[receptor]"]', 'Mi Empresa S.A.');
        await page.fill('input[name="factura_manual[fecha_emision]"]', '2025-08-08');

        // Agregar conceptos
        await page.fill('input[placeholder="Descripción del concepto"]', 'Concepto 1');
        await page.fill('input[type="number"][step="1"]', '2');
        await page.fill('input[type="number"][step="0.01"]', '100.00');

        // Agregar segundo concepto
        await page.click('button:has-text("Agregar Concepto")');
        const conceptRows = page.locator('tbody tr');
        await conceptRows.nth(1).locator('input[placeholder="Descripción del concepto"]').fill('Concepto 2');
        await conceptRows.nth(1).locator('input[type="number"][step="1"]').fill('1');
        await conceptRows.nth(1).locator('input[type="number"][step="0.01"]').fill('50.00');

        // Llenar totales
        await page.fill('input[name="factura_manual[subtotal]"]', '250.00');
        await page.fill('input[name="factura_manual[impuestos]"]', '40.00');
        await page.fill('input[name="factura_manual[total]"]', '290.00');

        // Resumen financiero
        await page.fill('input[name="total"]', '290.00');
        await page.selectOption('select[name="metodo_pago_id"]', '1');

        await page.click('button:has-text("Crear Transacción")');

        // Verificar creación exitosa
        await expect(page).toHaveURL(/\/transacciones\/\d+/);
        await expect(page.locator('text=290.00')).toBeVisible();
    });

    test('flujo completo: gestión de contactos (CRUD)', async ({ page }) => {
        // 1. Ver lista de contactos
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Ver Contactos")');

        const initialCount = await page.locator('tbody tr').count();

        // 2. Crear nuevo contacto
        await page.click('a:has-text("Nuevo Contacto")');

        const contactName = `Contacto CRUD ${Date.now()}`;
        await page.fill('input[name="nombre"]', contactName);
        await page.fill('input[name="rfc"]', 'CRUD850315ABC');
        await page.selectOption('select[name="tipo"]', 'proveedor');
        await page.fill('input[name="email"]', 'crud@test.com');

        await page.click('button:has-text("Crear Contacto")');

        // 3. Editar contacto
        await page.click('a:has-text("Editar Contacto")');

        await page.fill('input[name="telefono"]', '5559876543');
        await page.click('button:has-text("Actualizar Contacto")');

        // Verificar actualización
        await expect(page.locator('text=5559876543')).toBeVisible();

        // 4. Verificar que el contacto aparece en la lista
        await page.click('a:has-text("Ver Contactos")');
        await expect(page.locator(`text=${contactName}`)).toBeVisible();

        // 5. Desactivar contacto
        await page.click(`tr:has-text("${contactName}") a:has-text("Ver")`);
        await page.click('button:has-text("Desactivar")');

        // Confirmar en el modal si aparece
        if (await page.locator('button:has-text("Confirmar")').isVisible()) {
            await page.click('button:has-text("Confirmar")');
        }

        // Verificar estado inactivo
        await expect(page.locator('text=Inactivo')).toBeVisible();
    });

    test('flujo completo: filtros y búsqueda en transacciones', async ({ page }) => {
        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Ver Transacciones")');

        // 1. Filtrar por ingresos
        await page.click('a:has-text("Ingresos")');
        await expect(page).toHaveURL('/transacciones?tipo=ingreso');

        // 2. Filtrar por egresos
        await page.click('a:has-text("Egresos")');
        await expect(page).toHaveURL('/transacciones?tipo=egreso');

        // 3. Volver a ver todas
        await page.click('a:has-text("Ver Transacciones")');
        await expect(page).toHaveURL('/transacciones');

        // 4. Si hay transacciones, probar ver detalles
        const transactionLinks = page.locator('tbody a').first();
        if (await transactionLinks.count() > 0) {
            await transactionLinks.click();
            await expect(page).toHaveURL(/\/transacciones\/\d+/);
            await expect(page.locator('h1:has-text("Detalles")')).toBeVisible();
        }
    });

    test('flujo completo: navegación y estado del dashboard', async ({ page }) => {
        // 1. Verificar dashboard inicial
        await expect(page.locator('h1:has-text("Dashboard")')).toBeVisible();

        // 2. Verificar tarjetas de estadísticas
        await expect(page.locator('text=Total Contactos')).toBeVisible();
        await expect(page.locator('text=Total Transacciones')).toBeVisible();
        await expect(page.locator('text=Ingresos del Mes')).toBeVisible();
        await expect(page.locator('text=Egresos del Mes')).toBeVisible();

        // 3. Navegar a contactos y regresar
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Ver Contactos")');
        await page.click('a:has-text("Dashboard")');

        // Verificar que regresamos al dashboard
        await expect(page).toHaveURL('/dashboard');

        // 4. Navegar a transacciones y regresar
        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Ver Transacciones")');
        await page.click('a:has-text("Dashboard")');

        // Verificar que regresamos al dashboard
        await expect(page).toHaveURL('/dashboard');
    });

    test('flujo completo: sesión y logout', async ({ page }) => {
        // 1. Verificar que estamos logueados
        await expect(page.locator('button:has-text("Administrador")')).toBeVisible();

        // 2. Navegar por diferentes secciones
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Ver Contactos")');

        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Ver Transacciones")');

        // 3. Logout
        await page.click('button:has-text("Administrador")');
        await page.click('button:has-text("Cerrar Sesión")');

        // 4. Verificar redirección al login
        await expect(page).toHaveURL('/login');
        await expect(page.locator('h2:has-text("Iniciar Sesión")')).toBeVisible();

        // 5. Intentar acceder a página protegida sin login
        await page.goto('/dashboard');

        // Debe redirigir al login
        await expect(page).toHaveURL('/login');
    });

    test('flujo completo: validación de errores y recuperación', async ({ page }) => {
        // 1. Intentar crear contacto con datos inválidos
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Nuevo Contacto")');

        // Enviar formulario vacío
        await page.click('button:has-text("Crear Contacto")');

        // Debe permanecer en la página
        await expect(page).toHaveURL('/contactos/create');

        // 2. Llenar correctamente y enviar
        await page.fill('input[name="nombre"]', 'Contacto Validación');
        await page.fill('input[name="rfc"]', 'CVAL850315ABC');
        await page.selectOption('select[name="tipo"]', 'cliente');

        await page.click('button:has-text("Crear Contacto")');

        // Debe crear exitosamente
        await expect(page).toHaveURL(/\/contactos\/\d+/);

        // 3. Similar para transacciones
        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Nueva Transacción")');

        // Enviar formulario vacío
        await page.click('button:has-text("Crear Transacción")');
        await expect(page).toHaveURL('/transacciones/create');

        // Llenar mínimos requeridos
        await page.selectOption('select[name="tipo"]', 'ingreso');
        await page.selectOption('select[name="referencia_tipo"]', 'servicio');
        await page.fill('input[name="referencia_nombre"]', 'Servicio Validación');
        await page.selectOption('select[name="factura_tipo"]', 'archivo');
        await page.fill('input[name="total"]', '100.00');
        await page.selectOption('select[name="metodo_pago_id"]', '1');

        await page.click('button:has-text("Crear Transacción")');

        // Debe crear exitosamente
        await expect(page).toHaveURL(/\/transacciones\/\d+/);
    });
});
