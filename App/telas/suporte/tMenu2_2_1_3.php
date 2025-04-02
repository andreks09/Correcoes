<?php

use App\sistema\acesso\{
    sConfiguracao,
    sTratamentoDados,
    sNotificacao
};
use App\sistema\suporte\{
    sPrioridade,
    sEtapa,
    sLocal
    
};

//retorno de campo inválidos para notificação
if (isset($_GET['campo'])) {
    $sNotificacao = new sNotificacao($_GET['codigo']);
    switch ($_GET['campo']) {
        case 'alterar':
            if ($_GET['codigo'] == 'S1') {
                $alertaSecretaria = ' is-valid';
            } else {
                $alertaSecretaria = ' is-warning';
            }
            break;
    }

    //cria as variáveis da notificação
    $tipo = $sNotificacao->getTipo();
    $titulo = $sNotificacao->getTitulo();
    $mensagem = $sNotificacao->getMensagem();
}

$pagina = $_POST['pagina'];


if ($pagina == 'tMenu2_2_1.php') {
    $idProtocolo = $_POST['idProtocolo'];
    $numero = $_POST['etapa'];    
    $sEtapa = new sEtapa();
    $sEtapa->setNomeCampo('protocolo_idprotocolo');
    $sEtapa->setValorCampo($idProtocolo);
    $sEtapa->consultar('tMenu2_2_1_3.php');

    foreach ($sEtapa->mConexao->getRetorno() as $value) {
        if ($value['numero'] == $numero) {
            $idPrioridade = $value['prioridade_idprioridade'];
            $dataAbertura = $value['dataHoraAbertura'];
            $dataEncerramento = $value['dataHoraEncerramento'];
            $acessoRemoto = $value['acessoRemoto'];
            $descricao = $value['descricao'];
            $idLocal = $value['local_idlocal'];
            !is_null($value['usuario_idusuario']) ? $idUsuario = $value['usuario_idusuario'] : $idUsuario = 0;
            $solucao = $value['solucao'];
        }
    }
    //buscar dados da prioridade
    $sPrioridade = new sPrioridade();
    $sPrioridade->setNomeCampo('idprioridade');
    $sPrioridade->setValorCampo($idPrioridade);
    $sPrioridade->consultar('tMenu2_2_1_3.php');

    foreach ($sPrioridade->mConexao->getRetorno() as $value) {
        $prioridade = $value['nomenclatura'];
    }

    //tratamento da prioridade
    $sTratamentoPrioridade = new sTratamentoDados($prioridade);
    $dadosPrioridade = $sTratamentoPrioridade->corPrioridade();
    $posicao = $dadosPrioridade[0];
    $cor = $dadosPrioridade[1];

    //campo protocolo
    $ano = date("Y", strtotime(str_replace('-', '/', $dataAbertura)));
    $protocolo = str_pad($idProtocolo, 5, 0, STR_PAD_LEFT);
    $protocolo = $ano . $protocolo;
    
    //busca todas as prioridades para o campo
    $sPrioridade->consultar('tMenu2_2_1_3.php-f1');
    
    $sLocal = new sLocal();
    $sLocal->consultar('tMenu2_2_1_3.php');
    
    $sConfiguracao = new sConfiguracao();
    
    //redundância para ocultar o campo, não deletar assinado meu eu do passado
    foreach ($sPrioridade->mConexao->getRetorno() as $value) {
        if($idPrioridade == $value['idprioridade']){
            $verificacaoPrioridade = $value['idprioridade'];
        }        
    }
    
    //redundância para ocultar o campo, não deletar assinado meu eu do passado
    foreach ($sLocal->mConexao->getRetorno() as $value) {
        if($idLocal == $value['idlocal']){
            $verificacaoLocal = $value['idlocal'];
        }
    }
}

?>

<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-<?php echo $cor; ?> card-outline">
                <div class="card-header">
                    <h3 class="card-title">Protocolo n.º: <?php echo $protocolo; ?> - Etapa <?php echo $numero; ?></h3>
                </div>
                <!-- form start -->
                <div class="card-body">
                    <div class="row"> 
                        <?php
                        if($_SESSION['credencial']['nivelPermissao'] > 1 ){                        
                        ?>
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
                        <?php
                        }
                        if($_SESSION['credencial']['nivelPermissao'] == 1 && $verificacaoPrioridade > 2){
                            //oculte o campo
                        }else{
                        ?>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Prioridade</label>
                                <select class="form-control" name="prioridade" id="prioridade" form="f1">
                                    <?php
                                    //SE A PRIORIDADE FOR MAIOR QUE A PERMISSÃO DO USUÁRIO ENTÃO DESABILITE O CAMPO
                                    foreach ($sPrioridade->mConexao->getRetorno() as $value) {
                                        $idPrioridade == $value['idprioridade'] ? $atributo = 'selected=""' : $atributo = '';
                                                                               
                                        if($_SESSION['credencial']['nivelPermissao'] == 1){                                            
                                            if($value['idprioridade'] < 3){
                                                echo '<option value="' . $value['idprioridade'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            }                                            
                                        }else{                                        
                                            if($_SESSION['credencial']['nivelPermissao'] == 2 && $value['idprioridade'] < 4){
                                                echo '<option value="' . $value['idprioridade'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            }else if($_SESSION['credencial']['nivelPermissao'] == 3 && $value['idprioridade'] < 5){
                                                echo '<option value="' . $value['idprioridade'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            }else if($_SESSION['credencial']['nivelPermissao'] >= 4){
                                                echo '<option value="' . $value['idprioridade'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            }else{                                            
                                                echo '<option disabled="" value="' . $value['idprioridade'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="local">Acesso Remoto</label>
                                <input class="form-control" name="acessoRemoto" id="acessoRemoto" value="<?php echo $acessoRemoto; ?>" form="f1">                                        
                            </div>
                        </div>
                    </div>
                        
                    <div class="row">                     
                        <div class="col-sm-6">
                            <!-- textarea -->
                            <div class="form-group">
                                <label>Descrição</label>
                                <textarea class="form-control" rows="3" name="descricao" id="descricao" required="" maxlength="254" onkeyup="limite_textarea(this.value)" form="f1"><?php echo $descricao; ?></textarea>
                                <span id="cont">254</span> Caracteres restantes <br />
                            </div>
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
                <form action="<?php echo $sConfiguracao->getDiretorioControleSuporte(); ?>sAlterarSuporte.php" method="post" enctype="multipart/form-data" name="f1" id="f1">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <input type="hidden" value="f1" name="formulario" form="f1">
                        <input type="hidden" value="alterar" name="acao" form="f1">
                        <input type="hidden" value="tMenu2_2_1_3.php" name="pagina" form="f1">
                        <input type="hidden" value="<?php echo $idProtocolo ?>" name="idProtocolo" form="f1">
                        <input type="hidden" value="<?php echo $numero ?>" name="etapa" form="f1">
                        <input type="hidden" value="<?php echo $verificacaoPrioridade ?>" name="verificacaoPrioridade" form="f1">
                        <input type="hidden" value="<?php echo $verificacaoLocal ?>" name="verificacaoLocal" form="f1">
                        <button type="submit" class="btn btn-primary">Alterar</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
    //contador de caracteres para o campo descrição
    function limite_textarea(valor) {
        quant = 254;
        total = valor.length;
        if (total <= quant) {
            resto = quant - total;
            document.getElementById('cont').innerHTML = resto;
        } else {
            document.getElementById('descricao').value = valor.substr(0, quant);
        }
    }
</script>