<?php

use App\sistema\acesso\{
    sConfiguracao,
    sNotificacao
};
use App\sistema\suporte\{
    sMarca,
    sCategoria,
    sTensao,
    sCorrente,
    sSistemaOperacional,
    sLocal,
    sAmbiente
};

//instancia classes para manipulação dos dados
$sConfiguracao = new sConfiguracao();

$sMarca = new sMarca();
$sMarca->consultar('tMenu3_1.php-f4');

$sCategoria = new sCategoria();
$sCategoria->consultar('tMenu3_1.php-f1');

$sTensao = new sTensao();
$sTensao->consultar('tMenu3_1.php-f1');

$sCorrente = new sCorrente();
$sCorrente->consultar('tMenu3_1.php-f1');

$sSistemaOperacional = new sSistemaOperacional();
$sSistemaOperacional->consultar('tMenu3_1.php-f1');

$sAmbiente = new sAmbiente();
$sAmbiente->consultar('tMenu3_1.php');

$sLocal = new sLocal(0);
$sLocal->consultar('tMenu3_1.php');

//retorno de campo inválidos para notificação
if (isset($_GET['campo'])) {
    $sNotificacao = new sNotificacao($_GET['codigo']);
    switch ($_GET['campo']) {
        case 'patrimonioF1':
            if ($_GET['codigo'] == 'S4') {
                $alertaPatrimonioF1 = ' is-valid';
            } else {
                $alertaPatrimonioF1 = ' is-warning';
            }
            break;
        case 'categoriaF1':
            if ($_GET['codigo'] == 'S4') {
                $alertaCategoriaF1 = ' is-valid';
            } else {
                $alertaCategoriaF1 = ' is-warning';
            }
            break;
        case 'marcaF1':
            if ($_GET['codigo'] == 'S4') {
                $alertaMarcaF1 = ' is-valid';
            } else {
                $alertaMarcaF1 = ' is-warning';
            }
            break;
        case 'modeloF1':
            if ($_GET['codigo'] == 'S4') {
                $alertaModeloF1 = ' is-valid';
            } else {
                $alertaModeloF1 = ' is-warning';
            }
            break;
        case 'serviceTagF1':
            if ($_GET['codigo'] == 'S4') {
                $alertaServiceTagF1 = ' is-valid';
            } else {
                $alertaServiceTagF1 = ' is-warning';
            }
            break;
        case 'numeroDeSerieF1':
            if ($_GET['codigo'] == 'S4') {
                $alertaNumeroDeSerieF1 = ' is-valid';
            } else {
                $alertaNumeroDeSerieF1 = ' is-warning';
            }
            break;
        case 'tensaoF1':
            if ($_GET['codigo'] == 'S4') {
                $alertaTensaoF1 = ' is-valid';
            } else {
                $alertaTensaoF1 = ' is-warning';
            }
            break;
        case 'correnteF1':
            if ($_GET['codigo'] == 'S4') {
                $alertaCorrenteF1 = ' is-valid';
            } else {
                $alertaCorrenteF1 = ' is-warning';
            }
            break;
        case 'sistemaOperacionalF1':
            if ($_GET['codigo'] == 'S4') {
                $alertaSistemaOperacionalF1 = ' is-valid';
            } else {
                $alertaSistemaOperacionalF1 = ' is-warning';
            }
            break;
        case 'ambienteF1':
            if ($_GET['codigo'] == 'S4') {
                $alertaAmbienteF1 = ' is-valid';
            } else {
                $alertaAmbienteF1 = ' is-warning';
            }
            break;
        case 'localF1':
            if ($_GET['codigo'] == 'S4') {
                $alertaLocalF1 = ' is-valid';
            } else {
                $alertaLocalF1 = ' is-warning';
            }
            break;
        case 'categoriaF2':
            if ($_GET['codigo'] == 'S4') {
                $alertaCategoriaF2 = ' is-valid';
            } else {
                $alertaCategoriaF2 = ' is-warning';
            }
            break;
        case 'marcaF3':
            if ($_GET['codigo'] == 'S4') {
                $alertaMarcaF3 = ' is-valid';
            } else {
                $alertaMarcaF3 = ' is-warning';
            }
            break;
        case 'modeloF4':
            if ($_GET['codigo'] == 'S4') {
                $alertaModeloF4 = ' is-valid';
            } else {
                $alertaModeloF4 = ' is-warning';
            }
            break;
        case 'tensaoF5':
            if ($_GET['codigo'] == 'S4') {
                $alertaTensaoF5 = ' is-valid';
            } else {
                $alertaTensaoF5 = ' is-warning';
            }
            break;
        case 'correnteF6':
            if ($_GET['codigo'] == 'S4') {
                $alertaCorrenteF6 = ' is-valid';
            } else {
                $alertaCorrenteF6 = ' is-warning';
            }
            break;
        case 'sistemaOperacionalF7':
            if ($_GET['codigo'] == 'S4') {
                $alertaSistemaOperacionalF7 = ' is-valid';
            } else {
                $alertaSistemaOperacionalF7 = ' is-warning';
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
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Equipamento</h3>
                </div>
                <!-- form start -->
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-1">
                            <label for="Patrimonio">Patrimônio *</label> <a href="<?php echo $sConfiguracao->getDiretorioControleAcesso(); ?>tFAQ.php" target="_blank"><i class="fas fa-info-circle text-primary mr-1"></i></a>
                            <input class="form-control <?php echo isset($alertaPatrimonioF1) ? $alertaPatrimonioF1 : ''; ?>" type="text" id="patrimonioF1" name="patrimonioF1" placeholder="Ex.: 20580" form="f1" required="">
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Categoria *</label>
                                <select class="form-control <?php echo isset($alertaCategoriaF1) ? $alertaCategoriaF1 : ''; ?>" name="categoriaF1" id="categoriaF1" form="f1" required="">
                                <option value="0" selected="">--</option>
                                <?php
                                if ($sCategoria->getValidador()) {
                                    foreach ($sCategoria->mConexao->getRetorno() as $value) {
                                        echo '<option value="' . $value['idcategoria'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                    }
                                }
                                ?>
                                </select>
                            </div>
                        </div>                           
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Marca *</label>
                                <select class="form-control<?php echo isset($alertaMarcaF1) ? $alertaMarcaF1 : ''; ?>" name="marcaF1" id="marcaF1" form="f1" required="">
                                    <option value="0" selected="">--</option>
                                <?php
                                if ($sMarca->getValidador()) {
                                    foreach ($sMarca->mConexao->getRetorno() as $value) {
                                        echo '<option value="' . $value['idmarca'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                    }
                                }
                                ?>
                                </select>
                            </div>
                        </div>   
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Modelo</label>
                                <select class="form-control<?php echo isset($alertaModeloF1) ? $alertaModeloF1 : ''; ?>" name="modeloF1" id="modeloF1" form="f1">
                                    <option value="0" selected="">--</option>
                                </select>
                            </div>
                        </div>  
                        <div class="form-group col-md-2">
                            <label for="etiquetaF1">Etiqueta de Serviço</label> <a href="../acesso/tFAQ.php" target="_blank"><i class="fas fa-info-circle text-primary mr-1"></i></a>
                            <input class="form-control" type="text" name="etiquetaF1" id="etiquetaF1" placeholder="Ex.: 5VPMLY3" form="f1">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="serieF1">Número de Série</label> <a href="../acesso/tFAQ.php" target="_blank"><i class="fas fa-info-circle text-primary mr-1"></i></a>
                            <input class="form-control" type="text" name="serieF1" id="serieF1" placeholder="Ex.: 8498732518" form="f1">
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Tensão *</label>
                                <select class="form-control<?php echo isset($alertaTensaoF1) ? $alertaTensaoF1 : ''; ?>" name="tensaoF1" id="tensaoF1" form="f1" required="">
                                    <option value="0" selected="">--</option>
                                    <?php
                                    if ($sTensao->getValidador()) {
                                        foreach ($sTensao->mConexao->getRetorno() as $value) {
                                            echo '<option value="' . $value['idtensao'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>  
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Corrente *</label>
                                <select class="form-control<?php echo isset($alertaCorrenteF1) ? $alertaCorrenteF1 : ''; ?>" name="correnteF1" id="correnteF1" form="f1" required="">
                                    <option value="0" selected="">--</option>
                                    <?php
                                    if ($sCorrente->getValidador()) {
                                        foreach ($sCorrente->mConexao->getRetorno() as $value) {
                                            echo '<option value="' . $value['idcorrente'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>  
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Sistema Operacional *</label>
                                <select class="form-control<?php echo isset($alertaSistemaOperacionalF1) ? $alertaSistemaOperacionalF1 : ''; ?>" name="sistemaOperacionalF1" id="sistemaOperacionalF1" form="f1" required="">
                                    <option value="0" selected="">--</option>
                                    <?php
                                    if ($sSistemaOperacional->getValidador()) {
                                        foreach ($sSistemaOperacional->mConexao->getRetorno() as $value) {
                                            echo '<option value="' . $value['idsistemaOperacional'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div> 
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>Ambiente *</label>
                                <select class="form-control<?php echo isset($alertaAmbienteF1) ? $alertaAmbienteF1 : ''; ?>" name="ambienteF1" id="ambienteF1" form="f1" required="">
                                    <option value="0" selected="">--</option>
                                    <?php
                                    if ($sAmbiente->getValidador()) {
                                        foreach ($sAmbiente->mConexao->getRetorno() as $value) {
                                            echo '<option value="' . $value['idambiente'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>  
                    </div>
                </div>
<?php
if (isset($tipo) &&
    isset($titulo) &&
    isset($mensagem)) {
    if (isset($alertaPatrimonioF1) ||
        isset($alertaCategoriaF1) ||
        isset($alertaMarcaF1) ||
        isset($alertaModeloF1) ||
        isset($alertaServiceTagF1) ||
        isset($alertaNumeroSerieF1) ||
        isset($alertaTensaoF1) ||
        isset($alertaCorrenteF1) ||
        isset($alertaSistemaOperacionalF1) ||
        isset($alertaAmbienteF1) ||
        isset($alertaLocalF1)) {
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
                <form action="<?php echo $sConfiguracao->getDiretorioControleSuporte(); ?>sRegistrarEquipamento.php" id="f1" name="f1" method="post" enctype="multipart/form-data">
                    <!-- /.card-body-->
                    <div class="card-footer">
                        <input type="hidden" value="f1" name="formulario" form="f1">
                        <input type="hidden" value="inserir" name="acaoF1" form="f1">
                        <input type="hidden" value="menu3_1" name="paginaF1" form="f1">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.card -->
    </div>
    <div class="row">
        <!-- left column -->
        <div class="col-md-2">
            <!-- general form elements -->
            <div class="card card-outline card-primary <?php echo isset($alertaCategoriaF2) ? '' : 'collapsed-card' ?>">
                <div class="card-header">
                    <h3 class="card-title">Registrar Categoria</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas <?php echo isset($alertaCategoriaF2) ? 'fa-minus' : 'fa-plus' ?>"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- form start -->                
                <div class="card-body">
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="categoria">Categoria</label>
                            <input class="form-control<?php echo isset($alertaCategoriaF2) ? $alertaCategoriaF2 : ''; ?>" type="text" name="categoriaF2" id="categoriaF2" placeholder="Ex.: Impressora" form="f2" required="">
                        </div>
                    </div>
                </div>
<?php
if (isset($tipo) &&
        isset($titulo) &&
        isset($mensagem)) {
    if (isset($alertaCategoriaF2)) {
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
                <form action="<?php echo $sConfiguracao->getDiretorioControleSuporte(); ?>sRegistrarEquipamento.php" method="post" id="f2" enctype="multipart/form-data">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <input type="hidden" value="f2" name="formulario" form="f2">
                        <input type="hidden" value="inserir" name="acaoF2" form="f2">
                        <input type="hidden" value="menu3_1" name="paginaF2" form="f2">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-2">
            <!-- general form elements -->
            <div class="card card-outline card-primary <?php echo isset($alertaMarcaF3) ? '' : 'collapsed-card' ?>">
                <div class="card-header">
                    <h3 class="card-title">Registrar Marca</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas <?php echo isset($alertaMarcaF3) ? 'fa-minus' : 'fa-plus' ?>"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- form start -->   
                <div class="card-body">
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="marca">Marca</label>
                            <input class="form-control<?php echo isset($alertaMarcaF3) ? $alertaMarcaF3 : ''; ?>" type="text" name="marcaF3" id="marcaF3" placeholder="Ex.: Dell" form="f3" required="">
                        </div>
                    </div>
                </div>
    <?php
    if (isset($tipo) &&
            isset($titulo) &&
            isset($mensagem)) {
        if (isset($alertaMarcaF3)) {
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
                    <form action="<?php echo $sConfiguracao->getDiretorioControleSuporte(); ?>sRegistrarEquipamento.php" method="post" id="f3" enctype="multipart/form-data">
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <input type="hidden" value="f3" name="formulario" form="f3">
                            <input type="hidden" value="inserir" name="acaoF3" form="f3">
                            <input type="hidden" value="menu3_1" name="paginaF3" form="f3">
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-2">
                <!-- general form elements -->
                <div class="card card-outline card-primary <?php echo isset($alertaModeloF4) ? '' : 'collapsed-card' ?>">
                    <div class="card-header">
                        <h3 class="card-title">Registrar Modelo</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas <?php echo isset($alertaModeloF4) ? 'fa-minus' : 'fa-plus' ?>"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- form start -->                
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Marca</label>
                                    <select class="form-control" name="marcaF4" id="marcaF4" form="f4">
                                        <?php
                                        if ($sMarca->getValidador()) {
                                            foreach ($sMarca->mConexao->getRetorno() as $value) {
                                                echo '<option value="' . $value['idmarca'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            }
                                        } else {
                                            echo '<option value="0" selected="">--</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">                      
                            <div class="form-group col-md-12">
                                <label for="modelo">Modelo</label>
                                <input class="form-control<?php echo isset($alertaModeloF4) ? $alertaModeloF4 : ''; ?>" type="text" name="modeloF4" id="modeloF4" placeholder="Ex.: OptiPlex 3000" form="f4" required="">
                            </div>
                        </div>
                    </div>
<?php
if (isset($tipo) &&
        isset($titulo) &&
        isset($mensagem)) {
    if (isset($alertaModeloF4)) {
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
                    <form action="<?php echo $sConfiguracao->getDiretorioControleSuporte(); ?>sRegistrarEquipamento.php" method="post" id="f4" enctype="multipart/form-data">
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <input type="hidden" value="f4" name="formulario" form="f4">
                            <input type="hidden" value="inserir" name="acaoF4" form="f4">
                            <input type="hidden" value="menu3_1" name="paginaF4" form="f4">
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-2">
                <!-- general form elements -->
                <div class="card card-outline card-primary <?php echo isset($alertaTensaoF5) ? '' : 'collapsed-card' ?>">
                    <div class="card-header">
                        <h3 class="card-title">Registrar Tensão</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas <?php echo isset($alertaTensaoF5) ? 'fa-minus' : 'fa-plus' ?>"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- form start -->                
                    <div class="card-body">
                        <div class="row">                      
                            <div class="form-group col-md-12">
                                <label for="tensao">Tensão</label>
                                <input class="form-control<?php echo isset($alertaTensaoF5) ? $alertaTensaoF5 : ''; ?>" type="text" name="tensaoF5" id="tensaoF5" placeholder="Ex.: 19.5v" form="f5" required="">
                            </div>
                        </div>
                    </div>
<?php
if (isset($tipo) &&
        isset($titulo) &&
        isset($mensagem)) {
    if (isset($alertaTensaoF5)) {
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
                    <form action="<?php echo $sConfiguracao->getDiretorioControleSuporte(); ?>sRegistrarEquipamento.php" method="post" id="f5" enctype="multipart/form-data">
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <input type="hidden" value="f5" name="formulario" form="f5">
                            <input type="hidden" value="inserir" name="acaoF5" form="f5">
                            <input type="hidden" value="menu3_1" name="paginaF5" form="f5">
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-2">
                <!-- general form elements -->
                <div class="card card-outline card-primary <?php echo isset($alertaCorrenteF6) ? '' : 'collapsed-card' ?>">
                    <div class="card-header">
                        <h3 class="card-title">Registrar Corrente</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas <?php echo isset($alertaCorrenteF6) ? 'fa-minus' : 'fa-plus' ?>"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- form start -->                
                    <div class="card-body">
                        <div class="row">                      
                            <div class="form-group col-md-12">
                                <label for="corrente">Corrente</label>
                                <input class="form-control<?php echo isset($alertaCorrenteF6) ? $alertaCorrenteF6 : ''; ?>" type="text" name="correnteF6" id="correnteF6" placeholder="Ex.: 3.42a" form="f6" required="">
                            </div>
                        </div>
                    </div>
<?php
if (isset($tipo) &&
        isset($titulo) &&
        isset($mensagem)) {
    if (isset($alertaCorrenteF6)) {
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
                    <form action="<?php echo $sConfiguracao->getDiretorioControleSuporte(); ?>sRegistrarEquipamento.php" method="post" id="f6" enctype="multipart/form-data">
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <input type="hidden" value="f6" name="formulario" form="f6">
                            <input type="hidden" value="inserir" name="acaoF6" form="f6">
                            <input type="hidden" value="menu3_1" name="paginaF6" form="f6">
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-2">
                <!-- general form elements -->
                <div class="card card-outline card-primary <?php echo isset($alertaSistemaOperacionalF7) ? '' : 'collapsed-card' ?>">
                    <div class="card-header">
                        <h3 class="card-title">Registrar Sistema Operacional</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas <?php echo isset($alertaSistemaOperacionalF7) ? 'fa-minus' : 'fa-plus' ?>"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <!-- form start -->                
                    <div class="card-body">
                        <div class="row">                      
                            <div class="form-group col-md-12">
                                <label for="sistemaOperacional">Sistema Operacional</label>
                                <input class="form-control<?php echo isset($alertaSistemaOperacionalF7) ? $alertaSistemaOperacionalF7 : ''; ?>" type="text" name="sistemaOperacionalF7" id="sistemaOperacionalF7" placeholder="Ex.: Windows 11 Professional Edition" form="f7" required="">
                            </div>
                        </div>
                    </div>
<?php
if (isset($tipo) &&
        isset($titulo) &&
        isset($mensagem)) {
    if (isset($alertaSistemaOperacionalF7)) {
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
                    <form action="<?php echo $sConfiguracao->getDiretorioControleSuporte(); ?>sRegistrarEquipamento.php" method="post" id="f7" enctype="multipart/form-data">
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <input type="hidden" value="f7" name="formulario" form="f7">
                            <input type="hidden" value="inserir" name="acaoF7" form="f7">
                            <input type="hidden" value="menu3_1" name="paginaF7" form="f7">
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card -->
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
