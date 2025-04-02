<?php

use App\sistema\acesso\{
    sTratamentoDados,
    sUsuario,
    sConfiguracao,
    sEmail,
    sSair
};
use App\sistema\suporte\{
    sProtocolo,
    sEtapa,
    sCategoria,
    sEquipamento,
    sMarca,
    sModelo,
    sAmbiente,
    sLocal,
    sPrioridade
};

if (isset($_POST['pagina']) &&
        isset($_POST['ano']) &&
        isset($_POST['acao'])) {
    $ano = $_POST['ano'];
    $pagina = $_POST['pagina'];
    $acao = $_POST['acao'];
} else {
    //solicitar saída com tentativa de violação
    $sSair = new sSair();
    $sSair->verificar('0');
}


//consulta os dados para apresentar na tabela
if ($_SESSION['credencial']['nivelPermissao'] < 2) {
    $sProtocolo = new sProtocolo();
    $sProtocolo->setNomeCampo('usuario_idusuario');
    $sProtocolo->setValorCampo($_SESSION['credencial']['idUsuario']);
    $sProtocolo->consultar('tMenu6_1.php');
} else {
    $sProtocolo = new sProtocolo();
    $sProtocolo->setNomeCampo('dataHoraAbertura');
    $sProtocolo->setValorCampo($ano);
    $sProtocolo->consultar('tMenu6_1.php-2');
}
?>
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Tickets Encerrados</h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table name="tabelaMenu6_1" id="tabelaMenu6_1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Protocolo n.º</th>
                    <th>Abertura</th>
                    <th>Encerramento</th>
                    <th>Solicitante</th>
                    <th>Patrimônio</th>
                    <th>Categoria</th>
                    <th>Marca</th>
                    <th>Modelo</th>                    
                    <th>Descrição</th>
                    <th>Secretaria</th>    
                    <th>Responsável</th>
                    <th>Visualizar</th>
                </tr>
            </thead>
            <tbody>
<?php
if ($sProtocolo->getValidador()) {
    foreach ($sProtocolo->mConexao->getRetorno() as $key => $value) {
        $data = date('Y', strtotime($value['dataHoraAbertura']));
        if ($data == $ano) {
            if ($value['dataHoraEncerramento'] != null) {

                //armazena o id do solicitante para criptografia
                $seguranca = base64_encode($value['usuario_idusuario']);

                //campo secretaria
                $secretaria = $value['secretaria'];

                //tratar os dados para imprimir na tabela dinâmica
                //campo data e hora da abertura
                $sTratamentoDataAbertura = new sTratamentoDados($value['dataHoraAbertura']);
                $dataAberturaTratada = $sTratamentoDataAbertura->tratarData();

                //campo data e hora da abertura
                if (!is_null($value['dataHoraEncerramento'])) {
                    $sTratamentoDataEncerramento = new sTratamentoDados($value['dataHoraEncerramento']);
                    $dataEncerramentoTratada = $sTratamentoDataEncerramento->tratarData();
                } else {
                    $dataEncerramentoTratada = '--/--/---- --:--:--';
                }

                //campo protocolo
                $ano = date("Y", strtotime(str_replace('-', '/', $value['dataHoraAbertura'])));
                $protocolo = str_pad($value['idprotocolo'], 5, 0, STR_PAD_LEFT);
                $protocolo = $ano . $protocolo;

                //campo do requerente ou solicitante
                $nomeRequerente = $value['nomeDoRequerente'] . ' ' . $value['sobrenomeDoRequerente'];
                $sUsuario = new sUsuario();
                $sUsuario->setNomeCampo('idusuario');
                $sUsuario->setValorCampo($value['usuario_idusuario']);
                $sUsuario->consultar('tMenu2_2.php');

                foreach ($sUsuario->mConexao->getRetorno() as $dadosUsuario) {
                    $nomeSolicitante = $dadosUsuario['nome'] . ' ' . $dadosUsuario['sobrenome'];
                    $idEmail = $dadosUsuario['email_idemail'];
                }

                //dados do email
                $sEmail = new sEmail('', '');
                $sEmail->setNomeCampo('idemail');
                $sEmail->setValorCampo($idEmail);
                $sEmail->consultar('tMenu2_2.php');

                foreach ($sEmail->mConexao->getRetorno() as $dadosEmail) {
                    $email = $dadosEmail['nomenclatura'];
                }

                $email == $value['emailDoRequerente'] ? $requerente = false : $requerente = true;

                $requerente ? $nome = $nomeRequerente . '<br />por <i>' . $nomeSolicitante . '</i>' : $nome = $nomeSolicitante;

                //campo telefone
                $sTratamentoTelefone = new sTratamentoDados($value['telefoneDoRequerente']);
                $telefoneTratado = $sTratamentoTelefone->tratarTelefone();

                //campos da tabela etapa
                /* ATENÇÃO-------------------------------------//
                 * se não tiver etapa para um protocolo, não será impressa e gerará o seguinte erro:
                 * Fatal error: Uncaught Error: Typed property App\modelo\mConexao::$retorno must 
                 * not be accessed before initialization in /home/itapoaap/public_html/App/modelo/mConexao.php:476
                 * Stack trace: #0 /home/itapoaap/public_html/App/telas/suporte/tMenu2_2.php(385):
                 * App\modelo\mConexao->getRetorno() #1 /home/itapoaap/public_html/App/telas/acesso/tPainel.php(625):
                 * require_once('/home/itapoaap/...') #2 {main} thrown in /home/itapoaap/public_html/App/modelo/mConexao.php on line 476
                 * verifique se há algum protocolo sem etapa vinculada
                 * 
                 */
                $idProtocolo = $value['idprotocolo'];
                $sEtapa = new sEtapa();
                $sEtapa->setNomeCampo('protocolo_idprotocolo');
                $sEtapa->setValorCampo($idProtocolo);
                $sEtapa->consultar('tMenu2_2.php');

                foreach ($sEtapa->mConexao->getRetorno() as $key => $value) {
                    $idEquipamento = $value['equipamento_idequipamento'];
                    $descricao = $value['descricao'];
                    $idLocal = $value['local_idlocal'];
                    $idPrioridade = $value['prioridade_idprioridade'];
                    $numero = $value['numero'];
                    $idResponsavel = $value['usuario_idusuario'];
                }

                //cria uma hash para o id do protocolo
                $idProtocoloCriptografado = base64_encode($idProtocolo);

                //dados do equipamento                        
                $sEquipamento = new sEquipamento();
                $sEquipamento->setNomeCampo('idequipamento');
                $sEquipamento->setValorCampo($idEquipamento);
                $sEquipamento->consultar('tMenu2_2.php');

                foreach ($sEquipamento->mConexao->getRetorno() as $key => $value) {
                    $patrimonio = $value['patrimonio'];
                    $idCategoria = $value['categoria_idcategoria'];
                    $idModelo = $value['modelo_idmodelo'];
                    $idAmbiente = $value['ambiente_idambiente'];
                }

                //campo categoria
                $sCategoria = new sCategoria();
                $sCategoria->setNomeCampo('idcategoria');
                $sCategoria->setValorCampo($idCategoria);
                $sCategoria->consultar('tMenu2_2.php');

                foreach ($sCategoria->mConexao->getRetorno() as $key => $value) {
                    $categoria = $value['nomenclatura'];
                }

                //campo modelo
                $sModelo = new sModelo();
                $sModelo->setNomeCampo('idmodelo');
                $sModelo->setValorCampo($idModelo);
                $sModelo->consultar('tMenu2_2.php');

                foreach ($sModelo->mConexao->getRetorno() as $key => $value) {
                    $modelo = $value['nomenclatura'];
                    $idMarca = $value['marca_idmarca'];
                }

                //campo marca
                $sMarca = new sMarca();
                $sMarca->setNomeCampo('idmarca');
                $sMarca->setValorCampo($idMarca);
                $sMarca->consultar('tMenu2_2.php');

                foreach ($sMarca->mConexao->getRetorno() as $key => $value) {
                    $marca = $value['nomenclatura'];
                }

                //campo ambiente
                $sAmbiente = new sAmbiente();
                $sAmbiente->setNomeCampo('idambiente');
                $sAmbiente->setValorCampo($idAmbiente);
                $sAmbiente->consultar('tMenu2_2.php');

                foreach ($sAmbiente->mConexao->getRetorno() as $key => $value) {
                    $ambiente = $value['nomenclatura'];
                }

                //campo local
                $sLocal = new sLocal();
                $sLocal->setNomeCampo('idlocal');
                $sLocal->setValorCampo($idLocal);
                $sLocal->consultar('tMenu2_2.php');

                foreach ($sLocal->mConexao->getRetorno() as $key => $value) {
                    $local = $value['nomenclatura'];
                }

                //campo prioridade
                $sPrioridade = new sPrioridade();
                $sPrioridade->setNomeCampo('idprioridade');
                $sPrioridade->setValorCampo($idPrioridade);
                $sPrioridade->consultar('tMenu2_2.php');

                foreach ($sPrioridade->mConexao->getRetorno() as $key => $value) {
                    $prioridade = $value['nomenclatura'];
                }

                //obter dados do responsável pelo suporte
                if ($idResponsavel) {
                    $sResponsavel = new sUsuario();
                    $sResponsavel->setNomeCampo('idusuario');
                    $sResponsavel->setValorCampo($idResponsavel);
                    $sResponsavel->consultar('tMenu2_2.php');

                    foreach ($sResponsavel->mConexao->getRetorno() as $value) {
                        $nomeResponsavel = $value['nome'];
                        $sobrenomeResponsavel = $value['sobrenome'];
                        $responsavel = $nomeResponsavel . ' ' . $sobrenomeResponsavel;
                    }
                } else {
                    $responsavel = '--';
                }


                //instancia as configurações do sistema
                $sConfiguracao = new sConfiguracao();
                $diretorio = $sConfiguracao->getDiretorioVisualizacaoAcesso();

                //altera a cor das marcações da prioridade
                $sTratamentoPrioridade = new sTratamentoDados($prioridade);
                $dadosPrioridade = $sTratamentoPrioridade->corPrioridade();
                $posicao = $dadosPrioridade[0];
                $cor = $dadosPrioridade[1];

                //verifica se já possui responsável agregado
                if ($numero < 2) {
                    $atribuirTicket = '<i class="fas fa-receipt mr-1"></i><a href="' . $sConfiguracao->getDiretorioVisualizacaoAcesso() . 'tPainel.php?menu=2_2_3&protocolo=' . $idProtocoloCriptografado . '"> Atribuir</a><br />';
                } else {
                    $atribuirTicket = '<i class="fas fa-hands-helping mr-1"></i> Em Andamento<br />';
                }


                echo <<<HTML
                        <tr>
                            <td>{$protocolo}</td>
                            <td>{$dataAberturaTratada}</td>
                            <td>{$dataEncerramentoTratada}</td>
                            <td>{$nome}</td>
                            <td>{$patrimonio}</td>
                            <td>{$categoria}</td>
                            <td>{$marca}</td>
                            <td>{$modelo}</td>
                            <td>{$descricao}</td>
                            <td>{$secretaria}</td> 
                            <td>{$responsavel}</td>
                            <td>
                                <i class="fas fa-search mr-1"></i>
                                <a href="{$diretorio}tPainel.php?menu=6_1_2&protocolo={$idProtocoloCriptografado}&seguranca={$seguranca}">
                                    Visualizar
                                </a>                                
                            </td>
                        </tr>
HTML;
            }
        }
    }
}
?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Protocolo n.º</th>
                    <th>Abertura</th>
                    <th>Encerramento</th>
                    <th>Solicitante</th>
                    <th>Patrimônio</th>
                    <th>Categoria</th>
                    <th>Marca</th>
                    <th>Modelo</th>                    
                    <th>Descrição</th>
                    <th>Secretaria</th>    
                    <th>Responsável</th>
                    <th>Visualizar</th>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- /.card-body -->
</div>
<script>
    //função para alterar texto da checkbox
    function decisao() {
        if (document.getElementById('suportesEncerrados').checked) {
            document.getElementById('conteudo').innerHTML = 'Sim';
        } else {
            document.getElementById('conteudo').innerHTML = 'Não';
        }
    }
</script>
<script>
    $(function () {
        $("#tabelaMenu6_1").DataTable({
            language: {url: "https://itapoa.app.br/vendor/dataTable_pt_br/dataTable_pt_br.json"},
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            initComplete: function () {
                this.api()
                        .buttons()
                        .container()
                        .appendTo(" #tabelaMenu6_1_wrapper .col-md-6:eq(0)");
            }
        });
    });
</script>