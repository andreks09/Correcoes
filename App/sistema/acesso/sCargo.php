<?php
namespace App\sistema\acesso;

use App\modelo\{mConexao};

class sCargo {
    private int $idCargo;
    private string $nomenclatura;
    private string $nomeCampo;
    private string $valorCampo;
    private bool $validador;
    public mConexao $mConexao;
    
    public function __construct(int $idCargo) {
        $this->idCargo = $idCargo;
    }
    
    public function consultar($pagina) {
        //cria conexão para sonsulta
        $this->setMConexao(new mConexao());
        if( $pagina == 'tMenu1_3.php' ||
            $pagina == 'tMenu5_2_1.php'){                             
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'cargo',
                'camposCondicionados' => 'idcargo',
                'valoresCondicionados' => $this->getIdCargo(),
                'camposOrdenados' => null,//caso não tenha, colocar como null
                'ordem' => null //ASC ou DESC
            ];            
            $this->mConexao->CRUD($dados);
                        
            foreach ($this->mConexao->getRetorno() as $linha) {
                $this->setNomenclatura($linha['nomenclatura']);
            }
        } 
        
        if ($pagina == 'tAcessar.php' ||
            $pagina == 'tMenu1_2.php' ||
            $pagina == 'tMenu1_2_1.php') {
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'cargo',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null
            ];
            $this->mConexao->CRUD($dados);
            $this->setValidador($this->mConexao->getValidador());
        }
        
        if( $pagina == 'tMenu1_1_1.php' ||
            $pagina == 'tSolicitarAcesso.php' ||
            $pagina == 'tMenu1_2_1.php' ||
            $pagina == 'tMenu5_1.php' ||
            $pagina == 'tMenu5_2.php' ||
            $pagina == 'sAlterarCargo.php'){                             
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'cargo',
                'camposCondicionados' => '',
                'valoresCondicionados' => '',
                'camposOrdenados' => 'nomenclatura',//caso não tenha, colocar como null
                'ordem' => 'ASC' //ASC ou DESC
            ];            
            $this->mConexao->CRUD($dados);
            
            $this->setValidador($this->mConexao->getValidador());
        }         
    }
    
    public function inserir($pagina) {
        //cria conexão para inserir os dados na tabela
        $this->setMConexao(new mConexao());
        if( $pagina == 'tMenu5_1.php'){
            //insere os dados do histórico no BD            
            $dados = [
                'comando' => 'INSERT INTO',
                'tabela' => 'cargo',
                'camposInsercao' => [$this->getNomeCampo()],                    
                'valoresInsercao' => [$this->getValorCampo()]
            ];
            $this->mConexao->CRUD($dados);
        }
    }
    
    public function alterar($pagina) {
        //cria conexão para inserir os dados no BD
        $this->setMConexao(new mConexao());

        if ($pagina == 'tMenu5_2_1.php') {
            $dados = [
                'comando' => 'UPDATE',
                'tabela' => 'cargo',
                'camposAtualizar' => $this->getNomeCampo(),
                'valoresAtualizar' => $this->getValorCampo(),
                'camposCondicionados' => 'idcargo',
                'valoresCondicionados' => $this->getIdCargo(),
            ];
            $this->mConexao->CRUD($dados);
            //UPDATE table_name SET column1=value, column2=value2 WHERE some_column=some_value 
            if ($this->mConexao->getValidador()) {
                $this->setValidador(true);
            }
        }
    }

    public function getIdCargo(): int {
        return $this->idCargo;
    }

    public function getNomenclatura(): string {
        return $this->nomenclatura;
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

    public function setIdCargo(int $idCargo): void {
        $this->idCargo = $idCargo;
    }

    public function setNomenclatura(string $nomenclatura): void {
        $this->nomenclatura = $nomenclatura;
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
