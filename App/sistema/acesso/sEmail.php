<?php

namespace App\sistema\acesso;

use App\modelo\{
    mConexao
};
use App\sistema\acesso\{
    sNotificacao
};

class sEmail {

    private string $para;
    private string $assunto;
    private string $mensagem;
    private string $de;
    private string $responderPara;
    private array|string $cabecalho;
    private int $idEmail;
    private string $nomeCampo;
    private string $valorCampo;
    private string $nomenclatura;
    private string $nomenclaturaLocal;
    private bool $validador;
    public mConexao $mConexao;
    public sNotificacao $sNotificacao;

    public function __construct($nomenclatura, $nomenclaturaLocal) {
        $this->nomenclatura = $nomenclatura;
        $this->nomenclaturaLocal = $nomenclaturaLocal;
        $this->validador = false;
        $this->para = '';
        $this->assunto = '';
        $this->mensagem = '';
        $this->de = 'suporte@itapoa.app.br';
        $this->responderPara = 'suporte@itapoa.app.br';
    }

    public function verificar($pagina) {
        $this->setMConexao(new mConexao());
        if ($pagina == 'tMenu1_1_1.php' ||
            $pagina == 'tMenu1_2_1.php' ||
            $pagina == 'tSolicitarAcesso.php' ||
            $pagina == 'tMenu4_1.php' ||
            $pagina == 'tEsqueciMinhaSenha.php') {
            //etapas de verificase é um endereço de e-mail
            if (filter_var($this->getNomenclatura(), FILTER_VALIDATE_EMAIL)) {
                //verifica se consta o email no BD               
                if ($pagina == 'tEsqueciMinhaSenha.php') {
                    $dados = [
                        'comando' => 'SELECT',
                        'busca' => '*',
                        'tabelas' => 'email',
                        'camposCondicionados' => 'nomenclatura',
                        'valoresCondicionados' => $this->getNomenclatura(),
                        'camposOrdenados' => null, //caso não tenha, colocar como null
                        'ordem' => null //ASC ou DESC
                    ];
                    $this->mConexao->CRUD($dados);
                    $this->setValidador($this->mConexao->getValidador());
                    
                    if ($this->mConexao->getValidador()) {    
                        //retornar notificação
                        $this->setValidador(true);
                    } else {
                        $this->setSNotificacao(new sNotificacao('A1'));
                    }
                }

                //verifica se consta o email no BD               
                if ($pagina == 'tAcessar.php') {
                    $dados = [
                        'comando' => 'SELECT',
                        'busca' => '*',
                        'tabelas' => 'email',
                        'camposCondicionados' => 'nomenclatura',
                        'valoresCondicionados' => $this->getNomenclatura(),
                        'camposOrdenados' => null, //caso não tenha, colocar como null
                        'ordem' => null //ASC ou DESC
                    ];
                    $this->mConexao->CRUD($dados);

                    //se não localizou o registro do no BD
                    if (!$this->mConexao->getValidador()) {
                        $this->setValidador(false);
                        $this->setSNotificacao(new sNotificacao('A1'));
                    } else {
                        foreach ($this->mConexao->getRetorno() as $linha) {
                            $this->setIdEmail($linha['idemail']);
                            $this->setNomenclatura($linha['nomenclatura']);
                        }
                        $this->setValidador(true);
                    }
                }

                if ($pagina == 'tMenu1_1_1.php') {
                    $dados = [
                        'comando' => 'SELECT',
                        'busca' => '*',
                        'tabelas' => 'email',
                        'camposCondicionados' => 'nomenclatura',
                        'valoresCondicionados' => $this->getNomenclatura(),
                        'camposOrdenados' => null, //caso não tenha, colocar como null
                        'ordem' => null //ASC ou DESC
                    ];
                    $this->mConexao->CRUD($dados);

                    //se localizou o registro do no BD e o registro for diferento do email atual
                    if ($this->mConexao->getValidador() && $_SESSION['credencial']['emailUsuario'] != $this->getNomenclatura()) {
                        $this->setValidador(false);
                        $this->setSNotificacao(new sNotificacao('A12'));
                    } else {
                        $this->setValidador(true);
                    }
                }

                if ($pagina == 'tSolicitarAcesso.php') {
                    //verifica se consta o email no BD 
                    $dados = [
                        'comando' => 'SELECT',
                        'busca' => '*',
                        'tabelas' => 'solicitacao',
                        'camposCondicionados' => 'email',
                        'valoresCondicionados' => $this->getNomenclatura(),
                        'camposOrdenados' => 'idsolicitacao', //caso não tenha, colocar como null
                        'ordem' => 'ASC' //ASC ou DESC
                    ];
                    $this->mConexao->CRUD($dados);

                    //se localizou o registro do no BD
                    if ($this->mConexao->getValidador()) {
                        foreach ($this->mConexao->getRetorno() as $value) {
                            if ($value['situacao']) {
                                $this->setValidador(false);
                                $this->setSNotificacao(new sNotificacao('A20'));
                            } else if (!$value['situacao'] && !$value['dataHoraExaminador']) {
                                $this->setValidador(false);
                                $this->setSNotificacao(new sNotificacao('A14'));
                            } else {
                                $this->setValidador(true);
                            }
                        }
                    } else {
                        $this->setValidador(true);
                    }
                }

                if ($pagina == 'tMenu1_2_1.php' ||
                    $pagina == 'tMenu4_1.php'){
                    $dados = [
                        'comando' => 'SELECT',
                        'busca' => '*',
                        'tabelas' => 'email',
                        'camposCondicionados' => 'nomenclatura',
                        'valoresCondicionados' => $this->getNomenclatura(),
                        'camposOrdenados' => null, //caso não tenha, colocar como null
                        'ordem' => null //ASC ou DESC
                    ];
                    $this->mConexao->CRUD($dados);
                    
                    //se localizou o registro do no BD e o registro for diferento do email atual
                    if ($this->mConexao->getValidador()) {
                        $this->setValidador(false);
                        $this->setSNotificacao(new sNotificacao('A12'));
                    } else {
                        $this->setValidador(true);
                    }
                }
            } else {
                //retornar notificação
                $this->setValidador(false);
                $this->setSNotificacao(new sNotificacao('A2'));
            }
        }
        
        if($pagina == 'tAcessar.php'){
            if (filter_var($this->getNomenclatura(), FILTER_VALIDATE_EMAIL)) {
                $this->setValidador(true);
            }else{
                $this->setValidador(false);
            }
        }
        
    }

    public function consultar($pagina) {
        $this->setMConexao(new mConexao());
        //encaminha as buscas de acordo com a origem 
        if ($pagina == 'tAcessar.php' ||
            $pagina == 'tAcessar.php-secretaria2' ||   
            $pagina == 'tAcessar.php-departamento2' ||  
            $pagina == 'tAcessar.php-coordenacao2' ||  
            $pagina == 'tAcessar.php-setor2' ||
            $pagina == 'tMenu1_1_1.php' ||
            $pagina == 'tMenu1_2.php' ||
            $pagina == 'tMenu1_2_1.php' ||
            $pagina == 'tMenu1_2.php-secretaria2' ||
            $pagina == 'tMenu1_2.php-departamento2' ||
            $pagina == 'tMenu1_2.php-coordenacao2' ||
            $pagina == 'tMenu1_2.php-setor2' ||
            $pagina == 'tMenu2_2_1.php-2' ||
            $pagina == 'tMenu2_2.php' ||
            $pagina == 'tMenu4_2_1_1.php-2' ||
            $pagina == 'tMenu4_2_2_1.php-2' ||
            $pagina == 'tMenu4_2_3_1.php-2' ||
            $pagina == 'tMenu4_2_4_1.php-2') {
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'email',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null //ASC ou DESC
            ];

            $this->mConexao->CRUD($dados);
            $this->setValidador($this->mConexao->getValidador());
        }
        
        if ($pagina == 'tAcessar.php-secretaria' ||
            $pagina == 'tMenu1_2.php-secretaria' ||
            $pagina == 'tMenu2_2_1.php' ||
            $pagina == 'tMenu4_2_1_1.php') {
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'email_has_secretaria',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null //ASC ou DESC
            ];

            $this->mConexao->CRUD($dados);
            $this->setValidador($this->mConexao->getValidador());
        }
        
        if ($pagina == 'tAcessar.php-departamento' ||
            $pagina == 'tMenu1_2.php-departamento' ||
            $pagina == 'tMenu2_2_1.php-departamento' ||
            $pagina == 'tMenu4_2_2_1.php') {
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'email_has_departamento',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null //ASC ou DESC
            ];

            $this->mConexao->CRUD($dados);
            $this->setValidador($this->mConexao->getValidador());
        }
        
        if ($pagina == 'tAcessar.php-coordenacao' ||
            $pagina == 'tMenu1_2.php-coordenacao' ||
            $pagina == 'tMenu4_2_3_1.php') {
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'email_has_coordenacao',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null //ASC ou DESC
            ];

            $this->mConexao->CRUD($dados);
            $this->setValidador($this->mConexao->getValidador());
        }
        
        if ($pagina == 'tAcessar.php-setor' ||
            $pagina == 'tMenu1_2.php-setor' ||
            $pagina == 'tMenu4_2_4_1.php') {
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'email_has_setor',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null //ASC ou DESC
            ];

            $this->mConexao->CRUD($dados);
            $this->setValidador($this->mConexao->getValidador());
        }
    }

    public function alterar($pagina) {
        //cria conexão para inserir os dados no BD
        $this->setMConexao(new mConexao());

        if ($pagina == 'tMenu1_1_1.php' ||
            $pagina == 'tMenu1_2_1.php' ||
            $pagina == 'tMenu4_2_1_1.php' ||
            $pagina == 'tMenu4_2_2_1.php') {
            $dados = [
                'comando' => 'UPDATE',
                'tabela' => 'email',
                'camposAtualizar' => $this->getNomeCampo(),
                'valoresAtualizar' => $this->getValorCampo(),
                'camposCondicionados' => 'idemail',
                'valoresCondicionados' => $this->getIdEmail(),
            ];
            $this->mConexao->CRUD($dados);
            $this->setValidador($this->mConexao->getValidador());
            //UPDATE table_name SET column1=value, column2=value2 WHERE some_column=some_value 
            if ($this->mConexao->getValidador()) {                
                $this->setSNotificacao(new sNotificacao('S1'));
            }
        }

        if ($pagina == 'tAlterarSenha.php') {
            $dados = [
                'comando' => 'UPDATE',
                'tabela' => 'email',
                'camposAtualizar' => $this->getNomeCampo(),
                'valoresAtualizar' => $this->getValorCampo(),
                'camposCondicionados' => 'nomenclatura',
                'valoresCondicionados' => $this->getNomenclatura(),
            ];
            $this->mConexao->CRUD($dados);

            $this->setValidador($this->mConexao->getValidador());
        }
    }

    public function inserir($pagina, $tratarDados) {
        //cria conexão para inserir os dados na tabela
        $this->setMConexao(new mConexao());
        if ($pagina == 'tMenu4_2_1_1.php' ||
            $pagina == 'tMenu4_2_2_1.php' ||
            $pagina == 'tMenu4_2_3_1.php' ||
            $pagina == 'tMenu4_2_4_1.php') {
            //insere os dados do histórico no BD            
            $dados = [
                'comando' => 'INSERT INTO',
                'tabela' => 'email',
                'camposInsercao' => [
                    'nomenclatura'
                ],
                'valoresInsercao' => [
                    $tratarDados['nomenclatura']
                ]
            ];
        }

        //insere e-mail do usuário
        if ($pagina == 'tMenu1_3_1.php') {
            //insere os dados do histórico no BD            
            $dados = [
                'comando' => 'INSERT INTO',
                'tabela' => 'email',
                'camposInsercao' => [
                    'nomenclatura',
                    'senha'
                ],
                'valoresInsercao' => [
                    $tratarDados['nomenclatura'],
                    $tratarDados['senha']
                ]
            ];
        }

        if ($pagina == 'tMenu4_1-email_has_secretaria.php' ||
            $pagina == 'tMenu4_1-email_has_departamento.php' ||
            $pagina == 'tMenu4_1-email_has_coordenacao.php' ||
            $pagina == 'tMenu4_1-email_has_setor.php' ||
            $pagina == 'tMenu4_2_1_1-email_has_secretaria.php' ||
            $pagina == 'tMenu4_2_2_1-email_has_departamento.php' ||
            $pagina == 'tMenu4_2_3_1-email_has_coordenacao.php' ||
            $pagina == 'tMenu4_2_4_1-email_has_setor.php') {
            //insere os dados do histórico no BD            
            $dados = [
                'comando' => 'INSERT INTO',
                'tabela' => 'email_has_' . $this->getNomeCampo(),
                'camposInsercao' => [
                    'email_idemail',
                    $this->getNomeCampo() . '_id' . $this->getNomeCampo()
                ],
                'valoresInsercao' => [
                    $tratarDados['idemail'],
                    $tratarDados['id' . $this->getNomeCampo()],
                ]
            ];
        }

        $this->mConexao->CRUD($dados);
    }
    
    public function deletar($pagina, $tratarDados) {
        //cria conexão para inserir os dados na tabela
        $this->setMConexao(new mConexao());
        if ($pagina == 'tMenu4_2_1_1.php-2' ||
            $pagina == 'tMenu4_2_2_1.php-2' ||
            $pagina == 'tMenu4_2_3_1.php-2' ||
            $pagina == 'tMenu4_2_4_1.php-2') {
            //encaminha os dados para deletar 
            $dados = [
                'comando' => 'DELETE',
                'tabela' => 'email',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo()
            ];
        }   
        
        if ($pagina == 'tMenu4_2_1_1.php') {
            //insere os dados do histórico no BD            
            $dados = [
                'comando' => 'DELETE',
                'tabela' => 'email_has_secretaria',
                'camposCondicionados' => [
                    'email_idemail',
                    'secretaria_idsecretaria'
                ],
                'valoresCondicionados' => [
                    $tratarDados['email_idemail'],
                    $tratarDados['secretaria_idsecretaria']
                ]
            ];
        }  
        
        if ($pagina == 'tMenu4_2_2_1.php') {
            //insere os dados do histórico no BD            
            $dados = [
                'comando' => 'DELETE',
                'tabela' => 'email_has_departamento',
                'camposCondicionados' => [
                    'email_idemail',
                    'departamento_iddepartamento'
                ],
                'valoresCondicionados' => [
                    $tratarDados['email_idemail'],
                    $tratarDados['departamento_iddepartamento']
                ]
            ];
        }  
        
        if ($pagina == 'tMenu4_2_3_1.php') {
            //insere os dados do histórico no BD            
            $dados = [
                'comando' => 'DELETE',
                'tabela' => 'email_has_coordenacao',
                'camposCondicionados' => [
                    'email_idemail',
                    'coordenacao_idcoordenacao'
                ],
                'valoresCondicionados' => [
                    $tratarDados['email_idemail'],
                    $tratarDados['coordenacao_idcoordenacao']
                ]
            ];
        }  
        
        if ($pagina == 'tMenu4_2_4_1.php') {
            //insere os dados do histórico no BD            
            $dados = [
                'comando' => 'DELETE',
                'tabela' => 'email_has_setor',
                'camposCondicionados' => [
                    'email_idemail',
                    'setor_idsetor'
                ],
                'valoresCondicionados' => [
                    $tratarDados['email_idemail'],
                    $tratarDados['setor_idsetor']
                ]
            ];
        }  
        $this->mConexao->CRUD($dados);
        $this->setValidador($this->mConexao->getValidador());
    }

    public function enviar($pagina) {
        if ($pagina == 'tMenu1_3_1.php' ||
                $pagina == 'tEsqueciMinhaSenha.php') {
            $this->setCabecalho('MIME-Version: 1.0' . "\r\n" .
                    'Content-type: text/html; charset=iso-8859-1;' . "\r\n" .
                    'From: ' . $this->getDe() . "\r\n" .
                    'Reply-To: ' . $this->getResponderPara() . "\r\n" .
                    'X-Mailer: PHP/' . phpversion());
        }

        $enviado = mail($this->getPara(), $this->getAssunto(), $this->getMensagem(), $this->getCabecalho());

        $enviado ? $this->setValidador(true) : $this->setValidador(false);
    }

    public function getPara(): string {
        return $this->para;
    }

    public function getAssunto(): string {
        return $this->assunto;
    }

    public function getMensagem(): string {
        return $this->mensagem;
    }

    public function getDe(): string {
        return $this->de;
    }

    public function getResponderPara(): string {
        return $this->responderPara;
    }

    public function getVersao(): string {
        return $this->versao;
    }

    public function getCabecalho(): array|string {
        return $this->cabecalho;
    }

    public function getIdEmail(): int {
        return $this->idEmail;
    }

    public function getNomeCampo(): string {
        return $this->nomeCampo;
    }

    public function getValorCampo(): string {
        return $this->valorCampo;
    }

    public function getNomenclatura(): string {
        return $this->nomenclatura;
    }

    public function getNomenclaturaLocal(): string {
        return $this->nomenclaturaLocal;
    }

    public function getValidador(): bool {
        return $this->validador;
    }

    public function getMConexao(): mConexao {
        return $this->mConexao;
    }

    public function getSNotificacao(): sNotificacao {
        return $this->sNotificacao;
    }

    public function setPara(string $para): void {
        $this->para = $para;
    }

    public function setAssunto(string $assunto): void {
        $this->assunto = $assunto;
    }

    public function setMensagem(string $mensagem): void {
        $this->mensagem = $mensagem;
    }

    public function setDe(string $de): void {
        $this->de = $de;
    }

    public function setResponderPara(string $responderPara): void {
        $this->responderPara = $responderPara;
    }

    public function setVersao(string $versao): void {
        $this->versao = $versao;
    }

    public function setCabecalho(array|string $cabecalho): void {
        $this->cabecalho = $cabecalho;
    }

    public function setIdEmail(int $idEmail): void {
        $this->idEmail = $idEmail;
    }

    public function setNomeCampo(string $nomeCampo): void {
        $this->nomeCampo = $nomeCampo;
    }

    public function setValorCampo(string $valorCampo): void {
        $this->valorCampo = $valorCampo;
    }

    public function setNomenclatura(string $nomenclatura): void {
        $this->nomenclatura = $nomenclatura;
    }

    public function setNomenclaturaLocal(string $nomenclaturaLocal): void {
        $this->nomenclaturaLocal = $nomenclaturaLocal;
    }

    public function setValidador(bool $validador): void {
        $this->validador = $validador;
    }

    public function setMConexao(mConexao $mConexao): void {
        $this->mConexao = $mConexao;
    }

    public function setSNotificacao(sNotificacao $sNotificacao): void {
        $this->sNotificacao = $sNotificacao;
    }
}
