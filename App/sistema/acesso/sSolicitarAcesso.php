<?php

require_once '../../../vendor/autoload.php';

use App\sistema\acesso\{
    sSair,
    sHistorico,
    sConfiguracao,
    sEmail,
    sUsuario,
    sTelefone
};

if (isset($_POST['pagina'])) {
    if ($_POST['pagina'] != 'tMenu1_3_1.php') {
        //solicitar saída com tentativa de violação
        $sSair = new sSair();
        $sSair->verificar('0');
    }
    $sConfiguracao = new sConfiguracao();

    $pagina = $_POST['pagina'];
    $acao = $_POST['acao'];
    $valorCampoAnterior = '';
    $idUsuario = '';
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $sexo = $_POST['sexo'];
    $telefone = $_POST['telefone'];
    isset($_POST['whatsApp']) ? $whatsApp = 1 : $whatsApp = 0;
    $email = $_POST['email'];
    isset($_POST['secretaria']) ? $secretaria = $_POST['secretaria'] : '';
    isset($_POST['departamento']) ? $departamento = $_POST['departamento'] : '';
    isset($_POST['coordenacao']) ? $coordenacao = $_POST['coordenacao'] : '';
    isset($_POST['setor']) ? $setor = $_POST['setor'] : $setor = '';
    isset($_POST['cargo']) ? $cargo = $_POST['cargo'] : $cargo = '';
    isset($_POST['termo']) ? $termo = 1 : $termo = 0;
    $situacao = 0;
    
    //tratamento de campos
    $sTelefone = new sTelefone(0, 0, '0');
    if(strlen($telefone) > 0){
        //se foi passado algum número de telefone, trate o número antes
        $telefone = $sTelefone->tratarTelefone($telefone);
        //verificar se o número tratado atende aos requisitos
        $sTelefone->verificarTelefone($telefone);
        if (!$sTelefone->getValidador()) {
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tSolicitarAcesso.php?campo=telefone&codigo={$sTelefone->getSNotificacao()->getCodigo()}");
            exit();
        }
    }    

    //registrar solicitação no sistema
    alimentaHistorico($pagina, $acao, 'nome', $valorCampoAnterior, $nome, $idUsuario);
    alimentaHistorico($pagina, $acao, 'sobrenome', $valorCampoAnterior, $sobrenome, $idUsuario);
    alimentaHistorico($pagina, $acao, 'sexo', $valorCampoAnterior, $sexo, $idUsuario);
    alimentaHistorico($pagina, $acao, 'telefone_idtelefone', $valorCampoAnterior, $telefone, $idUsuario);
    alimentaHistorico($pagina, $acao, 'whatsApp', $valorCampoAnterior, $whatsApp, $idUsuario);
    alimentaHistorico($pagina, $acao, 'email', $valorCampoAnterior, $email, $idUsuario);
    alimentaHistorico($pagina, $acao, 'secretaria', $valorCampoAnterior, $secretaria, $idUsuario);
    alimentaHistorico($pagina, $acao, 'departamento', $valorCampoAnterior, $departamento, $idUsuario);
    alimentaHistorico($pagina, $acao, 'coordenacao', $valorCampoAnterior, $coordenacao, $idUsuario);
    alimentaHistorico($pagina, $acao, 'setor', $valorCampoAnterior, $setor, $idUsuario);
    alimentaHistorico($pagina, $acao, 'cargo', $valorCampoAnterior, $cargo, $idUsuario);
    alimentaHistorico($pagina, $acao, 'termo', $valorCampoAnterior, $termo, $idUsuario);
    alimentaHistorico($pagina, $acao, 'situacao', $valorCampoAnterior, $situacao, $idUsuario);
    
    $inserir = [
        'nome' => $nome,
        'sobrenome' => $sobrenome,
        'sexo' => $sexo,
        'telefone' => $telefone,
        'whatsApp' => $whatsApp,
        'email' => $email,
        'secretaria_idsecretaria' => $secretaria,
        'departamento_iddepartamento' => $departamento,
        'coordenacao_idcoordenacao' => $coordenacao,
        'setor_idsetor' => $setor,
        'cargo_idcargo' => $cargo,
        'situacao' => $situacao,
    ];

    //verifica se o email já não está registrado
    $sEmail = new sEmail($email, '');
    $sEmail->verificar('tSolicitarAcesso.php');
    
    if (!$sEmail->getValidador()) {
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tSolicitarAcesso.php?campo=email&codigo={$sEmail->getSNotificacao()->getCodigo()}");
        exit();
    } else {
        //alimenta os dados do usuário
        $sUsuario = new sUsuario();
        $sUsuario->setNome($nome);
        $sUsuario->setSobrenome($sobrenome);
        $sUsuario->setSexo($sexo);        
        $sUsuario->setTelefone($telefone);
        $sUsuario->setWhatsApp($whatsApp);
        $sUsuario->setEmail($email);
        $sUsuario->setIdSecretaria($secretaria);
        $sUsuario->setIdDepartamento($departamento);
        $sUsuario->setIdCoordenacao($coordenacao);
        $sUsuario->setIdSetor($setor);
        $sUsuario->setIdCargo($cargo);
        $sUsuario->setSituacao($situacao);
                
        //insere os dados no bd
        $sUsuario->inserir('sSolicitarAcesso.php');
        
        if ($sUsuario->getValidador()) {
            $sConfiguracao = new sConfiguracao();
             header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tSolicitarAcesso.php?campo=todos&codigo={$sUsuario->getSNotificacao()->getCodigo()}");
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
    $sHistorico->inserir('tSolicitarAcesso.php', $tratarDados);
}

?>