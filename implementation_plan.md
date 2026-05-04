# Plan: Resumen Metodología VAC (Vista-Acción-Clase)

El objetivo es crear un documento detallado que explique la metodología VAC utilizada en el repositorio, sus componentes, flujo de trabajo y aplicaciones.

## Pasos a seguir:

1.  **Analizar la estructura del repositorio**: Ya realizado. Identificados los directorios `clases/`, `aciones/`, `codes/` y el `index.php` como demostrador.
2.  **Definir los componentes de VAC**:
    *   **V (Vista)**: Ubicada en `codes/` y archivos HTML/PHP de interfaz.
    *   **A (Acción)**: Ubicada en `aciones/`. Maneja la lógica de negocio y peticiones.
    *   **C (Clase)**: Ubicada en `clases/`. Maneja el acceso a datos y generación de componentes de interfaz (tablas, combos).
3.  **Identificar características clave**:
    *   Abstracción de Base de Datos Multi-motor (MySQL, Postgres, SQL Server, Oracle).
    *   Uso de objetos `stdClass` para respuestas estandarizadas.
    *   Codificación Base64 para parámetros sensibles en la URL.
    *   Generación de HTML desde las clases (Componentización).
4.  **Redactar el documento resumen**: Crear un artefacto `resumen_metodologia_vac.md`.
5.  **Verificación**: Asegurar que el resumen cubra todos los archivos y patrones encontrados.

## Contenido del resumen:
*   Introducción a VAC.
*   Descripción detallada de cada capa (Vista, Acción, Clase).
*   El rol de la clase base `database.php`.
*   Flujo de trabajo de un CRUD típico.
*   Ventajas y Aplicaciones.
