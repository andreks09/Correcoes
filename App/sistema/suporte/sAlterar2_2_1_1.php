<?php
//inicia as sessões do sistema
session_start();

//carrega os diretórios via autoload
require_once '../../../vendor/autoload.php';

//carrega os arquivos para instanciá-los
use App\sistema\acesso\{
    sSair,
    sHistorico,
    sConfiguracao,
    sSecretaria,
    sDepartamento,
    sCoordenacao,
    sSetor,
    sTratamentoDados,
    sTelefone
};

use App\sistema\suporte\{
    sProtocolo
};

//verifica se tem credencial para acessar o sistema
if (!isset($_SESSION['credencial'])) {
    //solicitar saída com tentativa de violação
    $sSair = new sSair();
    $sSair->verificar('0');
}

if (isset($_POST['formulario'])) {
    //verifica se é para abrir o chamado com os dados do solicitante ou do representante
    $idProtocolo = $_POST['idProtocolo'];
    $pagina = $_POST['pagina'];
    $acao = $_POST['acao'];
    $idUsuario = $_SESSION['credencial']['idUsuario'];
    $valorCampoAnterior = '';
        
    //verifica se serão passados os dados do solicitante ou do requerente
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $telefone = $_POST['telefone'];
    isset($_POST['whatsApp']) ? $whatsApp = $_POST['whatsApp'] : $whatsApp = 0;
    $email = $_POST['email'];
    $idSecretaria = $_POST['secretaria'];
    $idDepartamento = $_POST['departamento'];
    $idCoordenacao = $_POST['coordenacao'];
    $idSetor = $_POST['setor'];
    
    //instancia as configurações do sistema
    $sConfiguracao = new sConfiguracao();
    
    //trata os dados para alteração no bd
    $sTratamentoNome = new sTratamentoDados($nome);
    $nomeTratado = $sTratamentoNome->tratarNomenclatura();
    
    //trata os dados para alteração no bd
    $sTratamentoSobreNome = new sTratamentoDados($sobrenome);
    $sobreNomeTratado = $sTratamentoSobreNome->tratarNomenclatura();

    //trata os dados para alteração no bd
    $sTratamentoTelefone = new sTratamentoDados($telefone);
    $telefoneTratado = $sTratamentoTelefone->tratarTelefone();

    $sTelefone = new sTelefone(0, 0, '');
    $sTelefone->verificarTelefone($telefoneTratado);   

    if(!$sTelefone->getValidador()){
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_2_1_1&campo=telefone&codigo=A11&idProtocolo=$idProtocolo&idProtocolo=$idProtocolo");
        exit();           
    }
    
    //trata os dados para inserção no bd
    $sTratamentoEmail = new sTratamentoDados($email);
    if($sTratamentoEmail->tratarEmail()){
        $emailTratado = $email;
    }else{
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_2_1_1&campo=email&codigo=A2&idProtocolo=$idProtocolo");
        exit();
    }    

    //buscar nomenclatura dos locais
    if ($idSecretaria == 0) {
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_2_1_1&campo=secretaria&codigo=A17&idProtocolo=$idProtocolo");
        exit();
    }else{
        $sSecretaria = new sSecretaria(0);
        $sSecretaria->setNomeCampo('idsecretaria');
        $sSecretaria->setValorCampo($idSecretaria);
        $sSecretaria->consultar('tMenu2_2_1_1.php');
        
        foreach ($sSecretaria->mConexao->getRetorno() as $value) {
            $secretaria = $value['nomenclatura'];
        }
    }

    if ($idDepartamento == 0) {
        $departamento = '--';
    } else {
        $sDepartamento = new sDepartamento(0);
        $sDepartamento->setNomeCampo('iddepartamento');
        $sDepartamento->setValorCampo($idDepartamento);
        $sDepartamento->consultar('tMenu2_2_1_1.php');
        
        foreach ($sDepartamento->mConexao->getRetorno() as $value) {
            $departamento = $value['nomenclatura'];
        }
    }

    if ($idCoordenacao == 0) {
        $coordenacao = '--';
    } else {
        $sCoordenacao = new sCoordenacao(0);
        $sCoordenacao->setNomeCampo('idcoordenacao');
        $sCoordenacao->setValorCampo($idCoordenacao);
        $sCoordenacao->consultar('tMenu2_2_1_1.php');
        
        foreach ($sCoordenacao->mConexao->getRetorno() as $value) {
            $coordenacao = $value['nomenclatura'];
        }
    }

    if ($idSetor == 0) {
        $setor = '--';
    } else {
        $sSetor = new sSetor(0);
        $sSetor->setNomeCampo('idsetor');
        $sSetor->setValorCampo($idSetor);
        $sSetor->consultar('tMenu2_2_1_1.php');
        
        foreach ($sSetor->mConexao->getRetorno() as $value) {
            $setor = $value['nomenclatura'];
        }
    }
    
    //gerar histórico dos campos do solicitante ou requerente
    alimentaHistorico($pagina, $acao, 'nomeDoRequerente', $valorCampoAnterior, $nome, $idUsuario);
    alimentaHistorico($pagina, $acao, 'sobrenomeDoRequerente', $valorCampoAnterior, $sobrenome, $idUsuario);
    alimentaHistorico($pagina, $acao, 'telefoneDoRequerente', $valorCampoAnterior, $telefone, $idUsuario);
    alimentaHistorico($pagina, $acao, 'whatsAppDoRequerente', $valorCampoAnterior, $whatsApp, $idUsuario);
    alimentaHistorico($pagina, $acao, 'emailDoRequerente', $valorCampoAnterior, $email, $idUsuario);
    alimentaHistorico($pagina, $acao, 'secretaria', $valorCampoAnterior, $secretaria, $idUsuario);
    alimentaHistorico($pagina, $acao, 'departamento', $valorCampoAnterior, $departamento, $idUsuario);
    alimentaHistorico($pagina, $acao, 'coordenacao', $valorCampoAnterior, $coordenacao, $idUsuario);
    alimentaHistorico($pagina, $acao, 'setor', $valorCampoAnterior, $setor, $idUsuario);

    //altera secretaria
    $sProtocolo = new sProtocolo();
    $sProtocolo->setIdProtocolo($idProtocolo);
    $sProtocolo->setNomeCampo('secretaria');
    $sProtocolo->setValorCampo($secretaria);
    $sProtocolo->alterar('tMenu2_2_1_1.php');
    
    //altera departamento
    $sProtocolo->setIdProtocolo($idProtocolo);
    $sProtocolo->setNomeCampo('departamento');
    $sProtocolo->setValorCampo($departamento);
    $sProtocolo->alterar('tMenu2_2_1_1.php');
    
    //altera coordenacao
    $sProtocolo->setIdProtocolo($idProtocolo);
    $sProtocolo->setNomeCampo('coordenacao');
    $sProtocolo->setValorCampo($coordenacao);
    $sProtocolo->alterar('tMenu2_2_1_1.php');
    
    //altera setor
    $sProtocolo->setIdProtocolo($idProtocolo);
    $sProtocolo->setNomeCampo('setor');
    $sProtocolo->setValorCampo($setor);
    $sProtocolo->alterar('tMenu2_2_1_1.php');
    
    //altera nome
    $sProtocolo->setIdProtocolo($idProtocolo);
    $sProtocolo->setNomeCampo('nomeDoRequerente');
    $sProtocolo->setValorCampo($nome);
    $sProtocolo->alterar('tMenu2_2_1_1.php');
    
    //altera sobrenome
    $sProtocolo->setIdProtocolo($idProtocolo);
    $sProtocolo->setNomeCampo('sobrenomeDoRequerente');
    $sProtocolo->setValorCampo($sobrenome);
    $sProtocolo->alterar('tMenu2_2_1_1.php');
    
    //altera telefone
    $sProtocolo->setIdProtocolo($idProtocolo);
    $sProtocolo->setNomeCampo('telefoneDoRequerente');
    $sProtocolo->setValorCampo($telefoneTratado);
    $sProtocolo->alterar('tMenu2_2_1_1.php');
    
    //altera nome
    $sProtocolo->setIdProtocolo($idProtocolo);
    $sProtocolo->setNomeCampo('whatsAppDoRequerente');
    $sProtocolo->setValorCampo($whatsApp);
    $sProtocolo->alterar('tMenu2_2_1_1.php');
    
    //altera nome
    $sProtocolo->setIdProtocolo($idProtocolo);
    $sProtocolo->setNomeCampo('emailDoRequerente');
    $sProtocolo->setValorCampo($emailTratado);
    $sProtocolo->alterar('tMenu2_2_1_1.php');

    
    //gerar histórico dos campos da etapa
    alimentaHistorico($pagina, $acao, 'protocolo_idprotocolo', $valorCampoAnterior, $idProtocolo, $idUsuario);

    //redireciona para o formulário com mensagem de sucesso
    header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_2_1_1&idProtocolo=$idProtocolo&campo=sistema&codigo=S4");
    exit();
} else {
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
    $sHistorico->inserir('tMenu2_2_1_1.php', $tratarDados);
}

