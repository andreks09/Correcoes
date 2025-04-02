<?php
use App\sistema\acesso\{
    sConfiguracao,
    sCoordenacao
};

$sConfiguracao = new sConfiguracao();

//busca dados das coordenacaos no bd
$sCoordenacao = new sCoordenacao(0);
$sCoordenacao->consultar('tMenu4_2_3.php');

?>
<div class="container-fluid">
    <div class="row">        
        <!--registro deaprtamento/ unidade-->
        <div class="col-md-4">
            <!-- general form elements -->
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Local</h3>
                </div>
                <!-- form start -->
                <div class="card-body">
                    <div class="row">                      
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Coordenacao</label>
                                <select class="form-control" name="coordenacao" id="coordenacao" form="f1">
                                    <?php
                                    foreach ($sCoordenacao->mConexao->getRetorno() as $value) {                                            
                                        echo '<option value="' . $value['idcoordenacao'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="<?php echo $sConfiguracao->getDiretorioVisualizacaoAcesso() ?>tPainel.php?menu=4_2_3_1" method="post" id="f1" name="f1" enctype="multipart/form-data">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <input type="hidden" name="pagina" id="pagina" value="tMenu4_2_3.php" form="f1">
                        <input type="hidden" name="formulario" id="formulario" value="f1" form="f1">
                        <button type="submit" class="btn btn-primary">Alterar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>