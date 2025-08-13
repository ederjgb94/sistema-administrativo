import { test, expect } from '@playwright/test';

// Helper para login
async function login(page) {
    await page.goto('/login');
    await page.fill('input[name="email"]', 'admin@sistema.com');
    await page.fill('input[name="password"]', 'Admin123!');
    await page.click('button[type="submit"]');
    await page.waitForURL('/dashboard');
}

test.describe('Performance y Edge Cases', () => {
    test.beforeEach(async ({ page }) => {
        await login(page);
    });

    test('debe cargar páginas en tiempo razonable', async ({ page }) => {
        const startTime = Date.now();

        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Ver Contactos")');

        const loadTime = Date.now() - startTime;
        expect(loadTime).toBeLessThan(3000); // Menos de 3 segundos
    });

    test('debe manejar campos con caracteres especiales', async ({ page }) => {
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Nuevo Contacto")');

        // Probar caracteres especiales
        await page.fill('input[name="nombre"]', 'José María & Asociados S.A. de C.V.');
        await page.fill('input[name="rfc"]', 'JMAS850315ABC');

        // Verificar que se mantienen los valores
        await expect(page.locator('input[name="nombre"]')).toHaveValue('José María & Asociados S.A. de C.V.');
    });

    test('debe validar RFC con formato correcto', async ({ page }) => {
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Nuevo Contacto")');

        // RFC inválido
        await page.fill('input[name="rfc"]', 'ABC123');
        await page.click('button:has-text("Crear Contacto")');

        // Debe permanecer en la página (validación falló)
        await expect(page).toHaveURL('/contactos/create');
    });

    test('debe manejar números grandes en transacciones', async ({ page }) => {
        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Nueva Transacción")');

        // Probar números grandes
        await page.fill('input[name="total"]', '999999999.99');

        // Verificar que acepta el valor
        await expect(page.locator('input[name="total"]')).toHaveValue('999999999.99');
    });

    test('debe manejar decimales correctamente', async ({ page }) => {
        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Nueva Transacción")');

        // Probar diferentes formatos decimales
        const testValues = ['123.45', '0.99', '1000.00', '50.5'];

        for (const value of testValues) {
            await page.fill('input[name="total"]', value);
            await expect(page.locator('input[name="total"]')).toHaveValue(value);
        }
    });

    test('debe prevenir múltiples puntos decimales', async ({ page }) => {
        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Nueva Transacción")');

        // Intentar escribir múltiples puntos
        await page.fill('input[name="total"]', '123.45.67');

        // Debe mantener solo el primer punto decimal válido
        const value = await page.locator('input[name="total"]').inputValue();
        expect(value.match(/\./g)?.length).toBeLessThanOrEqual(1);
    });

    test('debe limitar decimales a 2 dígitos', async ({ page }) => {
        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Nueva Transacción")');

        // Intentar escribir más de 2 decimales
        await page.fill('input[name="total"]', '123.456789');

        // Verificar que se limita a 2 decimales
        const value = await page.locator('input[name="total"]').inputValue();
        const decimals = value.split('.')[1];
        if (decimals) {
            expect(decimals.length).toBeLessThanOrEqual(2);
        }
    });

    test('debe manejar conexión lenta', async ({ page }) => {
        // Simular conexión lenta
        await page.route('**/*', route => {
            setTimeout(() => route.continue(), 100); // 100ms delay
        });

        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Ver Contactos")');

        // Verificar que eventualmente carga
        await expect(page.locator('h1')).toContainText('Contactos', { timeout: 10000 });
    });

    test('debe manejar errores de navegación', async ({ page }) => {
        // Intentar navegar a una página que no existe
        await page.goto('/contactos/99999');

        // Verificar manejo de error (redirección o página 404)
        await expect(page).toHaveURL(/\/(contactos|404|login)/);
    });

    test('debe mantener estado en navegación rápida', async ({ page }) => {
        // Navegación rápida entre páginas
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Ver Contactos")');

        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Ver Transacciones")');

        await page.click('a:has-text("Dashboard")');

        // Verificar que llega al dashboard correctamente
        await expect(page).toHaveURL('/dashboard');
        await expect(page.locator('h1:has-text("Dashboard")')).toBeVisible();
    });

    test('debe manejar formularios grandes', async ({ page }) => {
        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Nueva Transacción")');

        // Seleccionar factura manual
        await page.selectOption('select[name="factura_tipo"]', 'manual');

        // Agregar muchos conceptos
        for (let i = 0; i < 5; i++) {
            await page.click('button:has-text("Agregar Concepto")');
        }

        // Verificar que la página sigue respondiendo
        await expect(page.locator('table tbody tr')).toHaveCount({ min: 5 });
    });

    test('debe manejar autocomplete sin resultados', async ({ page }) => {
        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Nueva Transacción")');

        // Buscar contacto que no existe
        await page.fill('input[placeholder*="contacto"]', 'ContactoQueNoExiste12345');
        await page.waitForTimeout(500);

        // Verificar que no hay errores JavaScript
        const consoleLogs = [];
        page.on('console', msg => {
            if (msg.type() === 'error') {
                consoleLogs.push(msg.text());
            }
        });

        expect(consoleLogs.length).toBe(0);
    });

    test('debe manejar memoria en sesiones largas', async ({ page }) => {
        // Simular uso intensivo navegando múltiples veces
        for (let i = 0; i < 10; i++) {
            await page.click('button:has-text("Contactos")');
            await page.click('a:has-text("Ver Contactos")');

            await page.click('button:has-text("Transacciones")');
            await page.click('a:has-text("Ver Transacciones")');

            await page.click('a:has-text("Dashboard")');
        }

        // Verificar que sigue funcionando
        await expect(page.locator('h1:has-text("Dashboard")')).toBeVisible();
    });

    test('debe funcionar sin JavaScript (graceful degradation)', async ({ page }) => {
        // Deshabilitar JavaScript
        await page.setJavaScriptEnabled(false);

        // Navegar usando enlaces directos
        await page.goto('/contactos');

        // Verificar que al menos la página básica carga
        await expect(page.locator('h1')).toContainText('Contactos');
    });

    test('debe manejar diferentes zonas horarias', async ({ page }) => {
        // Simular zona horaria diferente
        await page.emulateTimezone('America/Mexico_City');

        await page.click('button:has-text("Transacciones")');
        await page.click('a:has-text("Nueva Transacción")');

        // Verificar que el campo fecha tiene valor por defecto
        const dateField = page.locator('input[name="fecha"]');
        await expect(dateField).toHaveValue(/\d{4}-\d{2}-\d{2}/);
    });

    test('debe mantener accesibilidad básica', async ({ page }) => {
        await page.click('button:has-text("Contactos")');
        await page.click('a:has-text("Nuevo Contacto")');

        // Verificar navegación por teclado
        await page.keyboard.press('Tab');
        await page.keyboard.press('Tab');

        // Verificar que los elementos son focusables
        await expect(page.locator(':focus')).toBeVisible();
    });
});
