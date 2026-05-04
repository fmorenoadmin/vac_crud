# Metodología de Programación VAC (Vista-Acción-Clase)

La metodología **VAC** es un patrón de arquitectura para aplicaciones PHP Nativo que busca organizar el código en tres capas principales: **Vista**, **Acción** y **Clase**. A diferencia del MVC tradicional, el VAC en este repositorio presenta una fuerte componentización donde la capa de datos (Clase) a menudo asume responsabilidades de presentación parcial (generación de HTML).

---

## 1. Arquitectura de las Capas

### 🟢 V - Vista (View)
Es la capa de interfaz de usuario. En este repositorio, se manifiesta de dos formas:
- **Estructura HTML**: Archivos que definen la estructura general (Bootstrap 4, FontAwesome).
- **Snippets de Código**: Localizados en la carpeta `codes/` (ej. `list_html.php`, `add_html.php`). 
- **Modales y Formularios**: Se utilizan modales para las operaciones de CRUD, desacoplando el formulario de la página principal.

### 🔵 A - Acción (Action)
Ubicada en la carpeta `aciones/`, es el "orquestador" o controlador de la lógica de negocio.
- **Intercepción de Peticiones**: Detecta acciones mediante variables `$_POST` (ej. `if (isset($_POST['nuevo']))`).
- **Validación y Preparación**: Limpia los datos de entrada (`custom_escape_string`) y prepara arrays para la persistencia.
- **Comunicación**: Instancia las Clases necesarias y llama a sus métodos.
- **Redirección**: Maneja el flujo de navegación mediante `header("Location: ...")`.

### 🔴 C - Clase (Class)
Ubicada en la carpeta `clases/`, maneja la lógica de datos y la generación de componentes.
- **Extensión de Base**: Todas las clases heredan de `database.php`.
- **Generación de UI**: Una característica distintiva es que métodos como `listar()` devuelven directamente código HTML (tablas `<thead>`/`<tbody>`) listo para ser renderizado en la Vista.
- **Abstracción SQL**: Define la tabla, vista de base de datos y llaves primarias asociadas al modelo.

---

## 2. El Corazón Técnico: `database.php`

El archivo `database.php` es una pieza fundamental de esta metodología, proporcionando una capa de abstracción extremadamente robusta:

- **Multi-Motor**: Soporta de forma nativa e intercambiable:
  - MySQL / MariaDB (via `mysqli`)
  - PostgreSQL (via `pg`)
  - SQL Server (via `sqlsrv`)
  - Oracle (via `oci`)
- **Mapeo Polimórfico**: Utiliza una función interna `map_functions` que vincula las funciones nativas de PHP a propiedades de clase (ej. `$this->db_query` puede apuntar a `mysqli_query` o `pg_query` según la configuración).
- **Seguridad y SSL**: Incluye soporte integrado para conexiones cifradas y validación de tokens (ej. Cloudflare Turnstile).

---

## 3. Flujo de Trabajo Típico (CRUD)

1.  **Petición**: El usuario hace clic en "Agregar" en la **Vista**.
2.  **Acción**: El archivo en `aciones/` detecta el envío del formulario.
3.  **Procesamiento**:
    - La **Acción** valida los campos.
    - Se llama al método `db_add` o `db_edit` de la clase base a través de la **Clase** específica.
4.  **Respuesta**: La **Acción** guarda el resultado (éxito/error) en la sesión y redirige a la **Vista** principal.
5.  **Renderizado**: La **Vista** solicita a la **Clase** el listado actualizado (`listar()`), el cual devuelve el HTML de la tabla para ser mostrado.

---

## 4. Características Clave y Buenas Prácticas Encontradas

- **Base64 para IDs**: Se utiliza codificación Base64 en los parámetros de URL para ocultar IDs numéricos simples y URLs de redirección.
- **Estandarización de Respuestas**: Casi todos los métodos devuelven un objeto `stdClass` con propiedades consistentes: `result`, `mensaje`, `cant`, `sql`.
- **Configuración Centralizada**: Uso de `config/constant.php` para definir rutas globales (`DIRACT`, `DIRCLA`) y entornos (QAS/PRD).
- **Componentes Reutilizables**: Métodos como `cbo()` (combobox) o `cal_fecha()` permiten reutilizar lógica visual en toda la aplicación.

---

## 5. Aplicaciones Sugeridas

Esta metodología es ideal para:
- **Sistemas Administrativos (ERP/CRM)**: Donde se requiere una alta velocidad de creación de módulos CRUD.
- **Proyectos Multi-base de datos**: Aplicaciones que deban ser desplegadas en diferentes infraestructuras sin reescribir la lógica de datos.
- **Herramientas Internas**: Proyectos donde la simplicidad de PHP Nativo permite un mantenimiento fácil sin depender de frameworks pesados.
