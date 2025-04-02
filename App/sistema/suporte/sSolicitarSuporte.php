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
    sTratamentoDados
};

use App\sistema\suporte\{
    sProtocolo,
    sEtapa
};

//verifica se tem credencial para acessar o sistema
if (!isset($_SESSION['credencial'])) {
    //solicitar saída com tentativa de violação
    $sSair = new sSair();
    $sSair->verificar('0');
}

if (isset($_POST['formulario'])) {
    //verifica se é para abrir o chamado com os dados do solicitante ou do representante
    $pagina = $_POST['pagina'];
    $acao = $_POST['acao'];
    $idUsuario = $_SESSION['credencial']['idUsuario'];
    $valorCampoAnterior = '';
    isset($_POST['meusDados']) ? $meusDados = $_POST['meusDados'] : $meusDados = false;
    $acessoRemoto = $_POST['acessoRemoto'];
    isset($_POST['local']) ? $idLocal = $_POST['local'] : $idLocal = 1;
    $idPrioridade = $_POST['prioridade'];
    $descricao = $_POST['descricao'];
    isset($_POST['categoria']) ? $categoria = $_POST['categoria'] : $categoria = false;
    $idEquipamento = $_POST['idEquipamento'];
    
    $descricao = mb_convert_encoding($descricao, "ISO8859-1", "HTML-ENTITIES");
    
    //verifica se serão passados os dados do solicitante ou do requerente
    if ($meusDados) {
        $nome = $_SESSION['credencial']['nome'];
        $sobrenome = $_SESSION['credencial']['sobrenome'];
        $telefone = $_SESSION['credencial']['telefoneUsuario'];
        $whatsApp = $_SESSION['credencial']['whatsAppUsuario'];
        $email = $_SESSION['credencial']['emailUsuario'];
        $idSecretaria = $_SESSION['credencial']['idSecretaria'];
        $idDepartamento = $_SESSION['credencial']['idDepartamento'];
        $idCoordenacao = $_SESSION['credencial']['idCoordenacao'];
        $idSetor = $_SESSION['credencial']['idSetor'];
    } else {
        $nome = $_POST['nome'];
        $sobrenome = $_POST['sobrenome'];
        $telefone = $_POST['telefone'];
        isset($_POST['whatsApp']) ? $whatsApp = $_POST['whatsApp'] : $whatsApp = 0;
        $email = $_POST['email'];
        $idSecretaria = $_POST['secretaria'];
        $idDepartamento = $_POST['departamento'];
        $idCoordenacao = $_POST['coordenacao'];
        $idSetor = $_POST['setor'];
    }
    
    //instancia as configurações do sistema
    $sConfiguracao = new sConfiguracao();
    
    //trata os dados para inserção no bd
    $sTratamentoNome = new sTratamentoDados($nome);
    $nomeTratado = $sTratamentoNome->tratarNomenclatura();
    
    //trata os dados para inserção no bd
    $sTratamentoSobreNome = new sTratamentoDados($sobrenome);
    $sobreNomeTratado = $sTratamentoSobreNome->tratarNomenclatura();

    //trata os dados para inserção no bd
    if(!$meusDados){
        $sTratamentoTelefone = new sTratamentoDados($telefone);
        $telefoneTratado = $sTratamentoTelefone->tratarTelefone();
    }else{
        $telefoneTratado = $telefone;
    }    
    
    //trata os dados para inserção no bd
    $sTratamentoEmail = new sTratamentoDados($email);
    if($sTratamentoEmail->tratarEmail()){
        $emailTratado = $email;
    }else{
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_1_1&campo=email&codigo=A2");
        exit();
    }    

    //buscar nomenclatura dos locais
    if ($idSecretaria == 0) {
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_1_1&campo=secretaria&codigo=A17");
        exit();
    }else{
        $sSecretaria = new sSecretaria(0);
        $sSecretaria->setNomeCampo('idsecretaria');
        $sSecretaria->setValorCampo($idSecretaria);
        $sSecretaria->consultar('tMenu2_1.php');
        
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
        $sDepartamento->consultar('tMenu2_1.php');
        
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
        $sCoordenacao->consultar('tMenu2_1.php');
        
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
        $sSetor->consultar('tMenu2_1.php');
        
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
    alimentaHistorico($pagina, $acao, 'acessoRemoto', $valorCampoAnterior, $acessoRemoto, $idUsuario);
    alimentaHistorico($pagina, $acao, 'equipamento_idequipamento', $valorCampoAnterior, $idEquipamento, $idUsuario);
    if($categoria){
        alimentaHistorico($pagina, $acao, 'categoria', $valorCampoAnterior, $categoria, $idUsuario);
    }    
    alimentaHistorico($pagina, $acao, 'prioridade', $valorCampoAnterior, $idPrioridade, $idUsuario);
    alimentaHistorico($pagina, $acao, 'descricao', $valorCampoAnterior, $descricao, $idUsuario);
    alimentaHistorico($pagina, $acao, 'secretaria', $valorCampoAnterior, $secretaria, $idUsuario);
    alimentaHistorico($pagina, $acao, 'departamento', $valorCampoAnterior, $departamento, $idUsuario);
    alimentaHistorico($pagina, $acao, 'coordenacao', $valorCampoAnterior, $coordenacao, $idUsuario);
    alimentaHistorico($pagina, $acao, 'setor', $valorCampoAnterior, $setor, $idUsuario);

    //inserir dados na tabela protocolo
    $dadosProtocolo = [
        'nomeDoRequerente' => $nomeTratado,
        'sobrenomeDoRequerente' => $sobreNomeTratado,
        'telefoneDoRequerente' => $telefoneTratado,
        'whatsAppDoRequerente' => $whatsApp,
        'emailDoRequerente' => $emailTratado,
        'usuario_idusuario' => $idUsuario,
        'secretaria' => $secretaria,
        'departamento' => $departamento,
        'coordenacao' => $coordenacao,
        'setor' => $setor
    ];

    //registra o protocolo
    $sProtocolo = new sProtocolo();
    $sProtocolo->inserir('tMenu2_1.php', $dadosProtocolo);
    $idProtocolo = $sProtocolo->mConexao->getRegistro();

    //consulta dados da etapa para determinar o seu número
    $sEtapa = new sEtapa();
    $sEtapa->setNomeCampo('protocolo_idprotocolo');
    $sEtapa->setValorCampo($idProtocolo);
    $sEtapa->consultar('tMenu2_1.php');

    //verifica se existe um número para seguir a sequência, caso não exista, cria o primeiro
    $numero = 0;
    if ($sEtapa->getValidador()) {
        foreach ($sEtapa->mConexao->getRetorno() as $linha) {
            if ($linha['numero'] > $numero) {
                $numero = $linha['numero'];
            }
        }
    } else {
        $numero++;
    }

    //gerar histórico dos campos da etapa
    alimentaHistorico($pagina, $acao, 'protocolo_idprotocolo', $valorCampoAnterior, $idProtocolo, $idUsuario);
    alimentaHistorico($pagina, $acao, 'local_idlocal', $valorCampoAnterior, $idLocal, $idUsuario);

    //registra os dados na tabela etapa
    $dadosEtapa = [
        'numero' => $numero,
        'acessoRemoto' => $acessoRemoto,
        'descricao' => $descricao,
        'equipamento_idequipamento' => $idEquipamento,
        'protocolo_idprotocolo' => $idProtocolo,
        'local_idlocal' => $idLocal,
        'prioridade_idprioridade' => $idPrioridade
    ];
        
    $sEtapa->inserir('tMenu2_1_1.php', $dadosEtapa);

    //redireciona para o formulário com mensagem de sucesso
    header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_1&campo=sistema&codigo=S4");
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
    $sHistorico->inserir('tMenu4_1.php', $tratarDados);
}
