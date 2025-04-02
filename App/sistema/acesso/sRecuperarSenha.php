<?php
//chama o caminho do autoload para carregamento dos arquivos
require_once '../../../vendor/autoload.php';

use App\sistema\acesso\{
    sSair,
    sEmail,
    sConfiguracao,
    sHistorico,
    sRecuperarAcesso,
    sSenha,
    sNotificacao
};

if(!isset($_POST['pagina']) && isset($_POST['pagina']) != 'tEsqueciMinhaSenha.php'){
    //solicitar saída com tentativa de violação
    $sSair = new sSair();
    $sSair->verificar('0');
}

$email = $_POST['email'];
$pagina = $_POST['pagina'];
$acao = $_POST['acao'];

//registrar solicitação no sistema
alimentaHistorico($pagina, $acao, 'email', '', $email, null);

//verifica se o e-mail é válido e se está registrado no BD
$sEmail = new sEmail($email, '');
$sEmail->verificar('tEsqueciMinhaSenha.php');

if(!$sEmail->getValidador()){
    $sConfiguracao = new sConfiguracao();
    header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tEsqueciMinhaSenha.php?campo=email&codigo={$sEmail->getSNotificacao()->getCodigo()}");
    exit();
}

//gera senha para inserir no bd
$sSenha = new sSenha(true);
$sSenha->gerar();
$sSenha->criptografar($sSenha->getSenha());
$chave = $sSenha->getSenhaCriptografada();
    
//registra os dados para recuperar senha
$sRecuperarAcesso = new sRecuperarAcesso();
$dados = [
    'email' => $email,
    'chave' => $chave
];
$sRecuperarAcesso->inserir($pagina, $dados);

//envia e-mail com notificação para o usuário
$sConfiguracao = new sConfiguracao();
$diretorio = $sConfiguracao->getDiretorioVisualizacaoAcesso().'tAlterarSenha.php?seguranca='.$chave;
$assunto = 'Recuperação de senha para acesso ao SSPMI';
$mensagem = <<<HTML
    <p>
        Houve uma solicitação de recuperação de senha para esse endereço de e-mail, se não foi solicitado por você basta ignorar essa mensagem e contatar nossos administradores.<br />
        Caso tenha sido você, clique no link abaixo:<br />
        <br />
        <a href="$diretorio">Link de recuperação</a><br />
        <br />
        <b>Obs.:</b> Esse e-mail é somente para mensagens automáticas, favor não respondê-lo.
    </p>
HTML;

$sEmail->setPara($email);
$sEmail->setAssunto($assunto);
$sEmail->setMensagem($mensagem);
$sEmail->enviar('tEsqueciMinhaSenha.php');

if(!$sEmail->getValidador()){
    $sNotificacao = new sNotificacao('A29');
    header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tEsqueciMinhaSenha.php?campo=email&codigo={$sNotificacao->getCodigo()}");
    exit();
}

$sNotificacao = new sNotificacao('S2');
header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tEsqueciMinhaSenha.php?campo=email&codigo={$sNotificacao->getCodigo()}");
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