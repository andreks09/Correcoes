<?php
session_start();

require_once '../../../vendor/autoload.php';

use App\sistema\acesso\{
    sNotificacao,
    sConfiguracao,
    sTratamentoDados,
    sSair,
    sHistorico
};
use App\sistema\suporte\{
    sPrioridade,
    sEtapa,
    sLocal    
};
$pagina = $_POST['pagina'];

if ($pagina == 'tMenu2_2_1_3.php') {
    $idProtocolo = $_POST['idProtocolo'];
    $numero = $_POST['etapa'];
    $acao = $_POST['acao'];
    $idUsuario = $_SESSION['credencial']['idUsuario'];
    $verificacaoPrioridade = $_POST['verificacaoPrioridade'];
    $verificacaoLocal = $_POST['verificacaoLocal'];
        
    //busca dados da etapa 
    $sEtapa = new sEtapa();
    $sEtapa->setNomeCampo('protocolo_idprotocolo');
    $sEtapa->setValorCampo($idProtocolo);
    $sEtapa->consultar('tMenu2_2_1_3.php');
    
    foreach ($sEtapa->mConexao->getRetorno() as $value) {
        if($value['numero'] == $numero){
            $idEtapa = $value['idetapa'];
            if(!isset($idPrioridade)){
                $idPrioridade = $verificacaoPrioridade;
            }
            $value['prioridade_idprioridade'] != $_POST['prioridade'] ? $idPrioridade = $_POST['prioridade'] : $idPrioridade = false;
            $prioridadeAnterior = $value['prioridade_idprioridade'];
            $value['acessoRemoto'] != $_POST['acessoRemoto'] ? $acessoRemoto = $_POST['acessoRemoto'] : $acessoRemoto = false;
            $acessoRemotoAnterior = $value['acessoRemoto'];
            $value['descricao'] != $_POST['descricao'] ? $descricao = $_POST['descricao'] : $descricao = false;
            $descricaoAnterior = $value['descricao'];
            if(!isset($idLocal)){
                $idLocal = $verificacaoLocal;
            }            
            $value['local_idlocal'] != $_POST['local'] ? $idLocal = $_POST['local'] : $idLocal = false;
            $localAnterior = $value['local_idlocal'];
        }
    }
    
    $sEtapa->setIdEtapa($idEtapa);
    
    //alimenta o histórico e altera os dados no bd
    if($idLocal){
        alimentaHistorico($pagina, $acao, 'local_idlocal', $localAnterior, $idLocal, $idUsuario);
        $sEtapa->setNomeCampo('local_idlocal');
        $sEtapa->setValorCampo($idLocal);
        $sEtapa->alterar('tMenu2_2_1_3.php');
    }    
    if($idPrioridade){
        alimentaHistorico($pagina, $acao, 'prioridade_idprioridade', $prioridadeAnterior, $idPrioridade, $idUsuario);
        $sEtapa->setNomeCampo('prioridade_idprioridade');
        $sEtapa->setValorCampo($idPrioridade);
        $sEtapa->alterar('tMenu2_2_1_3.php');
    }
    if($acessoRemoto || $acessoRemoto == ''){
        alimentaHistorico($pagina, $acao, 'acessoRemoto', $acessoRemotoAnterior, $acessoRemoto, $idUsuario);
        $sEtapa->setNomeCampo('acessoRemoto');
        $sEtapa->setValorCampo($acessoRemoto);
        $sEtapa->alterar('tMenu2_2_1_3.php');
    }
    if($descricao){
        alimentaHistorico($pagina, $acao, 'descricao', $descricaoAnterior, $descricao, $idUsuario);
        $sEtapa->setNomeCampo('descricao');
        $sEtapa->setValorCampo($descricao);
        $sEtapa->alterar('tMenu2_2_1_3.php');
    }
    
    //instancia configurações do sistema
    $sConfiguracao = new sConfiguracao();
    
    //gera notificação e redireciona para a página
    $sNotificacao = new sNotificacao('S1');    
    header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_2&&campo=alterar&codigo={$sNotificacao->getCodigo()}");
    exit();
    
}else{
    //solicitar saída com tentativa de violação
    $sSair = new sSair();
    $sSair->verificar('0');
}

function alimentaHistorico($pagina, $acao, $campo, $valorCampoAnterior, $valorCampoAtual, $idUsuario) {
    //tratar os campos antes do envio
    $tratarDados = [
        'pagina' => $pagina,
        'acao' => $acao,
        'campo' => $campo,
        'valorCampoAtual' => $valorCampoAtual,
        'valorCampoAnterior' => $valorCampoAnterior,
        'ip' => $_SERVER['REMOTE_ADDR'],
        'navegador' => $_SERVER['HTTP_USER_AGENT'],
        'sistemaOperacional' => php_uname(),
        'nomeDoDispositivo' => gethostname(),
        'idUsuario' => $idUsuario
    ];

    //insere na tabela histórico
    $sHistorico = new sHistorico();
    $sHistorico->inserir('tSolicitarAcesso.php', $tratarDados);
}