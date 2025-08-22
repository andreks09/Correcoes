<?php
use App\sistema\acesso\{
    sSecretaria,
    sEmail,
    sTelefone
};
                 
//instancia equipamento para buscar os dados
$pagina = 'tMenu7_1.php';

$sSecretaria = new sSecretaria(0);
$sSecretaria->consultar($pagina);

?>
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Contatos Locais - Secretarias</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="tabela1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Secretaria</th>
                    <th>Endereço</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($sSecretaria->mConexao->getRetorno() as $valueSecretaria) {  
                    //busca dados dos equipamentos
                    $idSecretaria = $valueSecretaria['idsecretaria'];
                    $secretaria = $valueSecretaria['nomenclatura'];      
                    $endereco = $valueSecretaria['endereco'];
                    
                    $sEmail = new sEmail(0, 0);
                    $sEmail->setNomeCampo('secretaria_idsecretaria');
                    $sEmail->setValorCampo($idSecretaria);
                    $sEmail->consultar('tMenu7_1.php-secretaria');
                    
                    if($sEmail->getValidador()){
                        foreach ($sEmail->mConexao->getRetorno() as $valueEmail) {
                            $idEmail = $valueEmail['email_idemail'];
                        }
                        
                        $sEmail->setNomeCampo('idemail');
                        $sEmail->setValorCampo($idEmail);
                        $sEmail->consultar($pagina);
                    }
                    
                    if($sEmail->getValidador()){
                        foreach ($sEmail->mConexao->getRetorno() as $valueEmail2) {
                            $email  = $valueEmail2['nomenclatura'];
                        }
                    }else{
                        $email      = '--';
                    }
                    
                    $sTelefone = new sTelefone(0, 0, '');
                    $sTelefone->setNomeCampo('secretaria_idsecretaria');
                    $sTelefone->setValorCampo($idSecretaria);
                    $sTelefone->consultar('tMenu7_1.php-secretaria');
                    
                    if($sTelefone->getValidador()){
                        foreach ($sTelefone->mConexao->getRetorno() as $valueTelefone) {
                            $idTelefone = $valueTelefone['telefone_idtelefone'];
                        }
                        
                        $sTelefone->setNomeCampo('idtelefone');
                        $sTelefone->setValorCampo($idTelefone);
                        $sTelefone->consultar($pagina);
                    }
                    
                    if($sTelefone->getValidador()){
                        foreach ($sTelefone->mConexao->getRetorno() as $valueTelefone2) {
                            $telefone = $valueTelefone2['numero'];
                        }
                    }else{
                        $telefoneTratado = '--';
                    }     
                    
                    $telefoneTratado = $sTelefone->tratarTelefone($telefone);                    
                    
                    echo <<<HTML
                    <tr>
                        <td>{$secretaria}</td>
                        <td>{$endereco}</td>
                        <td>{$email}</td>
                        <td>{$telefoneTratado}</td>                        
                    </tr>
HTML;
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Secretaria</th>
                    <th>Endereço</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- /.card-body -->
</div>