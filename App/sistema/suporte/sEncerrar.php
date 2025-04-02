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
    sEtapa,
    sProtocolo
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
    isset($_POST['solucao']) ? $solucao = $_POST['solucao'] : $solucao = false;
        
    //buscar dados da etapa
    $sEtapa = new sEtapa();
    $sEtapa->setNomeCampo('protocolo_idprotocolo');
    $sEtapa->setValorCampo($idProtocolo);
    $sEtapa->consultar('tMenu2_2_1_3_2.php');
    
    foreach ($sEtapa->mConexao->getRetorno() as $value) {
        $idEtapa = $value['idetapa'];
    }
        
    //variáveis para o histórico
    $acao = $_POST['acao'];
    $valorCampoAtual = $solucao;
    $valorCampoAnterior = '';
    $ip = $_SERVER['REMOTE_ADDR'];
    $navegador = $_SERVER['HTTP_USER_AGENT'];
    $sistemaOperacional = php_uname();
    $nomeDoDispositivo = gethostname();
    $idUsuario = $_SESSION['credencial']['idUsuario'];
    
    //alimentar o histórico
    alimentaHistorico($pagina, $acao, 'solucao', $valorCampoAnterior, $valorCampoAtual, $idUsuario);
    
    //configura timezone para São Paulo
    $sConfiguracao = new sConfiguracao();
    $sConfiguracao->getTimeZone();
    $dataHoraEncerramento = date("Y-m-d H:i:s");    
    
    //encerrar etapa     
    //altera o campo dataHoraEncerramento da etapa
    $sEtapa->setIdEtapa($idEtapa);
    $sEtapa->setNomeCampo('dataHoraEncerramento');
    $sEtapa->setValorCampo($dataHoraEncerramento);
    $sEtapa->alterar('tMenu2_2_1_3_2.php');
    
    //altera o campo usuário
    $sEtapa->setNomeCampo('usuario_idusuario');
    $sEtapa->setValorCampo($idUsuario);
    $sEtapa->alterar('tMenu2_2_1_3_2.php');
    
    //altera o campo solucao
    $sEtapa->setNomeCampo('solucao');
    $sEtapa->setValorCampo($solucao);
    $sEtapa->alterar('tMenu2_2_3.php');
    
    //altera o campo dataHora Encerramento do protocolo
    $sProtocolo = new sProtocolo();
    $sProtocolo->setIdProtocolo($idProtocolo);
    $sProtocolo->setNomeCampo('dataHoraEncerramento');
    $sProtocolo->setValorCampo($dataHoraEncerramento);
    $sProtocolo->alterar('tMenu2_2_1_3_2.php');
    
    //gera notificação e redireciona para a página
    $sNotificacao = new sNotificacao('S5');    
    header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_2&campo=encerrar&codigo={$sNotificacao->getCodigo()}");
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