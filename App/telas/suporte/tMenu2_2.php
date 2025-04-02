<?php

use App\sistema\acesso\{
    sTratamentoDados,
    sUsuario,
    sConfiguracao,
    sNotificacao,
    sEmail
};
use App\sistema\suporte\{
    sProtocolo,
    sEtapa,
    sCategoria,
    sEquipamento,
    sMarca,
    sModelo,
    sAmbiente,
    sLocal,
    sPrioridade
};

//consulta os dados para apresentar na tabela
$sProtocolo = new sProtocolo();
$sProtocolo->setNomeCampo('dataHoraEncerramento');
$sProtocolo->setValorCampo('null');
//$sProtocolo->setValorCampo('IS NULL');
$sProtocolo->consultar('tMenu2_2.php');

//retorno de campo inválidos para notificação
if(isset($_GET['campo'])){
    $sNotificacao = new sNotificacao($_GET['codigo']);
    switch ($_GET['campo']) {
        case 'atribuir':
            if($_GET['codigo'] == 'S6'){
                $alertaAtribuir = ' is-valid';
            }else{
                $alertaAtribuir = ' is-warning';
            }
        case 'alterar':
            if($_GET['codigo'] == 'S1'){
                $alertaAlterar = ' is-valid';
            }else{
                $alertaAlterar = ' is-warning';
            }
        case 'encerrar':
            if($_GET['codigo'] == 'S5'){
                $alertaAlterar = ' is-valid';
            }else{
                $alertaAlterar = ' is-warning';
            }
    }
    
    //cria as variáveis da notificação
    $tipo = $sNotificacao->getTipo();
    $titulo = $sNotificacao->getTitulo();
    $mensagem = $sNotificacao->getMensagem();
}
?>
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Etapa 1 - Acompanhar Suporte</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">        
        <!--
        <div class="col-md-4">
            <div class="form-group">
                <label>Mostrar suportes encerrados</label>
                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                    <input class="custom-control-input" type="checkbox" name="suportesEncerrados" id="suportesEncerrados" onclick="decisao();">
                    <label class="custom-control-label" for="suportesEncerrados">
                        <div class="conteudo" name="conteudo" id="conteudo"> Não</div>
                    </label>
                </div>
            </div>
        </div>
        -->
        <?php
        if(isset($tipo) && isset($titulo) && isset($mensagem)){
        echo <<<HTML
        <div class="col-mb-3">
            <div class="card card-outline card-{$tipo}">
                <div class="card-header">
                    <h3 class="card-title">{$titulo}</h3>
                </div>
                <div class="card-body">
                    {$mensagem}
                </div>
            </div>
        </div>
HTML;
        }       
        ?>
        
        <table name="tabelaMenu2_2" id="tabelaMenu2_2" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Protocolo n.º</th>
                    <th>Abertura</th>
                    <th>Encerramento</th>
                    <?php 
                    if($_SESSION['credencial']['nivelPermissao'] > 1){
                        echo <<<HTML
                        <th>Solicitante</th>
                        <th>Telefone</th>
HTML;
                    }
                    ?>
                    <th>Patrimônio</th>
                    <th>Categoria/ Marca/ Modelo</th>
                    <th>Descrição</th>
                    <th>
                        Secretaria
                    <?php
                    if ($_SESSION['credencial']['nivelPermissao'] > 1) {
                        echo <<<HTML
                    /<br />
                    Ambiente
HTML;
                    }
                    ?>
                    </th> 
                    <th>Local</th>
                    <?php
                    if ($_SESSION['credencial']['nivelPermissao'] > 1) {
                    echo <<<HTML
                    <th>Prioridade</th>
HTML;
                    }
                    ?>
                    <th>Responsável</th>
                    <th>Atribuir/ Visualizar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($sProtocolo->getValidador()) {           
                    foreach ($sProtocolo->mConexao->getRetorno() as $key => $value) {                        
                        //armazena o id do solicitante para criptografia
                        $seguranca = base64_encode($value['usuario_idusuario']);
                        
                        //campo secretaria
                        $secretaria = $value['secretaria'];
                        
                        //se não tiver permissão visualiza somente os chamados abertos pelo próprio usuário
                        if ($_SESSION['credencial']['nivelPermissao'] < 2 &&
                            $_SESSION['credencial']['idUsuario'] == $value['usuario_idusuario']) {
                            
                            //tratar os dados para imprimir na tabela dinâmica
                            //campo data e hora da abertura
                            $sTratamentoDataAbertura = new sTratamentoDados($value['dataHoraAbertura']);
                            $dataAberturaTratada = $sTratamentoDataAbertura->tratarData();
                            
                            //campo data e hora da abertura
                            if(!is_null($value['dataHoraEncerramento'])){
                                $sTratamentoDataEncerramento = new sTratamentoDados($value['dataHoraEncerramento']);
                                $dataEncerramentoTratada = $sTratamentoDataEncerramento->tratarData();
                            }else{
                                $dataEncerramentoTratada = '--/--/---- --:--:--';
                            }

                            //campo protocolo
                            $ano = date("Y", strtotime(str_replace('-', '/', $value['dataHoraAbertura'])));
                            $protocolo = str_pad($value['idprotocolo'], 5, 0, STR_PAD_LEFT);
                            $protocolo = $ano . $protocolo;

                            //campo do requerente ou solicitante
                            $nomeRequerente = $value['nomeDoRequerente'] . ' ' . $value['sobrenomeDoRequerente'];
                            $sUsuario = new sUsuario();
                            $sUsuario->setNomeCampo('idusuario');
                            $sUsuario->setValorCampo($value['usuario_idusuario']);
                            $sUsuario->consultar('tMenu2_2.php');
                            
                            foreach ($sUsuario->mConexao->getRetorno() as $dadosUsuario) {
                                $nomeSolicitante = $dadosUsuario['nome']. ' ' . $dadosUsuario['sobrenome'];
                                $idEmail = $dadosUsuario['email_idemail'];
                            }

                            //dados do email
                            $sEmail = new sEmail('', '');
                            $sEmail->setNomeCampo('idemail');
                            $sEmail->setValorCampo($idEmail);
                            $sEmail->consultar('tMenu2_2.php');

                            foreach ($sEmail->mConexao->getRetorno() as $dadosEmail) {
                                $email = $dadosEmail['nomenclatura'];
                            }

                            $email == $value['emailDoRequerente'] ? $requerente = false : $requerente = true;
                            
                            $requerente ? $nome = $nomeRequerente . '<br />por <i>' . $nomeSolicitante . '</i>' : $nome = $nomeSolicitante;
                            
                            //campo telefone
                            $sTratamentoTelefone = new sTratamentoDados($value['telefoneDoRequerente']);
                            $telefoneTratado = $sTratamentoTelefone->tratarTelefone();

                            //campos da tabela etapa
                            //se não tiver etapa para um protocolo não será impressa gerará um erro
                            //verifique se há algum protocolo sem etapa vinculada
                            $idProtocolo = $value['idprotocolo'];
                            $sEtapa = new sEtapa();
                            $sEtapa->setNomeCampo('protocolo_idprotocolo');
                            $sEtapa->setValorCampo($idProtocolo);
                            $sEtapa->consultar('tMenu2_2.php');

                            foreach ($sEtapa->mConexao->getRetorno() as $key => $value) {
                                $idEquipamento = $value['equipamento_idequipamento'];
                                $descricao = $value['descricao'];
                                $idLocal = $value['local_idlocal'];
                                $idPrioridade = $value['prioridade_idprioridade'];
                                $numero = $value['numero'];
                                $idResponsavel = $value['usuario_idusuario'];
                            }

                            //dados do equipamento                        
                            $sEquipamento = new sEquipamento();
                            $sEquipamento->setNomeCampo('idequipamento');
                            $sEquipamento->setValorCampo($idEquipamento);
                            $sEquipamento->consultar('tMenu2_2.php');

                            foreach ($sEquipamento->mConexao->getRetorno() as $key => $value) {
                                $patrimonio = $value['patrimonio'];
                                $idCategoria = $value['categoria_idcategoria'];
                                $idModelo = $value['modelo_idmodelo'];
                                $idAmbiente = $value['ambiente_idambiente'];
                            }

                            //campo categoria
                            $sCategoria = new sCategoria();
                            $sCategoria->setNomeCampo('idcategoria');
                            $sCategoria->setValorCampo($idCategoria);
                            $sCategoria->consultar('tMenu2_2.php');

                            foreach ($sCategoria->mConexao->getRetorno() as $key => $value) {
                                $categoria = $value['nomenclatura'];
                            }

                            //campo modelo
                            $sModelo = new sModelo();
                            $sModelo->setNomeCampo('idmodelo');
                            $sModelo->setValorCampo($idModelo);
                            $sModelo->consultar('tMenu2_2.php');

                            foreach ($sModelo->mConexao->getRetorno() as $key => $value) {
                                $modelo = $value['nomenclatura'];
                                $idMarca = $value['marca_idmarca'];
                            }

                            //campo marca
                            $sMarca = new sMarca();
                            $sMarca->setNomeCampo('idmarca');
                            $sMarca->setValorCampo($idMarca);
                            $sMarca->consultar('tMenu2_2.php');

                            foreach ($sMarca->mConexao->getRetorno() as $key => $value) {
                                $marca = $value['nomenclatura'];
                            }
                            
                            //campo ambiente
                            $sAmbiente = new sAmbiente();
                            $sAmbiente->setNomeCampo('idambiente');
                            $sAmbiente->setValorCampo($idAmbiente);
                            $sAmbiente->consultar('tMenu2_2.php');

                            foreach ($sAmbiente->mConexao->getRetorno() as $key => $value) {
                                $ambiente = $value['nomenclatura'];
                            }

                            //campo local
                            $sLocal = new sLocal();
                            $sLocal->setNomeCampo('idlocal');
                            $sLocal->setValorCampo($idLocal);
                            $sLocal->consultar('tMenu2_2.php');

                            foreach ($sLocal->mConexao->getRetorno() as $key => $value) {
                                $local = $value['nomenclatura'];
                            }

                            //campo prioridade
                            $sPrioridade = new sPrioridade();
                            $sPrioridade->setNomeCampo('idprioridade');
                            $sPrioridade->setValorCampo($idPrioridade);
                            $sPrioridade->consultar('tMenu2_2.php');

                            foreach ($sPrioridade->mConexao->getRetorno() as $key => $value) {
                                $prioridade = $value['nomenclatura'];
                            }
                            
                            //obter dados do responsável pelo suporte
                            if($idResponsavel){
                                $sResponsavel = new sUsuario();
                                $sResponsavel->setNomeCampo('idusuario');
                                $sResponsavel->setValorCampo($idResponsavel);
                                $sResponsavel->consultar('tMenu2_2.php');                            
                            
                                foreach ($sResponsavel->mConexao->getRetorno() as $value) {
                                    $nomeResponsavel = $value['nome'];
                                    $sobrenomeResponsavel = $value['sobrenome'];
                                    $responsavel = $nomeResponsavel.' '.$sobrenomeResponsavel;
                                }
                            }else{
                                $responsavel = '--';
                            }

                            //instancia as configurações do sistema
                            $sConfiguracao = new sConfiguracao();
                            $diretorio = $sConfiguracao->getDiretorioVisualizacaoAcesso();

                            //altera a cor das marcações da prioridade
                            $sTratamentoPrioridade = new sTratamentoDados($prioridade);
                            $dadosPrioridade = $sTratamentoPrioridade->corPrioridade();
                            $posicao = $dadosPrioridade[0];
                            $cor = $dadosPrioridade[1];
                            
                            //cria uma hash para o id do protocolo
                            $idProtocoloCriptografado = base64_encode($idProtocolo);
                            
                            //caso o suporte tenha sido encerrado
                            if($dataEncerramentoTratada == '--/--/---- --:--:--'){
                                $ocultarLinha = '';
                            }else{
                                $ocultarLinha = ' id="ocultar'.$i.'" style="display: none;"';
                            }
                            
                            //verifica se já possui responsável agregado
                            if($numero < 2){
                                $atribuirTicket = '<i class="fas fa-business-time"></i> Aguardando<br />';
                            }else{
                                $atribuirTicket = '<i class="fas fa-hands-helping mr-1"></i> Em Andamento<br />';
                            }
                            
                            
                            
                            echo <<<HTML
                        <tr $ocultarLinha>
                            <td>{$protocolo}</td>
                            <td>{$dataAberturaTratada}</td>
                            <td>{$dataEncerramentoTratada}</td>
                            <td>{$patrimonio}</td>
                            <td>
                                {$categoria}<br />
                                {$marca}<br />
                                {$modelo}<br />
                            </td>
                            <td>{$descricao}</td>
                            <td>
                                {$secretaria}<br />
HTML;
                            
                            if ($_SESSION['credencial']['nivelPermissao'] > 1) {
                                echo <<<HTML
                                {$ambiente}                                                        
HTML;
                            }
                            echo <<<HTML
                            </td>
                            <td>{$local}</td>
HTML;
                            if ($_SESSION['credencial']['nivelPermissao'] > 1) {
                                echo <<<HTML
                            <td>
                                <i class="nav-icon fas fa-flag text-{$cor}"></i> {$posicao} - {$prioridade}
                            </td>
HTML;
                            }
                            echo <<<HTML
                            <td>{$responsavel}</td>
                            <td>
                                <i class="fas fa-search mr-1"></i>
                                <a href="{$diretorio}tPainel.php?menu=2_2_1&protocolo={$idProtocoloCriptografado}&seguranca={$seguranca}">
                                    Visualizar
                                </a><br />
                                {$atribuirTicket}
                            </td>
                        </tr>
HTML;
//-----------------------------------------------tabela com nivel de permissão superior a 1-----------------------------------------------//
                        } else if($_SESSION['credencial']['nivelPermissao'] > 1) {
                            //tratar os dados para imprimir na tabela dinâmica
                            //campo data e hora da abertura
                            $sTratamentoDataAbertura = new sTratamentoDados($value['dataHoraAbertura']);
                            $dataAberturaTratada = $sTratamentoDataAbertura->tratarData();
                            
                            //campo data e hora da abertura
                            if(!is_null($value['dataHoraEncerramento'])){
                                $sTratamentoDataEncerramento = new sTratamentoDados($value['dataHoraEncerramento']);
                                $dataEncerramentoTratada = $sTratamentoDataEncerramento->tratarData();
                            }else{
                                $dataEncerramentoTratada = '--/--/---- --:--:--';
                            }                            

                            //campo protocolo
                            $ano = date("Y", strtotime(str_replace('-', '/', $value['dataHoraAbertura'])));
                            $protocolo = str_pad($value['idprotocolo'], 5, 0, STR_PAD_LEFT);
                            $protocolo = $ano . $protocolo;

                            //campo do requerente ou solicitante
                            $nomeRequerente = $value['nomeDoRequerente'] . ' ' . $value['sobrenomeDoRequerente'];
                            $sUsuario = new sUsuario();
                            $sUsuario->setNomeCampo('idusuario');
                            $sUsuario->setValorCampo($value['usuario_idusuario']);
                            $sUsuario->consultar('tMenu2_2.php');
                            
                            foreach ($sUsuario->mConexao->getRetorno() as $dadosUsuario) {
                                $nomeSolicitante = $dadosUsuario['nome']. ' ' . $dadosUsuario['sobrenome'];
                                $idEmail = $dadosUsuario['email_idemail'];
                            }

                            //dados do email
                            $sEmail = new sEmail('', '');
                            $sEmail->setNomeCampo('idemail');
                            $sEmail->setValorCampo($idEmail);
                            $sEmail->consultar('tMenu2_2.php');

                            foreach ($sEmail->mConexao->getRetorno() as $dadosEmail) {
                                $email = $dadosEmail['nomenclatura'];
                            }

                            $email == $value['emailDoRequerente'] ? $requerente = false : $requerente = true;
                            
                            $requerente ? $nome = $nomeRequerente . '<br />por <i>' . $nomeSolicitante . '</i>' : $nome = $nomeSolicitante;

                            //campo telefone
                            $sTratamentoTelefone = new sTratamentoDados($value['telefoneDoRequerente']);
                            $telefoneTratado = $sTratamentoTelefone->tratarTelefone();

                            //campos da tabela etapa
                            /*ATENÇÃO-------------------------------------//
                            * se não tiver etapa para um protocolo, não será impressa e gerará o seguinte erro:
                            * Fatal error: Uncaught Error: Typed property App\modelo\mConexao::$retorno must 
                            * not be accessed before initialization in /home/itapoaap/public_html/App/modelo/mConexao.php:476
                            * Stack trace: #0 /home/itapoaap/public_html/App/telas/suporte/tMenu2_2.php(385):
                            * App\modelo\mConexao->getRetorno() #1 /home/itapoaap/public_html/App/telas/acesso/tPainel.php(625):
                            * require_once('/home/itapoaap/...') #2 {main} thrown in /home/itapoaap/public_html/App/modelo/mConexao.php on line 476
                            * verifique se há algum protocolo sem etapa vinculada
                            * 
                            */
                            $idProtocolo = $value['idprotocolo'];
                            $sEtapa = new sEtapa();
                            $sEtapa->setNomeCampo('protocolo_idprotocolo');
                            $sEtapa->setValorCampo($idProtocolo);
                            $sEtapa->consultar('tMenu2_2.php');
                            
                            foreach ($sEtapa->mConexao->getRetorno() as $key => $value) {
                                $idEquipamento = $value['equipamento_idequipamento'];
                                $descricao = $value['descricao'];
                                $idLocal = $value['local_idlocal'];
                                $idPrioridade = $value['prioridade_idprioridade'];
                                $numero = $value['numero'];
                                $idResponsavel = $value['usuario_idusuario'];
                            }
                            
                            //cria uma hash para o id do protocolo
                            $idProtocoloCriptografado = base64_encode($idProtocolo);


                            //dados do equipamento                        
                            $sEquipamento = new sEquipamento();
                            $sEquipamento->setNomeCampo('idequipamento');
                            $sEquipamento->setValorCampo($idEquipamento);
                            $sEquipamento->consultar('tMenu2_2.php');

                            foreach ($sEquipamento->mConexao->getRetorno() as $key => $value) {
                                $patrimonio = $value['patrimonio'];
                                $idCategoria = $value['categoria_idcategoria'];
                                $idModelo = $value['modelo_idmodelo'];
                                $idAmbiente = $value['ambiente_idambiente'];
                            }

                            //campo categoria
                            $sCategoria = new sCategoria();
                            $sCategoria->setNomeCampo('idcategoria');
                            $sCategoria->setValorCampo($idCategoria);
                            $sCategoria->consultar('tMenu2_2.php');

                            foreach ($sCategoria->mConexao->getRetorno() as $key => $value) {
                                $categoria = $value['nomenclatura'];
                            }

                            //campo modelo
                            $sModelo = new sModelo();
                            $sModelo->setNomeCampo('idmodelo');
                            $sModelo->setValorCampo($idModelo);
                            $sModelo->consultar('tMenu2_2.php');

                            foreach ($sModelo->mConexao->getRetorno() as $key => $value) {
                                $modelo = $value['nomenclatura'];
                                $idMarca = $value['marca_idmarca'];
                            }

                            //campo marca
                            $sMarca = new sMarca();
                            $sMarca->setNomeCampo('idmarca');
                            $sMarca->setValorCampo($idMarca);
                            $sMarca->consultar('tMenu2_2.php');

                            foreach ($sMarca->mConexao->getRetorno() as $key => $value) {
                                $marca = $value['nomenclatura'];
                            }
                            
                            //campo ambiente
                            $sAmbiente = new sAmbiente();
                            $sAmbiente->setNomeCampo('idambiente');
                            $sAmbiente->setValorCampo($idAmbiente);
                            $sAmbiente->consultar('tMenu2_2.php');

                            foreach ($sAmbiente->mConexao->getRetorno() as $key => $value) {
                                $ambiente = $value['nomenclatura'];
                            }

                            //campo local
                            $sLocal = new sLocal();
                            $sLocal->setNomeCampo('idlocal');
                            $sLocal->setValorCampo($idLocal);
                            $sLocal->consultar('tMenu2_2.php');

                            foreach ($sLocal->mConexao->getRetorno() as $key => $value) {
                                $local = $value['nomenclatura'];
                            }

                            //campo prioridade
                            $sPrioridade = new sPrioridade();
                            $sPrioridade->setNomeCampo('idprioridade');
                            $sPrioridade->setValorCampo($idPrioridade);
                            $sPrioridade->consultar('tMenu2_2.php');

                            foreach ($sPrioridade->mConexao->getRetorno() as $key => $value) {
                                $prioridade = $value['nomenclatura'];
                            }
                            
                            //obter dados do responsável pelo suporte
                            if($idResponsavel){
                                $sResponsavel = new sUsuario();
                                $sResponsavel->setNomeCampo('idusuario');
                                $sResponsavel->setValorCampo($idResponsavel);
                                $sResponsavel->consultar('tMenu2_2.php');                            
                            
                                foreach ($sResponsavel->mConexao->getRetorno() as $value) {
                                    $nomeResponsavel = $value['nome'];
                                    $sobrenomeResponsavel = $value['sobrenome'];
                                    $responsavel = $nomeResponsavel.' '.$sobrenomeResponsavel;
                                }
                            }else{
                                $responsavel = '--';
                            }
                            

                            //instancia as configurações do sistema
                            $sConfiguracao = new sConfiguracao();
                            $diretorio = $sConfiguracao->getDiretorioVisualizacaoAcesso();

                            //altera a cor das marcações da prioridade
                            $sTratamentoPrioridade = new sTratamentoDados($prioridade);
                            $dadosPrioridade = $sTratamentoPrioridade->corPrioridade();
                            $posicao = $dadosPrioridade[0];
                            $cor = $dadosPrioridade[1];   
                            
                            //verifica se já possui responsável agregado
                            if($numero < 2){
                                $atribuirTicket = '<i class="fas fa-receipt mr-1"></i><a href="'.$sConfiguracao->getDiretorioVisualizacaoAcesso().'tPainel.php?menu=2_2_3&protocolo='.$idProtocoloCriptografado.'"> Atribuir</a><br />';
                            }else{
                                $atribuirTicket = '<i class="fas fa-hands-helping mr-1"></i> Em Andamento<br />';
                            }
                        
                        echo <<<HTML
                        <tr>
                            <td>{$protocolo}</td>
                            <td>{$dataAberturaTratada}</td>
                            <td>{$dataEncerramentoTratada}</td>
                            <td>{$nome}</td>
                            <td>{$telefoneTratado}</td>
                            <td>{$patrimonio}</td>
                            <td>
                                {$categoria}<br />
                                {$marca}<br />
                                {$modelo}<br />
                            </td>
                            <td>{$descricao}</td>
                            <td>
                                {$secretaria}/<br />
                                {$ambiente}
                            </td> 
                            <td>{$local}</td>
                            <td>
                                <i class="nav-icon fas fa-flag text-{$cor}"></i> {$posicao} - {$prioridade}
                            </td>
                            <td>{$responsavel}</td>
                            <td>
                                <i class="fas fa-search mr-1"></i>
                                <a href="{$diretorio}tPainel.php?menu=2_2_1&protocolo={$idProtocoloCriptografado}&seguranca={$seguranca}">
                                    Visualizar
                                </a><br />                                
                                {$atribuirTicket}
                                
                            </td>
                        </tr>
HTML;
                        }
                    }
                }
                ?>
                </tbody>
            <tfoot>
                <tr>
                    <th>Protocolo n.º</th>
                    <th>Abertura</th>
                    <th>Encerramento</th>
                    <?php 
                    if($_SESSION['credencial']['nivelPermissao'] > 1){
                        echo <<<HTML
                        <th>Solicitante</th>
                        <th>Telefone</th>
HTML;
                    }
                    ?>
                    <th>Patrimônio</th>
                    <th>Categoria/ Marca/ Modelo</th>
                    <th>Descrição</th>
                    <th>
                        Secretaria
                    <?php
                    if ($_SESSION['credencial']['nivelPermissao'] > 1) {
                    echo <<<HTML
                        /<br />
                        Ambiente
HTML;
                    }
                    ?>
                    </th>  
                    <th>Local</th>
                    <?php
                    if ($_SESSION['credencial']['nivelPermissao'] > 1) {
                    echo <<<HTML
                    <th>Prioridade</th>
HTML;
                    }
                    ?>
                    <th>Responsável</th>
                    <th>Atribuir/ Visualizar</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<script>
    //função para alterar texto da checkbox
    function decisao(){
       if (document.getElementById('suportesEncerrados').checked) {
            document.getElementById('conteudo').innerHTML = 'Sim';
        } else {
            document.getElementById('conteudo').innerHTML = 'Não';
        }
    }
</script>
<script>
    $(function () {
        $("#tabelaMenu2_2").DataTable({
            language:{
                url: "https://itapoa.app.br/vendor/dataTable_pt_br/dataTable_pt_br.json"
            },
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "aaSorting": [10, "desc"]
        }).buttons().container().appendTo('#tabelaMenu2_2_wrapper .col-md-6:eq(0)');        
    });
</script>