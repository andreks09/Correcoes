<?php

namespace App\sistema\acesso;

use App\modelo\{
    mConexao
};
use App\sistema\acesso\{
    sNotificacao,
    sSetor,
    sCoordenacao,
    sDepartamento,
    sSecretaria,
    sTelefone,
    sEmail,
    sCargo,
    sPermissao
};

class sUsuario {
    private int $idUsuario;
    private int $idEmail;
    private string $email;
    private string $telefone;
    private int $whatsApp;
    private int $idSecretaria;
    private int $idDepartamento;
    private int $idCoordenacao;
    private int $idSetor;
    private int $idCargo;
    private int $idPermissao;
    private int $idTelefone;
    private bool $validador;
    private string $nome;
    private string $sobrenome;
    private string $sexo;
    private string $imagem;
    private bool $situacao; //alterar no bd
    private string $nomeCampo;
    private mixed $valorCampo;
    private int $examinador;
    public sSetor $sSetor;
    public sCoordenacao $sCoordenacao;
    public sDepartamento $sDepartamento;
    public sSecretaria $sSecretaria;
    public sTelefone $sTelefoneUsuario;
    public sTelefone $sTelefoneSetor;
    public sTelefone $sTelefoneCoordenacao;
    public sTelefone $sTelefoneDepartamento;
    public sTelefone $sTelefoneSecretaria;
    public sEmail $sEmailUsuario;
    public sEmail $sEmailSetor;
    public sEmail $sEmailCoordenacao;
    public sEmail $sEmailDepartamento;
    public sEmail $sEmailSecretaria;
    public sCargo $sCargo;
    public sPermissao $sPermissao;
    public mConexao $mConexao;
    public sNotificacao $sNotificacao;

    public function __construct() {
        $this->validador = false;
    }

    public function consultar($pagina) {
        //busca os dados do usuário no BD
        $this->setMConexao(new mConexao());

        //tomada de decisão de acordo com a página
        if ($pagina == 'tAcessar.php' ||
            $pagina == 'tPainel.php') {
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'usuario',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null //caso não tenha, colocar como null
            ];
            $this->mConexao->CRUD($dados);
            
            //busca dos dados do usuário
            foreach ($this->mConexao->getRetorno() as $linha) {
                $idUsuario = $linha['idusuario'];
                $nome = $linha['nome'];
                $sobrenome = $linha['sobrenome'];
                $sexo = $linha['sexo'];
                $imagem = $linha['imagem'];
                $situacao = $linha['situacao'];
                $idSetor = $linha['setor_idsetor'];
                $idCoordenacao = $linha['coordenacao_idcoordenacao'];
                $idDepartamento = $linha['departamento_iddepartamento'];
                $idSecretaria = $linha['secretaria_idsecretaria'];
                $idTelefoneUsuario = $linha['telefone_idtelefone'];
                $idCargo = $linha['cargo_idcargo'];
                $idEmailUsuario = $linha['email_idemail'];
                $idPermissao = $linha['permissao_idpermissao'];
            }

            //trataemnto de dados
            if (!$situacao) {
                //notifica o usuário sobre a inatividade do perfil
                $this->setSNotificacao(new sNotificacao('A7'));
                $this->setValidador(false);
            } else {
                //tratamento dos dados buscados
                $sexo == 'M' ? $sexo = 'Masculino' : $sexo = 'Feminino';
                $situacao == true ? $situacao = 'Ativo' : $situacao = 'Inativo';

                //busca dados do setor no bd
                if (!is_null($idSetor)) {
                    $sSetor = new sSetor($idSetor);
                    $sSetor->setNomeCampo('idsetor');
                    $sSetor->setValorCampo($idSetor);
                    $sSetor->consultar('tAcessar.php');
                    
                    foreach ($sSetor->mConexao->getRetorno() as $value) {
                        $nomenclaturaSetor = $value['nomenclatura'];
                    }
                } else {
                    $nomenclaturaSetor = '--';
                }

                //busca dados do coordenacao no bd
                if (!is_null($idCoordenacao)) {
                    $sCoordenacao = new sCoordenacao($idCoordenacao);
                    $sCoordenacao->setNomeCampo('idcoordenacao');
                    $sCoordenacao->setValorCampo($idCoordenacao);
                    $sCoordenacao->consultar('tAcessar.php');
                    
                    foreach ($sCoordenacao->mConexao->getRetorno() as $value) {
                        $nomenclaturaCoordenacao = $value['nomenclatura'];
                    }
                } else {
                    $nomenclaturaCoordenacao = '--';
                }

                //busca dados do departamento no bd
                if (!is_null($idDepartamento)) {
                    $sDepartamento = new sDepartamento(0);
                    $sDepartamento->setNomeCampo('iddepartamento');
                    $sDepartamento->setValorCampo($idDepartamento);
                    $sDepartamento->consultar('tAcessar.php');
                    
                    foreach ($sDepartamento->mConexao->getRetorno() as $value) {
                        $nomenclaturaDepartamento = $value['nomenclatura'];
                    }
                } else {
                    $nomenclaturaDepartamento = '--';
                }

                $sSecretaria = new sSecretaria($idSecretaria);
                $sSecretaria->setNomeCampo('idsecretaria');
                $sSecretaria->setValorCampo($idSecretaria);
                $sSecretaria->consultar('tAcessar.php');
                
                foreach ($sSecretaria->mConexao->getRetorno() as $value) {
                    $nomenclaturaSecretaria = $value['nomenclatura'];
                }
                
                if (!is_null($idTelefoneUsuario)) {
                    $sTelefoneUsuario = new sTelefone(0, 0, '');
                    $sTelefoneUsuario->setNomeCampo('idtelefone');
                    $sTelefoneUsuario->setValorCampo($idTelefoneUsuario);                    
                    $sTelefoneUsuario->consultar('tAcessar.php');
                    
                    foreach ($sTelefoneUsuario->mConexao->getRetorno() as $value) {
                        $telefoneUsuario = $value['numero'];
                        $whatsAppUsuario = $value['whatsApp'];
                    }
                } else {
                    $telefoneUsuario = '--';
                    $whatsAppUsuario = false;
                }

                if (!is_null($idSetor)) {
                    $sTelefoneSetor = new sTelefone(0, 0, '');
                    $sTelefoneSetor->setNomeCampo('setor_idsetor');
                    $sTelefoneSetor->setValorCampo($idSetor);
                    $sTelefoneSetor->consultar('tAcessar.php-setor');
                    
                    if ($sTelefoneSetor->getValidador()) {
                        foreach ($sTelefoneSetor->mConexao->getRetorno() as $value) {
                            $idTelefoneSetor = $value['telefone_idtelefone'];
                        }
                        
                        $sTelefoneSetor = new sTelefone(0, 0, '');
                        $sTelefoneSetor->setNomeCampo('idtelefone');
                        $sTelefoneSetor->setValorCampo($idTelefoneSetor);
                        $sTelefoneSetor->consultar('tAcessar.php-setor2');
                        
                        foreach ($sTelefoneSetor->mConexao->getRetorno() as $value) {
                            $telefoneSetor = $value['numero'];
                            $whatsAppSetor = $value['whatsApp'];
                        }
                    } else {
                        $telefoneSetor = '--';
                        $whatsAppSetor = false;
                    }
                } else {
                    $telefoneSetor = '--';
                    $whatsAppSetor = false;
                }
                
                
                if (!is_null($idCoordenacao)) {
                    $sTelefoneCoordenacao = new sTelefone(0, 0, '');
                    $sTelefoneCoordenacao->setNomeCampo('coordenacao_idcoordenacao');
                    $sTelefoneCoordenacao->setValorCampo($idCoordenacao);
                    $sTelefoneCoordenacao->consultar('tAcessar.php-coordenacao');
                    
                    if ($sTelefoneCoordenacao->getValidador()) {
                        foreach ($sTelefoneCoordenacao->mConexao->getRetorno() as $value) {
                            $idTelefoneCoordenacao = $value['telefone_idtelefone'];
                        }
                        
                        $sTelefoneCoordenacao = new sTelefone(0, 0, '');
                        $sTelefoneCoordenacao->setNomeCampo('idtelefone');
                        $sTelefoneCoordenacao->setValorCampo($idTelefoneCoordenacao);
                        $sTelefoneCoordenacao->consultar('tAcessar.php-coordenacao2');
                        
                        foreach ($sTelefoneCoordenacao->mConexao->getRetorno() as $value) {
                            $telefoneCoordenacao = $value['numero'];
                            $whatsAppCoordenacao = $value['whatsApp'];
                        }
                    } else {
                        $telefoneCoordenacao = '--';
                        $whatsAppCoordenacao = false;
                    }
                } else {
                    $telefoneCoordenacao = '--';
                    $whatsAppCoordenacao = false;
                }
                 
                if (!is_null($idDepartamento)) {
                    $sTelefoneDepartamento = new sTelefone(0, 0, '');
                    $sTelefoneDepartamento->setNomeCampo('departamento_iddepartamento');
                    $sTelefoneDepartamento->setValorCampo($idDepartamento);
                    $sTelefoneDepartamento->consultar('tAcessar.php-departamento');
                                        
                    if ($sTelefoneDepartamento->getValidador()) {
                        foreach ($sTelefoneDepartamento->mConexao->getRetorno() as $value) {
                            $idTelefoneDepartamento = $value['telefone_idtelefone'];
                        }
                        
                        $sTelefoneDepartamento = new sTelefone(0, 0, '');
                        $sTelefoneDepartamento->setNomeCampo('idtelefone');
                        $sTelefoneDepartamento->setValorCampo($idTelefoneDepartamento);
                        $sTelefoneDepartamento->consultar('tAcessar.php-departamento2');
                        
                        foreach ($sTelefoneDepartamento->mConexao->getRetorno() as $value) {
                            $telefoneDepartamento = $value['numero'];
                            $whatsAppDepartamento = $value['whatsApp'];
                        }
                    } else {
                        $telefoneDepartamento = '--';
                        $whatsAppDepartamento = false;
                    }
                } else {
                    $telefoneDepartamento = '--';
                    $whatsAppDepartamento = false;
                }

               if (!is_null($idSecretaria)) {
                    $sTelefoneSecretaria = new sTelefone(0, 0, '');
                    $sTelefoneSecretaria->setNomeCampo('secretaria_idsecretaria');
                    $sTelefoneSecretaria->setValorCampo($idSecretaria);
                    $sTelefoneSecretaria->consultar('tAcessar.php-secretaria');
                    
                    if ($sTelefoneSecretaria->getValidador()) {
                        foreach ($sTelefoneSecretaria->mConexao->getRetorno() as $value) {
                            $idTelefoneSecretaria = $value['telefone_idtelefone'];
                        }
                        
                        $sTelefoneSecretaria = new sTelefone(0, 0, '');
                        $sTelefoneSecretaria->setNomeCampo('idtelefone');
                        $sTelefoneSecretaria->setValorCampo($idTelefoneSecretaria);
                        $sTelefoneSecretaria->consultar('tAcessar.php-secretaria2');
                        
                        foreach ($sTelefoneSecretaria->mConexao->getRetorno() as $value) {
                            $telefoneSecretaria = $value['numero'];
                            $whatsAppSecretaria = $value['whatsApp'];
                        }
                    } else {
                        $telefoneSecretaria = '--';
                        $whatsAppSecretaria = false;
                    }
                } else {
                    $telefoneSecretaria = '--';
                    $whatsAppSecretaria = false;
                }
                
                $sEmailUsuario = new sEmail('', '');
                $sEmailUsuario->setNomeCampo('idemail');
                $sEmailUsuario->setValorCampo($idEmailUsuario);
                $sEmailUsuario->consultar('tAcessar.php');
                
                foreach ($sEmailUsuario->mConexao->getRetorno() as $value) {
                    $emailUsuario = $value['nomenclatura'];
                }

                if (!is_null($idSetor)) {
                    $sEmailSetor = new sEmail('', '');
                    $sEmailSetor->setNomeCampo('setor_idsetor');
                    $sEmailSetor->setValorCampo($idSetor);
                    $sEmailSetor->consultar('tAcessar.php-setor');
                    
                    if ($sEmailSetor->getValidador()) {
                        foreach ($sEmailSetor->mConexao->getRetorno() as $value) {
                            $idEmailSetor = $value['email_idemail'];
                        }
                        
                        $sEmailSetor = new sEmail('', '');
                        $sEmailSetor->setNomeCampo('idemail');
                        $sEmailSetor->setValorCampo($idEmailSetor);
                        $sEmailSetor->consultar('tAcessar.php-setor2');
                        
                        foreach ($sEmailSetor->mConexao->getRetorno() as $value) {
                            $emailSetor = $value['nomenclatura'];
                        }
                    } else {
                        $emailSetor = '--';
                    }
                } else {
                    $emailSetor = '--';
                }

                if (!is_null($idCoordenacao)) {
                    $sEmailCoordenacao = new sEmail('', '');
                    $sEmailCoordenacao->setNomeCampo('coordenacao_idcoordenacao');
                    $sEmailCoordenacao->setValorCampo($idCoordenacao);
                    $sEmailCoordenacao->consultar('tAcessar.php-coordenacao');
                    
                    if ($sEmailCoordenacao->getValidador()) {
                        foreach ($sEmailCoordenacao->mConexao->getRetorno() as $value) {
                            $idEmailCoordenacao = $value['email_idemail'];
                        }
                        
                        $sEmailCoordenacao = new sEmail('', '');
                        $sEmailCoordenacao->setNomeCampo('idemail');
                        $sEmailCoordenacao->setValorCampo($idEmailCoordenacao);
                        $sEmailCoordenacao->consultar('tAcessar.php-coordenacao2');
                        
                        foreach ($sEmailCoordenacao->mConexao->getRetorno() as $value) {
                            $emailCoordenacao = $value['nomenclatura'];
                        }
                    } else {
                        $emailCoordenacao = '--';
                    }
                } else {
                    $emailCoordenacao = '--';
                }
                
                if (!is_null($idDepartamento)) {
                    $sEmailDepartamento = new sEmail('', '');
                    $sEmailDepartamento->setNomeCampo('departamento_iddepartamento');
                    $sEmailDepartamento->setValorCampo($idDepartamento);
                    $sEmailDepartamento->consultar('tAcessar.php-departamento');
                    
                    if ($sEmailDepartamento->getValidador()) {
                        foreach ($sEmailDepartamento->mConexao->getRetorno() as $value) {
                            $idEmailDepartamento = $value['email_idemail'];
                        }
                        
                        $sEmailDepartamento = new sEmail('', '');
                        $sEmailDepartamento->setNomeCampo('idemail');
                        $sEmailDepartamento->setValorCampo($idEmailDepartamento);
                        $sEmailDepartamento->consultar('tAcessar.php-departamento2');
                        
                        foreach ($sEmailDepartamento->mConexao->getRetorno() as $value) {
                            $emailDepartamento = $value['nomenclatura'];
                        }
                    } else {
                        $emailDepartamento = '--';
                    }
                } else {
                    $emailDepartamento = '--';
                }

                if (!is_null($idSecretaria)) {
                    $sEmailSecretaria = new sEmail('', '');
                    $sEmailSecretaria->setNomeCampo('secretaria_idsecretaria');
                    $sEmailSecretaria->setValorCampo($idSecretaria);
                    $sEmailSecretaria->consultar('tAcessar.php-secretaria');
                    
                    if ($sEmailSecretaria->getValidador()) {
                        foreach ($sEmailSecretaria->mConexao->getRetorno() as $value) {
                            $idEmailSecretaria = $value['email_idemail'];
                        }
                        
                        $sEmailSecretaria = new sEmail('', '');
                        $sEmailSecretaria->setNomeCampo('idemail');
                        $sEmailSecretaria->setValorCampo($idEmailSecretaria);
                        $sEmailSecretaria->consultar('tAcessar.php-secretaria2');
                        
                        foreach ($sEmailSecretaria->mConexao->getRetorno() as $value) {
                            $emailSecretaria = $value['nomenclatura'];
                        }
                    } else {
                        $emailSecretaria = '--';
                    }
                } else {
                    $emailSecretaria = '--';
                }

                $sCargo = new sCargo($idCargo);
                $sCargo->setNomeCampo('idcargo');
                $sCargo->setValorCampo($idCargo);
                $sCargo->consultar('tAcessar.php');
                
                foreach ($sCargo->mConexao->getRetorno() as $value) {
                    $nomenclaturaCargo = $value['nomenclatura'];
                }

                $sPermissao = new sPermissao($idPermissao);
                $sPermissao->setNomeCampo('idpermissao');
                $sPermissao->setValorCampo($idPermissao);
                $sPermissao->consultar('tAcessar.php');
                
                foreach ($sPermissao->mConexao->getRetorno() as $value) {
                    $nomenclaturaPermissao = $value['nomenclatura'];
                    $nivelPermissao = $value['nivel'];
                }

                if(!isset($_SESSION)){
                    session_start();
                }
                
                $_SESSION['credencial'] = [
                    'idUsuario' => $idUsuario,
                    'nome' => $nome,
                    'sobrenome' => $sobrenome,
                    'sexo' => $sexo,
                    'imagem' => $imagem,
                    'situacao' => $situacao,
                    'idSetor' => $idSetor,
                    'setor' => $nomenclaturaSetor,
                    'idCoordenacao' => $idCoordenacao,
                    'coordenacao' => $nomenclaturaCoordenacao,
                    'idDepartamento' => $idDepartamento,
                    'departamento' => $nomenclaturaDepartamento,
                    'idSecretaria' => $idSecretaria,
                    'secretaria' => $nomenclaturaSecretaria,
                    'idTelefoneUsuario' => $idTelefoneUsuario,
                    'telefoneUsuario' => $telefoneUsuario,
                    'whatsAppUsuario' => $whatsAppUsuario,
                    'telefoneSetor' => $telefoneSetor,
                    'whatsAppSetor' => $whatsAppSetor,
                    'telefoneCoordenacao' => $telefoneCoordenacao,
                    'whatsAppCoordenacao' => $whatsAppCoordenacao,
                    'telefoneDepartamento' => $telefoneDepartamento,
                    'whatsAppDepartamento' => $whatsAppDepartamento,
                    'telefoneSecretaria' => $telefoneSecretaria,
                    'whatsAppSecretaria' => $whatsAppSecretaria,
                    'idEmailUsuario' => $idEmailUsuario,
                    'emailUsuario' => $emailUsuario,
                    'emailSetor' => $emailSetor,
                    'emailCoordenacao' => $emailCoordenacao,
                    'emailDepartamento' => $emailDepartamento,
                    'emailSecretaria' => $emailSecretaria,
                    'idCargo' => $idCargo,
                    'cargo' => $nomenclaturaCargo,
                    'nivelPermissao' => $nivelPermissao,
                    'idPermissao' => $idPermissao,
                    'permissao' => $nomenclaturaPermissao
                ];

                //aprovado em todas as validações
                $this->setValidador(true);
                
                //encerra a conexão com o bd
                //$this->conexao->close();
            }
        }

        if ($pagina == 'tMenu1_2.php') {
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'usuario',
                'camposCondicionados' => '',
                'valoresCondicionados' => '',
                'camposOrdenados' => 'idusuario', //caso não tenha, colocar como null
                'ordem' => 'ASC'
            ];
            $this->mConexao->CRUD($dados);

            $this->setValidador($this->mConexao->getValidador());
        }

        if ($pagina == 'tMenu2_2.php' ||
            $pagina == 'tMenu2_2_1.php') {
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'usuario',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null
            ];
            $this->mConexao->CRUD($dados);
            $this->setValidador($this->mConexao->getValidador());

            foreach ($this->mConexao->getRetorno() as $linha) {
                $this->setNome($linha['nome']);
                $this->setSobrenome($linha['sobrenome']);
                $this->setSexo($linha['sexo']);
                $this->setImagem($linha['imagem']);
                $this->setSituacao($linha['situacao']);
                if (strlen($linha['setor_idsetor']) > 0) {
                    $this->setIdSetor($linha['setor_idsetor']);
                } else {
                    $this->setIdSetor(0);
                }
                if (strlen($linha['coordenacao_idcoordenacao']) > 0) {
                    $this->setIdCoordenacao($linha['coordenacao_idcoordenacao']);
                } else {
                    $this->setIdCoordenacao(0);
                }
                if (strlen($linha['departamento_iddepartamento']) > 0) {
                    $this->setIdDepartamento($linha['departamento_iddepartamento']);
                } else {
                    $this->setIdDepartamento(0);
                }
                $this->setIdSecretaria($linha['secretaria_idsecretaria']);

                if (!empty($linha['telefone_idtelefone'])) {
                    $this->setTelefone($linha['telefone_idtelefone']);
                } else {
                    $this->setTelefone(0);
                }

                $this->setIdEmail($linha['email_idemail']);

                $this->setIdCargo($linha['cargo_idcargo']);

                $this->setIdPermissao($linha['permissao_idpermissao']);
            }
        }

        if ($pagina == 'tMenu1_3.php') {
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'solicitacao',
                'camposCondicionados' => '',
                'valoresCondicionados' => '',
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null
            ];
            $this->mConexao->CRUD($dados);

            $this->setValidador($this->mConexao->getValidador());
        }
        
        if ($pagina == 'tMenu1_2_1.php') {
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'usuario',
                'camposCondicionados' => 'idusuario',
                'valoresCondicionados' => $this->getIdUsuario(),
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null
            ];
            
            $this->mConexao->CRUD($dados);
            foreach ($this->mConexao->getRetorno() as $linha) {
                $this->setNome($linha['nome']);
                $this->setSobrenome($linha['sobrenome']);
            }

            $this->setValidador($this->mConexao->getValidador());
        }
        
        if ($pagina == 'tMenu1_1_1.php' ||
            $pagina == 'tMenu1_3-examinador.php' ||
            $pagina == 'tMenu6_1_2.php') {
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'usuario',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null
            ];
            
            $this->mConexao->CRUD($dados);
            $this->setValidador($this->mConexao->getValidador());
        }
        
        if ($pagina == 'tMenu2_2_1_3_1.php') {
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'usuario',
                'camposCondicionados' => '',
                'valoresCondicionados' => '',
                'camposOrdenados' => 'nome', //caso não tenha, colocar como null
                'ordem' => 'ASC' //caso não tenha, colocar como null
            ];
            $this->mConexao->CRUD($dados);

            $this->setValidador($this->mConexao->getValidador());
        }
        
    }

    public function acessar($pagina) {
        if ($pagina == 'tAcessar.php') {
            header('Location: ./tPainel.php');
        }
    }

    public function verificarNome($nome) {
        //verifica se tem letras e espaço
        $caracterValido = !!preg_match('|^[\pL\s]+$|u', $nome);
        if (mb_strlen($nome) < 2 ||
                mb_strlen($nome) > 20) {            
            $this->setValidador(false);
            $this->setSNotificacao(new sNotificacao('A24'));
        } else if (!$caracterValido) {
            $this->setValidador(false);
            $this->setSNotificacao(new sNotificacao('A25'));
        } else {
            $this->setValidador(true);
        }
    }

    public function verificarSobrenome($sobrenome) {
        //verifica se tem letras e espaço
        $caracterValido = !!preg_match('|^[\pL\s]+$|u', $sobrenome);
        if (mb_strlen($sobrenome) < 2 ||
                mb_strlen($sobrenome) > 100) {
            $this->setValidador(false);
            $this->setSNotificacao(new sNotificacao('A26'));
        } else if (!$caracterValido) {
            $this->setValidador(false);
            $this->setSNotificacao(new sNotificacao('A27'));
        } else {
            $this->setValidador(true);
        }
    }

    public function alterar($pagina) {
        //cria conexão para inserir os dados no BD
        $this->setMConexao(new mConexao());

        if ($pagina == 'tMenu1_1_1.php' ||
            $pagina == 'tMenu1_2_1.php') {
            $dados = [
                'comando' => 'UPDATE',
                'tabela' => 'usuario',
                'camposAtualizar' => $this->getNomeCampo(),
                'valoresAtualizar' => $this->getValorCampo(),
                'camposCondicionados' => 'idusuario',
                'valoresCondicionados' => $this->getIdUsuario()
            ];
            $this->mConexao->CRUD($dados);
            //UPDATE table_name SET column1=value, column2=value2 WHERE some_column=some_value 
            if ($this->mConexao->getValidador()) {
                $this->setValidador(true);
                $this->setSNotificacao(new sNotificacao('S1'));
            }
        }
    }

    public function inserir($pagina) {
        //cria conexão para inserir os dados no BD
        $this->setMConexao(new mConexao());

        if ($pagina == 'sSolicitarAcesso.php') {
            $dados = [
                'comando' => 'INSERT INTO',
                'tabela' => 'solicitacao',
                'camposInsercao' => [
                    'nome',
                    'sobrenome',
                    'sexo',
                    'telefone',
                    'whatsApp',
                    'email',
                    'secretaria_idsecretaria',
                    'departamento_iddepartamento',
                    'coordenacao_idcoordenacao',
                    'setor_idsetor',
                    'cargo_idcargo'
                ],
                'valoresInsercao' => [
                    $this->getNome(),
                    $this->getSobrenome(),
                    $this->getSexo(),
                    $this->getTelefone(),
                    $this->getWhatsApp(),
                    $this->getEmail(),
                    $this->getIdSecretaria(),
                    $this->getIdDepartamento(),
                    $this->getIdCoordenacao(),
                    $this->getIdSetor(),
                    $this->getIdCargo()
                ]
            ];
            $this->mConexao->CRUD($dados);
            //INSERT INTO table_name (column1, column2, column3, ...) VALUES (value1, value2, value3, ...);
            if ($this->mConexao->getValidador()) {
                $this->setValidador(true);
                $this->setSNotificacao(new sNotificacao('S3'));
            }
        }
        
        if ($pagina == 'tMenu1_3_1.php') {
            //declara um array que incrementa campos conforme necessário
            
            $dados =[
                'comando' => 'INSERT INTO',
                'tabela' => 'usuario',
                'camposInsercao' => [
                    'nome',
                    'sobrenome',
                    'sexo'
                ],
                'valoresInsercao' => [
                    $this->getNome(),
                    $this->getSobrenome(),
                    $this->getSexo(),
                ]
            ];       
                        
            if($this->getIdDepartamento()){
                array_push($dados['camposInsercao'], 'departamento_iddepartamento');
                array_push($dados['valoresInsercao'], $this->getIdDepartamento());
            }
            if($this->getIdCoordenacao()){
                array_push($dados['camposInsercao'], 'coordenacao_idcoordenacao');
                array_push($dados['valoresInsercao'], $this->getIdCoordenacao());
            }
            if($this->getIdSetor()){
                array_push($dados['camposInsercao'], 'setor_idsetor');
                array_push($dados['valoresInsercao'], $this->getIdSetor());
            }
                     
            array_push(
                $dados['camposInsercao'],
                'secretaria_idsecretaria',
                'telefone_idtelefone',
                'cargo_idcargo',
                'email_idemail',
                'permissao_idpermissao',
                'situacao'
            );
            
            array_push(
                $dados['valoresInsercao'],
                $this->getIdSecretaria(),
                $this->getIdTelefone(),
                $this->getIdCargo(),
                $this->getIdEmail(),
                $this->getIdPermissao(),
                $this->getSituacao()
            );
            
            $this->mConexao->CRUD($dados);
            
            $this->setValidador($this->mConexao->getValidador());
        }
    }

    public function tratarData($data) {
        $dataTratada = date("d/m/Y H:i:s", strtotime(str_replace('-', '/', $data)));

        return $dataTratada;
    }

    public function getIdUsuario(): int {
        return $this->idUsuario;
    }

    public function getIdEmail(): int {
        return $this->idEmail;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getTelefone(): string {
        return $this->telefone;
    }

    public function getWhatsApp(): int {
        return $this->whatsApp;
    }

    public function getIdSecretaria(): int {
        return $this->idSecretaria;
    }

    public function getIdDepartamento(): int {
        return $this->idDepartamento;
    }

    public function getIdCoordenacao(): int {
        return $this->idCoordenacao;
    }

    public function getIdSetor(): int {
        return $this->idSetor;
    }

    public function getIdCargo(): int {
        return $this->idCargo;
    }

    public function getIdPermissao(): int {
        return $this->idPermissao;
    }

    public function getIdTelefone(): int {
        return $this->idTelefone;
    }

    public function getValidador(): bool {
        return $this->validador;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function getSobrenome(): string {
        return $this->sobrenome;
    }

    public function getSexo(): string {
        return $this->sexo;
    }

    public function getImagem(): string {
        return $this->imagem;
    }

    public function getSituacao(): bool {
        return $this->situacao;
    }

    public function getNomeCampo(): string {
        return $this->nomeCampo;
    }

    public function getValorCampo(): mixed {
        return $this->valorCampo;
    }

    public function getExaminador(): int {
        return $this->examinador;
    }

    public function getSSetor(): sSetor {
        return $this->sSetor;
    }

    public function getSCoordenacao(): sCoordenacao {
        return $this->sCoordenacao;
    }

    public function getSDepartamento(): sDepartamento {
        return $this->sDepartamento;
    }

    public function getSSecretaria(): sSecretaria {
        return $this->sSecretaria;
    }

    public function getSTelefoneUsuario(): sTelefone {
        return $this->sTelefoneUsuario;
    }

    public function getSTelefoneSetor(): sTelefone {
        return $this->sTelefoneSetor;
    }

    public function getSTelefoneCoordenacao(): sTelefone {
        return $this->sTelefoneCoordenacao;
    }

    public function getSTelefoneDepartamento(): sTelefone {
        return $this->sTelefoneDepartamento;
    }

    public function getSTelefoneSecretaria(): sTelefone {
        return $this->sTelefoneSecretaria;
    }

    public function getSEmailUsuario(): sEmail {
        return $this->sEmailUsuario;
    }

    public function getSEmailSetor(): sEmail {
        return $this->sEmailSetor;
    }

    public function getSEmailCoordenacao(): sEmail {
        return $this->sEmailCoordenacao;
    }

    public function getSEmailDepartamento(): sEmail {
        return $this->sEmailDepartamento;
    }

    public function getSEmailSecretaria(): sEmail {
        return $this->sEmailSecretaria;
    }

    public function getSCargo(): sCargo {
        return $this->sCargo;
    }

    public function getSPermissao(): sPermissao {
        return $this->sPermissao;
    }

    public function getMConexao(): mConexao {
        return $this->mConexao;
    }

    public function getSNotificacao(): sNotificacao {
        return $this->sNotificacao;
    }

    public function setIdUsuario(int $idUsuario): void {
        $this->idUsuario = $idUsuario;
    }

    public function setIdEmail(int $idEmail): void {
        $this->idEmail = $idEmail;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setTelefone(string $telefone): void {
        $this->telefone = $telefone;
    }

    public function setWhatsApp(int $whatsApp): void {
        $this->whatsApp = $whatsApp;
    }

    public function setIdSecretaria(int $idSecretaria): void {
        $this->idSecretaria = $idSecretaria;
    }

    public function setIdDepartamento(int $idDepartamento): void {
        $this->idDepartamento = $idDepartamento;
    }

    public function setIdCoordenacao(int $idCoordenacao): void {
        $this->idCoordenacao = $idCoordenacao;
    }

    public function setIdSetor(int $idSetor): void {
        $this->idSetor = $idSetor;
    }

    public function setIdCargo(int $idCargo): void {
        $this->idCargo = $idCargo;
    }

    public function setIdPermissao(int $idPermissao): void {
        $this->idPermissao = $idPermissao;
    }

    public function setIdTelefone(int $idTelefone): void {
        $this->idTelefone = $idTelefone;
    }

    public function setValidador(bool $validador): void {
        $this->validador = $validador;
    }

    public function setNome(string $nome): void {
        $this->nome = $nome;
    }

    public function setSobrenome(string $sobrenome): void {
        $this->sobrenome = $sobrenome;
    }

    public function setSexo(string $sexo): void {
        $this->sexo = $sexo;
    }

    public function setImagem(string $imagem): void {
        $this->imagem = $imagem;
    }

    public function setSituacao(bool $situacao): void {
        $this->situacao = $situacao;
    }

    public function setNomeCampo(string $nomeCampo): void {
        $this->nomeCampo = $nomeCampo;
    }

    public function setValorCampo(mixed $valorCampo): void {
        $this->valorCampo = $valorCampo;
    }

    public function setExaminador(int $examinador): void {
        $this->examinador = $examinador;
    }

    public function setSSetor(sSetor $sSetor): void {
        $this->sSetor = $sSetor;
    }

    public function setSCoordenacao(sCoordenacao $sCoordenacao): void {
        $this->sCoordenacao = $sCoordenacao;
    }

    public function setSDepartamento(sDepartamento $sDepartamento): void {
        $this->sDepartamento = $sDepartamento;
    }

    public function setSSecretaria(sSecretaria $sSecretaria): void {
        $this->sSecretaria = $sSecretaria;
    }

    public function setSTelefoneUsuario(sTelefone $sTelefoneUsuario): void {
        $this->sTelefoneUsuario = $sTelefoneUsuario;
    }

    public function setSTelefoneSetor(sTelefone $sTelefoneSetor): void {
        $this->sTelefoneSetor = $sTelefoneSetor;
    }

    public function setSTelefoneCoordenacao(sTelefone $sTelefoneCoordenacao): void {
        $this->sTelefoneCoordenacao = $sTelefoneCoordenacao;
    }

    public function setSTelefoneDepartamento(sTelefone $sTelefoneDepartamento): void {
        $this->sTelefoneDepartamento = $sTelefoneDepartamento;
    }

    public function setSTelefoneSecretaria(sTelefone $sTelefoneSecretaria): void {
        $this->sTelefoneSecretaria = $sTelefoneSecretaria;
    }

    public function setSEmailUsuario(sEmail $sEmailUsuario): void {
        $this->sEmailUsuario = $sEmailUsuario;
    }

    public function setSEmailSetor(sEmail $sEmailSetor): void {
        $this->sEmailSetor = $sEmailSetor;
    }

    public function setSEmailCoordenacao(sEmail $sEmailCoordenacao): void {
        $this->sEmailCoordenacao = $sEmailCoordenacao;
    }

    public function setSEmailDepartamento(sEmail $sEmailDepartamento): void {
        $this->sEmailDepartamento = $sEmailDepartamento;
    }

    public function setSEmailSecretaria(sEmail $sEmailSecretaria): void {
        $this->sEmailSecretaria = $sEmailSecretaria;
    }

    public function setSCargo(sCargo $sCargo): void {
        $this->sCargo = $sCargo;
    }

    public function setSPermissao(sPermissao $sPermissao): void {
        $this->sPermissao = $sPermissao;
    }

    public function setMConexao(mConexao $mConexao): void {
        $this->mConexao = $mConexao;
    }

    public function setSNotificacao(sNotificacao $sNotificacao): void {
        $this->sNotificacao = $sNotificacao;
    }


}
