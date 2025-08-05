<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Test de autenticación siguiendo las mejores prácticas de Context7.
 * Combina testing backend de Laravel con validación frontend via Playwright.
 */
class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que verifica el flujo de login con credenciales válidas.
     * Implementa patrones de testing recomendados por Context7.
     */
    public function test_login_with_valid_credentials(): void
    {
        // Crear usuario usando factory (mejores prácticas Laravel)
        $user = User::factory()->create([
            'name' => 'Test Administrator',
            'email' => 'admin@test.com',
            'password' => bcrypt('admin123'),
            'email_verified_at' => now(),
        ]);

        // Simular POST al endpoint de login
        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'admin123',
        ]);

        // Verificar redirección al dashboard
        $response->assertRedirect('/dashboard');
        
        // Verificar que el usuario está autenticado
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test que verifica el manejo de credenciales inválidas.
     */
    public function test_login_with_invalid_credentials(): void
    {
        // Intentar login con credenciales incorrectas
        $response = $this->post('/login', [
            'email' => 'wrong@email.com',
            'password' => 'wrongpassword',
        ]);

        // Verificar que hay errores de validación
        $response->assertSessionHasErrors();
        
        // Verificar que no hay usuario autenticado
        $this->assertGuest();
    }

    /**
     * Test que verifica la funcionalidad de logout.
     */
    public function test_logout_functionality(): void
    {
        // Crear y autenticar usuario
        $user = User::factory()->create([
            'email' => 'test@logout.com',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($user);

        // Verificar que está autenticado
        $this->assertAuthenticated();

        // Hacer logout
        $response = $this->post('/logout');

        // Verificar redirección a home
        $response->assertRedirect('/');
        
        // Verificar que ya no está autenticado
        $this->assertGuest();
    }

    /**
     * Test que verifica el acceso a rutas protegidas sin autenticación.
     */
    public function test_dashboard_requires_authentication(): void
    {
        // Intentar acceder al dashboard sin autenticación
        $response = $this->get('/dashboard');

        // Debería redirigir al login
        $response->assertRedirect('/login');
    }

    /**
     * Test que verifica el acceso al dashboard con autenticación.
     */
    public function test_authenticated_user_can_access_dashboard(): void
    {
        // Crear y autenticar usuario
        $user = User::factory()->create([
            'name' => 'Dashboard User',
            'email' => 'dashboard@test.com',
        ]);

        $this->actingAs($user);

        // Acceder al dashboard
        $response = $this->get('/dashboard');

        // Verificar respuesta exitosa
        $response->assertOk();
        
        // Verificar contenido del dashboard
        $response->assertSee('Dashboard');
        $response->assertSee('Bienvenido, Dashboard User');
    }

    /**
     * Test que verifica la redirección automática desde root.
     */
    public function test_root_redirects_authenticated_user_to_dashboard(): void
    {
        // Crear y autenticar usuario
        $user = User::factory()->create();
        $this->actingAs($user);

        // Acceder a la raíz
        $response = $this->get('/');

        // Debería redirigir al dashboard
        $response->assertRedirect('/dashboard');
    }

    /**
     * Test que verifica que usuarios no autenticados ven el login.
     */
    public function test_root_shows_login_for_guest_users(): void
    {
        // Acceder a la raíz sin autenticación
        $response = $this->get('/');

        // Debería mostrar la página de login
        $response->assertOk();
        $response->assertSee('Inicia sesión para acceder al sistema');
        $response->assertSee('Sistema Administrativo');
    }
}
