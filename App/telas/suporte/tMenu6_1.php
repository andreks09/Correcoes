<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Tickets Encerrados</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="col-md-2">
            <div class="form-group">
                <label>Ano</label>
                <select class="form-control" name="ano" id="ano" form="f1">
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                </select>
            </div>
        </div>
    </div>
        <div class="card-footer">
            <form action="<?php echo $sConfiguracao->getDiretorioVisualizacaoAcesso() ?>tPainel.php?menu=6_1_1" method="post" enctype="multipart/form-data" name="f1" id="f1">
                <input type="hidden" value="f1" name="formulario" form="f1">
                <input type="hidden" value="consultar" name="acao" form="f1">
                <input type="hidden" value="menu6_1" name="pagina" form="f1">
                <button type="submit" class="btn btn-primary">Consultar</button>
            </form>
        </div>
  
    <!-- /.card-body -->
</div>