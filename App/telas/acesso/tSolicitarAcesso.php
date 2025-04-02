<?php
require_once '../../../vendor/autoload.php';

use App\sistema\acesso\{
    sConfiguracao,
    sSecretaria,
    sCargo,
    sNotificacao
};

$sConfiguracao = new sConfiguracao();

//retorno de campo inválidos para notificação
if(isset($_GET['campo'])){
    $sNotificacao = new sNotificacao($_GET['codigo']);
    
    //cria as variáveis da notificação
    $tipo = $sNotificacao->getTipo();
    $titulo = $sNotificacao->getTitulo();
    $email = $sNotificacao->getMensagem();
        
    switch ($_GET['campo']) {
        case 'email':
            if($_GET['codigo'] == 'A1'){
                $alertaEmail = ' is-warning';
            }            
            break;
        case 'telefone':
            if($_GET['codigo'] == 'A11'){
                $alertaTelefone = ' is-warning';
            }            
            break;
        default:
            break;
    }    
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $sConfiguracao->getTitle(); ?></title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo $sConfiguracao->getDiretorioPrincipal(); ?>vendor/almasaeed2010/adminlte/plugins/fontawesome-free/css/all.min.css">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="<?php echo $sConfiguracao->getDiretorioPrincipal(); ?>vendor/almasaeed2010/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo $sConfiguracao->getDiretorioPrincipal(); ?>vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css">
    </head>
    <body class="hold-transition register-page">
        <div class="register-box">
            <div class="login-logo">
                <?php echo $sConfiguracao->getLoginLogo(); ?>
            </div>

            <div class="card">
                <div class="card-body register-card-body">
                    <p class="login-box-msg">Solicitar Acesso</p>
                    <form action="<?php echo $sConfiguracao->getDiretorioControleAcesso(); ?>sSolicitarAcesso.php" method="post">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Nome" name="nome" id="nome" required="">
                            <input type="text" class="form-control" placeholder="Sobrenome" name="sobrenome" id="sobrenome" required="">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <select class="form-control" name="sexo" id="sexo" required="">
                                <option selected="" disabled="">Sexo</option>
                                <option value="M">Masculino</option>
                                <option value="F">Feminino</option>
                            </select>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-venus-mars"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input class="form-control<?php echo isset($alertaTelefone) ? $alertaTelefone : ''; ?>" type="text" name="telefone" id="telefone" placeholder="Telefone pessoal" data-inputmask='"mask": "(99) 9 9999-9999"' data-mask inputmode="text">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-phone"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input class="form-control" type="text" value="Permito comunicação via WhatsApp" disabled>
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <input type="checkbox" name="whatsApp" id="whatsApp">
                                </span>
                            </div>                            
                        </div>
                        <div class="input-group mb-3">
                            <input class="form-control<?php echo isset($alertaEmail) ? $alertaEmail : ''; ?>" type="email" placeholder="Email pessoal" name="email" id="email" required="">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>                            
                        <div class="input-group mb-3">
                            <select class="form-control" name="secretaria" id="secretaria" required="">
                                <option selected="" value="1">Secretaria</option>
                                <?php
                                $sSecretaria = new sSecretaria(0);
                                $sSecretaria->consultar('tSolicitarAcesso.php');

                                if($sSecretaria->mConexao->getValidador()){
                                    foreach ($sSecretaria->mConexao->getRetorno() as $key => $value) {
                                        echo "<option value=\"{$value['idsecretaria']}\">{$value['nomenclatura']}</option>";
                                    }
                                }else{
                                    echo "<option value=\"0\" selected=\"\" disabeld=\"\">--</option>";
                                }
                                
                                ?>
                            </select>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-building"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <select class="form-control" name="departamento" id="departamento">
                                <option selected="" value="0">--</option>
                            </select>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-building"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <select class="form-control" name="coordenacao" id="coordenacao">
                                <option selected="" value="0">--</option>
                            </select>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-building"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <select class="form-control" name="setor" id="setor">
                                <option selected="" value="0">--</option>
                            </select>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-building"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <select class="form-control" name="cargo" id="cargo" required="">
                                <?php
                                $sCargo = new sCargo(0);
                                $sCargo->consultar('tSolicitarAcesso.php');

                                if($sCargo->getValidador()){
                                    foreach ($sCargo->mConexao->getRetorno() as $key => $value) {
                                        echo "<option value=\"{$value['idcargo']}\">{$value['nomenclatura']}</option>";
                                    }
                                }else{
                                    echo "<option value=\"0\">--</option>";
                                }
                                
                                ?>
                            </select>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-briefcase"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="icheck-primary">
                                    <input type="checkbox" name="termo" id="termo">
                                    <label for="termo">
                                        Eu aceito os <a href="tTermosDeUso.php">termos de uso</a>
                                    </label>
                                </div>
                            </div>
                        </div>                        
                        <div class="row">
                            <div class="col-12">
                                <input type="hidden" name="acao" id="acao" value="inserir">
                                <input type="hidden" name="pagina" id="pagina" value="tSolicitarAcesso.php">
                                <button class="btn btn-primary btn-block" type="submit" name="solicitar" id="solicitar" disabled>Solicitar</button>
                            </div>
                        </div>
                    </form>
                    <a href="<?php echo $sConfiguracao->getDiretorioVisualizacaoAcesso(); ?>tAcessar.php" class="text-center">Já possuo acesso</a>
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
                <!-- /.form-box -->
            </div><!-- /.card -->
        </div>
        <!-- /.register-box -->

        <!-- jQuery -->
        <script src="<?php echo $sConfiguracao->getDiretorioPrincipal(); ?>vendor/almasaeed2010/adminlte/plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="<?php echo $sConfiguracao->getDiretorioPrincipal(); ?>vendor/almasaeed2010/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="<?php echo $sConfiguracao->getDiretorioPrincipal(); ?>vendor/almasaeed2010/adminlte/dist/js/adminlte.min.js"></script>
        <!--input Customs-->
        <script src="<?php echo $sConfiguracao->getDiretorioPrincipal(); ?>vendor/almasaeed2010/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
        <!--Ajax-->
        <script type="text/javascript" src="<?php echo $sConfiguracao->getDiretorioControleAcesso() ?>jQuery.js"></script>
        <!--jQuery Mask-->
        <script src="<?php echo $sConfiguracao->getDiretorioPrincipal(); ?>vendor/almasaeed2010/adminlte/plugins/inputmask/jquery.inputmask.min.js"></script>
        <!--input Customs-->
        <script src="<?php echo $sConfiguracao->getDiretorioPrincipal(); ?>vendor/almasaeed2010/adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
        <!-- Page specific script -->
        <script>
        $(function(){
           $('[data-mask]').inputmask();
        });
        </script>
        <script>
            $(document).ready(function () {
                //traz os departamentos de acordo com a secretaria selecionada   
                $('#secretaria').on('change', function () {
                    var idSecretaria = $(this).val();

                    //mostra somente os departamentos da secretaria escolhida
                    $.ajax({
                        url: 'https://itapoa.app.br/App/sistema/acesso/ajaxDepartamento.php',
                        type: 'POST',
                        data: {
                            'idSecretaria': idSecretaria
                        },
                        success: function (html) {
                            $('#departamento').html(html);
                        }
                    });

                    //mostra somente as coordenações de acordo com a secretaria selecionada
                    var idSecretaria = $(this).val();
                    //mostra as coordenações do departamento escolhido
                    $.ajax({
                        url: 'https://itapoa.app.br/App/sistema/acesso/ajaxCoordenacao.php',
                        type: 'POST',
                        data: {
                            'idSecretaria': idSecretaria
                        },
                        success: function (html) {
                            $('#coordenacao').html(html);
                        }
                    });

                    //mostra somente as coordenações de acordo com a secretaria selecionada
                    var idSecretaria = $(this).val();
                    //mostra as coordenações do departamento escolhido
                    $.ajax({
                        url: 'https://itapoa.app.br/App/sistema/acesso/ajaxSetor.php',
                        type: 'POST',
                        data: {
                            'idSecretaria': idSecretaria
                        },
                        success: function (html) {
                            $('#setor').html(html);
                        }
                    });
                });
                
                //autoriza o clique no botão de envio
                $('#termo').change(function(){
                    if ($(this).is(':checked')) {
                        $('#solicitar').removeAttr('disabled');
                    } else {
                        $('#solicitar').attr('disabled',true);
                    }
                });
            });
        </script>
    </body>
</html>