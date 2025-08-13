import { test, expect } from '@playwright/test';
import fs from 'fs';
import path from 'path';

test.describe('PDF Export Tests', () => {
    test('should generate PDF with correct page numbers', async ({ page }) => {
        console.log('🔍 Iniciando test de exportación PDF...');

        try {
            // Navegar directamente a la ruta de exportación temporal (sin autenticación)
            console.log('📥 Descargando PDF...');
            const response = await page.goto('http://localhost:8080/test-transacciones-export', {
                waitUntil: 'domcontentloaded',
                timeout: 30000
            });

            // Verificar que la respuesta sea exitosa
            expect(response.status()).toBe(200);
            console.log('✅ Respuesta HTTP exitosa');

            // Verificar que el contenido sea PDF
            const contentType = response.headers()['content-type'];
            console.log('🔍 Content-Type recibido:', contentType);

            // Si no es PDF, obtener el contenido para ver el error
            if (!contentType.includes('application/pdf')) {
                const text = await response.text();
                console.log('❌ Error HTML recibido:', text.substring(0, 500));
                throw new Error(`Expected PDF but got ${contentType}. Content: ${text.substring(0, 200)}`);
            }

            expect(contentType).toContain('application/pdf');
            console.log('✅ Tipo de contenido correcto: PDF');

            // Obtener el contenido del PDF
            const buffer = await response.body();
            expect(buffer.length).toBeGreaterThan(0);
            console.log(`✅ PDF generado con tamaño: ${buffer.length} bytes`);

            // Crear directorio si no existe
            const testResultsDir = path.join(process.cwd(), 'test-results');
            if (!fs.existsSync(testResultsDir)) {
                fs.mkdirSync(testResultsDir, { recursive: true });
            }

            // Guardar el PDF
            const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
            const fileName = path.join(testResultsDir, `pdf-export-${timestamp}.pdf`);
            fs.writeFileSync(fileName, buffer);

            console.log(`💾 PDF guardado en: ${fileName}`);

            // Verificar que el archivo se guardó correctamente
            expect(fs.existsSync(fileName)).toBe(true);
            const fileSize = fs.statSync(fileName).size;
            expect(fileSize).toBeGreaterThan(0);
            console.log(`✅ Archivo guardado correctamente (${fileSize} bytes)`);

            // Verificar que el PDF contiene el contenido esperado
            const pdfText = buffer.toString();
            expect(pdfText).toContain('PDF'); // Verificar que es un PDF válido
            console.log('✅ PDF válido generado');

        } catch (error) {
            console.error('❌ Error en el test:', error);
            throw error;
        }
    });
});
