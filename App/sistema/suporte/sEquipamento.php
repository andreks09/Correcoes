<?php
namespace App\sistema\suporte;

use App\modelo\{mConexao};
use App\sistema\acesso\{
    sNotificacao
};

class sEquipamento {
    private int $idEquipamento;
    private string $patrimonio;
    private string $categoria;
    private string $modelo;
    private string $etiqueta;
    private string $serie;
    private string $tensao;
    private string $corrente;
    private string $sistemaOperacional;
    private string $nomeCampo;
    private string $valorCampo;
    private string $validador;
    public mConexao $mConexao;
    public sNotificacao $sNotificacao;    
    
    public function consultar($pagina) {
        //cria conexão com o bd
        $this->setMConexao(new mConexao());
        
        if ($pagina == 'tMenu2_1.php' ||
            $pagina == 'tMenu3_2.php' ||
            $pagina == 'tMenu6_2.php') {
            //monta os dados há serem passados na query               
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'equipamento',
                'camposCondicionados' => '',
                'valoresCondicionados' => '',
                'camposOrdenados' => 'patrimonio', //caso não tenha, colocar como null
                'ordem' => 'ASC'//caso não tenha, colocar como null
            ];
        }
        
        if ($pagina == 'tMenu2_1_1.php' ||
            $pagina == 'tMenu2_2.php' ||
            $pagina == 'tMenu2_2_1.php' ||
            $pagina == 'tMenu3_1.php' ||
            $pagina == 'tMenu3_2_1.php') {
            //monta os dados há serem passados na query               
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'equipamento',
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
    
    public function inserir($pagina, $dadosTratados) {
        //cria conexão para inserir os dados no BD
        $this->setMConexao(new mConexao());

        if ($pagina == 'tMenu3_1.php-f1') {
            $dados = [
                'comando' => 'INSERT INTO',
                'tabela' => 'equipamento',
                'camposInsercao' => [
                    'patrimonio',
                    'categoria_idcategoria',
                    'tensao_idtensao',
                    'corrente_idcorrente',
                    'ambiente_idambiente',
                    'sistemaOperacional_idsistemaOperacional',
                    'numeroDeSerie',
                    'etiquetaDeServico',
                    'modelo_idmodelo'
                ],
                'valoresInsercao' => [
                    $dadosTratados['patrimonio'],
                    $dadosTratados['categoria_idcategoria'],
                    $dadosTratados['tensao_idtensao'],
                    $dadosTratados['corrente_idcorrente'],
                    $dadosTratados['ambiente_idambiente'],
                    $dadosTratados['sistemaOperacional_idsistemaOperacional'],
                    $dadosTratados['numeroDeSerie'],                    
                    $dadosTratados['etiquetaDeServico'],
                    $dadosTratados['modelo_idmodelo']
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

        if ($pagina == '') {
            $dados = [
                'comando' => 'UPDATE',
                'tabela' => 'equipamento',
                'camposAtualizar' => $this->getNomeCampo(),
                'valoresAtualizar' => $this->getValorCampo(),
                'camposCondicionados' => 'idequipamento',
                'valoresCondicionados' => $this->getIdEquipamento()
            ];
            $this->mConexao->CRUD($dados);
            $this->setValidador($this->mConexao->getValidador());
        }
    }
    
    public function getIdEquipamento(): int {
        return $this->idEquipamento;
    }

    public function getPatrimonio(): string {
        return $this->patrimonio;
    }

    public function getCategoria(): string {
        return $this->categoria;
    }

    public function getModelo(): string {
        return $this->modelo;
    }

    public function getEtiqueta(): string {
        return $this->etiqueta;
    }

    public function getSerie(): string {
        return $this->serie;
    }

    public function getTensao(): string {
        return $this->tensao;
    }

    public function getCorrente(): string {
        return $this->corrente;
    }

    public function getSistemaOperacional(): string {
        return $this->sistemaOperacional;
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

    public function setIdEquipamento(int $idEquipamento): void {
        $this->idEquipamento = $idEquipamento;
    }

    public function setPatrimonio(string $patrimonio): void {
        $this->patrimonio = $patrimonio;
    }

    public function setCategoria(string $categoria): void {
        $this->categoria = $categoria;
    }

    public function setModelo(string $modelo): void {
        $this->modelo = $modelo;
    }

    public function setEtiqueta(string $etiqueta): void {
        $this->etiqueta = $etiqueta;
    }

    public function setSerie(string $serie): void {
        $this->serie = $serie;
    }

    public function setTensao(string $tensao): void {
        $this->tensao = $tensao;
    }

    public function setCorrente(string $corrente): void {
        $this->corrente = $corrente;
    }

    public function setSistemaOperacional(string $sistemaOperacional): void {
        $this->sistemaOperacional = $sistemaOperacional;
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