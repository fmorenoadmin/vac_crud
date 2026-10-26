# Detalle Técnico de Hallazgos y Remediaciones

**Marco de Auditoría:** Cloudflare Security Audit Skill  
**Objetivo:** `e:\xampp\htdocs\vac_crud`  

---

### FIND-001: Bypass de Autenticación por Sesión Administrativa Fijada por Defecto

- **Clase de Ataque:** CWE-287 (Improper Authentication) / CWE-306 (Missing Authentication for Critical Function)
- **Severidad:** **CRÍTICA** (Impacto: Crítico, Probabilidad: Segura)
- **Huella (Fingerprint):** `vac_crud.auth.hardcoded_session_elevation`
- **Límite de Confianza Violado:** TB-1 (Autenticación de Usuarios)

#### Traza de Código Fuente:
1. **Punto de Entrada:** [`config/constant.php:L50-51`](file:///e:/xampp/htdocs/vac_crud/config/constant.php#L50-L51)
   ```php
   $_SESSION['user_id'] = $uid = 1;
   $_SESSION['tipo_id'] = $rid = 1;
   ```
2. **Propagación:** Cada controlador o vista incluye `config/constant.php`.
3. **Punto de Impacto (Sink):** [`aciones/usuarios.php:L74`](file:///e:/xampp/htdocs/vac_crud/aciones/usuarios.php#L74)
   ```php
   if (isset($_SESSION['user_id'])) { ... }
   ```
   Cualquier petición web evalúa esta condición como verdadera, operando siempre como usuario con `id_u = 1` y rol de administrador (`id_tu = 1`).

#### Invariante de Seguridad Requerida:
Las variables de sesión de identidad (`user_id`, `tipo_id`) jamás deben inicializarse de forma estática en archivos de configuración global; deben establecerse únicamente tras verificar credenciales en un flujo de inicio de sesión (`login`).

#### Remediación Mínima Efectiva:
1. En `config/constant.php`, eliminar o comentar las asignaciones incondicionales:
   ```php
   // REMOVER de config/constant.php:
   // $_SESSION['user_id'] = $uid = 1;
   // $_SESSION['tipo_id'] = $rid = 1;
   $uid = $_SESSION['user_id'] ?? 0;
   $rid = $_SESSION['tipo_id'] ?? 0;
   ```
2. Crear un script de login formal con verificación de contraseña segura (`password_verify()`).

---

### FIND-002: Falsificación de Petición en Sitios Cruzados (CSRF)

- **Clase de Ataque:** CWE-352 (Cross-Site Request Forgery)
- **Severidad:** **ALTA** (Impacto: Alto, Probabilidad: Probable)
- **Huella (Fingerprint):** `vac_crud.csrf.missing_token_state_modification`
- **Límite de Confianza Violado:** TB-2 (Integridad de Operaciones de Estado)

#### Traza de Código Fuente:
1. **Punto de Entrada:** [`aciones/usuarios.php:L191`](file:///e:/xampp/htdocs/vac_crud/aciones/usuarios.php#L191)
   ```php
   if (isset($_REQUEST['meth'])) {
   ```
   Acepta peticiones HTTP GET para alternar el estado del usuario (`act` o `des`).
2. **Operaciones POST:** [`aciones/usuarios.php:L71`](file:///e:/xampp/htdocs/vac_crud/aciones/usuarios.php#L71) y [`L232`](file:///e:/xampp/htdocs/vac_crud/aciones/usuarios.php#L232)
   Procesan inserciones, ediciones y borrados sin validar el encabezado `Origin` / `Referer` ni un token de formulario.

#### Impacto Demostrado:
Un atacante puede alojar una página web con un formulario oculto que se envíe automáticamente mediante JavaScript hacia `aciones/usuarios.php`, o bien incrustar:
```html
<img src="http://localhost/vac_crud/aciones/usuarios.php?pid=Mg==&meth=des&url=aHR0cDovL2xvY2FsaG9zdA==" />
```
Cuando un usuario con sesión activa visite dicha página, el usuario con ID 2 será desactivado inmediatamente.

#### Remediación Mínima Efectiva:
1. Inicializar un token CSRF al iniciar sesión:
   ```php
   if (empty($_SESSION['csrf_token'])) {
       $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
   }
   ```
2. En cada formulario, añadir un campo oculto:
   ```html
   <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>" />
   ```
3. En `aciones/usuarios.php` y `aciones/tipo_usuarios.php`, validar antes de procesar:
   ```php
   if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
       http_response_code(403);
       die('Error de validación de seguridad (CSRF).');
   }
   ```
4. Migrar los cambios de estado (`meth`) de GET a POST exclusivamente.

---

### FIND-003: Redirección Abierta no Validada (Open Redirect)

- **Clase de Ataque:** CWE-601 (URL Redirection to Untrusted Site)
- **Severidad:** **MEDIA** (Impacto: Medio, Probabilidad: Probable)
- **Huella (Fingerprint):** `vac_crud.redirect.open_redirect_url_parameter`
- **Límite de Confianza Violado:** TB-4 (Navegación Confiable)

#### Traza de Código Fuente:
[`aciones/usuarios.php:L209, L224`](file:///e:/xampp/htdocs/vac_crud/aciones/usuarios.php#L209)
```php
$url = base64_decode($_REQUEST['url']);
...
header("Location: ".$url);
exit();
```
Ocurre igualmente en las líneas 92-93, 142-143 y 263-264.

#### Impacto Demostrado:
Si un atacante envía a una víctima un enlace legítimo con `?url=aHR0cHM6Ly9zaXRpby1waGlzaGluZy5jb20=`, la aplicación ejecutará la acción y redirigirá de inmediato al usuario al sitio malicioso de phishing.

#### Remediación Mínima Efectiva:
Validar que la URL pertenezca al mismo dominio o sea una ruta relativa:
```php
function safe_redirect($target_url) {
    $parsed = parse_url($target_url);
    if (!isset($parsed['host']) || $parsed['host'] === $_SERVER['HTTP_HOST']) {
        header("Location: " . $target_url);
    } else {
        header("Location: " . URL);
    }
    exit();
}
```

---

### FIND-004: Sanitización Inadecuada de SQL y Concatenación Dinámica de Consultas

- **Clase de Ataque:** CWE-89 (SQL Injection)
- **Severidad:** **ALTA** (Impacto: Alto, Probabilidad: Probable)
- **Huella (Fingerprint):** `vac_crud.sqli.inadequate_escaping_and_concatenation`
- **Límite de Confianza Violado:** TB-3 (Consultas a Base de Datos)

#### Traza de Código Fuente:
1. [`clases/database.php:L1078-1088`](file:///e:/xampp/htdocs/vac_crud/clases/database.php#L1078-L1088)
   ```php
   public function custom_escape_string($value) {
       $value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
       $value = strip_tags($value);
       $_temp = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
       return $_temp;
   }
   ```
   *Problema:* `htmlspecialchars()` es una función para prevenir XSS en contexto HTML, no para prevenir inyecciones SQL.
2. [`clases/database.php:L2299-2325`](file:///e:/xampp/htdocs/vac_crud/clases/database.php#L2299-L2325)
   ```php
   $clean = function($val) {
       return str_replace("'", "''", $val ?? '');
   };
   ...
   foreach ($dt as $key => $value) {
       $sql .= "{$key}, ";
       $vals .= "'" . $clean($value) . "', ";
   }
   ```
   *Problema:* En MySQL, si la configuración no activa `NO_BACKSLASH_ESCAPES`, una barra invertida `\` anula el entrecomillado y rompe la consulta. Además, los nombres de columna `$key` se concatenan sin comillas invertidas ni verificación contra lista blanca.
3. [`clases/database.php:L2371`](file:///e:/xampp/htdocs/vac_crud/clases/database.php#L2371)
   ```php
   $sql .= "{$this_tid}={$json_pid};";
   ```
   *Problema:* `$json_pid` se concatena sin comillas, posibilitando inyección numérica si no es sanitizado como entero estricto.

#### Remediación Mínima Efectiva:
Adoptar sentencias preparadas parametrizadas estándar (usando PDO o `mysqli_prepare`):
```php
// En lugar de interpolar cadenas en get_sql():
$stmt = $this->db_conec->prepare("INSERT INTO usuarios (nombres, apellidos, usuario, correo, obs) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $nombres, $apellidos, $usuario, $correo, $obs);
$stmt->execute();
```

---

### FIND-005: Secuencias de Comandos en Sitios Cruzados (XSS) Almacenado y Reflejado

- **Clase de Ataque:** CWE-79 (Cross-Site Scripting)
- **Severidad:** **ALTA** (Impacto: Alto, Probabilidad: Probable)
- **Huella (Fingerprint):** `vac_crud.xss.unescaped_reflection_in_views`
- **Límite de Confianza Violado:** TB-5 (Renderizado Seguro en Navegador)

#### Traza de Código Fuente:
1. [`detalle/index.php:L42, L48, L54, L60, L66`](file:///e:/xampp/htdocs/vac_crud/detalle/index.php#L42)
   ```html
   <input type="text" class="form-control" id="nombres" name="nombres" value="<?= $call->nombres; ?>" />
   <textarea class="form-control" id="obs" name="obs"><?= $call->obs; ?></textarea>
   ```
2. [`clases/usuarios.php:L65, L88`](file:///e:/xampp/htdocs/vac_crud/clases/usuarios.php#L65)
   ```php
   $inf.='<option value="No se ejecutó la consulta. Error: '.$res->error.'">';
   ```
   Reflejo directo de errores internos del motor SQL hacia el usuario final.

#### Remediación Mínima Efectiva:
Escapar siempre en el momento de la salida (late-escaping):
```html
<input type="text" class="form-control" id="nombres" name="nombres" value="<?= htmlspecialchars($call->nombres ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
<textarea class="form-control" id="obs" name="obs"><?= htmlspecialchars($call->obs ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
```

---

### FIND-006: Exposición Pública de Credenciales de Base de Datos

- **Clase de Ataque:** CWE-200 (Information Exposure) / CWE-798 (Use of Hard-coded Credentials)
- **Severidad:** **ALTA** (Impacto: Alto, Probabilidad: Segura)
- **Huella (Fingerprint):** `vac_crud.info_exposure.database_credentials_exposed`
- **Límite de Confianza Violado:** TB-1 (Confidencialidad de Credenciales)

#### Traza de Código Fuente:
[`index.php:L63`](file:///e:/xampp/htdocs/vac_crud/index.php#L63)
```php
<?php $html_code = file_get_contents($rut.'config/constant.php'); echo htmlspecialchars($html_code); ?>
```
[`config/constant.php:L24-28`](file:///e:/xampp/htdocs/vac_crud/config/constant.php#L24-L28)
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'vac_crud');
define('DB_USER', 'root');
define('DB_PASS', '');
```

#### Impacto Demostrado:
La página inicial del proyecto muestra voluntariamente a cualquier usuario el contenido textual del archivo que almacena el usuario, host, puerto y contraseña de la base de datos.

#### Remediación Mínima Efectiva:
1. Eliminar de `index.php` el bloque de visualización de `constant.php` y `database.sql`.
2. Mover las credenciales a un archivo de configuración `.env` ubicado fuera de la carpeta pública de Apache (`DocumentRoot`).

---

### FIND-007: Referencia Directa Insegura a Objetos (IDOR) Mediante Base64 Débil

- **Clase de Ataque:** CWE-639 (Insecure Direct Object Reference)
- **Severidad:** **ALTA** (Impacto: Alto, Probabilidad: Segura)
- **Huella (Fingerprint):** `vac_crud.idor.base64_pseudo_security_tampering`
- **Límite de Confianza Violado:** TB-3 (Control de Acceso a Nivel de Recurso)

#### Traza de Código Fuente:
[`aciones/usuarios.php:L199`](file:///e:/xampp/htdocs/vac_crud/aciones/usuarios.php#L199) y [`L240`](file:///e:/xampp/htdocs/vac_crud/aciones/usuarios.php#L240)
```php
$_tbl->pid = base64_decode($_REQUEST['pid']);
...
$_tbl->pid = base64_decode($_POST['pid']);
```

#### Impacto Demostrado:
El parámetro `pid` no está protegido criptográficamente. Codificar el número `5` da como resultado `NQ==`. Si un usuario envía `pid=NQ==`, modificará o eliminará el registro con ID 5 sin importar si dicho recurso le pertenece o si tiene permisos sobre él.

#### Remediación Mínima Efectiva:
Verificar en la capa de negocio la titularidad o permisos antes de invocar `db_edit` o `db_drop`:
```php
if (!tiene_permiso_sobre_usuario($_SESSION['user_id'], $pid)) {
    http_response_code(403);
    die('Acceso denegado al recurso.');
}
```
