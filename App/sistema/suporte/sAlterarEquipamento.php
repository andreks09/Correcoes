<?php
session_start();

require_once '../../../vendor/autoload.php';

use App\sistema\acesso\{
    sSair,
    sConfiguracao,
    sHistorico,
    sTratamentoDados
};

use App\sistema\suporte\{
    sProtocolo,
    sEquipamento,
    sModelo
};

//verifica se tem credencial para acessar o sistema
if (!isset($_SESSION['credencial'])) {
    //solicitar saída com tentativa de violação
    $sSair = new sSair();
    $sSair->verificar('0');
}

if (isset($_POST['pagina'])) {
    $idUsuario          = $_SESSION['credencial']['idUsuario'];
    $pagina             = $_POST['pagina'];
    $acao               = $_POST['acao'];
    $idEquipamento      = $_POST['idEquipamento'];
    $categoria          = $_POST['categoria'];
    $marca              = $_POST['marcaF1'];
    $modelo             = $_POST['modeloF1'];
    $etiqueta           = $_POST['etiqueta'];
    $serie              = $_POST['serie'];
    $tensao             = $_POST['tensao'];
    $corrente           = $_POST['corrente'];
    $sistemaOperacional = $_POST['sistemaOperacional'];
    $ambiente           = $_POST['ambiente'];
    
    //obtém dados anteriores do equipamento
    $sEquipamento = new sEquipamento(0);
    $sEquipamento->setNomeCampo('idequipamento');
    $sEquipamento->setValorCampo($idEquipamento);
    $sEquipamento->consultar($pagina.'-2');
    
    foreach ($sEquipamento->mConexao->getRetorno() as $value) {
        $categoriaAnterior = $value['categoria_idcategoria'];
        $tensaoAnterior = $value['tensao_idtensao'];
        $correnteAnterior = $value['corrente_idcorrente'];
        $sistemaOperacionalAnterior = $value['sistemaOperacional_idsistemaOperacional'];
        $numeroDeSerieAnterior = $value['numeroDeSerie'];
        $etiquetaDeServicoAnterior = $value['etiquetaDeServico'];
        $modeloAnterior = $value['modelo_idmodelo'];
        $ambienteAnterior = $value['ambiente_idambiente'];
    }
    
    //Obtém os dados da marca do equipamento
    $sModelo = new sModelo();
    $sModelo->setNomeCampo('idmodelo');
    $sModelo->setValorCampo($modeloAnterior);
    $sModelo->consultar($pagina);
    
    foreach ($sModelo->mConexao->getRetorno() as $value) {
        $marcaAnterior = $value['marca_idmarca'];
    }
        
    //alimenta histórico
    $alterar = [];
    if($categoria != $categoriaAnterior){
        array_push($alterar, 'categoria');
        alimentaHistorico($pagina, $acao, 'categoria', $categoriaAnterior, $categoria, $idUsuario);
    } 
    if($marca != $marcaAnterior){
        alimentaHistorico($pagina, $acao, 'marca', $marcaAnterior, $marca, $idUsuario);
    }     
    if($tensao != $tensaoAnterior){
        array_push($alterar, 'tensao');
        alimentaHistorico($pagina, $acao, 'tensao', $tensaoAnterior, $tensao, $idUsuario);
    } 
    if($corrente != $correnteAnterior){
        array_push($alterar, 'corrente');
        alimentaHistorico($pagina, $acao, 'corrente', $correnteAnterior, $corrente, $idUsuario);
    } 
    if($sistemaOperacional != $sistemaOperacionalAnterior){
        array_push($alterar, 'sistemaOperacional');
        alimentaHistorico($pagina, $acao, 'sistemaOperacional', $sistemaOperacionalAnterior, $sistemaOperacional, $idUsuario);
    } 
    if($serie != $numeroDeSerieAnterior){
        array_push($alterar, 'serie');
        alimentaHistorico($pagina, $acao, 'numeroDeSerie', $numeroDeSerieAnterior, $serie, $idUsuario);
    } 
    if($etiqueta != $etiquetaDeServicoAnterior){
        array_push($alterar, 'etiqueta');
        alimentaHistorico($pagina, $acao, 'etiquetaDeServico', $etiquetaDeServicoAnterior, $etiqueta, $idUsuario);
    } 
    if($modelo != $modeloAnterior){
        array_push($alterar, 'modelo');
        alimentaHistorico($pagina, $acao, 'modelo', $modeloAnterior, $modelo, $idUsuario);
    } 
    if($ambiente != $ambienteAnterior){
        array_push($alterar, 'ambiente');
        alimentaHistorico($pagina, $acao, 'ambiente', $ambienteAnterior, $ambiente, $idUsuario);
    }
    
    if(!empty($alterar)){
        if (in_array('categoria', $alterar)) {
            $sEquipamento->setIdEquipamento($idEquipamento);
            $sEquipamento->setNomeCampo('categoria_idcategoria');
            $sEquipamento->setValorCampo($categoria);
            $sEquipamento->alterar($pagina);
        }
        
        if (in_array('modelo', $alterar)) {
            $sEquipamento->setIdEquipamento($idEquipamento);
            $sEquipamento->setNomeCampo('modelo_idmodelo');
            $sEquipamento->setValorCampo($modelo);
            $sEquipamento->alterar($pagina);
        }
        
        if (in_array('etiqueta', $alterar)) {  
            $sEquipamento->setIdEquipamento($idEquipamento);
            $sEquipamento->setNomeCampo('etiquetaDeServico');
            $sEquipamento->setValorCampo($etiqueta);
            $sEquipamento->alterar($pagina);
        }
        
        if (in_array('serie', $alterar)) {  
            $sEquipamento->setIdEquipamento($idEquipamento);
            $sEquipamento->setNomeCampo('numeroDeSerie');
            $sEquipamento->setValorCampo($serie);
            $sEquipamento->alterar($pagina);
        }
        
        if (in_array('serie', $alterar)) {  
            $sEquipamento->setIdEquipamento($idEquipamento);
            $sEquipamento->setNomeCampo('numeroDeSerie');
            $sEquipamento->setValorCampo($serie);
            $sEquipamento->alterar($pagina);
        }
        
        if (in_array('tensao', $alterar)) {  
            $sEquipamento->setIdEquipamento($idEquipamento);
            $sEquipamento->setNomeCampo('tensao_idtensao');
            $sEquipamento->setValorCampo($tensao);
            $sEquipamento->alterar($pagina);
        }
        
        if (in_array('corrente', $alterar)) {  
            $sEquipamento->setIdEquipamento($idEquipamento);
            $sEquipamento->setNomeCampo('corrente_idcorrente');
            $sEquipamento->setValorCampo($corrente);
            $sEquipamento->alterar($pagina);
        }
        
        if (in_array('sistemaOperacional', $alterar)) {  
            $sEquipamento->setIdEquipamento($idEquipamento);
            $sEquipamento->setNomeCampo('sistemaOperacional_idsistemaOperacional');
            $sEquipamento->setValorCampo($sistemaOperacional);
            $sEquipamento->alterar($pagina);
        }
        
        if (in_array('ambiente', $alterar)) {  
            $sEquipamento->setIdEquipamento($idEquipamento);
            $sEquipamento->setNomeCampo('ambiente_idambiente');
            $sEquipamento->setValorCampo($ambiente);
            $sEquipamento->alterar($pagina);
        }
        
        if ($sEquipamento->mConexao->getValidador()) {
            $idEquipamentoCriptografada = base64_encode($idEquipamento);
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=3_2_1&campo=todos&codigo=S1&seguranca=$idEquipamentoCriptografada");
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
    $sHistorico->inserir($pagina, $tratarDados);
}
?>