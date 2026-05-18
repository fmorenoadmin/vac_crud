<?php
$sms="";
if ( isset($_SESSION['stat']) ) {
	$valorSta=$_SESSION['stat'];
	switch ($valorSta) {
		case 'hash': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert"><strong>Se encriptó</strong> tu contraseña con éxito.'.$bot.'</div>'; break;
		case 'generado': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert"><strong>Se generó</strong> el cadena con éxito.'.$bot.'</div>'; break;
		case 'encode': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert"><strong>Se codificó</strong> el contenido con éxito.'.$bot.'</div>'; break;
		case 'decode': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert"><strong>Se decodificó</strong> el contenido con éxito.'.$bot.'</div>'; break;
		case 'add': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert"><strong>Se guardó</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'pagado': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert"><strong>Se cobró</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'send': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert"><strong>Se envió</strong> el mensaje con éxito.'.$bot.'</div>'; break;
		case 'edit': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert"><strong>Se editó</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'drop': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert"><strong>Se eliminó</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'lock': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert"><strong>Se Bloqueó</strong> la Dirección IP con éxito.'.$bot.'</div>'; break;
		case 'unlock': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert"><strong>Se Desbloqueó</strong> la Dirección IP con éxito.'.$bot.'</div>'; break;
		case 'active': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert"><strong>Se Activó</strong> el Registro con éxito.'.$bot.'</div>'; break;
		case 'desactive': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert"><strong>Se Desactivó</strong> el Registro con éxito.'.$bot.'</div>'; break;
		case 'addP': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert">Se Compartió la Publicación con Éxito.'.$bot.'</div>';break;
		case 'addC': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert">Se Agregó el comentario con Éxito.'.$bot.'</div>';break;
		case 'dropP': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert">Se Eliminó la publicación con Éxito.'.$bot.'</div>';break;
		case 'dropC': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert">Se Eliminó el comentario con Éxito.'.$bot.'</div>';break;
		case 'editP': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert">Se Editó la Publicación con Éxito.'.$bot.'</div>';break;
		case 'editC': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert">Se Editó el comentario con Éxito.'.$bot.'</div>';break;
		case 'open': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert"><strong>Se aperturó</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'close': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert"><strong>Se cerró</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'import': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert"><strong>Se importó</strong> la información del CSV con éxito.'.$bot.'</div>'; break;
		case 'asig': $_SESSION['SMStrue'] = $sms='<div class="alert alert-success" role="alert"><strong>Se asignó</strong> la información con éxito.'.$bot.'</div>'; break;
		case 'nohash': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert"><strong>No se logró Encriptar</strong> tu contraseña.'.$bot.'</div>'; break;
		case 'nogenerado': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert"><strong>No se logró generar</strong> la cadena.'.$bot.'</div>'; break;
		case 'noencode': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert"><strong>No se logró codificar</strong> el contenido.'.$bot.'</div>'; break;
		case 'nodecode': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert"><strong>No se logró decodificar</strong> el contenido.'.$bot.'</div>'; break;
		case 'noadd': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert"><strong>No se logró guardar</strong> el registro.'.$bot.'</div>'; break;
		case 'nopagado': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert"><strong>No se logró cobrar</strong> el registro.'.$bot.'</div>'; break;
		case 'nosend': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert"><strong>No se logró enviar</strong> el mensaje.'.$bot.'</div>'; break;
		case 'noedit': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert"><strong>No se logró editar</strong> el registro.'.$bot.'</div>'; break;
		case 'nodrop': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert"><strong>No se logró eliminar</strong> el registro.'.$bot.'</div>'; break;
		case 'nounlock': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert"><strong>No se logró Desbloquear</strong> la Dirección IP.'.$bot.'</div>'; break;
		case 'nolock': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert"><strong>No se logró Bloquear</strong> la Dirección IP.'.$bot.'</div>'; break;
		case 'noactive': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert"><strong>No se logró Activar</strong> el Registro.'.$bot.'</div>'; break;
		case 'nodesactive': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert"><strong>No se logró Desactivar</strong> el Registro.'.$bot.'</div>'; break;
		case 'noopen': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert"><strong>No se logró aperturar</strong> el registro.'.$bot.'</div>'; break;
		case 'noclose': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert"><strong>No se logró cerrar</strong> el registro.'.$bot.'</div>'; break;
		case 'noimport': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert"><strong>No se logró importar</strong> la información del CSV.'.$bot.'</div>'; break;
		case 'noasig': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert"><strong>No se logró asignar</strong> la información.'.$bot.'</div>'; break;
		case 'noaddP': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert">No se Compartió la Publicación.'.$bot.'</div>';break;
		case 'noaddC': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert">No se Agregó el Comentario.'.$bot.'</div>';break;
		case 'nodropP': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert">No se logró Eliminar la Publicación.'.$bot.'</div>';break;
		case 'nodropC': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert">No se logró Eliminar el Comentario.'.$bot.'</div>';break;
		case 'noeditP': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert">No se logró Editar la Publicación.'.$bot.'</div>';break;
		case 'noeditC': $_SESSION['SMSfalse'] = $sms='<div class="alert alert-danger" role="alert">No se logró Editar el Comentario.'.$bot.'</div>';break;
		case 'null': $_SESSION['SMSnull'] = $sms='<div class="alert alert-warning" role="alert"><strong>Se ingresaron campos vacíos.</strong> Intentelo nuevamente.'.$bot.'</div>'; break;
		default:
			if (is_object($_SESSION['stat'])) {
				$_sms = $_SESSION['stat'];
				$sms='<div class="alert alert-'.((isset($_sms->type)) ? $_sms->type : 'success').'" role="alert">';
						$sms.=$_sms->mensaje.' '.$bot;
				$sms.='</div>';
			}else if(is_array($_SESSION['stat'])){
				$_sms = $_SESSION['stat'];
				$sms='<div class="alert alert-'.((isset($_sms['type'])) ? $_sms['type'] : 'success').'" role="alert">';
						$sms.=$_sms['mensaje'].' '.$bot;
				$sms.='</div>';
			}else{
				$sms='<div class="alert alert-info" role="alert">'.$_SESSION['stat'].' '.$bot.'</div>';
			}
		break;
	}
}
//----------------------------------------------------------------------------------------------------------
$sms2="";
if ( isset($_SESSION['stat2']) ) {
	$valorSta=$_SESSION['stat2'];
	switch ($valorSta) {
		case 'hash': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert"><strong>Se encriptó</strong> tu contraseña con éxito.'.$bot.'</div>'; break;
		case 'generado': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert"><strong>Se generó</strong> el cadena con éxito.'.$bot.'</div>'; break;
		case 'encode': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert"><strong>Se codificó</strong> el contenido con éxito.'.$bot.'</div>'; break;
		case 'decode': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert"><strong>Se decodificó</strong> el contenido con éxito.'.$bot.'</div>'; break;
		case 'add': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert"><strong>Se guardó</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'pagado': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert"><strong>Se cobró</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'send': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert"><strong>Se envió</strong> el mensaje con éxito.'.$bot.'</div>'; break;
		case 'edit': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert"><strong>Se editó</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'drop': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert"><strong>Se eliminó</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'lock': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert"><strong>Se Bloqueó</strong> la Dirección IP con éxito.'.$bot.'</div>'; break;
		case 'unlock': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert"><strong>Se Desbloqueó</strong> la Dirección IP con éxito.'.$bot.'</div>'; break;
		case 'active': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert"><strong>Se Activó</strong> el Registro con éxito.'.$bot.'</div>'; break;
		case 'desactive': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert"><strong>Se Desactivó</strong> el Registro con éxito.'.$bot.'</div>'; break;
		case 'addP': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert">Se Compartió la Publicación con Éxito.'.$bot.'</div>';break;
		case 'addC': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert">Se Agregó el comentario con Éxito.'.$bot.'</div>';break;
		case 'dropP': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert">Se Eliminó la publicación con Éxito.'.$bot.'</div>';break;
		case 'dropC': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert">Se Eliminó el comentario con Éxito.'.$bot.'</div>';break;
		case 'editP': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert">Se Editó la Publicación con Éxito.'.$bot.'</div>';break;
		case 'editC': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert">Se Editó el comentario con Éxito.'.$bot.'</div>';break;
		case 'open': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert"><strong>Se aperturó</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'close': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert"><strong>Se cerró</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'import': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert"><strong>Se importó</strong> la información del CSV con éxito.'.$bot.'</div>'; break;
		case 'asig': $_SESSION['SMStrue2'] = $sms2='<div class="alert alert-success" role="alert"><strong>Se asignó</strong> la información con éxito.'.$bot.'</div>'; break;
		case 'nohash': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert"><strong>No se logró Encriptar</strong> tu contraseña.'.$bot.'</div>'; break;
		case 'nogenerado': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert"><strong>No se logró generar</strong> la cadena.'.$bot.'</div>'; break;
		case 'noencode': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert"><strong>No se logró codificar</strong> el contenido.'.$bot.'</div>'; break;
		case 'nodecode': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert"><strong>No se logró decodificar</strong> el contenido.'.$bot.'</div>'; break;
		case 'noadd': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert"><strong>No se logró guardar</strong> el registro.'.$bot.'</div>'; break;
		case 'nopagado': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert"><strong>No se logró cobrar</strong> el registro.'.$bot.'</div>'; break;
		case 'nosend': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert"><strong>No se logró enviar</strong> el mensaje.'.$bot.'</div>'; break;
		case 'noedit': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert"><strong>No se logró editar</strong> el registro.'.$bot.'</div>'; break;
		case 'nodrop': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert"><strong>No se logró eliminar</strong> el registro.'.$bot.'</div>'; break;
		case 'nounlock': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert"><strong>No se logró Desbloquear</strong> la Dirección IP.'.$bot.'</div>'; break;
		case 'nolock': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert"><strong>No se logró Bloquear</strong> la Dirección IP.'.$bot.'</div>'; break;
		case 'noactive': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert"><strong>No se logró Activar</strong> el Registro.'.$bot.'</div>'; break;
		case 'nodesactive': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert"><strong>No se logró Desactivar</strong> el Registro.'.$bot.'</div>'; break;
		case 'noopen': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert"><strong>No se logró aperturar</strong> el registro.'.$bot.'</div>'; break;
		case 'noclose': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert"><strong>No se logró cerrar</strong> el registro.'.$bot.'</div>'; break;
		case 'noimport': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert"><strong>No se logró importar</strong> la información del CSV.'.$bot.'</div>'; break;
		case 'noasig': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert"><strong>No se logró asignar</strong> la información.'.$bot.'</div>'; break;
		case 'noaddP': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert">No se Compartió la Publicación.'.$bot.'</div>';break;
		case 'noaddC': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert">No se Agregó el Comentario.'.$bot.'</div>';break;
		case 'nodropP': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert">No se logró Eliminar la Publicación.'.$bot.'</div>';break;
		case 'nodropC': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert">No se logró Eliminar el Comentario.'.$bot.'</div>';break;
		case 'noeditP': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert">No se logró Editar la Publicación.'.$bot.'</div>';break;
		case 'noeditC': $_SESSION['SMSfalse2'] = $sms2='<div class="alert alert-danger" role="alert">No se logró Editar el Comentario.'.$bot.'</div>';break;
		case 'null': $_SESSION['SMSnull2'] = $sms2='<div class="alert alert-warning" role="alert"><strong>Se ingresarón campos vacios.</strong> Intentelo nuevamente.'.$bot.'</div>'; break;
		default:
			if (is_object($_SESSION['stat2'])) {
				$_sms = $_SESSION['stat2'];
				$sms='<div class="alert alert-'.((isset($_sms->type)) ? $_sms->type : 'success').'" role="alert">';
						$sms.=$_sms->mensaje.' '.$bot;
				$sms.='</div>';
			}else if(is_array($_SESSION['stat2'])){
				$_sms = $_SESSION['stat2'];
				$sms='<div class="alert alert-'.((isset($_sms['type'])) ? $_sms['type'] : 'success').'" role="alert">';
						$sms.=$_sms['mensaje'].' '.$bot;
				$sms.='</div>';
			}else{
				$sms='<div class="alert alert-info" role="alert">'.$_SESSION['stat2'].' '.$bot.'</div>';
			}
		break;
	}
}
//----------------------------------------------------------------------------------------------------------
$sms3="";
if ( isset($_SESSION['stat3']) ) {
	$valorSta=$_SESSION['stat3'];
	switch ($valorSta) {
		case 'hash': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert"><strong>Se encriptó</strong> tu contraseña con éxito.'.$bot.'</div>'; break;
		case 'generado': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert"><strong>Se generó</strong> el cadena con éxito.'.$bot.'</div>'; break;
		case 'encode': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert"><strong>Se codificó</strong> el contenido con éxito.'.$bot.'</div>'; break;
		case 'decode': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert"><strong>Se decodificó</strong> el contenido con éxito.'.$bot.'</div>'; break;
		case 'add': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert"><strong>Se guardó</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'pagado': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert"><strong>Se cobró</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'send': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert"><strong>Se envió</strong> el mensaje con éxito.'.$bot.'</div>'; break;
		case 'edit': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert"><strong>Se editó</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'drop': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert"><strong>Se eliminó</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'lock': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert"><strong>Se Bloqueó</strong> la Dirección IP con éxito.'.$bot.'</div>'; break;
		case 'unlock': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert"><strong>Se Desbloqueó</strong> la Dirección IP con éxito.'.$bot.'</div>'; break;
		case 'active': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert"><strong>Se Activó</strong> el Registro con éxito.'.$bot.'</div>'; break;
		case 'desactive': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert"><strong>Se Desactivó</strong> el Registro con éxito.'.$bot.'</div>'; break;
		case 'addP': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert">Se Compartió la Publicación con Éxito.'.$bot.'</div>';break;
		case 'addC': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert">Se Agregó el comentario con Éxito.'.$bot.'</div>';break;
		case 'dropP': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert">Se Eliminó la publicación con Éxito.'.$bot.'</div>';break;
		case 'dropC': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert">Se Eliminó el comentario con Éxito.'.$bot.'</div>';break;
		case 'editP': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert">Se Editó la Publicación con Éxito.'.$bot.'</div>';break;
		case 'editC': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert">Se Editó el comentario con Éxito.'.$bot.'</div>';break;
		case 'open': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert"><strong>Se aperturó</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'close': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert"><strong>Se cerró</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'import': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert"><strong>Se importó</strong> la información del CSV con éxito.'.$bot.'</div>'; break;
		case 'asig': $_SESSION['SMStrue3'] = $sms3='<div class="alert alert-success" role="alert"><strong>Se asignó</strong> la información con éxito.'.$bot.'</div>'; break;
		case 'nohash': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert"><strong>No se logró Encriptar</strong> tu contraseña.'.$bot.'</div>'; break;
		case 'nogenerado': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert"><strong>No se logró generar</strong> la cadena.'.$bot.'</div>'; break;
		case 'noencode': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert"><strong>No se logró codificar</strong> el contenido.'.$bot.'</div>'; break;
		case 'nodecode': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert"><strong>No se logró decodificar</strong> el contenido.'.$bot.'</div>'; break;
		case 'noadd': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert"><strong>No se logró guardar</strong> el registro.'.$bot.'</div>'; break;
		case 'nopagado': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert"><strong>No se logró cobrar</strong> el registro.'.$bot.'</div>'; break;
		case 'nosend': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert"><strong>No se logró enviar</strong> el mensaje.'.$bot.'</div>'; break;
		case 'noedit': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert"><strong>No se logró editar</strong> el registro.'.$bot.'</div>'; break;
		case 'nodrop': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert"><strong>No se logró eliminar</strong> el registro.'.$bot.'</div>'; break;
		case 'nounlock': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert"><strong>No se logró Desbloquear</strong> la Dirección IP.'.$bot.'</div>'; break;
		case 'nolock': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert"><strong>No se logró Bloquear</strong> la Dirección IP.'.$bot.'</div>'; break;
		case 'noactive': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert"><strong>No se logró Activar</strong> el Registro.'.$bot.'</div>'; break;
		case 'nodesactive': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert"><strong>No se logró Desactivar</strong> el Registro.'.$bot.'</div>'; break;
		case 'noopen': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert"><strong>No se logró aperturar</strong> el registro.'.$bot.'</div>'; break;
		case 'noclose': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert"><strong>No se logró cerrar</strong> el registro.'.$bot.'</div>'; break;
		case 'noimport': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert"><strong>No se logró importar</strong> la información del CSV.'.$bot.'</div>'; break;
		case 'noasig': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert"><strong>No se logró asignar</strong> la información.'.$bot.'</div>'; break;
		case 'noaddP': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert">No se Compartió la Publicación.'.$bot.'</div>';break;
		case 'noaddC': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert">No se Agregó el Comentario.'.$bot.'</div>';break;
		case 'nodropP': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert">No se logró Eliminar la Publicación.'.$bot.'</div>';break;
		case 'nodropC': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert">No se logró Eliminar el Comentario.'.$bot.'</div>';break;
		case 'noeditP': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert">No se logró Editar la Publicación.'.$bot.'</div>';break;
		case 'noeditC': $_SESSION['SMSfalse3'] = $sms3='<div class="alert alert-danger" role="alert">No se logró Editar el Comentario.'.$bot.'</div>';break;
		case 'null': $_SESSION['SMSnull3'] = $sms3='<div class="alert alert-warning" role="alert"><strong>Se ingresarón campos vacios.</strong> Intentelo nuevamente.'.$bot.'</div>'; break;
		default:
			if (is_object($_SESSION['stat3'])) {
				$_sms = $_SESSION['stat3'];
				$sms='<div class="alert alert-'.((isset($_sms->type)) ? $_sms->type : 'success').'" role="alert">';
						$sms.=$_sms->mensaje.' '.$bot;
				$sms.='</div>';
			}else if(is_array($_SESSION['stat3'])){
				$_sms = $_SESSION['stat3'];
				$sms='<div class="alert alert-'.((isset($_sms['type'])) ? $_sms['type'] : 'success').'" role="alert">';
						$sms.=$_sms['mensaje'].' '.$bot;
				$sms.='</div>';
			}else{
				$sms='<div class="alert alert-info" role="alert">'.$_SESSION['stat3'].' '.$bot.'</div>';
			}
		break;
	}
}
//----------------------------------------------------------------------------------------------------------
$sms4="";
if ( isset($_SESSION['stat4']) ) {
	$valorSta=$_SESSION['stat4'];
	switch ($valorSta) {
		case 'hash': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert"><strong>Se encriptó</strong> tu contraseña con éxito.'.$bot.'</div>'; break;
		case 'generado': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert"><strong>Se generó</strong> el cadena con éxito.'.$bot.'</div>'; break;
		case 'encode': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert"><strong>Se codificó</strong> el contenido con éxito.'.$bot.'</div>'; break;
		case 'decode': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert"><strong>Se decodificó</strong> el contenido con éxito.'.$bot.'</div>'; break;
		case 'add': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert"><strong>Se guardó</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'pagado': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert"><strong>Se cobró</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'send': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert"><strong>Se envió</strong> el mensaje con éxito.'.$bot.'</div>'; break;
		case 'edit': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert"><strong>Se editó</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'drop': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert"><strong>Se eliminó</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'lock': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert"><strong>Se Bloqueó</strong> la Dirección IP con éxito.'.$bot.'</div>'; break;
		case 'unlock': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert"><strong>Se Desbloqueó</strong> la Dirección IP con éxito.'.$bot.'</div>'; break;
		case 'active': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert"><strong>Se Activó</strong> el Registro con éxito.'.$bot.'</div>'; break;
		case 'desactive': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert"><strong>Se Desactivó</strong> el Registro con éxito.'.$bot.'</div>'; break;
		case 'addP': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert">Se Compartió la Publicación con Éxito.'.$bot.'</div>';break;
		case 'addC': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert">Se Agregó el comentario con Éxito.'.$bot.'</div>';break;
		case 'dropP': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert">Se Eliminó la publicación con Éxito.'.$bot.'</div>';break;
		case 'dropC': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert">Se Eliminó el comentario con Éxito.'.$bot.'</div>';break;
		case 'editP': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert">Se Editó la Publicación con Éxito.'.$bot.'</div>';break;
		case 'editC': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert">Se Editó el comentario con Éxito.'.$bot.'</div>';break;
		case 'open': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert"><strong>Se aperturó</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'close': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert"><strong>Se cerró</strong> el registro con éxito.'.$bot.'</div>'; break;
		case 'import': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert"><strong>Se importó</strong> la información del CSV con éxito.'.$bot.'</div>'; break;
		case 'asig': $_SESSION['SMStrue4'] = $sms4='<div class="alert alert-success" role="alert"><strong>Se asignó</strong> la información con éxito.'.$bot.'</div>'; break;
		case 'nohash': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert"><strong>No se logró Encriptar</strong> tu contraseña.'.$bot.'</div>'; break;
		case 'nogenerado': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert"><strong>No se logró generar</strong> la cadena.'.$bot.'</div>'; break;
		case 'noencode': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert"><strong>No se logró codificar</strong> el contenido.'.$bot.'</div>'; break;
		case 'nodecode': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert"><strong>No se logró decodificar</strong> el contenido.'.$bot.'</div>'; break;
		case 'noadd': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert"><strong>No se logró guardar</strong> el registro.'.$bot.'</div>'; break;
		case 'nopagado': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert"><strong>No se logró cobrar</strong> el registro.'.$bot.'</div>'; break;
		case 'nosend': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert"><strong>No se logró enviar</strong> el mensaje.'.$bot.'</div>'; break;
		case 'noedit': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert"><strong>No se logró editar</strong> el registro.'.$bot.'</div>'; break;
		case 'nodrop': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert"><strong>No se logró eliminar</strong> el registro.'.$bot.'</div>'; break;
		case 'nounlock': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert"><strong>No se logró Desbloquear</strong> la Dirección IP.'.$bot.'</div>'; break;
		case 'nolock': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert"><strong>No se logró Bloquear</strong> la Dirección IP.'.$bot.'</div>'; break;
		case 'noactive': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert"><strong>No se logró Activar</strong> el Registro.'.$bot.'</div>'; break;
		case 'nodesactive': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert"><strong>No se logró Desactivar</strong> el Registro.'.$bot.'</div>'; break;
		case 'noopen': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert"><strong>No se logró aperturar</strong> el registro.'.$bot.'</div>'; break;
		case 'noclose': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert"><strong>No se logró cerrar</strong> el registro.'.$bot.'</div>'; break;
		case 'noimport': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert"><strong>No se logró importar</strong> la información del CSV.'.$bot.'</div>'; break;
		case 'noasig': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert"><strong>No se logró asignar</strong> la información.'.$bot.'</div>'; break;
		case 'noaddP': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert">No se Compartió la Publicación.'.$bot.'</div>';break;
		case 'noaddC': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert">No se Agregó el Comentario.'.$bot.'</div>';break;
		case 'nodropP': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert">No se logró Eliminar la Publicación.'.$bot.'</div>';break;
		case 'nodropC': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert">No se logró Eliminar el Comentario.'.$bot.'</div>';break;
		case 'noeditP': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert">No se logró Editar la Publicación.'.$bot.'</div>';break;
		case 'noeditC': $_SESSION['SMSfalse4'] = $sms4='<div class="alert alert-danger" role="alert">No se logró Editar el Comentario.'.$bot.'</div>';break;
		case 'null': $_SESSION['SMSnull4'] = $sms4='<div class="alert alert-warning" role="alert"><strong>Se ingresarón campos vacios.</strong> Intentelo nuevamente.'.$bot.'</div>'; break;
		default:
			if (is_object($_SESSION['stat4'])) {
				$_sms = $_SESSION['stat4'];
				$sms='<div class="alert alert-'.((isset($_sms->type)) ? $_sms->type : 'success').'" role="alert">';
						$sms.=$_sms->mensaje.' '.$bot;
				$sms.='</div>';
			}else if(is_array($_SESSION['stat4'])){
				$_sms = $_SESSION['stat4'];
				$sms='<div class="alert alert-'.((isset($_sms['type'])) ? $_sms['type'] : 'success').'" role="alert">';
						$sms.=$_sms['mensaje'].' '.$bot;
				$sms.='</div>';
			}else{
				$sms='<div class="alert alert-info" role="alert">'.$_SESSION['stat4'].' '.$bot.'</div>';
			}
		break;
	}
}
//----------------------------------------------------------------------------------------------------------
if (isset($_SESSION['Mysqli_Error'])) { $Mysqli_Error=$_SESSION['Mysqli_Error']; }else{ $Mysqli_Error=''; }
//----------------------------------------------------------------------------------------------------------
$er="";$eru="";$erp="";
if (isset($_SESSION['error'])) {
	switch ($_SESSION['error']) {
		case 2: $erp='Error: Su contraseña es Incorrecta'; break;
		case 3: $er='Error: Su Usuario está suspendido.'; break;
		case 4: $eru='Error: No se encontró el Usuario.'; break;
		case 5: $er='Error: No se ejecutó la Consulta. '.$Mysqli_Error; break;
		case 6: $er='Error: Se ingresaron caracteres no permitidos.'; break;
		case 7: $er='Error: Su usuario no tiene permisos para acceder al sistema.'; break;
		default: $er=$_SESSION['error']; break;
	}
	unset($_SESSION['error']);
}
//----------------------------------------------------------------------------------------------------------
$edit=null;
if (isset($_SESSION['editado'])) {
	$edit='Su informaión fue editada con éxito.<br>Por Favor vuelve a ingresar al sistema.';
	unset($_SESSION['editado']);
}