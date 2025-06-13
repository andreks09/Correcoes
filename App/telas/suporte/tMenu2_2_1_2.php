<?php
use App\sistema\acesso\{
    sNotificacao
};
use App\sistema\suporte\{
    sEquipamento,
    sCategoria,
    sModelo,
    sMarca
};

//retorno de campo inválidos para notificação
if (isset($_GET['campo'])) {
    $sNotificacao = new sNotificacao($_GET['codigo']);
    switch ($_GET['campo']) {
        case 'categoria':
            if ($_GET['codigo'] == 'S4') {
                $alertaCategoria = ' is-valid';
            } else {
                $alertaCategoria = ' is-warning';
            }
            break;
        case 'equipamento':
            if ($_GET['codigo'] == 'S4') {
                $alertaEquipamento = ' is-valid';
            } else {
                $alertaEquipamento = ' is-warning';
            }
            break;
        case 'sistema':
            if ($_GET['codigo'] == 'S4') {
                $alertaSistema = ' is-valid';
            } else {
                $alertaSistema = ' is-warning';
            }
            break;
    }
    //cria as variáveis da notificação
    $tipo       = $sNotificacao->getTipo();
    $titulo     = $sNotificacao->getTitulo();
    $mensagem   = $sNotificacao->getMensagem();
}

//se clicou em alterar equipamento lá na página tMenu2_2_1 (tickets já abertos)
if( isset($_POST['menu'])){
    if($_POST['menu'] == '2_2_1'){
        $menu                   = $_POST['menu'];
        $idProtocolo            = $_POST['idProtocolo'];
        $idEquipamentoAnterior  = $_POST['idEquipamentoAnterior'];
        $patrimonioAnterior     = $_POST['patrimonioAnterior'];
    }
}

//se retornou com mensagem de erro do controlador
if( isset($_GET['menu']) &&
    !isset($_POST['menu'])){
    if($_GET['menu'] == '2_2_1_2'){
        $menu                   = $_GET['menu'];
        $idProtocolo            = $_GET['idProtocolo'];
        $idEquipamentoAnterior  = $_GET['idEquipamentoAnterior'];
        $patrimonioAnterior     = $_GET['patrimonioAnterior'];
    }
}

$sEquipamento = new sEquipamento();
$sEquipamento->consultar('tMenu2_2_1_2.php');

$sCategoria = new sCategoria();
$sCategoria->consultar('tMenu2_2_1_2.php');
?>
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Etapa 1 - Alterar Equipamento</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">                            
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Localizou o equipamento?</label>
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input class="custom-control-input" type="checkbox" name="patrimonio" id="patrimonio" <?php echo isset($alertaCategoria) ? '' : 'checked=""' ?> onclick="decisao();" form="f1">
                                    <label class="custom-control-label" for="patrimonio">
                                        <div class="conteudo" name="conteudo" id="conteudo">Sim</div>
                                    </label>
                                </div>
                                
                            </div>
                        </div>  
                        <div class="col-md-10">
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="text-success text-xl">
                                </p>
                                <p class="d-flex flex-column text-left">
                                    <span class="text-muted">
                                        Digite o número de Patrimônio do<br />
                                        equipamento, ou o código SELB<br />
                                        para impressoras locadas.
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>      
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
                    <div class="ocultarTabelaMenu2_2_1_2" id="ocultarTabelaMenu2_2_1_2" name="ocultarTabelaMenu2_2_1_2">
                        <table class="table table-bordered table-striped" name="tabelaMenu2_2_1_2" id="tabelaMenu2_2_1_2">
                            <thead>
                                <tr>
                                    <th>Identificação</th>
                                    <th>Equipamento</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <?php 
                                    if($_SESSION['credencial']['nivelPermissao'] > 1){
                                        echo <<<HTML
                                        <th>Etiqueta de Serviço</th>
HTML;
                                    }
                                    ?>                                    
                                    <th>Escolher</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($sEquipamento->getValidador()) {
                                    foreach ($sEquipamento->mConexao->getRetorno() as $value) {
                                        if($value['patrimonio'] != 'Indefinido'){
                                            $idEquipamento = $value['idequipamento'];
                                            $patrimonio = $value['patrimonio'];
                                            $idCategoria = $value['categoria_idcategoria'];
                                            $idModelo = $value['modelo_idmodelo'];
                                            $etiqueta = $value['etiquetaDeServico'];

                                            //busca os dados do modelo de acordo com sua id
                                            $sModelo = new sModelo();
                                            $sModelo->setNomeCampo('idmodelo');
                                            $sModelo->setValorCampo($idModelo);
                                            $sModelo->consultar('tMenu2_2_1_2.php');

                                            if ($sModelo->getValidador()) {
                                                foreach ($sModelo->mConexao->getRetorno() as $valueModelo) {
                                                    $modelo = $valueModelo['nomenclatura'];
                                                    $idMarca = $valueModelo['marca_idmarca'];
                                                }
                                            }

                                            //busca os dados da marca de acordo com sua id
                                            $sMarca = new sMarca();
                                            $sMarca->setNomeCampo('idmarca');
                                            $sMarca->setValorCampo($idMarca);
                                            $sMarca->consultar('tMenu2_2_1_2.php');

                                            if ($sMarca->getValidador()) {
                                                foreach ($sMarca->mConexao->getRetorno() as $valueMarca) {
                                                    $marca = $valueMarca['nomenclatura'];
                                                }
                                            }

                                            //busca os dados da categoria de acordo com sua id
                                            $sCategoriaTabela = new sCategoria();
                                            $sCategoriaTabela->setNomeCampo('idcategoria');
                                            $sCategoriaTabela->setValorCampo($idCategoria);
                                            $sCategoriaTabela->consultar('tMenu2_2_1_2.php-tabela');

                                            if ($sCategoria->getValidador()) {
                                                foreach ($sCategoria->mConexao->getRetorno() as $valueCategoria) {
                                                    if($idCategoria == $valueCategoria['idcategoria']){
                                                        $categoria = $valueCategoria['nomenclatura'];
                                                    }                                                
                                                }
                                            }

                                            echo <<<HTML
                                            <tr>
                                                <td>$patrimonio</td>
                                                <td>$categoria</td>  
                                                <td>$marca</td>
                                                <td>$modelo</td>
HTML;
                                            if($_SESSION['credencial']['nivelPermissao'] > 1){
                                                echo <<<HTML
                                                <td>$etiqueta</td>
HTML;
                                            }
                                            echo <<<HTML
                                                <td>
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input" type="radio" id="idEquipamento{$idEquipamento}" name="idEquipamento" value="$idEquipamento" form="f1">
                                                        <label for="idEquipamento{$idEquipamento}" class="custom-control-label"></label>
                                                    </div>
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
                                    <th>Identificação</th>
                                    <th>Equipamento</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <?php 
                                    if($_SESSION['credencial']['nivelPermissao'] > 1){
                                        echo <<<HTML
                                        <th>Etiqueta de Serviço</th>
HTML;
                                    }
                                    ?>
                                    <th>Escolher</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <form action="<?php echo $sConfiguracao->getDiretorioControleSuporte(); ?>sAlterarPatrimonio.php" method="post" enctype="multipart/form-data" name="f1" id="f1">
                    <input type="hidden" value="alterar" name="acao" id="acao" form="f1">
                    <input type="hidden" value="tMenu2_2_1_2.php" name="pagina" id="tMenu2_2_1_2.php" form="f1"> 
                    <input type="hidden" value="<?php echo $idProtocolo; ?>" name="idProtocolo" id="idProtocolo" form="f1"> 
                    <input type="hidden" value="<?php echo $idEquipamentoAnterior; ?>" name="idEquipamentoAnterior" id="idEquipamentoAnterior" form="f1"> 
                    <input type="hidden" value="<?php echo $patrimonioAnterior; ?>" name="patrimonioAnterior" id="patrimonioAnterior" form="f1"> 
                </form>
                <div class="card-footer">
                        <button type="submit" class="btn btn-primary" form="f1">Alterar</button>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#patrimonio').on('click', function () {
            $("#ocultarTabelaMenu2_2_1_2").toggle(this.checked);
            $("#ocultarCategoria").toggle(!this.checked);
        });
    });
    function decisao(){
       if (document.getElementById('patrimonio').checked) {
            document.getElementById('conteudo').innerHTML = 'Sim';
        } else {
            document.getElementById('conteudo').innerHTML = 'Não';
        }
    }
</script>
<script>
    $(function () {
        $("#tabelaMenu2_2_1_2").DataTable({
            language:{
                url: "https://itapoa.app.br/vendor/dataTable_pt_br/dataTable_pt_br.json"
            },
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#tabelaMenu2_2_1_2wrapper .col-md-6:eq(0)');        
    });
</script>