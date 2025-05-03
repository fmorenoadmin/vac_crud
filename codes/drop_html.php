<table class="table table-bordered table-hover table-info">
    <?php $data->list_drop->inf = $_SESSION['list_drop']; ?>
</table>
<!--modal-drop-->
    <div class="modal fade" id="eliminar" tabindex="-1" role="dialog" aria-labelledby="agregarUsuarioModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form method="POST" action="<?= ACTI.$action; ?>" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="agregarUsuarioModalLabel">Eliminar Usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="obs">Eliminar a:</label>
                                    <label class="form-control" id="eliminar_title"></label>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="obs">Motivo de Eliminación</label>
                                    <textarea class="form-control" name="motivo_drop" required="true"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="eliminar_pid" name="pid" />
                        <input type="hidden" name="uid" value="<?= base64_encode($uid); ?>" />
                        <input type="hidden" name="url" value="<?= base64_encode($location); ?>" />
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" name="eliminar">Eliminar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!--end-modal-drop-->
<script type="text/javascript">
    function eliminar(dats){
        var inf = dats.split('||');
        //---------------------------------
        $( "#eliminar_pid" ).val(inf[0]);
        $( "#eliminar_title" ).html(atob(inf[1]));
    }
</script>