/**
 * Test simple para iniciar sesión y mantenerte logueado
 */

import { test, expect } from '@playwright/test';

test('🚀 Acceso rápido al sistema', async ({ page }) => {
    console.log('🔑 Iniciando sesión automática...');

    // Login automático
    await page.goto('/login');
    await page.fill('input[name="email"]', 'admin@test.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');

    // Esperar a llegar al dashboard
    await page.waitForURL('/dashboard');

    console.log('✅ ¡SESIÓN INICIADA EXITOSAMENTE!');
    console.log('🎯 Dashboard cargado: http://127.0.0.1:8001/dashboard');
    console.log('📊 Puedes usar el dropdown de reportes');
    console.log('💡 Credenciales: admin@test.com / password');

    // Verificar que todo funciona
    await expect(page).toHaveTitle('Sistema Administrativo');
    await expect(page.locator('#reporteButton')).toBeVisible();

    // Abrir el dropdown para confirmar que funciona
    await page.click('#reporteButton');
    await expect(page.locator('#reporteDropdown')).toBeVisible();

    console.log('✨ ¡Sistema completamente funcional!');

    // Tomar screenshot final
    await page.screenshot({ path: 'sistema-listo.png', fullPage: true });
    console.log('📸 Screenshot guardado: sistema-listo.png');
});
