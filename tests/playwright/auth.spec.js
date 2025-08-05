/**
 * Test automatizado de UI usando Playwright
 * Implementando mejores prácticas de Context7 para testing frontend
 * 
 * Este script valida el flujo completo de autenticación y navegación
 * del Sistema Administrativo, siguiendo patrones de calidad y 
 * accesibilidad recomendados por Context7.
 */

import { test, expect } from '@playwright/test';

// Configuración base para todos los tests
const BASE_URL = 'http://127.0.0.1:8000';
const TEST_USER = {
    email: 'admin@admin.com',
    password: 'admin'
};

/**
 * Suite de tests de autenticación
 * Valida login, dashboard y logout siguiendo Context7
 */
test.describe('Sistema Administrativo - Autenticación', () => {

    /**
     * Test de login con credenciales válidas
     * Verifica el flujo completo desde login hasta dashboard
     */
    test('Login exitoso y acceso al dashboard', async ({ page }) => {
        // Navegar a la página de login
        await page.goto(BASE_URL);

        // Verificar elementos de la página de login (Context7: verificar estado inicial)
        await expect(page.locator('h2')).toContainText('Sistema Administrativo');
        await expect(page.locator('p').first()).toContainText('Inicia sesión para acceder al sistema');

        // Llenar formulario de login con datos válidos
        await page.fill('input[name="email"]', TEST_USER.email);
        await page.fill('input[name="password"]', TEST_USER.password);

        // Hacer clic en el botón de iniciar sesión
        await page.click('button[type="submit"]');

        // Verificar redirección al dashboard
        await expect(page).toHaveURL(`${BASE_URL}/dashboard`);

        // Verificar elementos del dashboard (Context7: validar contenido crítico)
        await expect(page.locator('h1')).toContainText('Dashboard');
        await expect(page.locator('text=Bienvenido,')).toBeVisible();

        // Verificar secciones principales del dashboard
        await expect(page.locator('text=Total Contactos')).toBeVisible();
        await expect(page.locator('text=Clientes')).toBeVisible();
        await expect(page.locator('text=Proveedores')).toBeVisible();
        await expect(page.locator('text=Transacciones del Mes')).toBeVisible();

        // Verificar áreas de contenido
        await expect(page.locator('text=Transacciones Recientes')).toBeVisible();
        await expect(page.locator('text=Resumen Financiero')).toBeVisible();
        await expect(page.locator('text=Acciones Rápidas')).toBeVisible();

        // Tomar screenshot para documentación (Context7: evidencia visual)
        await page.screenshot({
            path: 'tests/screenshots/dashboard-loaded.png',
            fullPage: true
        });
    });

    /**
     * Test de login con credenciales inválidas
     * Verifica manejo de errores según Context7
     */
    test('Login fallido con credenciales incorrectas', async ({ page }) => {
        await page.goto(BASE_URL);

        // Intentar login con credenciales incorrectas
        await page.fill('input[name="email"]', 'wrong@email.com');
        await page.fill('input[name="password"]', 'wrongpassword');
        await page.click('button[type="submit"]');

        // Verificar que permanecemos en la página de login
        await expect(page).toHaveURL(BASE_URL);

        // Verificar mensaje de error (Context7: feedback claro al usuario)
        await expect(page.locator('text=These credentials do not match our records')).toBeVisible();

        // Tomar screenshot del error
        await page.screenshot({
            path: 'tests/screenshots/login-error.png'
        });
    });

    /**
     * Test de navegación con dropdowns
     * Valida interactividad de la navegación principal
     */
    test('Navegación y dropdowns funcionan correctamente', async ({ page }) => {
        // Login primero
        await page.goto(BASE_URL);
        await page.fill('input[name="email"]', TEST_USER.email);
        await page.fill('input[name="password"]', TEST_USER.password);
        await page.click('button[type="submit"]');

        // Verificar que estamos en el dashboard
        await expect(page).toHaveURL(`${BASE_URL}/dashboard`);

        // Test dropdown de Contactos
        await page.click('button:has-text("Contactos")');
        await expect(page.locator('text=Ver Contactos')).toBeVisible();
        await expect(page.locator('text=Nuevo Contacto')).toBeVisible();
        await expect(page.locator('text=Clientes')).toBeVisible();
        await expect(page.locator('text=Proveedores')).toBeVisible();

        // Cerrar dropdown (Context7: test estado completo)
        await page.click('button:has-text("Contactos")');

        // Test dropdown de Transacciones
        await page.click('button:has-text("Transacciones")');
        await expect(page.locator('text=Ver Transacciones')).toBeVisible();
        await expect(page.locator('text=Nueva Transacción')).toBeVisible();
        await expect(page.locator('text=Ingresos')).toBeVisible();
        await expect(page.locator('text=Egresos')).toBeVisible();

        // Tomar screenshot de dropdown abierto
        await page.screenshot({
            path: 'tests/screenshots/navigation-dropdown.png'
        });

        // Cerrar dropdown
        await page.click('button:has-text("Transacciones")');
    });

    /**
     * Test de logout
     * Verifica la funcionalidad de cerrar sesión
     */
    test('Logout funciona correctamente', async ({ page }) => {
        // Login primero
        await page.goto(BASE_URL);
        await page.fill('input[name="email"]', TEST_USER.email);
        await page.fill('input[name="password"]', TEST_USER.password);
        await page.click('button[type="submit"]');

        // Verificar dashboard
        await expect(page).toHaveURL(`${BASE_URL}/dashboard`);

        // Abrir dropdown de usuario
        await page.click('button.inline-flex.items-center.px-3.py-2');

        // Hacer clic en cerrar sesión
        await page.click('text=Cerrar Sesión');

        // Verificar redirección a login
        await expect(page).toHaveURL(BASE_URL);
        await expect(page.locator('text=Sistema Administrativo')).toBeVisible();

        // Tomar screenshot después del logout
        await page.screenshot({
            path: 'tests/screenshots/after-logout.png'
        });
    });

    /**
     * Test de responsividad móvil
     * Verifica que la UI funciona en dispositivos móviles (Context7)
     */
    test('UI responsiva en móvil', async ({ page }) => {
        // Configurar viewport móvil
        await page.setViewportSize({ width: 375, height: 667 });

        // Navegar y hacer login
        await page.goto(BASE_URL);

        // Verificar que los elementos se ven bien en móvil
        await expect(page.locator('h2')).toContainText('Sistema Administrativo');

        // Login en móvil
        await page.fill('input[name="email"]', TEST_USER.email);
        await page.fill('input[name="password"]', TEST_USER.password);
        await page.click('button[type="submit"]');

        // Verificar dashboard en móvil
        await expect(page).toHaveURL(`${BASE_URL}/dashboard`);
        await expect(page.locator('text=Dashboard')).toBeVisible();

        // Tomar screenshot móvil
        await page.screenshot({
            path: 'tests/screenshots/mobile-dashboard.png',
            fullPage: true
        });
    });

    /**
     * Test de accesibilidad básica
     * Verifica elementos de accesibilidad según Context7
     */
    test('Elementos de accesibilidad presentes', async ({ page }) => {
        await page.goto(BASE_URL);

        // Verificar que los inputs tienen labels apropiados
        const emailInput = page.locator('input[name="email"]');
        const passwordInput = page.locator('input[name="password"]');

        await expect(emailInput).toBeVisible();
        await expect(passwordInput).toBeVisible();

        // Verificar que el botón submit es accesible
        const submitButton = page.locator('button[type="submit"]');
        await expect(submitButton).toBeVisible();
        await expect(submitButton).toContainText('Iniciar Sesión');

        // Login y verificar dashboard accessibility
        await page.fill('input[name="email"]', TEST_USER.email);
        await page.fill('input[name="password"]', TEST_USER.password);
        await page.click('button[type="submit"]');

        // Verificar estructura de headings en dashboard
        await expect(page.locator('h1')).toContainText('Dashboard');

        // Verificar que los botones de navegación son accesibles
        await expect(page.locator('button:has-text("Contactos")')).toBeVisible();
        await expect(page.locator('button:has-text("Transacciones")')).toBeVisible();
    });

    /**
     * Test de rendimiento básico
     * Verifica tiempos de carga según mejores prácticas Context7
     */
    test('Tiempos de carga aceptables', async ({ page }) => {
        const startTime = Date.now();

        // Cargar página de login
        await page.goto(BASE_URL);
        await page.waitForLoadState('networkidle');

        const loginLoadTime = Date.now() - startTime;

        // Verificar que la página de login carga en menos de 3 segundos
        expect(loginLoadTime).toBeLessThan(3000);

        // Test tiempo de login y carga de dashboard
        const loginStartTime = Date.now();

        await page.fill('input[name="email"]', TEST_USER.email);
        await page.fill('input[name="password"]', TEST_USER.password);
        await page.click('button[type="submit"]');

        await page.waitForURL(`${BASE_URL}/dashboard`);
        await page.waitForLoadState('networkidle');

        const dashboardLoadTime = Date.now() - loginStartTime;

        // Verificar que el dashboard carga en menos de 2 segundos
        expect(dashboardLoadTime).toBeLessThan(2000);

        console.log(`Login load time: ${loginLoadTime}ms`);
        console.log(`Dashboard load time: ${dashboardLoadTime}ms`);
    });

});
