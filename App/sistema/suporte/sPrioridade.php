<?php
namespace App\sistema\suporte;

use App\modelo\{
    mConexao
};
use App\sistema\acesso\{
    sNotificacao
};

class sPrioridade {
    private string $validador;
    private string $nomeCampo;
    private string $valorCampo;
    public mConexao $mConexao;
    public sNotificacao $sNotificacao;

    public function consultar($pagina) {
        $this->setMConexao(new mConexao());
        
        if ($pagina == 'tMenu2_1.php-f1' ||
            $pagina == 'tMenu2_2_3.php' ||
            $pagina == 'tMenu2_2_1_3.php-f1') {
            //monta os dados há serem passados na query               
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'prioridade',
                'camposCondicionados' => '',
                'valoresCondicionados' => '',
                'camposOrdenados' => 'idprioridade', //caso não tenha, colocar como null
                'ordem' => 'ASC'//caso não tenha, colocar como null
            ];            
        }
        
        if ($pagina == 'tMenu2_2.php' ||
            $pagina == 'tMenu2_2_1.php' ||
            $pagina == 'tMenu2_2_1_3.php' ||
            $pagina == 'tMenu2_2_1_3_1.php' ||
            $pagina == 'tMenu2_2_1_3_2.php' ||
            $pagina == 'tMenu6_1_2.php') {
            //monta os dados há serem passados na query               
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'prioridade',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null//caso não tenha, colocar como null
            ];            
        }
        
        //envia os dados para elaboração da query
        $this->mConexao->CRUD($dados);

        //atualiza o validador da classe de acordo com o validador da conexão
        $this->setValidador($this->mConexao->getValidador());
        
    }
    
    public function getValidador(): string {
        return $this->validador;
    }

    public function getNomeCampo(): string {
        return $this->nomeCampo;
    }

    public function getValorCampo(): string {
        return $this->valorCampo;
    }

    public function getMConexao(): mConexao {
        return $this->mConexao;
    }

    public function getSNotificacao(): sNotificacao {
        return $this->sNotificacao;
    }

    public function setValidador(string $validador): void {
        $this->validador = $validador;
    }

    public function setNomeCampo(string $nomeCampo): void {
        $this->nomeCampo = $nomeCampo;
    }

    public function setValorCampo(string $valorCampo): void {
        $this->valorCampo = $valorCampo;
    }

    public function setMConexao(mConexao $mConexao): void {
        $this->mConexao = $mConexao;
    }

    public function setSNotificacao(sNotificacao $sNotificacao): void {
        $this->sNotificacao = $sNotificacao;
    }
}