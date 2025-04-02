<?php

use App\sistema\acesso\{
    sConfiguracao,
    sUsuario,
    sCargo,
    sSecretaria,
    sDepartamento,
    sCoordenacao,
    sSetor,
    sTelefone,
    sEmail,
    sNotificacao,
    sTratamentoDados
};

$sConfiguracao = new sConfiguracao();
$sUsuario = new sUsuario();
$sUsuario->consultar('tMenu1_3.php');

if (isset($_GET['campo']) ||
    isset($_GET['codigo'])) {    
    $sNotificacao = new sNotificacao($_GET['codigo']);
    switch ($_GET['campo']) {
        case 'verificar':
            if ($_GET['codigo'] == 'S1') {
                $alertaVerificar = ' is-valid';
            } else {
                $alertaVerificar = ' is-warning';
            }
            break;
        case 'nome':
            if ($_GET['codigo'] == 'S1') {
                $alertaNome = ' is-valid';
            } else {
                $alertaNome = ' is-warning';
            }
            break;
        case 'situacao':
            if ($_GET['codigo'] == 'S1') {
                $alertaNome = ' is-valid';
            } else {
                $alertaNome = ' is-warning';
            }
            break;
        default:
            break;
    }

    //cria as variáveis da notificação
    $tipo = $sNotificacao->getTipo();
    $titulo = $sNotificacao->getTitulo();
    $mensagem = $sNotificacao->getMensagem();
}
?>
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Etapa 1 - Solicitações de Acesso</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <?php
        if (isset($tipo) &&
            isset($titulo) &&
            isset($mensagem)) {
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
        
        <table id="tabelaMenu1_3" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Cargo</th>
                    <th>Locais</th>
                    <th>Telefones</th>
                    <th>E-mails</th>
                    <th>Situação</th>
                    <th>Verificar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($sUsuario->mConexao->getValidador()) {
                    foreach ($sUsuario->mConexao->getRetorno() as $key => $value) {
                        //consultar os dados do usuário e armazenar em variáveis locais   
                        $seguranca = base64_encode($value['idsolicitacao']);
                        $nomeExaminado = $value['nome'] . ' ' . $value['sobrenome'];
                        $idSecretaria = $value['secretaria_idsecretaria'];
                        $emailUsuario = $value['email'];
                        $idDepartamento = $value['departamento_iddepartamento'];
                        $idCoordenacao = $value['coordenacao_idcoordenacao'];
                        $idSetor = $value['setor_idsetor'];
                        $sSecretaria = new sSecretaria($value['secretaria_idsecretaria']);
                        $sSecretaria->consultar('tMenu1_3.php');
                        $secretaria = $sSecretaria->getNomenclatura();
                        $sCargo = new sCargo($value['cargo_idcargo']);
                        $sCargo->consultar('tMenu1_3.php');
                        $cargo = $sCargo->getNomenclatura();
                        $dataHoraSolicitacao = $value['dataHoraSolicitacao'];
                        $dataHoraSolicitacaoTratada = $sUsuario->tratarData($dataHoraSolicitacao);
                        
                        if(!empty($value['dataHoraExaminador'])){
                            $dataHoraExaminador = $value['dataHoraExaminador'];
                            
                            $sExaminador = new sUsuario();
                            $dataHoraExaminador = $sExaminador->tratarData($dataHoraExaminador);
                        }
                        
                        //verifica se o registro já foi aprovado e por quem
                        if($value['examinador']){
                            $idExaminador = $value['examinador'];                            
                            $sExaminador = new sUsuario();
                            $sExaminador->setNomeCampo('idusuario');
                            $sExaminador->setValorCampo($idExaminador);
                            $sExaminador->consultar('tMenu1_3-examinador.php');
                            
                            foreach ($sExaminador->mConexao->getRetorno() as $valorExaminador) {
                                $nome = $valorExaminador['nome'];
                                $sobrenome = $valorExaminador['sobrenome'];
                            }
                            
                            $nomeExaminador = $nome.' '.$sobrenome;   
                            
                            if($value['situacao']){
                                $situacao = '<span class="bg-green"> aprovada</span><br /><i>por '.$nomeExaminador.'<br />em '.$dataHoraExaminador.'</i>';
                            }else{
                                $situacao = '<span class="bg-red">reprovada</span><br /><i>por '.$nomeExaminador.'<br />em '.$dataHoraExaminador.'</i>';
                            }
                        }else{
                            $situacao = '<span class="bg-info">aguardando análise</span><br /><i>desde '.$dataHoraSolicitacaoTratada.'</i>';
                        }

                        //se tiver um departamento vinculado, retorna os dados
                        if ($value['departamento_iddepartamento']) {
                            $sDepartamento = new sDepartamento(0);
                            $sDepartamento->setNomeCampo('iddepartamento');
                            $sDepartamento->setValorCampo($value['departamento_iddepartamento']);
                            $sDepartamento->consultar('tMenu1_3.php');
                            
                            foreach ($sDepartamento->mConexao->getRetorno() as $valorDeapartamento) {
                                $departamento = $valorDeapartamento['nomenclatura'];
                            }
                        } else {
                            $departamento = '--';
                        }
                        
                        //se tiver um coordenacao vinculado, retorna os dados
                        if ($value['coordenacao_idcoordenacao']) {
                            $sCoordenacao = new sCoordenacao(0);
                            $sCoordenacao->setNomeCampo('idcoordenacao');
                            $sCoordenacao->setValorCampo($value['coordenacao_idcoordenacao']);
                            $sCoordenacao->consultar('tMenu1_3.php');
                            
                            foreach ($sCoordenacao->mConexao->getRetorno() as $valorDeapartamento) {
                                $coordenacao = $valorDeapartamento['nomenclatura'];
                            }
                        } else {
                            $coordenacao = '--';
                        }
                        
                        //se tiver um setor vinculado, retorna os dados
                        if ($value['setor_idsetor']) {
                            $sSetor = new sSetor(0);
                            $sSetor->setNomeCampo('idsetor');
                            $sSetor->setValorCampo($value['setor_idsetor']);
                            $sSetor->consultar('tMenu1_3.php');
                            
                            foreach ($sSetor->mConexao->getRetorno() as $valorDeapartamento) {
                                $setor = $valorDeapartamento['nomenclatura'];
                            }
                        } else {
                            $setor = '--';
                        }
                        
                        //se tiver telefone na secretaria, retorna os dados
                        if(isset($value['secretaria_idsecretaria'])){
                            $sTelefoneSecretaria = new sTelefone(0, 0, '');
                            $sTelefoneSecretaria->setNomeCampo('secretaria_idsecretaria');
                            $sTelefoneSecretaria->setValorCampo($value['secretaria_idsecretaria']);
                            $sTelefoneSecretaria->consultar('tMenu1_3.php-secretaria');

                            if($sTelefoneSecretaria->getValidador()){
                                foreach ($sTelefoneSecretaria->mConexao->getRetorno() as $valorTelefoneSecretaria) {
                                    $idTelefoneSecretaria = $valorTelefoneSecretaria['telefone_idtelefone'];                                    
                                } 

                                //consultar o telefone da secretaria
                                $sTelefoneSecretaria->setNomeCampo('idtelefone');
                                $sTelefoneSecretaria->setValorCampo($idTelefoneSecretaria);
                                $sTelefoneSecretaria->consultar('tMenu1_3.php-secretaria2');

                                foreach ($sTelefoneSecretaria->mConexao->getRetorno() as $valorTelefoneSecretaria) {
                                    $telefoneSecretaria = $valorTelefoneSecretaria['numero'];
                                    $valorTelefoneSecretaria['whatsApp'] ? $whatsAppSecretaria = '<i class="fab fa-whatsapp mr-1"></i>' : $whatsAppSecretaria = '';
                                }

                                $sTratamentoTelefoneSecretaria = new sTratamentoDados($telefoneSecretaria);
                                $telefoneSecretaria = $sTratamentoTelefoneSecretaria->tratarTelefone();
                            }else{
                                $telefoneSecretaria = '--';
                                $whatsAppSecretaria = '';
                            }                            
                        }else{
                            $telefoneSecretaria = '--';
                            $whatsAppSecretaria = '';
                        }
                        
                        //se tiver telefone na departamento, retorna os dados
                        if( $value['departamento_iddepartamento'] != 0){
                            $sTelefoneDepartamento = new sTelefone(0, 0, '');
                            $sTelefoneDepartamento->setNomeCampo('departamento_iddepartamento');
                            $sTelefoneDepartamento->setValorCampo($value['departamento_iddepartamento']);
                            $sTelefoneDepartamento->consultar('tMenu1_3.php-departamento');

                            if($sTelefoneDepartamento->getValidador()){
                                foreach ($sTelefoneDepartamento->mConexao->getRetorno() as $valorTelefoneDepartamento) {
                                    $idTelefoneDepartamento = $valorTelefoneDepartamento['telefone_idtelefone'];                                    
                                } 

                                //consultar o telefone da departamento
                                $sTelefoneDepartamento->setNomeCampo('idtelefone');
                                $sTelefoneDepartamento->setValorCampo($idTelefoneDepartamento);
                                $sTelefoneDepartamento->consultar('tMenu1_3.php-departamento2');

                                foreach ($sTelefoneDepartamento->mConexao->getRetorno() as $valorTelefoneDepartamento) {
                                    $telefoneDepartamento = $valorTelefoneDepartamento['numero'];
                                    $valorTelefoneDepartamento['whatsApp'] ? $whatsAppDepartamento = '<i class="fab fa-whatsapp mr-1"></i>' : $whatsAppDepartamento = '';
                                }

                                $sTratamentoTelefoneDepartamento = new sTratamentoDados($telefoneDepartamento);
                                $telefoneDepartamento = $sTratamentoTelefoneDepartamento->tratarTelefone();
                            }else{
                                $telefoneDepartamento = '--';
                                $whatsAppDepartamento = '';
                            }
                        }else{
                            $telefoneDepartamento = '--';
                            $whatsAppDepartamento = '';
                        }
                            
                        //se tiver telefone na coordenacao, retorna os dados
                        if($value['coordenacao_idcoordenacao'] != 0){
                            $sTelefoneCoordenacao = new sTelefone(0, 0, '');
                            $sTelefoneCoordenacao->setNomeCampo('coordenacao_idcoordenacao');
                            $sTelefoneCoordenacao->setValorCampo($value['coordenacao_idcoordenacao']);
                            $sTelefoneCoordenacao->consultar('tMenu1_3.php-coordenacao');

                            if($sTelefoneCoordenacao->getValidador()){
                                foreach ($sTelefoneCoordenacao->mConexao->getRetorno() as $valorTelefoneCoordenacao) {
                                    $idTelefoneCoordenacao = $valorTelefoneCoordenacao['telefone_idtelefone'];                                    
                                } 

                                //consultar o telefone da coordenacao
                                $sTelefoneCoordenacao->setNomeCampo('idtelefone');
                                $sTelefoneCoordenacao->setValorCampo($idTelefoneCoordenacao);
                                $sTelefoneCoordenacao->consultar('tMenu1_3.php-coordenacao2');

                                foreach ($sTelefoneCoordenacao->mConexao->getRetorno() as $valorTelefoneCoordenacao) {
                                    $telefoneCoordenacao = $valorTelefoneCoordenacao['numero'];
                                    $valorTelefoneCoordenacao['whatsApp'] ? $whatsAppCoordenacao = '<i class="fab fa-whatsapp mr-1"></i>' : $whatsAppCoordenacao = '';
                                }

                                $sTratamentoTelefoneCoordenacao = new sTratamentoDados($telefoneCoordenacao);
                                $telefoneCoordenacao = $sTratamentoTelefoneCoordenacao->tratarTelefone();
                            }else{
                                $telefoneCoordenacao = '--';
                                $whatsAppCoordenacao = '';
                            }
                        }else{
                            $telefoneCoordenacao = '--';
                            $whatsAppCoordenacao = '';
                        }
                            
                        //se tiver telefone na setor, retorna os dados
                        if($value['setor_idsetor'] != 0){
                            $sTelefoneSetor = new sTelefone(0, 0, '');
                            $sTelefoneSetor->setNomeCampo('setor_idsetor');
                            $sTelefoneSetor->setValorCampo($value['setor_idsetor']);
                            $sTelefoneSetor->consultar('tMenu1_3.php-setor');

                            if($sTelefoneSetor->getValidador()){
                                foreach ($sTelefoneSetor->mConexao->getRetorno() as $valorTelefoneSetor) {
                                    $idTelefoneSetor = $valorTelefoneSetor['telefone_idtelefone'];                                    
                                } 

                                //consultar o telefone da setor
                                $sTelefoneSetor->setNomeCampo('idtelefone');
                                $sTelefoneSetor->setValorCampo($idTelefoneSetor);
                                $sTelefoneSetor->consultar('tMenu1_3.php-setor2');

                                foreach ($sTelefoneSetor->mConexao->getRetorno() as $valorTelefoneSetor) {
                                    $telefoneSetor = $valorTelefoneSetor['numero'];
                                    $valorTelefoneSetor['whatsApp'] ? $whatsAppSetor = '<i class="fab fa-whatsapp mr-1"></i>' : $whatsAppSetor = '';
                                }

                                $sTratamentoTelefoneSetor = new sTratamentoDados($telefoneSetor);
                                $telefoneSetor = $sTratamentoTelefoneSetor->tratarTelefone();
                            }else{
                                $telefoneSetor = '--';
                                $whatsAppSetor = '';
                            }
                        }else{
                            $telefoneSetor = '--';
                            $whatsAppSetor = '';
                        }
                            
                        //se tiver telefone na tabela solicitacao, retorna os dados                            
                        if($value['telefone']){
                                $telefoneUsuario = $value['telefone'];
                                $value['whatsApp'] ? $whatsAppUsuario = '<i class="fab fa-whatsapp mr-1"></i>' : $whatsAppUsuario = '';

                            $sTratamentoTelefoneUsuario = new sTratamentoDados($telefoneUsuario);
                            $telefoneUsuarioTratado = $sTratamentoTelefoneUsuario->tratarTelefone();
                        }else{
                            $telefoneUsuarioTratado = '--';
                            $whatsAppUsuario = '';
                        }

                        //se tiver email na secretaria, retorna os dados
                        if($idSecretaria){
                            $sEmailSecretaria = new sEmail('', '');
                            $sEmailSecretaria->setNomeCampo('secretaria_idsecretaria');
                            $sEmailSecretaria->setValorCampo($idSecretaria);
                            $sEmailSecretaria->consultar('tMenu1_2.php-secretaria');

                            if($sEmailSecretaria->getValidador()){
                                foreach ($sEmailSecretaria->mConexao->getRetorno() as $valorEmailSecretaria) {
                                    $idEmailSecretaria = $valorEmailSecretaria['email_idemail'];
                                }

                                $sEmailSecretaria = new sEmail('', '');
                                $sEmailSecretaria->setNomeCampo('idemail');
                                $sEmailSecretaria->setValorCampo($idEmailSecretaria);
                                $sEmailSecretaria->consultar('tMenu1_2.php-secretaria2');

                                foreach ($sEmailSecretaria->mConexao->getRetorno() as $valorEmailSecretaria) {
                                    $emailSecretaria = $valorEmailSecretaria['nomenclatura'];
                                }
                            }else{
                                $emailSecretaria = '--';
                            }
                        }else{
                            $emailSecretaria = '--';
                        }

                        //se tiver email na secretaria, retorna os dados
                            if($idDepartamento){
                                $sEmailDepartamento = new sEmail('', '');
                                $sEmailDepartamento->setNomeCampo('departamento_iddepartamento');
                                $sEmailDepartamento->setValorCampo($idDepartamento);
                                $sEmailDepartamento->consultar('tMenu1_2.php-departamento');
                                
                                if($sEmailDepartamento->getValidador()){
                                    foreach ($sEmailDepartamento->mConexao->getRetorno() as $valorEmailDepartamento) {
                                        $idEmailDepartamento = $valorEmailDepartamento['email_idemail'];
                                    }
                                    
                                    $sEmailDepartamento = new sEmail('', '');
                                    $sEmailDepartamento->setNomeCampo('idemail');
                                    $sEmailDepartamento->setValorCampo($idEmailDepartamento);
                                    $sEmailDepartamento->consultar('tMenu1_2.php-departamento2');

                                    foreach ($sEmailDepartamento->mConexao->getRetorno() as $valorEmailDepartamento) {
                                        $emailDepartamento = $valorEmailDepartamento['nomenclatura'];
                                    }
                                }else{
                                    $emailDepartamento = '--';
                                }
                            }else{
                                $emailDepartamento = '--';
                            }
                            
                            //se tiver email na secretaria, retorna os dados
                            if($idCoordenacao){
                                $sEmailCoordenacao = new sEmail('', '');
                                $sEmailCoordenacao->setNomeCampo('coordenacao_idcoordenacao');
                                $sEmailCoordenacao->setValorCampo($idCoordenacao);
                                $sEmailCoordenacao->consultar('tMenu1_2.php-coordenacao');
                                
                                if($sEmailCoordenacao->getValidador()){
                                    foreach ($sEmailCoordenacao->mConexao->getRetorno() as $valorEmailCoordenacao) {
                                        $idEmailCoordenacao = $valorEmailCoordenacao['email_idemail'];
                                    }
                                    
                                    $sEmailCoordenacao = new sEmail('', '');
                                    $sEmailCoordenacao->setNomeCampo('idemail');
                                    $sEmailCoordenacao->setValorCampo($idEmailCoordenacao);
                                    $sEmailCoordenacao->consultar('tMenu1_2.php-coordenacao2');

                                    foreach ($sEmailCoordenacao->mConexao->getRetorno() as $valorEmailCoordenacao) {
                                        $emailCoordenacao = $valorEmailCoordenacao['nomenclatura'];
                                    }
                                }else{
                                    $emailCoordenacao = '--';
                                }
                            }else{
                                $emailCoordenacao = '--';
                            }
                            
                            //se tiver email na secretaria, retorna os dados
                            if($idSetor){
                                $sEmailSetor = new sEmail('', '');
                                $sEmailSetor->setNomeCampo('setor_idsetor');
                                $sEmailSetor->setValorCampo($idSetor);
                                $sEmailSetor->consultar('tMenu1_2.php-setor');
                                
                                if($sEmailSetor->getValidador()){
                                    foreach ($sEmailSetor->mConexao->getRetorno() as $valorEmailSetor) {
                                        $idEmailSetor = $valorEmailSetor['email_idemail'];
                                    }
                                    
                                    $sEmailSetor = new sEmail('', '');
                                    $sEmailSetor->setNomeCampo('idemail');
                                    $sEmailSetor->setValorCampo($idEmailSetor);
                                    $sEmailSetor->consultar('tMenu1_2.php-setor2');

                                    foreach ($sEmailSetor->mConexao->getRetorno() as $valorEmailSetor) {
                                        $emailSetor = $valorEmailSetor['nomenclatura'];
                                    }
                                }else{
                                    $emailSetor = '--';
                                }
                            }else{
                                $emailSetor = '--';
                            }
                        
                        //instancia as configurações do sistema
                        $sConfiguracao = new sConfiguracao();
                        $diretorio = $sConfiguracao->getDiretorioVisualizacaoAcesso();
                        
                        $pagina = 'tMenu1_3.php';
                        
                        echo <<<HTML
                        <tr>
                            <td>{$nomeExaminado}</td>
                            <td>{$cargo}</td>
                            <td>
                                {$secretaria}<br / >
                                {$departamento}<br />
                                {$coordenacao}<br />
                                {$setor}</td>
                            <td>
                                <i class="fas fa-building mr-1"></i> {$whatsAppSecretaria} {$telefoneSecretaria}<br />
                                <i class="fas fa-building mr-1"></i> {$whatsAppDepartamento} {$telefoneDepartamento}<br />
                                <i class="fas fa-building mr-1"></i> {$whatsAppCoordenacao} {$telefoneCoordenacao}<br />
                                <i class="fas fa-building mr-1"></i> {$whatsAppSetor} {$telefoneSetor}<br />
                                <i class="fas fa-user-alt mr-1"></i> {$whatsAppUsuario} {$telefoneUsuarioTratado}
                            </td>
                            <td>
                                {$emailSecretaria}<br />
                                {$emailDepartamento}<br />
                                {$emailCoordenacao}<br />
                                {$emailSetor}<br />
                                {$emailUsuario}<br />
                            </td>
                            <td>
                                {$situacao}
                            </td>
                            <td>
                                
HTML;
                                if(empty($value['dataHoraExaminador'])){
                                    echo <<<HTML
                                    <i class="fas fa-door-open"></i>
                                    <a href="{$diretorio}tPainel.php?menu=1_3_1&seguranca={$seguranca}"> Verificar</a>
HTML;
                                } else {
                                    echo <<<HTML
                                    <i class="fas fa-check-circle"></i> Verificado
HTML;
                                }
                            echo <<<HTML
                            </td>
                        </tr>
HTML;
                    }
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Usuário</th>
                    <th>Cargo</th>
                    <th>Locais</th>
                    <th>Telefones</th>
                    <th>E-mails</th>
                    <th>Situação</th>
                    <th>Verificar</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<script>
    $(function () {
        $("#tabelaMenu1_3").DataTable({
            language:{
                url: "https://itapoa.app.br/vendor/dataTable_pt_br/dataTable_pt_br.json"
            },
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "aaSorting": [6, "asc"]
        }).buttons().container().appendTo('#tabelaMenu1_3_wrapper .col-md-6:eq(0)');        
    });
</script>