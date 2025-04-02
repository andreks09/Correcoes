<?php

use App\sistema\acesso\{
    sNotificacao,
    sConfiguracao,
    sSair
};
use App\sistema\suporte\{
    sEquipamento,
    sCategoria,
    sModelo,
    sMarca
};

if(!isset($_GET['seguranca'])){
    //solicitar saída com tentativa de violação
    $sSair = new sSair();
    $sSair->verificar('0');
}

$idSolicitacao = base64_decode($_GET['seguranca']);

$sConfiguracao = new sConfiguracao();

?>
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Etapa 1 - Equipamento</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">                            
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Aprovar solicitação?</label>
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input class="custom-control-input" type="checkbox" name="situacao" id="situacao" checked="" onclick="decisao();" form="f1">
                                    <label class="custom-control-label" for="situacao">
                                        <div class="conteudo" name="conteudo" id="conteudo">Sim</div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        if (isset($tipo) &&
                            isset($titulo) &&
                            isset($mensagem)) {
                            if (isset($alertaCategoria) ||
                                isset($alertaEquipamento) ||
                                isset($alertaSistema)) {
                            echo <<<HTML
                            <div class="col-mb-3">
                                <div class="card card-outline card-{$tipo}">
                                    <div class="card-header">
                                        <h3 class="card-title">{$titulo}</h3>
                                    </div>
                                    <div class="card-body">
                                        {$mensagem}
                                    </div>
                                </div>
                            </div>
HTML;
                            }
                        }
                    ?>
                    
                </div>
                <form action="<?php echo $sConfiguracao->getDiretorioControleAcesso(); ?>sAprovarAcesso.php" method="post" enctype="multipart/form-data" name="f1" id="f1">
                    <input type="hidden" value="f1" name="formulario" id="formulario" form="f1">
                    <input type="hidden" value="alterar" name="acao" id="acao" form="f1">
                    <input type="hidden" value="<?php echo $idSolicitacao; ?>" name="idSolicitacao" id="alterar" form="f1">
                    <input type="hidden" value="tMenu1_3_1.php" name="pagina" id="pagina" form="f1">

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
                <!-- /.card -->
            </div>
        </div>
    </div>
</div>
<script>
    function decisao(){
       if (document.getElementById('situacao').checked) {
            document.getElementById('conteudo').innerHTML = 'Sim';
        } else {
            document.getElementById('conteudo').innerHTML = 'Não';
        }
    }
</script>