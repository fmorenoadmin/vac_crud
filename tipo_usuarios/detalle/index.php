<?php
    if(isset($_SESSION)){ }else{ session_start(); }
    //------------------------------------------------
    $rut='../../';
    //------------------------------------------------
    require_once($rut.'config/constant.php');
    //------------------------------------------------
    $pagina = 'Detalle del Tipo de usuario';
    $singlr = 'Tipo de Usuario';
    $action = 'tipo_usuarios.php';
    //------------------------------------------------
    require_once($rut.DIRACT.$action);
    $data = detalle($rut,$rid,$pid);
    //------------------------------------------------
    $call = $data->call;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $pagina; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" integrity="sha512-Tn2m0TIpgVyTzzvmxLNuqbSJH3JP8jm+Cy3hvHrW7ndTDcJ1w5mBiksqDBb8GpE2ksktFvDB/ykZ0mDpsZj20w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
    <div class="container-fluid">
        <form method="POST" action="<?= ACTI.$action; ?>" enctype="multipart/form-data" class="card row">
            <div class="card-head">
                <h5 class="card-title" id="agregarUsuarioModalLabel">Editar <?= $singlr; ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="nombres">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $call->nombre; ?>" />
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="obs">Observaciones</label>
                            <textarea class="form-control" id="obs" name="obs"><?= $call->obs; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <input type="hidden" name="pid" value="<?= base64_encode($pid); ?>" />
                <input type="hidden" name="uid" value="<?= base64_encode($uid); ?>" />
                <input type="hidden" name="url" value="<?= base64_encode($location); ?>" />
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-success" name="editar">Guardar Cambios</button>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>