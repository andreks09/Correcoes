<?php

use App\sistema\acesso\{
    sTratamentoDados,
    sConfiguracao,
    sUsuario,
    sSecretaria,
    sDepartamento,
    sCoordenacao,
    sSetor
};
use App\sistema\suporte\{
    sProtocolo,
    sPrioridade,
    sEtapa,
    sEquipamento,
    sCategoria,
    sModelo,
    sMarca,
    sTensao,
    sCorrente,
    sSistemaOperacional,
    sAmbiente,
    sLocal
};

$seguranca = $_POST['seguranca'];
$idProtocolo = $_POST['idProtocolo'];

//consulta os dados para apresentar na tabela
$sProtocolo = new sProtocolo();
$sProtocolo->setNomeCampo('idprotocolo');
$sProtocolo->setValorCampo($idProtocolo);
$sProtocolo->consultar('tMenu2_2_2.php');

$sConfiguracao = new sConfiguracao();

//consulta os dados da secretaria
$sSecretaria = new sSecretaria(0);
$sSecretaria->consultar('tMenu2_2_2.php');

//dados do departamento
$sDepartamento = new sDepartamento(0);
$sDepartamento->consultar('tMenu2_2_2.php');

//dados do departamento
$sCoordenacao = new sCoordenacao(0);
$sCoordenacao->consultar('tMenu2_2_2.php');

//dados do departamento
$sSetor = new sSetor(0);
$sSetor->consultar('tMenu2_2_2.php');

if ($sProtocolo->getValidador()) {
    foreach ($sProtocolo->mConexao->getRetorno() as $value) {
        //dados do protocolo
        $secretaria = $value['secretaria'];
        $departamento = $value['departamento'];
        $coordenacao = $value['coordenacao'];
        $setor = $value['setor'];

        //dados do usuario
        $sUsuario = new sUsuario();
        $sUsuario->setNomeCampo('idusuario');
        $sUsuario->setValorCampo($value['usuario_idusuario']);
        $sUsuario->consultar('tMenu2_2_1.php');

        //campo do requerente ou solicitante
        $nome = $value['nome'];
        $sobrenome = $value['sobrenome'];

        //demais dados do protocolo
        $secretaria = $value['secretaria'];
        $departamento = $value['departamento'];
        $coordenacao = $value['coordenacao'];
        $setor = $value['setor'];
        $telefone = $value['telefoneDoRequerente'];
        $whatsApp = $value['whatsAppDoRequerente'];
        $email = $value['emailDoRequerente'];

        //trata os nomes do solicitante e requerente
        if ($nomeSolicitante == $nomeRequerente) {
            $requerente = false;
            $nome = '<h3 class="profile-username text-center">' . $nomeSolicitante . '</h3>';
        } else {
            $requerente = true;
            $nome = '<h3 class="profile-username text-center">' . $nomeRequerente . '</h3><p class="text-muted text-center">por <i>' . $nomeSolicitante . '</i></p>';
        }

        //verifique se há algum protocolo sem etapa vinculada
        $idProtocolo = $value['idprotocolo'];
        $sEtapa = new sEtapa();
        $sEtapa->setNomeCampo('protocolo_idprotocolo');
        $sEtapa->setValorCampo($idProtocolo);
        $sEtapa->consultar('tMenu2_2_1.php');

        foreach ($sEtapa->mConexao->getRetorno() as $key => $value) {
            $idEquipamento = $value['equipamento_idequipamento'];
            $descricao = $value['descricao'];
            $idLocal = $value['local_idlocal'];
            $idPrioridade = $value['prioridade_idprioridade'];
        }

        //tratamento da data de abertura
        $sTratamentoDataAbertura = new sTratamentoDados($value['dataHoraAbertura']);
        $dataAberturaTratada = $sTratamentoDataAbertura->tratarData();

        //campo protocolo
        $ano = date("Y", strtotime(str_replace('-', '/', $value['dataHoraAbertura'])));
        $protocolo = str_pad($idProtocolo, 5, 0, STR_PAD_LEFT);
        $protocolo = $ano . $protocolo;

        //tratamento da data de encerramento
        if (!is_null($value['dataHoraEncerramento'])) {
            $sTratamentoDataEncerramento = new sTratamentoDados($value['dataHoraEncerramento']);
            $dataEncerramentoTratada = $sTratamentoDataEncerramento->tratarData();
        } else {
            $dataEncerramentoTratada = '--/--/---- --:--:--';
        }

        //campo prioridade
        $sPrioridade = new sPrioridade();
        $sPrioridade->setNomeCampo('idprioridade');
        $sPrioridade->setValorCampo($idPrioridade);
        $sPrioridade->consultar('tMenu2_2_1.php');

        foreach ($sPrioridade->mConexao->getRetorno() as $key => $value) {
            $prioridade = $value['nomenclatura'];
        }

        //altera a cor das marcações da prioridade
        $sTratamentoPrioridade = new sTratamentoDados($prioridade);
        $dadosPrioridade = $sTratamentoPrioridade->corPrioridade();
        $posicao = $dadosPrioridade[0];
        $cor = $dadosPrioridade[1];

        foreach ($sPrioridade->mConexao->getRetorno() as $key => $value) {
            $prioridade = $value['nomenclatura'];
        }

        //altera a cor das marcações da prioridade
        $sTratamentoPrioridade = new sTratamentoDados($prioridade);
        $dadosPrioridade = $sTratamentoPrioridade->corPrioridade();
        $posicao = $dadosPrioridade[0];
        $cor = $dadosPrioridade[1];

        //tratamento do telefone
        $sTratamentoTelefone = new sTratamentoDados($telefone);
        $telefoneTratado = $sTratamentoTelefone->tratarTelefone();

        //dados do equipamento
        $sEquipamento = new sEquipamento();
        $sEquipamento->setNomeCampo('idequipamento');
        $sEquipamento->setValorCampo($idEquipamento);
        $sEquipamento->consultar('tMenu2_2_1.php');

        foreach ($sEquipamento->mConexao->getRetorno() as $value) {
            $patrimonio = $value['patrimonio'];
            $idCategoria = $value['categoria_idcategoria'];
            $idModelo = $value['modelo_idmodelo'];
            $etiqueta = $value['etiquetaDeServico'];
            $serie = $value['numeroDeSerie'];
            $idTensao = $value['tensao_idtensao'];
            $idCorrente = $value['corrente_idcorrente'];
            $idSistemaOperacional = $value['sistemaOperacional_idsistemaOperacional'];
            $idAmbiente = $value['ambiente_idambiente'];
        }

        //dados da categoria
        $sCategoria = new sCategoria();
        $sCategoria->setNomeCampo('idcategoria');
        $sCategoria->setValorCampo($idCategoria);
        $sCategoria->consultar('tMenu2_2_1.php');

        foreach ($sCategoria->mConexao->getRetorno() as $value) {
            $categoria = $value['nomenclatura'];
        }

        //dados da modelo
        $sModelo = new sModelo();
        $sModelo->setNomeCampo('idmodelo');
        $sModelo->setValorCampo($idModelo);
        $sModelo->consultar('tMenu2_2_1.php');

        foreach ($sModelo->mConexao->getRetorno() as $value) {
            $idMarca = $value['marca_idmarca'];
            $modelo = $value['nomenclatura'];
        }

        //dados da marca
        $sMarca = new sMarca();
        $sMarca->setNomeCampo('idmarca');
        $sMarca->setValorCampo($idMarca);
        $sMarca->consultar('tMenu2_2_1.php');

        foreach ($sMarca->mConexao->getRetorno() as $value) {
            $marca = $value['nomenclatura'];
        }

        //dados da tensao
        $sTensao = new sTensao();
        $sTensao->setNomeCampo('idtensao');
        $sTensao->setValorCampo($idTensao);
        $sTensao->consultar('tMenu2_2_1.php');

        foreach ($sTensao->mConexao->getRetorno() as $value) {
            $tensao = $value['nomenclatura'];
        }

        //dados da corrente
        $sCorrente = new sCorrente();
        $sCorrente->setNomeCampo('idcorrente');
        $sCorrente->setValorCampo($idCorrente);
        $sCorrente->consultar('tMenu2_2_1.php');

        foreach ($sCorrente->mConexao->getRetorno() as $value) {
            $corrente = $value['nomenclatura'];
        }

        //dados da sistemaOperacional
        $sSistemaOperacional = new sSistemaOperacional();
        $sSistemaOperacional->setNomeCampo('idsistemaOperacional');
        $sSistemaOperacional->setValorCampo($idSistemaOperacional);
        $sSistemaOperacional->consultar('tMenu2_2_1.php');

        foreach ($sSistemaOperacional->mConexao->getRetorno() as $value) {
            $sistemaOperacional = $value['nomenclatura'];
        }

        //dados da ambiente
        $sAmbiente = new sAmbiente();
        $sAmbiente->setNomeCampo('idambiente');
        $sAmbiente->setValorCampo($idAmbiente);
        $sAmbiente->consultar('tMenu2_2_1.php');

        foreach ($sAmbiente->mConexao->getRetorno() as $value) {
            $ambiente = $value['nomenclatura'];
        }
    }
}

if ($_POST['formulario'] == 'f1') {
    ?>
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-<?php echo $cor; ?> card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Protocolo n.º: <?php echo $protocolo; ?></h3>
                    </div>
                    <!-- form start -->
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-1">
                                <label for="nome">Nome</label>
                                <input type="text" class="form-control<?php echo isset($alertaNome) ? $alertaNome : ''; ?>" name="nome" id="nome" value="<?php echo $nome; ?>" required="" form="f1">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="sobrenome">Sobrenome</label>
                                <input class="form-control<?php echo isset($alertaSobrenome) ? $alertaSobrenome : ''; ?>" type="text" name="sobrenome" id="sobrenome" value="<?php echo $sobrenome; ?>" required="" form="f1">
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>Sexo</label>
                                    <select class="form-control<?php echo isset($alertaSexo) ? $alertaSexo : ''; ?>" name="sexo" id="sexo" required="" form="f1">
                                        <option value="M" <?php echo $_SESSION['credencial']['sexo'] == 'Masculino' ? 'selected=""' : ''; ?>>Masculino</option>
                                        <option value="F" <?php echo $_SESSION['credencial']['sexo'] == 'Feminino' ? 'selected=""' : ''; ?>>Feminino</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="email">E-mail</label>
                                <input type="email" class="form-control<?php echo isset($alertaEmail) ? $alertaEmail : ''; ?>" name="email" id="email" value="<?php echo $email ?>" required="" form="f1">                        </div>
                            <div class="form-group col-md-2">
                                <label for="telefone">Telefone</label>
                                <input type="telefone" class="form-control<?php echo isset($alertaTelefone) ? $alertaTelefone : ''; ?>" name="telefone" id="telefone" value="<?php echo $telefone ?>" form="f1">
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>WhatsApp</label>
                                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input class="custom-control-input" type="checkbox" name="whatsApp" id="whatsApp" <?php echo $whatsApp ? 'checked=""' : ''; ?>  onclick="decisao();" form="f1">
                                        <label class="custom-control-label" for="whatsApp">
                                            <div class="conteudo" name="conteudo" id="conteudo"><?php echo $whatsApp ? 'Sim' : 'Não' ?></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Secretaria</label>
                                    <select class="form-control" name="secretaria" id="secretaria" required="" form="f1">
                                        <?php
                                        foreach ($sSecretaria->mConexao->getRetorno() as $value) {
                                            echo '<option value="' . $value['idsecretaria'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            if ($secretaria == $value['nomenclatura']) {
                                                echo '<option value="' . $value['idsecretaria'] . '" selected="">' . $value['nomenclatura'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="departamento">Departamento</label>
                                    <select class="form-control" name="departamento" id="departamento" required="" form="f1">
                                        <option value="0" selected="">--</option>
                                        <?php
                                        foreach ($sDepartamento->mConexao->getRetorno() as $value) {
                                            echo '<option value="' . $value['iddepartamento'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            if ($departamento == $value['nomenclatura']) {
                                                echo '<option value="' . $value['iddepartamento'] . '" selected="">' . $value['nomenclatura'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Coordenação</label>
                                    <select class="form-control" name="coordenacao" id="coordenacao" required="" form="f1">
                                        <option value="0" selected="">--</option>
                                        <?php
                                        foreach ($sCoordenacao->mConexao->getRetorno() as $value) {
                                            echo '<option value="' . $value['idcoordenacao'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            if ($coordenacao == $value['nomenclatura']) {
                                                echo '<option value="' . $value['idcoordenacao'] . '" selected="">' . $value['nomenclatura'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="setor">Setor</label>
                                    <select class="form-control" name="setor" id="setor" required="" form="f1">
                                        <option value="0" selected="">--</option>
                                        <?php
                                        foreach ($sSetor->mConexao->getRetorno() as $value) {
                                            echo '<option value="' . $value['idsetor'] . '"' . $atributo . ' >' . $value['nomenclatura'] . '</option>';
                                            if ($setor == $value['nomenclatura']) {
                                                echo '<option value="' . $value['idsetor'] . '" selected="">' . $value['nomenclatura'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                            <!-- próxima build
                            <div class="form-group col-md-4">
                                <i class="far fa-images mr-1"></i><b> Print</b>
                                <a class="float-right">
                                    <img src="http://localhost/SSPMI/App/telas/suporte/img/2023000101.png" width="150px" height="100px" alt="..." data-toggle="modal" data-target="#modal-xl">
                                    <img src="https://placehold.it/150x100" alt="...">
                                </a>
                            </div>
                            -->
                    </div>
                    <!-- /.card-body -->
                    <form action="<?php echo $sConfiguracao->getDiretorioControleSuporte() ?>sAlterarSuporte.php" method="post" enctype="multipart/form-data" name="f1" id="f1">
                        <input type="hidden" value="f1" name="formulario" id="formulario" form="f1">
                        <input type="hidden" value="alterar" name="acao" id="acao" form="f1">
                        <input type="hidden" value="menu2_2_2" name="pagina" id="tMenu2_2_2.php" form="f1">

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Alterar</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
    <?php
}
?>
<script type="text/javascript">
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
    });
</script>
<script>
    //função para alterar texto da checkbox
    function decisao(){
       if (document.getElementById('whatsApp').checked) {
            document.getElementById('conteudo').innerHTML = 'Sim';
        } else {
            document.getElementById('conteudo').innerHTML = 'Não';
        }
    }
</script>
