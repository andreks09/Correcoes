<?php
use App\sistema\acesso\{
    sSecretaria,
    sSetor,
    sEmail,
    sTelefone
};
                 
//instancia equipamento para buscar os dados
$pagina = 'tMenu7_4.php';

$sSetor = new sSetor(0);
$sSetor->consultar($pagina);

?>
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Contatos Locais - Setores</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="tabela1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Secretaria</th>
                    <th>Setor</th>
                    <th>Endereço</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($sSetor->mConexao->getRetorno() as $valueSetor) {  
                    //busca dados dos equipamentos
                    $idSetor = $valueSetor['idsetor'];
                    $setor   = $valueSetor['nomenclatura'];      
                    $endereco       = $valueSetor['endereco'];
                    $idSecretaria   = $valueSetor['secretaria_idsecretaria'];
                    
                    $sSecretaria    = new sSecretaria($idSecretaria);
                    $sSecretaria->setNomeCampo('idsecretaria');
                    $sSecretaria->setValorCampo($idSecretaria);
                    $sSecretaria->consultar($pagina);
                    
                    if($sSecretaria->getValidador()){
                        foreach ($sSecretaria->mConexao->getRetorno() as $valueSecretaria) {
                            $secretaria = $valueSecretaria['nomenclatura'];
                        }
                    }
                    
                    $sEmail         = new sEmail(0, 0);
                    $sEmail->setNomeCampo('setor_idsetor');
                    $sEmail->setValorCampo($idSetor);
                    $sEmail->consultar('tMenu7_4.php-setor');
                    
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
                    
                    $sTelefone      = new sTelefone(0, 0, '');
                    $sTelefone->setNomeCampo('setor_idsetor');
                    $sTelefone->setValorCampo($idSetor);
                    $sTelefone->consultar('tMenu7_4.php-setor');
                    
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
                            $telefone    = $valueTelefone2['numero'];
                        }
                        
                        $telefoneTratado = $sTelefone->tratarTelefone($telefone);  
                    }else{
                        $telefoneTratado = '--';
                    }  
                    
                                      
                    
                    echo <<<HTML
                    <tr>
                        <td>{$secretaria}</td>
                        <td>{$setor}</td>
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
                    <th>Setor</th>
                    <th>Endereço</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- /.card-body -->
</div>