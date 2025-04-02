<?php

use App\sistema\acesso\{
    sConfiguracao,
    sPermissao,
    sNotificacao
};

$sConfiguracao = new sConfiguracao();

$sPermissao = new sPermissao($_SESSION['credencial']['idPermissao']);
$sPermissao->consultar('tMenu1_1_1.php');

//retorno de campo inválidos para notificação
if(isset($_GET['campo'])){
    $sNotificacao = new sNotificacao($_GET['codigo']);
    switch ($_GET['campo']) {
        case 'nome':
            if($_GET['codigo'] == 'S1'){
                $alertaNome = ' is-valid';
            }else{
                $alertaNome = ' is-warning';
            }            
            break;
        case 'sobrenome':
            if($_GET['codigo'] == 'S1'){
                $alertaSobrenome = ' is-valid';
            }else{
                $alertaSobrenome = ' is-warning';
            }  
            break;
        case 'sexo':
            if($_GET['codigo'] == 'S1'){
                $alertaSexo = ' is-valid';
            }else{
                $alertaSexo = ' is-warning';
            }  
            break;
        case 'telefone':
            if($_GET['codigo'] == 'S1'){
                $alertaTelefone = ' is-valid';
            }else{
                $alertaTelefone = ' is-warning';
            }  
            break;
        case 'email':
            if($_GET['codigo'] == 'S1'){
                $alertaEmail = ' is-valid';
            }else{
                $alertaEmail = ' is-warning';
            }  
            break;
        case 'permissao':
            if($_GET['codigo'] == 'S1'){
                $alertaPermissao = ' is-valid';
            }else{
                $alertaPermissao = ' is-warning';
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
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Etapa 2 - Alterar</h3>
                </div>
                <!-- form start -->
                <form name="form1_tMenu1_1_1" id="form1_tMenu1_1_1" action="<?php echo $sConfiguracao->getDiretorioControleAcesso(); ?>sAlterarDadosDoUsuario.php" method="post" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="row">
                            <!--
                            //próxima build
                            <div class="form-group col-md-1">
                                <div class="text-center">
                                    <img class="profile-user-img img-fluid img-circle" src="<?php //echo $sConfiguracao->getDiretorioPrincipal();   ?>vendor/almasaeed2010/adminlte/dist/img/user2-160x160.jpg" alt="User profile picture">
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="imagem">Alterar Imagem</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="imagem" disabled="">
                                        <label class="custom-file-label" for="imagem"></label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text">Enviar</span>
                                    </div>
                                </div>
                            </div>
                            -->
                            <div class="form-group col-md-1">
                                <label for="nome">Nome</label>
                                <input type="text" class="form-control<?php echo isset($alertaNome) ? $alertaNome : ''; ?>" name="nome" id="nome" value="<?php echo $_SESSION['credencial']['nome']; ?>" required="">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="sobrenome">Sobrenome</label>
                                <input class="form-control<?php echo isset($alertaSobrenome) ? $alertaSobrenome : ''; ?>" type="text" name="sobrenome" id="sobrenome" value="<?php echo $_SESSION['credencial']['sobrenome']; ?>" required="">
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>Sexo</label>
                                    <select class="form-control<?php echo isset($alertaSexo) ? $alertaSexo : ''; ?>" name="sexo" id="sexo" required="">
                                        <option value="M" <?php echo $_SESSION['credencial']['sexo'] == 'Masculino' ? 'selected=""' : ''; ?>>Masculino</option>
                                        <option value="F" <?php echo $_SESSION['credencial']['sexo'] == 'Feminino' ? 'selected=""' : ''; ?>>Feminino</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="telefoneUsuario">Telefone Pessoal</label>
                                <input class="form-control<?php echo isset($alertaTelefone) ? $alertaTelefone : ''; ?>" type="text" name="telefoneUsuario" id="telefoneUsuario" value="<?php echo $_SESSION['credencial']['telefoneUsuario']; ?>" data-inputmask='"mask": "(99) 9 9999-9999"' data-mask inputmode="text">
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>WhatsApp</label>
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input class="custom-control-input" type="checkbox" name="whatsAppUsuario" id="whatsAppUsuario" <?php echo $_SESSION['credencial']['whatsAppUsuario'] ? 'checked=""' : '';?> onclick="decisao();">
                                        <label class="custom-control-label" for="whatsAppUsuario">
                                            <div class="conteudo" name="conteudo" id="conteudo">
                                                <?php echo $_SESSION['credencial']['whatsAppUsuario'] ? 'Sim' : 'Não'; ?>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="emailUsuario">Email Pessoal</label>
                                <input class="form-control<?php echo isset($alertaEmail) ? $alertaEmail : ''; ?>" type="email" name="emailUsuario" id="emailUsuario" value="<?php echo $_SESSION['credencial']['emailUsuario']; ?>" required="">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="senhaUsuario">Senha</label>
                                <input class="form-control<?php echo isset($alertaSenha) ? $alertaSenha : ''; ?>" type="password" name="senhaUsuario" id="senhaUsuario">
                            </div>
                        </div>
                    </div>
                    
                    <?php
                    if(isset($tipo) && isset($titulo) && isset($mensagem)){
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
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <input type="hidden" name="pagina" id="pagina" value="tMenu1_1_1.php">
                        <input type="hidden" name="acao" id="acao" value="alterar">
                        <button class="btn btn-primary" type="submit">Alterar</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
<script>
    function decisao(){
       if (document.getElementById('whatsAppUsuario').checked) {
            document.getElementById('conteudo').innerHTML = 'Sim';
        } else {
            document.getElementById('conteudo').innerHTML = 'Não';
        }
    }
</script>