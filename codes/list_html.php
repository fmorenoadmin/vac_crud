<?php
    if(isset($_SESSION)){ }else{ session_start(); }
    //------------------------------------------------
    $rut='./';
    //------------------------------------------------
    require_once($rut.'config/constant.php');
    //------------------------------------------------
    $pagina = 'Visualizador de Código';
    $action = 'usuarios.php';
    //------------------------------------------------
    require_once($rut.DIRACT.$action);
    $data = index($rut,$rid,$uid,$location);
    //------------------------------------------------
    $inf = $data->inf;
?>
<div class="row">
    <div class="col-sm-12" style="overflow: auto;">
        <table class="table table-bordered table-hover table-info"><?= $inf->inf; ?></table>
    </div>
</div>