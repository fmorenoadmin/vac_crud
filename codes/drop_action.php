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
		$_tbl->tname = $cls['cl1'];
		$_tbl->tid = 'id_u';
		$_tbl->pid = 0;
		$_tbl->success = 'add';
		$_tbl->danger = 'no'.$_tbl->success;
		$_tbl->test = true;
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