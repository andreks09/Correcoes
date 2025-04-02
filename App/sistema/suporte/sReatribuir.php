<?php
session_start();

require_once '../../../vendor/autoload.php';

use App\sistema\acesso\{
    sSair,
    sHistorico,
    sConfiguracao,
    sNotificacao
};
use App\sistema\suporte\{
    sEtapa
};

//verifica se tem credencial para acessar o sistema
if (!isset($_SESSION['credencial'])) {
    //solicitar saída com tentativa de violação
    $sSair = new sSair();
    $sSair->verificar('0');
}

//instancia as configurações do sistema
$sConfiguracao = new sConfiguracao();

if(isset($_POST['pagina'])){
    //cria variáveis locais com os dados recebidos via post
    $pagina = $_POST['pagina'];
    $idProtocolo = $_POST['idProtocolo'];
    $numero = $_POST['etapa'];
    $local = $_POST['local'];  
    $responsavel = $_POST['responsavel'];
        
    //buscar dados da etapa
    $sEtapa = new sEtapa();
    $sEtapa->setNomeCampo('protocolo_idprotocolo');
    $sEtapa->setValorCampo($idProtocolo);
    $sEtapa->consultar('tMenu2_2_1_3_1.php');
    
    foreach ($sEtapa->mConexao->getRetorno() as $value) {
        $idEtapa = $value['idetapa'];
        $numero = $value['numero'];       
        $acessoRemoto = $value['acessoRemoto'];
        $descricao = $value['descricao'];
        $idProtocolo = $value['protocolo_idprotocolo'];
        $idLocal = $value['local_idlocal'];
        $idPrioridade = $value['prioridade_idprioridade'];
        $idEquipamento = $value['equipamento_idequipamento'];
        $idResponsavel = $value['usuario_idusuario'];
    }
    
    if($idResponsavel == $responsavel){
        //gera notificação e redireciona para a página
        $sNotificacao = new sNotificacao('A23');    
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_2_1_3_1&campo=responsavel&codigo={$sNotificacao->getCodigo()}&pagina=tMenu2_2_1_3_1.php&idProtocolo={$idProtocolo}&etapa={$numero}");
        exit();
    }
    
    //variáveis para o histórico
    $acao = $_POST['acao'];
    $valorCampoAtual = $local;
    $valorCampoAnterior = $idLocal;
    $ip = $_SERVER['REMOTE_ADDR'];
    $navegador = $_SERVER['HTTP_USER_AGENT'];
    $sistemaOperacional = php_uname();
    $nomeDoDispositivo = gethostname();
    $idUsuario = $_SESSION['credencial']['idUsuario'];
    
    //alimentar o histórico
    alimentaHistorico($pagina, $acao, 'local_idlocal', $valorCampoAnterior, $valorCampoAtual, $idUsuario);
    
    //variáveis para o histórico
    $valorCampoAtual = $responsavel;
    $valorCampoAnterior = $idResponsavel;
    
    //alimentar o histórico
    alimentaHistorico($pagina, $acao, 'responsavel', $valorCampoAnterior, $valorCampoAtual, $idUsuario);
    
    //configura timezone para São Paulo
    $sConfiguracao = new sConfiguracao();
    $sConfiguracao->getTimeZone();
    $dataHoraEncerramento = date("Y-m-d H:i:s");    
    
    //encerrar etapa anterior (etapa 1)    
    //altera o campo dataHoraEncerramento
    if($numero == 1){
        $sEtapa->setIdEtapa($idEtapa);
        $sEtapa->setNomeCampo('dataHoraEncerramento');
        $sEtapa->setValorCampo($dataHoraEncerramento);
        $sEtapa->alterar('tMenu2_2_1_3_1.php');

        //altera o campo usuário
        $sEtapa->setNomeCampo('usuario_idusuario');
        $sEtapa->setValorCampo($responsavel);
        $sEtapa->alterar('tMenu2_2_1_3_1.php');

        //altera o campo solucao
        $solucao = '--';
        $sEtapa->setNomeCampo('solucao');
        $sEtapa->setValorCampo($solucao);
        $sEtapa->alterar('tMenu2_2_3.php');
    }else{
        $sEtapa->setIdEtapa($idEtapa);
        $sEtapa->setNomeCampo('dataHoraEncerramento');
        $sEtapa->setValorCampo($dataHoraEncerramento);
        $sEtapa->alterar('tMenu2_2_1_3_1.php');
    }
    
    
    //alimentar o histórico de inserção da nova etapa
    $acao = 'inserir';
    $valorCampoAnterior = $numero;
    $numero++;  
    alimentaHistorico($pagina, $acao, 'numero', $valorCampoAnterior, $numero, $idUsuario);
    alimentaHistorico($pagina, $acao, 'acessoRemoto', $acessoRemoto, $acessoRemoto, $idUsuario);
    alimentaHistorico($pagina, $acao, 'descricao', $descricao, $descricao, $idUsuario);
    alimentaHistorico($pagina, $acao, 'protocolo_idprotocolo', $idProtocolo, $idProtocolo, $idUsuario);
    alimentaHistorico($pagina, $acao, 'equipamento_idequipamento', $idEquipamento, $idEquipamento, $idUsuario);
    alimentaHistorico($pagina, $acao, 'local_idlocal', $idLocal, $local, $idUsuario);
    alimentaHistorico($pagina, $acao, 'prioridade_idprioridade', $idPrioridade, $idPrioridade, $idUsuario);
    alimentaHistorico($pagina, $acao, 'usuario_idusuario', $idResponsavel, $responsavel, $idUsuario);
    
    //inserir nova etapa no sistema
    //inserir dados na nova etapa
    $dadosTratados = [
        'numero' => $numero,
        'acessoRemoto' => $acessoRemoto,
        'descricao' => $descricao,        
        'protocolo_idprotocolo' => $idProtocolo,
        'local_idlocal' => $local,
        'prioridade_idprioridade' => $idPrioridade,
        'equipamento_idequipamento' => $idEquipamento,
        'usuario_idusuario' => $responsavel
    ];
    $sEtapa->inserir('tMenu2_2_1_3_1.php', $dadosTratados);
    
    //gera notificação e redireciona para a página
    $sNotificacao = new sNotificacao('S7');    
    header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_2&campo=atribuir&codigo={$sNotificacao->getCodigo()}");
    exit();
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
?>