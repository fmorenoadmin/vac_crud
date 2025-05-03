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
        if (isset($_POST['nuevo'])) {
            require_once($ru0.'config/constant.php');
            //----------------------------------------
            if (isset($_SESSION['user_id'])) {
                require($ru0.DIRCLA.$cls['dbs'].'.php');
                require_once($ru0.DIRCLA.$cls['cl1'].'.php');
                $_dbs = new $cls['dbs']();
                $_cl1 = new $cls['cl1']();
                //-----------------------------------
                $add = array(
                    "nombres" => $_dbs->custom_escape_string($_POST['nombres']),
                    "apellidos" => $_dbs->custom_escape_string($_POST['apellidos']),
                    "usuario" => $_dbs->custom_escape_string($_POST['usuario']),
                    "correo" => $_dbs->custom_escape_string($_POST['correo']),
                    "obs" => str_replace("'", '´', $_POST['obs']),
                    "created_at" => date('Y-m-d H:i:s'),
                    "id_created" => base64_decode($_POST['uid']),
                    "status" => ((isset($_POST['status'])) ? $_POST['status'] : 1),
                );
                //-----------------------------------
                $url = base64_decode($_POST['url']);
                //-----------------------------------
                $resp = $_dbs->db_add($add,$_tbl);
                $_SESSION['stat'] = $resp;
                if ($resp->result) {
                    $_SESSION['SMStrue'] = $resp->mensaje;
                }else{
                    $_SESSION['SMSfalse'] = $resp->mensaje;
                }
                if (isset($_tbl->test) && $_tbl->test==true) {
                    $_SESSION['sql'] = $resp->sql;
                }
                //-----------------------------------
                $_POST = null;
                //-----------------------------------
                header("Location: ".$url);
                exit();
            }else{
                header("Location: ".E403);
                exit();
            }
        }