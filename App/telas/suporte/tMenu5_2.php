<?php

use App\sistema\acesso\{
    sConfiguracao,
    sNotificacao,
    sCargo
};

$sConfiguracao = new sConfiguracao();

$sCargo = new sCargo(0);
$sCargo->consultar('tMenu5_2.php');

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
                    <h3 class="card-title">Etapa 1 - Cargo/ Função</h3>
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Cargo/ Função</label>
                                <select class="form-control<?php echo isset($alertaCargo) ? $alertaCargo: ''; ?>" name="cargo" id="cargo" form="form1_tMenu5_2">
                                        <?php
                                        foreach ($sCargo->mConexao->getRetorno() as $value) {
                                            echo '<option value="' . $value['idcargo'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                        }
                                        ?>
                                    </select>
                            </div>
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
                <form action="<?php echo $sConfiguracao->getDiretorioVisualizacaoAcesso(); ?>tPainel.php?menu=5_2_1" name="form1_tMenu5_2" id="form1_tMenu5_2" method="post" enctype="multipart/form-data">
                    <!-- /.card-body-->
                    <div class="card-footer">
                        <input type="hidden" name="pagina" value="menu5_2" form="form1_tMenu5_2">
                        <button type="submit" class="btn btn-primary">Alterar</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.card -->
    </div>    
</div>