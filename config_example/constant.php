<?php
date_default_timezone_set("America/Lima");
//------------------------------------
define('HTTP', 'http://');
define('HTTPS', 'https://');
define('HTTP2', 'https://www.');
//------------------------------------
define('TIPE', 'EMPRESA NAME');
define('TIPO', 'Sitio Web');
define('TIT', ' | '.TIPE);
define('EMP', 'EMPRESA NAME');
define('EMP_RUC', 'RUC');
define('EMP_DIR', 'DIR');
define('EMP_MAP', 'MAP');
define('EMP_EMA', 'EMAIL');
define('EMP_PHO', 'PHONE');
define('EMP_WHA', 'https://wa.me/51'.EMP_PHO);
define('EMP_DEP', 'DEPARTMENT');
define('EMP_PRO', 'PROVINCE');
define('EMP_DIS', 'DISTRICT');
//------------------------------------
define('DOM', 'domain');
define('DOM_COM', DOM.'.com');
define('DOM_CPE', DOM.'.com.pe');
define('DOM_PE', DOM.'.pe');
define('DOM_NET', DOM.'.net.pe');
//------------------------------------
define('CONF', 'config/');
define('DIRACT', 'ACTIONVQ/');
define('DIRMOR', 'MORENOKU/');
define('DIRWEB', '/path/to/web/');
define('DIRARC', '/path/to/archivos/');
define('DIRSIS', '/path/to/sistema/');
define('DIRFIL', DIRARC.'files/');
define('DIRIMG', DIRARC.'img/');
define('DIRERR', 'error/');
//------------------------------------
define('__DIR__', $_SERVER['DOCUMENT_ROOT']);//ruta global del sistema
//-------------------------------------------
define('DB_TYPE', 'mysqli_');//base de datos MySQL
//define('DB_TYPE', 'pg_');//base de datos PostgreSQL
//define('DB_TYPE', 'sqlsrv_');//base de datos SQL Server
//------------------------------------
define('TURNSTILE_SITE_KEY', '');
define('TURNSTILE_SECRET_KEY', '');
//------------------------------------
define('DB_PORT', '3306');
//------------------------------------
define('ROWS', 100);
define('LIMIT_ROWS', 7000);
//------------------------------------
define('DETRAC', 700.00);
//------------------------------------
//define('SCHU', '_qas');
define('SCHU', '_prd');
//------------------------------------
define('SCHU_EMAIL', SCHU);
//define('SCHU_EMAIL', '_qas');
//------------------------------------
if (SCHU == '_qas') {
	define('DIRPRI', '/domain.com/v3/web/');
	//------------------------------------
	define('__DIRIMG__', $_SERVER['DOCUMENT_ROOT']."/domain.com/v3/archivos/img/");//ruta global donde se almacenan los archivos
	//------------------------------------
	define('URL', HTTPS.'localhost'.DIRPRI);
	define('URL2', HTTPS.'localhost');
	//------------------------------------
	define('PLUG', HTTPS.'localhost/domain.com/v3/plugins/');
	define('ARCH', HTTPS.'localhost/domain.com/v3/archivos/');
	define('SIST', HTTPS.'localhost/domain.com/v3/sistema/');
	//------------------------------------
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'DBNAME');
	define('DB_USER', 'DBUSER');
	define('DB_PASS', 'DBPASS');
	//------------------------------------
}else{
	define('DIRPRI', '/');
	//------------------------------------
	define('__DIRIMG__', substr($_SERVER['DOCUMENT_ROOT'], 0, -4)."/archivos/img/");//ruta global donde se almacenan los archivos
	//------------------------------------
	define('URL', HTTPS.'theme.'.DOM_PE.DIRPRI);
	define('URL2', HTTPS.'theme.'.DOM_PE);
	//------------------------------------
	define('PLUG', HTTPS.'plugins.'.DOM_PE.DIRPRI);
	define('ARCH', HTTPS.'archivos.'.DOM_PE.DIRPRI);
	define('SIST', HTTPS.'sistema.'.DOM_PE.DIRPRI);
	//------------------------------------
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'DBNAME');
	define('DB_USER', 'DBUSER');
	define('DB_PASS', 'DBPASS');
	//------------------------------------
}
//------------------------------------
define('ACTI', URL.DIRACT);
//------------------------------------
define('CSS', URL.'assets/css/');
define('JS', URL.'assets/js/');
define('FONTS', URL.'assets/fonts/');
define('IMAGES', URL.'assets/images/');
//------------------------------------
define('IMG', ARCH.'img/');
define('FILS', ARCH.'files/');
//------------------------------------
define('E401', URL.DIRERR.'401.shtml');
define('E402', URL2.DIRERR.'402.shtml');
define('E403', URL.DIRERR.'403.shtml');
define('E404', URL.DIRERR.'404.shtml');
//------------------------------------
define('ICO', URL.'favicon-32x32.png');
define('ICO16', URL.'favicon-16x16.png');
define('ICO32', URL.'favicon-32x32.png');
define('ICO64', URL.'android-chrome-192x192.png');
define('ICO128', URL.'android-chrome-512x512.png');
define('LOGO', ARCH.'img/logo-3@0,25x.png');
define('LOGO2', ARCH.'img/logolargo-3@0,25x.png');
//------------------------------------
define('FACE', 'https://www.facebook.com/CGSComputeroficial/');
define('INST', 'https://www.instagram.com/cgscomputer_oficial/');
define('LINK', 'https://www.youtube.com/channel/UCv2I3TRTfGokkAQ9ADFnNxA');
//-------------------------------------------
define('FACE_FM', 'https://www.facebook.com/fmorenoadmin/');
define('TWIT_FM', 'https://www.twitter.com/fmorenoadmin/');
define('INST_FM', 'https://www.instagram.com/fmorenoadmin/');
//------------------------------------
define('YEARHOY', date('Y'));
//------------------------------------
define('FMMA', HTTPS.'landing.fmorenoadmin.com.pe/');
//------------------------------------
function mayus_tilds($txt){
	$tmp = $txt;
	//------------------------------------
	$tmp = str_replace('á', 'Á', $tmp);
	$tmp = str_replace('é', 'É', $tmp);
	$tmp = str_replace('í', 'Í', $tmp);
	$tmp = str_replace('ó', 'Ó', $tmp);
	$tmp = str_replace('ú', 'Ú', $tmp);
	$tmp = str_replace('ñ', 'Ñ', $tmp);
	//------------------------------------
	$txt = $tmp;
	//------------------------------------
	return $txt;
}
//------------------------------------
// Obtener la dirección IP real del visitante cuando se usa CloudFlare
if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
	$ip = $ip_cli = $_SERVER['HTTP_CF_CONNECTING_IP'];
} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	$ip = $ip_cli = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
	$ip = $ip_cli = $_SERVER['REMOTE_ADDR'];
}
//------------------------------------
