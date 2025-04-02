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
    $tipo = $sNotificacao->getTipo();
    $titulo = $sNotificacao->getTitulo();
    $mensagem = $sNotificacao->getMensagem();
}

$sEquipamento = new sEquipamento();
$sEquipamento->consultar('tMenu2_1.php');

$sCategoria = new sCategoria();
$sCategoria->consultar('tMenu2_1.php');
?>
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Etapa 1 - Equipamento</h3>
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
                            if (isset($alertaCategoria) ||
                                isset($alertaEquipamento) ||
                                isset($alertaSistema)) {
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
                        }
                    ?>
                    <div class="ocultarTabelaMenu2_1" id="ocultarTabelaMenu2_1" name="ocultarTabelaMenu2_1">
                        <table class="table table-bordered table-striped" name="tabelaMenu2_1" id="tabelaMenu2_1">
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
                                            $sModelo->consultar('tMenu2_1.php');

                                            if ($sModelo->getValidador()) {
                                                foreach ($sModelo->mConexao->getRetorno() as $value) {
                                                    $modelo = $value['nomenclatura'];
                                                    $idMarca = $value['marca_idmarca'];
                                                }
                                            }

                                            //busca os dados da marca de acordo com sua id
                                            $sMarca = new sMarca();
                                            $sMarca->setNomeCampo('idmarca');
                                            $sMarca->setValorCampo($idMarca);
                                            $sMarca->consultar('tMenu2_1.php');

                                            if ($sMarca->getValidador()) {
                                                foreach ($sMarca->mConexao->getRetorno() as $value) {
                                                    $marca = $value['nomenclatura'];
                                                }
                                            }

                                            //busca os dados da categoria de acordo com sua id
                                            $sCategoriaTabela = new sCategoria();
                                            $sCategoriaTabela->setNomeCampo('idcategoria');
                                            $sCategoriaTabela->setValorCampo($idCategoria);
                                            $sCategoriaTabela->consultar('tMenu2_1.php-tabela');

                                            if ($sCategoria->getValidador()) {
                                                foreach ($sCategoria->mConexao->getRetorno() as $value) {
                                                    if($idCategoria == $value['idcategoria']){
                                                        $categoria = $value['nomenclatura'];
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
                <form action="<?php echo $sConfiguracao->getDiretorioVisualizacaoAcesso(); ?>tPainel.php?menu=2_1_1" method="post" enctype="multipart/form-data" name="f1" id="f1">
                    <input type="hidden" value="f1" name="formulario" id="formulario" form="f1">
                    <input type="hidden" value="inserir" name="acao" id="inserir" form="f1">
                    <input type="hidden" value="menu2_1" name="pagina" id="menu2_1" form="f1">

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Próximo</button>
                    </div>
                </form>
                <!-- /.card -->
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#patrimonio').on('click', function () {
            $("#ocultarTabelaMenu2_1").toggle(this.checked);
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
        $("#tabelaMenu2_1").DataTable({
            language:{
                url: "https://itapoa.app.br/vendor/dataTable_pt_br/dataTable_pt_br.json"
            },
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#tabelaMenu2_1_wrapper .col-md-6:eq(0)');        
    });
</script>