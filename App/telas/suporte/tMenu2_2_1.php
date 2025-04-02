<?php

use App\sistema\acesso\{
    sTratamentoDados,
    sConfiguracao,
    sSair,
    sUsuario,
    sEmail,
    sSecretaria,
    sDepartamento,
    sCoordenacao,
    sSetor,
    sTelefone
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

if (isset($_GET)) {
    $seguranca = base64_decode($_GET['seguranca']);
    $idProtocolo = base64_decode($_GET['protocolo']);
    //verifica se o id do usuário via GET é o mesmo da sessão
    if ($seguranca != $_SESSION['credencial']['idUsuario'] && $_SESSION['credencial']['nivelPermissao'] < 2) {
        //solicitar saída com tentativa de violação
        $sSair = new sSair();
        $sSair->verificar('0');
    }
}

//consulta os dados para apresentar na tabela
$sProtocolo = new sProtocolo();
$sProtocolo->setNomeCampo('idprotocolo');
$sProtocolo->setValorCampo($idProtocolo);
$sProtocolo->consultar('tMenu2_2_1.php');

$sConfiguracao = new sConfiguracao();

if ($sProtocolo->getValidador()) {
    foreach ($sProtocolo->mConexao->getRetorno() as $value) {

        //dados do usuario
        $sUsuario = new sUsuario();
        $sUsuario->setNomeCampo('idusuario');
        $sUsuario->setValorCampo($value['usuario_idusuario']);
        $sUsuario->consultar('tMenu2_2_1.php');

        foreach ($sUsuario->mConexao->getRetorno() as $dadosUsuario) {
            $nomeSolicitante = $dadosUsuario['nome'] . ' ' . $dadosUsuario['sobrenome'];
            $idEmail = $dadosUsuario['email_idemail'];
        }

        //dados do email
        $sEmail = new sEmail('', '');
        $sEmail->setNomeCampo('idemail');
        $sEmail->setValorCampo($idEmail);
        $sEmail->consultar('tMenu2_2_1.php-2');

        foreach ($sEmail->mConexao->getRetorno() as $dadosEmail) {
            $email = $dadosEmail['nomenclatura'];
        }

        $email == $value['emailDoRequerente'] ? $requerente = false : $requerente = true;

        $nomeRequerente = $value['nomeDoRequerente'] . ' ' . $value['sobrenomeDoRequerente'];

        //demais dados do protocolo
        $secretaria = $value['secretaria'];
        $departamento = $value['departamento'];
        $coordenacao = $value['coordenacao'];
        $setor = $value['setor'];
        $telefone = $value['telefoneDoRequerente'];
        $whatsApp = $value['whatsAppDoRequerente'];
        $email = $value['emailDoRequerente'];

        //busca os dados com base no nome da secretaria
        $sSecretaria = new sSecretaria(0);
        $sSecretaria->setNomeCampo('nomenclatura');
        $sSecretaria->setValorCampo($secretaria);
        $sSecretaria->consultar('tMenu2_2_1.php');

        foreach ($sSecretaria->mConexao->getRetorno() as $valorSecretaria) {
            $idSecretaria = $valorSecretaria['idsecretaria'];
            $enderecoSecretaria = $valorSecretaria['endereco'];
        }

        $sTelefone = new sTelefone(0, 0, '');
        $sTelefone->setNomeCampo('secretaria_idsecretaria');
        $sTelefone->setValorCampo($idSecretaria);
        $sTelefone->consultar('tMenu2_2_1.php');

        //se tiver telefone registrado para a secretaria então retorna os dados 
        if ($sTelefone->getValidador()) {
            foreach ($sTelefone->mConexao->getRetorno() as $valorTelefone) {
                $idTelefone = $valorTelefone['telefone_idtelefone'];
            }
            //busca os dados do telefone da secretaria
            $sTelefone = new sTelefone(0, 0, '');
            $sTelefone->setNomeCampo('idtelefone');
            $sTelefone->setValorCampo($idTelefone);
            $sTelefone->consultar('tMenu2_2_1.php-2');

            foreach ($sTelefone->mConexao->getRetorno() as $valorTelefone) {
                $telefoneSecretaria = $valorTelefone['numero'];
                $whatsAppSecretaria = $valorTelefone['whatsApp'];
            }
            //trata o telefone para imprimir
            $sTratamentoTelefone = new sTratamentoDados($telefoneSecretaria);
            $telefoneSecretariaTratado = $sTratamentoTelefone->tratarTelefone();
        } else {
            //caso não tenha telefone registrado
            $telefoneSecretariaTratado = '--';
        }

        //busca dados com base no nome do departamento
        if ($departamento != '--') {
            $sDepartamento = new sDepartamento(0);
            $sDepartamento->setNomeCampo('nomenclatura');
            $sDepartamento->setValorCampo($departamento);
            $sDepartamento->consultar('tMenu2_2_1.php');

            foreach ($sDepartamento->mConexao->getRetorno() as $valorDepartamento) {
                $idDepartamento = $valorDepartamento['iddepartamento'];
                $enderecoDepartamento = $valorDepartamento['endereco'];
            }

            $sTelefone = new sTelefone(0, 0, '');
            $sTelefone->setNomeCampo('departamento_iddepartamento');
            $sTelefone->setValorCampo($idDepartamento);
            $sTelefone->consultar('tMenu2_2_1.php-departamento');

            //se tiver telefone registrado para a departamento então retorna os dados 
            if ($sTelefone->getValidador()) {
                foreach ($sTelefone->mConexao->getRetorno() as $valorTelefone) {
                    $idTelefone = $valorTelefone['telefone_idtelefone'];
                }
                //busca os dados do telefone da departamento
                $sTelefone = new sTelefone(0, 0, '');
                $sTelefone->setNomeCampo('idtelefone');
                $sTelefone->setValorCampo($idTelefone);
                $sTelefone->consultar('tMenu2_2_1.php-2');

                foreach ($sTelefone->mConexao->getRetorno() as $valorTelefone) {
                    $telefoneDepartamento = $valorTelefone['numero'];
                    $whatsAppDepartamento = $valorTelefone['whatsApp'];
                }
                //trata o telefone para imprimir
                $sTratamentoTelefone = new sTratamentoDados($telefoneDepartamento);
                $telefoneDepartamentoTratado = $sTratamentoTelefone->tratarTelefone();
            } else {
                //caso não tenha telefone registrado
                $telefoneDepartamentoTratado = '--';
            }
        } else {
            //caso não tenha telefone registrado
            $telefoneDepartamentoTratado = '--';
        }

        //busca dados com base no nome do coordenacao
        if ($coordenacao != '--') {
            $sCoordenacao = new sCoordenacao(0);
            $sCoordenacao->setNomeCampo('nomenclatura');
            $sCoordenacao->setValorCampo($coordenacao);
            $sCoordenacao->consultar('tMenu2_2_1.php');

            foreach ($sCoordenacao->mConexao->getRetorno() as $valorCoordenacao) {
                $idCoordenacao = $valorCoordenacao['idcoordenacao'];
                $enderecoCoordenacao = $valorCoordenacao['endereco'];
            }

            $sTelefone = new sTelefone(0, 0, '');
            $sTelefone->setNomeCampo('coordenacao_idcoordenacao');
            $sTelefone->setValorCampo($idCoordenacao);
            $sTelefone->consultar('tMenu2_2_1.php-coordenacao');

            //se tiver telefone registrado para a coordenacao então retorna os dados 
            if ($sTelefone->getValidador()) {
                foreach ($sTelefone->mConexao->getRetorno() as $valorTelefone) {
                    $idTelefone = $valorTelefone['telefone_idtelefone'];
                }
                //busca os dados do telefone da coordenacao
                $sTelefone = new sTelefone(0, 0, '');
                $sTelefone->setNomeCampo('idtelefone');
                $sTelefone->setValorCampo($idTelefone);
                $sTelefone->consultar('tMenu2_2_1.php-2');

                foreach ($sTelefone->mConexao->getRetorno() as $valorTelefone) {
                    $telefoneCoordenacao = $valorTelefone['numero'];
                    $whatsAppCoordenacao = $valorTelefone['whatsApp'];
                }
                //trata o telefone para imprimir
                $sTratamentoTelefone = new sTratamentoDados($telefoneCoordenacao);
                $telefoneCoordenacaoTratado = $sTratamentoTelefone->tratarTelefone();
            } else {
                //caso não tenha telefone registrado
                $telefoneCoordenacaoTratado = '--';
            }
        } else {
            //caso não tenha telefone registrado
            $telefoneCoordenacaoTratado = '--';
        }

        //busca dados com base no nome do setor
        if ($setor != '--') {
            $sSetor = new sSetor(0);
            $sSetor->setNomeCampo('nomenclatura');
            $sSetor->setValorCampo($setor);
            $sSetor->consultar('tMenu2_2_1.php');

            foreach ($sSetor->mConexao->getRetorno() as $valorSetor) {
                $idSetor = $valorSetor['idsetor'];
                $enderecoSetor = $valorSetor['endereco'];
            }

            $sTelefone = new sTelefone(0, 0, '');
            $sTelefone->setNomeCampo('setor_idsetor');
            $sTelefone->setValorCampo($idSetor);
            $sTelefone->consultar('tMenu2_2_1.php-setor');

            //se tiver telefone registrado para a setor então retorna os dados 
            if ($sTelefone->getValidador()) {
                foreach ($sTelefone->mConexao->getRetorno() as $valorTelefone) {
                    $idTelefone = $valorTelefone['telefone_idtelefone'];
                }
                //busca os dados do telefone da setor
                $sTelefone = new sTelefone(0, 0, '');
                $sTelefone->setNomeCampo('idtelefone');
                $sTelefone->setValorCampo($idTelefone);
                $sTelefone->consultar('tMenu2_2_1.php-2');

                foreach ($sTelefone->mConexao->getRetorno() as $valorTelefone) {
                    $telefoneSetor = $valorTelefone['numero'];
                    $whatsAppSetor = $valorTelefone['whatsApp'];
                }
                //trata o telefone para imprimir
                $sTratamentoTelefone = new sTratamentoDados($telefoneSetor);
                $telefoneSetorTratado = $sTratamentoTelefone->tratarTelefone();
            } else {
                //caso não tenha telefone registrado
                $telefoneSetorTratado = '--';
            }
        } else {
            //caso não tenha telefone registrado
            $telefoneSetorTratado = '--';
        }

        $sEmail = new sEmail(0, 0, '');
        $sEmail->setNomeCampo('secretaria_idsecretaria');
        $sEmail->setValorCampo($idSecretaria);
        $sEmail->consultar('tMenu2_2_1.php');

        //se tiver email registrado para a secretaria então retorna os dados 
        if ($sEmail->getValidador()) {
            foreach ($sEmail->mConexao->getRetorno() as $valorEmail) {
                $idEmail = $valorEmail['email_idemail'];
            }
            //busca os dados do email da secretaria
            $sEmail = new sEmail(0, 0, '');
            $sEmail->setNomeCampo('idemail');
            $sEmail->setValorCampo($idEmail);
            $sEmail->consultar('tMenu2_2_1.php-2');

            foreach ($sEmail->mConexao->getRetorno() as $valorEmail) {
                $emailSecretaria = $valorEmail['nomenclatura'];
            }
        } else {
            //caso não tenha email registrado
            $emailSecretaria = '--';
        }

        if ($departamento != '--') {
            $sEmail = new sEmail(0, 0, '');
            $sEmail->setNomeCampo('departamento_iddepartamento');
            $sEmail->setValorCampo($idDepartamento);
            $sEmail->consultar('tMenu2_2_1.php-departamento');

            //se tiver email registrado para a departamento então retorna os dados 
            if ($sEmail->getValidador()) {
                foreach ($sEmail->mConexao->getRetorno() as $valorEmail) {
                    $idEmail = $valorEmail['email_idemail'];
                }
                //busca os dados do email da departamento
                $sEmail = new sEmail(0, 0, '');
                $sEmail->setNomeCampo('idemail');
                $sEmail->setValorCampo($idEmail);
                $sEmail->consultar('tMenu2_2_1.php-2');

                foreach ($sEmail->mConexao->getRetorno() as $valorEmail) {
                    $emailDepartamento = $valorEmail['nomenclatura'];
                }
            } else {
                //caso não tenha email registrado
                $emailDepartamento = '--';
            }
        }else{
            //caso não tenha email registrado
            $emailDepartamento = '--';
        }

        if ($coordenacao != '--') {
            $sEmail = new sEmail(0, 0, '');
            $sEmail->setNomeCampo('coordenacao_idcoordenacao');
            $sEmail->setValorCampo($idCoordenacao);
            $sEmail->consultar('tMenu2_2_1.php-coordenacao');

            //se tiver email registrado para a coordenacao então retorna os dados 
            if ($sEmail->getValidador()) {
                foreach ($sEmail->mConexao->getRetorno() as $valorEmail) {
                    $idEmail = $valorEmail['email_idemail'];
                }
                //busca os dados do email da coordenacao
                $sEmail = new sEmail(0, 0, '');
                $sEmail->setNomeCampo('idemail');
                $sEmail->setValorCampo($idEmail);
                $sEmail->consultar('tMenu2_2_1.php-2');

                foreach ($sEmail->mConexao->getRetorno() as $valorEmail) {
                    $emailCoordenacao = $valorEmail['nomenclatura'];
                }
            } else {
                //caso não tenha email registrado
                $emailCoordenacao = '--';
            }
        }else{
            //caso não tenha email registrado
            $emailCoordenacao = '--';
        }

        if ($setor != '--') {
            $sEmail = new sEmail(0, 0, '');
            $sEmail->setNomeCampo('setor_idsetor');
            $sEmail->setValorCampo($idSetor);
            $sEmail->consultar('tMenu2_2_1.php-setor');

            //se tiver email registrado para a setor então retorna os dados 
            if ($sEmail->getValidador()) {
                foreach ($sEmail->mConexao->getRetorno() as $valorEmail) {
                    $idEmail = $valorEmail['email_idemail'];
                }
                //busca os dados do email da setor
                $sEmail = new sEmail(0, 0, '');
                $sEmail->setNomeCampo('idemail');
                $sEmail->setValorCampo($idEmail);
                $sEmail->consultar('tMenu2_2_1.php-2');

                foreach ($sEmail->mConexao->getRetorno() as $valorEmail) {
                    $emailSetor = $valorEmail['nomenclatura'];
                }
            } else {
                //caso não tenha email registrado
                $emailSetor = '--';
            }
        }else{
            //caso não tenha email registrado
            $emailSetor = '--';
        }

        $requerente ?
        $nome = '<h3 class="profile-username text-center">' . $nomeRequerente . '</h3><p class="text-muted text-center">por <i>' . $nomeSolicitante . '</i></p>' :
        $nome = '<h3 class="profile-username text-center">' . $nomeSolicitante . '</h3>';

        //verifique se há algum protocolo sem etapa vinculada
        $idProtocolo = $value['idprotocolo'];
        $sEtapa = new sEtapa();
        $sEtapa->setNomeCampo('protocolo_idprotocolo');
        $sEtapa->setValorCampo($idProtocolo);
        $sEtapa->consultar('tMenu2_2_1.php');

        $i = 0;
        foreach ($sEtapa->mConexao->getRetorno() as $key => $value) {
            $idEquipamento = $value['equipamento_idequipamento'];
            $descricao = $value['descricao'];
            $idLocal = $value['local_idlocal'];
            $idPrioridade = $value['prioridade_idprioridade'];

            $etapaReversa[$i] = $value;
            $i++;
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
} else {
    //notificar erro 
}

if ($whatsApp) {
    $whatsApp = '<a class="float-right"><b><i class="fab fa-whatsapp mr-1"></i></b></a>';
    $linkWhatsApp = '<a class="float-right" href="https://wa.me/55' . $telefone . '" target="_blank">' . $telefoneTratado . '</a>';
} else {
    $linkWhatsApp = '<a class="float-right">' . $telefoneTratado . '</a>';
    $whatsApp = '';
}

echo <<<HTML
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <!-- Profile Image -->
            <div class="card card-{$cor} card-outline">
                <div class="card-body box-profile">
                    <!--
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="{$sConfiguracao->getDiretorioPrincipal()}vendor/almasaeed2010/adminlte/dist/img/{$imagem}" alt="User profile picture">
                    </div>
                    -->
                    {$nome}
                    <ul class="list-group list-group-unbordered mb-4">
                        <li class="list-group-item">
                            <i class="fas fa-building mr-1"></i><b> Secretaria</b><a class="float-right">{$secretaria}</a><br/>
                            <i class="fas fa-phone mr-1"></i><b> Telefone</b><a class="float-right">{$telefoneSecretariaTratado}</a><br/>
                            <i class="fas fa-envelope-open-text mr-1"></i><b>E-mail</b> <a class="float-right"><div id="conteudo">{$emailSecretaria}</div></a>
                            <a class="float-right">
                                <button type="button" class="btn btn-tool" title="Copiar" onclick="copiarTexto();">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-house-user mr-1"></i><b> Departamento/ Unidade</b><a class="float-right">{$departamento}</a><br />
                            <i class="fas fa-phone mr-1"></i><b> Telefone</b><a class="float-right">{$telefoneDepartamentoTratado}</a><br/>
                            <i class="fas fa-envelope-open-text mr-1"></i><b>E-mail</b> <a class="float-right"><div id="conteudo">{$emailDepartamento}</div></a>
                            <a class="float-right">
                                <button type="button" class="btn btn-tool" title="Copiar" onclick="copiarTexto();">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-house-user mr-1"></i><b> Coordenação</b> <a class="float-right">{$coordenacao}</a><br />
                            <i class="fas fa-phone mr-1"></i><b> Telefone</b><a class="float-right">{$telefoneCoordenacaoTratado}</a><br/>
                            <i class="fas fa-envelope-open-text mr-1"></i><b>E-mail</b> <a class="float-right"><div id="conteudo">{$emailCoordenacao}</div></a>
                            <a class="float-right">
                                <button type="button" class="btn btn-tool" title="Copiar" onclick="copiarTexto();">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-house-user mr-1"></i><b> Setor</b> <a class="float-right">{$setor}</a><br />
                            <i class="fas fa-phone mr-1"></i><b> Telefone</b><a class="float-right">{$telefoneSetorTratado}</a><br/>
                            <i class="fas fa-envelope-open-text mr-1"></i><b>E-mail</b> <a class="float-right"><div id="conteudo">{$emailSetor}</div></a>
                            <a class="float-right">
                                <button type="button" class="btn btn-tool" title="Copiar" onclick="copiarTexto();">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-phone mr-1"></i><b> Telefone</b>                            
                            {$linkWhatsApp}                            
                            {$whatsApp}<br />   
                            <i class="fas fa-envelope-open-text mr-1"></i>
                            <b>E-mail</b> 
                            <a class="float-right">  
                                <div id="conteudo">{$email}</div>
                            </a>
                            <a class="float-right">
                                <button type="button" class="btn btn-tool" title="Copiar" onclick="copiarTexto();">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-footer">  
                </div>
                
                <!-- /.card-body -->
            </div>
        </div>
        <!-- /.col -->
        <div class="col-md-4">
            <div class="card card-{$cor} card-outline">
                <div class="card-header">
                    <h3 class="card-title">Equipamento</h3>
                    <!-- /.card-tools -->
                </div>
                <div class="card-body box-profile">
                    <ul class="list-group list-group-unbordered mb-4">
                        <li class="list-group-item">
                            <i class="fas fa-barcode mr-1"></i><b> Patrimônio</b><a class="float-right">$patrimonio</a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-laptop mr-1"></i><b> Categoria</b><a class="float-right">$categoria</a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-tag mr-1"></i><b> Marca</b> <a class="float-right">$marca</a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-tags mr-1"></i><b> Modelo</b> <a class="float-right">$modelo</a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-qrcode mr-1"></i><b> Service Tag</b> <a class="float-right">$etiqueta</a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-barcode mr-1"></i><b> Número de Série</b> <a class="float-right">$serie</a><br />
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-bolt mr-1"></i><b> Tensão de Entrada</b> <a class="float-right">$tensao</a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-plug mr-1"></i><b> Corrente de Entrada</b> <a class="float-right">$corrente</a>
                        </li>
                        <li class="list-group-item">
                            <i class="fab fa-windows mr-1"></i><b> Sistema Operacional</b> <a class="float-right">$sistemaOperacional</a>
                        </li>
                        <li class="list-group-item">
                            <i class="fab fa-windows mr-1"></i><b> Ambiente</b> <a class="float-right">$ambiente</a>
                        </li>
                    </ul>
                </div>
                <div class="card-footer"> 
                    <form action="{$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=3_2_1" name="f2" id="f2" method="post">
                        <input type="hidden" name="menu" value="3_2_1" form="f2">
                        <input type="hidden" name="idEquipamento" value="{$idEquipamento}" form="f2">
HTML;
if ($_SESSION['credencial']['nivelPermissao'] > 1) {
    echo <<<HTML
                            <button type="submit" class="btn btn-primary float-left" form="f2">Alterar</button>
HTML;
}
echo <<<HTML
                        
                    </form>
                </div>  
                <!-- /.card-body -->
            </div>
        </div>        
        <div class="col-md-4">                    
HTML;

//inverte a ordem de ASC para DESC
$etapaReversa = array_reverse($etapaReversa, true);

$i = 0;
foreach ($etapaReversa as $value) {
    $numero = $value['numero'];
    $idPrioridadeEtapa = $value['prioridade_idprioridade'];
    $dataAberturaEtapa = $value['dataHoraAbertura'];
    $dataEncerramentoEtapa = $value['dataHoraEncerramento'];
    $acessoRemotoEtapa = $value['acessoRemoto'];
    $descricaoEtapa = $value['descricao'];
    $idLocalEtapa = $value['local_idlocal'];
    if (!is_null($value['usuario_idusuario'])) {
        $idUsuarioEtapa = $value['usuario_idusuario'];
    } else {
        $idUsuarioEtapa = 0;
    }

    $solucaoEtapa = $value['solucao'];

    //tratamento do para aparecer o status "atual" na etapa corrente
    if ($i < $numero) {
        $i = $numero;
        $recente = '(atual)';
    } else {
        $recente = '';
    }

    //buscar dados da prioridade
    $sPrioridadeEtapa = new sPrioridade();
    $sPrioridadeEtapa->setNomeCampo('idprioridade');
    $sPrioridadeEtapa->setValorCampo($idPrioridadeEtapa);
    $sPrioridadeEtapa->consultar('tMenu2_2_1.php');

    foreach ($sPrioridadeEtapa->mConexao->getRetorno() as $value) {
        $prioridadeEtapa = $value['nomenclatura'];
    }

    //tratamento da prioridade
    $sTratamentoPrioridadeEtapa = new sTratamentoDados($prioridadeEtapa);
    $dadosPrioridadeEtapa = $sTratamentoPrioridadeEtapa->corPrioridade();
    $posicaoEtapa = $dadosPrioridadeEtapa[0];
    $corEtapa = $dadosPrioridadeEtapa[1];

    //tratamento da data de abertura
    $sTratamentoDataAberturaEtapa = new sTratamentoDados($dataAberturaEtapa);
    $dataAberturaEtapaTratada = $sTratamentoDataAberturaEtapa->tratarData();

    //tratamento da data de encerramento
    if (!is_null($dataEncerramentoEtapa)) {
        $sTratamentoDataEncerramentoEtapa = new sTratamentoDados($dataEncerramentoEtapa);
        $dataEncerramentoEtapaTratada = $sTratamentoDataEncerramentoEtapa->tratarData();
        $icone = 'plus';
        $expandirCartao = 'collapsed-card';
    } else {
        $dataEncerramentoEtapaTratada = '--/--/---- --:--:--';
        $icone = 'minus';
        $expandirCartao = '';
    }

    //buscar dados da local
    $sLocalEtapa = new sLocal();
    $sLocalEtapa->setNomeCampo('idlocal');
    $sLocalEtapa->setValorCampo($idLocalEtapa);
    $sLocalEtapa->consultar('tMenu2_2_1.php');

    foreach ($sLocalEtapa->mConexao->getRetorno() as $value) {
        $localEtapa = $value['nomenclatura'];
    }

    //buscar dados da usuario
    if ($idUsuarioEtapa == 0) {
        $responsavelEtapa = '--';
    } else {
        $sUsuarioEtapa = new sUsuario();
        $sUsuarioEtapa->setNomeCampo('idusuario');
        $sUsuarioEtapa->setValorCampo($idUsuarioEtapa);
        $sUsuarioEtapa->consultar('tMenu2_2_1.php');
        $responsavelEtapa = $sUsuarioEtapa->getNome();
    }


    echo <<<HTML
            <div class="card card-$cor card-outline $expandirCartao">
                <div class="card-header">
                    <h3 class="card-title">Protocolo n.º: $protocolo - Etapa $numero $recente</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-$icone"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body box-profile">
                    <ul class="list-group list-group-unbordered mb-4">
                        <li class="list-group-item">
                            <i class="fas fa-route mr-1"></i><b> Etapa</b><a class="float-right">$numero</a>
                        </li>
                        <li class="list-group-item">
                            <i class="far fa-flag text-$corEtapa mr-1"></i><b> Prioridade</b><a class="float-right text-$corEtapa">$prioridadeEtapa</a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-clock mr-1"></i><b> Data e Hora - Abertura</b><a class="float-right">$dataAberturaEtapaTratada</a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-clock mr-1"></i><b> Data e Hora - Encerramento</b><a class="float-right">$dataEncerramentoEtapaTratada</a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-broadcast-tower mr-1"></i><b> Acesso Remoto</b> <a class="float-right">$acessoRemotoEtapa</a>
                        </li>
                        <!-- próxima build
                        <li class="list-group-item">
                            <i class="far fa-images mr-1"></i><b> Print</b>
                            <a class="float-right">
                                <img src="http://localhost/SSPMI/App/telas/suporte/img/2023000101.png" width="150px" height="100px" alt="..." data-toggle="modal" data-target="#modal-xl">
                                <img src="https://placehold.it/150x100" alt="...">
                            </a>
                        </li>
                        -->
                        <li class="list-group-item">
                            <i class="far fa-file-alt mr-1"></i><b> Descrição</b> <a class="float-right">$descricaoEtapa</a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-project-diagram mr-1"></i><b> Local</b> <a class="float-right">$localEtapa</a>
                        </li>
                        <li class="list-group-item">
                            <i class="far fa-id-card mr-1"></i><b> Responsável</b> <a class="float-right">$responsavelEtapa</a>
                        </li>
                        <li class="list-group-item">
                            <i class="fas far fa-file-alt mr-1"></i><b> Solução</b> <a class="float-right">$solucaoEtapa</a>
                        </li>
                        <!-- /.card-body -->
                    </ul>
                </div>
                <!-- /.card-body -->
                <div class="card-footer"> 
HTML;
    //se a etapa não foi encerrada mostre os botões
    if ($dataEncerramentoEtapaTratada == '--/--/---- --:--:--') {
        //se não for a primeira etapa somente os usuários com permissão maior que 1 poderão acessar os botões
        if ($numero > 1) {
            if ($_SESSION['credencial']['nivelPermissao'] == 2) {
                echo <<<HTML
                        <form action="{$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_2_1_3" name="f3_1" id="f3_1" method="post" enctype="multipart/form-data"></form>
                        <form action="{$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_2_1_3_1" name="f3_2" id="f3_2" method="post" enctype="multipart/form-data"></form>
                        <form action="{$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_2_1_3_2" name="f3_3" id="f3_3" method="post" enctype="multipart/form-data"></form>
                        
                        <input type="hidden" name="pagina" value="tMenu2_2_1.php" form="f3_1">
                        <input type="hidden" name="idProtocolo" value="$idProtocolo" form="f3_1">
                        <input type="hidden" name="etapa" value="$numero" form="f3_1">
                                
                        <input type="hidden" name="pagina" value="tMenu2_2_1.php" form="f3_3">
                        <input type="hidden" name="idProtocolo" value="$idProtocolo" form="f3_3">
                        <input type="hidden" name="etapa" value="$numero" form="f3_3">
HTML;
                //caso o protocolo pertença ao responsável
                if ($idUsuarioEtapa == $_SESSION['credencial']['idUsuario']) {
                    echo <<<HTML
                        <button type="submit" class="btn btn-primary" form="f3_1">Alterar</button>
                        <button type="submit" class="btn btn-primary float-right" form="f3_3">Encerrar</button>
HTML;
                }
            }
            if ($_SESSION['credencial']['nivelPermissao'] > 2) {
                echo <<<HTML
                        <form action="{$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_2_1_3" name="f3_1" id="f3_1" method="post" enctype="multipart/form-data"></form>
                        <form action="{$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_2_1_3_1" name="f3_2" id="f3_2" method="post" enctype="multipart/form-data"></form>
                        <form action="{$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_2_1_3_2" name="f3_3" id="f3_3" method="post" enctype="multipart/form-data"></form>
                           
                        <input type="hidden" name="pagina" value="tMenu2_2_1.php" form="f3_1">
                        <input type="hidden" name="idProtocolo" value="$idProtocolo" form="f3_1">
                        <input type="hidden" name="etapa" value="$numero" form="f3_1">
                        <button type="submit" class="btn btn-primary" form="f3_1">Alterar</button>  
                               
                        <input type="hidden" name="pagina" value="tMenu2_2_1.php" form="f3_2">
                        <input type="hidden" name="idProtocolo" value="$idProtocolo" form="f3_2">
                        <input type="hidden" name="etapa" value="$numero" form="f3_2">
                        <button type="submit" class="btn btn-primary" form="f3_2">Reatribuir</button>
                                
HTML;
                //caso o protocolo pertença ao responsável
                if ($idUsuarioEtapa == $_SESSION['credencial']['idUsuario']) {
                    echo <<<HTML
                        <input type="hidden" name="pagina" value="tMenu2_2_1.php" form="f3_3">
                        <input type="hidden" name="idProtocolo" value="$idProtocolo" form="f3_3">
                        <input type="hidden" name="etapa" value="$numero" form="f3_3">
                        <button type="submit" class="btn btn-primary float-right" form="f3_3">Encerrar</button>
HTML;
                }
            }
        } else {
            echo <<<HTML
                    <form action="{$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_2_1_3" name="f3_1" id="f3_1" method="post" enctype="multipart/form-data"></form>
                    <form action="{$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_2_1_3_1" name="f3_2" id="f3_2" method="post" enctype="multipart/form-data"></form>
                    <form action="{$sConfiguracao->getDiretorioVisualizacaoAcesso()}tPainel.php?menu=2_2_1_3_2" name="f3_3" id="f3_3" method="post" enctype="multipart/form-data"></form>
                    
                    <input type="hidden" name="pagina" value="tMenu2_2_1.php" form="f3_1">
                    <input type="hidden" name="idProtocolo" value="$idProtocolo" form="f3_1">
                    <input type="hidden" name="etapa" value="$numero" form="f3_1">
                    <button type="submit" class="btn btn-primary" form="f3_1">Alterar</button>
HTML;
            //caso o protocolo pertença ao responsável
            echo <<<HTML
                    <input type="hidden" name="pagina" value="tMenu2_2_1.php" form="f3_3">
                    <input type="hidden" name="idProtocolo" value="$idProtocolo" form="f3_3">
                    <input type="hidden" name="etapa" value="$numero" form="f3_3">
                    <button type="submit" class="btn btn-primary float-right" form="f3_3">Encerrar</button>
HTML;
        }
    }
    echo <<<HTML
                    <!--                    
                    <button type="submit" class="btn btn-primary float-right" data-toggle="modal" data-target="#modal2">Finalizar Suporte</button>
                    <form action="../../sistema/suporte/sAlterarSuporte.php" id="alterarSuporte" method="post">
                        <input type="hidden" name="pagina" value="2_2_1">
                    </form>
                    <form action="../../sistema/suporte/sFinalizarSuporte.php" id="finalizarSuporte" method="post">
                        <input type="hidden" name="pagina" value="2_2_1">
                    </form>
                    -->
                </div>
            </div>
HTML;
}
echo <<<HTML
            <!-- /.card 
        </div>        
    </div>
    <!-- /.row
</div>
<!-- /.container-fluid
<div class="modal fade" id="modal-xl">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Imagem 1</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p><img src="http://localhost/SSPMI/App/telas/suporte/img/2023000101.png" width="1024px" height="640px" alt="..."></p>
            </div>            
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid
<div class="modal fade" id="modal2">
    <div class="modal-dialog modal-xl card-orange card-outline">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Finalizar Suporte</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-sm-6">
                    <!-- textarea
                    <div class="form-group">
                        <label>Solução</label>
                        <textarea class="form-control" rows="3" required="" placeholder="Ex.: Alterado endereço de Gateway para 192.168.0.243"></textarea>
                    </div>
                </div>
            </div>
            <form action="../../sistema/suporte/sFinalizarSuporte.php" id="finalizarSuporte" method="post">
                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-primary float-left" form="finalizarSuporte">Finalizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
    -->

HTML;
?>
<script>
    //Copiar texto ao clicar sobre o mesmo
    function copiarTexto() {
        var content = document.getElementById('conteudo').innerHTML;

        navigator.clipboard.writeText(content).then();
    }
</script>