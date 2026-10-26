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
//define('DB_TYPE', 'sqlsrv_');
//---------------------------------------
if (SCHU=='_qas') {
	define('__DIRIMG__', $_SERVER['DOCUMENT_ROOT']."/vac_crud/assets/img/");//ruta global donde se almacenan los archivos
	//---------------------------------------
	define('DOM', 'localhost');
	//---------------------------------------
	define('URL', HTTPS.DOM.'/vac_crud/');
	define('URL2', HTTPS.DOM.'');
	//---------------------------------------
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'vac_crud');
	define('DB_USER', 'root');
	define('DB_PORT', '3306');
	define('DB_PASS', '');
	//---------------------------------------
}else{
	define('__DIRIMG__', $_SERVER['DOCUMENT_ROOT']."/assets/img/");//ruta global donde se almacenan los archivos
	//---------------------------------------
	define('DOM', 'domainname');
	//---------------------------------------
	define('URL', HTTPS.DOM.'/');
	define('URL2', HTTPS.DOM.'');
	//---------------------------------------
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'AQUI_TU_NOMBRE_DATABASE');
	define('DB_USER', 'AQUI_TU_USUARIO');
	define('DB_PORT', '3306');
	define('DB_PASS', 'AQUI_TU_CONTRASEÑA');
	//---------------------------------------
}
//---------------------------------------
//---------------------------------------
define('ACTI', URL.DIRACT.'');
//---------------------------------------
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
//---------------------------------------
// Helper de Protección CSRF
if (empty($_SESSION['csrf_token'])) {
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if (!function_exists('csrf_token')) {
	function csrf_token() {
		return $_SESSION['csrf_token'] ?? '';
	}
}
if (!function_exists('csrf_field')) {
	function csrf_field() {
		return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '" />';
	}
}
if (!function_exists('verify_csrf')) {
	function verify_csrf() {
		$token = $_POST['csrf_token'] ?? $_REQUEST['csrf_token'] ?? '';
		if (empty($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
			http_response_code(403);
			header("Content-Type: application/json; charset=UTF-8");
			die(json_encode(["result" => false, "error" => true, "mensaje" => "Error 403: Token de seguridad (CSRF) inválido o expirado."]));
		}
	}
}
//---------------------------------------
// Helper de Redirección Segura (Anti-Open Redirect)
if (!function_exists('safe_redirect')) {
	function safe_redirect($target_url, $fallback = URL) {
		if (empty($target_url)) {
			header("Location: " . $fallback);
			exit();
		}
		$parsed = parse_url($target_url);
		$current_host = $_SERVER['HTTP_HOST'] ?? 'localhost';
		if (!isset($parsed['host']) || strtolower($parsed['host']) === strtolower($current_host)) {
			header("Location: " . $target_url);
		} else {
			header("Location: " . $fallback);
		}
		exit();
	}
}
//---------------------------------------
$_SESSION['location'] = $location = HTTPS.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//---------------------------------------
// Control de Sesión: en _qas se inicializa demo si no existe, en _prd requiere login
if (!isset($_SESSION['user_id'])) {
	if (SCHU === '_qas') {
		$_SESSION['user_id'] = 1;
		$_SESSION['tipo_id'] = 1;
	} else {
		$_SESSION['user_id'] = 0;
		$_SESSION['tipo_id'] = 0;
	}
}
$uid = (int)($_SESSION['user_id'] ?? 0);
$rid = (int)($_SESSION['tipo_id'] ?? 0);
if (isset($_SESSION['stat'])) { $sms = $_SESSION['stat']; }else{ $sms = $_SESSION['stat'] = ''; }
//---------------------------------------
if (isset($_REQUEST['p'])) { $pid = base64_decode($_REQUEST['p']); }else{ $pid =0; }
//---------------------------------------