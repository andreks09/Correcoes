<?php

use App\sistema\acesso\{
    sConfiguracao,
    sTratamentoDados,
    sNotificacao,
    sSair
};
use App\sistema\suporte\{
    sPrioridade,
    sEtapa
    
};

//retorno de campo inválidos para notificação
if (isset($_GET['campo'])) {
    $sNotificacao = new sNotificacao($_GET['codigo']);
    switch ($_GET['campo']) {
        case 'solucao':
            if ($_GET['codigo'] == 'S1') {
                $alertaResponsavel = ' is-valid';
            } else {
                $alertaResponsavel = ' is-warning';
            }
            break;
    }

    //cria as variáveis da notificação
    $tipo = $sNotificacao->getTipo();
    $titulo = $sNotificacao->getTitulo();
    $mensagem = $sNotificacao->getMensagem();
}


if ($_POST['pagina'] == 'tMenu2_2_1.php') {
    isset($_POST['idProtocolo']) ? $idProtocolo = $_POST['idProtocolo'] : $idProtocolo = $_GET['idProtocolo'];    
    isset($_POST['etapa']) ? $numero = $_POST['etapa'] : $numero = $_GET['etapa'];
    $sEtapa = new sEtapa();
    $sEtapa->setNomeCampo('protocolo_idprotocolo');
    $sEtapa->setValorCampo($idProtocolo);
    $sEtapa->consultar('tMenu2_2_1_3_2.php');

    foreach ($sEtapa->mConexao->getRetorno() as $value) {
        if ($value['numero'] == $numero) {
            $idPrioridade = $value['prioridade_idprioridade'];
            $dataAbertura = $value['dataHoraAbertura'];
            $descricao = $value['descricao'];
        }
    }
    //buscar dados da prioridade
    $sPrioridade = new sPrioridade();
    $sPrioridade->setNomeCampo('idprioridade');
    $sPrioridade->setValorCampo($idPrioridade);
    $sPrioridade->consultar('tMenu2_2_1_3_2.php');

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
        
    //instancia classe com as configurações do sistema
    $sConfiguracao = new sConfiguracao();
    
}else{
    //solicitar saída com tentativa de violação
    $sSair = new sSair();
    $sSair->verificar('0');
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
                        <div class="col-sm-6">
                            <!-- textarea -->
                            <div class="form-group">
                                <label>Descrição</label>
                                <textarea class="form-control" rows="3" name="descricao" id="descricao" required="" maxlength="254" disabled=""><?php echo $descricao; ?></textarea>
                            </div>
                        </div>  
                        <div class="col-sm-6">
                            <!-- textarea -->
                            <div class="form-group">
                                <label>Solução</label>
                                <textarea class="form-control" rows="3" name="solucao" id="solucao" required="" maxlength="254" onkeyup="limite_textarea(this.value)" form="f1" placeholder="Ex.: ATUALIZAÇÃO DO S.O."></textarea>
                                <span id="cont">254</span> Caracteres restantes <br>
                            </div>
                        </div>  
                    </div>
                </div>
                <?php
                if (isset($tipo) &&
                    isset($titulo) &&
                    isset($mensagem)) {
                    if (isset($alertaResponsavel)) {
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
                <form action="<?php echo $sConfiguracao->getDiretorioControleSuporte()."sEncerrar.php"; ?>" method="post" enctype="multipart/form-data" name="f1" id="f1">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <input type="hidden" value="f1" name="formulario" form="f1">
                        <input type="hidden" value="alterar" name="acao" form="f1">
                        <input type="hidden" value="tMenu2_2_1_3_2.php" name="pagina" form="f1">
                        <input type="hidden" value="<?php echo $idProtocolo ?>" name="idProtocolo" form="f1">
                        <input type="hidden" value="<?php echo $numero ?>" name="etapa" form="f1">
                        <button type="submit" class="btn btn-primary">Encerrar</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
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