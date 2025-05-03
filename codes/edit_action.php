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
        function detalle($rut,$rid,$pid){
            global $cls,$_tbl;
            require($rut.DIRCLA.$cls['dbs'].'.php');
            require_once($rut.DIRCLA.$cls['cl1'].'.php');
            $_dbs = new $cls['dbs']();
            $_cl1 = new $cls['cl1']();
            $data = new stdClass();
            //-----------------------------------
            $_tbl->pid = $pid;
            //-----------------------------------
            $data->call = $_cl1->db_get_id(null,$_tbl);
            //-----------------------------------
            return $data;
        }
    //-----------------------------------
        if (isset($_POST['editar'])) {
            require_once($ru0.'config/constant.php');
            //----------------------------------------
            if (isset($_SESSION['user_id'])) {
                require($ru0.DIRCLA.$cls['dbs'].'.php');
                require_once($ru0.DIRCLA.$cls['cl1'].'.php');
                $_dbs = new $cls['dbs']();
                $_cl1 = new $cls['cl1']();
                //-----------------------------------
                $_tbl->pid = base64_decode($_POST['pid']);
                $_tbl->success = 'edit';
                $_tbl->danger = 'no'.$_tbl->success;
                //----------------------------------------
                $edit = array(
                    "nombres" => $_dbs->custom_escape_string($_POST['nombres']),
                    "apellidos" => $_dbs->custom_escape_string($_POST['apellidos']),
                    "usuario" => $_dbs->custom_escape_string($_POST['usuario']),
                    "correo" => $_dbs->custom_escape_string($_POST['correo']),
                    "obs" => str_replace("'", '´', $_POST['obs']),
                    "updated_at" => date('Y-m-d H:i:s'),
                    "id_updated" => base64_decode($_POST['uid']),
                    "status" => ((isset($_POST['status'])) ? $_POST['status'] : 1),
                );
                //-----------------------------------
                $url = base64_decode($_POST['url']);
                //-----------------------------------
                $resp = $_dbs->db_edit($edit,$_tbl);
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