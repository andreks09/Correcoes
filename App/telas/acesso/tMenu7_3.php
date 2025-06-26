<?php
use App\sistema\acesso\{
    sSecretaria,
    sCoordenacao,
    sEmail,
    sTelefone
};
                 
//instancia equipamento para buscar os dados
$pagina = 'tMenu7_3.php';

$sCoordenacao = new sCoordenacao(0);
$sCoordenacao->consultar($pagina);

?>
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Contatos Locais - Coordenações</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="tabelaMenu7_3" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Secretaria</th>
                    <th>Coordenacao</th>
                    <th>Endereço</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($sCoordenacao->mConexao->getRetorno() as $valueCoordenacao) {  
                    //busca dados dos equipamentos
                    $idCoordenacao = $valueCoordenacao['idcoordenacao'];
                    $coordenacao   = $valueCoordenacao['nomenclatura'];      
                    $endereco       = $valueCoordenacao['endereco'];
                    $idSecretaria   = $valueCoordenacao['secretaria_idsecretaria'];
                    
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
                    $sEmail->setNomeCampo('coordenacao_idcoordenacao');
                    $sEmail->setValorCampo($idCoordenacao);
                    $sEmail->consultar('tMenu7_3.php-coordenacao');
                    
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
                    $sTelefone->setNomeCampo('coordenacao_idcoordenacao');
                    $sTelefone->setValorCampo($idCoordenacao);
                    $sTelefone->consultar('tMenu7_3.php-coordenacao');
                    
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
                        <td>{$coordenacao}</td>
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
                    <th>Coordenacao</th>
                    <th>Endereço</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<script>
    $(function () {
        $("#tabelaMenu7_3").DataTable({            
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "aaSorting": [0, "asc"]
        }).buttons().container().appendTo('#tabelaMenu7_3_wrapper .col-md-6:eq(0)');        
    });
</script>