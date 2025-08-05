# Page snapshot

```yaml
- img
- heading "Sistema Administrativo" [level=2]
- paragraph: Inicia sesión para acceder al sistema
- text: Correo Electrónico
- textbox "Correo Electrónico"
- text: Contraseña
- textbox "Contraseña"
- checkbox "Recordarme"
- text: Recordarme
- link "¿Olvidaste tu contraseña?":
  - /url: http://127.0.0.1:8000/forgot-password
- button "Iniciar Sesión":
  - img
  - text: Iniciar Sesión
- text: ¿No tienes cuenta?
- link "Regístrate aquí":
  - /url: http://127.0.0.1:8000/register
```