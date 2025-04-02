<?php

use App\sistema\acesso\{
    sConfiguracao,
    sSetor,
    sTelefone,
    sTratamentoDados,
    sEmail,
    sSair,
    sNotificacao
};

if(isset($_GET['pagina'])){
    $pagina = $_GET['pagina'];
    $idSetor = base64_decode($_GET['seguranca']);
    if(isset($_GET['formulario'])){
        $formulario = $_GET['formulario'];
    }
}else if(isset($_POST['pagina'])){
    $pagina = $_POST['pagina'];
    $idSetor = $_POST['setor'];
    if(isset($_POST['formulario'])){
        $formulario = $_POST['formulario'];
    }
}else{
    $pagina = 0;
    $formulario = 0;
}

if (!$pagina || !$formulario) {
    //solicitar saída com tentativa de violação
    $sSair = new sSair();
    $sSair->verificar('0');
} else {
    if ($formulario == 'f1') {
        $opcao = 'Setor';

        //busca dados da setor no bd
        $sSetor = new sSetor(0);
        $sSetor->setNomeCampo('idsetor');
        $sSetor->setValorCampo($idSetor);
        $sSetor->consultar('tMenu4_2_4_1.php');

        foreach ($sSetor->mConexao->getRetorno() as $value) {
            $setor = $value['nomenclatura'];
            $endereco = $value['endereco'];
        }

        //busca dados do telefone
        $sTelefone = new sTelefone(0, $idSetor, '');
        $sTelefone->setNomeCampo('setor_idsetor');
        $sTelefone->setValorCampo($idSetor);
        $sTelefone->consultar('tMenu4_2_4_1.php');

        //se tiver alguma Setor registrada na tabela telefone_has_setor
        if ($sTelefone->getValidador()) {
            foreach ($sTelefone->mConexao->getRetorno() as $value) {
                $idTelefone = $value['telefone_idtelefone'];
            }

            //consulta o idTelefone na tabela telefone
            $sTelefone = new sTelefone($idTelefone, 0, 0);
            $sTelefone->setNomeCampo('idtelefone');
            $sTelefone->setValorCampo($idTelefone);
            $sTelefone->consultar('tMenu4_2_4_1.php-2');

            //se localizou um telefone registrado colete as informações
            if ($sTelefone->getValidador()) {
                foreach ($sTelefone->mConexao->getRetorno() as $value) {
                    $numero = $value['numero'];
                    $whatsApp = $value['whatsApp'];
                }

                //trata o numero de telefone para inserção dos caracteres especiais
                $sTratamentoTelefone = new sTratamentoDados($numero);
                $telefoneTratado = $sTratamentoTelefone->tratarTelefone();
            }
        }

        //busca dados do e-mail no bd
        $sEmail = new sEmail('', '');
        $sEmail->setNomeCampo('setor_idsetor');
        $sEmail->setValorCampo($idSetor);
        $sEmail->consultar('tMenu4_2_4_1.php');

        //se tiver algum e-mail registrado na tabela email_has_setor
        if ($sEmail->getValidador()) {
            foreach ($sEmail->mConexao->getRetorno() as $value) {
                $idEmail = $value['email_idemail'];
            }

            //consulta se possui e-mail registrado na tabela email
            $sEmail->setNomeCampo('idemail');
            $sEmail->setValorCampo($idEmail);
            $sEmail->consultar('tMenu4_2_4_1.php-2');

            //armazena os dados da consulta
            foreach ($sEmail->mConexao->getRetorno() as $value) {
                $email = $value['nomenclatura'];
            }
        }

        $sConfiguracao = new sConfiguracao();
    }
    
    if (isset($_GET['campo'])) {
    $sNotificacao = new sNotificacao($_GET['codigo']);
    switch ($_GET['campo']) {
        case 'setor':
            if ($_GET['codigo'] == 'S1') {
                $alertaSetor = ' is-valid';
            } else {
                $alertaSetor = ' is-warning';
            }
            break;
        case 'endereco':
            if ($_GET['codigo'] == 'S1') {
                $alertaEndereco = ' is-valid';
            } else {
                $alertaEndereco = ' is-warning';
            }
            break;
        case 'email':
            if ($_GET['codigo'] == 'S1') {
                $alertaEmail = ' is-valid';
            } else {
                $alertaEmail = ' is-warning';
            }
            break;
        case 'telefone':
            if ($_GET['codigo'] == 'S1') {
                $alertaTelefone = ' is-valid';
            } else {
                $alertaTelefone = ' is-warning';
            }
            break;
    }

    //cria as variáveis da notificação
    $tipo = $sNotificacao->getTipo();
    $titulo = $sNotificacao->getTitulo();
    $mensagem = $sNotificacao->getMensagem();
    }
}
?>        
<div class="container-fluid">
    <div class="row">
        <!-- left column -->
        <!--registro setor-->
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><?php echo $opcao; ?></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- form start -->                
                <div class="card-body">
                    <div class="row">                      
                        <div class="form-group col-md-2">
                            <label for="setor">Setor</label>
                            <input type="text" class="form-control<?php echo isset($alertaSetor) ? $alertaSetor : ''; ?>" name="setor" value="<?php echo $setor; ?>" form="f1" required="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="endereco">Endereço</label>
                            <input type="text" class="form-control<?php echo isset($alertaEndereco) ? $alertaEndereco : ''; ?>" name="endereco" value="<?php echo $endereco; ?>" form="f1" required="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="endereco">E-mail</label>
                            <input type="email" class="form-control<?php echo isset($alertaEmail) ? $alertaEmail : ''; ?>" name="email" value="<?php echo isset($email) ? $email : ''; ?>" form="f1">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="telefone">Telefone</label>
                            <input type="text" class="form-control<?php echo isset($alertaTelefone) ? $alertaTelefone : ''; ?>" name="telefone" value="<?php echo isset($telefoneTratado) ? $telefoneTratado : ''; ?>" form="f1" data-inputmask='"mask": "(99) 9 9999-9999"' data-mask inputmode="text">
                        </div>
                        <div class="form-group">
                            <label>WhatsApp?</label>
                            <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                <input class="custom-control-input" type="checkbox" name="whatsApp" id="whatsApp" 
                                    <?php
                                    if(isset($whatsApp)){
                                        echo $whatsApp == 1 ? 'checked=""' : ''; 
                                    }
                                    ?>                                       
                                onclick="decisao();" form="f1">
                                <label class="custom-control-label" for="whatsApp">
                                    <div class="conteudo" name="conteudo" id="conteudo">
                                    <?php 
                                    if(isset($whatsApp)){
                                        echo $whatsApp == 1 ? 'Sim' : 'Não';
                                    }    
                                    ?>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
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
                <form action="<?php echo $sConfiguracao->getDiretorioControleSuporte(); ?>sAlterarSetor.php" method="post" id="f1" name="f1" enctype="multipart/form-data">
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <input type="hidden" name="idSetor" id="idSetor" value="<?php echo $idSetor; ?>">
                        <input type="hidden" name="idTelefone" id="idTelefone" value="<?php echo $idTelefone ? $idTelefone : ''; ?>">
                        <input type="hidden" name="acao" id="acao" value="alterar">
                        <input type="hidden" name="pagina" value="tMenu4_2_4_1.php">
                        <button type="submit" class="btn btn-primary">Alterar</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
<script>
    function decisao() {
        if (document.getElementById('whatsApp').checked) {
            document.getElementById('conteudo').innerHTML = 'Sim';
        } else {
            document.getElementById('conteudo').innerHTML = 'Não';
        }
    }
</script>