<?php
namespace App\sistema\acesso;

use App\modelo\{mConexao};

class sPermissao {
    private int $idPermissao;
    private int $nivel;
    private string $nomenclatura;
    private string $nomeCampo;
    private string $valorCampo;
    private bool $validador;
    public mConexao $mConexao;
    
    public function __construct(int $idPermissao) {
        $this->idPermissao = $idPermissao;
    }
    
    public function consultar($pagina) {
        //cria conexão para realizar a consulta de acordo com a página requerente
        $this->setMConexao(new mConexao());  
        if( $pagina == 'tMenu2_2_1_3_1.php'){                           
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'permissao',
                'camposCondicionados' => 'idpermissao',
                'valoresCondicionados' => $this->getIdPermissao(),
                'camposOrdenados' => null,//caso não tenha, colocar como null
                'ordem' => null //ASC ou DESC
            ];            
            $this->mConexao->CRUD($dados);
                        
            foreach ($this->mConexao->getRetorno() as $linha) {
                $this->setNivel($linha['nivel']);
                $this->setNomenclatura($linha['nomenclatura']);
            }
        }  
        
        if ($pagina == 'tAcessar.php' ||
            $pagina == 'tMenu1_2_1.php') {
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'permissao',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null
            ];
            $this->mConexao->CRUD($dados);
            $this->setValidador($this->mConexao->getValidador());
        }
        
        if( $pagina == 'tMenu1_1_1.php' ||
            $pagina == 'tMenu1_2_1.php'){                           
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'permissao',
                'camposCondicionados' => '',
                'valoresCondicionados' => '',
                'camposOrdenados' => null,//caso não tenha, colocar como null
                'ordem' => null //ASC ou DESC
            ];            
            $this->mConexao->CRUD($dados);
            $this->setValidador($this->mConexao->getValidador());
        }     
    }
    public function getIdPermissao(): int {
        return $this->idPermissao;
    }

    public function getNivel(): int {
        return $this->nivel;
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

    public function setIdPermissao(int $idPermissao): void {
        $this->idPermissao = $idPermissao;
    }

    public function setNivel(int $nivel): void {
        $this->nivel = $nivel;
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