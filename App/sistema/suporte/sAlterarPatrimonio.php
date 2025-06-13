<?php
session_start();

require_once '../../../vendor/autoload.php';

use App\sistema\acesso\{
    sSair,
    sHistorico,
    sConfiguracao,
};

use App\sistema\suporte\{
    sEquipamento,
    sEtapa
};

//verifica se tem credencial para acessar o sistema
if (!isset($_SESSION['credencial'])) {
    //solicitar saída com tentativa de violação
    $sSair = new sSair();
    $sSair->verificar('0');
}

if(isset($_POST['pagina'])){
    if($_POST['pagina'] == 'tMenu2_2_1_2.php'){
        $pagina                 = 'tMenu2_2_1_2.php-f2';
        $acao                   = $_POST['acao'];
        $idProtocolo            = $_POST['idProtocolo'];
        isset($_POST['idEquipamento']) ? $idEquipamento = $_POST['idEquipamento'] : $idEquipamento = '';
        $idEquipamentoAnterior  = $_POST['idEquipamentoAnterior'];
        $idUsuario              = $_SESSION['credencial']['idUsuario'];
        isset($_POST['patrimonio']) ? $patrimonio = true : $patrimonio = false;
        $patrimonioAnterior     = $_POST['patrimonioAnterior'];
        
        if(!$patrimonio){
            $patrimonio = 'Indefinido';
            $sEquipamento       = new sEquipamento();
            $sEquipamento->setNomeCampo('patrimonio');
            $sEquipamento->setValorCampo('Indefinido');
            $sEquipamento->consultar($pagina);

            if($sEquipamento->getValidador()){
                foreach ($sEquipamento->mConexao->getRetorno() as $valueEquipamento) {
                    $idEquipamento = $valueEquipamento['idequipamento'];
                }
            }
            alimentaHistorico($pagina, $acao, 'patrimonio', $patrimonioAnterior, $patrimonio, $idUsuario);
            alimentaHistorico($pagina, $acao, 'equipamento_idequipamento', $idEquipamentoAnterior, $idEquipamento, $idUsuario);            
        }
        
        if( $patrimonio &&
            empty($idEquipamento)){            
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_2_1_2&idProtocolo={$idProtocolo}&idEquipamentoAnterior={$idEquipamentoAnterior}&campo=equipamento&patrimonioAnterior={$patrimonioAnterior}&codigo=A17");
            exit();           
        }
        
        if( $patrimonio &&
            !empty($idEquipamento)){
            $sEquipamento       = new sEquipamento();
            $sEquipamento->setNomeCampo('idequipamento');
            $sEquipamento->setValorCampo($idEquipamento);
            $sEquipamento->consultar($pagina);

            if($sEquipamento->getValidador()){
                foreach ($sEquipamento->mConexao->getRetorno() as $valueEquipamento) {
                    $patrimonio = $valueEquipamento['patrimonio'];
                }
            }
            alimentaHistorico($pagina, $acao, 'patrimonio', $patrimonioAnterior, $patrimonio, $idUsuario);
            alimentaHistorico($pagina, $acao, 'equipamento_idequipamento', $idEquipamentoAnterior, $idEquipamento, $idUsuario);       
        }
                
        //caso passe pela validação, realizar alteração no BD
        $sEtapa = new sEtapa();
        $sEtapa->setNomeCampo('protocolo_idprotocolo');
        $sEtapa->setValorCampo($idProtocolo);
        $sEtapa->consultar($pagina);
        
        if($sEtapa->getValidador()){
            $i = 0;
            foreach ($sEtapa->mConexao->getRetorno() as $valueEtapa) {                
                if($i > 0){
                    $ticketAtribuido = true;
                }
                $idEtapa = $valueEtapa['idetapa'];
                $i++;
            }
        }
        
        if( isset($ticketAtribuido) &&
            $_SESSION['credencial']['nivelPermissao'] < 2){
            $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_2_1_2&idProtocolo={$idProtocolo}&idEquipamentoAnterior={$idEquipamentoAnterior}&patrimonioAnterior={$patrimonioAnterior}&campo=equipamento&codigo=A34");
            exit(); 
        }
        
        //passa os dados há serem atualizados
        $sEtapa->setIdEtapa($idEtapa);
        $sEtapa->setNomeCampo('equipamento_idequipamento');
        $sEtapa->setValorCampo($idEquipamento);
        $sEtapa->alterar($pagina);
        
        //se atualizou redireciona com mensagem de sucesso
        if($sEtapa->getValidador()){
             $sConfiguracao = new sConfiguracao();
            header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_2&campo=alterar&codigo=S1");
            exit(); 
        }
        
        
    }else{
        //solicitar saída com tentativa de violação
        $sSair = new sSair();
        $sSair->verificar('0');
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
    $sHistorico->inserir($pagina, $tratarDados);
}

