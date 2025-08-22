<?php
use App\sistema\acesso\{
    sConfiguracao,
    sSecretaria,
    sDepartamento,
    sCoordenacao,
    sSetor,
    sNotificacao,
};

use App\sistema\suporte\{
    sProtocolo
};

if(isset($_POST['idProtocolo']) || isset($_GET['idProtocolo'])){
    if(isset($_POST['idProtocolo'])){
        $idProtocolo = $_POST['idProtocolo'];
    }
    if(isset($_GET['idProtocolo'])){
        $idProtocolo = $_GET['idProtocolo'];
    }
}

//instancia classes para manipulação dos dados
$sConfiguracao = new sConfiguracao();

$sSecretaria = new sSecretaria(0);
$sSecretaria->consultar('tMenu2_2_1_1.php-f1');

$sDepartamento = new sDepartamento(0);
$sDepartamento->consultar('tMenu2_2_1_1.php-f1');

$sCoordenacao = new sCoordenacao(0);
$sCoordenacao->consultar('tMenu2_2_1_1.php-f1');

$sSetor = new sSetor(0);
$sSetor->consultar('tMenu2_2_1_1.php-f1');

$sProtocolo = new sProtocolo();
$sProtocolo->setNomeCampo('idprotocolo');
$sProtocolo->setValorCampo($idProtocolo);
$sProtocolo->consultar('tMenu2_2_1_1.php');
if($sProtocolo->getValidador()){
    foreach ($sProtocolo->mConexao->getRetorno() as $valorProtocolo) {
        $secretaria = $valorProtocolo['secretaria'];
        $departamento = $valorProtocolo['departamento'];
        $coordenacao = $valorProtocolo['coordenacao'];
        $setor = $valorProtocolo['setor'];
        $nome = $valorProtocolo['nomeDoRequerente'];
        $sobrenome = $valorProtocolo['sobrenomeDoRequerente'];
        $telefone = $valorProtocolo['telefoneDoRequerente'];
        $whatsApp = $valorProtocolo['whatsAppDoRequerente'];
        $email = $valorProtocolo['emailDoRequerente'];
    }
}
        
//retorno de campo inválidos para notificação
if (isset($_GET['campo'])) {
    $sNotificacao = new sNotificacao($_GET['codigo']);
    switch ($_GET['campo']) {
        case 'secretaria':
            if ($_GET['codigo'] == 'S4') {
                $alertaSecretaria = ' is-valid';
            } else {
                $alertaSecretaria = ' is-warning';
            }
            break;
        case 'email':
            if ($_GET['codigo'] == 'S4') {
                $alertaEmail = ' is-valid';
            } else {
                $alertaEmail = ' is-warning';
            }
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
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Etapa 3 - Alterar Requerente</h3>
                </div>
                <!-- form start -->
                    <div class="card-body">                        
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Secretaria</label>
                                        <select class="form-control<?php echo isset($alertaSecretaria) ? $alertaSecretaria : ''; ?>" name="secretaria" id="secretaria" form="f2">
                                            <?php
                                            foreach ($sSecretaria->mConexao->getRetorno() as $valorSecreataria) {    
                                                $secretaria == $valorSecreataria['nomenclatura'] ? $atributo = 'selected=\"\"' : $atributo = '';
                                                echo '<option value="' . $valorSecreataria['idsecretaria'] . '"' . $atributo . ' >' . $valorSecreataria['nomenclatura'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="departamento">Departamento</label>
                                        <select class="form-control" name="departamento" id="departamento" form="f2">                                        
                                            <?php
                                            echo '<option value="0">--</option>';
                                            foreach ($sDepartamento->mConexao->getRetorno() as $valorDepartamento) {   
                                                $departamento == $valorDepartamento['nomenclatura'] ? $atributo = 'selected=\"\"' : $atributo = '';
                                                echo '<option value="' . $valorDepartamento['iddepartamento'] . '"' . $atributo . ' >' . $valorDepartamento['nomenclatura'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Coordenação</label>
                                        <select class="form-control" name="coordenacao" id="coordenacao" form="f2">
                                            <?php
                                            echo '<option value="0">--</option>';
                                            foreach ($sCoordenacao->mConexao->getRetorno() as $valorCoordenacao) {
                                                $coordenacao == $valorCoordenacao['nomenclatura'] ? $atributo = 'selected=\"\"' : $atributo = '';
                                                echo '<option value="' . $valorCoordenacao['idcoordenacao'] . '"' . $atributo . ' >' . $valorCoordenacao['nomenclatura'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="setor">Setor</label>
                                        <select class="form-control" name="setor" id="setor" form="f2">
                                            <?php
                                            echo '<option value="0">--</option>';
                                            foreach ($sSetor->mConexao->getRetorno() as $valorSetor) {
                                                $setor == $valorSetor['nomenclatura'] ? $atributo = 'selected=\"\"' : $atributo = '';
                                                echo '<option valorSetor="' . $valorSetor['idsetor'] . '"' . $atributo . ' >' . $valorSetor['nomenclatura'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label>Nome</label>
                                    <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $nome ;?>" required="" form="f2">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Sobrenome</label>
                                    <input type="text" class="form-control" id="sobrenome" name="sobrenome" value="<?php echo $sobrenome ;?>" required="" form="f2">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Telefone</label>
                                    <input type="text" class="form-control<?php echo isset($alertaTelefone) ? $alertaTelefone : ''; ?>" id="telefone" name="telefone" required="" value="<?php echo $telefone ;?>" data-inputmask='"mask": "(99) 9 9999-9999"' data-mask inputmode="text" form="f2">
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Whatsapp</label>
                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="whatsApp" name="whatsApp" value="1" <?php echo $whatsApp ? 'checked=\"\"': ''; ?> onclick="decisaoWhatsApp();" form="f2">
                                            <label class="custom-control-label" for="whatsApp">
                                                <div class="conteudo" name="conteudo" id="conteudoWhatsApp">
                                                    <?php echo $whatsApp ? 'Sim': 'Não'; ?>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>E-mail</label>
                                    <input class="form-control<?php echo isset($alertaEmail) ? $alertaEmail : ''; ?>" type="email" id="email" name="email" value="<?php echo $email ;?>" required="" form="f2">
                                </div>                            
                            </div>
                    </div>
                    <?php
                    //exibir notificação
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
                <form action="<?php echo $sConfiguracao->getDiretorioControleSuporte(); ?>sAlterar2_2_1_1.php" method="post" enctype="multipart/form-data" name="f2" id="f2">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <input type="hidden" value="f2" name="formulario" form="f2">
                        <input type="hidden" value="alterar" name="acao" form="f2">
                        <input type="hidden" value="tMenu2_2_1_1.php" name="pagina" form="f2">
                        <input type="hidden" value="<?php echo $idProtocolo ?>" name="idProtocolo" form="f2">
                        <button type="submit" class="btn btn-primary">Alterar</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
<?php
if(isset($alertaTelefone)){
echo "<script>
        window.onload = function decisao() {            
            document.getElementById('conteudo').innerHTML = 'Utilizar dados de outra pessoa';
            document.getElementById('secretaria').disabled = false;
            document.getElementById('departamento').disabled = false;
            document.getElementById('coordenacao').disabled = false;
            document.getElementById('setor').disabled = false;
            document.getElementById('nome').disabled = false;
            document.getElementById('sobrenome').disabled = false;
            document.getElementById('telefone').disabled = false;
            document.getElementById('whatsApp').disabled = false;
            document.getElementById('email').disabled = false;
        };
    </script>";
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">    
    function decisaoWhatsApp() {
        if (document.getElementById('whatsApp').checked) {
            document.getElementById('conteudoWhatsApp').innerHTML = 'Sim';
        }else{
            document.getElementById('conteudoWhatsApp').innerHTML = 'Não';
        }
    }
    
    //preenche os campos do departamento, coordenação e setor de acordo com a secretaria selecionada
    $(document).ready(function () {
        //traz os departamentos de acordo com a secretaria selecionada   
        $('#secretaria').on('change', function () {
            var idSecretaria = $(this).val();

            //mostra somente os departamentos da secretaria escolhida
            $.ajax({
                url: '../../../App/sistema/acesso/ajaxDepartamento.php',
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
                url: '../../../App/sistema/acesso/ajaxCoordenacao.php',
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
                url: '../../../App/sistema/acesso/ajaxSetor.php',
                type: 'POST',
                data: {
                    'idSecretaria': idSecretaria
                },
                success: function (html) {
                    $('#setor').html(html);
                }
            });
        });
        
        $('#meusDados').on('click', function () {
            $("#ocultarCampos").toggle(!this.checked);
        });
    
    });
    
    //contador de caracteres para o campo descrição
    function limite_textarea(valor) {
        quant = 240;
        total = valor.length;
        if(total <= quant) {
            resto = quant - total;
            document.getElementById('cont').innerHTML = resto;
        } else {
            document.getElementById('descricao').value = valor.substr(0,quant);
        }
    }   
</script>