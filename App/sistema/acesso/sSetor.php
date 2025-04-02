<?php
namespace App\sistema\acesso;

use App\modelo\{mConexao};

class sSetor {
    private int $idSetor;
    private int $idDepartamento;
    private int $idSecretaria;
    private string $nomenclatura;
    private string $endereco;
    private string $nomeCampo;
    private string $valorCampo;
    private bool $validador;
    public mConexao $mConexao;
    
    public function __construct(int $idSetor) {
        $this->idSetor = $idSetor;
        $this->validacao = false;
    }
    
    public function consultar($pagina) {
        $this->setMConexao(new mConexao());  
                
        if( $pagina == 'tAcessar.php' ||
            $pagina == 'tMenu1_2.php' ||
            $pagina == 'tMenu1_3.php' ||
            $pagina == 'tMenu2_1.php' ||
            $pagina == 'tMenu2_2_1.php' ||
            $pagina == 'tMenu4_2_4_1.php'){
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'setor',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => null,//caso não tenha, colocar como null
                'ordem' => null
            ];            
            $this->mConexao->CRUD($dados);
            $this->setValidador($this->mConexao->getValidador());
        }
        
        if($pagina == 'ajaxSetor.php'){
            //reoordena os IDs corretamente
            $this->setIdSecretaria($this->getIdSetor());
            $this->setIdSetor(0);
            
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'setor',
                'camposCondicionados' => 'secretaria_idsecretaria',
                'valoresCondicionados' => $this->getIdSecretaria(),
                'camposOrdenados' => 'nomenclatura',//caso não tenha, colocar como null
                'ordem' => 'ASC'
            ];
            
            $this->mConexao->CRUD($dados);
        }
        
        if ($pagina == 'tMenu1_2_1.php' ||
            $pagina == 'tMenu4_1.php' ||
            $pagina == 'tMenu2_1.php-f1' ||
            $pagina == 'tMenu2_2_2.php' ||
            $pagina == 'tMenu4_2_4.php') {
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'setor',
                'camposCondicionados' => '',
                'valoresCondicionados' => '',
                'camposOrdenados' => 'nomenclatura',//caso não tenha, colocar como null
                'ordem' => 'ASC'//caso não tenha, colocar como null
            ];            
            $this->mConexao->CRUD($dados);
        }
    }
    
    public function inserir($pagina, $tratarDados) {
        //cria conexão para inserir os dados na tabela
        $this->setMConexao(new mConexao());
        if ($pagina == 'tMenu4_1.php') {
            
            //insere os dados do histórico no BD            
            $dados = [
                'comando' => 'INSERT INTO',
                'tabela' => 'setor',
                'camposInsercao' => [
                    'nomenclatura',
                    'endereco',
                    'secretaria_idsecretaria'
                ],
                'valoresInsercao' => [
                    $tratarDados['nomenclatura'],
                    $tratarDados['endereco'],
                    $tratarDados['idSecretaria']
                ]
            ];            
        }
        $this->mConexao->CRUD($dados);
    }
    
    public function alterar($pagina) {
        //cria conexão para inserir os dados no BD
        $this->setMConexao(new mConexao());

        if ($pagina == 'tMenu4_2_4_1.php') {
            $dados = [
                'comando' => 'UPDATE',
                'tabela' => 'setor',
                'camposAtualizar' => $this->getNomeCampo(),
                'valoresAtualizar' => $this->getValorCampo(),
                'camposCondicionados' => 'idsetor',
                'valoresCondicionados' => $this->getIdSetor()
            ];            
        }
        
        $this->mConexao->CRUD($dados);
    }
    
    public function getIdSetor(): int {
        return $this->idSetor;
    }

    public function getIdDepartamento(): int {
        return $this->idDepartamento;
    }

    public function getIdSecretaria(): int {
        return $this->idSecretaria;
    }

    public function getNomenclatura(): string {
        return $this->nomenclatura;
    }

    public function getEndereco(): string {
        return $this->endereco;
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

    public function setIdSetor(int $idSetor): void {
        $this->idSetor = $idSetor;
    }

    public function setIdDepartamento(int $idDepartamento): void {
        $this->idDepartamento = $idDepartamento;
    }

    public function setIdSecretaria(int $idSecretaria): void {
        $this->idSecretaria = $idSecretaria;
    }

    public function setNomenclatura(string $nomenclatura): void {
        $this->nomenclatura = $nomenclatura;
    }

    public function setEndereco(string $endereco): void {
        $this->endereco = $endereco;
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
