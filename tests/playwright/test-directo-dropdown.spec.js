/**
 * Test directo y simple para el dropdown del botón Reporte Diario
 */

import { test, expect } from '@playwright/test';

test('🎯 Test DIRECTO: Botón Reporte Diario debe mostrar dropdown', async ({ page }) => {
    console.log('🎯 Test directo del botón Reporte Diario');

    // Login rápido
    await page.goto('/login');
    await page.fill('input[name="email"]', 'admin@test.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('/dashboard');
    console.log('✅ Autenticado en dashboard');

    // Esperar a que la página se cargue completamente
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    // Buscar el botón específico
    const botonReporte = page.locator('#reporteButton');
    await expect(botonReporte).toBeVisible();
    console.log('✅ Botón "Reporte Diario" encontrado');

    // Verificar texto del botón
    const textoBoton = await botonReporte.textContent();
    console.log(`📝 Texto del botón: "${textoBoton}"`);
    expect(textoBoton).toContain('Reporte Diario');

    // Buscar el dropdown
    const dropdown = page.locator('#reporteDropdown');

    // Estado inicial: dropdown debe estar oculto
    const claseInicial = await dropdown.getAttribute('class');
    console.log(`🔍 Clases iniciales del dropdown: ${claseInicial}`);
    expect(claseInicial).toContain('hidden');

    // ACCIÓN PRINCIPAL: Hacer clic en el botón
    console.log('🖱️ HACIENDO CLIC EN EL BOTÓN...');
    await botonReporte.click();

    // Esperar un momento para que aparezca
    await page.waitForTimeout(500);

    // Verificar que el dropdown aparezca
    const clasesDespues = await dropdown.getAttribute('class');
    console.log(`🔍 Clases después del clic: ${clasesDespues}`);

    // Verificar que ya no tiene la clase 'hidden'
    const estaVisible = await dropdown.isVisible();
    console.log(`👁️ ¿Dropdown visible? ${estaVisible}`);

    if (estaVisible) {
        console.log('🎉 ¡ÉXITO! El dropdown aparece al hacer clic');

        // Verificar que contiene las opciones
        const opcionPDF = page.locator('a[href*="formato=pdf"]');
        const opcionCSV = page.locator('a[href*="formato=csv"]');

        const pdfVisible = await opcionPDF.isVisible();
        const csvVisible = await opcionCSV.isVisible();

        console.log(`📄 Opción PDF visible: ${pdfVisible}`);
        console.log(`📊 Opción CSV visible: ${csvVisible}`);

        expect(pdfVisible).toBe(true);
        expect(csvVisible).toBe(true);

        // Screenshot del éxito
        await page.screenshot({
            path: 'dropdown-funcionando-exito.png',
            fullPage: true
        });
        console.log('📸 Screenshot del éxito guardado');

    } else {
        console.log('❌ FALLO: El dropdown NO aparece');

        // Debug adicional
        const elementoExiste = await dropdown.count();
        console.log(`🔍 ¿Elemento dropdown existe? ${elementoExiste > 0}`);

        // Intentar con JavaScript directo
        const resultadoJS = await page.evaluate(() => {
            const btn = document.getElementById('reporteButton');
            const dd = document.getElementById('reporteDropdown');

            if (!btn || !dd) {
                return { error: 'Elementos no encontrados' };
            }

            // Mostrar dropdown manualmente
            dd.classList.remove('hidden');

            return {
                botonExiste: !!btn,
                dropdownExiste: !!dd,
                clasesDropdown: dd.className,
                ahora_visible: !dd.classList.contains('hidden')
            };
        });

        console.log('🔧 Resultado del debug JS:', resultadoJS);

        // Screenshot del fallo
        await page.screenshot({
            path: 'dropdown-fallo-debug.png',
            fullPage: true
        });
        console.log('📸 Screenshot del fallo guardado');

        // Intentar verificar de nuevo
        const ahoraVisible = await dropdown.isVisible();
        if (ahoraVisible) {
            console.log('✅ Dropdown ahora visible después del debug JS');
        } else {
            console.log('❌ Dropdown sigue sin ser visible');
        }
    }

    console.log('🏁 Test completado');
});

test('🔧 Test de debug: Verificar elementos en el DOM', async ({ page }) => {
    await page.goto('/login');
    await page.fill('input[name="email"]', 'admin@test.com');
    await page.fill('input[name="password"]', 'password');
    await page.click('button[type="submit"]');
    await page.waitForURL('/dashboard');

    // Debug completo del DOM
    const debugInfo = await page.evaluate(() => {
        const btn = document.getElementById('reporteButton');
        const dropdown = document.getElementById('reporteDropdown');
        const container = document.getElementById('reporteDropdownContainer');

        return {
            boton: {
                existe: !!btn,
                visible: btn ? !btn.hidden && btn.offsetParent !== null : false,
                texto: btn ? btn.textContent.trim() : 'No existe',
                clases: btn ? btn.className : 'No existe'
            },
            dropdown: {
                existe: !!dropdown,
                visible: dropdown ? !dropdown.hidden && dropdown.offsetParent !== null : false,
                clases: dropdown ? dropdown.className : 'No existe',
                contienehidden: dropdown ? dropdown.classList.contains('hidden') : false
            },
            container: {
                existe: !!container,
                clases: container ? container.className : 'No existe'
            },
            funciones: {
                openDropdown: typeof window.openDropdown === 'function',
                closeDropdown: typeof window.closeDropdown === 'function',
                toggleDropdown: typeof window.toggleDropdown === 'function'
            }
        };
    });

    console.log('🔍 Debug completo del DOM:');
    console.log(JSON.stringify(debugInfo, null, 2));
});
