<?php
namespace App\sistema\acesso;

use App\modelo\{mConexao};

class sRecuperarAcesso {
    private int $idRecuperarAcesso;
    private string $nomeCampo;
    private string $valorCampo;
    private bool $validador;
    public mConexao $mConexao;
    
    public function __construct() {
        $this->validador = false;
    }

    public function consultar($pagina) {
        $this->setMConexao(new mConexao());
        if( $pagina == 'tAlterarSenha.php'){                            
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'recuperarAcesso',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => null,//caso não tenha, colocar como null
                'ordem' => null
            ];  
            
            $this->mConexao->CRUD($dados);
                        
            $this->setValidador($this->mConexao->getValidador());
        } 
    }
    
    public function inserir($pagina, $tratarDados) {
        //cria conexão para inserir os dados na tabela
        $this->setMConexao(new mConexao());
        if( $pagina == 'tEsqueciMinhaSenha.php'){
            //insere os dados do histórico no BD            
            $dados = [
                'comando' => 'INSERT INTO',
                'tabela' => 'recuperarAcesso',
                'camposInsercao' => [
                    'email',
                    'chave'
                ],
                'valoresInsercao' => [
                    $tratarDados['email'],
                    $tratarDados['chave']
                ]
            ];
            $this->mConexao->CRUD($dados);
            
            $this->setValidador($this->mConexao->getValidador());
        }
    }

    public function getIdRecuperarAcesso(): int {
        return $this->idRecuperarAcesso;
    }

    public function getNomeCampo(): string {
        return $this->nomeCampo;
    }

    public function getValorCampo(): string {
        return $this->valorCampo;
    }

    public function getValidador(): bool {
        return $this->validador;
    }

    public function getMConexao(): mConexao {
        return $this->mConexao;
    }

    public function setIdRecuperarAcesso(int $idRecuperarAcesso): void {
        $this->idRecuperarAcesso = $idRecuperarAcesso;
    }

    public function setNomeCampo(string $nomeCampo): void {
        $this->nomeCampo = $nomeCampo;
    }

    public function setValorCampo(string $valorCampo): void {
        $this->valorCampo = $valorCampo;
    }

    public function setValidador(bool $validador): void {
        $this->validador = $validador;
    }

    public function setMConexao(mConexao $mConexao): void {
        $this->mConexao = $mConexao;
    }
}
?>