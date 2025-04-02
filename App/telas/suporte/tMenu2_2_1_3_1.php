<?php

use App\sistema\acesso\{
    sConfiguracao,
    sTratamentoDados,
    sNotificacao,
    sUsuario,
    sPermissao
};
use App\sistema\suporte\{
    sPrioridade,
    sEtapa,
    sLocal,
    
};

//retorno de campo inválidos para notificação
if (isset($_GET['campo'])) {
    $sNotificacao = new sNotificacao($_GET['codigo']);
    switch ($_GET['campo']) {
        case 'responsavel':
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

isset($_POST['pagina']) ? $pagina = $_POST['pagina'] : $acesso = false;
isset($_GET['pagina']) ? $pagina = $_GET['pagina'] : $acesso = false;

if ($pagina == 'tMenu2_2_1.php' || $pagina == 'tMenu2_2_1_3_1.php') {
    isset($_POST['idProtocolo']) ? $idProtocolo = $_POST['idProtocolo'] : $idProtocolo = $_GET['idProtocolo'];    
    isset($_POST['etapa']) ? $numero = $_POST['etapa'] : $numero = $_GET['etapa'];
    $sEtapa = new sEtapa();
    $sEtapa->setNomeCampo('protocolo_idprotocolo');
    $sEtapa->setValorCampo($idProtocolo);
    $sEtapa->consultar('tMenu2_2_1_3_1.php');

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
    $sPrioridade->consultar('tMenu2_2_1_3_1.php');

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
    
    //busca os locais no bd
    $sLocal = new sLocal();
    $sLocal->consultar('tMenu2_2_1_3_1.php');
    
    //busca os usuários responsáveis
    $sResponsavel = new sUsuario();
    $sResponsavel->consultar('tMenu2_2_1_3_1.php');
    
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="responsavel">Responsável</label>
                                <select class="form-control<?php echo isset($alertaResponsavel) ? $alertaResponsavel : ''; ?>" name="responsavel" id="responsavel" form="f1">                                        
                                    <?php
                                    foreach ($sResponsavel->mConexao->getRetorno() as $value) {
                                        $idUsuario == $value['idusuario'] ? $atributo = 'selected=""' : $atributo = '';
                                        //instancia classe com as permissões para tratamento do campo responsável                                         
                                        $sPermissao = new sPermissao($value['permissao_idpermissao']);
                                        $sPermissao->consultar('tMenu2_2_1_3_1.php');
                                        if($sPermissao->getNivel() > 1){                                            
                                            echo '<option value="' . $value['idusuario'] . '"' . $atributo . ' >' . $value['nome'].' '.$value['sobrenome'] . '</option>';
                                        }                                        
                                    }
                                    ?>
                                </select>
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
                <form action="<?php echo $sConfiguracao->getDiretorioControleSuporte()."sReatribuir.php"; ?>" method="post" enctype="multipart/form-data" name="f1" id="f1">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <input type="hidden" value="f1" name="formulario" form="f1">
                        <input type="hidden" value="alterar" name="acao" form="f1">
                        <input type="hidden" value="tMenu2_2_1_3_1.php" name="pagina" form="f1">
                        <input type="hidden" value="<?php echo $idProtocolo ?>" name="idProtocolo" form="f1">
                        <input type="hidden" value="<?php echo $numero ?>" name="etapa" form="f1">
                        <button type="submit" class="btn btn-primary">Reatribuir</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>