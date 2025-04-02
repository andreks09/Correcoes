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
    sTratamentoDados
};

$sConfiguracao = new sConfiguracao();
$sUsuario = new sUsuario();
$sUsuario->consultar('tMenu1_2.php');
?>
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Etapa 1 - Outros Usuários</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="tabelaMenu1_2" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Cargo</th>
                    <th>Locais</th>
                    <th>Telefones</th>
                    <th>E-mails</th>
                    <th>Situação</th>
                    <th>Editar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($sUsuario->getValidador()) {
                    foreach ($sUsuario->mConexao->getRetorno() as $key => $value) {
                        $idUsuario = $value['idusuario'];
                        
                        //não mostrar os dados do usuário que acessa, pois a alteração desse localiza-se em outro menu
                        if ($_SESSION['credencial']['idUsuario'] != $idUsuario) {
                            //consultar os dados do usuário e armazenar em variáveis locais                                
                            $nome = $value['nome'] . ' ' . $value['sobrenome'];
                            $sSecretaria = new sSecretaria(0);
                            $sSecretaria->setNomeCampo('idsecretaria');
                            $sSecretaria->setValorCampo($value['secretaria_idsecretaria']);
                            $sSecretaria->consultar('tMenu1_2.php');
                            
                            if($sSecretaria->getValidador()){
                                foreach ($sSecretaria->mConexao->getRetorno() as $valorSecretaria) {
                                    $idSecretaria = $valorSecretaria['idsecretaria'];
                                    $secretaria = $valorSecretaria['nomenclatura'];
                                }
                            }else{
                                $secretaria = '--';
                            }
                            
                            $sCargo = new sCargo(0);
                            $sCargo->setNomeCampo('idcargo');
                            $sCargo->setValorCampo($value['cargo_idcargo']);
                            $sCargo->consultar('tMenu1_2.php');
                            
                            foreach ($sCargo->mConexao->getRetorno() as $valorCargo) {
                                $cargo = $valorCargo['nomenclatura'];
                            }

                            //se tiver um departamento vinculado, retorna os dados
                            if( $value['departamento_iddepartamento']){
                                $sDepartamento = new sDepartamento(0);
                                $sDepartamento->setNomeCampo('iddepartamento');
                                $sDepartamento->setValorCampo($value['departamento_iddepartamento']);
                                $sDepartamento->consultar('tMenu1_2.php');

                                if($sDepartamento->getValidador()){
                                    foreach ($sDepartamento->mConexao->getRetorno() as $valorDepartamento) {
                                        $idDepartamento = $valorDepartamento['iddepartamento'];
                                        $departamento = $valorDepartamento['nomenclatura'];
                                    }
                                }else{
                                    $departamento = '--';
                                }
                            }else{
                                $departamento = '--';
                            }
                            
                            //se tiver um departamento vinculado, retorna os dados
                            if($value['coordenacao_idcoordenacao']){
                                $sCoordenacao = new sCoordenacao(0);
                                $sCoordenacao->setNomeCampo('idcoordenacao');
                                $sCoordenacao->setValorCampo($value['coordenacao_idcoordenacao']);
                                $sCoordenacao->consultar('tMenu1_2.php');

                                if($sCoordenacao->getValidador()){
                                    foreach ($sCoordenacao->mConexao->getRetorno() as $valorCoordenacao) {
                                        $idCoordenacao = $valorCoordenacao['idcoordenacao'];
                                        $coordenacao = $valorCoordenacao['nomenclatura'];
                                    }
                                }else{
                                    $coordenacao = '--';
                                }
                            }else{
                                $coordenacao = '--';
                            }
                            
                            //se tiver um departamento vinculado, retorna os dados
                            if($value['setor_idsetor']){
                                $sSetor = new sSetor(0);
                                $sSetor->setNomeCampo('idsetor');
                                $sSetor->setValorCampo($value['setor_idsetor']);
                                $sSetor->consultar('tMenu1_2.php');

                                if($sSetor->getValidador()){
                                    foreach ($sSetor->mConexao->getRetorno() as $valorSetor) {
                                        $idSetor = $valorSetor['idsetor'];
                                        $setor = $valorSetor['nomenclatura'];
                                    }
                                }else{
                                    $setor = '--';
                                }
                            }else{
                                $setor = '--';
                            }
                            
                            //se tiver telefone na secretaria, retorna os dados
                            if(isset($value['secretaria_idsecretaria'])){
                                $sTelefoneSecretaria = new sTelefone(0, 0, '');
                                $sTelefoneSecretaria->setNomeCampo('secretaria_idsecretaria');
                                $sTelefoneSecretaria->setValorCampo($value['secretaria_idsecretaria']);
                                $sTelefoneSecretaria->consultar('tMenu1_2.php-secretaria');
                                
                                if($sTelefoneSecretaria->getValidador()){
                                    foreach ($sTelefoneSecretaria->mConexao->getRetorno() as $valorTelefoneSecretaria) {
                                        $idTelefoneSecretaria = $valorTelefoneSecretaria['telefone_idtelefone'];                                    
                                    } 

                                    //consultar o telefone da secretaria
                                    $sTelefoneSecretaria->setNomeCampo('idtelefone');
                                    $sTelefoneSecretaria->setValorCampo($idTelefoneSecretaria);
                                    $sTelefoneSecretaria->consultar('tMenu1_2.php-secretaria2');

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
                            if(isset($value['departamento_iddepartamento'])){
                                $sTelefoneDepartamento = new sTelefone(0, 0, '');
                                $sTelefoneDepartamento->setNomeCampo('departamento_iddepartamento');
                                $sTelefoneDepartamento->setValorCampo($value['departamento_iddepartamento']);
                                $sTelefoneDepartamento->consultar('tMenu1_2.php-departamento');

                                if($sTelefoneDepartamento->getValidador()){
                                    foreach ($sTelefoneDepartamento->mConexao->getRetorno() as $valorTelefoneDepartamento) {
                                        $idTelefoneDepartamento = $valorTelefoneDepartamento['telefone_idtelefone'];                                    
                                    } 

                                    //consultar o telefone da departamento
                                    $sTelefoneDepartamento->setNomeCampo('idtelefone');
                                    $sTelefoneDepartamento->setValorCampo($idTelefoneDepartamento);
                                    $sTelefoneDepartamento->consultar('tMenu1_2.php-departamento2');

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
                            if(isset($value['coordenacao_idcoordenacao'])){
                                $sTelefoneCoordenacao = new sTelefone(0, 0, '');
                                $sTelefoneCoordenacao->setNomeCampo('coordenacao_idcoordenacao');
                                $sTelefoneCoordenacao->setValorCampo($value['coordenacao_idcoordenacao']);
                                $sTelefoneCoordenacao->consultar('tMenu1_2.php-coordenacao');

                                if($sTelefoneCoordenacao->getValidador()){
                                    foreach ($sTelefoneCoordenacao->mConexao->getRetorno() as $valorTelefoneCoordenacao) {
                                        $idTelefoneCoordenacao = $valorTelefoneCoordenacao['telefone_idtelefone'];                                    
                                    } 

                                    //consultar o telefone da coordenacao
                                    $sTelefoneCoordenacao->setNomeCampo('idtelefone');
                                    $sTelefoneCoordenacao->setValorCampo($idTelefoneCoordenacao);
                                    $sTelefoneCoordenacao->consultar('tMenu1_2.php-coordenacao2');

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
                            if(isset($value['setor_idsetor'])){
                                $sTelefoneSetor = new sTelefone(0, 0, '');
                                $sTelefoneSetor->setNomeCampo('setor_idsetor');
                                $sTelefoneSetor->setValorCampo($value['setor_idsetor']);
                                $sTelefoneSetor->consultar('tMenu1_2.php-setor');

                                if($sTelefoneSetor->getValidador()){
                                    foreach ($sTelefoneSetor->mConexao->getRetorno() as $valorTelefoneSetor) {
                                        $idTelefoneSetor = $valorTelefoneSetor['telefone_idtelefone'];                                    
                                    } 

                                    //consultar o telefone da setor
                                    $sTelefoneSetor->setNomeCampo('idtelefone');
                                    $sTelefoneSetor->setValorCampo($idTelefoneSetor);
                                    $sTelefoneSetor->consultar('tMenu1_2.php-setor2');

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
                            
                            //se tiver telefone na usuario, retorna os dados
                            $sTelefoneUsuario = new sTelefone(0, 0, '');
                            $sTelefoneUsuario->setNomeCampo('idtelefone');
                            $sTelefoneUsuario->setValorCampo($value['telefone_idtelefone']);
                            $sTelefoneUsuario->consultar('tMenu1_2.php');
                            
                            if($sTelefoneUsuario->getValidador()){
                                foreach ($sTelefoneUsuario->mConexao->getRetorno() as $valorTelefoneUsuario) {
                                    $telefoneUsuario = $valorTelefoneUsuario['numero'];
                                    $valorTelefoneUsuario['whatsApp'] ? $whatsAppUsuario = '<i class="fab fa-whatsapp mr-1"></i>' : $whatsAppUsuario = '';
                                }          
                                
                                $sTratamentoTelefoneUsuario = new sTratamentoDados($telefoneUsuario);
                                $telefoneUsuario = $sTratamentoTelefoneUsuario->tratarTelefone();
                            }else{
                                $telefoneUsuario = '--';
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
                            
                            //se tiver email na usuario, retorna os dados
                            $sEmailUsuario = new sEmail(0, 0, '');
                            $sEmailUsuario->setNomeCampo('idemail');
                            $sEmailUsuario->setValorCampo($value['email_idemail']);
                            $sEmailUsuario->consultar('tMenu1_2.php');
                            
                            if($sEmailUsuario->getValidador()){
                                foreach ($sEmailUsuario->mConexao->getRetorno() as $valorEmailUsuario) {
                                    $emailUsuario = $valorEmailUsuario['nomenclatura'];
                                }          
                            }else{
                                $emailUsuario = '--';
                            }

                            if ($value['situacao'] == true) {
                                $situacao = '<span class="bg-green">ativa</span>';
                            } else {
                                $situacao = '<span class="bg-red">inativa</span>';
                            }
                            
                            $seguranca = base64_encode($idUsuario);

                    echo <<<HTML
                    <tr>
                        <td>{$nome}</td>
                        <td>{$cargo}</td>
                        <td>
                            {$secretaria}<br />
                            {$departamento}<br />
                            {$coordenacao}<br />
                            {$setor}</td>
                        </td>
                        <td>
                            <i class="fas fa-building mr-1"></i> {$whatsAppSecretaria} {$telefoneSecretaria}<br />
                            <i class="fas fa-building mr-1"></i> {$whatsAppDepartamento} {$telefoneDepartamento}<br />
                            <i class="fas fa-building mr-1"></i> {$whatsAppCoordenacao} {$telefoneCoordenacao}<br />
                            <i class="fas fa-building mr-1"></i> {$whatsAppSetor} {$telefoneSetor}<br />
                            <i class="fas fa-user-alt mr-1"></i> {$whatsAppUsuario} {$telefoneUsuario}
                        </td>
                        <td>
                            {$emailSecretaria}<br />
                            {$emailDepartamento}<br />
                            {$emailCoordenacao}<br />
                            {$emailSetor}<br />
                            {$emailUsuario}<br />
                        </td>
                        <td>{$situacao}</td>
                        <td>
                            <a href="{$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=1_2_1&seguranca={$seguranca}">
                                <i class="fas fa-edit mr-1"></i>
                            </a>
                        </td>
                    </tr>
HTML;
                        }
                    }
                } else {
                    echo <<<HTML
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
HTML;
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
                    <th>Editar</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<script>
$(function () {
    $("#tabelaMenu1_2").DataTable({
        language:{
            url: "https://itapoa.app.br/vendor/dataTable_pt_br/dataTable_pt_br.json"
        },
        "responsive": true, 
        "lengthChange": false, 
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#tabelaMenu1_2_wrapper .col-md-6:eq(0)');        
});
</script>