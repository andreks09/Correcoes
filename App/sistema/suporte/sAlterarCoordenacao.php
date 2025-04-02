<?php
session_start();

require_once '../../../vendor/autoload.php';

use App\sistema\acesso\{
    sSair,
    sConfiguracao,
    sHistorico,
    sTratamentoDados,
    sCoordenacao,
    sEmail,
    sTelefone
};

//verifica se tem credencial para acessar o sistema
if (!isset($_SESSION['credencial'])) {
    //solicitar saída com tentativa de violação
    $sSair = new sSair();
    $sSair->verificar('0');
}

if (isset($_POST['pagina'])) {
    $alteracao = false;
    $idUsuario = $_SESSION['credencial']['idUsuario'];
    $pagina = $_POST['pagina'];
    $acao = $_POST['acao'];
    $idCoordenacao = $_POST['idCoordenacao'];
    $idCoordenacaoCriptografada = base64_encode($idCoordenacao);
    isset($_POST['coordenacao']) ? $coordenacao = $_POST['coordenacao'] : $coordenacao = 0;
    isset($_POST['endereco']) ? $endereco = $_POST['endereco'] : $endereco = 0;
    isset($_POST['email']) ? $email = $_POST['email'] : $email = 0;
    isset($_POST['telefone']) ? $telefone = $_POST['telefone'] : $telefone = 0;
    isset($_POST['whatsApp']) ? $whatsApp = 1 : $whatsApp = 0;
    
    //obter dados anteriores da coordenacao
    $sCoordenacao = new sCoordenacao(0);
    $sCoordenacao->setNomeCampo('idcoordenacao');
    $sCoordenacao->setValorCampo($idCoordenacao);
    $sCoordenacao->consultar($pagina);
    
    foreach ($sCoordenacao->mConexao->getRetorno() as $value) {
        $coordenacaoAnterior = $value['nomenclatura'];
        $enderecoAnterior = $value['endereco'];
    }
    
    //verifica se a coordenacao já possui email
    $sEmail = new sEmail('', '');
    $sEmail->setNomeCampo('coordenacao_idcoordenacao');
    $sEmail->setValorCampo($idCoordenacao);
    $sEmail->consultar($pagina);
    
    //caso tenha email registrado então retorne o id do email
    if($sEmail->getValidador()){
        foreach ($sEmail->mConexao->getRetorno() as $value) {
            $idEmail = $value['email_idemail'];
        }
        
        //consulta os dados do e-mail anterior
        $sEmail->setNomeCampo('idemail');
        $sEmail->setValorCampo($idEmail);
        $sEmail->consultar('tMenu4_2_3_1.php-2');
        
        foreach ($sEmail->mConexao->getRetorno() as $value) {
            $emailAnterior = $value['nomenclatura'];
        }
    }   
    
    //verifica se a coordenacao já possui telefone
    $sTelefone = new sTelefone(0, 0, '');
    $sTelefone->setNomeCampo('coordenacao_idcoordenacao');
    $sTelefone->setValorCampo($idCoordenacao);
    $sTelefone->consultar($pagina);
    
    //caso tenha telefone registrado então retorne o id do telefone
    if($sTelefone->getValidador()){
        foreach ($sTelefone->mConexao->getRetorno() as $value) {
            $idTelefone = $value['telefone_idtelefone'];
        }
        
        //consulta os dados do e-mail anterior
        $sTelefone->setNomeCampo('idtelefone');
        $sTelefone->setValorCampo($idTelefone);
        $sTelefone->consultar('tMenu4_2_3_1.php-2');
        
        foreach ($sTelefone->mConexao->getRetorno() as $value) {
            $telefoneAnterior = $value['numero'];
            $whatsAppAnterior = $value['whatsApp'];
        }
        
        //Insere os caracteres especiais para realizar a comparação
        $sTratamentoTelefone = new sTratamentoDados($telefoneAnterior);
        $telefoneAnterior = $sTratamentoTelefone->tratarTelefone();
    }        
    
    //alimenta histórico
    if($coordenacao != $coordenacaoAnterior){
        alimentaHistorico($pagina, $acao, 'coordenacao', $coordenacaoAnterior, $coordenacao, $idUsuario);
    }    
    if(isset($enderecoAnterior)){
        if($endereco != $enderecoAnterior){
            alimentaHistorico($pagina, $acao, 'endereco', $enderecoAnterior, $endereco, $idUsuario);
        }
    }else{
        if($endereco){
            alimentaHistorico($pagina, $acao, 'endereco', '', $endereco, $idUsuario);
        }
    }
    if(isset($emailAnterior)){
        if($email != $emailAnterior){
            alimentaHistorico($pagina, $acao, 'email', $emailAnterior, $email, $idUsuario);
        }
    }else{
        if($email){
            alimentaHistorico($pagina, $acao, 'email', '', $email, $idUsuario);
        }
    }
    if(isset($telefoneAnterior)){
        if($telefone != $telefoneAnterior){
            alimentaHistorico($pagina, $acao, 'telefone', $telefoneAnterior, $telefone, $idUsuario);
        }
    }else{
        if($telefone){
            alimentaHistorico($pagina, $acao, 'telefone', '', $telefone, $idUsuario);
        }
    }
    if(isset($whatsAppAnterior)){
        if($whatsApp != $whatsAppAnterior){
            alimentaHistorico($pagina, $acao, 'whatsApp', $whatsAppAnterior, $whatsApp, $idUsuario);
        }
    }else{
        if($whatsApp){
            alimentaHistorico($pagina, $acao, 'whatsApp', '', $whatsApp, $idUsuario);
        }
    }
    
    
    //se não for preenchido o campo coordenacao, retorne com mensagem de erro
    if(!$coordenacao){
        $sConfiguracao = new sConfiguracao();
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_2_3_1&pagina=tMenu4_2_3.php&seguranca={$idCoordenacaoCriptografada}&formulario=f1&campo=coordenacao&codigo=A10");
        exit(); 
    }else{
        //altera a nomenclatura da coordenacao        
        if($coordenacao != $coordenacaoAnterior){
            $alteracao = true;
            $sCoordenacao = new sCoordenacao(0);
            $sCoordenacao->setNomeCampo('nomenclatura');
            $sCoordenacao->setValorCampo($coordenacao);
            $sCoordenacao->setIdCoordenacao($idCoordenacao);
            $sCoordenacao->alterar($pagina);
        }
    }
    
    //se não for preenchido o campo endereco, retorne com mensagem de erro
    if(!$endereco){
        $sConfiguracao = new sConfiguracao();
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_2_3_1&pagina=tMenu4_2_3.php&seguranca={$idCoordenacaoCriptografada}&formulario=f1&campo=endereco&codigo=A10");
        exit(); 
    }else{
        //altera o endereco da coordenacao        
        if($endereco != $enderecoAnterior){
            $alteracao = true;
            $sCoordenacao = new sCoordenacao(0);
            $sCoordenacao->setNomeCampo('endereco');
            $sCoordenacao->setValorCampo($endereco);
            $sCoordenacao->setIdCoordenacao($idCoordenacao);
            $sCoordenacao->alterar($pagina);
        }
    }
    
    //se for passado um endereço de email diferente
    if(isset($emailAnterior)){
        if( $email != $emailAnterior){
            //verifica se o email é válido
            $sTratamentoEmail = new sTratamentoDados($email);
            $emailTratado = $sTratamentoEmail->tratarEmail();            

            //se o email for válido, verifica se já não existe um registro 
            if($emailTratado || empty($email)){   
                //se não passou nenhum e-mail então passar "null" para a instrução
                if(empty($email)){
                    $email = 'null';
                }
                
                $sEmail->setNomeCampo('nomenclatura');
                $sEmail->setValorCampo($email);
                $sEmail->consultar('tMenu4_2_3_1.php-2');
                                
                if($sEmail->getValidador()){
                    $sConfiguracao = new sConfiguracao();
                    header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_2_3_1&pagina=tMenu4_2_3.php&seguranca={$idCoordenacaoCriptografada}&formulario=f1&campo=email&codigo=A12");
                    exit();
                }else{
                    if($email == 'null'){
                        $alteracao = true;
                        $dados = [
                            'email_idemail' => $idEmail,
                            'coordenacao_idcoordenacao' => $idCoordenacao
                        ];
                        $sEmail->deletar('tMenu4_2_3_1.php', $dados);
                        
                        $sEmail->setNomeCampo('idemail');
                        $sEmail->setValorCampo($idEmail);
                        $sEmail->deletar('tMenu4_2_3_1.php-2', '');
                    }else{
                        $alteracao = true;
                        $sEmail->setNomeCampo('nomenclatura');
                        $sEmail->setValorCampo($email);
                        $sEmail->setIdEmail($idEmail);
                        $sEmail->alterar('tMenu4_2_3_1.php');
                    }
                }
            }else{
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_2_3_1&pagina=tMenu4_2_3.php&seguranca={$idCoordenacaoCriptografada}&formulario=f1&campo=email&codigo=A2");
                exit(); 
            }
        }
    }else{
        if($email){
            //verifica se já existe um e-mail com essa nomenclatura
            $sEmail->setNomeCampo('nomenclatura');
            $sEmail->setValorCampo($email);
            $sEmail->consultar('tMenu4_2_3_1.php-2');
                
            if($sEmail->getValidador()){
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_2_3_1&pagina=tMenu4_2_3.php&seguranca={$idCoordenacaoCriptografada}&formulario=f1&campo=email&codigo=A12");
                exit();
            }else{
                //caso não tenha e-mail registrado para alteração, registre um novo na tabela email   
                $tratarDados = [
                    'nomenclatura' => $email
                ];
                $sEmail->inserir($pagina, $tratarDados);

                //se obteve o registro do último e-mail inserido, registre-o na tabela email_has_coordenacao
                if($sEmail->mConexao->getRegistro()){
                    //registre também na tabela email_has_coordenacao  
                    $alteracao = true;
                    $sEmail->setNomeCampo('coordenacao');
                    $tratarDados = [
                        'idemail' => $sEmail->mConexao->getRegistro(),
                        'idcoordenacao' => $idCoordenacao
                    ];
                    $sEmail->inserir('tMenu4_2_3_1-email_has_coordenacao.php', $tratarDados);
                }else{
                    //retorne mensagem de erro sem registrar o e-mail
                    $sConfiguracao = new sConfiguracao();
                    header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_2_3_1&pagina=tMenu4_2_3.php&seguranca={$idCoordenacaoCriptografada}&formulario=f1&campo=email&codigo=E5");
                    exit(); 
                }   
            }
        }           
    }
    
    //se tiver telefone registrado
    if(isset($telefoneAnterior)){
        if($telefone != $telefoneAnterior){
            //trata o numero antes de alterar no bd            
            $sTratamentoTelefone = new sTratamentoDados($telefone);
            $telefoneCoordenacaoTratado = $sTratamentoTelefone->tratarTelefone();
            
            $sTelefone = new sTelefone(0, 0, '');
            $sTelefone->verificarTelefone($telefoneCoordenacaoTratado);
            
            if($sTelefone->getValidador() || strlen($telefoneCoordenacaoTratado) == 0){
                $alteracao = true;
                //altera os dados do telefone no bd
                $sTelefone = new sTelefone(0, 0, '');
                $sTelefone->setIdTelefone($idTelefone);
                $sTelefone->setNomeCampo('numero');
                $sTelefone->setValorCampo($telefoneCoordenacaoTratado);
                $sTelefone->alterar('tMenu4_2_3_1.php');
            }else{
                //retorne com mensagem de alerta por não atender aos requisitos
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_2_3_1&pagina=tMenu4_2_3.php&seguranca={$idCoordenacaoCriptografada}&formulario=f1&campo=telefone&codigo=A11");
                exit();
            }
        }
    }else{
        //se não localizar registro de telefone e foi passado um número novo
        if($telefone){
            //verifica o telefone atende aos requisitos
            $sTratamentoTelefone = new sTratamentoDados($telefone);
            $telefoneTratado = $sTratamentoTelefone->tratarTelefone();
            
            $sTelefone = new sTelefone(0, 0, '');
            $sTelefone->verificarTelefone($telefoneTratado);
            
            if($sTelefone->getValidador()){
                //caso sim, trate o número para registro no bd                
                $tratarDados = [
                    'whatsApp' => 0,
                    'numero' => $telefoneTratado
                ];                
                $sTelefone->inserir('tMenu4_2_3_1.php', $tratarDados);
                
                //se o registro foi realizado com sucesso
                if($sTelefone->mConexao->getRegistro()){
                    $idTelefone = $sTelefone->mConexao->getRegistro();                    
                    //registre também na tabela email_has_coordenacao  
                    $alteracao = true;
                    $sTelefone->setNomeCampo('coordenacao');
                    $tratarDados = [
                        'idtelefone' => $sTelefone->mConexao->getRegistro(),
                        'idcoordenacao' => $idCoordenacao
                    ];
                    $sTelefone->inserir('tMenu4_2_3_1-telefone_has_coordenacao.php', $tratarDados);                    
                }else{
                    //não registrou o telefone
                    $sConfiguracao = new sConfiguracao();
                    header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_2_3_1&pagina=tMenu4_2_3.php&seguranca={$idCoordenacaoCriptografada}&formulario=f1&campo=telefone&codigo=E6");
                    exit();
                }   
            }else{
                //retorne com mensagem de alerta por não atender aos requisitos
                $sConfiguracao = new sConfiguracao();
                header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_2_3_1&pagina=tMenu4_2_3.php&seguranca={$idCoordenacaoCriptografada}&formulario=f1&campo=telefone&codigo=A11");
                exit();
            }
        }
    }
    
    if($whatsApp != $whatsAppAnterior){
        $alteracao = true;
        $sTelefone->setIdTelefone($idTelefone);
        $sTelefone->setNomeCampo('whatsApp');
        $sTelefone->setValorCampo($whatsApp);
        $sTelefone->alterar('tMenu4_2_3_1.php');

        //retorne com as alterações realizadas
        $sConfiguracao = new sConfiguracao();
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_2_3_1&pagina=tMenu4_2_3.php&seguranca={$idCoordenacaoCriptografada}&formulario=f1&campo=telefone&codigo=S1");
    }
    
    if($alteracao){
        //retorne com as alterações realizadas
        $sConfiguracao = new sConfiguracao();
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_2_3_1&pagina=tMenu4_2_3.php&seguranca={$idCoordenacaoCriptografada}&formulario=f1&campo=todos&codigo=S1");
        exit();
    }else{
        //retorne com as alterações realizadas
        $sConfiguracao = new sConfiguracao();
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=4_2_3_1&pagina=tMenu4_2_3.php&seguranca={$idCoordenacaoCriptografada}&formulario=f1");
        exit();
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