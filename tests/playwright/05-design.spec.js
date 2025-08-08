import { test, expect } from '@playwright/test';

// Helper para login
async function login(page) {
    await page.goto('/login');
    await page.fill('input[name="email"]', 'admin@sistema.com');
    await page.fill('input[name="password"]', 'Admin123!');
    await page.click('button[type="submit"]');
    await page.waitForURL('/dashboard');
}

test.describe('Diseño y Consistencia UI', () => {
    test.beforeEach(async ({ page }) => {
        await login(page);
    });

    test('debe tener título correcto en todas las páginas', async ({ page }) => {
        // Dashboard
        await expect(page).toHaveTitle('Sistema Administrativo');

        // Contactos
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Ver Contactos")');
        await expect(page).toHaveTitle('Sistema Administrativo');

        // Crear contacto
        await page.click('a:has-text("Nuevo Contacto")');
        await expect(page).toHaveTitle('Sistema Administrativo');

        // Transacciones
        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Ver Transacciones")');
        await expect(page).toHaveTitle('Sistema Administrativo');

        // Crear transacción
        await page.click('a:has-text("Nueva Transacción")');
        await expect(page).toHaveTitle('Sistema Administrativo');
    });

    test('debe usar layout de cards consistente', async ({ page }) => {
        // Verificar en crear contacto
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Nuevo Contacto")');

        await expect(page.locator('.bg-white.shadow.rounded-lg')).toHaveCount({ min: 1 });

        // Verificar en crear transacción
        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Nueva Transacción")');

        await expect(page.locator('.bg-white.shadow.rounded-lg')).toHaveCount({ min: 1 });
    });

    test('debe usar grid de dos columnas en formularios', async ({ page }) => {
        // Verificar en crear contacto
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Nuevo Contacto")');

        await expect(page.locator('.grid.grid-cols-1.lg\\:grid-cols-3')).toBeVisible();

        // Verificar en crear transacción
        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Nueva Transacción")');

        await expect(page.locator('.grid.grid-cols-1.lg\\:grid-cols-3')).toBeVisible();
    });

    test('debe mostrar campos obligatorios con asterisco', async ({ page }) => {
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Nuevo Contacto")');

        // Verificar asteriscos en campos obligatorios
        await expect(page.locator('text=Nombre *')).toBeVisible();
        await expect(page.locator('text=RFC *')).toBeVisible();
        await expect(page.locator('text=Tipo *')).toBeVisible();

        // En transacciones
        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Nueva Transacción")');

        await expect(page.locator('text=Tipo *')).toBeVisible();
        await expect(page.locator('text=Fecha *')).toBeVisible();
        await expect(page.locator('text=Total *')).toBeVisible();
    });

    test('debe usar colores coherentes para botones', async ({ page }) => {
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Nuevo Contacto")');

        // Botón primario (crear)
        const createBtn = page.locator('button:has-text("Crear Contacto")');
        await expect(createBtn).toHaveClass(/bg-indigo-600/);

        // Botón secundario (cancelar)
        const cancelBtn = page.locator('a:has-text("Cancelar")');
        await expect(cancelBtn).toHaveClass(/text-gray/);
    });

    test('debe mantener espaciado consistente', async ({ page }) => {
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Nuevo Contacto")');

        // Verificar espaciado entre secciones
        await expect(page.locator('.space-y-6')).toHaveCount({ min: 1 });
        await expect(page.locator('.space-y-4')).toHaveCount({ min: 1 });
    });

    test('debe ser responsive en diferentes tamaños', async ({ page }) => {
        // Desktop
        await page.setViewportSize({ width: 1920, height: 1080 });
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Nuevo Contacto")');

        await expect(page.locator('.lg\\:grid-cols-3')).toBeVisible();

        // Tablet
        await page.setViewportSize({ width: 768, height: 1024 });
        await expect(page.locator('form')).toBeVisible();

        // Móvil
        await page.setViewportSize({ width: 375, height: 667 });
        await expect(page.locator('form')).toBeVisible();
    });

    test('debe mostrar avatares y badges correctamente', async ({ page }) => {
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Ver Contactos")');

        // Si hay contactos, verificar avatares
        const contacts = await page.locator('tr').count();
        if (contacts > 1) { // Header + al menos un contacto
            await expect(page.locator('.bg-indigo-600').first()).toBeVisible(); // Avatar
        }
    });

    test('debe tener formularios bien estructurados', async ({ page }) => {
        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Nueva Transacción")');

        // Verificar estructura de formulario
        await expect(page.locator('h3:has-text("Información General")')).toBeVisible();
        await expect(page.locator('h3:has-text("Referencia")')).toBeVisible();
        await expect(page.locator('h3:has-text("Facturación")')).toBeVisible();
        await expect(page.locator('h3:has-text("Resumen Financiero")')).toBeVisible();
    });

    test('debe mostrar estados de validación', async ({ page }) => {
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Nuevo Contacto")');

        // Intentar enviar formulario vacío
        await page.click('button:has-text("Crear Contacto")');

        // Verificar que permanece en la página (validación funcionando)
        await expect(page).toHaveURL('/contactos/create');
    });

    test('debe tener íconos consistentes', async ({ page }) => {
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Ver Contactos")');

        // Verificar íconos en navegación
        await expect(page.locator('svg')).toHaveCount({ min: 5 }); // Varios íconos visibles

        // Verificar íconos en botones de acción
        await page.click('a:has-text("Nuevo Contacto")');
        await expect(page.locator('button:has-text("Crear Contacto") svg')).toBeVisible();
    });

    test('debe mostrar preview dinámico en contactos', async ({ page }) => {
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Nuevo Contacto")');

        // Llenar campo nombre
        await page.fill('input[name="nombre"]', 'Test Preview');

        // Verificar que aparece en el preview
        await expect(page.locator('text=Test Preview')).toHaveCount({ min: 1 });
    });

    test('debe cambiar colores dinámicamente en transacciones', async ({ page }) => {
        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Nueva Transacción")');

        // Llenar total
        await page.fill('input[name="total"]', '100.00');

        // Seleccionar ingreso (verde)
        await page.selectOption('select[name="tipo"]', 'ingreso');
        await expect(page.locator('input[name="total"]')).toHaveClass(/text-green/);

        // Seleccionar egreso (rojo)
        await page.selectOption('select[name="tipo"]', 'egreso');
        await expect(page.locator('input[name="total"]')).toHaveClass(/text-red/);
    });
});
