<?php
use App\sistema\acesso\{
    sConfiguracao,
    sDepartamento
};

$sConfiguracao = new sConfiguracao();

//busca dados das departamentos no bd
$sDepartamento = new sDepartamento(0);
$sDepartamento->consultar('tMenu4_2_2.php');

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
                                <label>Departamento/ Gerência/ Assessoria</label>
                                <select class="form-control" name="departamento" id="departamento" form="f1">
                                    <?php
                                    foreach ($sDepartamento->mConexao->getRetorno() as $value) {                                            
                                        echo '<option value="' . $value['iddepartamento'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="<?php echo $sConfiguracao->getDiretorioVisualizacaoAcesso() ?>tPainel.php?menu=4_2_2_1" method="post" id="f1" name="f1" enctype="multipart/form-data">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <input type="hidden" name="pagina" id="pagina" value="tMenu4_2_2.php" form="f1">
                        <input type="hidden" name="formulario" id="formulario" value="f1" form="f1">
                        <button type="submit" class="btn btn-primary">Alterar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>