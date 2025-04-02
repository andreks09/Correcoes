<?php

use App\sistema\acesso\{
    sConfiguracao,
    sNotificacao,
    sCargo
};

$sConfiguracao = new sConfiguracao();
   
if(isset($_POST['cargo'])){
    $idCargo = $_POST['cargo'];
    $sCargo = new sCargo($idCargo);
    $sCargo->consultar('tMenu5_2_1.php');
}else{
    $idCargo = $_GET['cargo'];
    $sCargo = new sCargo($idCargo);
    $sCargo->consultar('tMenu5_2_1.php');
}

//retorno de campo inválidos para notificação
if (isset($_GET['campo'])) {
    $sNotificacao = new sNotificacao($_GET['codigo']);
    switch ($_GET['campo']) {
        case 'cargo':
            if ($_GET['codigo'] == 'S1') {
                $alertaCargo = ' is-valid';
            } else {
                $alertaCargo = ' is-warning';
            }
            break;
        default:
            break;
    }

    //cria as variáveis da notificação
    $tipo = $sNotificacao->getTipo();
    $titulo = $sNotificacao->getTitulo();
    $email = $sNotificacao->getMensagem();
}
?>
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Etapa 2 - Cargo/ Função</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- form start -->
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-2">
                            <label for="cargo">Cargo/ Função</label>
                            <input class="form-control<?php echo isset($alertaCargo) ? $alertaCargo: ''; ?>" type="text" name="cargo" id="cargo" value="<?php echo isset($idCargo) ? $sCargo->getNomenclatura() : ''; ?>"  form="form1_tMenu5_2_1" required="">
                        </div>                        
                    </div>
                </div>
                <?php
                    if(isset($tipo) && isset($titulo) && isset($email)){
                    echo <<<HTML
                    <div class="col-mb-3">
                        <div class="card card-outline card-{$tipo}">
                            <div class="card-header">
                                <h3 class="card-title">{$titulo}</h3>
                            </div>
                            <div class="card-body">
                                {$email}
                            </div>
                        </div>
                    </div>
HTML;
                    }       
                    ?>
                <form action="<?php echo $sConfiguracao->getDiretorioControleSuporte(); ?>sAlterarCargo.php" name="form1_tMenu5_2_1" id="form1_tMenu5_2_1" method="post" enctype="multipart/form-data">
                    <!-- /.card-body-->
                    <div class="card-footer">
                        <input type="hidden" name="pagina" value="menu5_2_1">
                        <input type="hidden" name="idCargo" value="<?php echo $idCargo; ?>">
                        <input type="hidden" name="acao" value="alterar">
                        <button type="submit" class="btn btn-primary">Alterar</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.card -->
    </div>    
</div>