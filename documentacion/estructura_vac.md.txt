esta serà la dicumentaciòn de la metodología de programación VAC con PHP Nativa, desarrollada por FMORENOADMIN, el cual compartió su Google Gems con esta cuenta: https://gemini.google.com/gem/1n10HLnr6JQpcVhxbGLR-S688JSMWwEo9?usp=sharing

# 1. CORE Clase conectora con la Base de datos: `DataBase.php` o `database.php` (dependiendo del proyecto puede cambiar entre estos 2 archivos, es lo mismo, solo cambia el nombre en el archivo, mas no la funcionalidad)

La clase `DataBase` o `database` es el motor central de la metodología **VAC** (*Vista - Acción - Clase/Modelo*). Su propósito es proporcionar una capa de abstracción polimórfica y multimotor capaz de conectarse y ejecutar operaciones de manera transparente en cuatro motores de base de datos relacionales:
* **MySQL / MariaDB** (`mysqli_`)
* **PostgreSQL** (`pg_`)
* **SQL Server** (`sqlsrv_`)
* **Oracle Database** (`oci_`)

---

## 1.1. Atributos de la Clase

### Conexión y Entornos
* `$db_prd` / `$db_qas`: Dirección IP o Host de los servidores de Producción (`_prd`) y Pruebas (`_qas`), mapeados desde la constante `DB_HOST`.
* `$db_name_prd` / `$db_name_qas`: Nombre de la base de datos activa, tomado de `DB_NAME`.
* `$db_user_prd` / `$db_user_qas`: Usuario autenticado, tomado de `DB_USER`.
* `$db_pass_prd` / `$db_pass_qas`: Contraseña de la base de datos, tomada de `DB_PASS`.
* `$db_port`: Puerto de red de la base de datos, tomado de `DB_PORT` (por defecto `5432` en PostgreSQL / `3306` en MySQL).

### Configuración SSL/TLS
* `$ssl_mode` *(bool)*: Define si la conexión requiere cifrado SSL/TLS.
* `$ssl_key` / `$ssl_cert` / `$ssl_ca` / `$ssl_capath` / `$ssl_cipher`: Rutas de certificados de cliente, llave privada y CA.

### Diccionario de Funciones Mapeadas Polimórficas
Variables protegidas que almacenan el nombre de la función nativa de PHP según el motor activo:
* `$db_type`: Almacena el prefijo del motor activo (`mysqli_`, `pg_`, `sqlsrv_`, `oci_`).
* `$db_conec`: Función para conectar (ej. `pg_connect`, `mysqli_connect`).
* `$db_query`: Función para ejecutar consultas (ej. `pg_query`, `mysqli_query`).
* `$db_error`: Función para obtener el último mensaje de error (ej. `pg_last_error`, `mysqli_error`).
* `$db_fetch` / `$db_object` / `$db_assoc` / `$db_array`: Funciones de extracción de filas.
* `$db_num_r`: Función para contar filas afectadas/devueltas.
* `$db_fre_r`: Función para liberar memoria del cursor de resultados.
* `$db_close`: Función para cerrar la conexión.

---

## 1.2. Inicialización y Configuración

### `__construct()`
* **¿Qué recibe?**: Nada (constructor sin parámetros).
* **¿Qué hace?**: Detecta la constante global `DB_TYPE` (por defecto `'mysqli_'`) y ejecuta `$this->map_functions($this->db_type)` para cargar el diccionario de funciones nativas del motor correspondiente.
* **¿Qué devuelve?**: Instancia del objeto `DataBase`.

### `load_other_type($_db_type)`
* **¿Qué recibe?**: 
  * `$_db_type` *(string, requerido)*: Prefijo del nuevo motor de BD a utilizar temporalmente (ej. `'pg_'`, `'mysqli_'`).
* **¿Qué hace?**: Cambia dinámicamente la propiedad `$this->db_type` y recarga las funciones nativas mapped mediante `map_functions()`.
* **¿Qué devuelve?**: `void` (nada).

### `config_ssl($key, $cert, $ca, $capath=null, $cipher=null)`
* **¿Qué recibe?**: Nombres/rutas relativas de archivos de llave privada (`$key`), certificado cliente (`$cert`) y autoridad certificadora (`$ca`).
* **¿Qué hace?**: Activa `$this->ssl_mode = true` y concatena la ruta base `$this->ruta_certs` con los nombres de archivos.
* **¿Qué devuelve?**: `void` (nada).

### `map_functions($type)` *(Privado)*
* **¿Qué recibe?**: `$type` *(string, requerido)*: Nombre del motor (`mysqli_`, `pg_`, `sqlsrv_`, `oci_`).
* **¿Qué hace?**: Asigna las funciones nativas de PHP a las variables de clase protegidas según el motor seleccionado.
* **¿Qué devuelve?**: `void`.

### `connect($schu=null, $db='con', $_db_type=null)`
* **¿Qué recibe?**:
  * `$schu` *(string, opcional)*: Entorno `'PRD'` o `'QAS'` (por defecto lee la constante `SCHU`).
  * `$db` *(string, opcional)*: Identificador del esquema o base de datos específica (ej. `'con'`, `'vac2'`, `'vac3'`).
  * `$_db_type` *(string, opcional)*: Motor específico para esta conexión.
* **¿Qué ejecuta?**: Construye la cadena de conexión o parámetros requeridos según el motor (`pg_connect`, `mysqli_real_connect`, `sqlsrv_connect`, `oci_connect`), aplica SSL si `$ssl_mode` está activo y establece la codificación a UTF-8.
* **¿Qué devuelve?**: Recurso de conexión / objeto de conexión nativo de PHP (o `false` si falla).

---

## 1.3. Métodos Principales de Ejecución Directa (Núcleo)

### `db_exec($sql, $ret_res=true, $db='con', $_db_type=null)`
* **¿Qué recibe?**:
  * `$sql` *(string, requerido)*: Sentencia SQL a ejecutar.
  * `$ret_res` *(bool, opcional, por defecto `true`)*: Indica si se debe adjuntar el recurso original de consulta en `$data->res`.
  * `$db` *(string, opcional)*: Identificador de BD.
  * `$_db_type` *(string, opcional)*: Motor dinámico.
* **¿Qué hace?**: Abre conexión, ejecuta la sentencia SQL en el motor activo (manejando cursores y sintaxis especiales como Oracle/SQLServer), calcula la cantidad de filas afectadas/devueltas sin lanzar errores fatales.
* **¿Qué devuelve?**: `stdClass` objeto respuesta con la siguiente estructura:
  * `->result` *(bool)*: `true` si la ejecución fue exitosa, `false` si falló.
  * `->cant` *(int)*: Número de filas afectadas o encontradas.
  * `->res` *(resource/object|null)*: Recurso/statement de la consulta (si `$ret_res` es `true`).
  * `->error` *(string|null)*: Mensaje de error retornado por la base de datos.
  * `->mensaje` *(string)*: Descripción del estado ('Ejecutado exitosamente' o 'Error de Conexión').

### `db_exec_sql($sql, $ret_res=true, $db='con', $_db_type=null)`
* **¿Qué recibe?**:
  * `$sql` *(string, requerido)*: Sentencia SQL SELECT que retorna **una sola fila**.
  * `$ret_res`, `$db`, `$_db_type`.
* **¿Qué hace?**: Ejecuta la consulta SQL y realiza un *fetch asociativo* automático. Mapea cada columna de la fila devuelta como **propiedades directas** del objeto `$data` retornado.
* **¿Qué devuelve?**: `stdClass` objeto respuesta conteniendo:
  * `->result` *(bool)*: `true` si encontró la fila y se mapearon los campos.
  * `->cant` *(int)*: `1` si encontró registro, `0` si estuvo vacío, `-1` si falló.
  * `->[nombre_columna]`: Propiedad dinámica por cada columna devuelta en el SELECT (ej. `$data->nombres_u`).
  * `->error` / `->mensaje`.

### `db_exec_sql_array($sql, $ret_res=true, $db='con', $_db_type=null)`
* **¿Qué recibe?**:
  * `$sql` *(string, requerido)*: Consulta SQL SELECT que retorna **múltiples filas**.
  * `$ret_res`, `$db`, `$_db_type`.
* **¿Qué hace?**: Ejecuta la consulta SQL y recorre todas las filas devueltas creando un array de arrays asociativos de manera polimórfica. Cierra la conexión automáticamente.
* **¿Qué devuelve?**: `stdClass` objeto respuesta conteniendo:
  * `->result` *(bool)*: `true` si se ejecutó correctamente.
  * `->cant` *(int)*: Número de registros en la lista.
  * `->datos` *(array)*: Matriz/Array de filas asociativas `[ ['col1' => 'val1', ...], ... ]`.
  * `->error` / `->mensaje`.

---

## 1.4. Generador de Consultas Dinámicas SQL

### `get_sql($this_table, $dt, $tipo=1, $this_tid=null, $json_pid=null, $return=false, $adic=null)`
* **¿Qué recibe?**:
  * `$this_table` *(string)*: Nombre de la tabla o vista (ej. `'public.usuarios'`).
  * `$dt` *(array|null)*: Array asociativo de campos y valores `['campo' => 'valor']` o lista de columnas a consultar.
  * `$tipo` *(int)*: Código de la sentencia SQL a construir:
    * `1`: `INSERT INTO` (Soporta `RETURNING` en PostgreSQL o `OUTPUT` en SQL Server si `$return` es `true`).
    * `2` / `default`: `UPDATE` estándar por ID numérico (`{$this_tid}={$json_pid}`).
    * `3`: Invocación de Función / Procedimiento Almacenado `SELECT funcion('val1', 'val2')`.
    * `4`: `SELECT *` filtrado dinámicamente por los campos de `$dt` excepto metadatos de auditoría (`created_at`, `status`, etc.).
    * `5`: `UPDATE` por ID numérico agregando condiciones adicionales `$adic` en `WHERE`.
    * `6`: `SELECT * FROM tabla;` (Listar todo).
    * `7`: `UPDATE` por ID de tipo String usando `WHERE {$this_tid} LIKE '{$json_pid}'`.
    * `8`: `SELECT * FROM tabla WHERE id = (INT)` o `SELECT t1.*, c.nombre_comp AS user_add, c.correo_u AS mail_add, e.nombre_comp AS user_edit, e.correo_u AS mail_edit FROM tabla t1 LEFT OUTER JOIN public.view_users_all c ON t1.id_created=c.id_usuario LEFT OUTER JOIN public.view_users_all e ON t1.id_updated=e.id_usuario WHERE {$this_tid}={$json_pid};` (Consulta por ID entero realizando LEFT JOINs automáticos con la vista de usuarios `public.view_users_all` para adjuntar los datos del creador `user_add`/`mail_add` y del editor `user_edit`/`mail_edit`).
    * `9`: `SELECT * FROM tabla WHERE id LIKE 'string'` o `SELECT t1.*, c.nombre_comp AS user_add, c.correo_u AS mail_add, e.nombre_comp AS user_edit, e.correo_u AS mail_edit FROM tabla t1 LEFT OUTER JOIN public.view_users_all c ON t1.id_created=c.id_usuario LEFT OUTER JOIN public.view_users_all e ON t1.id_updated=e.id_usuario WHERE {$this_tid} LIKE '{$json_pid}';` (Consulta por ID string realizando LEFT JOINs automáticos con `public.view_users_all` para auditoría de creación y edición).
    * `10`: `SELECT col1, col2 FROM tabla WHERE {$this_tid} LIKE '{$json_pid}'`.
    * `11`: `SELECT col1, col2 FROM tabla WHERE {$this_tid}={$json_pid}` (Con soporte de filtro `$adic`).
    * `12`: `SELECT * FROM tabla WHERE {$this_tid}={$json_pid}` (Consulta directa por ID entero sin uniones con usuarios).
  * `$this_tid` *(string|null)*: Nombre de la columna llave primaria (PK).
  * `$json_pid` *(mixed|null)*: Valor de la llave primaria.
  * `$return` *(bool)*: Define si se agrega cláusula de retorno de ID insertado (`OUTPUT` / `RETURNING`).
  * `$adic` *(string|null)*: Condiciones adicionales SQL para concatenar en el `WHERE`.
* **¿Qué hace?**: Aplica sanitización y escape de comillas simples (`str_replace("'", "''", $val)`) para prevención de inyección SQL y genera la sentencia SQL formateada respetando el motor de BD activo.
* **¿Qué devuelve?**: `string` conteniendo la consulta SQL lista para su ejecución.

---

## 1.5. Métodos CRUD de Alto Nivel y Consultas Polimórficas

### `get_datos($pid, $type, $db='con', $_db_type=null)`
* **¿Qué recibe?**:
  * `$pid` *(mixed, requerido)*: ID, clave o término de búsqueda a consultar (numérico o texto sanitizado).
  * `$type` *(string, requerido)*: Identificador del tipo de consulta prediseñada a ejecutar:
    * `'user'`: Consulta un usuario activo por `id_usuario` en `public.view_users_all`.
    * `'ticket'`: Consulta un ticket por `id_t` en `public.view_tickets_all`.
    * `'clie'`: Consulta un cliente (`id_tipo IN (22, 23)`) por `id_ent` en `public.view_entitys_all`.
    * `'prov'`: Consulta un proveedor (`id_tipo IN (25, 26)`) por `id_ent` en `public.view_entitys_all`.
    * `'produc'`: Consulta un producto por `id_p` en `public.view_products_all`.
    * `'produc_all'`: Búsqueda textual de productos por coincidencia en `text LIKE '%pid%'` (retorna array de resultados).
    * `'tc'`: Consulta tipo de cambio por fecha en la tabla `tc`.
    * `'venta'`: Consulta una venta por `id_v` en `public.view_vents_all`.
    * `'doc_v'`: Consulta documento de venta por `id_doc` en `public.view_vents_docs_all`.
  * `$db` *(string, opcional, por defecto `'con'`)*: Identificador de la base de datos objetivo.
  * `$_db_type` *(string, opcional)*: Motor específico temporal para esta consulta.
* **¿Qué hace?**:
  1. Gestiona la conmutación dinámica de motor de BD con `load_other_type()`.
  2. Ajusta la sintaxis polimórfica de límite de registros (`TOP 1` en SQL Server, `FETCH FIRST 1 ROWS ONLY` en Oracle, `LIMIT 1` en PostgreSQL/MySQL).
  3. Construye la sentencia SQL segura y la ejecuta mediante `db_exec_sql()` (para registro único) o `db_exec_sql_array()` (para múltiples registros como `'produc_all'`).
  4. Restaura el motor de BD original al finalizar la ejecución.
* **¿Qué devuelve?**: `stdClass` objeto respuesta con los datos mapeados o mensaje de error en caso de fallo.

### `db_get_id($dt, $json, $db='con', $_db_type=null)`
* **¿Qué recibe?**: `$json` objeto con `tname` (tabla), `tid` (columna ID), `pid` (valor ID numérico).
* **¿Qué hace?**: Valida que `pid` sea numérico mayor a 0, genera la consulta con `get_sql(..., tipo=8)` (incluyendo los JOINs de auditoría de usuarios) y ejecuta `db_exec_sql()`.
* **¿Qué devuelve?**: `stdClass` con los datos de la fila mapeados en sus propiedades directas (incluyendo `user_add`, `mail_add`, `user_edit`, `mail_edit`).

### `db_get_string($dt, $json, $db='con', $_db_type=null)`
* **¿Qué recibe?**: `$json` objeto con `tname`, `tid`, `pid` (valor alfanumérico string).
* **¿Qué hace?**: Valida parámetros, genera la consulta con `get_sql(..., tipo=9)` con filtro `LIKE` (incluyendo JOINs de usuarios) y ejecuta `db_exec_sql()`.
* **¿Qué devuelve?**: `stdClass` con la fila mapeada y metadatos de auditoría.

### `db_get_camp_id_array($dt, $json, $db='con', $_db_type=null)`
* **¿Qué recibe?**: `$dt` array con nombres de columnas solicitadas; `$json` con `tname`, `tid`, `pid`, `adic`.
* **¿Qué hace?**: Genera `get_sql(..., tipo=11)` y ejecuta `db_exec_sql_array()`.
* **¿Qué devuelve?**: `stdClass` conteniendo `->datos` array de resultados.

### `db_get_all($dt, $json, $db='con', $_db_type=null)`
* **¿Qué recibe?**: `$json` conteniendo `tname`, `tid`, `pid`, `col_name` (columna a mostrar).
* **¿Qué hace?**: Consulta registros y transforma la lista devuelta a una estructura estandarizada `[ ["id" => ..., "name" => ...], ... ]` convirtiendo codificación de caracteres.
* **¿Qué devuelve?**: `stdClass` con `->inf` array de objetos `id/name` y `->rows` total de elementos.

### `db_get_cant($dt, $json, $db='con', $_db_type=null)`
* **¿Qué recibe?**: `$json` conteniendo `tname`.
* **¿Qué hace?**: Ejecuta `SELECT COUNT(*) AS total FROM tabla WHERE status=1;`.
* **¿Qué devuelve?**: `stdClass` conteniendo `->total` *(int)* con el conteo de registros activos.

### `db_get_btns($total, $pag, $url=null, $bootstrap_v=4)`
* **¿Qué recibe?**:
  * `$total` *(int)*: Total de registros en la tabla.
  * `$pag` *(int)*: Página actual solicitada.
  * `$url` *(string|null)*: URL base de la vista.
  * `$bootstrap_v` *(int)*: Versión de Bootstrap (`4` u `5`).
* **¿Qué hace?**: Captura automáticamente todos los parámetros `$_GET` activos en la URL (filtros, búsquedas, etc.), calcula el número de páginas según la constante `ROWS` (por defecto 50) y genera el código HTML completo del componente de paginación `<ul class="pagination">`.
* **¿Qué devuelve?**: `stdClass` conteniendo `->inf` *(string HTML del paginador)*.

### `db_add($dt, $json, $db='con', $_db_type=null)`
* **¿Qué recibe?**:
  * `$dt` *(array)*: Matriz clave-valor con los datos a insertar.
  * `$json` *(object)*: Objeto con `tname`, `success` (mensaje éxito), `danger` (mensaje error).
* **¿Qué hace?**: Genera `INSERT` mediante `get_sql(..., tipo=1)` sin retorno y ejecuta `db_exec_sql()`. Captura errores de clave duplicada (`duplicate`, `unique`, `violation`) para enviar mensajes amigables.
* **¿Qué devuelve?**: `stdClass` con `->result` *(bool)*, `->inf`, `->mensaje`.

### `db_add_ret($dt, $json, $db='con', $_db_type=null)`
* **¿Qué recibe?**: Similar a `db_add`, requiere `tname` y `tid`.
* **¿Qué hace?**: Inserta el registro habilitando la clausula de retorno. Obtiene el ID insertado mediante el mecanismo propio de cada motor (`mysqli_insert_id`, `RETURNING`, `OUTPUT` o `SELECT MAX`).
* **¿Qué devuelve?**: `stdClass` conteniendo `->pid` *(int, ID generado)* y estado de la operación.

### `db_add_all($dt, $json, $db='con', $_db_type=null)`
* **¿Qué recibe?**: `$dt` array de filas a insertar; `$json` con `tname`, `t_camp` (campo obligatorio).
* **¿Qué hace?**: Abre una única conexión global y recorre `$dt` insertando cada fila sin reconectar por cada elemento. Optimiza el retorno para lotes grandes (>1000 registros).
* **¿Qué devuelve?**: `stdClass` con `->rows` (total procesados), `->rows_add` (total insertados) y `->res` array de resultados por fila.

### `db_edit($dt, $json, $db='con', $_db_type=null)`
* **¿Qué recibe?**: `$dt` array de campos a actualizar; `$json` con `tname`, `tid`, `pid`, `success`.
* **¿Qué hace?**: Genera `UPDATE` mediante `get_sql(..., tipo=2)` por ID numérico y ejecuta la actualización con mensajes dinámicos de acción ('modificó', 'eliminó', 'activó', etc.).
* **¿Qué devuelve?**: `stdClass` conteniendo `->result` y `->mensaje`.

### `db_edit_string($dt, $json, $db='con', $_db_type=null)`
* **¿Qué recibe?**: Mismos parámetros que `db_edit`, pero para IDs alfanuméricos (`string`).
* **¿Qué hace?**: Genera `UPDATE` mediante `get_sql(..., tipo=7)` utilizando `WHERE tid LIKE 'pid'` y ejecuta la actualización.
* **¿Qué devuelve?**: `stdClass` con resultado de la edición.

### `db_edit_all($dt, $json, $db='con', $_db_type=null)`
* **¿Qué recibe?**: `$dt` array de filas a editar; `$json` con `tname`, `tid`, `pid`, `t_camp`.
* **¿Qué hace?**: Ejecuta un bucle masivo de `UPDATE` sobre la misma conexión sin reconectar por cada fila.
* **¿Qué devuelve?**: `stdClass` con `->rows_edit` (total editados) y resumen.

---

## 1.6. Funciones de Utilidad y Formateo Integradas

### Fechas y Tiempos
* `cal_fecha($fecha)`: Compara una fecha con la fecha actual y devuelve una insignia HTML (`<span>`) clasificada según la urgencia (éxito >60 días, alerta 30-60 días, peligro <30 días, vencido en pasado).
* `estadoFecha($fecha)`: Retorna un badge Tailwind de estado de vencimiento según la proximidad de la fecha.
* `getMonthStartEnd($periodo, $periodo_fin=null)`: Recibe un periodo en formato `"YYYY-MM"` y devuelve un objeto con `->ini` (primer día del mes `"YYYY-MM-01"`) y `->fin` (último día del mes `"YYYY-MM-31"`).
* `form_dia($dia)`: Formatea un entero de día a 2 dígitos con cero a la izquierda (ej. `5` -> `'05'`).
* `sum_fecha($campo, $fecha, $time)`: Suma un intervalo de años, meses, días, horas o minutos a una fecha utilizando objetos `DateTime` e `DateInterval`.
* `form_fecha($fecha)`: Normaliza cualquier entrada de fecha (formatos `YYYYMMDD`, `DD/MM/YYYY`, `DD-MM-YYYY`) convirtiéndola garantizadamente al formato ISO `'YYYY-MM-DD'`.
* `form_fecha_null($fecha)`: Convierte fechas formato `DD/MM/YYYY` a `YYYY-MM-DD` manteniendo valores nulos limpios.
* `get_mes_txt($mes)`: Convierte el número entero de mes (1 al 12) a su representación textual en español (ej. `1` -> `'01-Enero'`).

### Formateo Numérico y Sanitización
* `form_float($numero, $cant=2)`: Normaliza strings con comas/puntos europeos o americanos y devuelve un float formateado a `$cant` decimales.
* `tofloat($num)`: Parsea una cadena limpiando caracteres no numéricos y manteniendo el separador decimal correcto.
* `getRandomCode($tipo=8, $largo=16)`: Genera un código/token aleatorio criptográficamente seguro (`random_int`) combinando números, mayúsculas, minúsculas y símbolos según el tipo seleccionado.
* `getRandomColor()`: Genera un código de color hexadecimal aleatorio (ej. `#1a2b3c`).
* `form_txt($input)`: Elimina etiquetas HTML (`strip_tags`), remueve caracteres especiales extraños preservando tildes UTF-8 y aplica escape básico.
* `reemp_car_esp($texto)`: Reemplaza letras con tildes, caracteres latinos (`ñ`, `ü`, `ç`) y entidades HTML por sus equivalentes ASCII sin acento.
* `form_txt_sap($cadena, $codic='html', $longitud=30)`: Prepara cadenas de texto para integración con SAP escapando caracteres especiales y recortando a la longitud permitida.
* `dividir_str($cadena, $longitud=30)` / `dividir_str_dos($cadena, $test=false, $longitud=30)`: Decodifica entidades HTML, remueve etiquetas, limpia espacios y divide la cadena en fragmentos multibyte de longitud fija formateando cada fragmento para exportaciones (ej. SAP).
* `custom_escape_string($value)`: Realiza escape de caracteres especiales sobre strings para prevenir inyecciones SQL en consultas manuales.
* `calc_codigo($pid, $largo=11)` / `calc_cod_txt($pid, $largo=11)`: Rellena un ID numérico con ceros a la izquierda mediante `str_pad` hasta alcanzar la longitud especificada (ej. `5` -> `'00000000005'`).
* `get_drop_duplic(array $array, array $keys=[], bool $idem=true)`: Elimina elementos duplicados de un array multidimensional según las llaves especificadas.

# 2. CORE Estructura de las Clases del Modelo (Metodología VAC)

Las clases de modelo (ubicadas en la carpeta `MORENOKU/`) representan las entidades del negocio (ej. `usuarios.php`, `paginas.php`, `tipos_usuarios.php`). 

Para garantizar compatibilidad **100% independiente del motor de base de datos** (PostgreSQL, MySQL, SQL Server, Oracle) y **compatible con versiones de PHP desde la 7.4 hasta la 8.5+**, cada clase debe cumplir de forma estricta las siguientes reglas de diseño.

---

## 2.1. Reglas Fundamentales de Arquitectura VAC en Clases

1. **Herencia Obligatoria**: Toda clase DEBE heredar de la clase `DataBase`:
   ```php
   class usuarios extends DataBase { ... }
   ```

2. **Prohibición Total de Funciones Nativas de BD**:
   **QUEDA PROHIBIDO** usar funciones nativas como `pg_query()`, `mysqli_query()`, `pg_fetch_array()`, `mysqli_fetch_assoc()`, `pg_close()`, etc., dentro de los métodos de las clases. Todas las ejecuciones de consultas se deben realizar a través de `$this->db_exec()`, `$this->db_exec_sql()`, `$this->db_exec_sql_array()`, o mediante el mapeo polimórfico de funciones de `DataBase`.

3. **Línea Estándar de Mapeo de Funciones**:
   Cualquier función que requiera procesar un recurso de consulta manualmente DEBE declarar en su primera línea el mapeo de variables de función:
   ```php
   $fc_query=$this->db_query;$fc_error=$this->db_error;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
   ```

4. **Cierre de Conexión Multi-Motor Seguro**:
   Al finalizar cualquier método que maneje conexiones directas, el cierre se debe realizar mediante la sentencia condicional de compatibilidad:
   ```php
   if(DB_TYPE=='mysqli_'){ $fc_close($this->connect(SCHU)); }else{ $fc_close(); }
   ```

5. **Interpolación Estándar de Cadenas en Consultas**:
   Las cadenas SQL deben usar la sintaxis de interpolación compleja de PHP (compatibilidad 7.4 - 8.5+):
   ```php
   $sql = "SELECT {$this->tid} FROM {$this->table0} WHERE status=1;";
   ```

---

## 2.2. Definición de Propiedades Privadas de la Clase

Tomando como referencia la clase modelo prototipo `usuarios.php`:

```php
class usuarios extends DataBase
{
    private $table  = 'public.usuarios';           // Tabla física principal
    private $table0 = 'public.view_users_all';      // Vista principal consolidada
    private $table2 = 'public.tipos_usuarios';      // Tabla o vista secundaria
    private $table3 = 'public.';                    // Reservado para Tabla o vista secundaria
    private $table4 = 'public.';                    // Reservado para Tabla o vista secundaria
    private $table5 = 'public.';                    // Reservado para Tabla o vista secundaria
    private $table6 = 'public.';                    // Reservado para Tabla o vista secundaria
    private $table7 = 'public.';                    // Reservado para Tabla o vista secundaria
    private $actio  = 'usuarios.php';               // Nombre del controlador en ACTIONVQ/
    private $detail = 'detalle/?p=';                // Ruta de la vista de detalle
    private $tid    = "id_usuario";                 // Llave primaria (PK)
    private $tid1   = "id_tipo";                    // Llave foránea (FK)
```

---

## 2.3. Estructura Estándar de Métodos de la Clase

### 1. `cantidad($rid)`
* **¿Qué recibe?**: `$rid` *(int)*: Rol o tipo de usuario en sesión.
* **¿Qué hace?**: Consulta el número de registros activos en la vista `$this->table0` aplicando filtros según el nivel de privilegios del rol `$rid`.
* **¿Qué devuelve?**: `int`: Cantidad de filas devueltas (devuelve `0` en caso de error o sin registros).
* **Código Estructura**:
```php
function cantidad($rid){
    $fc_query=$this->db_query;$fc_error=$this->db_error;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
    $inf = 0;
    $sql = "SELECT {$this->tid} FROM {$this->table0} WHERE ";
    switch ($rid) {
        case 1:  break;
        case 2:  $sql .= "id_tipo NOT IN (1) AND "; break;
        case 4:  $sql .= "id_tipo NOT IN (1, 2) AND "; break;
        default: $sql .= "id_created=".$_SESSION['user_id']." AND "; break;
    }
    $sql .= " status = 1 ;";
    $res = $this->db_exec($sql, false);
    $inf = $res->cant;
    if(DB_TYPE=='mysqli_'){ $fc_close($this->connect(SCHU)); }else{ $fc_close(); }
    return $inf;
}
```

---

### 2. `dtl($campo)`
* **¿Qué recibe?**: `$campo` *(string)*: Nombre de la columna de la tabla/vista a autocompletar.
* **¿Qué hace?**: Genera elementos de opciones `<option value="valor" />` para datalists de autocompletado en formularios.
* **¿Qué devuelve?**: `string`: Cadena de texto con los elementos HTML `<option>`.
* **Código Estructura**:
```php
function dtl($campo){
    $fc_query=$this->db_query;$fc_error=$this->db_error;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
    $inf=null;
    $sql="SELECT {$campo} FROM {$this->table0} WHERE status=1 ORDER BY {$campo} ASC ;";
    $res = $this->db_exec($sql);
    if ($res->result==true && $res->cant > 0) {
        while ($row = $fc_assoc($res->res)){
            $inf.='<option value="'.$row[$campo].'" />';
        }
        $fc_fre_r($res->res);
    }else{
        $inf.='<option value="No se ejecutó la consulta. Error: '.$res->error.'">';
    }
    if(DB_TYPE=='mysqli_'){ $fc_close($this->connect(SCHU)); }else{ $fc_close(); }
    return $inf;
}
```

---

### 3. `cbo($rid)` / `cboCli_all(...)` (OPCIONAL) / `cboVen()` (OPCIONAL)
* **¿Qué recibe?**: `$rid` *(int)* y/o filtros del tipo de registro.
* **¿Qué hace?**: Genera las opciones HTML para campos desplegables `<select>`, codificando las llaves primarias en Base64 (`base64_encode($row[$this->tid])`) para mayor seguridad de la interfaz.
* **¿Qué devuelve?**: `string`: Opciones `<option value="BASE64">nombre_tipo - nombre_comp</option>`.
* **Código Estructura**:
```php
function cbo($rid){
    $fc_query=$this->db_query;$fc_error=$this->db_error;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
    $inf="";
    $inf.='<option value="'.base64_encode(0).'">Seleccione al empleado:</option>';
    $sql = "SELECT {$this->tid}, nombre_tipo, nombre_comp, id_created FROM {$this->table0} WHERE ";
    switch ($rid) {
        case 1:  break;
        case 2:  $sql .= "id_tipo NOT IN (1) AND "; break;
        default: $sql .= "id_tipo NOT IN (1) OR id_created=".$_SESSION['user_id']." AND "; break;
    }
    $sql .= " status=1 AND id_usuario NOT IN (".$_SESSION['user_id'].") ;";
    $res = $this->db_exec($sql);
    if ($res->result==true && $res->cant > 0) {
        while ($row = $fc_assoc($res->res)){
            $inf .= '<option value="'.base64_encode($row[$this->tid]).'">'.$row['nombre_tipo'].' - '.$row['nombre_comp'].'</option>';
        }
        $fc_fre_r($res->res);
    }else{
        $inf .= '<option value="'.base64_encode(0).'">No se obtuvo la información. Error: '.$res->error.'</option>';
    }
    if(DB_TYPE=='mysqli_'){ $fc_close($this->connect(SCHU)); }else{ $fc_close(); }
    return $inf;
}
```

---

### 4. `li_emp()` / `cbo_li_emp()` (OPCIONAL)
* **¿Qué recibe?**: Ninguno o parámetros de filtro de usuario.
* **¿Qué hace?**: Construye listas desordenadas HTML (`<li><button class="dropdown-item">...</button></li>`) para menús desplegables de selección interactiva de empleados/usuarios.
* **¿Qué devuelve?**: `string`: Lista en formato HTML de botones item.

---

### 5. `dest($rid)` (OPCIONAL)
* **¿Qué recibe?**: `$rid` *(int)*: ID del tipo de usuario.
* **¿Qué hace?**: Consulta los usuarios pertenecientes al tipo `$rid` y genera una lista de badges con nombres y correos electrónicos.
* **¿Qué devuelve?**: `stdClass` objeto respuesta con:
  * `->inf` *(string HTML)*: HTML renderizado de los destinatarios.
  * `->cant` *(int)*: Cantidad de destinatarios encontrados.

---

### 6. `listar($rid, $uid, $url, $act=1)`
* **¿Qué recibe?**:
  * `$rid` *(int)*: Rol del usuario en sesión.
  * `$uid` *(int)*: ID del usuario creador/logueado.
  * `$url` *(string)*: URL actual en Base64 para retornar tras ejecutar acciones.
  * `$act` *(int)*: Estado a listar (`1` para activos, `0` o `2` para inactivos/bloqueados). No en todas las clases se ocupa (OPCIONAL)
* **¿Qué hace?**: Genera la estructura completa de la tabla de datos Datatable (cabecera `<thead>` y cuerpo `<tbody>`), con botones de edición (`detalle/?p=BASE64`), eliminación con modal (`eliminar('BASE64')`) y cambio rápido de estado con iconos en badges interactivos.
* **¿Qué devuelve?**: `stdClass` objeto respuesta conteniendo:
  * `->inf` *(string)*: Código HTML del `<thead>` y `<tbody>` listo para imprimir dentro de `<table>`.
  * `->sql` *(string)*: Sentencia SQL ejecutada (para depuración).
* **Código Estructura**:
```php
function listar($rid, $uid, $url, $act=1){
    $fc_query=$this->db_query;$fc_error=$this->db_error;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
    $data = new stdClass();
    $inf = null; $n = 1; $cant = 8; $data->error = null;
    $inf.='<thead><tr><th><i class="fas fa-users-cog"></i></th><th><i class="fas fa-list-ol"></i></th><th>Nombre</th><th>Estado</th></tr></thead>';
    $inf.='<tbody>';
    $sql = "SELECT * FROM {$this->table0} WHERE status={$act} ORDER BY {$this->tid} DESC ;";
    $res = $this->db_exec($sql);
    if ($res->result==true && $res->cant > 0) {
        while ($row = $fc_assoc($res->res)) {
            $pid = base64_encode($row[$this->tid]);
            $inf.='<tr><td><a href="'.$this->detail.$pid.'" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i></a></td><td>'.$n.'</td><td>'.$row['nombre_comp'].'</td><td>'.$row['status'].'</td></tr>';
            $n++;
        }
        $fc_fre_r($res->res);
    }
    $inf.='</tbody>';
    $data->inf = $inf;
    $data->sql = $sql;
    if(DB_TYPE=='mysqli_'){ $fc_close($this->connect(SCHU)); }else{ $fc_close(); }
    return $data;
}
```

---

### 7. `acceder($uid)` (OPCIONAL)
* **¿Qué hace?**: Consulta todos los datos del usuario en la vista `public.view_users_all` mediante `$this->db_exec($sql)` y mapea todos los atributos como propiedades dinámicas del objeto devuelto.
* **¿Qué devuelve?**: `stdClass` objeto conteniendo:
  * `->result` *(int)*: `1` si el usuario existe, `2` si no se encontró.
  * `->mensaje` *(string)*: Mensaje explicativo.
  * `->nomb` *(string)*: Nombre completo del usuario (`nombre_comp`).
  * `->[todas_las_columnas]`: Propiedades devueltas por la vista `view_users_all`.

---

### 8. `validar_pass($pass, $uid)` (OPCIONAL)
* **¿Qué recibe?**:
  * `$pass` *(string)*: Contraseña ingresada por el usuario en texto plano.
  * `$uid` *(int)*: ID del usuario a validar.
* **¿Qué hace?**: Consulta el campo `contrasenia_u` en la BD usando `$this->db_exec_sql($sql)` y verifica el hash utilizando la función nativa segura `password_verify($pass, $hash)`.
* **¿Qué devuelve?**: `bool`: `true` si la contraseña en texto plano coincide con el hash encriptado de la BD, `false` en caso contrario.
* **Código Estructura**:
```php
function validar_pass($pass, $uid){
    $contrasenia_u = null; $resp = false;
    $sql = "SELECT contrasenia_u FROM {$this->table} WHERE {$this->tid}={$uid} ;";
    $res = $this->db_exec_sql($sql);
    if ($res->result) {
        if (password_verify($pass, $res->contrasenia_u)) {
            $resp = true;
        }
    }
    if(DB_TYPE=='mysqli_'){ $fc_close($this->connect(SCHU)); }else{ $fc_close(); }
    return $resp;
}
```

# 3. CORE Estructura de las Acciones del Controlador (Metodología VAC)

Los archivos controladores de acción (ubicados en la carpeta `ACTIONVQ/`) actúan como la capa intermedia entre las **Vistas** de la interfaz y las **Clases** del Modelo. Reciben las solicitudes HTTP (POST/GET/REQUEST), procesan los datos con la ayuda de la capa Modelo y responden redirigiendo las vistas o entregando datos en formato JSON para peticiones AJAX.

Tomando como base el controlador prototipo `sistema/ACTIONVQ/usuarios.php`, la estructura técnica estandarizada debe ser la siguiente:

---

## 3.1. Estructura de Encabezado e Inicialización Global

Todo controlador de acción debe comenzar definiendo el control de sesión, la ruta relativa a la raíz `$ru0`, el diccionario mapeador de clases `$cls`, la instanciación de objetos globales `$dt`, `$data`, `$json` y el objeto de configuración de la tabla `$_tbl`:

```php
<?php
    //--------------------------------------
    if(isset($_SESSION)){}else{ session_start(); }
    //--------------------------------------
    $ru0='../';
    //--------------------------------------
    $cls = array(
        "dbs" => "DataBase",           // 1. CONEXIÓN A LA BASE DE DATOS
        "cl1" => "usuarios",           // 2. CLASE PRINCIPAL DEL MODELO
        "cl2" => "tipos_usuarios",     // 3. CLASE O VISTA SECUNDARIA
        "cl3" => "view_users_all",     // 4. VISTA DE UNION O CONSULTA AUXILIAR
        "cl4" => "Sesiones",           // 5. CLASE AUXILIAR (ej. gestión de sesiones)
        "cl5" => "apis_peru",          // 6. CLASE API (ej. consultas externas)
        "cor" => "correos",            // 7. CLASE AUXILIAR (ej. correos)
    );
    //--------------------------------------
    $dt   = new stdClass();
    $data = new stdClass();
    $json = new stdClass();
    //--------------------------------------
    $_tbl = new stdClass();
    $_tbl->tname   = 'public.'.$cls['cl1'];
    $_tbl->tid     = 'id_usuario';
    $_tbl->pid     = 0;
    $_tbl->t_camp  = '';
    $_tbl->success = 'add';
    $_tbl->danger  = 'no'.$_tbl->success;
    //--------------------------------------
```

---

## 3.2. Funciones de Lectura Invócales desde las Vistas

Son funciones globales del controlador que se incluyen directamente desde las vistas (`home/*.php`) para alimentar de datos los componentes de la interfaz.

### 1. `index($rut, $rid, $uid, $url, $tipo='user', $act=1)`
* **¿Qué recibe?**:
  * `$rut` *(string)*: Ruta relativa hacia la carpeta raíz (ej. `'../../'`).
  * `$rid` *(int)*: ID del rol de usuario en sesión.
  * `$uid` *(int)*: ID del usuario logueado.
  * `$url` *(string)*: URL codificada en Base64 para retornos tras acciones.
  * `$tipo` *(string, opcional)*: Tipo de entidad a listar (`'user'`, `'clie'`).
  * `$act` *(int, opcional)*: Estado a listar (`1` activos, `0` inactivos).
* **¿Qué hace?**: Carga los requerimientos de clases (`DataBase`, `usuarios`, `tipos_usuarios`), ejecuta el método `listar()` de la clase modelo, invoca la generación de combos `cbo()` y genera contraseñas aleatorias temporales con `getRandomCode()`.
* **¿Qué devuelve?**: `stdClass` objeto respuesta con:
  * `->inf` *(stdClass)*: Matriz de tabla HTML generada por `listar()`.
  * `->cboTipos` *(string)*: Opciones HTML `<option>` para desplegables.
  * `->pass_gen` *(string)*: Token o clave aleatoria autogenerada.
* **Código Estructura**:
```php
function index($rut, $rid, $uid, $url, $tipo='user', $act=1){
    global $cls, $data;
    //--------------------------------------
    require($rut.DIRMOR.$cls['dbs'].'.php');
    require_once($rut.DIRMOR.$cls['cl1'].'.php');
    require_once($rut.DIRMOR.$cls['cl2'].'.php');
    //--------------------------------------
    $_dbs = new $cls['dbs']();
    $_cl1 = new $cls['cl1']();
    $_cl2 = new $cls['cl2']();
    //--------------------------------------
    if ($tipo == 'user') {
        $data->inf = $_cl1->listar($rid, $uid, $url, $act);
    } else {
        $data->inf = $_cl1->listar_clie($rid, $uid, $url, $act);
    }
    //--------------------------------------
    $data->cboTipos = $_cl2->cbo($rid, $uid, $tipo);
    $data->pass_gen = $_dbs->getRandomCode();
    //--------------------------------------
    return $data;
}
```

---

### 2. `detalle($rut, $rid, $uid, $pid, $tipo="user")`
* **¿Qué recibe?**:
  * `$rut` *(string)*: Ruta relativa hacia la raíz.
  * `$rid` *(int)*: Rol del usuario.
  * `$uid` *(int)*: ID del usuario.
  * `$pid` *(int)*: ID numérico del registro a consultar.
  * `$tipo` *(string)*: Tipo de vista. (opcional no todas las acciones la ocupan)
* **¿Qué hace?**: Prepara el objeto `$json` con el nombre de la vista/tabla y la llave primaria, ejecuta `$_dbs->db_get_id(null, $json)` para traer la fila de datos completa y carga los combos auxiliares.
* **¿Qué devuelve?**: `stdClass` objeto respuesta con:
  * `->call` *(stdClass)*: Atributos y campos del registro consultado en propiedades directas.
  * `->cboTipos` *(string)*: Opciones de combos desplegables para el formulario de edición. (No todas las acciones invocan cbos)
* **Código Estructura**:
```php
function detalle($rut, $rid, $uid, $pid, $tipo="user"){
    global $cls, $data, $json;
    //--------------------------------------
    require($rut.DIRMOR.$cls['dbs'].'.php');
    require_once($rut.DIRMOR.$cls['cl1'].'.php');
    require_once($rut.DIRMOR.$cls['cl2'].'.php');
    //--------------------------------------
    $_dbs = new $cls['dbs']();
    $_cl1 = new $cls['cl1']();
    $_cl2 = new $cls['cl2']();
    //--------------------------------------
    $json->tname = 'public.'.$cls['cl3'];
    $json->tid   = 'id_usuario';
    $json->pid   = $pid;
    //--------------------------------------
    $data->call     = $_dbs->db_get_id(null, $json);
    $data->cboTipos = $_cl2->cbo($rid, $uid, $tipo);
    //--------------------------------------
    return $data;
}
```

---

## 3.3. Manejadores de Eventos HTTP (POST y REQUEST)

Se ejecutan cuando un formulario HTML envía datos al servidor mediante POST o cuando una solicitud URL envía variables por REQUEST.

### 1. Evento de Inserción (`if (isset($_POST['nuevo']))`)
* **Condicionales de Seguridad**:
  1. Verifica que exista sesión activa `if (isset($_SESSION['user_id']))`.
  2. Procesa la subida de archivos de imagen `is_uploaded_file($_FILES["foto_u"]["tmp_name"])` sanitizando el nombre con la máscara `user-YYYYMMDDHHIISS-nombre.jpg`.
* **Procesamiento**:
  - Prepara el array asociativo `$add` asignando encriptación Hash con `password_hash($pass, PASSWORD_BCRYPT)`.
  - Ejecuta la inserción vía `$_dbs->db_add($add, $_tbl)`.
  - Si la inserción es exitosa, mueve la imagen a su directorio destino con `move_uploaded_file()`.
  - Setea las variables de sesión de mensaje flash: `$_SESSION['SMStrue4']` (éxito) o `$_SESSION['SMSfalse4']` (error).
  - Limpia el buffer `$_POST = null;` y redirige inmediatamente mediante `header("Location: ".$url); exit();`.

---

### 2. Evento de Edición (`if (isset($_POST['editar']))`)
* **Condicionales de Seguridad**:
  1. Decodifica la llave primaria enviada en Base64: `$_tbl->pid = base64_decode($_POST['pid']);`.
  2. Valida la nueva contraseña: si tiene una longitud mayor a 5 caracteres genera un nuevo hash `password_hash()`; de lo contrario, conserva el hash anterior enviado en Base64.
  3. Procesa reemplazo de archivos de imagen manteniendo la foto actual si no se adjunta un nuevo archivo.
* **Procesamiento**:
  - Prepara el array asociativo `$arr` con la marca de tiempo `updated_at` y el usuario editor `id_updated`.
  - Ejecuta la actualización vía `$_dbs->db_edit($arr, $_tbl)`.
  - Si el usuario editado es el mismo logueado en sesión (`$_tbl->pid == $_SESSION['user_id']`), actualiza la foto de perfil en sesión `$_SESSION['user_foto']`.
  - Asigna mensajes de sesión flash y redirige a la URL de origen.

---

### 3. Evento de Cambio de Identidad / Su-User (`if (isset($_REQUEST['accd']))`)
* **¿Qué hace?**: Permite a los usuarios con rol de administrador (roles `1`, `2` o `3`) asumir temporalmente la sesión de cualquier otro usuario de la plataforma.
* **Procesamiento**:
  - Decodifica el ID del usuario objetivo: `$uid = base64_decode(base64_decode($_REQUEST['uid']));`.
  - Ejecuta el método `acceder()` de la clase modelo.
  - Si el usuario existe, invoca `$_s->accedUID()` de la clase `Sesiones` para reasignar todas las variables globales de sesión y redirige a `MENU`.

---

### 4. Evento de Retorno de Identidad (`if (isset($_REQUEST['rev']))`)
* **¿Qué hace?**: Permite al administrador volver a su cuenta original tras haber asumido la identidad de otro usuario.
* **Procesamiento**:
  - Verifica la variable de sesión previa `$_SESSION['ant_tipo_id']`.
  - Invoca `$_s->regresar($uid)` de la clase `Sesiones` y redirige a `MENU`.

---

### 5. Eventos de Petición AJAX (`if (isset($_POST['consulta']))` / `if (isset($_POST['buscar']))`)
* **¿Qué hace?**: Responde a llamadas asíncronas de JavaScript entregando datos filtrados en formato JSON.
* **Procesamiento**:
  - Valida la sesión del usuario.
  - Ejecuta la consulta requerida a través de la clase API o con `$_dbs->db_get_string()`.
  - Emite los encabezados HTTP JSON y retorna la respuesta serializada:
```php
header("Content-Type: application/json; charset: UTF-8;");
echo json_encode($dt);
```

---

## 3.4. Inclusión Final Requerida

Todo controlador de acción DEBE finalizar con la inclusión del archivo compartido de funciones de estado y eliminación:

```php
    //--------------------------------------
    require_once('funciones.php'); // Llama a los handlers universales de activar, desactivar y eliminar (drop)
    //--------------------------------------
```

# 4. CORE Estructura de las Vistas-Listado (Metodología VAC)

Las Vistas de Listado (ubicadas en `home/{modulo}/{tabla}/index.php`) representan la pantalla principal de un módulo donde se presentan las tablas de datos, filtros y accesos a formularios modales de creación.

Tomando como base la vista prototipo `sistema/home/ACSG3QLY/usuarios/index.php`, la estructura estandarizada debe cumplir de forma estricta las siguientes reglas de diseño y arquitectura.

---

## 4.1. Reglas Fundamentales de Arquitectura en Vistas-Listado

1. **PROHIBICIÓN TOTAL DE BUCLES EN LA VISTA**:
   **QUEDA TOTALMENTE PROHIBIDO** usar bucles (`while`, `foreach`, `for`) dentro del archivo HTML de la vista. Todo el recorrido de registros y la construcción de filas `<tr>` y columnas `<td>` se procesa previamente dentro del método `listar()` de la Clase Modelo. La vista únicamente imprime el resultado directo pre-generado mediante:
   ```html
   <table id="example1" class="dataTable table table-bordered table-hover">
       <?= $inf->inf; $inf->inf=null; ?>
   </table>
   ```

2. **Compatibilidad Estricta (PHP 7.4 - 8.5+)**:
   - Inicialización limpia de variables antes de llamar al controlador (`$data=null; $inf=null;`).
   - Verificación defensiva de la existencia de propiedades devueltas para evitar cierres o crashes `TypeError` en PHP 8.2+:
     ```php
     if (isset($data->inf)) {
         $inf = $data->inf;
     } else {
         header("Location: ".$rut2);
         exit();
     }
     ```

3. **Inclusión Modular Estándar**:
   - Hojas de estilos Datatables: `include_once($rut.CONF.'1stylesDAT.php');`.
   - Mensajes flash Toastr/SweetAlert: `include_once($rut.CONF.'0mens.php');` y `include_once($rut.CONF.'5toastr.php');`.
   - Librerías JavaScript de Datatables: `include_once($rut.CONF.'3javaDAT.php');`.

---

## 4.2. Estructura Completa del Código Prototipo

```php
<?php
    //--------------------------------------
    if(isset($_SESSION)){}else{ session_start(); }
    //--------------------------------------
    $rut='../../../';
    $rut2='../';
    //--------------------------------------
    require($rut.'config/0code.php');
    //--------------------------------------
    $pagina='Usuarios';
    $singlr='Usuario';
    $action='usuarios.php';
    //--------------------------------------
    $raiz=true;$subraiz=true;$detail=false;$nuevo=false;$imp=false;$exp=false;
    //--------------------------------------
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title><?= $pagina; ?> | <?= $_SESSION['tipo_name'].TIT; ?></title>
    <?php
        //--------------------------------------
        include_once($rut.CONF.'1stylesDAT.php');
        //--------------------------------------
        if (isset($_REQUEST['new'])) {
            ?>
                <script type="text/javascript">
                    window.onload = function(){
                        $('#nuevo').modal('show');
                    }
                </script>
            <?php
        }
        //--------------------------------------
        $data=null;$inf=null;
        //--------------------------------------
        require_once($rut.DIRACT.$action);
        $data = index($rut,$rid,$uid,$location,'user',1);
        //--------------------------------------
        if (isset($data->inf)) {
            $inf = $data->inf;
        }else{
            header("Location: ".$rut2);
            exit();
        }
        //--------------------------------------
        require_once($rut.CONF.'0mens.php');
    ?>
</head>
<body class="<?= $_body_w; ?>">
<div class="wrapper">

    <?php include_once($rut2.'1nav.php'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <?php include_once($rut.VIEWS.'head.php'); ?>

        <section class="content pb-4">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4 text-center">
                        <h3>Lista de <?= $pagina; ?></h3>
                    </div>
                    <div class="col-sm-4 text-right">
                        <button type="button" class="text-right btn btn-icon btn-primary" data-toggle="modal" data-target="#nuevo">
                            <i class="fa fa-plus"></i> Nuevo <?= $singlr; ?>
                        </button>
                    </div>
                </div>
                
                <hr>

                <div class="row pb-4">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div id="div1" class="col-sm-12">
                            <table id="example1" class="dataTable table table-bordered table-hover">
                                <?= $inf->inf; $inf->inf=null; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->

    <?php include_once($rut.CONF.'4footer2.php'); ?>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<?php include_once($rut.CONF.'3javaDAT.php'); ?>
<?php include_once($rut.CONF.'5toastr.php'); ?>
<?php include_once($rut.VIEWS.'callchat.php'); ?>

<!-- Large modal -->
<div id="nuevo" class="modal fade bd-example-modal-lg" tabindex="-1" tipoe="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content" style="background-color: black;color: #fff;">
            <form method="POST" enctype="multipart/form-data" action="<?= ACTI.$action; ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nuevo <?= $singlr; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Tipo de Usuario:</label>
                                <select class="form-control select2" name="id_tipo" required="required">
                                    <?= $data->cboTipos; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">RUC O DNI:</label>
                                <input type="text" class="form-control" maxlength="11" name="id_int" placeholder="RUC o DNI" required="required">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Nombres Completos:</label>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="nombres_u" placeholder="NOMBRES" required="required">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="apellidos_u" placeholder="APELLIDOS" required="required">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="recipient-name" class="col-form-label">Accesos al Sistema:</label>
                                <div class="row">
                                    <div class="col-sm-6 pb-2">
                                        <input type="email" class="form-control" name="correo_u" placeholder="Correo electrónico" required="required">
                                    </div>
                                    <div class="col-sm-6 pb-2">
                                        <input type="text" class="form-control" name="usuario_u" placeholder="Nombre de usuario" required="required">
                                    </div>
                                    <div class="col-sm-12">
                                        <input type="password" class="form-control" id="pass1" name="contrasenia_u" placeholder="Contraseña" value="<?= $data->pass_gen; ?>" required="required">
                                        <label><input type="checkbox" title="Ver Contraseña" style="width: 15px; height: 15px;" onchange="document.getElementById('pass1').type = this.checked ? 'text' : 'password';"> Ver Contraseñas. </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="uid" value="<?= base64_encode($uid); ?>">
                    <input type="hidden" name="url" value="<?= base64_encode($location); ?>">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
                    <?php if ($is_visor): ?>
                        <button type="submit" name="nuevo" class="btn btn-primary">Agregar <?= $singlr; ?> <i class="fas fa-plus"></i></button>
                    <?php endif ?>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
<?php
    if (isset($_SESSION['stat'])) { unset($_SESSION['stat']); }
    if (isset($_SESSION['error'])) { unset($_SESSION['error']); }
    if (isset($_SESSION['Mysqli_Error'])) { unset($_SESSION['Mysqli_Error']); }
```

---

## 4.3. Desglose de Componentes de la Vista-Listado

1. **Variables de Configuración al Encabezado**:
   - `$rut`: Ruta hacia la raíz de la aplicación (ej. `'../../../'`).
   - `$pagina`: Nombre plural del módulo para los títulos.
   - `$singlr`: Nombre singular del recurso para botones y modales.
   - `$action`: Nombre del script controlador en `ACTIONVQ/`.
   - `$raiz`, `$subraiz`: Controladores booleanos de migas de pan (*breadcrumbs*).

2. **Invocación del Controlador y Carga de Datos**:
   - Se incluye el controlador `$action`.
   - Se invoca la función global `index($rut, $rid, $uid, $location, 'user', 1)`.
     - `$rut`: Ruta hacia la raíz de la aplicación.
     - `$rid`: RID del módulo.
     - `$uid`: ID del usuario logueado.
     - `$location`: URL de retorno.
     - `'user'`: Nombre de la tabla. (OPCIONAL no todas las vistas, aciones y clases, lo ocupan)
     - `1`: Número de campos de la tabla. (OPCIONAL no todas las vistas, aciones y clases, lo ocupan)
   - Se extraen la tabla pre-generada `$data->inf` y los combos desplegables `$data->cboTipos`.

3. **Renderizado de Tabla Datatable**:
   - La tabla `<table id="example1" class="dataTable ...">` imprime directamente el HTML pre-generado de `<thead>` y `<tbody>` producido en el backend (`<?= $inf->inf; $inf->inf=null; ?>`), vaciando la propiedad inmediatamente para no saturar memoria.

4. **Modal Formulario de Creación Rápida (`#nuevo`)**:
   - Formulario que transmite datos por POST a `action="<?= ACTI.$action; ?>"`.
   - Todos los input tienen name="[nombre_campo]" que es igual al nombre de la tabla. asi que se deben modificar de acuerdo a la tabla que se va a utilizar (revisar y buscar tabla en /database.sql).
   - Incluye los inputs ocultos obligatorios `$rid`, `$uid` (RID y ID de usuario logueado en Base64) y `url=$location` (URL de retorno en Base64).
   - Botón de confirmación condicionado al privilegio de escritura: `<?php if ($is_visor): ?> <button type="submit" name="nuevo">... <?php endif ?>`.

5. **Limpieza Final de Sesión**:
   - Al pie del documento se desasignan las variables temporales de estado y errores (`unset($_SESSION['stat'])`) para que los mensajes Toastr no se repitan al recargar la pantalla.

---

# 5. CORE Estructura de las Vistas-Detalle (Metodología VAC)

Las Vistas de Detalle (ubicadas en `home/{modulo}/{tabla}/detalle/index.php`) representan el formulario de edición y visualización individual de un registro de la base de datos.

Tomando como base la vista prototipo `sistema/home/ACSG3QLY/usuarios/detalle/index.php`, la estructura estandarizada debe cumplir de forma estricta las siguientes reglas. Esta vista tiene su componente reutilizable `sistema/views/detail_user.php` (No todas las vistas lo trabajan asi, solo aquellas que van a ser llamadas desde varios modulos como usuarios)

---

## 5.1. Reglas Fundamentales de Arquitectura en Vistas-Detalle

1. **Separación Modular (Maqueta y Formulario)**:
   - La maqueta principal de detalle se ubica en `home/{modulo}/{tabla}/detalle/index.php`.
   - El formulario HTML de edición se incluye de forma modular mediante (solo para módulos que serán llamados desde varios otros módulos, de lo contrario se define en la misma vista):
     ```php
     <?php include_once($rut.VIEWS.'detail_user.php'); ?>
     ```

2. **PROHIBICIÓN TOTAL DE BUCLES EN LA VISTA**:
   No existen bucles en la vista de detalle. Los atributos del registro se acceden como propiedades directas del objeto `$call` retornado por la base de datos (ej. `$call->nombres_u`, `$call->status`).

3. **Verificación Defensiva de Datos en Backend**:
   - En la sección `<head>` de `detalle/index.php` se ejecuta la función `detalle()` del controlador.
   - Se aplica validación estricta antes de renderizar el formulario:
     ```php
    //--------------------------------------
    require_once($rut.DIRACT.$action);
    $data = detalle($rut,$rid,$uid,$pid,'user');
    //--------------------------------------
     if (isset($data->call)) {
         $call = $data->call;
     } else {
         header("Location: ".$rut2);
         exit();
     }
     ```
   - Los parámetros de la función `detalle()` son: 
        `$rut`,
        `$rid`,
        `$uid`,
        `$pid`,
        `$vista` (si el módulo será llamado desde varios otros módulos, de lo contrario no va)

4. **Autoselección de Desplegables con JavaScript (Select2 / jQuery)**:
   - Al pie de la maqueta `detalle/index.php`, se incluye un bloque `<script>` que establece los valores seleccionados de los campos `<select>` utilizando la información de `$call`:
     ```javascript
     $(document).ready(function(){
         $('#id_tipo').val('<?= base64_encode($call->id_tipo); ?>').trigger('change.select2');
     });
     ```

---

## 5.2. Código Prototipo 1: Maqueta Principal (`detalle/index.php`)

```php
<?php
    //--------------------------------------
    if(isset($_SESSION)){}else{ session_start(); }
    //--------------------------------------
    $rut='../../../../';
    $rut2='../../';
    //--------------------------------------
    require($rut.'config/0code.php');
    //--------------------------------------
    $padre='Usuarios';
    $pagina='Detalle del Usuario';
    $singlr='Usuario';
    $action='usuarios.php';
    //--------------------------------------
    $raiz=true;$subraiz=true;$detail=true;$nuevo=false;$imp=false;$exp=false;
    //--------------------------------------
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title><?= $pagina; ?> | <?= $_SESSION['tipo_name'].TIT; ?></title>
    <?php
        //--------------------------------------
        include_once($rut.CONF.'1stylesDAT.php');
        //--------------------------------------
        $data=null;$inf=null;
        //--------------------------------------
        require_once($rut.DIRACT.$action);
        $data = detalle($rut,$rid,$uid,$pid,'user');
        //--------------------------------------
        if (isset($data->call)) {
            $call = $data->call;
        }else{
            header("Location: ".$rut2);
            exit();
        }
        //--------------------------------------
        require_once($rut.CONF.'0mens.php');
        //--------------------------------------
    ?>
</head>
<body class="<?= $_body_w; ?>">
<div class="wrapper">

    <?php include_once($rut2.'1nav.php'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <?php include_once($rut.VIEWS.'head.php'); ?>

        <section class="content pb-4">
            <div class="container-fluid">
                <?php include_once($rut.VIEWS.'detail_user.php'); ?>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->

    <?php include_once($rut.CONF.'4footer2.php'); ?>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<?php include_once($rut.CONF.'3javaDAT.php'); ?>
<?php include_once($rut.CONF.'5toastr.php'); ?>
<?php include_once($rut.VIEWS.'callchat.php'); ?>
<script type="text/javascript">
    $(document).ready(function(){
        $('#id_tipo').val('<?= base64_encode($call->id_tipo); ?>').trigger('change.select2');
        $('#tipo_pers').val('<?= $call->tipo_pers; ?>');
        $('#tipo_contrib').val('<?= $call->tipo_contrib; ?>');
    });
</script>
</body>
</html>
<?php
    if (isset($_SESSION['stat'])) { unset($_SESSION['stat']); }
    if (isset($_SESSION['error'])) { unset($_SESSION['error']); }
    if (isset($_SESSION['Mysqli_Error'])) { unset($_SESSION['Mysqli_Error']); }
```

---

## 5.3. Código Prototipo 2: Formulario Componente (`views/detail_user.php`) (Solo para módulos que serán llamados desde varios otros módulos, de lo contrario se define en la misma vista)

```html
<div class="row">
    <div class="col-sm-4">
        <a href="../" class="btn btn-danger btn-block"><i class="fas fa-arrow-left"></i> Regresar a <?= $singlr; ?></a>
    </div>
    <div class="col-sm-4 text-center">
        <h3><?= $pagina; ?>: <b><?= $call->nombres_u; ?></b></h3>
    </div>
    <div class="col-sm-4 text-right">
    </div>                  
</div>

<hr>

<div class="row pb-4">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <form class="row" method="POST" action="<?= ACTI.$action; ?>" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Tipo de Usuario: <span class="text-danger">requerido*</span></label>
                    <select class="form-control select2" id="id_tipo" name="id_tipo" required="required">
                        <?= $data->cboTipos; ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">RUC O DNI: <span class="text-danger">requerido*</span></label>
                    <input type="text" class="form-control" maxlength="11" name="id_int" placeholder="RUC o DNI" value="<?= $call->id_int; ?>" required="required">
                </div>
            </div>
            <div class="col-sm-9">
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Razón Social: <span class="text-danger">requerido*</span></label>
                    <input type="text" class="form-control" name="razon_soc" placeholder="Nombres" value="<?= $call->razon_soc; ?>" required="required">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Nombres: <span class="text-danger">requerido*</span></label>
                    <input type="text" class="form-control" name="nombres_u" placeholder="Nombres" value="<?= $call->nombres_u; ?>" required="required">
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Apellidos: <span class="text-danger">requerido*</span></label>
                    <input type="text" class="form-control" name="apellidos_u" placeholder="Apellidos" value="<?= $call->apellidos_u; ?>" required="required">
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Accesos al Sistema: <span class="text-danger">requerido*</span></label>
                    <input type="email" class="form-control" name="correo_u" placeholder="Correo electrónico" value="<?= $call->correo_u; ?>" required="required">
                    <label for="recipient-name" class="col-form-label">Usuario: <span class="text-danger">requerido*</span></label>
                    <input type="text" class="form-control" name="usuario_u" placeholder="Nombre de usuario" value="<?= $call->usuario_u; ?>" required="required">
                    <label for="recipient-name" class="col-form-label">Contraseña: <span class="text-danger">(Solo llene este campo si desea editar la contraseña actual del Usuario)</span></label>
                    <input type="text" class="form-control" id="editpass1" name="new_pass" placeholder="Contraseña">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Foto Nueva del Usuario:</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="customFile" name="foto_u">
                        <label class="custom-file-label" for="customFile">Seleccione foto</label>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="recipient-name" class="col-form-label">Foto Actual del Usuario:</label>
                    <br>
                    <?php if (strlen($call->foto_u) > 5): ?>
                        <img src="<?= IMG.'avatar/'.$call->foto_u; ?>" class="img-thumbnail" style="max-height: 150px;">
                    <?php endif ?>
                    <input type="hidden" name="foto_u_actual" value="<?= $call->foto_u; ?>">
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="status" <?= (($call->status == 1) ? 'checked="checked"' : NULL); ?> >
                        <label class="form-check-label">¿Usuario Activo?</label>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="../" class="btn btn-info btn-block"><i class="fas fa-arrow-left"></i> Regresar a <?= $singlr; ?></a>
                    </div>
                    <div class="col-sm-6">
                        <input type="hidden" class="form-control" name="pass" value="<?= base64_encode(base64_encode($call->contrasenia_u)); ?>">
                        <input type="hidden" name="pid" value="<?= base64_encode($pid); ?>">
                        <input type="hidden" name="uid" value="<?= base64_encode($uid); ?>">
                        <input type="hidden" name="url" value="<?= base64_encode($location); ?>">
                        <?php if ($is_visor): ?>
                            <button type="submit" class="btn btn-success btn-block" name="editar"><i class="fas fa-save"></i> Guardar la información del <?= $singlr; ?> <i class="fas fa-save"></i></button>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
```

---

## 5.4. Desglose de Componentes de la Vista-Detalle

1. **Campos Ocultos Obligatorios para la Edición**:
   - `name="pass"`: Contraseña hash actual codificada en doble Base64.
   - `name="pid"`: Llave primaria (PK) del registro codificada en Base64.
   - `name="uid"`: ID del usuario logueado codificado en Base64.
   - `name="url"`: URL de retorno codificada en Base64 (`$location`).
   - `name="foto_u_actual"`: Nombre del archivo de imagen actual guardado en el servidor.

2. **Formulario POST**:
   - Apunta a `action="<?= ACTI.$action; ?>"`.
   - Incluye `enctype="multipart/form-data"` para permitir la subida de archivos adjuntos.
   - Todos los inputs llevan de `name="[campo]"` el nombre exacto de la columna en la BD.
   - Botón submit con `name="editar"` condicionado a permisos de edición: `<?php if ($is_visor): ?>`.

---

# 6. CORE Estructura de las Vistas-Nuevo (Metodología VAC)

Las Vistas-Nuevo (ubicadas en `home/{modulo}/{tabla}/nuevo/index.php`) representan formularios dedicados e independientes para la creación de registros complejos que requieren demasiados campos, tablas secundarias dinámicas o interacción interactiva avanzada mediante **AJAX** (por ejemplo: **Cotizaciones**, **Productos**, **Pedidos**, **Facturas**, **Órdenes de Trabajo**), los cuales no son adecuados para desplegarse dentro de un modal simple.

---

## 6.1. Reglas Fundamentales de Arquitectura en Vistas-Nuevo

1. **Invocación de la Función `nuevo()` del Controlador**:
   - En el encabezado `<head>` de la vista se requiere el archivo de la Acción `$action` e invoca la función global `nuevo()`:
     ```php
     //--------------------------------------
     require_once($rut.DIRACT.$action);
     $data = nuevo($rut, $rid, $uid, $location);
     //--------------------------------------
     ```
   - Parámetros de la función `nuevo()`: `$rut`, `$rid`, `$uid`, `$location` (y opcionalmente `$vista` si aplica).

2. **PROHIBICIÓN TOTAL DE BUCLES EN LA VISTA**:
   - Queda totalmente prohibido el uso de bucles (`while`, `foreach`, `for`) en el marcado HTML de la vista.
   - Todos los selectores desplegables, opciones y listados sugeridos vienen pre-generados desde el backend dentro del objeto `$data` (ej. `$data->cboClientes`, `$data->cboProductos`).

3. **Interacción Dinámica AJAX para Tablas Temporales de Detalle**:
   - Para módulos con ítems o detalles dinámicos (ej. productos en una Cotización o Pedido), la vista implementa un script en JavaScript/jQuery que realiza llamadas asíncronas `$.ajax()` hacia los eventos `consulta` o `buscar` del controlador `ACTI.$action`.
   - La respuesta JSON permite consultar en tiempo real precios, stock o descripciones del producto seleccionado y agregarlo dinámicamente a una tabla temporal en el DOM (`<table id="tbl_items">`).
   - Cada fila agregada incluye inputs ocultos tipo array (`name="prod_id[]"`, `name="cant[]"`, `name="precio[]"`, `name="subtotal[]"`) para que al presionar enviar (`name="nuevo"`), el servidor reciba la cabecera y todo el detalle del registro en una sola transacción.

---

## 6.2. Código Prototipo de una Vista-Nuevo Dinámica con AJAX (`nuevo/index.php`)

```php
<?php
    //--------------------------------------
    if(isset($_SESSION)){}else{ session_start(); }
    //--------------------------------------
    $rut='../../../../';
    $rut2='../../';
    //--------------------------------------
    require($rut.'config/0code.php');
    //--------------------------------------
    $padre='Cotizaciones';
    $pagina='Nueva Cotización';
    $singlr='Cotización';
    $action='cotizaciones.php';
    //--------------------------------------
    $raiz=true;$subraiz=true;$detail=false;$nuevo=true;$imp=false;$exp=false;
    //--------------------------------------
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title><?= $pagina; ?> | <?= $_SESSION['tipo_name'].TIT; ?></title>
    <?php
        //--------------------------------------
        include_once($rut.CONF.'1stylesDAT.php');
        //--------------------------------------
        $data=null;
        //--------------------------------------
        require_once($rut.DIRACT.$action);
        $data = nuevo($rut, $rid, $uid, $location);
        //--------------------------------------
        require_once($rut.CONF.'0mens.php');
        //--------------------------------------
    ?>
</head>
<body class="<?= $_body_w; ?>">
<div class="wrapper">

    <?php include_once($rut2.'1nav.php'); ?>

    <div class="content-wrapper">
        <?php include_once($rut.VIEWS.'head.php'); ?>

        <section class="content pb-4">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-4">
                        <a href="../" class="btn btn-danger btn-block"><i class="fas fa-arrow-left"></i> Regresar a <?= $padre; ?></a>
                    </div>
                    <div class="col-sm-4 text-center">
                        <h3><b><?= $pagina; ?></b></h3>
                    </div>
                    <div class="col-sm-4 text-right">
                    </div>                  
                </div>

                <hr>

                <div class="row pb-4">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <form class="row" method="POST" action="<?= ACTI.$action; ?>" enctype="multipart/form-data">
                            <!-- Datos de Cabecera -->
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Cliente: <span class="text-danger">requerido*</span></label>
                                    <select class="form-control select2" name="id_cliente" required="required">
                                        <?= $data->cboClientes; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label class="col-form-label">Fecha de Emisión: <span class="text-danger">requerido*</span></label>
                                    <input type="date" class="form-control" name="fecha_emision" value="<?= date('Y-m-d'); ?>" required="required">
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <hr>
                                <h5><b>Agregar Productos / Detalle:</b></h5>
                            </div>

                            <!-- Selector e Inserción Dinámica vía AJAX -->
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label class="col-form-label">Producto:</label>
                                    <select class="form-control select2" id="sel_producto">
                                        <?= $data->cboProductos; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="col-form-label">Cantidad:</label>
                                    <input type="number" class="form-control" id="txt_cant" value="1" min="1">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="col-form-label">Precio Unit. (S/):</label>
                                    <input type="text" class="form-control" id="txt_precio" placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label class="col-form-label">&nbsp;</label>
                                    <button type="button" id="btn_agregar" class="btn btn-success btn-block"><i class="fas fa-plus"></i> Agregar</button>
                                </div>
                            </div>

                            <!-- Tabla Temporal Dinámica en DOM -->
                            <div class="col-sm-12 pt-3">
                                <table id="tbl_items" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th class="text-center">Cantidad</th>
                                            <th class="text-right">Precio Unit.</th>
                                            <th class="text-right">Subtotal</th>
                                            <th class="text-center">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody_items">
                                        <!-- Filas agregadas dinámicamente con JavaScript -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-right">TOTAL (S/):</th>
                                            <th class="text-right" id="lbl_total">0.00</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="col-sm-12 pt-3">
                                <div class="form-group">
                                    <label class="col-form-label">Observaciones / Notas:</label>
                                    <textarea class="form-control" name="observaciones" rows="3" placeholder="Ingrese observaciones del registro..."></textarea>
                                </div>
                            </div>
                            
                            <div class="col-sm-12 pt-3 pb-2">
                                <br>
                            </div>

                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <a href="../" class="btn btn-info btn-block"><i class="fas fa-arrow-left"></i> Cancelar y Regresar</a>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="hidden" name="uid" value="<?= base64_encode($uid); ?>">
                                        <input type="hidden" name="url" value="<?= base64_encode($location); ?>">
                                        <?php if ($is_visor): ?>
                                            <button type="submit" class="btn btn-primary btn-block" name="nuevo"><i class="fas fa-save"></i> Guardar <?= $singlr; ?> <i class="fas fa-save"></i></button>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include_once($rut.CONF.'4footer2.php'); ?>
</div>

<?php include_once($rut.CONF.'3javaDAT.php'); ?>
<?php include_once($rut.CONF.'5toastr.php'); ?>
<?php include_once($rut.VIEWS.'callchat.php'); ?>

<!-- Script de Interacción AJAX y Manipulación del DOM -->
<script type="text/javascript">
    $(document).ready(function(){
        var total_general = 0;

        // 1. Al cambiar el producto seleccionado, consultar precio mediante AJAX al controlador
        $('#sel_producto').on('change', function(){
            var pid = $(this).val();
            if (pid != '') {
                $.ajax({
                    url: '<?= ACTI.$action; ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: { 'buscar': true, 'valor': pid },
                    success: function(response){
                        if (response && response.precio) {
                            $('#txt_precio').val(response.precio);
                        }
                    }
                });
            }
        });

        // 2. Agregar ítem dinámicamente a la tabla temporal del DOM
        $('#btn_agregar').on('click', function(){
            var pid = $('#sel_producto').val();
            var pnom = $('#sel_producto option:selected').text();
            var cant = parseFloat($('#txt_cant').val()) || 0;
            var precio = parseFloat($('#txt_precio').val()) || 0;

            if (pid == '' || cant <= 0 || precio <= 0) {
                toastr.warning('Por favor seleccione un producto, cantidad y precio válidos.');
                return;
            }

            var subtotal = cant * precio;
            total_general += subtotal;

            var tr = '<tr id="row_' + pid + '">' +
                '<td>' + pnom + '<input type="hidden" name="prod_id[]" value="' + pid + '"></td>' +
                '<td class="text-center">' + cant + '<input type="hidden" name="cant[]" value="' + cant + '"></td>' +
                '<td class="text-right">' + precio.toFixed(2) + '<input type="hidden" name="precio[]" value="' + precio.toFixed(2) + '"></td>' +
                '<td class="text-right">' + subtotal.toFixed(2) + '<input type="hidden" name="subtotal[]" value="' + subtotal.toFixed(2) + '"></td>' +
                '<td class="text-center"><button type="button" class="btn btn-danger btn-xs btn-remove" data-subtotal="' + subtotal + '"><i class="fas fa-trash"></i></button></td>' +
            '</tr>';

            $('#tbody_items').append(tr);
            $('#lbl_total').text(total_general.toFixed(2));

            // Resetear inputs de selección
            $('#sel_producto').val('').trigger('change.select2');
            $('#txt_cant').val('1');
            $('#txt_precio').val('');
        });

        // 3. Remover fila de la tabla temporal y actualizar total
        $(document).on('click', '.btn-remove', function(){
            var sub = parseFloat($(this).data('subtotal')) || 0;
            total_general -= sub;
            if (total_general < 0) total_general = 0;
            $('#lbl_total').text(total_general.toFixed(2));
            $(this).closest('tr').remove();
        });
    });
</script>
</body>
</html>
<?php
    if (isset($_SESSION['stat'])) { unset($_SESSION['stat']); }
    if (isset($_SESSION['error'])) { unset($_SESSION['error']); }
    if (isset($_SESSION['Mysqli_Error'])) { unset($_SESSION['Mysqli_Error']); }
```

---

## 6.3. Desglose de Componentes de la Vista-Nuevo con AJAX

1. **Consulta Asíncrona AJAX a los Handlers del Controlador**:
   - Peticiones enviadas a `action="<?= ACTI.$action; ?>"` invocando el evento `buscar` o `consulta`.
   - Permite traer dinámicamente precios, unidades de medida o stock del servidor sin recargar la página.

2. **Manipulación del DOM y Tabla Temporal (`#tbl_items`)**:
   - Al hacer clic en `#btn_agregar`, JavaScript genera dinámicamente filas HTML `<tr>` que se concatenan en `#tbody_items`.
   - Cada fila genera inputs ocultos tipo array (`name="prod_id[]"`, `name="cant[]"`, `name="precio[]"`, `name="subtotal[]"`).

3. **Recepción en Controlador Backend**:
   - Al enviar el formulario con `name="nuevo"`, el controlador procesa los arreglos `$_POST['prod_id']`, `$_POST['cant']`, etc. mediante un bucle de inserción en la tabla detalle de la base de datos (ej. `db_add_all`).

4. **Limpieza Final de Sesiones**:
   - `unset($_SESSION['stat'])`, `unset($_SESSION['error'])`, `unset($_SESSION['Mysqli_Error'])` al pie del documento.

