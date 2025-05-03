<?php
    /**
     * 
     */
    class usuarios extends database
    {
        private $table    ='usuarios';
        private $table0    ='';
        private $table1    ='';
        private $table2    ='';
        private $actio    ='usuarios.php';
        private $detail    ='detalle/?p=';
        private $tid    ="id_u";
        private $tid1    ="";
        private $tid2    ="";
        //----------------------------------
            function listar($rid,$uid,$url,$test=false){
                $fc_query=$this->db_query;$fc_error=$this->db_error;$fc_array=$this->db_array;$fc_object=$this->db_object;$fc_assoc=$this->db_assoc;$fc_num_r=$this->db_num_r;$fc_fre_r=$this->db_fre_r;$fc_close=$this->db_close;
                //---------------------------------------------------------
                $data = new stdClass();
                $inf = null; $n=1; $cant = 11; $data->error = null;
                //--------------------------------
                $inf.='<thead style="width: 100%;">';
                    $inf.='<tr>';
                        $inf.='<th><i class="fas fa-list-ol"></i></th>';
                        $inf.='<th><i class="fas fa-users-cog"></i></th>';
                        $inf.='<th><i class="fas fa-id-badge"></i></th>';
                        $inf.='<th>Nombres</th>';
                        $inf.='<th>Apellidos</th>';
                        $inf.='<th>Usuario</th>';
                        $inf.='<th>Correo</th>';
                        $inf.='<th>Observaciones</th>';
                        $inf.='<th>Creado</th>';
                        $inf.='<th>Editado</th>';
                        $inf.='<th>Estado</th>';
                    $inf.='</tr>';
                $inf.='</thead>';
                $inf.='<tbody style="width: 100%;">';
                    $sql = "SELECT * FROM {$this->table} WHERE status<>2 ORDER BY {$this->tid} DESC ;";
                    //--------------------------------
                    $res = $this->db_exec($sql);
                    if ($res->result==true && $res->cant > 0) {
                        $data->result = true;
                        $data->mensaje = 'Registros encontrados.';
                        //--------------------------------
                        while ($row = $fc_assoc($res->res)) {
                            $status = $row['status'];
                            //-------------------------------------
                            $datos2 = base64_encode($row[$this->tid]).'||'.base64_encode(utf8_decode($row['nombres'].' '.$row['apellidos']));
                            //-------------------------------------
                            $inf.='<tr>';
                                $inf.='<td>'.$n.'</td>';
                                $inf.='<td>'.$row[$this->tid].'</td>';
                                $inf.='<td>';
                                    $inf .= '<a href="'.$this->detail.base64_encode($row[$this->tid]).'" target="_blank" class="btn btn-xs btn-warning" >';
                                        $inf .= '<i class="fa fa-edit" ></i>';
                                    $inf .= '</a> ';
                                    if ($rid==1 || $rid==2) {
                                        $inf .= '<button type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#eliminar" onclick="eliminar('."'".$datos2."'".');" >';
                                            $inf .= '<i class="fas fa-trash"></i>';
                                        $inf .= '</button>';
                                    }
                                $inf.='</td>';
                                $inf.='<td>'.$row['nombres'].'</td>';
                                $inf.='<td>'.$row['apellidos'].'</td>';
                                $inf.='<td>'.$row['usuario'].'</td>';
                                $inf.='<td>'.$row['correo'].'</td>';
                                $inf.='<td>'.$row['obs'].'</td>';
                                $inf.='<td>'.$row['created_at'].'</td>';
                                $inf.='<td>'.$row['updated_at'].'</td>';
                                $inf.='<td>';
                                    if ($rid==1 || $rid==2 || $rid==4) {
                                        switch ($status) {
                                            case 1:
                                                $inf.='<a href="'.ACTI.$this->actio.'?pid='.base64_encode($row[$this->tid]).'&meth=des&url='.base64_encode($url).'" class="btn btn-xs btn-block btn-outline-success btn-flat"><i class="fas fa-check-circle"></i></a> ';
                                            break;
                                            case 2:
                                                $inf.='<a href="'.ACTI.$this->actio.'?pid='.base64_encode($row[$this->tid]).'&meth=act&url='.base64_encode($url).'" class="btn btn-xs btn-block btn-outline-danger btn-flat"><i class="fas fa-times-circle"></i></a> ';
                                            break;
                                            default:
                                                $inf.='<a href="'.ACTI.$this->actio.'?pid='.base64_encode($row[$this->tid]).'&meth=act&url='.base64_encode($url).'" class="btn btn-xs btn-block btn-outline-warning btn-flat"><i class="fas fa-ban"></i></a> ';
                                            break;
                                        }
                                    }else{
                                        switch ($status) {
                                            case 1:
                                                $inf.='<span class="btn btn-xs btn-block btn-outline-success btn-flat"><i class="fas fa-check"></i></span> ';
                                            break;
                                            case 2:
                                                $inf.='<span class="btn btn-xs btn-block btn-outline-danger btn-flat"><i class="fas fa-times"></i></span> ';
                                            break;
                                            default:
                                                $inf.='<span class="btn btn-xs btn-block btn-outline-warning btn-flat"><i class="fas fa-ban"></i></span> ';
                                            break;
                                        }
                                    }
                                $inf.='</td>';
                            $inf.='</tr>';
                            //---------------------------------
                            $n++;
                        }
                        //--------------------------------
                        $fc_fre_r($res->res);
                    }else{
                        if ($res->cant == 0) {
                            $data->result = false;
                            $data->mensaje = 'No hay registros.';
                            $inf .= '';
                        }else{
                            $data->result = false;
                            $data->mensaje = 'No se ejecutó la consulta. Error: '.$res->error;
                            $inf .= '';
                        }
                    }
                $inf.='</tbody>';
                //--------------------------------
                $data->inf = $inf;
                $data->cant = $res->cant;
                if (isset($test) && $test==true) {
                    $data->sql = $sql;
                }
                //--------------------------------
                $fc_close($this->connect());
                return $data;
            }
        //----------------------------------
    }