<?php
// ANTI-CACHÉ ESTRICTO
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
//--------------------------------------------------
$ssid=null;
if (isset($_SESSION['ssid'])){ $ssid = $_SESSION['ssid']; }else{ $_SESSION['ssid'] = session_id(); }
//--------------------------------------------------
require_once($rut.'config/constant.php');
//--------------------------------------------------
$_SESSION['location'] = $location = HTTPS.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//--------------------------------------------------
//variables
$pid=0;$vdat=0;
$uid=0;$dni=null;$ruc=null;$rsoc=null;$una=null;$uno=null;$uap=null;$uem=null;$uph=null;$is_distrb=0;$u_sd=null;$u_fsd=null;
$rid=0;$tna=null;$uus=null;$tallid=0;$tallna=null;$ufo=null;$ap_M=false;$u_inte=null;$u_inic=null;$u_proy=null;$is_vac=0;$ufe=null;$uho=null;
$schu=SCHU;$singlr=null;
$bot=' <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>';
//--------------------------------------------------
require($rut.DIRMOR.'Sesiones.php');
$_s = new Sesiones();
//--------------------------------------------------
if (isset($_REQUEST['p'])) { $_s->setPID(intval(base64_decode($_REQUEST['p']))); }
if (isset($_REQUEST['v'])) { $_s->setVALOR(base64_decode($_REQUEST['v'])); }
//--------------------------------------------------
$pid=$_s->getPID();$vdat=$_s->getVALOR();
$uid=$_s->getUID();$dni=$_s->getDNI();$ruc=$_s->getRUC();$rsoc=$_s->getRSOC();$una=$_s->getUNAME();$uno=$_s->getUNOMB();$uap=$_s->getUAPEL();$uem=$_s->getUEMAI();$uph=$_s->getUPHON();$is_distrb=$_s->getDISTRIB();$u_sd=$_s->getSOLDIST();$u_fsd=$_s->getFSOLDIST();
//--------------------------------------------------
$rid=$_s->getTID();$uus=$_s->getUUSER();$tna=$_s->getTNAME();$ufo=$_s->getUFOTO();$tallid=$_s->getTALLID();$tallna=$_s->getTALLNAME();$u_inte=$_s->getINTER();$u_inic=$_s->getINIC();$u_proy=$_s->getPROYEC();$is_vac=$_s->getISVAC();
//--------------------------------------------------
if (isset($_SESSION['is_visor'])) { $is_visor=(($_SESSION['is_visor'] == 1) ? false : true); }else{ $is_visor=true; }
if (isset($_SESSION['u_code'])){ $u_code=$_SESSION['u_code']; }else{ $u_code=null; }
if (isset($_SESSION['cod_vers'])){ $cod_vers=$_SESSION['cod_vers']; }else{ $cod_vers='1.0.0'; }
if (isset($_SESSION['txt_vers'])){ $txt_vers=$_SESSION['txt_vers']; }else{ $txt_vers='Nueva Versión'; }
//--------------------------------------------------
$apel_sup = explode(' ', $uap);
$pri_ape = $apel_sup[0];
$l1_n = strtoupper(substr($uno, 0, 1));
$l1_a = substr($pri_ape, 0, 1);
$l2_a = (isset($apel_sup[1])) ? strtoupper(substr($apel_sup[1], 0, 1)) : '';
//--------------------------------------------------
$day = date('w');
$wk_s_ant = date('Y-m-d', strtotime('-'.($day + 7).' days'));
$wk_e_ant = date('Y-m-d', strtotime('+'.(6 - 7 - $day).' days'));
$wk_s_hoy = date('Y-m-d', strtotime('-'.($day).' days'));
$wk_e_hoy = date('Y-m-d', strtotime('+'.(6 - $day).' days'));
$mont_lim = date('Y-m-d', strtotime('-3 month'));
$_ayer = date('Y-m-d', strtotime('-3 days'));
//--------------------------------------------------
require_once($rut.DIRMOR.'Seguridad.php');
$_seg = new Seguridad();
//--------------------------------------------------
$nav_cli = $_SESSION['nav_cli'] = $_seg->getBrowser($_SERVER['HTTP_USER_AGENT']);
$sist_cli = $_SESSION['sist_cli'] = $_seg->getPlatform($_SERVER['HTTP_USER_AGENT']);
//--------------------------------------------------
	if(isset($_SERVER['HTTP_REFERER'])){
		$referer = $_SESSION['referer'] = $_SERVER['HTTP_REFERER'];
	}else if(isset($_SESSION['referer'])){
		$referer = $_SESSION['referer'];
	}else{
		$referer = null;
	}
	//--------------------------------------------------
	if(isset($_REQUEST['utm_id'])){
		$utm_id = $_SESSION['utm_id'] = $_REQUEST['utm_id'];
	}else if(isset($_SESSION['utm_id'])){
		$utm_id = $_SESSION['utm_id'];
	}else{
		$utm_id = null;
	}
	//--------------------------------------------------
	if(isset($_REQUEST['utm_campaign'])){
		$utm_campaign = $_SESSION['utm_campaign'] = $_REQUEST['utm_campaign'];
	}else if(isset($_SESSION['utm_campaign'])){
		$utm_campaign = $_SESSION['utm_campaign'];
	}else{
		$utm_campaign = null;
	}
	//--------------------------------------------------
	if(isset($_REQUEST['utm_source'])){
		$utm_source = $_SESSION['utm_source'] = $_REQUEST['utm_source'];
	}else if(isset($_SESSION['utm_source'])){
		$utm_source = $_SESSION['utm_source'];
	}else{
		$utm_source = 'google';
	}
	//--------------------------------------------------
	if(isset($_REQUEST['utm_medium'])){
		$utm_medium = $_SESSION['utm_medium'] = $_REQUEST['utm_medium'];
	}else if(isset($_SESSION['utm_medium'])){
		$utm_medium = $_SESSION['utm_medium'];
	}else{
		$utm_medium = 'Web';
	}
	//--------------------------------------------------
	if(isset($_REQUEST['utm_content'])){
		$utm_content = $_SESSION['utm_content'] = $_REQUEST['utm_content'];
	}else if(isset($_SESSION['utm_content'])){
		$utm_content = $_SESSION['utm_content'];
	}else{
		$utm_content = null;
	}
	//--------------------------------------------------
	if(isset($_REQUEST['utm_term'])){
		$utm_term = $_SESSION['utm_term'] = $_REQUEST['utm_term'];
	}else if(isset($_SESSION['utm_term'])){
		$utm_term = $_SESSION['utm_term'];
	}else{
		$utm_term = null;
	}
	//--------------------------------------------------
	if(isset($_REQUEST['fbclid'])){
		$fbclid = $_SESSION['fbclid'] = $_REQUEST['fbclid'];
	}else if(isset($_SESSION['fbclid'])){
		$fbclid = $_SESSION['fbclid'];
	}else{
		$fbclid = null;
	}
	//--------------------------------------------------
	if(isset($_REQUEST['gclid'])){
		$gclid = $_SESSION['gclid'] = $_REQUEST['gclid'];
	}else if(isset($_SESSION['gclid'])){
		$gclid = $_SESSION['gclid'];
	}else{
		$gclid = null;
	}
//--------------------------------------------------
if (isset($_REQUEST['mid'])) {
	$mid = intval(base64_decode($_REQUEST['mid']));
	if ($mid > 0) {
	}else{
		$mid = 0;
	}
}else{
	$mid = 0;
}
//--------------------------------------------------
if (isset($_REQUEST['pag'])) {
	$pag = intval(base64_decode($_REQUEST['pag']));
	if ($pag > 0) {
	}else{
		$pag = 1;
	}
}else{
	$pag = 1;
}
//--------------------------------------------------
// Filtros de Precio
$min = (isset($_REQUEST['min'])) ? floatval($_REQUEST['min']) : 0;
$max = (isset($_REQUEST['max'])) ? floatval($_REQUEST['max']) : 0;
//--------------------------------------------------
// Ordenamiento
$ord = (isset($_REQUEST['ord'])) ? $_REQUEST['ord'] : 'stock';
// Busqueda
$sch = (isset($_REQUEST['sch'])) ? $_REQUEST['sch'] : '';
//--------------------------------------------------