<?php

namespace App\sistema\acesso;

use App\modelo\{
    mConexao
};
use App\sistema\acesso\{
    sNotificacao
};

class sSolicitacao {

    private int $idSolicitacao;
    private string $nomeCampo;
    private string $valorCampo;
    private bool $validador;
    public mConexao $mConexao;
    public sNotificacao $sNotificacao;
    
    public function consultar($pagina) {
        //cria conexão para inserir os dados no BD
        $this->setMConexao(new mConexao());
        
        if($pagina == 'tMenu1_3_1.php'){
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'solicitacao',
                'camposCondicionados' => 'idsolicitacao',
                'valoresCondicionados' => $this->getIdSolicitacao(),
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null
            ];
            $this->mConexao->CRUD($dados);
            
            $this->setValidador($this->mConexao->getValidador());
        }
    }
    
    public function alterar($pagina) {
        //cria conexão para inserir os dados no BD
        $this->setMConexao(new mConexao());

        if ($pagina == 'tMenu1_3_1.php') {
            $dados = [
                'comando' => 'UPDATE',
                'tabela' => 'solicitacao',
                'camposAtualizar' => $this->getNomeCampo(),
                'valoresAtualizar' => $this->getValorCampo(),
                'camposCondicionados' => 'idsolicitacao',
                'valoresCondicionados' => $this->getIdSolicitacao(),
            ];
            $this->mConexao->CRUD($dados);

            //atualiza o validador do objeto de acordo com o validador da conexão
            $this->setValidador($this->mConexao->getValidador());
        }
    }

    public function getIdSolicitacao(): int {
        return $this->idSolicitacao;
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

    public function getSNotificacao(): sNotificacao {
        return $this->sNotificacao;
    }

    public function setIdSolicitacao(int $idSolicitacao): void {
        $this->idSolicitacao = $idSolicitacao;
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

    public function setSNotificacao(sNotificacao $sNotificacao): void {
        $this->sNotificacao = $sNotificacao;
    }
}