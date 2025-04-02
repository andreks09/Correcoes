<?php
use App\sistema\acesso\{
    sConfiguracao,
    sSair
};
use App\sistema\suporte\{
    sProtocolo,
    sEtapa,
    sLocal,
    sPrioridade
};

//verifica se tem credencial para acessar o sistema
if (!isset($_SESSION['credencial'])) {
    //solicitar saída com tentativa de violação
    $sSair = new sSair();
    $sSair->verificar('0');
}

//recebe e decodifica o prtocolo e id do usuario
$idProtocolo = base64_decode($_GET['protocolo']);

//consulta o protocolo
$sProtocolo = new sProtocolo();
$sProtocolo->setNomeCampo('idprotocolo');
$sProtocolo->setValorCampo($idProtocolo);
$sProtocolo->consultar('tMenu2_2_3.php');

//consulta o protocolo
$sEtapa = new sEtapa();
$sEtapa->setNomeCampo('protocolo_idprotocolo');
$sEtapa->setValorCampo($idProtocolo);
$sEtapa->consultar('tMenu2_2_3.php');

foreach ($sEtapa->mConexao->getRetorno() as $value) {
    $idPrioridade = $value['prioridade_idprioridade'];
    $idLocal = $value['local_idlocal'];
    $numero = $value['numero'];
}

//caso alguém já tenha atribuído o ticket, retornar mensagem informando
if($numero > 1){
    $sConfiguracao = new sConfiguracao();
    header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_2&campo=atribuir&codigo=A22");
    exit();
}

//consulta todas as prioridades
$sPrioridade = new sPrioridade();
$sPrioridade->setNomeCampo('idprioridade');
$sPrioridade->setValorCampo($idPrioridade);
$sPrioridade->consultar('tMenu2_2_3.php');

//consulta os locais
$sLocal = new sLocal();
$sLocal->consultar('tMenu2_2_3.php');


?>
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Etapa 2 - Atribuir Suporte</h3>
                </div>
                <!-- form start -->
                <div class="card-body">                        
                    <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Prioridade</label>
                                    <select class="form-control" name="prioridade" id="prioridade" form="f1">
                                        <?php
                                        //SE A PRIORIDADE FOR MAIOR QUE A PERMISSÃO DO USUÁRIO ENTÃO DESABILITE O CAMPO
                                        foreach ($sPrioridade->mConexao->getRetorno() as $value) {
                                            $idPrioridade == $value['idprioridade'] ? $atributo = 'selected=""' : $atributo = '';
                                            if($idPrioridade == $value['idprioridade']){
                                                $verificacaoPrioridade = $value['idprioridade'];
                                            }

                                            if($_SESSION['credencial']['nivelPermissao'] == 1 && $value['idprioridade'] < 3){
                                                echo '<option value="' . $value['idprioridade'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            }else if($_SESSION['credencial']['nivelPermissao'] == 2 && $value['idprioridade'] < 4){
                                                echo '<option value="' . $value['idprioridade'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            }else if($_SESSION['credencial']['nivelPermissao'] == 3 && $value['idprioridade'] < 5){
                                                echo '<option value="' . $value['idprioridade'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            }else if($_SESSION['credencial']['nivelPermissao'] >= 4){
                                                echo '<option value="' . $value['idprioridade'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            }else{
                                                echo '<option disabled="" value="' . $value['idprioridade'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="local">Local</label>
                                    <select class="form-control" name="local" id="local" form="f1">                                        
                                        <?php
                                        foreach ($sLocal->mConexao->getRetorno() as $value) {    
                                            $idLocal == $value['idlocal'] ? $atributo = 'selected=""' : $atributo = '';
                                            echo '<option value="' . $value['idlocal'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>                            
                        </div>
            <?php
                if (isset($tipo) &&
                    isset($titulo) &&
                    isset($mensagem)) {
                    if (isset($alertaSecretaria) ||
                        isset($alertaEmail)) {
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
                <!-- /.card-body -->
                <div class="card-footer">
                    <form action="<?php echo $sConfiguracao->getDiretorioControleSuporte(); ?>sAtribuirSuporte.php" method="post" enctype="multipart/form-data" name="f1" id="f1">
                        <input type="hidden" value="f1" name="formulario" form="f1">
                        <input type="hidden" value="inserir" name="acao" form="f1">
                        <input type="hidden" value="menu2_2_3" name="pagina" form="f1">
                        <input type="hidden" value="<?php echo $idProtocolo ?>" name="protocolo" form="f1">
                        <input type="hidden" value="<?php echo $verificacaoPrioridade ?>" name="verificacaoPrioridade" form="f1">
                        <button type="submit" class="btn btn-primary">Atribuir</button>
                    </form>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>