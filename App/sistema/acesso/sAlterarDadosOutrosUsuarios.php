<?php

session_start();
require_once '../../../vendor/autoload.php';

use App\sistema\acesso\{
    sSair,
    sConfiguracao,
    sHistorico,
    sUsuario,
    sTelefone,
    sEmail,
    sTratamentoDados,
    sNotificacao
};

//verifica se tem credencial para acessar o sistema
if (!isset($_SESSION['credencial'])) {
    //solicitar saída com tentativa de violação
    $sSair = new sSair();
    $sSair->verificar('0');
}

if (isset($_POST['pagina'])) {
    if ($_POST['pagina'] != 'tMenu1_2_1.php') {
        //solicitar saída com tentativa de violação
        $sSair = new sSair();
        $sSair->verificar('0');   
    }
   
    //receber valores para alteração
    $idUsuario = $_SESSION['credencial']['idUsuario'];
    $idUsuarioAlterar = $_POST['idUsuario'];
    $pagina = $_POST['pagina'];
    $acao = $_POST['acao'];
    //$imagem = $_POST['imagem']; próxima building
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $sexo = $_POST['sexo'];
    $telefone = $_POST['telefone'];
    isset($_POST['whatsApp']) ? $whatsApp = 1 : $whatsApp = 0;
    $email = $_POST['email'];
    $idCargo = $_POST['cargo'];
    $idPermissao = $_POST['permissao'];
    $idSecretaria = $_POST['secretaria'];
    $idDepartamento = $_POST['departamento'];
    $idCoordenacao = $_POST['coordenacao'];
    $idSetor = $_POST['setor'];
    isset($_POST['situacao']) ? $situacao = 1 : $situacao = 0;
    $seguranca = base64_encode($idUsuarioAlterar);
    $atualizar = [];
    
    //buscando dados anteriores do usuário para posterior comparação com os dados passados
    $sUsuario = new sUsuario();
    $sUsuario->setIdUsuario($idUsuarioAlterar);
    $sUsuario->consultar('tMenu1_2_1.php');
    
    foreach ($sUsuario->mConexao->getRetorno() as $value) {
        $nomeAnterior = $value['nome'];
        $nome == $value['nome'] ? $nome = false : $atualizar = ['nome' => $nome];
        $sobrenomeAnterior = $value['sobrenome'];
        $sobrenome == $value['sobrenome'] ? $sobrenome = false : $atualizar = ['sobrenome' => $sobrenome];
        $sexoAnterior = $value['sexo'];
        if($sexo == $value['sexo']){
            $verificacaoSexo = false;
        }else{
            $verificacaoSexo = true;
            $atualizar = ['sexo' => $sexo];
        }
        //$imagem = $value['imagem'];
        $situacaoAnterior = $value['situacao'];
        if($situacao == $value['situacao']){
            $verificacaoSituacao = false;
        }else{
            $verificacaoSituacao = true;
            $atualizar = ['situacao' => $situacao];
        }
        $setorAnterior = $value['setor_idsetor'];
        if($idSetor != 0){
            $idSetor == $value['setor_idsetor'] ? $idSetor = false : $atualizar = ['idSetor' => $idSetor];
        }else{
            $idSetor = false;
        }        
        $coordenacaoAnterior = $value['coordenacao_idcoordenacao'];
        if($idCoordenacao != 0){
            $idCoordenacao == $value['coordenacao_idcoordenacao'] ? $idCoordenacao = false : $atualizar = ['idCoordenacao' => $idCoordenacao];
        }else{
            $idCoordenacao = false;
        }        
        $departamentoAnterior = $value['departamento_iddepartamento'];
        if($idDepartamento != 0){
            $idDepartamento == $value['departamento_iddepartamento'] ? $idDepartamento = false : $atualizar = ['idDepartamento' => $idDepartamento];
        }else{
            $idDepartamento = false;
        }        
        $secretariaAnterior = $value['secretaria_idsecretaria'];
        $idSecretaria == $value['secretaria_idsecretaria'] ? $idSecretaria = false : $atualizar = ['idSecretaria' => $idSecretaria];
        $idTelefone = $value['telefone_idtelefone'];
        $cargoAnterior = $value['cargo_idcargo'];
        $idCargo == $value['cargo_idcargo'] ? $idCargo = false : $atualizar = ['idCargo' => $idCargo];
        $idEmail = $value['email_idemail'];
        $permissaoAnterior = $value['permissao_idpermissao'];
        $idPermissao == $value['permissao_idpermissao'] ? $idPermissao = false : $atualizar = ['idPermissao' => $idPermissao];
    }
    
    //busca os dados do id do telefone
    $sTelefone = new sTelefone(0, 0, '');   
    $sTelefone->setNomeCampo('idtelefone');
    $sTelefone->setValorCampo($idTelefone);
    $sTelefone->consultar('tMenu1_2_1.php');
    
    foreach ($sTelefone->mConexao->getRetorno() as $value) {
        $telefoneAnterior = $value['numero'];
        $whatsAppAnterior = $value['whatsApp'];
        if($whatsApp == $value['whatsApp']){
            $verificacaoWhatsApp = false;
        }else{
            $alteracao = true;
            $verificacaoWhatsApp = true;
            $atualizar = ['whatsApp' => $whatsApp];
        }
    }
    
    $sTratamentoTelefone = new sTratamentoDados($telefone);
    $telefoneTratado = $sTratamentoTelefone->tratarTelefone();
    
    if($telefoneAnterior == $telefoneTratado){
        $verificacaoTelefone = false;
    }else{
        $alteracao = true;
        $verificacaoTelefone = true;
        $atualizar = ['telefone' => $telefoneTratado];
    }
    
    //busca os dados do id do email
    $sEmail = new sEmail(0, '');
    $sEmail->setNomeCampo('idemail');
    $sEmail->setValorCampo($idEmail);
    $sEmail->consultar('tMenu1_2_1.php');
    
    foreach ($sEmail->mConexao->getRetorno() as $value) {
        if($email == $value['nomenclatura']){
            $verificacaoEmail = false;
        }else{
            $emailAnterior = $value['nomenclatura'];
            $alteracao = true;
            $verificacaoEmail = true;
            $atualizar = ['email' => $email];
        }
    }
            
    //alimentar histórico
    if($nome){
        alimentaHistorico($pagina, $acao, 'nome', $nomeAnterior, $nome, $idUsuario);
    }
    if($sobrenome){
        alimentaHistorico($pagina, $acao, 'sobrenome', $sobrenomeAnterior, $sobrenome, $idUsuario);
    }
    if($verificacaoSexo){
        alimentaHistorico($pagina, $acao, 'sexo', $sexoAnterior, $sexo, $idUsuario);
    }
    if($verificacaoTelefone){
        alimentaHistorico($pagina, $acao, 'numero', $telefoneAnterior, $telefone, $idUsuario);
    }
    if($verificacaoWhatsApp){
        alimentaHistorico($pagina, $acao, 'whatsApp', $whatsAppAnterior, $whatsApp, $idUsuario);
    }
    if($verificacaoEmail){
        alimentaHistorico($pagina, $acao, 'email', $emailAnterior, $email, $idUsuario);
    }
    if($idPermissao){
        alimentaHistorico($pagina, $acao, 'permissao_idpermissao', $permissaoAnterior, $idPermissao, $idUsuario);
    }
    if($idCargo){
        alimentaHistorico($pagina, $acao, 'cargo_idcargo', $cargoAnterior, $idCargo, $idUsuario);
    }
    if($idSecretaria){
        alimentaHistorico($pagina, $acao, 'secretaria_idsecretaria', $secretariaAnterior, $idSecretaria, $idUsuario);
    }
    if($idDepartamento){
        alimentaHistorico($pagina, $acao, 'departamento_iddepartamento', $departamentoAnterior, $idDepartamento, $idUsuario);
    }
    if($idCoordenacao){
        alimentaHistorico($pagina, $acao, 'coordenacao_idcoordenacao', $coordenacaoAnterior, $idCoordenacao, $idUsuario);
    }
    if($idSetor){
        alimentaHistorico($pagina, $acao, 'setor_idsetor', $setorAnterior, $idSetor, $idUsuario);
    }
    if($verificacaoSituacao){
        alimentaHistorico($pagina, $acao, 'situacao', $situacaoAnterior, $situacao, $idUsuario);
    }
    
    
    if ($nome) {
        //etapa4 - validação do conteúdo
        $sUsuario->verificarNome($nome);
        if (!$sUsuario->getValidador()) {
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_2_1&seguranca={$seguranca}&campo=nome&codigo={$sUsuario->getSNotificacao()->getCodigo()}");
            exit();
        } else {
            //etapa5 - atualizar os dados
            $alteracao = true;
        }
    }
        
    if ($sobrenome) {
        //etapa4 - validação do conteúdo
        $sUsuario->verificarSobrenome($sobrenome);
        if (!$sUsuario->getValidador()) {
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_2_1&seguranca={$seguranca}&campo=sobrenome&codigo={$sUsuario->getSNotificacao()->getCodigo()}");
            exit();
        } else {
            //etapa5 - atualizar os dados
            $alteracao = true;
        }
    }
    
    if($verificacaoSexo){
        $alteracao = true;
    }
        
    if ($verificacaoTelefone) {
        //etapa4 - validação do conteúdo        
        $sTelefone->verificarTelefone($telefoneTratado);
        if (!$sTelefone->getValidador()) {
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_2_1&seguranca={$seguranca}&campo=telefone&codigo={$sTelefone->getSNotificacao()->getCodigo()}");
            exit();
        } else {
            //etapa5 - atualizar os dados
            $alteracao = true;
        }
    }
    
    if ($verificacaoWhatsApp) {
        $alteracao = true;
    }

    if ($verificacaoEmail) {
        $sTratamentoEmail = new sTratamentoDados($email);
        $validaEmail = $sTratamentoEmail->tratarEmail();

        if(!$validaEmail){
            $sConfiguracao = new sConfiguracao();
            $sNotificacao = new sNotificacao('A2');
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_2_1&seguranca={$seguranca}&campo=email&codigo={$sNotificacao->getCodigo()}");
            exit();
        }
        
        $sEmail->setNomenclatura($email);
        $sEmail->verificar('tMenu1_2_1.php');  
        if (!$sEmail->getValidador()) {
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_2_1&seguranca={$seguranca}&campo=email&codigo={$sEmail->getSNotificacao()->getCodigo()}");
            exit();
        } else {
            //etapa5 - atualizar os dados            
            $alteracao = true;
        }
    }
    
    if ($idCargo) {
        //etapa4 - atualizar os dados
        $alteracao = true;
    }    
    
    if ($idPermissao) {
        //etapa4 - atualizar os dados
        $alteracao = true;
    }
    
    if ($verificacaoSituacao) {
        $alteracao = true;
    }
    
    if ($idSecretaria) {
        //etapa4 - atualizar os dados
        $alteracao = true;
    }
    
    if ($idDepartamento) {
        //etapa4 - atualizar os dados
        $alteracao = true;
    }
    
    if ($idCoordenacao) {
        $alteracao = true;
    }
    
    if ($idSetor) {
        $alteracao = true;
    }
    
    //etapa5 - alterar os dados
    if (!$alteracao) {
        //se não tem campo para validar
        $sConfiguracao = new sConfiguracao();
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_2_1&seguranca={$seguranca}");
    }else{        
        //se tem campos para atualizar
        if (array_key_exists('nome', $atualizar)) {
            //atualize o campo nome
            $sUsuario->setIdUsuario($idUsuarioAlterar);
            $sUsuario->setNomeCampo('nome');
            $sUsuario->setValorCampo($nome);
            $sUsuario->alterar('tMenu1_2_1.php');
                        
            if ($sUsuario->mConexao->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_2_1&seguranca={$seguranca}&campo=nome&codigo={$sUsuario->getSNotificacao()->getCodigo()}");
            }
        }
        
        if (array_key_exists('sobrenome', $atualizar)) {
            //atualize o campo sobrenome
            $sUsuario->setIdUsuario($idUsuarioAlterar);
            $sUsuario->setNomeCampo('sobrenome');
            $sUsuario->setValorCampo($sobrenome);
            $sUsuario->alterar('tMenu1_2_1.php');
            
            if ($sUsuario->mConexao->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_2_1&seguranca={$seguranca}&campo=sobrenome&codigo={$sUsuario->getSNotificacao()->getCodigo()}");
            }
        }
        
        if (array_key_exists('sexo', $atualizar)) {
            //atualize o campo sexo
            $sUsuario->setIdUsuario($idUsuarioAlterar);
            $sUsuario->setNomeCampo('sexo');
            $sUsuario->setValorCampo($sexo);
            $sUsuario->alterar('tMenu1_2_1.php');
            
            if ($sUsuario->mConexao->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_2_1&seguranca={$seguranca}&campo=sexo&codigo={$sUsuario->getSNotificacao()->getCodigo()}");
            }
        }
        
        if (array_key_exists('telefone', $atualizar)) {
            //atualize o campo telefone
            $sTelefone->setIdTelefone($idTelefone);
            $sTelefone->setNomeCampo('numero');
            $sTelefone->setValorCampo($telefoneTratado);
            $sTelefone->alterar('tMenu1_2_1.php');
            
            if ($sTelefone->mConexao->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_2_1&seguranca={$seguranca}&campo=telefone&codigo={$sTelefone->getSNotificacao()->getCodigo()}");
            }
        }
        
        if (array_key_exists('whatsApp', $atualizar)) {
            //atualize o campo whatsApp
            $sTelefone->setIdTelefone($idTelefone);
            $sTelefone->setNomeCampo('whatsApp');
            $sTelefone->setValorCampo($whatsApp);
            $sTelefone->alterar('tMenu1_2_1.php');
            
            if ($sTelefone->mConexao->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_2_1&seguranca={$seguranca}&campo=whatsApp&codigo={$sTelefone->getSNotificacao()->getCodigo()}");
            }
        }
        
        if (array_key_exists('email', $atualizar)) {
            //atualize o campo email
            $sEmail->setIdEmail($idEmail);
            $sEmail->setNomeCampo('nomenclatura');
            $sEmail->setValorCampo($email);
            $sEmail->alterar('tMenu1_2_1.php');
            
            if ($sEmail->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_2_1&seguranca={$seguranca}&campo=email&codigo={$sEmail->getSNotificacao()->getCodigo()}");
            }
        }
        
        if (array_key_exists('idCargo', $atualizar)) {
            //atualize o campo cargo
            $sUsuario->setIdUsuario($idUsuarioAlterar);
            $sUsuario->setNomeCampo('cargo_idcargo');
            $sUsuario->setValorCampo($idCargo);
            $sUsuario->alterar('tMenu1_2_1.php');
            
            if ($sUsuario->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_2_1&seguranca={$seguranca}&campo=cargo&codigo={$sUsuario->getSNotificacao()->getCodigo()}");
            }
        }
        
        if (array_key_exists('idPermissao', $atualizar)) {
            //atualize o campo cargo
            $sUsuario->setIdUsuario($idUsuarioAlterar);
            $sUsuario->setNomeCampo('permissao_idpermissao');
            $sUsuario->setValorCampo($idPermissao);
            $sUsuario->alterar('tMenu1_2_1.php');
            
            if ($sUsuario->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_2_1&seguranca={$seguranca}&campo=permissao&codigo={$sUsuario->getSNotificacao()->getCodigo()}");
            }
        }
        
        if (array_key_exists('idSecretaria', $atualizar)) {
            //atualize o campo cargo
            $sUsuario->setIdUsuario($idUsuarioAlterar);
            $sUsuario->setNomeCampo('secretaria_idsecretaria');
            $sUsuario->setValorCampo($idSecretaria);
            $sUsuario->alterar('tMenu1_2_1.php');
            
            if ($sUsuario->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_2_1&seguranca={$seguranca}&campo=secretaria&codigo={$sUsuario->getSNotificacao()->getCodigo()}");
            }
        }
        
        if (array_key_exists('idDepartamento', $atualizar)) {            
            $sUsuario->setIdUsuario($idUsuarioAlterar);
            $sUsuario->setNomeCampo('departamento_iddepartamento');
            $sUsuario->setValorCampo($idDepartamento);
            $sUsuario->alterar('tMenu1_2_1.php');
            
            if ($sUsuario->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_2_1&seguranca={$seguranca}&campo=departamento&codigo={$sUsuario->getSNotificacao()->getCodigo()}");
            }
        }
        
        if (array_key_exists('idCoordenacao', $atualizar)) {
            $sUsuario->setIdUsuario($idUsuarioAlterar);
            $sUsuario->setNomeCampo('coordenacao_idcoordenacao');
            $sUsuario->setValorCampo($idCoordenacao);
            $sUsuario->alterar('tMenu1_2_1.php');
            
            if ($sUsuario->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_2_1&seguranca={$seguranca}&campo=coordenacao&codigo={$sUsuario->getSNotificacao()->getCodigo()}");
            }
        }
        
        if (array_key_exists('idSetor', $atualizar)) {
            $sUsuario->setIdUsuario($idUsuarioAlterar);
            $sUsuario->setNomeCampo('setor_idsetor');
            $sUsuario->setValorCampo($idSetor);
            $sUsuario->alterar('tMenu1_2_1.php');
            
            if ($sUsuario->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_2_1&seguranca={$seguranca}&campo=setor&codigo={$sUsuario->getSNotificacao()->getCodigo()}");
            }
        }
        
        if (array_key_exists('situacao', $atualizar)) {
            //atualize o campo situacao
            $sUsuario->setIdUsuario($idUsuarioAlterar);
            $sUsuario->setNomeCampo('situacao');
            $sUsuario->setValorCampo($situacao);
            $sUsuario->alterar('tMenu1_2_1.php');
            
            if ($sUsuario->mConexao->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_2_1&seguranca={$seguranca}&campo=situacao&codigo={$sUsuario->getSNotificacao()->getCodigo()}");
            }
        }
    }
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
    $sHistorico->inserir('tMenu1_1_1.php', $tratarDados);
}
?>