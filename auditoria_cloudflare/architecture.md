# Arquitectura y Superficie de Ataque: vac_crud

**Fase 1: Reconocimiento (Metodología Cloudflare Security Audit)**  
**Objetivo:** `e:\xampp\htdocs\vac_crud`  
**Tecnología:** PHP 7.x/8.x (Apache / XAMPP), MariaDB / MySQL / PostgreSQL / SQL Server support.

---

## 1. Mapeo de Componentes

El proyecto implementa un patrón personalizado denominado **VAC (Vista - Acción - Clase)**, combinado con una interfaz educativa / demostrativa de código:

1. **Capa de Configuración y Constantes (`config/constant.php`):**
   - Define URLs base, rutas de carpetas, credenciales de conexión a bases de datos (`_qas` y `_prd`) y variables globales de sesión (`$_SESSION['user_id']`, `$_SESSION['tipo_id']`).
2. **Capa de Controladores de Acción (`aciones/usuarios.php`, `aciones/tipo_usuarios.php`):**
   - Puntos de entrada para operaciones CRUD: `nuevo` (POST), `editar` (POST), `eliminar` (POST), `meth` (GET para activar/desactivar estado) y `id` (POST para consultar registro único).
   - Manipula la sesión y redirige mediante `header("Location: " . $url)`.
3. **Capa de Persistencia y Negocio (`clases/database.php`, `clases/usuarios.php`, `clases/tipo_usuarios.php`):**
   - Abstracción de conexión multibase de datos (`mysqli_`, `pg_`, `sqlsrv_`, `oci_`).
   - Generación dinámica de sentencias SQL mediante `get_sql()`.
   - Limpieza de datos delegada en `custom_escape_string()`.
4. **Capa de Vista y Exposición (`index.php`, `detalle/index.php`, `tipo_usuarios/index.php`, `codes/*`):**
   - Renderiza tablas interactivas con Bootstrap 4.
   - Lee archivos locales con `file_get_contents()` para mostrar el código fuente de las acciones y clases en pantalla.

---

## 2. Límites de Confianza (Trust Boundaries)

| Frontera | Lado No Confiable (Actor / Entrada) | Lado Confiable (Control / Recurso) | Mecanismo Previsto |
|---|---|---|---|
| **TB-1: Autenticación Web** | Usuario anónimo / Navegador web | Sesión administrativa del sistema (`$_SESSION`) | Constante `constant.php` asignando sesión por defecto. |
| **TB-2: Integridad de Operaciones (CSRF)** | Peticiones HTTP externas / Enlaces externos | Modificación de base de datos (`usuarios`, `tipo_usuarios`) | Ninguno (Sin tokens anti-CSRF). |
| **TB-3: Validación de Parámetros** | Parámetros `$_POST`, `$_GET`, `$_REQUEST` | Consultas a la base de datos | `custom_escape_string()` y `base64_decode()`. |
| **TB-4: Redirección de Navegación** | Parámetro `url` codificado en Base64 | Cabecera HTTP `Location` | Decodificación directa y redirección. |
| **TB-5: Renderizado en el DOM** | Contenido almacenado en BD | Navegador de los usuarios / Administradores | Inserción directa en HTML (`<?= ... ?>`). |

---

## 3. Superficie de Entrada (Input Surfaces)

1. **Parámetros HTTP GET:**
   - `?p=`: Identificador codificado en Base64 para vistas de detalle.
   - `?pid=`: Identificador de registro para activación/desactivación.
   - `?meth=`: Operación de estado (`act`, `des`).
   - `?url=`: URL de retorno tras ejecutar la acción.
2. **Parámetros HTTP POST:**
   - `nuevo`, `editar`, `eliminar`, `id`: Verbos de acción.
   - `nombres`, `apellidos`, `usuario`, `correo`, `obs`, `nombre`: Campos de texto de entidades.
   - `id_tu`, `uid`, `pid`: Identificadores relacionales codificados en Base64.
   - `motivo_drop`: Justificación textual de eliminación lógica.
3. **Lectura de Archivos Locales en Runtime:**
   - `file_get_contents($rut . 'database.sql')`
   - `file_get_contents($rut . 'config/constant.php')`
   - `file_get_contents($rut . 'codes/*.php')`
