<?php
use App\sistema\acesso\{
    sConfiguracao,
    sNotificacao,
    sSair
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

if(isset($_GET['seguranca'])){
    //busca os dados do equipamento
    $idEquipamento = base64_decode($_GET['seguranca']);
    
    $sEquipamento = new sEquipamento();
    $sEquipamento->setNomeCampo('idequipamento');
    $sEquipamento->setValorCampo($idEquipamento);
    $sEquipamento->consultar('tMenu3_2_1.php');
    
    foreach ($sEquipamento->mConexao->getRetorno() as $value) {
        $patrimonio = $value['patrimonio'];
        $idCategoria = $value['categoria_idcategoria'];
        $idModelo = $value['modelo_idmodelo'];
        $etiqueta = $value['etiquetaDeServico'];
        $serie = $value['numeroDeSerie'];
        $idTensao = $value['tensao_idtensao'];
        $idCorrente = $value['corrente_idcorrente'];
        $idSistemaOperacional = $value['sistemaOperacional_idsistemaOperacional'];
        $idAmbiente = $value['ambiente_idambiente'];
    }
    
    //busca a categoria do equipamento
    $sCategoria = new sCategoria();
    $sCategoria->consultar('tMenu3_2_1.php');
    
    //busca o modelo do equipamento
    $sModelo = new sModelo();
    $sModelo->setNomeCampo('idmodelo');
    $sModelo->setValorCampo($idModelo);
    $sModelo->consultar('tMenu3_2_1.php');
    
    foreach ($sModelo->mConexao->getRetorno() as $value) {
        $idMarca = $value['marca_idmarca'];
    }  
    
    //busca o marca do equipamento
    $sMarca = new sMarca();
    $sMarca->consultar('tMenu3_2_1.php');
    
    //busca a tensao do equipamento
    $sTensao = new sTensao();
    $sTensao->consultar('tMenu3_2_1.php');
    
    //busca a corrente do equipamento
    $sCorrente = new sCorrente();
    $sCorrente->consultar('tMenu3_2_1.php');
    
    //busca a sistemaOperacional do equipamento
    $sSistemaOperacional = new sSistemaOperacional();
    $sSistemaOperacional->consultar('tMenu3_2_1.php');
    
    //busca a ambiente do equipamento
    $sAmbiente = new sAmbiente();
    $sAmbiente->consultar('tMenu3_2_1.php');
}else{
    //solicitar saída com tentativa de violação
    $sSair = new sSair();
    $sSair->verificar('0');
}

?>
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Etapa 2 - Alterar</h3>
                </div>
                <!-- form start -->
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Patrimônio</label>
                                <select class="form-control" name="equipamento" id="equipamento" form="f1">
                                    <?php
                                    foreach ($sEquipamento->mConexao->getRetorno() as $value) {
                                        $idEquipamento == $value['idequipamento'] ? $atributo = 'selected=""' : $atributo = '';
                                        echo '<option value="' . $value['idequipamento'] . '"' . $atributo . ' >' . $value['patrimonio'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>          
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Categoria</label>
                                <select class="form-control" name="categoria" id="categoria" form="f1">
                                    <?php
                                    foreach ($sCategoria->mConexao->getRetorno() as $value) {
                                        $idCategoria == $value['idcategoria'] ? $atributo = 'selected=""' : $atributo = '';
                                        echo '<option value="' . $value['idcategoria'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>                            
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Marca</label>
                                <select class="form-control" name="marcaF1" id="marcaF1" form="f1">
                                    <?php
                                    foreach ($sMarca->mConexao->getRetorno() as $value) {
                                        $idMarca == $value['idmarca'] ? $atributo = 'selected=""' : $atributo = '';
                                        echo '<option value="' . $value['idmarca'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>      
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Modelo</label>
                                <select class="form-control" name="modeloF1" id="modeloF1" form="f1">
                                    <?php
                                    foreach ($sModelo->mConexao->getRetorno() as $value) {
                                        $idModelo == $value['idmodelo'] ? $atributo = 'selected=""' : $atributo = '';
                                        echo '<option value="' . $value['idmodelo'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>   
                        <div class="form-group col-md-2">
                            <label for="etiqueta">Etiqueta de Serviço</label> <a href="../acesso/tFAQ.php" target="_blank"><i class="fas fa-info-circle text-primary mr-1"></i></a>
                            <input type="text" class="form-control" value="<?php echo $etiqueta ?>" name="etiqueta" id="etiqueta" form="f1">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="serie">Número de Série</label> <a href="../acesso/tFAQ.php" target="_blank"><i class="fas fa-info-circle text-primary mr-1"></i></a>
                            <input type="text" class="form-control" value="<?php echo $serie ?>" name="serie" id="serie" form="f1">
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Tensao</label>
                                <select class="form-control" name="tensao" id="tensao" form="f1">
                                    <?php
                                    foreach ($sTensao->mConexao->getRetorno() as $value) {
                                        $idTensao == $value['idtensao'] ? $atributo = 'selected=""' : $atributo = '';
                                        echo '<option value="' . $value['idtensao'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>  
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Corrente</label>
                                <select class="form-control" name="corrente" id="corrente" form="f1">
                                    <?php
                                    foreach ($sCorrente->mConexao->getRetorno() as $value) {
                                        $idCorrente == $value['idcorrente'] ? $atributo = 'selected=""' : $atributo = '';
                                        echo '<option value="' . $value['idcorrente'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>  
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Sistema Operacional</label>
                                <select class="form-control" name="sistemaOperacional" id="sistemaOperacional" form="f1">
                                    <?php
                                    foreach ($sSistemaOperacional->mConexao->getRetorno() as $value) {
                                        $idSistemaOperacional == $value['idsistemaOperacional'] ? $atributo = 'selected=""' : $atributo = '';
                                        echo '<option value="' . $value['idsistemaOperacional'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>  
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Ambiente</label>
                                <select class="form-control" name="ambienteF1" id="ambienteF1" form="f1">
                                    <?php
                                    foreach ($sAmbiente->mConexao->getRetorno() as $value) {
                                        $idAmbiente == $value['idambiente'] ? $atributo = 'selected=""' : $atributo = '';
                                        echo '<option value="' . $value['idambiente'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>  
                    </div>
                </div>
                <!-- /.card-body -->
                <form action="#" method="post" enctype="multipart/form-data" name="f1" id="f1">
                    <div class="card-footer">
                        <?php
                        if($idEquipamento != 6){
                            echo "<button type=\"submit\" class=\"btn btn-primary\">Alterar</button>";
                        }else{
                            $sNotificacao = new sNotificacao("A33");
                            //cria as variáveis da notificação
                            $tipo = $sNotificacao->getTipo();
                            $titulo = $sNotificacao->getTitulo();
                            $mensagem = $sNotificacao->getMensagem();
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
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
    <div class="row">
        <!-- left column -->
        <div class="col-md-2">
            <!-- general form elements -->
            <div class="card card-outline card-primary collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Categoria</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- form start -->                
                <div class="card-body">
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="categoria">Nomenclatura</label>
                            <input type="text" class="form-control" name="categoria" placeholder="Ex.: Impressora">
                            <input type="hidden" value="categoria" name="opcao" form="categoria">
                            <input type="hidden" value="menu3_2_1" name="pagina" form="categoria">
                        </div>
                    </div>
                </div>
                <form action="../../sistema/suporte/sRegistrarEquipamento.php" method="post" id="categoria" enctype="multipart/form-data">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-2">
            <!-- general form elements -->
            <div class="card card-outline card-primary collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Marca</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- form start -->

                <div class="card-body">
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="marca">Nomenclatura</label>
                            <input type="text" class="form-control" name="marca" placeholder="Ex.: Dell" form="marca">
                            <input type="hidden" value="marca" name="opcao" form="marca">
                            <input type="hidden" value="menu3_2_1" name="pagina" form="marca">
                        </div>
                    </div>
                </div>
                <form action="../../sistema/suporte/sRegistrarEquipamento.php" id="marca" method="post" enctype="multipart/form-data">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-2">
            <!-- general form elements -->
            <div class="card card-outline card-primary collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Modelo</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- form start -->

                <div class="card-body">
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="modelo">Nomenclatura</label>
                            <input type="text" class="form-control" name="modelo" placeholder="Ex.: OptiLex 3000" form="modelo">
                            <input type="hidden" value="modelo" name="opcao" form="modelo">
                            <input type="hidden" value="menu3_2_1" name="pagina" form="modelo">
                        </div>
                    </div>
                </div>
                <form action="../../sistema/suporte/sRegistrarEquipamento.php" id="modelo" method="post" enctype="multipart/form-data">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-2">
            <!-- general form elements -->
            <div class="card card-outline card-primary collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Tensão de Entrada</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- form start -->

                <div class="card-body">
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="tensao">Nomenclatura</label>
                            <input type="text" class="form-control" name="tensao" placeholder="Ex.: 19V">
                            <input type="hidden" value="tensaoDeEntrada" name="opcao" form="tensaoDeEntrada">
                            <input type="hidden" value="menu3_2_1" name="pagina" form="tensaoDeEntrada">
                        </div>
                    </div>
                </div>
                <form action="../../sistema/suporte/sRegistrarEquipamento.php" id="tensaoDeEntrada" method="post" enctype="multipart/form-data">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-2">
            <!-- general form elements -->
            <div class="card card-outline card-primary collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Corrente de Entrada</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- form start -->

                <div class="card-body">
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="corrente">Nomenclatura</label>
                            <input type="text" class="form-control" name="correnteDeEntrada" placeholder="Ex.: 3,95A" form="correnteDeEntrada">
                            <input type="hidden" value="correnteDeEntrada" name="opcao" form="correnteDeEntrada">
                            <input type="hidden" value="menu3_2_1" name="pagina" form="correnteDeEntrada">
                        </div>
                    </div>
                </div>
                <form action="../../sistema/suporte/sRegistrarEquipamento.php" id="correnteDeEntrada" method="post" enctype="multipart/form-data">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-2">
            <!-- general form elements -->
            <div class="card card-outline card-primary collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">Sistema Operacional</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- form start -->
                <div class="card-body">
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="sistemaOperacional">Nomenclatura</label>
                            <input type="text" class="form-control" name="sistemaOperacional" placeholder="Ex.: Windows 11" form="sistemaOperacional">
                            <input type="hidden" value="sistemaOperacional" name="opcao" form="sistemaOperacional">
                            <input type="hidden" value="menu3_2_1" name="pagina" form="sistemaOperacional">
                        </div>
                    </div>
                </div>
                <form action="../../sistema/suporte/sRegistrarEquipamento.php" id="sistemaOperacional" method="post" enctype="multipart/form-data">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.card -->
    </div>
    <div class="row">
        <div class="col-lg-12 col-12">
            <?php
            require_once '../../sistema/acesso/sNotificacao.php';

            if (isset($codigo)) {
                $mensagem = explode('|', $codigo);
                echo <<<HTML
                <div class="col-mb-3">
                    <div class="card card-outline card-{$mensagem[0]}">
                        <div class="card-header">
                            <h3 class="card-title">{$mensagem[1]}</h3>
                        </div>
                        <div class="card-body">
                            {$mensagem[2]}
                        </div>
                    </div>
                </div>
HTML;
            }
            ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        //traz os departamentos de acordo com a secretaria selecionada   
        $('#marcaF1').on('change', function () {
            var idMarca = $(this).val();

            //mostra somente os departamentos da secretaria escolhida
            $.ajax({
                url: 'https://itapoa.app.br/App/sistema/suporte/ajaxModelo.php',
                type: 'POST',
                data: {
                    'idMarca': idMarca
                },
                success: function (html) {
                    $('#modeloF1').html(html);
                }
            });
        });
    });
</script>