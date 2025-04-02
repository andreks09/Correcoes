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

        //verifique se há algum protocolo sem etapa vinculada
        $idProtocolo = $value['idprotocolo'];
        $sEtapa = new sEtapa();
        $sEtapa->setNomeCampo('protocolo_idprotocolo');
        $sEtapa->setValorCampo($idProtocolo);
        $sEtapa->consultar('tMenu2_2_1.php');

        $i = 0;
        foreach ($sEtapa->mConexao->getRetorno() as $key => $valorEquipamento) {
            $idEquipamento = $valorEquipamento['equipamento_idequipamento'];
            $descricao = $valorEquipamento['descricao'];
            $idLocal = $valorEquipamento['local_idlocal'];
            $idPrioridade = $valorEquipamento['prioridade_idprioridade'];

            $etapaReversa[$i] = $valorEquipamento;
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
        <div class="col-md-12">
            <!-- Profile Image -->
            <div class="card card-{$cor} card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img src="{$sConfiguracao->getDiretorioPrincipal()}App/telas/suporte/img/cabecalho.png" alt="User profile picture">
                    </div>
                    <h3 class="profile-username text-center">
                        PROTOCOLO N.º: {$protocolo}
                    </h3>
                    <h3 class="profile-username text-center">
                        Dados do Requerente
                    </h3>
                    <ul class="list-group list-group-unbordered mb-4">
                        <li class="list-group-item">
                            <i class="fas fas fa-id-card mr-1"></i><b> Solicitante: </b>{$nomeSolicitante}<br />   
                            <i class="fas fas fa-id-card mr-1"></i> <b>Requerente: </b>{$nomeRequerente}<br />
                            <i class="fas fa-phone mr-1"></i><b> Telefone: </b>Dados protegidos*<br />   
                            <i class="fas fa-envelope-open-text mr-1"></i> <b>E-mail: </b>Dados protegidos*
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-building mr-1"></i><b> Secretaria: </b>{$secretaria}<br/>
                            <i class="fas fa-phone mr-1"></i><b> Telefone: </b>{$telefoneSecretariaTratado}<br/>
                            <i class="fas fa-envelope-open-text mr-1"></i><b>E-mail: </b>{$emailSecretaria}
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-house-user mr-1"></i><b> Departamento/ Unidade/ Assessoria: </b>{$departamento}<br />
                            <i class="fas fa-phone mr-1"></i><b> Telefone: </b>{$telefoneDepartamentoTratado}<br/>
                            <i class="fas fa-envelope-open-text mr-1"></i><b>E-mail: </b>{$emailDepartamento}
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-house-user mr-1"></i><b> Coordenação: </b>{$coordenacao}<br />
                            <i class="fas fa-phone mr-1"></i><b> Telefone: </b>{$telefoneCoordenacaoTratado}<br/>
                            <i class="fas fa-envelope-open-text mr-1"></i><b>E-mail: </b>{$emailCoordenacao}
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-house-user mr-1"></i><b> Setor: </b>{$setor}<br />
                            <i class="fas fa-phone mr-1"></i><b> Telefone: </b>{$telefoneSetorTratado}<br/>
                            <i class="fas fa-envelope-open-text mr-1"></i><b>E-mail: </b>{$emailSetor}
                        </li>                        
                        <li class="list-group-item">
                            * Informações pessoais protegidas pela Lei Geral de Proteção de Dados (LGPD), Lei n.º 13.709 de 14 de agosto de 2018<br />
                            <i>(https://www.planalto.gov.br/ccivil_03/_ato2015-2018/2018/lei/l13709.htm)</i>
                        </li>
                        <div class="card-body box-profile">
                            <h3 class="profile-username text-center">
                                Dados do Equipamento
                            </h3>
                        </div>
                        <li class="list-group-item">
                            <i class="fas fa-barcode mr-1"></i><b>Patrimônio: </b>{$patrimonio}<br />                        
                            <i class="fas fa-laptop mr-1"></i><b>Categoria: </b>{$categoria}<br />                        
                            <i class="fas fa-tag mr-1"></i><b>Marca: </b>{$marca}<br />                        
                            <i class="fas fa-tags mr-1"></i><b>Modelo: </b>{$modelo}<br />                        
                            <i class="fas fa-qrcode mr-1"></i><b>Service Tag: </b>{$etiqueta}<br />                        
                            <i class="fas fa-barcode mr-1"></i><b>Número de Série: </b>{$serie}<br />                        
                            <i class="fas fa-bolt mr-1"></i><b>Tensão de Entrada: </b>{$tensao}<br />                        
                            <i class="fas fa-plug mr-1"></i><b>Corrente de Entrada: </b>{$corrente}<br />                        
                            <i class="fab fa-windows mr-1"></i><b>Sistema Operacional: </b>{$sistemaOperacional}<br />                        
                            <i class="fab fa-windows mr-1"></i><b>Ambiente: </b>{$ambiente}<br />
                        </li>
                        <div class="card-body box-profile">
                            <h3 class="profile-username text-center">
                                Dados do Protocolo
                            </h3>
                        </div>
                        <li class="list-group-item">
                            <i class="fas fas fa-qrcode mr-1"></i><b> Protocolo n.º: </b>{$protocolo}<br />
                            <i class="fas fas fa-calendar-alt mr-1"></i><b> Data de Abertura: </b>{$dataAberturaTratada}<br/>
                            <i class="fas fas fa-calendar-alt mr-1"></i><b>Data de Encerramento: </b>{$dataEncerramentoTratada}
                        </li> 
HTML;
                        //loop para gerar as etapas
                        //busca dados das etapas do protocolo
                        $sEtapa = new sEtapa();
                        $sEtapa->setNomeCampo('protocolo_idprotocolo');
                        $sEtapa->setValorCampo($idProtocolo);
                        $sEtapa->consultar('tMenu6_1_2.php');

                        foreach ($sEtapa->mConexao->getRetorno() as $valorEtapa) {
                            $numero = $valorEtapa['numero'];
                            $idPrioridadeEtapa = $valorEtapa['prioridade_idprioridade'];
                            $dataAberturaEtapa = $valorEtapa['dataHoraAbertura'];
                            $dataEncerramentoEtapa = $valorEtapa['dataHoraEncerramento'];
                            $acessoRemotoEtapa = $valorEtapa['acessoRemoto'];
                            $descricaoEtapa = $valorEtapa['descricao'];
                            $idLocalEtapa = $valorEtapa['local_idlocal'];
                            $idUsuarioEtapa = $valorEtapa['usuario_idusuario'];
                            $solucaoEtapa = $valorEtapa['solucao'];

                            //buscar dados da prioridade
                            $sPrioridadeEtapa = new sPrioridade();
                            $sPrioridadeEtapa->setNomeCampo('idprioridade');
                            $sPrioridadeEtapa->setValorCampo($idPrioridadeEtapa);
                            $sPrioridadeEtapa->consultar('tMenu6_1_2.php');

                            foreach ($sPrioridadeEtapa->mConexao->getRetorno() as $valorEtapa) {
                                $prioridadeEtapa = $valorEtapa['nomenclatura'];
                            }

                            //tratamento da prioridade
                            $sTratamentoPrioridadeEtapa = new sTratamentoDados($prioridadeEtapa);
                            $dadosPrioridadeEtapa = $sTratamentoPrioridadeEtapa->corPrioridade();

                            //tratamento da data de abertura
                            $sTratamentoDataAberturaEtapa = new sTratamentoDados($dataAberturaEtapa);
                            $dataAberturaEtapaTratada = $sTratamentoDataAberturaEtapa->tratarData();

                            //tratamento da data de encerramento
                            if (!is_null($dataEncerramentoEtapa)) {
                                $sTratamentoDataEncerramentoEtapa = new sTratamentoDados($dataEncerramentoEtapa);
                                $dataEncerramentoEtapaTratada = $sTratamentoDataEncerramentoEtapa->tratarData();
                            } else {
                                $dataEncerramentoEtapaTratada = '--/--/---- --:--:--';
                            }

                            //buscar dados da local
                            $sLocalEtapa = new sLocal();
                            $sLocalEtapa->setNomeCampo('idlocal');
                            $sLocalEtapa->setValorCampo($idLocalEtapa);
                            $sLocalEtapa->consultar('tMenu6_1_2.php');

                            foreach ($sLocalEtapa->mConexao->getRetorno() as $valorEtapa) {
                                $localEtapa = $valorEtapa['nomenclatura'];
                            }
                            
                            //buscar dados da usuario
                            $sUsuarioEtapa = new sUsuario();
                            $sUsuarioEtapa->setNomeCampo('idusuario');
                            $sUsuarioEtapa->setValorCampo($idUsuarioEtapa);
                            $sUsuarioEtapa->consultar('tMenu6_1_2.php');

                            foreach ($sUsuarioEtapa->mConexao->getRetorno() as $valorUsuario) {
                                $nomeEtapa = $valorUsuario['nome'];
                                $sobrenomeEtapa = $valorUsuario['sobrenome'];
                            }
                            $responsavelEtapa = $nomeEtapa.' '.$sobrenomeEtapa;
                                
                        echo <<<HTML
                        <li class="list-group-item">
                            <i class="fas fas fa-route mr-1"></i><b> Etapa n.º: </b>{$numero}<br />
                            <i class="far fa-flag mr-1"></i><b> Prioridade: </b>{$prioridadeEtapa}<br />                                    
                            <i class="fas fas fa-calendar-alt mr-1"></i><b> Data de Abertura: </b>{$dataAberturaEtapaTratada}<br/>
                            <i class="fas fas fa-calendar-alt mr-1"></i><b>Data de Encerramento: </b>{$dataEncerramentoEtapaTratada}<br />
                            <i class="fas fa-broadcast-tower mr-1"></i><b> Acesso Remoto: </b>{$acessoRemotoEtapa}<br />
                            <i class="far fa-file-alt mr-1"></i><b> Descrição: </b>{$descricaoEtapa}<br />                                    
                            <i class="fas fa-project-diagram mr-1"></i><b> Local: </b>{$localEtapa}<br />
                            <i class="far fa-id-card mr-1"></i><b> Responsável: </b>{$responsavelEtapa}<br />
                            <i class="fas far fa-file-alt mr-1"></i><b> Solução: </b>{$solucaoEtapa}
                        </li>
HTML;
                                
                            }
                    echo <<<HTML
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
HTML;
?>
<script>
    //Copiar texto ao clicar sobre o mesmo
    function copiarTexto() {
        var content = document.getElementById('conteudo').innerHTML;

        navigator.clipboard.writeText(content).then();
    }
</script>