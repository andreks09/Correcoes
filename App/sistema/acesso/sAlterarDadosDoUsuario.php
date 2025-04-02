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
    sNotificacao,
    sSenha
};

//verifica se tem credencial para acessar o sistema
if (!isset($_SESSION['credencial'])) {
    //solicitar saída com tentativa de violação
    $sSair = new sSair();
    $sSair->verificar('0');
}

if (isset($_POST['pagina'])) {
    if ($_POST['pagina'] != 'tMenu1_1_1.php') {
        //solicitar saída com tentativa de violação
        $sSair = new sSair();
        $sSair->verificar('0');
    }
    
    $idUsuario = $_SESSION['credencial']['idUsuario'];
    $pagina = $_POST['pagina'];
    $acao = $_POST['acao'];
    //$imagem = $_POST['imagem']; próxima build
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $sexo = $_POST['sexo'];
    $telefone = $_POST['telefoneUsuario'];
    isset($_POST['whatsAppUsuario']) ? $whatsAppUsuario = 1 : $whatsAppUsuario = 0;
    $emailUsuario = $_POST['emailUsuario'];
    $atualizar = [];
    $alteracao = false;    
    isset($_POST['senhaUsuario']) ? $senhaUsuario = $_POST['senhaUsuario'] : $senhaUsuario = false;
    
    //se existir um telefone registrado
    if($_SESSION['credencial']['idTelefoneUsuario']){
        $sTelefoneUsuario = new sTelefone($_SESSION['credencial']['idTelefoneUsuario'], 0, 'tMenu1_1_1.php');  
        $sWhatsAppUsuario = new sTelefone($_SESSION['credencial']['idTelefoneUsuario'], 0, 'tMenu1_1_1.php');
    }else{
        $sTelefoneUsuario = new sTelefone(0, 0, 'tMenu1_1_1.php');
        $sWhatsAppUsuario = new sTelefone(0, 0, 'tMenu1_1_1.php');
    }  
    
    //busca os dados do usuário no bd
    $sNomeUsuario = new sUsuario();
    $sNomeUsuario->setNomeCampo('idusuario');
    $sNomeUsuario->setValorCampo($idUsuario);
    $sNomeUsuario->consultar('tMenu1_1_1.php');
    
    foreach ($sNomeUsuario->mConexao->getRetorno() as $value) {
        $idEmail = $value['email_idemail'];
    }
    
    $sEmailUsuario = new sEmail(0, '');
    $sEmailUsuario->setNomeCampo('idemail');
    $sEmailUsuario->setValorCampo($idEmail);
    $sEmailUsuario->consultar('tMenu1_1_1.php');
    
    foreach ($sEmailUsuario->mConexao->getRetorno() as $value) {
        $senha = $value['senha'];
        $email = $value['nomenclatura'];
    }
    
    alimentaHistorico($pagina, $acao, 'nome', $_SESSION['credencial']['nome'], $nome, $idUsuario);
    alimentaHistorico($pagina, $acao, 'sobrenome', $_SESSION['credencial']['sobrenome'], $sobrenome, $idUsuario);
    alimentaHistorico($pagina, $acao, 'sexo', $_SESSION['credencial']['sexo'], $sexo, $idUsuario);
    alimentaHistorico($pagina, $acao, 'telefone', $_SESSION['credencial']['telefoneUsuario'], $telefone, $idUsuario);
    alimentaHistorico($pagina, $acao, 'whatsApp', $_SESSION['credencial']['whatsAppUsuario'], $whatsAppUsuario, $idUsuario);
    alimentaHistorico($pagina, $acao, 'nomenclatura', $email, $emailUsuario, $idUsuario);
    if($senhaUsuario){
        //etapa2 - validação de conteúdo
        $sTratamentoSenha = new sTratamentoDados($senhaUsuario);
        $senhaTratada = $sTratamentoSenha->tratarSenha();
        if(!$senhaTratada){
            //se não tem campo para validar
            $sConfiguracao = new sConfiguracao();
            $sNotificacao = new sNotificacao('A4');
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_1_1&campo=senha&codigo={$sNotificacao->getCodigo()}");
            exit();
        }else{
            //etapa3 - atualizar os dados
            $alteracao = true;
            $atualizar['senhaTratada'] = $senhaTratada;
            
            //criptografa a senha para ser alterada no bd
            $sSenha = new sSenha(true);
            $sSenha->criptografar($senhaTratada);
            $senhaCriptografada = $sSenha->getSenhaCriptografada();
        }
        alimentaHistorico($pagina, $acao, 'senha', $senha, $senhaCriptografada, $idUsuario);
    }
    
    //se existir um telefone registrado
    $sTratamentoTelefone = new sTratamentoDados($telefone);
    $telefoneUsuario = $sTratamentoTelefone->tratarTelefone();
    
    //etapa1 - verificar campos alterados
    if ($_SESSION['credencial']['nome'] != $nome) {
        //insere dados na tabela histórico
        $valorCampoAnterior = $_SESSION['credencial']['nome'];
        $sNomeUsuario->verificarNome($nome);
        
        //etapa2 - validação do conteúdo
        if (!$sNomeUsuario->getValidador()) {
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_1_1&campo=nome&codigo={$sNomeUsuario->getSNotificacao()->getCodigo()}");
            exit();
        } else {
            //etapa3 - atualizar os dados
            $alteracao = true;
            $atualizar['nome'] = $nome;
        }
    }

    if ($_SESSION['credencial']['sobrenome'] != $sobrenome) {
        //insere dados na tabela histórico
        $sSobrenomeUsuario = new sUsuario();
        $sSobrenomeUsuario->verificarSobrenome($sobrenome);
        
        //etapa2 - validação do conteúdo
        if (!$sSobrenomeUsuario->getValidador()) {
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_1_1&campo=sobrenome&codigo={$sSobrenomeUsuario->getSNotificacao()->getCodigo()}");
            exit();
        } else {
            //etapa3 - atualizar os dados
            $alteracao = true;
            $atualizar['sobrenome'] = $sobrenome;
        }
    }

    $sexo == 'M' ? $sexoSessao = 'Masculino' : $sexoSessao = 'Feminino';
    if ($_SESSION['credencial']['sexo'] != $sexoSessao) {
        $sSexoUsuario = new sUsuario();
        $alteracao = true;
        $atualizar['sexo'] = $sexo;
    }
    
    $_SESSION['credencial']['telefoneUsuario'] == '--' ? $telefoneSessao = '' : $telefoneSessao = $_SESSION['credencial']['telefoneUsuario'];
    if ($telefoneSessao != $telefoneUsuario) {
        //validação do conteúdo
        $sTelefoneUsuario->verificarTelefone($telefoneUsuario);
        if (!$sTelefoneUsuario->getValidador()) {
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_1_1&campo=telefone&codigo={$sTelefoneUsuario->getSNotificacao()->getCodigo()}");
            exit();
        } else {
            //etapa3 - atualizar os dados
            $alteracao = true;
            $atualizar['telefoneUsuario'] = $telefoneUsuario;
        }
    }

    if ($_SESSION['credencial']['whatsAppUsuario'] != $whatsAppUsuario) {
        $alteracao = true;
        $atualizar['whatsAppUsuario'] = $whatsAppUsuario;
    }

    if ($_SESSION['credencial']['emailUsuario'] != $emailUsuario) {
        //etapa2 - validação de conteúdo
        $sEmailUsuario->verificar('tMenu1_1_1.php');
        
        if (!$sEmailUsuario->getValidador()) {
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_1_1&campo=email&codigo={$sEmailUsuario->getSNotificacao()->getCodigo()}");
            exit();
        } else {
            //etapa3 - atualizar os dados
            $alteracao = true;
            $atualizar['emailUsuario'] = $emailUsuario;
        }
    }
    
    if ($alteracao == false) {
        //se não tem campo para validar
        $sConfiguracao = new sConfiguracao();
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_1_1");
    }else{        
        //se tem campos para atualizar
        if (array_key_exists('nome', $atualizar)) {
            //atualize o campo nome
            $sNomeUsuario->setIdUsuario($idUsuario);
            $sNomeUsuario->setNomeCampo('nome');
            $sNomeUsuario->setValorCampo($nome);
            $sNomeUsuario->alterar('tMenu1_1_1.php');
            //atualize a sessão nome
            $_SESSION['credencial']['nome'] = $nome;
            
            if ($sNomeUsuario->mConexao->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_1_1&campo=nome&codigo={$sNomeUsuario->getSNotificacao()->getCodigo()}");
            }
        }
        
        if (array_key_exists('sobrenome', $atualizar)) {
            //atualize o campo nome
            $sSobrenomeUsuario->setIdUsuario($idUsuario);
            $sSobrenomeUsuario->setNomeCampo('sobrenome');
            $sSobrenomeUsuario->setValorCampo($sobrenome);
            $sSobrenomeUsuario->alterar('tMenu1_1_1.php');
            
            //atualize a sessão nome
            $_SESSION['credencial']['sobrenome'] = $sobrenome;
            
            if ($sSobrenomeUsuario->mConexao->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_1_1&campo=sobrenome&codigo={$sSobrenomeUsuario->getSNotificacao()->getCodigo()}");
            }
        }
        
        if (array_key_exists('sexo', $atualizar)) {
            //atualize o campo nome
            $sSexoUsuario->setIdUsuario($idUsuario);
            $sSexoUsuario->setNomeCampo('sexo');
            $sSexoUsuario->setValorCampo($sexo);
            $sSexoUsuario->alterar('tMenu1_1_1.php');
            
            //atualize a sessão nome
            $sexo == 'M' ? $sexo = 'Masculino' : $sexo = 'Feminino';
            $_SESSION['credencial']['sexo'] = $sexo;
            
            if ($sSexoUsuario->mConexao->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_1_1&campo=sexo&codigo={$sSexoUsuario->getSNotificacao()->getCodigo()}");
            }
        }
        
        if (array_key_exists('telefoneUsuario', $atualizar)) {
            //se existir id atualize o campo telefone, senão insere
            
            if(!is_null($_SESSION['credencial']['idTelefoneUsuario'])){   
                $sTelefoneUsuario->setNomeCampo('numero');
                $sTelefoneUsuario->setValorCampo($telefoneUsuario);
                $sTelefoneUsuario->alterar('tMenu1_1_1.php');

                //atualize a sessão nome
                $_SESSION['credencial']['telefoneUsuario'] = $telefoneUsuario;
            }else{
                $dados = [
                    'whatsApp' => $whatsAppUsuario,
                    'numero' => $telefoneUsuario
                ];
                $sTelefoneUsuario->inserir('tMenu1_1_1.php', $dados);

                //altera na sessão
                $idTelefoneUsuario = $sTelefoneUsuario->mConexao->getRegistro();
                $_SESSION['credencial']['idTelefoneUsuario'] = $idTelefoneUsuario;
                
                $sUsuario = new sUsuario();
                $sUsuario->setNomeCampo('idusuario');
                $sUsuario->setValorCampo($idUsuario);
                $sUsuario->alterar('tMenu1_1_1.php');                
            }
            
            if ($sTelefoneUsuario->mConexao->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_1_1&campo=telefone&codigo={$sTelefoneUsuario->getSNotificacao()->getCodigo()}");
                exit();
            }
        }
        
        if (array_key_exists('whatsAppUsuario', $atualizar)) {
            //atualize o campo nome
            $sWhatsAppUsuario->setNomeCampo('whatsApp');
            $sWhatsAppUsuario->setValorCampo($whatsAppUsuario);
            $sWhatsAppUsuario->alterar('tMenu1_1_1.php');
            
            //atualize a sessão nome
            $_SESSION['credencial']['whatsAppUsuario'] = $whatsAppUsuario;
            
            if ($sWhatsAppUsuario->mConexao->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_1_1&campo=telefone&codigo={$sWhatsAppUsuario->getSNotificacao()->getCodigo()}");
                exit();
            }
        }
        
        if (array_key_exists('emailUsuario', $atualizar)) {
            //atualize o campo email
            $sEmailUsuario->setIdEmail($_SESSION['credencial']['idEmailUsuario']);
            $sEmailUsuario->setNomeCampo('nomenclatura');
            $sEmailUsuario->setValorCampo($emailUsuario);
            $sEmailUsuario->alterar('tMenu1_1_1.php');
            
            //atualize a sessão nome
            $_SESSION['credencial']['emailUsuario'] = $emailUsuario;
            
            if ($sEmailUsuario->mConexao->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_1_1&campo=email&codigo={$sEmailUsuario->getSNotificacao()->getCodigo()}");
            }
        }
        
        if (array_key_exists('senhaTratada', $atualizar)) {            
            //altera a senha do email no bd
            $sEmailUsuario->setIdEmail($_SESSION['credencial']['idEmailUsuario']);
            $sEmailUsuario->setNomeCampo('senha');
            $sEmailUsuario->setValorCampo($sSenha->getSenhaCriptografada());
            $sEmailUsuario->alterar('tMenu1_1_1.php');                        
                        
            if ($sEmailUsuario->mConexao->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_1_1&campo=email&codigo={$sEmailUsuario->getSNotificacao()->getCodigo()}");
            }
        }
        
        if (array_key_exists('idPermissao', $atualizar)) {
            //atualize o campo nome            
            $sPermissaoUsuario->setIdUsuario($idUsuario);
            $sPermissaoUsuario->setNomeCampo('permissao_idpermissao');
            $sPermissaoUsuario->setValorCampo($idPermissao);
            $sPermissaoUsuario->alterar('tMenu1_1_1.php');
            
            //atualize a sessão nome
            $_SESSION['credencial']['idPermissao'] = $idPermissao;
            //fazer consulta para retornar nomenclatura correta
            
            if ($sPermissaoUsuario->mConexao->getValidador()) {
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_1_1&campo=permissao&codigo={$sPermissaoUsuario->getSNotificacao()->getCodigo()}");
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