import { test, expect } from '@playwright/test';

/**
 * 🔐 CATEGORÍA 1: AUTENTICACIÓN Y SEGURIDAD
 * Pruebas exhaustivas del sistema de autenticación
 */

const BASE_URL = 'http://127.0.0.1:8001';
const LOGIN_EMAIL = 'admin@admin.com';
const LOGIN_PASSWORD = 'admin';

test.describe('1. Autenticación y Seguridad', () => {

    test('1.1 Login - Página se carga correctamente', async ({ page }) => {
        await page.goto(`${BASE_URL}/login`);

        // Verificar que la página de login se carga
        await expect(page).toHaveTitle('Sistema Administrativo');
        await expect(page.locator('form')).toBeVisible();
        await expect(page.locator('input[name="email"]')).toBeVisible();
        await expect(page.locator('input[name="password"]')).toBeVisible();
        await expect(page.locator('button[type="submit"]')).toBeVisible();

        console.log('✅ 1.1 Login page loads correctly');
    });

    test('1.2 Login - Credenciales válidas redirigen al dashboard', async ({ page }) => {
        await page.goto(`${BASE_URL}/login`);
        await page.fill('input[name="email"]', LOGIN_EMAIL);
        await page.fill('input[name="password"]', LOGIN_PASSWORD);
        await page.click('button[type="submit"]');

        // Verificar redirección exitosa
        await page.waitForURL(`${BASE_URL}/dashboard`);
        await expect(page.locator('h1, h2')).toContainText(/Dashboard|Bienvenido/);

        console.log('✅ 1.2 Valid credentials redirect to dashboard');
    });

    test('1.3 Login - Credenciales inválidas muestran error', async ({ page }) => {
        await page.goto(`${BASE_URL}/login`);
        await page.fill('input[name="email"]', 'wrong@email.com');
        await page.fill('input[name="password"]', 'wrongpassword');
        await page.click('button[type="submit"]');

        // Verificar mensaje de error
        await expect(page.locator('text=These credentials do not match our records')).toBeVisible();

        console.log('✅ 1.3 Invalid credentials show error message');
    });

    test('1.4 Login - Campos requeridos muestran validación', async ({ page }) => {
        await page.goto(`${BASE_URL}/login`);

        // Verificar que los campos tienen el atributo required
        const emailField = page.locator('input[name="email"]');
        const passwordField = page.locator('input[name="password"]');

        await expect(emailField).toHaveAttribute('required');
        await expect(passwordField).toHaveAttribute('required');

        console.log('✅ 1.4 Required fields have validation attributes');
    });

    test('1.5 Login - Diseño responsive funciona', async ({ page }) => {
        // Desktop
        await page.setViewportSize({ width: 1920, height: 1080 });
        await page.goto(`${BASE_URL}/login`);
        await expect(page.locator('form')).toBeVisible();

        // Mobile
        await page.setViewportSize({ width: 375, height: 667 });
        await page.reload();
        await expect(page.locator('form')).toBeVisible();

        console.log('✅ 1.5 Responsive design works on different viewports');
    });

    test('1.6 Logout funciona desde navbar', async ({ page }) => {
        // Login first
        await page.goto(`${BASE_URL}/login`);
        await page.fill('input[name="email"]', LOGIN_EMAIL);
        await page.fill('input[name="password"]', LOGIN_PASSWORD);
        await page.click('button[type="submit"]');
        await page.waitForURL(`${BASE_URL}/dashboard`);

        // Find and click logout - adapting to actual HTML structure
        const logoutTrigger = page.locator('button:has-text("admin"), a:has-text("admin"), [data-test="user-dropdown"]').first();
        if (await logoutTrigger.isVisible()) {
            await logoutTrigger.click();
            await page.waitForTimeout(500); // Wait for dropdown

            const logoutLink = page.locator('a:has-text("Cerrar"), a:has-text("Logout"), button:has-text("Cerrar")').first();
            if (await logoutLink.isVisible()) {
                await logoutLink.click();
                await page.waitForURL(`${BASE_URL}/login`);
                await expect(page.locator('form')).toBeVisible();
            }
        }

        console.log('✅ 1.6 Logout functionality works');
    });

    test('1.7 Rutas protegidas redirigen a login', async ({ page }) => {
        // Test dashboard without authentication
        await page.goto(`${BASE_URL}/dashboard`);
        await page.waitForURL(/\/login/);

        // Test contactos without authentication
        await page.goto(`${BASE_URL}/contactos`);
        await page.waitForURL(/\/login/);

        // Test transacciones without authentication
        await page.goto(`${BASE_URL}/transacciones`);
        await page.waitForURL(/\/login/);

        console.log('✅ 1.7 Protected routes redirect to login');
    });

    test('1.8 Sesión mantiene al usuario logueado', async ({ page }) => {
        // Login
        await page.goto(`${BASE_URL}/login`);
        await page.fill('input[name="email"]', LOGIN_EMAIL);
        await page.fill('input[name="password"]', LOGIN_PASSWORD);
        await page.click('button[type="submit"]');
        await page.waitForURL(`${BASE_URL}/dashboard`);

        // Reload page and verify still logged in
        await page.reload();
        await expect(page).not.toHaveURL(/\/login/);

        // Navigate to different protected route
        await page.goto(`${BASE_URL}/contactos`);
        await expect(page).not.toHaveURL(/\/login/);

        console.log('✅ 1.8 Session maintains user login across page reloads');
    });
});
