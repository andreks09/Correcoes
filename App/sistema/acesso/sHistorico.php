<?php

namespace App\sistema\acesso;

use App\modelo\{
    mConexao
};
use App\sistema\acesso\{
    sNotificacao,
    sTratamentoDados
};

class sHistorico {

    public mConexao $mConexao;
    public sNotificacao $sNotificacao;
    public sTratamentoDados $sTratamentoDados;

    public function consultar($pagina) {
        //cria conexão para inserir os dados na tabela
        $this->setMConexao(new mConexao());
        if ($pagina == 'tMenu6_2.php') {
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'historico',
                'camposCondicionados' => '',
                'valoresCondicionados' => '',
                'camposOrdenados' => 'idhistorico', //caso não tenha, colocar como null
                'ordem' => 'DESC' //ASC ou DESC
            ];
            $this->mConexao->CRUD($dados);
        }
    }

    public function inserir($pagina, $tratarDados) {
        //cria conexão para inserir os dados na tabela
        $this->setMConexao(new mConexao());
        if ($pagina == 'tMenu1_1_1.php' ||
            $pagina == 'tSolicitarAcesso.php' ||
            $pagina == 'tAcessar.php' ||
            $pagina == 'tMenu5_1.php' ||
            $pagina == 'tMenu5_2_1.php' ||
            $pagina == 'tMenu4_1.php' ||
            $pagina == 'tMenu2_2_3.php' ||
            $pagina == 'tMenu4_2_1_1.php') {
            
            //insere os dados do histórico no BD     
            //obtèm dados do endereço de ip
            $tratarDados['ip'] = str_replace(['-'], '.', gethostbyaddr($_SERVER['REMOTE_ADDR']));
            //obtém dados do navegador
            $sTratarNavegador = new sTratamentoDados($_SERVER['HTTP_USER_AGENT']);            
            $tratarDados['navegador'] = $sTratarNavegador->tratarNavegador();
            
            $dados = [
                'comando' => 'INSERT INTO',
                'tabela' => 'historico',
                'camposInsercao' => [
                    'pagina',
                    'acao',
                    'campo',
                    'valorAtual',
                    'valorAnterior',
                    'ip',
                    'navegador',
                    'sistemaOperacional',
                    'nomeDoDispositivo',
                    'idusuario'
                ],
                'valoresInsercao' => [
                    $tratarDados['pagina'],
                    $tratarDados['acao'],
                    $tratarDados['campo'],
                    $tratarDados['valorCampoAtual'],
                    $tratarDados['valorCampoAnterior'],
                    $tratarDados['ip'],
                    $tratarDados['navegador'],
                    $tratarDados['sistemaOperacional'],
                    $tratarDados['nomeDoDispositivo'],
                    $tratarDados['idUsuario']
                ]
            ];
            $this->mConexao->CRUD($dados);
        }
    }
    
    public function getMConexao(): mConexao {
        return $this->mConexao;
    }

    public function getSNotificacao(): sNotificacao {
        return $this->sNotificacao;
    }

    public function setMConexao(mConexao $mConexao): void {
        $this->mConexao = $mConexao;
    }

    public function setSNotificacao(sNotificacao $sNotificacao): void {
        $this->sNotificacao = $sNotificacao;
    }
}

?>
        