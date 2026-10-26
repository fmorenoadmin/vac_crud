# Informe Ejecutivo de Auditoría de Seguridad: vac_crud

**Marco Metodológico:** Cloudflare Security Audit Skill (`cloudflare/security-audit-skill`)  
**Fecha:** 26 de Octubre de 2026  
**Objetivo:** `e:\xampp\htdocs\vac_crud`  
**Perfil de auditoría:** Standard (Full Codebase Review)  
**Estado:** Completado (Completely Verified & Isolated)  

---

## 1. Resumen Ejecutivo

Se ha completado una auditoría exhaustiva de seguridad sobre el proyecto **`vac_crud`**, aplicando rigurosamente los estándares de evaluación defensiva y análisis de límites de confianza desarrollados por **Cloudflare**. 

El análisis se ejecutó en un entorno estrictamente aislado y local, garantizando que **ningún dato o fragmento de código saliera del equipo**. Asimismo, se respetó la directiva de no modificar ningún archivo del código fuente durante el proceso de auditoría.

El proyecto es una aplicación web basada en PHP y MySQL/MariaDB bajo una arquitectura personalizada VAC (Vista - Acción - Clase). La auditoría identificó **7 vulnerabilidades confirmadas** que violan límites de confianza críticos, destacando problemas en autenticación por defecto, ausencia de protección CSRF, sanitización no estándar en consultas a base de datos, y exposición directa de credenciales.

---

## 2. Resumen de Hallazgos Confirmados

| ID | Título | Clase de Ataque (CWE) | Severidad | Probabilidad | Impacto |
|---|---|---|:---:|:---:|:---:|
| **FIND-001** | Bypass de Autenticación por Sesión Fijada | CWE-287 / CWE-306 | **CRÍTICA** | Segura | Crítico |
| **FIND-002** | Falsificación de Petición en Sitios Cruzados (CSRF) | CWE-352 | **ALTA** | Probable | Alto |
| **FIND-003** | Redirección Abierta no Validada (Open Redirect) | CWE-601 | **MEDIA** | Probable | Medio |
| **FIND-004** | Sanitización Inadecuada de SQL y Concatenación | CWE-89 | **ALTA** | Probable | Alto |
| **FIND-005** | Secuencias de Comandos en Sitios Cruzados (XSS) | CWE-79 | **ALTA** | Probable | Alto |
| **FIND-006** | Exposición Pública de Credenciales de Base de Datos | CWE-200 / CWE-798 | **ALTA** | Segura | Alto |
| **FIND-007** | Referencia Directa Insegura a Objetos (IDOR) | CWE-639 | **ALTA** | Segura | Alto |

---

## 3. Vectores Críticos y Análisis de Impacto

1. **Control de Acceso y Autenticación:**
   - La inclusión incondicional de `$_SESSION['user_id'] = 1` y `$_SESSION['tipo_id'] = 1` en `config/constant.php` anula por completo cualquier mecanismo de autenticación. Cualquier actor que acceda al proyecto opera con privilegios totales de administrador.
2. **Integridad de Datos y Mutaciones:**
   - No existen tokens anti-CSRF. Un atacante puede engañar a un administrador para que visite una página maliciosa que realice peticiones automáticas hacia `aciones/usuarios.php` o `aciones/tipo_usuarios.php`, pudiendo eliminar o alterar usuarios silenciosamente.
   - La activación y desactivación de usuarios se realiza mediante el método HTTP GET (`?meth=act`/`?meth=des`), lo que agrava la vulnerabilidad frente a CSRF con simples etiquetas `<img src="...">`.
3. **Capa de Persistencia y Base de Datos:**
   - La función `custom_escape_string()` aplica `htmlspecialchars()` en lugar de un escapeo específico para SQL o sentencias preparadas.
   - En `get_sql()`, se concatenan nombres de columnas directamente, y la limpieza basada en `str_replace("'", "''", ...)` puede ser burlada en MySQL cuando no se deshabilita el escape por barra invertida (`\`).
4. **Seguridad en la Capa de Presentación:**
   - Las vistas de detalle imprimen valores sin escapar en atributos de campos de formulario (`<input value="<?= $call->nombres; ?>">`), lo que permite stored/reflected XSS.
   - La página principal `index.php` expone intencionalmente el código fuente de `constant.php` (incluyendo contraseñas y configuración de BD) a través de `file_get_contents()`.

---

## 4. Declaración de Cobertura y Metodología Cloudflare

La auditoría se realizó cubriendo la totalidad de las superficies del proyecto:
- **Controladores de acción:** `aciones/usuarios.php`, `aciones/tipo_usuarios.php`.
- **Clases del núcleo:** `clases/database.php`, `clases/usuarios.php`, `clases/tipo_usuarios.php`.
- **Configuración:** `config/constant.php`.
- **Vistas e interfaces:** `index.php`, `detalle/index.php`, `codes/*`.

Para el detalle técnico paso a paso, trazabilidad de código y propuesta mínima de remediación para cada hallazgo, consulte [`FINDINGS-DETAIL.md`](./FINDINGS-DETAIL.md). Para los aspectos que dependen de la configuración del servidor web en producción, consulte [`NEEDS-VALIDATION.md`](./NEEDS-VALIDATION.md).
