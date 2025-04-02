<?php

namespace App\sistema\acesso;

use App\modelo\{
    mConexao
};
use App\sistema\acesso\{
    sNotificacao
};

class sTelefone {
    private string $nomeCampo;
    private string $valorCampo;
    private bool $validador;
    private int $idTelefone;
    private int $idLocal;
    private string $numero;
    private bool $whatsApp;
    private string $nomenclaturaLocal;
    public mConexao $mConexao;
    public sNotificacao $sNotificacao;

    public function __construct(int $idTelefone, int $idLocal, string $nomenclaturaLocal) {
        $this->idTelefone = $idTelefone;
        $this->idLocal = $idLocal;
        $this->nomenclaturaLocal = $nomenclaturaLocal;
        $this->validador = false;
    }

    public function consultar($pagina) {
        $this->setMConexao(new mConexao());
        if ($pagina == 'tMenu4_2_1.php') {
            if ($this->getNomenclaturaLocal() == 'usuario') {
                $dados = [
                    'comando' => 'SELECT',
                    'busca' => '*',
                    'tabelas' => 'telefone',
                    'camposCondicionados' => 'idtelefone',
                    'valoresCondicionados' => $this->getIdTelefone(),
                    'camposOrdenados' => null, //caso não tenha, colocar como null
                    'ordem' => null
                ];

                $this->mConexao->CRUD($dados);
                $this->setValidador($this->mConexao->getValidador());

                if ($this->getValidador()) {
                    foreach ($this->mConexao->getRetorno() as $linha) {
                        $this->setNumero($linha['numero']);
                        $this->setWhatsApp($linha['whatsApp']);
                    }
                }
            } else if ($this->getNomenclaturaLocal() == 'setor' ||
                    $this->getNomenclaturaLocal() == 'coordenacao' ||
                    $this->getNomenclaturaLocal() == 'departamento' ||
                    $this->getNomenclaturaLocal() == 'secretaria') {
                $dados = [
                    'comando' => 'SELECT',
                    'busca' => ['telefone.numero', 'telefone.whatsApp'],
                    'tabelas' => ['telefone', 'telefone_has_' . $this->getNomenclaturaLocal()],
                    'camposCondicionados' => '',
                    'valoresCondicionados' => ['telefone.idtelefone', 'telefone_has_' . $this->getNomenclaturaLocal() . '.telefone_idtelefone'],
                    'camposOrdenados' => null, //caso não tenha, colocar como null
                    'ordem' => null
                ];

                $this->mConexao->CRUD($dados);
                $this->setValidador($this->mConexao->getValidador());

                if ($this->getValidador()) {
                    foreach ($this->mConexao->getRetorno() as $linha) {
                        $this->setNumero($linha['numero']);
                        $this->setWhatsApp($linha['whatsApp']);
                    }
                }
            }
        }
                
        if ($pagina == 'tAcessar.php' ||
            $pagina == 'tAcessar.php-secretaria2' ||
            $pagina == 'tAcessar.php-departamento2' ||   
            $pagina == 'tAcessar.php-coordenacao2' ||   
            $pagina == 'tAcessar.php-setor2' ||  
            $pagina == 'tMenu1_1_1.php' ||     
            $pagina == 'tMenu1_2.php' || 
            $pagina == 'tMenu1_2.php-secretaria2' ||
            $pagina == 'tMenu1_2.php-departamento2' ||
            $pagina == 'tMenu1_2.php-coordenacao2' ||
            $pagina == 'tMenu1_2.php-setor2' || 
            $pagina == 'tMenu1_2_1.php' ||
            $pagina == 'tMenu1_3.php' ||
            $pagina == 'tMenu1_3.php-secretaria2' ||
            $pagina == 'tMenu1_3.php-departamento2' ||
            $pagina == 'tMenu1_3.php-coordenacao2' ||
            $pagina == 'tMenu1_3.php-setor2' ||        
            $pagina == 'tMenu2_2_1.php-2' ||
            $pagina == 'tMenu4_2_1.php-2' ||
            $pagina == 'tMenu4_2_1_1.php-2' ||
            $pagina == 'tMenu4_2_2_1.php-2' ||
            $pagina == 'tMenu4_2_3_1.php-2' ||
            $pagina == 'tMenu4_2_4_1.php-2'
            ) {
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'telefone',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null
            ];
            $this->mConexao->CRUD($dados);
            $this->setValidador($this->mConexao->getValidador());
        }
        
        if ($pagina == 'tAcessar.php-secretaria' ||
            $pagina == 'tMenu1_2.php-secretaria' ||
            $pagina == 'tMenu1_3.php-secretaria' ||
            $pagina == 'tMenu2_2_1.php' ||
            $pagina == 'tMenu4_2_1.php' ||
            $pagina == 'tMenu4_2_1_1.php') {
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'telefone_has_secretaria',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null
            ];
            $this->mConexao->CRUD($dados);
            $this->setValidador($this->mConexao->getValidador());
        }
        
        if ($pagina == 'tAcessar.php-departamento' ||
            $pagina == 'tMenu1_2.php-departamento' ||
            $pagina == 'tMenu1_3.php-departamento' ||
            $pagina == 'tMenu2_2_1.php-departamento' ||
            $pagina == 'tMenu4_2_2_1.php') {
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'telefone_has_departamento',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null
            ];
            $this->mConexao->CRUD($dados);
            $this->setValidador($this->mConexao->getValidador());
        }

        if ($pagina == 'tAcessar.php-coordenacao' ||
            $pagina == 'tMenu1_2.php-coordenacao' ||
            $pagina == 'tMenu1_3.php-coordenacao' ||
            $pagina == 'tMenu4_2_3_1.php') {
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'telefone_has_coordenacao',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null
            ];
            $this->mConexao->CRUD($dados);
            $this->setValidador($this->mConexao->getValidador());
        }

        if ($pagina == 'tAcessar.php-setor' ||
            $pagina == 'tMenu1_2.php-setor' ||
            $pagina == 'tMenu1_3.php-setor' ||
            $pagina == 'tMenu4_2_4_1.php') {
            $dados = [
                'comando' => 'SELECT',
                'busca' => '*',
                'tabelas' => 'telefone_has_setor',
                'camposCondicionados' => $this->getNomeCampo(),
                'valoresCondicionados' => $this->getValorCampo(),
                'camposOrdenados' => null, //caso não tenha, colocar como null
                'ordem' => null
            ];
            $this->mConexao->CRUD($dados);
            $this->setValidador($this->mConexao->getValidador());
        }

    }

    public function tratarTelefone($telefone) {
        if (!ctype_alnum($telefone)) {
            $telefoneTratado = str_replace(['(', ')', '-', '_', ' '], '', $telefone);
        } else {
            $telefoneTratado = str_replace(" ", "", $telefone);
            //se for número de telefone fixo
            if (strlen($telefoneTratado) == 10) {
                $mascara = "(##) ####-####";
            } else {
                //se for número de telefone celular
                $mascara = "(##) # ####-####";
            }
            for ($i = 0; $i < strlen($telefoneTratado); $i++) {
                $mascara[strpos($mascara, "#")] = $telefoneTratado[$i];
            }
            $telefoneTratado = $mascara;
        }
        return $telefoneTratado;
    }

    public function verificarTelefone($telefone) {
        if (strlen($telefone) < 10 ||
            strlen($telefone) > 11) {
            $this->setSNotificacao(new sNotificacao('A11'));
            $this->setValidador(false);
        } else {
            $this->setValidador(true);
        }
    }

    public function alterar($pagina) {
        //cria conexão para inserir os dados no BD
        $this->setMConexao(new mConexao());

        if ($pagina == 'tMenu1_1_1.php' ||
            $pagina == 'tMenu1_2_1.php' ||
            $pagina == 'tMenu4_2_1_1.php' ||
            $pagina == 'tMenu4_2_2_1.php' ||
            $pagina == 'tMenu4_2_3_1.php' ||
            $pagina == 'tMenu4_2_4_1.php') {
            $dados = [
                'comando' => 'UPDATE',
                'tabela' => 'telefone',
                'camposAtualizar' => $this->getNomeCampo(),
                'valoresAtualizar' => $this->getValorCampo(),
                'camposCondicionados' => 'idtelefone',
                'valoresCondicionados' => $this->getIdTelefone(),
            ];

            $this->mConexao->CRUD($dados);
            $this->setValidador($this->mConexao->getValidador());
            //UPDATE table_name SET column1=value, column2=value2 WHERE some_column=some_value 
            if ($this->mConexao->getValidador()) {
                $this->setSNotificacao(new sNotificacao('S1'));
            }
        }
    }

    public function inserir($pagina, $tratarDados) {
        //cria conexão para inserir os dados na tabela
        $this->setMConexao(new mConexao());
        if ($pagina == 'tMenu4_1.php' ||
            $pagina == 'tMenu1_1_1.php' ||
            $pagina == 'tMenu1_3_1.php' ||
            $pagina == 'tMenu4_2_1_1.php' ||
            $pagina == 'tMenu4_2_2_1.php' ||
            $pagina == 'tMenu4_2_3_1.php' ||
            $pagina == 'tMenu4_2_4_1.php') {
            //insere os dados do histórico no BD            
            $dados = [
                'comando' => 'INSERT INTO',
                'tabela' => 'telefone',
                'camposInsercao' => [
                    'whatsApp',
                    'numero'
                ],
                'valoresInsercao' => [
                    $tratarDados['whatsApp'],
                    $tratarDados['numero'],
                ]
            ];
        }

        if ($pagina == 'tMenu4_1-telefone_has_secretaria.php' ||
            $pagina == 'tMenu4_1-telefone_has_departamento.php' ||
            $pagina == 'tMenu4_2_1_1-telefone_has_secretaria.php' ||
            $pagina == 'tMenu4_2_2_1-telefone_has_departamento.php' ||
            $pagina == 'tMenu4_2_3_1-telefone_has_coordenacao.php' ||
            $pagina == 'tMenu4_2_4_1-telefone_has_setor.php' ||
            $pagina == 'tMenu4_1-telefone_has_coordenacao.php' ||
            $pagina == 'tMenu4_1-telefone_has_setor.php') {
            //insere os dados do histórico no BD            
            $dados = [
                'comando' => 'INSERT INTO',
                'tabela' => 'telefone_has_' . $this->getNomeCampo(),
                'camposInsercao' => [
                    'telefone_idtelefone',
                    $this->getNomeCampo() . '_id' . $this->getNomeCampo()
                ],
                'valoresInsercao' => [
                    $tratarDados['idtelefone'],
                    $tratarDados['id' . $this->getNomeCampo()],
                ]
            ];
        }

        $this->mConexao->CRUD($dados);

        if ($this->mConexao->getValidador()) {
            $this->setSNotificacao(new sNotificacao('S1'));
        }
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

    public function getIdTelefone(): int {
        return $this->idTelefone;
    }

    public function getIdLocal(): int {
        return $this->idLocal;
    }

    public function getNumero(): string {
        return $this->numero;
    }

    public function getWhatsApp(): bool {
        return $this->whatsApp;
    }

    public function getNomenclaturaLocal(): string {
        return $this->nomenclaturaLocal;
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

    public function setValidador(bool $validador): void {
        $this->validador = $validador;
    }

    public function setIdTelefone(int $idTelefone): void {
        $this->idTelefone = $idTelefone;
    }

    public function setIdLocal(int $idLocal): void {
        $this->idLocal = $idLocal;
    }

    public function setNumero(string $numero): void {
        $this->numero = $numero;
    }

    public function setWhatsApp(bool $whatsApp): void {
        $this->whatsApp = $whatsApp;
    }

    public function setNomenclaturaLocal(string $nomenclaturaLocal): void {
        $this->nomenclaturaLocal = $nomenclaturaLocal;
    }

    public function setMConexao(mConexao $mConexao): void {
        $this->mConexao = $mConexao;
    }

    public function setSNotificacao(sNotificacao $sNotificacao): void {
        $this->sNotificacao = $sNotificacao;
    }

}
