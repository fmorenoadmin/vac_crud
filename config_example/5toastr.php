<?php 
function clear_sms($sms){
	$_tmp = str_replace(
		array(
			'<div class="alert alert-success" role="alert">',
			'<div class="alert alert-danger" role="alert">',
			'<div class="alert alert-warning" role="alert">',
			'<div class="alert alert-info" role="alert">',
			'<div class="alert alert-secondary" role="alert">',
			'<div class="alert alert-dark" role="alert">',
			'<div class="alert alert-light" role="alert">',
			'</div>'
		), 
		'', 
		$sms
	);
	//---------------------------------------------
	return $_tmp;
}
//---------------------------------------------
if(isset($_SESSION['Mysqli_Error'])){ 
	if (is_array($_SESSION['Mysqli_Error'])) {
		error_log(json_encode($_SESSION['Mysqli_Error']));
	}else{
		if (strlen($_SESSION['Mysqli_Error']) >= 5){
			?>
<script type="text/javascript">
	toastr.error(`<?= clear_sms($_SESSION['Mysqli_Error']); ?>`);
</script>
			<?php
		}
	}
	unset($_SESSION['Mysqli_Error']); 
}
//---------------------------------------------
//<!--TRUE-->
//---------------------------------------------
if(isset($_SESSION['SMStrue'])){
	if (strlen($_SESSION['SMStrue']) >= 5){
		?>
<script type="text/javascript">
	toastr.success(`<?= clear_sms($_SESSION['SMStrue']); ?>`);
</script>
		<?php 
	}
	unset($_SESSION['SMStrue']); 
}
//---------------------------------------------
if(isset($_SESSION['SMStrue2'])){ 
	if (strlen($_SESSION['SMStrue2']) >= 5){
		?>
<script type="text/javascript">
	toastr.success(`<?= clear_sms($_SESSION['SMStrue2']); ?>`);
</script>
		<?php 
	} 
	unset($_SESSION['SMStrue2']); 
}
//---------------------------------------------
if(isset($_SESSION['SMStrue3'])){ 
	if (strlen($_SESSION['SMStrue3']) >= 5){
		?>
<script type="text/javascript">
	toastr.success(`<?= clear_sms($_SESSION['SMStrue3']); ?>`);
</script>
		<?php 
	}
	unset($_SESSION['SMStrue3']);
}
//---------------------------------------------
if(isset($_SESSION['SMStrue4'])){
	if (strlen($_SESSION['SMStrue4']) >= 5){
		?>
<script type="text/javascript">
	toastr.success(`<?= clear_sms($_SESSION['SMStrue4']); ?>`);
</script>
		<?php
	}
	unset($_SESSION['SMStrue4']);
}
//---------------------------------------------
//<!--FLASE-->
//---------------------------------------------
if(isset($_SESSION['SMSfalse'])){
	if (strlen($_SESSION['SMSfalse']) >= 5){
		?>
<script type="text/javascript">
	toastr.error(`<?= clear_sms($_SESSION['SMSfalse']); ?>`);
</script>
		<?php
	}
	unset($_SESSION['SMSfalse']);
}
//---------------------------------------------
if(isset($_SESSION['SMSfalse2'])){
	if (strlen($_SESSION['SMSfalse2']) >= 5){
		?>
<script type="text/javascript">
	toastr.error(`<?= clear_sms($_SESSION['SMSfalse2']); ?>`);
</script>
		<?php
	}
	unset($_SESSION['SMSfalse2']);
}
//---------------------------------------------
if(isset($_SESSION['SMSfalse3'])){
	if (strlen($_SESSION['SMSfalse3']) >= 5){
		?>
<script type="text/javascript">
	toastr.error(`<?= clear_sms($_SESSION['SMSfalse3']); ?>`);
</script>
		<?php
	}
	unset($_SESSION['SMSfalse3']);
}
//---------------------------------------------
if(isset($_SESSION['SMSfalse4'])){
	if (strlen($_SESSION['SMSfalse4']) >= 5){
		?>
<script type="text/javascript">
	toastr.error(`<?= clear_sms($_SESSION['SMSfalse4']); ?>`);
</script>
		<?php
	}
	unset($_SESSION['SMSfalse4']);
}
//---------------------------------------------
//<!--NULL-->
//---------------------------------------------
if(isset($_SESSION['SMSnull'])){
	if (strlen($_SESSION['SMSnull']) >= 5){
		?>
<script type="text/javascript">
	toastr.warning(`<?= clear_sms($_SESSION['SMSnull']); ?>`);
</script>
		<?php
	}
	unset($_SESSION['SMSnull']);
}
//---------------------------------------------
if(isset($_SESSION['SMSnull2'])){
	if (strlen($_SESSION['SMSnull2']) >= 5){
		?>
<script type="text/javascript">
	toastr.warning(`<?= clear_sms($_SESSION['SMSnull2']); ?>`);
</script>
		<?php
	}
	unset($_SESSION['SMSnull2']);
}
//---------------------------------------------
if(isset($_SESSION['SMSnull3'])){
	if (strlen($_SESSION['SMSnull3']) >= 5){
		?>
<script type="text/javascript">
	toastr.warning(`<?= clear_sms($_SESSION['SMSnull3']); ?>`);
</script>
		<?php
	}
	unset($_SESSION['SMSnull3']);
}
//---------------------------------------------
if(isset($_SESSION['SMSnull4'])){
	if (strlen($_SESSION['SMSnull4']) >= 5){
		?>
<script type="text/javascript">
	toastr.warning(`<?= clear_sms($_SESSION['SMSnull4']); ?>`);
</script>
		<?php
	}
	unset($_SESSION['SMSnull4']);
}
//---------------------------------------------
//<!--DG-ERROR-->
//---------------------------------------------
if(isset($_SESSION['dg_error'])){
	if (strlen($_SESSION['dg_error']) >= 5){
		?>
<script type="text/javascript">
	toastr.error(`<?= clear_sms($_SESSION['dg_error']); ?>`);
</script>
		<?php
	}
	unset($_SESSION['dg_error']);
}
//---------------------------------------------
//<!--EMAIL-->
//---------------------------------------------
if(isset($_SESSION['mensjEmail'])){ 
	if ($_SESSION['mensjEmail']=="send"){
		?>
<script type="text/javascript">
	toastr.success(`Su correo electrónico fué enviado con éxito.`);
</script>
		<?php
	}else{
		?>
<script type="text/javascript">
	toastr.error(`Lo sentimos no se logró enviar su correo electrónico.`);
</script>
		<?php
	}
	unset($_SESSION['mensjEmail']);
}
//---------------------------------------------
if (isset($_SESSION['stat'])) { unset($_SESSION['stat']); }
if (isset($_SESSION['stat2'])) { unset($_SESSION['stat2']); }
if (isset($_SESSION['stat3'])) { unset($_SESSION['stat3']); }
if (isset($_SESSION['stat4'])) { unset($_SESSION['stat4']); }