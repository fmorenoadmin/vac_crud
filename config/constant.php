<?php
//---------------------------------------
define('HTTP', 'http://');
define('HTTPS', 'https://');
//---------------------------------------
define('DIRACT', 'aciones/');
define('DIRCLA', 'clases/');
//---------------------------------------
define('SCHU', '_qas');
//define('SCHU', '_prd');
//---------------------------------------
define('DB_TYPE', 'mysqli_');
//define('DB_TYPE', 'pg_');
//---------------------------------------
if (SCHU=='_qas') {
	define('__DIRIMG__', $_SERVER['DOCUMENT_ROOT']."/vac_crud/assets/img/");//ruta global donde se almacenan los archivos
	//---------------------------------------
	define('DOM', 'localhost');
	//---------------------------------------
	define('URL', HTTPS.DOM.'/vac_crud/');
	define('URL2', HTTPS.DOM.'');
}else{
	define('__DIRIMG__', $_SERVER['DOCUMENT_ROOT']."/assets/img/");//ruta global donde se almacenan los archivos
	//---------------------------------------
	define('DOM', 'domainname');
	//---------------------------------------
	define('URL', HTTPS.DOM.'/');
	define('URL2', HTTPS.DOM.'');
}
//---------------------------------------
define('ACTI', URL.DIRACT.'');
//---------------------------------------
$_SESSION['location'] = $location = HTTPS.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//---------------------------------------
$_SESSION['user_id'] = $uid = 1;
$_SESSION['tipo_id'] = $rid = 1;
if (isset($_SESSION['stat'])) { $sms = $_SESSION['stat']; }else{ $sms = $_SESSION['stat'] = ''; }
//---------------------------------------
if (isset($_REQUEST['p'])) { $pid = base64_decode($_REQUEST['p']); }else{ $pid =0; }
//---------------------------------------