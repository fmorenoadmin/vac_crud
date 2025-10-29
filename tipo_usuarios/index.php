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
		<div class="accordion" id="codigoAccordion">
			<div class="card">
				<div class="card-header" id="headingListar">
					<h2 class="mb-0">
						<button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseListar" aria-expanded="true" aria-controls="collapseListar">
							1.- Listar
						</button>
					</h2>
				</div>
				<div id="collapseListar" class="collapse show" aria-labelledby="headingListar" data-parent="#codigoAccordion">
					<div class="card-body">
						<div class="row">
							<div class="col-sm-4"></div>
							<div class="col-sm-4 text-center"><h3 class="title">Resultado HTML</h3></div>
							<div class="col-sm-4"></div>
							<hr>
							<div class="col-sm-12" style="overflow: auto;">
								<table id="listaDatos1" class="table table-bordered table-hover table-info"><?= $inf->inf; ?></table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header" id="headingAgregar">
					<h2 class="mb-0">
						<button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseAgregar" aria-expanded="true" aria-controls="collapseAgregar">
							2.- Agregar
						</button>
					</h2>
				</div>
				<div id="collapseAgregar" class="collapse" aria-labelledby="headingAgregar" data-parent="#codigoAccordion">
					<div class="card-body">
						<div class="row">
							<div class="col-sm-4"></div>
							<div class="col-sm-4 text-center"><h3 class="title">Resultado HTML</h3></div>
							<div class="col-sm-4"></div>
							<hr>
							<div class="col-sm-12" style="overflow: auto;">
								<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#nuevo">
									Agregar <?= $singlr; ?>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header" id="headingEditar">
					<h2 class="mb-0">
						<button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseEditar" aria-expanded="false" aria-controls="collapseEditar">
							3.- Editar
						</button>
					</h2>
				</div>
				<div id="collapseEditar" class="collapse" aria-labelledby="headingEditar" data-parent="#codigoAccordion">
					<div class="card-body">
						<div class="row">
							<div class="col-sm-4"></div>
							<div class="col-sm-4 text-center"><h3 class="title">Acciones de la tabla HTML:<br>Editar</h3></div>
							<div class="col-sm-4"></div>
							<hr>
							<div class="col-sm-12" style="overflow: auto;">
								<table class="table table-bordered table-hover table-info"><?= $data->edit->inf; ?></table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="card">
				<div class="card-header" id="headingEliminar">
					<h2 class="mb-0">
						<button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseEliminar" aria-expanded="false" aria-controls="collapseEliminar">
							4.- Activar / Desactivar / Eliminar
						</button>
					</h2>
				</div>
				<div id="collapseEliminar" class="collapse" aria-labelledby="headingEliminar" data-parent="#codigoAccordion">
					<div class="card-body">
						<div class="row">
							<div class="col-sm-4"></div>
							<div class="col-sm-4 text-center"><h3 class="title">Acciones de la tabla HTML:<br>Eliminar - Activar - Desactivar</h3></div>
							<div class="col-sm-4"></div>
							<hr>
							<div class="col-sm-12" style="overflow: auto;">
								<table class="table table-bordered table-hover table-info"><?= $data->drop->inf; ?></table>
							</div>
						</div>
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