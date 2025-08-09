import { test, expect } from '@playwright/test';

// Helper para login
async function login(page) {
    await page.goto('/login');
    await page.fill('input[name="email"]', 'admin@sistema.com');
    await page.fill('input[name="password"]', 'Admin123!');
    await page.click('button[type="submit"]');
    await page.waitForURL('/dashboard');
}

test.describe('Navegación y UI/UX', () => {
    test.beforeEach(async ({ page }) => {
        await login(page);
    });

    test('debe mostrar navegación principal correctamente', async ({ page }) => {
        await expect(page.locator('nav')).toBeVisible();
        await expect(page.locator('a:has-text("Sistema Admin")')).toBeVisible();
        await expect(page.locator('a:has-text("Dashboard")')).toBeVisible();
        await expect(page.locator('button:has-text("Contactos")')).toBeVisible();
        await expect(page.locator('button:has-text("Transacciones")')).toBeVisible();
        await expect(page.locator('a:has-text("Reportes")')).toBeVisible();
    });

    test('debe funcionar dropdown de Contactos', async ({ page }) => {
        await page.click('button:has-text("Contactos")');

        await expect(page.locator('a:has-text("Ver Contactos")')).toBeVisible();
        await expect(page.locator('a:has-text("Nuevo Contacto")')).toBeVisible();
        await expect(page.locator('a:has-text("Activos")')).toBeVisible();
        await expect(page.locator('a:has-text("Inactivos")')).toBeVisible();
    });

    test('debe funcionar dropdown de Transacciones', async ({ page }) => {
        await page.click('button:has-text("Transacciones")');

        await expect(page.locator('a:has-text("Ver Transacciones")')).toBeVisible();
        await expect(page.locator('a:has-text("Nueva Transacción")')).toBeVisible();
        await expect(page.locator('a:has-text("Ingresos")')).toBeVisible();
        await expect(page.locator('a:has-text("Egresos")')).toBeVisible();
    });

    test('debe navegar a diferentes secciones desde dropdown Contactos', async ({ page }) => {
        await page.click('button:has-text("Contactos")');

        // Test navegación a lista de contactos
        await page.click('a:has-text("Ver Contactos")');
        await expect(page).toHaveURL('/contactos');

        // Volver al dashboard
        await page.click('a:has-text("Dashboard")');
        await expect(page).toHaveURL('/dashboard');

        // Test navegación a nuevo contacto
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Nuevo Contacto")');
        await expect(page).toHaveURL('/contactos/create');
    });

    test('debe navegar a diferentes secciones desde dropdown Transacciones', async ({ page }) => {
        await page.click('button:has-text("Transacciones")');

        // Test navegación a lista de transacciones
        await page.click('a:has-text("Ver Transacciones")');
        await expect(page).toHaveURL('/transacciones');

        // Volver al dashboard
        await page.click('a:has-text("Dashboard")');
        await expect(page).toHaveURL('/dashboard');

        // Test navegación a nueva transacción
        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Nueva Transacción")');
        await expect(page).toHaveURL('/transacciones/create');
    });

    test('debe mostrar perfil de usuario', async ({ page }) => {
        await expect(page.locator('button:has-text("Administrador")')).toBeVisible();

        await page.click('button:has-text("Administrador")');
        // Verificar que aparece el dropdown de perfil
        await expect(page.locator('a:has-text("Perfil")')).toBeVisible();
        await expect(page.locator('form:has-text("Cerrar Sesión")')).toBeVisible();
    });

    test('debe mantener estado activo en navegación', async ({ page }) => {
        // Ir a contactos
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Ver Contactos")');

        // Verificar que contactos sigue siendo el item activo
        await expect(page.locator('button:has-text("Contactos")')).toHaveClass(/bg-gray/);

        // Ir a transacciones
        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Ver Transacciones")');

        // Verificar que transacciones ahora es el item activo
        await expect(page.locator('button:has-text("Transacciones")')).toHaveClass(/bg-gray/);
    });

    test('debe ser responsive en móvil', async ({ page }) => {
        // Simular pantalla móvil
        await page.setViewportSize({ width: 375, height: 667 });

        // Verificar que la navegación sigue siendo funcional
        await expect(page.locator('nav')).toBeVisible();
        await expect(page.locator('a:has-text("Sistema Admin")')).toBeVisible();
    });

    test('debe funcionar logout', async ({ page }) => {
        await page.click('button:has-text("Administrador")');
        await page.click('button:has-text("Cerrar Sesión")');

        // Verificar redirección al login
        await expect(page).toHaveURL('/login');
        await expect(page.locator('h2:has-text("Iniciar Sesión")')).toBeVisible();
    });

    test('debe mantener coherencia visual', async ({ page }) => {
        // Verificar que los elementos principales tienen los estilos correctos
        await expect(page.locator('nav')).toHaveClass(/bg-white/);
        await expect(page.locator('a:has-text("Sistema Admin")')).toHaveClass(/text-indigo/);

        // Verificar colores de botones de navegación
        const contactosBtn = page.locator('button:has-text("Contactos")');
        const transaccionesBtn = page.locator('button:has-text("Transacciones")');

        await expect(contactosBtn).toHaveClass(/text-gray/);
        await expect(transaccionesBtn).toHaveClass(/text-gray/);
    });

    test('debe mostrar breadcrumbs en páginas internas', async ({ page }) => {
        // Ir a crear contacto
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Nuevo Contacto")');

        // Verificar breadcrumb/botón volver
        await expect(page.locator('a:has-text("Volver")')).toBeVisible();

        // Ir a crear transacción
        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Nueva Transacción")');

        // Verificar breadcrumb/botón volver
        await expect(page.locator('a:has-text("Volver")')).toBeVisible();
    });
});
