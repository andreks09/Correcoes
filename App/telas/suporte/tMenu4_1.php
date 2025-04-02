<?php

use App\sistema\acesso\{
    sConfiguracao,
    sNotificacao,
    sSecretaria,
    sDepartamento,
    sCoordenacao
};

//instancia classes para manipulação dos dados
$sConfiguracao = new sConfiguracao();

$sSecretaria = new sSecretaria(0);
$sSecretaria->consultar('tMenu4_1.php');

$sDepartamento = new sDepartamento(0);
$sDepartamento->consultar('tMenu4_1.php');

$sCoordenacao = new sCoordenacao(0);
$sCoordenacao->consultar('tMenu4_1.php');
//retorno de campo inválidos para notificação
if (isset($_GET['campo'])) {
    $sNotificacao = new sNotificacao($_GET['codigo']);
    switch ($_GET['campo']) {
        case 'secretariaF1':
            if ($_GET['codigo'] == 'S4') {
                $alertaSecretariaF1 = ' is-valid';
            } else {
                $alertaSecretariaF1 = ' is-warning';
            }
            break;
        case 'enderecoF1':
            if ($_GET['codigo'] == 'S4') {
                $alertaEnderecoF1 = ' is-valid';
            } else {
                $alertaEnderecoF1 = ' is-warning';
            }
            break;
        case 'emailF1':
            if ($_GET['codigo'] == 'S4') {
                $alertaEmailF1 = ' is-valid';
            } else {
                $alertaEmailF1 = ' is-warning';
            }
            break;
        case 'telefoneF1':
            if ($_GET['codigo'] == 'S4') {
                $alertaTelefoneF1 = ' is-valid';
            } else {
                $alertaTelefoneF1 = ' is-warning';
            }
            break;
        case 'ambienteF1':
            if ($_GET['codigo'] == 'S4') {
                $alertaAmbienteF1 = ' is-valid';
            } else {
                $alertaAmbienteF1 = ' is-warning';
            }
            break;
        case 'departamentoF2':
            if ($_GET['codigo'] == 'S4') {
                $alertaDepartamentoF2 = ' is-valid';
            } else {
                $alertaDepartamentoF2 = ' is-warning';
            }
            break;
        case 'enderecoF2':
            if ($_GET['codigo'] == 'S4') {
                $alertaEnderecoF2 = ' is-valid';
            } else {
                $alertaEnderecoF2 = ' is-warning';
            }
            break;
        case 'emailF2':
            if ($_GET['codigo'] == 'S4') {
                $alertaEmailF2 = ' is-valid';
            } else {
                $alertaEmailF2 = ' is-warning';
            }
            break;
        case 'telefoneF2':
            if ($_GET['codigo'] == 'S4') {
                $alertaTelefoneF2 = ' is-valid';
            } else {
                $alertaTelefoneF2 = ' is-warning';
            }
            break;
        case 'coordenacaoF3':
            if ($_GET['codigo'] == 'S4') {
                $alertaCoordenacaoF3 = ' is-valid';
            } else {
                $alertaCoordenacaoF3 = ' is-warning';
            }
            break;
        case 'enderecoF3':
            if ($_GET['codigo'] == 'S4') {
                $alertaEnderecoF3 = ' is-valid';
            } else {
                $alertaEnderecoF3 = ' is-warning';
            }
            break;
        case 'emailF3':
            if ($_GET['codigo'] == 'S4') {
                $alertaEmailF3 = ' is-valid';
            } else {
                $alertaEmailF3 = ' is-warning';
            }
            break;
        case 'telefoneF3':
            if ($_GET['codigo'] == 'S4') {
                $alertaTelefoneF3 = ' is-valid';
            } else {
                $alertaTelefoneF3 = ' is-warning';
            }
            break;
        case 'setorF4':
            if ($_GET['codigo'] == 'S4') {
                $alertaSetorF4 = ' is-valid';
            } else {
                $alertaSetorF4 = ' is-warning';
            }
            break;
        case 'enderecoF4':
            if ($_GET['codigo'] == 'S4') {
                $alertaEnderecoF4 = ' is-valid';
            } else {
                $alertaEnderecoF4 = ' is-warning';
            }
            break;
        case 'emailF4':
            if ($_GET['codigo'] == 'S4') {
                $alertaEmailF4 = ' is-valid';
            } else {
                $alertaEmailF4 = ' is-warning';
            }
            break;
        case 'telefoneF4':
            if ($_GET['codigo'] == 'S4') {
                $alertaTelefoneF4 = ' is-valid';
            } else {
                $alertaTelefoneF4 = ' is-warning';
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
        <!--registro secretaria-->
        <div class="col-md-3">
            <!-- general form elements -->
            <div class="card card-outline card-primary <?php echo isset($alertaSecretariaF1) || isset($alertaEnderecoF1) || isset($alertaTelefoneF1) || isset($alertaEmailF1) ? '' : 'collapsed-card' ?>">
                <div class="card-header">
                    <h3 class="card-title">Registrar Secretaria</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas <?php echo isset($alertaSecretariaF1) || isset($alertaEnderecoF1) || isset($alertaTelefoneF1) || isset($alertaEmailF1) ? 'fa-minus' : 'fa-plus' ?>"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- form start -->                
                <div class="card-body">
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="secretariaF1">Secretaria</label>
                            <input class="form-control <?php echo isset($alertaSecretariaF1) ? $alertaSecretariaF1 : ''; ?>" type="text" name="secretariaF1" id="secretariaF1" placeholder="Ex.: Administração" form="f1" required="">
                        </div>
                    </div>
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="enderecoF1">Endereço</label>
                            <input class="form-control <?php echo isset($alertaEnderecoF1) ? $alertaEnderecoF1 : ''; ?>" type="text" name="enderecoF1" id="enderecoF1" placeholder="Ex.: Rua 960, Mariana Michels Borges, 201" form="f1" required="">
                        </div>
                    </div>
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="emailF1">E-mail</label>
                            <input class="form-control <?php echo isset($alertaEmailF1) ? $alertaEmailF1 : ''; ?>" type="email" name="emailF1" id="emailF1" placeholder="Ex.: secretaria@itapoa.sc.gov.br" form="f1" required="">
                        </div>
                    </div>
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="telefoneF1">Telefone</label>
                            <input class="form-control <?php echo isset($alertaTelefoneF1) ? $alertaTelefoneF1 : ''; ?>" type="text" name="telefoneF1" id="telefoneF1" placeholder="Ex.: 47 3443-8832" form="f1" data-inputmask='"mask": "(99) 9 9999-9999"' data-mask inputmode="text">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Ambiente</label>
                                <select class="form-control<?php echo isset($alertaAmbienteF1) ? $alertaAmbienteF1 : ''; ?>" name="ambienteF1" id="ambienteF1" form="f1">
                                    <option value="interno">Interno</option>
                                    <option value="externo">Externo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1">
                            <div class="form-group">
                                <label>WhatsApp</label>
                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input class="custom-control-input" type="checkbox" name="whatsAppF1" id="whatsAppF1" form="f1">
                                    <label class="custom-control-label" for="whatsAppF1">Não</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if (isset($tipo) &&
                    isset($titulo) &&
                    isset($mensagem)) {
                    if (isset($alertaSecretariaF1) ||
                        isset($alertaEnderecoF1) ||
                        isset($alertaTelefoneF1) ||
                        isset($alertaEmailF1)) {
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
                <form action="<?php echo $sConfiguracao->getDiretorioControleSuporte(); ?>sRegistrarLocal.php" method="post" id="f1" enctype="multipart/form-data">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <input type="hidden" value="f1" name="formulario" form="f1">
                        <input type="hidden" value="inserir" name="acaoF1" form="f1">
                        <input type="hidden" value="menu4_1" name="paginaF1" form="f1">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
        <!--registro deaprtamento/ unidade-->
        <div class="col-md-3">
            <!-- general form elements -->
            <div class="card card-outline card-primary <?php echo isset($alertaDepartamentoF2) || isset($alertaEnderecoF2) || isset($alertaTelefoneF2) || isset($alertaEmailF2) ? '' : 'collapsed-card' ?>">
                <div class="card-header">
                    <h3 class="card-title">Departamento</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas <?php echo isset($alertaDepartamentoF2) || isset($alertaEnderecoF2) || isset($alertaTelefoneF2) || isset($alertaEmailF2) ? 'fa-minus' : 'fa-plus' ?>"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- form start -->                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Secretaria</label>
                                <select class="form-control<?php echo isset($alertaSecretariaF2) ? $alertaSecretariaF2 : ''; ?>" name="secretariaF2" id="secretariaF2" form="f2">
                                    <?php
                                    foreach ($sSecretaria->mConexao->getRetorno() as $value) {
                                        echo '<option value="' . $value['idsecretaria'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="departamentoF2">Departamento</label>
                            <input class="form-control <?php echo isset($alertaDepartamentoF2) ? $alertaDepartamentoF2 : ''; ?>" type="text" name="departamentoF2" id="departamentoF2" placeholder="Ex.: Departamento de Tecnologia da Informação" form="f2" required="">
                        </div>
                    </div>
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="enderecoF2">Endereço</label>
                            <input class="form-control <?php echo isset($alertaEnderecoF2) ? $alertaEnderecoF2 : ''; ?>" type="text" name="enderecoF2" id="enderecoF2" placeholder="Ex.: Rua 960, Mariana Michels Borges, 201" form="f2" required="">
                        </div>
                    </div>
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="emailF2">E-mail</label>
                            <input class="form-control <?php echo isset($alertaEmailF2) ? $alertaEmailF2 : ''; ?>" type="email" name="emailF2" id="emailF2" placeholder="Ex.: departamento@itapoa.sc.gov.br" form="f2" required="">
                        </div>
                    </div>
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="telefoneF2">Telefone</label>
                            <input class="form-control <?php echo isset($alertaTelefoneF2) ? $alertaTelefoneF2 : ''; ?>" type="text" name="telefoneF2" id="telefoneF2" placeholder="Ex.: 47 3443-8832" form="f2" data-inputmask='"mask": "(99) 9 9999-9999"' data-mask inputmode="text">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>WhatsApp</label>
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                <input class="custom-control-input" type="checkbox" name="whatsAppF2" id="whatsAppF2" form="f2">
                                <label class="custom-control-label" for="whatsAppF2">Não</label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if (isset($tipo) &&
                        isset($titulo) &&
                        isset($mensagem)) {
                    if (isset($alertaDepartamentoF2) ||
                            isset($alertaEnderecoF2) ||
                            isset($alertaTelefoneF2) ||
                            isset($alertaEmailF2)) {
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
                <form action="<?php echo $sConfiguracao->getDiretorioControleSuporte(); ?>sRegistrarLocal.php" method="post" id="f2" enctype="multipart/form-data">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <input type="hidden" value="f2" name="formulario" form="f2">
                        <input type="hidden" value="inserir" name="acaoF2" form="f2">
                        <input type="hidden" value="menu4_1" name="paginaF2" form="f2">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
        <!--registro coordenação-->
        <div class="col-md-3">
            <!-- general form elements -->
            <div class="card card-outline card-primary <?php echo isset($alertaCoordenacaoF3) || isset($alertaEnderecoF3) || isset($alertaTelefoneF3) || isset($alertaEmailF3) ? '' : 'collapsed-card' ?>">
                <div class="card-header">
                    <h3 class="card-title">Coordenação</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas <?php echo isset($alertaCoordenacaoF3) || isset($alertaEnderecoF3) || isset($alertaTelefoneF3) || isset($alertaEmailF3) ? 'fa-minus' : 'fa-plus' ?>"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- form start -->                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Secretaria</label>
                                <select class="form-control" name="secretariaF3" id="secretariaF3" form="f3">
                                    <?php
                                    foreach ($sSecretaria->mConexao->getRetorno() as $value) {
                                        echo '<option value="' . $value['idsecretaria'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="coordenacaoF3">Coordenação</label>
                            <input class="form-control <?php echo isset($alertaCoordenacaoF3) ? $alertaCoordenacaoF3 : ''; ?>" type="text" name="coordenacaoF3" id="coordenacaoF3" placeholder="Ex.: Coordenacao de Tecnologia da Informação" form="f3" required="">
                        </div>
                    </div>
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="enderecoF3">Endereço</label>
                            <input class="form-control <?php echo isset($alertaEnderecoF3) ? $alertaEnderecoF3 : ''; ?>" type="text" name="enderecoF3" id="enderecoF3" placeholder="Ex.: Rua 960, Mariana Michels Borges, 201" form="f3" required="">
                        </div>
                    </div>
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="emailF3">E-mail</label>
                            <input class="form-control <?php echo isset($alertaEmailF3) ? $alertaEmailF3 : ''; ?>" type="email" name="emailF3" id="emailF3" placeholder="Ex.: coordenacao@itapoa.sc.gov.br" form="f3" required="">
                        </div>
                    </div>
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="telefoneF3">Telefone</label>
                            <input class="form-control <?php echo isset($alertaTelefoneF3) ? $alertaTelefoneF3 : ''; ?>" type="text" name="telefoneF3" id="telefoneF3" placeholder="Ex.: 47 3443-8832" form="f3" data-inputmask='"mask": "(99) 9 9999-9999"' data-mask inputmode="text">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>WhatsApp</label>
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                <input class="custom-control-input" type="checkbox" name="whatsAppF3" id="whatsAppF3" form="f3">
                                <label class="custom-control-label" for="whatsAppF3">Não</label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if (isset($tipo) &&
                        isset($titulo) &&
                        isset($mensagem)) {
                    if (isset($alertaCoordenacaoF3) ||
                            isset($alertaEnderecoF3) ||
                            isset($alertaTelefoneF3) ||
                            isset($alertaEmailF3)) {
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
                <form action="<?php echo $sConfiguracao->getDiretorioControleSuporte(); ?>sRegistrarLocal.php" method="post" id="f3" enctype="multipart/form-data">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <input type="hidden" value="f3" name="formulario" form="f3">
                        <input type="hidden" value="inserir" name="acaoF3" form="f3">
                        <input type="hidden" value="menu4_1" name="paginaF3" form="f3">
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
        <!--registro setor-->
        <div class="col-md-3">
            <!-- general form elements -->
            <div class="card card-outline card-primary <?php echo isset($alertaSetorF4) || isset($alertaEnderecoF4) || isset($alertaTelefoneF4) || isset($alertaEmailF4) ? '' : 'collapsed-card' ?>">
                <div class="card-header">
                    <h3 class="card-title">Setor</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas <?php echo isset($alertaSetorF4) || isset($alertaEnderecoF4) || isset($alertaTelefoneF4) || isset($alertaEmailF4) ? 'fa-minus' : 'fa-plus' ?>"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- form start -->                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Secretaria</label>
                                <select class="form-control" name="secretariaF4" id="secretariaF4" form="f4">
                                    <?php
                                    foreach ($sSecretaria->mConexao->getRetorno() as $value) {
                                        echo '<option value="' . $value['idsecretaria'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="setorF4">Setor</label>
                            <input class="form-control <?php echo isset($alertaSetorF4) ? $alertaSetorF4 : ''; ?>" type="text" name="setorF4" id="setorF4" placeholder="Ex.: Setor de Tecnologia da Informação" form="f4" required="">
                        </div>
                    </div>
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="enderecoF4">Endereço</label>
                            <input class="form-control <?php echo isset($alertaEnderecoF4) ? $alertaEnderecoF4 : ''; ?>" type="text" name="enderecoF4" id="enderecoF4" placeholder="Ex.: Rua 960, Mariana Michels Borges, 201" form="f4" required="">
                        </div>
                    </div>
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="emailF4">E-mail</label>
                            <input class="form-control <?php echo isset($alertaEmailF4) ? $alertaEmailF4 : ''; ?>" type="email" name="emailF4" id="emailF4" placeholder="Ex.: setor@itapoa.sc.gov.br" form="f4" required="">
                        </div>
                    </div>
                    <div class="row">                      
                        <div class="form-group col-md-12">
                            <label for="telefoneF4">Telefone</label>
                            <input class="form-control <?php echo isset($alertaTelefoneF4) ? $alertaTelefoneF4 : ''; ?>" type="text" name="telefoneF4" id="telefoneF4" placeholder="Ex.: 47 3443-8832" form="f4" data-inputmask='"mask": "(99) 9 9999-9999"' data-mask inputmode="text">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>WhatsApp</label>
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                <input class="custom-control-input" type="checkbox" name="whatsAppF4" id="whatsAppF4" form="f4">
                                <label class="custom-control-label" for="whatsAppF4">Não</label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if (isset($tipo) &&
                        isset($titulo) &&
                        isset($mensagem)) {
                    if (isset($alertaSetorF4) ||
                            isset($alertaEnderecoF4) ||
                            isset($alertaTelefoneF4) ||
                            isset($alertaEmailF4)) {
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
                <form action="<?php echo $sConfiguracao->getDiretorioControleSuporte(); ?>sRegistrarLocal.php" method="post" id="f4" enctype="multipart/form-data">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <input type="hidden" value="f4" name="formulario" form="f4">
                        <input type="hidden" value="inserir" name="acaoF4" form="f4">
                        <input type="hidden" value="menu4_1" name="paginaF4" form="f4">
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