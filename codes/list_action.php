<?php
    if(isset($_SESSION)){}else{ session_start(); }
    //-----------------------------------
    $ru0='../';
    //-----------------------------------
    $cls = array(
        "dbs"    =>    'database',
        "cl1"    =>    'usuarios',
    );
    //-----------------------------------
    $json = new stdClass();
    //-----------------------------------
        $_tbl = new stdClass();
        $_tbl->tname = $cls['cl1'];
        $_tbl->tid = 'id_u';
        $_tbl->pid = 0;
        $_tbl->success = 'add';
        $_tbl->danger = 'no'.$_tbl->success;
        $_tbl->test = true;
    //-----------------------------------
        function index($rut,$rid,$uid,$url){
            global $cls;
            require($rut.DIRCLA.$cls['dbs'].'.php');
            require_once($rut.DIRCLA.$cls['cl1'].'.php');
            $_dbs = new $cls['dbs']();
            $_cl1 = new $cls['cl1']();
            $data = new stdClass();
            //-----------------------------------
            $data->inf = $_cl1->listar($rid,$uid,$url);
            //-----------------------------------
            return $data;
        }