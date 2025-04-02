<?php
//chama o caminho do autoload para carregamento dos arquivos
require_once '../../../vendor/autoload.php';

//chama os arquivos para instanciá-los
use App\sistema\acesso\{
    sConfiguracao,
    sNotificacao,
    sSair
};

if(!isset($_GET['seguranca'])){
    //solicitar saída com tentativa de violação
    $sSair = new sSair();
    $sSair->verificar('0');
}

//caso o sistema esteja retornando o alerta A31 verifique os caracteres da url com os do BD
//altera "espaço" por sinal de "+" ao passar via get
$chave = str_replace([' '], '+', $_GET['seguranca']);

$sConfiguracao = new sConfiguracao();

if (isset($_GET['campo']) ||
    isset($_GET['codigo'])) {    
    $sNotificacao = new sNotificacao($_GET['codigo']);
    switch ($_GET['campo']) {
        case 'senha':
            if ($_GET['codigo'] == 'S2') {
                $alertaSenha = ' is-valid';
            } else {
                $alertaSenha = ' is-warning';
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
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $sConfiguracao->getTitle() ?></title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo $sConfiguracao->getDiretorioPrincipal(); ?>vendor/almasaeed2010/adminlte/plugins/fontawesome-free/css/all.min.css">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="<?php echo $sConfiguracao->getDiretorioPrincipal(); ?>vendor/almasaeed2010/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo $sConfiguracao->getDiretorioPrincipal(); ?>vendor/almasaeed2010/adminlte/dist/css/adminlte.min.css">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <?php echo $sConfiguracao->getLoginLogo(); ?>
            </div>
            <!-- /.login-logo -->
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Registrar nova senha para acesso</p>
                    <form action="<?php echo $sConfiguracao->getDiretorioControleAcesso(); ?>sVerificarSenha.php" method="post" enctype="multipart/form-data">
                        <div class="input-group mb-3">
                            <input class="form-control<?php echo isset($alertaSenha) ? $alertaSenha : ''; ?>" type="password" name="senha" id="senha" placeholder="Senha" required="">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="confirmarSenha" id="confirmarSenha" placeholder="Confirmar Senha" required="">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-block">Alterar Senha</button>
                            </div>
                            <!-- /.col -->
                        </div>
                        <input type="hidden" value="<?php echo $chave; ?>" name="chave" id="chave">
                        <input type="hidden" value="tAlterarSenha.php" name="pagina" id="pagina">
                        <input type="hidden" value="inserir" name="acao" id="acao">
                    </form>
                    <p class="mt-3 mb-1">
                        <a href="<?php echo $sConfiguracao->getDiretorioVisualizacaoAcesso(); ?>tAcessar.php">Acessar</a>
                    </p>
                    <p class="mt-3 mb-1">
                        <a href="<?php echo $sConfiguracao->getDiretorioVisualizacaoAcesso(); ?>tEsqueciMinhaSenha.php">Esqueci minha senha</a>
                    </p>
                </div>
                <!-- /.login-card-body -->
            </div>
            <?php
            if( isset($tipo) &&
                isset($titulo) &&
                isset($mensagem)){
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
        <!-- /.login-box -->

        <!-- jQuery -->
        <script src="<?php echo $sConfiguracao->getDiretorioPrincipal(); ?>vendor/almasaeed2010/adminlte/plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="<?php echo $sConfiguracao->getDiretorioPrincipal(); ?>vendor/almasaeed2010/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="<?php echo $sConfiguracao->getDiretorioPrincipal(); ?>vendor/almasaeed2010/adminlte/dist/js/adminlte.min.js"></script>
    </body>
</html>