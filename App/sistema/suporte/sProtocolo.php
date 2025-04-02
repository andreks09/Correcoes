<?php

namespace App\sistema\suporte;

use App\modelo\{
    mConexao
};
use App\sistema\acesso\{
    sNotificacao
};

class sProtocolo {
    private int $idProtocolo;
    private string $nomeCampo;
    private string $valorCampo;
    private string $validador;
    public mConexao $mConexao;
    public sNotificacao $sNotificacao;
    
    public function consultar($pagina) {
        //cria conexão com o bd
        $this->setMConexao(new mConexao());
        
        if ($pagina == 'tMenu2_2.php' ||
            $pagina == 'tMenu6_1.php') {
            //monta os dados há serem passados na query               
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'protocolo',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => 'idprotocolo', //caso não tenha, colocar como null
                'ordem' => 'ASC'//caso não tenha, colocar como null
            ];
        }
        
        if ($pagina == 'tMenu2_2_1.php' ||
            $pagina == 'tMenu2_2_2.php' ||
            $pagina == 'tMenu2_2_3.php') {
            //monta os dados há serem passados na query               
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'protocolo',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null//caso não tenha, colocar como null
            ];
        }
        
        if ($pagina == 'tMenu6_1.php-2') {
            //monta os dados há serem passados na query               
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'protocolo',
                'camposCondicionados' => $this->nomeCampo,
                'valoresCondicionados' => $this->valorCampo,
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null//caso não tenha, colocar como null
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

        if ($pagina == 'tMenu2_1.php') {
            $dados = [
                'comando' => 'INSERT INTO',
                'tabela' => 'protocolo',
                'camposInsercao' => [
                    'nomeDoRequerente',
                    'sobrenomeDoRequerente',
                    'telefoneDoRequerente',
                    'whatsAppDoRequerente',
                    'emailDoRequerente',
                    'usuario_idusuario',
                    'secretaria',
                    'departamento',
                    'coordenacao',
                    'setor'
                ],
                'valoresInsercao' => [
                    $dadosTratados['nomeDoRequerente'],
                    $dadosTratados['sobrenomeDoRequerente'],
                    $dadosTratados['telefoneDoRequerente'],
                    $dadosTratados['whatsAppDoRequerente'],
                    $dadosTratados['emailDoRequerente'],
                    $dadosTratados['usuario_idusuario'],
                    $dadosTratados['secretaria'],
                    $dadosTratados['departamento'],
                    $dadosTratados['coordenacao'],
                    $dadosTratados['setor']
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

        if ($pagina == 'tMenu2_2_1_3_2.php') {
            
            $dados = [
                'comando' => 'UPDATE',
                'tabela' => 'protocolo',
                'camposAtualizar' => $this->getNomeCampo(),
                'valoresAtualizar' => $this->getValorCampo(),
                'camposCondicionados' => 'idprotocolo',
                'valoresCondicionados' => $this->getIdProtocolo()
            ];
            
            $this->mConexao->CRUD($dados);
            //INSERT INTO table_name (column1, column2, column3, ...) VALUES (value1, value2, value3, ...);
            if ($this->mConexao->getValidador()) {
                $this->setValidador(true);
            }
        }
    }
    
    public function getIdProtocolo(): int {
        return $this->idProtocolo;
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

    public function setIdProtocolo(int $idProtocolo): void {
        $this->idProtocolo = $idProtocolo;
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
