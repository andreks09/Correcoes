<?php
//chama o caminho do autoload para carregamento dos arquivos
require_once '../../../vendor/autoload.php';

//chama os arquivos para instanciá-los
use App\sistema\acesso\{
    sConfiguracao,
    sSenha,
    sTratamentoDados,
    sSair,
    sHistorico,
    sRecuperarAcesso,
    sEmail
};

if(!isset($_POST['pagina'])){
    //solicitar saída com tentativa de violação
    $sSair = new sSair();
    $sSair->verificar('0');
}

//declara as variáveis e armazena os dados passados por POST
$senha = $_POST['senha'];
$confirmarSenha = $_POST['confirmarSenha'];
$chave = $_POST['chave'];
$pagina = $_POST['pagina'];
$acao = $_POST['acao'];

//criptografa senha para histórico
$sSenha = new sSenha(false);
$sSenha->criptografar($senha);
$senhaCriptografada = $sSenha->getSenhaCriptografada();

//criptografar confirmarSenha para histórico
$sConfirmarSenha = new sSenha(false);
$sConfirmarSenha->criptografar($confirmarSenha);
$confirmarSenhaCriptografada = $sConfirmarSenha->getSenhaCriptografada();

//alimenta histórico
alimentaHistorico($pagina, $acao, 'chave', '', $chave, null);
alimentaHistorico($pagina, $acao, 'senha', '', $senhaCriptografada, null);
alimentaHistorico($pagina, $acao, 'confirmarSenha', '', $confirmarSenhaCriptografada, null);

//verificação dos dados
//verificar a senha 
$sTratamentoSenha = new sTratamentoDados($senha);
$senhaTratada = $sTratamentoSenha->tratarSenha();

$sTratamentoConfirmarSenha = new sTratamentoDados($confirmarSenha);
$confirmarSenhaTratada = $sTratamentoConfirmarSenha->tratarSenha();

if(!$senhaTratada || !$confirmarSenhaTratada){
    $sConfiguracao = new sConfiguracao();
    header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tAlterarSenha.php?campo=senha&codigo=A4&seguranca={$chave}");
    exit(); 
}

if($senha != $confirmarSenha){
    $sConfiguracao = new sConfiguracao();
    header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tAlterarSenha.php?campo=senha&codigo=A30&seguranca={$chave}");
    exit();
}

//verifica se existe a chave registrada.
$sRecuperarAcesso = new sRecuperarAcesso();
$sRecuperarAcesso->setNomeCampo('chave');
$sRecuperarAcesso->setValorCampo($chave);
$sRecuperarAcesso->consultar($pagina);

if(!$sRecuperarAcesso->getValidador()){
    $sConfiguracao = new sConfiguracao();
    header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tAlterarSenha.php?campo=chave&codigo=A31&seguranca={$chave}");
    exit();
}

//verifica se já decorreram 24h
foreach ($sRecuperarAcesso->mConexao->getRetorno() as $value) {
    $dataHora = $value['dataHora'];
    $email = $value['email'];
}

$sConfiguracao = new sConfiguracao();
$dataHoraAtual = date("Y-m-d H:i:s");

$dataHoraLimite = date("Y-m-d H:i:s",strtotime('+24 hours', strtotime($dataHora)));

if($dataHoraAtual > $dataHoraLimite){
    $sConfiguracao = new sConfiguracao();
    header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tAlterarSenha.php?campo=chave&codigo=A32&seguranca={$chave}");
    exit();
}

//altera os dados na tabela email
$sEmail = new sEmail($email, '');
$sEmail->setNomeCampo('senha');
$sEmail->setValorCampo($senhaCriptografada);
$sEmail->setNomenclatura($email);
$sEmail->alterar($pagina);

$sConfiguracao = new sConfiguracao();
header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tAcessar.php?codigo=S1");
exit();
        
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