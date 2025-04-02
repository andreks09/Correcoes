<?php

namespace App\sistema\suporte;

use App\modelo\{
    mConexao
};
use App\sistema\acesso\{
    sNotificacao
};

class sEtapa {
    private int $idEtapa;
    private mixed $nomeCampo;
    private mixed $valorCampo;
    private string $validador;
    public mConexao $mConexao;
    public sNotificacao $sNotificacao;
    
    public function consultar($pagina) {
        //cria conexão com bd
        $this->setMConexao(new mConexao());
        
        if ($pagina == 'tMenu2_1.php' ||
            $pagina == 'tMenu2_2.php' ||
            $pagina == 'tMenu2_2_1.php' ||
            $pagina == 'tMenu2_2_1_3.php' ||
            $pagina == 'tMenu2_2_1_3_1.php' ||
            $pagina == 'tMenu2_2_1_3_2.php' ||
            $pagina == 'tMenu2_2_3.php' ||
            $pagina == 'tMenu6_1_2.php') {
            //monta os dados há serem passados na query               
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'etapa',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => 'numero', //caso não tenha, colocar como null
                'ordem' => 'ASC'//caso não tenha, colocar como null
            ];            
        }
        
        if ($pagina == 'tMenu6_2.php') {
            //monta os dados há serem passados na query               
            $dados = [
                'comando' => 'SELECT',
                'busca' => 'COUNT(*)',
                'tabelas' => 'etapa',
                'camposCondicionados' => '',
                'valoresCondicionados' => '',
                'camposOrdenados' => 'idetapa', //caso não tenha, colocar como null
                'ordem' => 'ASC'//caso não tenha, colocar como null
            ];            
        }
        
        //envia os dados para elaboração da query
        $this->mConexao->CRUD($dados);

        //atualiza o validador da classe de acordo com o validador da conexão
        $this->setValidador($this->mConexao->getValidador());
    }

    public function inserir($pagina, $dadosTratados) {
        //cria conexão para inserir os dados no BD
        $this->setMConexao(new mConexao());

        if ($pagina == 'tMenu2_1_1.php') {
            $dados = [
                'comando' => 'INSERT INTO',
                'tabela' => 'etapa',
                'camposInsercao' => [
                    'numero',
                    'acessoRemoto',
                    'descricao',
                    'equipamento_idequipamento',
                    'protocolo_idprotocolo',
                    'local_idlocal',
                    'prioridade_idprioridade'
                ],
                'valoresInsercao' => [
                    $dadosTratados['numero'],
                    $dadosTratados['acessoRemoto'],
                    $dadosTratados['descricao'],
                    $dadosTratados['equipamento_idequipamento'],
                    $dadosTratados['protocolo_idprotocolo'],
                    $dadosTratados['local_idlocal'],
                    $dadosTratados['prioridade_idprioridade'],
                ]
            ]; 
            
            $this->mConexao->CRUD($dados);
            //INSERT INTO table_name (column1, column2, column3, ...) VALUES (value1, value2, value3, ...);
            if ($this->mConexao->getValidador()) {
                $this->setValidador(true);
                $this->setSNotificacao(new sNotificacao('S4'));
            }
        }
        if ($pagina == 'tMenu2_2_3.php' ||
            $pagina == 'tMenu2_2_1_3_1.php') {
            $dados = [
                'comando' => 'INSERT INTO',
                'tabela' => 'etapa',
                'camposInsercao' => [
                    'numero',
                    'acessoRemoto',
                    'descricao',
                    'equipamento_idequipamento',
                    'protocolo_idprotocolo',
                    'local_idlocal',
                    'prioridade_idprioridade',
                    'usuario_idusuario'
                ],
                'valoresInsercao' => [
                    $dadosTratados['numero'],
                    $dadosTratados['acessoRemoto'],
                    $dadosTratados['descricao'],
                    $dadosTratados['equipamento_idequipamento'],
                    $dadosTratados['protocolo_idprotocolo'],
                    $dadosTratados['local_idlocal'],
                    $dadosTratados['prioridade_idprioridade'],
                    $dadosTratados['usuario_idusuario']
                ]
            ]; 
            
            $this->mConexao->CRUD($dados);
            //INSERT INTO table_name (column1, column2, column3, ...) VALUES (value1, value2, value3, ...);
            if ($this->mConexao->getValidador()) {
                $this->setValidador(true);
                $this->setSNotificacao(new sNotificacao('S4'));
            }
        }
    }
    
    public function alterar($pagina) {
        //cria conexão para inserir os dados no BD
        $this->setMConexao(new mConexao());

        if ($pagina == 'tMenu2_2_3.php' ||
            $pagina == 'tMenu2_2_1_3.php' ||
            $pagina == 'tMenu2_2_1_3_1.php' ||
            $pagina == 'tMenu2_2_1_3_2.php') {
            
            $dados = [
                'comando' => 'UPDATE',
                'tabela' => 'etapa',
                'camposAtualizar' => $this->getNomeCampo(),
                'valoresAtualizar' => $this->getValorCampo(),
                'camposCondicionados' => 'idetapa',
                'valoresCondicionados' => $this->getIdEtapa()
            ];
            
            $this->mConexao->CRUD($dados);
            //INSERT INTO table_name (column1, column2, column3, ...) VALUES (value1, value2, value3, ...);
            if ($this->mConexao->getValidador()) {
                $this->setValidador(true);
                $this->setSNotificacao(new sNotificacao('S4'));
            }
        }
    }
    
    public function getIdEtapa(): int {
        return $this->idEtapa;
    }

    public function getNomeCampo(): mixed {
        return $this->nomeCampo;
    }

    public function getValorCampo(): mixed {
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

    public function setIdEtapa(int $idEtapa): void {
        $this->idEtapa = $idEtapa;
    }

    public function setNomeCampo(mixed $nomeCampo): void {
        $this->nomeCampo = $nomeCampo;
    }

    public function setValorCampo(mixed $valorCampo): void {
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