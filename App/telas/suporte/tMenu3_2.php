<?php
use App\sistema\acesso\{
    sConfiguracao
};
use App\sistema\suporte\{
    sEquipamento,
    sCategoria,
    sModelo,
    sMarca,
    sTensao,
    sCorrente,
    sSistemaOperacional,
    sAmbiente
};
                 
//instancia equipamento para buscar os dados
$sEquipamento = new sEquipamento();
$sEquipamento->consultar('tMenu3_2.php');

?>
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Etapa 1 - Selecionar Equipamento</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="tabelaMenu3_2" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patrimônio</th>
                    <th>Categoria</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Etiqueta de Serviço</th>
                    <th>Número de Série</th>
                    <th>Tensão de Entrada</th>
                    <th>Corrente de Entrada</th>
                    <th>Sistema Operacional</th>
                    <th>Ambiente</th>
                    <th>Alterar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($sEquipamento->mConexao->getRetorno() as $value) {  
                    //busca dados dos equipamentos
                    $idEquipamento = $value['idequipamento'];
                    $patrimonio = $value['patrimonio'];
                    $idCategoria = $value['categoria_idcategoria'];
                    $idTensao = $value['tensao_idtensao'];
                    $idCorrente = $value['corrente_idcorrente'];
                    $idSistemaOperacional = $value['sistemaOperacional_idsistemaOperacional'];
                    $serie = $value['numeroDeSerie'];
                    $etiqueta = $value['etiquetaDeServico'];
                    $idModelo = $value['modelo_idmodelo'];
                    $idAmbiente = $value['ambiente_idambiente'];

                    //busca a categoria do equipamento
                    $sCategoria = new sCategoria();
                    $sCategoria->setNomeCampo('idcategoria');
                    $sCategoria->setValorCampo($idCategoria);
                    $sCategoria->consultar('tMenu3_2.php');
                    
                    foreach ($sCategoria->mConexao->getRetorno() as $value) {
                        $categoria = $value['nomenclatura'];
                    }

                    //busca o modelo do equipamento
                    $sModelo = new sModelo();
                    $sModelo->setNomeCampo('idmodelo');
                    $sModelo->setValorCampo($idModelo);
                    $sModelo->consultar('tMenu3_2.php');

                    foreach ($sModelo->mConexao->getRetorno() as $value) {
                        $modelo = $value['nomenclatura'];
                        $idMarca = $value['marca_idmarca'];
                    }  

                    //busca o marca do equipamento
                    $sMarca = new sMarca();
                    $sMarca->setNomeCampo('idmarca');
                    $sMarca->setValorCampo($idMarca);
                    $sMarca->consultar('tMenu3_2.php');
                    
                    foreach ($sMarca->mConexao->getRetorno() as $value) {
                        $marca = $value['nomenclatura'];
                    }

                    //busca a tensao do equipamento
                    $sTensao = new sTensao();
                    $sTensao->setNomeCampo('idtensao');
                    $sTensao->setValorCampo($idTensao);
                    $sTensao->consultar('tMenu3_2.php');
                    
                    foreach ($sTensao->mConexao->getRetorno() as $value) {
                        $tensao = $value['nomenclatura'];
                    }

                    //busca a corrente do equipamento
                    $sCorrente = new sCorrente();
                    $sCorrente->setNomeCampo('idcorrente');
                    $sCorrente->setValorCampo($idCorrente);
                    $sCorrente->consultar('tMenu3_2.php');
                    
                    foreach ($sCorrente->mConexao->getRetorno() as $value) {
                        $corrente = $value['nomenclatura'];
                    }

                    //busca a sistemaOperacional do equipamento
                    $sSistemaOperacional = new sSistemaOperacional();
                    $sSistemaOperacional->setNomeCampo('idsistemaOperacional');
                    $sSistemaOperacional->setValorCampo($idSistemaOperacional);
                    $sSistemaOperacional->consultar('tMenu3_2.php');
                    
                    foreach ($sSistemaOperacional->mConexao->getRetorno() as $value) {
                        $sistemaOperacional = $value['nomenclatura'];
                    }

                    //busca a ambiente do equipamento
                    $sAmbiente = new sAmbiente();
                    $sAmbiente->setNomeCampo('idambiente');
                    $sAmbiente->setValorCampo($idAmbiente);
                    $sAmbiente->consultar('tMenu3_2.php');
                    
                    foreach ($sAmbiente->mConexao->getRetorno() as $value) {
                        $ambiente = $value['nomenclatura'];
                    }
                    
                    $idEquipamentoCriptografada = base64_encode($idEquipamento);
                    
                    echo <<<HTML
                    <tr>
                        <td>{$idEquipamento}</td>
                        <td>{$patrimonio}</td>
                        <td>{$categoria}</td>
                        <td>{$marca}</td>
                        <td>{$modelo}</td>
                        <td>{$etiqueta}</td>
                        <td>{$serie}</td>
                        <td>{$tensao}</td>
                        <td>{$corrente}</td>
                        <td>{$sistemaOperacional}</td>
                        <td>{$ambiente}</td>
                        <td>
                            <i class="fas fa-edit mr-1"></i>
                            <a href="{$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=3_2_1&seguranca={$idEquipamentoCriptografada}">
                                 Alterar
                            </a>
                        </td>

                    </tr>
HTML;
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Patrimônio</th>
                    <th>Categoria</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Etiqueta de Serviço</th>
                    <th>Número de Série</th>
                    <th>Tensão de Entrada</th>
                    <th>Corrente de Entrada</th>
                    <th>Sistema Operacional</th>
                    <th>Ambiente</th>
                    <th>Alterar</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<script>
    $(function () {
        $("#tabelaMenu3_2").DataTable({
            language:{
                url: "https://itapoa.app.br/vendor/dataTable_pt_br/dataTable_pt_br.json"
            },
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "aaSorting": [0, "asc"]
        }).buttons().container().appendTo('#tabelaMenu3_2_wrapper .col-md-6:eq(0)');        
    });
</script>