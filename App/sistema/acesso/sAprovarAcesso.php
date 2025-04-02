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
    sSolicitacao,
    sSenha,
    sNotificacao
};

//verifica se tem credencial para acessar o sistema
if (!isset($_SESSION['credencial'])) {
    //solicitar saída com tentativa de violação
    $sSair = new sSair();
    $sSair->verificar('0');
}

if (isset($_POST['pagina'])) {
    if ($_POST['pagina'] != 'tMenu1_3_1.php') {
        //solicitar saída com tentativa de violação
        $sSair = new sSair();
        $sSair->verificar('0');
    }
    
    $idSolicitacao = $_POST['idSolicitacao'];
    isset($_POST['situacao']) ? $situacao = 1 : $situacao = 0;
    $pagina = $_POST['pagina'];
    $acao = $_POST['acao'];
    $idUsuario = $_SESSION['credencial']['idUsuario'];
    
    //configura timezone para São Paulo
    $sConfiguracao = new sConfiguracao();
    $sConfiguracao->getTimeZone();
    $dataHoraExaminador = date("Y-m-d H:i:s");
    
    //alimenta o histórico
    alimentaHistorico($pagina, $acao, 'situacao', '', $situacao, $idUsuario);
    alimentaHistorico($pagina, $acao, 'examinador', '', $idUsuario, $idUsuario);    
    alimentaHistorico($pagina, $acao, 'dataHoraExaminador', '', $dataHoraExaminador, $idUsuario);    
    
    //altera os dados da solicitação
    $sSolicitacao = new sSolicitacao();
    $sSolicitacao->setIdSolicitacao($idSolicitacao);
    
    //altera o campo situação
    $sSolicitacao->setNomeCampo('situacao');
    $sSolicitacao->setValorCampo($situacao);
    $sSolicitacao->alterar($pagina);
    
    //altera o campo examinador
    $sSolicitacao->setNomeCampo('examinador');
    $sSolicitacao->setValorCampo($idUsuario);
    $sSolicitacao->alterar($pagina);
    
    //altera o campo dataHoraExaminador
    $sSolicitacao->setNomeCampo('dataHoraExaminador');
    $sSolicitacao->setValorCampo($dataHoraExaminador);
    $sSolicitacao->alterar($pagina);
    
    //consulta dados da solicitação
    $sSolicitacao->consultar($pagina);    
    
    foreach ($sSolicitacao->mConexao->getRetorno() as $value) {
        $nome = $value['nome'];
        $sobrenome = $value['sobrenome'];
        $sexo = $value['sexo'];
        $telefone = $value['telefone'];
        $whatsApp = $value['whatsApp'];
        $email = $value['email'];
        $idSecretaria = $value['secretaria_idsecretaria'];
        isset($value['departamento_iddepartamento']) ? $idDepartamento = $value['departamento_iddepartamento'] : $idDepartamento = '';
        isset($value['coordenacao_idcoordenacao']) ? $idCoordenacao = $value['coordenacao_idcoordenacao'] : $idCoordenacao = '';
        isset($value['setor_idsetor']) ? $idSetor = $value['setor_idsetor'] : $idSetor = '';
        $idCargo = $value['cargo_idcargo'];
    }    
        
    //verifica os dados antes de inserir no bd
    //verifica nome
    $sUsuario = new sUsuario();
    $sUsuario->verificarNome($nome);
    
    if(!$sUsuario->getValidador()){
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_3&campo=nome&codigo={$sUsuario->getSNotificacao()->getCodigo()}");
        exit();
    }
    
    //verifica sobrenome
    $sUsuario->verificarSobrenome($sobrenome);
    
    if(!$sUsuario->getValidador()){
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_3&campo=sobrenome&codigo={$sUsuario->getSNotificacao()->getCodigo()}");
        exit();
    }
    
    //reprova a solicitação e retorna com sucesso
    if(!$situacao){
        //envia e-mail para o usuário com senha provisória
        $nomeTratado = $minuscula = mb_strtolower($nome);
        $nomeTratado = ucfirst($nomeTratado);
        $sexo == 'M' ? $tratamento = 'o' : $tratamento = 'a';
        
        $assunto = 'Reprovação de Acesso';
        $mensagem = <<<HTML
        <b>Prezad$tratamento $nomeTratado,</b><br />
        <p>
            A sua solicitação para acessar o SSPMI (Sistema de Suporte da Prefeitura Municiapal de Itapoá) foi reprovada por alguma das razões:<br />
            _ Inconsistência dos dados registrados com o apresentado no RH (IPM);<br />
            _ Não recebeu treinamento para uso da ferramenta;<br />
            Caso você entenda que houve um equívoco na decisão, favor contatar o Departamento de Tecnologia da Informação.<br />
            <br />
            <b>Obs.:</b> Esse e-mail é somente para mensagens automáticas, favor não respondê-lo.
        </p>
HTML;

        $sEmail = new sEmail($email, '');
        $sEmail->setPara($email);
        $sEmail->setAssunto(utf8_decode($assunto));
        $sEmail->setMensagem(utf8_decode($mensagem));

        $sEmail->enviar($pagina);

        if(!$sEmail->getValidador()){
            $sNotificacao = new sNotificacao('A28');
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_3&campo=situacao&codigo={$sNotificacao->getCodigo()}");
            exit();
        }       
        
        $sNotificacao = new sNotificacao('S1');
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_3&campo=situacao&codigo={$sNotificacao->getCodigo()}");
        exit();
    }
    
    //insere dados do telefone
    $dados = [
        'whatsApp' => $whatsApp,
        'numero' => $telefone
    ];
    $sTelefone = new sTelefone(0, 0, 0);
    $sTelefone->inserir('tMenu1_3_1.php', $dados);
    $idTelefone = $sTelefone->mConexao->getRegistro();
    
    //gera senha para inserir no bd
    $sSenha = new sSenha(true);
    $sSenha->gerar();
    $sSenha->criptografar($sSenha->getSenha());
    
    //insere email e senha no bd
    $dados = [
        'nomenclatura' => $email,
        'senha' => $sSenha->getSenhaCriptografada()
    ];
    $sEmail = new sEmail($email, '');
    $sEmail->inserir($pagina, $dados);
    $idEmail = $sEmail->mConexao->getRegistro();    
    
    //dados do usuario
    $sUsuario->setNome($nome);
    $sUsuario->setSobrenome($sobrenome);
    $sUsuario->setSexo($sexo);
    $sUsuario->setSituacao($situacao);
    $sUsuario->setIdSetor($idSetor);
    $sUsuario->setIdCoordenacao($idCoordenacao);
    $sUsuario->setIdDepartamento($idDepartamento);
    $sUsuario->setIdSecretaria($idSecretaria);
    $sUsuario->setIdTelefone($idTelefone);
    $sUsuario->setIdCargo($idCargo);
    $sUsuario->setIdEmail($idEmail);
    $sUsuario->setIdPermissao(1);
    
    //registra os dados do usuário
    $sUsuario->inserir($pagina);
    
    //envia e-mail para o usuário com senha provisória
    $nomeTratado = $minuscula = mb_strtolower($nome);
    $nomeTratado = ucfirst($nomeTratado);
    $sexo == 'M' ? $tratamento = 'o' : $tratamento = 'a';
    
    $assunto = 'Aprovação de Acesso';
    $mensagem = <<<HTML
    <b>Prezad$tratamento $nomeTratado,</b><br />
    <p>
        A sua solicitação para acessar o SSPMI (Sistema de Suporte da Prefeitura Municiapal de Itapoá) foi aprovada. Segue abaixo senha temporária.<br />
        {$sSenha->getSenha()}<br />
        <br />
        Obs.: Esse e-mail é somente para mensagens automáticas, não respondê-lo.
    </p>
HTML;
                
    $sEmail->setPara($email);
    $sEmail->setAssunto(utf8_decode($assunto));
    $sEmail->setMensagem(utf8_decode($mensagem));
    
    $sEmail->enviar($pagina);

    if(!$sEmail->getValidador()){
        $sNotificacao = new sNotificacao('A28');
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_3&campo=situacao&codigo={$sNotificacao->getCodigo()}");
        exit();
    }
    
    //gera notificação e redireciona para a página
    $sNotificacao = new sNotificacao('S1');    
    header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_3&campo=verificar&codigo={$sNotificacao->getCodigo()}");
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
    $sHistorico->inserir('tMenu1_1_1.php', $tratarDados);
}
?>