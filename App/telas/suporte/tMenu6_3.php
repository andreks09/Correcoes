<div class="card card-default">
    <div class="card-header">         
        <h3 class="card-title">
            <i class="fas fa-bullhorn"></i>
            Informativo
        </h3>
    </div>
    <div class="card-body">
        <div class="col-md-2">
            <form action="<?php echo $sConfiguracao->getDiretorioVisualizacaoAcesso(); ?>tPainel.php" method="get" enctype="multipart/form-data" name="f1" id="f1">
                <div class="form-group">
                    <input type="hidden" name="menu" id="menu" value="6_3_1" form="f1">
                    <button type="submit" class="btn btn-block btn-dark btn-lg">Tickets Encerrados</button>
                </div>
            </form>
        </div>
    </div>
</div>