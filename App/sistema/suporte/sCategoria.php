<?php

namespace App\sistema\suporte;

use App\modelo\{
    mConexao
};
use App\sistema\acesso\{
    sNotificacao
};

class sCategoria {

    private string $nomeCampo;
    private string $valorCampo;
    private string $validador;
    public mConexao $mConexao;
    public sNotificacao $sNotificacao;

    public function consultar($pagina) {
        $this->setMConexao(new mConexao());
        if ($pagina == 'tMenu3_1.php' ||
            $pagina == 'tMenu2_1.php-tabela' ||
            $pagina == 'tMenu2_2.php' ||
            $pagina == 'tMenu2_2_1.php' ||
            $pagina == 'tMenu3_2.php') {
            //monta os dados há serem passados na query               
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'categoria',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null//caso não tenha, colocar como null
            ];
            //envia os dados para elaboração da query
            $this->mConexao->CRUD($dados);

            //atualiza o validador da classe de acordo com o validador da conexão
            $this->setValidador($this->mConexao->getValidador());

            if ($this->getValidador()) {
                //se existir um registro no BD com mesma nomenclatura retorne mensagem de Atenção
                $this->setSNotificacao(new sNotificacao('A15'));
            }
        }
        
        if ($pagina == 'tMenu3_1.php-f1' ||
            $pagina == 'tMenu2_1.php' ||
            $pagina == 'tMenu3_2_1.php') {
            //monta os dados há serem passados na query               
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'categoria',
                'camposCondicionados' => '',
                'valoresCondicionados' => '',
                'camposOrdenados' => 'nomenclatura', //caso não tenha, colocar como null
                'ordem' => 'ASC'//caso não tenha, colocar como null
            ];
            //envia os dados para elaboração da query
            $this->mConexao->CRUD($dados);

            //atualiza o validador da classe de acordo com o validador da conexão
            $this->setValidador($this->mConexao->getValidador());
        }
    }

    public function inserir($pagina) {
        //cria conexão para inserir os dados na tabela
        $this->setMConexao(new mConexao());

        if ($pagina == 'tMenu3_1.php') {
            $dados = [
                'comando' => 'INSERT INTO',
                'tabela' => 'categoria',
                'camposInsercao' => [$this->getNomeCampo()],
                'valoresInsercao' => [$this->getValorCampo()]
            ];
        }
        $this->mConexao->CRUD($dados);
    }
    
    public function getNomeCampo(): string {
        return $this->nomeCampo;
    }

    public function getValorCampo(): string {
        return $this->valorCampo;
    }
    
    public function getValidador(): string {
        return $this->validador;
    }

    public function getMConexao(): mConexao {
        return $this->mConexao;
    }

    public function getSNotificacao(): sNotificacao {
        return $this->sNotificacao;
    }

    public function setNomeCampo(string $nomeCampo): void {
        $this->nomeCampo = $nomeCampo;
    }

    public function setValorCampo(string $valorCampo): void {
        $this->valorCampo = $valorCampo;
    }

    public function setValidador(string $validador): void {
        $this->validador = $validador;
    }

    public function setMConexao(mConexao $mConexao): void {
        $this->mConexao = $mConexao;
    }

    public function setSNotificacao(sNotificacao $sNotificacao): void {
        $this->sNotificacao = $sNotificacao;
    }
}
