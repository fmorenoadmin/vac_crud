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
			private $db_port = '';
			//---------------------------------------
			private $db_name_qas = 'vac_crud';
			private $db_name_prd = 'AQUI_TU_NOMBRE_DATABASE';
			//---------------------------------------
			private $db_user_qas = 'root';
			private $db_user_prd = 'AQUI_TU_USUARIO';
			//---------------------------------------
			private $db_pass_qas = '';
			private $db_pass_prd = 'AQUI_TU_CONTRASEÑA';
		//---------------------------------------------------------
			protected $db_type = DB_TYPE;
			protected $db_conec = NULL;
			protected $db_query = NULL;
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
			public function __construct(){
				$this->db_conec = $this->db_type.'connect';
				$this->db_query = $this->db_type.'query';
				if ($this->db_type == 'mysqli_') {
					$this->db_error = $this->db_type.'error';
				} else {
					$this->db_error = $this->db_type.'last_error';
				}
				if ($this->db_type == 'sqlsrv_') {
					$this->db_fetch = $this->db_type.'fetch';
				}
				$this->db_array = $this->db_type.'fetch_array';
				$this->db_object = $this->db_type.'fetch_object'; // Corrección aquí
				$this->db_assoc = $this->db_type.'fetch_assoc';
				if ($this->db_type == 'mysqli_') {
					$this->db_num_r = 'num_rows';
				}else{
					$this->db_num_r = $this->db_type.'num_rows';
				}
				$this->db_fre_r = $this->db_type.'free_result';
				$this->db_close = $this->db_type.'close';
			}
			public function load_other_type($_db_type){
				$this->db_conec = $_db_type.'connect';
				$this->db_query = $_db_type.'query';
				if ($_db_type == 'mysqli_') {
					$this->db_error = $_db_type.'error';
				} else {
					$this->db_error = $_db_type.'last_error';
				}
				if ($this->_db_type == 'sqlsrv_') {
					$this->db_fetch = $this->_db_type.'fetch';
				}
				$this->db_array = $_db_type.'fetch_array';
				$this->db_object = $_db_type.'fetch_object'; // Corrección aquí
				$this->db_assoc = $_db_type.'fetch_assoc';
				if ($_db_type == 'mysqli_') {
					$this->db_num_r = 'num_rows';
				}else{
					$this->db_num_r = $_db_type.'num_rows';
				}
				$this->db_fre_r = $_db_type.'free_result';
				$this->db_close = $_db_type.'close';
			}
		//---------------------------------------------------------CON
			function connect($schu=null,$db='con',$_db_type=null){
				if (is_null($_db_type)) {
					$fc_conec=$this->db_conec;
					$dt_type = $this->db_type;
				}else{
					$fc_conec=$_db_type.'connect';
					$dt_type = $_db_type;
				}
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
						$con = $fc_conec("host=".$_host." port=".$_port." dbname=".$_name." user=".$_user." password=".$_pass);
						pg_set_client_encoding($con, "UTF8");
					break;
					case 'sqlsrv_'://conexcióna  base de datos SQL Server
						$serverName = $_host."\sqlexpress"; //serverName\instanceName
						//$serverName = $_host."\sqlexpress, 1542"; //serverName\instanceName, portNumber (por defecto es 1433)
						//$connectionInfo = array( "Database"=>"dbName");
						$connectionInfo = array( "Database" => $_name, "UID" => $_user, "PWD" => $_pass);
						$con = $fc_conec($serverName, $connectionInfo);
						return($con);
					break;
					default://conexcióna  base de datos MySQL - Siempre por defecto
						$con = $fc_conec($_host, $_user, $_pass) OR die($_host.' - '.$_user.' - '.$_pass);
						mysqli_select_db($con, $_name);
						$con->set_charset("utf8");
					break;
				}
				//----------------------------------
				return($con);
			}
		//---------------------------------------------------------EXEC
			public function db_exec($sql,$ret_res=true,$db='con',$_db_type=null){
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				$fc_query=$this->db_query;$fc_error=$this->db_error;$fc_fetch=$this->db_fetch;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
				//---------------------------------------------------------
				$data = new stdClass();
				$error = NULL;
				//----------------------------
				$_cc = $this->connect(SCHU,$db,$_db_type);
				//---------------------------------------------------------
				$res = $fc_query($_cc, $sql) OR $error = $fc_error($_cc);
				if ($res) {
					if ($ret_res) {
						if ($this->db_type == 'sqlsrv_') {
							$data->result = true;
							$n=0;
							while ($row = $fc_object($res)) {
								$n++;
							}
							$data->cant = $n;
							$data->res = $res;
						}else{
							if ((($this->db_type == 'mysqli_') ? $res->$fc_num_r : $fc_num_r($res)) > 0) {
								$data->result = true;
								$data->cant = (($this->db_type == 'mysqli_') ? $res->$fc_num_r : $fc_num_r($res));
								$data->res = $res;
							}else{
								$data->result = false;
								$data->cant = 0;
								$data->res = $res;
							}
						}
					}else{
						$data->result = true;
						$data->cant = (($this->db_type == 'mysqli_') ? $res->$fc_num_r : $fc_num_r($res));
						$data->mensaje = "Ejecutado exitosamente";
					}
				}else{
					$data->result = false;
					$data->cant = -1;
					if ($ret_res) {
						$data->res = $res;
					}
				}
				//---------------------------------------------------------
				$data->error = $error;
				//---------------------------------------------------------
				return $data;
			}
			public function db_exec_sql($sql,$ret_res=true,$db='con',$_db_type=null){
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				$fc_query=$this->db_query;$fc_error=$this->db_error;$fc_fetch=$this->db_fetch;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
				//---------------------------------------------------------
				$data = new stdClass();
				$error = NULL;
				//----------------------------
				$_cc = $this->connect(SCHU,$db,$_db_type);
				//---------------------------------------------------------
				$res = $fc_query($_cc, $sql) OR $error = $fc_error($_cc);
				if ($res) {
					if ($ret_res) {
						if ($this->db_type == 'sqlsrv_') {
							$data->result = true;
							$n=0;
							while ($row = $fc_object($res)) {
								foreach ($row as $key => $value) {
									$data->$key = $value;
								}
								$n++;
							}
							$data->cant = $n;
						}else{
							if ((($this->db_type == 'mysqli_') ? $res->$fc_num_r : $fc_num_r($res)) > 0) {
								$data->result = true;
								$data->cant = (($this->db_type == 'mysqli_') ? $res->$fc_num_r : $fc_num_r($res));
								while ($row = $fc_assoc($res)) {
									foreach ($row as $key => $value) {
										$data->$key = $value;
									}
								}
							}else{
								$data->result = false;
								$data->cant = 0;
								$data->res = null;
							}
						}
					}else{
						$data->result = true;
						$data->cant = (($this->db_type == 'mysqli_') ? $res->$fc_num_r : $fc_num_r($res));
						$data->mensaje = "Ejecutado exitosamente";
					}
				}else{
					$data->result = false;
					$data->cant = -1;
					if ($ret_res) {
						$data->res = $res;
					}
				}
				//---------------------------------------------------------
				$data->error = $error;
				//---------------------------------------------------------
				return $data;
			}
			public function db_exec_sql_array($sql,$ret_res=true,$db='con',$_db_type=null){
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				$fc_query=$this->db_query;$fc_error=$this->db_error;$fc_fetch=$this->db_fetch;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
				//---------------------------------------------------------
				$data = new stdClass();$datos = array();
				$error = NULL;
				//----------------------------
				$_cc = $this->connect(SCHU,$db,$_db_type);
				//---------------------------------------------------------
				$res = $fc_query($_cc, $sql) OR $error = $fc_error($_cc);
				if ($res) {
					if ($ret_res) {
						if ($this->db_type == 'sqlsrv_') {
							$data->result = true;
							$n=0;
							while ($row = $fc_object($res)) {
								$datos[] = $row;
								$n++;
							}
							$data->cant = $n;
						}else{
							if ((($this->db_type == 'mysqli_') ? $res->$fc_num_r : $fc_num_r($res)) > 0) {
								$data->result = true;
								$data->cant = (($this->db_type == 'mysqli_') ? $res->$fc_num_r : $fc_num_r($res));
								while ($row = $fc_assoc($res)) {
									$datos[] = $row;
								}
							}else{
								$data->result = false;
								$data->cant = 0;
								$data->res = null;
							}
						}
					}else{
						$data->result = true;
						$data->mensaje = "Ejecutado exitosamente";
						$data->cant = (($this->db_type == 'mysqli_') ? $res->$fc_num_r : $fc_num_r($res));
					}
				}else{
					$data->result = false;
					$data->cant = -1;
					if ($ret_res) {
						$data->res = $res;
					}
				}
				//---------------------------------------------------------
				$data->datos = $datos;
				$data->error = $error;
				//---------------------------------------------------------
				$fc_close($_cc);
				return $data;
			}
		//---------------------------------------------------------UTILIDADES
			public function cal_fecha($fecha){
				$inf = '<span class="btn btn-outline-{COLOR} btn-xs">'.$fecha.'</span>';
				//-----------------------------
				if (!is_null($fecha)) {
					//-----------------------------
					$hoy = date('Y-m-d');
					//-----------------------------
					$diferencia = strtotime($fecha) - strtotime($hoy);
					 // Convertir la diferencia a días
					$diferencia_dias = $diferencia / 86400;
					//-----------------------------
					if ($diferencia_dias > 60){
						$inf = str_replace('{COLOR}', 'success '.$diferencia_dias, $inf);
					}else if($diferencia_dias <= 60 && $diferencia_dias >= 30){
						$inf = str_replace('{COLOR}', 'warning '.$diferencia_dias, $inf);
						$inf = str_replace('</span>', ' ALERTA</span>', $inf);
					}else if($diferencia_dias < 30){
					//}else{
						$inf = str_replace('{COLOR}', 'danger '.$diferencia_dias, $inf);
						$inf = str_replace('</span>', ' URGENTE</span>', $inf);
					}else{
						$inf = str_replace('{COLOR}', 'info '.$diferencia_dias, $inf);
					}
				}else{
					$inf = str_replace('{COLOR}', 'info 0', $inf);
				}
				//-----------------------------
				return $inf;
			}
			public function sum_fecha($campo,$fecha,$time){
				if ($campo == 1 && !is_null($fecha)) {
					// Verificar si la fecha tiene el formato DD/MM/YYYY
					$fecha_formato_dmy = DateTime::createFromFormat('d/m/Y', $fecha);
					//-----------------------------------
					if ($fecha_formato_dmy !== false) {
						// Si es formato DD/MM/YYYY, convertir a YYYY-MM-DD
						$nueva_fecha = $fecha_formato_dmy->format('Y-m-d');
					} else {
						// Si no es formato DD/MM/YYYY, asumimos que es YYYY-MM-DD
						$nueva_fecha = $fecha;
					}
					//-----------------------------------
					// Convertir a objeto DateTime
					$fecha_obj = DateTime::createFromFormat('Y-m-d', $nueva_fecha);
					//-----------------------------------
					$tiempo = 'P';
					if (isset($time->años) && $time->años==true) { $tiempo .= $time->cant_años.'Y'; }
					if (isset($time->meses) && $time->meses==true) { $tiempo .= $time->cant_meses.'M'; }
					if (isset($time->semanas) && $time->semanas==true) { $tiempo .= $time->cant_semanas.'W'; }
					if (isset($time->dias) && $time->dias==true) { $tiempo .= $time->cant_dias.'D'; }
					if (isset($time->tiempo) && $time->tiempo==true) { $tiempo .= 'T'; }
					if (isset($time->hor) && $time->hor==true) { $tiempo .= $time->cant_hor.'H'; }
					if (isset($time->min) && $time->min==true) { $tiempo .= $time->cant_min.'M'; }
					if (isset($time->seg) && $time->seg==true) { $tiempo .= $time->cant_seg.'S'; }
					//-----------------------------------
					// Sumar un año
					$fecha_obj->add(new DateInterval($tiempo));
					//-----------------------------------
					// Obtener la nueva fecha en formato 'Y-m-d'
					$nueva_fecha = $fecha_obj->format('Y-m-d');
				}else{
					$nueva_fecha = NULL;
				}
				//-----------------------------------
				return $nueva_fecha;
			}
			public function form_fecha($fecha){
				if (is_null($fecha)) {
					$fecha = date('Y-m-d');
				}
				//-----------------------------------
				// Verificar si la fecha tiene el formato DD/MM/YYYY
				$fecha_formato_dmy = DateTime::createFromFormat('d/m/Y', $fecha);
				//-----------------------------------
				if ($fecha_formato_dmy !== false) {
					// Si es formato DD/MM/YYYY, convertir a YYYY-MM-DD
					$nueva_fecha = $fecha_formato_dmy->format('Y-m-d');
				} else {
					// Si no es formato DD/MM/YYYY, asumimos que es YYYY-MM-DD
					$nueva_fecha = $fecha;
				}
				//-----------------------------------
				return $nueva_fecha;
			}
			public function form_float($numero,$cant=2){
				$numero = trim($numero);
				//$numero = str_replace('.', '', $numero);
				$numero = floatval($numero);
				$numero = str_replace(',', '.', $numero);
				//-----------------------------------
				if ($numero > 0) {
					$num = number_format($numero, $cant, '.', '');
				}else{
					$num = '0.00';
				}
				//-----------------------------------
				return $num;
			}
			public function getRandomCode($tipo=8,$largo=16){
				$inf = null;
				$an1 = '0123456789';
				$an2 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$an3 = 'abcdefghijklmnopqrstuvwxyz';
				$an4 = '$%&{}[]()=!@|#*^+-_<>';
				$an5 = $an1.$an2;
				$an6 = $an1.$an3;
				$an7 = $an1.$an2.$an3;
				$an8 = $an1.$an2.$an3.$an4;
				$su1 = strlen($an1) - 1;
				$su2 = strlen($an2) - 1;
				$su3 = strlen($an3) - 1;
				$su4 = strlen($an4) - 1;
				$su5 = strlen($an5) - 1;
				$su6 = strlen($an6) - 1;
				$su7 = strlen($an7) - 1;
				$su8 = strlen($an8) - 1;
				//------------------------------------------------
				switch ($tipo) {
					case 1:
						for ($i=0; $i < $largo; $i++) {
							$inf.=substr($an1,rand(0,$su1),1);
						}
					break;
					case 2:
						for ($i=0; $i < $largo; $i++) {
							$inf.=substr($an2,rand(0,$su2),1);
						}
					break;
					case 3:
						for ($i=0; $i < $largo; $i++) {
							$inf.=substr($an3,rand(0,$su3),1);
						}
					break;
					case 4:
						for ($i=0; $i < $largo; $i++) {
							$inf.=substr($an4,rand(0,$su4),1);
						}
					break;
					case 5:
						for ($i=0; $i < $largo; $i++) {
							$inf.=substr($an5,rand(0,$su5),1);
						}
					break;
					case 6:
						for ($i=0; $i < $largo; $i++) {
							$inf.=substr($an6,rand(0,$su6),1);
						}
					break;
					case 7:
						for ($i=0; $i < $largo; $i++) {
							$inf.=substr($an7,rand(0,$su7),1);
						}
					break;
					case 8:
						for ($i=0; $i < $largo; $i++) {
							$inf.=substr($an8,rand(0,$su8),1);
						}
					break;
				}
				//-----------------------------
				return $inf;
			}
			public function form_txt($input) {
				// Eliminar caracteres especiales excepto acentos y ñ
				$input = preg_replace('/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s+\-_.,$@!¡?¿()]/u', '', $input);
				//---------------------------------------
				// Eliminar etiquetas HTML y PHP
				$input = strip_tags($input);
				//---------------------------------------
				// Eliminar espacios al principio y al final
				$input = trim($input);
				//---------------------------------------
				// Escapar comillas simples y dobles
				$input = addslashes($input);
				//---------------------------------------
				return $input;
			}
			public function reemp_car_esp($texto) {
				// Array de reemplazo
				$reemplazos = array(
					'á' => 'a', '&aacute;' => 'a',
					'é' => 'e', '&eacute;' => 'e',
					'í' => 'i', '&iacute;' => 'i',
					'ó' => 'o', '&oacute;' => 'o',
					'ú' => 'u', '&uacute;' => 'u',
					'ñ' => 'n', '&ntilde;' => 'n',
					'Á' => 'A', '&Aacute;' => 'A',
					'É' => 'E', '&Eacute;' => 'E',
					'Í' => 'I', '&Iacute;' => 'I',
					'Ó' => 'O', '&Oacute;' => 'O',
					'Ú' => 'U', '&Uacute;' => 'U',
					'Ñ' => 'N', '&Ntilde;' => 'N',
					'ü' => 'u', '&uuml;' => 'u', 'Ü' => 'U', '&Uuml;' => 'U',
					'ç' => 'c', '&ccedil;' => 'c', 'Ç' => 'C', '&Ccedil;' => 'C',
					'º' => 'o', '&ordm;' => 'o', // grados
					'ª' => 'a', '&ordf;' => 'a', // ordinales femeninos
					'¡' => '', '&iexcl;' => '', // signo de exclamación invertido
					'¿' => '', '&iquest;' => ''  // signo de interrogación invertido
				);
				//---------------------------------------------------------
				// Realizar el reemplazo
				$reemplazado = strtr($texto, $reemplazos);
				//---------------------------------------------------------
				return $reemplazado;
			}
			public function form_txt_sap($cadena,$codic='html',$longitud=30) {
				// Divide la cadena en partes de longitud especificada
				$partes = str_split($cadena, $longitud);
				//---------------------------------------------------------
				// Array para almacenar las partes convertidas a HTML
				$html_parts = array();
				//---------------------------------------------------------
				$busq = array('`', '´');
				// Itera sobre cada parte y convierte a HTML
				foreach ($partes as $parte) {
					// Convierte la parte a HTML
					switch ($codic) {
						case 'html':
							// Primero reemplazamos los caracteres ` y ´ por &#039;
							$parte_reemplazada = str_replace($busq, "'", $parte);
							// Luego aplicamos htmlentities
							$html_parte = htmlentities($parte_reemplazada);
						break;
						case 'str':
							$html_parte = $this->reemp_car_esp($parte);
						break;
						default:
							$html_parte = $parte;
						break;
					}
					// Agrega la parte convertida al array
					$html_parts[] = $html_parte;
				}
				//---------------------------------------------------------
				// Retorna el array con las partes convertidas a HTML
				return $html_parts;
			}
			public function dividir_str($cadena, $longitud = 30) {
				// Paso 1: Decodificar entidades HTML (&amp;, &lt;, etc.)
				$cadena = html_entity_decode($cadena);
				//---------------------------------------------------------
				// Paso 2: Eliminar todas las etiquetas HTML
				$cadena_limpia = strip_tags($cadena);
				//---------------------------------------------------------
				// Paso 3: Reemplazar las letras acentuadas por sus equivalentes sin acento
				$acentos = array('á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ');
				$sin_acentos = array('a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N');
				$cadena_limpia = str_replace($acentos, $sin_acentos, $cadena_limpia);
				//---------------------------------------------------------
				// Paso 4: Eliminar espacios en blanco adicionales al principio y al final
				$cadena_limpia = trim($cadena_limpia);
				//---------------------------------------------------------
				// Paso 5: Dividir la cadena en partes de la longitud especificada
				$partes = str_split($cadena_limpia, $longitud);
				//---------------------------------------------------------
				// Retornar el array con las partes limpias
				return $partes;
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
			public function calc_cod_txt($pid) {
				$val = null;$largo = strlen($pid);
				//---------------------------------------
				switch ($largo) {
					case 1:		$val = '0000000000'.$pid;	break;
					case 2:		$val = '000000000'.$pid;	break;
					case 3:		$val = '00000000'.$pid;		break;
					case 4:		$val = '0000000'.$pid;		break;
					case 5:		$val = '000000'.$pid;		break;
					case 6:		$val = '00000'.$pid;		break;
					case 7:		$val = '0000'.$pid;			break;
					case 8:		$val = '000'.$pid;			break;
					case 9:		$val = '00'.$pid;			break;
					case 10:	$val = '0'.$pid;			break;
					default:	$val = $pid;				break;
				}
				//---------------------------------------
				return $val;
			}
			public function get_mes_txt($mes){
				switch (intval($mes)) {
					case 1:		$mes_txt = '01-Enero';			break;
					case 2:		$mes_txt = '02-Febrero';		break;
					case 3:		$mes_txt = '03-Marzo';			break;
					case 4:		$mes_txt = '04-Abril';			break;
					case 5:		$mes_txt = '05-Mayo';			break;
					case 6:		$mes_txt = '06-Junio';			break;
					case 7:		$mes_txt = '07-Julio';			break;
					case 8:		$mes_txt = '08-Agosto';			break;
					case 9:		$mes_txt = '09-Septiembre';		break;
					case 10:	$mes_txt = '10-Octubre';		break;
					case 11:	$mes_txt = '11-Noviembre';		break;
					case 12:	$mes_txt = '12-Diciembre';		break;
					default:
						$mes_txt = '00-No definido';
					break;
				}
				//--------------------------------
				return $mes_txt;
			}
		//---------------------------------------------------------GET
			public function db_get_string($dt,$json,$db='con',$_db_type=null){
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				$fc_query=$this->db_query;$fc_error=$this->db_error;$fc_fetch=$this->db_fetch;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
				//---------------------------------------------------------
				$data = new stdClass(); $sql = null;
				$data->error = null;
				//----------------------------
				$_cc = $this->connect(SCHU,$db,$_db_type);
				//----------------------------
				$er=1;
				if(is_null($json->tname)){ $er=0; }
				if(is_null($json->tid)){ $er=0; }
				if(is_null($json->pid)){ $er=0; }
				if($json->pid <= 0){ $er=0; }
				//----------------------------
				if ($er == 1) {
					$sql = $this->get_sql($json->tname, $dt, 8, $json->tid, $json->pid);//8 select por VSLOR STRING
					$res = $fc_query($_cc,$sql) OR $data->error = (($this->db_type=='sqlsrv_') ? $fc_error() : $fc_error($_cc));
					if ($res) {
						if ($this->db_type == 'sqlsrv_') {
							$data->result = true;
							$n=0;
							while ($row = $fc_object($res)) {
								foreach ($row as $key => $value) {
									$data->$key = $value;
								}
								$n++;
							}
							$data->cant = $n;
						}else{
							if ((($this->db_type == 'mysqli_') ? $res->$fc_num_r : $fc_num_r($res)) > 0) {
								$data->result = true;
								$data->mensaje = "Registro encontrado exitosamente.";
								//----------------------------
								while ($row = $fc_assoc($res)) {
									foreach ($row as $key => $value) {
										$data->$key = $value;
									}
								}
								//----------------------------
								$row = null;
							}else{
								$data->result = false;
								$data->mensaje = "La repuesta está vacía.";
							}
						}
						//----------------------------
						$fc_fre_r($res);
					}else{
						$data->result = false;
						$data->mensaje = "No se encontró coincidencia, para el ID: ".$json->pid.".";
					}
				}else{
					$data->result = false;
					$data->mensaje = "No existe el nombre de la tabla. Datos: ".json_encode($json);
				}
				//------------------
				//$data->sql = $sql;
				//$data->input = $json;
				//------------------
				$fc_close($_cc);
				return $data;
			}
			public function db_get_id($dt,$json,$db='con',$_db_type=null){
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				$fc_query=$this->db_query;$fc_error=$this->db_error;$fc_fetch=$this->db_fetch;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
				//---------------------------------------------------------
				$data = new stdClass(); $sql = null;
				$data->error = null;
				//----------------------------
				$_cc = $this->connect(SCHU,$db,$_db_type);
				$tipo_get = 8;//8 select por ID
				//----------------------------
				$er=1;
				if(is_null($json->tname)){ $er=0; }
				if(is_null($json->tid)){ $er=0; }
				if(is_null($json->pid)){ $er=0; }
				if($json->pid <= 0){ $er=0; }
				//----------------------------
				if ($er == 1) {
					$sql = $this->get_sql($json->tname, $dt, $tipo_get, $json->tid, $json->pid);
					$res = $fc_query($_cc,$sql) OR $data->error = (($this->db_type=='sqlsrv_') ? $fc_error() : $fc_error($_cc));
					if ($res) {
						if ($this->db_type == 'sqlsrv_') {
							$data->result = true;
							$n=0;
							while ($row = $fc_object($res)) {
								foreach ($row as $key => $value) {
									$data->$key = $value;
								}
								$n++;
							}
							$data->cant = $n;
						}else{
							if ((($this->db_type == 'mysqli_') ? $res->$fc_num_r : $fc_num_r($res)) > 0) {
								$data->result = true;
								$data->mensaje = "Registro encontrado exitosamente.";
								//----------------------------
								while ($row = $fc_assoc($res)) {
									foreach ($row as $key => $value) {
										$data->$key = $value;
									}
								}
								//----------------------------
								$row = null;
							}else{
								$data->result = false;
								$data->mensaje = "La repuesta está vacía.";
							}
						}
						//----------------------------
						$fc_fre_r($res);
					}else{
						$data->result = false;
						$data->mensaje = "No se encontró coincidencia, para el ID: ".$json->pid.".";
					}
				}else{
					$data->result = false;
					$data->mensaje = "No existe el nombre de la tabla. Datos: ".json_encode($json);
				}
				//------------------
				//$data->sql = $sql;
				//$data->input = $json;
				//------------------
				$fc_close($_cc);
				return $data;
			}
			public function db_get_camp_id_array($dt,$json,$db='con',$_db_type=null){
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				$fc_query=$this->db_query;$fc_error=$this->db_error;$fc_fetch=$this->db_fetch;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
				//---------------------------------------------------------
				$data = new stdClass(); $sql = null; $datos = array();
				$data->error = null;
				//----------------------------
				$_cc = $this->connect(SCHU,$db,$_db_type);
				//----------------------------
				$er=1;
				if(is_null($json->tname)){ $er=0; }
				if(is_null($json->tid)){ $er=0; }
				if(is_null($json->pid)){ $er=0; }
				if($json->pid <= 0){ $er=0; }
				//----------------------------
				if ($er == 1) {
					$sql = $this->get_sql($json->tname, $dt, 11, $json->tid, $json->pid, null, $json->adic);//8 select por ID
					$res = $fc_query($_cc,$sql) OR $data->error = (($this->db_type=='sqlsrv_') ? $fc_error() : $fc_error($_cc));
					if ($res) {
						if ($this->db_type == 'sqlsrv_') {
							$data->result = true;
							$n=0;
							while ($row = $fc_object($res)) {
								$datos[] = $row;
								$n++;
							}
							$data->cant = $n;
						}else{
							if ((($this->db_type == 'mysqli_') ? $res->$fc_num_r : $fc_num_r($res)) > 0) {
								$data->result = true;
								$data->mensaje = "Registro encontrado exitosamente.";
								//----------------------------
								while ($row = $fc_assoc($res)) {
									$datos[] = $row;
								}
								//----------------------------
								$row = null;
							}else{
								$data->result = false;
								$data->mensaje = "La repuesta está vacía.";
							}
						}
						//----------------------------
						$fc_fre_r($res);
					}else{
						$data->result = false;
						$data->mensaje = "No se encontró coincidencia, para el ID: ".$json->pid.".";
					}
				}else{
					$data->result = false;
					$data->mensaje = "No existe el nombre de la tabla. Datos: ".json_encode($json);
				}
				//------------------
				$data->datos = $datos;
				//$data->sql = $sql;
				//$data->input = $json;
				//------------------
				$fc_close($_cc);
				return $data;
			}
			public function db_get_all($dt,$json,$db='con',$_db_type=null){
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				$fc_query=$this->db_query;$fc_error=$this->db_error;$fc_fetch=$this->db_fetch;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
				//---------------------------------------------------------
				$data = new stdClass(); $sql = null; $fila = array(); $inf = array();
				$data->error = null;$n=0;
				//----------------------------
				$_cc = $this->connect(SCHU,$db,$_db_type);
				//----------------------------
				$er=1;
				if(is_null($json->tname)){ $er=0; }
				if(is_null($json->tid)){ $er=0; }
				if(is_null($json->pid)){ $er=0; }
				//----------------------------
				if ($er == 1) {
					$sql = $this->get_sql($json->tname, $dt, $json->type, $json->tid, $json->pid);//5 select por ID
					$res = $fc_query($_cc,$sql) OR $data->error = (($this->db_type=='sqlsrv_') ? $fc_error() : $fc_error($_cc));
					if ($res) {
						if ($this->db_type == 'sqlsrv_') {
							$data->result = true;
							$n=0;
							while ($row = $fc_object($res)) {
								$inf[] = array(
									"id"	=>	$row[$json->tid],
									//"name"	=>	mb_convert_encoding($row[$json->col_name], $this->codificacion_objetivo, $this->codificacion_original),
									//"name"	=>	htmlentities($row[$json->col_name], ENT_QUOTES, 'UTF-8'),
									"name"	=>	mb_convert_encoding($row[$json->col_name], $this->codificacion_original, $this->codificacion_objetivo),
								);
								//----------------------------
								$n++;
							}
							$data->cant = $n;
						}else{
							if ((($this->db_type == 'mysqli_') ? $res->$fc_num_r : $fc_num_r($res)) > 0) {
								$data->result = true;
								$data->mensaje = "Registros encontrados exitosamente.";
								//----------------------------
								while ($row = $fc_assoc($res)) {
									$inf[] = array(
										"id"	=>	$row[$json->tid],
										//"name"	=>	mb_convert_encoding($row[$json->col_name], $this->codificacion_objetivo, $this->codificacion_original),
										//"name"	=>	htmlentities($row[$json->col_name], ENT_QUOTES, 'UTF-8'),
										"name"	=>	mb_convert_encoding($row[$json->col_name], $this->codificacion_original, $this->codificacion_objetivo),
									);
									//----------------------------
									$n++;
								}
								//----------------------------
								$row = null;
							}else{
								$data->result = false;
								$data->mensaje = "La repuesta está vacía.";
							}
						}
						//----------------------------
						$fc_fre_r($res);
					}else{
						$data->result = false;
						$data->mensaje = "No se encontró coincidencia, para el ID: ".$json->pid.".";
					}
				}else{
					$data->result = false;
					$data->mensaje = "No existe el nombre de la tabla. Datos: ".json_encode($json);
				}
				//------------------
				$data->rows = count($inf);
				$data->inf = $inf;
				//$data->sql = $sql;
				//$data->input = $json;
				//error_log("Result: ".$n);
				//------------------
				$fc_close($_cc);
				//------------------
				return $data;
			}
			public function db_get_cant($dt,$json,$db='con',$_db_type=null){
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				$fc_query=$this->db_query;$fc_error=$this->db_error;$fc_fetch=$this->db_fetch;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
				//---------------------------------------------------------
				$data = new stdClass(); $sql = null;
				$data->error = null;
				//----------------------------
				$_cc = $this->connect(SCHU,$db,$_db_type);
				//----------------------------
				$er=1;
				if(is_null($json->tname)){ $er=0; }
				if(is_null($json->tid)){ $er=0; }
				//----------------------------
				if ($er == 1) {
					$sql = "SELECT COUNT(*) AS total FROM ".$json->tname." WHERE status=1 ;";
					$res = $fc_query($_cc,$sql) OR $data->error = (($this->db_type=='sqlsrv_') ? $fc_error() : $fc_error($_cc));
					if ($res) {
						if ($this->db_type == 'sqlsrv_') {
							$data->result = true;
							$n=0;
							while ($row = $fc_object($res)) {
								foreach ($row as $key => $value) {
									$data->$key = $value;
								}
								$n++;
							}
							$data->total = $n;
						}else{
							if ((($this->db_type == 'mysqli_') ? $res->$fc_num_r : $fc_num_r($res)) > 0) {
								$data->result = true;
								$data->mensaje = "Registro encontrado exitosamente.";
								//----------------------------
								while ($row = $fc_assoc($res)) {
									foreach ($row as $key => $value) {
										$data->$key = $value;
									}
								}
								//----------------------------
								$row = null;
							}else{
								$data->result = false;
								$data->total = 0;
								$data->mensaje = "La repuesta está vacía.";
							}
						}
						//----------------------------
						$fc_fre_r($res);
					}else{
						$data->result = false;
						$data->total = 0;
						$data->mensaje = "No se encontró coincidencia, para el ID: ".$json->tname.".";
					}
				}else{
					$data->result = false;
					$data->total = 0;
					$data->mensaje = "No existe el nombre de la tabla. Datos: ".json_encode($json);
				}
				//------------------
				//$data->sql = $sql;
				//$data->input = $json;
				//------------------
				$fc_close($_cc);
				return $data;
			}
			public function db_get_btns($total,$pag,$url){
				$data = new stdClass(); $sql = null; $inf = null;
				//----------------------------
					// Parsear la URL
					$parsedUrl = parse_url($url);
					//----------------------------
					// Verificar si existe la consulta "?pag="
					if (isset($parsedUrl['query'])) {
						// Parsear los parámetros de la consulta
						parse_str($parsedUrl['query'], $queryParams);
						//----------------------------
						// Verificar si existe el parámetro "pag"
						if (isset($queryParams['pag'])) {
							// Eliminar el parámetro "pag"
							unset($queryParams['pag']);
							//----------------------------
							// Construir la nueva URL
							$newUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'];
							//----------------------------
							// Agregar los nuevos parámetros si hay alguno
							if (!empty($queryParams)) {
								$newUrl .= '?' . http_build_query($queryParams);
							}
							//----------------------------
							// Mostrar la nueva URL
							$_url = $newUrl;
						}else if (isset($queryParams['new'])) {
							// Eliminar el parámetro "pag"
							unset($queryParams['new']);
							//----------------------------
							// Construir la nueva URL
							$newUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'];
							//----------------------------
							// Agregar los nuevos parámetros si hay alguno
							if (!empty($queryParams)) {
								$newUrl .= '?' . http_build_query($queryParams);
							}
							//----------------------------
							// Mostrar la nueva URL
							$_url = $newUrl;
						}else {
							// No hay parámetro "pag", la URL permanece igual
							$_url = $url;
						}
					} else {
						// No hay consulta en la URL, la URL permanece igual
						$_url = $url;
					}
				//----------------------------
				$inf .= '<ul class="pagination justify-content-end">';
					//----------------------------
					// Configuración de la paginación
					$resultados_por_pagina = ROWS;
					$total_paginas = ceil($total / $resultados_por_pagina);
					//----------------------------
					//boton primera
					$inf .= '<li class="page-item ' . (($pag == 1) ? 'disabled' : null) . '" title="Primera Página">';
						$inf .= '<a href="' . (($pag == 1) ? null : $_url . '?pag=' . base64_encode(1)) . '" class="page-link"><i class="fas fa-backward-fast"></i></a>';
					$inf .= '</li>';
					//----------------------------
					//boton anterior
					$anterior = max(1, $pag - 1);
					//----------------------------
					$inf .= '<li class="page-item ' . (($pag == 1) ? 'disabled' : null) . '" title="Página Anterior">';
						$inf .= '<a href="' . (($pag == 1) ? null : $_url . '?pag=' . base64_encode($anterior)) . '" class="page-link">Anterior</a>';
					$inf .= '</li>';
					//----------------------------
					//calcular el rango de botones de página a mostrar
					$rango_inicial = max(1, $pag - 2);
					$rango_final = min($total_paginas, $rango_inicial + 4);
					//----------------------------
					// Mostrar botones de página
					for ($i = $rango_inicial; $i <= $rango_final; $i++) {
						$inf .= '<li class="page-item ' . (($i == $pag) ? 'active' : null) . '"><a href="' . $_url . '?pag=' . base64_encode($i) . '" class="page-link">' . $i . '</a></li>';
					}
					//----------------------------
					// Agregar puntos suspensivos si hay más páginas después del rango final
					if ($rango_final < $total_paginas) {
						$inf .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
					}
					//----------------------------
					// Mostrar el último botón de página si no es parte del rango final
					if ($rango_final != $total_paginas) {
						$inf .= '<li class="page-item"><a href="' . $_url . '?pag=' . base64_encode($total_paginas) . '" class="page-link">' . $total_paginas . '</a></li>';
					}
					//----------------------------
					//boton siguiente
					$siguiente = min($total_paginas, $pag + 1);
					$inf .= '<li class="page-item ' . (($pag == $total_paginas || $total_paginas <= 1) ? 'disabled' : null) . '" title="Página Siguiente">';
						$inf .= '<a href="' . (($pag == $total) ? null : $_url . '?pag=' . base64_encode($siguiente)) . '" class="page-link">Siguiente</a>';
					$inf .= '</li>';
					//----------------------------
					//boton último
					$inf .= '<li class="page-item ' . (($pag == $total_paginas) ? 'disabled' : null) . '" title="Última Página">';
						$inf .= '<a href="' . (($pag == $total_paginas) ? null : $_url . '?pag=' . base64_encode($total_paginas)) . '" class="page-link"><i class="fas fa-forward-fast"></i></a>';
					$inf .= '</li>';
				$inf .= '</ul>';
				//----------------------------
				$data->inf = $inf;
				//$data->sql = $sql;
				//$data->input = $json;
				//----------------------------
				return $data;
			}
		//---------------------------------------------------------ADD Y EDIT
			public function db_add($dt,$json,$db='con',$_db_type=null){
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				$fc_query=$this->db_query;$fc_error=$this->db_error;$fc_fetch=$this->db_fetch;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
				//---------------------------------------------------------
				$data = new stdClass(); $sql = null;
				$data->error = null;
				//----------------------------
				$_cc = $this->connect(SCHU,$db,$_db_type);
				//----------------------------
				$er=1;
				if(is_null($json->tname)){ $er=0; }
				//----------------------------
				if ($er == 1) {
					$sql = $this->get_sql($json->tname, $dt, 1);
					try {
						$res = $fc_query($_cc,$sql) OR $data->error = (($this->db_type=='sqlsrv_') ? $fc_error() : $fc_error($_cc));
						if ($res) {
							$data->result = true;
							$data->inf = $json->success;
							$data->mensaje = "Registro agregado exitosamente.";
						}else{
							$data->result = false;
							$data->inf = $json->danger;
							$data->mensaje = "No se logró agregar los datos.";
						}
					} catch (Exception $e) {
						$data->result = false;
						$data->inf = $json->danger;
						$data->mensaje = "No se logró agregar, Ya existe un valor igual.";
					}
				}else{
					$data->result = false;
					$data->inf = 'null';
					$data->mensaje = "No existe el nombre de la tabla. Datos: ".json_encode($json);
				}
				//------------------
				$data->sql = $sql;
				//------------------
				$fc_close($_cc);
				return $data;
			}
			public function db_add_all($dt,$json,$db='con',$_db_type=null){
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				$fc_query=$this->db_query;$fc_error=$this->db_error;$fc_fetch=$this->db_fetch;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
				//---------------------------------------------------------
				$data = new stdClass(); $sql = null; $result = array(); $fila_res = array();
				$data->error = null;
				//----------------------------
				$_cc = $this->connect(SCHU,$db,$_db_type);
				//----------------------------
				$er=1;$n=0;$r=0;
				if(is_null($json->tname)){ $er=0; }
				//----------------------------
				if ($er == 1) {
					foreach ($dt as $fila) {
						$fila_res = array();
						//-----------------------------
						if (!is_null($fila[$json->t_camp])) {
							$sql = $this->get_sql($json->tname, $fila, 1);
							try {
								$res = $fc_query($_cc,$sql) OR $data->error = (($this->db_type=='sqlsrv_') ? $fc_error() : $fc_error($_cc));
								if ($res) {
									$result[] = array(
										"result"	=>	true,
										"inf"	=>	$json->success,
										"mensaje"	=>	"Registro agregado exitosamente.",
									);
									//-----------------------------
									$r++;
								}else{
									$result[] = array(
										"result"	=>	false,
										"inf"	=>	$json->danger,
										"mensaje"	=>	"No se logró agregar los datos.",
									);
								}
							} catch (Exception $e) {
								$result[] = array(
									"result"	=>	false,
									"inf"	=>	$json->danger,
									"mensaje"	=>	"No se logró agregar, Ya existe un valor igual.",
								);
							}
						}else{
							$result[] = array(
								"result"	=>	false,
								"inf"	=>	$json->danger,
								"mensaje"	=>	"El primer campo está vacío, por ello no se agregó la fila: ".$n,
							);
						}
						//----------------------------
						$n++;
					}
				}else{
					$result[] = array(
						"result"	=>	false,
						"inf"	=>	'null',
						"mensaje"	=>	"No existe el nombre de la tabla.",
					);
				}
				//------------------
				//$data->sql = $sql;
				$data->res = (($n > 1000) ? $result[0] : $result);
				$data->rows = $n;
				$data->rows_add = $r;
				if ($data->rows_add > 0) {
					$data->add_one = $result[0];
				}
				//------------------
				$fc_close($_cc);
				return $data;
			}
			public function db_add_ret($dt,$json,$db='con',$_db_type=null){
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				$fc_query=$this->db_query;$fc_error=$this->db_error;$fc_fetch=$this->db_fetch;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
				//---------------------------------------------------------
				$data = new stdClass(); $sql = null;
				$data->error = null;
				$data->pid = 0;
				//----------------------------
				$_cc = $this->connect(SCHU,$db,$_db_type);
				//----------------------------
				$er=1;
				if(is_null($json->tname)){ $er=0; }
				//----------------------------
				if ($er == 1) {
					$sql = $this->get_sql($json->tname, $dt, 1, $json->tid, $json->pid, true);
					try {
						$res = $fc_query($_cc,$sql) OR $data->error = (($this->db_type=='sqlsrv_') ? $fc_error() : $fc_error($_cc));
						if ($res) {
							if ($this->db_type == 'sqlsrv_') {
								$data->result = true;
								$data->inf = $json->success;
								$n=0;
								while ($row = $fc_object($res)) {
									$data->pid = $row[$json->tid];
									$n++;
								}
								//----------------------------
								$data->mensaje = "Registro agregado exitosamente.";
							}else{
								if ((($this->db_type == 'mysqli_') ? $res->$fc_num_r : $fc_num_r($res)) > 0) {
									$data->result = true;
									$data->inf = $json->success;
									//----------------------------
									while ($row = $fc_assoc($res)) {
										$data->pid = $row[$json->tid];
									}
									//----------------------------
									$data->mensaje = "Registro agregado exitosamente.";
								}else{
									$data->result = false;
									$data->inf = $json->success;
									$data->mensaje = "Registro agregado, pero no se retorna ID.";
								}
							}
						}else{
							$data->result = false;
							$data->inf = $json->danger;
							$data->mensaje = "No se logró agregar los datos.";
						}
					} catch (Exception $e) {
						$data->result = false;
						$data->inf = $json->danger;
						$data->mensaje = "No se logró agregar, Ya existe un valor igual.";
					}
				}else{
					$data->result = false;
					$data->inf = 'null';
					$data->mensaje = "No existe el nombre de la tabla. Datos: ".json_encode($json);
				}
				//------------------
				//$data->sql = $sql;
				//------------------
				$fc_close($_cc);
				return $data;
			}
			public function db_edit($dt,$json,$db='con',$_db_type=null){
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				$fc_query=$this->db_query;$fc_error=$this->db_error;$fc_fetch=$this->db_fetch;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
				//---------------------------------------------------------
				$data = new stdClass(); $sql = null;
				$data->error = null;
				//----------------------------
				$_cc = $this->connect(SCHU,$db,$_db_type);
				//----------------------------
				switch ($json->success) {
					case "edit":
						$success = "modifico";
						$danger = "modificar";
					break;
					case "drop":
						$success = "elimino";
						$danger = "eliminar";
					break;
					case "active":
						$success = "activado";
						$danger = "activar";
					break;
					case "desactive":
						$success = "desactivado";
						$danger = "desactivar";
					break;
					case "lock":
						$success = "bloqueo";
						$danger = "bloquear";
					break;
					case "unlock":
						$success = "desbloqueo";
						$danger = "desbloquear";
					break;
					default:
						$success = "agregado";
						$danger = "agregar";
					break;
				}
				//----------------------------
				$er=1;
				if(is_null($json->tname)){ $er=0; }
				//----------------------------
				if ($er == 1) {
					$sql = $this->get_sql($json->tname, $dt, 2, $json->tid, $json->pid);
					try {
						$res = $fc_query($_cc,$sql) OR $data->error = (($this->db_type=='sqlsrv_') ? $fc_error() : $fc_error($_cc));
						if ($res) {
							$data->result = true;
							$data->inf = $json->success;
							$data->mensaje = "Registro ".$success." exitosamente.";
						}else{
							$data->result = false;
							$data->inf = $json->danger;
							$data->mensaje = "No se logró ".$danger." el registro.";
						}
					} catch (Exception $e) {
						$data->result = false;
						$data->inf = $json->danger;
						$data->mensaje = "No se logró agregar, Ya existe un valor igual.";
					}
				}else{
					$data->result = false;
					$data->inf = 'null';
					$data->mensaje = "No existe el nombre de la tabla. Datos: ".json_encode($json);
				}
				//------------------
				$data->sql = $sql;
				//------------------
				$fc_close($_cc);
				return $data;
			}
			public function db_edit_string($dt,$json,$db='con',$_db_type=null){
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				$fc_query=$this->db_query;$fc_error=$this->db_error;$fc_fetch=$this->db_fetch;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
				//---------------------------------------------------------
				$data = new stdClass(); $sql = null;
				$data->error = null;
				//----------------------------
				$_cc = $this->connect(SCHU,$db,$_db_type);
				//----------------------------
				switch ($json->success) {
					case "edit":
						$success = "modificado";
						$danger = "modificar";
					break;
					case "drop":
						$success = "eliminado";
						$danger = "eliminar";
					break;
					case "active":
						$success = "activado";
						$danger = "activar";
					break;
					case "desactive":
						$success = "desactivado";
						$danger = "desactivar";
					break;
					case "lock":
						$success = "bloquear";
						$danger = "bloquear";
					break;
					case "unlock":
						$success = "desbloquear";
						$danger = "desbloqueado";
					break;
					default:
						$success = "agregado";
						$danger = "agregar";
					break;
				}
				//----------------------------
				$er=1;
				if(is_null($json->tname)){ $er=0; }
				//----------------------------
				if ($er == 1) {
					$sql = $this->get_sql($json->tname, $dt, 7, $json->tid, $json->pid);
					try {
						$res = $fc_query($_cc,$sql) OR $data->error = (($this->db_type=='sqlsrv_') ? $fc_error() : $fc_error($_cc));
						if ($res) {
							$data->result = true;
							$data->inf = $json->success;
							$data->mensaje = "Registro ".$success." exitosamente.";
						}else{
							$data->result = false;
							$data->inf = $json->danger;
							$data->mensaje = "No se logró ".$danger." el registro.";
						}
					} catch (Exception $e) {
						$data->result = false;
						$data->inf = $json->danger;
						$data->mensaje = "No se logró ".$danger.".";
					}
				}else{
					$data->result = false;
					$data->inf = 'null';
					$data->mensaje = "No existe el nombre de la tabla. Datos: ".json_encode($json);
				}
				//------------------
				//$data->sql = $sql;
				//------------------
				$fc_close($_cc);
				return $data;
			}
			public function db_edit_all($dt,$json,$_db='con',$_db_type=null){
				if (!is_null($_db_type)) {
					$this->load_other_type($_db_type);
				}
				//---------------------------------------------------------
				$fc_query=$this->db_query;$fc_error=$this->db_error;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
				//---------------------------------------------------------
				$data = new stdClass(); $sql = null; $result = array(); $fila_res = array();
				$data->error = null;
				//----------------------------
				$er=1;$n=0;$r=0;
				if(is_null($json->tname)){ $er=0; }
				//----------------------------
				if ($er == 1) {
					foreach ($dt as $fila) {
						$fila_res = array();
						//-----------------------------
						if (!is_null($fila[$json->t_camp])) {
							$sql = $this->get_sql($json->tname, $fila, 2, $json->tid, $json->pid);
							try {
								$res = $fc_query($this->connect(SCHU,$_db,$_db_type),$sql) OR $data->error .= (($this->db_type=='sqlsrv_') ? $fc_error() : $fc_error($this->connect(SCHU,$_db,$_db_type)));
								if ($res) {
									$result[] = array(
										"result"	=>	true,
										"inf"	=>	$json->success,
										"mensaje"	=>	"Registro editado exitosamente.",
										"fila"	=>	$fila[$json->t_camp],
									);
									$r++;
								}else{
									$result[] = array(
										"result"	=>	false,
										"inf"	=>	$json->danger,
										"mensaje"	=>	"No se logró editar los datos.",
										"fila"	=>	$fila,
									);
								}
							} catch (Exception $e) {
								$result[] = array(
									"result"	=>	false,
									"inf"	=>	$json->danger,
									"mensaje"	=>	"No se logró editar, Ya existe un valor igual.",
									"fila"	=>	$fila,
								);
							}
						}else{
							$result[] = array(
								"result"	=>	false,
								"inf"	=>	$json->danger,
								"mensaje"	=>	"El primer campo está vacío, por ello no se agregó la fila: ".$n,
							);
						}
						//----------------------------
						$n++;
					}
				}else{
					$result[] = array(
						"result"	=>	false,
						"inf"	=>	'null',
						"mensaje"	=>	"No existe el nombre de la tabla. Datos: ".json_encode($json),
					);
				}
				//------------------
				//$data->sql = $sql;
				$data->res = (($n > 1000) ? $result[0] : $result);
				$data->rows = $n;
				$data->rows_edit = $r;
				//------------------
				if($this->db_type=='sqlsrv_'){ $fc_close(); }else{ $fc_close($this->connect(SCHU,$_db,$_db_type)); }
				return $data;
			}
		//---------------------------------------------------------PREDETERMINADO
			public function get_datos($pid,$type,$db='con',$_db_type=null){
				$data = new stdClass();
				//---------------------------------------------------------
				switch ($type) {
					case 'user':
						$sql = "SELECT u.*, tu.nombre_t FROM usuarios u INNER JOIN tipos_usuarios tu ON u.id_tipo=tu.id_tipo WHERE id_user=".$pid." LIMIT 1 ;";
					break;
					case 'placa':
						$sql = "SELECT * FROM scheme_name.unidades WHERE placa LIKE '".$pid."' LIMIT 1 ;";
					break;
					case 'clie':
						$sql = "SELECT * FROM scheme_name.v_clients WHERE id_int LIKE '".$pid."' LIMIT 1 ;";
					break;
					default:
						$sql = null;
					break;
				}
				//---------------------------------------------------------
				if (!is_null($sql)) {
					$data = $this->db_exec_sql($sql,true,$db,$_db_type);
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
				switch ($tipo) {
					case 1://GENERAR SENTENCIA INSERT
						$sql = "INSERT INTO ".$this_table." ( ";
						//-----------campos----------------
							foreach ($dt as $key => $value) {
								$sql .= $key.", ";
							}					
						//-----------fin-campos------------
						$sql = substr($sql, 0, -2).") VALUES (";
						//-----------valores----------------
							foreach ($dt as $key => $value) {
								$sql .= "'".$value."', ";
							}
						//-----------fin-valores------------
						$sql = substr($sql, 0, -2);
						$sql .= " )";
						//-----------return-ult-id----------
						if ($return) {
							$sql .= " RETURNING ".$this_tid;//solo aplica para PostgreSQL
						}
						$sql .= " ;";
					break;
					case 3://GENERRAR SENTENCIA PARA LLAMAR PROCEDIMIENTOS ALMACENADOS
						$sql = "SELECT ".$this_table." ( ";
						//-----------valores----------------
							foreach ($dt as $key => $value) {
								$sql .= "'".$value."', ";
							}
						//-----------fin-valores------------
						$sql = substr($sql, 0, -2);
						$sql .= " );";
					break;
					case 4://GENERRAR SENTENCIA PARA BUSCAR EN TABLA CON CAMPOS DADOS
						$sql = "SELECT * FROM ".$this_table." WHERE ";
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
										$sql .= $key."='".$value."' AND ";
									break;
								}
							}
						//-----------fin-valores------------
						$sql = substr($sql, 0, -2);
						$sql .= " ;";
					break;
					case 5://GENERAR SENTENCIA UPDATE USANDO WHERE CON KEY_ID DISTINTO INT
						$sql = "UPDATE ".$this_table." SET ";
						//-----------campos-valores----------------
							foreach ($dt as $key => $value) {
								$sql .= $key."='".$value."', ";
							}
						//-----------fin-campos-valores------------
						$sql = substr($sql, 0, -2);
						$sql .= " WHERE ";
						//------------campos-adicionale------------
							if (!is_null($adic)) {
								$sql .= $adic." AND ";
							}
						//-----------fin-campos-adicionale---------
						$sql .= $this_tid."=".$json_pid.";";
					break;
					case 6://GENERRAR SENTENCIA PARA LISTAR TODO
						$sql = "SELECT * FROM ".$this_table." ;";
					break;
					case 7://GENERAR SENTENCIA UPDATE DONDE ID/CAMPO - VALOR (STRING)
						$sql = "UPDATE ".$this_table." SET ";
						//-----------campos-valores----------------
							foreach ($dt as $key => $value) {
								$sql .= $key."='".$value."', ";
							}
						//-----------fin-campos-valores------------
						$sql = substr($sql, 0, -2);
						$sql .= " WHERE ";
						//------------campos-adicionale------------
							if (!is_null($adic)) {
								$sql .= $adic." AND ";
							}
						//-----------fin-campos-adicionale---------
						$sql .= $this_tid." LIKE '".$json_pid."' ;";
					break;
					case 8://GENERRAR SENTENCIA PARA SELECT EN TABLA POR ID/CAMPO - VALOR (INT)
						$sql = "SELECT * FROM ".$this_table."  ";
						//$sql = "SELECT t1.*, c.nombre_u AS user_add, c.correo_u AS mail_add, e.nombre_u AS user_edit, e.correo_u AS mail_edit FROM ".$this_table." t1 ";
							//$sql .= " LEFT OUTER JOIN scheme_name.view_users_all c ON t1.id_created=c.id_usuario ";
							//$sql .= " LEFT OUTER JOIN scheme_name.view_users_all e ON t1.id_updated=e.id_usuario ";
						$sql .= " WHERE ";
						//------------campos-adicionale------------
							if (!is_null($adic)) {
								$sql .= "".$adic." AND ";
							}
						//-----------fin-campos-adicionale---------
						$sql .= "".$this_tid."=".$json_pid.";";
					break;
					case 9://GENERRAR SENTENCIA PARA SELECT EN TABLA POR ID/CAMPO - VALOR (STRING)
						$sql = "SELECT * FROM ".$this_table." WHERE ";
						//------------campos-adicionale------------
							if (!is_null($adic)) {
								$sql .= $adic." AND ";
							}
						//-----------fin-campos-adicionale---------
						$sql .= $this_tid." LIKE '".$json_pid."';";
					break;
					case 10://GENERRAR SENTENCIA PARA BUSCAR EN TABLA LOS CAMPOS DADOS
						$sql = "SELECT ";
						//-----------valores----------------
							foreach ($dt as $value) {
								$sql .= $value.", ";
							}
						//-----------fin-valores------------
						$sql = substr($sql, 0, -2);
						$sql .= " FROM ".$this_table." WHERE ";
						$sql .= $this_tid." LIKE '".$json_pid."' ;";
					break;
					case 11://GENERRAR SENTENCIA PARA SELECT EN TABLA PARA LLAMAR CAMPOS ESPECÍFICOS POR ID/CAMPO - VALOR (INT)
						$sql = "SELECT ";
						//------------campos-adicionale------------
							if (!is_null($dt)) {
								foreach ($dt as $value) {
									$sql .= $value.", ";
								}
							}else{
								$sql .= " * ";
							}
						//-----------fin-campos-adicionale---------
						$sql = substr($sql, 0, -2);
						$sql .= " FROM ".$this_table." WHERE ";
						//------------campos-adicionale------------
							if (!is_null($adic)) {
								$sql .= $adic." AND ";
							}
						//-----------fin-campos-adicionale---------
						$sql .= $this_tid."=".$json_pid.";";
					break;
					case 12://GENERRAR SENTENCIA PARA SELECT EN TABLA POR ID/CAMPO - VALOR (INT) - SIN USUARIOS
						$sql = "SELECT * FROM ".$this_table." WHERE ";
						//------------campos-adicionale------------
							if (!is_null($adic)) {
								$sql .= "".$adic." AND ";
							}
						//-----------fin-campos-adicionale---------
						$sql .= "".$this_tid."=".$json_pid.";";
					break;
					default://GENERAR SENTENCIA UPDATE
						$sql = "UPDATE ".$this_table." SET ";
						//-----------campos-valores----------------
							foreach ($dt as $key => $value) {
								$sql .= $key."='".$value."', ";
							}
						//-----------fin-campos-valores------------
						$sql = substr($sql, 0, -2);
						$sql .= " WHERE ";
						$sql .= $this_tid."=".$json_pid." ";
						//------------campos-adicionale------------
							if (!is_null($adic)) {
								$sql .= $adic." ";
							}
						//-----------fin-campos-adicionale---------
						$sql .= " ;";
					break;
				}
				//----------------------------------
				return $sql;
			}
		//---------------------------------------------------------
	}