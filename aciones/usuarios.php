<?php
	if(isset($_SESSION)){}else{ session_start(); }
	//-----------------------------------
	$ru0='../';
	//-----------------------------------
	$cls = array(
		"dbs"	=>	'database',
		"cl1"	=>	'usuarios',
	);
	//-----------------------------------
	$json = new stdClass();
	//-----------------------------------
		$_tbl = new stdClass();
		$_tbl->tname = ''.$cls['cl1'];//este siempre debe ser el NOMBRE de la tabla
		$_tbl->tid = 'id_u';//este siempre debe de ser el ID PRIMARY KEY de la tabla
		$_tbl->pid = 0;
		$_tbl->success = 'add';
		$_tbl->danger = 'no'.$_tbl->success;
		$_tbl->test = true;
	//-----------------------------------
		function index($rut,$rid,$uid,$url){
			global $cls;
			require($rut.DIRCLA.$cls['dbs'].'.php');
			require_once($rut.DIRCLA.$cls['cl1'].'.php');
			$_dbs = new $cls['dbs']();
			$_cl1 = new $cls['cl1']();
			$data = new stdClass();
			//-----------------------------------
			$data->inf = $_cl1->listar($rid,$uid,$url);
			$data->edit = $_cl1->list_edit($rid,$uid,$url);//estos son solo para el index de la muestra, normalmente no irian
			$_SESSION['list_drop'] = $data->drop = $_cl1->list_drop($rid,$uid,$url);//estos son solo para el index de la muestra, normalmente no irian
			//-----------------------------------
			return $data;
		}
		function detalle($rut,$rid,$pid){
			global $cls,$_tbl;
			require($rut.DIRCLA.$cls['dbs'].'.php');
			require_once($rut.DIRCLA.$cls['cl1'].'.php');
			$_dbs = new $cls['dbs']();
			$_cl1 = new $cls['cl1']();
			$data = new stdClass();
			//-----------------------------------
			$_tbl->pid = $pid;
			//-----------------------------------
			$data->call = $_cl1->db_get_id(null,$_tbl);
			//-----------------------------------
			return $data;
		}
		function exportar($rut,$tipo){
			global $cls;
			require_once($rut.DIRCLA.$cls['dbs'].'.php');
			require_once($rut.DIRCLA.$cls['cl1'].'.php');
			$_dbs = new $cls['dbs']();
			$_cl1 = new $cls['cl1']();
			$data = new stdClass();
			//-------------------------------
			$data->inf = $_cl1->exportar($tipo);
			//-------------------------------
			return $data;
		}
	//-----------------------------------
		if (isset($_POST['nuevo'])) {
			require_once($ru0.'config/constant.php');
			//----------------------------------------
			if (isset($_SESSION['user_id'])) {
				require($ru0.DIRCLA.$cls['dbs'].'.php');
				require_once($ru0.DIRCLA.$cls['cl1'].'.php');
				$_dbs = new $cls['dbs']();
				$_cl1 = new $cls['cl1']();
				//-----------------------------------
				$add = array(
					"nombres" => $_dbs->custom_escape_string($_POST['nombres']),
					"apellidos" => $_dbs->custom_escape_string($_POST['apellidos']),
					"usuario" => $_dbs->custom_escape_string($_POST['usuario']),
					"correo" => $_dbs->custom_escape_string($_POST['correo']),
					"obs" => str_replace("'", '´', $_POST['obs']),
					"created_at" => date('Y-m-d H:i:s'),
					"id_created" => base64_decode($_POST['uid']),
					"status" => ((isset($_POST['status'])) ? $_POST['status'] : 1),
				);
				//-----------------------------------
				$url = base64_decode($_POST['url']);
				//-----------------------------------
				$resp = $_dbs->db_add($add,$_tbl);
				$_SESSION['stat'] = $resp;
				if ($resp->result) {
					$_SESSION['SMStrue'] = $resp->mensaje;
				}else{
					$_SESSION['SMSfalse'] = $resp->mensaje;
				}
				if (isset($_tbl->test) && $_tbl->test==true) {
					$_SESSION['sql'] = $resp->sql;
				}
				//-----------------------------------
				$_POST = null;
				//-----------------------------------
				header("Location: ".$url);
				exit();
			}else{
				header("Location: ".E403);
				exit();
			}
		}
	//-----------------------------------
		if (isset($_POST['editar'])) {
			require_once($ru0.'config/constant.php');
			//----------------------------------------
			if (isset($_SESSION['user_id'])) {
				require($ru0.DIRCLA.$cls['dbs'].'.php');
				require_once($ru0.DIRCLA.$cls['cl1'].'.php');
				$_dbs = new $cls['dbs']();
				$_cl1 = new $cls['cl1']();
				//-----------------------------------
				$_tbl->pid = base64_decode($_POST['pid']);
				$_tbl->success = 'edit';
				$_tbl->danger = 'no'.$_tbl->success;
				//----------------------------------------
				$edit = array(
					"nombres" => $_dbs->custom_escape_string($_POST['nombres']),
					"apellidos" => $_dbs->custom_escape_string($_POST['apellidos']),
					"usuario" => $_dbs->custom_escape_string($_POST['usuario']),
					"correo" => $_dbs->custom_escape_string($_POST['correo']),
					"obs" => str_replace("'", '´', $_POST['obs']),
					"updated_at" => date('Y-m-d H:i:s'),
					"id_updated" => base64_decode($_POST['uid']),
					"status" => ((isset($_POST['status'])) ? $_POST['status'] : 1),
				);
				//-----------------------------------
				$url = base64_decode($_POST['url']);
				//-----------------------------------
				$resp = $_dbs->db_edit($edit,$_tbl);
				$_SESSION['stat'] = $resp;
				if ($resp->result) {
					$_SESSION['SMStrue'] = $resp->mensaje;
				}else{
					$_SESSION['SMSfalse'] = $resp->mensaje;
				}
				if (isset($_tbl->test) && $_tbl->test==true) {
					$_SESSION['sql'] = $resp->sql;
				}
				//-----------------------------------
				$_POST = null;
				//-----------------------------------
				header("Location: ".$url);
				exit();
			}else{
				header("Location: ".E403);
				exit();
			}
		}
	//-----------------------------------
		if (isset($_POST['call_id'])) {
			require_once($ru0.'config/constant.php');
			$json = new stdClass();
			//-----------------------------
			if (isset($_SESSION['user_id'])) {
				require_once($ru0.DIRCLA.$cls['dbs'].'.php');
				require_once($ru0.DIRCLA.$cls['cl1'].'.php');
				$_dbs = new $cls['dbs']();
				$_cl1 = new $cls['cl1']();
				//-----------------------------
				$pid = $_dbs->custom_escape_string($_POST['pid']);
				$_tbl->pid = intval($pid);
				//-----------------------------
				$url = base64_decode($_POST['url']);
				//-----------------------------
				$json = $_cl1->db_get_id(null,$_tbl);
				//-----------------------------
				$_POST = null;
			}else{
				$json->result = false;
				$json->error = true;
				$json->mensaje = 'Usted no tiene Permisos para acceder a este recurso';
			}
			//-----------------------------
			header("Content-type: application/grcp+json; Charset: UTF-8;");
			echo json_encode($json, JSON_PRETTY_PRINT);
		}
	//-----------------------------------
		if (isset($_REQUEST['meth'])) {
			require_once($ru0.'config/constant.php');
			if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
				require($ru0.DIRCLA.$cls['dbs'].'.php');
				require_once($ru0.DIRCLA.$cls['cl1'].'.php');
				$_dbs = new $cls['dbs']();
				$_cl1 = new $cls['cl1']();
				//-----------------------------------
				$_tbl->pid = base64_decode($_REQUEST['pid']);
				$_tbl->success = (($_REQUEST['meth']=="act") ? 'active' : 'desactive');
				$_tbl->danger = 'no'.$_tbl->success;
				//-----------------------------------
				$edit = array(
					"updated_at" => date('Y-m-d H:i:s'),
					"id_updated" => $_SESSION['user_id'],
					"status" => (($_REQUEST['meth']=="act") ? 1 : 0),
				);
				//-----------------------------------
				$url = base64_decode($_REQUEST['url']);
				//-----------------------------------
				$resp = $_dbs->db_edit($edit,$_tbl);
				$_SESSION['stat'] = $resp;
				if ($resp->result) {
					$_SESSION['SMStrue'] = $resp->mensaje;
					if (isset($_tbl->test) && $_tbl->test==true) {
						$_SESSION['sql'] = $resp->sql;
					}
				}else{
					$_SESSION['SMSfalse'] = $resp->mensaje;
				}
				//-----------------------------------
				$_REQUEST = null;
				//-----------------------------------
				header("Location: ".$url);
				exit();
			}else{
				header("Location: ".E403);
				exit();
			}
		}
	//-----------------------------------
		if (isset($_POST['eliminar'])) {
			require_once($ru0.'config/constant.php');
			if (isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0) {
				require($ru0.DIRCLA.$cls['dbs'].'.php');
				require_once($ru0.DIRCLA.$cls['cl1'].'.php');
				$_dbs = new $cls['dbs']();
				$_cl1 = new $cls['cl1']();
				//-----------------------------------
				$_tbl->pid = base64_decode($_POST['pid']);
				$_tbl->success = 'drop';
				$_tbl->danger = 'no'.$_tbl->success;
				//-----------------------------------
				$drop = array(
					"motivo_drop" => str_replace("'", '´', $_POST['motivo_drop']),
					"drop_at" => date('Y-m-d H:i:s'),
					"id_drop" => base64_decode($_POST['uid']),
					"status" => 2,
				);
				//-----------------------------------
				$url = base64_decode($_POST['url']);
				//-----------------------------------
				$resp = $_dbs->db_edit($drop,$_tbl);
				$_SESSION['stat'] = $resp;
				if ($resp->result) {
					$_SESSION['SMStrue'] = $resp->mensaje;
				}else{
					$_SESSION['SMSfalse'] = $resp->mensaje;
				}
				if (isset($_tbl->test) && $_tbl->test==true) {
					$_SESSION['sql'] = $resp->sql;
				}
				//-----------------------------------
				$_POST = null;
				//-----------------------------------
				header("Location: ".$url);
				exit();
			}else{
				header("Location: ".E403);
				exit();
			}
		}
	//-----------------------------------