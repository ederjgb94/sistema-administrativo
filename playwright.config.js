/**
 * Configuración de Playwright para Sistema Administrativo
 * Implementando mejores prácticas de Context7 para testing automatizado
 */

import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
    // Directorio donde están los tests
    testDir: './tests/playwright',

    // Patrón de archivos de test
    testMatch: '**/*.spec.js',

    // Timeout por test (Context7: tiempos realistas)
    timeout: 30000,

    // Configuración global de expect
    expect: {
        timeout: 5000
    },

    // Configuración de ejecución
    fullyParallel: true,
    forbidOnly: !!process.env.CI,
    retries: process.env.CI ? 2 : 0,
    workers: process.env.CI ? 1 : undefined,

    // Reporter (Context7: reporting detallado)
    reporter: [
        ['html', {
            outputFolder: 'playwright-report',
            open: 'never'
        }],
        ['json', {
            outputFile: 'test-results/results.json'
        }],
        ['list']
    ],

    // Configuración global para todos los tests
    use: {
        // URL base del proyecto
        baseURL: 'http://127.0.0.1:8000',

        // Configuración de browser
        headless: true,
        viewport: { width: 1280, height: 720 },
        ignoreHTTPSErrors: true,

        // Screenshots y videos para debugging (Context7)
        screenshot: 'only-on-failure',
        video: 'retain-on-failure',
        trace: 'retain-on-failure',

        // Configuración de timeouts
        actionTimeout: 10000,
        navigationTimeout: 30000,
    },

    // Configuración de proyectos para diferentes browsers
    projects: [
        {
            name: 'chromium',
            use: {
                ...devices['Desktop Chrome'],
                // Configuración específica para Chrome
                launchOptions: {
                    args: ['--disable-web-security', '--disable-features=VizDisplayCompositor']
                }
            },
        },

        {
            name: 'firefox',
            use: { ...devices['Desktop Firefox'] },
        },

        {
            name: 'webkit',
            use: { ...devices['Desktop Safari'] },
        },

        // Tests móviles (Context7: responsive testing)
        {
            name: 'Mobile Chrome',
            use: { ...devices['Pixel 5'] },
        },

        {
            name: 'Mobile Safari',
            use: { ...devices['iPhone 12'] },
        },
    ],

    // Servidor de desarrollo (Context7: automatizar setup)
    webServer: {
        command: 'php artisan serve',
        port: 8000,
        reuseExistingServer: !process.env.CI,
        timeout: 120000,
    },

    // Directorio para artefactos
    outputDir: 'test-results/',

    // Configuración de globalSetup (si necesario)
    // globalSetup: require.resolve('./tests/playwright/global-setup.js'),

    // Configuración de globalTeardown (si necesario)  
    // globalTeardown: require.resolve('./tests/playwright/global-teardown.js'),
});
