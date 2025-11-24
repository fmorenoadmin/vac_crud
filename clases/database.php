<?php
	/**
	 * 
	 */
	class database
	{
		//---------------------------------------------------------
			private $db_prd = 'localhost';//IP SERVER prd
			private $db_qas = 'localhost';//IP SERVER qas
			//---------------------------------------
			private $db_name_qas = 'vac_crud';
			private $db_name_prd = 'AQUI_TU_NOMBRE_DATABASE';
			//---------------------------------------
			private $db_user_qas = 'root';
			private $db_user_prd = 'AQUI_TU_USUARIO';
			//---------------------------------------
			private $db_pass_qas = '';
			private $db_pass_prd = 'AQUI_TU_CONTRASEÑA';
			private $db_port = 3306;// 5432 para postgresql
		//---------------------------------------------------------
			private $ruta_certs = '/var/www/certs/';
			private $ssl_mode = false;//Esto es manual, o tambien puede ser dimánico, ya que significa que la base de datos requiere conexión SSL
			// Variables para las rutas de los certificados (inicialmente vacías)
			private $ssl_key  = null; // Ruta a client-key.pem
			private $ssl_cert = null; // Ruta a client-cert.pem
			private $ssl_ca   = null; // Ruta a ca-cert.pem (Autoridad certificadora)
			private $ssl_capath = null; 
			private $ssl_cipher = null;
		//---------------------------------------------------------
			protected $db_type = DB_TYPE;
			protected $db_conec = NULL;
			protected $db_seldb = NULL;
			protected $db_encod = NULL;
			protected $db_query = NULL;
			protected $db_parse = NULL;
			protected $db_exect = NULL;
			protected $db_error = NULL;
			protected $db_array = NULL;
			protected $db_fetch = NULL;
			protected $db_object = NULL;
			protected $db_assoc = NULL;
			protected $db_num_r = NULL;
			protected $db_fre_r = NULL;
			protected $db_close = NULL;
			private $codificacion_original = 'ISO-8859-1';
			private $codificacion_objetivo = 'UTF-8';
		//---------------------------------------------------------CONST
			public function __construct() {
				// Definimos el tipo por defecto si existe la constante, sino mysql
				$this->db_type = $this->getConst('DB_TYPE', 'mysqli_');
				//---------------------------------------------------------
				// Cargamos el mapeo inicial
				$this->map_functions($this->db_type);
			}
			//---------------------------------------------------------
			public function load_other_type($_db_type) {
				// Actualizamos el tipo
				$this->db_type = $_db_type;
				//---------------------------------------------------------
				// Recargamos el mapeo
				$this->map_functions($_db_type);
			}
			// Función para configurar SSL desde fuera (en tu config.php o index)
			public function config_ssl($key, $cert, $ca, $capath=null, $cipher=null) {
				$this->ssl_mode = true; // Al configurar rutas, activamos el modo automáticamente
				//---------------------------------------------------------
				// CONCATENACIÓN: Ruta Base + Nombre de Archivo
				// Validamos si la ruta termina en / para evitar dobles barras
				$base = rtrim($this->ruta_certs, '/') . '/';
				//---------------------------------------------------------
				$this->ssl_key		= $key ? $base . $key : null;
				$this->ssl_cert		= $cert ? $base . $cert : null;
				$this->ssl_ca		= $ca ? $base . $ca : null;
				// capath suele ser un directorio, cipher es un string de algoritmo
				$this->ssl_capath	= $capath; 
				$this->ssl_cipher	= $cipher;
			}
			//---------------------------------------------------------
			/**
			 * El corazón de la abstracción: Mapea funciones nativas a variables de clase
			 */
			private function map_functions($type) {
				switch ($type) {
					case 'mysqli_':
						$this->db_conec		= 'mysqli_connect';
						$this->db_seldb		= 'mysqli_select_db';
						$this->db_encod		= 'mysqli_set_charset';
						$this->db_query		= 'mysqli_query';
						$this->db_parse		= null;
						$this->db_exect		= null;
						$this->db_error		= 'mysqli_error';
						$this->db_array		= 'mysqli_fetch_array';
						$this->db_fetch		= 'mysqli_fetch_object';
						$this->db_object	= 'mysqli_fetch_object';
						$this->db_assoc		= 'mysqli_fetch_assoc';
						$this->db_num_r		= 'mysqli_num_rows';
						$this->db_fre_r		= 'mysqli_free_result';
						$this->db_close		= 'mysqli_close';
					break;
					case 'pg_': // PostgreSQL
						$this->db_conec		= 'pg_connect';
						$this->db_seldb		= null;// PG selecciona DB en el connect string
						$this->db_encod		= 'pg_set_client_encoding';
						$this->db_query		= 'pg_query';
						$this->db_parse		= null;
						$this->db_exect		= null;
						$this->db_error		= 'pg_last_error';
						$this->db_array		= 'pg_fetch_array';
						$this->db_fetch		= 'pg_fetch_object';
						$this->db_object	= 'pg_fetch_object';
						$this->db_assoc		= 'pg_fetch_assoc';
						$this->db_num_r		= 'pg_num_rows';
						$this->db_fre_r		= 'pg_free_result';
						$this->db_close		= 'pg_close';
					break;
					case 'sqlsrv_': // SQL Server (Microsoft Drivers)
						$this->db_conec		= 'sqlsrv_connect';
						$this->db_seldb		= null;// SQLSrv selecciona en connect options
						$this->db_encod		= null;
						$this->db_query		= 'sqlsrv_query';
						$this->db_parse		= null;
						$this->db_exect		= null;
						$this->db_error		= 'sqlsrv_errors';// Ojo: devuelve array
						$this->db_array		= 'sqlsrv_fetch_array';
						$this->db_fetch		= 'sqlsrv_fetch';
						$this->db_object	= 'sqlsrv_fetch_object';
						$this->db_assoc		= 'sqlsrv_fetch_array';// SQLSrv usa fetch_array con constante
						$this->db_num_r		= 'sqlsrv_num_rows';// Requiere cursor específico
						$this->db_fre_r		= 'sqlsrv_free_stmt';
						$this->db_close		= 'sqlsrv_close';
					break;
					case 'oci_': // Oracle
						$this->db_conec		= 'oci_connect';
						$this->db_seldb		= null;
						$this->db_encod		= null;
						$this->db_query		= 'oci_parse';// Oracle requiere parse + execute
						$this->db_parse		= 'oci_parse';
						$this->db_exect		= 'oci_execute';
						$this->db_error		= 'oci_error';
						$this->db_array		= 'oci_fetch_array';
						$this->db_fetch		= 'oci_fetch';
						$this->db_object	= 'oci_fetch_object';
						$this->db_assoc		= 'oci_fetch_assoc';
						$this->db_num_r		= 'oci_num_rows';
						$this->db_fre_r		= 'oci_free_statement';
						$this->db_close		= 'oci_close';
					break;
					default:
						// Fallback a MySQLi si no se reconoce
						$this->map_functions('mysqli_');
					break;
				}
			}
			private function getConst($name, $default = null) {
				return defined($name) ? constant($name) : $default;
			}
		//---------------------------------------------------------CON
			function connect($schu=null,$db='con',$_db_type=null){
				if (is_null($_db_type)) {
					$fc_conec= $this->db_conec;
					$dt_type = $this->db_type;
				}else{
					$fc_conec= $_db_type.'connect';
					$dt_type = $_db_type;
				}
				$db_seldb = $this->db_seldb;
				$db_encod = $this->db_encod;
				//----------------------------------
				if (!is_null($schu)) {
					$name = "db".strtolower($schu);
					$base = "db_name".strtolower($schu);
					$user = "db_user".strtolower($schu);
					$pass = "db_pass".strtolower($schu);
				}else{
					$name = "db".strtolower(SCHU);
					$base = "db_name".strtolower(SCHU);
					$user = "db_user".strtolower(SCHU);
					$pass = "db_pass".strtolower(SCHU);
				}
				//----------------------------------
					switch ($db) {
						case 'vac2':
							$_host = $this->$name;
							$_port = $this->db_port;
							$_user = $this->$user;
							$_pass = $this->$pass;
							$_name = 'vac2';//nombre de otra base de datos
						break;
						case 'vac3':
							$_host = $this->$name;
							$_port = $this->db_port;
							$_user = $this->$user;
							$_pass = $this->$pass;
							$_name = 'vac3';//nombre de otra base de datos
						break;
						default:
							$_host = $this->$name;
							$_port = $this->db_port;
							$_user = $this->$user;
							$_pass = $this->$pass;
							$_name = $this->$base;
						break;
					}
				//----------------------------------
				switch ($dt_type) {
					case 'pg_'://conexcióna  base de datos PostgreSQL
						//$str = "host=".$_host." port=".$_port." dbname=".$_name." user=".$_user." password=".$_pass;
						$str = "host={$_host} port=5432 dbname={$_name} user={$_user} password={$_pass}";
						//----------------------------------
						if($this->ssl_mode){
							$str .= " sslmode=verify-ca"; 
							// PG acepta rutas, aquí usamos las que ya concatenamos en config_ssl
							if($this->ssl_ca)   $str .= " sslrootcert={$this->ssl_ca}";
							if($this->ssl_cert) $str .= " sslcert={$this->ssl_cert}";
							if($this->ssl_key)  $str .= " sslkey={$this->ssl_key}";
						}
						//----------------------------------
						$con = $fc_conec($str);
						// PG usa la función mapeada correctamente
						if($con) $db_encod($con, "UTF8");
					break;
					case 'sqlsrv_'://conexcióna  base de datos SQL Server
						$serverName = $_host."\\sqlexpress"; //serverName\instanceName
						//$serverName = $_host.", ".$_port;
						//$serverName = $_host."\sqlexpress, 1542"; //serverName\instanceName, portNumber (por defecto es 1433)
						//----------------------------------
						//$connectionInfo = array( "Database"=>"dbName");
						//----------------------------------
						// Añadimos CharacterSet UTF-8 para evitar problemas de tildes
						$connectionInfo = array(
							"Database" => $_name,
							"UID" => $_user,
							"PWD" => $_pass,
							"CharacterSet" => "UTF-8"
						);
						// AÑADIDO: Lógica SSL que faltaba en tu copia
						if($this->ssl_mode) {
							$connectionInfo['Encrypt'] = true;
							// TrustServerCertificate true si es autofirmado, false si es certificado real
							$connectionInfo['TrustServerCertificate'] = true; 
						}
						//----------------------------------
						$con = $fc_conec($serverName, $connectionInfo);
					break;
					case 'oci_': // ORACLE
						// AÑADIDO: Lógica SSL Protocol que faltaba en tu copia
						$protocol = $this->ssl_mode ? 'TCPS' : 'TCP';
						// Oracle string: //host:port/service_name
						$db_str = "(DESCRIPTION=(ADDRESS=(PROTOCOL={$protocol})(HOST={$_host})(PORT={$_port}))(CONNECT_DATA=(SERVICE_NAME={$_name})))";
						// Si tienes Wallet de Oracle para SSL, se configura en sqlnet.ora generalmente,
						// pero el protocolo TCPS es clave aquí.
						// oci_connect(user, pass, string, encoding)
						$con = $fc_conec($_user, $_pass, $db_str, 'AL32UTF8');
					break;
					default://conexción a base de datos MySQL - Siempre por defecto
						if ($this->ssl_mode) {// Inicializamos MySQLi
							$con = mysqli_init();
							// Verificamos que los archivos existan antes de intentar usarlos
							// Esto evita errores fatales si la ruta está mal
							if ($this->ssl_key && $this->ssl_cert && $this->ssl_ca) {
								mysqli_options($con, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);
								//----------------------------------
								// Aquí usamos las variables que ya tienen la RUTA COMPLETA concatenada
								mysqli_ssl_set(
									$con,
									$this->ssl_key,
									$this->ssl_cert,
									$this->ssl_ca,
									$this->ssl_capath,
									$this->ssl_cipher
								);
							}
							//----------------------------------
							// Conectamos
							if (!mysqli_real_connect($con, $_host, $_user, $_pass, $_name, $_port)) {
								die("Error SSL Connect: " . mysqli_connect_error());
							}
						} else {
							$con = $fc_conec($_host, $_user, $_pass, $_name, $_port) OR die("{$_host} - {$_user} - {$_pass} - {$_name} - {$_port}");
							// Selección de DB procedimental
							//$db_seldb($con, $_name);
							// CORRECCIÓN: Usamos la variable de función, no el método de objeto
							$db_encod($con, "utf8");
						}
					break;
				}
				//----------------------------------
				return($con);
			}
		//---------------------------------------------------------EXEC
			public function db_exec($sql, $ret_res=true, $db='con', $_db_type=null){
				// 1. Gestión de cambio temporal de tipo
				$tipo_actual = $this->db_type;// Guardamos el tipo original
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				// 2. Carga de funciones mapeadas (usamos las de la clase)
				$fc_query=$this->db_query;$fc_parse=$this->db_parse;$fc_exect=$this->db_exect;$fc_error=$this->db_error;$fc_fetch=$this->db_fetch;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
				//---------------------------------------------------------
				// 3. Inicializar Respuesta
				$data = new stdClass();
				$data->result = false;
				$data->cant = 0;
				$data->res = null;
				$data->error = null;
				$data->mensaje = "";
				//---------------------------------------------------------
				// 4. Conexión
				// Nota: SCHU debe ser una constante definida en tu sistema, si no, pásala como null o string
				$schu_val = defined('SCHU') ? SCHU : null; 
				$_cc = $this->connect($schu_val, $db, $this->db_type);
				//---------------------------------------------------------
				if (!$_cc) {
					$data->error = "Error de Conexión";
					return $data;
				}
				//---------------------------------------------------------
				// 5. Ejecución según el motor
				$res = null;
				//---------------------------------------------------------
				switch($this->db_type){
					case 'oci_':
						// --- Lógica ORACLE (Parse + Execute) ---
						$stmt = @$fc_parse($_cc, $sql);
						//---------------------------------------------------------
						if ($stmt) {
							// OCI_COMMIT_ON_SUCCESS es el default. 
							if (@$fc_exect($stmt)) {
								$res = $stmt;// En Oracle el recurso es el statement
							} else {
								$e = $fc_error($stmt); 
								$data->error = is_array($e) ? $e['message'] : $e;
							}
						} else {
							$e = $fc_error($_cc); 
							$data->error = is_array($e) ? $e['message'] : $e;
						}
					break;
					case 'sqlsrv_':
						// --- Lógica SQL SERVER (Cursor Scrollable) ---
						// Necesario para que num_rows funcione sin recorrer el array
						$params = array();
						$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
						//---------------------------------------------------------
						$res = @$fc_query($_cc, $sql, $params, $options);
						//---------------------------------------------------------
						if (!$res) {
							$errors = $fc_error();
							// Convertimos array de errores a string
							if(is_array($errors)) {
								foreach($errors as $e) $data->error .= $e['message'] . " ";
							}
						}
					break;
					default:
						// --- Lógica MySQLi / PostgreSQL ---
						$res = @$fc_query($_cc, $sql);
						if (!$res) {
							$data->error = $fc_error($_cc);
						}
					break;
				}
				//---------------------------------------------------------
				// 6. Procesar Resultados
				if ($res) {
					$data->result = true;
					$data->mensaje = "Ejecutado exitosamente";
					// ---------------------------------------------------------
					// CORRECCIÓN PARA EL ERROR: TypeError: mysqli_num_rows()
					// ---------------------------------------------------------
					// Si $res es booleano (true), es un INSERT/UPDATE exitoso en MySQLi.
					// No contiene filas para contar, así que forzamos 0.
					if (is_bool($res)) {
						$num_rows = 0; 
					} else {
						// Si es un objeto/recurso, contamos las filas normalmente
						$num_rows = @$fc_num_r($res);
					}
					//---------------------------------------------------------
					if ($num_rows === false || $num_rows === null) $num_rows = 0;
					$data->cant = $num_rows;
					//---------------------------------------------------------
					// Corrección menor para Oracle: Si devuelve false o null, forzamos 0
					if ($data->cant === false || $data->cant === null) {
						$data->cant = 0;
					}
					//---------------------------------------------------------
					if ($ret_res) {
						$data->res = $res;
					}
				}
				//---------------------------------------------------------
				// 7. Restaurar tipo original
				if (!is_null($_db_type)) {
					$this->load_other_type($tipo_actual);
				}
				//---------------------------------------------------------
				return $data;
			}
			public function db_exec_sql($sql, $ret_res=true, $db='con', $_db_type=null){
				// 1. Gestión de cambio temporal de tipo
				$tipo_actual = $this->db_type;
				//---------------------------------------------------------
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				// 2. Carga de funciones mapeadas (Línea conservada como pediste)
				$fc_query=$this->db_query;$fc_parse=$this->db_parse;$fc_exect=$this->db_exect;$fc_error=$this->db_error;$fc_fetch=$this->db_fetch;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
				//---------------------------------------------------------
				// 3. Inicializar Respuesta
				$data = new stdClass();
				$data->result = false;
				$data->cant = 0;
				$data->error = null;
				$data->mensaje = "";
				//---------------------------------------------------------
				// 4. Conexión
				$schu_val = defined('SCHU') ? SCHU : null; 
				$_cc = $this->connect($schu_val, $db, $this->db_type);
				//---------------------------------------------------------
				if (!$_cc) {
					$data->error = "Error de Conexión";
					return $data;
				}
				//---------------------------------------------------------
				// 5. Ejecución (Lógica Polimórfica Universal)
				$res = null;
				//---------------------------------------------------------
				switch($this->db_type){
					case 'oci_': // ORACLE
						$stmt = @$fc_parse($_cc, $sql);
						//---------------------------------------------------------
						if ($stmt) {
							if (@$fc_exect($stmt)) {
								$res = $stmt;
							} else {
								$e = $fc_error($stmt);
								$data->error = is_array($e) ? $e['message'] : $e;
							}
						} else {
							$e = $fc_error($_cc);
							$data->error = is_array($e) ? $e['message'] : $e;
						}
					break;
					case 'sqlsrv_': // SQL SERVER
						$params = array();
						$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
						$res = @$fc_query($_cc, $sql, $params, $options);
						if (!$res) {
							$errors = $fc_error();
							if(is_array($errors)) {
								foreach($errors as $e) $data->error .= $e['message'] . " ";
							}
						}
					break;
					default: // MySQLi / PostgreSQL
						$res = @$fc_query($_cc, $sql);
						if (!$res) {
							$data->error = $fc_error($_cc);
						}
					break;
				}
				//---------------------------------------------------------
				// 6. Procesar Resultados y Mapear a $data
				if ($res) {
					$data->result = true;
					$data->mensaje = "Ejecutado exitosamente";
					// ---------------------------------------------------------
					// CORRECCIÓN PARA EL ERROR: TypeError: mysqli_num_rows()
					// ---------------------------------------------------------
					// Si $res es booleano (true), es un INSERT/UPDATE exitoso en MySQLi.
					// No contiene filas para contar, así que forzamos 0.
					if (is_bool($res)) {
						$num_rows = 0; 
					} else {
						// Si es un objeto/recurso, contamos las filas normalmente
						$num_rows = @$fc_num_r($res);
					}
					//---------------------------------------------------------
					if ($num_rows === false || $num_rows === null) $num_rows = 0;
					$data->cant = $num_rows;
					//---------------------------------------------------------
					if ($ret_res && $num_rows > 0) {
						// --- LÓGICA DE FETCH UNIVERSAL ---
						// El objetivo es mapear las columnas de la fila directamente a $data
						// Usamos assoc para garantizar claves string (nombre de columna)
						//---------------------------------------------------------
						if ($this->db_type == 'sqlsrv_') {
							// SQLSrv requiere constante explicita para array asociativo
							while ($row = $fc_assoc($res, SQLSRV_FETCH_ASSOC)) {
								foreach ($row as $key => $value) {
									$data->$key = $value;
								}
							}
						} elseif ($this->db_type == 'oci_') {
							// Oracle
							while ($row = $fc_assoc($res)) {
								foreach ($row as $key => $value) {
									// Oracle suele devolver MAYUSCULAS, aquí se asignan tal cual
									$data->$key = $value;
								}
							}
						} else {
							// MySQLi y PostgreSQL (Usan la función mapeada en $fc_assoc)
							while ($row = $fc_assoc($res)) {
								foreach ($row as $key => $value) {
									$data->$key = $value;
								}
							}
						}
					}
					// Si no hay filas, $data queda limpio solo con result=true
					//---------------------------------------------------------
				} else {
					$data->result = false;
					$data->cant = -1;
					// Si hubo error en ejecución, ya se llenó $data->error arriba
				}
				//---------------------------------------------------------
				// 7. Restaurar tipo original
				if (!is_null($_db_type)) {
					$this->load_other_type($tipo_actual);
				}
				//---------------------------------------------------------
				return $data;
			}
			public function db_exec_sql_array($sql, $ret_res=true, $db='con', $_db_type=null){
				// 1. Gestión de cambio temporal de tipo
				$tipo_actual = $this->db_type;
				//---------------------------------------------------------
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				// 2. Carga de funciones mapeadas (Conservada como pediste)
				$fc_query=$this->db_query;$fc_parse=$this->db_parse;$fc_exect=$this->db_exect;$fc_error=$this->db_error;$fc_fetch=$this->db_fetch;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
				//---------------------------------------------------------
				// 3. Inicializar Respuesta
				$data = new stdClass();
				$datos = array(); // Array para almacenar las filas
				$data->result = false;
				$data->cant = 0;
				$data->error = null;
				$data->mensaje = "";
				//---------------------------------------------------------
				// 4. Conexión
				$schu_val = defined('SCHU') ? SCHU : null; 
				$_cc = $this->connect($schu_val, $db, $this->db_type);
				//---------------------------------------------------------
				if (!$_cc) {
					$data->error = "Error de Conexión";
					return $data;
				}
				//---------------------------------------------------------
				// 5. Ejecución (Lógica Polimórfica Universal)
				$res = null;
				//---------------------------------------------------------
				switch($this->db_type){
					case 'oci_': // ORACLE
						$stmt = @$fc_parse($_cc, $sql);
						//---------------------------------------------------------
						if ($stmt) {
							if (@$fc_exect($stmt)) {
								$res = $stmt;
							} else {
								$e = $fc_error($stmt);
								$data->error = is_array($e) ? $e['message'] : $e;
							}
						} else {
							$e = $fc_error($_cc);
							$data->error = is_array($e) ? $e['message'] : $e;
						}
					break;
					case 'sqlsrv_': // SQL SERVER
						$params = array();
						$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
						//---------------------------------------------------------
						$res = @$fc_query($_cc, $sql, $params, $options);
						if (!$res) {
							$errors = $fc_error();
							if(is_array($errors)) {
								foreach($errors as $e) $data->error .= $e['message'] . " ";
							}
						}
					break;
					default: // MySQLi / PostgreSQL
						$res = @$fc_query($_cc, $sql);
						if (!$res) {
							$data->error = $fc_error($_cc);
						}
					break;
				}
				//---------------------------------------------------------
				// 6. Procesar Resultados y Llenar Array
				if ($res) {
					$data->result = true;
					$data->mensaje = "Ejecutado exitosamente";
					// ---------------------------------------------------------
					// CORRECCIÓN PARA EL ERROR: TypeError: mysqli_num_rows()
					// ---------------------------------------------------------
					// Si $res es booleano (true), es un INSERT/UPDATE exitoso en MySQLi.
					// No contiene filas para contar, así que forzamos 0.
					if (is_bool($res)) {
						$num_rows = 0; 
					} else {
						// Si es un objeto/recurso, contamos las filas normalmente
						$num_rows = @$fc_num_r($res);
					}
					//---------------------------------------------------------
					if ($num_rows === false || $num_rows === null) $num_rows = 0;
					$data->cant = $num_rows;
					//---------------------------------------------------------
					if ($ret_res && $num_rows > 0) {
						// --- BUCLE DE LLENADO UNIVERSAL ---
						//---------------------------------------------------------
						if ($this->db_type == 'sqlsrv_') {
							// SQLSrv con fetch_array asociativo
							while ($row = $fc_array($res, SQLSRV_FETCH_ASSOC)) {
								$datos[] = $row;
							}
						} elseif ($this->db_type == 'oci_') {
							// Oracle con fetch_assoc
							while ($row = $fc_assoc($res)) {
								// Oracle devuelve claves en MAYÚSCULAS por defecto.
								// Si quieres normalizar a minúsculas descomenta la línea de abajo:
								// $row = array_change_key_case($row, CASE_LOWER);
								$datos[] = $row;
							}
						} else {
							// MySQLi y PostgreSQL (Usan la función mapeada en $fc_assoc)
							while ($row = $fc_assoc($res)) {
								$datos[] = $row;
							}
						}
					}
				} else {
					$data->result = false;
					$data->cant = -1;
					// Error ya capturado arriba
				}
				//---------------------------------------------------------
				// 7. Finalizar
				$data->datos = $datos; // Asignamos el array lleno
				//---------------------------------------------------------
				// Opcional: Cerrar conexión explícitamente si se desea
				$fc_close($_cc);
				//---------------------------------------------------------
				// Restaurar tipo original
				if (!is_null($_db_type)) {
					$this->load_other_type($tipo_actual);
				}
				//---------------------------------------------------------
				return $data;
			}
		//---------------------------------------------------------UTILIDADES
			public function cal_fecha($fecha){
				if (is_null($fecha)) {
					return '<span class="btn btn-outline-info btn-xs">Sin Fecha</span>';
				}
				//---------------------------------------------------------
				$hoy = new DateTime(); // Usamos DateTime, es más preciso
				$fecha_obj = new DateTime($fecha);
				//---------------------------------------------------------
				// Obtenemos diferencia exacta en días (signed para saber si pasó)
				$diferencia = $hoy->diff($fecha_obj); 
				$dias = (int)$diferencia->format('%r%a'); // %r signo, %a días totales
				//---------------------------------------------------------
				// Lógica de visualización
				$class = 'info';
				$extra = '';
				//---------------------------------------------------------
					if ($dias > 60) {
						$class = 'success';
					} elseif ($dias >= 30) {
						$class = 'warning';
						$extra = ' ALERTA';
					} elseif ($dias >= 0) { // Menos de 30 pero futuro/hoy
						$class = 'danger';
						$extra = ' URGENTE';
					} else { // Fechas pasadas
						$class = 'secondary'; 
						$extra = ' VENCIDO';
					}
				//---------------------------------------------------------
				return sprintf(
					'<span class="btn btn-outline-%s btn-xs">%s%s</span>',
					$class . ' ' . abs($dias), // Clase + días (como tenías en tu lógica)
					$fecha,
					'</span>' . $extra // Hack para mantener tu estructura de str_replace anterior
				);
			}
			public function sum_fecha($campo, $fecha, $time){
				if ($campo != 1 || is_null($fecha)) {
					return NULL;
				}
				//---------------------------------------------------------
				// Normalizamos fecha usando nuestra propia función
				$nueva_fecha = $this->form_fecha($fecha);
				$fecha_obj = new DateTime($nueva_fecha);
				//---------------------------------------------------------
				// Construcción dinámica del intervalo ISO 8601 (P1Y2M...)
				$intervalo_str = 'P';
				if (!empty($time->años))	$intervalo_str .= $time->cant_años . 'Y';
				if (!empty($time->meses))   $intervalo_str .= $time->cant_meses . 'M';
				if (!empty($time->semanas)) $intervalo_str .= $time->cant_semanas . 'W';
				if (!empty($time->dias))	$intervalo_str .= $time->cant_dias . 'D';
				//---------------------------------------------------------
				// Si hay tiempo (Horas/Min/Seg), se agrega 'T'
				if (!empty($time->tiempo)) {
					$intervalo_str .= 'T';
					if (!empty($time->hor)) $intervalo_str .= $time->cant_hor . 'H';
					if (!empty($time->min)) $intervalo_str .= $time->cant_min . 'M';
					if (!empty($time->seg)) $intervalo_str .= $time->cant_seg . 'S';
				}
				//---------------------------------------------------------
				// Solo sumamos si se construyó un intervalo válido (mayor a 'P' o 'PT')
				if (strlen($intervalo_str) > 1 && $intervalo_str !== 'PT') {
					try {
						$fecha_obj->add(new DateInterval($intervalo_str));
					} catch (Exception $e) {
						return $nueva_fecha; // Retorna original si falla el intervalo
					}
				}
				//---------------------------------------------------------
				return $fecha_obj->format('Y-m-d');
			}
			public function form_fecha($fecha){
				// 1. Validar entrada vacía
				if (is_null($fecha) || empty($fecha)) {
					return date('Y-m-d');
				}
				//---------------------------------------------------------
				$fecha = trim($fecha);
				//---------------------------------------------------------
				// 2. Caso Especial: Formato Compacto "YYYYMMDD" (8 dígitos seguidos)
				if (ctype_digit($fecha) && strlen($fecha) === 8) {
					$obj = DateTime::createFromFormat('Ymd', $fecha);
					return $obj ? $obj->format('Y-m-d') : date('Y-m-d');
				}
				//---------------------------------------------------------
				// 3. Normalización: Convertir cualquier separador (/ o .) a guiones (-)
				// Esto convierte "2023/01/01" o "2023.01.01" en "2023-01-01"
				$fecha_norm = str_replace(['/', '.'], '-', $fecha);
				//---------------------------------------------------------
				// 4. Detección Inteligente basada en posición del año (4 dígitos)
				//---------------------------------------------------------
				// CASO A: Formato Internacional (El año está al principio: YYYY-MM-DD)
				// Regex: Empieza con 4 dígitos, guion, 1 o 2 dígitos, guion, 1 o 2 dígitos
				if (preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}$/', $fecha_norm)) {
					try {
						$obj = new DateTime($fecha_norm);
						return $obj->format('Y-m-d');
					} catch (Exception $e) {
						// Si la fecha es inválida (ej: 2023-14-40), continuamos
					}
				}
				//---------------------------------------------------------
				// CASO B: Formato Latino/Europeo (El año está al final: DD-MM-YYYY)
				// Regex: Empieza con 1 o 2 dígitos, guion, 1 o 2 dígitos, guion, 4 dígitos
				if (preg_match('/^\d{1,2}\-\d{1,2}\-\d{4}$/', $fecha_norm)) {
					// createFromFormat es mejor aquí para especificar que el primero es día
					$obj = DateTime::createFromFormat('d-m-Y', $fecha_norm);
					return $obj ? $obj->format('Y-m-d') : date('Y-m-d');
				}
				//---------------------------------------------------------
				// 5. Fallback Final: La "Magia" de PHP
				// Si los regex fallaron (ej: formatos textuales), intentamos strtotime
				$timestamp = strtotime($fecha_norm);
				if ($timestamp !== false && $timestamp > 0) {
					return date('Y-m-d', $timestamp);
				}
				//---------------------------------------------------------
				// 6. Si nada funcionó, devolvemos la fecha actual por defecto 
				// (Cumpliendo tu requisito de devolver SIEMPRE un formato fecha válido)
				return date('Y-m-d');
			}
			public function form_float($numero, $cant=2){
				// Limpieza robusta: acepta "1.000,50" o "1000.50"
				$numero = trim((string)$numero); // Aseguramos string
				//---------------------------------------------------------
				// Si tiene coma y punto, asumimos formato europeo (1.000,00) -> quitamos punto, cambiamos coma
				if (strpos($numero, '.') !== false && strpos($numero, ',') !== false) {
					$numero = str_replace('.', '', $numero);
					$numero = str_replace(',', '.', $numero);
				} elseif (strpos($numero, ',') !== false) {
					// Solo comas (1000,50) -> cambiar a punto
					$numero = str_replace(',', '.', $numero);
				}
				//---------------------------------------------------------
				$val = floatval($numero);
				//---------------------------------------------------------
				return ($val > 0) ? number_format($val, $cant, '.', '') : '0.00';
			}
			public function getRandomCode($tipo=8, $largo=16){
				//---------------------------------------------------------
				$sets = array(
					1 => '0123456789',
					2 => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
					3 => 'abcdefghijklmnopqrstuvwxyz',
					4 => '$%&{}[]()=!@|#*^+-_<>',
				);
				//---------------------------------------------------------
				// Construir el pool de caracteres según el tipo
				$pool = '';
				//---------------------------------------------------------
				switch ($tipo) {
					case 1: $pool = $sets[1]; break;
					case 2: $pool = $sets[2]; break;
					case 3: $pool = $sets[3]; break;
					case 4: $pool = $sets[4]; break;
					case 5: $pool = $sets[1] . $sets[2]; break;
					case 6: $pool = $sets[1] . $sets[3]; break;
					case 7: $pool = $sets[1] . $sets[2] . $sets[3]; break;
					case 8: $pool = $sets[1] . $sets[2] . $sets[3] . $sets[4]; break;
					default: $pool = $sets[1] . $sets[2] . $sets[3] . $sets[4]; break;
				}
				//---------------------------------------------------------
				// Generación rápida
				$max = strlen($pool) - 1;
				//---------------------------------------------------------
				$token = '';
				//---------------------------------------------------------
				for ($i = 0; $i < $largo; $i++) {
					$token .= $pool[random_int(0, $max)]; // random_int es criptográficamente seguro
				}
				//---------------------------------------------------------
				return $token;
			}
			public function form_txt($input) {
				// Limpieza estándar
				$input = strip_tags(trim($input));
				//---------------------------------------------------------
				// Permite letras, números y puntuación básica, elimina otros símbolos raros
				// Agregado 'u' al final del regex para soporte UTF-8 completo
				$input = preg_replace('/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s+\-_.,$@!¡?¿()]/u', '', $input);
				//---------------------------------------------------------
				// addslashes es útil si no usas Prepared Statements, pero cuidado con doble escape
				$input = addslashes($input);
				//---------------------------------------------------------
				return $input;
			}
			public function reemp_car_esp($texto) {
				// Array de reemplazo
				$reemplazos = array(
					'á'=>'a', 'é'=>'e', 'í'=>'i', 'ó'=>'o', 'ú'=>'u', 'ñ'=>'n',
					'Á'=>'A', 'É'=>'E', 'Í'=>'I', 'Ó'=>'O', 'Ú'=>'U', 'Ñ'=>'N',
					'ü'=>'u', 'Ü'=>'U', 'ç'=>'c', 'Ç'=>'C', 
					'&aacute;'=>'a', '&eacute;'=>'e', '&iacute;'=>'i', '&oacute;'=>'o', '&uacute;'=>'u', '&ntilde;'=>'n',
					'&Aacute;'=>'A', '&Eacute;'=>'E', '&Iacute;'=>'I', '&Oacute;'=>'O', '&Uacute;'=>'U', '&Ntilde;'=>'N',
					'º'=>'o', '&ordm;'=>'o', 'ª'=>'a', '&ordf;'=>'a',
					'¡'=>'', '&iexcl;'=>'', '¿'=>'', '&iquest;'=>''
				);
				//---------------------------------------------------------
				// Realizar el reemplazo
				$reemplazado = strtr($texto, $reemplazos);
				//---------------------------------------------------------
				return $reemplazado;
			}
			public function form_txt_sap($cadena, $codic='html', $longitud=30) {
				// Usamos mb_str_split para no romper caracteres especiales al dividir
				// Si no tienes PHP 7.4, usa la función dividir_str de abajo
				$partes = $this->dividir_str($cadena, $longitud); 
				//---------------------------------------------------------
				$html_parts = array();
				//---------------------------------------------------------
				foreach ($partes as $parte) {
					$parte = str_replace(['`', '´'], "'", $parte);
					//---------------------------------------------------------
					if ($codic === 'html') {
						$html_parts[] = htmlentities($parte, ENT_QUOTES, 'UTF-8');
					} elseif ($codic === 'str') {
						$html_parts[] = $this->reemp_car_esp($parte);
					} else {
						$html_parts[] = $parte;
					}
				}
				//---------------------------------------------------------
				return $html_parts;
			}
			public function dividir_str($cadena, $longitud = 30) {
				$cadena = html_entity_decode($cadena, ENT_QUOTES, 'UTF-8');
				$cadena = strip_tags($cadena);
				//---------------------------------------------------------
				// Reemplazo de acentos básico antes de cortar para evitar líos de codificación
				$cadena = $this->reemp_car_esp($cadena); 
				$cadena = trim($cadena);
				//---------------------------------------------------------
				// Uso de str_split. Como ya limpiamos acentos arriba con reemp_car_esp, 
				// str_split es seguro. Si quisieras mantener acentos, deberías usar mb_str_split.
				$str = str_split($cadena, $longitud);
				//---------------------------------------------------------
				return $str;
			}
			public function custom_escape_string($value) {
				// Paso 1: Decodificar entidades HTML (&amp;, &lt;, etc.)
				$value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
				//---------------------------------------------------------
				// Eliminar etiquetas HTML y PHP
				$value = strip_tags($value);
				//---------------------------------------------------------
				$_temp = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
				//---------------------------------------------------------
				return $_temp;
			}
			public function calc_cod_txt($pid,$largo=11) {
				// ¡OPTIMIZACIÓN MASIVA! - str_pad hace todo tu switch en 1 línea
				// Rellena con '0' a la izquierda hasta llegar a longitud 11 (según tu lógica original)
				return str_pad($pid, $largo, '0', STR_PAD_LEFT);
			}
			public function get_mes_txt($mes){
				$mes = intval($mes);
				//---------------------------------------------------------
				$meses = array(
					1 => '01-Enero', 2 => '02-Febrero', 3 => '03-Marzo', 4 => '04-Abril',
					5 => '05-Mayo',  6 => '06-Junio',   7 => '07-Julio',  8 => '08-Agosto',
					9 => '09-Septiembre', 10 => '10-Octubre', 11 => '11-Noviembre', 12 => '12-Diciembre'
				);
				//---------------------------------------------------------
				$mes_txt = isset($meses[$mes]) ? $meses[$mes] : '00-No definido';
				//---------------------------------------------------------
				return $mes_txt;
			}
		//---------------------------------------------------------GET
			public function db_get_string($dt, $json, $db='con', $_db_type=null){
				// 1. Gestión de Tipo
				$tipo_actual = $this->db_type;
				//---------------------------------------------------------
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				// 2. Validación de Entrada
				$tname = isset($json->tname) ? $json->tname : null;
				$tid   = isset($json->tid)   ? $json->tid   : null;
				$pid   = isset($json->pid)   ? $json->pid   : null;
				//---------------------------------------------------------
				// Validación estricta
				if (empty($tname) || empty($tid) || is_null($pid) || $pid === '') {
					$data = new stdClass();
					$data->result = false;
					$data->mensaje = "Faltan parámetros (tname, tid, pid). Datos: " . json_encode($json);
					return $data;
				}
				//---------------------------------------------------------
				// 3. Generar SQL (Tipo 9 es para String ID con LIKE/Clean, Tipo 8 es INT)
				// Tu comentario decía "8 select por VALOR STRING", pero en get_sql el 8 es INT y el 9 es STRING con LIKE.
				// Si pid es string, deberíamos usar el 9 (que usa clean() y comillas simples).
				// Si prefieres mantener el 8, asegúrate de que get_sql case 8 maneje comillas si es string.
				// Usaré el 9 para string para mayor seguridad con el $clean interno.
				//---------------------------------------------------------
				$sql = $this->get_sql($tname, $dt, 9, $tid, $pid);
				//---------------------------------------------------------
				// 4. Ejecutar usando nuestra función maestra ya validada
				// Esto nos ahorra repetir toda la lógica de conexión, oracle parse, fetch, etc.
				$data = $this->db_exec_sql($sql, true, $db, $this->db_type);
				//---------------------------------------------------------
				// 5. Ajustar mensajes específicos de esta función
				if ($data->result && $data->cant > 0) {
					$data->mensaje = "Registro encontrado exitosamente.";
				} else {
					$data->result = false; // Aseguramos false si cant es 0
					$data->mensaje = "No se encontró coincidencia para el ID: " . $pid;
				}
				//---------------------------------------------------------
				// 6. Debug opcional
				if(isset($json->test) && $json->test == true){
					$data->sql = $sql;
					$data->input = $json;
				}
				//---------------------------------------------------------
				// 7. Restaurar tipo
				if (!is_null($_db_type)) {
					$this->load_other_type($tipo_actual);
				}
				//---------------------------------------------------------
				return $data;
			}
			public function db_get_id($dt, $json, $db='con', $_db_type=null){
				// 1. Gestión de Tipo
				$tipo_actual = $this->db_type;
				//---------------------------------------------------------
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				// 2. Validación de Entrada
				$tname = isset($json->tname) ? $json->tname : null;
				$tid   = isset($json->tid)   ? $json->tid   : null;
				$pid   = isset($json->pid)   ? $json->pid   : null;
				//---------------------------------------------------------
				// Validación estricta (Pid debe ser numérico y mayor a 0)
				if (empty($tname) || empty($tid) || is_null($pid) || !is_numeric($pid) || $pid <= 0) {
					$data = new stdClass();
					$data->result = false;
					$data->mensaje = "Parámetros inválidos o ID incorrecto. Datos: " . json_encode($json);
					return $data;
				}
				//---------------------------------------------------------
				// 3. Generar SQL (Tipo 8 es para ID Numérico en get_sql)
				$sql = $this->get_sql($tname, $dt, 8, $tid, $pid); 
				//---------------------------------------------------------
				// 4. Ejecutar usando nuestra función maestra
				// Esto maneja la conexión, ejecución en Oracle/SQLSrv y el mapeo de datos automáticamente.
				$data = $this->db_exec_sql($sql, true, $db, $this->db_type);
				//---------------------------------------------------------
				// 5. Ajustar mensajes específicos
				if ($data->result && $data->cant > 0) {
					$data->mensaje = "Registro encontrado exitosamente.";
				} else {
					$data->result = false;
					$data->mensaje = "No se encontró coincidencia para el ID: " . $pid . ".";
				}
				//---------------------------------------------------------
				// 6. Debug opcional
				if(isset($json->test) && $json->test == true){
					$data->sql = $sql;
					$data->input = $json;
				}
				//---------------------------------------------------------
				// 7. Restaurar tipo
				if (!is_null($_db_type)) {
					$this->load_other_type($tipo_actual);
				}
				//---------------------------------------------------------
				return $data;
			}
			public function db_get_camp_id_array($dt, $json, $db='con', $_db_type=null){
				// 1. Gestión de Tipo
				$tipo_actual = $this->db_type;
				//---------------------------------------------------------
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				// 2. Validación de Entrada
				$tname = isset($json->tname) ? $json->tname : null;
				$tid   = isset($json->tid)   ? $json->tid   : null;
				$pid   = isset($json->pid)   ? $json->pid   : null;
				$adic  = isset($json->adic)  ? $json->adic  : null;
				//---------------------------------------------------------
				// Validación estricta (Pid numérico > 0)
				if (empty($tname) || empty($tid) || is_null($pid) || !is_numeric($pid) || $pid <= 0) {
					$data = new stdClass();
					$data->result = false;
					$data->datos = array();
					$data->mensaje = "Parámetros inválidos o ID incorrecto. Datos: " . json_encode($json);
					//---------------------------------------------------------
					return $data;
				}
				//---------------------------------------------------------
				// 3. Generar SQL (Tipo 11: Select Specific Cols by ID)
				// $dt debe ser el array de campos a seleccionar
				$sql = $this->get_sql($tname, $dt, 11, $tid, $pid, null, $adic); 
				//---------------------------------------------------------
				// 4. Ejecutar usando nuestra función maestra de Arrays
				// Esta función ya maneja fetch_assoc, bucles y normalización de motores
				$data = $this->db_exec_sql_array($sql, true, $db, $this->db_type);
				//---------------------------------------------------------
				// 5. Ajustar mensajes específicos
				if ($data->result && $data->cant > 0) {
					$data->mensaje = "Registros encontrados exitosamente.";
				} else {
					// Si cant es 0, db_exec_sql_array devuelve result=true pero cant=0.
					// Aquí forzamos false si quieres que "no encontrar" sea un error lógico
					// O lo dejamos true con array vacío. Tu código original ponía result=false.
					if ($data->cant === 0) {
						$data->result = false;
						$data->mensaje = "No se encontró coincidencia para el ID: " . $pid . ".";
					}
				}
				//---------------------------------------------------------
				// 6. Debug opcional
				if(isset($json->test) && $json->test == true){
					$data->sql = $sql;
					$data->input = $json;
				}
				//---------------------------------------------------------
				// 7. Restaurar tipo
				if (!is_null($_db_type)) {
					$this->load_other_type($tipo_actual);
				}
				//---------------------------------------------------------
				return $data;
			}
			public function db_get_all($dt, $json, $db='con', $_db_type=null){
				// 1. Gestión de Tipo
				$tipo_actual = $this->db_type;
				//---------------------------------------------------------
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				// 2. Inicializar Respuesta
				$data = new stdClass();
				$data->result = false;
				$data->error = null;
				$data->mensaje = "";
				$data->inf = array();
				$data->rows = 0;
				//---------------------------------------------------------
				// 3. Validación de Entrada
				$tname = isset($json->tname) ? $json->tname : null;
				$tid   = isset($json->tid)   ? $json->tid   : null;
				$pid   = isset($json->pid)   ? $json->pid   : null;
				// Necesitamos saber qué columna es el "nombre" para mostrar
				$col_name = isset($json->col_name) ? $json->col_name : null;
				//---------------------------------------------------------
				if (empty($tname) || empty($tid) || is_null($pid) || empty($col_name)) {
					$data->mensaje = "Faltan parámetros (tname, tid, pid, col_name).";
					return $data;
				}
				//---------------------------------------------------------
				// 4. Generar SQL (Usamos tipo 5: Update Where? No, espera...)
				// Tu código original usaba $json->type. Si es un SELECT por ID, debería ser 8 o 9.
				// Si es un "Get All" filtrado por algún padre (ej: ciudades de un país), usaremos tipo 8 (ID INT) o 9 (ID String).
				// Asumiré que $json->type viene correcto, o usaré 8 por defecto si es numérico.
				//---------------------------------------------------------
				$tipo_sql = isset($json->type) ? $json->type : (is_numeric($pid) ? 8 : 9);
				//---------------------------------------------------------
				// $dt debe ser array con los campos a traer. Mínimo necesitamos ID y NAME.
				// Si $dt viene null, get_sql(8) hace SELECT *
				//---------------------------------------------------------
				$sql = $this->get_sql($tname, $dt, $tipo_sql, $tid, $pid);
				//---------------------------------------------------------
				// 5. Ejecutar query usando nuestra función maestra de Arrays
				$exec_data = $this->db_exec_sql_array($sql, true, $db, $this->db_type);
				//---------------------------------------------------------
				if ($exec_data->result && $exec_data->cant > 0) {
					$data->result = true;
					$data->mensaje = "Registros encontrados exitosamente.";
					//---------------------------------------------------------
					// 6. Transformar al formato específico ID/NAME
					foreach ($exec_data->datos as $row) {
						// Verificamos que las columnas existan en el resultado
						// (En Oracle podrían venir en mayúsculas si no se manejó antes)
						$val_id = isset($row[$tid]) ? $row[$tid] : (isset($row[strtoupper($tid)]) ? $row[strtoupper($tid)] : null);
						$val_name = isset($row[$col_name]) ? $row[$col_name] : (isset($row[strtoupper($col_name)]) ? $row[strtoupper($col_name)] : null);
						//---------------------------------------------------------
						if ($val_id !== null) {
							$data->inf[] = array(
								"id"   => $val_id,
								// Conversión de encoding segura (solo si hay valor)
								"name" => $val_name ? mb_convert_encoding($val_name, $this->codificacion_original, $this->codificacion_objetivo) : ''
							);
						}
					}
					//---------------------------------------------------------
					$data->rows = count($data->inf);
				} else {
					$data->result = false;
					$data->mensaje = $exec_data->mensaje ?: "No se encontraron registros.";
					$data->error = $exec_data->error;
				}
				//---------------------------------------------------------
				// 7. Debug opcional
				if(isset($json->test) && $json->test == true){
					$data->sql = $sql;
					$data->input = $json;
				}
				//---------------------------------------------------------
				// 8. Restaurar tipo
				if (!is_null($_db_type)) {
					$this->load_other_type($tipo_actual);
				}
				//---------------------------------------------------------
				return $data;
			}
			public function db_get_cant($dt, $json, $db='con', $_db_type=null){
				// 1. Gestión de Tipo
				$tipo_actual = $this->db_type;
				//---------------------------------------------------------
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				// 2. Inicializar Respuesta
				$data = new stdClass();
				$data->result = false;
				$data->total = 0;
				$data->error = null;
				$data->mensaje = "";
				//---------------------------------------------------------
				// 3. Validación de Entrada
				$tname = isset($json->tname) ? $json->tname : null;
				// Nota: En tu código original validabas 'tid' pero no lo usabas en el SQL COUNT(*).
				// Lo quité para ser coherente con el SQL que mostraste. Si lo necesitas, agrégalo.
				//---------------------------------------------------------
				if (empty($tname)) {
					$data->mensaje = "No existe el nombre de la tabla. Datos: " . json_encode($json);
					return $data;
				}
				//---------------------------------------------------------
				// 4. Generar SQL
				// SQL estándar compatible con todos los motores
				$sql = "SELECT COUNT(*) AS total FROM " . $tname . " WHERE status=1;";
				//---------------------------------------------------------
				// 5. Ejecutar usando nuestra función maestra de una sola fila
				// db_exec_sql mapea las columnas a propiedades del objeto $exec_data
				$exec_data = $this->db_exec_sql($sql, true, $db, $this->db_type);
				//---------------------------------------------------------
				if ($exec_data->result && $exec_data->cant > 0) {
					$data->result = true;
					$data->mensaje = "Registro encontrado exitosamente.";
					//---------------------------------------------------------
					// Intentamos obtener 'total' (o 'TOTAL' para Oracle si no se normalizó)
					if (isset($exec_data->total)) {
						$data->total = (int)$exec_data->total;
					} elseif (isset($exec_data->TOTAL)) {
						$data->total = (int)$exec_data->TOTAL;
					} else {
						// Fallback: tomar el primer valor del objeto si el alias falló
						$vars = get_object_vars($exec_data);
						// Filtramos propiedades internas de control
						unset($vars['result'], $vars['cant'], $vars['error'], $vars['mensaje']);
						$first_val = reset($vars);
						$data->total = (int)$first_val;
					}
				} else {
					$data->result = false;
					$data->mensaje = $exec_data->error ? "Error: " . $exec_data->error : "La respuesta está vacía o tabla sin registros activos.";
					$data->error = $exec_data->error;
				}
				//---------------------------------------------------------
				// 6. Debug opcional
				if(isset($json->test) && $json->test == true){
					$data->sql = $sql;
					$data->input = $json;
				}
				//---------------------------------------------------------
				// 7. Restaurar tipo
				if (!is_null($_db_type)) {
					$this->load_other_type($tipo_actual);
				}
				//---------------------------------------------------------
				return $data;
			}
			public function db_get_btns($total, $pag, $url = null, $bootstrap_v = 4) {
				$data = new stdClass();
				$data->inf = '';
				$html = '';
				//---------------------------------------------------------
				// 1. Definir filas por página (Usamos constante ROWS si existe, sino 10 por defecto)
				//---------------------------------------------------------
				// 2. Calcular total de páginas
				$rows = $this->getConst('ROWS', 50);
				//---------------------------------------------------------
				// Si hay 1 página, dejamos que fluya para mostrar los botones desactivados.
				$total_paginas = ceil($total / $rows);
				if ($total_paginas < 1) return $data;
				// ---------------------------------------------------------
				// LÓGICA DE URL AUTOMÁTICA (UNIVERSAL)
				// ---------------------------------------------------------
				// 1. Capturamos TODOS los parámetros que vienen en la URL actual
				$params = $_GET;
				// ---------------------------------------------------------
				// 2. Eliminamos 'pag' para que no se duplique ni se arrastre el valor anterior
				unset($params['pag']);
				//---------------------------------------------------------
				// 3. Construimos la cadena de consulta con TODO lo demás (tipo, titulo, estado, fecha, etc.)
				// Esto lo hace dinámico. Si agregas 50 filtros, esto los agarra solos.
				$query_string = http_build_query($params);
				//---------------------------------------------------------
				// 4. Definimos la URL Base
				// Si la función recibió una URL (ej: 'recursos.php'), la usamos. Si no, usamos cadena vacía (misma página).
				$base_path = !is_null($url) ? $url : '';
				// 5. Detectamos el conector correcto (? o &)
				// Si $base_path ya tiene '?' (ej: 'recursos.php?v=1'), usamos '&'. Si no, '?'.
				$conector = (strpos($base_path, '?') !== false) ? '&' : '?';
				// 6. URL Final base para los botones
				// Ejemplo resultante: "recursos.php?tipo=PDF&titulo=Hola&pag="
				$url_base = !empty($query_string) ? $base_path . $conector . $query_string . '&pag=' : $base_path . $conector . 'pag=';
				//---------------------------------------------------------
				// Configuración por versión Bootstrap
				$bootstrap_config = [
					4 => ['disabled_tag' => 'span', 'disabled_attrs' => '', 'active_class' => 'active'],
					5 => ['disabled_tag' => 'a', 'disabled_attrs' => 'tabindex="-1" aria-disabled="true"', 'active_class' => 'active'],
				];
				//---------------------------------------------------------
				$config = $bootstrap_config[$bootstrap_v] ?? $bootstrap_config[4];
				// ---------------------------------------------------------
				// CONSTRUCCIÓN HTML
				// ---------------------------------------------------------
				$html = '<ul class="pagination justify-content-end">';
				//---------------------------------------------------------
				// Función auxiliar para generar botones
				$renderBtn = function($label, $link = null, $disabled = false, $icon = null) use ($config) {
					$icon_html = $icon ? '<i class="' . $icon . '"></i>' : '';
					if ($disabled) {
						return '<li class="page-item disabled"><' . $config['disabled_tag'] . ' class="page-link" ' . $config['disabled_attrs'] . '>' . $icon_html . $label . '</' . $config['disabled_tag'] . '></li>';
					}
					return '<li class="page-item"><a class="page-link" href="' . $link . '">' . $icon_html . $label . '</a></li>';
				};
				//---------------------------------------------------------
				// Botones
				$html .= $renderBtn('', $url_base . base64_encode(1), $pag <= 1, 'fas fa-angle-double-left');
				$html .= $renderBtn('', $url_base . base64_encode($pag - 1), $pag <= 1, 'fas fa-angle-left');
				//---------------------------------------------------------
				// Rango numérico
				$rango_inicio = max(1, $pag - 2);
				$rango_fin = min($total_paginas, $pag + 2);
				if ($rango_inicio > 1) {
					$html .= $renderBtn('1', $url_base . base64_encode(1));
					if ($rango_inicio > 2) $html .= $renderBtn('...', null, true);
				}
				//---------------------------------------------------------
				for ($i = $rango_inicio; $i <= $rango_fin; $i++) {
					if ($i == $pag) {
						$html .= '<li class="page-item ' . $config['active_class'] . '"><span class="page-link">' . $i . '</span></li>';
					} else {
						$html .= $renderBtn($i, $url_base . base64_encode($i));
					}
				}
				//---------------------------------------------------------
				if ($rango_fin < $total_paginas) {
					if ($rango_fin < $total_paginas - 1) $html .= $renderBtn('...', null, true);
					$html .= $renderBtn($total_paginas, $url_base . base64_encode($total_paginas));
				}
				//---------------------------------------------------------
				$html .= $renderBtn('', $url_base . base64_encode($pag + 1), $pag >= $total_paginas, 'fas fa-angle-right');
				$html .= $renderBtn('', $url_base . base64_encode($total_paginas), $pag >= $total_paginas, 'fas fa-angle-double-right');
				//---------------------------------------------------------
				$html .= '</ul>';
				//---------------------------------------------------------
				$data->inf = $html;
				//---------------------------------------------------------
				return $data;
			}
		//---------------------------------------------------------ADD Y EDIT
			public function db_add($dt, $json, $db='con', $_db_type=null){
				// 1. Gestión de Tipo
				$tipo_actual = $this->db_type;
				//---------------------------------------------------------
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				// 2. Inicializar Respuesta
				$data = new stdClass();
				$data->result = false;
				$data->error = null;
				$data->mensaje = "";
				$data->inf = isset($json->danger) ? $json->danger : 'null';
				//---------------------------------------------------------
				// 3. Validación de Entrada
				if(empty($json->tname)){
					$data->mensaje = "No existe el nombre de la tabla. Datos: " . json_encode($json);
					//---------------------------------------------------------
					if (!is_null($_db_type)) $this->load_other_type($tipo_actual);
					return $data;
				}
				//---------------------------------------------------------
				// 4. Generar SQL
				// IMPORTANTE: El último parámetro es 'false' para NO generar RETURNING/OUTPUT.
				// Solo queremos insertar.
				$sql = $this->get_sql($json->tname, $dt, 1, null, null, false);
				//---------------------------------------------------------
				// 5. Ejecutar (Usamos db_exec_sql)
				// Como no pedimos retorno, para todos los motores (MySQL, PG, SQLSrv, Oracle)
				// esto se comportará como una ejecución que devuelve true/false sin filas.
				$exec = $this->db_exec_sql($sql, true, $db, $this->db_type);
				//---------------------------------------------------------
				// 6. Procesar Resultados
				if ($exec->result) {
					$data->result = true;
					$data->inf = isset($json->success) ? $json->success : 'Success';
					$data->mensaje = "Registro agregado exitosamente.";
				} else {
					$data->result = false;
					$data->error = $exec->error;
					//---------------------------------------------------------
					// 7. Detección inteligente de errores (Duplicados)
					// Analizamos el mensaje de error devuelto por el motor
					$err_msg = strtolower((string)$exec->error);
					//---------------------------------------------------------
					if (strpos($err_msg, 'duplicate') !== false ||  // MySQL
						strpos($err_msg, 'unique') !== false ||	 // Postgres / Oracle
						strpos($err_msg, 'violation') !== false) {  // SQL Server
						//---------------------------------------------------------
						$data->mensaje = "No se logró agregar, Ya existe un valor igual.";
					} else {
						$data->mensaje = "No se logró agregar los datos.";
					}
				}
				//---------------------------------------------------------
				// 8. Debug opcional
				$data->sql = $sql; 
				//---------------------------------------------------------
				if(isset($json->test) && $json->test == true){
					$data->input = $json;
				}
				//---------------------------------------------------------
				// 9. Restaurar tipo
				if (!is_null($_db_type)) {
					$this->load_other_type($tipo_actual);
				}
				//---------------------------------------------------------
				return $data;
			}
			public function db_add_ret($dt, $json, $db='con', $_db_type=null){
				// 1. Gestión de Tipo
				$tipo_actual = $this->db_type;
				//---------------------------------------------------------
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				// Carga de funciones mapeadas (Conservada como pediste)
				$fc_query=$this->db_query;$fc_parse=$this->db_parse;$fc_exect=$this->db_exect;$fc_error=$this->db_error;$fc_fetch=$this->db_fetch;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
				//---------------------------------------------------------
				// 2. Inicializar Respuesta
				$data = new stdClass();
				$data->result = false;
				$data->pid = 0;
				$data->error = null;
				$data->mensaje = "";
				$data->inf = isset($json->danger) ? $json->danger : 'null';
				//---------------------------------------------------------
				// 3. Validación de Entrada
				if(empty($json->tname) || empty($json->tid)){
					$data->mensaje = "Faltan datos (tname o tid). Datos: " . json_encode($json);
					//---------------------------------------------------------
					if (!is_null($_db_type)) $this->load_other_type($tipo_actual);
					return $data;
				}
				//---------------------------------------------------------
				// 4. Generar SQL (Con RETURNING/OUTPUT habilitado)
				// get_sql tipo 1 con true al final añade RETURNING id / OUTPUT INSERTED.id
				$sql = $this->get_sql($json->tname, $dt, 1, $json->tid, null, true);
				//---------------------------------------------------------
				// 5. Conexión Manual (Necesaria para mysqli_insert_id)
				// No podemos usar db_exec_sql porque necesitamos el objeto de conexión $_cc
				// para pedir el último ID en MySQL.
				$schu_val = defined('SCHU') ? SCHU : null;
				$_cc = $this->connect($schu_val, $db, $this->db_type);
				//---------------------------------------------------------
				if (!$_cc) {
					$data->error = "Error de Conexión";
					return $data;
				}
				//---------------------------------------------------------
				// 6. Ejecución Específica
				// Replicamos la lógica de ejecución de db_exec_sql pero mantenemos el control
				$res = null;
				//---------------------------------------------------------
				// --- Lógica de Ejecución ---
				switch($this->db_type){
					case 'oci_': 
						$stmt = @$fc_parse($_cc, $sql);
						//---------------------------------------------------------
						if ($stmt && @$fc_exect($stmt)) {
							$res = $stmt; // Éxito, pero Oracle no devuelve ID aquí
						} else {
							$e = $fc_error($stmt ? $stmt : $_cc);
							$data->error = is_array($e) ? $e['message'] : $e;
						}
					break;
					case 'sqlsrv_':
						$res = @$fc_query($_cc, $sql); // Sin scrollable para INSERT
						if (!$res) {
							$errors = $fc_error();
							if(is_array($errors)) foreach($errors as $e) $data->error .= $e['message'];
						}
					break;
					default: // MySQL / PG
						$res = @$fc_query($_cc, $sql);
						if (!$res) $data->error = $fc_error($_cc);
					break;
				}
				//---------------------------------------------------------
				// 7. Procesar Resultado y Obtener ID
				if ($res || ($this->db_type == 'mysqli_' && $res === true)) { // MySQL devuelve true en insert
					$data->result = true;
					$data->inf = isset($json->success) ? $json->success : 'Success';
					$data->mensaje = "Registro agregado exitosamente.";
					// --- ESTRATEGIA DE OBTENCIÓN DE ID ---
					if ($this->db_type == 'mysqli_') {
						// MySQL: Función nativa (Es lo más seguro y correcto)
						$data->pid = mysqli_insert_id($_cc);
					} elseif ($this->db_type == 'pg_') {
						// Postgres: Leemos el RETURNING
						$row = $fc_assoc($res);
						//---------------------------------------------------------
						if ($row && isset($row[$json->tid])) {
							$data->pid = $row[$json->tid];
						}
					} elseif ($this->db_type == 'sqlsrv_') {
						// SQL Server: Leemos el OUTPUT
						// SQLSrv requiere next_result a veces si hay triggers
						sqlsrv_next_result($res);
						//---------------------------------------------------------
						$row = $fc_array($res, SQLSRV_FETCH_ASSOC);
						if ($row && isset($row[$json->tid])) {
							$data->pid = $row[$json->tid];
						}
					} elseif ($this->db_type == 'oci_') {
						// Oracle: Fallback SELECT MAX (No es ideal en concurrencia alta, pero funciona)
						// Lo ideal sería usar RETURNING INTO, pero requiere binding complejo.
						$sql_max = "SELECT MAX({$json->tid}) AS MAXID FROM {$json->tname}";
						$stmt_max = $fc_parse($_cc, $sql_max);
						$fc_exect($stmt_max);
						$row = $fc_assoc($stmt_max);
						if ($row) {
							$data->pid = $row['MAXID']; // Oracle devuelve keys en mayúsculas
						}
					}
					//---------------------------------------------------------
					// Si después de todo pid sigue siendo 0 (falló insert_id o returning)
					// Aplicamos tu fallback de SELECT MAX para MySQL también si falló insert_id
					if (empty($data->pid)) {
						// Fallback Universal: SELECT MAX
						$sql_max = "SELECT MAX({$json->tid}) as max_id FROM {$json->tname}";
						// Usamos nuestra función auxiliar para no repetir lógica
						$res_max = $this->db_exec_sql($sql_max, true, $db, $this->db_type);
						if ($res_max->result && isset($res_max->max_id)) {
							$data->pid = $res_max->max_id;
						} else {
							$data->mensaje .= " (Advertencia: No se pudo recuperar el ID)";
						}
					}
				} else {
					// Manejo de Errores (Duplicados, etc)
					$data->result = false;
					//---------------------------------------------------------
					$err_msg = strtolower((string)$data->error);
					if (strpos($err_msg, 'duplicate') !== false || strpos($err_msg, 'unique') !== false || strpos($err_msg, 'violation') !== false) {
						$data->mensaje = "No se logró agregar, Ya existe un valor igual.";
					} else {
						$data->mensaje = "No se logró agregar los datos.";
					}
				}
				//---------------------------------------------------------
				// 8. Debug
				if(isset($json->test) && $json->test==true){
					$data->sql = $sql;
					$data->input = $json;
				}
				//---------------------------------------------------------
				// 9. Cerrar y Restaurar
				// $fc_close($_cc); // Opcional
				if (!is_null($_db_type)) {
					$this->load_other_type($tipo_actual);
				}
				//---------------------------------------------------------
				return $data;
			}
			public function db_add_all($dt, $json, $db='con', $_db_type=null){
				// 1. Gestión de Tipo
				$tipo_actual = $this->db_type;
				//---------------------------------------------------------
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				// 2. Carga de funciones mapeadas (Conservada como pediste)
				$fc_query=$this->db_query;$fc_parse=$this->db_parse;$fc_exect=$this->db_exect;$fc_error=$this->db_error;$fc_fetch=$this->db_fetch;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
				//---------------------------------------------------------
				// 3. Inicializar Respuesta
				$data = new stdClass();
				$result = array();
				$data->error = null;
				$n = 0; // Total procesados
				$r = 0; // Total agregados con éxito
				//---------------------------------------------------------
				// 4. Validación
				if(empty($json->tname)){
					$result[] = array(
						"result"  => false,
						"mensaje" => "No existe el nombre de la tabla."
					);
					//---------------------------------------------------------
					$data->res = $result;
					//---------------------------------------------------------
					// Restaurar y salir
					if (!is_null($_db_type)) $this->load_other_type($tipo_actual);
					return $data;
				}
				//---------------------------------------------------------
				// 5. Conexión (UNA SOLA VEZ)
				$schu_val = defined('SCHU') ? SCHU : null;
				$_cc = $this->connect($schu_val, $db, $this->db_type);
				//---------------------------------------------------------
				if (!$_cc) {
					$data->error = "Error de Conexión Global";
					return $data;
				}
				//---------------------------------------------------------
				// 6. Bucle de Inserción
				foreach ($dt as $fila) {
					// Validamos que el campo clave no sea nulo (según tu lógica original)
					// $json->t_camp es el nombre del campo obligatorio
					if (!empty($json->t_camp) && isset($fila[$json->t_camp]) && $fila[$json->t_camp] !== null) {
						//---------------------------------------------------------
						// Generamos SQL para esta fila (Insert simple)
						$sql = $this->get_sql($json->tname, $fila, 1, null, null, false);
						//---------------------------------------------------------
						$exito = false;
						$error_msg = "";
						//---------------------------------------------------------
						// Ejecución manual reutilizando la conexión abierta
						// (No usamos db_exec_sql para no reconectar por cada fila)
						if ($this->db_type == 'oci_') {
							$stmt = @$fc_parse($_cc, $sql);
							//---------------------------------------------------------
							if ($stmt && @$fc_exect($stmt)) {
								$exito = true;
							} else {
								$e = $fc_error($stmt ? $stmt : $_cc);
								$error_msg = is_array($e) ? $e['message'] : $e;
							}
						} elseif ($this->db_type == 'sqlsrv_') {
							$res = @$fc_query($_cc, $sql);
							if ($res) {
								$exito = true;
							} else {
								$errors = $fc_error();
								if(is_array($errors)) foreach($errors as $e) $error_msg .= $e['message'];
							}
						} else { // MySQL / PG
							$res = @$fc_query($_cc, $sql);
							if ($res) {
								$exito = true;
							} else {
								$error_msg = $fc_error($_cc);
							}
						}
						//---------------------------------------------------------
						// Procesar resultado de la fila
						if ($exito) {
							$result[] = array(
								"result"  => true,
								"inf"	 => isset($json->success) ? $json->success : 'Success',
								"mensaje" => "Registro agregado exitosamente."
							);
							$r++;
						} else {
							// Analizar error (Duplicado)
							$err_str = strtolower((string)$error_msg);
							if (strpos($err_str, 'duplicate') !== false || strpos($err_str, 'unique') !== false || strpos($err_str, 'violation') !== false) {
								$msg_final = "No se logró agregar, Ya existe un valor igual.";
							} else {
								$msg_final = "No se logró agregar los datos. Error: " . $error_msg;
							}
							//---------------------------------------------------------
							$result[] = array(
								"result"  => false,
								"inf"	 => isset($json->danger) ? $json->danger : 'Danger',
								"mensaje" => $msg_final
							);
						}
					} else {
						// Campo obligatorio vacío
						$result[] = array(
							"result"  => false,
							"inf"	 => isset($json->danger) ? $json->danger : 'Danger',
							"mensaje" => "El campo obligatorio (" . $json->t_camp . ") está vacío. Fila: " . ($n + 1)
						);
					}
					//---------------------------------------------------------
					$n++;
				}
				//---------------------------------------------------------
				// 7. Finalizar
				// Si son muchos datos (>1000), devolvemos solo el primero para no saturar la memoria/json
				$data->res	  = ($n > 1000) ? array($result[0]) : $result;
				$data->rows	 = $n;	 // Total procesados
				$data->rows_add = $r;	 // Total insertados
				//---------------------------------------------------------
				if ($r > 0) {
					$data->add_one = $result[0]; // Muestra del primer resultado
				}
				//---------------------------------------------------------
				// Debug
				if(isset($json->test) && $json->test==true){
					$data->sql_last = isset($sql) ? $sql : ''; // Último SQL generado
					$data->input = $json;
				}
				//---------------------------------------------------------
				// Cerrar y Restaurar
				$fc_close($_cc);
				//---------------------------------------------------------
				if (!is_null($_db_type)) {
					$this->load_other_type($tipo_actual);
				}
				//---------------------------------------------------------
				return $data;
			}
			public function db_edit($dt, $json, $db='con', $_db_type=null){
				// 1. Gestión de Tipo
				$tipo_actual = $this->db_type;
				//---------------------------------------------------------
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				// 2. Inicializar Respuesta
				$data = new stdClass();
				$data->result = false;
				$data->error = null;
				$data->mensaje = "";
				$data->inf = isset($json->danger) ? $json->danger : 'null';
				//---------------------------------------------------------
				// 3. Definir Verbos de Acción (Mensajería Dinámica)
				$action = isset($json->success) ? $json->success : 'edit';
				switch ($action) {
					case "edit":	  $success = "modificó";	$danger = "modificar";	break;
					case "drop":	  $success = "eliminó";	 $danger = "eliminar";	 break;
					case "active":	$success = "activó";	  $danger = "activar";	  break;
					case "desactive": $success = "desactivó";   $danger = "desactivar";   break;
					case "lock":	  $success = "bloqueó";	 $danger = "bloquear";	 break;
					case "unlock":	$success = "desbloqueó";  $danger = "desbloquear";  break;
					default:		  $success = "actualizó";   $danger = "actualizar";   break;
				}
				//---------------------------------------------------------
				// 4. Validación de Entrada
				if(empty($json->tname) || empty($json->tid) || !isset($json->pid)){
					$data->mensaje = "Faltan datos (tname, tid, pid). Datos: " . json_encode($json);
					//---------------------------------------------------------
					if (!is_null($_db_type)) $this->load_other_type($tipo_actual);
					return $data;
				}
				//---------------------------------------------------------
				// 5. Generar SQL (Tipo 2: UPDATE Standard)
				// get_sql(table, data, 2, tid, pid)
				$sql = $this->get_sql($json->tname, $dt, 2, $json->tid, $json->pid);
				//---------------------------------------------------------
				// 6. Ejecutar (Usamos db_exec_sql)
				// Al ser un UPDATE, db_exec_sql retornará result=true si no hubo error de sintaxis/conexión.
				// Para Oracle/MySQL devolverá cant=filas afectadas (si el driver lo soporta en update).
				$exec = $this->db_exec_sql($sql, true, $db, $this->db_type);
				//---------------------------------------------------------
				// 7. Procesar Resultados
				if ($exec->result) {
					$data->result = true;
					$data->inf = isset($json->success) ? $json->success : 'edit'; // Retornamos el tipo de acción
					$data->mensaje = "Registro " . $success . " exitosamente.";
					//---------------------------------------------------------
					// Opcional: Verificar si realmente se afectaron filas (si exec->cant > 0)
					// Si cant es 0, significa que el ID no existía o los datos eran iguales.
					// Pero generalmente devolvemos "éxito" porque el query corrió bien.
				} else {
					$data->result = false;
					$data->error = $exec->error;
					//---------------------------------------------------------
					// Detección de Errores (Duplicados al editar, etc)
					$err_msg = strtolower((string)$exec->error);
					if (strpos($err_msg, 'duplicate') !== false || strpos($err_msg, 'unique') !== false || strpos($err_msg, 'violation') !== false) {
						$data->mensaje = "No se logró " . $danger . ", Ya existe un valor igual.";
					} else {
						$data->mensaje = "No se logró " . $danger . " el registro.";
					}
				}
				//---------------------------------------------------------
				// 8. Debug
				if(isset($json->test) && $json->test == true){
					$data->sql = $sql;
					$data->input = $json;
				}
				//---------------------------------------------------------
				// 9. Restaurar tipo
				if (!is_null($_db_type)) {
					$this->load_other_type($tipo_actual);
				}
				//---------------------------------------------------------
				return $data;
			}
			public function db_edit_string($dt, $json, $db='con', $_db_type=null){
				// 1. Gestión de Tipo
				$tipo_actual = $this->db_type;
				//---------------------------------------------------------
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				// 2. Inicializar Respuesta
				$data = new stdClass();
				$data->result = false;
				$data->error = null;
				$data->mensaje = "";
				$data->inf = isset($json->danger) ? $json->danger : 'null';
				//---------------------------------------------------------
				// 3. Definir Verbos de Acción (Mensajería Dinámica)
				$action = isset($json->success) ? $json->success : 'edit';
				switch ($action) {
					case "edit":	  $success = "modificó";	$danger = "modificar";	break;
					case "drop":	  $success = "eliminó";	 $danger = "eliminar";	 break;
					case "active":	$success = "activó";	  $danger = "activar";	  break;
					case "desactive": $success = "desactivó";   $danger = "desactivar";   break;
					case "lock":	  $success = "bloqueó";	 $danger = "bloquear";	 break;
					case "unlock":	$success = "desbloqueó";  $danger = "desbloquear";  break;
					default:		  $success = "actualizó";   $danger = "actualizar";   break;
				}
				//---------------------------------------------------------
				// 4. Validación de Entrada
				if(empty($json->tname) || empty($json->tid) || !isset($json->pid)){
					$data->mensaje = "Faltan datos (tname, tid, pid). Datos: " . json_encode($json);
					//---------------------------------------------------------
					if (!is_null($_db_type)) $this->load_other_type($tipo_actual);
					return $data;
				}
				//---------------------------------------------------------
				// 5. Generar SQL (Tipo 7: UPDATE DONDE ID/CAMPO - VALOR (STRING))
				// get_sql(table, data, 7, tid, pid)
				$sql = $this->get_sql($json->tname, $dt, 7, $json->tid, $json->pid);
				//---------------------------------------------------------
				// 6. Ejecutar (Usamos db_exec_sql)
				// Al ser un UPDATE, db_exec_sql retornará result=true si no hubo error de sintaxis/conexión.
				// Para Oracle/MySQL devolverá cant=filas afectadas (si el driver lo soporta en update).
				$exec = $this->db_exec_sql($sql, true, $db, $this->db_type);
				//---------------------------------------------------------
				// 7. Procesar Resultados
				if ($exec->result) {
					$data->result = true;
					$data->inf = isset($json->success) ? $json->success : 'edit'; // Retornamos el tipo de acción
					$data->mensaje = "Registro " . $success . " exitosamente.";
					//---------------------------------------------------------
					// Opcional: Verificar si realmente se afectaron filas (si exec->cant > 0)
					// Si cant es 0, significa que el ID no existía o los datos eran iguales.
					// Pero generalmente devolvemos "éxito" porque el query corrió bien.
				} else {
					$data->result = false;
					$data->error = $exec->error;
					//---------------------------------------------------------
					// Detección de Errores (Duplicados al editar, etc)
					$err_msg = strtolower((string)$exec->error);
					if (strpos($err_msg, 'duplicate') !== false || strpos($err_msg, 'unique') !== false || strpos($err_msg, 'violation') !== false) {
						$data->mensaje = "No se logró " . $danger . ", Ya existe un valor igual.";
					} else {
						$data->mensaje = "No se logró " . $danger . " el registro.";
					}
				}
				//---------------------------------------------------------
				// 8. Debug
				if(isset($json->test) && $json->test == true){
					$data->sql = $sql;
					$data->input = $json;
				}
				//---------------------------------------------------------
				// 9. Restaurar tipo
				if (!is_null($_db_type)) {
					$this->load_other_type($tipo_actual);
				}
				//---------------------------------------------------------
				return $data;
			}
			public function db_edit_all($dt, $json, $db='con', $_db_type=null){
				// 1. Gestión de Tipo
				$tipo_actual = $this->db_type;
				//---------------------------------------------------------
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				// 2. Carga de funciones mapeadas
				$fc_query = $this->db_query; 
				$fc_parse = $this->db_parse; 
				$fc_exect = $this->db_exect; 
				$fc_error = $this->db_error;
				$fc_close = $this->db_close;
				//---------------------------------------------------------
				// 3. Inicializar Respuesta
				$data = new stdClass();
				$result = array();
				$data->error = null;
				$n = 0; // Total procesados
				$r = 0; // Total editados con éxito
				//---------------------------------------------------------
				// 4. Validación Global
				if(empty($json->tname) || empty($json->tid) || empty($json->pid)){
					$result[] = array(
						"result"  => false,
						"mensaje" => "Faltan datos de configuración (tname, tid o pid)."
					);
					//---------------------------------------------------------
					$data->res = $result;
					//---------------------------------------------------------
					if (!is_null($_db_type)) $this->load_other_type($tipo_actual);
					return $data;
				}
				//---------------------------------------------------------
				// 5. Conexión (UNA SOLA VEZ)
				$schu_val = defined('SCHU') ? SCHU : null;
				$_cc = $this->connect($schu_val, $db, $this->db_type);
				//---------------------------------------------------------
				if (!$_cc) {
					$data->error = "Error de Conexión Global";
					return $data;
				}
				//---------------------------------------------------------
				// 6. Bucle de Edición
				foreach ($dt as $fila) {
					// Validamos que exista el campo que se va a editar o el ID
					// $json->t_camp es opcional para validación extra, si no está, asumimos que intentamos editar todo
					$valid_row = true;
					//---------------------------------------------------------
					if (!empty($json->t_camp) && (!isset($fila[$json->t_camp]) || $fila[$json->t_camp] === null)) {
						$valid_row = false;
					}
					//---------------------------------------------------------
					if ($valid_row) {
						// Generamos SQL UPDATE para esta fila
						// Nota: $json->pid es el NOMBRE del campo ID en el array $fila que contiene el valor del ID
						// Ejemplo: Si en la DB es 'id_usuario', y en el array $fila es 'id_user', entonces $json->pid = 'id_user'
						// Pero get_sql espera el VALOR del ID en el último parámetro.
						// CORRECCIÓN LÓGICA RESPECTO A TU CÓDIGO ORIGINAL:
						// En db_edit (individual), $json->pid es el VALOR.
						// En db_edit_all (masivo), $json->pid parece ser la CLAVE en $fila que tiene el ID.
						// Asumo que $fila[$json->pid] contiene el ID de esa fila específica.
						$id_valor = isset($fila[$json->pid]) ? $fila[$json->pid] : null;
						//---------------------------------------------------------
						if ($id_valor) {
							$sql = $this->get_sql($json->tname, $fila, 2, $json->tid, $id_valor);
							//---------------------------------------------------------
							$exito = false;
							$error_msg = "";
							//---------------------------------------------------------
							// Ejecución manual (Reutilizando conexión)
							if ($this->db_type == 'oci_') {
								$stmt = @$fc_parse($_cc, $sql);
								//---------------------------------------------------------
								if ($stmt && @$fc_exect($stmt)) {
									$exito = true;
								} else {
									$e = $fc_error($stmt ? $stmt : $_cc);
									$error_msg = is_array($e) ? $e['message'] : $e;
								}
							} elseif ($this->db_type == 'sqlsrv_') {
								$res = @$fc_query($_cc, $sql);
								//---------------------------------------------------------
								if ($res) {
									$exito = true;
								} else {
									$errors = $fc_error();
									if(is_array($errors)) foreach($errors as $e) $error_msg .= $e['message'];
								}
							} else { // MySQL / PG
								$res = @$fc_query($_cc, $sql);
								//---------------------------------------------------------
								if ($res) {
									$exito = true;
								} else {
									$error_msg = $fc_error($_cc);
								}
							}
							//---------------------------------------------------------
							// Procesar resultado de la fila
							if ($exito) {
								$result[] = array(
									"result"  => true,
									"inf"	 => isset($json->success) ? $json->success : 'Success',
									"mensaje" => "Registro editado exitosamente.",
									"fila"	=> isset($json->t_camp) ? $fila[$json->t_camp] : 'ID: '.$id_valor
								);
								//---------------------------------------------------------
								$r++;
							} else {
								$err_str = strtolower((string)$error_msg);
								//---------------------------------------------------------
								if (strpos($err_str, 'duplicate') !== false || strpos($err_str, 'unique') !== false || strpos($err_str, 'violation') !== false) {
									$msg_final = "No se logró editar, Ya existe un valor igual.";
								} else {
									$msg_final = "No se logró editar los datos. Error: " . $error_msg;
								}
								//---------------------------------------------------------
								$result[] = array(
									"result"  => false,
									"inf"	 => isset($json->danger) ? $json->danger : 'Danger',
									"mensaje" => $msg_final,
									"fila"	=> $fila
								);
							}
						} else {
							$result[] = array(
								"result"  => false,
								"mensaje" => "Fila sin ID (" . $json->pid . ") válido.",
								"fila"	=> $fila
							);
						}
					} else {
						$result[] = array(
							"result"  => false,
							"inf"	 => isset($json->danger) ? $json->danger : 'Danger',
							"mensaje" => "El campo obligatorio (" . $json->t_camp . ") está vacío. Fila: " . ($n + 1)
						);
					}
					//---------------------------------------------------------
					$n++;
				}
				//---------------------------------------------------------
				// 7. Finalizar
				$data->res	   = ($n > 1000) ? array($result[0]) : $result;
				$data->rows	  = $n;
				$data->rows_edit = $r;
				//---------------------------------------------------------
				if ($r > 0) {
					$data->edit_one = $result[0];
				}
				//---------------------------------------------------------
				// Debug
				if(isset($json->test) && $json->test==true){
					$data->sql_last = isset($sql) ? $sql : '';
					$data->input = $json;
				}
				//---------------------------------------------------------
				// Cerrar y Restaurar
				$fc_close($_cc);
				if (!is_null($_db_type)) {
					$this->load_other_type($tipo_actual);
				}
				//---------------------------------------------------------
				return $data;
			}
		//---------------------------------------------------------PREDETERMINADO
			public function get_datos($pid, $type, $db='con', $_db_type=null){
				// 1. Gestión de Tipo
				$tipo_actual = $this->db_type;
				//---------------------------------------------------------
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				$data = new stdClass();
				$sql = null;
				//---------------------------------------------------------
				// 2. Definir Sintaxis de LIMIT según el motor
				// Esto es necesario porque SQL Server no entiende 'LIMIT' y MySQL no entiende 'TOP'
				$top = "";   // Va después del SELECT (Para SQL Server)
				$limit = ""; // Va al final del Query (Para MySQL, PG, Oracle)
				//---------------------------------------------------------
				if ($this->db_type == 'sqlsrv_') {
					$top = " TOP 1 ";
				} elseif ($this->db_type == 'oci_') {
					// Oracle 12c en adelante soporta sintaxis ANSI. 
					// Si usas una versión muy vieja (11g), cámbialo por: " AND ROWNUM <= 1 "
					$limit = " FETCH FIRST 1 ROWS ONLY "; 
				} else {
					// MySQL y PostgreSQL
					$limit = " LIMIT 1 "; 
				}
				//---------------------------------------------------------
				// 3. Sanitización básica de PID (Para evitar inyección en concatenación directa)
				// Si es numérico lo dejamos, si es texto escapamos comillas simples
				$pid_clean = is_numeric($pid) ? $pid : str_replace("'", "''", $pid);
				//---------------------------------------------------------
				// 4. Construcción de SQL Universal
				switch ($type) {
					case 'user':
						// Observemos dónde se coloca $top y $limit
						$sql = "SELECT {$top} u.*, tu.nombre_t FROM usuarios u INNER JOIN tipos_usuarios tu ON u.id_tipo=tu.id_tipo WHERE id_user={$pid_clean} {$limit};";
					break;
					case 'placa':
						$sql = "SELECT {$top} * FROM scheme_name.unidades WHERE placa LIKE '{$pid_clean}' {$limit};";
					break;
					case 'clie':
						$sql = "SELECT {$top} * FROM scheme_name.v_clients WHERE id_int LIKE '{$pid_clean}' {$limit};";
					break;
					default:
						$sql = null;
					break;
				}
				//---------------------------------------------------------
				// 5. Ejecución (Usamos db_exec_sql que ya mapea una sola fila al objeto)
				if (!is_null($sql)) {
					$data = $this->db_exec_sql($sql, true, $db, $this->db_type);
				} else {
					$data->result = false;
					$data->mensaje = "Tipo de consulta '{$type}' no definido.";
				}
				//---------------------------------------------------------
				// 6. Restaurar tipo original
				if (!is_null($_db_type)) {
					$this->load_other_type($tipo_actual);
				}
				//---------------------------------------------------------
				return $data;
			}
		//---------------------------------------------------------
			public function get_sql(
				$this_table, //nombre de tabla
				$dt, //array con los datos. El nombre de las Key debe ser igual al nombre de los campos en la tabla
				$tipo=1, //Tipo de sentencia: 1 para INSERT / 2 para UPDATE / 3 para CALL
				$this_tid=null, //Nombre del campo Primary Key(PK) de la Tabla. Solo usar para UPDATE
				$json_pid=null, //valor del PK a editar. Solo usar para UPDATE
				$return=false, //este campo indica si se retorna o no el ID del insert
				$adic=null //campos adicionales en sentencia WHERE del UPDATE
			){
				$sql = "";
				//---------------------------------------------------------
				// Helper local para escapar comillas simples (SQL Injection básico)
				// Reemplaza ' por '' (estándar SQL)
				$clean = function($val) {
					return str_replace("'", "''", $val);
				};
				//---------------------------------------------------------
				switch ($tipo) {
					case 1://GENERAR SENTENCIA INSERT
						// Manejo especial SQL SERVER para RETURNING (OUTPUT)
						$output_clause = "";
						$returning_clause = "";
						//---------------------------------------------------------
						if ($return) {
							if ($this->db_type == 'sqlsrv_') {
								$output_clause = " OUTPUT INSERTED.{$this_tid} ";
							} elseif ($this->db_type == 'pg_') {
								$returning_clause = " RETURNING {$this_tid} ";
							}
						}
						//---------------------------------------------------------
						$sql = "INSERT INTO {$this_table} (";
						$vals = "";
						//---------------------------------------------------------
						foreach ($dt as $key => $value) {
							$sql .= "{$key}, ";
							$vals .= "'" . $clean($value) . "', ";
						}
						//---------------------------------------------------------
						$sql = substr($sql, 0, -2) . ") {$output_clause} VALUES (" . substr($vals, 0, -2) . ") {$returning_clause};";
					break;
					case 3://GENERRAR SENTENCIA PARA LLAMAR PROCEDIMIENTOS ALMACENADOS
						$sql = "SELECT {$this_table} ( ";
						//---------------------------------------------------------
						foreach ($dt as $value) {
							$sql .= "'" . $clean($value) . "', ";
						}
						//---------------------------------------------------------
						$sql = substr($sql, 0, -2) . " );";
					break;
					case 4://GENERRAR SENTENCIA PARA BUSCAR EN TABLA CON CAMPOS DADOS
						$sql = "SELECT * FROM {$this_table} WHERE ";
						//-----------valores----------------
							foreach ($dt as $key => $value) {
								switch ($key) {
									case 'created_at':
									case 'id_created':
									case 'updated_at':
									case 'id_updated':
									case 'drop_at':
									case 'id_drop':
									case 'motivo_drop':
									case 'status':
									break;
									default:
										$sql .= "{$key}='" . $clean($value) . "' AND ";
									break;
								}
							}
						//-----------fin-valores------------
						$sql = substr($sql, 0, -5) . ";"; // Quitamos ' AND '
					break;
					case 5://GENERAR SENTENCIA UPDATE USANDO WHERE CON KEY_ID DISTINTO INT
						$sql = "UPDATE {$this_table} SET ";
						//-----------campos-valores----------------
							foreach ($dt as $key => $value) {
								$sql .= "{$key}='" . $clean($value) . "', ";
							}
						//-----------fin-campos-valores------------
						$sql = substr($sql, 0, -2) . " WHERE ";
						//------------campos-adicionale------------
							if (!is_null($adic)) {
								$sql .= "{$adic} AND ";
							}
						//-----------fin-campos-adicionale---------
						$sql .= "{$this_tid}={$json_pid};";
					break;
					case 6://GENERRAR SENTENCIA PARA LISTAR TODO
						$sql = "SELECT * FROM {$this_table};";
					break;
					case 7://GENERAR SENTENCIA UPDATE DONDE ID/CAMPO - VALOR (STRING)
						$sql = "UPDATE {$this_table} SET ";
						//-----------campos-valores----------------
							foreach ($dt as $key => $value) {
								$sql .= "{$key}='" . $clean($value) . "', ";
							}
						//-----------fin-campos-valores------------
						$sql = substr($sql, 0, -2) . " WHERE ";
						//------------campos-adicionale------------
							if (!is_null($adic)) {
								$sql .= "{$adic} AND ";
							}
						//-----------fin-campos-adicionale---------
						$sql .= "{$this_tid} LIKE '" . $clean($json_pid) . "';";
					break;
					case 8://GENERRAR SENTENCIA PARA SELECT EN TABLA POR ID/CAMPO - VALOR (INT)
						$sql = "SELECT * FROM {$this_table} ";
						//$sql = "SELECT t1.*, c.nombre_u AS user_add, c.correo_u AS mail_add, e.nombre_u AS user_edit, e.correo_u AS mail_edit FROM ".$this_table." t1 ";
							//$sql .= " LEFT OUTER JOIN scheme_name.view_users_all c ON t1.id_created=c.id_usuario ";
							//$sql .= " LEFT OUTER JOIN scheme_name.view_users_all e ON t1.id_updated=e.id_usuario ";
						$sql .= " WHERE ";
						//------------campos-adicionale------------
							if (!is_null($adic)) {
								$sql .= "{$adic} AND ";
							}
						//-----------fin-campos-adicionale---------
						$sql .= "{$this_tid}={$json_pid};";
					break;
					case 9://GENERRAR SENTENCIA PARA SELECT EN TABLA POR ID/CAMPO - VALOR (STRING)
						$sql = "SELECT * FROM {$this_table} WHERE ";
						//------------campos-adicionale------------
							if (!is_null($adic)) {
								$sql .= "{$adic} AND ";
							}
						//-----------fin-campos-adicionale---------
						$sql .= "{$this_tid} LIKE '" . $clean($json_pid) . "';";
					break;
					case 10://GENERRAR SENTENCIA PARA BUSCAR EN TABLA LOS CAMPOS DADOS
						$sql = "SELECT ";
						//-----------valores----------------
							foreach ($dt as $value) {
								$sql .= "{$value}, ";
							}
						//-----------fin-valores------------
						$sql = substr($sql, 0, -2) . " FROM {$this_table} WHERE {$this_tid} LIKE '" . $clean($json_pid) . "';";
					break;
					case 11://GENERRAR SENTENCIA PARA SELECT EN TABLA PARA LLAMAR CAMPOS ESPECÍFICOS POR ID/CAMPO - VALOR (INT)
						$sql = "SELECT ";
						//------------campos-adicionale------------
							if (!is_null($dt) && is_array($dt)) {
								foreach ($dt as $value) {
									$sql .= "{$value}, ";
								}
								$sql = substr($sql, 0, -2);
							} else {
								$sql .= "*";
							}
						//-----------fin-campos-adicionale---------
						$sql .= " FROM {$this_table} WHERE ";
						//------------campos-adicionale------------
							if (!is_null($adic)) {
								$sql .= "{$adic} AND ";
							}
						//-----------fin-campos-adicionale---------
						$sql .= "{$this_tid}={$json_pid};";
					break;
					case 12://GENERRAR SENTENCIA PARA SELECT EN TABLA POR ID/CAMPO - VALOR (INT) - SIN USUARIOS
						$sql = "SELECT * FROM {$this_table} WHERE ";
						//------------campos-adicionale------------
							if (!is_null($adic)) {
								$sql .= "{$adic} AND ";
							}
						//-----------fin-campos-adicionale---------
						$sql .= "{$this_tid}={$json_pid};";
					break;
					default://GENERAR SENTENCIA UPDATE
						$sql = "UPDATE {$this_table} SET ";
						//-----------campos-valores----------------
							foreach ($dt as $key => $value) {
								$sql .= "{$key}='" . $clean($value) . "', ";
							}
						//-----------fin-campos-valores------------
						$sql = substr($sql, 0, -2) . " WHERE {$this_tid}={$json_pid} ";
						//------------campos-adicionale------------
							if (!is_null($adic)) {
								$sql .= $adic;
							}
						//-----------fin-campos-adicionale---------
						$sql .= ";";
					break;
				}
				//----------------------------------
				return $sql;
			}
		//---------------------------------------------------------
	}