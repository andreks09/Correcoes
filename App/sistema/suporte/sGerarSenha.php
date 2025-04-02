<?php
session_start();

require_once '../../../vendor/autoload.php';
use App\sistema\acesso\{
    sSenha,
    sConfiguracao
};

$sSenha = new sSenha(false);
$sSenha->criptografar($_POST['senha']);
$criptografia = $sSenha->getSenhaCriptografada();

$sConfiguracao = new sConfiguracao();
header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=6_3&criptografia={$criptografia}");
exit();
