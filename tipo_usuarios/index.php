<?php
	if(isset($_SESSION)){ }else{ session_start(); }
	//------------------------------------------------
	$rut='../';//a que nivel de raiz me encuentro
	//------------------------------------------------
	require_once($rut.'config/constant.php');
	//------------------------------------------------
	$pagina = 'Tipos de Usuarios';
	$singlr = 'Tipo de Usuario';
	$action = 'tipo_usuarios.php';
	//------------------------------------------------
	require_once($rut.DIRACT.$action);
	$data = index($rut,$rid,$uid,$location);
	//------------------------------------------------
	$inf = $data->inf;
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?= $pagina; ?></title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" integrity="sha512-Tn2m0TIpgVyTzzvmxLNuqbSJH3JP8jm+Cy3hvHrW7ndTDcJ1w5mBiksqDBb8GpE2ksktFvDB/ykZ0mDpsZj20w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<style>
		.code-container {
			background-color: #f8f9fa;
			border: 1px solid #ced4da;
			padding: 15px;
			border-radius: 5px;
			margin-bottom: 10px;
			font-family: monospace;
			font-size: 14px;
			white-space: pre-wrap;
			min-height: 450px;
			max-height: 450px;
			overflow: auto;
		}
	</style>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-4"></div>
			<div class="col-sm-4 text-center"><h3 class="title"><?= $pagina; ?></h3></div>
			<div class="col-sm-4">
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#nuevo">
					Agregar <?= $singlr; ?>
				</button>
			</div>
			<hr>
			<div class="col-sm-12" style="overflow: auto;">
				<div class="row">
					<div class="col-sm-12" style="overflow: auto;">
						<table id="listaDatos1" class="table table-bordered table-hover table-info"><?= $inf->inf; ?></table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<?php include_once($rut.'codes/modals_tipo_usuarios.php'); ?>
</body>
</html>