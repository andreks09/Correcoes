<?php
use App\sistema\acesso\{
    sConfiguracao,
    sNotificacao
};

$sConfiguracao = new sConfiguracao();

//retorno de campo inválidos para notificação
if(isset($_GET['campo'])){
    $sNotificacao = new sNotificacao($_GET['codigo']);
    switch ($_GET['campo']) {
        case 'cargo':
            if($_GET['codigo'] == 'S4'){
                $alertaCargo = ' is-valid';
            }else{
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
                        <div class="form-group col-md-2">
                            <label for="cargo">Cargo/ Função</label>
                            <input class="form-control<?php echo isset($alertaCargo) ? $alertaCargo: ''; ?>" type="text" id="cargo" name="cargo" placeholder="Ex.: Diretor" required="" form="form1_tMenu5_1">
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
                <form id="form1_tMenu5_1" name="form1_tMenu5_1" action="<?php echo $sConfiguracao->getDiretorioControleSuporte(); ?>sRegistrarCargo.php" method="post" enctype="multipart/form-data">
                    <!-- /.card-body-->
                    <div class="card-footer">
                        <input name="pagina" type="hidden" value="menu5_1" form="form1_tMenu5_1">
                        <input name="acao" type="hidden" value="inserir" form="form1_tMenu5_1">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>