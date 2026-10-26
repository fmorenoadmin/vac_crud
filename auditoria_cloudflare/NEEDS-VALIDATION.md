# Aspectos que Requieren Validación en Entorno de Producción (Needs Validation)

**Marco de Auditoría:** Cloudflare Security Audit Skill  
**Objetivo:** `e:\xampp\htdocs\vac_crud`  

---

De acuerdo con el principio fundamental de Cloudflare:
> *"Deployment controls, proxy behavior, provider settings, browser headers, identity policy, and topology are real controls. If they are required and absent from the repository, do not assume either presence or absence. Use needs_validation with the exact missing fact and a safe owner-observed or local plan."*

Los siguientes puntos representan hipótesis de seguridad que dependen de la configuración del servidor web en producción y que el propietario del sistema debe verificar:

---

### 1. NV-001: Listado de Directorios y Acceso Directo a Archivos PHP en Apache (`.htaccess`)
- **Hecho no resuelto:** El repositorio no contiene un archivo `.htaccess` ni configuración de servidor web que impida el acceso directo a carpetas internas como `clases/`, `config/` o `codes/`.
- **Riesgo potencial:** Si Apache tiene la opción `Options +Indexes` activada en producción, un atacante que navegue a `http://dominio/vac_crud/config/` podrá ver la lista de archivos y descargar respaldos o archivos no interpretados.
- **Plan de validación para el propietario:**
  1. Verificar si al acceder por navegador a `http://localhost/vac_crud/config/` el servidor responde con error `403 Forbidden` o si muestra el listado de archivos.
  2. Asegurar que exista un archivo `.htaccess` en la raíz con la directiva:
     ```apache
     Options -Indexes
     ```

---

### 2. NV-002: Atributos de Seguridad en Cookies de Sesión (`Secure`, `HttpOnly`, `SameSite`)
- **Hecho no resuelto:** La llamada a `session_start()` en `index.php` y en las acciones se ejecuta con la configuración predeterminada del `php.ini`.
- **Riesgo potencial:** Si `session.cookie_httponly` y `session.cookie_samesite` no están configurados en `php.ini`, la cookie de sesión `PHPSESSID` es accesible mediante JavaScript (agravando el impacto de XSS) y viaja en peticiones de sitios cruzados (agravando el impacto de CSRF).
- **Plan de validación para el propietario:**
  1. Inspeccionar en las herramientas de desarrollo del navegador (pestaña Red / Almacenamiento) los flags de la cookie `PHPSESSID`.
  2. Configurar en `config/constant.php` antes de `session_start()`:
     ```php
     session_set_cookie_params([
         'lifetime' => 0,
         'path' => '/',
         'domain' => $_SERVER['HTTP_HOST'],
         'secure' => true,      // Requiere HTTPS
         'httponly' => true,    // Protege contra robo por XSS
         'samesite' => 'Lax'    // Mitigación base contra CSRF
     ]);
     ```

---

### 3. NV-003: Privilegios del Usuario de Base de Datos en Producción
- **Hecho no resuelto:** En `config/constant.php`, la configuración de calidad (`_qas`) usa el usuario `root` sin contraseña. Se desconoce si en producción (`_prd`) se aplican políticas de menor privilegio (Least Privilege).
- **Riesgo potencial:** Si el usuario de producción posee privilegios `SUPER`, `FILE` o `DROP`, una inyección SQL podría derivar en la lectura/escritura de archivos del sistema operativo (`LOAD_FILE` o `INTO OUTFILE`).
- **Plan de validación para el propietario:**
  1. Ejecutar en MySQL de producción: `SHOW GRANTS FOR CURRENT_USER;`
  2. Limitar los permisos del usuario exclusivamente a `SELECT, INSERT, UPDATE, DELETE` sobre la base de datos `vac_crud`.
