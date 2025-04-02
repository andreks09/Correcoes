<?php
use App\sistema\acesso\{
    sConfiguracao,
    sSecretaria,
    sDepartamento,
    sCoordenacao,
    sSetor,
    sNotificacao
};

use App\sistema\suporte\{
    sPrioridade,
    sLocal,
    sEquipamento
};

isset($_POST['patrimonio']) ? $patrimonio = true : $patrimonio = false;
//$categoria = $_POST['categoria'];
isset($_POST['idEquipamento']) ? $idEquipamento = $_POST['idEquipamento'] : $idEquipamento = false;

if(!$idEquipamento && $patrimonio){
    $sConfiguracao = new sConfiguracao;
    header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_1&campo=equipamento&codigo=A19");
    exit();
    //finaliza alteração do cabeçalho para não gerar erro de output, vinculado à instrução da linha 1 do tPainel.
    ob_clean();
}

//instancia classes para manipulação dos dados
$sConfiguracao = new sConfiguracao();

$sSecretaria = new sSecretaria(0);
$sSecretaria->consultar('tMenu2_1.php-f1');

$sDepartamento = new sDepartamento(0);
$sDepartamento->consultar('tMenu2_1.php-f1');

$sCoordenacao = new sCoordenacao(0);
$sCoordenacao->consultar('tMenu2_1.php-f1');

$sSetor = new sSetor(0);
$sSetor->consultar('tMenu2_1.php-f1');

$sLocal = new sLocal();
$sLocal->consultar('tMenu2_1.php-f1');

$sPrioridade = new sPrioridade();        
$sPrioridade->consultar('tMenu2_1.php-f1');

if(!$idEquipamento){
    $sEquipamento = new sEquipamento();
    $sEquipamento->setNomeCampo('patrimonio');
    $sEquipamento->setValorCampo('Indefinido');
    $sEquipamento->consultar('tMenu2_1_1.php');
    
    if(!$sEquipamento->getValidador()){
        $sConfiguracao = new sConfiguracao;
        header("Location: {$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_1&campo=sistema&codigo=E4");
        exit();
        //finaliza alteração do cabeçalho para não gerar erro de output, vinculado à instrução da linha 1 do tPainel.
        ob_clean();
    }else{
        foreach ($sEquipamento->mConexao->getRetorno() as $linha) {
            if ($linha['patrimonio'] == 'Indefinido') {
                $idEquipamento = $linha['idequipamento'];
            }
        }
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
                    <h3 class="card-title">Etapa 1 - Solicitante</h3>
                </div>
                <!-- form start -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="meusDados" name="meusDados" checked="checked" value="1" onclick="decisao();" form="f2">
                                        <label class="custom-control-label" for="meusDados">
                                            <div class="conteudo" name="conteudo" id="conteudo">
                                                Utilizar meus dados para a solicitação do suporte
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" name="ocultarCampos" id="ocultarCampos" style="display: none;">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Secretaria</label>
                                        <select class="form-control<?php echo isset($alertaSecretaria) ? $alertaSecretaria : ''; ?>" name="secretaria" id="secretaria" disabled="" form="f2">
                                            <?php
                                            echo '<option value="0" selected="">--</option>';
                                            foreach ($sSecretaria->mConexao->getRetorno() as $value) {                                            
                                                echo '<option value="' . $value['idsecretaria'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="departamento">Departamento</label>
                                        <select class="form-control" name="departamento" id="departamento" disabled="" form="f2">                                        
                                            <?php
                                            foreach ($sDepartamento->mConexao->getRetorno() as $value) {    
                                                echo '<option value="' . $value['iddepartamento'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Coordenação</label>
                                        <select class="form-control" name="coordenacao" id="coordenacao" disabled="" form="f2">
                                            <?php
                                            foreach ($sCoordenacao->mConexao->getRetorno() as $value) {
                                                echo '<option value="' . $value['idcoordenacao'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="setor">Setor</label>
                                        <select class="form-control" name="setor" id="setor" disabled="" form="f2">
                                            <?php
                                            foreach ($sSetor->mConexao->getRetorno() as $value) {
                                                echo '<option value="' . $value['idsetor'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="form-group col-md-2">
                                    <label>Nome</label>
                                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required="" disabled="" form="f2">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Sobrenome</label>
                                    <input type="text" class="form-control" id="sobrenome" name="sobrenome" placeholder="Sobrenome" required="" disabled="" form="f2">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Telefone</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone" required="" disabled="" placeholder="(99) 9 9999-9999" data-inputmask='"mask": "(99) 9 9999-9999"' data-mask inputmode="text" form="f2">
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Whatsapp</label>
                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="checkbox" class="custom-control-input" id="whatsApp" name="whatsApp" value="1" onclick="decisaoWhatsApp();" disabled="" form="f2">
                                            <label class="custom-control-label" for="whatsApp">
                                                <div class="conteudo" name="conteudo" id="conteudoWhatsApp">
                                                    Não
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-2">
                                    <label>E-mail</label>
                                    <input class="form-control<?php echo isset($alertaEmail) ? $alertaEmail : ''; ?>" type="email" id="email" name="email" placeholder="E-mail" required="" disabled="" form="f2">
                                </div>                            
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Prioridade</label>
                                    <select class="form-control" name="prioridade" id="prioridade" form="f2" required="">
                                        <?php
                                        //SE A PRIORIDADE FOR MAIOR QUE A PERMISSÃO DO USUÁRIO ENTÃO DESABILITE O CAMPO
                                        foreach ($sPrioridade->mConexao->getRetorno() as $value) {
                                            $value['nomenclatura'] == 'Normal' ? $atributo = 'selected=""' : $atributo = '';
                                            
                                            if($_SESSION['credencial']['nivelPermissao'] == 1 && $value['idprioridade'] < 3){
                                                echo '<option value="' . $value['idprioridade'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            }else{                                            
                                                if($_SESSION['credencial']['nivelPermissao'] == 2 && $value['idprioridade'] < 4){
                                                    echo '<option value="' . $value['idprioridade'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                                }else if($_SESSION['credencial']['nivelPermissao'] == 3 && $value['idprioridade'] < 5){
                                                    echo '<option value="' . $value['idprioridade'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                                }else if($_SESSION['credencial']['nivelPermissao'] == 4 || $_SESSION['credencial']['nivelPermissao'] == 5){
                                                    echo '<option value="' . $value['idprioridade'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                                }else if($_SESSION['credencial']['nivelPermissao'] != 1){
                                                    echo '<option disabled="" value="' . $value['idprioridade'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>  
                            <?php
                            if($_SESSION['credencial']['nivelPermissao'] > 1){
                            ?>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="local">Local</label>
                                    <select class="form-control" name="local" id="local" form="f2">
                                        <?php
                                        foreach ($sLocal->mConexao->getRetorno() as $value) {
                                            $value['nomenclatura'] == 'Solicitante' ? $atributo = 'selected=""' : $atributo = '';
                                            if($_SESSION['credencial']['nivelPermissao'] == 1 && $value['nomenclatura'] == 'Solicitante'){
                                                echo '<option value="' . $value['idlocal'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            }else if($_SESSION['credencial']['nivelPermissao'] > 1){
                                                echo '<option value="' . $value['idlocal'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            }                                            
                                        }
                                        ?>
                                    </select>
                                </div>                                
                            </div>
                            <?php
                            }
                            ?>
                            <div class="form-group col-md-2">
                                <label for="acessoRemoto">Acesso remoto</label> <a href="../acesso/tFAQ.php" target="_blank"><i class="fas fa-info-circle text-primary mr-1"></i></a>
                                <input class="form-control" type="text" id="acessoRemoto" name="acessoRemoto" placeholder="Ex.: 1505389456" form="f2">
                            </div>
                        </div>
                        <div class="row">                     
                            <div class="col-sm-6">
                                <!-- textarea -->
                                <div class="form-group">
                                    <label>Descrição</label>
                                    <textarea class="form-control" rows="3" name="descricao" id="descricao" placeholder="Descrição..." required="" maxlength="240"  onkeyup="limite_textarea(this.value)" form="f2"></textarea>
                                    <span id="cont">240</span> Caracteres restantes <br>
                                </div>
                            </div>
                            <!-- próxima build
                            <div class="form-group col-md-3">
                                <label for="print">Print</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="print">
                                        <label class="custom-file-label" for="print">Caminho do diretório</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Enviar</span>
                                    </div>
                                </div>
                            </div>
                            -->
                        </div>
                    </div>
                    <?php
                        if (isset($tipo) &&
                            isset($titulo) &&
                            isset($mensagem)) {
                            if (isset($alertaSecretaria) ||
                                isset($alertaEmail)) {
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
                <form action="<?php echo $sConfiguracao->getDiretorioControleSuporte(); ?>sSolicitarSuporte.php" method="post" enctype="multipart/form-data" name="f2" id="f2">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <input type="hidden" value="f2" name="formulario" form="f2">
                        <input type="hidden" value="inserir" name="acao" form="f2">
                        <input type="hidden" value="menu2_1_1" name="pagina" form="f2">
                        <input type="hidden" value="<?php echo $idEquipamento ?>" name="idEquipamento" form="f2">
                        <button type="submit" class="btn btn-primary">Próxima</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript">
    //habilita/ desabilita campos ao ativar/ desativar checkbox
    function decisao() {
        if (document.getElementById('meusDados').checked) {
            document.getElementById('conteudo').innerHTML = 'Utilizar meus dados';
            document.getElementById('secretaria').disabled = true;
            document.getElementById('departamento').disabled = true;
            document.getElementById('coordenacao').disabled = true;
            document.getElementById('setor').disabled = true;
            document.getElementById('nome').disabled = true;
            document.getElementById('sobrenome').disabled = true;
            document.getElementById('telefone').disabled = true;
            document.getElementById('whatsApp').disabled = true;
            document.getElementById('email').disabled = true;
        } else {
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
        }
    }
    
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