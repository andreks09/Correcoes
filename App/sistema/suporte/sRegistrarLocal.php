<?php

session_start();

require_once '../../../vendor/autoload.php';

use App\sistema\acesso\{
    sSair,
    sConfiguracao,
    sHistorico,
    sTratamentoDados,
    sSecretaria,
    sDepartamento,
    sCoordenacao,
    sSetor,
    sEmail,
    sTelefone
};

//verifica se tem credencial para acessar o sistema
if (!isset($_SESSION['credencial'])) {
    //solicitar saída com tentativa de violação
    $sSair = new sSair();
    $sSair->verificar('0');
}

if (isset($_POST['formulario'])) {
    //formulário secretaria
    if ($_POST['formulario'] == 'f1') {
        $pagina = $_POST['paginaF1'];
        $acao = $_POST['acaoF1'];
        $idUsuario = $_SESSION['credencial']['idUsuario'];
        $secretaria = $_POST['secretariaF1'];
        $endereco = $_POST['enderecoF1'];
        $email = $_POST['emailF1'];
        $telefone = $_POST['telefoneF1'];
        isset($_POST['whatsAppF1']) ? $whatsApp = 1 : $whatsApp = 0;
        $idAmbiente = $_POST['ambienteF1'];

        //alimenta a tabela de histórico
        alimentaHistorico($pagina, $acao, 'secretaria', null, $secretaria, $idUsuario);
        alimentaHistorico($pagina, $acao, 'endereco', null, $endereco, $idUsuario);
        alimentaHistorico($pagina, $acao, 'email', null, $email, $idUsuario);
        alimentaHistorico($pagina, $acao, 'telefone', null, $telefone, $idUsuario);
        alimentaHistorico($pagina, $acao, 'whatsApp', null, $whatsApp, $idUsuario);
        alimentaHistorico($pagina, $acao, 'ambiente_idambiente', null, $idAmbiente, $idUsuario);

        //tratamento de dados
        $tratamentoSecretaria = new sTratamentoDados($secretaria);
        $tratamentoEndereco = new sTratamentoDados($endereco);

        //verifica se já existe alguma secretaria registrada com a nomenclatura
        $secretariaTratada = $tratamentoSecretaria->tratarNomenclatura();
        $enderecoTratado = $tratamentoEndereco->tratarNomenclatura();

        $sSecretaria = new sSecretaria(0);
        $sSecretaria->consultar('tMenu4_1.php');

        $sEmail = new sEmail($email, 'secretaria');
        $sEmail->setNomenclatura($email);
        $sEmail->verificar('tMenu4_1.php');

        //compara os registros do BD com a nova solicitação
        foreach ($sSecretaria->mConexao->getRetorno() as $linha) {
            if ($secretariaTratada == $linha['nomenclatura']) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=secretariaF1&codigo=A15");
                exit();
            }
        }

        //verifica quantidade de caracteres do registro
        if (mb_strlen($secretaria) < 5) {
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=secretariaF1&codigo=A16");
            exit();
        }

        if (mb_strlen($endereco) < 5) {
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=enderecoF1&codigo=A16");
            exit();
        }

        if (!$sEmail->getValidador()) {
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=emailF1&codigo={$sEmail->getSNotificacao()->getCodigo()}");
            exit();
        }
        
        //valida o número de telefone
        $sTelefone = new sTelefone(0, 0, '');
        $telefoneTratado = $sTelefone->tratarTelefone($telefone);
        if (strlen($telefoneTratado) > 0) {
            $sTelefone->verificarTelefone($telefoneTratado);
            if (!$sTelefone->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=telefoneF1&codigo={$sTelefone->getSNotificacao()->getCodigo()}");
                exit();
            }
        }

        //inserir novo registro no BD
        $dadosSecretaria = [
            'nomenclatura' => $secretariaTratada,
            'endereco' => $enderecoTratado,
            'ambiente_idambiente' => $idAmbiente
        ];
        $sSecretaria->inserir('tMenu4_1.php', $dadosSecretaria);

        //retorna a id do registro realizado da Secretaria
        $idSecretaria = $sSecretaria->mConexao->getRegistro();

        //inserir novo registro no BD
        $dadosEmail = [
            'nomenclatura' => $email
        ];
        $sEmail->inserir('tMenu4_1.php', $dadosEmail);
        $idEmail = $sEmail->getMConexao()->getRegistro();

        $sEmailHasSecretaria = new sEmail($email, 'email_has_secretaria');
        $sEmailHasSecretaria->setNomeCampo('secretaria');
        $dadosEmailHasSecretaria = [
            'idemail' => $idEmail,
            'idsecretaria' => $idSecretaria
        ];
        $sEmailHasSecretaria->inserir('tMenu4_1-email_has_secretaria.php', $dadosEmailHasSecretaria);

        if (strlen($telefoneTratado) > 0) {
            $dadosTelefone = [
                'whatsApp' => $whatsApp,
                'numero' => $telefoneTratado
            ];
            $sTelefone->inserir('tMenu4_1.php', $dadosTelefone);

            //retorna a id do registro realizado da Secretaria
            $idTelefone = $sTelefone->mConexao->getRegistro();

            //inserir novo registro no BD
            $sTelefoneHasSecretaria = new sTelefone($idTelefone, $idSecretaria, $whatsApp);
            $sTelefoneHasSecretaria->setNomeCampo('secretaria');
            $dadosTelefoneHasSecretaria = [
                'idtelefone' => $idTelefone,
                'idsecretaria' => $idSecretaria
            ];
            $sTelefoneHasSecretaria->inserir('tMenu4_1-telefone_has_secretaria.php', $dadosTelefoneHasSecretaria);

            //retorna a id do registro realizado da Secretaria
            $idTelefone = $sTelefoneHasSecretaria->mConexao->getRegistro();
        }

        $sConfiguracao = new sConfiguracao();
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=secretariaF1&codigo=S4");
        exit();
    }
    //formulário departamento
    if ($_POST['formulario'] == 'f2') {
        $pagina = $_POST['paginaF2'];
        $acao = $_POST['acaoF2'];
        $idUsuario = $_SESSION['credencial']['idUsuario'];
        $idSecretaria = $_POST['secretariaF2'];
        $departamento = $_POST['departamentoF2'];
        $endereco = $_POST['enderecoF2'];
        $email = $_POST['emailF2'];
        $telefone = $_POST['telefoneF2'];
        isset($_POST['whatsAppF2']) ? $whatsApp = 1 : $whatsApp = 0;

        //alimenta a tabela de histórico
        alimentaHistorico($pagina, $acao, 'secretaria', null, $idSecretaria, $idUsuario);
        alimentaHistorico($pagina, $acao, 'departamento', null, $departamento, $idUsuario);
        alimentaHistorico($pagina, $acao, 'endereco', null, $endereco, $idUsuario);
        alimentaHistorico($pagina, $acao, 'email', null, $email, $idUsuario);
        alimentaHistorico($pagina, $acao, 'telefone', null, $telefone, $idUsuario);
        alimentaHistorico($pagina, $acao, 'whatsApp', null, $whatsApp, $idUsuario);

        //tratamento de dados
        $tratamentoDepartamento = new sTratamentoDados($departamento);
        $tratamentoEndereco = new sTratamentoDados($endereco);

        //verifica se já existe alguma departamento registrado com a nomenclatura
        $departamentoTratado = $tratamentoDepartamento->tratarNomenclatura();
        $enderecoTratado = $tratamentoEndereco->tratarNomenclatura();

        $sDepartamento = new sDepartamento(0);
        $sDepartamento->setNomenclatura($email);
        $sDepartamento->consultar('tMenu4_1.php');

        $sEmail = new sEmail($email, 'departamento');
        $sEmail->verificar('tMenu4_1.php');

        //compara os registros do BD com a nova solicitação
        foreach ($sDepartamento->mConexao->getRetorno() as $linha) {
            if ($departamentoTratado == $linha['nomenclatura']) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=departamentoF2&codigo=A15");
                exit();
            }
        }

        //verifica quantidade de caracteres do registro
        if (mb_strlen($departamento) < 5) {
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=departamentoF2&codigo=A16");
            exit();
        }       

        if (mb_strlen($endereco) < 5) {
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=enderecoF2&codigo=A16");
            exit();
        }

         if (!$sEmail->getValidador()) {
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=emailF2&codigo={$sEmail->getSNotificacao()->getCodigo()}");
            exit();
        }
        
        //valida o número de telefone
        $sTelefone = new sTelefone(0, 0, '');
        $telefoneTratado = $sTelefone->tratarTelefone($telefone);
        if (strlen($telefoneTratado) > 0) {
            $sTelefone->verificarTelefone($telefoneTratado);
            if (!$sTelefone->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=telefoneF2&codigo={$sTelefone->getSNotificacao()->getCodigo()}");
                exit();
            }
        }

        //inserir novo registro no BD
        $dadosDepartamento = [
            'nomenclatura' => $departamentoTratado,
            'endereco' => $enderecoTratado,
            'idSecretaria' => $idSecretaria
        ];
        $sDepartamento->inserir('tMenu4_1.php', $dadosDepartamento);

        //retorna a id do registro realizado da Departamento
        $idDepartamento = $sDepartamento->mConexao->getRegistro();

        //inserir novo registro no BD
        $dadosEmail = [
            'nomenclatura' => $email
        ];
        $sEmail->inserir('tMenu4_1.php', $dadosEmail);
        $idEmail = $sEmail->getMConexao()->getRegistro();

        $sEmailHasDepartamento = new sEmail($email, 'email_has_departamento');
        $sEmailHasDepartamento->setNomeCampo('departamento');
        $dadosEmailHasDepartamento = [
            'idemail' => $idEmail,
            'iddepartamento' => $idDepartamento
        ];
        $sEmailHasDepartamento->inserir('tMenu4_1-email_has_departamento.php', $dadosEmailHasDepartamento);

        if (strlen($telefoneTratado) > 0) {
            $dadosTelefone = [
                'whatsApp' => $whatsApp,
                'numero' => $telefoneTratado
            ];
            $sTelefone->inserir('tMenu4_1.php', $dadosTelefone);

            //retorna a id do registro realizado da Departamento
            $idTelefone = $sTelefone->mConexao->getRegistro();

            //inserir novo registro no BD
            $sTelefoneHasDepartamento = new sTelefone($idTelefone, $idDepartamento, $whatsApp);
            $sTelefoneHasDepartamento->setNomeCampo('departamento');
            $dadosTelefoneHasDepartamento = [
                'idtelefone' => $idTelefone,
                'iddepartamento' => $idDepartamento
            ];
            $sTelefoneHasDepartamento->inserir('tMenu4_1-telefone_has_departamento.php', $dadosTelefoneHasDepartamento);
        }

        $sConfiguracao = new sConfiguracao();
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=departamentoF2&codigo=S4");
        exit();
    }    
    //formulário coordenacao
    if ($_POST['formulario'] == 'f3') {
        $pagina = $_POST['paginaF3'];
        $acao = $_POST['acaoF3'];
        $idUsuario = $_SESSION['credencial']['idUsuario'];
        $idSecretaria = $_POST['secretariaF3'];
        $coordenacao = $_POST['coordenacaoF3'];
        $endereco = $_POST['enderecoF3'];
        $email = $_POST['emailF3'];
        $telefone = $_POST['telefoneF3'];
        isset($_POST['whatsAppF3']) ? $whatsApp = 1 : $whatsApp = 0;

        //alimenta a tabela de histórico
        alimentaHistorico($pagina, $acao, 'secretaria', null, $idSecretaria, $idUsuario);
        alimentaHistorico($pagina, $acao, 'coordenacao', null, $coordenacao, $idUsuario);
        alimentaHistorico($pagina, $acao, 'endereco', null, $endereco, $idUsuario);
        alimentaHistorico($pagina, $acao, 'email', null, $email, $idUsuario);
        alimentaHistorico($pagina, $acao, 'telefone', null, $telefone, $idUsuario);
        alimentaHistorico($pagina, $acao, 'whatsApp', null, $whatsApp, $idUsuario);

        //tratamento de dados
        $tratamentoCoordenacao = new sTratamentoDados($coordenacao);
        $tratamentoEndereco = new sTratamentoDados($endereco);

        //verifica se já existe alguma coordenacao registrado com a nomenclatura
        $coordenacaoTratado = $tratamentoCoordenacao->tratarNomenclatura();
        $enderecoTratado = $tratamentoEndereco->tratarNomenclatura();

        $sCoordenacao = new sCoordenacao(0);
        $sCoordenacao->setNomenclatura($email);
        $sCoordenacao->consultar('tMenu4_1.php');

        $sEmail = new sEmail($email, 'coordenacao');
        $sEmail->verificar('tMenu4_1.php');

        //compara os registros do BD com a nova solicitação
        foreach ($sCoordenacao->mConexao->getRetorno() as $linha) {
            if ($coordenacaoTratado == $linha['nomenclatura']) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=coordenacaoF3&codigo=A15");
                exit();
            }
        }

        //verifica quantidade de caracteres do registro
        if (mb_strlen($coordenacao) < 5) {
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=coordenacaoF3&codigo=A16");
            exit();
        }       

        if (mb_strlen($endereco) < 5) {
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=enderecoF3&codigo=A16");
            exit();
        }

         if (!$sEmail->getValidador()) {
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=emailF3&codigo={$sEmail->getSNotificacao()->getCodigo()}");
            exit();
        }
        
        //valida o número de telefone
        $sTelefone = new sTelefone(0, 0, '');
        $telefoneTratado = $sTelefone->tratarTelefone($telefone);
        if (strlen($telefoneTratado) > 0) {
            $sTelefone->verificarTelefone($telefoneTratado);
            if (!$sTelefone->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=telefoneF3&codigo={$sTelefone->getSNotificacao()->getCodigo()}");
                exit();
            }
        }

        //inserir novo registro no BD
        $dadosCoordenacao = [
            'nomenclatura' => $coordenacaoTratado,
            'endereco' => $enderecoTratado,
            'idSecretaria' => $idSecretaria
        ];
        $sCoordenacao->inserir('tMenu4_1.php', $dadosCoordenacao);

        //retorna a id do registro realizado da Coordenacao
        $idCoordenacao = $sCoordenacao->mConexao->getRegistro();

        //inserir novo registro no BD
        $dadosEmail = [
            'nomenclatura' => $email
        ];
        $sEmail->inserir('tMenu4_1.php', $dadosEmail);
        $idEmail = $sEmail->getMConexao()->getRegistro();

        $sEmailHasCoordenacao = new sEmail($email, 'email_has_coordenacao');
        $sEmailHasCoordenacao->setNomeCampo('coordenacao');
        $dadosEmailHasCoordenacao = [
            'idemail' => $idEmail,
            'idcoordenacao' => $idCoordenacao
        ];
        $sEmailHasCoordenacao->inserir('tMenu4_1-email_has_coordenacao.php', $dadosEmailHasCoordenacao);

        if (strlen($telefoneTratado) > 0) {
            $dadosTelefone = [
                'whatsApp' => $whatsApp,
                'numero' => $telefoneTratado
            ];
            $sTelefone->inserir('tMenu4_1.php', $dadosTelefone);

            //retorna a id do registro realizado da Coordenacao
            $idTelefone = $sTelefone->mConexao->getRegistro();

            //inserir novo registro no BD
            $sTelefoneHasCoordenacao = new sTelefone($idTelefone, $idCoordenacao, $whatsApp);
            $sTelefoneHasCoordenacao->setNomeCampo('coordenacao');
            $dadosTelefoneHasCoordenacao = [
                'idtelefone' => $idTelefone,
                'idcoordenacao' => $idCoordenacao
            ];
            $sTelefoneHasCoordenacao->inserir('tMenu4_1-telefone_has_coordenacao.php', $dadosTelefoneHasCoordenacao);
        }

        $sConfiguracao = new sConfiguracao();
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=coordenacaoF3&codigo=S4");
        exit();
    }
    //formulário setor
    if ($_POST['formulario'] == 'f4') {
        $pagina = $_POST['paginaF4'];
        $acao = $_POST['acaoF4'];
        $idUsuario = $_SESSION['credencial']['idUsuario'];
        $idSecretaria = $_POST['secretariaF4'];
        $setor = $_POST['setorF4'];
        $endereco = $_POST['enderecoF4'];
        $email = $_POST['emailF4'];
        $telefone = $_POST['telefoneF4'];
        isset($_POST['whatsAppF4']) ? $whatsApp = 1 : $whatsApp = 0;

        //alimenta a tabela de histórico
        alimentaHistorico($pagina, $acao, 'secretaria', null, $idSecretaria, $idUsuario);
        alimentaHistorico($pagina, $acao, 'setor', null, $setor, $idUsuario);
        alimentaHistorico($pagina, $acao, 'endereco', null, $endereco, $idUsuario);
        alimentaHistorico($pagina, $acao, 'email', null, $email, $idUsuario);
        alimentaHistorico($pagina, $acao, 'telefone', null, $telefone, $idUsuario);
        alimentaHistorico($pagina, $acao, 'whatsApp', null, $whatsApp, $idUsuario);

        //tratamento de dados
        $tratamentoSetor = new sTratamentoDados($setor);
        $tratamentoEndereco = new sTratamentoDados($endereco);

        //verifica se já existe alguma setor registrado com a nomenclatura
        $setorTratado = $tratamentoSetor->tratarNomenclatura();
        $enderecoTratado = $tratamentoEndereco->tratarNomenclatura();

        $sSetor = new sSetor(0);
        $sSetor->setNomenclatura($email);
        $sSetor->consultar('tMenu4_1.php');

        $sEmail = new sEmail($email, 'setor');
        $sEmail->verificar('tMenu4_1.php');

        //compara os registros do BD com a nova solicitação
        foreach ($sSetor->mConexao->getRetorno() as $linha) {
            if ($setorTratado == $linha['nomenclatura']) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=setorF4&codigo=A15");
                exit();
            }
        }

        //verifica quantidade de caracteres do registro
        if (mb_strlen($setor) < 5) {
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=setorF4&codigo=A16");
            exit();
        }       

        if (mb_strlen($endereco) < 5) {
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=enderecoF4&codigo=A16");
            exit();
        }

         if (!$sEmail->getValidador()) {
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=emailF4&codigo={$sEmail->getSNotificacao()->getCodigo()}");
            exit();
        }
        
        //valida o número de telefone
        $sTelefone = new sTelefone(0, 0, '');
        $telefoneTratado = $sTelefone->tratarTelefone($telefone);
        if (strlen($telefoneTratado) > 0) {
            $sTelefone->verificarTelefone($telefoneTratado);
            if (!$sTelefone->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=telefoneF4&codigo={$sTelefone->getSNotificacao()->getCodigo()}");
                exit();
            }
        }

        //inserir novo registro no BD
        $dadosSetor = [
            'nomenclatura' => $setorTratado,
            'endereco' => $enderecoTratado,
            'idSecretaria' => $idSecretaria
        ];
        $sSetor->inserir('tMenu4_1.php', $dadosSetor);

        //retorna a id do registro realizado da Setor
        $idSetor = $sSetor->mConexao->getRegistro();

        //inserir novo registro no BD
        $dadosEmail = [
            'nomenclatura' => $email
        ];
        $sEmail->inserir('tMenu4_1.php', $dadosEmail);
        $idEmail = $sEmail->getMConexao()->getRegistro();

        $sEmailHasSetor = new sEmail($email, 'email_has_setor');
        $sEmailHasSetor->setNomeCampo('setor');
        $dadosEmailHasSetor = [
            'idemail' => $idEmail,
            'idsetor' => $idSetor
        ];
        $sEmailHasSetor->inserir('tMenu4_1-email_has_setor.php', $dadosEmailHasSetor);

        if (strlen($telefoneTratado) > 0) {
            $dadosTelefone = [
                'whatsApp' => $whatsApp,
                'numero' => $telefoneTratado
            ];
            $sTelefone->inserir('tMenu4_1.php', $dadosTelefone);

            //retorna a id do registro realizado da Setor
            $idTelefone = $sTelefone->mConexao->getRegistro();

            //inserir novo registro no BD
            $sTelefoneHasSetor = new sTelefone($idTelefone, $idSetor, $whatsApp);
            $sTelefoneHasSetor->setNomeCampo('setor');
            $dadosTelefoneHasSetor = [
                'idtelefone' => $idTelefone,
                'idsetor' => $idSetor
            ];
            $sTelefoneHasSetor->inserir('tMenu4_1-telefone_has_setor.php', $dadosTelefoneHasSetor);
        }

        $sConfiguracao = new sConfiguracao();
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_1&campo=setorF4&codigo=S4");
        exit();
    }
    
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
    $sHistorico->inserir('tMenu4_1.php', $tratarDados);
}

?>